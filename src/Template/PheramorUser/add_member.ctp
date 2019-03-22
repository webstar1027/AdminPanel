<?php
//echo '<pre>';print_r($staff);
$bradcrumb = ($edit) ? 'Edit Member' : 'Add Member';
$this->Html->addCrumb('List Members', array('controller' => 'PheramorUser', 'action' => 'memberList'));
$this->Html->addCrumb($bradcrumb);
$session = $this->request->session()->read("User");

echo $this->Html->css('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.css');
echo $this->Html->css('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.skinFlat.css');
echo $this->Html->script('assets/global/plugins/ion.rangeslider/js/ion.rangeSlider.min.js');
echo $this->Html->script('assets/pages/scripts/components-ion-sliders.min.js');
 //echo "<pre>";print_r($data);
$profile_data=@$data['pheramor_user_profile'][0];
if($edit && $profile_data['age_range'] ){
    
    list($stpoint,$endpoint)=explode(',',@$profile_data['age_range']);
     $from=$stpoint;
     $to= $endpoint;      
}else{
    $from=18;
     $to= 25; 
}

//echo "<pre>";print_r($profile_data); die;
?>
<script>
   var ComponentsIonSliders = function() {
     var handleAdvancedDemos = function() {
         var $range = $("#agerange");
          $range.ionRangeSlider({
            type: "double",
            min: 18,
            max: 70,
            from: <?php echo $from;?>,
            to: <?php echo $to;?>,
            from_fixed: false
        });

        $range.on("change", function () {
        var $this = $(this),
         value = $this.prop("value").split(";");
         var txtval=value[0] + "," + value[1];
         console.log(txtval);
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
                    <a href="<?php echo $this->Gym->createurl("PheramorUser", "memberList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Members List"); ?></a>

                </div>
            </div>

        </div>
        
        <div class="portlet-body">
            <div class="box-body">
                <?php
                echo $this->Form->create("adduser", ["type" => "file", "class" => "validateForm form-horizontal", "role" => "form"]);
                ?>
                <input type="hidden" id="itsId" value="<?php echo ($edit) ? $data['id'] : ''; ?>">
                 <input type="hidden" id="profile_id" name="profile_id" value="<?php echo ($edit) ? $profile_data['id'] : ''; ?>">
                 <input type="hidden" name="app_password" value="--">
                <div class="form-body">

                    <h4><legend>Personal Information</legend></h4>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">First Name
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required,custom[onlyLetterSp]]" value="<?php echo(($edit) ? $profile_data['first_name'] : ''); ?>" placeholder="Enter first name"  name="first_name">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter first name....</span>
                        </div> </div>
                     </div>

                   <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Last Name
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo(($edit) ? $profile_data['last_name'] : ''); ?>" placeholder="Enter last name"  name="last_name">
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
                                    <input type="radio" id="checkbox1_8" <?php echo (($edit && $profile_data['gender'] == '1') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="gender"   class="check_limit md-radiobtn">
                                    <label for="checkbox1_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Male </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1_9" <?php echo (($edit && $profile_data['gender'] == '0') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : ""); ?> value="0" name="gender"   class="check_limit md-radiobtn">
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
                             
                            <input type="text" class="form-control dob validate[required,ajax[isDobValid]]" value="<?php echo (($edit & !empty($profile_data['dob'])) ? date($this->Gym->getSettings("date_format"), strtotime($profile_data['dob']->format("Y-m-d"))) : ''); ?>" placeholder="Enter birthday"  id="dob" name="dob">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter birthday....</span>
                        </div></div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Race
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="input-group-control">
                                    <?php echo @$this->Form->select("race", $race, ["default" => $profile_data['race'], "id" => "race", "empty" => __("Select Race"), "class" => "form-control"]); ?>
                                     <div class="form-control-focus"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input" id="other_race_div" style="display:none;">
                        <label class="col-md-3 control-label" for="form_control_1">Other Race
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="input-group-control">
                                    <input type="text" name="other_race" id="other_race" class="form-control validate[required]" value="<?php echo ($edit) ? $profile_data['other_race'] : ''; ?>" placeholder="Enter Race Name">
                                     <div class="form-control-focus"> </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Religion
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="input-group-control">
                                    <?php echo @$this->Form->select("religion", $religion, ["default" => $profile_data['religion'], "id" => "religion", "empty" => __("Select Religion"), "class" => "form-control"]); ?>
                                     <div class="form-control-focus"> </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input" id="other_religion_div" style="display:none;">
                        <label class="col-md-3 control-label" for="form_control_1">Other Religion
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="input-group-control">
                                    <input type="text" name="other_religion" id="other_religion" class="form-control validate[required]" value="<?php echo ($edit) ? $profile_data['other_religion'] : ''; ?>" placeholder="Enter Religion Name">
                                     <div class="form-control-focus"> </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                   
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Show Me
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="md-checkbox-inline">
                                    <?php
                                    $show_male=0;
                                    $show_fmale=1;
                                    $showproducts = explode(',',$profile_data['show_me']);
                                   /* if (@in_array('1',$showproducts))
                                    {
                                        $show_male=1;
                                        
                                    }
                                    if (@in_array('0',$showproducts))
                                    {
                                        $show_fmale=0;
                                    }
                                    */
                                    $show_male=$showproducts[0];
                                    $show_fmale=$showproducts[1];
                                    ?>
                                      <div class="md-checkbox">
                                          <input type="checkbox" id="checkbox1_3" name="show_me1" <?Php echo (($edit && $show_male== 1)? "checked" : "")?> value="1" class="md-check ">
                                          <label for="checkbox1_3">
                                              <span></span>
                                              <span class="check"></span>
                                              <span class="box"></span> Male </label>
                                      </div>
                                    <div class="md-checkbox">
                                          <input type="checkbox" id="checkbox11_3" name="show_me2" <?Php echo (($edit && $show_fmale== 1)? "checked" : "")?> value="1" class="md-check ">
                                          <label for="checkbox11_3">
                                              <span></span>
                                              <span class="check"></span>
                                              <span class="box"></span> Female </label>
                                      </div>
                                      
                                  </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Age Range
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <input id="agerange" class="agerange" type="text"  />
                                <input type="hidden" name="age_range" id="age_range" value="<?php echo (($edit) ? $profile_data['age_range'] : '18,25'); ?>">
                             </div>
                        </div>
                    </div>
                       <div class="form-group form-md-checkboxes">
                              <label class="col-md-3 control-label" for="form_control_1">Enable Notification</label>
                              <div class="col-md-9">
                                   <div class="input-group col-md-9">
                                  <div class="md-checkbox-inline">
                                      <div class="md-checkbox">
                                          <input type="checkbox" id="checkbox111_3" name="enable_notification" <?Php echo (($edit && $data['enable_notification'] == 1)? "checked" : "")?> value="1" class="md-check">
                                          <label for="checkbox111_3">
                                              <span></span>
                                              <span class="check"></span>
                                              <span class="box"></span> Enable </label>
                                      </div>
                                      
                                  </div>
                                   </div>
                              </div>
                          </div>
                    
                   
                    
                    
                    
                    
                    <h4><legend>Contact Information</legend></h4>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Home Town Address
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo (($edit) ? $profile_data['address'] : ''); ?>" placeholder="Enter address"  name="address">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter address....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">City
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[custom[onlyLetterSp]]" value="<?php echo (($edit) ? $profile_data['city'] : ''); ?>" placeholder="Enter city"  name="city">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter city....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">State
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[custom[onlyLetterSp]" value="<?php echo (($edit) ? $profile_data['state'] : ''); ?>" placeholder="Enter state"  name="state">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter state....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Country
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[custom[onlyLetterSp]" value="<?php echo (($edit) ? $profile_data['country'] : ''); ?>" placeholder="Enter country"  name="country">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter country....</span>
                        </div></div>
                    </div>
                    

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Zip code
                            <span class="required" aria-required="true">*</span>
                        </label>
<!--                        <div class="col-md-9">
                         <div class="col-md-9">
                             <input type="text" class="form-control validate[required, custom[integer],maxSize[6]]" maxlength="6" value="<?php echo (($edit) ? $profile_data['zipcode'] : ''); ?>" placeholder="Enter zipcode"  onkeyup="search_neighborhood(this.value);" name="zipcode">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter zipcode....</span>
                        </div></div>-->
                       <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="input-group-control">
                                    <?php echo @$this->Form->select("zipcode", $zipcode, ["default" => $profile_data['zipcode'], "id" => "religion", "empty" => __("Select Zipcode"), "class" => "form-control validate[required] search_neighborhood", ]); ?>
                                     <div class="form-control-focus"> </div>
                                  </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Phone
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                             <input type="text" class="form-control  validate[custom[phone]]" value="<?php echo (($edit) ? $profile_data['phone'] : ''); ?>" placeholder="Enter phone number"  name="phone">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter phone number....</span>
                            </div>
                        </div>
                    </div>
                   <h4><legend>Login Information</legend></h4>

                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Email
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control validate[required,custom[email],ajax[isEmailUnique1]]" value="<?php echo (($edit) ? $data['email'] : ''); ?>" placeholder="Enter email"  name="email" id="email">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter email....</span>
                        </div></div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Password
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9"> <div class="col-md-9">
                            <input type="password" class="form-control validate[required,minSize[6]]" value="<?php echo (($edit) ? $data['password'] : ''); ?>" placeholder="Enter password"  name="password" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter password....</span>
                        </div></div>
                    </div>

                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Display Image
                        </label>
                        <div class="col-md-9"> <div class="col-md-9">
                            <?php
                             echo $this->Form->file("image", ["id"=>"userfile","class" => "form-control validate[custom[validateMIME[image/jpeg|image/png]]]"]);
                            $image = ($edit && !empty($profile_data['image'])) ? $profile_data['image'] : $this->request->webroot."upload/profile-placeholder.png";
                              echo "<div id='img-div'><br><img width='100' src='{$image}'>";
                            if(!empty($profile_data['image']) && $profile_data['image'] != $this->request->webroot.'upload/profile-placeholder.png'){
                                echo '<div style="padding:10px;"><span id="del-img" class="label label-success">Remove</span></div>';
                             }
                             echo '</div>';
                            ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div></div>
                    </div>-->


                    <h4><legend>Other Information</legend></h4>
                    
                   <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Neighborhood
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo (($edit) ?$profile_data['neighborhood'] : ''); ?>" placeholder="Enter Neighborhood "  name="neighborhood" id="neighborhood" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter neighborhood....</span>
                        </div>  </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Profession
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo (($edit) ?$profile_data['profession'] : ''); ?>" placeholder="Enter Profession "  name="profession" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter profession....</span>
                        </div>  </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">About Me
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <textarea class="form-control" placeholder="Enter details "  name="about_me" ><?php echo (($edit) ?$profile_data['about_me'] : ''); ?>  </textarea>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter details....</span>
                        </div>  </div>
                    </div>
                    
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Status Message
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <textarea class="form-control" placeholder="Enter Status Message "  name="about_status" ><?php echo (($edit) ?$profile_data['about_status'] : ''); ?>  </textarea>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Status Message....</span>
                        </div>  </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Height
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control  validate[custom[number]]" value="<?php echo (($edit) ? $profile_data['height'] : ''); ?>" placeholder="Enter height in cm "  name="height" >
                                    <span class="input-group-addon">cm</span>
                                    <div class="form-control-focus"> </div>

                                </div>
                                <span class="help-block">Enter height in cm....</span>
                            </div>  </div>
                    </div>
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Weight
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control  validate[custom[number]]" value="<?php echo (($edit) ? $profile_data['weight'] : ''); ?>" placeholder="Enter weight in lbs "  name="weight" >
                                    <span class="input-group-addon">lbs</span>
                                    <div class="form-control-focus"> </div>

                                </div>
                                <span class="help-block">Enter weight in lbs....</span>
                            </div>  </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Body Type
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="input-group-control">
                                    <?php echo @$this->Form->select("body_type", $body_type, ["default" => $profile_data['body_type'], "id" => "body_type", "empty" => __("Select Body Type"), "class" => "form-control"]); ?>
                                     <div class="form-control-focus"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="orientation" value="">
                     <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Sexual Orientation
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group col-md-9">
                                <div class="input-group-control">
                                    <?php echo @$this->Form->select("orientation", $orienation, ["default" => $profile_data['orientation'], "id" => "orienation", "empty" => __("Select Sexual Orientation"), "class" => "form-control "]); ?>
                                     <div class="form-control-focus"> </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                     <input type='hidden' name="facebook" value="">
                     <input type='hidden' name="twitter" value="">
                     <input type='hidden' name="Instagram" value="">
                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Facebook URL
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control  validate[custom[url]]" value="<?php echo (($edit) ?$profile_data['facebook'] : ''); ?>" placeholder="Enter facebook url "  name="facebook" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Facebook URL....</span>
                        </div>  </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Twitter URL
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control validate[custom[url]]" value="<?php echo (($edit) ?$profile_data['twitter'] : ''); ?>" placeholder="Enter Twitter url "  name="twitter" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Twitter URL....</span>
                        </div>  </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Instagram URL
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control validate[custom[url]]" value="<?php echo (($edit) ?$profile_data['Instagram'] : ''); ?>" placeholder="Enter Instagram url "  name="Instagram" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Instagram URL....</span>
                        </div>  </div>
                    </div>-->
                    <?php if($edit && $profile_data['gender']=='0'){ ?>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Birth Pill
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-6">
                         <div class="col-md-12">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1111_800" <?php echo (($edit && $profile_data['birth_pill'] == '1') ? "checked" : ""); ?> value="1" name="birth_pill" class="md-radiobtn">
                                    <label for="checkbox1111_800">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Yes </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1111_900" <?php echo (($edit && $profile_data['birth_pill'] == '0') ? "checked" : ""). ' ' . ((!$edit) ? "checked" : "") ; ?> value="0" name="birth_pill" class="md-radiobtn">
                                    <label for="checkbox1111_900">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> No </label>
                                </div>

                            </div>
                        </div> </div>
                    </div>
                    
                    <?php } ?>
                       <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Bone Marrow Donor
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-6">
                         <div class="col-md-12">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1111_8" <?php echo (($edit && $profile_data['bone_marrow_donor'] == '1') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="bone_marrow_donor" class="md-radiobtn">
                                    <label for="checkbox1111_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Yes </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox1111_9" <?php echo (($edit && $profile_data['bone_marrow_donor'] == '0') ? "checked" : "") ; ?> value="0" name="bone_marrow_donor" class="md-radiobtn">
                                    <label for="checkbox1111_9">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> No </label>
                                </div>

                            </div>
                        </div> </div>
                    </div>

                       <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member Status
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-6">
                         <div class="col-md-12">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_8" <?php echo (($edit && $data['activated'] == '1') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="activated" class="md-radiobtn validate[required]">
                                    <label for="checkbox111_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Active </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_9" <?php echo (($edit && $data['activated'] == '0') ? "checked" : "") ; ?> value="0" name="activated" class="md-radiobtn validate[required]">
                                    <label for="checkbox111_9">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Inactive </label>
                                </div>

                            </div>
                        </div> </div>
                    </div>
                    
                     <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member Location
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-6">
                         <div class="col-md-12">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_81" <?php echo (($edit && $data['location_key'] == 'pheramor') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="pheramor" name="location_key" class="md-radiobtn validate[required]">
                                    <label for="checkbox111_81">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Pheramor </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_91" <?php echo (($edit && $data['location_key'] == 'placeholder') ? "checked" : "") ; ?> value="placeholder" name="location_key" class="md-radiobtn validate[required]">
                                    <label for="checkbox111_91">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Placeholder </label>
                                </div>

                            </div>
                        </div> </div>
                    </div>-->
                     <input type='hidden' name='location_key' value='pheramor'>
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

<script>
$(document).ready(function(){
    
    $('#del-img').click(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/PheramorAjax/deleteImageByUserId'; ?>",
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
       
       //// Other Religion Div toggle 
       var string2 = "Other: Please specify";
       var religion_data= $("select#religion").find("option:selected").text();
       var race_data= $("select#race").find("option:selected").text();
          if(religion_data.toLowerCase() === string2.toLowerCase()){
                 $("#other_religion_div").show();
           }else{
              $("#other_religion_div").hide();
             }
       
      
        $("select#religion").change(function(){
            $("#other_religion_div").hide();
            var string1 = $(this).find("option:selected").text();
            
            if(string1.toLowerCase() === string2.toLowerCase())
            {
                $("#other_religion_div").show();
            }
        });
        
        /// Other Race toggle box
        
           if(race_data.toLowerCase() === string2.toLowerCase()){
                 $("#other_race_div").show();
           }else{
              $("#other_race_div").hide();
           }
             
        $("select#race").change(function(){
            $("#other_race_div").hide();
            var string1 = $(this).find("option:selected").text();
            if(string1.toLowerCase() === string2.toLowerCase())
            {
                $("#other_race_div").show();
            }
        });
        
       $('select.search_neighborhood').on('change', function() {
       var zipcode=this.value;;
       search_neighborhood(zipcode);
       });  
        
    
});    
function search_neighborhood(value){
       
       var url = "<?php echo $this->request->base . '/PheramorAjax/getNeighborhoodData'; ?>";
      
       if (value!='') {
         $.ajax({
            type: "POST",
            url: url,
            data: {'zipcode' : value},
            dataType: "json",
            success: function(msg){
              if(msg ==null) {
                    $("#neighborhood").val('');
               }else{
                    $("#neighborhood").val(msg.neighborhood);
               }
             }
         });
     }
}
  
$(document).ready(function(){
    var zip = '<?php echo ( $edit && !empty($profile_data['zipcode']) ) ? $profile_data['zipcode'] : FALSE;?>';
    if(zip !== false){
        search_neighborhood(zip);
    }
});
</script>