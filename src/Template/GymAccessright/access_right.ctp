<?php 
$this->Html->addCrumb('Access Rights');
?>
<script type="text/javascript">
$(document).ready(function() {	
	$(".content-wrapper").css("min-height","1550px");
});
</script>

	<div class="col-md-12">	
             <div class="portlet light portlet-fit portlet-form bordered">
		
                 <div class="portlet-title" style="margin-bottom:15px;">
                     <div class="caption">
                         <i class=" icon-key font-red"></i>
                         <span class="caption-subject font-red sbold uppercase"><?php echo __("Access Right Settings"); ?></span>
                     </div>
                     <div class="top">
                     </div>

                 </div>
                 <div class="portlet-body">
		<div class="box-body" style="padding:0 20px;">	
		<form name="student_form" action="" method="post" class="form-horizontal" id="access_right_form">
			<div class="row">
				<div class="col-md-1 col-sm-3 col-xs-3 custom-col"><?php echo __("Menu");?></div>
				<?php 
					foreach($roles as $role){
						echo '<div class="col-md-1 col-sm-3 col-xs-3 custom-col text-center">
							'.$role['name'].'
						</div>';
				} ?>
			</div>
			<?php 
				//echo '<pre>'; print_r($menus); 
				foreach($menus as $key=>$menu){
					echo '<div class="row">
						<div class="col-md-1 col-sm-3 col-xs-5 custom-col">
							<span class="menu-label">
								<strong>'.$key.'</strong>
							</span>
						</div>
					</div>';		
					foreach($menu as $menu_key=>$menu_val){
						echo '<div class="row">
								<div class="col-md-1 col-sm-3 col-xs-5 custom-col">
									<span class="menu-label">
										'.$menu_val['name'].'
									</span>
								</div>';
						foreach($roles as $role){
							$checked = (in_array($role['id'], explode(',',$menu_val['assigned_roles']))) ? "checked":" ";	
							echo '<div class="col-md-1 col-sm-3 col-xs-2 custom-col">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="1" '.$checked.' name="'.$role['id'].'_'.$menu_key.'" readonly="">
										</label>
									</div>
								</div>';
						}
						echo '</div>';
					 } 
				 } ?>

		
                    <hr>
		<div class="col-sm-8 row_bottom">
        	
        	<input type="submit" value="<?php echo __("Save Setting");?>" name="save_access_right" class="btn btn-flat btn-success">
               </div>
                    <br>
                    <br>
                     <br>
                   
        	
        </form>
		
		<!-- END -->
		</div>
             </div>
		</div>
	</div>

