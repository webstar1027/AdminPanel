<?php
############# Membership Update cron ###########
$curl = curl_init();
$fp = fopen("payroll-cron.txt", "w");
chmod('payroll-cron.txt', 0777);
curl_setopt ($curl, CURLOPT_URL, "http://gotribe.rnf.tech/member-registration/cron-payroll");
curl_setopt($curl, CURLOPT_FILE, $fp);
$result=curl_exec ($curl);
print_r($result);
curl_close ($curl);
?>

