<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Gmgt_paypal_class;
use Cake\Datasource\ConnectionManager;

class PheramorProductPaymentController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent("PHMFunction");
        $this->loadComponent("Stripe");
    }
    /**
     * Add client  Product
     * @Method addProduct
     * @Date 24 Oct 2017
     * @Author RNF Technologies  
     */
    public function addProduct($mid) {
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $members = $this->PheramorProductPayment->PheramorUser->PheramorUserProfile->find("list", ["keyField" => "user_id", "valueField" => "name"])->contain(["PheramorUser"])->where(["PheramorUser.role_name" => "member", "PheramorUser.is_deleted" => 0,]);
        $members = $members->select(["user_id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $membership = $this->PheramorProductPayment->PheramorSubscription
                        ->find("list", ["keyField" => "id", "valueField" => "subscription_title"])
                        ->contain(['PheramorSubscriptionCategory'])->where([
                            'PheramorSubscription.subscription_status' => 1,
                            'PheramorSubscriptionCategory.category_type' => 'product',
                            "PheramorSubscription.is_deleted" => "0"
                        ])->hydrate(false)->toArray();

        $card_info_tbl = TableRegistry::get("PheramorUserCardInfo");
        $card_data = $card_info_tbl->find()->where(['user_id' => $mid,"card_token !="=>''])->hydrate(false)->toArray();
        $this->set("card_data", $card_data);
        $this->set("members", $members);
        $this->set("membership", $membership);
        $this->set("members_ids", $mid);
        if ($this->request->is('post')) {
         $product_count = $this->PheramorProductPayment->find()->where(['payment_status'=>1,'user_id' => $mid])->first();
        if(!empty($product_count)){
            
          $this->Flash->error(__("Error! You have already purchased pheramor Kit"));
           return $this->redirect($this->referer());  
        } 
        
        $product_amt = $this->PheramorProductPayment->PheramorSubscription->find()->where(['id' => $this->request->data["subscription_id"]])->first();
        $price_arr = json_decode($product_amt->subscription_amount);
        $pamount = $this->PHMFunction->getUserRegisterWeek($mid, $price_arr);
       // $pamount=$product_amt->subscription_amount;
            //print_r($this->request->data); die;
            $order_id = strtotime(date('Y-m-d H:i:s'));
           // $start_date = date("Y-m-d", strtotime($this->request->data["membership_valid_from"]));
           // $end_date = date("Y-m-d", strtotime($this->request->data["membership_valid_to"]));
            $paid_amount=$this->request->data["paid_amount"];
            $pdata["user_id"] = $mid;
            $pdata["product_id"] = $this->request->data["subscription_id"];
            $pdata["product_amount"] = $pamount;
            $pdata["paid_amount"] = 0;
            $pdata["discount_amount"] = $this->request->data["discount_amount"];
            $pdata["discount_code"] = $this->request->data["discount_code_input"];
            $pdata["order_status"] = 0;
           // $pdata["start_date"] = $start_date;
            //$pdata["end_date"] = $end_date;
           // $pdata["referal_amount"] = 0;
            //$pdata["plan_status"] = '0';
            $pdata["payment_status"] = '0';
            $pdata["paid_by"] = $this->request->data["payment_method"];
            $pdata["created_date"] = date('Y-m-d H:i:s');
            $pdata["created_by"] = $session['id'];
            //$pdata["card_id"] = 0;
           // $pdata["auto_pay_amt"] = 0;
            //$pdata["weekly"] = 0;
            $row_payment = $this->PheramorProductPayment->newEntity();
            $row_payment = $this->PheramorProductPayment->patchEntity($row_payment, $pdata);
            $this->PheramorProductPayment->save($row_payment);
            $mp_id = $row_payment->id;
             
            if ($this->request->data["payment_method"] == 'Cash') {
                $membership_pay_table = TableRegistry::get("PheramorProductPayment");
                $rowss = $membership_pay_table->findById($mp_id)->first();
                $rowss["paid_amount"] = $paid_amount;
                $rowss["payment_status"] = 1;
                $rowss["order_status"] = 3;
                $rowss["order_id"] = $order_id;
                $rowss["charge_id"] = '';
                $rowss["trasaction_id"] = '';
                $membership_pay_table->save($rowss);
                $this->addMembership($mid);
                $this->Flash->success(__("Success! Payment Added Successfully."));
            } else {
                $card_id = $this->request->data["card_info"];
                $token = $this->PHMFunction->get_user_card_id($card_id);
                $customerId = $this->PHMFunction->get_user_card_detail($mid);
                $shippingData = $this->PHMFunction->get_user_shipping_address($mid);
//                 $shipping = array(
//                        "name"=>$shippingData->ship_name,
//                        "phone"=>'7894561230',
//                        "address"=>array(
//                            "city"=>$shippingData->ship_city,
//                            "country"=>'United States',
//                            "line1"=>$shippingData->ship_address,
//                            "line2"=>'',
//                            "postal_code"=>$shippingData->ship_zipcode,
//                            "state"=>$shippingData->ship_state,
//                          )
//                        );
                $paid_amt = $paid_amount * 100;
                if (!empty($customerId) && !empty($token)) {
                    $data = array(
                        "amount" => $paid_amt, // 1500, // $15.00 this time
                        "currency" => "usd",
                        "description" => $this->PHMFunction->get_subscription_name($this->request->data["subscription_id"]),
                        "source" => $token
                       // "shipping" => $shipping
                    );
                    $response = $this->Stripe->charge($data, $customerId);
                    if ($response['status'] == 'success') {
                        $membership_pay_table = TableRegistry::get("PheramorProductPayment");
                        $rowss = $membership_pay_table->findById($mp_id)->first();
                        $paid_amounts = $response['response']['amount'] / 100;
                        $rowss["paid_amount"] = $paid_amounts;
                        $rowss["payment_status"] = 1;
                        $rowss["order_status"] = 2;
                        $rowss["order_id"] = $order_id;
                        $rowss["trasaction_id"] = $response['response']['balance_transaction'];
                        $rowss["charge_id"] =$response['response']['id'];
                         // Payment history table update here
                       $membership_pay_table->save($rowss);
                        $this->addMembership($mid);
                       $this->Flash->success(__("Success! Payment Added Successfully."));
                    } else {
                        $this->Flash->error(__("Error! Your Payment has beeen failed"));
                    }
                } else {
                    $this->Flash->error(__("Error! Your Payment has beeen failed"));
                }
            }
             return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $mid));
        }
       
    }
    
     /**
     * Edit client  Product
     * @Method editProduct
     * @Date 24 Oct 2017
     * @Author RNF Technologies  
     */
    public function editProduct($paymentID)
    {
        $session = $this->request->session()->read("User");
        $this->set("edit", true);
        $data = $this->PheramorProductPayment->get($paymentID);
        $this_record = $data->toArray();
        $this->set("data", $this_record);
        
        $membership = $this->PheramorProductPayment->PheramorSubscription
                        ->find("list", ["keyField" => "id", "valueField" => "subscription_title"])
                        ->contain(['PheramorSubscriptionCategory'])->where([
                            'PheramorSubscription.subscription_status' => 1,
                            "PheramorSubscription.is_deleted" => "0",
                            "PheramorSubscriptionCategory.category_type"=>"product",
                        ])->hydrate(false)->toArray();
         $this->set("membership", $membership);
         
        $members = $this->PheramorProductPayment->PheramorUser->PheramorUserProfile->find("list", ["keyField" => "user_id", "valueField" => "name"])->contain(["PheramorUser"])->where(["PheramorUser.role_name" => "member", "PheramorUser.is_deleted" => 0,]);
        $members = $members->select(["user_id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $this->set("members", $members);
        if ($this->request->is('post')) {
             
          //  echo "<pre>";print_r($this->request->data); die;
            $id = $this->request->data['id'];
            $user_id = $this->request->data["user_id"];
            $row1 = $this->PheramorProductPayment->get($id);
            $pdata["payment_status"] = $this->request->data["payment_status"];
            $pdata["paid_amount"] = $this->request->data["paid_amount"];
            $pdata["discount_amount"] = $this->request->data["discount_amount"];
            $row2 = $this->PheramorProductPayment->patchEntity($row1, $pdata);
            $this->PheramorProductPayment->save($row2);
             $this->Flash->success(__("Success! Payment has been updated Successfully."));
            return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $user_id));
            
         }
    }
    
     /**
     * Product PDF View
     * @Method pdfView
     * @Date 24 Oct 2017
     * @Author RNF Technologies  
     */  
    
    
    public function pdfView($ftype = '0', $mp_id) {

         $payment_tbl = TableRegistry::get("PheramorProductPayment");
         $setting_tbl = TableRegistry::get("PheramorGeneralSetting");
         $sys_data = $setting_tbl->find()->select(["company_name", "company_address", "company_logo", "date_format", "company_phone"])->hydrate(false)->toArray();
         $sys_data[0]["company_logo"] = (!empty($sys_data[0]["company_logo"])) ? $this->request->base . "/webroot/upload/" . $sys_data[0]["company_logo"] : $this->request->base . "/webroot/img/Thumbnail-img.png";
         $data = $payment_tbl->find("all")->contain(["PheramorUser"])->where(["PheramorProductPayment.id" => $mp_id])->hydrate(false)->toArray();
    //print_r($data);
        //$this->GYMFunction->pre($data); die;
        $session = $this->request->session();
        $float_l = ($session->read("User.is_rtl") == "1") ? "right" : "left";
        $float_r = ($session->read("User.is_rtl") == "1") ? "left" : "right";
        $float_l = "left";
        $float_r = "right";

        $this->set('ftype', $ftype);
        $this->set('title', 'Product Invoice');
        $this->viewBuilder()->layout('pdf/pdf');
        $this->set("float_r", $float_r);
        $this->set("float_l", $float_l);
        $this->set("sys_data", $sys_data);
        //$this->GYMFunction->pre($data);
        $this->set("data", $data);
     
        $this->set('mp_id', $mp_id);
        $this->viewBuilder()->template('pdf/invoice');
        $this->set('filename', date('Y-m-d') . '_invoice.pdf');
        $this->response->type('pdf');
    }
    
     /**
     * Subscription PDF View
     * @Method pdfViews
     * @Date 24 Oct 2017
     * @Author RNF Technologies  
     */  
    
    
    public function pdfViews($ftype = '0', $mp_id) {

         $payment_tbl = TableRegistry::get("PheramorPayment");
         $setting_tbl = TableRegistry::get("PheramorGeneralSetting");
         $sys_data = $setting_tbl->find()->select(["company_name", "company_address", "company_logo", "date_format", "company_phone"])->hydrate(false)->toArray();
         $sys_data[0]["company_logo"] = (!empty($sys_data[0]["company_logo"])) ? $this->request->base . "/webroot/upload/" . $sys_data[0]["company_logo"] : $this->request->base . "/webroot/img/Thumbnail-img.png";
         $data = $payment_tbl->find("all")->contain(["PheramorUser"])->where(["PheramorPayment.id" => $mp_id])->hydrate(false)->toArray();
    //print_r($data);
        //$this->GYMFunction->pre($data); die;
        $session = $this->request->session();
        $float_l = ($session->read("User.is_rtl") == "1") ? "right" : "left";
        $float_r = ($session->read("User.is_rtl") == "1") ? "left" : "right";
        $float_l = "left";
        $float_r = "right";

        $this->set('ftype', $ftype);
        $this->set('title', 'Subscription Invoice');
        $this->viewBuilder()->layout('pdf/pdf');
        $this->set("float_r", $float_r);
        $this->set("float_l", $float_l);
        $this->set("sys_data", $sys_data);
        //$this->GYMFunction->pre($data);
        $this->set("data", $data);
     
        $this->set('mp_id', $mp_id);
        $this->viewBuilder()->template('pdf/invoices');
        $this->set('filename', date('Y-m-d') . '_invoice.pdf');
        $this->response->type('pdf');
    }
    
    
    /** Add new membership Added after Purchases
     * Pheramor Kit 
     * @Method addMembership
     * @Date 24 Nov 2017
     * @Author RNF Technologies  
     */
    
    private function addMembership($member_id){
        
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(!empty($member_id)){
          $stmt = $conn->execute("Update  pheramor_payment set plan_status = '3' WHERE user_id='$member_id'  ");
          $stmt_data = $conn->execute("SELECT id FROM pheramor_genetic_information  WHERE user_id = '" . $member_id . "'");
         if ($stmt_data->count()) {}else{
              $pin = $this->generatePIN(5);
              $pheramor_kit_ID='UP-'.$pin.'-'.$member_id;
              $stmt_data = $conn->execute("insert into pheramor_genetic_information set user_id = '" . $member_id . "', pheramor_kit_ID='".$pheramor_kit_ID."', created_date='".date('Y-m-d H:i:s')."', updated_date='".date('Y-m-d H:i:s')."' ");
         }
         
         /**** Referal code status Updated here ***/
         $referal_tbl = TableRegistry::get("PheramorUserReferred");
         $refer_data = $referal_tbl->find("all")->where(['refer_to' => $member_id,'is_credit'=>'0'])->first();
         if(!empty($refer_data)){
            $stmt = $conn->execute("Update pheramor_user_referred set is_credit = '1' WHERE id='$refer_data->id'  "); 
         }
         
         
         /*** Free Basic Subscription added here **/
//        if ($stmt->count()) {
//            return $member_id;
//        } else {
            $credit_setting_table = TableRegistry::get("PheramorCreditSetting");
            $credit_data = $credit_setting_table->find('all')->select(['kit_subscription_id', 'kit_subscription_duration'])->where(['id' => 1])->first();
            if (!empty($credit_data->kit_subscription_id)) {
                $kit_subscription_id = $credit_data->kit_subscription_id;
                $duration = $credit_data->kit_subscription_duration;

                $stmts = $conn->execute("SELECT subscription_id FROM pheramor_payment WHERE user_id='$member_id' AND subscription_id = '$kit_subscription_id'");
                if ($stmts->count()) {
                    return $member_id;
                } else {
                    $product_amt = $this->PheramorProductPayment->PheramorSubscription->find()->where(['id' => $kit_subscription_id])->first();
                    $pamount = $product_amt->subscription_amount;

                    $order_id = strtotime(date('Y-m-d H:i:s'));
                    $start_date = date("Y-m-d");
                    $end_date = date('Y-m-d', strtotime($start_date . " + {$duration} month"));
                    $paid_amount = $this->request->data["paid_amount"];
                    $pdata["user_id"] = $member_id;
                    $pdata["subscription_id"] = $kit_subscription_id;
                    $pdata["subscription_amount"] = $pamount;
                    $pdata["paid_amount"] = 0;
                    $pdata["discount_amount"] = $pamount;
                    $pdata["discount_code"] = 'Pheramor Kit';
                    $pdata["start_date"] = $start_date;
                    $pdata["end_date"] = $end_date;
                    // $pdata["referal_amount"] = 0;
                    $pdata["plan_status"] = '1';
                    $pdata["payment_status"] = '1';
                    $pdata["paid_by"] = 'Cash';
                    $pdata["created_date"] = date('Y-m-d H:i:s');
                    $pdata["created_by"] = $session['id'];
                    $row_payment = $this->PheramorProductPayment->PheramorPayment->newEntity();
                    $row_payment = $this->PheramorProductPayment->PheramorPayment->patchEntity($row_payment, $pdata);
                    $this->PheramorProductPayment->PheramorPayment->save($row_payment);
                    $mp_id = $row_payment->id;

                    /// Payment History

                    $paymenth_table = TableRegistry::get("PheramorPaymentHistory");
                    $pay_history = $paymenth_table->newEntity();
                    $pay_history->payment_id = $mp_id;
                    $pay_history->amount = $pamount;
                    $pay_history->payment_method = 'Online';
                    $pay_history->paid_by_date = date("Y-m-d");
                    $pay_history->paid_by = $session['id'];
                    $pay_history->trasaction_id = '';
                    $pay_history->order_id = $order_id;
                    $paymenth_table->save($pay_history);
                }
            }
       // }
        
        }
    }

   /*** Generate number **/
    
    private function generatePIN($digits = 4) {
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while ($i < $digits) {
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    public function isAuthorized($user) {
      return parent::isAuthorizedCustom($user);
   }

}
