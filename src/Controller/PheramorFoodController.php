<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorFoodController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Food Lists
     * @Method addFood
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    
    private function addFood() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Food"));
       
        $category = $this-> PheramorFood->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $this->request->data["is_deleted"] = '0';
            $this->request->data["created_by"] = $session['id'];
           
           // Category Music mutiple selected value
            
            $category_array = $this->request->data['parent'];
            $category_array_string = '';
            foreach ($category_array as $catname) {
                 $category_array_string .= $catname;
                 $category_array_string .= ',';
            }
            $category_array_string = substr(trim($category_array_string), 0, -1);
            $this->request->data['parent'] = $category_array_string;
            
            
            $category = $this-> PheramorFood->patchEntity($category, $this->request->data());

            if ($this-> PheramorFood->save($category)) {
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
     * Food Lists
     * @Method FoodLists
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        
        /** Edit Subscription **/
        if(!empty($id))
        {
            $this->editFoods($id);
             $parent = $this->PheramorFood->find("list", ["keyField" => "id", "valueField" => "title"])->where(["is_deleted" => '0',"parent" => '0','id !='=>$id])->hydrate(false)->toArray();
        }else{
           $this->addFood(); 
           $parent = $this->PheramorFood->find("list", ["keyField" => "id", "valueField" => "title"])->where(["is_deleted" => '0',"parent" => '0'])->hydrate(false)->toArray();
        }
        if ($session["role_id"] == 1) {
            $category_data = $this->PheramorFood->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("category_data", $category_data);
        /// Category
        $parent[0]='Parent';
       
        $this->set("parent", $parent);
       
     }

     /**
     * Edit Food Lists
     * @Method editFood
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
     
     public function editFood($id)
     {
        // $this->index($id);
         return $this->redirect(["action" => "index",$id]);
        
         
     }
    private function editFoods($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Food"));
        $cat_data = $this->PheramorFood->get($id)->toArray();
        $this->set("cat_data", $cat_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorFood->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
             // Category Music mutiple selected value
            
            $category_array = $this->request->data['parent'];
            $category_array_string = '';
            foreach ($category_array as $catname) {
                 $category_array_string .= $catname;
                 $category_array_string .= ',';
            }
            $category_array_string = substr(trim($category_array_string), 0, -1);
            $this->request->data['parent'] = $category_array_string;
            
            $category= $this->PheramorFood->patchEntity($row, $this->request->data);

            if ($this->PheramorFood->save($category)) {
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
     * Delete Food Lists
     * @Method deleteFood
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */

    public function deleteFood($id) {
        $row = $this->PheramorFood->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorFood->patchEntity($row,$this->request->data);
        if ($this->PheramorFood->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
