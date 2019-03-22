<?php
echo $this->Html->script('assets/global/plugins/highcharts/js/highcharts.js');
echo $this->Html->script('assets/pages/scripts/charts-highcharts.min.js');
  
?>
<div class="row widget-row">
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">Total Users</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-user"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">Register</span>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($total_member);?>">0</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">Premium Members</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-red icon-check"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">Active</span>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($activePremiumMember);?>">0</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">This Week Sales</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-purple icon-wallet"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">USD</span>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($weekly_sales);?>">0</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">Total Sales</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-blue icon-bag"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">USD</span>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($total_sales);?>">0</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
</div>

<!-- Monthly Sales Chart -->

<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit ">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Sales Revenue Monthly</span>
                </div>
                
            </div>
            <div class="portlet-body">
                <div id="monthlySalesRevenue" style="height:300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Revenue</span>
                    <span class="caption-helper">distance stats...</span>
                </div>
               
            </div>
            <div class="portlet-body">
                <div id="dashboard_amchart_1" class="CSSAnimationChart"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption ">
                    <span class="caption-subject font-dark bold uppercase">Finance</span>
                    <span class="caption-helper">distance stats...</span>
                </div>
               
            </div>
            <div class="portlet-body">
                <div id="dashboard_amchart_3" class="CSSAnimationChart"></div>
            </div>
        </div>
    </div>
</div>
<!--##### Subscription Graph Start Here--->
<div class="row">
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">Sales By Subscription</span>
                </div>
                
            </div>
            <div class="portlet-body">
                <div class="portlet-body">
                    <div id="dashboard_amchart_41" class="CSSAnimationChart" style="height:345px;"></div>
              </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class=" icon-social-twitter font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">Latest Payment</span>
                </div>
               
            </div>
            <div class="portlet-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_actions_pending">
                        <!-- BEGIN: Actions -->
                        <div class="mt-actions">
                            <?php if(!empty($latest_payment_data)){
                                foreach($latest_payment_data as $payment_data){
                                    $user_data=$this->Pheramor->get_user_details($payment_data['user_id']);
                                    $user_profile_data=$user_data['pheramor_user_profile'][0];
                                  // echo "<pre>";print_r($user_data); die;
                                   
                                  if(empty($user_profile_data['image'])) {$user_profile_data['image']=$this->request->base.'/upload/profile-placeholder.png';}
                                  if($payment_data['payment_status']==1){  $color_status='green'; $payment_txt='Confirm';}else{$color_status='red'; $payment_txt='Reject';}
                                  $subscription=$this->Pheramor->get_subscription_names($payment_data['subscription_id']);
                                    ?>
                             <div class="mt-action">
                                <div class="mt-action-img">
                                    <img src="<?php echo $user_profile_data['image'];?>" height="45" width="45"> </div>
                                <div class="mt-action-body">
                                    <div class="mt-action-row">
                                        <div class="mt-action-info ">
                                            
                                            <div class="mt-action-details ">
                                                <span class="mt-action-author"><?Php echo $user_profile_data['first_name']." ".$user_profile_data['last_name'];?></span>
                                                <p class="mt-action-desc"><?php echo $subscription[0]['subscription_title'] ;?></p>
                                            </div>
                                        </div>
                                        <div class="mt-action-datetime ">
                                            <span class="mt-action-date"><?php echo date($this->Pheramor->getSettings("date_format"), strtotime($payment_data['created_date'])); ?></span>
                                            <span class="mt-action-dot bg-<?php echo $color_status;?>"></span>
                                            <span class="mt-action-time">$ <?php echo number_format(($payment_data['subscription_amount']-$payment_data['discount_amount']),2);?></span>
                                        </div>
                                       
                                        <div class="mt-action-buttons ">
                                         <label  class="btn <?php echo $color_status;?> btn-outline btn-circle btn-sm"><?php echo $payment_txt;?></label>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <?Php } } ?>
                       
                            
                        </div>
                        <!-- END: Actions -->
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Here -->


