<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px;">
  <tbody>
 
       <tr><td style="color:#000000;"> &nbsp;</td></tr>
    <tr>
      <td style="color:#000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="81%" style="font-size:30px;"><h1>Report</h1></td>
              <td width="21%" style="font-size:30px;"><strong>&nbsp;Issue Date:</strong> <?php echo date($this->Gym->getSettings('date_format'));?><br>
                <strong>Report Type :</strong> Cafe </td>
            </tr>
           <!-- <tr>
              <td colspan="2" style="border-bottom:3px solid #ed4934; height:30px;"></td>
            </tr>-->
          </tbody>
        </table></td>
    </tr>
    
    <tr>
        <td style="color:#000000;height:30px;">
            
           <?php foreach($users as $user){ ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tbody>
              <tr><td colspan="5">&nbsp;</td></tr>
              <tr><th colspan="5" style="color:#000; font-size:40px;"><span style="color:#ed4934;">Cafe Name : </span><?php echo $user['title'];?></th></tr>
               <tr>
              <td colspan="5" style="border-bottom:3px solid #ed4934; height:5px;"></td>
             </tr>
              <tr><td colspan="5">&nbsp;</td></tr>
              
              
              <tr>
              <th scope="col" style="color:#ed4934; font-size:38px; width:5%">#</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:30%">Name</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:30%">Email</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:20%">Check-in Date</th>
              <th align="left" style="color:#ed4934; font-size:38px;width:15%">Time</th>
             </tr>
            <tr><td colspan="5" style="border-bottom:3px solid #ed4934; height:0px;"></td></tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            
            <?Php
           // die;
                $i=1;
             if (!empty($cafe_check_data)) {
                  foreach ($cafe_check_data as $val) {
                     if($val['cafe_id']==$user['id']){
                 $user_data=$this->Pheramor->get_user_details($val['user_id']);
               ?>
                <tr>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $i?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $user_data['pheramor_user_profile'][0]['first_name']." ".$user_data['pheramor_user_profile'][0]['last_name'];?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo $user_data['email']?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo date('F j, Y', strtotime($val['check_in_time']))?></td>
                <td align="left" style="height:25px;font-size: 30px;"><?php echo date('h:i:s A', strtotime($val['check_in_time']))?></td>
                
                
                </tr>
                        
                <?php 
                    $i++; 
                  }else{
                     //  echo '<tr><td colspan="5" align="center" style="height:25px;font-size: 30px;">Sorry, no check-in member in this cafe </td></tr>';
                  }
                  }
                }else{
                    echo '<tr><td colspan="5" align="center" style="height:25px;font-size: 30px;">Sorry, no check-in member in this cafe </td></tr>';
                }?>
            <tr>
              <td colspan="5" style="border-bottom:3px solid #ccc; height:0px;">&nbsp;</td>
            </tr>
           <tr><td colspan="5   " style="height:100px;">&nbsp;</td></tr>
          
          </tbody>
          </table>
            
           <?php } ?>
            
            
        </td>
 </tr>
    <tr><td>&nbsp;</td></tr>
   
   
    
    
  </tbody>
</table>

