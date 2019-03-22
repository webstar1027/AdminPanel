<?php 
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.day_list').multiselect({
		includeSelectAllOption: true	
	});
	// $(".dob").datepicker({format: '<?php echo $this->Gym->getSettings("date_format"); ?>'});
	$(".dob").datepicker({format: '<?php echo $this->Gym->getSettings("date_format"); ?>'});
        $(".date").datepicker({format:'<?php echo $this->Gym->getSettings("date_format"); ?>'});
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo $title;?>
				<small><?php echo __("Class Schedule");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("ClassSchedule","classList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Class Schedule List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
               
			echo $this->Form->create("addClass",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Class Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo @$this->Form->select("class_name",$classes,["default"=>$data['class_name'],"empty"=>__("Select Class Name"),"class"=>"form-control validate[required]"]);
			echo "</div>";	
			echo "</div>";	
                        echo "<fieldset><legend>". __('Schedule Information')."</legend>";
			echo "<div class='form-group'>";
                        echo '<label class="control-label col-md-2" for="start_date">'. __("Start Date").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"placeholder" =>"Enter Start Date","name"=>"start_date","class"=>"date validate[required] form-control","value"=>(($edit)?date('Y-m-d',strtotime($data['start_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Days").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			$days = ["Sunday"=>__("Sunday"),"Monday"=>__("Monday"),"Tuesday"=>__("Tuesday"),"Wednesday"=>__("Wednesday"),"Thursday"=>__("Thursday"),"Friday"=>__("Friday"),"Saturday"=>__("Saturday")];
			echo @$this->Form->select("days",$days,["default"=>json_decode($data['days']),"multiple"=>"multiple","class"=>"form-control validate[required] day_list"]);
			echo "</div>";				
			echo "</div>";	
			
			$hrs = ["0","1","2","3","4","5","6","7","8","9","10","11","12"];
			$min = ["00"=>"00","15"=>"15","30"=>"30","45"=>"45"];
			$ampm = ["AM"=>"AM","PM"=>"PM"];
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Start Time").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-2">';			
			echo @$this->Form->select("start_hrs",$hrs,["default"=>$data['start_hrs'],"empty"=>__("Select Time"),"class"=>"start_hrs form-control validate[required]"]);
			echo "</div>";	
			echo '<div class="col-md-2">';			
			echo @$this->Form->select("start_min",$min,["default"=>$data['start_min'],"class"=>"start_min form-control"]);
			echo "</div>";
			echo '<div class="col-md-2">';			
			echo @$this->Form->select("start_ampm",$ampm,["default"=>$data['start_ampm'],"class"=>"start_ampm form-control"]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("End Time").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-2">';			
			echo @$this->Form->select("end_hrs",$hrs,["default"=>$data['end_hrs'],"empty"=>__("Select Time"),"class"=>"end_hrs form-control validate[required]"]);
			echo "</div>";	
			echo '<div class="col-md-2">';			
			echo @$this->Form->select("end_min",$min,["default"=>$data['end_min'],"class"=>"end_min form-control"]);
			echo "</div>";
			echo '<div class="col-md-2">';			
			echo @$this->Form->select("end_ampm",$ampm,["default"=>$data['end_ampm'],"class"=>"end_ampm form-control"]);
			echo "</div>";
			echo "</div>";
		        echo $this->Form->button(__("Add Time"),['type'=>'button','id'=>'add_time','class'=>"btn btn-flat btn-success col-md-offset-2","name"=>"add_class"]);
			echo "<br><br>";
			echo "<div class='time_list col-md-10 col-md-offset-2'>";?>
			<table class="table">
				<tr><th><?php echo __("Days");?></th><th><?php echo __("Start Time");?></th><th><?php echo __("End Time");?></th><th><?php echo __("Action");?></th></tr>
				<tbody class="time_table">
					<?php
					if($edit)
					{
						foreach($schedule_list as $schedule)
						{?>
							<tr>
								<td><?php echo implode(",",json_decode($schedule["days"]));?></td>
								<td><?php echo $schedule["start_time"];?></td>
								<td><?php echo $schedule["end_time"];?>
								<input type="hidden" name="time_list[]" value='[<?php echo $schedule["days"].",&quot;".$schedule["start_time"]."&quot;,&quot;".$schedule["end_time"] ."&quot;"; ?>]'>								
								</td>								
								<td>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-danger class_sch_del_row"><i class="fa fa-times-circle"></i></span></td>
							</tr>							
					<?php }
					}?>
				</tbody>
			</table>
			<?php
			echo "</div>";			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="end_date">'. __("End Date").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"placeholder" =>"Enter End Date","name"=>"end_date","class"=>"date validate[required] form-control","value"=>(($edit)?date('Y-m-d',strtotime($data['end_date'])):'')]);
			echo "</div>";	
			echo "</div>";
                         echo "<fieldset><legend>". __('Staff Information')."</legend>";
                        echo "<div class='form-group'>";
                        echo '<label class="control-label col-md-2" for="email">'. __("Select Staff Member").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("assign_staff_mem",$staff,["default"=>$data['assign_staff_mem'],"empty"=>__("Select Staff Member"),"class"=>"form-control validate[required]"]);
			echo "</div>";	
		        echo '<div class="col-md-2">';
                        echo "<a href='{$this->request->base}/StaffMembers/addStaff' class='btn btn-flat btn-primary'>".__("Add")."</a>";
                        echo "</div>";	
			echo "</div>";
                        echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="pay_rate">'. __("Pay Rate").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"placeholder" =>"Enter Pay rate","name"=>"pay_rate","class"=>"validate[required] form-control","value"=>(($edit)?$data['pay_rate']:'')]);
			echo "</div>";	
			echo "</div>";
                       
                        echo "<fieldset><legend>". __('Location Information')."</legend>";
                        echo "<div class='form-group'>";
                        echo '<label class="control-label col-md-2" for="location_id">'. __("Select Location").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("location_id",$location,["default"=>$data['location_id'],"empty"=>__("Select Location"),"class"=>"form-control validate[required] location_list"]);
			echo "</div>";	
			
			if($this->request->session()->read("User.role_name") == "administrator" || $this->request->session()->read("User.role_name") == "licensee")
			{
                            echo '<div class="col-md-2">';
                            //echo "<a href='{$this->request->base}/StaffMembers/addStaff' class='btn btn-flat btn-primary'>".__("Add")."</a>";
                            echo "<a href='javascript:void(0)' class='add-location btn btn-flat btn-success' data-url='{$this->Gym->createurl("GymAjax","addLocation")}'>".__("Add/Remove")."</a>";
                            echo "</div>";	
				
			}
			echo "</div>";
                        
                        echo "<fieldset><legend>". __('Class Size Information')."</legend>";
                        echo "<div class='form-group'>";
                        echo '<label class="control-label col-md-2" for="total_capacity">'. __("Total Capacity").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo $this->Form->input("",["label"=>false,"placeholder" =>"Enter Total Capacity","name"=>"total_capacity","class"=>"validate[required] form-control","value"=>(($edit)?$data['total_capacity']:'')]);
			echo "</div>";	
			echo "</div>";
                        echo "<div class='form-group'>";
                        echo '<label class="control-label col-md-2" for="wait_list">'. __("How Many Can Waitlist?").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo $this->Form->input("",["label"=>false,"placeholder" =>"Enter Waitlist","name"=>"wait_list","class"=>"validate[required] form-control","value"=>(($edit)?$data['wait_list']:'')]);
			echo "</div>";	
			echo "</div>";
                        
                        echo "<fieldset><legend>". __('Online Options')."</legend>";
                        ?>
                       <div class="form-group">
				<div class="control-label col-md-2">
					<label><?php echo __("Online Scheduling");?></label>
				</div>
				<div class="col-md-6">
					<label class="radio-inline"><input type="radio" name="online_schedule" value="1" class="membership_status_type" <?php echo ($edit && $data['online_schedule'] == 1) ? "checked":"checked";?>><?php echo __("Yes, allow clients to sign up for this class online");?></label>
					<label class="radio-inline"><input type="radio" name="online_schedule" value="0" class="membership_status_type" <?php echo ($edit && $data['online_schedule'] == 0) ? "checked":"";?>><?php echo __("No");?></label>
					
				</div>
			</div>	
                       
                        <?php
                        
                        echo "<div class='form-group'>";
                        echo '<label class="control-label col-md-2" for="online_capacity">'. __("Online Capacity").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo $this->Form->input("",["label"=>false,"placeholder" =>"Enter Online Capacity","name"=>"online_capacity","class"=>"validate[required] form-control","value"=>(($edit)?$data['online_capacity']:'')]);
			echo "<small>Clients can sign up for this class online</small></div>";	
			echo "</div>";
                        
                        echo "<fieldset><legend>". __('Other Information')."</legend>";
                        ?>
                       <div class="form-group">
				<div class="control-label col-md-2">
					<label><?php echo __("Can clients sign up unpaid?");?></label>
				</div>
				<div class="col-md-6">
					<label class="radio-inline"><input type="radio" name="signup_unpaid" value="1" class="membership_status_type" <?php echo ($edit && $data['signup_unpaid'] == 1) ? "checked":"checked";?>><?php echo __("Yes, allow clients to sign up now and pay later");?></label>
					<label class="radio-inline"><input type="radio" name="signup_unpaid" value="0" class="membership_status_type" <?php echo ($edit && $data['signup_unpaid'] == 0) ? "checked":"";?>><?php echo __("No");?></label>
					
				</div>
			</div>	
			<div class="form-group">
				<div class="control-label col-md-2">
					<label><?php echo __("Is this a free class?");?></label>
				</div>
				<div class="col-md-6">
					<label class="radio-inline"><input type="radio" name="free_class" value="1" class="membership_status_type" <?php echo ($edit && $data['free_class'] == 1) ? "checked":"checked";?>><?php echo __("Yes, clients can attend this class for free");?></label>
					<label class="radio-inline"><input type="radio" name="free_class" value="0" class="membership_status_type" <?php echo ($edit && $data['free_class'] == 0) ? "checked":"";?>><?php echo __("No");?></label>
					
				</div>
			</div>	
                       <?php
                       
                       echo "<hr>";
			
			echo $this->Form->button(__("Save Class"),['class'=>"btn btn-flat btn-success col-md-offset-2","name"=>"add_class"]);
			echo $this->Form->end();
		?>
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
<?php 
if(!$edit)
{?>
	<script>
		$(".time_list").css("display","none");
	</script>
<?php }
?>

<script>

$("#add_time").click(function(){	
	var time_list = [];
	var days = $(".day_list").val();
	if(days == null || $(".start_hrs").val() == "" || $(".end_hrs").val() == "")
	{
		alert("Please select days,start time and end time");
		return false;
	}
	$(".time_list").css("display","block");
	var json_days =  JSON.stringify(days);	
	var start_time = $(".start_hrs").val() + ":" +  $(".start_min").val() + ":" +  $(".start_ampm").val();	
	var end_time = $(".end_hrs").val() + ":" +  $(".end_min").val() + ":" +  $(".end_ampm").val();	
	time_list[0] = days;
	time_list[1] = start_time;
	time_list[2] = end_time;
	var val = JSON.stringify(time_list);	
	
	/* $(".time_list").append("<input type='text' name='time_list[]' class='ssd' value='"+val+"'>"); */
	
	$(".time_table").append('<tr><td>'+days+'</td><td>'+start_time+'</td><td>'+end_time+'<input type="hidden" name="time_list[]" value='+val+'></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-danger class_sch_del_row"><i class="fa fa-times-circle"></i></span></td></tr>');
	
});

$(document).ready(function(){
	$("body").on("click",".class_sch_del_row",function(){		
		$(this).parents("tr").remove();
	});	
});


</script>
