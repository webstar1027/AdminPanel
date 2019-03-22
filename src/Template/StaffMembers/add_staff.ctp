<?php 
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
$bradcrumb = ($edit) ? 'Edit Staff Member' : 'Add Staff Member';
$this->Html->addCrumb('List Staff Member', array('controller' => 'StaffMembers', 'action' => 'StaffList'));
$this->Html->addCrumb($bradcrumb);
?>
<script type="text/javascript">
/*$(document).ready(function() {
        var date = new Date();
	$('#specialization').multiselect({
		includeSelectAllOption: true	
	});
	var box_height = $(".box").height();
	var box_height = box_height + 500 ;
	$(".content-wrapper").css("height",box_height+"px");
});

function validate_multiselect()
{		
	var specialization = $("#specialization").val();
	if(specialization == null)
	{
		alert("Select Specialization.");
		return false;
	}else{
		return true;
	}	 		
}*/
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
                             <a href="<?php echo $this->Gym->createurl("StaffMembers", "StaffList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Staff List"); ?></a>

                         </div>
                     </div>

                 </div>
		 <div class="portlet-body">
                     
                     
		<div class="box-body">			
			<?php				
			echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			?>
                        <input type="hidden" id="itsId" value="<?php echo ($edit) ? $data['id'] : '';?>">
                        
                         <div class="form-body">
                        
                          <h4><legend>Personal Information</legend></h4>
                          
                          
                          <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">First Name
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[required]" value="<?php echo(($edit)?$data['first_name']:''); ?>" placeholder="Enter first name"  name="first_name">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter first name....</span>
                              </div>
                          </div>
                           
                          <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Middle Name
                                  <span class="required" aria-required="true"></span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control" value="<?php echo(($edit)?$data['middle_name']:''); ?>" placeholder="Enter middle name"  name="middle_name">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter middle name....</span>
                              </div>
                          </div>
                          
                          <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Last Name
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[required]" value="<?php echo(($edit)?$data['last_name']:''); ?>" placeholder="Enter last name"  name="last_name">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter last name....</span>
                              </div>
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
                                  <input type="text" class="form-control dob validate[required]" value="<?php echo (($edit)? date($this->Gym->getSettings("date_format"), strtotime($data['birth_date'])):''); ?>" placeholder="Enter birthday"  name="birth_date">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter birthday....</span>
                              </div>
                          </div>
                          <input type="hidden" name="role" value=''>
                          <!--<div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Assign Role
                              </label>
                              <div class="col-md-9">
                                  <div class="input-group">
                                      <div class="input-group-control">
                                          <?php echo @$this->Form->select("role", $roles, ["default" => $data['role'], "empty" => __("Select Role"), "class" => "form-control roles_list"]); ?>
                                          <div class="form-control-focus"> </div>
                                      </div>

                                      <span class="input-group-btn btn-right">
                                          <?php echo "<a href='javascript:void(0)' class='add-role btn btn-flat btn-success' data-url='{$this->Gym->createurl("GymAjax", "addRole")}'>" . __("Add/Remove") . "</a>"; ?>
                                      </span>

                                  </div>
                              </div>
                          </div>-->
                          <?php if($session['role_id'] == 1) {?>
                              <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Associated Location
                                      <span class="required" aria-required="true">*</span>
                                  </label>
                                  <div class="col-md-9">
                                      <div class="input-group col-md-12">
                                          <div class="input-group-control">
                                              <?php echo @$this->Form->select("associated_location", $locations, ["default" => $location, "id"=>"associated_location", "empty" => __("Select Location"), "class" => "form-control validate[required]", ($edit && $assigned_members) ? "disabled" : ""]); ?>
                                              <?php echo ($edit && $assigned_members)?"<small><em> You can't change Location for this staff as this staff has been assigned ".$assigned_members." customers.</em></small>" : '';?>
                                              <div class="form-control-focus"> </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          <?php }?>
                            <?php if ($session['role_id'] == 1 || $session['role_id'] == 7 || $session['role_id'] == 8) { ?>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1"><?php echo ($session['role_id'] == 7 || $session['role_id'] == 8) ? 'Associated Manager' : 'Associated Licensee';?>
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <div class="input-group-control">
                                                <?php echo @$this->Form->select("associated_licensee", $licensees, ["default" => $data['associated_licensee'], "id"=>"associated_licensee", "empty" => ($session['role_id'] == 7 || $session['role_id'] == 8) ? __("Select Manager") : __("Select Licensee"), "class" => "form-control validate[required]", ($edit && $assigned_members) ? "disabled" : ""]); ?>
                                                <?php echo ($edit && $assigned_members)?"<small><em> You can't change Licensee for this staff as this staff has been assigned ".$assigned_members." customers.</em></small>" : '';?>
                                                <div class="form-control-focus"> </div>
                                            </div>

                                            <span class="input-group-btn btn-right">
                                                <?php echo "<a href='{$this->request->base}/Licensee/addLicensee' class='btn btn-flat btn-primary'><i class='fa fa-plus'></i>" . __(" Add") . "</a>"; ?>
                                            </span>

                                        </div>
                                    </div>
                                </div>
                                <?php
                                }else {
                                    echo $this->Form->hidden("", ["label" => false, "name" => "associated_licensee", "class" => "form-control validate[required]", "value" => ( ($edit) ? $data['associated_licensee'] : $session['original_id'])]);
                                }
                                ?>
                          
                          
                          <!--<div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Specialization
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <div class="input-group-control">
                                      <?php
                                      //echo @$this->Form->select("s_specialization", $specialization, ["default" => json_decode($data['s_specialization']), "multiple" => "multiple", "class" => "form-control validate[required] specialization_list", "id" => "specialization"]);
                                      //echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='add-spec btn btn-flat btn-success' data-url='{$this->request->base}/GymAjax/AddSpecialization'>" . __("Add/Remove") . "</a>";
                                      ?>
                                      <div class="form-control-focus"> </div>
                                  </div>
                              </div>
                          </div>-->
                          
                           <h4><legend>Contact Information</legend></h4>
                           
                           <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Home Town Address
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[required]" value="<?php echo (($edit)?$data['address']:''); ?>" placeholder="Enter address"  name="address">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter address....</span>
                              </div>
                          </div>
                          
                           <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">City
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[required]" value="<?php echo (($edit)?$data['city']:''); ?>" placeholder="Enter city"  name="city">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter city....</span>
                              </div>
                          </div>
                           
                          <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">State
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['state'] : ''); ?>" placeholder="Enter state"  name="state">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter state....</span>
                              </div>
                          </div>
                          
                          
                          
                          <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Zip code
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[required, custom[integer]]" value="<?php echo (($edit)?$data['zipcode']:''); ?>" placeholder="Enter zipcode"  name="zipcode">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter zipcode....</span>
                              </div>
                          </div>
                          
                           <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Mobile Number
                                  <span class="required" aria-required="true">*</span>
                              </label>
                               <div class="col-md-9">
                                   <div class="input-group">
                                       <span class='input-group-addon '>+<?php echo $this->Gym->getCountryCode($this->Gym->getSettings("country")); ?></span>
                                       <input type="text" class="form-control validate[required, custom[phone]]" value="<?php echo (($edit) ? $data['mobile'] : ''); ?>" placeholder="Enter mobile number"  name="mobile">
                                       <div class="form-control-focus"> </div>
                                       <span class="help-block">Enter mobile number....</span>
                                   </div>
                               </div>
                          </div>
                          
                           <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Phone
                                  <span class="required" aria-required="true"></span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[custom[phone]]" value="<?php echo (($edit)?$data['phone']:''); ?>" placeholder="Enter phone"  name="phone">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter phone....</span>
                              </div>
                          </div>
                          
                           <div class="form-group form-md-line-input">
                              <label class="col-md-3 control-label" for="form_control_1">Email
                                  <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="col-md-9">
                                  <input type="text" class="form-control validate[required,custom[email],ajax[isEmailUnique1]]" value="<?php echo (($edit)?$data['email']:''); ?>" placeholder="Enter email"  name="email">
                                  <div class="form-control-focus"> </div>
                                  <span class="help-block">Enter email....</span>
                              </div>
                          </div>
                          
                           <h4><legend>Login Information</legend></h4>
                           
                           
                            <!--<div class="form-group form-md-line-input">
                               <label class="col-md-3 control-label" for="form_control_1">Username
                                   
                               </label>
                               <div class="col-md-9">
                                   <input type="text" class="form-control" value="<?php //echo (($edit) ? $data['username'] : ''); ?>" placeholder="Enter username"  name="username" <?Php //echo (($edit)?"readonly":"")?>>
                                   <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter username....</span>
                               </div>
                           </div>-->
                           
                            <div class="form-group form-md-line-input">
                               <label class="col-md-3 control-label" for="form_control_1">Password
                                   <span class="required" aria-required="true">*</span>
                               </label>
                               <div class="col-md-9">
                                   <input type="password" class="form-control validate[required]" value="<?php echo (($edit) ? $data['password'] : ''); ?>" placeholder="Enter password"  name="password" >
                                   <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter password....</span>
                               </div>
                           </div>
                           <div class="form-group form-md-line-input">
                               <label class="col-md-3 control-label" for="form_control_1">Cut Percentage (%)
                                   <span class="required" aria-required="true"></span>
                               </label>
                               <div class="col-md-9">
                                   <?php 
                                   if($edit) { 
                                       $cut_percent=$this->Gym->lice_cut_percentage($data['associated_licensee']);
                                       if(empty($data['cut_percent'])){$data['cut_percent']=$cut_percent;}
                                   }?>
                                   <input type="text" class="form-control" value="<?php echo (($edit) ? $data['cut_percent'] : ''); ?>" placeholder="Enter cut percentage"  name="cut_percent" >
                                   <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter cut percentage....</span>
                               </div>
                           </div>
                           
                           <div class="form-group form-md-line-input">
                               <label class="col-md-3 control-label" for="form_control_1">Display Image
                               </label>
                               <div class="col-md-9">
                                    <?php
                                        echo $this->Form->file("image", ["class" => "form-control"]);
                                         $image = ($edit && !empty($data['image'])) ? $data['image'] : $this->request->webroot."upload/profile-placeholder.png";
                                         echo "<div id='img-div'><br><img width='100' src='{$image}'>";
                                         if(!empty($data['image']) && $data['image']!=$this->request->webroot.'upload/profile-placeholder.png'){
                                           echo '<div style="padding:10px;"><span id="del-img" class="label label-success">Remove</span></div>';
                                        }
                                        echo '</div>';
                                     ?>
                                   <div class="form-control-focus"> </div>
                                   <span class="help-block"></span>
                               </div>
                           </div>
                           
                            <div class="form-group form-md-line-input">
                            <div class="col-md-offset-3 col-md-6">
                                <input type="submit" value="<?php echo __("Save Staff"); ?>" name="save_staff" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>
                          
                         </div>
                    <?php echo $this->Form->end();?>				
		</div>	
                 </div>
	</div>
</div>
<script>
    $('#associated_location').change(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/GymAjax/fetchLicenseesByLocationId'; ?>",
            data: {loc: $(this).val()},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#associated_licensee").empty().append(response.data);
                    
                } else if (response.status == 'error') {
                    $("#associated_licensee").empty().append("<option value=''>Select Staff</option>");
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
        
        if(location && location != '' && (role_id == '7' || role_id == '1' || role_id == '8')){
            $.ajax({
                type: "POST",
                url: "<?php echo $this->request->base . '/GymAjax/fetchLicenseesByLocationId'; ?>",
                data: {loc: location},
                dataType: "JSON",
                beforeSend: function () {
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $("#associated_licensee").empty().append(response.data);
                        $('#associated_licensee').val("<?php echo @$data['associated_licensee'];//$associated_licensee;?>").prop('selected', true);
                    } else if (response.status == 'error') {
                        $("#associated_licensee").empty().append("<option value=''>Select Staff</option>");
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