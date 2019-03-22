<?php
############# Membership Update cron ###########
$curl = curl_init();
$fp = fopen("renew_payment_cron.txt", "w");
chmod('renew_payment_cron.txt', 0777);
curl_setopt ($curl, CURLOPT_URL, "http://localhost/gym_master/member-registration/cron-autopay");
curl_setopt($curl, CURLOPT_FILE, $fp);
$result=curl_exec ($curl);
print_r($result);
curl_close ($curl);
?>

