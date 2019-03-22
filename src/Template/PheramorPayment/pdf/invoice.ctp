
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
                   $issue_date = $data[0]['membership']['created_date']->format($sys_data[0]['date_format']);
                   $issue_date = date($sys_data[0]['date_format'], strtotime($issue_date));
                  echo $issue_date;?><br>
                <strong>Status :</strong> <?php echo $this->Gym->get_membership_paymentstatus($mp_id);?> </td>
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
                echo $sys_data[0]["name"] . "<br>";
                echo $sys_data[0]["address"] . ",";
                echo $sys_data[0]["country"] . "<br>";
                echo $sys_data[0]["office_number"] . "<br>";
                ?>
              </td>
              <td width="13%" style="font-size:30px;"><h3>Bill To </h3>
                <?php
                $member_id = $data[0]["member_id"];
                echo $data[0]["gym_member"]["first_name"] . " " . $data[0]["gym_member"]["last_name"] . "<br>";
                echo $data[0]["gym_member"]["address"] . ",";
                echo $data[0]["gym_member"]["city"] . ",";
                echo $data[0]["gym_member"]["zipcode"] . ",<BR>";
                echo $data[0]["gym_member"]["state"] . ",";
                echo $sys_data[0]["country"] . ",";
                echo $data[0]["gym_member"]["mobile"] . "<br>";
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
              <tr><td colspan="4">&nbsp;</td></tr>
            <tr>
              <th scope="col" style="color:#ed4934; font-size:38px; width:10%">#</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:30%">Membership Type</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:30%">Membership Fee</th>
              <th align="center" style="color:#ed4934; font-size:38px; width:30%">Total</th>
              
            </tr>
            <tr><td colspan="4" style="border-bottom:3px solid #ed4934; height:0px;"></td></tr>
            <tr><td colspan="4">&nbsp;</td></tr>
             
            <tr>
             <td scope="col" style="height:25px;font-size: 30px;">1</td>
             <td align="center" style="height:25px;font-size: 30px;"><?php echo $data[0]["membership"]["membership_label"]; ?></td>
             <td align="center" style="height:25px;font-size: 30px;"><?php echo $this->Gym->get_currency_symbol(); ?> <?php echo number_format((float)($data[0]["membership_amount"]), 2, '.', ''); ?></td>
             <td align="center" style="height:25px;font-size: 30px;"><?php echo $this->Gym->get_currency_symbol(); ?> <?php echo $subtotal = number_format((float)($data[0]["membership_amount"]), 2, '.', ''); ?></td>

           </tr>
             <tr>
              <td colspan="4" style="border-bottom:3px solid #ccc; height:0px;">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="right" style="height:30px;font-size: 30px;"><strong>Subtotal :</strong> <?php echo $this->Gym->get_currency_symbol(); ?> <?php echo number_format((float)($subtotal), 2, '.', ''); ?></td>
            </tr>
            <tr>
                <td colspan="4" align="right" style="height:30px;font-size: 30px;"><strong>Payment Made : </strong><?php echo $this->Gym->get_currency_symbol(); ?> <?php echo number_format((float)($data[0]["paid_amount"]), 2, '.', ''); ?></td>
            </tr>
            <tr>
                <td colspan="4" align="right" style="height:30px;font-size: 30px;"> <strong>Due Amount :</strong> <?php echo $this->Gym->get_currency_symbol(); ?> <?php echo number_format((float)($subtotal - $data[0]["paid_amount"]), 2, '.', '');  ?></td>
            </tr>
            <tr>
              <td colspan="4" style="border-bottom:3px solid #ccc; height:30px;"></td>
            </tr>
          
          </tbody>
        </table></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <?php if (!empty($history_data)) { ?>
    
   <tr>
       <td style="color:#000000;height:20px;font-size: 30px;" ><h2>Payment History</h2></td>
    </tr>
    
    <tr>
      <td style="color:#000000;height:20px;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td colspan="4" style="border-bottom:3px solid #ed4934;">&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
    </tr>
     <tr>
       <td style="color:#000000;height:30px;;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <th width="40%" align="left" scope="col" style="color:#ed4934; font-size:38px;">Date</th>
      <th width="35%" align="left" scope="col" style="color:#ed4934; font-size:38px;">Amount</th>
      <th width="25%" align="center" scope="col" style="color:#ed4934; font-size:38px;">Method</th>
     
    </tr>
      <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
     <?php foreach ($history_data as $retrive_date) { ?>
     <tr>
      <td  align="left" style="height:30px;font-size: 30px;"><?php echo date($sys_data[0]['date_format'], strtotime($retrive_date["paid_by_date"])); ?></td>
      <td  align="left" style="height:30px;font-size: 30px;"><?php echo $this->Gym->get_currency_symbol(); ?> <?php echo $retrive_date["amount"]; ?></td>
      <td  align="center" style="height:30px;font-size: 30px;"><?php echo $retrive_date["payment_method"]; ?></td>
     </tr>
     <?php } ?>
  </tbody>
</table>
</td>
    </tr>
    <tr>
              <td style="border-bottom:3px solid #ccc; height:30px;"></td>
            </tr>
     <?php } ?>
    
  </tbody>
</table>













