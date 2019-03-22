<?php
$this->Html->addCrumb('List Members', array('controller' => 'GymMember', 'action' => 'memberList'));
$this->Html->addCrumb('View Member');
//echo json_encode($cal_array); die;
$base_url = $this->request->base;
?>

<script>
    $(".content-wrapper").css("height", "2500px");
    $(document).ready(function () {
        $(".sub-history").dataTable({
            "responsive": true,
            "order": [[1, "asc"]],
            "aoColumns": [
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true}],
            "language": {<?php echo $this->Gym->data_table_lang(); ?>}
        });

        var box_height = $(".box").height();
        var box_height = box_height + 100;
        $(".content").css("height", box_height + "px");
    });
</script>
<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-eye font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase"> <?php echo __("View Member"); ?>

            </span>
        </div>
        <div class="top">

            <div class="btn-set pull-right">
                <a href="<?php echo $this->Gym->createurl("GymMember", "memberList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Members List"); ?></a>

            </div>
        </div>
    </div>
    <div class="portlet-body">
        <div class="">

            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="portlet green-meadow box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Personal Information </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row static-info">
                                        <div class="col-md-5 name"> <?php echo __("Member ID"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $data['member_id']; ?>

                                        </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-5 name"> <?php echo __("Name"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $data['first_name']; ?></div>
                                    </div>
                                    <!-- <div class="row static-info">
                                         <div class="col-md-5 name"> <?php echo __("Address"); ?>: </div>
                                         <div class="col-md-7 value"><?php echo $data['address']; ?>
                                          </div>
                                     </div>-->
                                    <div class="row static-info">
                                        <div class="col-md-5 name"> <?php echo __("Email"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $data['email']; ?> </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-5 name"> <?php echo __("Mobile No"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $data['mobile']; ?> </div>
                                    </div>
                                    <!--<div class="row static-info">
                                       <div class="col-md-5 name"> <?php echo __("Joining Date"); ?>: </div>
                                       <div class="col-md-7 value"> <?php echo date($this->Gym->getSettings("date_format"), strtotime($data['created_date'])); ?> </div>
                                   </div>-->
                                    <div class="row static-info">
                                        <div class="col-md-5 name"> <?php echo __("Date Of Birth"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo date($this->Gym->getSettings("date_format"), strtotime($data['birth_date'])); ?> </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-5 name"> <?php echo __("Gender"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $data['gender']; ?> </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="portlet blue-hoki box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Other Information </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <!--<div class="row static-info">
                                        <div class="col-md-5 name"> <?php echo __("Username"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $data['username']; ?> </div>
                                    </div>-->
                                    <div class="row static-info">
                                        <div class="col-md-5 name"><?php echo __("Address"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $data['address'] . " , " . $data['city']; ?></div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-5 name"><?php echo __("Location"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $this->Gym->getUserLocation($data['associated_licensee']); ?></div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-5 name"><?php echo __("Assigned Licensee"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $this->Gym->get_user_name($data['associated_licensee']); ?></div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-5 name"><?php echo __("Assigned Trainer"); ?>: </div>
                                        <div class="col-md-7 value"> <?php echo $this->Gym->get_user_name($data['assign_staff_mem']); ?></div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-5 name"><?php echo __(" Member Status"); ?>: </div>
                                        <div class="col-md-7 value"><?php echo $data['activated'] == 1 ? "Active" : "Inactive"; ?> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------- Client's Calendar------------>
                        
                                <!-- Calender-->
                                <div class="row">
                                <div class="workout-calender-chart">
                                    <div class="col-xs-12 col-sm-12 workout-calender-chart-left">
                                        <div class="portlet box green">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                   <i class="fa fa-calendar-plus-o"></i>
                                                       <?php echo $data['first_name'].' '.$data['last_name']."'s Calendar"; ?> 
                                                </div>
                                                <div class="tools">
                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                    <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">

                                                <div id="calendars"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                </div>
                        <!------------------- Client's Calendar------------>
                    <div class="row">

                        <!--- Membership subscriptions -->
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet yellow box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Subscription Management</div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: none;" >

                                    <div class="portlet-titl col-md-12 col-sm-12">

                                        <div class="top">
                                            <?php //if ($session["role_name"] != 'staff_member') { ?>
                                                <div class="btn-set pull-right" style="padding-bottom: 10px;">
                                                    <a href="<?php echo $this->Gym->createurl("MembershipPayment", "generatePaymentInvoices/" . $user_id); ?>" class="btn blue"><i class="fa fa-bars"></i> Add Subscription</a>

                                                </div>
                                            <?php //} ?>
                                        </div>

                                    </div>


                                    <div class="table-responsive1">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo __('Title', 'gym_mgt'); ?></th>
                                                    <th><?php echo __('Amount', 'gym_mgt'); ?></th>
                                                    <th><?php echo __('Paid Amount', 'gym_mgt'); ?></th>
                                                    <th><?php echo __('Start Date', 'gym_mgt'); ?></th>
                                                    <th><?php echo __('End Date', 'gym_mgt'); ?></th>
                                                    <th><?php echo __('Payment Status', 'gym_mgt'); ?></th>
                                                    <th><?php echo __('Plan Status', 'gym_mgt'); ?></th>
                                                    <th><?php echo __('Action', 'gym_mgt'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (count($subscription)) {
                                                    foreach ($subscription as $row) {
                                                        if ($row['mem_plan_status'] == 1 && $row['payment_status'] == 1) {
                                                            $plan_status = "<span class='label label-success'>Current</span>";
                                                        } else if ($row['mem_plan_status'] == 2 && $row['payment_status'] == 0) {
                                                            $plan_status = "<span class='label label-warning'>Wish</span>";
                                                        } else if ($row['mem_plan_status'] == 0 && $row['payment_status'] == 1) {
                                                            $plan_status = "<span class='label label-warning'>Disabled</span>";
                                                        } else if ($row['mem_plan_status'] == 0 && $row['payment_status'] == 0) {
                                                            $plan_status = "<span class='label label-default'>Pending</span>";
                                                        } else if ($row['mem_plan_status'] == 0 && $row['payment_status'] == 2) {
                                                            $plan_status = "<span class='label label-default'>Pending</span>";
                                                        } else if ($row['mem_plan_status'] == 3 && $row['payment_status'] == 1) {
                                                            $plan_status = "<span class='label label-danger'>Expired</span>";
                                                        } else if ($row['mem_plan_status'] == 3 && $row['payment_status'] == 2) {
                                                            $plan_status = "<span class='label label-danger'>Pending</span>";
                                                        } else if ($row['mem_plan_status'] == 2 && $row['payment_status'] == 1) {
                                                            $plan_status = "<span class='label label-danger'>Upgraded</span>";
                                                        } else if ($row['mem_plan_status'] == 4 && $row['payment_status'] == 1) {
                                                            $plan_status = "<span class='label label-info'>Unsubscribe</span>";
                                                        } else if ($row['mem_plan_status'] == 4 && $row['payment_status'] == 0) {
                                                            $plan_status = "<span class='label label-info'>Unsubscribe</span>";
                                                        }

                                                        if ($row['payment_status'] == 1) {
                                                            $pay_status = "<span class='label label-success'> Paid</span>";
                                                        } else if ($row['payment_status'] == 0) {
                                                            $pay_status = "<span class='label label-default'>Not Paid</span>";
                                                        } else if ($row['payment_status'] == 2) {
                                                            $pay_status = "<span class='label label-danger'>Failed</span>";
                                                        }
                                                        // $due = ($row['membership_amount']- $row['paid_amount'])+($row['membership']['signup_fee']);
                                                        //  echo "<pre>";print_r($row);
                                                        $due = $row['paid_amount'];
                                                        echo "<tr>
								<td>{$row['membership']['membership_label']}</td>
								<td>" . $this->Gym->get_currency_symbol() . " {$row['membership_amount']}</td>
								<td>" . $this->Gym->get_currency_symbol() . " {$row['paid_amount']}</td>
								<!--<td>" . $this->Gym->get_currency_symbol() . " {$due}</td>-->    
								<td>" . date($this->Gym->getSettings("date_format"), strtotime($row["start_date"])) . "</td>
								<td>" . date($this->Gym->getSettings("date_format"), strtotime($row["end_date"])) . "</td>
								<td>" . $pay_status . "</td>
                                                                <td>" . $plan_status . "</td>
								<td>";
                                                        
                                                            echo "<div class='btn-group'>
                                                                    <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                                        <i class='fa fa-angle-down'></i>
                                                                    </button>
                                                                    <ul class='dropdown-menu pull-right' role='menu'>
                                                                        <li>";
                                                            if ($due > 0) {
                                                                //echo "<a href='javascript:void(0)'  onclick=\"alert('No Dues')\"><i class='icon-credit-card'></i> Pay Amount</a>";
                                                            } else {
                                                                //echo "<a href='javascript:void(0)' class='amt_pay' data-url='" . $this->request->base . "/GymAjax/gymPay/{$row['mp_id']}/1'><i class=' icon-credit-card'></i> Pay Amount</a>";
                                                               // echo "<a href='javascript:void(0)' class='amt_pay' data-url='" . $this->request->base . "/GymAjax/gymPay/{$row['mp_id']}/0'><i class=' icon-credit-card'></i> Generate Invoices</a>";
                                                            }
                                                            
                                                            echo "</li><li><a href='javascript:void(0)' class='view_invoice' data-url='" . $this->request->base . "/GymAjax/viewInvoice/{$row['mp_id']}'><i class='icon-book-open'></i> View Invoice</a></li>";
                                                            if ($session["role_name"] != 'staff_member') {
                                                                echo "<li><a href='" . $this->request->base . "/MembershipPayment/MembershipEdit/{$row['mp_id']}' title='Edit'> <i class='icon-pencil'></i> Edit Membership</a></li>";
                                                            }
                                                            //<li><a href='" . $this->request->base . "/MembershipPayment/membershipUnsubscribe/{$row['mp_id']}' class='btn btn-flat btn-primary' title='Unsubscribe'><i class='fa fa-ban'></i></a></li>
                                                            echo "<li><a href='" . $this->request->base . "/membership-payment/pdf-view/2/{$row['mp_id']}' class='' title='Download PDF'><i class='fa fa-file-pdf-o'></i> Download Invoice</a></li>";
                                                            if ($session["role_name"] == "administrator" || $session["role_name"] == "manager" || $session["role_name"] == "licensee") {

                                                                // echo "<li><a href='" . $this->request->base . "/MembershipPayment/deletePayment/{$row['mp_id']}' onclick=\"return confirm('Are you sure,You want to delete this record?')\"> <i class='icon-trash'></i> Delete Membership</a></li>";
                                                            }
                                                           // echo $run_date=date('Y-m-d',strtotime($row["end_date"]));
                                                            if (($row['payment_status'] == 2) && (strtotime($row["end_date"]) >= strtotime(date('Y-m-d'))) ) {
                                                                  echo "<li class='divider'> </li>";
                                                                     echo "<li>
                                                                <a href='{$this->request->base}/MembershipPayment/runTransaction/{$row['mp_id']}/{$row['member_id']}'>
                                                                    <i class='icon-action-undo'></i> Run Transaction

                                                                </a>
                                                            </li>";
                                                                }
                                                                
                                                                
                                                            if ($session["role_name"] != 'staff_member') {
                                                            if ($session["role_id"] == 1 || $session["role_id"] == 2 || $session["role_id"] == 7 || $session["role_id"] == 8 || $session["role_id"] ==6) {
                                                                if ($row['payment_status'] == 1 ) {
                                                                    echo "<li class='divider'> </li>";
                                                          if($row['mem_plan_status'] == 1) {             
                                                       echo "<li>
                                                        <a href='{$this->request->base}/GymMember/unsubscribe/{$row['mp_id']}/{$row['member_id']}'>
                                                            <i class='icon-user-unfollow'></i> Unsubscribe Membership

                                                        </a>
                                                       </li>";
                                                          }

                                                                    echo "<li>
                                                        <a href='{$this->request->base}/MembershipPayment/RefundPayment/{$row['mp_id']}/{$row['member_id']}'>
                                                            <i class='icon-action-undo'></i> Refund Payment

                                                        </a>
                                                    </li>";
                                                                }
                                                           
                                                                echo "</ul></div>";
                                                            }
                                                            echo "</td>
						</tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr align='center'><td colspan='7'>There is no any subscription</td></tr>";
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--- End here -->
                         <?php //if ($session["role_name"] != 'staff_member') { ?>
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet green-meadow box">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i>Customer Card Information </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">
                                        <div class="portlet-titl col-md-12 col-sm-12">

                                        <div class="top">
                                            <?php //if ($session["role_name"] != 'staff_member') { ?>
                                                <div class="btn-set pull-right" style="padding-bottom: 10px;">
                                                    <a href="<?php echo $this->Gym->createurl("MembershipPayment", "addCard/" . $user_id); ?>" class="btn blue"><i class="fa fa-bars"></i> Add Card Details</a>

                                                </div>
                                            <?php //} ?>
                                        </div>

                                    </div>
                                        <div class="table-responsive1">
                                            <table class="table table-hover table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width:100px;"> S.N. </th>
                                                    <th> Card Holder Name</th>
                                                    <th> Card Type</th>
                                                    <th> Card Number </th>
                                                    <th> Created Date </th>
                                                    <th> Set Default Card </th>
                                                   <?php if ($session["role_name"] != 'staff_member') {?>
                                                       <th> Action </th>
                                                   <?php }?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($card_data)) {
                                                    $k =1;
                                                    foreach ($card_data as $row) {
                                                        if($row['default_card_id'] == '1'){
                                                            $checked = "checked";
                                                        }else{
                                                            $checked = "";
                                                        }
                                                        echo "<tr> <td>{$k}</td>
                                                        <td>{$row["cardholderName"]}</td>
                                                         <td>{$row["cardType"]}</td>
                                                         <td>{$row["maskedNumber"]}</td>";
                                                         echo"<td>" . date($this->Gym->getSettings("date_format"), strtotime($row["created_date"]->format('Y-m-d'))) ."</td>";
                                                         echo '<td>
                                                            <div class="md-radio col-md-3">
                                                                <input type="radio" id="checkbox1_2_'.$row['id'].'" '.$checked.' value="1" data-id="'.$row['id'].'" name="set_default_card" class="check_limit md-radiobtn">
                                                                <label for="checkbox1_2_'.$row['id'].'">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span>
                                                                </label>
                                                            </div>
                                                        </td>';  
                                                        if ($session["role_name"] != 'staff_member') {
                                                            echo " <td><a  href='{$this->request->base}/MembershipPayment/delete-card/{$row['id']}/{$row['member_id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\" > Remove Card</a></td>";
                                                            }
                                                echo "</tr>";
                                                     $k++;
                                                    }
                                                }else{
                                                   echo "<tr align='center'><td colspan='5'>There is no record</td></tr> ";
                                                }
                                                ?>

                                            </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                     <?php //} ?>    
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet red box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Customer Notes</div>
                                    <div class="tools">
                                        <?php if($session['role_id'] == 3 || $session['role_id'] == 6){?>
                                        <!--<a href="<?php echo $this->Gym->createurl("CustomerNotes", "addCustomerNotes/" . $user_id); ?>">  <i class="fa fa-plus"></i></a>-->
                                        <?php } ?>
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: none;">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="btn-group">
                                                <?php
                                                if ($session["role_id"] == 3 || $session["role_id"] == 6) {
                                                    ?>
                                                    <a href="<?php echo $this->Gym->createurl("CustomerNotes", "addCustomerNotes/" . $user_id); ?>" class="btn sbold green"><?php echo __("Add Note"); ?> <i class="fa fa-plus"></i></a>
                                                <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo __("Title"); ?></th>
                                                    <th><?php echo __("Comment"); ?></th>
                                                    <th><?php echo __("Note For"); ?></th>
                                                    <th><?php echo __("Class"); ?></th>
                                                    <th><?php echo __("Added By"); ?></th>
                                                    <th><?php echo __("Asso. Licensee"); ?></th>
                                                    <th><?php echo __("Action"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
if (count($notes)) {
    foreach ($notes as $row) {
        echo "<tr>";
        echo "<td>{$row['note_title']}</td>
                                                                <td>{$row['comment']}</td>
                                                                <td>" . ucwords($row['NoteForCN']['first_name'] . " " . $row['NoteForCN']['last_name']) . "</td>
                                                                <td>" . ($row['gym_clas']['name']) . "</td>
                                                                <td>" . ucwords($row['CreatedByCN']['first_name'] . " " . $row['CreatedByCN']['last_name']) . "</td>
                                                                <td>" . ucwords($row['AssociatedLicenseeCN']['first_name'] . " " . $row['AssociatedLicenseeCN']['last_name']) . "</td>
                                                                <td>";
        echo "<a href='javascript:void(0)' id='{$row['id']}' data-url='" . $this->request->base . "/GymAjax/view_customer_notes' class='view_jmodal btn btn-small' >
                                                                                   <i class='icon-eye'></i> View Customer Note</a>
                                                                      ";
        /* echo "<div class='btn-group'>
          <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
          <i class='fa fa-angle-down'></i>
          </button>";
          echo "<ul class='dropdown-menu pull-right' role='menu'>";
          if($session["id"] == $row['CreatedByCN']['id'] || $session["role_id"] == 1 || $session["role_id"] == 2){

          echo "<li>
          <a href='".$this->request->base ."/customer-notes/edit-customer-notes/{$row['id']}'>
          <i class='icon-pencil'></i> Edit Customer Note </a>
          </li>
          <li>
          <a href='{$this->request->base}/customer-notes/delete-customer-notes/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
          <i class='icon-trash'></i> Delete Customer Note </a>
          </li>";

          }
          echo "<li>
          <a href='javascript:void(0)' id='{$row['id']}' data-url='".$this->request->base ."/GymAjax/view_customer_notes' class='view_jmodal' >
          <i class='icon-eye'></i> View Customer Note</a>
          </li></ul>";
          echo "</div>"; */
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr align='center'><td colspan='7'>There is no note</td></tr>";
}
?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Section Start Here -->
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet blue box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Attendance History</div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: none;">
                                    <div class="col-md-12" style="padding: 20px 0px;">
                                        <form method="post" class="validateForm" id="attendance_frm">  
                                            <input type="hidden" name="user_id" value="<?php echo $data['id'];?>">
                                            <label class="control-label col-md-2"> Select Membership</label>
                                            <div class="form-group col-md-4">
<?php echo @$this->Form->select("selected_membership", $membership, ["default" => '', "empty" => __("All Membership"), "id" => "membership_id", "class" => "form-control"]); ?>	

                                            </div>
                                            <label class="control-label col-md-2">Select Month</label>
                                            <div class="form-group col-md-3">
                                                <select name="month_id"  class="form-control validate[required]" id="month_id">
                                                    <option value="" >Select Month</option>
<?php
for ($i = 0; $i <= 11; $i++) {
    $month = date('M', strtotime("-" . $i . " month"));
    $year = date('Y', strtotime("-" . $i . " month"));
    echo '<option value="' . date('m', strtotime($month)) . '-' . $year . '" >' . $month . '-' . $year . '</option>';
}
?>

                                                </select>		
                                            </div>

                                            <div class="form-group col-md-1">
                                                <input type="submit" name="attendance_report" id="attendancebtn" value="Search" class="btn btn-flat btn-info">
                                            </div> 


                                        </form>

                                    </div>

                                    <div class="table-responsive1">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo __("Class Name"); ?></th>
                                                    <th><?php echo __("Date"); ?></th>
                                                    <th><?php echo __("Day"); ?></th>
                                                    <th><?php echo __("Time"); ?></th>
                                                    <th><?php echo __("Status"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="attendance_data">
<?php
echo "<tr align='center'><td colspan='7'>Please select class and date.</td></tr>";
?>

                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <!-- Attendence Section End here -->
                        
                        <!-- Subscription History --------------->
<?php //if ($session["role_name"] != 'staff_member') { ?>
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet grey-cascade box">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i>Subscription History </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th> Membership Title </th>
                                                        <th> Payment Status </th>
                                                        <th> Plan Status </th>
                                                        <th> Start Date </th>
                                                        <th> End Date </th>
                                                        <!--<th> Due Amount </th>-->
                                                        <th> Price </th>
                                                       <!-- <th> Discount </th>-->
                                                        <th> Referral Discount <br><small>(5% per ppl)</small> </th>
                                                        <th> Paid Amount </th>
                                                        <th> Action </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
    <?php
    if (!empty($history)) {
        $tpad = 0;
        $tdue = 0;
        $tdiscount = 0;
        foreach ($history as $row) {
            if ($row['mem_plan_status'] == 1 && $row['payment_status'] == 1) {
                $plan_status = "<span class='label label-success'>Current</span>";
            } else if ($row['mem_plan_status'] == 2 && $row['payment_status'] == 0) {
                $plan_status = "<span class='label label-warning'>Wish</span>";
            } else if ($row['mem_plan_status'] == 0 && $row['payment_status'] == 1) {
                $plan_status = "<span class='label label-warning'>Disabled</span>";
            } else if ($row['mem_plan_status'] == 0 && $row['payment_status'] == 0) {
                $plan_status = "<span class='label label-default'>Pending</span>";
            } else if ($row['mem_plan_status'] == 3 && $row['payment_status'] == 1) {
                $plan_status = "<span class='label label-danger'>Expired</span>";
            } else if ($row['mem_plan_status'] == 2 && $row['payment_status'] == 1) {
                $plan_status = "<span class='label label-danger'>Upgraded</span>";
            } else if ($row['mem_plan_status'] == 4 && $row['payment_status'] == 1) {
                $plan_status = "<span class='label label-info'>Unsubscribe</span>";
            }

            
            /*if (__($this->Gym->get_membership_paymentstatus($row['mp_id'])) == 'Paid') {
                $pay_status = "<span class='label label-success'>" . __($this->Gym->get_membership_paymentstatus($row['mp_id'])) . "</span>";
            } else if (__($this->Gym->get_membership_paymentstatus($row['mp_id'])) == 'Not Paid') {
                $pay_status = "<span class='label label-default'>" . __($this->Gym->get_membership_paymentstatus($row['mp_id'])) . "</span>";
            } else if (__($this->Gym->get_membership_paymentstatus($row['mp_id'])) == 'Partially Paid') {
                $pay_status = "<span class='label label-success'>Paid</span>";
            }*/
            if ($row['payment_status'] == 1) {
                $pay_status = "<span class='label label-success'> Paid</span>";
            } else if ($row['payment_status'] == 0) {
                $pay_status = "<span class='label label-default'>Not Paid</span>";
            } else if ($row['payment_status'] == 2) {
                $pay_status = "<span class='label label-danger'>Failed</span>";
            }

            $tpad = $tpad + $row["paid_amount"];
            $tdue = $tdue + ($row["membership_amount"]);
            $tdiscount = $tdiscount + $row["discount_amount"];
            echo "<tr>
                                                                                    <td>{$row["membership"]["membership_label"]}</td>
                                                                                     <td>" . $pay_status . "</span></td>
                                                                                         <td>" . $plan_status . "</span></td>";
            if (!empty($row["start_date"])) {
                echo "<td>" . date($this->Gym->getSettings("date_format"), strtotime($row["start_date"]->format('Y-m-d'))) . "</td>
                                                                                    <td>" . date($this->Gym->getSettings("date_format"), strtotime($row["end_date"]->format('Y-m-d'))) . "</td>";
            } else {
                echo "<td>N/A</td> <td>N/A</td>";
            }

            echo "<!--<td>" . $this->Gym->get_currency_symbol() . " " . ($row["membership_amount"] - $row["paid_amount"]) . "</td>--> 
                                                                                    <td>" . $this->Gym->get_currency_symbol() . " {$row["membership_amount"]}</td>
                                                                                    <!--<td>" . $this->Gym->get_currency_symbol() . " {$row["discount_amount"]}</td>-->
                                                                                    <td>" . $this->Gym->get_currency_symbol() . " {$row["discount_amount"]}</td>
                                                                                    <td>" . $this->Gym->get_currency_symbol() . " {$row["paid_amount"]}</td>
                                                                                    <td><a href='javascript:void(0)' class='view_invoice btn btn-success btn-sm' data-url='" . $this->request->base . "/GymAjax/viewInvoice/{$row['mp_id']}'> View Invoice</a></td>
                                                                            </tr>";
        }
    }
    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"> </div>
                                            <div class="col-md-6">
                                                <div class="well">

                                                    <div class="row static-info align-reverse">
                                                        <div class="col-md-8 name"> Total Paid: </div>
                                                        <div class="col-md-3 value"> $ <?php echo number_format(@$tpad, 2); ?> </div>
                                                    </div>

                                                    <div class="row static-info align-reverse">
                                                        <div class="col-md-8 name"> Total Discount: </div>
                                                        <div class="col-md-3 value"> $ <?php echo number_format(@$tdiscount, 2); ?> </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php //} ?>
                        
                        <!-- End Subscription History -->
                        
                        <!------------------------- My Goals -->
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="goal-dash-title">
                                    <div class="portlet-title col-sm-3" style="border:0;">
                                        <div class="caption">
                                            <i class="icon-settings"></i>
                                            <span class="caption-subject bold uppercase"> 
                                                <?php echo $data['first_name'].' '.$data['last_name']."'s Goals"; ?> 
                                            </span>

                                        </div>
                                        <div class="actions">
                                            <div class="tools"> </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="portlet-body">

                                            <?php echo $this->Form->create("viewGoals", ["class" => "form-horizontal", "role" => "form", "id" => 'viewGoals']); ?>
                                            <div class="form-group form-md-line-input">
                                                <div class="col-md-3">
                                                    <label class="control-label" for="form_control_1">Filter By Status:
                                                    </label>
                                                </div>

                                                <div class="md-radio-horizontal col-md-9">
                                                    <div class="md-radio col-md-3">
                                                        <input type="radio" id="checkbox1_1" <?php echo ( isset($goalStatus) && $goalStatus == 'all') ? "checked" : ""; ?> value="all" name="filter_by_status" class="check_limit md-radiobtn">
                                                        <label for="checkbox1_1">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> <?php echo __("All"); ?>
                                                        </label>
                                                    </div>
                                                    <div class="md-radio col-md-3">
                                                        <input type="radio" id="checkbox1_2" <?php echo (isset($goalStatus) && $goalStatus == 'active') ? "checked" : ""; ?> value="active" name="filter_by_status" class="check_limit md-radiobtn">
                                                        <label for="checkbox1_2">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> <?php echo __("Active"); ?>
                                                        </label>
                                                    </div>
                                                    <div class="md-radio col-md-3">
                                                        <input type="radio" id="checkbox1_3" <?php echo (isset($goalStatus) && $goalStatus == 'succeed') ? "checked" : ""; ?>  value="succeed" name="filter_by_status" class="check_limit md-radiobtn">
                                                        <label for="checkbox1_3">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> <?php echo __("Succeeded"); ?> </label>
                                                    </div>
                                                    <div class="md-radio col-md-3">
                                                        <input type="radio" id="checkbox1_4" <?php echo (isset($goalStatus) && $goalStatus == 'failed') ? "checked" : ""; ?> value="failed" name="filter_by_status" class="check_limit md-radiobtn">
                                                        <label for="checkbox1_4">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> <?php echo __("Incomplete"); ?> </label>
                                                    </div>

                                                </div>

                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </div>
                                    </div> <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- Search Criteria---------------------->
                                <!-- /Search Criteria---------------------->

                                <div class="portlet-body">
                                    <!-- ------------------------------------------------------->
                                    <div class="list-group">
                                        <?php
                                        $count = 1;
                                        foreach ($mygoals as $goals) {
                                            if ($goals['status'] == 'succeed') {
                                                $list_color = 'bg-success';
                                                $status = 'Goal Succeeded';
                                            } else if ($goals['status'] == 'failed') {
                                                $list_color = 'bg-danger';
                                                $status = 'Goal Failed';
                                            } else {
                                                $list_color = 'bg-info';
                                                $status = 'Active Goal';
                                            }

                                            $active = '';
                                            if ($goals['endDate'] > date('Y-m-d'))
                                                $active = 'active';
                                            ?>
                                            <div class="col-xs-12 col-sm-4">
                                                <a href="<?php echo $this->request->base . '/my-goals/details/' . $goals['id']."/".$data['id']; ?>" class="list-group-item list-group-item goal-box">
                                                    <div class="goal-header"> 
                                                        <div class="col-sm-6 border-right">
                                                            <div class="text-center">
                                                                <span style="color:#999">Start Date</span>
                                                                <br> <?php echo date($this->Gym->getSettings('date_format'), strtotime($goals['startDate'])); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="text-center">
                                                                <span style="color:#999">End Date</span>
                                                                <br> <?php echo date($this->Gym->getSettings('date_format'), strtotime($goals['endDate'])); ?>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="goal-status <?= $status ?>"><?= ( ($status == 'Goal Failed') ? 'Incomplete Goal' : $status); ?></div>


                                                    <?php
                                                    $targets = json_decode($goals['target'],true);
                                                    $initValues = json_decode($goals['initValues'],true);
                                                    foreach ($targets as $targetIndex=>$targetVal) {
                                                        if($targetVal < $initValues[$targetIndex]){
                                                            $lossGain = 'Lose';
                                                            $diff = $initValues[$targetIndex] - $targetVal;
                                                        }else{
                                                            $lossGain = 'Gain';
                                                            $diff = $targetVal - $initValues[$targetIndex];
                                                        }

                                                        ?>
                                                        <div class="col-sm-12 margin-bottom">
                                                            <div class="col-sm-7" style="color:#000;"><?php  echo $this->Gym->getTargetKeys($targetIndex);?> </div> 
                                                            <div class="col-sm-5">
                                                                <strong class="pull-right" style="color:#999"><small> <?= $lossGain ?> </small><?php echo round($diff,2); ?> <?php echo ( $this->Gym->getUnit('imperial',$targetIndex) ) ? $this->Gym->getUnit('imperial',$targetIndex) : '';?> </strong>
                                                            </div>  
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    <?php } ?>


                                                    <div class="clearfix"></div>
                                                </a>

                                            </div>
                                            <?php
                                            $count++;
                                        }
                                        ?><div class="clearfix"></div>
                                    </div>
                                    <!-- ------------------------------------------------------->
                                </div>
                            </div>
                        </div>
                        <!---------------------------- End My Goals -->
                        
                        
                        <!-- Base Line Measurement ---------------------------->
                        <div class="col-md-12">
    <div class="portlet box green measurements">
        <div class="portlet-title">
            <div class="caption"> 
                <i class="icon-settings"></i> 
                <span class="caption-subject uppercase"> <?php echo __("Baseline Measurements"); ?> </span> 
            </div>
            <div class="tools">
                <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
            </div>
        </div>
        
        <div class="portlet-body" style="display:none;">
            <?php echo $this->Form->create("baselineMeasurement", ["class" => "validateForm", "role" => "form"]); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label><strong>Weight</strong></label>
                        <input type="text" name="weight" id="weight" value="<?php echo @$baselineMeasurements['weight'];?>" class="form-control validate[required, custom[number]]" placeholder="" aria-controls="sample_1" onblur="calclbm()">
                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial','weight');?></span>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><strong>Height</strong></label>
                                <input type="text" name="height1" value="<?php echo @$baselineMeasurements['height1'];?>" class="form-control validate[required, custom[number]]" placeholder="" aria-controls="sample_1">
                                <span class="input-group-addon right">ft</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="invisible">Height</label>
                                <input type="text" name="height2" value="<?php echo @$baselineMeasurements['height2'];?>" class="form-control validate[required, custom[number]]" placeholder="" aria-controls="sample_1">
                                <span class="input-group-addon right">in</span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php //echo "<pre>";print_r($baselineMeasurements);?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label><strong>Activity Level</strong></label>
                        <select name="activityLevel" id="activityLevel" class="form-control">
                            <option value="low" <?php if (@$baselineMeasurements['activityLevel'] == 'low') { echo "selected='selected'";}?>>Low</option>
                            <option value="moderate" <?php if(@$baselineMeasurements['activityLevel'] == 'moderate') { echo "selected='selected'";}?>>Moderate</option>
                            <option value="high" <?php if(@$baselineMeasurements['activityLevel'] == 'high') { echo "selected='selected'";}?>>High</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label><strong>Body Fat</strong></label>
                        <input type="text" name="bodyFat" id="bodyFat" value="<?php echo @$baselineMeasurements['bodyFat'];?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1" onblur="calclbm()">
                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial','bodyFat');?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label><strong>Lean Body Mass</strong></label>
                        <input type="text" name="leanBodyMass" id="leanBodyMass" value="<?php echo @$baselineMeasurements['leanBodyMass'];?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1" readonly="readonly">
                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial','leanBodyMass');?></span>
                    </div>
                    <div class="col-sm-6">
                        <label><strong>Water Weight</strong></label>
                        <input type="text" name="waterWeight" value="<?php echo @$baselineMeasurements['waterWeight'];?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1">
                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial','waterWeight');?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label><strong>Bone Density</strong></label>
                        <input type="text" name="boneDensity" value="<?php echo @$baselineMeasurements['boneDensity'];?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1">
                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial','boneDensity');?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-flat btn-primary" name="save" value="<?php echo ($baselineEdit) ? 'Update': 'Submit';?>">
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php echo $this->Form->end();?>
        </div>
        <div class="clearfix"></div>
        <!-- Search Criteria----------------------> 
        <!-- /Search Criteria----------------------> 

    </div>
</div>
                        <!-- End Base Line measurement --------------------------->
                      
                        <!-- Subscription History --------------->
<?php //if ($session["role_name"] != 'staff_member') { ?>
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet green-meadow box">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i>Product History </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">
                                        <div class="portlet-titl col-md-12 col-sm-12">

                                        <div class="top">
                                            <?php //if ($session["role_name"] != 'staff_member') { ?>
                                                <div class="btn-set pull-right" style="padding-bottom: 10px;">
                                                    <a href="<?php echo $this->Gym->createurl("GymProduct", "purchaseProductForClientList/" . $user_id); ?>" class="btn blue"><i class="fa fa-bars"></i> Purchase Product</a>

                                                </div>
                                            <?php //} ?>
                                        </div>

                                    </div>
                                        <div class="table-responsive1">
                                            <table class="table table-hover table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width:100px;"> Image </th>
                                                    <th> Product Name </th>
                                                    <th> Payment Status </th>
                                                    <th> Price </th>
                                                    <th> Paid Amount </th>
                                                    <th> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($product)) {
                                                    $tpadamt = 0;
                                                    
                                                    foreach ($product as $row) {
                                                       $url = (isset($row['gym_product']['image']) && $row['gym_product']['image'] != "") ? $row['gym_product']['image'] : $this->request->webroot . "upload/no_image_placeholder.png"; 

                                                        if ($row['payment_status'] == 1) {
                                                            $pay_status = "<span class='label label-success'> Paid</span>";
                                                        } else if ($row['payment_status'] == 2) {
                                                            $pay_status = "<span class='label label-danger'> Failed</span>";
                                                        } 

                                                        $tpadamt = $tpadamt + $row["paid_amount"];
                                                       
                                                        echo "<tr> <td><img class='img-responsive' src='{$url}' alt=''></td>
                                                                                <td>{$row["gym_product"]["product_name"]}</td>
                                                                                 <td>" . $pay_status . "</span></td>
                                                                                 <td>" . $this->Gym->get_currency_symbol() . " {$row["product_amount"]}</td>
                                                                                <td>". $this->Gym->get_currency_symbol() ." {$row["paid_amount"]}</td>
                                                                                <td><a style='border-radius:25px !important;border:0;background:#777985;' href='javascript:void(0)' class='view_invoice btn btn-success btn-sm' data-url='" . $this->request->base . "/GymAjax/viewProductInvoice/{$row['id']}'> View Invoice</a></td>
                                                                        </tr>";
                                                    }
                                                }else{
                                                   echo "<tr align='center'><td colspan='4'>There is no record</td></tr> ";
                                                }
                                                ?>

                                            </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"> </div>
                                            <div class="col-md-6">
                                                <div class="well">

                                                    <div class="row static-info align-reverse">
                                                        <div class="col-md-8 name"> Total Paid: </div>
                                                        <div class="col-md-3 value"> $ <?php echo number_format(@$tpadamt, 2); ?> </div>
                                                    </div>

                                                   

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php //} ?> 
                        <!-- Referral History --------------->
<?php if ($session["role_name"] != 'staff_member') { ?>
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet red box">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i>Referral History </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">
                                        <div class="portlet-titl col-md-12 col-sm-12">


                                    </div>
                                        <div class="table-responsive1">
                                            <table class="table table-hover table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width:100px;"> Image </th>
                                                    <th> Name </th>
                                                    <th> Email </th>
                                                    <th> Discount Amount </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (!empty($my_referrals)) {
                                                        $count = true;
                                                        $discount_amount_total = 0.00;
                                                        foreach ($my_referrals as $my_referral) {
                                                            if(!$this->Gym->hasActivePlan($my_referral['id'], $my_referral['mem_id']))
                                                                continue;
                                                            $count = false;
                                                            //$this->Gym->getLowestPlanPriceBasedOnLocation($my_referral['id']);
                                                            $discount_amount = ( ( $this->Gym->getLowestPlanPriceBasedOnLocation($my_referral['id']) ) * 5 )/100;
                                                            $discount_amount_total += $discount_amount;
                                                            $url = (isset($my_referral['image']) && $my_referral['image'] != "") ? $my_referral['image'] : $this->request->base . "/upload/profile-placeholder.png"; 
                                                            echo "<tr> <td><img class='img-responsive' src='{$url}' alt=''></td>
                                                                                    <td>{$my_referral["first_name"]} {$my_referral["last_name"]}</td>
                                                                                     <td>" . $my_referral["email"] . "</span></td>
                                                                                    <td>". $this->Gym->get_currency_symbol() .number_format($discount_amount, 2) ."</td>";
                                                                                    
                                                        }
                                                        if($count)
                                                            echo "<tr align='center'><td colspan='4'>There is no record</td></tr> ";
                                                    }else{
                                                        echo "<tr align='center'><td colspan='4'>There is no record</td></tr> ";
                                                    }
                                                    ?>

                                            </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"> </div>
                                            <div class="col-md-6">
                                                <div class="well">

                                                    <div class="row static-info align-reverse">
                                                        <div class="col-md-8 name"> Total Discount: </div>
                                                        <div class="col-md-3 value"> $ <?php echo number_format(@$discount_amount_total, 2); ?> </div>
                                                    </div>

                                                   

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php } ?>



<!-- Attendance Section Start Here -->
                          <div class="col-md-12">
                        <div class="portlet blue box">
                            <div class="portlet-title">
                                <div class="caption"> 
                                    <i class="icon-settings"></i> 
                                    <span class="caption-subject uppercase"> <?php echo __("Membership Discount"); ?> </span> 
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                    <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                </div>
                            </div>

                            <div class="portlet-body" style="display:none;">
                                <?php echo $this->Form->create("#", ["class" => "validateForm", "role" => "form","id"=>"discount_form"]); ?>
                                <?php // echo "<pre>";print_r($mem_discount_data); die; ?>
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label><strong>Discount Amount</strong></label>
                                        <input type="text" name="discount_amt" id="discount_amt" value="<?php echo @$mem_discount_data['discount_amt']; ?>" class="form-control validate[required]" placeholder="Enter Discount Amount" aria-controls="sample_1">
                                        <span class="input-group-addon right">$</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label><strong>Select Membership</strong></label>
                                                    <?php echo @$this->Form->select("selected_membership", $membership, ["default" => @$mem_discount_data['membership_id'], "empty" => __("All Membership"), "id" => "membership_ids", "class" => "form-control gen_membership_ids validate[required]","data-url" => $this->request->base . "/GymAjax/get_amount_by_memberships"]); ?>	
                                                </div>
                                            <div class="col-sm-4" style="margin-top:25px;">
                                                  <input type="submit" class="btn btn-flat btn-primary" name="save" value="Submit">
                                             </div>
                                            
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $data['id'];?>">
                                <input type="hidden" name="associated_licensee" id="associated_licensee" value="<?php echo $data['associated_licensee'];?>">
                                <?php echo $this->Form->end(); ?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                       <div id="total_amount_div" style="display:none;"> Membership Amount : $ <span id="total_amount">0.00</span></div>
                                    </div>
                                 </div>
                                <div class=" clearfix"></div>
                            </div>
                             
                            <div class="clearfix" id="discount_status"></div>
                            <!-- Search Criteria----------------------> 
                            <!-- /Search Criteria----------------------> 

                        </div>
                    </div>
                       

                        <!-- Attendence Section End here -->

                        
                        
                    </div>

                </div>




            </div>
        </div>
    </div>
</div>
<!-- Customer Note Modal not in use -->
<div class="modal fade" id="customerNoteModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Customer Note</h4>
            </div>
            <div class="modal-body">
                <form class="validateForm form-horizontal" method="post" role="form">		
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Note Title"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" value="" placeholder="Enter Note Title"  name="note_title">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter note title...</span>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Class"); ?>
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
<?php
echo $this->Form->select("class_id", $classes, ["empty" => __("Select Class"), "default" => '', "class" => "form-control"]);
?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select class for note...</span>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Start Date"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Select start date" name="start_date" class="hasDatepicker form-control validate[required]" value="">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select start date...</span>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("End Date"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Select end date" name="end_date" class="hasDatepicker form-control validate[required]" value="">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select end date...</span>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Comment"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <textarea type="text" name="comment" id="comment" class="form-control validate[required]"></textarea>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Please enter comment...</span>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group form-md-line-input">
                            <div class="col-md-offset-2 col-md-6">
                                <input type="submit" value="<?php echo __("Save"); ?>" name="save_note" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>data-id

<!-- Workout Modal -->
<div id="WorkoutModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Workout Completed</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN CHART PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-bar-chart font-green-haze"></i>
                                    <span class="caption-subject bold uppercase font-green-haze" id="workout_date"></span>
                                    <span class="caption-helper">Completed</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="workout_chart" class="chart" style="height: 400px;"> </div>
                            </div>
                        </div>
                        <!-- END CHART PORTLET-->
                    </div>
                     <div class="clearfix"></div>
                    <div class="col-sm-12">
                    <div style="background:#ccc;padding-top:10px">
                    <div class="col-sm-8">
                        <div class="portlet green-meadow box" style="background: transparent; border:0; margin-bottom:0">
                            <div class="portlet-title" style="background: transparent;">
                                <div class="caption" style="color:#000;">
                                   <strong id="workout_client_name"> EMILY ACEVES </strong>
                                </div>
                            </div>
                            <div class="portlet-body" style="background: transparent;">
                                <div class="row static-info">
                                    <div class="col-md-12 value">
                                    <div class="row">
                                        <div class="col-sm-6"><i class="fa fa-heartbeat"></i><span> <strong>AVERAGE HR</strong></span> <span id="averageHr"></span></div>
                                         <div class="col-sm-6"><i class="fa fa-gamepad "></i><span>  <strong>GO POINTS</strong></span> <span id="points"></span></div>
                                          <div class="col-sm-6"><i class="fa fa-free-code-camp"></i><span> <strong>CALORIES BURN</strong></span><span id="calorie"></span> </div>
                                         <div class="col-sm-6"><i class="fa fa-clock-o"></i><span> <strong>WORK OUT DURATION</strong></span><span id="duration"></span></div>
										 </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                      
                            <div class="easy-pie-chart">
                                <div class="number transactions" data-percent="55">
                                    <span id="avragePercentage"></span>% </div>
                                </div>
                            <a class="title" href="javascript:;"> Average Percentage

                            </a>
                     
                    </div>
                    <div class="clearfix"></div>
                      </div> </div>
                </div>

            </div>
        </div>
        
    </div>
</div>

<!-- Measurement Modal -->
<div id="MeasurementModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Measurements</h4>
            </div>
            <div class="modal-body">
                <div class="portlet light portlet-fit bordered">
                    <!--<div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Editable Form</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                <label class="btn btn-transparent dark btn-outline btn-circle btn-sm active">
                                    <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                                <label class="btn btn-transparent dark btn-outline btn-circle btn-sm">
                                    <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                            </div>
                        </div>
                    </div>-->
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12" id="measurement_workout_details_div">
                                <table id="user" class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td> WEIGHT </td>
                                            <td>
                                                <a href="javascript:;" id="weight" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Weight"> </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"> CALIPER </td>
                                        </tr>

                                        <tr>
                                            <td> BICEP </td>
                                            <td>
                                                <a href="javascript:;" id="caliperBicep" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Bicep"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> TRICEP </td>
                                            <td>
                                                <a href="javascript:;" id="triceps" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Tricep">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> SUBSCAPULRA </td>
                                            <td>
                                                <a href="javascript:;" id="subscapular" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Subscapular">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> ILIAC CREST </td>
                                            <td>
                                                <a href="javascript:;" id="iliacCrest" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Lliac Crest">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> BODY FAT </td>
                                            <td>
                                                <a href="javascript:;" id="bodyFat" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Body Fat">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> LEAN BODY MASS </td>
                                            <td>
                                                <a href="javascript:;" id="leanBodyMass" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Lean Body Mass">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> WATER WEIGHT </td>
                                            <td>
                                                <a href="javascript:;" id="waterWeight" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Water Weight"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> BONE DENSITY </td>
                                            <td>
                                                <a href="javascript:;" id="boneDensity" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Bone Density"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> CIRCUMFERENCE </td>
                                        </tr>
                                        <tr>
                                            <td> NECK </td>
                                            <td>
                                                <a href="javascript:;" id="neck" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Neck"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> CHEST </td>
                                            <td>
                                                <a href="javascript:;" id="chest" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Chest">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> BICEP </td>
                                            <td>
                                                <a href="javascript:;" id="circumferenceBicep" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Circumference Bicep"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> FOREARM </td>
                                            <td>
                                                <a href="javascript:;" id="forearm" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Forearm"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> WAIST </td>
                                            <td>
                                                <a href="javascript:;" id="waist" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Waist"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> HIP </td>
                                            <td>
                                                <a href="javascript:;" id="hip" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Hip">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> THIGH </td>
                                            <td>
                                                <a href="javascript:;" id="thigh" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Thigh">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> CALF </td>
                                            <td>
                                                <a href="javascript:;" id="calf" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Calf">  </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div>
                            <img src="<?php echo $this->request->base; ?>/webroot/upload/profile-placeholder.png" width="150" title="" class="img-responsive">
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Schedule Modal -->
<div id="ScheduleModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="schedule_title">Schedule</h4>
            </div>
            <div class="modal-body" id="schedule_modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Nutrition Modal -->
<div id="NutritionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="nutrition_title">Nutrition</h4>
            </div>
            <div class="modal-body" id="nutrition_modal_body">
                
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        $('#calendars').fullCalendar({
            header: {
                left: 'prev,next,today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            lang: 'en',
            editable: false,
            firstDay:1,
            eventLimit: false, // allow "more" link when too many events
            events: <?php echo json_encode($cal_array); ?>,
            eventClick: function (event, jsEvent, view) {
                console.log('event:', event);
                //console.log('jsEvent:',jsEvent);
                //console.log('view:',view);
                //console.log(event.start._i);
                var user_id = "<?php echo $data['id']; ?>";
                var title = event.title;
                var titleUnique = event.titleUnique;
                var uniqueId = event.uniqueId;
                var date = event.start._i;
                var ajaxurl = "<?php echo $base_url; ?>" + "/GymAjax/get" + titleUnique + "DetailsViewMember";
                //alert('title: ' + title + ' ------- date: ' + date + ' ---- ajaxUrl: ' + ajaxurl);
                var curr_data = {user_id:user_id,date: date, title: titleUnique, id: uniqueId};
                $.ajax({
                    url: ajaxurl,
                    data: curr_data,
                    type: "POST",
                    dataType: "JSON",
                    success: function (result) {
                        if (result.status == 'success') {

                            if (result.title == 'Measurement') {
                                console.log(result.data);
                                $.each(result.data, function (index, value) {
                                    var unit = getUnit('imperial', index);
                                    if (unit !== false) {
                                        //console.log(unit);
                                        $('.modal-body #' + index).text(parseFloat(value).toFixed(2) + ' ' + unit);
                                    }
                                });
                            }else if (result.title == 'Workout') {
                                $('#workout_date').text(moment(result.data.createdAt).format('ddd MMM DD, YYYY HH:mm'));
                                initializeChart(result.data.zonesDuration, result.data.duration);
                                var duration = getDayHourMinSec(0, result.data.duration);
                                $('#duration').text(duration);
                                $('#points').text(result.data.points);
                                $('#calorie').text(result.data.calorie + ' Kcal');
                                $('#averageHr').text(result.data.averageHr);
                                $('#avragePercentage').text((((result.data.averageHr) * 100) / result.data.averageMaxHr).toFixed());
                                $('#workout_client_name').text('<?php echo strtoupper($this->Gym->get_user_name($user_id));?>');
                            }else if (result.title == 'Schedule') {
                                $('#schedule_title').text(result.modal_title + ' Schedule');
                                $('#schedule_modal_body').html(result.data);

                            }else if (result.title == 'Nutrition') {
                                $('#nutrition_modal_body').html(result.data.nutrition_notes);
                            }

                            $('#' + titleUnique + 'Modal').modal('show');

                        } else {
                            alert('There is no ' + titleUnique + 'added.');
                        }
                        return false;
                    }
                });
            },
            eventRender: function (event, element) {
                element.addClass(event.class)

                //console.log(event)
            },
            eventMouseover: function(calEvent, jsEvent) {
                if (typeof calEvent.title === "undefined") {
                    calEvent.title = moment(calEvent.start._d).format('hh:mm A') + ' - ' + moment(calEvent.end._d).format('hh:mm A');
                }
                var tooltip = '<div class="tooltipevent" style="width:100px;height:100px;background:#ccc;position:absolute;z-index:10001;">' + calEvent.title + '</div>';
                var $tooltip = $(tooltip).appendTo('body');

                $(this).mouseover(function(e) {
                    $(this).css('z-index', 10000);
                    $tooltip.fadeIn('500');
                    $tooltip.fadeTo('10', 1.9);
                }).mousemove(function(e) {
                    $tooltip.css('top', e.pageY + 10);
                    $tooltip.css('left', e.pageX + 20);
                });
            },

            eventMouseout: function(calEvent, jsEvent) {
                $(this).css('z-index', 8);
                $('.tooltipevent').remove();
            },
            slotDuration:'00:15:00',
        });

        $(".fc-state-highlight").wrapInner("<div class='today-date'></div>");
    });
    function getUnit(unitType, data) {
        var lbsItem = ["weight", "leanBodyMass", "boneDensity"];
        var milimeterItem = ["caliperBicep", "triceps", "subscapular", "iliacCrest"];
        var inchItem = ["neck", "chest", "circumferenceBicep", "forearm", "waist", "hip", "thigh", "calf"];
        var percentItem = ["bodyFat", "waterWeight"];

        if ($.inArray(data, lbsItem) !== -1)
            return (unitType == 'imperial') ? 'lbs' : 'kg';
        else if ($.inArray(data, milimeterItem) !== -1)
            return (unitType == 'imperial') ? 'mm' : 'mm';
        else if ($.inArray(data, inchItem) !== -1)
            return (unitType == 'imperial') ? 'in' : 'in';
        else if ($.inArray(data, percentItem) !== -1)
            return '%';
        else
            return false;
    }

</script>
<script>
    function initializeChart(zonesDuration, duration) {
        console.log(zonesDuration);
        //var value2=100;
        var zonesDurationArr = zonesDuration.split(',');
        AmCharts.makeChart("workout_chart", {
            "theme": "light",
            "type": "serial",
            "startDuration": 2,
            "fontFamily": 'Open Sans',
            "color": '#888',
            "dataProvider": [
                {
                    "country": "ZONE 1",
                    "visits": (zonesDurationArr[0])/(1000*60).toFixed(2),
                    "color": "#575757",
                    "percent": ((zonesDurationArr[0] * 100) / duration).toFixed() + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[0])
                },
                {
                    "country": "ZONE 2",
                    "visits": (zonesDurationArr[1])/(1000*60).toFixed(2),
                    "color": "#2199BE",
                    "percent": ((zonesDurationArr[1] * 100) / duration).toFixed() + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[1])
                }, {
                    "country": "ZONE 3",
                    "visits": (zonesDurationArr[2])/(1000*60).toFixed(2),
                    "color": "#3CC24F",
                    "percent": ((zonesDurationArr[2] * 100) / duration).toFixed() + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[2])
                }, {
                    "country": "ZONE 4",
                    "visits": (zonesDurationArr[3])/(1000*60).toFixed(2),
                    "color": "#F7A80A",
                    "percent": ((zonesDurationArr[3] * 100) / duration).toFixed() + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[3])
                }, {
                    "country": "ZONE 5",
                    "visits": (zonesDurationArr[4])/(1000*60).toFixed(2),
                    "color": "#EA4221",
                    "percent": ((zonesDurationArr[4] * 100) / duration).toFixed() + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[4])
                }],
            "valueAxes": [{
                    "position": "left",
                    "axisAlpha": zonesDurationArr[0],
                    "gridAlpha": 0,
                    "title": "Time spent in zones (in minutes)"
                }],
            "graphs": [{
                    "balloonText": "[[category]]: <b>[[percent]]</b>", //
                    "colorField": "color",
                    "fillAlphas": 0.85,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "topRadius": 1,
                    "valueField": "visits",
                    "labelText": "[[zoneDuration]]"
                }],
            "depth3D": 40,
            "angle": 10,
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },
            "categoryField": "country",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "gridAlpha": 0

            },
            "exportConfig": {
                "menuTop": "20px",
                "menuRight": "20px",
                "menuItems": [{
                        "icon": '<?php echo $this->request->base; ?>/lib/3/images/export.png',
                        "format": 'png'
                    }]
            }
        }, 0);
    }

    jQuery('.chart_5_chart_input').off().on('input change', function () {
        var property = jQuery(this).data('property');
        var target = chart;
        chart.startDuration = 0;

        if (property == 'topRadius') {
            target = chart.graphs[0];
        }

        target[property] = this.value;
        chart.validateNow();
    });

    $('#workout_chart').closest('.portlet').find('.fullscreen').click(function () {
        chart.invalidateSize();
    });
</script>

<script>
    $(document).ready(function () {

        $("#attendance_frm").submit(function (e) {

            membership_id = $("#membership_id").val();
            month_id = $("#month_id").val();

            if (month_id != '')
            {

                var url = "<?php echo $this->request->base . '/GymAjax/fetchAttendanceData'; ?>"; // the script where you handle the form input.

                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#attendance_frm").serialize(), // serializes the form's elements.
                    // dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                        $("#attendance_data").html(data); // show response from the php script.
                    },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });

                e.preventDefault(); // avoid to execute the actual submit of the form.
            }
        });

        $("#discount_form").submit(function (e) {

            var discount_amt = $("#discount_amt").val();
            var membership_id = $("#membership_ids").val();

            if (discount_amt != '' && membership_id != '')
            {

                var url = "<?php echo $this->request->base . '/GymAjax/updateDiscountAmount'; ?>"; // the script where you handle the form input.

                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#discount_form").serialize(), // serializes the form's elements.
                    dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                         if(data.status=='fail'){
                               HTMLMSG='<div class="alert alert-danger"><strong>Error!</strong> Discount amount will less than membership amount.</div>';
                          }else{
                              HTMLMSG='<div class="alert alert-success"><strong>Success!</strong> Discount amount has been updated successfully.</div>'; 
                          }
                         $("#discount_status").html(HTMLMSG); // show response from the php script.
                         $("#discount_status").show().delay(5000).fadeOut();
                    },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });

               
            }
             e.preventDefault(); // avoid to execute the actual submit of the form.
        });

       $("body").on("change",".gen_membership_ids",function(){
	var mid = $(this).val();
        $("#total_amount_div").hide();
	$("#total_amount").html('');
        var lice =$("#associated_licensee").val();
	var ajaxurl = $(this).attr("data-url");	
	var curr_data = {mid:mid,lice:lice};
	$.ajax({
		url:ajaxurl,
		data : curr_data,
		type : "POST",
		success : function(result){
                       $("#total_amount_div").show();
			$("#total_amount").html(result);
                        
		}
	});	
          });


    });
