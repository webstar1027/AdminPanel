<?php
//die('connected..');
 //echo $_SERVER['DOCUMENT_ROOT'] . '/GOTRIBEAPNS.pem';die;
//set_time_limit(0);
ini_set('max_execution_time', 0); //0=NOLIMIT
//database connection details
$con = mysqli_connect("gotribeprod-1.cluster-cpqh8v9nt9lt.us-west-1.rds.amazonaws.com", "gotribe_aws", "PHeYEbrest?3&r6r", "gotribe_prod");

// Check connection
if (mysqli_connect_errno())
    echo "Failed to connect to MySQL: " . mysqli_connect_error();

$sqls = "select * from push_notification";
$raw = mysqli_query($con, $sqls);

$final = array();
$device_address = array();
/*while ($rows = mysqli_fetch_assoc($raw)) {

    $device_address[] = $rows['device_address'];

}*/
//$device_address = array('dMYXn6w74uk:APA91bHVCY0W9E_aZXTei73Ba6quEe5XUXXRRyMZPOTXj9ZyPNsPqet2jmGkqEbFIDR_sfGdjms-zY09DiyT2Y1ZkpzMWSPNEwSZm-PLTHAwJCzeRao78EEXBnBUFWhHHYOer2SmKUQt');
//echo '<pre>';print_r($device_address);die;
//$rows['id'] = $rows;
//$rows['user'] = 130013;
//$rows['device_type'] = 'an';
/*$rows['device_address'] = [
    'dktFVz6EF54:APA91bGJSPFzD_vtrBaeyBf-A6fturNmId_z5OG6Tu9pGZTo0MXZR8yxRhvy93HLIUgLpeoBqpFAmYEJVT6245RLhilxqnTF_8UjFFiFAXbOCqmXClItu25cDzjCqgOFUXhaoeULBT6w',
    'dktFVz6EF54:APA91bGJSPFzD_vtrBaeyBf-A6fturNmId_z5OG6Tu9pGZTo0MXZR8yxRhvy93HLIUgLpeoBqpFAmYEJVT6245RLhilxqnTF_8UjFFiFAXbOCqmXClItu25cDzjCqgOFUXhaoeULBT6w',
    'dktFVz6EF54:APA91bGJSPFzD_vtrBaeyBf-A6fturNmId_z5OG6Tu9pGZTo0MXZR8yxRhvy93HLIUgLpeoBqpFAmYEJVT6245RLhilxqnTF_8UjFFiFAXbOCqmXClItu25cDzjCqgOFUXhaoeULBT6w',
];*/
while ($rows = mysqli_fetch_assoc($raw)) {

    //$device_address[] = $rows['device_address'];
    $message = array('message' => "Hey! We have pushed out new updated GoTRIBE App on the App Store. Kindly update your App.", 'type' => 'GEN', 'user' => $rows['user']);
    if ($rows['device_type'] == 'an') {
        //$final[] = android_notification(array($rows['device_address']), $message);
        $final[] = android_notification(array($rows['device_address']), $message);
    } else {
        $final[] = ios_notification(array($rows['device_address']), $message);
    }
}
function android_notification($push_token, $message) {
    define("SERVER_KEY", "AIzaSyBiiEPOB_oNH_yCuWn8R3PQxskZALvKzTg");
    $fields = array(
        'registration_ids' => $push_token,
        'priority' => 'high',
        'data' => $message,
    );
    $fields_json = json_encode($fields);

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
    
    $final_an =array();
    $final_an['result'] = $result;
    
    if ($result === FALSE) {
        $error_log = array('status' => 'Message was not delivered to android due to the error : ' . curl_error($ch), 'msg' => $message);
        $final_an['custom_error'] = $error_log;
        print_r($final_an);die;
    } else {
        $error_log = array('status' => 'Message successfully delivered.', 'msg' => $message);
    }
    curl_close($ch);
    return $final_an;
}

function ios_notification($push_token, $message) {
    //$apnsHost = 'gateway.sandbox.push.apple.com';
    $apnsHost = 'gateway.push.apple.com';
    $apnsCert = $_SERVER['DOCUMENT_ROOT'] . '/GOTRIBEAPNS.pem';
    $apnsPort = 2195;
    $apnsPass = '123456';

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $apnsPass);

    // Open a connection to the APNS server
    $fp = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
    stream_set_blocking($fp, 0);

    if (!$fp) {
        $error_log = array('status' => 'Connected to APNS', 'msg' => $message);
        //Log::critical(json_encode($error_log), $scope = ['pushLog']);
    }
    $error_log = array('err' => $err, 'errstr' => $errstr, 'msg' => $message);
    //Log::critical(json_encode($error_log), $scope = ['pushLog']);
    $final_an['custom_error'] = $error_log;
    
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

    $response = checkAppleErrorResponse($fp, $message);
    if ($response) {
        $keys = array_keys($push_token);
        $new_index = $keys[array_search($response, $keys)];
        $push_token_array = removeElement($push_token, $new_index);
        ios_notification($push_token_array, $message);
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

function checkAppleErrorResponse($fp, $message) {
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

        $error_log = array('command' => $error_response['command'], 'identifier' => $error_response['identifier'], 'status_code' => $error_response['status_code'], 'message' => $message);
        //Log::critical(json_encode($error_log), $scope = ['pushLog']);
        //echo json_encode($error_log);die;
        //echo 'Identifier is the rowID (index) in the database that caused the problem, and Apple will disconnect you from server. To continue sending Push Notifications, just start at the next rowID after this Identifier.<br>';
        return $error_response['identifier'];
    }

    return false;
}
echo "Notification has been sent successfully!. Please <a href='".$_SERVER['HTTP_HOST']."'>go back</a>";
mysql_close($con);
?>