<?php if(!empty($member_data)){ ?>
<div class="portlet light portlet-fit ">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green bold uppercase">Recent Register Users</span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="mt-element-card mt-card-round mt-element-overlay">
            <div class="row">
             <?php 
                foreach($member_data as $data){ 
                    //print_r($data);
                    $image = (!empty($data['image'])) ? $data['image'] : $this->request->webroot."upload/profile-placeholder.png";
                    $profession=(!empty($data['profession'])) ? $data['profession'] : '--';
                   // $profileimage=$this->Pheramor->getProfileImage($data['id']);
                     if(empty($image)) {
                        $profileimage=$this->request->base.'/upload/profile-placeholder.png';
                    }else{
                        //$profileimage=$this->request->base.'/upload/thumbnails/'.$imgstr;
                        $profileimage=$image;
                    }
                     $dob=$data['dob'];
                    $birth = (date('Y') - date('Y',strtotime($dob))). ' Years';
                    
                    ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="mt-card-item">
                        <div class="mt-card-avatar mt-overlay-1" style="height:180px">
                            <img src="<?php echo $profileimage;?>" style="height:180px">
                            <div class="mt-overlay">
                                <ul class="mt-info">
                                   <li>
                                        <a class="btn default btn-outline" href="<?php echo $this->request->base.'/PheramorUser/viewMember/'.$data['id'];?>">
                                            <i class="icon-link"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-card-content">
                            <h3 class="mt-card-name"><?php echo ucfirst($data['first_name'])." ".ucfirst($data['last_name'])?></h3>
                            <p class="mt-card-desc font-grey-mint"><strong>Age: </strong><i><?php echo $birth;?></i></p>
                            <!--<div class="mt-card-social">
                                <ul>
                                    <li>
                                        <a href="<?php echo $data['facebook'];?>" target="_blank">
                                            <i class="icon-social-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $data['twitter'];?>" target="_blank">
                                            <i class="icon-social-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                       <a href="<?php echo $this->request->base.'/PheramorUser/viewMember/'.$data['id'];?>" target="_blank">
                                            <i class=" icon-link"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>-->
                        </div>
                    </div>
                </div>
                <?php } ?>
              
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script>
$(document).ready(function(){
    
   ///JAM
        $('#monthlySalesRevenue').highcharts({
            chart : {
                style: {
                    fontFamily: 'Open Sans'
                }
            },
            title: {
                    text: 'Monthly Sales Revenue',
                    x: -20 //center
            },
            subtitle: {
                style: {
                   fontSize: '16px',
                   //color:'#f7a35c',
                  // TextAlign:'Left',
                   textTransform: 'uppercase',
                   textAnchor:'start',

                },
               text: '<strong><?php echo $month1; ?></strong>: $ <?php echo $f_month_amt; ?>\n',
               x: 350,

            },
            xAxis: {
                categories: [<?php echo $monString; ?>]
            },
            yAxis: {
                title: {
                        text: 'Sales ($)'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                }]
            },
            tooltip: {
                    valuePrefix: '$'
            },
            legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
            },
            series: [{
                    name: '<?php echo $month1; ?>',
                    data: [<?php echo $first_month_data; ?>]
            }]
	});
        ////// 
        
         var chart = AmCharts.makeChart("dashboard_amchart_41", {
                "type": "pie",
                "theme": "light",
                "path": "../assets/global/plugins/amcharts/ammap/images/",
                "dataProvider": <?php echo $subscription_graph;?>,
                "valueField": "value",
                "titleField": "subscription",
                "outlineAlpha": 0.4,
                "depth3D": 15,
                "precision": 2,
                "balloonText": "[[title]]<br><span style='font-size:14px'><b>$ [[value]]</b> ([[percents]]%)</span>",
                "angle": 30,
                "export": {
                    "enabled": true
                }
            });
            
  ////
         var chart = AmCharts.makeChart("dashboard_amchart_3", {
                "type": "serial",
                "addClassNames": true,
                "theme": "light",
                "path": "../assets/global/plugins/amcharts/ammap/images/",
                "autoMargins": false,
                "marginLeft": 30,
                "marginRight": 8,
                "marginTop": 10,
                "marginBottom": 26,
                "balloon": {
                    "adjustBorderColor": false,
                    "horizontalPadding": 10,
                    "verticalPadding": 8,
                    "color": "#ffffff"
                },

                "dataProvider":<?php echo $subscription_piller_graph;?>,
                "startDuration": 1,
                "graphs": [{
                    "alphaField": "alpha",
                    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>$[[value]]</span> [[additional]]</span>",
                    "fillAlphas": 1,
                    "title": "Income",
                    "type": "column",
                    "valueField": "income",
                    "dashLengthField": "dashLengthColumn"
                }, {
                    "id": "graph2",
                    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>$[[value]]</span> [[additional]]</span>",
                    "bullet": "round",
                    "lineThickness": 3,
                    "bulletSize": 7,
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "useLineColorForBulletBorder": true,
                    "bulletBorderThickness": 3,
                    "fillAlphas": 0,
                    "lineAlpha": 1,
                    "title": "Expenses",
                    "valueField": "expenses"
                }],
                "categoryField": "year",
                "categoryAxis": {
                    "gridPosition": "start",
                    "axisAlpha": 0,
                    "tickLength": 0
                },
                "export": {
                    "enabled": true
                }
            });
       



});
</script>
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>
  $( function() {
    var progressbar = $( "#progressbar" ),
      progressLabel = $( ".progress-label" );
 
    progressbar.progressbar({
      value: false,
      change: function() {
        progressLabel.text( progressbar.progressbar( "value" ) + "%" );
      },
      complete: function() {
        progressLabel.text( "Complete!" );
      }
    });
 
    function progress() {
      var val = progressbar.progressbar( "value" ) || 0;
 
      progressbar.progressbar( "value", val + 2 );
 
      if ( val < 99 ) {
        setTimeout( progress, 80 );
      }
    }
 
    setTimeout( progress, 2000 );
  } );
  </script>
  
  <div id="progressbar"><div class="progress-label">Loading...</div></div>-->