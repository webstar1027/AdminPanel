<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px;">
  <tbody>
 
       <tr><td style="color:#000000;"> &nbsp;</td></tr>
    <tr>
      <td style="color:#000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="70%" style="font-size:30px;"><h1>Report</h1></td>
              <td width="30%" style="font-size:30px;"><strong>&nbsp;Issue Date:</strong> <?php echo date($this->Gym->getSettings('date_format'));?><br>
                <strong>Report Type :</strong> Refund Payment </td>
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
              <tr><td colspan="7">&nbsp;</td></tr>
              <tr>
              <th scope="col" style="color:#ed4934; font-size:38px; width:5%">#</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:20%">Name</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%">Email</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%">Amount</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%">Date</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%"> Mode</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%"> Status</th>
            </tr>
            <tr><td colspan="7" style="border-bottom:3px solid #ed4934; height:0px;"></td></tr>
            <tr><td colspan="7">&nbsp;</td></tr>
            
            <?Php
                $i=1;
             foreach ($users as $row)
              { 
                if($row['refund_status']==1){$status='Confirm';}else{$status='Failed';}
                 $user_data=$this->Pheramor->get_user_details($row['user_id']);
                 $amount=number_format($row['refund_amount'],2);
               ?>
                <tr>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $i?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $user_data['pheramor_user_profile'][0]['first_name']." ".$user_data['pheramor_user_profile'][0]['last_name'];?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $user_data['email']?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo "$ ".$amount?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo date('Y-m-d',strtotime($row['refund_date']))?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $row['refund_type'];?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $status?></td>
                
                </tr>
                        
                <?php 
                    $i++; 
                }?>
            <tr>
              <td colspan="7" style="border-bottom:3px solid #ccc; height:0px;">&nbsp;</td>
            </tr>
          
          </tbody>
          </table>
        </td>
 </tr>
    <tr><td>&nbsp;</td></tr>
   
   
    
    
  </tbody>
</table>

