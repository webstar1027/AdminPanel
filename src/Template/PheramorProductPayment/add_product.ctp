<?php
$bradcrumb = ($edit) ? 'Edit Payment Product' : 'Add Payment Product';
$this->Html->addCrumb('Product Payment List', array('controller' => 'PheramorUser', 'action' => 'viewMember/' . $members_ids));
$this->Html->addCrumb($bradcrumb);
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');

?>
<script>
    $(document).ready(function () {
//$(".mem_list").select2();
        $(".mem_valid_from").datepicker().on("changeDate", function (ev) {

            var ajaxurl = $("#mem_date_check_path").val();
            var date = ev.target.value;
            var membership = $(".gen_membership_id option:selected").val();
            var user_id = $("#user_id").val();
            var licensee_id = $("#associated_licensee").val();
            if (membership != "")
            {
                var curr_data = {date: date, membership: membership, user_id: user_id};
                $(".valid_to").val("Calculatind date..");
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: curr_data,
                    success: function (response) {
                        // $(".valid_to").val($.datepicker.formatDate('<?php echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
                        //$(".valid_to").val(response);
                        var res = response.split('@@');
                        $(".valid_to").val(res[0]);
                        $("#dprice").show();
                        $("#paid_amount_input").val(res[1]);
                        $("#paid_amount").html(res[1]);
                    }
                });
            } else {
                $(".valid_to").val("Select Membership");
            }
        });


    });
</script>
<style>
    .mt-element-ribbon .ribbon{
        margin-top: 0px;
    }
</style>
<div class="col-md-12">	
    <div class="portlet light portlet-fit portlet-form bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase">Add Payment Product</span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Gym->createurl("PheramorUser", "viewMember/" . $members_ids); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("View Member"); ?></a>

                </div>
            </div>

        </div>
        <div class="portlet-body">
            <div class="box-body">		
                <form name="payment_form" action="" method="post" class="form-horizontal validateForm" id="payment_form">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="mp_id" value="0">

                    <div class="form-body">

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Member
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                 <div class="col-md-9">
                                <?php
                                // echo $members_ids;
                                echo @$this->Form->select("user_id", $members, ["default" => $members_ids, "empty" => __("Select Member"), "id" => "user_id", "class" => "form-control validate[required]", "disabled"]);
                               
                                ?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">select member...</span>
                                 </div>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Product
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                 <div class="col-md-9">
                                <?php
                                echo $this->Form->select("subscription_id", $membership, ["default" => ($edit) ? $data["subscription_id"] : "", "empty" => __("Select Product"), "class" => "form-control input-text gen_membership_id validate[required]", "data-url" => $this->request->base . "/PheramorAjax/get_amount_by_memberships"]);
                                ?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">select product...</span>
                                 </div>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Total Amount
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                 <div class="col-md-9">
                                <div class="input-group">
                                    <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                    <input id="total_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit) ? $data["subscription_amount"] : ""; ?>" name="subscription_amount" readonly="">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block">select membership...</span>
                                </div>
                                 </div>
                            </div>
                        </div>

                       <div id="discount_div" style="display:none;">
                        <!--<input type="hidden" name="paid_amount_input" id="paid_amount_input" value="">-->
                        <!--<div class="form-group form-md-line-input" >
                            <label class="col-md-3 control-label" for="form_control_1">Paid Amount
                                <span class="required" aria-required="true"></span>
                            </label> 
                            <div class="col-sm-3"><div class="mt-element-ribbon bg-grey-steel">
                                    <div class="ribbon ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">$  <span id="paid_amount"></span></div>
                                    </div></div>

                        </div>-->
                        <input type="hidden" name="discount_code_input" id="discount_code_input" value="">
                        <div class="form-group form-md-line-input" id="discount-prod-div">
                            <label class="col-md-3 control-label" for="form_control_1">Discount Code
                                <span class="required" aria-required="true"></span>
                            </label> 
                            <div class="col-sm-3" style="padding-left: 20px;"><div class="mt-element-ribbon bg-grey-steel">
                                    <div class="ribbon ribbon-round ribbon-border-dash ribbon-color-warning uppercase">  <span id="discount-prod-label"> --- </span></div>
                                    </div></div>

                        </div>
                         <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Discount Amount
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                <input id="discount_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit) ? $data["discount_amount"] : "0"; ?>" name="discount_amount" >
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter discount amount...</span>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Paid Amount
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                            <div class="input-group">
                                <span class='input-group-addon '><?php echo $this->Gym->get_currency_symbol(); ?></span>
                                <input id="paid_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit) ? $data["paid_amount"] : ""; ?>" name="paid_amount">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter paid amount...</span>
                            </div>
                        </div>
                        </div>
                    </div>
                        
                       </div> 

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Select Payment Method
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <select name="payment_method" id="payment_method" class="form-control validate[required]">
                                        <option value="">Select Payment Type</option>
                                        <option value="Card">Credit Card</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <div class="input-group" id="card-div" style="display:none;">
                                    <?php
                                    $c = 0;
                                    if (!empty($card_data)) {
                                        echo '<ul style="list-style: none;padding:0;">';
                                        $c = 1;
                                        foreach ($card_data as $cdata) {
                                            ?>
                                            <li class="md-radio-list"><div class="md-radio">
                                                    <input type="radio" id="checkbox1_<?php echo $c; ?>" name="card_info" checked="checked" value="<?php echo $cdata['id'] ?>" class="md-radiobtn">
                                                    <label for="checkbox1_<?php echo $c; ?>">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> <?php echo $cdata['maskedNumber'] ?> 
                                                    </label>
                                                </div>
                                            </li>


                                            <?php
                                            $c++;
                                        }
                                        echo ' </ul>';
                                    }
                                    ?>
                                    <input type="hidden" name="flag" id="flag" value="<?php echo $c; ?>">
                                </div>
                            </div>
                        </div>


                        <div class="form-group form-md-line-input" id="btn-div">
                            <div class="col-md-offset-3 col-md-6">
                                <input type="submit" value="<?php echo __("Submit"); ?>" name="save_membership_payment" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>

                    </div>

                </form>

                <input type="hidden" value="<?php echo $this->request->base; ?>/PheramorAjax/get_membership_end_date_price" id="mem_date_check_path">



                <!-- END -->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
          $("#btn-div").hide();
        $("#payment_method").on('change', function () {

            var lic = $("#payment_method").val();
           
            if(lic == 'Card')
            {
                $("#card-div").show();
                var cnt = $("#flag").val();
                if (cnt > 0)
                {
                    $("#btn-div").show();
                } else {
                    $("#btn-div").hide();
                }
            }else if(lic == ''){
                $("#btn-div").hide();
                 $("#card-div").hide();
            }else {
                $("#card-div").hide();
                $("#btn-div").show();
            }

        });
        
        ///
         $("body").on("change",".gen_membership_id",function(e){
             var mid = $(this).val();
             e.preventDefault(); // avoid to execute the actual submit of the form.
           
            if($(this).val()!=""){
               
                 $("#discount_div").css("display","block");
                  $('#discount_amount').val('0');
                 
             }else{
                $("#discount_div").hide(); 
             }
            // setTimeout(function(){ $("#paid_amount").val($("#total_amount").val()); }, 500);
           
         });
       
       ///
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