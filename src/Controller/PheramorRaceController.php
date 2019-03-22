<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorRaceController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Race for Profile
     * @Method Add Race
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function addRace() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Race"));
       
        $category = $this->PheramorRace->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorRace->patchEntity($category, $this->request->data());

            if ($this->PheramorRace->save($category)) {
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
     * All Race Lists
     * @Method RaceList
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($id))
        {
            $this->editRace($id);
        }else{
           $this->addRace(); 
        }
        if ($session["role_id"] == 1) {
            $race_data = $this->PheramorRace->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("race_data", $race_data);
       
     }

     /**
     * Edit Member Race
     * @Method editRace
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
     
    public function editRaces($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editRace($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Race"));
        $races_data = $this->PheramorRace->get($id)->toArray();
        $this->set("races_data", $races_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorRace->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorRace->patchEntity($row, $this->request->data);

            if ($this->PheramorRace->save($tag)) {
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
     * Delete Race Lists
     * @Method deleteRace
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */

    public function deleteRace($id) {
        $row = $this->PheramorRace->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorRace->patchEntity($row,$this->request->data);
        if ($this->PheramorRace->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
