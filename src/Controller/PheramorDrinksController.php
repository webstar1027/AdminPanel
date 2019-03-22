<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorDrinksController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Drink for Profile
     * @Method Add Drink
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function addDrink() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Drink"));
       
        $category = $this->PheramorDrinks->newEntity();
        if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $category = $this->PheramorDrinks->patchEntity($category, $this->request->data());

            if ($this->PheramorDrinks->save($category)) {
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
     * All Drink Lists
     * @Method DrinkList
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($id))
        {
            $this->editDrink($id);
        }else{
           $this->addDrink(); 
        }
        if ($session["role_id"] == 1) {
            $drink_data = $this->PheramorDrinks->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("drink_data", $drink_data);
       
     }

     /**
     * Edit Member Drink
     * @Method editDrink
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
     
    public function editDrinks($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editDrink($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Drink"));
        $races_data = $this->PheramorDrinks->get($id)->toArray();
        $this->set("races_data", $races_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorDrinks->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->PheramorDrinks->patchEntity($row, $this->request->data);

            if ($this->PheramorDrinks->save($tag)) {
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
     * Delete Drink Lists
     * @Method deleteDrink
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */

    public function deleteDrink($id) {
        $row = $this->PheramorDrinks->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorDrinks->patchEntity($row,$this->request->data);
        if ($this->PheramorDrinks->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
