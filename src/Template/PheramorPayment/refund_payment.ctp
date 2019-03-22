
<?php
$this->Html->addCrumb('Refund Payment', array('controller' => 'PheramorPayment', 'action' => 'refundPaymentList'));
$this->Html->addCrumb('Add Refund');
?>
        <div class="col-md-12">	
             <div class="portlet light portlet-fit portlet-form bordered">
                 <div class="portlet-title">
                     <div class="caption">
                         <i class=" icon-layers font-red"></i>
                         <span class="caption-subject font-red sbold uppercase">Add Refund Payment</span>
                     </div>
                     <div class="top">

                         <div class="btn-set pull-right">
                             <a href="<?php echo $this->Gym->createurl("PheramorPayment", "RefundPaymentList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Refund Payment List"); ?></a>

                         </div>
                     </div>

                 </div>
		<div class="portlet-body">
		<div class="box-body">		
		<form name="payment_form" action="" method="post" class="form-horizontal validateForm" id="payment_form">
                    <input type="hidden" name="action" value="insert">
		<input type="hidden" name="mp_id" value="<?php echo $mpID;?>">
              <input type="hidden" name="subscription_id" value="<?php echo $subscription_id;?>">
		<!--<input type="hidden" name="created_by" value="1">-->
                <div class="form-body">
                
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Refund Mode
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                           <span class="label label-sm label-success"><?php echo $payment_method;?> </span>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select member...</span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Member
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                           <?php
                             echo @$this->Form->select("user_id",$members,["default"=>$members_ids,"empty"=>__("Select Member"),"class"=>"form-control validate[required]",($edit)?"disabled":""]);
                                if($edit){
                                    echo $this->Form->input("",["type"=>"hidden","name"=>"user_id","label"=>false,"class"=>"form-control","value"=>$members_ids]);
				}
			 
                                ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select member...</span>
                        </div>
                    </div>
                    
                   
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Enter Amount
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                <input id="amount" class="form-control validate[required,custom[number],ajax[maxRefundAmount]]" type="text" value="" name="amount" >
                                
                                
                            </div>
                            <div><small style='color:red;'>Remaining refund amount : $ <span id="rem-amt"><?php echo $refunded_amount;?></span></small></div>
                        </div>
                    </div>
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Comments
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9 form">
                              <?php  echo $this->Form->textarea("comments", ["rows" => "15", "class" => "wysihtml5 form-control", "value" => ""]); ?>
                                 
                               <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Description...</span>
                        </div>
                    </div>
                    
                   
                   
                    
                    <div class="form-group form-md-line-input">
                        <div class="col-md-offset-3 col-md-6">
                            <input type="submit" value="<?php echo __("Submit"); ?>" name="save" class="btn btn-flat btn-primary">
                            <button type="reset" class="btn default">Reset</button>
                        </div>   
                    </div>

                </div>
                 <input type="hidden" name="mp_id" id="mp_id" value="<?php echo $mpID; ?>">
                 <input type="hidden" name="refundeed_amount" id="refundeed_amount" value="<?php echo $refunded_amount;?>">
                </form>
		
		
		
		<!-- END -->
		</div>
             </div>
	</div>
</div>
<script>
$(document).ready(function(){

$('#amount').keyup(function() {
var amount=$(this).val();
var refundeed_amount = $("#refundeed_amount").val();
var pending=refundeed_amount-amount;
var pending1=pending.toFixed(2);
if(pending >=0) { $("#rem-amt").html(pending1);} else{$("#rem-amt").html('0');}

});

 });

</script>
  