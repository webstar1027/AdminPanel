<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

// use GoogleCharts;

Class PheramorCafeController extends AppController {

    public function initialize() {
        parent::initialize();
        
        $session = $this->request->session()->read("User");
        $this->set("session", $session);
        $this->loadComponent("PHMFunction");
        $this->loadComponent("Stripe");
    }
  
      /**
     * Display cafe Lists
     * @Method cafeLists
     * @Date 22 Sep 2017
     * @Author RNF Technologies  
     */
    
     public function index() {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $data = $this->PheramorCafe->find("all")->toArray();
        $this->set("data", $data);
       // echo $this->Stripe->getCents(12); die;
        
       
     /*
       $customerId = 'cus_BTvWx5DOE2o1AG';
        $cardData = array('number' => '4111111111111111', 'exp_month' => '10',
            'exp_year' => 2022, 'cvc' => 123);
       $response = $this->Stripe->createCard($customerId, $cardData);
         echo "<pre>";print_r($response); die;
       
         $stripe_token =$this->Stripe->createToken(array(
        "card" => array(
          "number" => "4242424242424242",
          "exp_month" => 01,
          "exp_year" => 20,
          "cvc" => "314"
        )
      )); // does not exist afaik*/
       
       //  print_r($stripe_token); die;
       
         
        
       }
    
   
    
    /**
     * Delete Cafe Lists
     * @Method deleteCafe
     * @Date 22 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteCafe($id=null) {
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "memberList"]);
        }
        $session = $this->request->session()->read("User");
        $row = $this->PheramorCafe->get($id);
       
        if ($this->PheramorCafe->delete($row)) {
            $this->Flash->success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    
     /**
     * Add Cafe Lists
     * @Method addCafe
     * @Date 22 Sep 2017
     * @Author RNF Technologies  
     */
    
    
   public function addCafe() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $this->set("title", __("Add Cafe"));
        $event = $this->PheramorCafe->newEntity();
        if ($this->request->is("post")) {
            $this->request->data['updated_date'] = date("Y-m-d H:i:s");
            $this->request->data['created_date'] = date("Y-m-d H:i:s");
            $address=$this->request->data['address']." ".$this->request->data['city']." ".$this->request->data['state']." ".$this->request->data['zipcode'];
            $val = $this->PHMFunction->getLnt($address);
            $this->request->data['latitude']= $val['lat'];
            $this->request->data['longitude']= $val['lng'];
           // $this->request->data['start_date'] = date("Y-m-d", strtotime($this->request->data['start_date']));
           // $this->request->data['end_date'] = date("Y-m-d", strtotime($this->request->data['end_date']));
            $image = $this->PHMFunction->uploadImage($this->request->data['image']);
            
            
            
            $this->request->data['image'] = (!empty($image)) ? $image : $this->request->webroot."upload/profile-placeholder.png";
            $event = $this->PheramorCafe->patchEntity($event, $this->request->data());
            if ($this->PheramorCafe->save($event)) {
                
                $eid = $event->id; 
                ///Event Gallery
                if (isset($this->request->data['myfile']) && !empty($this->request->data['myfile'])) {
                    $no_files = count($_FILES["myfile"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        $image = $this->PHMFunction->mutliuploadImage($this->request->data['myfile'],$i);
                        if(!empty($image))
                        $insert=$conn->execute("insert into pheramor_cafe_gallery set cafe_id='".$eid."',image='".$image."'");
                        
                       }
                }
                
             /// Send Push Notification here 
                $ctitle = $this->request->data['title'];
                $use_tbl = TableRegistry::get("PheramorNotification");
                $data = $use_tbl->find("all")->where(['id' => 4])->first();
                $templateContent = $data->notification_message;
                if($data->status==1){
                $rendered = str_replace('{Cafe_Name}', $ctitle, $templateContent);

                $stmt = $conn->execute("SELECT id from pheramor_user WHERE role_name='member' and  is_deleted='0'");
                $users = $stmt->fetchAll('assoc');
                foreach ($users as $udata) {
                    $message = array('message' => $rendered, 'type' => 'Cafe', 'user_id' => $udata['id']);
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
                
                $this->Flash->success(__("Success! Record Saved Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
                 $this->Flash->error('Error');
               
                return;
            }
           
        }
    }
    
    
    
     /**
     * Edit Cafe Lists
     * @Method editCafe
     * @Date 22 Sep 2017
     * @Author RNF Technologies  
     */
    
   public function editCafe($id=null) {
        $conn = ConnectionManager::get('default');
        $this->set("edit", true);
        $this->set("title", __("Edit Cafe"));
        $this->set("eid", $id);
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "index"]);
        }
        $session = $this->request->session()->read("User");
        $cafe_data = $this->PheramorCafe->get($id)->toArray();
        $this->set("cafe", $cafe_data);
        
       $gallery_query = "SELECT * from pheramor_cafe_gallery where cafe_id='$id'";
        $gallery_query = $conn->execute($gallery_query);
        $gallery_data = $gallery_query->fetchAll('assoc');
        $this->set("gallery_data", $gallery_data);
        $this->render("addCafe");

        if ($this->request->is("post")) {
            $row = $this->PheramorCafe->get($id);
            $address=$this->request->data['address']." ".$this->request->data['city']." ".$this->request->data['state']." ".$this->request->data['zipcode'];
            $val = $this->PHMFunction->getLnt($address);
           // $val = $this->PHMFunction->getLnt($this->request->data['zipcode']);
            $this->request->data['latitude']= $val['lat'];
            $this->request->data['longitude']= $val['lng'];
           // $this->request->data['start_date'] = date("Y-m-d", strtotime($this->request->data['start_date']));
           // $this->request->data['end_date'] = date("Y-m-d", strtotime($this->request->data['end_date']));
            $image = $this->PHMFunction->uploadImage($this->request->data['image']);
            if ($image != "") {
                $this->request->data['image'] = $image;
            } else {
                unset($this->request->data['image']);
            }
            //$this->request->data['image'] = (!empty($image)) ? $image : $this->request->webroot."upload/profile-placeholder.png";
            $update = $this->PheramorCafe->patchEntity($row, $this->request->data);
            
            if ($saveResult = $this->PheramorCafe->save($update)) {
                
                
                
                if (isset($this->request->data['myfile']) && !empty($this->request->data['myfile'])) {
                    $no_files = count($_FILES["myfile"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        $image = $this->PHMFunction->mutliuploadImage($this->request->data['myfile'],$i);
                        if(!empty($image))
                        $insert=$conn->execute("insert into pheramor_cafe_gallery set cafe_id='".$id."',image='".$image."'");
                        
                       }
                }
                
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
