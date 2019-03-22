<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorBodyTypeController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Body Type for Profile
     * @Method Add Type
     * @Date 23 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function addType() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Body Type"));
       
        $category = $this->PheramorBodyType->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorBodyType->patchEntity($category, $this->request->data());

            if ($this->PheramorBodyType->save($category)) {
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
     * All Body Type Lists
     * @Method BodyList
     * @Date 23 Oct 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($id))
        {
            $this->editType($id);
        }else{
           $this->addType(); 
        }
        if ($session["role_id"] == 1) {
            $race_data = $this->PheramorBodyType->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("body_data", $race_data);
       
     }

     /**
     * Edit Body Type
     * @Method editType
     * @Date 23 Oct 2017
     * @Author RNF Technologies  
     */
     
    public function editTypes($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editType($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Body Type"));
        $races_data = $this->PheramorBodyType->get($id)->toArray();
        $this->set("races_data", $races_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorBodyType->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorBodyType->patchEntity($row, $this->request->data);

            if ($this->PheramorBodyType->save($tag)) {
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
     * Delete Body Type Lists
     * @Method deleteType
     * @Date 23 Oct 2017
     * @Author RNF Technologies  
     */

    public function deleteType($id) {
        $row = $this->PheramorBodyType->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorBodyType->patchEntity($row,$this->request->data);
        if ($this->PheramorBodyType->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
