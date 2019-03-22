<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class DiscountCodeController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Discount Code
     * @Method Add Discount
     * @Date 27 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function addDiscount() {
        $session = $this->request->session()->read("User");
        $this->set("category", null);
        $this->set("edit", false);
        $this->set("title", __("Add Discount Code"));
       
        $category = $this->DiscountCode->newEntity();
        if ($this->request->is("post")) {
           $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["is_used"] = '0';
           $category = $this->DiscountCode->patchEntity($category, $this->request->data());
            if ($this->DiscountCode->save($category)) {
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
     * All Discount Lists
     * @Method DiscountList
     * @Date 27 Sep 2017
     * @Author RNF Technologies  
     */
    public function index($id=NULL) {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($id))
        {
            $this->editDiscount($id);
        }else{
           $this->addDiscount(); 
        }
        if ($session["role_id"] == 1) {
            $discount_data = $this->DiscountCode->find("all")->where(['is_deleted'=>'0'])->toArray();
        }
       
        $this->set("discount_data", $discount_data);
       
     }

     /**
     * Edit Member Race
     * @Method editRace
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
     
    public function editDiscounts($id){
         return $this->redirect(["action" => "index",$id]);
    }
    public function editDiscount($id) {

        $this->set("edit", true);
        $this->set("category", null);
        $this->set("title", __("Update Discount Code"));
        $dis_data = $this->DiscountCode->get($id)->toArray();
        $this->set("dis_data", $dis_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->DiscountCode->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $tag= $this->DiscountCode->patchEntity($row, $this->request->data);

            if ($this->DiscountCode->save($tag)) {
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

    public function deleteDiscount($id) {
        $row = $this->DiscountCode->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->DiscountCode->patchEntity($row,$this->request->data);
        if ($this->DiscountCode->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
