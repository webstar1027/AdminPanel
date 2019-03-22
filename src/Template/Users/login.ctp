<?php
//echo $this->request->base;die;
$footer = $this->Gym->getSettings("footer");
$logo = $this->Gym->getSettings("company_logo");
$logo = (!empty($logo)) ? $logo : $this->request->base . "/webroot/img/pheramore.png";
?>


 <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="#">
                <img src="<?php echo $logo; ?>" alt="" /> </a>
        </div>
        <!-- END LOGO -->
        
 <div class="content">
     <form class="login-form"  method="post">
                <h3 class="form-title">Login to your account</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Enter any username and password. </span>
                </div>
                <?php //if($this->request->session->flash()){ ?>
                    
                            <?php echo $this->Flash->render('other'); ?>
                            <?php echo $this->Flash->render(); ?>
                            <?php echo __($this->Flash->render('auth')); ?> 
                        
                    <?php  //}?>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Email Address</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="email" autocomplete="off" placeholder="Email Address" name="username" /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> </div>
                </div>
                <div class="form-actions">
                    <label class="rememberme mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="remember" value="1" /> Remember me
                        <span></span>
                    </label>
                    <button type="submit" class="btn green pull-right"> Login </button>
                </div>
                <!--<div class="login-options">
                    <h4>Or login with</h4>
                    <ul class="social-icons">
                        <li>
                            <a class="facebook" data-original-title="facebook" href="javascript:;"> </a>
                        </li>
                        <li>
                            <a class="twitter" data-original-title="Twitter" href="javascript:;"> </a>
                        </li>
                        <li>
                            <a class="googleplus" data-original-title="Goole Plus" href="javascript:;"> </a>
                        </li>
                        <li>
                            <a class="linkedin" data-original-title="Linkedin" href="javascript:;"> </a>
                        </li>
                    </ul>
                </div>-->
                <div class="forget-password">
                    <h4>Forgot your password ?</h4>
                    <p> no worries, click
                        <a href="javascript:;" id="forget-password"> here </a> to reset your password. </p>
                </div>
                <!--<div class="create-account">
                    <p> Don't have an account yet ?&nbsp;
                        <a href="javascript:;" id="register-btn"> Create an account </a>
                    </p>
                </div>-->
            </form>
     
      <!-- BEGIN FORGOT PASSWORD FORM -->
             <?php
                    echo  $this->Form->create('forget-form', array('url' => '/ResetPassword/index', "class"=>"forget-form"));
                ?>
               <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span>Enter valid email address.</span>
                </div>
                <div class="alert alert-success display-hide">
                    <button class="close" data-close="alert"></button>
                    <span>Enter valid email address.</span>
                </div>
                <?php echo $this->Flash->render(); ?>
                <h3>Forget Password ?</h3>
                <p> Enter your e-mail address below to reset your password. </p>
                
                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" id="reset_email" placeholder="Email" name="email" /> </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn grey-salsa btn-outline"> Back </button>
                    <button type="submit" class="btn green pull-right"> Submit </button>
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->
 </div>
<style>
.user-login-5 .login-container>.login-content{
	margin-top:20%;
}
</style>
<?php /* ?><div class="user-login-5">
    <div class="row bs-reset">
        <div class="col-md-6 bs-reset mt-login-5-bsfix">
            <div class="login-bg" style="background-image:url(\"<?php echo $this->request->base . '/webroot/upload/cover-image5.png';?>\")">
                <img class="login-logo" src="<?php echo $logo; ?>" /> 
            </div>
        </div>
        <div class="col-md-6 login-container bs-reset mt-login-5-bsfix">
            <div class="login-content">
                <h1><?php echo $this->Gym->getSettings("name"); ?></h1>
                <p> Your live personal trainer anywhere anytime. </p>
                
                <form class="login-form" method="post">
                    
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <span>Enter email and password. </span>
                    </div>
                    <?php //if($this->request->session->flash()){ ?>
                    
                            <?php echo $this->Flash->render('other'); ?>
                            <?php echo $this->Flash->render(); ?>
                            <?php echo __($this->Flash->render('auth')); ?> 
                        
                    <?php  //}?>
                    <div class="row">
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="on" placeholder="<?php echo __("Email"); ?>" name="username" /> </div>
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="<?php echo __("Password"); ?>" name="password" /> </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="rem-password">
                                <label class="rememberme mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="remember" value="1" /> Remember me
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-8 text-right">
                            <div class="forgot-password">
                                <a href="javascript:void(0)" id="forget-password" class="forget-password"><?php echo __("Forgot Password?"); ?></a>
                            </div>
                            <button class="btn green" id="btn_login" type="submit"><?php echo __("Login"); ?></button>
                            <div class="create-account">
                    <p> Don't have an account yet ?&nbsp;
                        <a href="<?=$this->request->base?>/member-registration" id="register-btn"> Member Registration </a>
                    </p>
                </div>
                        </div>
                    </div>
                </form>
                <!-- BEGIN FORGOT PASSWORD FORM -->
                <?php
                    echo  $this->Form->create('forget-form', array('url' => '/ResetPassword/index', "class"=>"forget-form"));
                ?>
                
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span>Enter valid email address.</span>
                </div>
                <div class="alert alert-success display-hide">
                    <button class="close" data-close="alert"></button>
                    <span>Enter valid email address.</span>
                </div>

                <?php echo $this->Flash->render(); ?>
                
                    <h3 class="font-green">Forgot Password ?</h3>
                    <p> Enter your e-mail address below to reset your password. </p>
                    <div class="form-group">
                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="on" placeholder="Email" name="email" id="reset_email" /> </div>
                    <div class="form-actions">
                        <button type="button" id="back-btn" class="btn green btn-outline">Back</button>
                        <button type="submit" id="submit-btn" class="btn btn-success uppercase pull-right">Submit</button>
                    </div>
                <?php echo $this->Form->end();?>
                <!-- END FORGOT PASSWORD FORM -->
            </div>
            <div class="login-footer">
                <div class="row bs-reset">
                    <div class="col-xs-5 bs-reset">
                        <ul class="login-social">
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-dribbble"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-7 bs-reset">
                        <div class="login-copyright text-right">
                            <p><?php echo $footer;?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php */ ?>
<script>
    function resetPassword(){
        if($('#submit-btn').text()=='Wait..'){
            return false;
        }
        var email = $.trim($('#reset_email').val());
        $.ajax({
            url:"<?php echo $this->request->base . '/ResetPassword/index';?>",
            type:"POST",
            data:{email:email},
            dataType:"JSON",
            beforeSend: function(){
                $('#submit-btn').text('Wait..');
            },
            success:function(response){
                if(response.status == 'error'){
                    $('.alert-danger', $('.forget-form')).html('<button class="close" data-close="alert"></button><span>'+response.msg+'</span>').show();
                    $('.alert-success', $('.forget-form')).hide();
                }else{
                    $('.alert-success', $('.forget-form')).html('<button class="close" data-close="alert"></button><span>'+response.msg+'</span>').show();
                    $('.alert-danger', $('.forget-form')).hide();
                }
                $('#submit-btn').text('Submit');
            },
            error:function(jqXHR, exception){
                $('#submit-btn').text('Submit');
            },

        });
        //alert();
        return false;
    }  
</script>