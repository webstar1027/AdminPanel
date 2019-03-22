<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class PheramorSubscriptionController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
    }
    
    /**
     * Add Membership Subscription
     * @Method Add Subscription
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
    
    public function add() {
        $session = $this->request->session()->read("User");
        $this->set("membership", null);
        $this->set("edit", false);
        $this->set("title", __("Add Subscription"));
        $catgories = $this->PheramorSubscription->PheramorSubscriptionCategory->find("list", ["keyField" => "id", "valueField" => "category_name"])->where(['is_deleted'=>'0']);
        $catgories = $catgories->toArray();
        $this->set('categories', $catgories);
        $membership = $this->PheramorSubscription->newEntity();
        if ($this->request->is("post")) {
            $subscription_cat_id=$this->request->data["subscription_cat_id"];
            $cat_type = $this->PheramorSubscription->PheramorSubscriptionCategory->find("all")->where(['id'=>$subscription_cat_id])->first();
            if($cat_type->category_type=='product'){
                 $subscription_arr = json_encode($this->request->data['subscription_amount_week']);
                 $this->request->data['subscription_amount']=$subscription_arr;
            }
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
           $this->request->data["updated_date"] = date("Y-m-d H:i:s");
           $this->request->data["is_deleted"] = '0';
           $this->request->data["created_by"] = $session['id'];
           
            
            $membership = $this->PheramorSubscription->patchEntity($membership, $this->request->data());

            if ($this->PheramorSubscription->save($membership)) {
                $this->Flash->success(__("Success! Record Saved Successfully"));
                return $this->redirect(["action" => "subscriptionList"]);
            } else {
               
                $this->Flash->error('Error');
               
                return;
               
            }
        }
    }
    
    /**
     * Membership Subscription Lists
     * @Method subscriptionList
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */
    public function subscriptionList() {
       
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if ($session["role_id"] == 1) {
            $membership_data = $this->PheramorSubscription->find("all")->contain(["PheramorSubscriptionCategory"])->where(['PheramorSubscription.is_deleted'=>'0','PheramorSubscriptionCategory.activated'=>'1',])->toArray();
        }
       
        $this->set("membership_data", $membership_data);
       
     }

     /**
     * Edit Membership Subscription
     * @Method Edit Subscription.
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
     
    public function editSubscription($id) {

        $this->set("edit", true);
        $this->set("membership", null);
        $this->set("title", __("Edit Subscription"));
        $membership_data = $this->PheramorSubscription->get($id)->toArray();
        $catgories = $this->PheramorSubscription->PheramorSubscriptionCategory->find("list", ["keyField" => "id", "valueField" => "category_name"])->where(['is_deleted'=>'0']);
        $catgories = $catgories->toArray();
        $this->set('categories', $catgories);
        $this->set("membership_data", $membership_data);
       

        if ($this->request->is("post")) {
          
            $row = $this->PheramorSubscription->get($id);
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $subscription_cat_id=$row->subscription_cat_id;
            $cat_type = $this->PheramorSubscription->PheramorSubscriptionCategory->find("all")->where(['id'=>$subscription_cat_id])->first();
            if($cat_type->category_type=='product'){
                 $subscription_arr = json_encode($this->request->data['subscription_amount_week']);
                 $this->request->data['subscription_amount']=$subscription_arr;
            }
            
            $membership = $this->PheramorSubscription->patchEntity($row, $this->request->data);
            if ($this->PheramorSubscription->save($membership)) {
                // echo "<pre>";print_r($membership); die;
                $this->Flash->success(__("Success! Record Updated Successfully"));
                return $this->redirect(["action" => "SubscriptionList"]);
            } else {
                $this->Flash->error(__("Error! There was an error while updating,Please try again later."));
            }
        }
        $this->render("add");
    }

    /**
     * Delete Subscription Lists
     * @Method deleteMemberships
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */

    public function deleteSubscription($id) {
        $row = $this->PheramorSubscription->get($id);
        $this->request->data['is_deleted'] = '1';
        $row = $this->PheramorSubscription->patchEntity($row,$this->request->data);
        if ($this->PheramorSubscription->save($row)) {
            $this->Flash->Success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    




    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
