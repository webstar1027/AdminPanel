<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = 'CakePHP: the rapid development php framework';
$session = $this->request->session()->read("User");

 $baseUrl = $this->request->base;
define('BASE_URL', $_SERVER['HTTP_HOST']);
if (empty($session)) { 
 ?>
<script>
    window.location.href = "<?php echo BASE_URL?>";
    </script>
<?php
 die;     
} 
?>
<!DOCTYPE html>
<html>
    <?= $this->Element('header') ?>
    
    <?php $slugasclass = $this->request->controller.'-'.$this->request->action;?>
   
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white <?php echo $slugasclass;?> <?php echo $session['role_name'];?>">
        
      
<?php
//$session = $this->request->session()->read("User");
//$this->Html->addCrumb('404 Error Page');
error_reporting(0);
echo $this->Html->css('assets/pages/css/error.min');
?>
        
        
        <div class="page-wrapper">
            <?= $this->Element('topbar') ?>	
            <div class="clearfix"> </div>
            <div class="page-container">
                <div class="page-sidebar-wrapper">
                    
                    <?php
                    $menu_cell = $this->cell('GymRenderMenu::adminMenu');
                    ?>

                    <?= $menu_cell ?>

                </div>

                     
                
                               
                                        
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        
                        <!--<div class="page-bar">
                            <ul class="page-breadcrumb">
                                <?php 
                                //$this->Html->addCrumb('Users', '/users');
                                //$this->Html->addCrumb('Add User', array('controller' => $this->request->params['controller'], 'action' => $this->request->params['action']));
                                //$this->Html->addCrumb('member');
                                if(count($this->Html->getCrumbListRaw())){?>
                                    <li>
                                    <a href="<?="http://".$_SERVER['HTTP_HOST'];?>">Dashboard</a>
                                    <i class="fa fa-circle"></i>
                                    </li>
                                    <?php
                                    foreach ($this->Html->getCrumbListRaw() as $crumbs){
                                ?>
                                <li>
                                    <?php 
                                        if(!empty($crumbs[1])){
                                            if(is_array($crumbs[1])){?>
                                                <a href="<?php echo $this->Gym->createurl($crumbs[1]['controller'],$crumbs[1]['action']);?>"><?=$crumbs[0];?></a>
                                    <?php   }else{?>
                                                <a href="<?=$this->request->base.$crumbs[1];?>"><?=$crumbs[0];?></a>
                                    <?php   }
                                    ?>
                                            <i class="fa fa-circle"></i>
                                    <?php }else{?>
                                            <span><?=$crumbs[0]?></span>
                                    <?php }?>
                                </li>
                                <?php }
                                
                                }else{?>
                                    <li>
                                    <a href="<?="http://".$_SERVER['HTTP_HOST'];?>">Dashboard</a>
                                    </li>
                                <?php }
                                ?>
                            </ul>
                        </div>-->
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <?php
                         //$currentController = $this->request->params['controller'];
                         //$currentAction = $this->request->params['action'];
                         
                         $accessright = $this->Gym->getAccessRightRecord();
                           // print_r( $this->request->referer());
                        ?>
                        <?php if($session['role_id'] != 4){?>
                        <h1 class="page-title"> <?=$accessright['module'];?>
                            <small><?php //$accessrightName = explode(' ',$accessright['name']); echo $accessrightName[0];?></small>
                        </h1>
                        <?php }?>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
  
                        <div class="row">
                            
                            <div class="col-md-12">
    <!--<h1 class="page-title"> 404 Error Page-->
                            <!--<small>404 page option 1</small>-->
                        <!--</h1>-->
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <div class="row">
                            <div class="col-md-12 page-404">
                                <div class="number font-green"> 404 </div>
                                <div class="details">
                                    <h3>Oops! You're lost.</h3>
                                    <p> We can't seem to find the page you're looking for.
                                        <br/>
                                        Go to our <a href="<?php echo $this->Gym->createurl("Dashboard","index");?>">Homepage </a> or go back to <a href="javascript:void(0)" onclick="previous()">previous page.</a> </p>
                                    <!--<form action="#">
                                        <div class="input-group input-medium">
                                            <input type="text" class="form-control" placeholder="keyword...">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn green">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <!-- /input-group -->
                                   <!-- </form>-->
                                </div>
                            </div>
                        </div>

    
    
    	
    
</div>
                            <div class="modal fade gym-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                <div class="modal-dialog modal-lg gym-modal">
                                    <div class="modal-content">			

                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                    <!-- END CONTENT BODY -->
                    <!-- BEGIN SIDEBAR -->
                    <?= $this->Element('right-sidebar') ?>
                    <!-- END SIDEBAR -->
            </div>
            <?= $this->Element('footer') ?>

            
        </div>

    </body>
</html>

<script>
function previous(){
    var previousURL = document.referrer;
    //alert(previousURL);
    window.location.href = previousURL;
}
</script>
