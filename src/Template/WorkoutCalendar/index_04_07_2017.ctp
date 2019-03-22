<?php
echo $this->Html->css('fullcalendar');

echo $this->Html->css('assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css');
echo $this->Html->css('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css');
echo $this->Html->css('assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css');

echo $this->Html->script('fullcalendar.min');

echo $this->Html->script('assets/global/plugins/jquery.mockjax.js');
echo $this->Html->script('assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js');
echo $this->Html->script('assets/global/plugins/bootstrap-editable/inputs-ext/address/address.js');
echo $this->Html->script('assets/global/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js');
echo $this->Html->script('assets/pages/scripts/form-editable.js');


//BEGIN PAGE LEVEL PLUGINS

echo $this->Html->script('assets/pages/scripts/charts-amcharts.min.js');


echo $this->Html->script('lang-all');
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Workout Calendar');
$base_url = $this->request->base;
?>
<?php //echo json_encode($cal_array); die;   ?>

<!-- Calender-->
<div class="workout-calender-chart">
    <div class="col-xs-12 col-sm-12 workout-calender-chart-left">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-calendar-plus-o"></i><?php echo __("My Calendar"); ?> </div>

            </div>
            <div class="portlet-body">
                <div id="calendars"></div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Google Calendar Event</h4>
            </div>
            <div class="modal-body"> <?php print "<a class='login' href='$authUrl'>Connect Me!</a>";?></div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
               
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
                                            <strong> EMILY ACEVES </strong>
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
                                    <span id="avragePercentage"></span>% </div>
                                <a class="title" href="javascript:;"> Average Percentage

                                </a>

                            </div>
                            <div class="clearfix"></div>
                        </div> </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<!-- Create Schedule Modal -->
