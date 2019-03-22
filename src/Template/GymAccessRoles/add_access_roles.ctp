<?php
$bradcrumb = ($edit) ? 'Edit Access Roles' : 'Add Access Roles';
$this->Html->addCrumb('List Access Roles', array('controller' => 'gymAccessRoles', 'action' => 'accessRolesList'));
$this->Html->addCrumb($bradcrumb);
?>
<script>
$(document).ready(function(){	
	
	var box_height = $(".box").height();
	var box_height = box_height + 300 ;
	$(".content-wrapper").css("height",box_height+"px");
	$(".content-wrapper").css("min-height","500px");
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
                             <a href="<?php echo $this->Gym->createurl("GymAccessRoles","AccessRolesList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Access Roles List"); ?></a>

                         </div>
                     </div>

                 </div>
		<div class="portlet-body">
		<div class="box-body">
			
			<?php
			
			echo $this->Form->create("addaccessroles",["class"=>"validateForm form-horizontal"]);
			?>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Access Role Name
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" value="<?php echo ($edit) ? $data["name"] : ""; ?>" placeholder="Enter your name"  name="name">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter your title...</span>
                            </div>
                        </div>
			 <div class="form-group form-md-line-input">
                            <div class="col-md-offset-3 col-md-6">

			    <?php echo $this->Form->button(__("Save Access Role"),['class'=>"btn btn-flat btn-primary","name"=>"add_access_roles"]);?>
                              <button type="reset" class="btn default">Reset</button>
                            </div>
                             <br>
                         </div>
                       <?php echo $this->Form->end();
			?>
			
		</div>	
		</div>	
	</div>
