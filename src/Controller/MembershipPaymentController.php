<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Gmgt_paypal_class;
use Cake\Datasource\ConnectionManager;

class MembershipPaymentController extends AppController {

    public function initialize() {
        parent::initialize();
        require_once(ROOT . DS . 'vendor' . DS . 'paypal' . DS . 'paypal_class.php');
        require_once(ROOT . DS . 'vendor' . DS . 'tcpdf' . DS . 'tcpdf.php');
        $this->loadComponent("GYMFunction");
    }

    public function paymentList() {
        $new_session = $this->request->session();
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $loggedUser = $this->GYMFunction->get_user_detail($session['id']);
        if ($session["role_name"] == "administrator") {
            $data = $this->MembershipPayment->find("all")
                            ->where(['MembershipPayment.mem_plan_status' => 1])
                            ->orWhere(['MembershipPayment.mem_plan_status' => 2])
                            ->orWhere(['MembershipPayment.mem_plan_status' => 0])
                            ->order([
                                'MembershipPayment.mp_id' => 'DESC',
                                'MembershipPayment.payment_status' => 'DESC',
                                'MembershipPayment.mem_plan_status' => 'ASC',
                                'MembershipPayment.start_date' => 'ASC'
                            ])
                            ->contain(["Membership", "GymMember"])
                            ->hydrate(false)->toArray();
        } else if ($session["role_name"] == "licensee" || $session["role_name"] == "manager" || $session["role_name"] == "staff_member") {
            $data = $this->MembershipPayment->find("all")
                            ->contain(["Membership", "GymMember"])
                            ->where(['MembershipPayment.mem_plan_status' => 1])
                            ->orWhere(['MembershipPayment.mem_plan_status' => 2])
                            ->orWhere(['MembershipPayment.mem_plan_status' => 0])
                            ->order([
                                'MembershipPayment.mp_id' => 'DESC',
                                'MembershipPayment.payment_status' => 'DESC',
                                'MembershipPayment.mem_plan_status' => 'ASC',
                                'MembershipPayment.start_date' => 'ASC'
                            ])
                            ->hydrate(false)->toArray();
        } else {
            $data = $this->MembershipPayment->find("all")
                            ->contain(["Membership", "GymMember"])
                            ->where([
                                "MembershipPayment.member_id" => $session["id"],
                                'OR' => [
                                    ['MembershipPayment.mem_plan_status' => 1],
                                    ['MembershipPayment.mem_plan_status' => 2],
                                    ['MembershipPayment.mem_plan_status' => 0],
                                ],
                            ])
                            ->order([
                                'MembershipPayment.payment_status' => 'DESC',
                                'MembershipPayment.mem_plan_status' => 'ASC',
                                'MembershipPayment.start_date' => 'ASC',
                                'MembershipPayment.mp_id' => 'DESC'
                            ])
                            ->hydrate(false)->toArray();
        }
        //$this->GYMFunction->pre($data);
        $this->set("data", $data);

        if ($this->request->is("post")) {

            //  print_r($this->request->data);

            $session = $this->request->session()->read("User");
            $mp_id = $this->request->data["mp_id"];
            $row = $this->MembershipPayment->findByMpId($mp_id)->contain(['Membership'])->first();
            /* $user_id = $row['member_id'];
              $membership_id = $row['membership_id']; */
            $user_id = $this->request->data['user_id'];
            $membership_id = $this->request->data['gen_membership_id'];
            $start_date = date('Y-m-d', strtotime($this->request->data['membership_valid_from']));
            $end_date = date('Y-m-d', strtotime($this->request->data['membership_valid_to']));

            $user_info = $this->MembershipPayment->GymMember->get($user_id);
            //Online Payment by customers themselves
            // Active client data
            $membership_cat_id = $row['membership']['membership_cat_id'];

            $active_memb = $this->MembershipPayment->find()->contain(['Membership'])->where(['Membership.membership_cat_id' => $membership_cat_id, 'MembershipPayment.mem_plan_status' => '1', 'MembershipPayment.member_id' => $user_id])->select(['MembershipPayment.end_date', 'MembershipPayment.membership_id'])->hydrate(false)->toArray();
            if (!empty($active_memb)) {
                $active_memb = $active_memb[0];
                // echo "<pre>";print_r($active_memb['end_date']);
                if ($membership_id <= $active_memb['membership_id']) {
                    if (strtotime($active_memb['end_date']) >= strtotime($start_date)) {
                        $this->Flash->error(__("Error! Please select valid date range"));
                        //  return $this->redirect(["action" => "view-member/".$user_id]); 
                        return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $user_id));
                    }
                }
            }
            // die; 
            if (@$this->request->data["card_info"] != '') {
                $new_session->write("Payment.mp_id", $mp_id);
                $new_session->write("Payment.amount", $this->request->data["amount"]);
                require_once(ROOT . DS . 'vendor' . DS . 'braintree-php' . DS . 'braintree_admin_payment.php');
                if ($OrderStatus == 1) {
                    $this->Flash->success(__("Success! Payment Added Successfully."));
                } else {
                    $this->Flash->error(__("Error! Your Payment has beeen failed"));
                }
            } else {

                $row['paid_amount'] = $row['paid_amount'] + $this->request->data["amount"];
                $row['paid_by'] = $session['id'];
                $row['payment_status'] = 1;
                //$row['mem_plan_status'] = 1;
                // Check logic to set mem_plan_status
                $membership_info = $this->MembershipPayment->Membership->find()->where(['membership_cat_id' => $membership_cat_id])->select(['Membership.id'])->hydrate(false)->toArray();
                $existingMembershipIdsArr = array(0);
                if (count($membership_info) > 0) {
                    foreach ($membership_info as $existingIds) {
                        $existingMembershipIdsArr[] = $existingIds['id'];
                    }
                }

                $other_rows = $this->MembershipPayment->find()->where(['member_id' => $user_id, 'mp_id !=' => $mp_id, 'membership_id IN' => $existingMembershipIdsArr]);
                //  echo "<pre>"; print_r($other_rows->toArray()); die;
                if (count($other_rows->toArray()) > 0) {
                    foreach ($other_rows->toArray() as $other_row) {

                        if ($other_row['mem_plan_status'] == 1 && ( strtotime($other_row['end_date']) >= strtotime($start_date) )) {
                            //$row['mem_plan_status'] = 1;
                            if (strtotime($start_date) == strtotime(date('Y-m-d'))) {
                                $other_row_update_array['mem_plan_status'] = 0;
                                $row['mem_plan_status'] = 1;
                                $start_date1 = date('Y-m-d', strtotime('-1 day', strtotime($start_date)));
                                $other_row_update_array['end_date'] = $start_date1;



                                //  $update_group ="update gym_member set assign_group='3' where id='$user_id'";
                                //  $update_group = $conn->execute($update_group);
                            } else {
                                //$other_row_update_array['mem_plan_status'] = 0;
                                $start_date1 = date('Y-m-d', strtotime('-1 day', strtotime($start_date)));
                                $other_row_update_array['end_date'] = $start_date1;
                            }

                            $other_row_update = $this->MembershipPayment->get($other_row['mp_id']);
                            $other_row_update = $this->MembershipPayment->patchEntity($other_row_update, $other_row_update_array);
                            $this->MembershipPayment->save($other_row_update);
                            break;
                        } else if ($other_row['mem_plan_status'] == 1 && ( strtotime($other_row['end_date']) < strtotime($start_date) )) {
                            $row['mem_plan_status'] = 2;
                        } else if (strtotime($start_date) == strtotime(date('Y-m-d'))) {
                            $row['mem_plan_status'] = 1;
                        }
                    }
                } else {
                    $row['mem_plan_status'] = 1;
                    // $update_group ="update gym_member set assign_group='3' where id='$user_id'";
                    // $update_group = $conn->execute($update_group);
                }




                $row['start_date'] = $start_date;
                $row['end_date'] = $end_date;
                $this->MembershipPayment->save($row);

                //if record exist and payment not made, start date less than this record then disable that one and enable this one.
                //if record exist and payment made and end date less or equal to start date of this record then

                $hrow = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
                $data['mp_id'] = $mp_id;
                $data['amount'] = $this->request->data["amount"];
                $data['payment_method'] = $this->request->data["payment_method"];
                $data['paid_by_date'] = date("Y-m-d");
                $data['created_by'] = $session["id"];
                $data['transaction_id'] = "";

                $hrow = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($hrow, $data);
                $saveResult = $this->MembershipPayment->MembershipPaymentHistory->save($hrow);

                // Group Update here

                $stmt = $conn->execute("SELECT count(*) as active  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='" . $user_id . "' and membership_payment.mem_plan_status='1'  and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4'");
                $rows = $stmt->fetchAll('assoc');
                if (@$rows[0]['active'] > 0) {
                    $assign_group_val = 3;
                    // $update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$user_id."' ");
                } else {
                    $assign_group_val = 20;
                    ///$update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$user_id."' "); 
                }


                ######################## Auto assign group here ###############
                if (!empty($assign_group_val)) {
                    $select_group = $conn->execute("select assign_group from gym_member where id='$user_id'");
                    $grows = $select_group->fetchAll('assoc');
                    $assign_group_string = '';
                    if (!empty($grows)) {
                        $assign_group_array = explode(',', $grows[0]['assign_group']);
                        $match_arr = array('3', '4', '5', '6', '20');
                        foreach ($match_arr as $marr) {
                            if (($key = array_search($marr, $assign_group_array)) !== FALSE) {
                                unset($assign_group_array[$key]);
                            }
                        }
                        $assign_group_array[] = $assign_group_val;
                        foreach ($assign_group_array as $assign_group) {
                            $assign_group_string .= $assign_group;
                            $assign_group_string .= ',';
                        }
                        rtrim(trim($assign_group_string), ",");
                        $assign_group_string = substr(trim($assign_group_string), 0, -1);
                        $update = $conn->execute("update  gym_member set assign_group='$assign_group_string' where id='" . $user_id . "' ");
                    } else {
                        $update = $conn->execute("update  gym_member set assign_group='$assign_group_val' where id='" . $user_id . "' ");
                    }
                }
                ######################### End Here ##################################


                $mailArrUser = [
                    "template" => "payment_user_mail",
                    "subject" => "GoTribe : Payment Successfull",
                    "emailFormat" => "html",
                    "to" => $user_info->email,
                    "viewVars" => [
                        'name' => $user_info->first_name . ' ' . $user_info->last_name,
                        'membership' => $row['membership']['membership_label'],
                        'amount' => $this->request->data["amount"],
                        'method' => $this->request->data["payment_method"],
                        'payment_method' => $this->request->data["payment_method"],
                        'transaction_id' => $data['transaction_id'],
                        'payment_made_by' => $this->GYMFunction->get_user_name($session['id'])
                    ]
                ];
                $associated_licensee = $this->GYMFunction->get_user_detail($user_info->associated_licensee);
                $mailArrAdmin = [
                    "template" => "payment_admin_mail",
                    "subject" => "GoTribe : Payment successfull",
                    "emailFormat" => "html",
                    "to" => $associated_licensee['email'],
                    "viewVars" => [
                        'name' => $user_info->first_name . ' ' . $user_info->last_name,
                        'email' => $user_info->email,
                        'username' => $user_info->username,
                        'membership' => $row['membership']['membership_label'],
                        'adminName' => $associated_licensee['first_name'] . ' ' . $associated_licensee['last_name'],
                        'amount' => $this->request->data["amount"],
                        'method' => $this->request->data["payment_method"],
                        'transaction_id' => $data['transaction_id'],
                        'payment_made_by' => $this->GYMFunction->get_user_name($session['id'])
                    ]
                ];
                if ($this->GYMFunction->sendEmail($mailArrUser) && $this->GYMFunction->sendEmail($mailArrAdmin)) {
                    $this->Flash->success(__("Success! Payment Added Successfully."));
                }
            }
            // return $this->redirect(["action" => "paymentList"]);
            return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $user_id));
        }
    }

    public function generatePaymentInvoice() {
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $existingMembers = $this->MembershipPayment->find('all')
                ->select(['MembershipPayment.member_id'])
                ->where(['MembershipPayment.mem_plan_status !=' => 3])
                ->toArray();
        $existingMembersIdsArr = array("0");
        if (count($existingMembers) > 0) {
            foreach ($existingMembers as $existingMembersIds)
                $existingMembersIdsArr[] = $existingMembersIds['member_id'];
        }

        $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])
                ->where(["role_name" => "member", "activated" => 1, "GymMember.id NOT IN" => $existingMembersIdsArr]);
        $members = $members->select(["id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $this->set("members", $members);
        //echo $this->GYMFunction->pre($members);
        $memCats = $this->MembershipPayment->Category->find('all')->hydrate(false)->toArray();
        foreach ($memCats as $memCat) {
            $membership[$memCat['name']] = $this->MembershipPayment->Membership
                            ->find("list", ["keyField" => "id", "valueField" => "membership_label"])
                            ->where(['membership_cat_id' => $memCat['id']])->hydrate(false)->toArray();
        }
        //$membership = $this->MembershipPayment->Membership->find("list", ["keyField" => "id", "valueField" => "membership_label"])->contain(['Category']);

        $this->set("membership", $membership);

        if ($this->request->is('post')) {
            $mid = $this->request->data["user_id"];

            $exist_member = $this->MembershipPayment->findByMemberId($mid)
                    ->where(['mem_plan_status !=' => 3])
                    ->first();
            if (count($exist_member) > 0) {
                $this->Flash->error(__("Error! Member already subscribed a plan."));
                return $this->redirect(["action" => "paymentList"]);
            }

            $user_info = $this->MembershipPayment->GymMember->get($mid);

            $start_date = date("Y-m-d", strtotime($this->request->data["membership_valid_from"]));
            $end_date = date("Y-m-d", strtotime($this->request->data["membership_valid_to"]));
            $row = $this->MembershipPayment->newEntity();
            $pdata["member_id"] = $mid;
            $pdata["created_by"] = $session['id'];
            $pdata["membership_id"] = $this->request->data["membership_id"];
            $pdata["membership_amount"] = $this->request->data["membership_amount"];
            $pdata["paid_amount"] = 0;
            $pdata["start_date"] = $start_date;
            $pdata["end_date"] = $end_date;
            $pdata["membership_status"] = "Continue";
            $pdata["mem_plan_status"] = 0;
            /**
             * We will change the status at the time of recurring payment schedule 
             */
            $pdata["payment_status"] = 0;
            $row = $this->MembershipPayment->patchEntity($row, $pdata);
            $this->MembershipPayment->save($row);
            ################## MEMBER's Current Membership Change ##################
            $member_data = $this->MembershipPayment->GymMember->get($mid);
            $member_data_update['selected_membership'] = $this->request->data["membership_id"];
            $member_data_update['membership_valid_from'] = $start_date;
            $member_data_update['membership_valid_to'] = $end_date;
            $member_data = $this->MembershipPayment->GymMember->patchEntity($member_data, $member_data_update);
            $this->MembershipPayment->GymMember->save($member_data);
            #####################Add Membership History #############################
            $mem_histoty = $this->MembershipPayment->MembershipHistory->newEntity();
            $hdata["member_id"] = $mid;
            $hdata["selected_membership"] = $this->request->data["membership_id"];
            $hdata["membership_valid_from"] = $start_date;
            $hdata["membership_valid_to"] = $end_date;
            //$hdata["created_date"] = date("Y-m-d");
            $hdata = $this->MembershipPayment->MembershipHistory->patchEntity($mem_histoty, $hdata);
            if ($this->MembershipPayment->MembershipHistory->save($mem_histoty)) {

                //Membership plan info

                $membership_info = $this->MembershipPayment->Membership->findById($this->request->data["membership_id"])->first();

                $mailArrUser = [
                    "template" => "subscribe_membership_user_mail",
                    "subject" => "GoTribe : Subscribed Membership Successfull",
                    "emailFormat" => "html",
                    "to" => $user_info->email,
                    "viewVars" => [
                        'name' => $user_info->first_name . ' ' . $user_info->last_name,
                        'membership' => $membership_info['membership_label'],
                        'amount' => $membership_info["membership_amount"],
                        'validity' => date($this->GYMFunction->getSettings('date_format'), strtotime($start_date)) . " To " . date($this->GYMFunction->getSettings('date_format'), strtotime($end_date))
                    ]
                ];
                $associated_licensee = $this->GYMFunction->get_user_detail($user_info->associated_licensee);
                $mailArrAdmin = [
                    "template" => "subscribe_membership_admin_mail",
                    "subject" => "GoTribe : Subscribed Membership Successfull",
                    "emailFormat" => "html",
                    "to" => $associated_licensee['email'],
                    "viewVars" => [
                        'name' => $user_info->first_name . ' ' . $user_info->last_name,
                        'email' => $user_info->email,
                        'username' => $user_info->username,
                        'membership' => $membership_info['membership_label'],
                        'adminName' => $associated_licensee['first_name'] . ' ' . $associated_licensee['last_name'],
                        'amount' => $membership_info["membership_amount"],
                        'validity' => date($this->GYMFunction->getSettings('date_format'), strtotime($start_date)) . " To " . date($this->GYMFunction->getSettings('date_format'), strtotime($end_date))
                    ]
                ];
                if ($this->GYMFunction->sendEmail($mailArrUser) && $this->GYMFunction->sendEmail($mailArrAdmin)) {
                    $this->Flash->success(__("Success! Subscribed Membership Successfully."));
                    return $this->redirect(["action" => "paymentList"]);
                }
            }
        }
    }

    public function generatePaymentInvoices($mid = null) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if (empty($mid)) {
            $this->Flash->error(__("Sorry ! Please select member for subscription."));
            // return $this->redirect($this->referer());
            return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
        }
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        
        
        $location_id = $this->GYMFunction->getLocIdMemStaff($mid);
        if( $location_id){
            $stmt = $conn->execute("SELECT id FROM gym_member WHERE location_id = '".$location_id."'");
            $result = $stmt->fetchAll('assoc');
            foreach ($result as $v)
                $created_by_ids[] = $v['id'];
        }

        $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])
                ->where(["role_name" => "member", "activated" => 1, "GymMember.id" => $mid]);
        $members = $members->select(["id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();

        $licensee = $this->MembershipPayment->GymMember->find()->select(['associated_licensee'])->where(["role_name" => "member", "activated" => 1, "GymMember.id" => $mid])->hydrate(false)->toArray();
        $this->set("members", $members);
        $this->set("licensee", $licensee[0]['associated_licensee']);
        //$this->GYMFunction->pre($members);
        
        if (empty($members)) {//die('ljnn');
           // $this->Flash->error(__("Sorry ! Please select valid member for subscriptions."));
            // return $this->redirect($this->referer());
            return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
            
        }
        $memDetails = $this->GYMFunction->get_user_detail($mid);
        if($session['role_id'] != 1){
            if( $session['role_id'] == 2 && $session['created_role'] != 'administrator' && $session['original_id'] != $memDetails['assign_staff_mem'] && $memDetails['associated_licensee'] != $session['original_id']){
                $this->Flash->error(__("Error! No record found."));
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
            }else if($session['original_id'] != $memDetails['assign_staff_mem'] && !in_array($memDetails['associated_licensee'], $created_by_ids)){
                $this->Flash->error(__("Error! No record found."));
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
            }
        }

        $memCats = $this->MembershipPayment->Category->find('all')->hydrate(false)->toArray();

        foreach ($memCats as $memCat) {
            $membership[$memCat['name']] = $this->MembershipPayment->Membership
                            ->find("list", ["keyField" => "id", "valueField" => "membership_label"])
                            ->where([
                                'membership_cat_id' => $memCat['id'],
                                "is_deleted"=>"0",
                                'OR' => [['created_by_id IN'=>$created_by_ids], ['role_name'=>'administrator']],
                            ])
                            ->hydrate(false)->toArray();
        }
        foreach($membership as $k=>$membershipParts){
            foreach($membershipParts as $key=>$memship){
                if( $this->GYMFunction->membership_status_for_location_new($key, $mid) === false )
                    continue;
                $membershipLast[$k][$key] = $memship;
            }
        }
        //$this->GYMFunction->pre($membershipLast);
        //$membership = $this->MembershipPayment->Membership->find("list", ["keyField" => "id", "valueField" => "membership_label"])->contain(['Category']);
        $this->set("members_ids", $mid);
        $this->set("membership", $membershipLast);

        $card_info_tbl = TableRegistry::get("MemberCardInfo");
        $card_data = $card_info_tbl->find()->where(['member_id' => $mid])->hydrate(false)->toArray();
        $this->set("card_data", $card_data);


        if ($this->request->is('post')) {
            $start_date = date("Y-m-d", strtotime($this->request->data["membership_valid_from"]));
            $end_date = date("Y-m-d", strtotime($this->request->data["membership_valid_to"]));
            // echo $mid;
            // print_r($this->request->data);
            //echo $mid = $this->request->data["user_id"];
            // die;
            $membership_id = $this->request->data["membership_id"];

            /// Category Id
            $subscription = $this->MembershipPayment->Membership->findById($membership_id)->hydrate(false)->toArray();
            // $subscription = $this->MembershipPayment->find('all')->where(["Membership.id"=>$membership_id])->contain(['Membership'])->select(["Membership.membership_cat_id"])->hydrate(false)->toArray();
            //print_r($subscription);
            $membership_cat_id = @$subscription[0]['membership_cat_id'];
            // die;
            //$exist_member = $this->MembershipPayment->find()->where(["Membership.membership_cat_id"=>$membership_cat_id,'MembershipPayment.member_id'=>$mid])->contain(['Membership'])->select(["Membership.membership_cat_id"])->first();
            /* $exist_member = $this->MembershipPayment->findByMemberId($mid)
              ->where(['membership_id' =>$membership_id])
              ->first(); */
            if ($membership_cat_id == 4) {
                $check_member = $this->MembershipPayment->find()->where(['MembershipPayment.member_id' => $mid, 'MembershipPayment.payment_status' => '1', 'Membership.membership_cat_id' => 4])->contain(['Membership'])->hydrate(false)->toArray();
                if (!empty($check_member)) {
                    $this->Flash->error(__("Error! You have already subscribed trial plan. please select another plan"));
                    return $this->redirect(["action" => "generatePaymentInvoices/" . $mid]);
                }
            }

            $exist_member = $this->MembershipPayment->find()->where(["MembershipPayment.membership_id" => $membership_id, 'MembershipPayment.member_id' => $mid, 'MembershipPayment.payment_status' => '0'])->contain(['Membership'])->select(["Membership.membership_cat_id"])->first();
            if (count($exist_member) > 0) {


                $this->Flash->error(__("Error! Member already subscribed a plan. Please pay amount."));
                return $this->redirect(["action" => "generatePaymentInvoices/" . $mid]);
                //die("exist");
            }
            $exist_membership = $this->MembershipPayment->find()->where(["Membership.membership_cat_id" => $membership_cat_id, 'MembershipPayment.member_id' => $mid, 'MembershipPayment.mem_plan_status' => '1'])->contain(['Membership'])->select(["MembershipPayment.membership_id", "MembershipPayment.end_date"])->first();
            //echo "<pre>"; print_r($exist_membership);
          //  echo $exist_membership['membership_id'];
          // // echo $membership_id; die;
           // die;
            if (count($exist_membership) > 0) {
                //$check_membership=$exist_membership->toArray();
                //  echo "<pre>";print_r($exist_membership);
                // $start_date = date('Y-m-d');

                if ($membership_id <= $exist_membership['membership_id']) {

                    if (strtotime($exist_membership['end_date']) >= strtotime($start_date)) {
                        // $this->Flash->error(__("Error! Please select valid date range"));
                        //  return $this->redirect(["action" => "view-member/".$user_id]); 
                        //return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/'.$user_id));
                        $this->Flash->error(__("Currently your membership already activated you can not upgraded."));
                        return $this->redirect(["action" => "generatePaymentInvoices/" . $mid]);
                    }
                }
            }

            // die;
            $user_info = $this->MembershipPayment->GymMember->get($mid);

            ///

            $row = $this->MembershipPayment->newEntity();
            $pdata["member_id"] = $mid;
            $pdata["created_by"] = $session['id'];
            $pdata["membership_id"] = $this->request->data["membership_id"];
            $pdata["membership_amount"] = $this->request->data["membership_amount"];
            $pdata["paid_amount"] = 0;
            $pdata["start_date"] = $start_date;
            $pdata["end_date"] = $end_date;
            $pdata["membership_status"] = "Continue";
            $pdata["mem_plan_status"] = 0;
            /**
             * We will change the status at the time of recurring payment schedule 
             */
            $pdata["payment_status"] = 0;
            $row = $this->MembershipPayment->patchEntity($row, $pdata);
            $this->MembershipPayment->save($row);
            $mp_id = $row->mp_id;
            $this->request->data["mp_id"] = $mp_id;
            $this->request->data["user_id"] = $mid;
            
            if (strtotime($start_date) == strtotime(date('Y-m-d'))) {

                if ($this->request->data['payment_method'] == 'Card' && $this->request->data['card_info'] != '') {
                    require_once(ROOT . DS . 'vendor' . DS . 'braintree-php' . DS . 'braintree_admin_payment.php');
                    if ($OrderStatus == 1) {
                        $this->Flash->success(__("Success! Payment Added Successfully."));
                    } else {
                        $this->Flash->error(__("Error! Your Payment has beeen failed"));
                    }
                } else {
                    $order_id = rand();

                   
                    /** Update Record **/
                     $membership_table = TableRegistry::get("Membership");
                     $membership_info = $membership_table->find()->where(['membership_cat_id'=>$membership_cat_id])->select(['Membership.id'])->hydrate(false)->toArray();
                     $existingMembershipIdsArr=array(0); 
                     if(count($membership_info) > 0){
                     foreach($membership_info as $existingIds)
                      {
                       $existingMembershipIdsArr[] = $existingIds['id'];

                      }
                     }
                     
                    $membership_pay_table = TableRegistry::get("MembershipPayment");
                    $rowss = $membership_pay_table->findByMpId($mp_id)->first();
                    $other_rows = $membership_pay_table->find()->where(['member_id'=>$mid,'mp_id !='=>$mp_id, 'membership_id IN'=>$existingMembershipIdsArr]);
                    if(count($other_rows->toArray())>0){
                    foreach($other_rows->toArray() as $other_row){
                        
                        if( $other_row['mem_plan_status'] == 1 && ( strtotime( $other_row['end_date']) >= strtotime($start_date) )){
                            //$row['mem_plan_status'] = 1;
                            if(strtotime($start_date) == strtotime(date('Y-m-d')))
                            {
                            $other_row_update_array['mem_plan_status'] = 0;
                            $rowss['mem_plan_status'] = 1;
                            $start_date1=date('Y-m-d', strtotime('-1 day', strtotime($start_date)));
                            //$other_row_update_array['end_date'] = $start_date1;
                            }else{
                             //$other_row_update_array['mem_plan_status'] = 0;
                            // $start_date1=date('Y-m-d', strtotime('-1 day', strtotime($start_date)));
                            // $other_row_update_array['end_date'] = $start_date1;
                            }

                            $other_row_update = $membership_pay_table->get($other_row['mp_id']);
                            $other_row_update = $membership_pay_table->patchEntity($other_row_update, $other_row_update_array);
                            $membership_pay_table->save($other_row_update);
                            break;
                        }else if( $other_row['mem_plan_status'] == 1 && ( strtotime( $other_row['end_date']) < strtotime($start_date ) ) ){
                            $rowss['mem_plan_status'] = 2;
                        }
                        else if(strtotime($start_date) == strtotime(date('Y-m-d')))
                        {
                            $rowss['mem_plan_status'] = 1;
                        }
                    }
                }else{
                    $rowss['mem_plan_status'] = 1;
                }
                    
                  $conn->execute("update membership_payment set paid_amount='".$this->request->data["paid_amount_input"]."', discount_amount='".$this->request->data["discount_amount_input"]."',payment_status='1',mem_plan_status='1' where mp_id='$mp_id'");
                   
                    
                    /*****/
                    $hrow = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
                    $data['mp_id'] = $mp_id;
                    $data['amount'] = $this->request->data["paid_amount_input"];
                    $data['payment_method'] = $this->request->data["payment_method"];
                    $data['paid_by_date'] = date("Y-m-d");
                    $data['created_by'] = $session["id"];
                    $data['transaction_id'] = "";
                    $data['order_id'] =$order_id;

                    $hrow = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($hrow, $data);
                    $saveResult = $this->MembershipPayment->MembershipPaymentHistory->save($hrow);

                    // Group Update here

                    $stmt = $conn->execute("SELECT count(*) as active  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='" . $mid . "' and membership_payment.mem_plan_status='1'  and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4'");
                    $rows = $stmt->fetchAll('assoc');
                    if (@$rows[0]['active'] > 0) {
                        $assign_group_val = 3;
                        // $update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$user_id."' ");
                    } else {
                        $assign_group_val = 20;
                        ///$update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$user_id."' "); 
                    }


                    ######################## Auto assign group here ###############
                    if (!empty($assign_group_val)) {
                        $select_group = $conn->execute("select assign_group from gym_member where id='$mid'");
                        $grows = $select_group->fetchAll('assoc');
                        $assign_group_string = '';
                        if (!empty($grows)) {
                            $assign_group_array = explode(',', $grows[0]['assign_group']);
                            $match_arr = array('3', '4', '5', '6', '20');
                            foreach ($match_arr as $marr) {
                                if (($key = array_search($marr, $assign_group_array)) !== FALSE) {
                                    unset($assign_group_array[$key]);
                                }
                            }
                            $assign_group_array[] = $assign_group_val;
                            foreach ($assign_group_array as $assign_group) {
                                $assign_group_string .= $assign_group;
                                $assign_group_string .= ',';
                            }
                            rtrim(trim($assign_group_string), ",");
                            $assign_group_string = substr(trim($assign_group_string), 0, -1);
                            $update = $conn->execute("update  gym_member set assign_group='$assign_group_string' where id='" . $mid . "' ");
                        } else {
                            $update = $conn->execute("update  gym_member set assign_group='$assign_group_val' where id='" . $mid . "' ");
                        }
                    }
                    ######################### End Here ##################################
                     $this->Flash->success(__("Success! Payment Added Successfully."));
                }
                //  $this->Flash->success(__("Success! Subscribed Membership Successfully."));
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid));
            } else {
                 
                 if ($this->request->data['payment_method'] == 'Card' && $this->request->data['card_info'] != '') {
                    $conn->execute("update membership_payment set card_id='".$this->request->data["card_info"]."' where mp_id='$mp_id'");
          
                 }
                  $conn->execute("update membership_payment set auto_pay_amt='".$this->request->data["paid_amount_input"]."', discount_amount='".$this->request->data["discount_amount_input"]."' where mp_id='$mp_id'");
          
                 
                ################## MEMBER's Current Membership Change ##################
                $member_data = $this->MembershipPayment->GymMember->get($mid);
                $member_data_update['selected_membership'] = $this->request->data["membership_id"];
                $member_data_update['membership_valid_from'] = $start_date;
                $member_data_update['membership_valid_to'] = $end_date;
                $member_data = $this->MembershipPayment->GymMember->patchEntity($member_data, $member_data_update);
                $this->MembershipPayment->GymMember->save($member_data);
                #####################Add Membership History #############################
                $mem_histoty = $this->MembershipPayment->MembershipHistory->newEntity();
                $hdata["member_id"] = $mid;
                $hdata["selected_membership"] = $this->request->data["membership_id"];
                $hdata["membership_valid_from"] = $start_date;
                $hdata["membership_valid_to"] = $end_date;
                //$hdata["created_date"] = date("Y-m-d");

                $hdata = $this->MembershipPayment->MembershipHistory->patchEntity($mem_histoty, $hdata);
                if ($this->MembershipPayment->MembershipHistory->save($mem_histoty)) {



                    //Membership plan info

                    $membership_info = $this->MembershipPayment->Membership->findById($this->request->data["membership_id"])->first();

                    $mailArrUser = [
                        "template" => "subscribe_membership_user_mail",
                        "subject" => "GoTribe : Subscribed Membership Successfull",
                        "emailFormat" => "html",
                        "to" => $user_info->email,
                        "viewVars" => [
                            'name' => $user_info->first_name . ' ' . $user_info->last_name,
                            'membership' => $membership_info['membership_label'],
                            'amount' => $membership_info["membership_amount"],
                            'validity' => date($this->GYMFunction->getSettings('date_format'), strtotime($start_date)) . " To " . date($this->GYMFunction->getSettings('date_format'), strtotime($end_date))
                        ]
                    ];
                    if (!empty($user_info->associated_licensee)) {
                        $associated_licensee = $this->GYMFunction->get_user_detail($user_info->associated_licensee);
                        $mailArrAdmin = [
                            "template" => "subscribe_membership_admin_mail",
                            "subject" => "GoTribe : Subscribed Membership Successfull",
                            "emailFormat" => "html",
                            "to" => $associated_licensee['email'],
                            "viewVars" => [
                                'name' => $user_info->first_name . ' ' . $user_info->last_name,
                                'email' => $user_info->email,
                                'username' => $user_info->username,
                                'contact' => $user_info->mobile,
                                'membership' => $membership_info['membership_label'],
                                'adminName' => $associated_licensee['first_name'] . ' ' . $associated_licensee['last_name'],
                                'amount' => $membership_info["membership_amount"],
                                'validity' => date($this->GYMFunction->getSettings('date_format'), strtotime($start_date)) . " To " . date($this->GYMFunction->getSettings('date_format'), strtotime($end_date))
                            ]
                        ];
                        $this->GYMFunction->sendEmail($mailArrAdmin);
                    }
                    if ($this->GYMFunction->sendEmail($mailArrUser)) {
                        $this->Flash->success(__("Success! Subscribed Membership Successfully."));
                        //return $this->redirect(["action" => "paymentList"]);
                        return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid));
                    }
                }
            }
        }
    }

    ###################################################################################################

    public function membershipEdit($eid) {
        $session = $this->request->session()->read("User");
        $this->set("edit", true);
        $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])
                ->where(["role_name" => "member", "activated" => 1]);
        $members = $members->select(["id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $this->set("members", $members);
        $memCats = $this->MembershipPayment->Category->find('all')->hydrate(false)->toArray();
        foreach ($memCats as $memCat) {
            $membership[$memCat['name']] = $this->MembershipPayment->Membership
                            ->find("list", ["keyField" => "id", "valueField" => "membership_label"])
                            ->where(['membership_cat_id' => $memCat['id']])->hydrate(false)->toArray();
        }
        $this->set("membership", $membership);

        $data = $this->MembershipPayment->get($eid);
        $this_record = $data->toArray();
        //$this->GYMFunction->pre($this_record);
        $this->set("data", $this_record);

        if ($this->request->is('post')) {
            // echo "<pre>";
            $mp_id = $this->request->data['mp_id'];

            $mid = $this->request->data["user_id"];

            $row1 = $this->MembershipPayment->get($mp_id);
            $pdata["mem_plan_status"] = $this->request->data["mem_plan_status"];
            $pdata["payment_status"] = $this->request->data["payment_status"];
             $end_date = date("Y-m-d", strtotime($this->request->data["membership_valid_to"]));
            $pdata["end_date"] = $end_date;
            // print_r($pdata); die;
            $row2 = $this->MembershipPayment->patchEntity($row1, $pdata);
            //$this->MembershipPayment->save($row2);


            if ($this->MembershipPayment->save($row2)) {
                $conn = ConnectionManager::get('default');
                $stmt = $conn->execute("SELECT count(*) as active  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='" . $mid . "' and membership_payment.mem_plan_status='1'  and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4'");
                $rows = $stmt->fetchAll('assoc');
                $assign_group_val = '';
                if (@$rows[0]['active'] > 0) {
                    $assign_group_val = 3;
                    // $update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$mid."' ");
                } else {
                    $assign_group_val = 20;
                    // $update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$mid."' "); 
                }

                ######################## Auto assign group here ###############
                if (!empty($assign_group_val)) {
                    $select_group = $conn->execute("select assign_group from gym_member where id='$mid'");
                    $grows = $select_group->fetchAll('assoc');
                    $assign_group_string = '';
                    if (!empty($grows)) {
                        $assign_group_array = explode(',', $grows[0]['assign_group']);
                        $match_arr = array('3', '4', '5', '6', '20');
                        foreach ($match_arr as $marr) {
                            if (($key = array_search($marr, $assign_group_array)) !== FALSE) {
                                unset($assign_group_array[$key]);
                            }
                        }
                        $assign_group_array[] = $assign_group_val;
                        foreach ($assign_group_array as $assign_group) {
                            $assign_group_string .= $assign_group;
                            $assign_group_string .= ',';
                        }
                        rtrim(trim($assign_group_string), ",");
                        $assign_group_string = substr(trim($assign_group_string), 0, -1);
                        $update = $conn->execute("update  gym_member set assign_group='$assign_group_string' where id='" . $mid . "' ");
                    } else {
                        $update = $conn->execute("update  gym_member set assign_group='$assign_group_val' where id='" . $mid . "' ");
                    }
                }
                ######################### End Here ##################################

                $this->Flash->success(__("Success! Subscribed Membership Updated Successfully."));
                // return $this->redirect(["action" => "paymentList"]);
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid));
            }
        }
        $this->render("generatePaymentInvoice");
    }

    public function deletePayment($mp_id) {
        $row = $this->MembershipPayment->get($mp_id);
        if ($this->MembershipPayment->delete($row)) {
            $this->Flash->success(__("Success! Payment Record Deleted Successfully."));
            return $this->redirect(["action" => "paymentList"]);
        }
    }

    public function pdfView($ftype = '0', $mp_id) {

        $payment_tbl = TableRegistry::get("MembershipPayment");
        $setting_tbl = TableRegistry::get("GeneralSetting");
        $pay_history_tbl = TableRegistry::get("MembershipPaymentHistory");

        $sys_data = $setting_tbl->find()->select(["name", "address", "gym_logo", "date_format", "office_number", "country"])->hydrate(false)->toArray();
        $sys_data[0]["gym_logo"] = (!empty($sys_data[0]["gym_logo"])) ? $this->request->base . "/webroot/upload/" . $sys_data[0]["gym_logo"] : $this->request->base . "/webroot/img/Thumbnail-img.png";
        $data = $payment_tbl->find("all")->contain(["GymMember", "Membership"])->where(["mp_id" => $mp_id])->hydrate(false)->toArray();
        $history_data = $pay_history_tbl->find("all")->where(["mp_id" => $mp_id])->hydrate(false)->toArray();
        //$this->GYMFunction->pre($data); die;
        $session = $this->request->session();
        $float_l = ($session->read("User.is_rtl") == "1") ? "right" : "left";
        $float_r = ($session->read("User.is_rtl") == "1") ? "left" : "right";
        $float_l = "left";
        $float_r = "right";

        $this->set('ftype', $ftype);
        $this->set('title', 'Member Invoice');
        $this->viewBuilder()->layout('pdf/pdf');
        $this->set("float_r", $float_r);
        $this->set("float_l", $float_l);
        $this->set("sys_data", $sys_data);
        //$this->GYMFunction->pre($data);
        $this->set("data", $data);
        $this->set("history_data", $history_data);
        $this->set('mp_id', $mp_id);
        $this->viewBuilder()->template('pdf/invoice');
        $this->set('filename', date('Y-m-d') . '_invoice.pdf');
        $this->response->type('pdf');
    }

    /*
      public function membershipUnsubscribe($mp_id) {

      $row = $this->MembershipPayment->get($mp_id);
      $row_update_array['unsubscribed'] = 1;
      $row = $this->MembershipPayment->patchEntity($row, $row_update_array);
      if ($this->MembershipPayment->save($row)) {
      $this->Flash->success(__("Success! Plan unsubscribed."));
      return $this->redirect(["action" => "paymentList"]);
      }
      } */
     public function cardList() {
        $card_info_tbl = TableRegistry::get("MemberCardInfo");
        $card_data = $card_info_tbl->find()->where(['member_id' => $mid])->hydrate(false)->toArray();
        $this->set("card_data", $card_data);
    }
    public function addCard($mid,$flag=null)
    {
       //  echo $flag; die; // $data['member_id']=$mid;
        //require_once(ROOT . DS . 'vendor' . DS . 'braintree-php' . DS . 'braintree_environment_refund.php');
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        if (empty($mid) || !is_numeric($mid)) {
            $this->Flash->error(__("Error! No record found."));
            return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
        }
        $location_id = $session['location_id'];
        if( $location_id){
            $stmt = $conn->execute("SELECT id FROM gym_member WHERE location_id = '".$location_id."'");
            $result = $stmt->fetchAll('assoc');
            foreach ($result as $v)
                $created_by_ids[] = $v['id'];
        }
        
        $memDetails = $this->GYMFunction->get_user_detail($mid);
        if($session['role_id'] != 1){
            if( $session['role_id'] == 2 && $session['created_role'] != 'administrator' && $session['original_id'] != $memDetails['assign_staff_mem'] && $memDetails['associated_licensee'] != $session['original_id']){
                $this->Flash->error(__("Error! No record found."));
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
            }else if($session['original_id'] != $memDetails['assign_staff_mem'] && !in_array($memDetails['associated_licensee'], $created_by_ids)){
                $this->Flash->error(__("Error! No record found."));
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
            }
        }
          $this->set("member_id", $mid);
          $this->set("flag", $flag);
         if ($this->request->is("post")) {
            $this->request->data["user_id"] = $mid;
            require_once(ROOT . DS . 'vendor' . DS . 'braintree-php' . DS . 'save_card.php');
            if ($OrderStatus == 1) {
                $this->Flash->success(__("Success! Card Added Successfully."));
            } else {
                $this->Flash->error(__("Error! Failed to save your card details."));
            }
            //return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid));
            if($flag==1)
            {
                return $this->redirect(array('controller' => 'Licensee', 'action' => 'viewlicensee/' . $mid)); 
            }
            else if($flag==2)
            {
                return $this->redirect(array('controller' => 'GymProfile', 'action' => 'viewProfile')); 
            }else{
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid)); 
            }
        }
    }
    public function deleteCard($id,$mid,$flag=null)
    {
       // $data['member_id']=$mid;
           $this->autoRender = false;
           $session = $this->request->session()->read("User");
            $row = $this->MembershipPayment->GymMember->get($mid);
            $row1 = $row->toArray();

              if($session['role_id'] == 2){
                  if($row1['associated_licensee'] != $session['id']){
                      $this->Flash->error(__("Error! You Do Not Have Sufficient Permissions to Delete This Record."));
                      return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid));
                  }
              }
             /* if($session['role_id'] == 6 || $session['role_id'] == 7 || $session['role_id'] == 8){
                  if($row1['created_by'] != $session['id']){
                      $this->Flash->error(__("Error! You Do Not Have Sufficient Permissions to Delete This Record."));
                     return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid));
                  }
              }*/
              if(!empty($id) && !empty($mid))
              {
                     $conn = ConnectionManager::get('default');
                     $stmt = $conn->execute("delete from member_card_info where id='$id'");
                     $this->Flash->success(__("Success! Record Deleted Successfully."));
                     if($flag==1)
                     {
                         return $this->redirect(array('controller' => 'Licensee', 'action' => 'viewlicensee/' . $mid)); 
                     }
                     else if($flag==2)
                     {
                         return $this->redirect(array('controller' => 'GymProfile', 'action' => 'viewProfile')); 
                     }else{
                         return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid)); 
                     }
                     
              }
         
    }
    public function incomeList() {
        $data = $this->MembershipPayment->GymIncomeExpense->find("all")->contain(["GymMember"])->where(["invoice_type" => "income"])->hydrate(false)->toArray();
        $this->set("data", $data);
    }

    public function addIncome() {
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])->where(["role_name" => "member"]);
        $members = $members->select(["id", "name" => $members->func()->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $this->set("members", $members);

        if ($this->request->is("post")) {
            $row = $this->MembershipPayment->GymIncomeExpense->newEntity();
            $data = $this->request->data;
            $total_amount = null;
            foreach ($data["income_amount"] as $amount) {
                $total_amount += $amount;
            }
            $data["total_amount"] = $total_amount;
            $data["entry"] = $this->get_entry_records($data);
            $data["receiver_id"] = $session["id"]; //current userid;			
            $data["invoice_date"] = date("Y-m-d", strtotime($data["invoice_date"]));
            $row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row, $data);
            if ($this->MembershipPayment->GymIncomeExpense->save($row)) {
                $this->Flash->success(__("Success! Record Saved Successfully."));
                return $this->redirect(["action" => "incomeList"]);
            }
        }
    }

    public function get_entry_records($data) {
        $all_income_entry = $data['income_entry'];
        $all_income_amount = $data['income_amount'];

        $entry_data = array();
        $i = 0;
        foreach ($all_income_entry as $one_entry) {
            $entry_data[] = array('entry' => $one_entry,
                'amount' => $all_income_amount[$i]);
            $i++;
        }
        return json_encode($entry_data);
    }

    public function incomeEdit($eid) {
        $this->set("edit", true);
        $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])->where(["role_name" => "member"]);
        $members = $members->select(["id", "name" => $members->func()->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();
        $this->set("members", $members);

        $row = $this->MembershipPayment->GymIncomeExpense->get($eid);
        $this->set("data", $row->toArray());

        if ($this->request->is("post")) {
            $data = $this->request->data;
            $total_amount = null;
            foreach ($data["income_amount"] as $amount) {
                $total_amount += $amount;
            }
            $data["total_amount"] = $total_amount;
            $data["entry"] = $this->get_entry_records($data);
            $data["invoice_date"] = date("Y-m-d", strtotime($data["invoice_date"]));

            $row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row, $data);
            if ($this->MembershipPayment->GymIncomeExpense->save($row)) {
                $this->Flash->success(__("Success! Record Updated Successfully."));
                return $this->redirect(["action" => "incomeList"]);
            }
        }
        $this->render("addIncome");
    }

    public function deleteIncome($did) {
        $row = $this->MembershipPayment->GymIncomeExpense->get($did);
        if ($this->MembershipPayment->GymIncomeExpense->delete($row)) {
            $this->Flash->success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }

    public function printInvoice() {
        $id = $this->request->params["pass"][0];
        $invoice_type = $this->request->params["pass"][1];
        $in_ex_table = TableRegistry::get("GymIncomeExpense");
        $setting_tbl = TableRegistry::get("GeneralSetting");
        $income_data = array();
        $expense_data = array();
        $invoice_data = array();

        $sys_data = $setting_tbl->find()->select(["name", "address", "gym_logo", "date_format", "office_number", "country"])->hydrate(false)->toArray();

        if ($invoice_type == "income") {
            $income_data = $this->MembershipPayment->GymIncomeExpense->find("all")->contain(["GymMember"])->where(["GymIncomeExpense.id" => $id])->hydrate(false)->toArray();
            $this->set("income_data", $income_data[0]);
            $this->set("expense_data", $expense_data);
            $this->set("invoice_data", $invoice_data);
        } else if ($invoice_type == "expense") {
            $expense_data = $this->MembershipPayment->GymIncomeExpense->find("all")->where(["GymIncomeExpense.id" => $id])->select($this->MembershipPayment->GymIncomeExpense);
            $expense_data = $expense_data->leftjoin(["GymMember" => "gym_member"], ["GymIncomeExpense.receiver_id = GymMember.id"])->select($this->MembershipPayment->GymMember)->hydrate(false)->toArray();
            $expense_data[0]["gym_member"] = $expense_data[0]["GymMember"];
            unset($expense_data[0]["GymMember"]);
            $this->set("income_data", $income_data);
            $this->set("expense_data", $expense_data[0]);
            $this->set("invoice_data", $invoice_data);
        }

        $this->set("sys_data", $sys_data[0]);
    }

    public function expenseList() {
        $data = $this->MembershipPayment->GymIncomeExpense->find("all")->where(["invoice_type" => "expense"])->hydrate(false)->toArray();
        $this->set("data", $data);
    }

    public function addExpense() {
        $this->set("edit", false);
        $session = $this->request->session()->read("User");

        if ($this->request->is("post")) {
            $row = $this->MembershipPayment->GymIncomeExpense->newEntity();
            $data = $this->request->data;
            $total_amount = null;
            foreach ($data["income_amount"] as $amount) {
                $total_amount += $amount;
            }
            $data["total_amount"] = $total_amount;
            $data["entry"] = $this->get_entry_records($data);
            $data["receiver_id"] = $session["id"]; //current userid;			
            $data["invoice_date"] = date("Y-m-d", strtotime($data["invoice_date"]));
            $row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row, $data);
            if ($this->MembershipPayment->GymIncomeExpense->save($row)) {
                $this->Flash->success(__("Success! Record Saved Successfully."));
                return $this->redirect(["action" => "expenseList"]);
            }
        }
    }

    public function expenseEdit($eid) {
        $this->set("edit", true);

        $row = $this->MembershipPayment->GymIncomeExpense->get($eid);
        $this->set("data", $row->toArray());

        if ($this->request->is("post")) {
            $data = $this->request->data;
            $total_amount = null;
            foreach ($data["income_amount"] as $amount) {
                $total_amount += $amount;
            }
            $data["total_amount"] = $total_amount;
            $data["entry"] = $this->get_entry_records($data);
            $data["invoice_date"] = date("Y-m-d", strtotime($data["invoice_date"]));

            $row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row, $data);
            if ($this->MembershipPayment->GymIncomeExpense->save($row)) {
                $this->Flash->success(__("Success! Record Updated Successfully."));
                return $this->redirect(["action" => "expenseList"]);
            }
        }
        $this->render("addExpense");
    }

    public function deleteAccountant($id) {
        $row = $this->GymAccountant->GymMember->get($id);
        if ($this->GymAccountant->GymMember->delete($row)) {
            $this->Flash->success(__("Success! Accountant Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }

    public function paymentSuccess() {
        $payment_data = $this->request->session()->read("Payment");
        $session = $this->request->session()->read("User");
        $feedata['mp_id'] = $payment_data["mp_id"];
        $feedata['amount'] = $payment_data['amount'];
        $feedata['payment_method'] = 'Paypal';
        $feedata['paid_by_date'] = date("Y-m-d");
        $feedata['created_by'] = $session["id"];
        $row = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
        $row = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($row, $feedata);
        if ($this->MembershipPayment->MembershipPaymentHistory->save($row)) {
            $row = $this->MembershipPayment->get($payment_data["mp_id"]);
            $row->paid_amount = $row->paid_amount + $payment_data['amount'];
            $this->MembershipPayment->save($row);
        }

        $session = $this->request->session();
        $session->delete('Payment');

        $this->Flash->success(__("Success! Payment Successfully Completed."));
        return $this->redirect(["action" => "paymentList"]);
    }

    public function ipnFunction() {
        if ($this->request->is("post")) {
            $trasaction_id = $_POST["txn_id"];
            $custom_array = explode("_", $_POST['custom']);
            $feedata['mp_id'] = $custom_array[1];
            $feedata['amount'] = $_POST['mc_gross_1'];
            $feedata['payment_method'] = 'Paypal';
            $feedata['trasaction_id'] = $trasaction_id;
            $feedata['created_by'] = $custom_array[0];
            //$log_array		= print_r($feedata, TRUE);
            //wp_mail( 'bhaskar@dasinfomedia.com', 'gympaypal', $log_array);
            $row = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
            $row = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($row, $feedata);
            if ($this->MembershipPayment->MembershipPaymentHistory->save($row)) {
                $this->Flash->success(__("Success! Payment Successfully Completed."));
            } else {
                $this->Flash->error(__("Paypal Payment IPN save failed to DB."));
            }
            return $this->redirect(["action" => "paymentList"]);
            //require_once SMS_PLUGIN_DIR. '/lib/paypal/paypal_ipn.php';
        }
    }

    ####  Upgrade membership by BrainTree Payment Gateway ####

    public function membershipUpgrade($membership_id = null) {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $mid = $session['id'];
        if (!empty($membership_id)) {
            $rows = $this->MembershipPayment->Membership->get($membership_id);
            $mydata = $rows->toArray();
            if ($mydata['membership_cat_id'] == 4) {
                $exist_member = $this->MembershipPayment->find()->where(["MembershipPayment.mem_plan_status >" => 0, 'MembershipPayment.member_id' => $mid, 'MembershipPayment.payment_status' => '1', 'Membership.membership_cat_id' => 4])->contain(['Membership'])->hydrate(false)->toArray();
                if (!empty($exist_member)) {
                    $this->Flash->error(__("Error! You have already subscribed trial plan. please select another plan"));
                    return $this->redirect(array('controller' => 'Membership', 'action' => 'membershipList'));
                } else {
                    if ($mydata['membership_amount'] == 0) {
                        $start_date = date("Y-m-d");
                        $period = $mydata["membership_length"];
                        $end_date1 = date('Y-m-d', strtotime($start_date . " + {$period} month"));
                        $end_date = date('Y-m-d', strtotime("-1 days", strtotime($end_date1)));
                        $row = $this->MembershipPayment->newEntity();
                        $pdata["member_id"] = $mid;
                        $pdata["created_by"] = $session['id'];
                        $pdata["paid_by"] = $session['id'];
                        $pdata["membership_id"] = $membership_id;
                        $pdata["membership_amount"] = $mydata['membership_amount'];
                        $pdata["paid_amount"] = $mydata['membership_amount'];
                        $pdata["discount_amount"] = 0;
                        $pdata["start_date"] = $start_date;
                        $pdata["end_date"] = $end_date;
                        $pdata["membership_status"] = "Continue";
                        $pdata["mem_plan_status"] = '1';
                        $pdata["payment_status"] = 1;
                        $pdata["created_date"] = date('Y-m-d h:i:s');
                        $row = $this->MembershipPayment->patchEntity($row, $pdata);
                        $this->MembershipPayment->save($row);
                        $mp_id = $row->mp_id;

                        #####################Add Membership History #############################
                        $hrow = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
                        $data['mp_id'] = $mp_id;
                        $data['amount'] = $mydata['membership_amount'];
                        $data['payment_method'] = 'Online';
                        $data['paid_by_date'] = date("Y-m-d");
                        $data['created_by'] = $session["id"];
                        $data['transaction_id'] = "";
                        $data['order_id'] = strtotime(date('Y-m-d h:i:s'));
                        $hrow = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($hrow, $data);

                        if ($this->MembershipPayment->MembershipPaymentHistory->save($hrow)) {

                            //Membership plan info
                            $user_info = $this->MembershipPayment->GymMember->get($mid);
                            $membership_info = $this->MembershipPayment->Membership->findById($membership_id)->first();

                            $mailArrUser = [
                                "template" => "subscribe_membership_user_mail",
                                "subject" => "GoTribe : Subscribed Membership Successfull",
                                "emailFormat" => "html",
                                "to" => $user_info->email,
                                "viewVars" => [
                                    'name' => $user_info->first_name . ' ' . $user_info->last_name,
                                    'membership' => $membership_info['membership_label'],
                                    'amount' => $membership_info["membership_amount"],
                                    'validity' => date($this->GYMFunction->getSettings('date_format'), strtotime($start_date)) . " To " . date($this->GYMFunction->getSettings('date_format'), strtotime($end_date))
                                ]
                            ];
                            if (!empty($user_info->associated_licensee)) {
                                $associated_licensee = $this->GYMFunction->get_user_detail($user_info->associated_licensee);
                                $mailArrAdmin = [
                                    "template" => "subscribe_membership_admin_mail",
                                    "subject" => "GoTribe : Subscribed Membership Successfull",
                                    "emailFormat" => "html",
                                    "to" => $associated_licensee['email'],
                                    "viewVars" => [
                                        'name' => $user_info->first_name . ' ' . $user_info->last_name,
                                        'email' => $user_info->email,
                                        'username' => $user_info->username,
                                        'contact' => $user_info->mobile,
                                        'membership' => $membership_info['membership_label'],
                                        'adminName' => $associated_licensee['first_name'] . ' ' . $associated_licensee['last_name'],
                                        'amount' => $membership_info["membership_amount"],
                                        'validity' => date($this->GYMFunction->getSettings('date_format'), strtotime($start_date)) . " To " . date($this->GYMFunction->getSettings('date_format'), strtotime($end_date))
                                    ]
                                ];
                                $this->GYMFunction->sendEmail($mailArrAdmin);
                            }
                            if ($this->GYMFunction->sendEmail($mailArrUser)) {
                                $this->Flash->success(__("Success! Subscribed Membership Successfully."));
                                //return $this->redirect(["action" => "paymentList"]);
                                return $this->redirect(array('controller' => 'Membership', 'action' => 'membershipList'));
                            }
                        }
                        ///
                        $assign_group_val = '';
                        $stmt = $conn->execute("SELECT count(*) as active  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='" . $user_id . "' and membership_payment.mem_plan_status='1'  and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4'");
                        $rows = $stmt->fetchAll('assoc');
                        if (@$rows[0]['active'] > 0) {
                            $assign_group_val = 3;
                            // $update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$mid."' ");
                        } else {
                            $assign_group_val = 20;
                            //$update = $conn->execute("update  gym_member set assign_group='$assign_group' where id='".$mid."' "); 
                        }

                        ######################## Auto assign group here ###############
                        if (!empty($assign_group_val)) {
                            $select_group = $conn->execute("select assign_group from gym_member where id='$mid'");
                            $grows = $select_group->fetchAll('assoc');
                            $assign_group_string = '';
                            if (!empty($grows)) {
                                $assign_group_array = explode(',', $grows[0]['assign_group']);
                                $match_arr = array('3', '4', '5', '6', '20');
                                foreach ($match_arr as $marr) {
                                    if (($key = array_search($marr, $assign_group_array)) !== FALSE) {
                                        unset($assign_group_array[$key]);
                                    }
                                }
                                $assign_group_array[] = $assign_group_val;
                                foreach ($assign_group_array as $assign_group) {
                                    $assign_group_string .= $assign_group;
                                    $assign_group_string .= ',';
                                }
                                rtrim(trim($assign_group_string), ",");
                                $assign_group_string = substr(trim($assign_group_string), 0, -1);
                                $update = $conn->execute("update  gym_member set assign_group='$assign_group_string' where id='" . $mid . "' ");
                            } else {
                                $update = $conn->execute("update  gym_member set assign_group='$assign_group_val' where id='" . $mid . "' ");
                            }
                        }
                        ######################### End Here ##################################
                    }
                }
            }
            $this->set("data", $mydata);
            $card_info_tbl = TableRegistry::get("MemberCardInfo");
            $card_data = $card_info_tbl->find()->where(['member_id' => $mid])->hydrate(false)->toArray();
            $this->set("card_data", $card_data);
            
            $discount_info_tbl = TableRegistry::get("UserMembershipDiscount");
            $mem_discount_data = $discount_info_tbl->find()->where(['user_id' => $mid,'membership_id'=>$membership_id])->hydrate(false)->toArray();
            $this->set("mem_discount_data", @$mem_discount_data[0]);
            
        } else {
            $this->Flash->error(__("Please select member subscription plan."));
            // return $this->redirect(["action" => "membershipList"]);
            return $this->redirect(array('controller' => 'Membership', 'action' => 'membershipList'));
        }

        $licensee = $this->MembershipPayment->GymMember->find()->select(['associated_licensee'])->where(["role_name" => "member", "activated" => 1, "GymMember.id" => $mid])->hydrate(false)->toArray();
        // $this->set("member_id", $mid);
        $this->set("licensee", $licensee[0]['associated_licensee']);
    }

    public function refundPayment($mp_id = 0, $userID = 0) {
        if (empty($mp_id) && empty($userID)) {
            $this->Flash->error(__("Please select member subscription plan for refund."));
            return $this->redirect(["action" => "refundPaymentList"]);
        }

        $refund = $this->MembershipPayment->MembershipPaymentHistory->find('all')->where(["mp_id" => $mp_id])->hydrate(false)->toArray();
        $this->set("payment_method", $refund[0]['payment_method']);
        $this->set("members_ids", $userID);
        $session = $this->request->session()->read("User");
       // echo "<pre>";print_r($session); die;
        $uid = intval($session["id"]);
        if ($session["role_name"] == "administrator") {
            $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])
                    ->where(["role_name" => "member", "id" => $userID]);
        }
        if ($session["role_name"] == "licensee" || $session["role_name"] == "manager") {
            $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])
                    ->where(["role_name" => "member", "GymMember.associated_licensee" => $uid, "GymMember.id" => $userID]);
        }
        if ($session["role_name"] == "admin" || $session["role_name"] == "subadmin") {
              $loc =$session['location_id'];
              $associated_licensees_res = $this->MembershipPayment->GymMember->find("all")->select(['id'])->where(["role_id NOT IN" => array(1,3,4), "location_id" => $loc])->hydrate(false)->toArray();
              foreach($associated_licensees_res as $associated_licensee){
                 $associated_licensees[] = $associated_licensee['id'];
                }
          $members = $this->MembershipPayment->GymMember->find("list", ["keyField" => "id", "valueField" => "name"])
                    ->where(["role_name" => "member", "GymMember.associated_licensee IN" => $associated_licensees]);
        }
        $members = $members->select(["id", "name" => $members->func()
                            ->concat(["first_name" => "literal", " ", "last_name" => "literal", " (", "member_id" => "literal", ")"])])->hydrate(false)->toArray();
        $this->set("members", $members);


        if ($this->request->is("post")) {
            $row = $this->MembershipPayment->RefundPaymentHistory->newEntity();
            if ($refund[0]['payment_method'] == 'Cash') {
                $data["gym_member_id"] = $this->request->data['gym_member_id'];
                $data["amount"] = $this->request->data['amount'];
                $data["created_by"] = $session["id"]; //current userid;			
                $data["created_date"] = date("Y-m-d");
                $data["status"] = 1;
                $data["comments"] = $this->request->data['comments'];
                $data["refund_type"] = 'Cash';

                $row = $this->MembershipPayment->RefundPaymentHistory->patchEntity($row, $data);
                if ($this->MembershipPayment->RefundPaymentHistory->save($row)) {
                    $this->Flash->success(__("Success! Record Saved Successfully."));
                    return $this->redirect(["action" => "refundPaymentList"]);
                }
            } else {

                require_once(ROOT . DS . 'vendor' . DS . 'braintree-php' . DS . 'braintree_environment_refund.php');
                return $this->redirect(["action" => "refundPaymentList"]);
            }
        }
    }

    public function RefundPaymentList() {

        $session = $this->request->session()->read("User");
        $ref_pay_history_tbl = TableRegistry::get("RefundPaymentHistory");
        if ($session["role_name"] == "administrator") {
            /* $data = $this->RefundPaymentHistory->find("all")
              ->contain(['GymMember','RefundPaymentHistory'])
              ->hydrate(false)->toArray(); */
            $data = $this->MembershipPayment->RefundPaymentHistory->find("all")->contain(["GymMember"])->hydrate(false)->toArray();
        }
        if ($session["role_name"] == "licensee" || $session["role_name"] == "manager") {
            $uid = intval($session["id"]);
            $data = $this->MembershipPayment->RefundPaymentHistory->find("all")
                            ->contain(['GymMember'])
                            ->where(["GymMember.associated_licensee" => $uid, "GymMember.role_name" => "member"])
                            ->hydrate(false)->toArray();
        }
        if ($session["role_name"] == "subadmin" || $session["role_name"] == "admin") {
            $licensee_list = $this->MembershipPayment->GymMember->find()->where(["role_name" => 'licensee', "created_by" => $session['id']])->orwhere(["id" => $session['id']])->hydrate(false)->toArray();
            if (!empty($licensee_list)) {
                foreach ($licensee_list as $licensee) {
                    $assign_licensee[] = $licensee["id"];
                }
            }

            if (!empty($assign_licensee)) {
                /* $member_list =$this->MembershipPayment->GymMember->find()->where(["role_name" => 'member',"associated_licensee IN"=>$assign_licensee])->group(["id"])->hydrate(false)->toArray();
                  if (!empty($member_list)) {
                  foreach ($member_list as $member) {
                  $assign_member[] = $member["id"];
                  }
                  } */
                $data = $this->MembershipPayment->RefundPaymentHistory->find("all")
                                ->contain(['GymMember'])
                                ->where(["GymMember.associated_licensee IN" => $assign_licensee, "GymMember.role_name" => "member"])
                                ->hydrate(false)->toArray();
            }
        }

        $this->set("data", @$data);
    }

    ####  Refund Payment by BrainTree Payment Gateway ####
    
    ### RUN Transaction Function here 
    
    public function runTransaction($pid,$mid)
    {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        if (empty($mid) || !is_numeric($mid)) {
            $this->Flash->error(__("Error! No record found."));
            return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
        }
        if (empty($pid) || !is_numeric($pid)) {
            $this->Flash->error(__("Error! No record found."));
            return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
        }
        $location_id = $session['location_id'];
        if( $location_id){
            $stmt = $conn->execute("SELECT id FROM gym_member WHERE location_id = '".$location_id."'");
            $result = $stmt->fetchAll('assoc');
            foreach ($result as $v)
                $created_by_ids[] = $v['id'];
        }
        
        $memDetails = $this->GYMFunction->get_user_detail($mid);
        if($session['role_id'] != 1){
            if( $session['role_id'] == 2 && $session['created_role'] != 'administrator' && $session['original_id'] != $memDetails['assign_staff_mem'] && $memDetails['associated_licensee'] != $session['original_id']){
                $this->Flash->error(__("Error! No record found."));
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
            }else if($session['original_id'] != $memDetails['assign_staff_mem'] && !in_array($memDetails['associated_licensee'], $created_by_ids)){
                $this->Flash->error(__("Error! No record found."));
                return $this->redirect(array('controller' => 'GymMember', 'action' => 'memberList'));
            }
        }
        $myarray=array("member_id"=>$mid,"pid"=>$pid);
       // $this->set("member_id", $mid);
       // $this->set("pid", $pid);
        if(!empty($mid) && !empty($pid))
        {
           require_once(ROOT . DS . 'vendor' . DS . 'braintree-php' . DS . 'braintree_run_transaction.php');
           echo $OrderStatus;
           if($OrderStatus==3){
                 $this->Flash->error(__("Error! You have already subscribed plan. please select another plan"));
           } else if($OrderStatus==2 || $OrderStatus==0){
                 $this->Flash->error(__("Error! You payment has been failed. please try again"));
           } else {
                 $this->Flash->success(__("Success! Subscribed Membership Updated Successfully."));
           }
        }
        return $this->redirect(array('controller' => 'GymMember', 'action' => 'viewMember/' . $mid)); 
         // $this->set("member_id", $mid);
         // $this->set("pid", $pid);
    }

    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
