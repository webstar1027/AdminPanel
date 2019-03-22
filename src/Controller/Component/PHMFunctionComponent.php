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

Class PHMfunctionComponent extends Component {

   public $components = ['Aws','Phpthumb'];
      
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
            $this->generateThumb(WWW_ROOT .'upload/', WWW_ROOT."upload/thumbnails/",$thumb_img_width='480', $file_name=$new_name);
        } else {
            if (!empty($old_file_name)) {
                $new_name = $old_file_name;
            }
        }
        if ($new_name == '')
            return $new_name;
        else
            return $this->request->base . '/webroot/upload/thumbnails/' . $new_name;
    }

     public function mutliuploadImage($file, $i, $old_file_name = null) {
        $new_name = "";
       //echo "<pre>";print_r($file); die;
        $img_name = $file[$i]["name"];
      
       if (!empty($img_name)) {
            $tmp_name = $file[$i]["tmp_name"];
            $ext = substr(strtolower(strrchr($img_name, '.')), 1);
            $new_name = time() . "_" . rand(000000, 999999) . "." . $ext;
            move_uploaded_file($tmp_name, WWW_ROOT . "/upload/" . $new_name);
            $this->generateThumb(WWW_ROOT .'upload/', WWW_ROOT."upload/thumbnails/",$thumb_img_width='700', $file_name=$new_name);
        
            
        } else {
            if (!empty($old_file_name)) {
                $new_name = $old_file_name;
            }
        }
        if ($new_name == '')
            return $new_name;
        else
            return $this->request->base . '/webroot/upload/thumbnails/' . $new_name;
    }
    
    
   public function mutliuploadImageUser($file, $i, $old_file_name = null) {
        $ObjectURL = "";
        $new_name='';
      
        $img_name = $file["name"][$i];
       //echo "<pre>";print_r($file); die;
       if (!empty($img_name)) {
            $tmp_name = $file["tmp_name"][$i];
            $ext = substr(strtolower(strrchr($img_name, '.')), 1);
            $new_name = time() . "_" . rand(000000, 999999) . "." . $ext;
            $new_name_thumnail = "thumb_".$new_name;
            
            $data=$this->Aws->uploadfile($tmp_name,$new_name);
            $ObjectURL=$data['ObjectURL'];
           if($ObjectURL){
              move_uploaded_file($tmp_name, WWW_ROOT . "/upload/" . $new_name_thumnail);
              $this->generateThumbUser(WWW_ROOT .'upload/', WWW_ROOT."upload/thumbnails/",$thumb_img_width='480', $new_name_thumnail);
           }
        } else {
            
                $ObjectURL = '';
           
        }
        if ($ObjectURL == '')
            return $ObjectURL;
        else
            return $ObjectURL;
    }
    
    public function mutliuploadImageUsers($file ,$imageID) {
        $ObjectURL = "";
        $new_name='';
      
        $img_name = $file["name"];
       //echo "<pre>";print_r($file); die;
       if (!empty($img_name)) {
            $tmp_name = $file["tmp_name"];
            $ext = substr(strtolower(strrchr($img_name, '.')), 1);
            $new_name = time() . "_" . rand(000000, 999999) . "." . $ext;
           // $new_name_thumnail = "thumb_".$new_name;
             move_uploaded_file($tmp_name, WWW_ROOT . "/upload/" . $new_name);
             
             // Profile Thumb Image
             $this->profileThumb($new_name,$imageID);
             
             $this->profileThumbGallery($new_name,$imageID);
             
             $this->profileBlurThumb($new_name,$imageID);
//            $data=$this->Aws->uploadfile($tmp_name,$new_name);
//            $ObjectURL=$data['ObjectURL'];
//           if($ObjectURL){
//              move_uploaded_file($tmp_name, WWW_ROOT . "/upload/" . $new_name_thumnail);
//              $this->generateThumbUser(WWW_ROOT .'upload/', WWW_ROOT."upload/thumbnails/",$thumb_img_width='480', $new_name_thumnail);
//           }
        } 
    }
    
    
     public function profileThumb($new_name,$imageID){
             $conn = ConnectionManager::get('default');
             $this->Phpthumb->Thumblocation =  WWW_ROOT."upload/thumbnails/";           // directory in which to save the thumbnail
             $this->Phpthumb->Thumbprefix = 'thumb_';              // The prefix for the thumb filename
             $this->Phpthumb->Thumbwidth = 480;
             $this->Phpthumb->Thumbheight = 480;
             $this->Phpthumb->Createthumb(WWW_ROOT .'upload/'.$new_name, 'file');
             $thumb_img='thumb_'.$new_name;
             $moveURL=WWW_ROOT."upload/thumbnails/".$thumb_img;
             $data=$this->Aws->movefile($moveURL,$thumb_img);
             $ObjectURL=$data['ObjectURL'];
            
             $insert = $conn->execute("update pheramor_user_gallery set profile_thumb='" . $ObjectURL . "' where  id='" . $imageID . "'");
             unlink("{$moveURL}");
             return $ObjectURL;
      }
    public function profileThumbGallery($new_name,$imageID){
             $conn = ConnectionManager::get('default');
             $this->Phpthumb->Thumblocation =  WWW_ROOT."upload/thumbnails/";           // directory in which to save the thumbnail
             $this->Phpthumb->Thumbprefix = 'large_';  
             $this->Phpthumb->Thumbwidth = 700;
             $this->Phpthumb->Thumbheight = 700;
             $this->Phpthumb->Createthumb(WWW_ROOT .'upload/'.$new_name, 'file');
             $thumb_img='large_'.$new_name;
             $moveURL=WWW_ROOT."upload/thumbnails/".$thumb_img;
             $data=$this->Aws->movefile($moveURL,$thumb_img);
             $ObjectURL=$data['ObjectURL'];
             $insert = $conn->execute("update pheramor_user_gallery set name='" . $ObjectURL . "' where  id='" . $imageID . "'");
             unlink("{$moveURL}");
      }
    public function profileBlurThumb($new_name,$imageID){
             $conn = ConnectionManager::get('default');
             $this->Phpthumb->Thumblocation =  WWW_ROOT."upload/thumbnails/";           // directory in which to save the thumbnail
             $this->Phpthumb->Thumbprefix = 'blur_thumb_';              // The prefix for the thumb filename
             $this->Phpthumb->Thumbwidth = 480;
             $this->Phpthumb->Thumbheight = 480;
             $this->Phpthumb->Pixelate = array(1,10);
             $this->Phpthumb->Createthumb(WWW_ROOT .'upload/'.$new_name, 'file');
             $thumb_img='blur_thumb_'.$new_name;
             $moveURL=WWW_ROOT."upload/thumbnails/".$thumb_img;
             $data=$this->Aws->movefile($moveURL,$thumb_img);
             $ObjectURL=$data['ObjectURL'];
             $insert = $conn->execute("update pheramor_user_gallery set profile_thumb_blur='" . $ObjectURL . "' where  id='" . $imageID . "'");
             unlink("{$moveURL}");
      }
    
    public function getSettings($key) {
        $settings = TableRegistry::get("PheramorGeneralSetting");
        $row = $settings->find()->all();
        $row = $row->first()->toArray();
        $value = "";
        switch ($key) {
            CASE "company_name":
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
        $settings = TableRegistry::get("PheramorGeneralSetting");
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
    
     /**
     * Get User Profile Picture from profile page.
     * @Method Get_user_function
     * @Date 22 Aug 2017
     * @Author RNF Technologies  
     */
    public function get_user_picture($uid) {
        
        $mem_table = TableRegistry::get("PheramorUserProfile");
        $mem_data = $mem_table->find('all')->select(['image'])->where(['user_id' => $uid])->first();
      //  print_r($mem_data->first_name); die;
        if(!empty($mem_data))
        {
           return $mem_data->image;
        }else{
            return "--";
        }
    }
    
    
   /**
     * Get Latitude and Longitude from Zipcode
     * @Method getLnt
     * @Date 18 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function getLnt($zip) {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($zip)."&sensor=false";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        return $result3[0];
    }
 
     /**  * **************************** PUSH NOTIFICATIONS ********** 
     /**
     * Send Android push notification
     * @Method android_notification
     * @Date 28 Sep 2017
     * @Author RNF Technologies  
     */
     public function android_notification($push_token, $message) {
         
         $message_new = array('receiverId'=>$message['user_id'],'title'=>'Pheramor '.$message['type'], 'type' => $message['type'],'message' => array('msg'=> $message['message'], 'type' => $message['type']));
         
        define("SERVER_KEY", "AAAAxbSkifc:APA91bEQfY5Rc1rT7u4Q46uz8g9zu8Q7tVIhhtU6Ij7wdHY08ABKnEq63tYpG31koTKQqE49VsOwjniY5kdTWsVUNs95HT39PGDEQfVh8cWctKGjM3pIymlQoLn3cjZt4UTHPxGL1VVm");
        $fields = array(
            'registration_ids' => $push_token,
            'priority' => 'high',
            'data' => $message_new,
        );
        $fields_json =json_encode($fields);
        
        $headers = array(
            'Authorization: key=' . SERVER_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_json);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            $error_log = array('status' => 'Message was not delivered to android due to the error : '.curl_error($ch), 'msg'=>$message);
            Log::write('critical', json_encode($error_log));
        } else {
            $error_log = array('status' => 'Message successfully delivered.', 'msg'=>$message);
            Log::write('critical', json_encode($error_log));
        }
        curl_close($ch);
        return $result;
    }
    
    /**
     * Send IOS push notification
     * @Method ios_notification
     * @Date 28 Sep 2017
     * @Author RNF Technologies  
     */
    
    
    public function ios_notification($push_token, $message) {
        
       if(NOTIFICATION_MODE==0)
        {
             $apnsHost = 'gateway.sandbox.push.apple.com';
             $apnsCert = $_SERVER['DOCUMENT_ROOT'] . '/DevelopmentAPNS.pem';
        }else{
             $apnsHost = 'gateway.push.apple.com';
             $apnsCert = $_SERVER['DOCUMENT_ROOT'] . '/ProductionAPNS.pem';
        }
      
        //$apnsHost = 'gateway.push.apple.com';
      //  $apnsCert = $_SERVER['DOCUMENT_ROOT'] . '/pheramor.pem';
      
        $apnsPort = 2195;
        $apnsPass = '';

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $apnsPass);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        stream_set_blocking($fp, 0);
        
        if (!$fp) {
            $error_log = array('status' => 'Connected to APNS','msg'=>$message);
            Log::critical(json_encode($error_log),  $scope = ['pushLog']);
        }
        $error_log = array('err' => $err, 'errstr' => $errstr,'msg'=>$message);
        Log::critical(json_encode($error_log),  $scope = ['pushLog']);

        $body = array();
        $message_body = array(
            'alert' => $message['message'], //accept only string
            'type' => $message['type'], //optional
            'user' => $message['user'], //optional
            //'badge' => 1,
            'sound' => 'default',
            'content-available' => 1
        );

        $body['aps'] = $message_body;
        $apple_expiry = time() + (90 * 24 * 60 * 30);  // Keep push alive (waiting for delivery) for 30 days

        ksort($push_token);

        foreach ($push_token as $id => $token) {
            $apple_identifier = $id;
            $payload = json_encode($body);

            // Enhanced Notification
            $msg = pack("C", 1) . pack("N", $apple_identifier) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', $token) . pack("n", strlen($payload)) . $payload;

            $result = fwrite($fp, $msg);
        }
        usleep(500000);

        $response = $this->checkAppleErrorResponse($fp,$message);
        if ($response) {
            $keys = array_keys($push_token);
            $new_index = $keys[array_search($response, $keys)];
            $push_token_array = $this->removeElement($push_token, $new_index);
            $this->ios_notification($push_token_array, $message);
        }

        fclose($fp);
    }

    function removeElement($arr, $deletKey) {
        foreach ($arr as $key => $val) {
            if ($key != $deletKey) {
                unset($arr[$key]);
            } else {
                break;
            }
        }
        $key = array_keys($arr)[0];
        unset($arr[$key]);
        return $arr;
    }

    function checkAppleErrorResponse($fp,$message) {
        //byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID). 
        // Should return nothing if OK.
        //NOTE: Make sure to set stream_set_blocking($fp, 0) or else fread will pause your script and wait 
        // forever when there is no response to be sent. 
        $apple_error_response = fread($fp, 6);
        if ($apple_error_response) {
            // unpack the error response (first byte 'command" should always be 8)
            $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);

            if ($error_response['status_code'] == '0') {
                $error_response['status_code'] = '0-No errors encountered';
            } else if ($error_response['status_code'] == '1') {
                $error_response['status_code'] = '1-Processing error';
            } else if ($error_response['status_code'] == '2') {
                $error_response['status_code'] = '2-Missing device token';
            } else if ($error_response['status_code'] == '3') {
                $error_response['status_code'] = '3-Missing topic';
            } else if ($error_response['status_code'] == '4') {
                $error_response['status_code'] = '4-Missing payload';
            } else if ($error_response['status_code'] == '5') {
                $error_response['status_code'] = '5-Invalid token size';
            } else if ($error_response['status_code'] == '6') {
                $error_response['status_code'] = '6-Invalid topic size';
            } else if ($error_response['status_code'] == '7') {
                $error_response['status_code'] = '7-Invalid payload size';
            } else if ($error_response['status_code'] == '8') {
                $error_response['status_code'] = '8-Invalid token';
            } else if ($error_response['status_code'] == '255') {
                $error_response['status_code'] = '255-None (unknown)';
            } else {
                $error_response['status_code'] = $error_response['status_code'] . '-Not listed';
            }
            
            $error_log = array('command' => $error_response['command'], 'identifier' => $error_response['identifier'], 'status_code' => $error_response['status_code'],'message'=>$message);
            Log::critical(json_encode($error_log), $scope = ['pushLog']);
            //echo json_encode($error_log);die;
            //echo 'Identifier is the rowID (index) in the database that caused the problem, and Apple will disconnect you from server. To continue sending Push Notifications, just start at the next rowID after this Identifier.<br>';
            return $error_response['identifier'];
        }

        return false;
    }

    /*     * **************************** PUSH NOTIFICATIONS ********** */
    
    /**
     * Get Use Details for Push notification
     * @Method user_push_details
     * @Date 28 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function user_push_details($user){
        if(isset($user) && $user != ''){
            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute("SELECT * from device_token WHERE user_id='".$user."'");
            if ($stmt->count()) {
                return $stmt->fetch('assoc');
                //$this->GYMFunction->android_notification(array("f7ct_0hWvJM:APA91bGUM6ist9Dme2N8w-u1qcoX611wKmPxIQCCf5vVND88BYghP_2BvUD1ZiZCYjISDnnKNWrQ6qtTB3cSp0MSYGW2_tCbHkfTxRkiRkNnz57HcCVWOQXPcWUzBv59rwV9Go3ij2I0"),"Hi Rahil.Testing push Notification");
            }else{
                return false;
            }
        }else{
             return false;
        }
    }
    
    
    /**
     * Get customer id for card details
     * @Method get_user_card_detail
     * @Date 28 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function get_user_card_detail($uid)
    {
        $payment_table = TableRegistry::get("PheramorUserCardInfo");
        $card_data = $payment_table->find('all')->select(['customer_id'])->where(['user_id' => $uid])->first();
        if(!empty($card_data))
        {
           return $card_data->customer_id;
        }else{
            return false;
        }
        
    }
    
     /**
     * Get User  Details
     * @Method get_user_details
     * @Date 28 Sep 2017
     * @Author RNF Technologies  
     */
      
    public function get_user_details($uid)
    {
         $use_tbl = TableRegistry::get("PheramorUser");
         $data = $use_tbl->find("all")->contain(["PheramorUserProfile"])->where(['id' => $uid])->first();
         return $data;
      }
      
      /**
     * Get Card id for card details
     * @Method get_user_card_id
     * @Date 06 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function get_user_card_id($id)
    {
        $payment_table = TableRegistry::get("PheramorUserCardInfo");
        $card_data = $payment_table->find('all')->select(['card_token'])->where(['id' => $id])->first();
        if(!empty($card_data))
        {
           return $card_data->card_token;
        }else{
            return false;
        }
        
    }
    
    /**
     * Get Subscription name by id details
     * @Method get_user_card_id
     * @Date 06 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function get_subscription_name($id)
    {
        $subscription_table = TableRegistry::get("PheramorSubscription");
        $subscription_data = $subscription_table->find('all')->select(['subscription_title'])->where(['id' => $id])->first();
        if(!empty($subscription_data))
        {
           return $subscription_data->subscription_title;
        }else{
            return '--';
        }
        
    }
     /**
     * Get Subscription refund Amount
     * @Method get_total_refunded_amt
     * @Date 09 Oct 2017
     * @Author RNF Technologies  
     */
      public function get_total_refunded_amt($mpID) {
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(refund_amount) as total_ref FROM pheramor_refund_payment WHERE mpID = '" . $mpID . "' and refund_status='1'");
        $result = $stmt->fetch('assoc');
        //print_r($result); die;
        if (!empty($result)) {
            return @$result['total_ref'];
        } else {
            return 0;
        }
    }
    
    /**
     * Get Generate Thumbnail
     * @Method generateThumb
     * @Date 25 Oct 2017
     * @Author RNF Technologies  
     */
    
    function generateThumb($src_path, $thumb_path, $thumb_width, $image_file_name) {

        $src_dir = opendir($src_path);
        $img_path_info = pathinfo($src_path . $image_file_name);

        if (strtolower($img_path_info['extension']) == 'jpg') {
            $image = imagecreatefromjpeg("{$src_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'png') {
            $image = imagecreatefrompng("{$src_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'gif') {
            $image = imagecreatefromgif("{$src_path}{$image_file_name}");
        }

        $imgwidth = imagesx($image);
        $imgheight = imagesy($image);
        $new_thumb_width = $thumb_width;
        $new_thumb_height = floor($imgheight * ( $thumb_width / $imgwidth ));
        $temp_img = imagecreatetruecolor($new_thumb_width, $new_thumb_height);
        imagecopyresized($temp_img, $image, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $imgwidth, $imgheight);

        if (strtolower($img_path_info['extension']) == 'jpg') {
            imagejpeg($temp_img, "{$thumb_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'png') {
            imagepng($temp_img, "{$thumb_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'gif') {
            imagegif($temp_img, "{$thumb_path}{$image_file_name}");
        }
    }

    /**
     * Get Generate Thumbnail
     * @Method generateThumb
     * @Date 25 Oct 2017
     * @Author RNF Technologies  
     */
    
    function generateThumbUser($src_path, $thumb_path, $thumb_width, $image_file_name) {
            
        
        $src_dir = opendir($src_path);
        $img_path_info = pathinfo($src_path . $image_file_name);

        if (strtolower($img_path_info['extension']) == 'jpg') {
            $image = imagecreatefromjpeg("{$src_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'png') {
            $image = imagecreatefrompng("{$src_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'gif') {
            $image = imagecreatefromgif("{$src_path}{$image_file_name}");
        }

        $imgwidth = imagesx($image);
        $imgheight = imagesy($image);
        $new_thumb_width = $thumb_width;
        $new_thumb_height = floor($imgheight * ( $thumb_width / $imgwidth ));
        $temp_img = imagecreatetruecolor($new_thumb_width, $new_thumb_height);
        imagecopyresized($temp_img, $image, 0, 0, 0, 0, $new_thumb_width, $new_thumb_height, $imgwidth, $imgheight);

        if (strtolower($img_path_info['extension']) == 'jpg') {
            imagejpeg($temp_img, "{$thumb_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'png') {
            imagepng($temp_img, "{$thumb_path}{$image_file_name}");
        } else if (strtolower($img_path_info['extension']) == 'gif') {
            imagegif($temp_img, "{$thumb_path}{$image_file_name}");
        }
        $remove_url=$thumb_path.$image_file_name;
        $data=$this->Aws->movefile($remove_url,$image_file_name);
        unlink("{$thumb_path}{$image_file_name}");
        unlink("{$src_path}{$image_file_name}");
        
    }
    /**
     * Get Music  Category Name
     * @Method musicCategory
     * @Date 25 Sep 2017
     * @Author RNF Technologies  
     */
 
      public function musicCategory($movie_group) {
        $category_table = TableRegistry::get("PheramorMusic");
       // if($id==0) return 'Parent';
        if(!empty($movie_group)){
        $movie_groups=explode(',',$movie_group);
        $data = "";
        if (!empty($movie_groups)) {
            foreach ($movie_groups as $group) {
                  if($group>0){
                    $grp_name = $category_table->get($group)->toArray();
                    $data .= $grp_name["title"] . ",";
                  }
                
            }
            $data = trim($data, ",");
            return $data;
        } else {
            return "--";
        }
        }else{
             return "---";
        }
       
    } 
    
    /**
     * Get Movie  Category Name
     * @Method movieCategory
     * @Date 15 Sep 2017
     * @Author RNF Technologies  
     */
    public function movieCategory($movie_group) {
        $category_table = TableRegistry::get("PheramorMovies");
       // if($id==0) return 'Parent';
        if(!empty($movie_group)){
        $movie_groups=explode(',',$movie_group);
        $data = "";
        if (!empty($movie_groups)) {
            foreach ($movie_groups as $group) {
                  if($group>0){
                    $grp_name = $category_table->get($group)->toArray();
                    $data .= $grp_name["title"] . ",";
                  }
                
            }
            $data = trim($data, ",");
            return $data;
        } else {
            return "--";
        }
        }else{
             return "---";
        }
       
    } 
    
    /**
     * Get Book  Category Name
     * @Method bookCategory
     * @Date 15 Sep 2017
     * @Author RNF Technologies  
     */
    public function bookCategory($book_group) {
        $category_table = TableRegistry::get("PheramorBooks");
       // if($id==0) return 'Parent';
        if(!empty($book_group)){
        $book_groups=explode(',',$book_group);
        $data = "";
        if (!empty($book_groups)) {
            foreach ($book_groups as $group) {
                  if($group>0){
                    $grp_name = $category_table->get($group)->toArray();
                    $data .= $grp_name["name"] . ",";
                  }
                
            }
            $data = trim($data, ",");
            return $data;
        } else {
            return "--";
        }
        }else{
             return "---";
        }
       
    } 
    
   /**
     * Upload file here
     * @Method uploadImportImage
     * @Date 23 Nov 2017
     * @Author RNF Technologies  
     */ 
    
    public function uploadImportImage($file, $old_file_name = null){
        $new_name = "";
        $img_name = $file["name"];
        $file_size = $file['size'];
        $type = explode(".",$img_name);
        if(strtolower(end($type))!= 'csv'){
             return $new_name;
        }
        if (($file_size<=2)){
            return $new_name;
        }
        if (!empty($img_name)) {
            $tmp_name = $file["tmp_name"];
            $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
            if(in_array($file['type'],$mimes)){
            $ext = substr(strtolower(strrchr($img_name, '.')), 1);
            $new_name = time() . "_" . rand(000000, 999999) . "." . $ext;
            move_uploaded_file($tmp_name, WWW_ROOT . "/upload/generic/" . $new_name);
            }else{
                return $new_name;
            }
        } else {
            if (!empty($old_file_name)) {
                $new_name = $old_file_name;
            }
        }
        if ($new_name == '')
            return $new_name;
        else
            return $this->request->base . '/webroot/upload/generic/' . $new_name;
        
    }
    
     /**
     * Get User Oder Status
     * @Method orderTrackingStatus
     * @Date 5 Jan 2018
     * @Author RNF Technologies  
     */ 
    
    public function orderTrackingStatus($uid)
    {
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT order_status,id  FROM `pheramor_product_payment` WHERE user_id=$uid and payment_status='1' order by id desc limit 1");
        $rows = $stmt->fetch('assoc');
        if (!empty($rows['order_status'])) {
            return $rows;
        }else{
            return 0;
        }
    }
    
    /**
     * Get Membership Category type
     * @Method getCategoryType
     * @Date 15 March 2018
     * @Author RNF Technologies  */ 
    
    public function getCategoryType($id)
    {
       
           $conn = ConnectionManager::get('default');
           $category_table = TableRegistry::get("PheramorSubscriptionCategory");
           $categories = $category_table->find("all")->select(['category_type'])->where(['id'=>$id]);
           $categories = $categories->first();
           return $categories->category_type;
       
    }
    
   /**
     * Get User Register date in week
     * @Method getUserRegisterWeek
     * @Date 15 March 2018
     * @Author RNF Technologies  */  
    
    public function getUserRegisterWeek($id,$price_arr)
    {
        $user_table = TableRegistry::get("PheramorUser");
        $users = $user_table->find("all")->select(['created_date'])->where(['id' => $id]);
        $user = $users->first();
        $datefrom = $user->created_date;
        $dateto = date('Y-m-d H:i:s');
        $diff = strtotime($dateto, 0) - strtotime($datefrom, 0);
        $register_week = ($diff / 604800);
        if ($register_week >= 0 && $register_week <= 1) {
            return $price_arr[0];
        } else if ($register_week > 1 && $register_week <= 2) {
            return $price_arr[1];
        } else if ($register_week > 2 && $register_week <= 3) {
            return $price_arr[2];
        } else {
            return $price_arr[3];
        }
    }
    
    /**
     * Get User Referral code
     * @Method getUserReferCode
     * @Date 16 March 2018
     * @Author RNF Technologies  */  
    
    public function getUserReferCode($user_id) 
    {
        $user_table = TableRegistry::get("PheramorReferralCode");
        $users = $user_table->find("all")->select(['code'])->where(['user_id' => $user_id]);
        $user = $users->first();
        return $user->code;
        
    }
    
    
    /**
     * Get Generate Referral code private Function 
     * @Method generateReferalUid
     * @Date 22 March 2018
     * @Author RNF Technologies  */
    
    private function generateReferalUid($user_id) {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 10; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT * from pheramor_referral_code WHERE code='" . $res . "'");
        if ($stmt->count()) {
            return false;
        } else {
            $conn->execute("insert into pheramor_referral_code set code='" . $res . "' , user_id='".$user_id."', created_at='".date('Y-m-d H:i:s')."'");
            return $res;
        }
    }

    /**
     * Get Generate Referral code
     * @Method generateReferralCode
     * @Date 22 March 2018
     * @Author RNF Technologies  */  
    
   public function generateReferralCode($user_id){
       $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT * from pheramor_referral_code WHERE user_id='" . $user_id . "'");
        if ($stmt->count() == 0) {
            if (!empty($user_id) || !is_numeric($user_id)) {
                while ($quid == false) {
                    $quid = $this->generateReferalUid($user_id);
                }
            }
        }
    }
    
    /**
     * Check generic type data
     * @Method generateReferralCode
     * @Date 22 March 2018
     * @Author RNF Technologies  */  
    
   public function checkGenticData($id){
       
        $user_table = TableRegistry::get("PheramorGeneticInformation");
        $users = $user_table->find("all")->select(['HLA_A_1'])->where(['id' => $id]);
        $user = $users->first();
        if(!empty($user->HLA_A_1)){
            return true;
        }else{
            return false;
        }
   }
    
    /**
     * Get Shipping address
     * @Method get_user_shipping_address
     * @Date 10 April 2018
     * @Author RNF Technologies  */  
    
   public function get_user_shipping_address($id){
       
        $user_table = TableRegistry::get("PheramorUserShippingAddress");
        $users = $user_table->find("all")->where(['user_id' => $id]);
        $user = $users->first();
        if(!empty($user->user_id)){
            return $user;
        }else{
            return false;
        }
   } 
   
    /**
     * Check User Generic Kit Connected 
     * @Method check_kit_connected
     * @Date 18 May 2018
     * @Author RNF Technologies  */ 
   
   public function check_kit_connected($user_id, $kitstatus,$kit_id){
       $conn = ConnectionManager::get('default');
       $where='';
       if(!empty($kit_id)){ $where .=" AND pheramor_kit_ID='$kit_id'"; }
      if (empty($kitstatus)) {
            $stmt = $conn->execute("SELECT * from pheramor_genetic_information WHERE user_id='" . $user_id . "'");
            if ($stmt->count() > 0) { return false;}else{ return true;}
        } else {
            
            $stmt = $conn->execute("SELECT * from pheramor_genetic_information WHERE user_id='" . $user_id . "' $where");
            if ($stmt->count() > 0) { return true;}else{ return false;}
      }
       
    }
   
   
   
    

}


