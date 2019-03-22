<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Client Card List');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Client Card Lists"); ?>
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
                             if ($session["role_name"] == "administrator" || $session["role_name"] == "manager" || $session["role_name"] == "licensee" || $session["role_name"] == "admin" || $session["role_name"] == "subadmin") {
                                ?>

                                <a href="<?php echo $this->Gym->createurl("MembershipPayment", "addCard"); ?>" class="btn sbold green"><?php echo __("Add Card Details"); ?> <i class="icon-doc"></i></a>

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
                         <th><?php echo __('Title', 'gym_mgt'); ?></th>
                        <th><?php echo __('Member Name', 'gym_mgt'); ?></th>
                        <th><?php echo __('Amount', 'gym_mgt'); ?></th>
                        <!--<th><?php echo __('Paid Amount', 'gym_mgt'); ?></th>
                        <th><?php echo __('Due Amount', 'gym_mgt'); ?></th>-->
                        <th><?php echo __('Start Date', 'gym_mgt'); ?></th>
                        <th><?php echo __('End Date', 'gym_mgt'); ?></th>
                        <th><?php echo __('Payment Status', 'gym_mgt'); ?></th>
                        <th><?php echo __('Plan Status', 'gym_mgt'); ?></th>
                        <th><?php echo __('Action', 'gym_mgt'); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                    <?php
                    if (!empty($data)) {
                        foreach ($data as $row) {
                            
                            if($row['mem_plan_status'] == 1 && $row['payment_status'] == 1 ){
                                $plan_status = "<span class='label label-success'>Current</span>";
                                
                            }else if ($row['mem_plan_status'] == 2 && $row['payment_status'] == 0) {
                                $plan_status = "<span class='label label-warning'>Wish</span>";
                                
                            }else if ($row['mem_plan_status'] == 0 && $row['payment_status'] == 1 ){
                                $plan_status = "<span class='label label-warning'>Disabled</span>";
                            }else if ($row['mem_plan_status'] == 0 && $row['payment_status'] ==  0){
                                $plan_status = "<span class='label label-default'>Pending</span>";
                            }else if ($row['mem_plan_status'] == 3 && $row['payment_status'] ==  1){
                                $plan_status = "<span class='label label-danger'>Expired</span>";
                            }else if ($row['mem_plan_status'] == 2 && $row['payment_status'] ==  1){
                                $plan_status = "<span class='label label-danger'>Upgrade</span>";
                            }
                            
                            if( __($this->Gym->get_membership_paymentstatus($row['mp_id'])) == 'Paid'){
                                $pay_status = "<span class='label label-success'>".__($this->Gym->get_membership_paymentstatus($row['mp_id']))."</span>";
                            }else if(__($this->Gym->get_membership_paymentstatus($row['mp_id'])) == 'Not Paid'){
                                $pay_status = "<span class='label label-default'>".__($this->Gym->get_membership_paymentstatus($row['mp_id']))."</span>";
                            }else if(__($this->Gym->get_membership_paymentstatus($row['mp_id'])) == 'Partially Paid'){
                                $pay_status = "<span class='label label-warning'>".__($this->Gym->get_membership_paymentstatus($row['mp_id']))."</span>";
                            }
                            // $due = ($row['membership_amount']- $row['paid_amount'])+($row['membership']['signup_fee']);
                            $due = ($row['membership_amount'] - $row['paid_amount']);
                            echo "<tr>
								<td>{$row['membership']['membership_label']}</td>
								<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>
								<td>" . $this->Gym->get_currency_symbol() . " {$row['membership_amount']}</td>
								<!--<td>" . $this->Gym->get_currency_symbol() . " {$row['paid_amount']}</td>
								<td>" . $this->Gym->get_currency_symbol() . " {$due}</td>-->    
								<td>" . date($this->Gym->getSettings("date_format"), strtotime($row["start_date"])) . "</td>
								<td>" . date($this->Gym->getSettings("date_format"), strtotime($row["end_date"])) . "</td>
								<td>".$pay_status ."</td>
                                                                <td>".$plan_status."</td>
								<td>";
                                                                echo "<div class='btn-group'>
                                                                    <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                                        <i class='fa fa-angle-down'></i>
                                                                    </button>
                                                                    <ul class='dropdown-menu pull-right' role='menu'>
                                                                        <li>";
                                                                if($due <= 0){
                                                                    echo "<a href='javascript:void(0)'  onclick=\"alert('No Dues')\"><i class='icon-credit-card'></i> Pay Amount</a>";
                                                                }else{
                                                                    echo "<a href='javascript:void(0)' class='amt_pay' data-url='" . $this->request->base . "/GymAjax/gymPay/{$row['mp_id']}'><i class=' icon-credit-card'></i> Pay Amount</a>";
                                                                }
								echo "</li><li><a href='javascript:void(0)' class='view_invoice' data-url='" . $this->request->base . "/GymAjax/viewInvoice/{$row['mp_id']}'><i class='icon-book-open'></i> View Invoice</a></li>
                                                                <li><a href='" . $this->request->base . "/MembershipPayment/MembershipEdit/{$row['mp_id']}' title='Edit'> <i class='icon-pencil'></i> Edit Membership</a></li>";
                                                                //<li><a href='" . $this->request->base . "/MembershipPayment/membershipUnsubscribe/{$row['mp_id']}' class='btn btn-flat btn-primary' title='Unsubscribe'><i class='fa fa-ban'></i></a></li>
                                                                echo "<li><a href='" . $this->request->base . "/membership-payment/pdf-view/2/{$row['mp_id']}' class='' title='Download PDF'><i class='fa fa-file-pdf-o'></i> Download Invoice</a></li>";
                                                                if ($session["role_name"] == "administrator" || $session["role_name"] == "manager" || $session["role_name"] == "licensee") {
                                                                    echo "<li><a href='" . $this->request->base . "/MembershipPayment/deletePayment/{$row['mp_id']}' onclick=\"return confirm('Are you sure,You want to delete this record?')\"> <i class='icon-trash'></i> Delete Membership</a></li>";
                                                                }
                                                                echo "</ul></div></td>
						</tr>";
                        }
                    }
                    ?>
                  
                   
                    


                </tbody>
            </table>
        </div>
    </div>
  </div>
