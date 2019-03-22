<?php

namespace App\Controller;

use Cake\App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use GoogleCharts;

class ReportsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent("PHMFunction");
       require_once(ROOT . DS . 'vendor' . DS . 'phpexcel' . DS . 'PHPExcel.php');
    }
     /**
     * View member Report
     * @Method memberReport
     * @Date 03 Oct 2017
     * @Author RNF Technologies  
     */
   public function memberReport() {
        $conn = ConnectionManager::get('default');
        /** Remove comma from table **/
        $conn->execute("UPDATE pheramor_user_hash_tags SET hash_tags = TRIM(BOTH ',' FROM hash_tags)");
        $conn->execute("UPDATE pheramor_user_hash_tags SET twitter_hashtags = TRIM(BOTH ',' FROM twitter_hashtags)");
        $conn->execute("UPDATE pheramor_user_interest SET fb_interest_id = TRIM(BOTH ',' FROM fb_interest_id)");
        $conn->execute("UPDATE pheramor_user_interest SET interest_id = TRIM(BOTH ',' FROM interest_id)");
        
        /*** End Here ***/
        
        
        $session = $this->request->session()->read("User");
        if ($this->request->is("post")) {
            $where = $string = $string1 = "";
            $startdate = $this->request->data['startdate'];
            $startdate1 = strtotime($this->request->data['startdate']);
            $enddate = $this->request->data['enddate'];
            $enddate1 = strtotime($this->request->data['enddate']);
            $status=$this->request->data['member_status'];
            $gender=$this->request->data['member_gender'];
            if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", strtotime($this->request->data["startdate"]));
                $to = date("Y-m-d", strtotime($this->request->data["enddate"]));
                //$where .=" AND (pu.created_date >= '$from' AND pu.created_date <= '$to')";
                $where .=" AND (DATE_FORMAT(pu.created_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(pu.created_date,'%Y-%m-%d') <= '$to')";
            }
            if($status!='all')
            {
                if($status==3){
                  //  $where .=" AND pu.activated = '$status' ";
                    $where .=" AND pu.is_deleted > '0' ";
                }else{
                    $where .=" AND pu.activated = '$status' ";
                    $where .=" AND pu.is_deleted='0' ";
                }
              
            }
            if($gender!='all')
            {
                 $where .=" AND pup.gender = '$gender' ";
            }
             $search_query = "SELECT * from pheramor_user pu INNER JOIN pheramor_user_profile pup on pu.id=pup.user_id where pu.role_name = 'member'  $where  order by pup.first_name";
        } else {
              $startdate1 = 0;
              $enddate1 = 0;
              $status='all';
              $gender='all';
              $search_query = "SELECT * from pheramor_user pu INNER JOIN pheramor_user_profile pup on pu.id=pup.user_id where pu.role_name = 'member' order by pup.first_name";
 
            }

        $search_query = $conn->execute($search_query);
        $search_query = $search_query->fetchAll('assoc');
        $this->set("member_data", $search_query);
        $this->set("enddate", $enddate1);
        $this->set("startdate", $startdate1);
        $this->set("status", $status);
        $this->set("gender", $gender);
    }

    /**
     * Export member Report
     * @Method memberExport
     * @Date 03 Oct 2017
     * @Author RNF Technologies  
     */
   public function memberExport($startdate = 0, $enddate = 0, $status = 'all', $gender = 'all', $etype = 0,  $output_type = 'D', $file = 'Member_Report.xls'){
         $conn = ConnectionManager::get('default');
         $where='';
         if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", $startdate);
                $to = date("Y-m-d", $enddate);
               // $where .=" AND (pu.created_date >= '$from' AND pu.created_date <= '$to')";
                $where .=" AND (DATE_FORMAT(pu.created_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(pu.created_date,'%Y-%m-%d') <= '$to')";
            }
            if($status!='all')
            {
                // $where .=" AND pu.activated = '$status' ";
                if($status==3){
                  //  $where .=" AND pu.activated = '$status' ";
                    $where .=" AND pu.is_deleted > '0' ";
                }else{
                    $where .=" AND pu.activated = '$status' ";
                    $where .=" AND pu.is_deleted='0' ";
                }
            }
            if($gender!='all')
            {
                 $where .=" AND pup.gender = '$gender' ";
            }
        $search_query = "SELECT * from pheramor_user pu INNER JOIN pheramor_user_profile pup on pu.id=pup.user_id where pu.role_name = 'member'  $where  order by pup.first_name";
        $search_query = $conn->execute($search_query);
        $search_query = $search_query->fetchAll('assoc');
     //   print_r($search_query); die;
        $this->set("member_data", $search_query);
        $this->set("users", $search_query);
        
        if ($etype == 0) {
           // echo "<pre>";print_r($users); die;
            $this->set(compact('user', 'output_type', 'file'));
            $this->viewBuilder()->layout('xls/default');
            $this->viewBuilder()->template('xls/member_report');
            $this->RequestHandler->respondAs('xlsx');
            $this->render();
        } else {
            $ftype = 0;
            $this->viewBuilder()->layout('pdf/pdf');
            $this->set('title', 'Member Report');
            $this->set('ftype', $ftype);
            $this->set('print_style', 1);
            $this->viewBuilder()->template('pdf/member_report');
            $this->set('filename', date('Y-m-d') . '_member_report.pdf');
            $this->response->type('pdf');
        }
    }
    
    /**
     * View Cafe Report
     * @Method cafeReport
     * @Date 03 Oct 2017
     * @Author RNF Technologies  
     */
    
   public function cafeReport() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $cafe_tbl = TableRegistry::get("PheramorCafe");
        $cafe_lists = $cafe_tbl->find("list", ["keyField" => "id", "valueField" => "title"]);
        $cafe_lists = $cafe_lists->toArray();
        $this->set("cafe_lists", $cafe_lists);
        if ($this->request->is("post")) {
            $where = $string = $string1 = "";
            $startdate = $this->request->data['startdate'];
            $startdate1 = strtotime($this->request->data['startdate']);
            $enddate = $this->request->data['enddate'];
            $enddate1 = strtotime($this->request->data['enddate']);
            $cafe_name=$this->request->data['cafe_name'];
            $status=$this->request->data['cafe_status'];
           if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", strtotime($this->request->data["startdate"]));
                $to = date("Y-m-d", strtotime($this->request->data["enddate"]));
               // $where .=" AND (pu.created_date >= '$from' AND pu.created_date <= '$to')";
            }
            if($status!='all')
            {
                 $where .=" AND cafe_type = '$status' ";
            }
            if(!empty($cafe_name))
            {
                 $where .=" AND id = '$cafe_name' ";
            }
            $search_query = "SELECT * from pheramor_cafe where 1 $where order by title ASC";
        } else {
              $startdate1 = 0;
              $enddate1 = 0;
              $status='all';
              $cafe_name=0;
              $search_query = "SELECT * from pheramor_cafe  order by title ASC";
 
            }

        $search_query = $conn->execute($search_query);
        $search_query = $search_query->fetchAll('assoc');
        $this->set("cafe_data", $search_query);
        $this->set("enddate", $enddate1);
        $this->set("startdate", $startdate1);
        $this->set("status", $status);
        $this->set("cafe_name", @$cafe_name);
    }
   
   /**
     * Cafe View Details
     * @Method cafeViewReport
     * @Date 03 Oct 2017
     * @Author RNF Technologies  
     */
    
    
    public function cafeViewReport($id, $startdate=0, $enddate=0)
    {
       $cafe_tbl = TableRegistry::get("PheramorCafe");
       $cafe_data = $cafe_tbl->find("all")->where(['id' => $id])->first();
       $this->set("cafe_data", $cafe_data);
       
       $cafecheckin_tbl = TableRegistry::get("PheramorUserCafeCheckins");
       $cafe_check_data = $cafecheckin_tbl->find("all"); 
       if (!empty($startdate) && !empty($enddate)) {
            $from = date("Y-m-d", $startdate);
            $to = date("Y-m-d", $enddate);
            $cafe_check_data = $cafecheckin_tbl->find("all")->where(['cafe_id' => $id, 'DATE_FORMAT(check_in_time,"%Y-%m-%d") >='=> $from, 'DATE_FORMAT(check_in_time,"%Y-%m-%d") <='=> $to])->toArray();
       }else{
           $cafe_check_data = $cafecheckin_tbl->find("all")->where(['cafe_id' => $id])->toArray(); 
       }
       $this->set("cafe_check_data", $cafe_check_data);
      }
    
       /**
     * Cafe Export Excel and PDF
     * @Method cafeExport
     * @Date 05 Oct 2017
     * @Author RNF Technologies  
     */
    public function cafeExport($startdate = 0, $enddate = 0, $status = 'all', $cafe_name =0, $etype = 0,  $output_type = 'D', $file = 'Cafe_Report.xls')
    {
            $conn = ConnectionManager::get('default');
            $where='';
            $where_check='';
            if($status!='all')
            {
                 $where .=" AND cafe_type = '$status' ";
            }
            if(!empty($cafe_name))
            {
                 $where .=" AND id = '$cafe_name' ";
                 $where_check .=" AND cafe_id = '$cafe_name' ";
                 
            }
            if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", $startdate);
                $to = date("Y-m-d", $enddate);
                $where_check .=" AND (DATE_FORMAT(check_in_time,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(check_in_time,'%Y-%m-%d') <= '$to')";
             }
            $search_query = "SELECT * from pheramor_cafe where 1 $where order by title ASC";
            $search_query = $conn->execute($search_query);
            $search_query = $search_query->fetchAll('assoc');
           //   print_r($search_query); die;
            /*** Cafe check In data **/
            $search_check = "SELECT * from pheramor_user_cafe_checkins where 1 $where_check order by cafe_id DESC";
            $search_check = $conn->execute($search_check);
            $search_check_data = $search_check->fetchAll('assoc');
            $this->set("cafe_check_data", $search_check_data);
        /*** Cafe check in data end here **/
            
            $this->set("member_data", $search_query);
            $this->set("users", $search_query);
            $this->set("enddate", $enddate);
            $this->set("startdate", $startdate);
        
        if ($etype == 0) {
           // echo "<pre>";print_r($users); die;
            $this->set(compact('user', 'output_type', 'file'));
            $this->viewBuilder()->layout('xls/default');
            $this->viewBuilder()->template('xls/cafe_report');
            $this->RequestHandler->respondAs('xlsx');
            $this->render();
        } else {
            $ftype = 0;
            $this->viewBuilder()->layout('pdf/pdf');
            $this->set('title', 'Cafe Report');
            $this->set('ftype', $ftype);
            $this->set('print_style', 1);
            $this->viewBuilder()->template('pdf/cafe_report');
            $this->set('filename', date('Y-m-d') . '_cafe_report.pdf');
            $this->response->type('pdf');
        }
    }
      
     /**
     * View Refund Payment Report
     * @Method refundPaymentReport
     * @Date 09 Oct 2017
     * @Author RNF Technologies  
     */
    public function refundPaymentReport()
    {
       $conn = ConnectionManager::get('default');
       $session = $this->request->session()->read("User");
       if ($this->request->is("post")) {
            $where = $string = $string1 = "";
            $startdate = $this->request->data['startdate'];
            $startdate1 = strtotime($this->request->data['startdate']);
            $enddate = $this->request->data['enddate'];
            $enddate1 = strtotime($this->request->data['enddate']);
            $status=$this->request->data['refund_status'];
           if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", strtotime($this->request->data["startdate"]));
                $to = date("Y-m-d", strtotime($this->request->data["enddate"]));
                //$where .=" AND (refund_date >= '$from' AND refund_date <= '$to')";
                 $where .=" AND (DATE_FORMAT(refund_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(refund_date,'%Y-%m-%d') <= '$to')";
            }
            if($status!='all')
            {
                 $where .=" AND refund_status = '$status' ";
            }
            
            $search_query = "SELECT * from pheramor_refund_payment where 1 $where order by id Desc";
        } else {
              $startdate1 = 0;
              $enddate1 = 0;
              $status='all';
              $search_query = "SELECT * from pheramor_refund_payment  order by id DESC";
 
            }

        $search_query = $conn->execute($search_query);
        $search_query = $search_query->fetchAll('assoc');
        $this->set("refund_data", $search_query);
        $this->set("enddate", $enddate1);
        $this->set("startdate", $startdate1);
        $this->set("status", $status);
      
        
    }
    
    
      /**
     * Refund Payment Export Excel and PDF
     * @Method refundPaymentExport
     * @Date 05 Oct 2017
     * @Author RNF Technologies  
     */
    public function refundPaymentExport($startdate = 0, $enddate = 0, $status = 'all', $etype = 0,  $output_type = 'D', $file = 'Refund_Payment_Report.xls'){
        
            $conn = ConnectionManager::get('default');
            $where='';
            $where_check='';
            if($status!='all')
            {
                 $where .=" AND refund_status = '$status' ";
            }
            if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", $startdate);
                $to = date("Y-m-d", $enddate);
                $where .=" AND (DATE_FORMAT(refund_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(refund_date,'%Y-%m-%d') <= '$to')";
             }
            $search_query = "SELECT * from pheramor_refund_payment where 1 $where order by id DESC";
            $search_query = $conn->execute($search_query);
            $search_query = $search_query->fetchAll('assoc');
            //print_r($search_query); die;
            $this->set("member_data", $search_query);
            $this->set("users", $search_query);
            $this->set("enddate", $enddate);
            $this->set("startdate", $startdate);
        
        if ($etype == 0) {
           // echo "<pre>";print_r($users); die;
            $this->set(compact('user', 'output_type', 'file'));
            $this->viewBuilder()->layout('xls/default');
            $this->viewBuilder()->template('xls/refund_payment_report');
            $this->RequestHandler->respondAs('xlsx');
            $this->render();
        } else {
            $ftype = 0;
            $this->viewBuilder()->layout('pdf/pdf');
            $this->set('title', 'Refund Payment Report');
            $this->set('ftype', $ftype);
            $this->set('print_style', 1);
            $this->viewBuilder()->template('pdf/refund_payment_report');
            $this->set('filename', date('Y-m-d') . '_refund_payment_report.pdf');
            $this->response->type('pdf');
        }
        
        
    }
    
     /**
     * View Product Sales Report
     * @Method productSalesReport
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function productSalesReport() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $cafe_tbl = TableRegistry::get("PheramorCafe");
        $cafe_lists = $cafe_tbl->find("list", ["keyField" => "id", "valueField" => "title"]);
        $cafe_lists = $cafe_lists->toArray();
        $this->set("cafe_lists", $cafe_lists);
        if ($this->request->is("post")) {
            $where = $string = $string1 = "";
            $startdate = $this->request->data['startdate'];
            $startdate1 = strtotime($this->request->data['startdate']);
            $enddate = $this->request->data['enddate'];
            $enddate1 = strtotime($this->request->data['enddate']);
           // $cafe_name=$this->request->data['cafe_name'];
            $status=$this->request->data['payment_status'];
           if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", strtotime($this->request->data["startdate"]));
                $to = date("Y-m-d", strtotime($this->request->data["enddate"]));
                $where .=" AND (DATE_FORMAT(ppp.created_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(ppp.created_date,'%Y-%m-%d') <= '$to')";
            }
            if($status!='all')
            {
                 $where .=" AND ppp.payment_status = '$status' ";
            }
              $search_query = "SELECT *,ppp.created_date as payment_date from pheramor_product_payment ppp INNER JOIN pheramor_user pu on ppp.user_id=pu.id INNER JOIN pheramor_user_profile puf on ppp.user_id=puf.user_id Where 1 $where order by puf.first_name ASC";
            
              
            } else {
              $startdate1 = 0;
              $enddate1 = 0;
              $status='all';
              $cafe_name=0;
              $search_query = "SELECT *,ppp.created_date as payment_date from pheramor_product_payment ppp INNER JOIN pheramor_user pu on ppp.user_id=pu.id INNER JOIN pheramor_user_profile puf on ppp.user_id=puf.user_id order by puf.first_name ASC";
 
            }

        $search_query = $conn->execute($search_query);
        $search_query = $search_query->fetchAll('assoc');
        $this->set("product_sales_data", $search_query);
        $this->set("enddate", $enddate1);
        $this->set("startdate", $startdate1);
        $this->set("status", $status);
       // $this->set("cafe_name", @$cafe_name);
    }
    
    
     /**
     * Product Sales Export Excel and PDF
     * @Method productSalesExport
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    public function productSalesExport($startdate = 0, $enddate = 0, $status = 'all', $etype = 0,  $output_type = 'D', $file = 'Product_Sales_Report.xls')
    {
            $conn = ConnectionManager::get('default');
            $where='';
            $where_check='';
            if($status!='all')
            {
                 $where_check .=" AND ppp.payment_status = '$status' ";
            }
           
            if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", $startdate);
                $to = date("Y-m-d", $enddate);
                $where_check .=" AND (DATE_FORMAT(ppp.created_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(ppp.created_date,'%Y-%m-%d') <= '$to')";
             }
            //$search_query = "SELECT * from pheramor_cafe where 1 $where order by title ASC";
            $search_query = "SELECT *,ppp.created_date as payment_date from pheramor_product_payment ppp INNER JOIN pheramor_user pu on ppp.user_id=pu.id INNER JOIN pheramor_user_profile puf on ppp.user_id=puf.user_id Where 1 $where_check order by puf.first_name ASC";
            $search_query = $conn->execute($search_query);
            $search_query = $search_query->fetchAll('assoc');
          
            
            $this->set("member_data", $search_query);
            $this->set("users", $search_query);
            $this->set("enddate", $enddate);
            $this->set("startdate", $startdate);
        
        if ($etype == 0) {
           // echo "<pre>";print_r($users); die;
            $this->set(compact('user', 'output_type', 'file'));
            $this->viewBuilder()->layout('xls/default');
            $this->viewBuilder()->template('xls/product_sales_report');
            $this->RequestHandler->respondAs('xlsx');
            $this->render();
        } else {
            $ftype = 0;
            $this->viewBuilder()->layout('pdf/pdf');
            $this->set('title', 'Cafe Report');
            $this->set('ftype', $ftype);
            $this->set('print_style', 1);
            $this->viewBuilder()->template('pdf/product_sales_report');
            $this->set('filename', date('Y-m-d') . '_product_sales_report.pdf');
            $this->response->type('pdf');
        }
    }
      
    /**
     * View Subscription Sales Report
     * @Method subscriptionSalesReport
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function subscriptionSalesReport() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $subscription_tbl = TableRegistry::get("PheramorSubscription");
        $subscription_lists = $subscription_tbl->find("list", ["keyField" => "id", "valueField" => "subscription_title"]);
        $subscription_lists = $subscription_lists->where(["subscription_cat_id !="=>9, "is_deleted" => "0"])->toArray();
        $this->set("subscription_lists", $subscription_lists);
        if ($this->request->is("post")) {
            $where = $string = $string1 = "";
            $startdate = $this->request->data['startdate'];
            $startdate1 = strtotime($this->request->data['startdate']);
            $enddate = $this->request->data['enddate'];
            $enddate1 = strtotime($this->request->data['enddate']);
            $plan_status=$this->request->data['plan_status'];
            $payment_status=$this->request->data['payment_status'];
            $subscription_id=$this->request->data['subscription_id'];
           if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", strtotime($this->request->data["startdate"]));
                $to = date("Y-m-d", strtotime($this->request->data["enddate"]));
                $where .=" AND (DATE_FORMAT(pp.created_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(pp.created_date,'%Y-%m-%d') <= '$to')";
            }
            if(!empty($subscription_id))
            {
                 $where .=" AND pp.subscription_id = '$subscription_id' ";
            }
            if($plan_status!='all')
            {
                 $where .=" AND pp.plan_status = '$plan_status' ";
            }
            if($payment_status!='all')
            {
                 $where .=" AND pp.payment_status = '$payment_status' ";
            }else{
                $where .=" AND pp.payment_status = '1' ";
            }
            
             $search_query = "SELECT *,pp.created_date as payment_date from pheramor_payment pp INNER JOIN pheramor_user pu on pp.user_id=pu.id INNER JOIN pheramor_user_profile puf on pp.user_id=puf.user_id Where 1 $where order by puf.first_name ASC";
            } else {
              $startdate1 = 0;
              $enddate1 = 0;
              $payment_status='all';
              $plan_status='all';
              $subscription_id=0;
              $search_query = "SELECT *,pp.created_date as payment_date from pheramor_payment pp INNER JOIN pheramor_user pu on pp.user_id=pu.id INNER JOIN pheramor_user_profile puf on pp.user_id=puf.user_id  order by puf.first_name ASC";
 
            }

        $search_query = $conn->execute($search_query);
        $search_query = $search_query->fetchAll('assoc');
        $this->set("subscription_sales_data", $search_query);
        $this->set("enddate", $enddate1);
        $this->set("startdate", $startdate1);
        $this->set("subscription_id", $subscription_id);
       $this->set("plan_status", @$plan_status);
       $this->set("payment_status", @$payment_status);
    }
    
     /**
     * Subscription Sales Export Excel and PDF
     * @Method subscriptionSalesExport
     * @Date 26 Oct 2017
     * @Author RNF Technologies  
     */
    public function subscriptionSalesExport($startdate = 0, $enddate = 0, $subscription_id = 0, $plan_status = 'all',$payment_status = 'all', $etype = 0,  $output_type = 'D', $file = 'Subscription_Sales_Report.xls')
    {
            $conn = ConnectionManager::get('default');
            $where='';
            $where_check='';
            if($plan_status!='all')
            {
                 $where_check .=" AND pp.plan_status = '$plan_status' ";
            }
            if($payment_status!='all')
            {
                 $where_check .=" AND pp.payment_status = '$payment_status' ";
            }
            if(!empty($subscription_id))
            {
                 $where_check .=" AND pp.subscription_id = '$subscription_id' ";
            }
            if (!empty($startdate) && !empty($enddate)) {
                $from = date("Y-m-d", $startdate);
                $to = date("Y-m-d", $enddate);
                $where_check .=" AND (DATE_FORMAT(pp.created_date,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(pp.created_date,'%Y-%m-%d') <= '$to')";
             }
            $search_query = "SELECT *,pp.created_date as payment_date from pheramor_payment pp INNER JOIN pheramor_user pu on pp.user_id=pu.id INNER JOIN pheramor_user_profile puf on pp.user_id=puf.user_id Where 1 $where_check order by puf.first_name ASC";
            $search_query = $conn->execute($search_query);
            $search_query = $search_query->fetchAll('assoc');
          
            
            $this->set("member_data", $search_query);
            $this->set("users", $search_query);
            $this->set("enddate", $enddate);
            $this->set("startdate", $startdate);
        
        if ($etype == 0) {
           // echo "<pre>";print_r($users); die;
            $this->set(compact('user', 'output_type', 'file'));
            $this->viewBuilder()->layout('xls/default');
            $this->viewBuilder()->template('xls/subscription_sales_report');
            $this->RequestHandler->respondAs('xlsx');
            $this->render();
        } else {
            $ftype = 0;
            $this->viewBuilder()->layout('pdf/pdf');
            $this->set('title', 'Cafe Report');
            $this->set('ftype', $ftype);
            $this->set('print_style', 1);
            $this->viewBuilder()->template('pdf/subscription_sales_report');
            $this->set('filename', date('Y-m-d') . '_subscription_sales_report.pdf');
            $this->response->type('pdf');
        }
    }
    
    public function isAuthorized($user){
      return parent::isAuthorizedCustom($user);
      } 
    ############################################################
}
