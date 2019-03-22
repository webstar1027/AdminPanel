<?php
//echo '<pre>';print_r($staff);
$bradcrumb = ($edit) ? 'Edit Notification' : 'Send Notification';
$this->Html->addCrumb('List Notification', array('controller' => 'PheramorNotification', 'action' => 'index'));
$this->Html->addCrumb($bradcrumb);
$session = $this->request->session()->read("User");
echo $this->Html->css('assets/global/plugins/jquery-multi-select/css/multi-select.css');
echo $this->Html->script('assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js');
echo $this->Html->script('assets/pages/scripts/components-multi-select.min.js');
echo $this->Html->script('assets/pages/scripts/components-date-time-pickers.min.js');

//
echo $this->Html->css('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.css');
echo $this->Html->css('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.skinFlat.css');
echo $this->Html->script('assets/global/plugins/ion.rangeslider/js/ion.rangeSlider.min.js');
echo $this->Html->script('assets/pages/scripts/components-ion-sliders.min.js');


?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#start_date").datepicker({
            todayBtn: 1,
            autoclose: true,
            forceParse: false

        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#end_date').datepicker('setStartDate', minDate);
        });

    });
</script>
<script>
   var ComponentsIonSliders = function() {
     var handleAdvancedDemos = function() {
         var $range = $("#agerange");
          $range.ionRangeSlider({
            type: "double",
            min: 18,
            max: 70,
            from: 18,
            to: 70,
            from_fixed: false
        });

        $range.on("change", function () {
        var $this = $(this),
         value = $this.prop("value").split(";");
         var txtval=value[0] + "," + value[1];
         //console.log(txtval);
         $("#age_range").val(txtval);
         // console.log(value[0] + " - " + value[1]);
        });
    }

    return {
        //main function to initiate the module
        init: function() {
            handleAdvancedDemos();
        }

    };

}();

jQuery(document).ready(function() {
    ComponentsIonSliders.init();
});


///
 $(document).ready(function(){
    $("#generic_kit").change(function () {
    // alert();
        var gkit = this.value;
        if(gkit=='1'){
             $('#kit_id').removeAttr("disabled");
        }else{
            $('#kit_id').attr("disabled", "disabled");

        }
       
    });

});
</script>

