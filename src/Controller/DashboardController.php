<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class DashboardController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent("GYMFunction");
        require_once(ROOT . DS . 'vendor' . DS . 'chart' . DS . 'GoogleCharts.class.php');
    }

    /**
     * Controller Load Default index function call
     * @param defaul function
     * @Date 22 Aug 2017
     * @Author RNF Technologies  
     */
    public function index() {
        $session = $this->request->session()->read("User");
        switch ($session["role_name"]) {
            CASE "administrator":
                return $this->redirect(["action" => "adminDashboard"]);
                break;
            default:
                return $this->redirect(["action" => "adminDashboard"]);
        }
    }

    /**
     * Admin dashboard display functions
     * @param  AdminDashboard  function
     * @Date 22 Aug 2017
     * @Author RNF Technologies  
     */
    public function adminDashboard() {
      //  phpinfo();
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $this->autoRender = false;
        $member_data="Select pup.profession,pup.dob,pup.first_name,pup.last_name,pu.id,pup.image from pheramor_user pu "
                 . "INNER JOIN pheramor_user_profile pup ON pu.id=pup.user_id where pu.activated='1' and pu.is_deleted='0' and pu.role_id='2' "
                 . "group by pu.id order by RAND() limit 4";
        $member_data = $conn->execute($member_data);
        $member_data = $member_data->fetchAll('assoc');
        $this->set("member_data", $member_data);
       
        ### Total Register Users ####
        
        $member_tbl = TableRegistry::get("PheramorUser");
        $total_member = $member_tbl->find("all")->where(["role_name" => 'member']);
        $total_member = $total_member->count();
        $this->set("total_member", $total_member);
        
      /////////////Monthly Sales Report //////////////////////
        $y1 = date('Y');
        $f_month_amt=0;
        $mon1 = date('M');
        $monArr = range(1,date('t'));
        $monString = implode(',',$monArr);
        $dgraphMon = "SELECT EXTRACT(MONTH FROM mp.start_date) as date_d, EXTRACT(DAY FROM mp.start_date) as day_d, sum(mp.paid_amount) as paid_amount from pheramor_payment mp INNER JOIN pheramor_user pu on mp.user_id=pu.id  where YEAR(mp.start_date)='$y1' AND MONTH(mp.start_date)='" . date('m') . "' AND mp.payment_status = '1'  AND pu.role_name='member' order by  DAY(mp.start_date)";
        $dgraphMon = $conn->execute($dgraphMon);
        $resultMon = $dgraphMon->fetchAll('assoc');
        if (!empty($resultMon)) {
            foreach ($resultMon as $rMon) {
                $f_month_amt = $f_month_amt + $rMon["paid_amount"];
                $chart_array_fmonth[$rMon["day_d"]] = array((float) $rMon["paid_amount"]);
                $days_array_first_month[] = $rMon["day_d"];
            }

            //print_r($days_array_first_month);die;
            for ($i = 1; $i <= date('t'); $i++) {
                if (!in_array(sprintf('%02d', $i), @$days_array_first_month)) {
                    $chart_array_fmonth[$i] = array(0);
                }
            }
        } else {
            for ($i = 1; $i <= date('t'); $i++) {
                $chart_array_fmonth[$i] = array(0);
            }
        }
        @ksort($chart_array_fmonth);

        $fstring = '';
        foreach ($chart_array_fmonth as $fdata) {
            $fstring .= $fdata[0] . ', ';
        }
        $firstmonthdata = substr($fstring, 0, -2);
        $this->set("first_month_data", $firstmonthdata);
        $this->set("month1", @$mon1);
        $this->set("f_month_amt", $f_month_amt);
        $this->set("monString", $monString);

        #### Sales by Subscription ####
        
        $gsub ="SELECT * FROM (
                (Select psc.category_type as subscription,sum(pm.paid_amount) as value from pheramor_subscription_category psc LEFT JOIN pheramor_subscription ps  on psc.id=ps.subscription_cat_id LEFT JOIN pheramor_payment pm on ps.id=pm.subscription_id  where pm.payment_status='1' and  psc.category_type='subscription')
                UNION ALL
                (Select psc.category_type as subscription,sum(ppp.paid_amount) as value from pheramor_subscription_category psc LEFT JOIN pheramor_subscription ps  on psc.id=ps.subscription_cat_id LEFT JOIN pheramor_product_payment ppp on ps.id=ppp.product_id  where ppp.payment_status='1' and  psc.category_type='product')
               )results
                ";
        $gsub = $conn->execute($gsub);
        $resultsub = $gsub->fetchAll('assoc');
        $subscription_graph=json_encode($resultsub);
        $this->set("subscription_graph", $subscription_graph);
        
        ### Histogram subscription Graph
        $ghsub ="Select ps.subscription_title as year,sum(pm.paid_amount) as income from pheramor_subscription_category psc LEFT JOIN pheramor_subscription ps  on psc.id=ps.subscription_cat_id LEFT JOIN pheramor_payment pm on ps.id=pm.subscription_id  where pm.payment_status='1' and  psc.category_type='subscription' group by ps.id ";
        $ghsub = $conn->execute($ghsub);
        $resultsubh = $ghsub->fetchAll('assoc');
      //  print_r($resultsubh); die;
        $subscription_histo_graph=json_encode($resultsubh);
        $this->set("subscription_piller_graph", $subscription_histo_graph);
        
        
        ### Latest Payment History data ### 
        //$payment_query="select pp.paid_amount,pp.subscription_amount,pp.discount_amount,puf.first_name,puf.last_name,puf.image,pp.payment_status,pp.created_date,pp.subscription_id from pheramor_payment pp INNER JOIN pheramor_user_profile puf on pp.user_id=puf.user_id order by created_date desc limit 0,6";
       $payment_query="SELECT * FROM (
                    (SELECT user_id as user_id, payment_status as payment_status, discount_amount as discount_amount, product_id as subscription_id,product_amount as subscription_amount, created_date as created_date FROM pheramor_product_payment)
                    UNION ALL
                    (SELECT user_id as user_id, payment_status as payment_status, discount_amount as discount_amount,subscription_id as subscription_id,subscription_amount as subscription_amount, created_date as created_date FROM pheramor_payment)
                ) results
                ORDER BY created_date DESC limit 0,5";
        $payment_query = $conn->execute($payment_query);
        $payment_data = $payment_query->fetchAll('assoc');
        $this->set("latest_payment_data", $payment_data);
        
        #### Total Sales ####
        $total_sales=$this->totalSales();
        $this->set("total_sales", $total_sales);
        
        ### This week sales ####
        $weekly_sales=$this->weeklySales();
        $this->set("weekly_sales", $weekly_sales);
        
        ### Permium members #####
        $activePremiumMember=$this->activePremiumMember();
        $this->set("activePremiumMember", $activePremiumMember);
        
        
        
        
        $this->render("dashboard");
        
    }

    /**
     * Total Sales display functions
     * @param  totalSales  function
     * @Date 11 Oct 2017
     * @Author RNF Technologies  
     */
    
    private function totalSales()
    {
       $conn = ConnectionManager::get('default');
       $payment_query="select sum(paid_amount) as total_paid from pheramor_payment where payment_status='1'";
       $payment_query = $conn->execute($payment_query);
       $payment_data = $payment_query->fetch('assoc');
       return $payment_data['total_paid'];
        
        
    }
    
     /**
     * Total Weekly Sales display functions
     * @param  weeklySales  function
     * @Date 11 Oct 2017
     * @Author RNF Technologies  
     */
    
    private function weeklySales()
    {
       $conn = ConnectionManager::get('default');
       $payment_query="SELECT sum(paid_amount) as total_paid FROM pheramor_payment WHERE WEEKOFYEAR(created_date)=WEEKOFYEAR(NOW()) and payment_status='1'"; /// This week
       //$payment_query="SELECT sum(paid_amount) as total_paid FROM pheramor_payment WHERE WEEKOFYEAR(created_date)=WEEKOFYEAR(NOW())-1 and payment_status='1'"; /// Last week
       $payment_query = $conn->execute($payment_query);
       $payment_data = $payment_query->fetch('assoc');
       return $payment_data['total_paid'];
        
        
    }
    
     /**
     * Total PREMIUM  Member display functions
     * @param  activePremiumMember  function
     * @Date 11 Oct 2017
     * @Author RNF Technologies  
     */
    
    private function activePremiumMember()
    {
       $conn = ConnectionManager::get('default');
       $sql="select * from pheramor_payment pm INNER JOIN pheramor_subscription ps on pm.subscription_id=ps.id where pm.plan_status='1' and ps.subscription_cat_id NOT IN (5,9) group by pm.user_id";
       $sql_data = $conn->execute($sql);
       return $sql_data->count();
      
        
        
    }
    
    
    /**
     * Check Access right table to access this method
     * @param  isAuthorized  function
     * @Date 22 Aug 2017
     * @Author RNF Technologies  
     */
    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
