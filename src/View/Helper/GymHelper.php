<?php

namespace App\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\View\Helper;
use Cake\Utility\Xml;
use Cake\Datasource\ConnectionManager;

Class GymHelper extends Helper {

    var $helpers = array('Url'); //Loading Url Helper

    public function createurl($controller, $action) {
        return $this->Url->build(["controller" => $controller, "action" => $action]);
    }

    public function getAccessRightRecord() {
        $gym_accessright = TableRegistry::get("pheramor_accessright");
        $accessright = $gym_accessright->find()->where(["controller" => $this->request->params['controller'], "action" => $this->request->params['action']])->hydrate(false);
        $accessright = $accessright->first();
        //echo '<pre>'; print_r($accessright);die;
        if (!empty($accessright)) {
            return $accessright;
        }
    }

    public function get_plan_duration($id) {
        $plan_table = TableRegistry::get("Installment_Plan");
        $plan_duration = $plan_table->find()->where(["id" => $id])->hydrate(false);
        $plan_duration = $plan_duration->toArray();
        if (!empty($plan_duration)) {
            return $plan_duration[0];
        }
    }

    public function get_activity_name($id) {
        $activity_table = TableRegistry::get("Activity");
        $title = $activity_table->find()->where(["id" => $id])->select(['title'])->hydrate(false);
        $title = $title->toArray();
        if (!empty($title)) {
            return $title[0]['title'];
        }
    }

    public function getSettings($key) {
        $settings = TableRegistry::get("PheramorGeneralSetting");
        $row = $settings->find()->all();
        $row = $row->first()->toArray();
        $value = "";
        switch ($key) {
            CASE "company_name";
                $value = $row[$key];
                break;
            CASE "company_logo";
                $value = $row[$key];
                break;
            CASE "date_format";
                $value = $row[$key];
                break;
            CASE "country";
                $value = $row[$key];
                break;
            CASE "member_can_view_other";
                $value = $row[$key];
                break;
            CASE "staff_can_view_other_member";
                $value = $row[$key];
                break;
            CASE "enable_message";
                $value = $row[$key];
                break;
            CASE "currency";
                $value = $row[$key];
                break;
            CASE "left_header";
                $value = $row[$key];
                break;
            CASE "footer";
                $value = $row[$key];
                break;
            CASE "enable_rtl";
                $value = $row[$key];
                break;
            CASE "datepicker_lang";
                $value = $row[$key];
                break;
            CASE "sys_language";
                $value = $row[$key];
                break;

            CASE "email";
                $value = $row[$key];
                break;
        }
        return $value;
    }

    public function getCountryCode($country) {
        $xml = Xml::build('../vendor/xml/countrylist.xml');
        foreach ($xml as $x) {
            if ($x->code == $country) {
                return $x->phoneCode;
            }
        }
    }

    function get_js_dateformat($php_format) {
        $SYMBOLS_MATCHING = array(
            // Day
            'd' => 'dd',
            'D' => 'D',
            'j' => 'd',
            'l' => 'DD',
            'N' => '',
            'S' => '',
            'w' => '',
            'z' => 'o',
            // Week
            'W' => '',
            // Month
            'F' => 'MM',
            'm' => 'mm',
            'M' => 'M',
            'n' => 'm',
            't' => '',
            // Year
            'L' => '',
            'o' => '',
            'Y' => 'yyyy',
            'y' => 'y',
            // Time
            'a' => '',
            'A' => '',
            'B' => '',
            'g' => '',
            'G' => '',
            'h' => '',
            'H' => '',
            'i' => '',
            's' => '',
            'u' => ''
        );
        $jqueryui_format = "";
        $escaping = false;
        for ($i = 0; $i < strlen($php_format); $i++) {
            $char = $php_format[$i];
            if ($char === '\\') { // PHP date format escaping character
                $i++;
                if ($escaping)
                    $jqueryui_format .= $php_format[$i];
                else
                    $jqueryui_format .= '\'' . $php_format[$i];
                $escaping = true;
            }
            else {
                if ($escaping) {
                    $jqueryui_format .= "'";
                    $escaping = false;
                }
                if (isset($SYMBOLS_MATCHING[$char]))
                    $jqueryui_format .= $SYMBOLS_MATCHING[$char];
                else
                    $jqueryui_format .= $char;
            }
        }
        return $jqueryui_format;
    }

    public function get_category_name($id) {
        $category_table = TableRegistry::get("Category");
        $name = $category_table->find()->where(["id" => $id])->select(['name'])->hydrate(false);
        $name = $name->toArray();
        if (!empty($name)) {
            return $name[0]['name'];
        }
    }

    public function get_staff_name($id) {
        $mem_table = TableRegistry::get("GymMember");
        $staff = $mem_table->find()->where(["id" => $id, ["role_name" => "staff_member"]])->select(['first_name', 'last_name'])->hydrate(false);
        $staff = $staff->toArray();
        if (!empty($staff)) {
            return $staff[0]["first_name"] . " " . $staff[0]["last_name"];
        } else {
            return "None";
        }
    }

    public function days_array() {
        return $week = array('Sunday' => 'Sunday', 'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday');
    }

    public function minute_array() {
        return $minute = array('00' => '00', '15' => '15', '30' => '30', '45' => '45');
    }

    public function measurement_array() {
        return $measurment = array('Height' => 'Height',
            'Weight' => 'Weight', 'Chest' => 'Chest', 'Waist' => 'Waist', 'Thigh' => 'Thigh', 'Arms' => 'Arms', 'Fat' => 'Fat');
    }

    public function get_activity_by_category($id) {
        $activity_table = TableRegistry::get("Activity");
        $activities = $activity_table->find("all")->where(["cat_id" => $id])->hydrate(false)->toArray();
        return $activities;
    }

    public function get_activity_by_id($id) {
        $activity_table = TableRegistry::get("Activity");
        $activity = $activity_table->get($id)->toArray();
        return $activity["title"];
    }

    public function get_interest_by_id($id) {
        $interest_table = TableRegistry::get("gymInterestArea");
        // $row = $interest_table->get($id)->toArray();	 // generates record not found error.
        $row = $interest_table->find("all")->where(["id" => $id])->toArray();
        return (!empty($row)) ? $row[0]["interest"] : "---";
    }

    public function get_attendance_status($id, $date) {
        $date = date("Y-m-d", strtotime($date));
        $att_table = TableRegistry::get("GymAttendance");
        $row = $att_table->find()->where(["user_id" => $id, "attendance_date" => "{$date}"])->hydrate(false)->toArray();
        if (!empty($row)) {
            return $row[0]["status"];
        } else {
            return __("Not Taken");
        }
    }

    public function get_class_by_id($id) {
        $class_table = TableRegistry::get("ClassSchedule");
        $row = $class_table->get($id)->toArray();
        return $row["class_name"];
    }

    public function get_member_list_for_message() {
        $mem_table = TableRegistry::get("GymMember");
        $staff = $mem_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(["role_name" => "staff_member"]);
        $staff = $staff->select(["id", "name" => $staff->func()->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();

        $accountant = $mem_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(["role_name" => "accountant"]);
        $accountant = $accountant->select(["id", "name" => $accountant->func()->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();

        $member = $mem_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(["role_name" => "member"]);
        $member = $member->select(["id", "name" => $member->func()->concat(["first_name" => "literal", " ", "last_name" => "literal"])])->hydrate(false)->toArray();

        $session = $this->request->session()->read("User");
        if ($session["role_name"] == "administrator") {
            $roles = ["member" => __("Members"), "staff_member" => __("Staff Members"), "accountant" => __("Accountants")];
        } else {
            $roles = ["member" => __("Members"), "staff_member" => __("Staff Members"), "accountant" => __("Accountants"), "administrator" => __("Administrator")];
        }
        $options = ["To" => $roles, "Members" => $member, "Staff" => $staff, "Accountant" => $accountant];
        // var_dump($options);die;
        return $options;
    }

    public function get_membership_paymentstatus($mp_id) {
        $membership_payment_tbl = TableRegistry::get('MembershipPayment');
        $result = $membership_payment_tbl->get($mp_id)->toArray();
        if ($result['paid_amount'] >= $result['membership_amount'])
            return 'Paid';
        else if ($result['paid_amount'] == 0)
            return 'Not Paid';
        else
            return 'Paid';

        /* 	
          $mem_table = TableRegistry::get('Membership');
          $signup_fee = $mem_table->get($result['membership_id'])->toArray();
          $signup_fee = $signup_fee["signup_fee"];
          // var_dump($result);
          if($result['paid_amount'] >= $result['membership_amount'] + $signup_fee)
          return 'Fully Paid';
          elseif($result['paid_amount'] == 0 )
          return 'Not Paid';
          else
          return 'Partially Paid';
         */
    }

    public function get_total_group_members($gid) {
        $session = $this->request->session()->read("User");
        $mem_table = TableRegistry::get("GymMember");
        $data = $mem_table->find("all");
        if ($session['role_id'] == 2 || $session['role_id'] == 6) {
            $data->where(["associated_licensee" => $session['id']]);
        }
        if($session['role_id'] == 7 || $session['role_id'] == 8)
        {
             $licensee_list = $mem_table->find()->where(["role_name" => 'licensee',"created_by"=>$session['id']])->orwhere(["id"=>$session['id']])->hydrate(false)->toArray();
               if (!empty($licensee_list)) {
                foreach ($licensee_list as $licensee) {
                 $list_lice[]= $licensee["id"]; 
                }
                 $data->where(["associated_licensee IN" => $list_lice]); 
               //$data = $data->orwhere(["id" => $session['id']]); 
              }
        }
        $data->where(["role_name" => "member", 'FIND_IN_SET(\'' . $gid . '\',assign_group)',])
                ->select(["id"]);
        return $data->count();
    }

    function get_currency_symbol($currency = '') {
        $currency = $this->getSettings("currency");
        switch ($currency) {
            case 'AED' :
                $currency_symbol = 'د.إ';
                break;
            case 'AUD' :
            case 'CAD' :
            case 'CLP' :
            case 'COP' :
            case 'HKD' :
            case 'MXN' :
            case 'NZD' :
            case 'SGD' :
            case 'USD' :
                $currency_symbol = '&#36;';
                break;
            case 'BDT':
                $currency_symbol = '&#2547;&nbsp;';
                break;
            case 'BGN' :
                $currency_symbol = '&#1083;&#1074;.';
                break;
            case 'BRL' :
                $currency_symbol = '&#82;&#36;';
                break;
            case 'CHF' :
                $currency_symbol = '&#67;&#72;&#70;';
                break;
            case 'CNY' :
            case 'JPY' :
            case 'RMB' :
                $currency_symbol = '&yen;';
                break;
            case 'CZK' :
                $currency_symbol = '&#75;&#269;';
                break;
            case 'DKK' :
                $currency_symbol = 'kr.';
                break;
            case 'DOP' :
                $currency_symbol = 'RD&#36;';
                break;
            case 'EGP' :
                $currency_symbol = 'EGP';
                break;
            case 'EUR' :
                $currency_symbol = '&euro;';
                break;
            case 'GBP' :
                $currency_symbol = '&pound;';
                break;
            case 'HRK' :
                $currency_symbol = 'Kn';
                break;
            case 'HUF' :
                $currency_symbol = '&#70;&#116;';
                break;
            case 'IDR' :
                $currency_symbol = 'Rp';
                break;
            case 'ILS' :
                $currency_symbol = '&#8362;';
                break;
            case 'INR' :
                $currency_symbol = 'Rs.';
                break;
            case 'ISK' :
                $currency_symbol = 'Kr.';
                break;
            case 'KIP' :
                $currency_symbol = '&#8365;';
                break;
            case 'KRW' :
                $currency_symbol = '&#8361;';
                break;
            case 'MYR' :
                $currency_symbol = '&#82;&#77;';
                break;
            case 'NGN' :
                $currency_symbol = '&#8358;';
                break;
            case 'NOK' :
                $currency_symbol = '&#107;&#114;';
                break;
            case 'NPR' :
                $currency_symbol = 'Rs.';
                break;
            case 'PHP' :
                $currency_symbol = '&#8369;';
                break;
            case 'PLN' :
                $currency_symbol = '&#122;&#322;';
                break;
            case 'PYG' :
                $currency_symbol = '&#8370;';
                break;
            case 'RON' :
                $currency_symbol = 'lei';
                break;
            case 'RUB' :
                $currency_symbol = '&#1088;&#1091;&#1073;.';
                break;
            case 'SEK' :
                $currency_symbol = '&#107;&#114;';
                break;
            case 'THB' :
                $currency_symbol = '&#3647;';
                break;
            case 'TRY' :
                $currency_symbol = '&#8378;';
                break;
            case 'TWD' :
                $currency_symbol = '&#78;&#84;&#36;';
                break;
            case 'UAH' :
                $currency_symbol = '&#8372;';
                break;
            case 'VND' :
                $currency_symbol = '&#8363;';
                break;
            case 'ZAR' :
                $currency_symbol = '&#82;';
                break;
            default :
                $currency_symbol = $currency;
                break;
        }
        return $currency_symbol;
    }

    public function get_class_by_member($mid) {
        $class_table = TableRegistry::get("GymMemberClass");
        $class_sche_table = TableRegistry::get("ClassSchedule");
        $row = $class_table->find()->where(["member_id" => $mid])->select(["assign_class"]);
        $row = $row->leftjoin(["ClassSchedule" => "class_schedule"], ["GymMemberClass.assign_class = ClassSchedule.id"])->select(["ClassSchedule.class_name"])->hydrate(false)->toArray();
        $class = "None";
        if (!empty($row)) {
            $class = "";
            foreach ($row as $data) {
                $class .= $data["ClassSchedule"]["class_name"] . ",";
            }
        }
        return trim($class, ",");
    }

    public function get_group_by_member($mid) {
        $mem_table = TableRegistry::get("GymMember");
        $grp_table = TableRegistry::get("GymGroup");
        $row = $mem_table->get($mid)->toArray();
        $assign_groups = json_decode($row["assign_group"]);
        $data = "";
        if (!empty($assign_groups)) {
            foreach ($assign_groups as $group) {
                $grp_name = $grp_table->get($group)->toArray();
                $data .= $grp_name["name"] . ",";
            }
            $data = trim($data, ",");
            return $data;
        } else {
            return "None";
        }
    }

    public function data_table_lang() {
        $parameters = '"sEmptyTable":     "' . __('No data available in table') . '",
						"sInfo":           "' . __("Showing _START_ to _END_ of _TOTAL_ entries") . '",
						"sInfoEmpty":      "' . __("Showing 0 to 0 of 0 entries") . '",
						"sInfoFiltered":   "' . __("(filtered from _MAX_ total entries)") . '",
						"sInfoPostFix":    "",
						"sInfoThousands":  ",",
						"sLengthMenu":     "' . __("Show _MENU_ entries") . '",
						"sLoadingRecords": "' . __("Loading...") . '",
						"sProcessing":     "' . __("Processing...") . '",
						"sSearch":         "' . __("Search") . ':",
						"sZeroRecords":    "' . __("No matching records found") . '",
						"oPaginate": {
							"sFirst":    "' . __("First") . '",
							"sLast":     "' . __("Last") . '",
							"sNext":     "' . __("Next") . '",
							"sPrevious": "' . __("Previous") . '"
						},
						"oAria": {
							"sSortAscending":  ": ' . __("activate to sort column ascending") . '",
							"sSortDescending": ": ' . __("activate to sort column descending") . '"
						}';
        return $parameters;
    }

    public function get_class_type($mid = null) {
        $class_type_table = TableRegistry::get("ClassType");
        $row = $class_type_table->get($mid)->toArray();
        return $row['title'];
    }

    public function get_classes_by_id($id) {
        $class_table = TableRegistry::get("GymClass");
        $row = $class_table->get($id)->toArray();
        return $row["name"];
    }

    function getUserLicensee($uid) {
        $mem_table = TableRegistry::get("GymMember");
        $mem_data = $mem_table->find('all')->where(['id' => $uid])->select(['associated_licensee'])->first();
        return $mem_data['associated_licensee'];
    }
    
    function getClientTrainerLocationId($uid) {
        $lic = $this->getUserLicensee($uid);
        
        $mem_table = TableRegistry::get("GymMember");
        $mem_data = $mem_table->find('all')->select(['location_id'])->where(['id' => $lic])->first();
        return $mem_data['location_id'];
    }

    public function get_classes_scheduled_by_id($gid) {
        $session = $this->request->session()->read('User');
        $class_sche_table = TableRegistry::get("ClassSchedule");

        $datas = $class_sche_table->find('all');
        if ($session['role_id'] == 2 || $session['role_id'] == 6 || $session['role_id'] == 7) {
            $datas->where(["licensee_id" => $session['id']]);
        }
        if ($session['role_id'] == 3) {
            $licensee_id = $this->getUserLicensee($session['id']);
            $datas->where(["licensee_id" => $licensee_id]);
            $datas->where(["assign_staff_mem" => $session['id']]);
        }
        $datas->where(["class_name" => $gid,"end_date >=" => date("Y-m-d")])->toArray();
        //print_r( $datas);
        $sum = 0;
        foreach ($datas as $res) {
            //print_r($res);
            $id = $res['id'];
            $class_table = TableRegistry::get("ClassScheduleList");
            $data = $class_table->find("all")->where(["class_id" => $id])->select(["id"]);
            $count = $data->count();
            $sum = $sum + $count;
        }

        return $sum;
    }

    public function get_classes_scheduled_list_count_by_id($cid) {
        $session = $this->request->session()->read('User');
        $class_schedule_tbl = TableRegistry::get("ClassScheduleList");
        $schedule_list = $class_schedule_tbl->find()->where(["class_id" => $cid])->hydrate(false)->toArray();
        return count($schedule_list);
    }

    public function get_classes_scheduled_list_count_by_id1($cid) {
        $session = $this->request->session()->read('User');
        $class_schedule_tbl = TableRegistry::get("ClassSchedule");
        $class_schedule_tbl = TableRegistry::get("ClassScheduleList");
        $schedule_list = $class_schedule_tbl->find()->where(["class_id" => $cid])->hydrate(false)->toArray();
        return count($schedule_list);
    }

    public function get_class_schedule_name($gid) {

        $class_sche_table = TableRegistry::get("ClassSchedule");
        $datas = $class_sche_table->find('all')->where(["id" => $gid])->toArray();
        $classes_id = $datas[0]["class_name"];

        $class_table = TableRegistry::get("GymClass");
        $datass = $class_table->find('all')->where(["id" => $classes_id])->toArray();

        return $datass[0]["name"];
    }

    /// Check member assign class count
    public function get_member_assign_class($id) {
        $assign_class_table = TableRegistry::get("GymMemberClass");
        $data = $assign_class_table->find("all")->where(["member_id" => $id])->select(["id"]);
        return $data->count();
    }

    // Overrite assign class to member on view member profile
    public function get_class_by_members($mid) {
        // echo $mid;
        $class_table = TableRegistry::get("GymMemberClass");
        $class_sche_table = TableRegistry::get("ClassSchedule");
        $row = $class_table->find()->where(["member_id" => $mid])->select(["assign_class"]);
        $row = $row->leftjoin(["GymClass" => "gym_class"], ["GymMemberClass.assign_class = GymClass.id"])->select(["GymClass.name"])->hydrate(false)->toArray();
        // print_r($row); die;
        $class = "None";
        if (!empty($row)) {
            $class = "";
            foreach ($row as $data) {
                $class .= $data["GymClass"]["name"] . ",";
            }
        }
        $str = trim($class, ",");
        /// Remove dublicate class name here
        return $str = implode(',', array_unique(explode(',', $str)));
    }

    /// display schedule time display on member attendece lists.
    public function get_schedule_time_by_id($id) {
        $class_schedule_list_table = TableRegistry::get("ClassScheduleList");
        $datass = $class_schedule_list_table->find('all')->where(["id" => $id])->toArray();
        return $datass[0]["start_time"] . " - " . $datass[0]["end_time"];
    }

    /// display schedule days display on member attendece lists.
    public function get_schedule_days_by_id($id) {
        $class_schedule_list_table = TableRegistry::get("ClassScheduleList");
        $datass = $class_schedule_list_table->find('all')->where(["id" => $id])->toArray();
        //  echo $datass[0]["days"]; die;
        return @$datass[0]["days"];
    }

    // Get attadence status Overrite function
    public function get_attendance_custom_status($id, $schedule_id, $date) {
        $date = date("Y-m-d", strtotime($date));
        $att_table = TableRegistry::get("GymAttendance");
        $row = $att_table->find()->where(["user_id" => $id, "schedule_id" => $schedule_id, "attendance_date" => "{$date}"])->hydrate(false)->toArray();
        if (!empty($row)) {
            $att_status = $row[0]["status"];
            if ($att_status == 'Absent') {
                $style = "style='color:red;'";
            } else {
                $style = "style='color:green;'";
            }
            return "<span $style>" . __($att_status) . "</span>";
        } else {
            return __("Not Taken");
        }
    }

    /// display location by class id.
    public function get_location_by_class_id($id) {
        $class_schedule_table = TableRegistry::get("ClassSchedule");
        $location_table = TableRegistry::get("GymLocation");
        $row = $class_schedule_table->find()->where(["class_name" => $id])->select(["location_id"]);
        $row = $row->leftjoin(["GymLocation" => "gym_location"], ["ClassSchedule.location_id = GymLocation.id"])->select(["GymLocation.location"])->hydrate(false)->toArray();
        return ($row[0]['GymLocation']['location']);
    }

    public function get_user_name($uid) {
        $mem_table = TableRegistry::get("GymMember");
        $name = $mem_table->find('all')
                ->where(['id' => $uid])
                ->select(['first_name', 'last_name'])
                ->first();
        //echo '<pre>';print_r($name);die;
        if(empty($name)){
             return 'N/A';
        }else{
           return $name["first_name"] . " " . $name["last_name"];
        }
    }
    public function get_role_name($uid) {
        $mem_table = TableRegistry::get("GymMember");
        $name = $mem_table->find('all')
                ->where(['id' => $uid])
                ->select(['role_name'])
                ->first();
        //echo '<pre>';print_r($name);die;
       
           return $name["role_name"];
        
    }

    public function getUserLocation($uid) {

        $mem_table = TableRegistry::get("GymMember");
        $location = $mem_table->find('all')
                ->contain(['GymLocation'])
                ->select(['GymLocation.title'])
                ->where(['GymMember.id' => $uid])
                ->first();
        return  $location['GymLocation']['title'];
        //return $location['GymLocation']['location'];
    }

    public function get_membership_names($ids) {
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT `membership_label` FROM `membership` WHERE id IN ($ids)");
        return $rows = $stmt->fetchAll('assoc');
        //$res = $mem_tbl->find('all')
        //->where(['id IN' => $ids])
        //->select(['membership_label'])
        //->toArray();
        //print_r($rows);die;
    }

    public function get_membership_name($mid) {
        /*$mem_tbl = TableRegistry::get("Membership");
        $amt = $mem_tbl->get($mid)->toArray();
        
        return $amt["membership_label"];*/
         $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT membership_label,count(*) as newcount  FROM membership WHERE id='$mid'");
        $rows = $stmt->fetchAll('assoc');
        if ($rows[0]['newcount'] > 0) {
            return $rows[0]['membership_label'];
        } else {
            return ' -- ';
        }
    }

    public function usernameExist($email) {
        $member_tbl = TableRegistry::get("GymMember");
        $query = $member_tbl->find()->where(["username" => $email])->first();
        $count = intval($query->count());
        if ($count == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function emailExist($email) {
        $member_tbl = TableRegistry::get("GymMember");
        $query = $member_tbl->find()->where(["username" => $username])->first();
        $count = intval($query->count());
        if ($count == 1) {
            return true;
        } else {
            return false;
        }
    }

    ### Get Location By Licensee ID #####

    public function get_member_report_location($licenseeID) {
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT *,count(*) as newcount  FROM gym_member INNER JOIN gym_location ON  gym_member.location_id=gym_location.id WHERE gym_member.role_name='licensee' and gym_location.status='1' and gym_member.id='$licenseeID'");
        $rows = $stmt->fetchAll('assoc');
        if ($rows[0]['newcount'] > 0) {
            return $rows[0]['location'];
        } else {
            return ' -- ';
        }
    }

    public function get_member_report_location_title($licenseeID) {
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT *,count(*) as newcount  FROM gym_member INNER JOIN gym_location ON  gym_member.location_id=gym_location.id WHERE gym_member.role_name='licensee' and gym_location.status='1' and gym_member.id='$licenseeID'");
        $rows = $stmt->fetchAll('assoc');
        if ($rows[0]['newcount'] > 0) {
            return $rows[0]['title'];
        } else {
            return ' -- ';
        }
    }

    ### Membership Plan Name #####

    public function get_member_report_plan($membershipID) {
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT membership_label,count(*) as newcount  FROM membership where id='$membershipID'");
        $rows = $stmt->fetchAll('assoc');
        if ($rows[0]['newcount'] > 0) {
            return $rows[0]['membership_label'];
        } else {
            return ' -- ';
        }
    }

    

    public function get_member_report_plan_status($membshipID, $membID) {
        //echo "SELECT count(*) as active  FROM membership_payment where member_id='$membID' and (membership_id='$membshipID' AND mem_plan_status='1' and payment_status='1') ";
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT count(*) as active  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='1' OR membership_payment.mem_plan_status='2')  and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4'");
        $rows = $stmt->fetchAll('assoc');
        if (@$rows[0]['active'] > 0) {
            return '<span class="label label-sm label-success">Active</span>';
        } else {
            $stmt1 = $conn->execute("SELECT count(*) as inactive  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='3' OR membership_payment.mem_plan_status='4') and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4' ");
            // $stmt1 = $conn->execute("SELECT count(*) as inactive  FROM membership_payment where member_id='$membID' and ( mem_plan_status='3' and payment_status='1') ");
            $rows1 = $stmt1->fetchAll('assoc');
            if (@$rows1[0]['inactive'] > 0) {
                return '<span class="label label-sm label-danger">Past Client</span>';
            } else {

                $stmt2 = $conn->execute("SELECT count(*) as trail  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='1' and membership_payment.payment_status='1' and membership.membership_cat_id='4') ");
                // $stmt1 = $conn->execute("SELECT count(*) as inactive  FROM membership_payment where member_id='$membID' and ( mem_plan_status='3' and payment_status='1') ");
                $rows2 = $stmt2->fetchAll('assoc');
                if (@$rows2[0]['trail'] > 0) {
                    return '<span class="label label-sm label-warning">Trial </span>';
                } else {
                    $stmt2 = $conn->execute("SELECT count(*) as past_trail  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='3' and membership_payment.payment_status='1' and membership.membership_cat_id='4') ");
                   $rows2 = $stmt2->fetchAll('assoc');
                   if (@$rows2[0]['past_trail'] > 0) {
                       return '<span class="label label-sm label-warning">Past Trial </span>';
                   }else{
                        return '<span class="label label-sm label-info">Leads </span>';
                   }
                }
            }
        }
    }

    public function get_member_report_plan_status_custom($membshipID, $membID) {
        //echo "SELECT count(*) as active  FROM membership_payment where member_id='$membID' and (membership_id='$membshipID' AND mem_plan_status='1' and payment_status='1') ";
          $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT count(*) as active  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='1' OR membership_payment.mem_plan_status='2')  and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4'");
        $rows = $stmt->fetchAll('assoc');
        if (@$rows[0]['active'] > 0) {
            return 'Active';
        } else {
            $stmt1 = $conn->execute("SELECT count(*) as inactive  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='3' OR membership_payment.mem_plan_status='4') and membership_payment.payment_status='1' AND  membership.membership_cat_id!='4' ");
            // $stmt1 = $conn->execute("SELECT count(*) as inactive  FROM membership_payment where member_id='$membID' and ( mem_plan_status='3' and payment_status='1') ");
            $rows1 = $stmt1->fetchAll('assoc');
            if (@$rows1[0]['inactive'] > 0) {
                return 'Past Client';
            } else {

                $stmt2 = $conn->execute("SELECT count(*) as trail  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='1' and membership_payment.payment_status='1' and membership.membership_cat_id='4') ");
                // $stmt1 = $conn->execute("SELECT count(*) as inactive  FROM membership_payment where member_id='$membID' and ( mem_plan_status='3' and payment_status='1') ");
                $rows2 = $stmt2->fetchAll('assoc');
                if (@$rows2[0]['trail'] > 0) {
                    return 'Trial ';
                } else {
                    $stmt2 = $conn->execute("SELECT count(*) as past_trail  FROM membership_payment INNER JOIN membership on membership_payment.membership_id=membership.id where membership_payment.member_id='$membID' and (membership_payment.mem_plan_status='3' and membership_payment.payment_status='1' and membership.membership_cat_id='4') ");
                   $rows2 = $stmt2->fetchAll('assoc');
                   if (@$rows2[0]['past_trail'] > 0) {
                       return 'Past Trial';
                   }else{
                        return 'Leads ';
                   }
                }
            }
        }
    }

    ### Encription And Decription 

    public function encryptIt($q) {
        $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
        $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
        return( $qEncoded );
    }

    public function decryptIt($q) {
        $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
        $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
        return( $qDecoded );
    }

    public function get_location_name_d($lID) {
        $conn = ConnectionManager::get('default');
        $stmt = @$conn->execute("SELECT title,count(*) as newcount  FROM gym_location WHERE  status='1' and id='$lID'");
        $rows = $stmt->fetchAll('assoc');
        // print_r($rows); die;
        if ($rows[0]['newcount'] > 0) {
            return $rows[0]['title'];
        } else {
            return '--';
        }
    }

    public function get_location_name_ddd($lID) {
        $conn = ConnectionManager::get('default');
        $stmt = @$conn->execute("SELECT title,count(*) as newcount  FROM gym_location WHERE  id='$lID'");
        $rows = $stmt->fetchAll('assoc');
        // print_r($rows); die;
        if ($rows[0]['newcount'] > 0) {
            return $rows[0]['title'];
        } else {
            return 'N/A';
        }
    }

    public function enrollment_count_by_cslId($cslId) {
        $conn = ConnectionManager::get('default');
        $stmt = @$conn->execute("SELECT count(*) as newcount  FROM gym_attendance WHERE schedule_id='$cslId' AND status != 'Cancelled' AND status != 'cancelled_by_trainer' AND waiting = 0");
        $rows = $stmt->fetchAll('assoc');
        // print_r($rows); die;
        if ($rows[0]['newcount'] > 0) {
            return $rows[0]['newcount'];
        } else {
            return 0;
        }
    }
    
    public function isEnrolled($cslId) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT attendance_id  FROM gym_attendance WHERE schedule_id='$cslId' AND user_id = '".$session['id']."' AND status != 'Cancelled' AND status != 'cancelled_by_trainer'");
        
        if ($stmt->count()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_sgt_status($cslId) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT status FROM gym_attendance WHERE schedule_id='$cslId' AND status = 'cancelled_by_trainer'");
        
        if ($stmt->count()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_active_membership_member_id($mid) {
        // echo $mid;
        $payment_table = TableRegistry::get("MembershipPayment");
        
        $row = $payment_table->find()->where(['member_id' => $mid,'mem_plan_status'=>'1']);
        $row = $row->innerjoin(["Membership" => "membership"], ["MembershipPayment.membership_id = Membership.id"])->select(["Membership.membership_label"])->hydrate(false)->toArray();
        //print_r($row); die;
        $class = "None";
        if (!empty($row)) {
            $class = "";
            foreach ($row as $data) {
                $class .= $data["Membership"]["membership_label"] . ",";
            }
        }
        $str = trim($class, ",");
        /// Remove dublicate class name here
        return $str = implode(',', array_unique(explode(',', $str)));
    }
     public function get_active_membership_report_member_id($mid) {
        // echo $mid;
        $payment_table = TableRegistry::get("MembershipPayment");
        
        $row = $payment_table->find()->where(['member_id' => $mid,'mem_plan_status'=>'1']);
        $row = $row->innerjoin(["Membership" => "membership"], ["MembershipPayment.membership_id = Membership.id"])->select(["Membership.membership_label","MembershipPayment.end_date"])->hydrate(false)->toArray();
        
        $class = "None";
        if (!empty($row)) {
            $class = "";
            foreach ($row as $data) {
                 $end_date=strtotime ( '+1 day' , strtotime($data['end_date'])) ;
                $end_date=date('Y-m-d',$end_date);
                $class .= $data["Membership"]["membership_label"] ." (".$end_date.") ". ",";
            }
        }
        $str = trim($class, ",");
        /// Remove dublicate class name here
        return $str = implode(',', array_unique(explode(',', $str)));
    }
    /// Membership check price by location 
    
    public function get_membership_amt($amount,$mpid) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT amount  FROM membership_location WHERE memship_id='$mpid' AND location_id = '".$session['location_id']."' ");
        
        if ($stmt->count()) {
           $rows = $stmt->fetchAll('assoc');
            return $rows[0]['amount'];
           // return true;
        } else {
            return $amount;
        }
    }
    
    public function isActiveMembership($userID, $memID) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT membership_id,start_date,end_date FROM membership_payment WHERE member_id='$userID' AND membership_id = '".$memID."' AND mem_plan_status ='1' AND payment_status = '1'");
        
        if ($stmt->count()) {
            return $stmt->fetch('assoc');
        } else {
            return false;
        }
    }
     public function get_membership_status_by_userid($userID) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT membership_id  FROM membership_payment WHERE member_id='$userID'  AND mem_plan_status ='1' AND payment_status = '1'");
        
        if ($stmt->count()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_membership_amt_lice($amount,$mpid,$loc=null) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        //$associated_licensees_res = $conn->execute("SELECT id  FROM gym_member WHERE location_id='$loc' AND activated = '1'");
        //$associated_licensees_rows = $associated_licensees_res->fetchAll('assoc');
        //foreach($associated_licensees_rows as $associated_licensees_row)
            //$associated_licensees[] = $associated_licensees_row['id'];
        
        //$associated_licensees = implode(',',$associated_licensees);
        
        $stmt = $conn->execute("SELECT amount  FROM membership_location WHERE memship_id='$mpid' AND location_id = '".$loc."'");
        
        if ($stmt->count()) {
           $rows = $stmt->fetchAll('assoc');
            return $rows[0]['amount'];
           // return true;
        } else {
            return $amount;
        }
    }
    
    public function getProductDataByLocation($data, $mid = null) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if(empty($mid)){//die($mid);
            $loc = $this->getUserLocationId($session['id']);
        }else{
            $loc = $this->getUserLocationId($mid);
        }
        $stmt = $conn->execute("SELECT * FROM product_update_by_location WHERE product_id='".$data['id']."' AND location_id = '".$loc."'");
        
        if ($stmt->count()){
           $data1 = $stmt->fetch('assoc');
           $data['location_id'] = $data1['location_id'];
           $data['product_name'] = $data1['product_name'];
           $data['product_id'] = $data1['product_id'];
           $data['updated_by'] = $data1['updated_by'];
           $data['updated_at'] = $data1['updated_at'];
           $data['price'] = $data1['price'];
           $data['quantity'] = $data1['quantity'];
           $data['description'] = $data1['description'];
           $data['image'] = $data1['image'];
           $data['status'] = $data1['status'];
        }
        //echo '<pre>';print_r($data);die;
       return $data;
    }
    
    public function getUnit($unitType,$data){
        $lbsItem = array("weight","leanBodyMass","boneDensity");
        $milimeterItem = array("caliperBicep","triceps","subscapular","iliacCrest");
        $inchItem = array("neck","chest","circumferenceBicep","forearm","waist","hip","thigh","calf");
        $percentItem = array("bodyFat","waterWeight");

        if(in_array($data,$lbsItem))
                return ($unitType == 'imperial') ? 'lbs' : 'kg';
        else if(in_array($data,$milimeterItem))
                return ($unitType == 'imperial') ? 'mm' : 'mm';
        else if(in_array($data,$inchItem))
                return ($unitType == 'imperial') ? 'in' : 'in';
        else if(in_array($data,$percentItem))
                return '%';
        else
            return false;
    }
    
    public function getTargetKeys($targetIndex){
        $pieces = preg_split('/(?=[A-Z])/',$targetIndex); 
        $formattedIndex = '';
        foreach($pieces as $nameFraction){
            $formattedIndex .= ucwords(strtolower($nameFraction)).' ';
        } 
        return rtrim($formattedIndex);
    }
    
    public function get_membership_memb_count($membership_id,$location_id,$startdate,$enddate,$report_type)
    {
        //die($licensee_id);
      $where='';
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $location_id = $session['location_id'];
        if($location_id){
            $stmt = $conn->execute("SELECT id FROM gym_member WHERE location_id = '".$location_id."'");
            $result = $stmt->fetchAll('assoc');
            foreach ($result as $v)
              {
                $licensees_ids[] = $v['id'];
               }
              $where .=" AND gm.associated_licensee IN (".implode(',',$licensees_ids).")";
        }else{
            $location_id = '';
           // $licensees_ids = 0;
        }
        
        if(!empty($startdate) && !empty($enddate))
        {
            
            $from=date("Y-m-d", $startdate);
            $to=date("Y-m-d",$enddate);
            if($report_type==1)
            {
                $where .=" AND (mp.start_date >= '$from' AND mp.start_date <= '$to')"; 
            }else{
                $where .=" AND (mp.start_date >= '$from' AND mp.start_date <= '$to')"; 
            }
         
        }
        if($location_id){
            $stmt = $conn->execute("SELECT mp.member_id As mID  FROM membership_payment mp INNER JOIN gym_member gm on mp.member_id=gm.id INNER JOIN membership_payment_history mph on mp.mp_id=mph.mp_id WHERE mp.payment_status='1' AND mp.membership_id='".$membership_id."' AND gm.associated_licensee IN (".implode(',',$licensees_ids).")".$where." group by mp.member_id");
        }else{
            $stmt = $conn->execute("SELECT mp.member_id As mID  FROM membership_payment mp INNER JOIN gym_member gm on mp.member_id=gm.id INNER JOIN membership_payment_history mph on mp.mp_id=mph.mp_id WHERE mp.payment_status='1' AND mp.membership_id='".$membership_id."' ".$where." group by mp.member_id");
        }
        $rows = $stmt->fetchAll('assoc');
        return $stmt->count();
        //return $rows[0]['tmem'];
    }
    
    public function lice_cut_percentage($uid)
    {
        $mem_table = TableRegistry::get("GymMember");
        $mem_data = $mem_table->find('all')->where(['id' => $uid])->select(['cut_percent'])->first();
        return $mem_data['cut_percent'];
    }
    
    
    /*
     * @$start, $end$end in sesc
     */
    public function getDayHourMinSec($start, $end) {
	$different = $start - $end;
	$secondsInMilli = 1000;
	$minutesInMilli = $secondsInMilli * 60;
	$hoursInMilli = $minutesInMilli * 60;
	$daysInMilli = $hoursInMilli * 24;
	// $monthInMilli = $daysInMilli * 31;

	// $elapsedMonths = $different / $monthInMilli;
	// $different = $different % $monthInMilli;


	//$elapsedDays = $different / $daysInMilli;
	// $different = $different % $daysInMilli;

	$elapsedHours = $different / $hoursInMilli;
	$different = $different % $hoursInMilli;
	//
	$elapsedMinutes = $different / $minutesInMilli;
	$different = $different % $minutesInMilli;
	//
	$elapsedSeconds = $different / $secondsInMilli;
	
        return array('h'=>$elapsedHours,'m'=>$elapsedMinutes,'s'=>$elapsedSeconds);
    }
    // Fetch Location details for Staff and customer associated with their licensee
    public function getLocationOfUser($lId) {

        $gym_member_table = TableRegistry::get("GymMember");
        $row = $gym_member_table->find()->where(["id" => $lId])->select(["location_id"])->first();

        $location_table = TableRegistry::get("GymLocation");
        $row1 = $location_table->find()->where(["id" => $row['location_id']])->select(["title"])->first();

        if (!empty($row1))
            return $row1['title'];
        return '-';
    }
    
    public function getLocIdMemStaff($mId) {
        $gym_member_table = TableRegistry::get("GymMember");
        
        $row1 = $gym_member_table->find()->select(['associated_licensee'])->where(["id" => $mId])->first();
        $row = $gym_member_table->find()->select(['location_id'])->where(["id" => $row1['associated_licensee']])->first();
        
        if (!empty($row))
            return $row['location_id'];
        return false;
    }
    
    public function membership_status_for_location($mId) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if($session['role_id'] == 1){
            return true;
        }
        if( in_array($session['role_id'], array(2,6,7,8)) ){
            $loc = $session['location_id'];
        }else{
            $loc = $this->getClientTrainerLocationId($session['id']);
        }
        //echo "SELECT status FROM membership_status_for_location WHERE membership_id = '".$mId."' AND location_id = '".$loc."'";die;
        $stmt = $conn->execute("SELECT status FROM membership_status_for_location WHERE membership_id = '".$mId."' AND location_id = '".$loc."'");
        
        if ($stmt->count()) {
            $rows = $stmt->fetch('assoc');
            return ($rows['status'] == '1') ? true : false;
        } else {
            return true;
        }
    }
    
    public function product_status_for_location($pId, $mid = null) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        if($session['role_id'] == 1 && $mid !== null){
            $loc = $this->getUserLocationId($mid);
        }else if( in_array($session['role_id'], array(2,6,7,8)) ){
            $loc = $session['location_id'];
        }else if(in_array($session['role_id'], array(3,4))){
            $loc = $this->getClientTrainerLocationId($session['id']);
        }else{
            return true;
        }
        $stmt = $conn->execute("SELECT status FROM product_status_for_location WHERE product_id = '".$pId."' AND location_id = '".$loc."'");
        
        if ($stmt->count()) {
            $rows = $stmt->fetch('assoc');
            return ($rows['status'] == '1') ? true : false;
        } else {
            return true;
        }
    }
    
    public function getUserLocationId($mId){
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT role_id, location_id, associated_licensee FROM gym_member WHERE id = '".$mId."'");
        if ($stmt->count()) {
            $rows = $stmt->fetch('assoc');
            if( in_array($rows['role_id'], array(2,6,7,8) ) ){
                return $rows['location_id'];
            }else if( in_array($rows['role_id'], array(3,4) )){
                $stmt1 = $conn->execute("SELECT location_id FROM gym_member WHERE id = '".$rows['associated_licensee']."'");
                if ($stmt1->count()) {
                    $rows1 = $stmt1->fetch('assoc');
                    return $rows1['location_id'];
                }else{
                    return false;
                }
            }else{
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function get_user_email($id) {
        $activity_table = TableRegistry::get("GymMember");
        $title = $activity_table->find()->where(["id" => $id])->select(['email'])->hydrate(false);
        $title = $title->toArray();
        if (!empty($title)) {
            return $title[0]['email'];
        }
    }
    public function get_user_detail($uid) {
        //echo $uid; die;
        $mem_table = TableRegistry::get("GymMember");
        $user = $mem_table->get($uid)->toArray();
        return $user;
    }
     public function roleIdForReport($mId) {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $result=$this->get_user_detail($mId);
        $role_id=$result['role_id']; 
        return $role_id; 
    }
    // Method to find whether the any location is enabled referral module or not.
    public function isLocationEnabledReferralMod(){
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $locId = $session['location_id'];
        if($locId){
            $stmt = $conn->execute("SELECT ref_mod FROM gym_location WHERE id = '".$locId."'");
            if ($stmt->count()) {
                $rows = $stmt->fetch('assoc');
                if($rows['ref_mod'] == '1')
                    return true;
                else
                    return false;
            } else {
                return true;
            }
        }else{
            return true;
        }
    }
    
    public function hasActivePlan($logged_id, $mem_id){
        $conn = ConnectionManager::get('default');
        $q = "SELECT mp_id"
                . " FROM membership_payment"
                . " WHERE member_id = '".$mem_id."'"
                . " AND mem_plan_status = '1'"
                . " AND payment_status = '1'";
        $stmt = $conn->execute($q);
        if ($stmt->count())
            return true;
        return false;
    }
    public function getLowestPlanPriceBasedOnLocation($logged_id){
        $conn = ConnectionManager::get('default');
        $q = "SELECT m.membership_amount, mp.membership_id"
                . " FROM membership_payment mp"
                . " LEFT JOIN membership m ON m.id = mp.membership_id"
                . " WHERE mp.member_id = '".$logged_id."'"
                . " AND mp.mem_plan_status = '1'"
                . " AND mp.payment_status = '1'"
                . " ORDER BY m.membership_amount ASC"
                . " LIMIT 1";
        $stmt = $conn->execute($q);
        if ($stmt->count()){
            $data = $stmt->fetch('assoc');
            //echo '<pre>';print_r($data);die;
            $loc = $this->getLocIdMemStaff($logged_id);
            return $this->get_membership_amt_lice($data['membership_amount'],$data['membership_id'],$loc);
        }else{
           return false;
        }
    }
    

}
