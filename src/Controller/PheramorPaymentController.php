<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Gmgt_paypal_class;
use Cake\Datasource\ConnectionManager;

class PheramorPaymentController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent("PHMFunction");
        $this->loadComponent("Stripe");
    }

    
    
     /**
     * Add Card in stripe
     * @Method addCard
     * @Date 29 Sep 2017
     * @Author RNF Technologies  
     */
    public function addCard($mid,$flag=null){
        
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        if (empty($mid) || !is_numeric($mid)) {
            $this->Flash->error(__("Error! No record found."));
            return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'memberList'));
        }
        $userdetails = $this->PHMFunction->get_user_details($mid);
        $this->set("member_id", $mid);
        $this->set("userdetails", $userdetails);
        if ($this->request->is("post")) {
            $memDetails = $this->PHMFunction->get_user_card_detail($mid);
            if ($memDetails) {
                $customerId = $memDetails;
            } else {
                $userdetails = $this->PHMFunction->get_user_details($mid);
                $customer = $this->Stripe->createCustomer(array(
                    'email' => strip_tags(trim($userdetails->email)),
                    'description' => "Card Save for auto paymemt by admin"
                ));
                if ($customer['status'] == 'success') {
                    $customerId = $customer['response']['id'];
                }else{
                    $customerId=0;
                }
           }

           if($customerId){
               
                $cardData = array('name'=>$this->request->data['card_name'],'number' => $this->request->data['card_number'], 'exp_month' => $this->request->data['expiry_month'],
                   'exp_year' => $this->request->data['expiry_year'], 'cvc' => $this->request->data['cvv']);
                 $response = $this->Stripe->createCard($customerId, $cardData);
               if ($response['status'] == 'success') {
                    $card_tables = TableRegistry::get("PheramorUserCardInfo");
                    $card_infos = $card_tables->newEntity();
                    $card_infos->user_id = $mid;
                    $card_infos->customer_id = $customerId;
                    $card_infos->cardholderName = $response['response']['name'];
                    $card_infos->maskedNumber = '********'.$response['response']['last4'];
                    $card_infos->cardType = $response['response']['brand'];
                    $card_infos->card_token = $response['response']['id'];
                    $card_infos->created_date =  date('Y-m-d H:i:s');
                    $card_infos->is_default =0;
                    $card_tables->save($card_infos);
                    $this->Flash->success(__("Success! Card Added Successfully."));
                   return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $mid));
                }else{
                    $this->Flash->error(__("Error! Failed to save your card details."));
                     return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $mid));
                }
            }else{
                $this->Flash->error(__("Error! Failed to save your card details."));
                 return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $mid));
            }
   
            return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $mid));
            
        }
    }
    
     /**
     * Delete Save Card in stripe
     * @Method deleteCard
     * @Date 06 Oct 2017
     * @Author RNF Technologies  
     */
    public function deleteCard($id, $mid) {

        $this->autoRender = false;
        $session = $this->request->session()->read("User");
        if (empty($mid) || !is_numeric($mid)) {
            $this->Flash->error(__("Error! No record found."));
            return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'memberList'));
        }

        if (!empty($id) && !empty($mid)) {
            $conn = ConnectionManager::get('default');
            $memDetails = $this->PHMFunction->get_user_card_detail($mid);
            $cardId = $this->PHMFunction->get_user_card_id($id);
            if ($memDetails) {
                $customerId = $memDetails;
            }
            if (!empty($customerId) && !empty($cardId)) {

                $response = $this->Stripe->deleteCard($customerId, $cardId);
                if ($response['status'] == 'success') {
                    $stmt = $conn->execute("delete from pheramor_user_card_info where id='$id'");
                    $this->Flash->success(__("Success! Record Deleted Successfully."));
                } else {
                    $this->Flash->error(__("Error! Card details has not deleted."));
                }
            } else {
                $this->Flash->error(__("Error! No customer register on stripe."));
            }
        }
        return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $mid));
    }
    
     /**
     * Add client new Subscription
     * @Method addSubscription
     * @Date 06 Oct 2017
     * @Author RNF Technologies  
     */

    public function addSubscription($mid) {
        $session = $this->request->session()->read("User");
         $conn = ConnectionManager::get('default');
        $this->set("edit", false);
        $members = $this->PheramorPayment->PheramorUser->PheramorUserProfile->find("list", ["keyField" => "user_id", "valueField" => "name"])->contain(["PheramorUser"])->where(["PheramorUser.role_name" => "member", "PheramorUser.is_deleted" => 0,]);
        $members = $members->select(["user_id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $membership = $this->PheramorPayment->PheramorSubscription
                        ->find("list", ["keyField" => "id", "valueField" => "subscription_title"])
                        ->contain(['PheramorSubscriptionCategory'])->where([
                            'PheramorSubscription.subscription_status' => 1,
                            "PheramorSubscriptionCategory.category_type"=>'subscription',
                            "PheramorSubscription.is_deleted" => "0"
                        ])->hydrate(false)->toArray();

        $card_info_tbl = TableRegistry::get("PheramorUserCardInfo");
        $card_data = $card_info_tbl->find()->where(['user_id' => $mid])->hydrate(false)->toArray();
        $this->set("card_data", $card_data);
        $this->set("members", $members);
        $this->set("membership", $membership);
        $this->set("members_ids", $mid);
        if ($this->request->is('post')) {

            //print_r($this->request->data); die;
            if($this->request->data["paid_amount_input"]< 0){
                 $this->Flash->error(__("Error! Your Payment amount is not valid. Please select correct subscription date"));
                  return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $mid));
            }
            $order_id = strtotime(date('Y-m-d H:i:s'));
            $start_date = date("Y-m-d", strtotime($this->request->data["membership_valid_from"]));
            $end_date = date("Y-m-d", strtotime($this->request->data["membership_valid_to"]));
           
            $pdata["user_id"] = $mid;
            $pdata["subscription_id"] = $this->request->data["subscription_id"];
            $pdata["subscription_amount"] = $this->request->data["subscription_amount"];
            $pdata["paid_amount"] = 0;
            $pdata["discount_amount"] = $this->request->data["discount_amount_input"];
            $pdata["discount_code"] = ''; 
            $pdata["start_date"] = $start_date;
            $pdata["end_date"] = $end_date;
            $pdata["referal_amount"] = 0;
            $pdata["plan_status"] = '0';
            $pdata["payment_status"] = '0';
            $pdata["paid_by"] = $this->request->data["payment_method"];
            $pdata["created_date"] = date('Y-m-d H:i:s');
            $pdata["created_by"] = $session['id'];
            $pdata["card_id"] = 0;
            $pdata["auto_pay_amt"] = 0;
            $pdata["weekly"] = 0;
            $row_payment = $this->PheramorPayment->newEntity();
            $row_payment = $this->PheramorPayment->patchEntity($row_payment, $pdata);
            $this->PheramorPayment->save($row_payment);
            $mp_id = $row_payment->id;
             
            if ($this->request->data["payment_method"] == 'Cash') {
                $update = $conn->execute("UPDATE pheramor_payment set plan_status = '0' WHERE plan_status = '1' and user_id = '$mid'");
                $membership_pay_table = TableRegistry::get("PheramorPayment");
                $rowss = $membership_pay_table->findById($mp_id)->first();
                $rowss["paid_amount"] = $this->request->data["paid_amount_input"];
                $rowss["plan_status"] = 1;
                $rowss["payment_status"] = 1;
                // Payment history table update here
                if ($membership_pay_table->save($rowss)) {
                    $paymenth_table = TableRegistry::get("PheramorPaymentHistory");
                    $pay_history = $paymenth_table->newEntity();
                    $pay_history->payment_id = $mp_id;
                    $pay_history->payment_amount = $this->request->data["paid_amount_input"];
                    $pay_history->payment_method = 'Cash';
                    $pay_history->charge_id = '';
                    $pay_history->paid_by_date = date("Y-m-d");
                    $pay_history->paid_by = $this->request->session()->read("User.id");
                    $pay_history->trasaction_id = '';
                    $pay_history->order_id = $order_id;
                    $paymenth_table->save($pay_history);
                }
                $this->Flash->success(__("Success! Payment Added Successfully."));
            } else {
                $card_id = $this->request->data["card_info"];
                $token = $this->PHMFunction->get_user_card_id($card_id);
                $customerId = $this->PHMFunction->get_user_card_detail($mid);
                $paid_amt = $this->request->data["paid_amount_input"] * 100;
                if (!empty($customerId) && !empty($token)) {
                    $data = array(
                        "amount" => $paid_amt, // 1500, // $15.00 this time
                        "currency" => "usd",
                        "description" => $this->PHMFunction->get_subscription_name($this->request->data["subscription_id"]),
                        "source" => $token,
                    );
                    $response = $this->Stripe->charge($data, $customerId);
                    if ($response['status'] == 'success') {
                        $update = $conn->execute("UPDATE pheramor_payment set plan_status = '0' WHERE plan_status = '1' and user_id = '$mid'");
                        $membership_pay_table = TableRegistry::get("PheramorPayment");
                        $rowss = $membership_pay_table->findById($mp_id)->first();
                        $paid_amount = $response['response']['amount'] / 100;
                        $rowss["paid_amount"] = $paid_amount;
                        $rowss["plan_status"] = 1;
                        $rowss["payment_status"] = 1;
                        $rowss["card_id"] = $card_id;
                         // Payment history table update here
                        if ($membership_pay_table->save($rowss)) {
                            $paymenth_table = TableRegistry::get("PheramorPaymentHistory");
                            $pay_history = $paymenth_table->newEntity();
                            $pay_history->payment_id = $mp_id;
                            $pay_history->payment_amount = $paid_amount;
                            $pay_history->payment_method = 'Online';
                            $pay_history->paid_by_date = date("Y-m-d");
                             $pay_history->charge_id = $response['response']['id'];;
                            $pay_history->paid_by = $this->request->session()->read("User.id");
                            $pay_history->trasaction_id = $response['response']['balance_transaction'];
                            $pay_history->order_id = $order_id;
                            $paymenth_table->save($pay_history);
                        }
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
     * Edit client  Subscription
     * @Method subscriptionEdit
     * @Date 06 Oct 2017
     * @Author RNF Technologies  
     */
    public function editSubscription($paymentID)
    {
        $session = $this->request->session()->read("User");
        $this->set("edit", true);
        $data = $this->PheramorPayment->get($paymentID);
        $this_record = $data->toArray();
        $this->set("data", $this_record);
        
        $membership = $this->PheramorPayment->PheramorSubscription
                        ->find("list", ["keyField" => "id", "valueField" => "subscription_title"])
                         ->contain(['PheramorSubscriptionCategory'])->where([
                            'PheramorSubscription.subscription_status' => 1,
                            "PheramorSubscription.is_deleted" => "0",
                            "PheramorSubscriptionCategory.category_type"=>"subscription",
                        ])->hydrate(false)->toArray();
         $this->set("membership", $membership);
         
        $members = $this->PheramorPayment->PheramorUser->PheramorUserProfile->find("list", ["keyField" => "user_id", "valueField" => "name"])->contain(["PheramorUser"])->where(["PheramorUser.role_name" => "member", "PheramorUser.is_deleted" => 0,]);
        $members = $members->select(["user_id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $this->set("members", $members);
        if ($this->request->is('post')) {
             
          //  echo "<pre>";print_r($this->request->data); die;
            $id = $this->request->data['id'];
            $user_id = $this->request->data["user_id"];
            $row1 = $this->PheramorPayment->get($id);
            $pdata["plan_status"] = $this->request->data["plan_status"];
            $pdata["payment_status"] = $this->request->data["payment_status"];
            $pdata["paid_amount"] = $this->request->data["paid_amount"];
            $pdata["discount_amount"] = $this->request->data["discount_amount"];
            $end_date = date("Y-m-d", strtotime($this->request->data["membership_valid_to"]));
            $pdata["end_date"] = $end_date;
            // print_r($pdata); die;
            $row2 = $this->PheramorPayment->patchEntity($row1, $pdata);
            $this->PheramorPayment->save($row2);
             $this->Flash->success(__("Success! Payment has been updated Successfully."));
            return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $user_id));
            
         }
    }
    
    
     /**
     * Un-subscribe client  Subscription
     * @Method unsubscribe
     * @Date 09 Oct 2017
     * @Author RNF Technologies  
     */
     public function unsubscribe($mp_id, $user_id) {
        $session = $this->request->session()->read("User");
        $row1 = $this->PheramorPayment->get($mp_id);
        $pdata["plan_status"] = '4';
        $row2 = $this->PheramorPayment->patchEntity($row1, $pdata);
        $this->PheramorPayment->save($row2);   
        $this->Flash->success(__("Success! Your plan has been unsubscribed."));
        return $this->redirect(array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $user_id));
            
            
     }
     
     /**
     * Refund Payment Module
     * @Method refundPayment
     * @Date 09 Oct 2017
     * @Author RNF Technologies  
     */
     
     public function refundPayment($mp_id = 0, $userID = 0) {
        $session = $this->request->session()->read("User");
        if (empty($mp_id) && empty($userID)) {
            $this->Flash->error(__("Please select member subscription plan for refund."));
            return $this->redirect(["action" => "refundPaymentList"]);
        }
        $this->set("edit", true);
        $refund = $this->PheramorPayment->find('all')->where(["id" => $mp_id])->first();
        $this->set("payment_method", $refund->paid_by);
        $this->set("members_ids", $userID);
        $this->set("mpID", $mp_id);
        $members = $this->PheramorPayment->PheramorUser->PheramorUserProfile->find("list", ["keyField" => "user_id", "valueField" => "name"])->contain(["PheramorUser"])->where(["PheramorUser.role_name" => "member", "PheramorUser.is_deleted" => 0,]);
        $members = $members->select(["user_id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $this->set("members", $members);

        $mem_payment_data = $this->PheramorPayment->find('all')->where(['id' => $mp_id])->select(['paid_amount', 'subscription_id'])->first();
        $paid_amount = $mem_payment_data['paid_amount'];
        $total_refund = $this->PHMFunction->get_total_refunded_amt($mp_id);
        $refunded_amount = $paid_amount - $total_refund;
        $this->set("refunded_amount", $refunded_amount);
        $this->set("subscription_id", $mem_payment_data['subscription_id']);

        if ($this->request->is("post")) {
            $amt = $this->request->data['amount'];
            if ($amt > $refunded_amount) {
                $this->Flash->error(__("your refund amount is greater than reaming amount."));
                return $this->redirect(array('controller' => 'PheramorPayment', 'action' => 'RefundPayment/' . $mp_id . '/' . $userID));
            }
            $row = $this->PheramorPayment->PheramorRefundPayment->newEntity();
            if ($refund->paid_by == 'Cash') {
                $data["user_id"] = $this->request->data['user_id'];
                $data["refund_amount"] = $this->request->data['amount'];
                $data["refund_by"] = $session["id"]; //current userid;			
                $data["refund_date"] = date("Y-m-d H:i:s");
                $data["refund_status"] = 1;
                $data["mpID"] = $this->request->data['mp_id'];
                $data["subscription_id"] = $this->request->data['subscription_id'];
                $data["comments"] = $this->request->data['comments'];
                $data["refund_type"] = 'Cash';

                $row = $this->PheramorPayment->PheramorRefundPayment->patchEntity($row, $data);
                if ($this->PheramorPayment->PheramorRefundPayment->save($row)) {
                    $this->Flash->success(__("Success! Record Saved Successfully."));
                    return $this->redirect(["action" => "refundPaymentList"]);
                }
            } else {
                $refund_amt = $this->request->data['amount'] * 100;
                $payment_charge_data = $this->PheramorPayment->PheramorPaymentHistory->find('all')->where(['payment_id' => $mp_id])->select(['charge_id'])->first();
                $chargeId = $payment_charge_data->charge_id;
                $data = array(
                    "amount" => $refund_amt // 1500, // $15.00 this time
                        //"reason" => "Refund"
                );
                //print_r($data); die;
                if (!empty($chargeId)) {
                    $refund_data = $this->Stripe->refundCharge($chargeId, $data);
                    $data["user_id"] = $this->request->data['user_id'];
                    $data["refund_amount"] = 0;
                    $data["refund_by"] = $session["id"]; //current userid;			
                    $data["refund_date"] = date("Y-m-d H:i:s");
                    $data["refund_status"] = 0;
                    $data["mpID"] = $this->request->data['mp_id'];
                    $data["subscription_id"] = $this->request->data['subscription_id'];
                    $data["comments"] = $this->request->data['comments'];
                    $data["refund_type"] = 'Online';
                    //print_r($refund_data);
                    if ($refund_data['status'] == 'success') {
                        $data["refund_amount"] = $refund_data['response']['amount_refunded'] / 100;
                        $data["transaction_id"] = $refund_data['response']['balance_transaction'];
                        $data["charge_id"] = $refund_data['response']['id'];
                         $data["refund_status"] = 1;
                        $this->Flash->success(__("Success! Record Saved Successfully."));
                    } else {
                        $this->Flash->error(__("Error! Refund payment has been failed."));
                    }
                    $row = $this->PheramorPayment->PheramorRefundPayment->patchEntity($row, $data);
                    $this->PheramorPayment->PheramorRefundPayment->save($row);
                } else {
                    $this->Flash->error(__("Your Transaction is not valid."));
                }
                return $this->redirect(["action" => "refundPaymentList"]);
            }
        }
    }
    
     /**
     * View All Refund payment listing
     * @Method RefundPaymentList
     * @Date 09 Oct 2017
     * @Author RNF Technologies  
     */
    public function RefundPaymentList() {
           $session = $this->request->session()->read("User");
           $data = $this->PheramorPayment->PheramorRefundPayment->find("all")->order(["PheramorRefundPayment.id"=>"DESC"])->hydrate(false)->toArray();
           $this->set("data", @$data);
           }

    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
