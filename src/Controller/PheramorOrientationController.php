<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorOrientationController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Sexual Orientation for Profile
     * @Method AddOrientation
     * @Date 24 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function addOrientation() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Sexual Orientation"));
       
        $category = $this->PheramorOrientation->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorOrientation->patchEntity($category, $this->request->data());

            if ($this->PheramorOrientation->save($category)) {
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
     * All Sexual Orientation Lists
     * @Method SexualOrientationList
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($id))
        {
            $this->editOrientation($id);
        }else{
           $this->addOrientation(); 
        }
        if ($session["role_id"] == 1) {
            $orientation_data = $this->PheramorOrientation->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("orientation_data", $orientation_data);
       
     }

     /**
     * Edit Member Sexual Orientation
     * @Method editOrientation
     * @Date 24 Oct 2017
     * @Author RNF Technologies  
     */
     
    public function editOrientations($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editOrientation($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Sexual Orientation"));
        $races_data = $this->PheramorOrientation->get($id)->toArray();
        $this->set("races_data", $races_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorOrientation->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorOrientation->patchEntity($row, $this->request->data);

            if ($this->PheramorOrientation->save($tag)) {
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
     * Delete Sexual Orientation Lists
     * @Method deleteOrientation
     * @Date 24 Oct 2017
     * @Author RNF Technologies  
     */

    public function deleteOrientation($id) {
        $row = $this->PheramorOrientation->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorOrientation->patchEntity($row,$this->request->data);
        if ($this->PheramorOrientation->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
