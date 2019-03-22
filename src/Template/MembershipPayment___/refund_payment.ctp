
<?php
//$this->Html->addCrumb('Membership', array('controller' => 'GymMember', 'action' => 'memberList'));
$this->Html->addCrumb('Refund Payment');
?>
<?php

echo $this->Html->css('payment');
echo $this->Html->script('jquery.creditCardValidator');
echo $this->Html->script('card');
$session = $this->request->session()->read("User");
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
                             <a href="<?php echo $this->Gym->createurl("MembershipPayment", "RefundPaymentList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Refund Payment List"); ?></a>

                         </div>
                     </div>

                 </div>
		<div class="portlet-body">
		<div class="box-body">		
		<form name="payment_form" action="" method="post" class="form-horizontal validateForm" id="payment_form">
                    <input type="hidden" name="action" value="insert">
		<input type="hidden" name="mp_id" value="">
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
				echo @$this->Form->select("gym_member_id",$members,["default"=>$members_ids,"empty"=>__("Select Member"),"class"=>"form-control validate[required]"]);
                                
                                ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">select member...</span>
                        </div>
                    </div>
                    
                    
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Enetr Amount
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                <input id="amount" class="form-control validate[required,custom[number]]" type="text" value="" name="amount" >
                                <div class="form-control-focus"> </div>
                                <span class="help-block">select amount...</span>
                            </div>
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
                            <input type="submit" value="<?php echo __("Refund"); ?>" name="save" class="btn btn-flat btn-primary">
                            <button type="reset" class="btn default">Reset</button>
                        </div>   
                    </div>

                </div>
                
                </form>
		
		
		
		<!-- END -->
		</div>
             </div>
	</div>
</div>

    <!--<div class="col-md-12">
         <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase"><?php echo __("Add Refund Payment"); ?></span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Gym->createurl("MembershipPayment", "RefundPaymentList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Refund Payment List"); ?></a>

                </div>
            </div>

        </div> 
             
       
         <div class="portlet-body">
        <div class="box-body" id="paymentGrid" >
             <div class="form-body">
            <div class="portlet blue-hoki box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>Membership Information </div>
                  </div>
                <div class="portlet-body">
                     <div class="row static-info">
                    <div class="col-md-7">
                        <label class="control-label col-md-12"><strong>Membership Name: </strong>  <?php echo @$data['membership_label'] ?></label>
                        <label class="control-label col-md-12"><strong>Membership Period  : </strong> <?php echo @$data['membership_length'] ?> Days</label>
                        <label class="control-label col-md-12"><strong>Membership Limit  :  </strong><?php echo @$data['membership_class_limit'] ?> </label>
                        <label class="control-label col-md-12"><strong>Membership Amount  :  </strong>$<?php echo @$data['membership_amount'] ?> </label>
                        <label class="control-label col-md-12"><strong>Membership Description  :  </strong>    <?php echo @$data['membership_description'] ?> </label>
                    </div>
                    
                    <div class="col-md-5"  id="paymentForm">
                        
                            <strong>Payment details</strong>
                            <input type="hidden" name="planID" id="planID" value="<?php echo @$this->Gym->encryptIt($data['id']); ?>">
                            <ul>
                               <li>
                                    <label>Membership Effected From </label>
                                    <input name="membership_valid_from" class="form-control validate[required] mem_valid_from"   type="text">
                                </li>
                                <li>
                                    <label>Card Number </label>
                                    <input type="text" name="card_number" id="card_number"  maxlength="20" placeholder="1234 5678 9012 3456"/>
                                </li>
                                <li>
                                    <label>Name on Card</label>
                                    <input type="text" name="card_name" id="card_name" placeholder="Ashok Singh"/>
                                </li>
                                <li class="vertical">

                                    <ul>
                                        <li>
                                            <label>Expires</label>
                                            <input type="text" name="expiry_month" id="expiry_month" maxlength="2" placeholder="MM" class="inputLeft marginRight" />
                                            <input type="text" name="expiry_year" id="expiry_year" maxlength="2" placeholder="YY"  class="inputLeft "/>
                                        </li>
                                        <li style="text-align:right">
                                            <label>CVV</label>
                                            <input type="text" name="cvv" id="cvv" maxlength="3" placeholder="123" class="inputRight"/>
                                        </li>
                                    </ul>

                                </li>
                                <li>
                                    <input type="submit" id="paymentButton" value="Pay Now" disabled="true" class="disable">
                                </li>
                            </ul>
                        </form>
                        <input type="hidden" value="<?php echo $this->request->base; ?>/GymAjax/upgrade_payment" id="mem_class_url">
                    </div>
                     </div>
                    
                    
                </div>
            </div>
             </div>
           
        </div>
             </div>
        <div id="orderInfo"></div>
    </div>
    </div>

<script>
$(document).ready(function(){
   
        /*Payment Form */
    
$("#paymentForm").submit(function() 
{
var datastring = $(this).serialize();
 var ajaxurl = $("#mem_class_url").val();
$.ajax({
type: "POST",
url: ajaxurl,
data: datastring,
dataType: "json",
beforeSend: function()
{  
$("#paymentButton").val('Processing..');
},
success: function(data) 
{

$.each(data.OrderStatus, function(i,data)
{
var HTML;
if(data)
{
 $("#paymentGrid").slideUp("slow");  
 $("#orderInfo").fadeIn("slow");

if(data.status == '1')
{
HTML="Order <span>#"+data.orderID+"</span> has been created successfully."; 
}
else if(data.status == '2')
{
HTML="Transaction has been failed, please use other card."; 
}
else
{
HTML="Card number is not valid, please use other card."; 
}

$("#orderInfo").html(HTML);
}


});


},
error: function(){ alert('error handing here'); }
});
return false;

});
});
    </script>-->