<?php if($session['role_id']!=1){ ?>
<div id="createPtOptModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title caption-subject font-red sbold uppercase" id="createPtOptModal_title">
                    <i class=" icon-layers font-red"></i> Create PT/OPT Schedule
                </h4>
            </div>
            <div class="modal-body" id="createPtOpt_modal_body">
                <!-------------- Switch Busy ----------->
                        
                <!--<input type="checkbox" name="trainer_availability" checked />-->

                <!-------------- Switch Busy ----------->
                <?php echo $this->Form->create("addClass", ["type" => "file", "class" => "validateForm form-horizontal", "role" => "form", "url" => "/class-schedule/schedule-personal-trainings"]); ?>
                <div class="form-body" id="at_work_div">
                    <h4><legend>Customer & Training Information</legend></h4>
                    <div class="form-group form-md-line-input col-md-12">
                        <label class="col-md-3 control-label" for="form_control_1">Schedule Iteration 
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="md-radio-horizontal col-md-9">
                            <div class="md-radio col-md-4">
                                <input type="radio" id="checkbox2_1" value="recurring" name="schedule_iteration" class="check_limit md-radiobtn">
                                <label for="checkbox2_1">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> <?php echo __("Recurring Schedule"); ?> </label>
                            </div>

                            <div class="md-radio col-md-4">
                                <input type="radio" id="checkbox2_2" value="onetime" name="schedule_iteration" class="check_limit md-radiobtn" checked="checked">
                                <label for="checkbox2_2">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> <?php echo __("One Time Schedule"); ?> </label>
                            </div>

                        </div>
                    </div>
                    <div class="form-group form-md-line-input col-md-12">

                        <label class="col-md-3 control-label" for="form_control_1">Schedule Type
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-6">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1_1" value="PT" name="schedule_type" class="check_limit md-radiobtn">
                                    <label for="checkbox1_1">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> <?php echo __("Personal Training"); ?> 
                                    </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1_2" value="OPT" name="schedule_type" class="check_limit md-radiobtn">
                                    <label for="checkbox1_2">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> <?php echo __("Online Personal Training"); ?>
                                    </label>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Select Member
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-12">
                                <div class="input-group-control">
                                    <?php echo @$this->Form->select("member_for", $customers, ["default" => $data['member_for'], "empty" => __("Select Member"), "class" => "form-control validate[required]", "onchange" => "findMembership(this.value)", "id" => "member_for"]); ?>
                                    <div class="form-control-focus"> </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <div class="col-md-10 col-md-offset-2 alert alert-warning" id="member_plan_details_div_notice" style="display:none;">

                        </div>
                        <div class='col-md-10 col-md-offset-2' id="member_plan_details_div">
                            <!--schedules list table will render here-->
                        </div>
                    </div>

                    <h4><legend>Schedule Information</legend></h4>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Start Date
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="sdate form-control validate[required]" value="" placeholder="Enter Start Date"  name="start_date" id="start_date">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Start Date....</span>
                        </div>
                    </div>

                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">End Date
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="sdate form-control validate[required]" value="" placeholder="Enter End Date" id="end_date" name="end_date">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter End Date....</span>
                        </div>
                    </div>-->
                    
                    <input type="hidden" name="end_date" id="end_date" value="">

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Select Days for a Week
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9" id="day-section">
                            <?php
                            $days = ["Sunday" => __("Sunday"), "Monday" => __("Monday"), "Tuesday" => __("Tuesday"), "Wednesday" => __("Wednesday"), "Thursday" => __("Thursday"), "Friday" => __("Friday"), "Saturday" => __("Saturday")];
                            echo @$this->Form->select("days", $days, ["default" => json_decode($data['days']), "multiple" => "multiple", "class" => "form-control validate[required] day_list select2-multiple", "id" => "days"]);
                            ?>
                            <small>*You will be able to select day(s) per week as per customer's membership plan.</small>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <?php
                    $hrs = ["00" => "00", "01" => "01", "02" => "02", "03" => "03", "04" => "04", "05" => "05", "06" => "06", "07" => "07", "08" => "08", "09" => "09", "10" => "10", "11" => "11", "12" => "12"];
                    $min = ["00" => "00", "15" => "15", "30" => "30", "45" => "45"];
                    $ampm = ["AM" => "AM", "PM" => "PM"];
                    ?>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Start Time
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("start_hrs", $hrs, ["default" => $data['start_hrs'], "id" => "start_hrs", "empty" => __("Select Time"), "class" => "start_hrs form-control validate[required]"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Select Time....</span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("start_min", $min, ["default" => $data['start_min'], "id" => "start_min", "class" => "start_min form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("start_ampm", $ampm, ["default" => $data['start_ampm'], "id" => "start_ampm", "class" => "start_ampm form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">End Time
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("end_hrs", $hrs, ["default" => $data['end_hrs'], "id" => "end_hrs", "empty" => __("Select Time"), "class" => "end_hrs form-control validate[required]"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select Time....</span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("end_min", $min, ["default" => $data['end_min'], "id" => "end_min", "class" => "end_min form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("end_ampm", $ampm, ["default" => $data['end_ampm'], "id" => "end_ampm", "class" => "end_ampm form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    

                    <div class="form-group form-md-line-input">
                        <div class="col-md-offset-2 col-md-6">

                            <?php
                            echo $this->Form->button(__("Save Class"), ['class' => "btn btn-flat btn-primary col-md-offset-2", "name" => "add_class", "id" => "add_class_btn"]);
                            echo ' <button type="reset" class="btn default">Reset</button>';
                            echo $this->Form->end();
                            ?>
                        </div>
                    </div>
                </div>
                
                <?php echo $this->Form->create("setAvailability", ["type" => "file", "class" => "validateForm form-horizontal", "role" => "form", "url" => "/class-schedule/set-availability"]); ?>
                <div class="form-body" id="engage_me_div" style="display:none;">
                    <h4><legend>Unavailability</legend></h4>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Start Date
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="date form-control validate[required]" value="" placeholder="Enter Start Date"  name="start_date_avail" id="start_date_avail">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Start Date....</span>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">End Date
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="date form-control validate[required]" value="" placeholder="Enter End Date" id="end_date_avail" name="end_date_avail">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter End Date....</span>
                        </div>
                    </div>
                    
                    <?php
                    $hrs = ["00" => "00", "01" => "01", "02" => "02", "03" => "03", "04" => "04", "05" => "05", "06" => "06", "07" => "07", "08" => "08", "09" => "09", "10" => "10", "11" => "11", "12" => "12"];
                    $min = ["00" => "00", "15" => "15", "30" => "30", "45" => "45"];
                    $ampm = ["AM" => "AM", "PM" => "PM"];
                    ?>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Start Time
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("start_hrs_avail", $hrs, ["default" => $data['start_hrs'], "id" => "start_hrs_avail", "empty" => __("Select Time"), "class" => "start_hrs form-control validate[required]"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Select Time....</span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("start_min_avail", $min, ["default" => $data['start_min'], "id" => "start_min_avail", "class" => "start_min form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("start_ampm_avail", $ampm, ["default" => $data['start_ampm'], "id" => "start_ampm_avail", "class" => "start_ampm form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">End Time
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("end_hrs_avail", $hrs, ["default" => $data['end_hrs'], "id" => "end_hrs_avail", "empty" => __("Select Time"), "class" => "end_hrs form-control validate[required]"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select Time....</span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("end_min_avail", $min, ["default" => $data['end_min'], "id" => "end_min_avail", "class" => "end_min form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-md-3">
                            <?php echo @$this->Form->select("end_ampm_avail", $ampm, ["default" => $data['end_ampm'], "id" => "end_ampm_avail", "class" => "end_ampm form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1"> Reasons <small>(if any)<small
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9 form">
                              <?php  echo $this->Form->textarea("desc_avail", ["rows" => "15", "class" => "wysihtml5 form-control"]); ?>
                                 
                               <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Description...</span>
                        </div>
                    </div>
                    

                    <div class="form-group form-md-line-input">
                        <div class="col-md-offset-2 col-md-6">

                            <?php
                            echo $this->Form->button(__("Submit"), ['class' => "btn btn-flat btn-primary col-md-offset-2", "name" => "engage_me", "id" => "engage_me"]);
                            echo ' <button type="reset" class="btn default">Reset</button>';
                            echo $this->Form->end();
                            ?>
                        </div>
                    </div>
                </div>
            </div>	
            
        </div>
    </div>

</div>
<?php } ?>
</div>
<script>
    $(document).ready(function () {
        <?Php if($google_popup==1){?>
         $("#basic").modal('show');
          <?Php } ?>
        $('#calendars').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
             <?php if($session['role_id']!=1){ ?>
            defaultView: 'agendaWeek',
             <?php } else { ?>
               defaultView: 'month',   
             <?php } ?>   
            timezone: 'local',
            lang: 'en',
            slotDuration:'00:15:00',
            editable: false,
            eventRender: function (event, element) {
                element.addClass(event.class)

                //console.log(event)
            },
            eventLimit: true, // allow "more" link when too many events
            events: <?php echo json_encode($cal_array); ?>,
            eventClick: function (event, jsEvent, view) {
                var role_id = "<?php echo $session['role_id']; ?>";
                var title = event.title;
                var titleUnique = event.titleUnique;
                var uniqueId = event.uniqueId;
                var date = event.start._i;
                if (role_id != '4' && titleUnique == 'Schedule')
                    var ajaxurl = "<?php echo $base_url; ?>" + "/GymAjax/get" + titleUnique + "DetailsTrainerLicensee";
                else
                    var ajaxurl = "<?php echo $base_url; ?>" + "/GymAjax/get" + titleUnique + "Details";

                //alert('title: ' + title + ' ------- date: ' + date + ' ---- ajaxUrl: ' + ajaxurl);
                var curr_data = {date: date, title: titleUnique, id: uniqueId};
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
                            }
                            if (result.title == 'Workout') {
                                //$this->Gym->getDayHourMinSec();

                                $('#workout_date').text(moment(result.data.createdAt).format('ddd MMM DD, YYYY HH:mm'));
                                initializeChart(result.data.zonesDuration, result.data.duration);
                                var duration = getDayHourMinSec(0, result.data.duration);
                                //console.log('duration: ',duration);
                                $('#duration').text(duration);
                                $('#points').text(result.data.points);
                                $('#calorie').text(result.data.calorie + ' Kcal');
                                $('#averageHr').text(result.data.averageHr);
                                $('#avragePercentage').text((((result.data.averageHr) * 100) / result.data.averageMaxHr).toFixed(2));
                            }
                            if (result.title == 'Schedule') {
                                $('#schedule_title').text(result.modal_title + ' Schedule');
                                $('#schedule_modal_body').html(result.data);

                            }

                            $('#' + titleUnique + 'Modal').modal('show');

                        } else {
                            alert('There is no ' + titleUnique + 'added.');
                        }
                        return false;
                    }
                });
            },
            selectable: true,
            selectHelper: true,
            <?php if($session['role_id']!=1){ ?>
            select: function (start, end, jsEvent, view) {
                var d = new Date();
                if (start._d.getTime() < d.getTime()) {
                    alert("You can not create schedule for past date.");
                    return false;
                }
                
                $('#start_hrs').val(moment(start._d).format('hh')).prop('selected', true);
                $('#start_min').val(moment(start._d).format('mm')).prop('selected', true);
                $('#start_ampm').val(moment(start._d).format('A')).prop('selected', true);
                
                $('#start_hrs_avail').val(moment(start._d).format('hh')).prop('selected', true);
                $('#start_min_avail').val(moment(start._d).format('mm')).prop('selected', true);
                $('#start_ampm_avail').val(moment(start._d).format('A')).prop('selected', true);


                $('#end_hrs').val(moment(end._d).format('hh'));
                $('#end_min').val(moment(end._d).format('mm'));
                $('#end_ampm').val(moment(end._d).format('A'));
                
                $('#end_hrs_avail').val(moment(end._d).format('hh'));
                $('#end_min_avail').val(moment(end._d).format('mm'));
                $('#end_ampm_avail').val(moment(end._d).format('A'));

                $('#start_date').val(moment(start._d).format('MMM D, YYYY'));
                $('#end_date').val(moment(start._d).format('MMM D, YYYY'));
                
                $('#start_date_avail').val(moment(start._d).format('MMM D, YYYY'));
                $('#end_date_avail').val(moment(start._d).format('MMM D, YYYY'));


                $('#days').val(moment(start._d).format('dddd')).prop('selected', true);
                $('#createPtOptModal').modal('show');
                manipulateDaysField(moment(start._d).format('dddd'));
            },
            <?php } ?>
            eventMouseover: function(calEvent, jsEvent) {
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
    function manipulateDaysField(day) {
        var start_date = $('#start_date').val();
        //var end_date = $('#end_date').val();
        var schedule_iteration = $("input[name='schedule_iteration']:checked").val();
        if(schedule_iteration == 'onetime'){
            
        }
        $("input[name='schedule_iteration']").on('change',function(){
            if($(this).val() == 'recurring'){
                $('#recurring_iteration_case').css('display','block');
                $('#onetime_iteration_case').css('display','none');
            }else{
                $('#recurring_iteration_case').css('display','none');
                $('#onetime_iteration_case').css('display','block');
            }
        });
        var moment_start_date = moment(start_date);
        var moment_end_date = moment(end_date);
        var d = new Date();
        var option = "";
        var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        //console.log(moment_end_date.diff(moment_start_date, "days"));
        if (new Date(start_date).getTime() > new Date(end_date).getTime()) {
            alert("Start date can not be greater than end date");
            $("#days").empty();
            $("#end_date").val('');

        } else if ((new Date(start_date).getTime() <= new Date(end_date).getTime()) && (moment_end_date.diff(moment_start_date, "days") < 6)) {
            //console.log('fall');
            var count = 0;
            var n = moment(start_date).format('e');
            var totalIteration = parseInt(moment_end_date.diff(moment_start_date, "days"));
            for (var i = n; ; i++) {
                if (i > 6)
                    i = 0;
                option += "<option value='" + weekday[i] + "'>" + weekday[i] + "</option>";
                if (count == totalIteration)
                    break;
                count++;
            }
            $("#days").empty().append(option);
            $('#days').val(day).prop('selected', true);
        } else {
            $.each(weekday, function (i, v) {
                option += "<option value='" + v + "'>" + v + "</option>"
            });
            $("#days").empty().append(option);
            $('#days').val(day).prop('selected', true);
        }
    }
    

    $(function () {
        $('#start_date, #end_date').blur(function () {
            var start_date = $('#start_date').val();
            //var end_date = $('#end_date').val();
            $('#end_date').val(start_date);
            var end_date = $('#end_date').val();
            var moment_start_date = moment(start_date);
            var moment_end_date = moment(end_date);
            var d = new Date();
            var option = "";
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            //console.log(moment_end_date.diff(moment_start_date, "days"));
            if (new Date(start_date).getTime() > new Date(end_date).getTime()) {
                alert("Start date can not be greater than end date");
                $("#days").empty();
                $("#end_date").val('');

            } else if ((new Date(start_date).getTime() <= new Date(end_date).getTime()) && (moment_end_date.diff(moment_start_date, "days") < 6)) {
                console.log('fall');
                var count = 0;
                var n = moment(start_date).format('e');
                var totalIteration = parseInt(moment_end_date.diff(moment_start_date, "days"));
                for (var i = n; ; i++) {
                    if (i > 6)
                        i = 0;
                    option += "<option value='" + weekday[i] + "'>" + weekday[i] + "</option>";
                    if (count == totalIteration)
                        break;
                    count++;
                }
                $("#days").empty().append(option);
            } else {
                $.each(weekday, function (i, v) {
                    option += "<option value='" + v + "'>" + v + "</option>"
                });
                $("#days").empty().append(option);
            }
        });
    });

    function findMembership(mid) {
        if (mid == '') {
            return false;
        }
        var schedule_type = $("input[name='schedule_type']:checked").val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/GymAjax/find_membership'; ?>",
            data: {mid: mid, schedule_type: schedule_type},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    //var memshipLblArr = response.data.split(' ');
                    //var alowedDaysPerWeek = memshipLblArr[memshipLblArr.length - 1];
                    //reinitializeSelect2(alowedDaysPerWeek);
                    //console.log(response);
                    var html = '<table class="table">';

                    html += '<tr>';
                    if(response.data.total_number_of_sessions >0){
                    html += '<td colspan="4"> <strong>Membership:</strong> ' + response.data.membership_label + ' &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Classes Allowed:</strong> ' + response.data.total_number_of_sessions + ' Sessions </td>';
                    }else{
                    html += '<td colspan="4"> <strong>Membership:</strong> ' + response.data.membership_label + ' &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Classes Allowed:</strong> ' + response.data.limit_days + ' per week / ' + response.data.classes_per_month + ' per month </td>';
                    }
                    html += '</tr>';
                    html += '<tbody class="time_table">';

                    if (response.data1.length) {
                        var active_records = 0;
                        $.each(response.data1, function (i, v) {

                            if ((moment(v.schedule_date).format('YYYY-MM-DD') > moment(new Date()).format('YYYY-MM-DD')) && v.status == 'Taken') {
                                active_records++;
                                var status_format = '<span class="label label-info">Coming</span>';
                            } else if ((moment(v.schedule_date).format('YYYY-MM-DD') <= moment(new Date()).format('YYYY-MM-DD')) && v.status == 'Taken') {
                                active_records++;
                                var status_format = '<span class="label label-success">' + v.status + '</span>';
                            } else if (v.status == 'Cancelled') {
                                var status_format = '<span class="label label-danger">' + v.status + '</span>';
                            } else if (v.status == 'cancelled_by_trainer') {
                                var status_format = '<span class="label label-warning">Cancelled By Trainer</span>';
                            }
                            html += '<tr>';
                            html += '<td> ' + v.start_time + ' - ' + v.end_time + '</td>';
                            html += '<td>' + v.days + '</td>';
                            html += '<td>' + moment(v.schedule_date).format('MMM DD, YYYY') + '</td>';
                            html += '<td>' + status_format + '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html += '<tr>';
                        html += '<td colspan="4" align="center"> There is no schedule for this member.</td>';
                        html += '</tr>';
                    }

                    html += '</tbody>';
                    html += '</table>';

                    $('#member_plan_details_div').html(html);
                    if (response.data1.length) {
                        if (active_records >= response.data1[0].classes_per_month) {
                            var notice = "<strong>Notice:</strong> This member is reached its monthly limit. Please cancel or delete coming schedules to add new schedules.";
                            $('#member_plan_details_div_notice').html(notice);
                            $('#member_plan_details_div_notice').fadeIn('slow');
                            $('#add_class_btn').prop('disabled', true);
                        }

                    } else {
                        $('#member_plan_details_div_notice').html('');
                        $('#member_plan_details_div_notice').fadeOut('slow');
                        $('#add_class_btn').prop('disabled', false);
                    }

                    reinitializeSelect2(response.data.limit_days);
                    
                    $(".sdate").datepicker({
                        language: "<?php echo 'en'; //$dtp_lang; ?>",
                        format: "<?php echo $this->Gym->get_js_dateformat($this->Gym->getSettings("date_format")); ?>",
                        forceParse: false,
                        //startDate: moment(response.data.start_date).format('MM-DD-YYYY'),
                        startDate: moment().format('MM-DD-YYYY'),
                        endDate: moment(response.data.end_date).format('MM-DD-YYYY')
                    });
                    
                    var schedule_iteration = $("input[name='schedule_iteration']:checked").val();

                    if(schedule_iteration == 'recurring'){
                        $('#end_date').val(response.data.end_date);
                    }
                    
                    
                } else if (response.status == 'error') {
                    alert(response.msg);
                }
                return false;
            },
            error: function (jqXHR, exception) {
                return false;
            }
        });
    }

    function fetch_pt_opt_customers(schedule_type) {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/GymAjax/fetchPtOptCustomers'; ?>",
            data: {schedule_type: schedule_type},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#member_for").empty().append(response.data);
                } else if (response.status == 'error') {
                    $("#member_for").empty().append("<option value=''>Select Member</option>");
                    alert(response.msg);
                }
                return false;
            },
            error: function (jqXHR, exception) {
                return false;
            }
        });
    }

    $(document).ready(function () {
        $("input[name='schedule_type']").on('change', function () {
            //$('#schedule_type').val(this.value);
            fetch_pt_opt_customers(this.value);
        });

        $('.day_list').select2({
            includeSelectAllOption: true,
            maximumSelectionLength: 1
        });

        $('#member_for').click(function () {
            if (!$("input[name='schedule_type']").is(":checked")) {
                alert("Please Select Schedule Type First.");
                return;
            }
        });

        $('.sdate').focus(function () {
            if ($("#member_for").val() == '') {
                alert("Please Select Member First.");
                return;
            }
        });

    });

    function reinitializeSelect2(alowedDaysPerWeek) {
        $('.day_list').select2({
            includeSelectAllOption: true,
            maximumSelectionLength: alowedDaysPerWeek
        });
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
                    "visits": zonesDurationArr[0],
                    "color": "#575757",
                    "percent": ((zonesDurationArr[0] * 100) / duration).toFixed(2) + "%"
                },
                {
                    "country": "ZONE 2",
                    "visits": zonesDurationArr[1],
                    "color": "#2199BE",
                    "percent": ((zonesDurationArr[1] * 100) / duration).toFixed(2) + "%"
                }, {
                    "country": "ZONE 3",
                    "visits": zonesDurationArr[2],
                    "color": "#3CC24F",
                    "percent": ((zonesDurationArr[2] * 100) / duration).toFixed(2) + "%"
                }, {
                    "country": "ZONE 4",
                    "visits": zonesDurationArr[3],
                    "color": "#F7A80A",
                    "percent": ((zonesDurationArr[3] * 100) / duration).toFixed(2) + "%"
                }, {
                    "country": "ZONE 5",
                    "visits": zonesDurationArr[4],
                    "color": "#EA4221",
                    "percent": ((zonesDurationArr[4] * 100) / duration).toFixed(2) + "%"
                }],
            "valueAxes": [{
                    "position": "left",
                    "axisAlpha": zonesDurationArr[0],
                    "gridAlpha": 0
                }],
            "graphs": [{
                    "balloonText": "[[category]]: <b>[[percent]]</b>", //
                    "colorField": "color",
                    "fillAlphas": 0.85,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "topRadius": 1,
                    "valueField": "visits"
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

    function getDayHourMinSec(start, end) {
        var different = end - start;
        var secondsInMilli = 1000;
        var minutesInMilli = secondsInMilli * 60;
        var hoursInMilli = minutesInMilli * 60;
        var daysInMilli = hoursInMilli * 24;
        var returnStr = '';

        // long monthInMilli = daysInMilli * 31;

        // final long elapsedMonths = different / monthInMilli;
        // different = different % monthInMilli;


        //final long elapsedDays = different / daysInMilli;
        // different = different % daysInMilli;

        var elapsedHours = different / hoursInMilli;
        returnStr += getInTwoDigit(elapsedHours.toFixed());

        different = different % hoursInMilli;

        var elapsedMinutes = different / minutesInMilli;
        returnStr += ':' + getInTwoDigit(elapsedMinutes.toFixed());

        different = different % minutesInMilli;

        var elapsedSeconds = different / secondsInMilli;
        returnStr += ':' + getInTwoDigit(elapsedSeconds.toFixed());

        return returnStr;
    }

    function getInTwoDigit(n) {
        return n > 9 ? "" + n : "0" + n;
    }
    
    $('#createPtOptModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $('#member_plan_details_div_notice').hide();
        $('#member_plan_details_div').html('');
    });
    
</script>

<script>
    $('#label-toggle-switch').on('click', function(e, data) {
        $('.label-toggle-switch').bootstrapSwitch('toggleState');
    });
    $('.label-toggle-switch').on('switch-change', function (e, data) {
        alert(data.value);
    });
    
    $("[name='trainer_availability']").bootstrapSwitch({
        size : 'large',
        onColor : 'success',
        offColor : 'warning',
        handleWidth : 100,
        onText : "Available",
        offText : "Unavailable",
        
        onSwitchChange : function(event, state){
          /*  console.log('event: ',event);
            console.log('state: ',state);
            
            if(state){
                $('#at_work_div').fadeIn('slow');
            }else{
                $('#at_work_div').fadeOut('slow');
            }*/
        }
    });
    
    function changeSchedule(id){
        $('#changeTimingDiv').fadeIn('slow');
    }
    function cancelScheduleRedirect(url){
        if(confirm("Are you sure?")){
            window.location.href = url;
        }
    }
</script>
