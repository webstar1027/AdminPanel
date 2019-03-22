<?php

echo $this->Html->css('payment');
echo $this->Html->script('jquery.creditCardValidator');
echo $this->Html->script('card');
$session = $this->request->session()->read("User");
$locationID=$this->Gym->getLocIdMemStaff($session["id"]);
?>
<style>
    
    label {
    padding: 0px;
}
</style>
    <div class="col-md-12">
         <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase"><?php echo __("Purchase Membership"); ?></span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Gym->createurl("Membership", "MembershipList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Membership List"); ?></a>

                </div>
            </div>

        </div> 
             
       
         <div class="portlet-body">
        <div class="box-body" id="paymentGrid" >
             <div class="form-body">
            <div class="portlet blue-hoki box">
               
                <div class="portlet-body">
                     <div class="row static-info">
                         
                    <div class="col-md-6">
                   
                    <div class="caption membership-information">
                       Membership Information </div>
                         <?php 
                         $discount=0;
                         if(!empty($mem_discount_data))
                         {
                            $discount=$mem_discount_data['discount_amt'];
                         }
                         ?>
         
                        <div><label class="control-label">Membership Name: <strong><?php echo $data['membership_label'] ?></strong></label>
                            <label class="control-label">Membership Period: <strong> <?php echo $data['membership_length'].' '.ucwords($data['membership_recurrence_length']); ?> </strong></label>
                        <label class="control-label">Membership Limit: <strong><?php echo $data['membership_class_limit'] ?></strong> </label>
                       </div>
                      	<div class="payment-description">
                          <h4 class="control-label">Membership Description:</h4><?php echo $data['membership_description'] ?>
                         </div>
                          <p>&nbsp;</p>
                         <div class="payment-description">
                          <div class="total"> Membership Amount:<span class="pull-right total-amount">$<?php echo ($this->Gym->get_membership_amt_lice($data['membership_amount'],$data['id'],$locationID)); ?></span></div>
                          </div>
                        <p>&nbsp;</p>
                        <div class="payment-description">
                          <div class="total"> Pay Amount:<span class="pull-right total-amount">$<?php echo ($this->Gym->get_membership_amt_lice($data['membership_amount'],$data['id'],$locationID)-$discount); ?></span></div> 
                        </div>
                    </div>
                    
                    <form class="col-md-6"  id="paymentForm">
                        <div class="card-del-status"></div>
                            <h5>Select Payment Method</h5>
                            <input type="hidden" name="planID" id="planID" value="<?php echo $this->Gym->encryptIt($data['id']); ?>">
                            <input type="hidden" name="liceID" id="liceID" value="<?php echo $this->Gym->encryptIt($licensee); ?>">
                            <ul>
                                <?php 
                                 $c=6;
                                 $cstatus='style="display:block;"';
                                 $cstatus1='style="display:none;"';
                                if(!empty($card_data)){
                                     $cstatus='style="display:none;"';
                                     $cstatus1='style="display:block;"';
                                    foreach($card_data as $cdata)
                                    { 
                                     ?>
                                     <li class="md-radio-list" id="del-<?php echo $cdata['id']?>"><div class="md-radio">
                                       <input type="radio" id="checkbox1_<?php echo $c;?>" name="card_info" checked="checked" value="<?php echo $cdata['id']?>" class="md-radiobtn">
                                       <label for="checkbox1_<?php echo $c;?>">
                                       <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span> <?php echo $cdata['maskedNumber']?> 
                                            
                                       </label>
                                       <?php if($c!=6){
                                            echo '<a class="btn btn-circle btn-icon-only btn-default card-delete" data-id="'.$cdata['id'].'">
                                        <i class="icon-trash"></i>
                                                </a>';
                                            }
                                            ?>
                                         </div>
                                        
                                         
                                             
                                        
                                         
                                         
                                     </li>
                                        
                                  <?php   $c++; }
                                }
                                ?>
                              
                                <li class="md-radio-list"><div class="md-radio">
                                       <input type="radio" id="checkbox1_0" name="card_info"  value="0" <?php if($c==6){ echo "checked='checked'";}?> class="md-radiobtn">
                                       <label for="checkbox1_0">
                                       <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Add A New Card</label>
                                         </div>
                                </li>
                                 <div id="card-status" <?php echo $cstatus;?>>
                                <li>
                                    <label>Card Number </label>
                                    <input type="text" name="card_number" id="card_number"  maxlength="20" placeholder="1234 5678 9012 3456"/>
                                </li>
                                <li>
                                    <label>Name on Card</label>
                                    <input type="text" name="card_name" id="card_name" placeholder="Card Holder Name"/>
                                </li>
                                <li class="vertical">

                                    <ul>
                                        <li>
                                            <label>Expires</label>
                                            <input type="text" name="expiry_month" id="expiry_month" maxlength="2" placeholder="MM" class="inputLeft marginRight" />
                                            <input type="text" name="expiry_year" id="expiry_year" maxlength="2" placeholder="YY"  class="inputLeft "/>
                                        </li>
                                        <li>
                                            <label>CVV</label>
                                            <input type="text" name="cvv" id="cvv" maxlength="4" placeholder="cvv" class="inputRight"/>
                                        </li>
                                    </ul>

                                </li>
                                <li>
                                    
                                    <input type="submit" id="paymentButton" value="Pay Now" disabled="true" class="disable">
                                </li>
                             </div>
                                <li id="card-status1" <?php echo $cstatus1;?>>
                                    
                                    <input type="submit" id="paymentButton" value="Pay Now" >
                                </li>
                            </ul>
                        </form>
                        <input type="hidden" value="<?php echo $this->request->base; ?>/GymAjax/upgrade_payment" id="mem_class_url">
                        <input type="hidden" value="<?php echo $this->request->base; ?>/GymAjax/delete_card" id="mem_delete_card">
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
  $('input[name="card_info"]').click(function(){
      var inputValue = $(this).attr("value");
        if(inputValue=='0')
        {
           $("#card-status").show();
           $("#card-status1").hide();
        }else{
             $("#card-status").hide();
              $("#card-status1").show();
        }
    });
 $(".card-delete").click(function(){
      var cardID = $(this).attr("data-id");
      var ajaxurls = $("#mem_delete_card").val();
      var curr_data = { card_id : cardID};
      $.ajax({
        url :ajaxurls,
        type : 'POST',
        data : curr_data,
        success : function(response){
          $(".card-del-status").html(response);
          $("#del-"+cardID).hide();
        }
     });
 });
      
  
      
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
else if(data.status == '3')
{
HTML="Currently your membership already activated you can not upgraded."; 
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
    </script>