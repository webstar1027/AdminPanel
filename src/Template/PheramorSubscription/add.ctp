<?php
$bradcrumb = ($edit) ? 'Edit Subscription' : 'Add Subscription';
$this->Html->addCrumb('List Subscription', array('controller' => 'PheramorSubscription', 'action' => 'subscriptionList'));
$this->Html->addCrumb($bradcrumb);
?>


<div class="col-md-12">
    <div class="portlet light portlet-fit portlet-form bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase"><?php echo $title; ?></span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Pheramor->createurl("PheramorSubscription", "subscriptionList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Subscription List"); ?></a>

                </div>
            </div>

        </div>
        <div class="portlet-body">
            <?php
            echo $this->Form->create($membership, ["type" => "file", "class" => "validateForm form-horizontal", "novalidate" => "novalidate"]);
            ?>

            <div class="form-body">
<div class="form-group form-md-line-input">
                    <label class="col-md-3 control-label" for="form_control_1">Subscription Name
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control validate[required]" value="<?php echo ($edit) ? $membership_data['subscription_title'] : ""; ?>" placeholder="Enter Subscription Name"  name="subscription_title">
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Enter Subscription Name...</span>
                    </div>
                </div>


                <div class="form-group form-md-line-input">
                    <label class="col-md-3 control-label" for="form_control_1">Subscription Category
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-control">
                                <?php
                                echo $this->Form->select("subscription_cat_id", $categories, ["default" => ($edit) ? $membership_data["subscription_cat_id"] : "", "empty" => __("Select Category"), "class" => "form-control validate[required] cat_list", "onchange" => "filterLimited(this.value)", ($edit) ? "disabled":""]);
                                ?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select Subscription Category...</span>
                            </div>

                            <span class="input-group-btn btn-right">
                                <i class="fa fa-angle-down"></i>
                                <?php echo $this->Form->button(__("Add Category"), ["class" => "form-control add_category btn green btn-flat", "type" => "button", "data-url" => $this->Gym->createurl("PheramorAjax", "addCategory")]); ?>
                            </span>
                        </div>
                    </div>
                </div>

               
                <div id="membership_limit_recurring">
                   <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Subscription Type
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-3">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox2_10"<?php echo (($edit && $membership_data['subscription_type'] == "months") ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="months" name="subscription_type" class="md-radiobtn">
                                    <label for="checkbox2_10">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Monthly 
                                    </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox2_11" <?php echo (($edit && $membership_data['subscription_type'] == "days") ? "checked" : ""); ?> value="days" name="subscription_type" class="md-radiobtn">
                                    <label for="checkbox2_11">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>Days </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div id="membership_period_div">
                 <div class="form-group form-md-line-input">
                    <label class="col-md-3 control-label" for="form_control_1">Subscription Length
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control validate[required,custom[number]]" value="<?php echo ($edit) ? $membership_data["subscription_length"] : ""; ?>" name="subscription_length" id="subscription_length" placeholder="Enter Number Of Months">
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Enter Subscription Length...</span>
                    </div>
                </div>
               <div class="form-group form-md-line-input">
                <label class="col-md-3 control-label" for="form_control_1">Subscription Amount ($)
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-9">
                    <input type="text" class="form-control validate[required,custom[number]]" value="<?php echo ($edit) ? $membership_data["subscription_amount"] : ""; ?>" name="subscription_amount" placeholder="Enter Amount">
                    <div class="form-control-focus"> </div>
                    <span class="help-block">Enter Amount...</span>
                </div>
            </div>
            </div>
                <div id="membership_period_div_sub">
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Amount ($)
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <!-- 1st Week -->
                            <?Php 
                               if($edit){
                                  $subscription_arr = json_decode($membership_data["subscription_amount"]);
                               }
                            ?>
                           <div class="col-md-1" style="padding:0px">
                                <input type="text" class="form-control validate[required,custom[number]]" value="<?php echo ($edit) ? $subscription_arr[0] : ""; ?>" name="subscription_amount_week[]" placeholder="0.00">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter Amount...</span>
                            </div>
                            <div class="col-md-2" style="padding-left:0px">
                                <button class="btn purple" type="button">1<sup>st</sup> &nbsp;Week</button>
                            </div>
                            <!-- 2nd Week -->
                            <div class="col-md-1" style="padding:0px">
                                <input type="text" class="form-control validate[required,custom[number]]" value="<?php echo ($edit) ? $subscription_arr[1] : ""; ?>" name="subscription_amount_week[]" placeholder="0.00">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter Amount...</span>
                            </div>
                            <div class="col-md-2" style="padding-left:0px">
                                <button class="btn yellow" type="button">2<sup>nd</sup> &nbsp;Week</button>
                            </div>
                            <!-- 3rd Week -->
                            <div class="col-md-1" style="padding:0px">
                                <input type="text" class="form-control validate[required,custom[number]]" value="<?php echo ($edit) ? $subscription_arr[2] : ""; ?>" name="subscription_amount_week[]" placeholder="0.00">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter Amount...</span>
                            </div>
                            <div class="col-md-2" style="padding-left:0px">
                                <button class="btn red" type="button">3<sup>rd</sup> &nbsp;Week</button>
                            </div>
                            <!-- 4th Week -->
                            <div class="col-md-1" style="padding:0px">
                                <input type="text" class="form-control validate[required,custom[number]]" value="<?php echo ($edit) ? $subscription_arr[3] : ""; ?>" name="subscription_amount_week[]" placeholder="0.00">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter Amount...</span>
                            </div>
                            <div class="col-md-2" style="padding-left:0px">
                                <button class="btn dark" type="button">4<sup>th</sup> &nbsp;Week</button>
                            </div>
                        </div>
                    </div>   

                </div>  
                
            </div>
            

            <div class="col-md-12">
                &nbsp;


            </div>
            <div class="form-group form-md-line-input">
                <label class="col-md-3 control-label" for="form_control_1">Subscription Description
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-9 form">
                    <?php echo $this->Form->textarea("subscription_desc", ["rows" => "15", "class" => "wysihtml5 form-control", "value" => ($edit) ? $membership_data['subscription_desc'] : ""]); ?>

                    <div class="form-control-focus"> </div>
                    <span class="help-block">Enter Description...</span>
                </div>
            </div>
                  
            <div class="form-group form-md-line-input">
                 <label class="col-md-3 control-label" for="form_control_1">Subscription Status
                     <span class="required" aria-required="true">*</span>
                 </label>
                 <div class="col-md-3">
                     <div class="md-radio-inline">
                         <div class="md-radio">
                             <input type="radio" id="checkbox2_101"<?php echo (($edit && $membership_data['subscription_status'] == "1") ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="subscription_status" class="md-radiobtn">
                             <label for="checkbox2_101">
                                 <span></span>
                                 <span class="check"></span>
                                 <span class="box"></span> Active 
                             </label>
                         </div>
                         <div class="md-radio">
                             <input type="radio" id="checkbox2_111" <?php echo (($edit && $membership_data['subscription_status'] == "0") ? "checked" : ""); ?> value="0" name="subscription_status" class="md-radiobtn">
                             <label for="checkbox2_111">
                                 <span></span>
                                 <span class="check"></span>
                                 <span class="box"></span>Inactive </label>
                         </div>

                     </div>
                 </div>
             </div>
               
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn green" name="add_subscription">Submit</button>
                    <button type="reset" class="btn default">Reset</button>
                </div>
            </div>
            <p>&nbsp;</p>
        </div>
       
        </form>
        <!-- END FORM-->
    </div>
</div>


</div>

<script>

    var membership_period_type = $('input[name="membership_period_type"]:checked').val();

   $('input[name="subscription_type"]').on('change', function () {
        if ($(this).val() == 'days') {
           // $('#membership_period_div').fadeOut('slow');
           $('#subscription_length').attr("placeholder", "Enter Number Of Days");
           } else {
            $('#subscription_length').attr("placeholder", "Enter Number Of Months");
          }
    });
    
    /// Product Category
    
    function filterLimited(value) {
        if (value!='') {
             
            var url = "<?php echo $this->request->base . '/PheramorAjax/getCategoryType'; ?>"; // the script where you handle the form input.
                   $.ajax({
                    type: "POST",
                    url: url,
                    data: {id:value}, // serializes the form's elements.
                    dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                        if(data.category_type=='product'){
                              $('#membership_limit_recurring').hide();
                              $("#membership_period_div").hide();
                              $("#membership_period_div_sub").show();
                        }else{
                            $('#membership_limit_recurring').show();
                             $("#membership_period_div").show();
                             $("#membership_period_div_sub").hide();
                        }
                         
                    },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });

          
            
            
            
           // $('#membership_period_div').fadeOut('slow');
         
           } else {
           $('#membership_limit_recurring').show();
           $("#membership_period_div").show();
            $("#membership_period_div_sub").hide();
          }
    }
    
    
    $(document).ready(function () {
        var membership_period_type = "<?php echo @$membership_data['subscription_type']; ?>";
        if (membership_period_type == 'days') {
            $('#subscription_length').attr("placeholder", "Enter Number Of Days");
          
        } else {
           $('#subscription_length').attr("placeholder", "Enter Number Of Months");
         
        }
        $("#loader-content-section").hide();
        $(".loader").show();
         var selected_cat_id = "<?php echo @$membership_data['subscription_cat_id']; ?>";
         if (selected_cat_id!='') {
           // $('#membership_period_div').fadeOut('slow');
           var url = "<?php echo $this->request->base . '/PheramorAjax/getCategoryType'; ?>"; // the script where you handle the form input.
                   $.ajax({
                    type: "POST",
                    url: url,
                    data: {id:selected_cat_id}, // serializes the form's elements.
                    dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                        if(data.category_type=='product'){
                              $('#membership_limit_recurring').hide();
                              $("#membership_period_div").hide();
                              $("#membership_period_div_sub").show();
                        }else{
                            $('#membership_limit_recurring').show();
                             $("#membership_period_div").show();
                             $("#membership_period_div_sub").hide();
                        }
                         
                    },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });
           } else {
            $('#membership_limit_recurring').show();
             $("#membership_period_div").show();
             $("#membership_period_div_sub").hide();
          }
    });
    

   
</script>
