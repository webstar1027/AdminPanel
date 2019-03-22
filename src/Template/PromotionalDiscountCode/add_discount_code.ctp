<?php
$bradcrumb = ($edit) ? 'Edit Promotional Code' : 'Add Promotional Code';
$this->Html->addCrumb('Promotional Code List', array('controller' => 'PromotionalDiscountCode', 'action' => 'index'));
$this->Html->addCrumb($bradcrumb);
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#membership').multiselect({
            includeSelectAllOption: true	
    });
    var date = new Date();
    $("#valid_till").datepicker({
        format: "<?php echo $this->Gym->get_js_dateformat($this->Gym->getSettings("date_format")); ?>",
        forceParse: false,
        startDate: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0),
        enableOnReadonly: false,
    });
     $("#valid_from").datepicker({
        format: "<?php echo $this->Gym->get_js_dateformat($this->Gym->getSettings("date_format")); ?>",
        forceParse: false,
        startDate: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0),
        enableOnReadonly: false,
    });
    var box_height = $(".box").height();
    var box_height = box_height + 500 ;
    $(".content-wrapper").css("height",box_height+"px");
});

function validate_multiselect(){		
    var specialization = $("#membership").val();
    if(specialization == null){
            alert("Select Memberships associated with this code.");
            return false;
    }else{
            return true;
    }	 		
}
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
                            <a href="<?php echo $this->Gym->createurl("PromotionalDiscountCode","index"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Promotional Code List"); ?></a>

                        </div>
                    </div>

                </div>
		<div class="portlet-body">
		<div class="box-body">	
                    
		<form class="validateForm form-horizontal" method="post" role="form" onsubmit="return validate_multiselect()">		
                <input type="hidden" id="itsId" value="<?php echo ($edit) ? $data['id'] : '';?>">
                    
               <div class="form-body">
                   
                   <div class="form-group form-md-line-input">
                       <label class="col-md-3 control-label" for="form_control_1">Promotional Code
                           <span class="required" aria-required="true">*</span>
                       </label>
                       <div class="col-md-9">
                           <input type="text" name="code" id="code" placeholder="Enter promotional code" class="form-control validate[required,custom[onlyLetterNumber],ajax[isDiscountCodeUnique1]]" value="<?php echo (($edit) ? $data['code'] : ''); ?>" maxlength="8">
                           <div class="form-control-focus"> </div>
                           <span class="help-block">Enter promotional code...</span>

                       </div>
                   </div>
                   
                   <div class="form-group form-md-line-input">
                       <label class="col-md-3 control-label" for="form_control_1">Discount Rate(%) 
                           <span class="required" aria-required="true">*</span>
                       </label>
                       <div class="col-md-9">
                           <input type="text" name="discount" placeholder="Enter discount rate" id="discount" class="form-control validate[required,custom[number]]" value="<?php echo (($edit)?$data['discount']:'');?>">
                           <div class="form-control-focus"> </div>
                           <span class="help-block">Enter discount rate...</span>

                       </div>
                   </div>
                   <div class="form-group form-md-line-input">
                       <label class="col-md-3 control-label" for="form_control_1">Credits 
                           <span class="required" aria-required="true">*</span>
                       </label>
                       <div class="col-md-9">
                           <input type="text" name="promo_credits" placeholder="Enter Credits" id="promo_credits" class="form-control validate[required,custom[number]]" value="<?php echo (($edit)?$data['promo_credits']:'');?>">
                           <div class="form-control-focus"> </div>
                           <span class="help-block">Enter Credits...</span>

                       </div>
                   </div>
                   <div class="form-group form-md-line-input">
                       <label class="col-md-3 control-label" for="form_control_1">Subscription
                           <span class="required" aria-required="true">*</span>
                       </label>
                       <div class="col-md-9">
                           <?php echo @$this->Form->select("subscription_id",$memberships,["default"=>explode(',',$data['subscription_id']),"multiple"=>"multiple","class"=>"form-control validate[required] membership_list","id"=>"membership"]);?>
                           <div class="form-control-focus"> </div>
                           <span class="help-block">Select subscription...</span>

                       </div>
                   </div>
                   
                   <div class="form-group form-md-line-input">
                       <label class="col-md-3 control-label" for="form_control_1">Validity From
                           <span class="required" aria-required="true">*</span>
                       </label>
                       <div class="col-md-9">
                           <input type="text" name="valid_from" id="valid_from" class="form-control validate[required]" value="<?php echo (($edit && isset($data['valid_from'])) ? (date($this->Gym->getSettings("date_format"), strtotime($data['valid_from']))) : ''); ?>" readonly="readonly">
                           <div class="form-control-focus"> </div>
                           <span class="help-block">validity From...</span>

                       </div>
                   </div>
                   <div class="form-group form-md-line-input">
                       <label class="col-md-3 control-label" for="form_control_1">Validity To
                           <span class="required" aria-required="true">*</span>
                       </label>
                       <div class="col-md-9">
                           <input type="text" name="valid_to" id="valid_till" class="form-control validate[required]" value="<?php echo (($edit && isset($data['valid_to'])) ? (date($this->Gym->getSettings("date_format"), strtotime($data['valid_to']))) : ''); ?>" readonly="readonly">
                           <div class="form-control-focus"> </div>
                           <span class="help-block">validity to...</span>

                       </div>
                   </div>
                   
                   <div class="form-group form-md-line-input">
                       <label class="col-md-3 control-label" for="form_control_1">Status
                           <span class="required" aria-required="true">*</span>
                       </label>
                       <div class="col-md-6">
                           <div class="md-radio-inline">
                               <div class="md-radio">
                                   <input type="radio" id="checkbox1_8" <?php echo (($edit && $data['activated'] == 1) ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="activated" class="check_limit md-radiobtn">
                                   <label for="checkbox1_8">
                                       <span></span>
                                       <span class="check"></span>
                                       <span class="box"></span> Active </label>
                               </div>
                               <div class="md-radio">
                                   <input type="radio" id="checkbox1_9" <?php echo (($edit && $data['activated'] == 0) ? "checked" : "") . ' ' . ((!$edit) ? "checked" : ""); ?> value="0" name="activated" class="check_limit md-radiobtn">
                                   <label for="checkbox1_9">
                                       <span></span>
                                       <span class="check"></span>
                                       <span class="box"></span> Inactive </label>
                               </div>

                           </div>
                       </div>
                   </div>
                   
                   <div class="form-group form-md-line-input">
                            <div class="col-md-offset-3 col-md-6">
                                <input type="submit" value="<?php echo __("Save"); ?>" name="save_discount_code" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>
                   
               </div>
              
            
                     
                </form>
		</div>
		<!-- END -->
		</div>
		
	</div>
</div>
