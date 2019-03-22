<?php
$bradcrumb = 'Add Card Deatils';
$this->Html->addCrumb($bradcrumb);
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
                    <a href="<?php echo $this->Gym->createurl("PheramorUser", "viewMember/" . $member_id); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Manage Card List"); ?></a> 
                </div>
            </div>

        </div>
        <div class="portlet-body">


            <div class="box-body" style="padding:10px 0px;">
                <form class="col-md-4"  id="paymentForm" method="post" style="background-color: #fff;box-shadow: none;">
                    <div class="form-body" style="padding:0px;">  
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
                 <?php 
                 $profile_data=$userdetails['pheramor_user_profile'][0];
                // echo "<pre>";print_r($profile_data);  ?>
                <div class="col-md-8" style="background: #fff; min-height: 297px;">
                    <div class="col-md-10 profile-info" style="background: #d5dcd5;">
                        <h1 class="font-green sbold uppercase"><?php echo $profile_data->first_name." ".$profile_data->last_name;?></h1>
                        <p> <?php echo $profile_data->about_me;?> </p>
                        <p>
                           <a href="javascript:;"> <i class="fa fa-child"></i> <?php echo ($profile_data['gender']==1)?'Male':'Female';?> </a>
                        </p>
                        <ul class="list-inline">
                            <li>
                                <i class="fa fa-map-marker"></i> <?php echo $profile_data->country;?> </li>
                            <li>
                                <i class="fa fa-calendar"></i> <?php echo date($this->Pheramor->getSettings("date_format"), strtotime($profile_data['dob'])); ?> </li>
                            <li>
                                <i class="fa fa-briefcase"></i> <?php echo $this->Pheramor->getRaceName($profile_data->race);?> </li>
                            <li>
                                <i class="fa fa-tags"></i> <?php echo $this->Pheramor->getReligionName($profile_data->religion);?> </li>
                            <li>
                                <i class="fa fa-heart"></i><?php echo $this->Pheramor->getShowMe($profile_data->show_me);?> </li>
                        </ul>
                    </div>
                </div>


                <!-- END -->
            </div>
        </div>
    </div>
</div>
