<?php
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Refund Payment Reports');
?>
<style>
.dt-buttons {display:none;}
.portlet>.portlet-title>.tools>a {color:#fff;}
 .mt-element-ribbon .ribbon{
        margin: 0px;
    } 
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("#startdate").datepicker({
            todayBtn: 1,
            autoclose: true,
            forceParse: false



        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#enddate').datepicker('setStartDate', minDate);
        });

        $("#enddate").datepicker({forceParse: false})
                .on('changeDate', function (selected) {
                    var maxDate = new Date(selected.date.valueOf());
                    $('#startdate').datepicker('setEndDate', maxDate);
                });
    });
</script>

<?php
if(empty($startdate)){$startdate=0;}
if(empty($enddate)){$enddate=0;}


?>

<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-book-open font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Refund Payment Report"); ?>
                </span>

            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>

        <div class="portlet-body">

            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-12">
                        <form method="post">  

                           
                            <label  class="control-label col-md-1" ><?php echo __('From'); ?></label>
                            <div class="form-group col-md-2">
                                <input type="text" name="startdate" data-date-format="<?php echo $this->Gym->get_js_dateformat($this->Gym->getSettings("date_format")); ?>" id="startdate" class="form-control" value="<?php if($startdate){ echo date($this->Gym->getSettings("date_format"), $startdate);} ?>">		
                            </div>
                            <label  class="control-label col-md-1" ><?php echo __('To'); ?></label>
                            <div class="form-group col-md-2">
                                <input type="text" name="enddate" data-date-format="<?php echo $this->Gym->get_js_dateformat($this->Gym->getSettings("date_format")); ?>" id="enddate" class="form-control" value="<?php if($enddate){echo date($this->Gym->getSettings("date_format"), $enddate);} ?>">		
                            </div>
                            <label  class="control-label col-md-2" ><?php echo __('Select Status'); ?></label>
                            <div class="form-group col-md-2">
                                <select name="refund_status" class="form-control" >
                                    <option value="all" <?php if($status=='all'){ echo "selected";}?>>All Type</option>
                                    <option value="1" <?php if($status=='1'){ echo "selected";}?>>Confirm</option>
                                    <option value="0" <?php if($status=='0'){ echo "selected";}?>>Failed</option>
                                    
                                </select>

                            </div>
                           
                            <div class="form-group col-md-2">
                                <input type="submit" name="cafe_report" Value="<?php echo __('Search Filter'); ?>"  class="btn btn-flat btn-success"/>
                            </div> 
                

                        </form>
                        
                    </div>
                   
                   

                </div>
                
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments"></i>Refund Payment Listing </div>
                        <div class="tools">
                           <a href="<?php echo $this->Pheramor->createurl("Reports", "refundPaymentExport/" . $startdate . '/' . $enddate.'/' . $status.'/0'); ?>" class=""><i class="fa fa-bar-chart"></i> <?php echo __("Export Excel"); ?></a>
                            <a href="<?php echo $this->Pheramor->createurl("Reports", "refundPaymentExport/" . $startdate . '/' . $enddate.'/' . $status.'/1'); ?>" class=""><i class="fa fa-pie-chart"></i> <?php echo __("Export Pdf"); ?></a>
                           
                        </div>
                    </div>
                    
                </div>
                <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                    <thead>


                        <tr>
                           
                            <th><?php echo __("S.N."); ?></th>
                            <th ><?php echo __("Name"); ?></th>
                            <th><?php echo __("Amount"); ?></th>
                            <th><?php echo __("Date"); ?></th>
                            <th><?php echo __("Refund By"); ?></th>
                            <th><?php echo __("Status"); ?></th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        $k=1;
                       //  echo "<pre>"; print_r($member_data); die;
                        if(!empty($refund_data)){
                            foreach ($refund_data as $row) {
                               // gender
                                 $user_deatils=$this->Pheramor->get_user_details($row['user_id']);
                                 $profile_data=$user_deatils['pheramor_user_profile'][0];
                                 $amount=number_format($row['refund_amount'],2);
                                 if ($row['refund_status'] == 0 ) {
                                      $plan_status = "<span class='label label-danger'>Failed</span>";
                                 } else if ($row['refund_status'] == 1) {
                                    //   $total_amt=$total_amt+$amount;
                                    $plan_status = "<span class='label label-success'>Confirm</span>";
                                 }
                               echo "<tr>
                                            <td>{$k}</td>
                                            <td>{$profile_data['first_name']} {$profile_data['last_name']}</td>
                                            <td>$ {$amount}</td>  
                                            <td>".date($this->Pheramor->getSettings("date_format"),strtotime($row['refund_date']))."</td>
                                           <td> {$row['refund_type']}</td>
                                            <td>{$plan_status}</td> 
                                            </tr>";
                                           $k++;
                            }
                        }
                        ?>




                    </tbody>
                </table>

            </div>

        </div>
      </div>
</div>
