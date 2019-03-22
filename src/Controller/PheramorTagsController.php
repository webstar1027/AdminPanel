<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorTagsController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Tags for Profile
     * @Method Add Tags
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
    
    public function addTag() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Tag"));
       
        $category = $this->PheramorTags->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorTags->patchEntity($category, $this->request->data());

            if ($this->PheramorTags->save($category)) {
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
            $this->editTag($id);
        }else{
           $this->addTag(); 
        }
        if ($session["role_id"] == 1) {
            $tag_data = $this->PheramorTags->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("tag_data", $tag_data);
       
     }

     /**
     * Edit Member Tags
     * @Method editTags
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
     
    public function editTags($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editTag($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Tag"));
        $tags_data = $this->PheramorTags->get($id)->toArray();
        $this->set("tags_data", $tags_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorTags->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorTags->patchEntity($row, $this->request->data);

            if ($this->PheramorTags->save($tag)) {
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
     * Delete Tag Lists
     * @Method deleteTag
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */

    public function deleteTag($id) {
        $row = $this->PheramorTags->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorTags->patchEntity($row,$this->request->data);
        if ($this->PheramorTags->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
