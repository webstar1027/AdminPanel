<?php $this->Html->addCrumb('General Settings');?>
<script>
$(document).ready(function(){	

	var box_height = $(".box").height();
	var box_height = box_height + 200 ;
	$(".content-wrapper").css("height",box_height+"px");
});
</script>

	<div class="col-md-12">	
             <div class="portlet light portlet-fit portlet-form bordered">
                <div class="portlet-body">
                 <div class="box-body">
		 <div class="form-body">
                      <div class="profile-content">
                     <div class="row">
                         <div class="col-md-12">
                             <div class="portlet light ">
                                 <div class="portlet-title tabbable-line">
                                     <div class="caption">
                                         <i class=" icon-settings font-red"></i>
                                         <span class="caption-subject font-red sbold uppercase"><?php echo __("General Settings"); ?></span>
                                     </div>
                                     <ul class="nav nav-tabs">
                                         <li class="active">
                                          <a href="#tab_1_1" data-toggle="tab" aria-expanded="true">Main Setting</a>
                                         </li>
                                         <li class="">
                                             <a href="#tab_1_2" data-toggle="tab" aria-expanded="false">Payment Details</a>
                                         </li>
                                         <li>
                                             <a href="#tab_1_3" data-toggle="tab">Configuration</a>
                                         </li>
                                         <li>
                                             <a href="#tab_1_4" data-toggle="tab">Genetic Password</a>
                                         </li>
                                     </ul>
                                 </div>
                                 <div class="portlet-body">
                                     <div class="tab-content">
                                         <!-- PERSONAL INFO TAB -->
                                         <div class="tab-pane active" id="tab_1_1">
                                             <?php echo $this->Form->create("settings", ["type" => "file", "class" => "validateForm form-horizontal"]); ?>
                                             <div class="form-group form-md-line-input">
                                                 <label class="col-md-3 control-label" for="form_control_1">Company Name
                                                     <span class="required" aria-required="true">*</span>
                                                 </label>
                                                 <div class="col-md-9">
                                                     <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['company_name'] : ""); ?>" placeholder="Enter comapny name"  name="company_name">
                                                     <div class="form-control-focus"> </div>
                                                     <span class="help-block">Enter company name....</span>
                                                 </div>
                                             </div>



                                             <div class="form-group form-md-line-input">
                                                 <label class="col-md-3 control-label" for="form_control_1">Company Address
                                                     <span class="required" aria-required="true">*</span>
                                                 </label>
                                                 <div class="col-md-9">
                                                     <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['company_address'] : ""); ?>" placeholder="Enter company address"  name="company_address">
                                                     <div class="form-control-focus"> </div>
                                                     <span class="help-block">Enter company address....</span>
                                                 </div>
                                             </div>

                                             <div class="form-group form-md-line-input">
                                                 <label class="col-md-3 control-label" for="form_control_1">Office Phone Number
                                                     <span class="required" aria-required="true">*</span>
                                                 </label>
                                                 <div class="col-md-9">
                                                     <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['company_phone'] : ""); ?>" placeholder="Enter office phone"  name="company_phone">
                                                     <div class="form-control-focus"> </div>
                                                     <span class="help-block">Enter office phone....</span>
                                                 </div>
                                             </div>


                                             <div class="form-group form-md-line-input">
                                                 <label class="col-md-3 control-label" for="form_control_1">Email
                                                     <span class="required" aria-required="true">*</span>
                                                 </label>
                                                 <div class="col-md-9">
                                                     <input type="text" class="form-control validate[required,custom[email]]" value="<?php echo (($edit) ? $data['comapny_email'] : ""); ?>" placeholder="Enter email address"  name="comapny_email">
                                                     <div class="form-control-focus"> </div>
                                                     <span class="help-block">Enter email address....</span>
                                                 </div>
                                             </div>
                                              <!--<div class="form-group form-md-line-input">
                                                 <label class="col-md-3 control-label" for="form_control_1">Suggest Profile Limit
                                                     <span class="required" aria-required="true">*</span>
                                                 </label>
                                                 <div class="col-md-9">
                                                     <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['suggest_profile_limit'] : ""); ?>" placeholder="Enter profile limit"  name="suggest_profile_limit">
                                                     <div class="form-control-focus"> </div>
                                                     <span class="help-block">Enter profile limit....</span>
                                                 </div>
                                             </div>-->
                                             <div class="form-group form-md-line-input">
                                                 <label class="col-md-3 control-label" for="form_control_1">Date Formate
                                                     <span class="required" aria-required="true"></span>
                                                 </label>
                                                 <div class="col-md-9">
                                                     <?Php
                                                     $format = [
                                                         "F j, Y" => date("F j, Y"),
                                                         "M j, Y" => date("M j, Y"),
                                                         "Y-m-d" => date("Y-m-d"),
                                                         "mm/dd/yyyy" => date("m/d/Y"),
                                                         "m/d/Y" => date("d/m/Y")
                                                     ];

                                                     $default = ($edit && !empty($data['date_format'])) ? [$data['date_format']] : ['yy/mm/dd'];
                                                     echo $this->Form->select("date_format", $format, ["default" => $default, "class" => "form-control plan_list validate[required]"]);
                                                     ?>
                                                     <div class="form-control-focus"> </div>
                                                     <span class="help-block">Select date formate....</span>
                                                 </div>
                                             </div>



                                             <div class="form-group form-md-line-input">
                                                 <label class="col-md-3 control-label" for="form_control_1">Company Logo
                                                     <span class="required" aria-required="true"></span>
                                                 </label>
                                                 <div class="col-md-7">
                                                     <?php echo $this->Form->file("", ["name" => "company_logo", "class" => "form-control"]); ?>
                                                     <div class="form-control-focus"> </div>
                                                     <span class="help-block">Select logo....</span>
                                                 </div>
                                                 <div class="col-md-2">(Max. height 50px.)</div>
                                                 &nbsp;
                                                 <?php
                                                 $src = ($edit && !empty($data['company_logo'])) ? $data['company_logo'] : $this->request->webroot."upload/logo.png";
                                                 echo "<div class='col-md-offset-3 col-md-9'>";
                                                 echo "<br><img src='{$src}' height='70'><br><br><br>";
                                                 echo "</div>";
                                                 ?>
                                             </div>
                                                 <h4><legend>Header & Footer Text</legend></h4>
                           
                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Left Header Text
                                                         <span class="required" aria-required="true"></span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="text" class="form-control " value="<?php echo (($edit) ? $data['left_header'] : ""); ?>" placeholder="Enter left header"  name="left_header">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter left header....</span>
                                                     </div>
                                                 </div>

                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Footer Text
                                                         <span class="required" aria-required="true"></span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="text" class="form-control " value="<?php echo (($edit) ? $data['footer'] : ""); ?>" placeholder="Enter footer text"  name="footer">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter footer text....</span>
                                                     </div>
                                                 </div>


                                                 <hr>

                                                <div class="col-md-3">&nbsp;</div>
                                                 <div class="col-md-9" style="padding-top: 10px;">
                                                     <?php
                                                     echo $this->Form->button(__("Save Main Setting"), ['class' => "btn btn-flat btn-success", "name" => "save_setting"]);
                                                     echo $this->Form->end();
                                                     //echo "<br><br><br>";
                                                     ?>	
                                                 </div>
                                         </div>
                                         <!-- END PERSONAL INFO TAB -->
                                         <!-- CHANGE AVATAR TAB -->
                                         <div class="tab-pane" id="tab_1_2">
                                             <form name="payment_setting" id="payment_setting" method="post">
                                                 <div class="form-group">
                                                     <label class="col-md-3 control-label" for="form_control_1">Payment Mode</label>
                                                     <div class="col-md-9">
                                                         <div class="md-radio-list">
                                                             <div class="md-radio has-success">
                                                                 <input type="radio" id="radio50" value="0" name="enable_sandbox" <?php echo (($edit && $data['enable_sandbox'] == '0') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> class="md-radiobtn">
                                                                 <label for="radio50">
                                                                     <span></span>
                                                                     <span class="check"></span>
                                                                     <span class="box"></span> Sandbox </label>
                                                             </div>
                                                             <div class="md-radio has-error">
                                                                 <input type="radio" id="radio51" value="1" name="enable_sandbox" <?php echo (($edit && $data['enable_sandbox'] == '1') ? "checked" : "") ; ?> class="md-radiobtn" >
                                                                 <label for="radio51">
                                                                     <span></span>
                                                                     <span class="check"></span>
                                                                     <span class="box"></span> Live </label>
                                                             </div>

                                                         </div>
                                                        
                                                     </div>
                                                 </div>
                                                 

                                                 <h4><div class="caption">
                                                         <i class="icon-settings font-green-sharp"></i>
                                                         <span class="caption-subject font-green-sharp bold uppercase">Test Account Details</span>
                                                     </div><hr></h4>

                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Secret Key
                                                         <span class="required" aria-required="true">*</span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['test_secret_key'] : ""); ?>" placeholder="Enter Secret Key"  name="test_secret_key">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter secret key....</span>
                                                     </div>
                                                 </div>


                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Publishable Key
                                                         <span class="required" aria-required="true">*</span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['test_publishable_key'] : ""); ?>" placeholder="Enter Publishable Key"  name="test_publishable_key">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter Publishable Key....</span>
                                                     </div>
                                                 </div>
                                                 <br><br>

                                                 <h4><div class="caption">
                                                         <i class="icon-settings font-red-sharp" style="color:red;"></i>
                                                         <span class="caption-subject font-red-sharp bold uppercase"  style="color:red;">Live Account Details</span>
                                                     </div><hr></h4>
                                                

                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Secret Key
                                                         <span class="required" aria-required="true">*</span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['live_secret_key'] : ""); ?>" placeholder="Enter Secret Key"  name="live_secret_key">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter secret key....</span>
                                                     </div>
                                                 </div>


                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Publishable Key
                                                         <span class="required" aria-required="true">*</span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['live_publishable_key'] : ""); ?>" placeholder="Enter Publishable Key"  name="live_publishable_key">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter Publishable Key....</span>
                                                     </div>
                                                 </div>
                                                 <br><br>
                                                 <div class="col-md-3">  
                                                     <button class="btn btn-flat btn-success" type="submit">Save Payment Details</button>
                                                 </div>
                                                 <div class="col-md-9" style="padding-top: 10px;" id="payment_status">
                                                           
                                                  </div>
                                             </form>
                                         </div>
                                         <!-- END CHANGE AVATAR TAB -->
                                         <!-- CHANGE PASSWORD TAB -->
                                         <div class="tab-pane" id="tab_1_3">
                                             
                                             
                                             
                                             <form name="configFrm" id="configFrm" class="form-horizontal validateForm" method="post">
                                               
                                                     
                                                <div style="background: #E5E5E5; color:#00; padding:20px;">
                                                  <div class="list-toggle-title bold">1 Credit = How Many Dollars?</div>
                                                </div>
                                                <p>&nbsp;</p>
                                                <div class="form-group">
                                                     <label class="col-md-2 control-label" style="text-align:right;">1 Credit = </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="credit_dollar" class="form-control" placeholder="$" value="<?php echo $credit_data['credit_dollar']?>">
                                                             <span class="input-group-addon">
                                                                 <i class="fa fa-usd"></i>
                                                             </span>
                                                         </div>
                                                     </div>
                                                     <div class="col-md-8"></div>
                                                 </div>
                                                  <div style="background: #E5E5E5; color:#00; padding:20px;">
                                                  <div class="list-toggle-title bold">Credit Configuration</div>
                                                </div>
                                                <p>&nbsp;</p>
                                               <div class="form-group">
                                                     <label class="col-md-2 control-label" style="text-align:right;">Registration </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="register_credit" class="form-control" placeholder="Credits" value="<?php echo $credit_data['register_credit']?>">
                                                             <span class="input-group-addon">
                                                                 Credits
                                                             </span>
                                                          </div>
                                                     </div>
                                                    <label class="col-md-2 control-label" style="text-align:right;">Daily Login Activity </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="login_credit" class="form-control" placeholder="Credits" value="<?php echo $credit_data['login_credit']?>">
                                                             <span class="input-group-addon">
                                                                 Credits
                                                             </span>
                                                          </div>
                                                     </div><label class="col-md-2 control-label" style="text-align:right;">Cafe Check-In </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="cafe_credit" class="form-control" placeholder="Credits" value="<?php echo $credit_data['cafe_credit']?>">
                                                             <span class="input-group-addon">
                                                                 Credits
                                                             </span>
                                                          </div>
                                                     </div>
                                                 </div>
                                                <!--<div class="form-group">
                                                     <label class="col-md-2 control-label" style="text-align:right;">Purchase Pharamor Kit </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="kit_credit" class="form-control" placeholder="Credits" value="<?php echo $credit_data['kit_credit']?>">
                                                             <span class="input-group-addon">
                                                                 Credits
                                                             </span>
                                                          </div>
                                                     </div>
                                                    <label class="col-md-2 control-label" style="text-align:right;">Subscription Plan </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="subscription_credit" class="form-control" placeholder="Credits" value="<?php echo $credit_data['subscription_credit']?>">
                                                             <span class="input-group-addon">
                                                                 Credits
                                                             </span>
                                                          </div>
                                                     </div>
                                                     <label class="col-md-2 control-label" style="text-align:right;">Promotional Code Kit </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="promotional_pher_kit_credit" class="form-control" placeholder="Credits" value="<?php echo $credit_data['promotional_pher_kit_credit']?>">
                                                             <span class="input-group-addon">
                                                                 Credits
                                                             </span>
                                                          </div>
                                                     </div>
                                                     
                                                 </div>-->
                                                
                                                <div class="form-group">
                                                     <label class="col-md-2 control-label" style="text-align:right;">Event Check-In </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="event_credit" class="form-control" placeholder="Credits" value="<?php echo $credit_data['event_credit']?>">
                                                             <span class="input-group-addon">
                                                                 Credits
                                                             </span>
                                                          </div>
                                                     </div>
                                                   
                                                     
                                                 </div>
                                                
                                                      
                                                <div style="background: #E5E5E5; color:#00; padding:20px;">
                                                  <div class="list-toggle-title bold">How Many Suggest Profile?</div>
                                                </div>
                                                <p>&nbsp;</p>
                                                 <div class="form-group">
                                                     <label class="col-md-2 control-label" style="text-align:right;">Profile Limit </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="suggest_profile_limit" class="form-control" placeholder="Profile Limit" value="<?php echo $credit_data['suggest_profile_limit']?>">
                                                             <span class="input-group-addon">
                                                                 Members
                                                             </span>
                                                          </div>
                                                     </div>
                                                   
                                                     
                                                 </div>
                                                
                                                 <div style="background: #E5E5E5; color:#00; padding:20px;">
                                                  <div class="list-toggle-title bold">Pheramor Kit Purchased Offer</div>
                                                </div>
                                                <p>&nbsp;</p>
                                                 <div class="form-group">
                                                     <label class="col-md-2 control-label" style="text-align:right;">Membership Name </label>
                                                     <div class="col-md-4">
                                                         <div class="input-group">
                                                             <?php
                                                              $sub_data=$this->Pheramor->get_subscription_names($credit_data["kit_subscription_id"]);
                                                             // print_r($sub_data);
                                                             ?>
                                                             <input type="text" value="<?php echo $sub_data[0]['subscription_title'];?>" readonly="readonly" class="form-control">
                                                             <?php
                                                                   //echo $this->Form->select("kit_subscription_id", $membership, ["default" =>$credit_data["kit_subscription_id"], "id"=>"kit_subscription_id", "disabled"=>true,"empty" => __("Select Membership"), "class" => "form-control input-text validate[required]"]);
                                                               ?>
                                                          </div>
                                                     </div>
                                                   <div class="form-group">
                                                     <label class="col-md-2 control-label" style="text-align:right;">Duration </label>
                                                     <div class="col-md-2">
                                                         <div class="input-group">
                                                             <input type="text" name="kit_subscription_duration" class="form-control" placeholder="Duration" value="<?php echo $credit_data['kit_subscription_duration']?>">
                                                             <span class="input-group-addon">
                                                                 Months
                                                             </span>
                                                          </div>
                                                     </div>
                                                   
                                                     
                                                 </div>
                                                     
                                                 </div>
                                                
                                                
                                                
                                                   <hr>

                                               
                                                 <div class="col-md-3" style="padding-top: 10px;">
                                                     <?php
                                                     echo $this->Form->button(__("Save Configuration"), ['class' => "btn btn-flat btn-success", "name" => "save_setting"]);
                                                     echo $this->Form->end();
                                                     //echo "<br><br><br>";
                                                     ?>	
                                                 </div>
                                                   <div class="col-md-9" id="credit_status" style="padding-top: 10px;">
                                                       
                                                   </div>
                                                   
                                             </form>
                                             
                                         </div>
                                         <!-- END CHANGE PASSWORD TAB -->
                                         <!-- PRIVACY SETTINGS TAB -->
                                         <div class="tab-pane" id="tab_1_4">
                                           
                                             <form action="#" name="genetic_data_pass" id="genetic_data_pass" class="form-horizontal validateForm" method="post">
                                                 
                                                 

                                                 <h4><div class="caption">
                                                         <i class="icon-settings font-green-sharp"></i>
                                                         <span class="caption-subject font-green-sharp bold uppercase">Reset Genetic Data Password</span>
                                                     </div><hr></h4>

                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Current Password
                                                         <span class="required" aria-required="true">*</span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="password" class="form-control validate[required]" value="" placeholder="Current Password"  name="current_password" id="current_password">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter Current Password....</span>
                                                     </div>
                                                 </div>


                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">New Password
                                                         <span class="required" aria-required="true">*</span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="password" class="form-control validate[required,minSize[5]]" value="" placeholder="New Password"  id="password" name="password">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter New Password....</span>
                                                     </div>
                                                 </div>
                                                 <div class="form-group form-md-line-input">
                                                     <label class="col-md-3 control-label" for="form_control_1">Confirm Password
                                                         <span class="required" aria-required="true">*</span>
                                                     </label>
                                                     <div class="col-md-9">
                                                         <input type="password" class="form-control validate[required,equals[password]] " value="" id="confirm_password" placeholder="Confirm Password"  name="confirm_password">
                                                         <div class="form-control-focus"> </div>
                                                         <span class="help-block">Enter Confirm Password....</span>
                                                     </div>
                                                 </div>
                                                 

                                                 
                                                 <div class="col-md-3">  
                                                  </div>
                                                  <div class="col-md-9">  
                                                     <button class="btn btn-flat btn-success" type="submit">Reset Password</button>
                                                    
                                                 </div>
                                                 <div class="col-md-12"> 
                                                     <div class="col-md-3">  &nbsp;</div>
                                                 <div class="col-md-9" style="padding-top: 10px;" id="generic_pass_status">
                                                           
                                                  </div>
                                                 </div>
                                                 
                                             </form>
                                         
                                         </div>
                                        
                                         <!-- END PRIVACY SETTINGS TAB -->
                                     </div>
                                      
                                 </div>
                                
                             </div>
                         </div>
                     </div>
                 </div>
                        
                    </div>
                     <br><br>
                    </div>
		</div>	
                </div>
		</div>	
	
