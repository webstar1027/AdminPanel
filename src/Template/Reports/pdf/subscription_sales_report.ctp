<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px;">
  <tbody>
 
       <tr><td style="color:#000000;"> &nbsp;</td></tr>
    <tr>
      <td style="color:#000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="81%" style="font-size:30px;"><h1>Report</h1></td>
              <td width="21%" style="font-size:30px;"><strong>&nbsp;Issue Date:</strong> <?php echo date($this->Gym->getSettings('date_format'));?><br>
                <strong>Report Type :</strong>Sales </td>
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
              <tr><td colspan="9">&nbsp;</td></tr>
              <tr>
              <th scope="col" style="color:#ed4934; font-size:38px; width:5%">#</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:10%">Name</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%">Email</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%">Subscription Name</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%">Subscription Price</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:10%">Paid Amount</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:10%"> Payment Date</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:10%"> Payment Status</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:10%"> Plan Status</th>
            </tr>
            <tr><td colspan="9" style="border-bottom:3px solid #ed4934; height:0px;"></td></tr>
            <tr><td colspan="9">&nbsp;</td></tr>
            
            <?Php
                $i=1;
             foreach ($users as $row)
              { 
                $product_name=$this->Pheramor->get_subscription_names($row['subscription_id']);
                if($row['payment_status']==1){$status='Paid';}else{$status='Failed';}
                if ($row['plan_status'] == 1) {$planstaus='Active';}else if($row['plan_status'] == 3){$planstaus='Expired';}else if($row['plan_status'] == 4){$planstaus='Unsubscribe';}else {$planstaus='--';}
        
               ?>
                <tr>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $i?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $row['first_name']." ".$row['last_name']?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $row['email']?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $product_name[0]['subscription_title']?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo "$".$row['subscription_amount']?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo "$".$row['paid_amount']?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo date('Y-m-d',strtotime($row['payment_date']));?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $status?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $planstaus?></td>
                
                </tr>
                        
                <?php 
                    $i++; 
                }?>
            <tr>
              <td colspan="9" style="border-bottom:3px solid #ccc; height:0px;">&nbsp;</td>
            </tr>
          
          </tbody>
          </table>
        </td>
 </tr>
    <tr><td>&nbsp;</td></tr>
   
   
    
    
  </tbody>
</table>

