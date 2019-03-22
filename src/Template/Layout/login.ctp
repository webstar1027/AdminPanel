<!DOCTYPE html>

<html lang="en">
    <!-- BEGIN HEAD -->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $this->Html->charset(); ?> 
        
        <title><?php echo $this->Gym->getSettings("company_name"); ?></title>
        <?php echo $this->Html->meta('icon'); ?>
        <?php echo $this->fetch('meta'); ?>
        
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <?php echo $this->Html->css('validationEngine/validationEngine.jquery');?>
        <?php echo $this->Html->css([
            'assets/global/plugins/font-awesome/css/font-awesome.min.css',
            'assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
            'assets/global/plugins/bootstrap/css/bootstrap.min.css',
            'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
            'assets/global/plugins/select2/css/select2.min.css',
            'assets/global/plugins/select2/css/select2-bootstrap.min.css',
            'assets/global/css/components.min.css',
            'assets/global/css/plugins.min.css',
            'assets/pages/css/login-5.min.css',
             'assets/pages/css/login-3.min.css',
            ]); ?>
        <!-- BEGIN CORE PLUGINS -->
        
        
        <?php echo $this->Html->script([
            "assets/global/plugins/jquery.min.js",
            "assets/global/plugins/bootstrap/js/bootstrap.min.js",
            "assets/global/plugins/js.cookie.min.js",
            "assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js",
            "assets/global/plugins/jquery.blockui.min.js",
            "assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js",
            "assets/global/plugins/jquery-validation/js/jquery.validate.min.js",
            "assets/global/plugins/jquery-validation/js/additional-methods.min.js",
            "assets/global/plugins/select2/js/select2.full.min.js",
            "assets/global/plugins/backstretch/jquery.backstretch.min.js",
            "assets/global/scripts/app.min.js",
            "assets/pages/scripts/login-5.min.js"
            ]);?>
        <?php echo $this->Html->script('validationEngine/languages/jquery.validationEngine-en');
	echo $this->Html->script('validationEngine/jquery.validationEngine'); ?>
        <script>
            $(document).ready(function(){
                $(".validateForm").validationEngine('attach',
                    {
                        promptPosition : "topLeft", 
                        scroll: true
                    }
                );
            });
        </script>
    </head>
    <!-- END HEAD -->
    <body class="login">
        <?php echo $this->fetch('content'); ?>
    </body>
 </html>