<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorMoviesController extends AppController {

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
    
    private function addMovie() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Movie"));
       
        $category = $this->PheramorMovies->newEntity();
        if ($this->request->is("post")) {
            
           // print_r( $this->request->data); die;
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            $category_array = $this->request->data['parent'];
            $category_array_string = '';
            foreach ($category_array as $catname) {
                 $category_array_string .= $catname;
                 $category_array_string .= ',';
            }
            $category_array_string = substr(trim($category_array_string), 0, -1);
            $this->request->data['parent'] = $category_array_string;
            
            $category = $this->PheramorMovies->patchEntity($category, $this->request->data());

            if ($this->PheramorMovies->save($category)) {
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
            $this->editMovies($id);
             $parent = $this->PheramorMovies->find("list", ["keyField" => "id", "valueField" => "title"])->where(["is_deleted" => '0',"parent" => '0','id !='=>$id])->hydrate(false)->toArray();
        }else{
           $this->addMovie(); 
           $parent = $this->PheramorMovies->find("list", ["keyField" => "id", "valueField" => "title"])->where(["is_deleted" => '0',"parent" => '0'])->hydrate(false)->toArray();
        }
        if ($session["role_id"] == 1) {
           // $category_data = $this->PheramorMovies->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
       // $this->set("category_data", $category_data);
        /// Category
        $parent[0]='Parent';
      // print_r($parent); die;
        $this->set("parent", $parent);
       
     }

     /**
     * Edit Subscription Category
     * @Method editCategory
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
     
     public function editMovie($id)
     {
        // $this->index($id);
         return $this->redirect(["action" => "index",$id]);
        
         
     }
    private function editMovies($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Movie"));
        $cat_data = $this->PheramorMovies->get($id)->toArray();
        $this->set("cat_data", $cat_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorMovies->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            
            $category_array = $this->request->data['parent'];
            $category_array_string = '';
            foreach ($category_array as $catname) {
                 $category_array_string .= $catname;
                 $category_array_string .= ',';
            }
            $category_array_string = substr(trim($category_array_string), 0, -1);
            $this->request->data['parent'] = $category_array_string;
            
            $category= $this->PheramorMovies->patchEntity($row, $this->request->data);

            if ($this->PheramorMovies->save($category)) {
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
     * Delete Movies Lists
     * @Method deleteMovie
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */

    public function deleteMovie($id) {
        $row = $this->PheramorMovies->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorMovies->patchEntity($row,$this->request->data);
        if ($this->PheramorMovies->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
