<?php
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Member Reports');
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
                <span class="caption-subject bold uppercase"> <?php echo __("Member Report"); ?>
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
                            <div class="form-group col-md-4">
                                <select name="member_status" class="form-control" >
                                    <option value="all" <?php if($status=='all'){ echo "selected";}?>>All Status</option>
                                    <option value="1" <?php if($status=='1'){ echo "selected";}?>>Active</option>
                                    <option value="0" <?php if($status=='0'){ echo "selected";}?>>Inactive</option>
                                    <option value="3" <?php if($status=='3'){ echo "selected";}?>>Disable</option>
                                </select>

                            </div>
                            <label  class="control-label col-md-2" ><?php echo __('Select Gender'); ?></label>
                            <div class="form-group col-md-3">
                                <select name="member_gender" class="form-control" >
                                    <option value="all" <?php if($gender=='all'){ echo "selected";}?>>All</option>
                                    <option value="1" <?php if($gender=='1'){ echo "selected";}?>>Male</option>
                                    <option value="0" <?php if($gender=='0'){ echo "selected";}?>>Female</option>
                                </select>

                            </div>
                            <div class="form-group col-md-4">
                                <input type="submit" name="member_report" Value="<?php echo __('Search Filter'); ?>"  class="btn btn-flat btn-success"/>
                            </div> 
                

                        </form>
                        
                    </div>
                   
                   

                </div>
                
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments"></i>Member Listing </div>
                        <div class="tools">
                           <a href="<?php echo $this->Pheramor->createurl("Reports", "memberExport/" . $startdate . '/' . $enddate.'/'.$status.'/'.$gender.'/0'); ?>" class=""><i class="fa fa-bar-chart"></i> <?php echo __("Export Excel"); ?></a>
                            <a href="<?php echo $this->Pheramor->createurl("Reports", "memberExport/" . $startdate . '/' . $enddate.'/'.$status.'/'.$gender.'/1'); ?>" class=""><i class="fa fa-pie-chart"></i> <?php echo __("Export Pdf"); ?></a>
                           
                        </div>
                    </div>
                    
                </div>
                <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                    <thead>


                        <tr>
                           
                            <th><?php echo __("S.N."); ?></th>
                            <th><?php echo __("Name"); ?></th>
                            <th ><?php echo __("Email"); ?></th>
<!--                            <th><?php echo __("Phone"); ?></th>-->
                            <th><?php echo __("Gender"); ?></th>
                            <th><?php echo __("Credits"); ?></th>
                            <th><?php echo __("Status"); ?></th>
                             <th><?php echo __("Registration Date"); ?></th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        $k=1;
                       //  echo "<pre>"; print_r($member_data);
                        if(!empty($member_data)){
                            foreach ($member_data as $row) {
                               // gender
                                $regDate=date('Y-m-d',strtotime($row['created_date']));
                                  if ($row["gender"] == 1) {
                                    $gender = '<span class="label label-success">Male</span>';
                                    }else{
                                    $gender = '<span class="label label-warning">Female</span>';
                                    }
                                  if($row["is_deleted"] > '0'){
                                       $status = '<span style="border-radius:25px !important;border:0;background:#659be0;" class="btn-success btn-sm">Disable</span>';
                                  }else{  
                                    if ($row["activated"] == 1) {
                                       $status = '<span class="label label-success">Active</span>';
                                       }else{
                                       $status = '<span class="label label-warning">Inactive</span>';
                                       }
                                  }
                                echo "<tr>
                                            <td>{$k}</td>
                                            <td>{$row['first_name']} {$row['last_name']}</td>
                                            <td>{$row['email']}</td>  
                                            <!--<td>{$row['phone']}</td>-->
                                            <td>{$gender}</td>
                                            <td><div class='mt-element-ribbon bg-grey-steel'>
                                                <div class='ribbon ribbon-round ribbon-border-dash ribbon-color-danger uppercase'>Total : {$this->Pheramor->totalCredits($row['user_id'])}</div>
                                                 </div></td>
                                            <td>{$status}</td> 
                                            <td>{$regDate}</td>  
                                           
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