<div class="col-md-12">	
    <div class="portlet light portlet-fit portlet-form bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase"><?php echo $title; ?></span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Gym->createurl("PheramorNotification", "index"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Notification List"); ?></a>

                </div>
            </div>

        </div>
        <!-- Custom Search box here --->
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-book-open font-dark"></i>
                        <span class="caption-subject bold uppercase"> Search Member</span>

                    </div>
                    <div class="actions">
                        <div class="tools"> </div>
                    </div>
                </div>

                <div class="portlet-body">

                    <div class="table-toolba">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" id="search-filter-from" class="validateForm">  


                                    <label class="control-label col-md-2">City</label>
                                    <div class="form-group col-md-2">
                                        <input type="text" name="city" id="city" class="form-control" value="">		
                                    </div>
                                    <label class="control-label col-md-2">Gender</label>
                                    <div class="form-group col-md-2">
                                        <select name="gender" class="form-control">
                                            <option value="" selected="">--</option>
                                            <option value="1">Male</option>
                                            <option value="0">Female</option>
                                            
                                        </select>		
                                    </div>
                                    <label class="control-label col-md-2">Bone Marrow Donor </label>
                                    <div class="form-group col-md-2">
                                        <select name="bone_marrow_donor" class="form-control">
                                            <option value="" selected="">--</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                           </select>

                                    </div>
                                    <label class="control-label col-md-2">Genetic kit Connected </label>
                                    <div class="form-group col-md-2">
                                        <select name="generic_kit" class="form-control" id="generic_kit">
                                            <option value="" selected="">--</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>

                                    </div>
                                    <label class="control-label col-md-2">Sexual Orientation </label>
                                    <div class="form-group col-md-2">
                                         <?php //echo @$this->Form->select("orientation", $orienation, ["default" => $profile_data['orientation'], "id" => "orienation", "empty" => __("--"), "class" => "form-control "]); ?>
                                       <select name="orientation" class="form-control">
                                            <option value="" selected="">--</option>
                                            <option value="1,0">Male</option>
                                            <option value="0,1">Female</option>
                                            <option value="1,1">Both</option>
                                        </select>

                                    </div>
                                    <label class="control-label col-md-2">Email</label>
                                    <div class="form-group col-md-2">
                                        <input type="text" name="email" id="email" class="form-control" value="">		
                                    </div>
                                    <label class="control-label col-md-2">Genetic kit ID </label>
                                    <div class="form-group col-md-2">
                                        <input type="text" name="kit_id" id="kit_id" class="form-control" disabled value="">		
                                    </div>
                                    <label class="control-label col-md-2">Age </label>
                                    <div class="form-group col-md-6">
                                         <input id="agerange" class="agerange" type="text"  />
                                         <input type="hidden" name="age" id="age_range" value="18,70">
<!--                                        <select name="age" class="form-control">
                                            <option value="" selected="">--</option>
                                            <option value="18-24">18-24</option>
                                            <option value="25-34">25-34</option>
                                            <option value="35-44">35-44</option>
                                            <option value="45-54">45-54</option>
                                            <option value="55-64">55-64</option>
                                            <option value="65-100">65+</option>
                                        </select>-->

                                    </div>
                                    <div class="form-group col-md-3" style="font-weight:bold"> Total Results : <span id="tmcount"><?php echo count($data);?></span></div>
                                    <div class="form-group col-md-offset-6 col-md-3">
                                        <input type="submit" name="member_report" value="Search Filter" class="btn btn-flat btn-success">
                                        <input type="button" name="member_report" value="Reset Filter" id="reset-filter" class="btn btn-flat btn-danger">
                                    </div> 


                                </form>

                            </div>



                        </div>


                    </div>

                </div>
            </div>
        </div>
        <!-- End Here -->

        <div class="portlet-body">
            <div class="box-body">








                <div id='noti_loader_div'  style="display:none;">
                    <img src='<?php echo $this->request->webroot . 'img/noti_loader1.gif'; ?>'>

                </div>

                <div id="noti_loader">
                    <?php
                    echo $this->Form->create("adduser", ["type" => "file", "class" => "validateForm form-horizontal", "role" => "form", "id" => "send_notification_frm"]);
                    ?>

                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Title
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control validate[required,custom[onlyLetterSp]]" value="" placeholder="Enter  title"  name="title" id="title">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">Enter title....</span>
                                </div> </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Member</label>
                                <div class="col-md-9">
                                    <select multiple="multiple" class="multi-select validate[required]" id="my_multi_select2" name="my_multi_select1[]">
                                        <optgroup label="Select All" id="ashok">
                                            <?php
                                            if (!empty($data)) {
                                                foreach ($data as $dataval) { //print_r($dataval);
                                                    ?>
                                                    <option value="<?php echo $dataval->id; ?>"><?php echo $dataval['pheramor_user_profile'][0]['first_name'] . " " . $dataval['pheramor_user_profile'][0]['last_name'] . " (" . $dataval['email'] . ")"; ?></option>
                                                <?php }
                                            }
                                            ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                        </div>



                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Message
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9 ">
                                <div class="col-md-9 ">
<?php echo $this->Form->textarea("notification_message", ["rows" => "15", "class" => "validate[required] form-control", "placeholder" => "Enter notification message", "value" => "", "id" => "notification_message"]); ?>

                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">Enter Message...</span>
                                </div>
                            </div>
                        </div>
                        <!--<div class="form-group form-md-line-input">
                           <label class="col-md-3 control-label" for="form_control_1">Status
                               <span class="required" aria-required="true"></span>
                           </label>
                           <div class="col-md-6">
                            <div class="col-md-12">
                               <div class="md-radio-inline">
                                   <div class="md-radio">
                                       <input type="radio" id="checkbox111_8" <?php echo (($edit && $notify_data['status'] == '1') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="status" class="md-radiobtn">
                                       <label for="checkbox111_8">
                                           <span></span>
                                           <span class="check"></span>
                                           <span class="box"></span> Active </label>
                                   </div>
                                   <div class="md-radio">
                                       <input type="radio" id="checkbox111_9" <?php echo (($edit && $notify_data['status'] == '0') ? "checked" : ""); ?> value="0" name="status" class="md-radiobtn">
                                       <label for="checkbox111_9">
                                           <span></span>
                                           <span class="check"></span>
                                           <span class="box"></span> Inactive </label>
                                   </div>
   
                               </div>
                           </div> </div>
                       </div>-->

                        <div class="form-group form-md-line-input">
                            <div class="col-md-offset-3 col-md-6">
                                <input type="submit" value="<?php echo __("Send"); ?>" name="add_member" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>
                        <div class="form-group form-md-line-input">
                            <div class="col-md-offset-3 col-md-6" id="notification_save_status"></div>

                        </div>

                    </div>

<?php echo $this->Form->end(); ?>
                </div>
                <!-- Custom Notification listing here -->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> Custom Notification Lists </span>

                        </div>
                        <div class="actions">
                            <div class="tools"> </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <?php
                        //echo $date=date('Y-m-d');
                        // echo date('Y-m-d',(strtotime ( '-29 days' , strtotime ( $date) ) ));
                        ?>
                        <div class="form-group ">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-4">
                                <div id="reportrange" class="btn default">
                                    <i class="fa fa-calendar"></i> &nbsp;
                                    <span> </span>
                                    <b class="fa fa-angle-down"></b>
                                </div>
                            </div>
                        </div>
                        <table id="employee-grid"  cellpadding="0" cellspacing="0" border="0" class="employee-grid mydataTable table table-striped table-bordered table-hover table-checkable order-column" width="100%">
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Device</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>


                    </div>
                </div>

                <!-- End Here -->




            </div>


        </div>







    </div>
</div>
<style>
    div#ms-my_multi_select2 {
        width: 90%;
    }
</style> 
<script type="text/javascript" language="javascript" >
    $(document).ready(function () {


        var dataTable = $('.employee-grid').DataTable({
            "processing": true,
            "serverSide": true,
            "bRetrieve": true,
            // "columnDefs": [
            // { "orderable": false, targets: [0,4] }
            //  ],
            "ajax": {
                url: "<?php echo $this->request->base . '/PheramorAjax/getCustonNotificationData'; ?>", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $(".employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");

                }
            }
        });



    });
