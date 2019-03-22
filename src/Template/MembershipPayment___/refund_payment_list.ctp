<?php 
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
                <div class="tools"> </div>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <?php
                            if($session["role_name"] == "administrator" || $session["role_name"] == "manager" || $session["role_name"] == "licensee" || $session["id"] == 1 || $session["id"] == 2 ){
                                ?>
                                    <!--<a href="<?php echo $this->Gym->createurl("MembershipPayment","refundPayment"); ?>" class="btn sbold green"><?php echo __("Add Refund Payment"); ?> <i class="fa fa-plus"></i></a>-->

                            <?php } ?>

                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>


                    <tr>
                                <th><?php echo __("Member ID");?></th>
                                <th><?php echo __("Name");?></th>
                                <th><?php echo __("Refund Amount");?></th>
                                <th><?php echo __("Refunded By");?></th>
                                <th><?php echo __("Refunded Date");?></th>
                                <th><?php echo __("Refund Type");?></th>
                                <th><?php echo __("Status");?></th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $total_count=0;
                    $total_amt=0;
                       if(!empty($data)){
			foreach($data as $row)
			{
                          //  print_r($row);
                            $amount=number_format($row['amount'],2);
                            $total_amt=$total_amt+$amount;
                             if ($row['status'] == 0 ) {
                             $plan_status = "<span class='label label-danger'>Failed</span>";
                              } else if ($row['status'] == 1) {
                              $plan_status = "<span class='label label-success'>Confirm</span>";
                              }
                              echo "<tr>";
				echo "<td>{$row['gym_member']['member_id']}</td>
                                    <td><a href='{$this->request->base}/GymMember/viewMember/{$row['gym_member']['id']}'>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</a></td>
                                    <td>$ {$amount}</td>
                                    <td>".$this->Gym->get_user_name($row['created_by'])."</td>
                                    <td>".date($this->Gym->getSettings("date_format"),strtotime($row['created_date']))." </td>
                                    <td> {$row['refund_type']}</td>
                                    <td>".$plan_status." </td></tr>";
                                    $total_count++;
			}
                       }
			?>
                   
                </tbody>
                  <thead>
                        <tr>
                            <th colspan="3"><?php echo __("Total Members: ") . $total_count; ?></th>
                            <th>&nbsp;</th> 
                            <th colspan="3"><?php echo __("Total Refund: $") . $total_amt; ?></th>					
                         </tr>
                     </thead>
            </table>
        </div>
    </div>
</div>

