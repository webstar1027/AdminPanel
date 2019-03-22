<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorSubscriptionCategoryController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Subscription Category
     * @Method Add Category
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
    
    public function addCategory() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Category"));
       
        $category = $this->PheramorSubscriptionCategory->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorSubscriptionCategory->patchEntity($category, $this->request->data());

            if ($this->PheramorSubscriptionCategory->save($category)) {
                $this->Flash->success(__("Success! Record Saved Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
               
                $this->Flash->error('Error');
               
                return;
               
            }
        }
         //$this->render("add");
    }
    
    /**
     * Subscription Category Lists
     * @Method subscriptionList
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        
        /** Edit Subscription **/
        if(!empty($id))
        {
            $this->editCategory($id);
        }else{
           $this->addCategory(); 
        }
        if ($session["role_id"] == 1) {
            $category_data = $this->PheramorSubscriptionCategory->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("category_data", $category_data);
       
     }

     /**
     * Edit Subscription Category
     * @Method editCategory
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
     
     public function editCat($id)
     {
        // $this->index($id);
         return $this->redirect(["action" => "index",$id]);
        
         
     }
    public function editCategory($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Category"));
        $cat_data = $this->PheramorSubscriptionCategory->get($id)->toArray();
        $this->set("cat_data", $cat_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorSubscriptionCategory->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $category= $this->PheramorSubscriptionCategory->patchEntity($row, $this->request->data);

            if ($this->PheramorSubscriptionCategory->save($category)) {
                // echo "<pre>";print_r($membership); die;
                $this->Flash->success(__("Success! Record Updated Successfully"));
                return $this->redirect(["action" => "index"]);
            } else {
                $this->Flash->error(__("Error! There was an error while updating,Please try again later."));
            }
        }
       // $this->render("add");
    }

    /**
     * Delete Category Lists
     * @Method deleteCategory
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */

    public function deleteCategory($id) {
        $row = $this->PheramorSubscriptionCategory->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorSubscriptionCategory->patchEntity($row,$this->request->data);
        if ($this->PheramorSubscriptionCategory->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
