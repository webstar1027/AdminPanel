<?php 
$this->Html->addCrumb('Admin Profile');
?>
<script type="text/javascript">
$(document).ready(function() {	
	
	var box_height = $(".box").height();
	var box_height = box_height + 100 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	// $('.class_list').multiselect({
		// includeSelectAllOption: true	
	// });
	
	$(".datepick").datepicker({format: 'yyyy-mm-dd'});
	
	// $(".content-wrapper").removeAttr("style");
	$(".content-wrapper").css("min-height","600px");
});
</script>

	<div class="col-md-12">	
            <div class="portlet light portlet-fit portlet-form bordered">
                
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-user font-red"></i>
                        <span class="caption-subject font-red sbold uppercase"><?php echo __("Profile Setting"); ?></span>
                        <small><?php echo __("Admin"); ?></small>
                    </div>
                    <div class="top">
                    </div>
                </div>
                
                
		<div class="portlet-body">
		<div class="box-body">
		<?php	echo $this->Form->create("editprofile",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);   ?>
                    <div class="form-body">
                        
                        <h4><legend>Personal Information</legend></h4>
                        
                         <div class="form-group form-md-line-input">
                             <label class="col-md-3 control-label" for="form_control_1">First Name
                                 <span class="required" aria-required="true">*</span>
                             </label>
                             <div class="col-md-9">
                                 <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['first_name'] : ""); ?>" placeholder="Enter first name"  name="first_name">
                                 <div class="form-control-focus"> </div>
                                 <span class="help-block">Enter first name....</span>
                             </div>
                         </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Last Name
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['last_name'] : ""); ?>" placeholder="Enter last name"  name="last_name">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter last name....</span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Email
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required,custom[email]]" value="<?php echo (($edit) ? $data['email'] : ""); ?>" placeholder="Enter email address"  name="email">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter email address....</span>
                            </div>
                        </div>
                        
                         <h4><legend>Login Information</legend></h4>
                         
                         
                         <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Username
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $data['username'] : ""); ?>" placeholder="Enter username"  name="username">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter username....</span>
                            </div>
                        </div>
                         
                          <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Password
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <?php echo $this->Form->password("",["label"=>false,"name"=>"password", "placeholder"=>"Enter password","class"=>"form-control"]); ?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter password....</span>
                            </div>
                        </div>
                         
                          <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Display Image
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <?php echo $this->Form->file("image",["class"=>"form-control"]);
                                $image = ($edit && !empty($data['image'])) ? $data['image'] : "logo.png";
			        echo "<br><img src='{$this->request->webroot}upload/{$image}'>";
                                ?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Upload Image....</span>
                            </div>
                        </div>
                         <hr>
                          <div class="form-group form-md-line-input">
                            <div class="col-md-offset-2 col-md-6">
                                <input type="submit" value="<?php echo __("Save Details"); ?>" name="save_details" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>
                        
                    </div>

                    <?Php echo $this->Form->end();?>
		</div>	
            </div>
                </div>
	</div>

