<?php
$this->Html->addCrumb('Staff List', array('controller' => 'StaffMembers', 'action' => 'staffList'));
$this->Html->addCrumb('View Staff');
//echo json_encode($cal_array); die;
$base_url = $this->request->base;
?>
<style>
    .dashboard-stat .details .number1 {
        padding-top: 13px;
        text-align: right;
        font-size: 22px;
        line-height: 24px;
        letter-spacing: -1px;
        margin-bottom: 0;
        font-weight: 300;
        color:#fff;
    }
</style>
<?php
/** SGT Clases * */
$prefix = '';
$myarray = '';
if (!empty($array_sgt)) {
    foreach ($array_sgt as $ddata) {
        $myarray .= $prefix . " [";
        $myarray .= ' "' . $ddata[0] . '" , ' . $ddata[1] . " ]";
        $prefix = ",\n";
    }
}
/** PT Classes * */
$prefix3 = '';
$myarray3 = '';
if (!empty($array_pt)) {
    foreach ($array_pt as $rdata) {
        $myarray3 .= $prefix3 . " [";
        $myarray3 .= ' "' . $rdata[0] . '" , ' . $rdata[1] . " ]";
        $prefix3 = ",\n";
    }
}
/** OPT Classes * */
$prefix4 = '';
$myarray4 = '';
if (!empty($array_opt)) {
    foreach ($array_opt as $rdata) {
        $myarray4 .= $prefix4 . " [";
        $myarray4 .= ' "' . $rdata[0] . '" , ' . $rdata[1] . " ]";
        $prefix4 = ",\n";
    }
}

/** My clients * */
$prefix1 = '';
$myarray1 = '';
if (!empty($array_nclient)) {
    foreach ($array_nclient as $cdata) {
        $myarray1 .= $prefix1 . " [";
        $myarray1 .= ' "' . $cdata[0] . '" , ' . $cdata[1] . " ]";
        $prefix1 = ",\n";
    }
}
/** Earing **/
$prefix_e = '';
$myarray_e = '';
if(!empty($array_earning))
{
foreach ($array_earning as $cdata) {
    $myarray_e .= $prefix_e . " [";
    $myarray_e .= ' "' . $cdata[0] . '" , ' . $cdata[1] . " ]";
    $prefix_e = ",\n";
}
}
/* * Location * */
$prefix2 = '';
$myarray2 = '';
if (!empty($array_location)) {
    foreach ($array_location as $ldata) {
        $myarray2 .= $prefix2 . " {";
        $myarray2 .= '"year":"' . $this->Gym->get_location_name_d($ldata[0]) . '" , "income":' . $ldata[1] . " }";
        $prefix2 = ",\n";
    }
}

