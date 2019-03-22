<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorHobbiesController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Hobbies for Profile
     * @Method Add Hobbies
     * @Date 27 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function addHobbie() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Hobbies"));
       
        $category = $this->PheramorHobbies->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorHobbies->patchEntity($category, $this->request->data());

            if ($this->PheramorHobbies->save($category)) {
                $this->Flash->success(__("Success! Record Saved Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
               
                $this->Flash->error('Error');
               
                return;
               
            }
        }
        // $this->render("add");
    }
    
    /**
     * All Hobbies Lists
     * @Method HobbiesList
     * @Date 27 Oct 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($id))
        {
            $this->editHobbie($id);
        }else{
           $this->addHobbie(); 
        }
        if ($session["role_id"] == 1) {
          //  $hobbies_data = $this->PheramorHobbies->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        //$this->set("hobbies_data", $hobbies_data);
       
     }

     /**
     * Edit Member Hobbies
     * @Method editHobbies
     * @Date 27 Oct 2017
     * @Author RNF Technologies  
     */
     
    public function editHobbies($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editHobbie($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Hobbies"));
        $races_data = $this->PheramorHobbies->get($id)->toArray();
        $this->set("races_data", $races_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorHobbies->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorHobbies->patchEntity($row, $this->request->data);

            if ($this->PheramorHobbies->save($tag)) {
                // echo "<pre>";print_r($membership); die;
                $this->Flash->success(__("Success! Record Updated Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
                $this->Flash->error(__("Error! There was an error while updating,Please try again later."));
            }
        }
        //$this->render("add");
    }

    /**
     * Delete Hobbies Lists
     * @Method deleteHobbies
     * @Date 27 Oct 2017
     * @Author RNF Technologies  
     */

    public function deleteHobbie($id) {
        $row = $this->PheramorHobbies->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorHobbies->patchEntity($row,$this->request->data);
        if ($this->PheramorHobbies->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
