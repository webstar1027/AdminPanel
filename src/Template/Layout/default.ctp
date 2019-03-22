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
//print_r($session);
 $baseUrl = $this->request->base;
?>
<!DOCTYPE html>
<html>
    <?= $this->Element('header') ?>
    
    <?php $slugasclass = $this->request->controller.'-'.$this->request->action;?>
   
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white <?php echo $slugasclass;?> <?php echo $session['role_name'];?>">
     	
        <div class="page-wrapper">
            <div class="page-wrapper-row">
                <div class="page-wrapper-top">
                    <div class="page-header">
                        <?= $this->Element('topbar') ?>

                        <?php
                        $menu_cell = $this->cell('GymRenderMenu::adminMenu');
                        ?>

                        <?= $menu_cell ?>

                    </div>
                </div>
            </div>
            <div class="page-wrapper-row full-height">
             <div class="page-wrapper-middle">
              <div class="page-container">
                 <div class="page-content-wrapper">
                     <div class="page-head">
                                <div class="container">
                                    <!-- BEGIN PAGE TITLE -->
                                    <?php
                                        $accessright = $this->Gym->getAccessRightRecord();

                                       ?>
                                     <div class="page-title">
                                       <h1> <?=$accessright['module'];?>
                                           <small><?php //$accessrightName = explode(' ',$accessright['name']); echo $accessrightName[0];?></small>
                                       </h1>
                                     </div>
                                </div>
                        </div>
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                         <div class="container">
                            <ul class="page-breadcrumb breadcrumb">
                                <?php 
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

                            <div class="page-content-inner"> 
                                
                                 <div class="loader" style="display:none;"></div>
                                <div class="row" id="loader-content-section">
                                    <div class="col-md-12">
                                        <?= $this->Flash->Render() ?>
                                    </div>
                                    
                                   
                                    <?= $this->fetch('content') ?>
                                    <div class="modal fade gym-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog modal-lg gym-modal">
                                            <div class="modal-content">			

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>



                    </div>
                </div>
                    
            </div>
                </div>
              </div>
            <div class="page-wrapper-row">
                <div class="page-wrapper-bottom">
            
                 <?= $this->Element('footer') ?>
                </div>
            </div>
            </div>
        
        <style>
 .loader {
  border: 32px solid #32c5d2;
  border-radius: 50% !important;
  border-top: 32px solid #3498db;
  width: 240px;
  height: 240px;
  left:40%;
  top:50%;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
 position: absolute;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}</style>
        </body>
</html>
