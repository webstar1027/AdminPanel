<?php
$bradcrumb = ($edit) ? 'Edit Payment Invoice' : 'Generate Payment Invoice';
$this->Html->addCrumb('Payment List', array('controller' => 'MembershipPayment', 'action' => 'paymentList'));
$this->Html->addCrumb($bradcrumb);
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script>
$(document).ready(function(){
//$(".mem_list").select2();
$(".mem_valid_from").datepicker().on("changeDate",function(ev){
				
				var ajaxurl = $("#mem_date_check_path").val();
				var date = ev.target.value;	
				var membership = $(".gen_membership_id option:selected").val();			
				if(membership != "")
				{
					var curr_data = { date : date, membership:membership};
					$(".valid_to").val("Calculatind date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response){
								// $(".valid_to").val($.datepicker.formatDate('<?php echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
								$(".valid_to").val(response);								
							}
						});
				}else{
					$(".valid_to").val("Select Membership");
				}
			});	


});
$(".mem_valid_to_custom").datepicker();
</script>

	<div class="col-md-12">	
             <div class="portlet light portlet-fit portlet-form bordered">
                 <div class="portlet-title">
                     <div class="caption">
                         <i class=" icon-layers font-red"></i>
                         <span class="caption-subject font-red sbold uppercase">Generate Payment Invoice</span>
                     </div>
                     <div class="top">

                         <div class="btn-set pull-right">
                             <a href="<?php echo $this->Gym->createurl("GymMember","viewMember/".$data['member_id']); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Membership Payment List"); ?></a>

                         </div>
                     </div>

                 </div>
		<div class="portlet-body">
		<div class="box-body">		
		<form name="payment_form" action="" method="post" class="form-horizontal validateForm" id="payment_form">
                    <input type="hidden" name="action" value="insert">
		<input type="hidden" name="mp_id" value="<?php echo $data['mp_id'];?>">
		<!--<input type="hidden" name="created_by" value="1">-->
                <div class="form-body">
                
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                           <?php
				echo @$this->Form->select("user_id",$members,["default"=>($edit)?$data['member_id']:'',"empty"=>__("Select Member"),"class"=>"form-control validate[required]",($edit)?"disabled":""]);
                                if($edit){
                                    echo $this->Form->input("",["type"=>"hidden","name"=>"user_id","label"=>false,"class"=>"form-control","value"=>$data["member_id"]]);
				}
                                ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select member...</span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Membership Plan
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo $this->Form->select("membership_id", $membership, ["default" => ($edit) ? $data["membership_id"] : "", "empty" => __("Select Membership"), "class" => "form-control input-text gen_membership_id validate[required]", "disabled", "data-url" => $this->request->base . "/GymAjax/get_amount_by_memberships"]);
                            ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select membership plan...</span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Total Amount
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                <input id="total_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit) ? $data["membership_amount"] : ""; ?>" name="membership_amount" readonly="">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">select amount...</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Membership Valid From
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input("", ["label" => false, "name" => "membership_valid_from", "class" => "form-control validate[required]", "value" => ($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data["start_date"])) : "", "readonly" => true]); ?>				
                        </div>
                        <div class="col-sm-1 text-center">	<?php echo __("To"); ?>			</div>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input("", ["label" => false, "name" => "membership_valid_to", "class" => "date form-control validate[required] valid_to", "value" => (($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data['end_date'])) : ''), "readonly" => false]);
                            ?>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Membership Status
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php if($data["mem_plan_status"]=='4')  
                                  {
                                  echo "<input type='hidden' name='mem_plan_status' value='4'>";
                                  echo "<span class='label label-info'>Unsubscribe</span>";
                                  }else { ?>
                                <select name="mem_plan_status" class="form-control input-text validate[required]">
                               <option value="">Membership Status</option>
                               <option value="0" <?php if($data["mem_plan_status"]=='0'){ echo "selected";}?>>Disable</option>
                               <option value="1" <?php if($data["mem_plan_status"]=='1'){ echo "selected";}?>>Active</option>
                               <option value="2" <?php if($data["mem_plan_status"]=='2'){ echo "selected";}?>>Upgraded</option>
                               <option value="3" <?php if($data["mem_plan_status"]=='3'){ echo "selected";}?>>Expired</option>
                             </select> 
                                  <?php } ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select membership status...</span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Payment Status
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                           <select name="payment_status" class="form-control input-text validate[required]">
                               <option value="">Payment Status</option>
                               <option value="0" <?php if($data["payment_status"]==0){ echo "selected";}?>>Unpaid</option>
                               <option value="1" <?php if($data["payment_status"]==1){ echo "selected";}?>>Paid</option>
                               <option value="2" <?php if($data["payment_status"]==2){ echo "selected";}?>>Failed</option>
                             </select> 
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select payment status...</span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <div class="col-md-offset-3 col-md-6">
                            <input type="submit" value="<?php echo __("Save"); ?>" name="save_membership_payment" class="btn btn-flat btn-primary">
                            <button type="reset" class="btn default">Reset</button>
                        </div>   
                    </div>

                </div>
                
                </form>
		
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_end_date" id="mem_date_check_path">
		
		
		
		<!-- END -->
		</div>
             </div>
	</div>
</div>
