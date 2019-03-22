<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorInterestAreaController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add INTERESTS for Profile
     * @Method Add INTERESTS
     * @Date 11 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function addInterest() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Interest"));
       
        $category = $this->PheramorInterestArea->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorInterestArea->patchEntity($category, $this->request->data());

            if ($this->PheramorInterestArea->save($category)) {
                $this->Flash->success(__("Success! Record Saved Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
               
                $this->Flash->error('Error');
               
                return;
               
            }
        }
       //  $this->render("add");
    }
    
    /**
     * All Tag Lists
     * @Method TagsList
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        
        if(!empty($id))
        {
            $this->editInterest($id);
        }else{
           $this->addInterest(); 
        }
        if ($session["role_id"] == 1) {
            $interest_data = $this->PheramorInterestArea->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("interest_data", $interest_data);
       
     }

     /**
     * Edit Member Interest
     * @Method editInterest
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    public function editInterests($id)
     {
        // $this->index($id);
         return $this->redirect(["action" => "index",$id]);
        
         
     }
    public function editInterest($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Interest"));
        $interests_data = $this->PheramorInterestArea->get($id)->toArray();
        $this->set("interests_data", $interests_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorInterestArea->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorInterestArea->patchEntity($row, $this->request->data);

            if ($this->PheramorInterestArea->save($tag)) {
                // echo "<pre>";print_r($membership); die;
                $this->Flash->success(__("Success! Record Updated Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
                $this->Flash->error(__("Error! There was an error while updating,Please try again later."));
            }
        }
      //  $this->render("add");
    }

    /**
     * Delete Interest Lists
     * @Method deleteInterest
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */

    public function deleteInterest($id) {
        $row = $this->PheramorInterestArea->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorInterestArea->patchEntity($row,$this->request->data);
        if ($this->PheramorInterestArea->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
