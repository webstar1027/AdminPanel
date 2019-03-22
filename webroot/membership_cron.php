<?php
############# Membership Update cron ###########
$curl = curl_init();
$fp = fopen("membership-cron.txt", "w");
chmod('membership-cron.txt', 0777);
curl_setopt ($curl, CURLOPT_URL, "http://gotribe.rnf.tech/member-registration/cron-membership");
curl_setopt($curl, CURLOPT_FILE, $fp);
$result=curl_exec ($curl);
print_r($result);
curl_close ($curl);
?>

