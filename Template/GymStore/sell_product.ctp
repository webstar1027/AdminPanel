<?php $session = $this->request->session()->read("User");?>
<?php
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
?>
<script>
$(document).ready(function(){
	$(".sell-date").datepicker({format:"yyyy-mm-dd"});	
	$(".mem_list").select2();
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Sell Product");?>
				<small><?php echo __("Store");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymStore","sellRecord");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Sell Records");?></a>
			 </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">					
		<form class="validateForm form-horizontal" method="post" role="form">		
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Member");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
		<?php 
		echo $this->Form->select("member_id",$members,["empty"=>__("Select Member"),"default"=>($edit)?array($data['member_id']):"","class"=>"mem_list","required"=>"true"]);
		?>
		</div>	
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Date");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
			<input type="text" name="sell_date" class="sell-date form-control validate[required]" value="<?php echo ($edit)?date("Y-m-d",strtotime($data["sell_date"])) : "";?>">
		</div>	
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Product");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
		<?php 
		echo $this->Form->select("product_id",$products,["empty"=>__("Select Product"),"default"=>($edit)?array($data['product_id']):"","class"=>"form-control validate[required]"]);
		?>
		</div>
		<?php
		if($session["role_name"] == "administrator")
		{?>
		<div class="col-md-3">
			<a href="<?php echo $this->request->base ."/GymProduct/addProduct";?>" class="btn btn-flat btn-default"><?php echo __("Add Product");?></a>
		</div>
	<?php } ?>
		</div>
		<div class='form-group'>	
		<label class="control-label col-md-2" for="email"><?php  echo __("Quantity");?><span class="text-danger"> *</span></label>
		<div class="col-md-6">
			<input type="text" name="quantity" class="form-control validate[required,custom[integer,min[0]]]" value="<?php echo ($edit)?$data["quantity"] : "";?>">
		</div>	
		</div>
		<div class="col-md-offset-2 col-md-6">
			<input type="submit" value="<?php echo __("Save");?>" name="save_product" class="btn btn-flat btn-success">
		</div>
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
