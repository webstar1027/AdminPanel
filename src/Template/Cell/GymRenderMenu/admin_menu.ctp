<?php
$session = $this->request->session();
$role_id = $session->read("User.role_id");
$role_name = $session->read("User.role_name");
$is_rtl = $session->read("User.is_rtl");
$pull = ($is_rtl == "1") ? "pull-left" : "pull-right";

if ($is_rtl == "1") {
    ?>

<?php } ?>



<?php
foreach ($menus as $menu) {
    $controller[] = $menu['controller'];
    $action[] = $menu['action'];
}
//echo '<pre>';print_r($controller);print_r($action);die;
?>
<style>
    
.datepicker table tr td.disabled,
.datepicker table tr td.disabled:hover {
  background: none;
  color: #999999;
  cursor: default;
}
.page-header .page-header-menu .hor-menu .navbar-nav>li>a{
    padding: 16px 12px 15px;
}
</style>
<div class="page-header-menu">
    <div class="container">
<div class="hor-menu">
    <ul class="nav navbar-nav">
       

        <li class="nav-item start <?php echo ($this->request->controller == "Dashboard") ? "active" : ""; ?>" style="<?php echo (in_array('Dashboard', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link" href="<?php echo $this->Gym->createurl("Dashboard", "index"); ?>">
                <i class="fa fa-television" aria-hidden="true"></i> <span class="title"><?php echo __('Dashboard'); ?></span></i> 
            </a>             
        </li>

        <!--<li class="nav-item <?php echo ($this->request->controller == "PheramorSubscription") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorSubscription', $controller) && in_array('subscriptionList', $action) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorSubscription", "subscription_list"); ?>">
                <i class="fa fa-user-plus" aria-hidden="true"></i> <span class="title"><?php echo __('Services & Pricing');?></span>  
            </a>			   
        </li>-->
           <li class="menu-dropdown classic-menu-dropdown  <?php echo ( $this->request->controller == "PheramorSubscription" || $this->request->controller == "PheramorSubscriptionCategory" || $this->request->action == "refundPaymentList") ? "active" : ""; ?>" style="<?php echo ( ( in_array('PheramorSubscription', $controller) || in_array('PheramorSubscriptionCategory', $controller) ) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link nav-toggle" href="javascript:void(0)">
                <i class="fa fa-user-plus"></i> <span class="title"><?php echo __("Services & Pricing"); ?></span>
                <span class="arrow"></span>
            </a>
            <ul class="dropdown-menu pull-left">
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "PheramorSubscriptionCategory") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorSubscriptionCategory', $controller) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorSubscriptionCategory", "index"); ?>">
                       <span class="title"><?php echo __('Subscription Category'); ?></span></a>
                </li>
                 <li aria-haspopup="true" class="<?php echo ($this->request->controller == "PheramorSubscription") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorSubscription', $controller) && in_array('subscriptionList', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorSubscription", "subscription_list"); ?>">
                        <span class="title"><?php echo __('Subscription List');?></span>  
                    </a>			   
                </li>
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "PromotionalDiscountCode") ? "active" : ""; ?>" style="<?php echo (in_array('PromotionalDiscountCode', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PromotionalDiscountCode", "index"); ?>">
                        <span class="title"><?php echo __('Promotional Code Management ');?></span>  
                    </a>			   
                </li>
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "DiscountCode") ? "active" : ""; ?>" style="<?php echo (in_array('DiscountCode', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("DiscountCode", "index"); ?>">
                        <span class="title"><?php echo __('Discount Code Management ');?></span>  
                    </a>			   
                </li>
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "PheramorPayment") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorPayment', $controller) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorPayment", "refundPaymentList"); ?>">
                       <span class="title"><?php echo __('Refund Payment List'); ?></span></a>
                </li>
              </ul>
        </li>
        <li class="menu-dropdown classic-menu-dropdown  <?php echo ( $this->request->controller == "PheramorUser" || $this->request->action == "index") ? "active" : ""; ?>" style="<?php echo ( ( in_array('PheramorUser', $controller) ) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link nav-toggle" href="javascript:void(0)">
                <i class="fa fa-id-card-o"></i> <span class="title"><?php echo __("Manage Members"); ?></span>
                <span class="arrow"></span>
            </a>
            <ul class="dropdown-menu pull-left">
                <li class="nav-item <?php echo ($this->request->controller == "PheramorUser") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorUser', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorUser", "index"); ?>">
                       <span class="title"><?php echo __('Manage Active Members');?></span>  
                    </a>			   
                </li>
                <li class="nav-item <?php echo ($this->request->controller == "PheramorUser") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorUser', $controller) && in_array('deletedMember', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorUser", "deletedMember"); ?>">
                       <span class="title"><?php echo __('Manage Deleted Members');?></span>  
                    </a>			   
                </li>
            </ul>
        </li>
        <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown <?php echo ($this->request->controller == "PheramorRace" || $this->request->controller == "PheramorReligion" || $this->request->controller == "PheramorTags" || $this->request->controller == "PheramorInterestArea" || $this->request->controller == "PheramorMovies") ? "active" : ""; ?>" style="<?php echo ( ( in_array('PheramorNotification', $controller) || in_array('PheramorInterestArea', $controller) ) ) ? 'display:block' : 'display:none'; ?>">
            <a href="javascript:;"> <i class="fa fa-tags" aria-hidden="true"></i> Manage Features
                <span class="arrow"></span>
            </a>
            <ul class="dropdown-menu pull-left">
                <li>
                    <div class="mega-menu-content">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="mega-menu-submenu">

                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorRace") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorRace', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorRace", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Race'); ?></span>  
                                        </a>			   
                                    </li>
                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorReligion") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorReligion', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorReligion", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Religion'); ?></span>  
                                        </a>			   
                                    </li>
                                    <!--<li class="nav-item <?php echo ($this->request->controller == "PheramorTags") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorTags', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorTags", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Tags'); ?></span>  
                                        </a>			   
                                    </li>-->
                                   <!--<li class="nav-item <?php echo ($this->request->controller == "PheramorInterestArea") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorInterestArea', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorInterestArea", "index"); ?>">
                                            <span class="title"><?php echo __('Interests Area'); ?></span>  
                                        </a>			   
                                    </li>-->
                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorMovies") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorMovies', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorMovies", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Movies'); ?></span>  
                                        </a>			   
                                    </li>
                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorMusic") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorMusic', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorMusic", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Music'); ?></span>  
                                        </a>			   
                                    </li>
                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorEvents") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorEvents', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorEvents", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Events'); ?></span>  
                                        </a>			   
                                    </li>
                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorCafe") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorCafe', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorCafe", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Cafe'); ?></span>  
                                        </a>			   
                                    </li>
                                     <li class="nav-item <?php echo ($this->request->controller == "PheramorHobbies") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorHobbies', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorHobbies", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Hobbies'); ?></span>  
                                        </a>			   
                                    </li>
                                     <li class="nav-item <?php echo ($this->request->controller == "PheramorSports") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorSports', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorSports", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Sports'); ?></span>  
                                        </a>			   
                                    </li>

                                </ul>
                            </div>

                            <div class="col-md-6">
                                <ul class="mega-menu-submenu">
                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorBooks") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorBooks', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorBooks", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Books'); ?></span>  
                                        </a>			   
                                    </li>
                                   <li class="nav-item <?php echo ($this->request->controller == "PheramorGames") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorGames', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorGames", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Games'); ?></span>  
                                        </a>			   
                                    </li>
                                   <li class="nav-item <?php echo ($this->request->controller == "PheramorDrinks") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorDrinks', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorDrinks", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Drinks'); ?></span>  
                                        </a>			   
                                    </li>
                                   <li class="nav-item <?php echo ($this->request->controller == "PheramorFood") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorFood', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorFood", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Foods'); ?></span>  
                                        </a>			   
                                    </li>
                                   <!--<li class="nav-item <?php echo ($this->request->controller == "PheramorBodyType") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorBodyType', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorBodyType", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Body Type'); ?></span>  
                                        </a>			   
                                    </li>-->
                                     <li class="nav-item <?php echo ($this->request->controller == "PheramorHashtags") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorHashtags', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorHashtags", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Hashtags'); ?></span>  
                                        </a>			   
                                    </li>
                                     <li class="nav-item <?php echo ($this->request->controller == "PheramorOrientation") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorOrientation', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorOrientation", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Sexual Orientation'); ?></span>  
                                        </a>			   
                                    </li>
