<?php
$session = $this->request->session();
$this->Html->addCrumb('Profile');
$profile_img = $session->read("User.profile_img");
$profile_img = (!empty($profile_img)) ?  $profile_img : $this->request->base . "/upload/profile-placeholder.png";
echo $this->Html->css("gymprofile.css");
$profile_data=$data['pheramor_user_profile'][0];
//echo "<pre>";print_r($data); die;
//echo "<pre>";print_r($profile_data); die;
?>
<script>
    $(document).ready(function () {
        $(".hasDatepicker").datepicker({format: "yyyy-mm-dd"});
        //$(".content").css("height", "1400px");
    });
</script>

<section class="content no-padding">
    <div> 
       <div id="main-wrapper"> 
            <div class="row">
                <div class="col-md-3 user-profile" style="margin-top:25px;">
                    <div class="portlet-title" style="background:#fff;">
                            <div class="caption bold text-uppercase" style="color:#f86141; padding:20px 20px 20px">
                               <!-- <i class="fa fa-pencil-square-o" aria-hidden="true"></i>--><?php echo __("Edit Profile"); ?> </div>
                            
                         <div class="col-md-12"  style="background:#fff;">
                     <div class="profile-image-container text-center">
                        <img src="<?php echo $profile_img; ?>" height="150px" width="150px" class="img-circle">
                    </div>
                  <!--  <h3 class="text-center"><?php echo $session->read("User.display_name"); ?></h3>		-->		
                         <div class="text-center" style="color:#f86141; font-size:16px;"><i class="fa fa-user m-r-xs"></i>
                                <a href="#" style="color:#f86141;">
                                   <?php echo $session->read("User.display_name"); ?>
                                
                                </a></div>
                             <p>&nbsp;</p>
                        </div>
                        <div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <!--<a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                <a href="javascript:;" class="reload"> </a>-->
                                <a href="javascript:;" class="remove"> </a>
                            </div>
                        </div>
                </div>

                <div class="col-md-8 m-t-lg">


                    <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-comments"></i><?php echo __("Account Settings"); ?> </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <!--<a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                <a href="javascript:;" class="reload"> </a>-->
                                <a href="javascript:;" class="remove"> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form class="validateForm form-horizontal" action="#" method="post">
                                <div class="form-group">
                                    <label class="control-label col-xs-2"></label>
                                    <div class="col-xs-10">	
                                        <p></p>
                                        <h4 class="bg-danger"></h4>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Name"); ?>
                                        <span class="required" aria-required="true"></span>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control " id="name" placeholder="Full Name" value="<?php echo $session->read("User.display_name"); ?>" readonly="">	
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter full name...</span>
                                    </div>
                                </div>
                                    <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Current Password"); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control validate[required]" id="current_password" placeholder="Password" name="current_password">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter current password...</span>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("New Password"); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="password" class="validate[required] form-control" id="password" placeholder="New Password" name="password">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter new password...</span>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Confirm Password"); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="password" class="validate[required,equals[password]] form-control" id="confirm_password" placeholder="Confirm Password" name="confirm_password">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter confirm password...</span>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-flat btn-success" name="save_change"><?php echo __("Save"); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption" >
                                <i class="fa fa-comments"></i><?php echo __("Other Information"); ?> </div>
                            <div class="tools">
                                <a href="javascript:;" class="expand"> </a>
                                <!--<a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                <a href="javascript:;" class="reload"> </a>-->
                                <a href="javascript:;" class="remove"> </a>
                            </div>
                        </div>
                        <div class="portlet-body" style="display: none;">
                            <form class="validateForm form-horizontal" action="#" method="post" id="doctor_form" enctype="multipart/form-data">							
                                <input type="hidden" value="<?php echo $session->read("User.role_name"); ?>" name="role">
                                <input type="hidden" value="<?php echo $session->read("User.id") ?>" name="user_id">
                                <input type="hidden" id="itsId" value="<?php echo $data['id']; ?>">
                                  <input type="hidden" name="profile_id" id="profile_id" value="<?php echo $profile_data['id']; ?>">
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Gender
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="checkbox1_8" <?php echo (($profile_data['gender'] == '1') ? "checked" : "checked") ?> value="1" name="gender" class="check_limit md-radiobtn">
                                                <label for="checkbox1_8">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Male </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="checkbox1_9" <?php echo (($profile_data['gender'] == '0') ? "checked" : ""); ?> value="0" name="gender" class="check_limit md-radiobtn">
                                                <label for="checkbox1_9">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Female </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Date of birth"); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input id="birth_date" class="form-control validate[required] hasDatepicker" type="text" name="dob" value="<?php echo (isset($profile_data->dob)) ? $profile_data->dob->format($this->Pheramor->getSettings('date_format')) : ''; ?>">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter your birthday...</span>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Home Town Address"); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input id="address" class="form-control validate[required]" type="text" name="address" value="<?php echo $profile_data->address; ?>">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter your address...</span>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("City"); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input id="city" class="form-control validate[required]" type="text" name="city" value="<?php echo  $profile_data->city; ?>">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter your city...</span>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Mobile No."); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input id="mobile" class="form-control validate[,custom[phone]] text-input" type="text" name="phone" value="<?php echo $profile_data->phone; ?>">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter your phone...</span>
                                    </div>
                                </div>	

                              


                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Email"); ?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input id="email"  class="form-control validate[required,custom[email],ajax[isEmailUnique1]] text-input" type="text" name="email" value="<?php echo $data["email"]; ?>">
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Enter your email...</span>
                                    </div>
                                </div>
                                  
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Upload Image
                                    </label>
                                    <div class="col-md-9"> <div class="col-md-9">
                                            <?php
                                            echo $this->Form->file("image", ["class" => "form-control"]);
                                            $image = (!empty($profile_img)) ? $profile_img : $this->request->webroot . "upload/profile-placeholder.png";
                                            echo "<div id='img-div'><br><img width='100' src='{$image}'>";
                                            if (!empty($profile_img) && $profile_img != $this->request->webroot . 'upload/profile-placeholder.png') {
                                                echo '<div style="padding:10px;"><span id="del-img" class="label label-success">Remove</span></div>';
                                            }
                                            echo '</div>';
                                            ?>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div></div>
                                </div>


                                <div class="form-group form-md-line-input">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-flat btn-info" name="profile_save_change"><?php echo __("Save"); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                   
</section>
<input type="hidden" name="itsId" id="itsId" value="<?php echo $session->read("User.original_id"); ?>">
<script>
     ////Image delete 
        
         $('#del-img').click(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/PheramorAjax/deleteImageByProfileId'; ?>",
            data: {id: $("#profile_id").val()},
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
    </script>