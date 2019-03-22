<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\View\Helper\UrlHelper;
use Cake\Datasource\ConnectionManger;
use Cake\Mailer\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

Class GYMfunctionComponent extends Component {

    public function sanitize_string($str) {
        $str = urldecode($str);
        $str = filter_var($str, FILTER_SANITIZE_STRING);
        $str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
        return $str;
    }

    var $helpers = array('Url'); //Loading Url Helper

    public function createurl($controller, $action) {
        return $this->Url->build(["controller" => $controller, "action" => $action]);
    }

    public function getSpecializations($sIds) {
        // @sIds string ["1","2"]
        // make it array
        $sIds = str_replace('[', '', $sIds);
        $sIds = str_replace(']', '', $sIds);
        $sIds = str_replace('"', '', $sIds);
        $sIdsArr = explode(",", $sIds);

        $specialization_table = TableRegistry::get("specialization");
        $row = $specialization_table->find()->where(["id IN " => $sIdsArr])->select(["name"])->toArray();

        if (!empty($row)) {
            $class = "";
            foreach ($row as $data) {
                $class .= $data["name"] . ",";
            }
        }
        return trim($class, ",");
    }

    public function uploadImage($file, $old_file_name = null) {
        $new_name = "";
        $img_name = $file["name"];
        if (!empty($img_name)) {
            $tmp_name = $file["tmp_name"];
            $ext = substr(strtolower(strrchr($img_name, '.')), 1);
            $new_name = time() . "_" . rand(000000, 999999) . "." . $ext;
            move_uploaded_file($tmp_name, WWW_ROOT . "/upload/" . $new_name);
        } else {
            if (!empty($old_file_name)) {
                $new_name = $old_file_name;
            }
        }
        if ($new_name == '')
            return $new_name;
        else
            return $this->request->base . '/webroot/upload/' . $new_name;
    }

    public function getSettings($key) {
        $settings = TableRegistry::get("PheramorGeneralSetting");
        $row = $settings->find()->all();
        $row = $row->first()->toArray();
        $value = "";
        switch ($key) {
            CASE "name":
                $value = $row[$key];
                break;
            CASE "company_logo":
                $value = $row[$key];
                break;
            CASE "date_format":
                $value = $row[$key];
                break;
            CASE "country":
                $value = $row[$key];
                break;
            CASE "enable_rtl":
                $value = $row[$key];
                break;
            CASE "weight":
                $value = $row[$key];
                break;
            CASE "height":
                $value = $row[$key];
                break;
            CASE "chest":
                $value = $row[$key];
                break;
            CASE "waist":
                $value = $row[$key];
                break;
            CASE "thing":
                $value = $row[$key];
                break;
            CASE "arms":
                $value = $row[$key];
                break;
            CASE "fat":
                $value = $row[$key];
                break;
            CASE "waist":
                $value = $row[$key];
                break;
            CASE "member_can_view_other";
                $value = $row[$key];
                break;
            CASE "enable_message":
                $value = $row[$key];
                break;
            CASE "paypal_email":
                $value = $row[$key];
                break;
            CASE "currency":
                $value = $row[$key];
                break;
            CASE "enable_sandbox":
                $value = $row[$key];
                break;
            CASE "enable_alert":
                $value = $row[$key];
                break;
            CASE "reminder_message":
                $value = $row[$key];
                break;
            CASE "reminder_days":
                $value = $row[$key];
                break;
            CASE "email":
                $value = $row[$key];
                break;
            CASE "staff_can_view_other_member":
                $value = $row[$key];
                break;
            CASE "calendar_lang":
                $value = $row[$key];
                break;
            CASE "system_installed":
                $value = $row[$key];
                break;
            CASE "left_header":
                $value = $row[$key];
                break;
            CASE "footer":
                $value = $row[$key];
                break;
            CASE "datepicker_lang":
                $value = $row[$key];
                break;
            CASE "sys_language":
                @$value = $row[$key];
                break;
            CASE "system_version":
                @$value = (isset($row[$key])) ? $row[$key] . ".0" : "1.0";
                break;
        }
        return $value;
    }

    public function date_format() {
        $settings = TableRegistry::get("GeneralSetting");
        $row = $settings->find()->all();
        $row = $row->first()->toArray();
        $value = $row["date_format"];
        return $value;
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

    public function index() {
        /* $msg = "First line of text\nSecond line of text";
          $to = "priyal@dasinfomedia.com";
          mail($to,"My subject",$msg); */
        $this->autoRender = false;
    }

    public function word_list_for_translation() {
        $months = array(__("January"), __("February"), __("March"), __("April"),
            __("May"), __("June"), __("July"), __("August"), __("September"), __("October"), __("November"), __("December"),
            __("You are not authorized to access that location."));
    }

    public function getActionsByRoles($role_id, $controller) {
        $access_tbl = TableRegistry::get("PheramorAccessright");
        $row = $access_tbl->find("all", array('conditions' => array('controller' => $controller, 'FIND_IN_SET(\'' . $role_id . '\',assigned_roles)')))->hydrate(false)->toArray();
        $actions = array();
        foreach ($row as $data) {
            $actions[] = lcfirst($data["action"]);
        }
        return $actions;
    }

    public function pre($param) {
        echo '<pre>';
        print_r($param);
        echo '</pre>';
        die('<br/>pre');
    }

    /**
     * Sends email to user's email address.
     * @param $id
     * @return
     */
    function sendEmail($mailArr = null) {
        if (!empty($mailArr)) {
            //echo '<pre>';print_r($mailArr);die();
            $email = new Email();
            $email->emailFormat($mailArr['emailFormat']);
            $email->from(['info@pheramor.com' => 'Pheramor']);
            $email->to($mailArr['to']);
            $email->template($mailArr['template']);
            $email->subject($mailArr['subject']);

            if (isset($mailArr['addTo'])) {
                $email->addTo($mailArr['addTo']);
            }
            if (isset($mailArr['viewVars'])) {
                $email->viewVars($mailArr['viewVars']);
            }
            if (isset($mailArr['cc'])) {
                $email->bcc($mailArr['cc']);
            }
            if (isset($mailArr['addCc'])) {
                $email->bcc($mailArr['addCc']);
            }
            if (isset($mailArr['bcc'])) {
                $email->bcc($mailArr['bcc']);
            }
            if (isset($mailArr['addBcc'])) {
                $email->bcc($mailArr['addBcc']);
            }



            $email->send();

            return true;
        }
        return false;
    }

    /**
     * Generate a unique hash / referal Url from referal token.
     * @param Object User
     * @return Object User
     */
    public function __generateReferalUrl($action, $code = null) {
        $access_tbl = TableRegistry::get("ReferralCode");
        $session = $this->request->session()->read("User");
        if ($action == 'encrypt') {
            $row = $access_tbl->find()->where(['user_id' => $session['id']])->first();
            //echo Security::encrypt($row['code'], Configure::read('Security.key'));die;
            return $this->encrypt_decrypt($action, $row['code']);
        }

        return $this->encrypt_decrypt($action, $code);
    }

    /**
     * simple method to encrypt or decrypt a plain text string
     * initialization vector(IV) has to be the same when encrypting and decrypting
     * PHP 5.4.9 ( check your PHP version for function definition changes )
     *
     * this is a beginners template for simple encryption decryption
     * before using this in production environments, please read about encryption
     * use at your own risk
     *
     * @param string $action: can be 'encrypt' or 'decrypt'
     * @param string $string: string to encrypt or decrypt
     *
     * @return string
     */
    private function encrypt_decrypt($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    /**
     * Use for Encryption
     * @param $q
     * @Date 22 Aug 2017
     * @Author RNF Technologies  
     */
    public function encryptIt($q) {
        $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
        $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
        return( $qEncoded );
    }

    /**
     * Use for Decryption
     * @param $q
     * @Date 22 Aug 2017
     * @Author RNF Technologies  
     */
    public function decryptIt($q) {
        $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
        $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
        return( $qDecoded );
    }

     /**
     * Get User name from profile page.
     * @Method Get_user_function
     * @Date 22 Aug 2017
     * @Author RNF Technologies  
     */
    public function get_user_name($uid) {
        
        $mem_table = TableRegistry::get("PheramorUserProfile");
        $mem_data = $mem_table->find('all')->select(['first_name','last_name'])->where(['user_id' => $uid])->first();
      //  print_r($mem_data->first_name); die;
        if(!empty($mem_data))
        {
           return $mem_data->first_name . " " . $mem_data->last_name;
        }else{
            return "--";
        }
    }
    
}
