<?php
$session = $this->request->session(); 
//$session = $this->request->session()->read("User");
$this->Html->addCrumb('View Cafe Reports');
$logo = $session->read("User.logo");
echo $this->Html->css('assets/pages/css/invoice.min.css');
//echo "<pre>";print_r($cafe_data);echo "</pre>";
?>
<div class="col-md-12">
    <div class="portlet light bordered">

        <div class="invoice">
            <div class="row invoice-logo">
                <div class="col-xs-6 invoice-logo-space">
                    <?php echo $this->Html->image($logo, ["class" => "logo-default img-responsive"]); ?> </div>
                <div class="col-xs-6">
                    <p> # <?php  echo substr(number_format(time() * rand(),0,'',''),0,6)." / ".date($this->Pheramor->getSettings("date_format"), strtotime(date('Y-m-d')))?>
                        <span class="muted"> Pheramor Cafe </span>
                    </p>
                </div>
            </div>
            <hr>
            <div class="row">
                 <div class="col-xs-6 invoice-payment">
                    <h3>Cafe Details:</h3>
                    <ul class="list-unstyled">
                        <li>
                            <strong>Type: </strong> <?php echo $cafe_data->cafe_type;?> </li>
                        <li>
                            <strong>Cafe Name: </strong><?php echo $cafe_data->title;?> </li>
                        <li>
                            <strong>Address: </strong> <?php echo $cafe_data->address;?> </li>
                        <li>
                            <strong>City: </strong><?php echo $cafe_data->city;?> </li>
                        <li>
                            <strong>State: </strong> <?php echo $cafe_data->state;?> </li>
                         <li>
                            <strong>Zip code: </strong> <?php echo $cafe_data->zipcode;?> </li>
                    </ul>
                </div>
               
                <div class="col-xs-6">
                    <h3>Descriptions:</h3>
                   <?php echo $cafe_data->description;?>
                </div>
               
            </div>
         
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th>Name </th>
                                <th class=""> Email</th>
                                <th class=""> Check-in Date </th>
                                <th class=""> Time </th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            if(!empty($cafe_check_data)){
                                $k=1;
                            foreach($cafe_check_data as $data){ 
                              // echo "<pre>";print_r($data['user_id']);
                                $user_datas=$this->pheramor->get_user_details($data['user_id']);
                                $user_data=$user_datas['pheramor_user_profile'][0];
                                ?>
                            <tr>
                                <td><?php echo  $k;?></td>
                                <td><?php echo $user_data['first_name']." ".$user_data['last_name']?></td>
                                <td class=""><?php echo $user_datas['email']?></td>
                                <td class=""><?php echo date('F j, Y ', strtotime($data['check_in_time']));?></td>
                                <td class=""><?php echo date('h:i:s A', strtotime($data['check_in_time']));?></td>
                            </tr>
                            <?php $k++;} }else{ ?>
                            <tr><td colspan="5" style="text-align: center">Sorry ! no record found</td></tr>
                            <?php }?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
           
            <div class="row">
                <div class="col-xs-4">
                    <div class="well">
                        <address>
                            <strong>Pheramor, Inc.</strong>
                            <br> 795 Park Ave, Suite 120
                            <br> San Francisco, CA 94107
                            <br>
                            <abbr title="Phone">P:</abbr> (234) 145-1810 </address>
                        <address>
                            <strong>Email Address</strong>
                            <br>
                            <a href="mailto:info@pheramor.com"> info@pheramor.com </a>
                        </address>
                    </div>
                </div>
                <div class="col-xs-8 invoice-block">
                    <ul class="list-unstyled amounts">
                       
                        <li>
                            <strong>Total Members:</strong> <?php echo (@$k)?@$k-1:'--';?></li>
                       
                       
                    </ul>
                    <br>
                    <a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();"> Print
                        <i class="fa fa-print"></i>
                    </a>
                   <a class="btn btn-lg green hidden-print margin-bottom-5" href="<?php echo $this->Pheramor->createurl("Reports", "cafeReport"); ?>"> Retun Cafe Report
                                                        <i class="fa fa-reply"></i>
                                                    </a>
                </div>
            </div>
            
        </div>


    </div>
        
</div>

