
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px;">
  <tbody>
 
       <tr><td style="color:#000000;"> &nbsp;</td></tr>
    <tr>
      <td style="color:#000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="81%" style="font-size:30px;"><h1>Invoice</h1></td>
              <td width="21%" style="font-size:30px;"><strong>&nbsp;Issue Date:</strong> 
                  <?php 
                   $issue_date = date('Y-m-d');
                   $issue_date = date($sys_data[0]['date_format'], strtotime($issue_date));
                  echo $issue_date;
                  
                   if ($data[0]['payment_status'] == 1) {
                        $status = "Paid";
                    } else {
                        $status = "Failed";
                    }
                  ?><br>
                <strong>Status :</strong> <?php echo $status;?> </td>
            </tr>
            <tr>
              <td colspan="2" style="border-bottom:3px solid #ed4934; height:30px;"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td style="color:#000000;height:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
              <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
              <td width="87%" style="font-size:30px;"><h3>Payment To </h3>
               <?php
                echo $sys_data[0]["company_name"] . "<br>";
                echo $sys_data[0]["company_address"] . ",";
                echo $sys_data[0]["company_phone"] . "<br>";
              //  echo $sys_data[0]["office_number"] . "<br>";
                ?>
              </td>
              <td width="13%" style="font-size:30px;"><h3>Bill To </h3>
                <?php
                 $user_data=$this->Pheramor->get_user_details($data[0]['user_id']);
                $user_profile_data=$user_data['pheramor_user_profile'][0];
                $member_id = $user_profile_data["member_id"];
                echo $user_profile_data["first_name"] . " " . $user_profile_data["last_name"] . "<br>";
                echo $user_profile_data["address"] . ",";
                echo $user_profile_data["city"] . ",";
                 echo $user_profile_data["state"] . ",";
                echo $user_profile_data["zipcode"] . ",<BR>";

                //echo $user_profile_data["country"] . ",";
                echo $user_profile_data["mobile"] . "<br>"; 
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" style="border-bottom:3px solid #ed4934; height:30px;"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td style="color:#000000;height:30px;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
              <tr><td colspan="6">&nbsp;</td></tr>
            <tr>
              <th scope="col" style="color:#ed4934; font-size:38px; width:5%">#</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:20%">Subscription Name</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:20%">Subscription Price</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:20%">Discount Amount</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:20%">Paid Amount</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:15%">Status</th>
              
            </tr>
            <tr><td colspan="6" style="border-bottom:3px solid #ed4934; height:0px;">&nbsp;</td></tr>
            <tr><td colspan="6">&nbsp;</td></tr>
             
            <tr>
             <td scope="col" style="height:25px;font-size: 30px;">1</td>
             <td align="center" style="height:25px;font-size: 30px;"><?php $name=$this->Pheramor->get_subscription_names($data[0]["subscription_id"]); echo $name[0]['subscription_title']; ?></td>
             <td align="center" style="height:25px;font-size: 30px;"><?php echo $this->Pheramor->get_currency_symbol(); ?> <?php echo $data[0]["subscription_amount"]; ?></td>
             <td align="center" style="height:25px;font-size: 30px;"><?php echo $this->Pheramor->get_currency_symbol(); ?> <?php echo $data[0]["discount_amount"]; ?></td>
             <td align="center" style="height:25px;font-size: 30px;"><?php echo $this->Pheramor->get_currency_symbol(); ?> <?php echo number_format((float) ($data[0]["paid_amount"]), 2, '.', ''); ?></td>
             <td align="center" style="height:25px;font-size: 30px;"><?php echo $status; ?></td>

           </tr>
             <tr>
              <td colspan="6" style="border-bottom:3px solid #ccc; height:0px;">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6" align="right" style="height:30px;font-size: 30px;"><strong>Subscription Amount :</strong> <?php echo $this->Gym->get_currency_symbol(); ?> <?php echo number_format((float)($data[0]["subscription_amount"]), 2, '.', ''); ?></td>
            </tr>
            <tr>
                <td colspan="6" align="right" style="height:30px;font-size: 30px;"><strong>Discount Amount : </strong><?php echo $this->Gym->get_currency_symbol(); ?> <?php echo number_format((float)($data[0]["discount_amount"]), 2, '.', ''); ?></td>
            </tr>
            <tr>
                <td colspan="6" align="right" style="height:30px;font-size: 30px;"> <strong>Paid Amount :</strong> <?php echo $this->Gym->get_currency_symbol(); ?> <?php echo number_format((float)($data[0]["paid_amount"]), 2, '.', '');  ?></td>
            </tr>
            <tr>
              <td colspan="6" style="border-bottom:3px solid #ccc; height:30px;"></td>
            </tr>
          
          </tbody>
        </table></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
   
    
  </tbody>
</table>