</script>
<script>


    $(document).ready(function () {
        

       // $('#my_multi_select2').multiselect();
       // $('#my_multi_select2').multiSelect('addOption', { value: 'test', text: 'test', nested: 'Select All' });
        
        
        /// Ajax filter member listing
        $("#reset-filter").click(function(e){
            $('#search-filter-from')[0].reset();
            $('#kit_id').attr("disabled", "disabled");
             var slider = $("#agerange").data("ionRangeSlider");

            // Fire public method
            slider.reset();
           // $("#agerange").ionRangeSlider();
            $('#search-filter-from').trigger('submit');
           // $('#my_multi_select2').multiSelect('refresh');
        });         

        $("#search-filter-from").submit(function(e){
            
             var count=0;
             var url = "<?php echo $this->request->base . '/PheramorAjax/filterMemberNotification'; ?>";
             $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#search-filter-from").serialize(), // serializes the form's elements.
                    dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                       // console.log(data.status);
                        $('#my_multi_select2').empty().multiSelect('refresh');
                        var k=1;
                        $.each(data, function(key, value) {
                            if(k==1){
                                  $("#my_multi_select2").append('<optgroup label="Select All"><option value="'+key+'">'+value+'</option></optgroup>');
                              }else{
                                  $('#my_multi_select2').multiSelect('addOption', { value: key, text: value, nested: 'Select All' });    
                              }
                             k++;
                             count++;
                        });
                       
                       // $('#my_multi_select2').multiSelect('refresh');
                        $("#tmcount").html(count);
                       $('#my_multi_select2').multiSelect('refresh');
                      
                       },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });

               
            
             e.preventDefault(); 
            
        });
        
        

        var otable = $('#employee-grid').DataTable();

        $('#reportrange span').bind('DOMSubtreeModified', function (e) {
            var endDate = $("#reportrange").data('daterangepicker').endDate.format('YYYY-MM-DD');
            var startDate = $("#reportrange").data('daterangepicker').startDate.format('YYYY-MM-DD');
            otable.columns(5).search(startDate + '@@' + endDate).draw();
            // otable.draw();
        });




        /// end Notification by ajax


        $("#send_notification_frm").submit(function (e) {

            var url = "<?php echo $this->request->base . '/PheramorAjax/sendCustomNotification'; ?>"; // the script where you handle the form input.
            // console.log($("#my_multi_select2").val());

            if ($("#title").val() == '' || $("#notification_message").val() == '' || $("#my_multi_select2").val() == null) {
                return false;
            }

            $.ajax({
                type: "POST",
                url: url,
                data: $("#send_notification_frm").serialize(), // serializes the form's elements.
                dataType: "JSON",
                beforeSend: function () {
                    $("#noti_loader_div").css('display', 'block');
                    $("#noti_loader").css('display', 'none');
                },
                success: function (data)
                {
                    HTMLMSG = '<div class="alert alert-success"><strong>Success!</strong> Notification Message Sent Successfully..</div>';
                    $("#noti_loader_div").css('display', 'none');
                    $("#noti_loader").css('display', 'block');
                    // $('#send_notification_frm')[0].reset();
                    $("#notification_save_status").html(HTMLMSG); // show response from the php script.
                    $("#notification_save_status").show().delay(5000).fadeOut();
                    $("#title").val('');
                    $("#notification_message").val('');
                    otable.draw();

                },
                error: function (jqXHR, exception) {
                    return false;
                }
            });



            e.preventDefault(); // avoid to execute the actual submit of the form.
        });

    });

</script>



<style>
    .daterangepicker_start_input, .daterangepicker_end_input {
        display: none;
    }
    #noti_loader_div {
        text-align: center;
        padding: 15%;
    }
</style>