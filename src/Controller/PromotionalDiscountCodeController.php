<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

class PromotionalDiscountCodeController extends AppController{
    
    public function initialize()
    {
            parent::initialize();
            $this->loadComponent("PHMFunction");
            
    }
    
    
      /**
     * Display Discount Lists
     * @Method DiscountLists
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
	
    public function index(){
        //$this->set("GYMFunction",$this->GYMFunction);
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $data = $this->PromotionalDiscountCode->find("all")->where(['is_deleted'=>'0'])->toArray();
        $this->set("data",$data);
    }
    
    
     /**
     * Add Discount Lists
     * @Method addDiscountCode
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
        
    public function addDiscountCode(){
        
        $session = $this->request->session()->read("User");
        $this->set("edit",false);
        $this->set("title",__("Add Promotional Code"));
        
        $memberships = $this->PromotionalDiscountCode->PheramorSubscription->find("list",["keyField"=>"id","valueField"=>"subscription_title"])->where(['is_deleted'=>0]);
        $this->set("memberships",$memberships);
        
         if($this->request->is("post")){
            $row = $this->PromotionalDiscountCode->newEntity();
            $this->request->data['valid_from'] = date('Y-m-d',strtotime($this->request->data["valid_from"]));
            $this->request->data['valid_to'] = date('Y-m-d',strtotime($this->request->data["valid_to"]));   
            $this->request->data["created_by"] = $session["id"];
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
            $this->request->data["is_deleted"] = '0';
            $this->request->data['subscription_id'] = implode(',',$this->request->data['subscription_id']);
            $row = $this->PromotionalDiscountCode->patchEntity($row,$this->request->data);
            //$this->GYMFunction->pre($row);
            if($this->PromotionalDiscountCode->save($row)){				
                $this->Flash->success(__("Success! Discount Code Successfully Saved."));
                return $this->redirect(["action"=>"index"]);			
            }else{
                $this->Flash->error(__("Error! Discount Code Not Saved.Please Try Again."));
            }
        }		
    }
        
     /**
     * Update Discount Lists
     * @Method editDiscountCode
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    
    
    public function editDiscountCode($pid){	

        $session = $this->request->session()->read("User");
        $this->set("edit",true);	
        $this->set("title",__("Edit Promotional Code"));
        $row = $this->PromotionalDiscountCode->get($pid)->toArray();
         $this->set("data",$row);
        
        $memberships = $this->PromotionalDiscountCode->PheramorSubscription->find("list",["keyField"=>"id","valueField"=>"subscription_title"])->where(['is_deleted'=>0]);
        $this->set("memberships",$memberships);
        
        $this->render("addDiscountCode");
        
        if($this->request->is("post")){
            $row = $this->PromotionalDiscountCode->get($pid);
            $this->request->data['subscription_id'] = implode(',',$this->request->data['subscription_id']);
            $this->request->data['valid_from'] = date('Y-m-d',strtotime($this->request->data["valid_from"]));
            $this->request->data['valid_to'] = date('Y-m-d',strtotime($this->request->data["valid_to"]));   
            $row = $this->PromotionalDiscountCode->patchEntity($row,$this->request->data);
            if($this->PromotionalDiscountCode->save($row)){
                $this->Flash->success(__("Success! Record Successfully Updated."));
                return $this->redirect(["action"=>"index"]);
            }else{
                $this->Flash->error(__("Error! Record Not Updated.Please Try Again."));
            }
        }
        ///$this->render("addLocation");
    }
	
    /**
     * Delete Discount Lists
     * @Method deleteDiscountCode
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    
    
    
    public function deleteDiscountCode($did){

        $session = $this->request->session()->read("User");
        $row = $this->PromotionalDiscountCode->get($did);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PromotionalDiscountCode->patchEntity($row,$this->request->data);
        if($this->PromotionalDiscountCode->save($row)){
            $this->Flash->success(__("Success! Record Deleted Successfully Updated."));
            return $this->redirect(["action"=>"index"]); 
        } 
        
    }
    
    public function sendNotification()
    {
       $this->autoRender = false;
       $users= $this->request->data['my_multi_select1'];
       if(!empty($users))
       {    $conn = ConnectionManager::get('default');
            $p_id=$this->request->data['PCODE'];
            $code_info_table = TableRegistry::get("PromotionalDiscountCode");
            $code= $code_info_table->get($p_id);
            $codeval=$code->code;
            $use_tbl = TableRegistry::get("PheramorNotification");
            $data = $use_tbl->find("all")->where(['id' => 5])->first();
            $templateContent=$data->notification_message;
            $rendered = str_replace('{PCODE}', $codeval, $templateContent);
            //return $rendered;
            foreach($users as $udata){
              //Send Push Notification here  
             // echo $udata;
                $message = array('message' => $rendered, 'type' => 'Promotional Discount Code', 'user_id' => $udata);
                $message_send=$rendered;
                $push_deatils = $this->PHMFunction->user_push_details($udata);
               // print_r($rendered);
              // print_r($push_deatils); die;
                if ($push_deatils !== false) {
                   if(!empty($push_deatils['device_address'])){
                    if ($push_deatils['device_type'] == 'an') {
                        $this->PHMFunction->android_notification(array($push_deatils['device_address']), $message);
                    } else {
                        $this->PHMFunction->ios_notification(array($push_deatils['device_address']), $message);
                    }
                    // Save Notification History
                   
                    }
                }
               $save_noti_query = "INSERT INTO push_notification_history (user_id, device_address, device_type, message, notification_type,datetime) VALUES ('" . $udata . "','" . $push_deatils['device_address'] . "','" . $push_deatils['device_type'] . "','" . $message_send . "','" . $message['type'] . "','" . date('Y-m-d H:i:s') . "')";
               $conn->execute($save_noti_query);

                // End Here 
          } 
       }
        $this->Flash->success(__("Success! Notification has been sent."));
        return $this->redirect(["action"=>"index"]); 
       
    }

    public function isAuthorized($user){
        return parent::isAuthorizedCustom($user);
    }
}
