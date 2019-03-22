<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\ORM\TableRegistry;
use Cake\Network\Response;

class GymRenderMenuCell extends Cell
{
    public function adminMenu()
	{
		$session = $this->request->session();
		$role_id = $session->read("User.role_id");
		$access_tbl = TableRegistry::get("PheramorAccessright");
		$menus = $access_tbl->find("all",array('conditions' => array('FIND_IN_SET(\''. $role_id .'\',assigned_roles)')))->hydrate(false)->toArray();		
		$this->set("menus",$menus);
	}
	
	/*public function memberMenu()
	{
		$access_tbl = TableRegistry::get("GymAccessright");
		$menus = $access_tbl->find("all")->where(["member"=>1])->hydrate(false)->toArray();		
		$this->set("menus",$menus);
	}
	
	public function staffMenu()
	{
		$access_tbl = TableRegistry::get("GymAccessright");
		$menus = $access_tbl->find("all")->where(["staff_member"=>1])->hydrate(false)->toArray();		
		$this->set("menus",$menus);
	}
	
	public function accMenu()
	{
		$access_tbl = TableRegistry::get("GymAccessright");
		$menus = $access_tbl->find("all")->where(["accountant"=>1])->hydrate(false)->toArray();		
		$this->set("menus",$menus);
	}*/
}