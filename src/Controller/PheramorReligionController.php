<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorReligionController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Religion for Profile
     * @Method Add Religion
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function addReligion() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Religion"));
       
        $category = $this->PheramorReligion->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorReligion->patchEntity($category, $this->request->data());

            if ($this->PheramorReligion->save($category)) {
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
     * All Religion Lists
     * @Method ReligionList
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($id))
        {
            $this->editReligion($id);
        }else{
           $this->addReligion(); 
        }
        if ($session["role_id"] == 1) {
            $race_data = $this->PheramorReligion->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("race_data", $race_data);
       
     }

     /**
     * Edit Member Race
     * @Method editRace
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
     
    public function editReligions($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editReligion($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Religion"));
        $races_data = $this->PheramorReligion->get($id)->toArray();
        $this->set("races_data", $races_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorReligion->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorReligion->patchEntity($row, $this->request->data);

            if ($this->PheramorReligion->save($tag)) {
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
     * Delete Religion Lists
     * @Method deleteReligion
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */

    public function deleteReligion($id) {
        $row = $this->PheramorReligion->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorReligion->patchEntity($row,$this->request->data);
        if ($this->PheramorReligion->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
