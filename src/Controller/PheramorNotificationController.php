<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

// use GoogleCharts;

Class PheramorNotificationController extends AppController {

    public function initialize() {
        parent::initialize();
        
        $session = $this->request->session()->read("User");
        $this->set("session", $session);
          $this->loadComponent("PHMFunction");
    }
  
      /**
     * Display Notification Lists
     * @Method NotificationLists
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    
     public function index() {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $data = $this->PheramorNotification->find("all")->toArray();
        $this->set("data", $data);
        
       }
    
   
    
    /**
     * Delete Notification Lists
     * @Method deleteNotification
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteNotification($id=null) {
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "memberList"]);
        }
        $session = $this->request->session()->read("User");
        $row = $this->PheramorNotification->get($id);
       
        if ($this->PheramorNotification->delete($row)) {
            $this->Flash->success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    
     /**
     * Add Notification Lists
     * @Method addNotification
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    
    
   public function addNotification() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $this->set("title", __("Add Message Notification"));
        $event = $this->PheramorNotification->newEntity();
        if ($this->request->is("post")) {
            $this->request->data['updated_date'] = date("Y-m-d H:i:s");
            $this->request->data['created_date'] = date("Y-m-d H:i:s");
            $event = $this->PheramorNotification->patchEntity($event, $this->request->data());
            if ($this->PheramorNotification->save($event)) {
                $this->Flash->success(__("Success! Record Saved Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
                 $this->Flash->error('Error');
               
                return;
            }
           
        }
    }
    
    
    
     /**
     * Edit Notification Lists
     * @Method editNotification
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    
   public function editNotification($id=null) {
        $conn = ConnectionManager::get('default');
        $this->set("edit", true);
        $this->set("title", __("Edit Message Notification"));
        $this->set("eid", $id);
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "index"]);
        }
        $session = $this->request->session()->read("User");
        $notify_data = $this->PheramorNotification->get($id)->toArray();
        $this->set("notify_data", $notify_data);
        $this->render("addNotification");

        if ($this->request->is("post")) {
            $row = $this->PheramorNotification->get($id);
              $this->request->data['updated_date'] = date("Y-m-d H:i:s");
             $update = $this->PheramorNotification->patchEntity($row, $this->request->data);
            
            if ($saveResult = $this->PheramorNotification->save($update)) {
                 $this->Flash->success(__("Success! Record Saved Successfully."));
                 return $this->redirect(["action" => "index"]);
            } else {
                 $this->Flash->error(__("Error! There was an error while updating,Please try again later."));
            }
        }
    }

    /**
     * Add Custom Notification Lists
     * @Method addCustomNotification
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    
    
    public function addCustomNotification() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $this->set("title", __("Send Custom Notification"));
        $use_tbl = TableRegistry::get("PheramorUser");
        $data = $use_tbl->find("all")->contain(["PheramorUserProfile"])->where(['is_deleted' => 0, 'activated' => 1, 'role_name' => 'member'])->toArray();
        $this->set("data", $data);
        
        $orientaton_tbl = TableRegistry::get("PheramorOrientation");
        $orienation = $orientaton_tbl->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("orienation", $orienation);
        
        if ($this->request->is("post")) {
            $users = $this->request->data['my_multi_select1'];
           
            if (!empty($users)) {
                $rendered = $this->request->data['notification_message'];
                $message_send=$rendered;
                foreach ($users as $udata) {
                    $message = array('message' => $rendered, 'type' => 'Pheramor', 'user_id' => $udata);
                    $push_deatils = $this->PHMFunction->user_push_details($udata);
                    if ($push_deatils !== false) {
                        if (!empty($push_deatils['device_address'])) {
                            if ($push_deatils['device_type'] == 'an') {
                                $this->PHMFunction->android_notification(array($push_deatils['device_address']), $message);
                            } else {
                                $this->PHMFunction->ios_notification(array($push_deatils['device_address']), $message);
                            }
                            // Save Notification History
                        }
                    }
                    if(empty($push_deatils['device_type'])){$push_deatils['device_type']='none';}
                    $clean_string = $conn->quote($message_send);
                    $save_noti_query = "INSERT INTO push_notification_history (user_id, device_address, device_type, message, notification_type,datetime) VALUES ('" . $udata . "','" . $push_deatils['device_address'] . "','" . $push_deatils['device_type'] . "',$clean_string,'" . $message['type'] . "','" . date('Y-m-d H:i:s') . "')";
                    $conn->execute($save_noti_query);
                }
            }
            
            $this->Flash->success(__("Success! Notification Message Sent Successfully."));
        }
    }

    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
