<?php
$this->Html->addCrumb('List Members', array('controller' => 'GymMember', 'action' => 'memberList'));
$this->Html->addCrumb('Assign Class');
?>
<script>
$(document).ready(function() {
//$(".mem_list_workout").select2();
$(".date").datepicker();
var box_height = $(".box").height();
var box_height = box_height + 100 ;
$(".content-wrapper").css("height",box_height+"px");

/* FETCH Activity On Page Load */

	var member_id = $(".mem_list_workout option:selected").val()	
	var ajaxurl = $("#getcategory").attr("data-url");
	var curr_data = {member_id:member_id};
	$.ajax({
		url : ajaxurl,
		type : "POST",
		data : curr_data,
		success : function(result)
		{
			$("#append").html("");			
			$("#append").append(result);			
		},
		error : function(e)
		{
			console.log(e.responseText);
		}
	});
	
/* FETCH Activity On Page Load */


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
                             <a href="<?php echo $this->Gym->createurl("GymMember", "memberList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Members List"); ?></a>

                         </div>
                     </div>

                 </div>
                 
		<div class="portlet-body">
		<div class="box-body">
		<?php
                
                 // echo "<pre>";print_r($member_data);
			echo $this->Form->create("assignMember",["class"=>"validateForm form-horizontal","role"=>"form"]);
		?>
                    <div class="form-body">
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Select Member
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group-control">
                                    <?php
                                    echo $this->Form->select("user_id", $members, ["default" => ($edit) ? $this->request->params["pass"] : "", "class" => "form-control mem_list_workout"]);
                                    ?>
                                    <input type="hidden" id="getcategory" data-url="<?php echo $this->request->base; ?>/GymAjax/getCategoriesByMember" >
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Membership
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" disabled="disabled" value="<?php echo $member_data['membership']['membership_label']; ?>">
                                <div class="form-control-focus"> </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                         <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Membership Status
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" disabled="disabled" value="<?php echo $member_data['membership_status']; ?>">
                                <div class="form-control-focus"> </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Membership Start Date
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" disabled="disabled" value="<?php echo date($this->Gym->getSettings("date_format"),strtotime($member_data['membership_valid_from'])); ?>">
                                <div class="form-control-focus"> </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Membership End Date 
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" disabled="disabled" value="<?php echo date($this->Gym->getSettings("date_format"),strtotime($member_data['membership_valid_to'])); ?>">
                                <div class="form-control-focus"> </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i><?php echo __("Assign Class Schedule");?></div>
                            </div>
                            <div class="portlet-body flip-scroll">
                                
                                <table class="table table-bordered table-striped table-condensed flip-content">
                                    <thead class="flip-content">
                                        <tr>
                                            <th>Select</th>
                                            <th width="25%">Class Name</th>
                                            <th>Days</th>
                                            <th>Start Time</th>		
                                            <th>End Time</th>
                                            			
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <?php
                                        // print_r($member_data);

                                        foreach ($schedule_list as $data) {
                                            $class_schedule_list = $data['class_schedule_list'];
                                            $clas = $data['class_name'];
                                            //print_r($membership_classes);
                                            if (@in_array($clas, $membership_classes)) {
                                                foreach ($class_schedule_list as $row) {
                                                    //echo $clas;print_r($member_class);
                                                    ?>
                                        <tr> 
                                            <td class="checkbox_field"><span><input type="checkbox" class="checkbox1" name="assign_class[]" <?php if(in_array($row['id'], $member_class)){echo "checked";}else{ echo "";} ?> value="<?php echo $data['class_name'].'-'.$row['id'];?>" ></span></td>
                                            <td><?php echo $this->Gym->get_classes_by_id($data['class_name']); ?></td>
                                            <td><span><?php echo implode(",",json_decode($row["days"])); ?> </span></td>
                                            <td><span><?php echo $row['start_time']?></span></td>
                                            <td><span><?php echo $row['end_time'];?></span></td>
                                            
                                        </tr>
                                       <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="5">    
                                                <div class="col-sm-12"> 
                                                    <input type="submit" value="Save Schedule" name="save_assign" class="btn btn-flat btn-success">
                                                     <button type="reset" class="btn default">Reset</button>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>


                        </div>
                        
                    </div>
		
                
		<?php 
		$this->Form->end();
                ?>
		
				
		<br><br>
		</div>
             </div>
		
	</div>
            </div>

