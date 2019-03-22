<?php
//echo '<pre>';print_r($staff);
$bradcrumb = ($edit) ? 'Edit Notification Message' : 'Add Notification Message';
$this->Html->addCrumb('List Notification Message', array('controller' => 'PheramorNotification', 'action' => 'index'));
$this->Html->addCrumb($bradcrumb);
$session = $this->request->session()->read("User");

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

<div class="col-md-12">	
    <div class="portlet light portlet-fit portlet-form bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase"><?php echo $title; ?></span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Gym->createurl("PheramorNotification", "index"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Notification Message List"); ?></a>

                </div>
            </div>

        </div>
        <div class="portlet-body">
            <div class="box-body">
                <?php
                     echo $this->Form->create("adduser", ["type" => "file", "class" => "validateForm form-horizontal", "role" => "form"]);
                ?>
                <input type="hidden" id="itsId" value="<?php echo ($edit) ? $notify_data['id'] : ''; ?>">
                 
                <div class="form-body">

                    
            <div class="form-body">
                <div class="form-group form-md-line-input">
                    <label class="col-md-3 control-label" for="form_control_1">Notification Type
                        <span class="required" aria-required="true">*</span>
                    </label>
                     <div class="col-md-9">
                         <div class="col-md-9">
                       <select class="form-control validate[required]" name="notification_type">
                           <option value="">Select Notification Type</option>
                            <option value="Event"  <?php  if(($edit) && @$notify_data['notification_type']=='Event'){ echo "selected";} ?>>Event</option>
                            <option value="Cafe"  <?php  if(($edit) && @$notify_data['notification_type']=='Cafe'){ echo "selected";} ?>>Cafe</option>
                            <option value="Registration"  <?php  if(($edit) && @$notify_data['notification_type']=='Registration'){ echo "selected";} ?>>Registration</option>
                            <option value="Credits"  <?php  if(($edit) && @$notify_data['notification_type']=='Credits'){ echo "selected";} ?>>Credits</option>
                            <option value="Custom"  <?php  if(($edit) && @$notify_data['notification_type']=='Custom'){ echo "selected";} ?>>Custom</option>
                           </select>
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Select Notification Type...</span>
                    </div>
                  </div>
                </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Notification Name
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required,custom[onlyLetterSp]]" value="<?php echo(($edit) ? $notify_data['notification_title'] : ''); ?>" placeholder="Enter notification title"  name="notification_title" id="notification_title">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Notification title....</span>
                        </div> </div>
                     </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Notification Label
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                             <input type="text" class="form-control validate[required]" readonly="readonly" value="<?php echo (($edit) ? $notify_data['notification_label'] : ''); ?>" placeholder="Enter notification label"  name="notification_label" id="notification_label">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Notification label....</span>
                        </div></div>
                    </div>
                    
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Message
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9 ">
                             <div class="col-md-9 ">
                            <?php echo $this->Form->textarea("notification_message", ["rows" => "15", "class" => "validate[required] form-control", "placeholder"=>"Enter notification message", "value" => ($edit) ? $notify_data['notification_message'] : ""]); ?>

                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Message...</span>
                        </div>
                             </div>
                    </div>
                     <div class="form-group form-md-line-input">
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
                                    <input type="radio" id="checkbox111_9" <?php echo (($edit && $notify_data['status'] == '0') ? "checked" : "") ; ?> value="0" name="status" class="md-radiobtn">
                                    <label for="checkbox111_9">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Inactive </label>
                                </div>

                            </div>
                        </div> </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <div class="col-md-offset-3 col-md-6">
                            <input type="submit" value="<?php echo __("Save"); ?>" name="add_member" class="btn btn-flat btn-primary">
                            <button type="reset" class="btn default">Reset</button>
                        </div>   
                    </div>

                </div>

                <?php echo $this->Form->end(); ?>
               
            </div>
        </div>

    </div>
</div>
    <?php if($edit){}else{ ?>
    <script>
    
    $("#notification_title").keyup(function(){
        var Text = $(this).val();
        Text = Text.toLowerCase();
        var regExp = /\s+/g;
        Text = Text.replace(regExp,'-');
        $("#notification_label").val(Text);        
     });
    </script>
    <?php } ?>
