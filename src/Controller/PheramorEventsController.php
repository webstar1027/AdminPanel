<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

// use GoogleCharts;

Class PheramorEventsController extends AppController {

    public function initialize() {
        parent::initialize();
        
        $session = $this->request->session()->read("User");
        $this->set("session", $session);
         $this->loadComponent("PHMFunction");
        date_default_timezone_set("America/Chicago");
    }
  
      /**
     * Display Event Lists
     * @Method eventLists
     * @Date 21 Sep 2017
     * @Author RNF Technologies  
     */
    
     public function index() {
         
        
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $data = $this->PheramorEvents->find("all")->toArray();
        $this->set("data", $data);
        
       }
    
   
    
    /**
     * Delete Event Lists
     * @Method deleteEvent
     * @Date 21 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteEvent($id=null) {
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "memberList"]);
        }
        $session = $this->request->session()->read("User");
        $row = $this->PheramorEvents->get($id);
       
        if ($this->PheramorEvents->delete($row)) {
            $this->Flash->success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    
     /**
     * Add Event Lists
     * @Method addEvent
     * @Date 21 Sep 2017
     * @Author RNF Technologies  
     */
    
    
   public function addEvent() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $this->set("title", __("Add Event"));
        $event = $this->PheramorEvents->newEntity();
        if ($this->request->is("post")) {
            $this->request->data['event_type'] = 'web';
            $this->request->data['created_date'] = date("Y-m-d H:i:s");
            //$val = $this->PHMFunction->getLnt($this->request->data['zipcode']);
            $address=$this->request->data['address']." ".$this->request->data['city']." ".$this->request->data['state']." ".$this->request->data['zipcode'];
            $val = $this->PHMFunction->getLnt($address);
            $this->request->data['latitude']= $val['lat'];
            $this->request->data['longitude']= $val['lng'];
            $start_date=strtotime($this->request->data['start_date']);
            $end_date=strtotime($this->request->data['end_date']);
            date_default_timezone_set('UTC');
            $this->request->data['start_date'] = date("Y-m-d H:i:s", $start_date);
            $this->request->data['end_date'] = date("Y-m-d H:i:s", $end_date);
           // $this->request->data['start_date'] = date("Y-m-d H:i:s", strtotime($this->request->data['start_date']));
           // $this->request->data['end_date'] = date("Y-m-d H:i:s", strtotime($this->request->data['end_date']));
            $image = $this->PHMFunction->uploadImage($this->request->data['image']);
            $this->request->data['image'] = (!empty($image)) ? $image : $this->request->webroot."upload/profile-placeholder.png";
            $event = $this->PheramorEvents->patchEntity($event, $this->request->data());
            if ($this->PheramorEvents->save($event)) {
                $eid = $event->id; 
                ///Event Gallery
                if (isset($this->request->data['myfile']) && !empty($this->request->data['myfile'])) {
                    $no_files = count($_FILES["myfile"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        $image = $this->PHMFunction->mutliuploadImage($this->request->data['myfile'],$i);
                        if(!empty($image))
                         $insert=$conn->execute("insert into pheramor_event_gallery set event_id='".$eid."',image='".$image."'");
                        
                       }
                }
                ///Event Gallery End here 
                
                 /// Send Push Notification here 
                $ename= $this->request->data['title'];
                $edate= date("Y-m-d", strtotime($this->request->data['start_date']));
                $etime= date("H:i A", strtotime($this->request->data['start_date']));
                $use_tbl = TableRegistry::get("PheramorNotification");
                $data = $use_tbl->find("all")->where(['id' => 3])->first();
               
                $templateContent = $data->notification_message;
                $find=array('{Event_name}','{Date}','{Time}');
                $replace=array($ename,$edate,$etime);
                $rendered = str_replace($find, $replace, $templateContent);
                 if($data->status==1){
                $stmt = $conn->execute("SELECT id from pheramor_user WHERE role_name='member' and  is_deleted='0'");
                $users = $stmt->fetchAll('assoc');
                foreach ($users as $udata) {
                    $message = array('message' => $rendered, 'type' => 'Event', 'user_id' => $udata['id']);
                   // print_r($message); die;
                    //  $message_send=$rendered;
                    $push_deatils = $this->PHMFunction->user_push_details($udata['id']);
                    if ($push_deatils !== false && !empty($push_deatils['device_address'])) {
                        if ($push_deatils['device_type'] == 'an') {
                            $this->PHMFunction->android_notification(array($push_deatils['device_address']), $message);
                        } else {
                            $this->PHMFunction->ios_notification(array($push_deatils['device_address']), $message);
                        }
                    }
                    if(empty($push_deatils['device_type'])){ $push_deatils['device_type']='none';}
                    $save_noti_query = "INSERT INTO push_notification_history (user_id, device_address, device_type, message, notification_type,datetime) VALUES ('" . $message['user_id'] . "','" . $push_deatils['device_address'] . "','" . $push_deatils['device_type'] . "','" . $message['message'] . "','" . $message['type'] . "','" . date('Y-m-d H:i:s') . "')";
                    $conn->execute($save_noti_query);
                }
                 }

                // End Here  

                // End Here 
               
               
               
                $this->Flash->success(__("Success! Record Saved Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
                 $this->Flash->error('Error');
               
                return;
            }
           
        }
    }
    
    
    
     /**
     * Edit Event Lists
     * @Method editEvent
     * @Date 21 Sep 2017
     * @Author RNF Technologies  
     */
    
   public function editEvent($id=null) {
      /* date_default_timezone_set("America/Chicago");
      echo $the_date = date("Y-m-d H:i:s");
      $timeList=strtotime($the_date);
date_default_timezone_set('UTC');
echo(date("Y-m-d H:i:s",$timeList) . "<br />");


die;*/
      
       
        $conn = ConnectionManager::get('default');
        $this->set("edit", true);
        $this->set("title", __("Edit Event"));
        $this->set("eid", $id);
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "index"]);
        }
        $session = $this->request->session()->read("User");
        $event_data = $this->PheramorEvents->get($id)->toArray();
        $this->set("event", $event_data);
       
        $gallery_query = "SELECT * from pheramor_event_gallery where event_id='$id'";
        $gallery_query = $conn->execute($gallery_query);
        $gallery_data = $gallery_query->fetchAll('assoc');
        $this->set("gallery_data", $gallery_data);
        
        $this->render("addEvent");

        if ($this->request->is("post")) {
            
           
            
            $row = $this->PheramorEvents->get($id);
           // $val = $this->PHMFunction->getLnt($this->request->data['zipcode']);
            $address=$this->request->data['address']." ".$this->request->data['city']." ".$this->request->data['state']." ".$this->request->data['zipcode'];
            $val = $this->PHMFunction->getLnt($address);
            $this->request->data['latitude']= $val['lat'];
            $this->request->data['longitude']= $val['lng'];
            $start_date=strtotime($this->request->data['start_date']);
            $end_date=strtotime($this->request->data['end_date']);
            date_default_timezone_set('UTC');
            $this->request->data['start_date'] = date("Y-m-d H:i:s", $start_date);
            $this->request->data['end_date'] = date("Y-m-d H:i:s", $end_date);
            $image = $this->PHMFunction->uploadImage($this->request->data['image']);
            if ($image != "") {
                $this->request->data['image'] = $image;
            } else {
                unset($this->request->data['image']);
            }
            //$this->request->data['image'] = (!empty($image)) ? $image : $this->request->webroot."upload/profile-placeholder.png";
            $update = $this->PheramorEvents->patchEntity($row, $this->request->data);
            
            if ($saveResult = $this->PheramorEvents->save($update)) {
                
                ///Event Gallery
                if (isset($this->request->data['myfile']) && !empty($this->request->data['myfile'])) {
                    $no_files = count($_FILES["myfile"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        $image = $this->PHMFunction->mutliuploadImage($this->request->data['myfile'],$i);
                        if(!empty($image))
                        $insert=$conn->execute("insert into pheramor_event_gallery set event_id='".$id."',image='".$image."'");
                        
                       }
                }
                ///Event Gallery End here 
                
                 $this->Flash->success(__("Success! Record Saved Successfully."));
                 return $this->redirect(["action" => "index"]);
            } else {
                 $this->Flash->error(__("Error! There was an error while updating,Please try again later."));
            }
        }
    }

   

     
    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
