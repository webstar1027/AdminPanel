
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px;">
  <tbody>
 
       <tr><td style="color:#000000;"> &nbsp;</td></tr>
    <tr>
      <td style="color:#000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="81%" style="font-size:30px;"><h1>Report</h1></td>
              <td width="21%" style="font-size:30px;"><strong>&nbsp;Issue Date:</strong> <?php echo date($this->Gym->getSettings('date_format'));?><br>
                <strong>Report Type :</strong> <?php echo $group['name'];?> </td>
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
              <tr><td colspan="5">&nbsp;</td></tr>
            <tr>
              <th scope="col" style="color:#ed4934; font-size:38px;">#</th>
              <th align="left" style="color:#ed4934; font-size:38px;">Name</th>
              <th align="left" style="color:#ed4934; font-size:38px;">Email</th>
              <th align="left" style="color:#ed4934; font-size:38px;">Mobile</th>
              <th align="left" style="color:#ed4934; font-size:38px;">Location</th>
            </tr>
            <tr><td colspan="5" style="border-bottom:3px solid #ed4934; height:0px;"></td></tr>
            <tr><td colspan="5">&nbsp;</td></tr>
             <?php 
                if(count($data) > 0){
                    $i=1;
                     //print_r($data); die;
                    foreach($data as $row){
                       
                        ?>
                            <tr>
                             <td scope="col" style="height:25px;font-size: 30px;"><?=$i?></td>
                             <td align="left" style="height:25px;font-size: 30px;"><?php echo $row["first_name"].' '.$row["last_name"]; ?></td>
                             <td align="left" style="height:25px;font-size: 30px;"><?php echo $row["email"]; ?></td>
                             <td align="left" style="height:25px;font-size: 30px;"><?php echo $row["mobile"]; ?></td>
                             <td align="left" style="height:25px;font-size: 30px;"><?php echo $this->Gym->getUserLocation($row["associated_licensee"]); ?></td>
                           </tr>
                        
                <?php 
                    $i++; }
                }else{?>
                    <tr>
                        <td colspan="5" class="text-center">There is no customer associated with this group.</td></tr>
               <?php }
                ?>
            
            <tr>
              <td colspan="5" style="border-bottom:3px solid #ccc; height:0px;">&nbsp;</td>
            </tr>
          
          </tbody>
        </table></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
   
   
    
    
  </tbody>
</table>






