<?php
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Product Sales Reports');
?>
<style>
.dt-buttons {display:none;}
.portlet>.portlet-title>.tools>a {color:#fff;}
 .mt-element-ribbon .ribbon{
        margin: 0px;
    } 
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("#startdate").datepicker({
            todayBtn: 1,
            autoclose: true,
            forceParse: false



        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#enddate').datepicker('setStartDate', minDate);
        });

        $("#enddate").datepicker({forceParse: false})
                .on('changeDate', function (selected) {
                    var maxDate = new Date(selected.date.valueOf());
                    $('#startdate').datepicker('setEndDate', maxDate);
                });
    });
</script>

<?php
if(empty($startdate)){$startdate=0;}
if(empty($enddate)){$enddate=0;}
if(empty($cafe_name)) {$cafe_name=0;}

?>

<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-book-open font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Product Sales Report"); ?>
                </span>

            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>

        <div class="portlet-body">

            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-12">
                        <form method="post">  

                           
                            <label  class="control-label col-md-1" ><?php echo __('From'); ?></label>
                            <div class="form-group col-md-2">
                                <input type="text" name="startdate" data-date-format="<?php echo $this->Gym->get_js_dateformat($this->Gym->getSettings("date_format")); ?>" id="startdate" class="form-control" value="<?php if($startdate){ echo date($this->Gym->getSettings("date_format"), $startdate);} ?>">		
                            </div>
                            <label  class="control-label col-md-1" ><?php echo __('To'); ?></label>
                            <div class="form-group col-md-2">
                                <input type="text" name="enddate" data-date-format="<?php echo $this->Gym->get_js_dateformat($this->Gym->getSettings("date_format")); ?>" id="enddate" class="form-control" value="<?php if($enddate){echo date($this->Gym->getSettings("date_format"), $enddate);} ?>">		
                            </div>
                             <label  class="control-label col-md-2" ><?php echo __('Payment Status'); ?></label>
                            <div class="form-group col-md-2">
                                <select name="payment_status" class="form-control" >
                                    <option value="all" <?php if($status=='all'){ echo "selected";}?>>All</option>
                                    <option value="1" <?php if($status=='1'){ echo "selected";}?>>Paid</option>
                                    <option value="0" <?php if($status=='0'){ echo "selected";}?>>Failed</option>
                                   
                                </select>

                            </div>
                           <div class="form-group col-md-2">
                                <input type="submit" name="product_report" Value="<?php echo __('Search Filter'); ?>"  class="btn btn-flat btn-success"/>
                            </div> 
                

                        </form>
                        
                    </div>
                   
                   

                </div>
                
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments"></i>Product Sales Listing </div>
                        <div class="tools">
                           <a href="<?php echo $this->Pheramor->createurl("Reports", "productSalesExport/" . $startdate . '/' . $enddate.'/'.$status.'/0'); ?>" class=""><i class="fa fa-bar-chart"></i> <?php echo __("Export Excel"); ?></a>
                            <a href="<?php echo $this->Pheramor->createurl("Reports", "productSalesExport/" . $startdate . '/' . $enddate.'/'.$status.'/1'); ?>" class=""><i class="fa fa-pie-chart"></i> <?php echo __("Export Pdf"); ?></a>
                           
                        </div>
                    </div>
                    
                </div>
                <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                    <thead>


                        <tr>
                           
                            <th><?php echo __("S.N."); ?></th>
                            <th><?php echo __("Member Name"); ?></th>
                            <th ><?php echo __("Email address"); ?></th>
                            <th><?php echo __("Product Name"); ?></th>
                            <th><?php echo __("Product Amount"); ?></th>
                            <th><?php echo __("Paid Amount"); ?></th>
                            <th><?php echo __("Payment Date"); ?></th>
                            <th><?php echo __("Status"); ?></th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        $k=1;
                       // echo "<pre>"; print_r($product_sales_data); die;
                        $total_sales=0;
                        if(!empty($product_sales_data)){
                            foreach ($product_sales_data as $row) {
                               // gender
                                $product_name=$this->Pheramor->get_subscription_names($row['product_id']);
                              //  print_r($product_name); die;
                                if ($row['payment_status'] == 1) {
                                    $type_color='#32c5d2';
                                    $type_text='Paid';
                                    $total_sales=$total_sales+$row['paid_amount'];
                                    
                                } else if ($row['payment_status'] == 0) {
                                    $type_color='#e7505a';
                                    $type_text='Failed';
                                } 
                                $paid_date=date('Y-m-d H:i A', strtotime($row['payment_date']));
                              // $paid_date=$row['payment_date'];
                               echo "<tr>
                                            <td>{$k}</td>
                                            <td>{$row['first_name']} {$row['last_name']}</td>
                                            <td>{$row['email']}</td>  
                                            <td>{$product_name[0]['subscription_title']}</td>
                                            <td><div class='mt-element-ribbon bg-grey-steel'>
                                                <div class='ribbon ribbon-round ribbon-border-dash ribbon-color-warning uppercase'>$ {$row['product_amount']}</div>
                                                 </div></td>
                                            <td><div class='mt-element-ribbon bg-grey-steel'>
                                                <div class='ribbon ribbon-round ribbon-border-dash ribbon-color-primary uppercase'>$ {$row['paid_amount']}</div>
                                                 </div></td>
                                           
                                            <td>{$paid_date}</td>
                                            <td><span style='border-radius:25px !important;border:0;background:{$type_color};' class=' btn-success btn-sm'>{$type_text}</span></td>
                                            <!--<td><a href='". $this->Pheramor->createurl("Reports", "cafeViewReport/" .$row['id'].'/'. $startdate . '/' . $enddate)."' class='btn btn-sm btn-circle btn-default btn-editable'><i class='fa fa-search'></i> View</a></td> -->
                                            </tr>";
                                           $k++;
                            }
                            $total_sales= number_format($total_sales,2);
                            echo "</tbody><tr><th colspan='4'>&nbsp;</th><th colspan='2'><div class='mt-element-ribbon bg-grey-steel'>
                                                <div class='ribbon ribbon-round ribbon-border-dash ribbon-color-danger uppercase'>Total Sales : $ {$total_sales}</div>
                                                 </div></th><th colspan='2'>&nbsp;</th></tr>";
                        }
                        ?>


                       

                    </tbody>
                </table>

            </div>

        </div>
      </div>
</div>
