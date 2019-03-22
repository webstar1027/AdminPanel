<?php
namespace App\Controller;
use Cake\App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use  Cake\Utility\Xml; 

class PheramorGeneralSettingController extends AppController{

	public function initialize()
	{
		parent::initialize();
		$this->loadcomponent("PHMFunction");
		$this->loadcomponent("GYMFunction");
	}
        
        /**
       * Save General Setting
       * @Method saveSetting
       * @Date 28 Aug 2017
       * @Author RNF Technologies  
       */
	public function saveSetting()
	{
               $conn = ConnectionManager::get('default');
               $pheramor_subscription_tbl = TableRegistry::get("PheramorSubscription");
               $membership = $pheramor_subscription_tbl
                        ->find("list", ["keyField" => "id", "valueField" => "subscription_title"])
                        ->contain(['PheramorSubscriptionCategory'])->where([
                            'PheramorSubscription.subscription_status' => 1,
                            "PheramorSubscriptionCategory.category_type"=>'subscription',
                            "PheramorSubscription.is_deleted" => "0"
                        ])->hydrate(false)->toArray();
               
               $credit_query = "SELECT * from pheramor_credit_setting where id='1'";
               $credit_query = $conn->execute($credit_query);
               $credit_data = $credit_query->fetch('assoc');
             //  print_r($credit_data); die;
               $this->set("credit_data", $credit_data);
               $this->set("membership", $membership);
               $query = $this->PheramorGeneralSetting->find("all");
	       $count = $query->count();
		if($count == 1)
		{			
			$results = $this->PheramorGeneralSetting->find()->all();
			$row = $results->first();
			$this->set("data",$row);
			$this->set("edit",true);			
		}
		else{
			$this->set("edit",false);
			$this->set("data","");	
		}	
		
		$xml = Xml::build('../vendor/xml/countrylist.xml');			
		$currency_xml = Xml::toArray(Xml::build('../vendor/xml/currency-symbols.xml'));;			
		$this->set('xml',$xml);			
		$this->set('currency_xml',$currency_xml['currency-symbol']['entry']);	

		if($this->request->is("post"))
		{
			$session = $this->request->session();
                        $session->write("User.left_header",$this->request->data["left_header"]);
			$session->write("User.footer",$this->request->data["footer"]);
                       
			if($count == 0)
			{
				$settings = $this->PheramorGeneralSetting->newEntity();
				$logo_name =  $this->GYMFunction->uploadImage($this->request->data['company_logo']);
                               
				//$cover_name =  $this->GYMFunction->uploadImage($this->request->data['cover_image']);
				$this->request->data['company_logo'] = $logo_name;
				//$this->request->data['cover_image'] = $cover_name;
				$session->write("User.logo",$this->request->data["company_logo"]);
				$settings = $this->PheramorGeneralSetting->patchEntity($settings,$this->request->data);
				if($this->PheramorGeneralSetting->save($settings))
				{
					$this->Flash->success(__("Success! Settings Saved Successfully."));
				}
			}
			else{
				$results = $this->PheramorGeneralSetting->find()->all();
				$update_row = $results->first();				
				$logo_name =  $this->GYMFunction->uploadImage($this->request->data['company_logo']);
                               // echo $logo_name; die;
				//$cover_name =  $this->GYMFunction->uploadImage($this->request->data['cover_image']);
				if($this->request->data['company_logo']['name'] != "")
				{
					$this->request->data['company_logo'] = $logo_name;
					$session->write("User.logo",$this->request->data["company_logo"]);
				}
				//if($this->request->data['cover_image']['name'] != ""){
					//$this->request->data['cover_image'] = $cover_name;
			//	}
                                
                               
                                //print_r($this->request->data); die;
				$update = $this->PheramorGeneralSetting->patchEntity($update_row,$this->request->data);
				if($this->PheramorGeneralSetting->save($update))
				{
					$this->Flash->success(__("Success! Settings Saved Successfully."));
					return $this->redirect($this->here);
				}
				
			}
		}
	}
	
	public function isAuthorized($user){
            return parent::isAuthorizedCustom($user);
	}
}