<script>
 $(document).ready(function(){
      $("#configFrm").submit(function (e) {
          
          var kit_subscription_id=$("#kit_subscription_id").val();
          if(kit_subscription_id==''){ return false;}
              var url = "<?php echo $this->request->base . '/PheramorAjax/updateCreditSetting'; ?>"; // the script where you handle the form input.
               $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#configFrm").serialize(), // serializes the form's elements.
                    dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                         if(data.status=='fail'){
                               HTMLMSG='<div class="alert alert-danger"><strong>Error!</strong> Sorry credit configuration failed.</div>';
                          }else{
                              HTMLMSG='<div class="alert alert-success"><strong>Success!</strong> Credit configuration has been updated successfully.</div>'; 
                          }
                         $("#credit_status").html(HTMLMSG); // show response from the php script.
                         $("#credit_status").show().delay(5000).fadeOut();
                    },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });
               e.preventDefault(); // avoid to execute the actual submit of the form.
        }); 
        
        /// Payment Settings 
        $("#payment_setting").submit(function (e) {
          
              var url = "<?php echo $this->request->base . '/PheramorAjax/updatePaymentSetting'; ?>"; // the script where you handle the form input.
               $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#payment_setting").serialize(), // serializes the form's elements.
                    dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                         if(data.status=='fail'){
                               HTMLMSG='<div class="alert alert-danger"><strong>Error!</strong> Sorry payment setting failed.</div>';
                          }else{
                              HTMLMSG='<div class="alert alert-success"><strong>Success!</strong> Payment setting has been updated successfully.</div>'; 
                          }
                         $("#payment_status").html(HTMLMSG); // show response from the php script.
                         $("#payment_status").show().delay(5000).fadeOut();
                    },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });
               e.preventDefault(); // avoid to execute the actual submit of the form.
        }); 
        
        /// Generic Password Reset Ajax code
        
         $("#genetic_data_pass").validate({
         ignore: ":hidden",
         rules: {
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            current_password: {
                 required: true           
             }
         },
         submitHandler: function (form) {
         
              var url = "<?php echo $this->request->base . '/PheramorAjax/updateGenericPassword'; ?>"; // the script where you handle the form input.
               $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#genetic_data_pass").serialize(), // serializes the form's elements.
                    dataType: "JSON",
                    beforeSend: function () {
                    },
                    success: function (data)
                    {
                         if(data.status=='fail'){
                               HTMLMSG='<div class="alert alert-danger"><strong>Error!</strong> &nbsp;Your current password is not valid.</div>';
                          }else{
                              HTMLMSG='<div class="alert alert-success"><strong>Success!</strong> &nbsp;Generic password has been updated successfully.</div>'; 
                          }
                         $("#generic_pass_status").html(HTMLMSG); // show response from the php script.
                         $("#generic_pass_status").show().delay(5000).fadeOut();
                    },
                    error: function (jqXHR, exception) {
                        return false;
                    }
                });
                return false; // required to block normal submit since you used ajax
         }
     });
              
 });
 
 </script>
 
 <style>
  label.error {
    display: none !important;
}
     
 </style>