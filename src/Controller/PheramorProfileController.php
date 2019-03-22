<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
class PheramorProfileController extends AppController {
 
    public function initialize() {
        parent::initialize();
        /* $this->loadComponent('Csrf'); */
        $this->loadComponent("GYMFunction");
        $session = $this->request->session()->read("User");
        $this->set("session", $session);
    }
    
     /**
     * View and update admin profile
     * @Method View Admin Profile
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */
    public function viewProfile() {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $user_data = $this->PheramorProfile->PheramorUser->find()->contain(["PheramorUserProfile"])->where(["PheramorUser.id" => $session['original_id']]);
        $this->set("data", $user_data->first());
       // print_r($user_data->first()); die;
        /**/
        if ($this->request->is("post")) {
           
            if (isset($this->request->data["save_change"])) {
                $post = $this->request->data;
                $patch = $this->PheramorProfile->PheramorUser->get($session['original_id']);
                $saved_pass = $this->PheramorProfile->PheramorUser->get($this->Auth->user('id'))->password;
                $curr_pass = (new DefaultPasswordHasher)->check($post["current_password"], $saved_pass);
                if ($curr_pass) {
                    if ($post["password"] != $post["confirm_password"]) {
                        $this->Flash->error(__("Error! New password and confirm password does not matched.Please try again."));
                    } else {

                        $update["password"] = $this->request->data["confirm_password"];
                       // $update["app_password"] = md5($this->request->data["confirm_password"]);
                        $update_row = $this->PheramorProfile->PheramorUser->patchEntity($patch, $update);
                        if ($this->PheramorProfile->PheramorUser->save($update_row)) {
                            $this->Flash->success(__("Success! Password Updated Successfully"));
                        }
                    }
                } else {
                    $this->Flash->error(__("Error! Current password is wrong.Please try again."));
                }
            }

            if (isset($this->request->data["email"])) {
                $age = (date('Y') - date('Y',strtotime($this->request->data['dob'])));
                
                
                if(!empty($this->request->data['image'])){
                $image = $this->GYMFunction->uploadImage($this->request->data['image']);
                $this->request->data['image'] = (!empty($image)) ? $image : $this->request->webroot."upload/profile-placeholder.png";
                $sessions = $this->request->session();
                $sessions->write("User.profile_img", $this->request->data['image']);
                }
                $post = $this->request->data;
               // print_r( $post); die;
                 $curr_email = $this->Auth->User('email');
                 $patch = $this->PheramorProfile->PheramorUser->get($session['original_id']);
                if ($curr_email != $post["email"]) {
                    $emails = $this->PheramorProfile->PheramorUser->find("all")->where(["email" => $post["email"]]);
                    $count = $emails->count();
                } else {
                    $count = 0;
                }
                if ($count == 0) {
                     
                   // $this->GYMFunction->pre($post);
                    //print_r($user_data); die;
                     $patchemail = $this->PheramorProfile->PheramorUser->get($session['original_id']);
                     $update_email['email']= $post['email'];
                     $update_rows = $this->PheramorProfile->PheramorUser->patchEntity($patchemail, $update_email);
                     $this->PheramorProfile->PheramorUser->save($update_rows);
                             
                    $datapatch = $this->PheramorProfile->PheramorUserProfile->get($post['profile_id']);
                    $savepost['dob'] = date('Y-m-d',strtotime($post['dob']));
                    $savepost['gender'] = $post['gender'];
                    $savepost['address'] = $post['address'];
                    $savepost['city'] = $post['city'];
                    $savepost['phone'] =$post['phone'];
                    $savepost['image'] = $post['image'];
                    $savepost['updated_date'] = date('Y-m-d H:i:s');
                    
                    $update_row = $this->PheramorProfile->PheramorUserProfile->patchEntity($datapatch, $savepost);
                    if ($this->PheramorProfile->PheramorUserProfile->save($update_row)) {
                        $this->Flash->success(__("Success! Password Updated Successfully"));
                        return $this->redirect(["action" => "viewProfile"]);
                    }
                } else {
                    $this->Flash->error(__("Error! Email-id already exists.Please try again."));
                }
            }

           
        }
        
    }
}
