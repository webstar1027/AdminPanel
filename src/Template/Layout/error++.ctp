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
        
        <?php if($session['role_id']==2 && $session['agree']==0 && $session['lice_agree_type']!='Self'){?>
       <!-- <script type="text/javascript">
	$(document).ready(function(){
          // $('#btn-agree').disable(true);
           $("#myModals").modal({backdrop: 'static', keyboard: false});
           $("#mem_auto_popup11").submit(function(e) 
            {
                e.preventDefault();
               if($('input.checkbox_check').is(':checked')) 
                {
                var datastring = $(this).serialize();
                var ajaxurl = $("#mem_location_add_url11").val();
                $.ajax({
                type: "POST",
                url: ajaxurl,
                data: datastring,
                dataType: "json",
            beforeSend: function()
            {  
                $("#paymentButtons").val('Processing..');
            },
            success: function(data) 
            {

            $.each(data.OrderStatus, function(i,data){
            var HTML;
            if(data)
            {
                if(data.status == '1')
                {
                   $("#myModals").modal('hide');
                   $("#termdiv").show();
                }
                else
                {
                    window.location.href='<?php echo $baseUrl; ?>/users/logout';
                }
            }
            });
           },
            error: function(){ alert('error handing here'); }
            });
            return false;
            }
            });
            
            $("#term_condition").click(function(){
               if($('input.checkbox_check').is(':checked')){
                   $('#btn-agree').removeClass('disabled');
               }else{
                   $('#btn-agree').addClass('disabled');
               }
            });
	});
</script>-->
        <?Php } ?>
<div id="myModals" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Terms & Conditions Agreement</h4>
            </div>
            <div class="modal-body">
                <?php if($session['lice_agree_type']=='Licensee2' || $session['agree']=='Licensee1'){ ?>
		<iframe src="https://docs.google.com/viewer?url=http://gotribe.rnf.tech/webroot/img/level_1_2_License_Agreement.pdf&embedded=true" style="width:100%; height:300px;" frameborder="0"></iframe>
               <?Php  }else if($session['lice_agree_type']=='Licensee3'){?>
                <iframe src="https://docs.google.com/viewer?url=http://gotribe.rnf.tech/webroot/img/Level_3_License_Agreement.pdf&embedded=true" style="width:100%; height:300px;" frameborder="0"></iframe>
               <?php } ?>
                <form id="mem_auto_popup11" name="mem_auto_popup11" method="post">
                    <div class="col-md-6"><strong><input type="checkbox"  name="term_condition" class="checkbox_check" id="term_condition" value="1"> &nbsp;I accept the Terms of Service</strong></div>
                    
                    <div class="col-md-4">
                     <button type="submit" class="btn btn-primary disabled" id="btn-agree">Submit</button>
                    </div>
                    <div class="row">&nbsp;</div>
                </form>
                 <input type="hidden" value="<?php echo $this->request->base; ?>/GymAjax/update_licensee_terms" id="mem_location_add_url11">
            </div>
        </div>
    </div>
  </div>
        
        
        <div class="page-wrapper" id="termdiv" <?php //if($session['role_id']==2 && $session['agree']==0 && $session['lice_agree_type']!='Self'){ echo 'style="display:none;"';}?>>
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
                        
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <?php 
                                //$this->Html->addCrumb('Users', '/users');
                                //$this->Html->addCrumb('Add User', array('controller' => $this->request->params['controller'], 'action' => $this->request->params['action']));
                                //$this->Html->addCrumb('member');
                                if(count($this->Html->getCrumbListRaw())){?>
                                    <li>
                                    <a href="<?="http://".$_SERVER['HTTP_HOST'];?>">404 Error Page</a>
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
                                    <a href="<?="http://".$_SERVER['HTTP_HOST'];?>">404 Error Page</a>
                                    </li>
                                <?php }
                                ?>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <?php
                         //$currentController = $this->request->params['controller'];
                         //$currentAction = $this->request->params['action'];
                         
                         $accessright = $this->Gym->getAccessRightRecord();
                            
                        ?>
                        <?php if($session['role_id'] != 4){?>
                        <h1 class="page-title"> <?=$accessright['module'];?>
                            <small><?php //$accessrightName = explode(' ',$accessright['name']); echo $accessrightName[0];?></small>
                        </h1>
                        <?php }?>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->

                        <div class="row">
                            <?php
                            $this->Html->addCrumb('404 Error Page');
                           // error_reporting(0);
                            echo $this->Html->css('assets/pages/css/error.min');
                            ?>
                              
                            
                            <?= $this->Flash->Render() ?>
                            <div class="col-md-12">
                         <h1 class="page-title"> 404 Error Page
                            <!--<small>404 page option 1</small>-->
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <div class="row">
                            <div class="col-md-12 page-404">
                                <div class="number font-green"> 404 </div>
                                <div class="details">
                                    <h3>Oops! You're lost.</h3>
                                    <p> We can not find the page you're looking for.
                                        <br/>
                                        <a href="<?php echo $this->Gym->createurl("Dashboard","index");?>"> Return dashboard </a> or try again. </p>
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
