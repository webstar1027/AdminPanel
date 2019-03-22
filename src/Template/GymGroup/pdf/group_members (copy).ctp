<div class="modal-header">
    <h4 class="modal-title" id="gridSystemModalLabel"><?php echo "<b>".$group['name']."</b> ",__("Group Members"); ?></h4>
</div>
<div class="modal-body">
    <div id="invoice_print"> 
        <table width="100%" border="0">
            <tbody>
                <tr>
                    <td width="70%">
                        <img style="max-height:80px;max-width:100px;" src="http://<?php echo $_SERVER['HTTP_HOST'] . $sys_data[0]["gym_logo"]; ?>">
                    </td>
                    <td align="right" width="24%">
                        <h5><?php
                            echo __('Date') . " : " . date($this->Gym->getSettings('date_format'));
                            ?>
                        </h5>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table width="100%" border="0">
            <tbody>
                <tr>
                    <td align="<?php echo $float_l; ?>">
                        <h4><?php echo __(''); ?> </h4>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="<?php echo $float_l; ?>">
                        <?php
                        echo $sys_data[0]["name"] . "<br>";
                        echo $sys_data[0]["address"] . ",";
                        echo $sys_data[0]["country"] . "<br>";
                        echo $sys_data[0]["office_number"] . "<br>";
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="text-center">S.No.</th>
                    <th class="text-center"> <?php echo __('Name'); ?></th>
                    <th class="text-center"> <?php echo __('Email'); ?></th>
                    <th class="text-center"><?php echo __('Mobile'); ?> </th>
                    <th class="text-center"><?php echo __('Location'); ?> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(count($data) > 0){
                    $i=1;
                     //print_r($data); die;
                    foreach($data as $row){
                       
                        ?>
                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center"><?php echo $row["first_name"].' '.$row["last_name"]; ?></td>
                            <td class="text-center"><?php echo $row["email"]; ?></td>
                            <td class="text-center"><?php echo $row["mobile"]; ?></td>
                            <td class="text-center"><?php echo $this->Gym->getUserLocation($row["associated_licensee"]); ?></td>
                        </tr>
                <?php 
                    $i++; }
                }else{?>
                    <tr>
                        <td colspan="5" class="text-center">There is no customer associated with this group.</td>
               <?php }
                ?>
            </tbody>
        </table>
       
        
    </div>

</div>