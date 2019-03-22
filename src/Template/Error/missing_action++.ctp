<?php 
$session = $this->request->session()->read("User");
$session = $this->request->session()->read("User");
$this->Html->addCrumb('404 Error Page');
error_reporting(0);
echo $this->Html->css('assets/pages/css/error.min');
?>
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