<!--                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorNotification"  && ($this->request->action == "index" || $this->request->action == "addNotification")) ? "active" : ""; ?>" style="<?php echo (in_array('PheramorNotification', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorNotification", "index"); ?>">
                                            <span class="title"><?php echo __('Manage Notification Message '); ?></span>  
                                        </a>			   
                                    </li>
                                    <li class="nav-item <?php echo ($this->request->controller == "PheramorNotification"  && $this->request->action == "addCustomNotification") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorNotification', $controller) && in_array('addCustomNotification', $action) ) ? 'display:block' : 'display:none'; ?>">
                                        <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorNotification", "addCustomNotification"); ?>">
                                            <span class="title"><?php echo __('Send Custom Notification'); ?></span>  
                                        </a>			   
                                    </li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

        </li>
  
  
  <li class="menu-dropdown classic-menu-dropdown  <?php echo ( $this->request->controller == "Reports" ) ? "active" : ""; ?>" style="<?php echo ( ( in_array('Reports', $controller) ) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link nav-toggle" href="javascript:void(0)">
                <i class="icon-book-open"></i> <span class="title"><?php echo __("Reports"); ?></span>
                <span class="arrow"></span>
            </a>
            <ul class="dropdown-menu pull-left">
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "Reports") ? "active" : ""; ?>" style="<?php echo (in_array('Reports', $controller) && in_array('memberReport', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("Reports", "memberReport"); ?>">
                       <span class="title"><?php echo __('Member Reports'); ?></span></a>
                </li>
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "Reports") ? "active" : ""; ?>" style="<?php echo (in_array('Reports', $controller) && in_array('cafeReport', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("Reports", "cafeReport"); ?>">
                       <span class="title"><?php echo __('Cafe Reports'); ?></span></a>
                </li>
                 <li aria-haspopup="true" class="<?php echo ($this->request->controller == "Reports") ? "active" : ""; ?>" style="<?php echo (in_array('Reports', $controller) && in_array('productSalesReport', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("Reports", "productSalesReport"); ?>">
                       <span class="title"><?php echo __('Product Sales Report'); ?></span></a>
                </li>
                 <li aria-haspopup="true" class="<?php echo ($this->request->controller == "Reports") ? "active" : ""; ?>" style="<?php echo (in_array('Reports', $controller) && in_array('subscriptionSalesReport', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("Reports", "subscriptionSalesReport"); ?>">
                       <span class="title"><?php echo __('Subscription Sales Report'); ?></span></a>
                </li>
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "Reports") ? "active" : ""; ?>" style="<?php echo (in_array('Reports', $controller) && in_array('refundPaymentReport', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("Reports", "refundPaymentReport"); ?>">
                       <span class="title"><?php echo __('Refund Payment Reports'); ?></span></a>
                </li>
                 
              </ul>
    </li>
    
    <li class="menu-dropdown classic-menu-dropdown  <?php echo ( $this->request->controller == "PheramorImport" ) ? "active" : ""; ?>" style="<?php echo ( ( in_array('PheramorImport', $controller) ) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link nav-toggle" href="javascript:void(0)">
                <i class="fa fa-files-o"></i> <span class="title"><?php echo __("Genetic Data"); ?></span>
                <span class="arrow"></span>
            </a>
            <ul class="dropdown-menu pull-left">
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "PheramorImport") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorImport', $controller) && in_array('importGenericData', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorImport", "importGenericData"); ?>">
                       <span class="title"><?php echo __('Import User Genetic Data'); ?></span></a>
                </li>
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "PheramorImport") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorImport', $controller) && in_array('manageGenericData', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorImport", "manageGenericData"); ?>">
                       <span class="title"><?php echo __('Manage User Genetic Data'); ?></span></a>
                </li>
                <li aria-haspopup="true" class="<?php echo ($this->request->controller == "PheramorImport") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorImport', $controller) && in_array('addMachineGeneticData', $action) ) ? 'display:block' : 'display:none'; ?>">
                    <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorImport", "addMachineGeneticData"); ?>">
                       <span class="title"><?php echo __('Add Machine Genetic Data'); ?></span></a>
                </li>
            </ul>
    </li>
  
    <li class="nav-item <?php echo ($this->request->controller == "PheramorGeneralSetting") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorGeneralSetting', $controller) && in_array('saveSetting', $action) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorGeneralSetting", "saveSetting"); ?>">
                <i class="fa fa-sliders"></i> <span class="title"><?php echo __("System Settings"); ?></span>
            </a>
        </li>
        
        
        
        
         <li class="menu-dropdown classic-menu-dropdown <?php echo ($this->request->controller == "PheramorNotification" || $this->request->controller =='PheramorAppLocation') ? "active" : ""; ?>" style="<?php echo (in_array('PheramorGeneralSetting', $controller) && in_array('saveSetting', $action) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link nav-toggle" href="javascript:void(0)">
                <i class="fa fa-bell-o"></i> <span class="title"><?php echo __("Notification"); ?></span>
                <span class="arrow"></span>
            </a>
             <ul class="dropdown-menu pull-left">
                 <li class="nav-item <?php echo ($this->request->controller == "PheramorNotification" && ($this->request->action == "index" || $this->request->action == "addNotification")) ? "active" : ""; ?>" style="<?php echo (in_array('PheramorNotification', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                     <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorNotification", "index"); ?>">
                         <span class="title"><?php echo __('Manage Notification Message '); ?></span>  
                     </a>			   
                 </li>
                 <li class="nav-item <?php echo ($this->request->controller == "PheramorNotification" && $this->request->action == "addCustomNotification") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorNotification', $controller) && in_array('addCustomNotification', $action) ) ? 'display:block' : 'display:none'; ?>">
                     <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorNotification", "addCustomNotification"); ?>">
                         <span class="title"><?php echo __('Send Custom Notification'); ?></span>  
                     </a>			   
                 </li>
                 <li class="nav-item <?php echo ($this->request->controller == "PheramorAppLocation" && $this->request->action == "index") ? "active" : ""; ?>" style="<?php echo (in_array('PheramorAppLocation', $controller) && in_array('index', $action) ) ? 'display:block' : 'display:none'; ?>">
                     <a class="nav-link" href="<?php echo $this->Gym->createurl("PheramorAppLocation", "index"); ?>">
                         <span class="title"><?php echo __('Manage App Location'); ?></span>  
                     </a>			   
                 </li>
             </ul>
        </li>
       <!-- <li class="nav-item <?php echo ($this->request->controller == "GymAccessRoles") ? "active" : ""; ?>" style="<?php echo (in_array('GymAccessRoles', $controller) && in_array('AccessRolesList', $action) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link" href="<?php echo $this->Gym->createurl("GymAccessRoles", "AccessRolesList"); ?>">
                <i class="fa fa-object-group"></i> <span class="title"><?php echo __('Members Role'); ?></span> 
            </a>
        </li>-->

        <li class="nav-item <?php echo ($this->request->controller == "GymAccessright") ? "active" : ""; ?>" style="<?php echo (in_array('GymAccessright', $controller) && in_array('accessRight', $action) ) ? 'display:block' : 'display:none'; ?>">
            <a class="nav-link" href="<?php echo $this->Gym->createurl("GymAccessright", "accessRight"); ?>">
                <i class="fa fa-universal-access" aria-hidden="true"></i>  
                <span class="title"><?php echo __("Access Right"); ?></span>
            </a>
        </li>

    </ul>
</div>
</div>
</div>


