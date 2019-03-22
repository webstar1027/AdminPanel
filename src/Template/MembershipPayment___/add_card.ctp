<?php
$bradcrumb = 'Add Client Card';

?>
<?php

echo $this->Html->css('payment');
echo $this->Html->script('jquery.creditCardValidator');
echo $this->Html->script('card');
$session = $this->request->session()->read("User");
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
                         <span class="caption-subject font-red sbold uppercase">Add Card Information</span>
                     </div>
                     <div class="top">

                         <div class="btn-set pull-right">
                             <?php if($flag==1){?>
                             <a href="<?php echo $this->Gym->createurl("Licensee","viewLicensee/".$member_id); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Manage Card List"); ?></a>
                             <?php } else{?>
                                 <a href="<?php echo $this->Gym->createurl("GymMember","viewMember/".$member_id); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Manage Card List"); ?></a> 
                           <?php  }?>
                         </div>
                     </div>

                 </div>
		<div class="portlet-body">
		<div class="box-body" style="padding:10px 0px;">		
		<form class="col-md-6"  id="paymentForm" method="post">
                      
                           <div class="form-body">  
                            <ul>
                              
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
                                    
                                    <input type="submit" id="paymentButton" value="Save Card" disabled="true" class="disable">
                                </li>
                             
                               
                            </ul>
                           </div>
                        </form>
		
		
		
		
		<!-- END -->
		</div>
             </div>
	</div>
</div>