// print_r($myarray3); die;
//
$dashboard_url = $this->Gym->createurl("StaffMembers", "viewStaff/".$row['id']);
$ncdate = @$_GET['ncdate'];
$sbdate = @$_GET['sbdate'];
$iodate = @$_GET['iodate'];
?>
<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-eye font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase"> <?php echo __("View Staff"); ?>

            </span>
        </div>
        <div class="top">

            <div class="btn-set pull-right">
                <a href="<?php echo $this->Gym->createurl("StaffMembers", "staffList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Staff List"); ?></a>

            </div>
        </div>
    </div>
    <div class="portlet-body">
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="row">
                    <!-- dashboard stuff -->
                    <div class="col-md-12">

    <!-- BEGIN DASHBOARD STATS 1-->
   <!-- BEGIN DASHBOARD STATS 1-->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 blue" href="<?php echo $this->Gym->createurl("Reports", "MembershipReport")?>">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" data-value="<?php echo number_format($assignclient);?>">0</span>
                                          </div>
                                        
                                        <div class="desc">My Active Clients</div>
                                         <span class="desc"> New Clients <span data-counter="counterup" data-value="<?php echo number_format($newclient);?>">0</span></span>
                                    </div>
                                    
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 red" href="<?php echo $this->Gym->createurl("Payroll", "MyPaycheck")?>">
                                    <div class="visual">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            $<span data-counter="counterup" data-value="<?php echo number_format($payroll_amt,2);?>">0</span> </div>
                                        <div class="desc"> My Last Paycheck</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 green" href="<?php echo $this->Gym->createurl("ClassSchedule", "Schedules")?>">
                                    <div class="visual">
                                        <i class="fa fa-shopping-cart"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number1">
                                            <div class="col-xs-4">
                                            <span class="desc">SGT</span>
                                            <div data-counter="counterup" data-value="<?php echo number_format($total_booking_tweek_sgt);?>">0</div>
                                            </div>
                                             <div class="col-xs-4">
                                            <span class="desc">OPT</span>
                                            <div data-counter="counterup" data-value="<?php echo number_format($total_booking_tweek_opt);?>">0</div>
                                             </div>
                                             <div class="col-xs-4">
                                            <span class="desc">PT</span>
                                            <div data-counter="counterup" data-value="<?php echo number_format($total_booking_tweek_pt);?>">0</div>
                                             </div>
                                            
                                        </div>
                                        
                                        <div class="desc number">Bookings This Week </div>
                                        
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a class="dashboard-stat dashboard-stat-v2 purple" href="<?php echo $this->Gym->createurl("ClassSchedule", "Schedules")?>">
                                    <div class="visual">
                                        <i class="fa fa-globe"></i>
                                    </div>
                                    <div class="details">
                                         <div class="number1">
                                            <div class="col-xs-4">
                                            <span class="desc">SGT</span>
                                           <div data-counter="counterup" data-value="<?php echo number_format($total_booking_nweek_sgt);?>">0</div>
                                            </div>
                                              <div class="col-xs-4">
                                            <span class="desc">OPT</span>
                                             <div  data-counter="counterup" data-value="<?php echo number_format($total_booking_nweek_opt);?>">0</div>
                                             </div>
                                             <div class="col-xs-4">
                                            <span class="desc">PT</span>
                                              <div  data-counter="counterup" data-value="<?php echo number_format($total_booking_nweek_pt);?>">0</div>
                                             </div>
                                            
                                        </div>
                                        <div class="desc number">Bookings Next Week </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <!-- END DASHBOARD STATS 1-->  
                        
                         <div class="row">
                             
                            <div class="col-lg-6 col-xs-12 col-sm-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-bar-chart font-dark hide"></i>
                                            <span class="caption-subject font-dark bold uppercase">My Classes</span>
                                            <span class="caption-helper">&nbsp;</span>
                                        </div>
                                        <div class="actions">
                                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                <label class="btn red btn-outline btn-circle btn-sm active" id="sgtbtn">
                                                    <input type="radio" name="options" class="toggle" >SGT</label>
                                                <label class="btn red btn-outline btn-circle btn-sm" id="ptbtn">
                                                    <input type="radio" name="options" class="toggle" >PT</label>
                                                <label class="btn red btn-outline btn-circle btn-sm" id="optbtn">
                                                    <input type="radio" name="options" class="toggle" >OPT</label>
                                            </div>
                                            
                                            <div class="btn-group">
                                                <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Filter Range
                                                    <span class="fa fa-angle-down"> </span>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php 
                                                    $cyear=date('Y');
                                                    for($w=$cyear;$w >=$cyear-4;$w--)
                                                    {
                                                        
                                                    ?>
                                                    <li <?php if($w==$sbdate){echo "class='active'";}?>>
                                                        <a href='<?php echo $dashboard_url.'?iodate='.$iodate.'&ncdate='.$ncdate.'&sbdate='.$w?>'> <?php echo $w;?>
                                                            <?php if($w==$cyear){?><span class="label label-sm label-success"> current </span><?Php }else{?>
                                                            <span class="label label-sm label-default"> past </span><?php } ?>
                                                        </a>
                                                    </li>
                                                    <?php }?>
                                                    
                                                   
                                                   
                                                </ul>
                                            </div>
                                        </div>
                                        
                                    </div>
                                   
                                    <div class="portlet-body" id="sgt_div">
                                        <div id="site_statistics_loading">
                                            <img src="<?php echo $this->request->webroot;?>css/assets/global/img/loading.gif" alt="loading" /> </div>
                                        <div id="site_statistics_content" class="display-none">
                                            <div id="site_statistics1" class="chart" style="height: 300px;"> </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body" id="pt_div" >
                                        <div id="site_statistics_loading111">
                                            <img src="<?php echo $this->request->webroot;?>css/assets/global/img/loading.gif" alt="loading" /> </div>
                                        <div id="site_statistics_content111" class="display-none">
                                            <div id="site_statistics111" class="chart" style="height: 300px;"> </div>
                                        </div>
                                    </div>
                                     <div class="portlet-body" id="opt_div" >
                                         <div id="site_statistics_loading1111">
                                            <img src="<?php echo $this->request->webroot;?>css/assets/global/img/loading.gif" alt="loading" /> </div>
                                        <div id="site_statistics_content1111" class="display-none">
                                            <div id="site_statistics1111" style="height: 300px;"> </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                            
                             
                            
                             
                             
                            <div class="col-lg-6 col-xs-12 col-sm-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-share font-red-sunglo hide"></i>
                                            <span class="caption-subject font-dark bold uppercase">My Clients</span>
                                            <span class="caption-helper">&nbsp;</span>
                                        </div>
                                        <div class="actions">
                                            <div class="btn-group">
                                                <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Filter Range
                                                    <span class="fa fa-angle-down"> </span>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                   <?Php
                                                    $cyear=date('Y');
                                                    for($x=$cyear;$x >=$cyear-4;$x--)
                                                    {
                                                   
                                                    ?>
                                                    <li <?php if($x==$ncdate){echo "class='active'";}?>>
                                                        <a href='<?php echo $dashboard_url.'?iodate='.$iodate.'&ncdate='.$x.'&sbdate='.$sbdate?>'> <?php echo $x;?> 
                                                            <?php if($x==$cyear){?><span class="label label-sm label-success"> current </span><?Php }else{?>
                                                            <span class="label label-sm label-default"> past </span><?php } ?>
                                                        </a>
                                                    </li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="site_activities_loading">
                                            <img src="<?php echo $this->request->webroot;?>css/assets/global/img/loading.gif" alt="loading" /> </div>
                                        <div id="site_activities_content" class="display-none">
                                            <div id="site_activitiess" style="height: 228px;"> </div>
                                        </div>
                                        <div style="margin: 20px 0 10px 30px">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                                    <span class="label label-sm label-warning"> Count </span>
                                                    <h3><?php echo ($newmale+$newfemale);?></h3>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                                    <span class="label label-sm label-info"> Total Sales </span>
                                                    <h3>$<?php echo number_format($nclient_sales);?></h3>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                                    <span class="label label-sm label-danger"> Males </span>
                                                    <h3><?php echo ($newmale > 0 ? $newmale : 0);?></h3>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                                    <span class="label label-sm label-success"> Females </span>
                                                    <h3><?php echo ($newfemale > 0 ? $newfemale : 0);?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6 col-xs-12 col-sm-12">
                                 <div class="portlet light bordered">
                                     <div class="portlet box green">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-comments"></i>UPCOMING SCHEDULE </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th> # </th>
                                                        <th> Class Name </th>
                                                        <th> Date </th>
                                                        <th> Time </th>
                                                       
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                   

                                                    ////

                                                  /*  $custom_date_array = array();
                                                    $date = date("Y-m-d");
                                                    //$next_date=date('Y-m-d',  strtotime($date));
                                                    $day = date('l', strtotime($date));
                                                    */

                                                    if (!empty($up_schedule)) {
                                                        $s=1;
                                                        foreach ($up_schedule as $schedule) {
                                                         
                                                         $canceled_by_traier = $this->Gym->get_sgt_status($schedule['cID']);
                                                            if ($canceled_by_traier)
                                                                continue;
                                                            // print_r($schedule);
                                                            ?>
                                                            <tr>
                                                                <td> <?php echo $s;?> </td>
                                                                <td><?php echo $this->Gym->get_classes_by_id($schedule['class_name']) ?> <br><strong> <?php echo $schedule['schedule_type'];?></strong></td>
                                                                <td><?php echo date('F j, Y', strtotime($schedule['schedule_date']));?></td>
                                                                <td> <?php echo $schedule['start_time'];?> - <?php echo $schedule['end_time'];?></td>

                                                            </tr>
                                                            <?Php 
                                                            $s++;
                                                            }
                                                            
                                                               } 
                                                    
                                                    
                                                            
                                                    
                                                    ?>


                                                    </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                     
                                     
                                     
                                 </div>
                            </div>
                            <div class="col-lg-6 col-xs-12 col-sm-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-share font-red-sunglo hide"></i>
                                            <span class="caption-subject font-dark bold uppercase">My Earnings</span>
                                            <span class="caption-helper">&nbsp;</span>
                                        </div>
                                        <div class="actions">
                                            <div class="btn-group">
                                                <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Filter Range
                                                    <span class="fa fa-angle-down"> </span>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                   <?Php
                                                    $cyear=date('Y');
                                                    for($x=$cyear;$x >=$cyear-4;$x--)
                                                    {
                                                   
                                                    ?>
                                                    <li <?php if($x==$iodate){echo "class='active'";}?>>
                                                        <a href='<?php echo $dashboard_url.'?iodate='.$x.'&ncdate='.$ncdate.'&sbdate='.$sbdate?>'> <?php echo $x;?> 
                                                            <?php if($x==$cyear){?><span class="label label-sm label-success"> current </span><?Php }else{?>
                                                            <span class="label label-sm label-default"> past </span><?php } ?>
                                                        </a>
                                                    </li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="portlet-body">
                                        <div id="site_activities_loadingsss">
                                            <img src="<?php echo $this->request->webroot;?>css/assets/global/img/loading.gif" alt="loading" /> </div>
                                        <div id="site_activities_contentsss" class="display-none">
                                            <div id="site_activitiesss" style="height: 350px;"> </div>
                                        </div>
                                        
                                    </div>
                                   
                                </div>
                            </div>
                            
                             
                        </div>
</div>
                    <!-- dashboard stuff -->
                    
                    <!-- Personal Info -->
                    <div class="col-md-12 col-sm-12">
                        <div class="portlet blue-hoki box">
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
                                    <div class="col-md-2 name"> <?php echo __("User Name"); ?>: </div>
                                    <div class="col-md-4 value"> <?php echo $row["username"]; ?> </div>
                                    <div class="col-md-2 name"> <?php echo __("Name"); ?>: </div>
                                    <div class="col-md-4 value"> <?php echo $row["first_name"] . ( (isset($row["middle_name"])) ? (' ' . $row["middle_name"] ) : '' ) . ( (isset($row["last_name"])) ? (' ' . $row["last_name"] ) : '' ); ?> </div>
                                </div>
                                <!--<div class="row static-info">
                                    <div class="col-md-2 name"> <?php echo __("Associated Licensee"); ?>: </div>
                                    <div class="col-md-4 value"> <?php echo $this->Gym->get_user_name($row["associated_licensee"]); ?> </div>
                                    <div class="col-md-2 name"> <?php echo __("Assigned Role"); ?>: </div>
                                    <div class="col-md-4 value">  <?php echo $row['gym_role']["name"]; ?> </div>
                                </div>-->
                                <div class="row static-info">
                                    <div class="col-md-2 name"> <?php echo __("Email"); ?>: </div>
                                    <div class="col-md-4 value"> <?php echo $row["email"]; ?> </div>
                                    <div class="col-md-2 name"> <?php echo __("Location"); ?>: </div>
                                    <div class="col-md-4 value">  <?php echo $this->Gym->getUserLocation($row['associated_licensee']); ?> </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-2 name"> <?php echo __("Address"); ?>: </div>
                                    <div class="col-md-4 value"> <?php echo $row["address"]; ?> </div>
                                    <div class="col-md-2 name"> <?php echo __("City"); ?>: </div>
                                    <div class="col-md-4 value">  <?php echo $row["city"]; ?></div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-2 name"> <?php echo __("State"); ?>: </div>
                                    <div class="col-md-4 value"> <?php echo $row["state"]; ?> </div>
                                    <div class="col-md-2 name"> <?php echo __("Zip Code"); ?>: </div>
                                    <div class="col-md-4 value">  <?php echo $row["zipcode"]; ?></div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-2 name"> <?php echo __("Mobile"); ?>: </div>
                                    <div class="col-md-4 value"> <?php echo $row["mobile"]; ?> </div>
                                    <div class="col-md-2 name"> <?php echo __("Created Date"); ?>: </div>
                                    <div class="col-md-4 value">  <?php echo date($this->Gym->getSettings("date_format"), strtotime($row["created_date"])); ?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- -->

                    <!-- Attendance Management -->
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

                                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                        <label class="control-label col-md-2"> Select Schedule</label>
                                        <div class="form-group col-md-3">
                                            <select name="selected_membership" id="membership_id" class="form-control validate[required]">
                                                <option value="">Select Schedule</option>
                                                <option value="SGT">SGT</option>
                                                <option value="PT">PT</option>
                                                <option value="OPT">OPT</option>

                                            </select>

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

                                        <div class="form-group col-md-2">
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
                    <!-- -->

                    <!-- Calendar -->
                    <div class="col-xs-12 col-sm-12">
                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-calendar-plus-o"></i>
<?php echo $row['first_name'] . ' ' . $row['last_name'] . "'s Calendar"; ?> 
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
                    <!-- -->

                    <!------------------------- My Goals -->
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="goal-dash-title">
                                <div class="portlet-title col-sm-3" style="border:0;">
                                    <div class="caption">
                                        <i class="icon-settings"></i>
                                        <span class="caption-subject bold uppercase"> 
<?php echo $row['first_name'] . ' ' . $row['last_name'] . "'s Goals"; ?> 
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
                                </div> 
                                <div class="clearfix"></div>
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
                                            <a href="<?php echo $this->request->base . '/my-goals/details/' . $goals['id'] . "/" . $row['id']; ?>" class="list-group-item list-group-item goal-box">
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
                                                <div class="goal-status <?= $status ?>"><?= ($status == 'Goal Failed') ? 'Incomplete Goal' : $status; ?></div>


    <?php
    $targets = json_decode($goals['target'], true);
    $initValues = json_decode($goals['initValues'], true);
    foreach ($targets as $targetIndex => $targetVal) {
        if ($targetVal < $initValues[$targetIndex]) {
            $lossGain = 'Lose';
            $diff = $initValues[$targetIndex] - $targetVal;
        } else {
            $lossGain = 'Gain';
            $diff = $targetVal - $initValues[$targetIndex];
        }
        ?>
                                                    <div class="col-sm-12 margin-bottom">
                                                        <div class="col-sm-7" style="color:#000;"><?php echo $this->Gym->getTargetKeys($targetIndex); ?> </div> 
                                                        <div class="col-sm-5">
                                                            <strong class="pull-right" style="color:#999"><small> <?= $lossGain ?> </small><?php echo $diff; ?> <?php echo ( $this->Gym->getUnit('imperial', $targetIndex) ) ? $this->Gym->getUnit('imperial', $targetIndex) : ''; ?> </strong>
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
                                        <input type="text" name="weight" id="weight" value="<?php echo @$baselineMeasurements['weight']; ?>" class="form-control validate[required, custom[number]]" placeholder="" aria-controls="sample_1" onblur="calclbm()">
                                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial', 'weight'); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label><strong>Height</strong></label>
                                                <input type="text" name="height1" value="<?php echo @$baselineMeasurements['height1']; ?>" class="form-control validate[required, custom[number]]" placeholder="" aria-controls="sample_1">
                                                <span class="input-group-addon right">ft</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="invisible">Height</label>
                                                <input type="text" name="height2" value="<?php echo @$baselineMeasurements['height2']; ?>" class="form-control validate[required, custom[number]]" placeholder="" aria-controls="sample_1">
                                                <span class="input-group-addon right">in</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label><strong>Activity Level</strong></label>
                                        <select name="activityLevel" id="activityLevel" class="form-control">
                                            <option value="low" selected="selected">Low</option>
                                            <option value="moderate" <?php (isset($baselineMeasurements['activityLevel']) && $baselineMeasurements['activityLevel'] == 'moderate') ? "selected='selected'" : ''; ?>>Moderate</option>
                                            <option value="high" <?php (isset($baselineMeasurements['activityLevel']) && $baselineMeasurements['activityLevel'] == 'high') ? "selected='selected'" : ''; ?>>High</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label><strong>Body Fat</strong></label>
                                        <input type="text" name="bodyFat" id="bodyFat" value="<?php echo @$baselineMeasurements['bodyFat']; ?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1" onblur="calclbm()">
                                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial', 'bodyFat'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label><strong>Lean Body Mass</strong></label>
                                        <input type="text" name="leanBodyMass" id="leanBodyMass" value="<?php echo @$baselineMeasurements['leanBodyMass']; ?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1" readonly="readonly">
                                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial', 'leanBodyMass'); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label><strong>Water Weight</strong></label>
                                        <input type="text" name="waterWeight" value="<?php echo @$baselineMeasurements['waterWeight']; ?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1">
                                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial', 'waterWeight'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label><strong>Bone Density</strong></label>
                                        <input type="text" name="boneDensity" value="<?php echo @$baselineMeasurements['boneDensity']; ?>" class="form-control validate[custom[number]]" placeholder="" aria-controls="sample_1">
                                        <span class="input-group-addon right"><?php echo $this->Gym->getUnit('imperial', 'boneDensity'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="submit" class="btn btn-flat btn-primary" name="save" value="<?php echo ($baselineEdit) ? 'Update' : 'Submit'; ?>">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
<?php echo $this->Form->end(); ?>
                            </div>
                            <div class="clearfix"></div>
                            <!-- Search Criteria----------------------> 
                            <!-- /Search Criteria----------------------> 

                        </div>
                    </div>
                    <!-- End Base Line measurement --------------------------->
                    
                    <!-- Referral History --------------->
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
                                            <!--<th> Discount Amount </th>-->
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
                                                    //$discount_amount = ( ( $this->Gym->getLowestPlanPriceBasedOnLocation($my_referral['id']) ) * 5 )/100;
                                                    //$discount_amount_total += $discount_amount;
                                                    $url = (isset($my_referral['image']) && $my_referral['image'] != "") ? $my_referral['image'] : $this->request->base . "/upload/profile-placeholder.png"; 
                                                    echo "<tr> <td><img class='img-responsive' src='{$url}' alt=''></td>
                                                                            <td>{$my_referral["first_name"]} {$my_referral["last_name"]}</td>
                                                                             <td>" . $my_referral["email"] . "</span></td>";
                                                                            //<td>". $this->Gym->get_currency_symbol() .number_format($discount_amount, 2) ."</td>";

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
                                <!--<div class="row">
                                    <div class="col-md-6"> </div>
                                    <div class="col-md-6">
                                        <div class="well">

                                            <div class="row static-info align-reverse">
                                                <div class="col-md-8 name"> Total Discount: </div>
                                                <div class="col-md-3 value"> $ <?php //echo number_format(@$discount_amount_total, 2); ?> </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                    <!-- /Referral History --------------->
                </div>
            </div>
        </div>
    </div>
</div>



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
            slotDuration: '00:15:00',
            editable: false,
            firstDay:1,
            eventLimit: true, // allow "more" link when too many events
            events: <?php echo json_encode($cal_array); ?>,
            eventClick: function (event, jsEvent, view) {
                console.log('event:', event);
                //console.log('jsEvent:',jsEvent);
                //console.log('view:',view);
                //console.log(event.start._i);
                var user_id = "<?php echo $row['id']; ?>";
                var role_id = "<?php echo $row['role_id']; ?>";
                var title = event.title;
                var titleUnique = event.titleUnique;
                var uniqueId = event.uniqueId;
                var date = event.start._i;
                var ajaxurl = "<?php echo $base_url; ?>" + "/GymAjax/get" + titleUnique + "DetailsViewMember";
                //alert('title: ' + title + ' ------- date: ' + date + ' ---- ajaxUrl: ' + ajaxurl);
                var curr_data = {role_id: role_id, user_id: user_id, date: date, title: titleUnique, id: uniqueId};
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
                                        $('.modal-body #' + index).text(value + ' ' + unit);
                                    }
                                });
                            }else if (result.title == 'Workout') {
                                $('#workout_date').text(moment(result.data.createdAt).format('ddd MMM DD, YYYY HH:mm'));
                                initializeChart(result.data.zonesDuration, result.data.duration);
                                //alert(result.data.duration);
                                var duration = getDayHourMinSec(0, result.data.duration);
                                $('#duration').text(duration);
                                $('#points').text(result.data.points);
                                $('#calorie').text(result.data.calorie + ' Kcal');
                                $('#averageHr').text(result.data.averageHr);
                                $('#avragePercentage').text((((result.data.averageHr) * 100) / result.data.averageMaxHr).toFixed());
                                $('#workout_client_name').text('<?php echo strtoupper($this->Gym->get_user_name($row['id']));?>');
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
            return (unitType == 'imperial') ? 'in' : 'cm';
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
                    "percent": ((zonesDurationArr[0] * 100) / duration).toFixed(2) + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[0])
                },
                {
                    "country": "ZONE 2",
                    "visits": (zonesDurationArr[1])/(1000*60).toFixed(2),
                    "color": "#2199BE",
                    "percent": ((zonesDurationArr[1] * 100) / duration).toFixed(2) + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[1])
                }, {
                    "country": "ZONE 3",
                    "visits": (zonesDurationArr[2])/(1000*60).toFixed(2),
                    "color": "#3CC24F",
                    "percent": ((zonesDurationArr[2] * 100) / duration).toFixed(2) + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[2])
                }, {
                    "country": "ZONE 4",
                    "visits": (zonesDurationArr[3])/(1000*60).toFixed(2),
                    "color": "#F7A80A",
                    "percent": ((zonesDurationArr[3] * 100) / duration).toFixed(2) + "%",
                    "zoneDuration" : getDayHourMinSec(0,zonesDurationArr[3])
                }, {
                    "country": "ZONE 5",
                    "visits": (zonesDurationArr[4])/(1000*60).toFixed(2),
                    "color": "#EA4221",
                    "percent": ((zonesDurationArr[4] * 100) / duration).toFixed(2) + "%",
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

            var membership_id = $("#membership_id").val();
            var month_id = $("#month_id").val();

            if (membership_id != '' && month_id != '')
            {

                var url = "<?php echo $this->request->base . '/GymAjax/fetchAttendanceStaffData'; ?>"; // the script where you handle the form input.

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

    });
