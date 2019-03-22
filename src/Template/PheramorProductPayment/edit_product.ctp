<?php
$bradcrumb = ($edit) ? 'Edit Payment Product' : 'Add Payment Product';
$this->Html->addCrumb('Payment List', array('controller' => 'PheramorUser', 'action' => 'viewMember/'.$data['user_id']));
$this->Html->addCrumb($bradcrumb);
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
//print_r($data);
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
                         <span class="caption-subject font-red sbold uppercase">Edit Payment Subscription</span>
                     </div>
                     <div class="top">

                         <div class="btn-set pull-right">
                             <a href="<?php echo $this->Gym->createurl("PheramorUser","viewMember/".$data['user_id']); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Product Payment List"); ?></a>

                         </div>
                     </div>

                 </div>
		<div class="portlet-body">
		<div class="box-body">		
		<form name="payment_form" action="" method="post" class="form-horizontal validateForm" id="payment_form">
                    <input type="hidden" name="action" value="insert">
		<input type="hidden" name="id" value="<?php echo $data['id'];?>">
		<!--<input type="hidden" name="created_by" value="1">-->
                <div class="form-body">
                
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                           <?php
				echo @$this->Form->select("user_id",$members,["default"=>($edit)?$data['user_id']:'',"empty"=>__("Select Member"),"class"=>"form-control validate[required]",($edit)?"disabled":""]);
                                if($edit){
                                    echo $this->Form->input("",["type"=>"hidden","name"=>"user_id","label"=>false,"class"=>"form-control","value"=>$data["user_id"]]);
				}
                                ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select member...</span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Product Name
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo $this->Form->select("subscription_id", $membership, ["default" => ($edit) ? $data["product_id"] : "", "empty" => __("Select Membership"), "class" => "form-control input-text gen_membership_id validate[required]", "disabled", "data-url" => $this->request->base . "/PheramorAjax/get_amount_by_memberships"]);
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
                                <input id="total_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit) ? $data["product_amount"] : ""; ?>" name="subscription_amount" readonly="">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">select amount...</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Discount Amount
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                <input id="discount_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit) ? $data["discount_amount"] : ""; ?>" name="discount_amount" readonly="" >
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter discount amount...</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Paid Amount
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                <input id="paid_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit) ? $data["paid_amount"] : ""; ?>" name="paid_amount" readonly="">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter paid amount...</span>
                            </div>
                        </div>
                    </div>
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Payment Status
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                           <select name="payment_status" class="form-control input-text validate[required]">
                               <option value="">Payment Status</option>
                               
                               <option value="1" <?php if($data["payment_status"]==1){ echo "selected";}?>>Paid</option>
                               <option value="0" <?php if($data["payment_status"]==0){ echo "selected";}?>>Failed</option>
                             </select> 
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select payment status...</span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <div class="col-md-offset-3 col-md-6">
                            <input type="submit" value="<?php echo __("Submit"); ?>" name="save_membership_payment" class="btn btn-flat btn-primary">
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

<script>
$(document).ready(function(){

$('#discount_amount').keyup(function() {
 var amount=$(this).val();
var refundeed_amount = $("#total_amount").val();
var pending=refundeed_amount-amount;
var pending1=pending.toFixed(2);
if(pending >=0) { $("#paid_amount").val(pending1);} else{$("#paid_amount").val('0');}
});

/// Discount amount set
$('#paid_amount').keyup(function() {
 var amount=$(this).val();
var refundeed_amount = $("#total_amount").val();
var pending=refundeed_amount-amount;
var pending1=pending.toFixed(2);
if(pending >=0) { $("#discount_amount").val(pending1);} else{$("#discount_amount").val('0');}
});



 });

</script>