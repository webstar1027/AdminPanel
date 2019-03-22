<?php
//echo '<pre>';print_r($staff);
$bradcrumb = ($edit) ? 'Edit Member' : 'Add Member';
$this->Html->addCrumb('List Members', array('controller' => 'GymMember', 'action' => 'memberList'));
$this->Html->addCrumb($bradcrumb);
//echo '<pre>';print_r($staff);die;
//echo $this->Html->css('bootstrap-multiselect');
//echo $this->Html->script('bootstrap-multiselect');
$session = $this->request->session()->read("User");
?>
<script type="text/javascript">
    $(document).ready(function () {
        //$('.group_list').multiselect({
            //includeSelectAllOption: true
        //});

        var box_height = $(".box").height();
        var box_height = box_height + 500;
        $(".content-wrapper").css("height", box_height + "px");

        //$('.assign_class').multiselect({
           // includeSelectAllOption: true
        //});

        //$(".datepick").datepicker({format: 'yyyy-mm-dd'});
        $(".mem_valid_from").datepicker().on("changeDate", function (ev) {
            // var ajaxurl = document.location + "/GymAjax/get_membership_end_date";
            var ajaxurl = $("#mem_date_check_path").val();
            var date = ev.target.value;
            var membership = $(".membership_id option:selected").val();
            if (membership != "")
            {
                var curr_data = {date: date, membership: membership};
                $(".valid_to").val("Calculating date..");
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: curr_data,
                    success: function (response)
                    {
                        // $(".valid_to").val($.datepicker.formatDate('<?php //echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
                        $(".valid_to").val(response);
                        // alert(response);
                        // console.log(response);
                    }
                });
            } else {
                $(".valid_to").val("Select Membership");
            }
        });
        $(".content-wrapper").css("height", "2600px");
    });

    function validate_multiselect()
    {
        var classes = $("#assign_class").val();
        if (classes == null)
        {
            alert("Please Select Class or Add class class first.");
            return false;
        } else {
            return true;
        }
    }

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
                    <a href="<?php echo $this->Gym->createurl("GymMember", "memberList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Members List"); ?></a>

                </div>
            </div>

        </div>
        <div class="portlet-body">
            <div class="box-body">
                <?php
                echo $this->Form->create("addgroup", ["type" => "file", "class" => "validateForm form-horizontal", "role" => "form"]);
                ?>
                <input type="hidden" id="itsId" value="<?php echo ($edit) ? $data['id'] : ''; ?>">

                <div class="form-body">

                    <h4><legend>Personal Information</legend></h4>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member ID
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo (($edit) ? $data['member_id'] : $member_id); ?>"  disabled="disabled" name="member_id">
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div></div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">First Name
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo(($edit) ? $data['first_name'] : ''); ?>" placeholder="Enter first name"  name="first_name">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter first name....</span>
                        </div> </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Middle Name
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo(($edit) ? $data['middle_name'] : ''); ?>" placeholder="Enter middle name"  name="middle_name">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter middle name....</span>
                        </div></div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Last Name
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo(($edit) ? $data['last_name'] : ''); ?>" placeholder="Enter last name"  name="last_name">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter last name....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Gender
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-6">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1_8" <?php echo (($edit && $data['gender'] == 'male') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="male" name="gender" class="check_limit md-radiobtn">
                                    <label for="checkbox1_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Male </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1_9" <?php echo (($edit && $data['gender'] == 'female') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : ""); ?> value="female" name="gender" class="check_limit md-radiobtn">
                                    <label for="checkbox1_9">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Female </label>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Date of birth
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control dob validate[required]" value="<?php echo (($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data['birth_date'])) : ''); ?>" placeholder="Enter birthday"  name="birth_date">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter birthday....</span>
                        </div></div>
                    </div>

                    <h4><legend>Contact Information</legend></h4>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Home Town Address
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['address'] : ''); ?>" placeholder="Enter address"  name="address">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter address....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">City
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['city'] : ''); ?>" placeholder="Enter city"  name="city">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter city....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">State
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['state'] : ''); ?>" placeholder="Enter state"  name="state">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter state....</span>
                        </div></div>
                    </div>

                    

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Zip code
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required, custom[integer]]" value="<?php echo (($edit) ? $data['zipcode'] : ''); ?>" placeholder="Enter zipcode"  name="zipcode">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter zipcode....</span>
                        </div></div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Mobile Number
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '>+<?php echo $this->Gym->getCountryCode($this->Gym->getSettings("country")); ?></span>
                                <input type="text" class="form-control  validate[required, custom[phone]]" value="<?php echo (($edit) ? $data['mobile'] : ''); ?>" placeholder="Enter mobile number"  name="mobile">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter mobile number....</span>
                            </div> </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Phone
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control validate[custom[phone]]" value="<?php echo (($edit) ? $data['phone'] : ''); ?>" placeholder="Enter phone"  name="phone">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter phone....</span>
                        </div></div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Email
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control validate[required,custom[email],ajax[isEmailUnique1]]" value="<?php echo (($edit) ? $data['email'] : ''); ?>" placeholder="Enter email"  name="email">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter email....</span>
                        </div></div>
                    </div>

                    <h4><legend>Login Information</legend></h4>

                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Username
                        </label>
                        <div class="col-md-9"> <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php //echo (($edit) ? $data['username'] : ''); ?>" placeholder="Enter username"  name="username" <?Php //echo (($edit) ? "readonly" : "") ?>>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter username....</span>
                        </div></div>
                    </div>-->

                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Password
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9"> <div class="col-md-9">
                            <input type="password" class="form-control validate[required]" value="<?php echo (($edit) ? $data['password'] : ''); ?>" placeholder="Enter password"  name="password" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter password....</span>
                        </div></div>
                    </div>-->

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Display Image
                        </label>
                        <div class="col-md-9"> <div class="col-md-9">
                            <?php
                             echo $this->Form->file("image", ["class" => "form-control"]);
                            $image = ($edit && !empty($data['image'])) ? $data['image'] : $this->request->webroot."upload/profile-placeholder.png";
                              echo "<div id='img-div'><br><img width='100' src='{$image}'>";
                            if(!empty($data['image']) && $data['image'] != $this->request->webroot.'upload/profile-placeholder.png'){
                                echo '<div style="padding:10px;"><span id="del-img" class="label label-success">Remove</span></div>';
                             }
                             echo '</div>';
                            ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div></div>
                    </div>


                    <h4><legend>More Information</legend></h4>

                    <?Php
                    if ($session['role_id'] == 2 || $session['role_id'] == 6 || $session['role_id'] == 7 || $session['role_id'] == 8) {
                        echo $this->Form->input("", ["label" => false, "name" => "associated_licensee", "type" => "hidden", "value" => $session['original_id']]);
                    } else {
                        ?>
                           <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Associated Location
                                      <span class="required" aria-required="true">*</span>
                                  </label>
                                  <div class="col-md-9">
                                      <div class="input-group col-md-12">
                                          <div class="input-group-control">
                                              <?php echo @$this->Form->select("associated_location", $locations, ["default" => $location, "id"=>"associated_location", "empty" => __("Select Location"), "class" => "form-control validate[required]", ($edit && $assigned_members) ? "disabled" : ""]); ?>
                                              <?php //echo ($edit && $assigned_members)?"<small><em> You can't change Location for this staff as this staff has been assigned ".$assigned_members." customers.</em></small>" : '';?>
                                              <div class="form-control-focus"> </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                       <!-- <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Select Location
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                               
                                <div class="col-sm-9">
                                   
                                        <?php echo @$this->Form->select("associated_licensee", $licensee, ["default" => $data['associated_licensee'], "empty" => ($session['role_id'] == 7 || $session['role_id'] == 8) ? __("Select Manager") : __("Select Licensee"), "class" => "form-control validate[required]", "id" => "associated_licensee", "onchange" => "fetchTrainersAndGroups(this.value)"]); ?>
                                        <div class="form-control-focus"> </div>
                                    </div>

                                    <span class="input-group-btn btn-right col-sm-3">
                                        <?php echo "<a href='{$this->request->base}/licensee/add-licensee/' class='btn btn-flat btn-primary custom-btn'>" . ( ($session['role_id'] == 7 || $session['role_id'] == 8) ? __("Add Manager") : __("Add Licensee") ). "</a>"; ?>
                                    </span>

                              
                            </div>
                        </div>-->

                    <?php } ?>
                   <?Php
                    if ($session['role_id'] == 6) {
                        echo $this->Form->input("", ["label" => false, "name" => "assign_staff_mem", "type" => "hidden", "value" => $session['original_id']]);
                    } else {
                        ?>
                   <!-- <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Assign Trainer
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                           <div class="col-md-9">
                               
                                    <?php echo @$this->Form->select("assign_staff_mem", $staff, ["default" => $data['assign_staff_mem'], "empty" => __("Select Trainer"), "class" => "form-control validate[required]", "id" => "assign_staff_mem"]); ?>

                                    <div class="form-control-focus"> </div>
                                  </div>

                                <span class="input-group-btn btn-right col-sm-3">
                                    <?php echo "<a href='{$this->request->base}/staff-members/add-staff/' class='btn btn-flat btn-primary custom-btn'>" . __("Add Trainer") . "</a>"; ?>
                                </span>

                          
                        </div>
                    </div>-->
                      <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Associated Trainer
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <div class="input-group-control">
                                                <?php echo @$this->Form->select("associated_trainer", $staff, ["default" => $data['assign_staff_mem'], "id"=>"associated_trainer", "empty" => __("Select Trainer"), "class" => "form-control validate[required]", ($edit && $assigned_members) ? "disabled" : ""]); ?>
                                                <?php //echo ($edit && $assigned_members)?"<small><em> You can't change Licensee for this staff as this staff has been assigned ".$assigned_members." customers.</em></small>" : '';?>
                                                <div class="form-control-focus"> </div>
                                            </div>

                                            <span class="input-group-btn btn-right">
                                                <?php echo "<a href='{$this->request->base}/staff-members/add-staff' class='btn btn-flat btn-primary'><i class='fa fa-plus'></i>" . __(" Add") . "</a>"; ?>
                                            </span>

                                        </div>
                                    </div>
                                </div>
                   
                    <?php } if($edit){ ?>
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Assign Group
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group-control">
                                <?php
                                echo @$this->Form->select("assign_group",$groups,["default"=>(explode(',',$data['assign_group'])),"multiple"=>"multiple","class"=>"form-control select2-multiple validate[required]","id"=>"assign_group"]);
                                if($session['role_id'] == 2 || $session['role_id'] == 1){
                               // echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='{$this->request->base}/GymGroup/addGroup/' class='btn btn-flat btn-success btn-default'>".__("Add Group")."</a>";
                                }?>
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Referred By
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                                <div class="input-group-control">
                                    <?php echo @$this->Form->select("referrer_by", $referrer_by, ["default" => $data['referrer_by'], "empty" => __("Select Referer"), "class" => "form-control", "id" => "referrer_by"]); ?>
                                    <div class="form-control-focus"> </div>
                                </div>
                                <span class="input-group-btn btn-right">
                                    <?php //echo "<a href='{$this->request->base}/StaffMembers/addStaff/' class='btn btn-flat btn-primary'>".__("Add Staff")."</a>"; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Inquiry Date
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control datepick" value="<?php echo (($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data['inquiry_date'])) : ''); ?>" placeholder="Enter inquiry date"  name="inquiry_date" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter inquiry date....</span>
                        </div>  </div>
                    </div>-->

                   <?php /*<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Trial End Date
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9"> <div class="col-md-9">
                            <input type="text" class="form-control datepick" value="<?php echo (($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data['trial_end_date'])) : ''); ?>" placeholder="Enter trial end date"  name="trial_end_date" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter trial end date....</span>
                        </div></div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Class Type
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-6">
                         <div class="col-md-12">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_8" <?php echo (($edit && $data['class_type'] == 'Group') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="Group" name="class_type" class="md-radiobtn">
                                    <label for="checkbox111_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Group </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_9" <?php echo (($edit && $data['class_type'] == 'Individual') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : ""); ?> value="Individual" name="class_type" class="md-radiobtn">
                                    <label for="checkbox111_9">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Individual </label>
                                </div>

                            </div>
                        </div> </div>
                    </div>

                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member Type
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-6">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox11_8" <?php //echo (($edit && $data['member_type'] == 'Member') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "")  ?> value="Member" name="member_type" class="membership_status_type md-radiobtn">
                                    <label for="checkbox11_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Member </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox11_9" <?php //echo (($edit && $data['member_type'] == 'Prospect') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "");  ?> value="Prospect" name="member_type" class="membership_status_type md-radiobtn">
                                    <label for="checkbox11_9">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Prospect </label>
                                </div>
                                
                                <div class="md-radio">
                                    <input type="radio" id="checkbox11_10" <?php //echo (($edit && $data['member_type'] == 'Alumni') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "");  ?> value="Alumni" name="member_type" class="membership_status_type md-radiobtn">
                                    <label for="checkbox11_10">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Alumni </label>
                                </div>

                            </div>
                        </div>
                      </div>-->

                    <?Php
                    if ($edit) {
                        ?>
                        <!--<div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Membership Status
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-6">
                                <div class="md-radio-inline">
                                    <div class="md-radio">
                                        <input type="radio" id="checkbox1111_8" <?php echo (($edit && $data['membership_status'] == 'Continue') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="Continue" name="membership_status" class="md-radiobtn">
                                        <label for="checkbox1111_8">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Continue </label>
                                    </div>
                                    <div class="md-radio">
                                        <input type="radio" id="checkbox1111_9" <?php echo (($edit && $data['membership_status'] == 'Expired') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : ""); ?> value="Expired" name="membership_status" class="md-radiobtn">
                                        <label for="checkbox1111_9">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Expired </label>
                                    </div>

                                    <div class="md-radio">
                                        <input type="radio" id="checkbox1111_10" <?php echo (($edit && $data['membership_status'] == 'Dropped') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : ""); ?> value="Dropped" name="membership_status" class="md-radiobtn">
                                        <label for="checkbox1111_10">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Dropped </label>
                                    </div>

                                </div>
                            </div>
                        </div>-->

                    <?php } ?>

                    <?php
                    /* if (@$data['member_type'] == 'Prospect' || @$data['member_type'] == 'Alumni') {
                      $styles = "style='display:none;'";
                      } else {
                      $styles = "style='display:block;'";
                      } */
                    /*?>
                    <div class="form-group form-md-line-input class-member" <?php //echo $styles; ?>>
                        <label class="col-md-3 control-label" for="form_control_1">Membership
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            
                                <div class="col-md-9">
                                    <?php echo @$this->Form->select("selected_membership", $membership, ["default" => $data['selected_membership'], "empty" => __("Select Membership"), "class" => "form-control validate[required] membership_id"]); ?>
                                    <div class="form-control-focus"> </div>
                                </div>

                                <span class="input-group-btn btn-right col-md-3">
                                    <?php echo "<a href='{$this->request->base}/Membership/add/' class='btn btn-flat btn-primary'>" . __("Add Membership") . "</a>"; ?>
                                </span>

                         
                        </div>
                    </div>

                    <div class="form-group form-md-line-input class-member" <?php //echo $styles;  ?>>
                        <label class="col-md-3 control-label" for="form_control_1">Membership Valid From
                            <span class="required" aria-required="true">*</span>
                        </label>
                          <div class="col-md-9">
                        <div class="col-md-5">
                            <div class="input-group-control">
                                <?php echo $this->Form->input("", ["label" => false, "name" => "membership_valid_from", "id" => "membership_valid_from", "class" => "form-control validate[required] mem_valid_from", "value" => (($edit && $data['membership_valid_from'] != "") ? date($this->Gym->getSettings("date_format"), strtotime($data['membership_valid_from'])) : '')]); ?>
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="col-md-1 no-padding text-center">
                            <div class="input-group-control">
                                To
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group-control">
                                <?php echo $this->Form->input("", ["label" => false, "name" => "membership_valid_to", "class" => "form-control validate[required] valid_to", "value" => (($edit && $data['membership_valid_to'] != "") ? date($this->Gym->getSettings("date_format"), strtotime($data['membership_valid_to'])) : ''), "readonly" => true]); ?>
                                <div class="form-control-focus"> </div>
                            </div>
                        </div> </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">First Payment Date
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control datepick" value="<?php echo (($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data['first_pay_date'])) : ''); ?>" placeholder="Enter first pay date"  name="first_pay_date" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter first pay date....</span>
                        </div>  </div>
                    </div><?Php */ ?>

                       <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member Status
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-6">
                         <div class="col-md-12">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_8" <?php echo (($edit && $data['activated'] == '1') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="activated" class="md-radiobtn">
                                    <label for="checkbox111_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Active </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_9" <?php echo (($edit && $data['activated'] == '0') ? "checked" : "") ; ?> value="0" name="activated" class="md-radiobtn">
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
                <input type="hidden" value="<?php echo $this->request->base; ?>/GymAjax/get_membership_end_date" id="mem_date_check_path">
                <input type="hidden" value="<?php echo $this->request->base; ?>/GymAjax/get_membership_classes" id="mem_class_url">
            </div>
        </div>

    </div>
