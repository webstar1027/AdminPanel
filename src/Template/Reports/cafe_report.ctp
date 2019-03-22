<?php
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Cafe Reports');
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
                <span class="caption-subject bold uppercase"> <?php echo __("Cafe Report"); ?>
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
                             <label  class="control-label col-md-2" ><?php echo __('Select Type'); ?></label>
                            <div class="form-group col-md-4">
                                <select name="cafe_status" class="form-control" >
                                    <option value="all" <?php if($status=='all'){ echo "selected";}?>>All Type</option>
                                    <option value="Cafe" <?php if($status=='Cafe'){ echo "selected";}?>>Cafe</option>
                                    <option value="Restaurant" <?php if($status=='Restaurant'){ echo "selected";}?>>Restaurant</option>
                                    <option value="Lounge" <?php if($status=='Lounge'){ echo "selected";}?>>Lounge</option>
                                    <option value="Bakery" <?php if($status=='Bakery'){ echo "selected";}?>>Bakery</option>
                                    <option value="Bar" <?php if($status=='Bar'){ echo "selected";}?>>Bar</option>
                                </select>

                            </div>
                           <label  class="control-label col-md-2" ><?php echo __('Select Cafe Name'); ?></label>
                            <div class="form-group col-md-3">
                                 <?php echo @$this->Form->select("cafe_name", $cafe_lists, ["default" => $cafe_name, "empty" => __("All Cafe"), "class" => "form-control"]);?>
                             

                            </div>
                            <div class="form-group col-md-4">
                                <input type="submit" name="cafe_report" Value="<?php echo __('Search Filter'); ?>"  class="btn btn-flat btn-success"/>
                            </div> 
                

                        </form>
                        
                    </div>
                   
                   

                </div>
                
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments"></i>Cafe Listing </div>
                        <div class="tools">
                           <a href="<?php echo $this->Pheramor->createurl("Reports", "cafeExport/" . $startdate . '/' . $enddate.'/'.$status.'/'.$cafe_name.'/0'); ?>" class=""><i class="fa fa-bar-chart"></i> <?php echo __("Export Excel"); ?></a>
                            <a href="<?php echo $this->Pheramor->createurl("Reports", "cafeExport/" . $startdate . '/' . $enddate.'/'.$status.'/'.$cafe_name.'/1'); ?>" class=""><i class="fa fa-pie-chart"></i> <?php echo __("Export Pdf"); ?></a>
                           
                        </div>
                    </div>
                    
                </div>
                <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                    <thead>


                        <tr>
                           
                            <th><?php echo __("S.N."); ?></th>
                            <th><?php echo __("Type"); ?></th>
                            <th ><?php echo __("Name"); ?></th>
                            <th><?php echo __("Zipcode"); ?></th>
                            <th><?php echo __("Check-in"); ?></th>
                            <th><?php echo __("Status"); ?></th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        $k=1;
                       //  echo "<pre>"; print_r($member_data); die;
                        if(!empty($cafe_data)){
                            foreach ($cafe_data as $row) {
                               // gender
                                $status='';
                               echo "<tr>
                                            <td>{$k}</td>
                                            <td>{$row['cafe_type']}</td>
                                            <td>{$row['title']}</td>  
                                            <td>{$row['zipcode']}</td>
                                            <td><div class='mt-element-ribbon bg-grey-steel'>
                                                <div class='ribbon ribbon-round ribbon-border-dash ribbon-color-primary uppercase'>Total : {$this->Pheramor->totalCheckinMembers($row['id'],$startdate,$enddate)}</div>
                                                 </div></td>
                                            <td><a href='". $this->Pheramor->createurl("Reports", "cafeViewReport/" .$row['id'].'/'. $startdate . '/' . $enddate)."' class='btn btn-sm btn-circle btn-default btn-editable'><i class='fa fa-search'></i> View</a></td> 
                                            </tr>";
                                           $k++;
                            }
                        }
                        ?>




                    </tbody>
                </table>

            </div>

        </div>
      </div>
</div>
