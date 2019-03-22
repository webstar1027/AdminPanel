<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorMusicController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Music Lists
     * @Method addMusic
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
    
    private function addMusic() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Music"));
       
        $category = $this-> PheramorMusic->newEntity();
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
            
            
            $category = $this-> PheramorMusic->patchEntity($category, $this->request->data());

            if ($this-> PheramorMusic->save($category)) {
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
     * Music Lists
     * @Method MusicLists
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        
        /** Edit Subscription **/
        
        if(!empty($id))
        {
            $this->editMusics($id);
             $parent = $this->PheramorMusic->find("list", ["keyField" => "id", "valueField" => "title"])->where(["is_deleted" => '0',"parent" => '0','id !='=>$id])->hydrate(false)->toArray();
        }else{
           $this->addMusic(); 
           $parent = $this->PheramorMusic->find("list", ["keyField" => "id", "valueField" => "title"])->where(["is_deleted" => '0',"parent" => '0'])->hydrate(false)->toArray();
        }
        if ($session["role_id"] == 1) {
           // $category_data = $this->PheramorMusic->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
        $parent[0]='Parent';
       // $this->set("category_data", $category_data);
        /// Category
        
       
        $this->set("parent", $parent);
       
     }

     /**
     * Edit Music Lists
     * @Method editMusic
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
     
     public function editMusic($id)
     {
        // $this->index($id);
         return $this->redirect(["action" => "index",$id]);
        
         
     }
    private function editMusics($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Music"));
        $cat_data = $this->PheramorMusic->get($id)->toArray();
        $this->set("cat_data", $cat_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorMusic->get($id);
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
            
            $category= $this->PheramorMusic->patchEntity($row, $this->request->data);

            if ($this->PheramorMusic->save($category)) {
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
     * Delete Music Lists
     * @Method deleteMusic
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */

    public function deleteMusic($id) {
        $row = $this->PheramorMusic->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorMusic->patchEntity($row,$this->request->data);
        if ($this->PheramorMusic->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