</script>
<script>

    $("input[name='filter_by_status']").on('change', function () {
        $('#viewGoals').submit();
    });
    $("input[name='set_default_card']").on('change', function () {
        //alert($(this).attr('data-id'));
        //return;
        var id = $(this).attr('data-id');
        var mid = <?php echo $user_id;?>;
        var ajaxurl = "<?php echo $this->request->base . '/GymAjax/setDefaultCard'; ?>";
        $.ajax({
		url:ajaxurl,
		data : {id:id,mid:mid},
		type : "POST",
		success : function(result){
                       //$("#total_amount_div").show();
                    //$("#total_amount").html(result);
                    return false;
		}
                
	});
    });

</script>
<script>
function calclbm(){
    var weight = $('#weight').val();
    var bodyFat = $('#bodyFat').val();
    
    if(weight && bodyFat && bodyFat != '' && weight != ''){
        var leanBodyMass = ( parseInt(weight) - ( parseInt(weight) * ( parseInt(bodyFat) / 100 ) ) ) ;
        $('#leanBodyMass').val(Math.round(leanBodyMass));
    }else{
        $('#leanBodyMass').val('');
    }
}  

    function getDayHourMinSec(start, end) {
        var seconds= end/1000;
        //for seconds
        if(seconds > 0){
            var sec = "" + (seconds % 60);
            if(seconds % 60 < 10){
              sec= "0" + (seconds % 60);
            }
        }else{
            sec= "00";
        }
        //for mins
        if(seconds > 60){
            var mins= ""+ (seconds/60%60);
            if((seconds/60%60)<10){
                mins= "0" + (seconds/60%60);
            }
        }else{
            mins= "00";
        }
        //for hours
        if(seconds/60 > 60){
            var hours= ""+ (seconds/60/60);
            if((seconds/60/60) < 10){
                hours= "0" + (seconds/60/60);
            }
        }else{
            hours= "00";
        }
        
        var hh = (hours == '00') ? hours : getInTwoDigit(parseInt(hours).toFixed());
        var mm = (mins == '00') ? mins : getInTwoDigit(parseInt(mins).toFixed());
        var ss = (sec == '00') ? sec : getInTwoDigit(parseInt(sec).toFixed());
        
        return time_format = "" + hh + ":" + mm + ":" + ss; //00:15:00

    }
    
    function getDayHourMinSec1(end) {
        var seconds= end/1000;
        //for seconds
        if(seconds > 0){
            var sec = "" + (seconds % 60);
            if(seconds % 60 < 10){
              sec= "0" + (seconds % 60);
            }
        }
        //for mins
        if(seconds > 60){
            var mins= ""+ (seconds/60%60);
            if((seconds/60%60)<10){
                mins= "0" + (seconds/60%60);
            }
        }else{
            mins= "00";
        }
        //for hours
        if(seconds/60 > 60){
            var hours= ""+ (seconds/60/60);
            if((seconds/60/60) < 10){
                hours= "0" + (seconds/60/60);
            }
        }else{
            hours= "00";
        }
        
        var hh = (hours == '00') ? hours : getInTwoDigit(parseInt(hours).toFixed());
        var mm = (mins == '00') ? mins : getInTwoDigit(parseInt(mins).toFixed());
        var ss = (sec == '00') ? sec : getInTwoDigit(parseInt(sec).toFixed());
        
        return time_format = "" + hh + "h" + mm + "m" + ss + "s"; //00:15:00

    }
    function getInTwoDigit(n) {
        return n > 9 ? "" + n : "0" + n;
    }
</script>