</div>
<script>
    /*$(".membership_status_type").change(function(){
     if($(this).val() == "Prospect" || $(this).val() == "Alumni" )
     {
     $(".class-member").hide("SlideDown");
     $(".class-member input,.class-member select").attr("disabled", "disabled");				
     }else{
     $(".class-member").show("SlideUp");
     $(".class-member input,.class-member select").removeAttr("disabled");	
     $("#available_classes").attr("disabled", "disabled");
     }
     });
     if($(".membership_status_type:checked").val() == "Prospect" || $(".membership_status_type:checked").val() == "Alumni")
     { 
     $(".class-member").hide("SlideDown");
     $(".class-member input,.class-member select").attr("disabled", "disabled");		
     }
     */

    function fetchTrainersAndGroups(licensee) {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/GymAjax/fetchTrainersAndGroups'; ?>",
            data: {licensee: licensee},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    //$('#selectId option:gt(0)').remove();
                    $("#assign_staff_mem").empty().append(response.data);
                    $("#referrer_by").empty().append(response.data1);
                    $("#assign_group").empty().append(response.data2);
                    //console.log(response.data2);
                }
                return false;
            },
            error: function (jqXHR, exception) {
                return false;
            }
        });
    }
</script>

<script>
    $('#associated_location').change(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/GymAjax/fetchStaffByLocationId'; ?>",
            data: {loc: $(this).val()},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#associated_trainer").empty().append(response.data);
                    
                } else if (response.status == 'error') {
                    $("#associated_trainer").empty().append("<option value=''>Select Trainer</option>");
                    alert(response.msg);
                }
                return false;
            },
            error: function (jqXHR, exception) {
                return false;
            }
        });
    });
    
    $(document).ready(function(){
    
        var location = "<?php echo $location;?>"; 
        var role_id = "<?php echo $session['role_id'];?>"; 

        if( location && location != '' && (role_id == '7' || role_id == '1' || role_id == '8') ){
            $.ajax({
                type: "POST",
                url: "<?php echo $this->request->base . '/GymAjax/fetchStaffByLocationId'; ?>",
                data: {loc: location},
                dataType: "JSON",
                beforeSend: function () {
                },
                success: function (response) {
                  //  alert();
                    if (response.status == 'success') {
                        $("#associated_trainer").empty().append(response.data);
                        $('#associated_trainer').val("<?php echo @$data['assign_staff_mem'];//$associated_licensee;?>").prop('selected', true);
                    } else if (response.status == 'error') {
                        $("#associated_trainer").empty().append("<option value=''>Select Trainer</option>");
                        alert(response.msg);
                    }
                    return false;
                },
                error: function (jqXHR, exception) {
                    return false;
                }
            });
        }
        
        ////Image delete 
        
         $('#del-img').click(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/GymAjax/deleteImageByUserId'; ?>",
            data: {id: $("#itsId").val()},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#img-div").hide();
                    
                } else if (response.status == 'error') {
                   // $("#associated_trainer").empty().append("<option value=''>Select Trainer</option>");
                    alert(response.msg);
                }
                return false;
            },
            error: function (jqXHR, exception) {
                return false;
            }
        });
       });
       
       /// End here 
        
    });
    
    
    </script>
