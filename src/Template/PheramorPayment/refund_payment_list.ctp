<?php 
$this->Html->addCrumb('Manage Members', array('controller' => 'PheramorUser', 'action' => 'memberList'));
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Refund Payment List');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Refund Payment List"); ?>
                </span>

            </div>
            <div class="actions">
                <div class="tools"> 
                 <a href="<?php echo $this->Gym->createurl("PheramorUser","memberList"); ?>" class="btn sbold green"><?php echo __("Manage Members"); ?> <i class="fa fa-list"></i></a>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <p>&nbsp;</p> 
                         </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>


                    <tr>
                                <th><?php echo __("#");?></th>
                                <th><?php echo __("Name");?></th>
                                <th><?php echo __("Email");?></th>
                                <th><?php echo __("Refund Amount");?></th>
                                <th><?php echo __("Refunded Date");?></th>
                                <th><?php echo __("Refund Type");?></th>
                                <th><?php echo __("Status");?></th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $total_count=0;
                    $total_amt=0;
                    $k=1;
                       if(!empty($data)){
			foreach($data as $row)
			{
                          //  print_r($row);
                            $amount=number_format($row['refund_amount'],2);
                          
                             if ($row['refund_status'] == 0 ) {
                             $plan_status = "<span class='label label-danger'>Failed</span>";
                              } else if ($row['refund_status'] == 1) {
                                    $total_amt=$total_amt+$amount;
                              $plan_status = "<span class='label label-success'>Confirm</span>";
                              }
                              $user_deatils=$this->Pheramor->get_user_details($row['user_id']);
                              $profile_data=$user_deatils['pheramor_user_profile'][0];
                             //echo "<pre>"; print_r($user_deatils); die;
                              echo "<tr>";
				echo "<td>{$k}</td>
                                    <td><a href='{$this->request->base}/PheramorUser/viewMember/{$row['user_id']}'>{$profile_data['first_name']} {$profile_data['last_name']}</a></td>
                                   <td>".($user_deatils['email'])."</td>
                                    <td>$ {$amount}</td>
                                    <td>".date($this->Pheramor->getSettings("date_format"),strtotime($row['refund_date']))." </td>
                                    <td> {$row['refund_type']}</td>
                                    <td>".$plan_status." </td></tr>";
                                    $total_count++;
			}
                       }
			?>
                   
                </tbody>
                  <thead>
                        <tr>
                            <th colspan="3"><?php //echo __("Total Members: ") . $total_count; ?></th>
                            <th>&nbsp;</th> 
                            <th colspan="3"><?php echo __("Total Refund: $") . number_format($total_amt,2); ?></th>					
                         </tr>
                     </thead>
            </table>
        </div>
    </div>
</div>

