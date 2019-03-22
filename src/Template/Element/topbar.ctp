<?php $session = $this->request->session(); 

$user_img = (!empty($session->read("User.profile_img"))) ? $session->read("User.profile_img") : $this->request->base."/upload/profile-placeholder.png";
  $baseUrl = $this->request->base;
   $dashboardUrl = $baseUrl."/dashboard/admin-dashboard";
?>
<style>
    
    .page-header .page-header-top .page-logo .logo-default {
    margin: 3.5px 0 0;
}
</style>
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-top">
        <div class="container">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <?php $logo = $session->read("User.logo"); 
            if(empty($logo)){
                $logo=$this->request->webroot."upload/logo.png";
            }
            ?>
            <a href="<?php echo $dashboardUrl; ?>">
                <?php echo "<img src='{$logo}' style='height:70px' class='logo-default img-responsive'>"; ?>
            </a>  
            
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:void(0)" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        
         <div class="top-menu">
             <ul class="nav navbar-nav pull-right">

                 <li class="dropdown dropdown-user dropdown-dark">
                     <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                         <img alt="" class="img-circle" src="<?php echo $user_img;?>">
                         <span class="username username-hide-mobile"><?php echo $session->read("User.display_name"); ?></span>
                    <i class="fa fa-angle-down"></i>
                     </a>
                     <?php 
                                $baseUrl = $this->request->base;
                                if($session->read("User.role_name") == "administrator"){
                                   // $profileUrl = $baseUrl."/admin-profile/edit-profile/".$session->read("User.id");
                                    $profileUrl = $baseUrl."/pheramor-profile/view-profile";
                                }else{
                                    $profileUrl = $baseUrl."/pheramor-profile/view-profile";
                                }
                            ?>
                     <ul class="dropdown-menu dropdown-menu-default">
                         <li>
                             <a href="<?php echo $profileUrl;?>">
                                 <i class="icon-user"></i> My Profile </a>
                         </li>
                         
                         <li>
                             <a href="<?php echo $baseUrl; ?>/users/logout">
                                 <i class="icon-key"></i> Log Out </a>
                         </li>
                     </ul>
                 </li>
                
             </ul>
         </div>
        
        <!-- END TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- END HEADER INNER -->
