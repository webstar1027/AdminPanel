<?php
//echo $this->request->base;die;
$footer = $this->Gym->getSettings("footer");
$logo = $this->Gym->getSettings("gym_logo");
$logo = (!empty($logo)) ? $this->request->base . "/webroot/upload/" . $logo : $this->request->base . "/webroot/img/pheramore.png";
?>
 <div class="logo">
            <a href="#">
                <img src="<?php echo $logo; ?>" alt="" /> </a>
        </div>
        <!-- END LOGO -->
        
 <div class="content">
     <form class="login-form validateForm"  method="post">
                <h3 class="form-title">Reset Password</h3>
                
                    
                    <?php echo $this->Flash->render(); ?>
              
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix validate[required]" type="password" autocomplete="off" placeholder="<?php echo __("New Password"); ?>" name="password" id="password"  /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix validate[required,equals[password]]" type="password" autocomplete="off" placeholder="<?php echo __("Confirm New Password"); ?>" name="cpassword" id="cpassword" /> </div>
                </div>
                <div class="form-actions">
                    <label class="rememberme ">
                       &nbsp;
                        <span></span>
                    </label>
                    <button type="submit" class="btn green pull-right"> Submit </button>
                </div>
                
            </form>
 </div>
 