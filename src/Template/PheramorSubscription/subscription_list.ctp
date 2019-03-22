<?php 
$session = $this->request->session()->read("User");

$this->Html->addCrumb('Subscription List');
echo $this->Html->css('assets/pages/css/pricing.min.css');
?>



<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title ">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Subscription List"); ?>
                </span>
                 
            </div>
            <div class="actions">
                <div class="tools"><a href="<?php echo $this->Gym->createurl("PheramorSubscription","add"); ?>" class="btn sbold green"><?php echo __("Add Subscription"); ?> <i class="fa fa-plus"></i></a> </div>
            </div>
        </div>
        <div class="portlet-body">

            <!--<div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <a href="<?php echo $this->Gym->createurl("PheramorSubscription","add"); ?>" class="btn sbold green"><?php echo __("Add Subscription"); ?> <i class="fa fa-plus"></i></a>
                           </div>
                    </div>
                   
                </div>
                
            </div>-->
            
            
            <div class="pricing-content-1">
                <div class="row">
                   <?php 
                    $k = 1;
                    $colors=array(0=>'grey',1=>'blue',2=>'red',3=>'green',4=>'purple',5=>'purple',6=>'green',7=>'red',8=>'blue');
                    
                    foreach ($membership_data as $membership) { 
                        if ($membership->subscription_type =='months') {
                            if($membership->subscription_length <=1){
                                $length_msg = $membership->subscription_length. ' Month';
                            }else{
                                 $length_msg = $membership->subscription_length. ' Months';
                            }
                            
                            if($membership->subscription_length==0){
                                $length_msg ='Unmilimted';
                            }
                        } else {
                           
                            $length_msg = $membership->subscription_length. ' Days';
                        }
                         if ($membership->subscription_status== 1) {
                              $status = "Active";
                              $color = $colors[$k];
                          } else {
                             $status = "Inactive";
                              $color = $colors[0];
                          } 
                          //echo $this->Pheramor->getCategoryType($membership['subscription_cat_id']); die;
                          if($this->Pheramor->getCategoryType($membership['subscription_cat_id'])=='product'){
                              $price_array= json_decode($membership['subscription_amount']);
                              //print_r($price_array); die;
                              $membership['subscription_amount']=$price_array[0];
                          }
                          
                        //echo "<pre>";print_r();
                        ?>
                    <div class="col-md-3">
                        <div class="price-column-container border-active">
                            <div class="price-table-head bg-<?php echo $color;?>">
                                <h2 class="no-margin"><?php echo $membership['subscription_title'];?></h2>
                            </div>
                            <div class="arrow-down border-top-<?php echo $color?>"></div>
                            <div class="price-table-pricing">
                                <h3>
                                    <sup class="price-sign">$</sup><?php echo $membership['subscription_amount']?></h3>
                                <p><?php echo $length_msg;?><?php //echo $membership['subscription_title'];?></p>
                                <?php if($membership['subscription_cat_id']=='9'){ ?>
                                <div class="price-ribbon">Popular</div>
                                <?php } ?>
                            </div>
                            <div class="price-table-content">
                                <div class="row mobile-padding">
                                    <div class="col-xs-3 text-right mobile-padding">
                                        <i class="icon-book-open"></i>
                                    </div>
                                    <div class="col-xs-9 text-left mobile-padding"><?php echo $length_msg;?></div>
                                </div>
                                <div class="row mobile-padding">
                                    <div class="col-xs-3 text-right mobile-padding">
                                        <i class=" icon-docs"></i>
                                    </div>
                                    <div class="col-xs-9 text-left mobile-padding"><?php echo $membership['pheramor_subscription_category']['category_name'];?></div>
                                </div>
                                <div class="row mobile-padding">
                                    <div class="col-xs-3 text-right mobile-padding">
                                        <i class="icon-basket"></i>
                                    </div>
                                    <div class="col-xs-9 text-left mobile-padding">$ <?php echo $membership['subscription_amount']?></div>
                                </div>
                                <div class="row mobile-padding">
                                    <div class="col-xs-3 text-right mobile-padding">
                                        <i class="icon-check"></i>
                                    </div>
                                    <div class="col-xs-9 text-left mobile-padding"><?php echo $status;?></div>
                                </div>
                            </div>
                            <div class="arrow-down arrow-grey"></div>
                            <div class="price-table-footer">
                               <a href="<?php echo $this->Pheramor->createurl("PheramorSubscription", "editSubscription/".$membership->id);?>" class="btn green price-button sbold uppercase">Update</a>
                               <a href="<?php echo $this->Pheramor->createurl("PheramorSubscription", "deleteSubscription/".$membership->id);?>" onclick="return confirm('Are you sure,you want to delete this record?');" class="btn red price-button sbold uppercase">Delete</a>
                            </div>
                        </div>
                    </div>
                    
                    <?php  $k++; 
                    
                    }?>
                    
                </div>
            </div>
            
            <!--<table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    

                    <tr>
                        <th><?php echo __("S.N."); ?></th>
                        <th><?php echo __("Name"); ?></th>
                        <th><?php echo __("Category"); ?></th>	
                        <th><?php echo __("Duration"); ?></th>
                        <th><?php echo __("Amount"); ?></th>
                        <th><?php echo __("Status"); ?></th>
                        <th><?php echo __("Action"); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                   <?php
                     //echo "<pre>"; print_r($membership_data); die;
                    $k = 1;
                    foreach ($membership_data as $membership) {
                       if ($membership->subscription_status== 1) {
                              $status = "<span class='label label-success'>Acive</span>";
                          } else {
                             $status = "<span class='label label-warning'>Inactive</span>";
                          } 
                        if ($membership->subscription_length == 1) {
                            $length_msg = 'Month on Month';
                        } else {
                            $length_msg = $membership->subscription_length . ' ' . ucwords($membership->subscription_type);
                        }
                        echo "<tr class='gradeX odd'><td>{$k}</td><td>{$membership->subscription_title}</td><td>{$membership->pheramor_subscription_category->category_name}</td><td>{$length_msg}</td>";
                         echo "<td>" . $this->Pheramor->get_currency_symbol() . $membership->subscription_amount . "</td>";
                         echo "<td>" . $status . "</td>";
                         echo "<td>";
                            echo "<div class='btn-group'>
                                                <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                    <i class='fa fa-angle-down'></i>
                                                </button>
                                                <ul class='dropdown-menu pull-right' role='menu'>
                                                    <li>
                                                        <a href='javascript:void(0)' id='{$membership->id}' data-url='" . $this->request->base . "/PheramorAjax/view-subscription' class='view_jmodal' >
                                                           <i class='icon-eye'></i> View Membership</a>
                                                    </li>
                                                    <li>
                                                        <a href='{$this->Pheramor->createurl("PheramorSubscription", "editSubscription")}/{$membership->id}'>
                                                            <i class='icon-pencil'></i> Edit Subscription </a>
                                                    </li>
                                                    <li>
                                                        <a href='{$this->Pheramor->createurl("PheramorSubscription", "deleteSubscription")}/{$membership->id}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                            <i class='icon-trash'></i> Delete Subscription </a>
                                                    </li>
                                                  
                                                    </ul>
                                                    </div>
                                                    </td>
						</tr>";
                        $k++;
                    }
                    ?>

                    


                </tbody>
            </table>-->
        </div>
    </div>

    
    
    	
    
</div>
<script>
    $('#search_loc').change(function(){
        $('#search_licensee_frm').submit();
    });
        
    $("[name='membership_enable_disable']").bootstrapSwitch({
        size : 'small',
        onColor : 'success',
        offColor : 'warning',
        handleWidth : 100,
        onText : "Enabled",
        offText : "Disabled",
        data:{'name':'jameel'},

        onSwitchChange : function(event, state){
            console.log('event: ',event.target.value);
            console.log('state: ',state);
            $.ajax({
                type: "POST",
                url: "<?php echo $this->request->base . '/GymAjax/membershipDisableEnableForLocation'; ?>",
                data: {mId: event.target.value, state: state},
                dataType: "JSON",
                beforeSend: function () {
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $("#member_for").empty().append(response.data);
                    } else if (response.status == 'error') {
                        $("#member_for").empty().append("<option value=''>Select Member</option>");
                        alert(response.msg);
                    }
                    return false;
                },
                error: function (jqXHR, exception) {
                    return false;
                }
            });
        }
    });
    
    function disableMembershipPlan(id){
        if(confirm('Are you sure?')){
            window.location.href='<?php echo $this->Gym->createurl("Membership","disableMembershipPlan");?>/'+id;
        }
    }
</script>