</script>
<script>

    $("input[name='filter_by_status']").on('change', function () {
        $('#viewGoals').submit();
    });

</script>

<script>
        function showChartTooltip(x, y, xValue, yValue) {
            $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 40,
                border: '0px solid #ccc',
                padding: '2px 6px',
                'background-color': '#fff'
            }).appendTo("body").fadeIn(200);
        }
        var visitors = [
<?php echo $myarray; ?>

        ];
        var visitorss = [
<?php echo $myarray3; ?>

        ];

        var visitorsss = [
<?php echo $myarray4; ?>

        ];

        if ($('#site_statistics1').size() != 0) {

            $('#site_statistics_loading').hide();
            $('#site_statistics_content').show();

            var plot_statistics = $.plot($("#site_statistics1"), [{
                    data: visitors,
                    lines: {
                        fill: 0.6,
                        lineWidth: 0
                    },
                    color: ['#f89f9f']
                }, {
                    data: visitors,
                    points: {
                        show: true,
                        fill: true,
                        radius: 5,
                        fillColor: "#f89f9f",
                        lineWidth: 3
                    },
                    color: '#fff',
                    shadowSize: 0
                }],
                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

            var previousPoint = null;
            $("#site_statistics1").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                        showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' Classes');
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        }

        ///PT Classes
        //
        if ($('#site_statistics111').size() != 0) {

            $('#site_statistics_loading111').hide();
            $('#site_statistics_content111').show();

            var plot_statistics = $.plot($("#site_statistics111"), [{
                    data: visitorss,
                    lines: {
                        fill: 0.6,
                        lineWidth: 0
                    },
                    color: ['#f89f9f']
                }, {
                    data: visitorss,
                    points: {
                        show: true,
                        fill: true,
                        radius: 5,
                        fillColor: "#f89f9f",
                        lineWidth: 3
                    },
                    color: '#fff',
                    shadowSize: 0
                }],
                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

            var previousPoint = null;
            $("#site_statistics111").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                        showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' Schedule');
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        }
        //OPT classes

        if ($('#site_statistics1111').size() != 0) {

            $('#site_statistics_loading1111').hide();
            $('#site_statistics_content1111').show();

            var plot_statistics = $.plot($("#site_statistics1111"), [{
                    data: visitorsss,
                    lines: {
                        fill: 0.6,
                        lineWidth: 0
                    },
                    color: ['#f89f9f']
                }, {
                    data: visitorsss,
                    points: {
                        show: true,
                        fill: true,
                        radius: 5,
                        fillColor: "#f89f9f",
                        lineWidth: 3
                    },
                    color: '#fff',
                    shadowSize: 0
                }],
                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

            var previousPoint = null;
            $("#site_statistics1111").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                        showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' Schedule');
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });

        }


        // new clients Graph
        if ($('#site_activitiess').size() != 0) {
            //site activities
            var previousPoint2 = null;
            $('#site_activities_loading').hide();
            $('#site_activities_content').show();

            var data1 = [<?php echo $myarray1; ?>
            ];


            var plot_statistics = $.plot($("#site_activitiess"),
                    [{
                            data: data1,
                            lines: {
                                fill: 0.2,
                                lineWidth: 0,
                            },
                            color: ['#BAD9F5']
                        }, {
                            data: data1,
                            points: {
                                show: true,
                                fill: true,
                                radius: 4,
                                fillColor: "#9ACAE6",
                                lineWidth: 2
                            },
                            color: '#9ACAE6',
                            shadowSize: 1
                        }, {
                            data: data1,
                            lines: {
                                show: true,
                                fill: false,
                                lineWidth: 3
                            },
                            color: '#9ACAE6',
                            shadowSize: 0
                        }],
                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

            $("#site_activitiess").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));
                if (item) {
                    if (previousPoint2 != item.dataIndex) {
                        previousPoint2 = item.dataIndex;
                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                        showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + " Members");
                    }
                }
            });

            $('#site_activitiess').bind("mouseleave", function () {
                $("#tooltip").remove();
            });
        }
        /////

        ///My Earing

        if ($('#site_activitiesss').size() != 0) {
            //site activities
            var previousPoint2 = null;
            $('#site_activities_loadingsss').hide();
            $('#site_activities_contentsss').show();

            var data1 = [<?php echo $myarray_e; ?>
            ];


            var plot_statistics = $.plot($("#site_activitiesss"),
                    [{
                            data: data1,
                            lines: {
                                fill: 0.2,
                                lineWidth: 0,
                            },
                            color: ['#BAD9F5']
                        }, {
                            data: data1,
                            points: {
                                show: true,
                                fill: true,
                                radius: 4,
                                fillColor: "#9ACAE6",
                                lineWidth: 2
                            },
                            color: '#9ACAE6',
                            shadowSize: 1
                        }, {
                            data: data1,
                            lines: {
                                show: true,
                                fill: false,
                                lineWidth: 3
                            },
                            color: '#9ACAE6',
                            shadowSize: 0
                        }],
                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

            $("#site_activitiesss").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));
                if (item) {
                    if (previousPoint2 != item.dataIndex) {
                        previousPoint2 = item.dataIndex;
                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                        showChartTooltip(item.pageX, item.pageY, item.datapoint[0], '$' + item.datapoint[1]);
                    }
                }
            });

            $('#site_activitiesss').bind("mouseleave", function () {
                $("#tooltip").remove();
            });
        }


        /// Location Chart
        setTimeout(function () {
            $("#pt_div").hide();
            $("#opt_div").hide();
        }, 1);
        $("#sgtbtn").click(function () {
            $("#sgt_div").show();
            $("#pt_div").hide();
            $("#opt_div").hide();
        });
        $("#ptbtn").click(function () {
            $("#sgt_div").hide();
            $("#pt_div").show();
            $("#opt_div").hide();
        });
        $("#optbtn").click(function () {
            $("#opt_div").show();
            $("#sgt_div").hide();
            $("#pt_div").hide();

        });

        /// Ajax chages graph
        $(".sales_location_data").click(function () {

            var ids = $this.attr("data-id");
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
        
        return "" + hh + ":" + mm + ":" + ss; //00:15:00
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