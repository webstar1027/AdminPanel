<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class GymAccessrightController extends AppController
{
	public function accessRight()
    {
    	$roles = $this->GymAccessright->GymAccessRoles->find("all")->hydrate(false)->toArray();
    	$this->set("roles",$roles);
       //print_r($roles); die;
    	$rights = $this->GymAccessright->find("all")->hydrate(false)->toArray();
    	foreach($rights as $right)
		{
			$data['name'] = $right["name"];
			$data['assigned_roles'] = $right["assigned_roles"];
			$menus[$right['module']][$right['id']] = $data;
		}

		$this->set("menus",$menus);
		if($this->request->is("post"))
		{
			$access_right = array();
			$request = $this->request->data;
			foreach($request as $key=>$value){
				$afterExplodeValue = explode('_', $key);
				$access_right[$afterExplodeValue[1]][] = $afterExplodeValue[0];
			}

			$conn = ConnectionManager::get('default');
			foreach($access_right as $menu=>$right)
			{
				$sql = "UPDATE gym_accessright SET assigned_roles = '".implode(',' ,$right)."' WHERE id= '".$menu."' ";	
				$stmt = $conn->execute($sql);	
				if($stmt)
				{$success = true;}else{$success = false;}
			}
			if($success)
			{
				$this->Flash->success(__("Success! Settings Saved successfully."));
				return $this->redirect(["action"=>"accessRight"]);
			}	
		}	
	}
	
        /*
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = [];
		$staff__acc_actions = [];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;			
			CASE "staff_member":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}*/
        
        public function isAuthorized($user){
            return parent::isAuthorizedCustom($user);
	}
}

