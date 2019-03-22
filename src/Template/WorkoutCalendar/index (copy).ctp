<?php
echo $this->Html->css('fullcalendar');

echo $this->Html->css('assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css');
echo $this->Html->css('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css');
echo $this->Html->css('assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css');

echo $this->Html->script('fullcalendar.min');

echo $this->Html->script('assets/global/plugins/jquery.mockjax.js');
echo $this->Html->script('assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js');
echo $this->Html->script('assets/global/plugins/bootstrap-editable/inputs-ext/address/address.js');
echo $this->Html->script('assets/global/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js');
echo $this->Html->script('assets/pages/scripts/form-editable.js');


//BEGIN PAGE LEVEL PLUGINS

echo $this->Html->script('assets/pages/scripts/charts-amcharts.min.js');


echo $this->Html->script('lang-all');
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Workout Calendar');
$base_url = $this->request->base;
?>
<?php //echo json_encode($cal_array); die;  ?>

<!-- Calender-->
<div class="workout-calender-chart">
    <div class="col-xs-12 col-sm-8 workout-calender-chart-left">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-calendar-plus-o"></i><?php echo __("Workout Calendar"); ?> </div>

            </div>
            <div class="portlet-body">

                <div id="calendars"></div>
            </div>

        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="col-xs-12  text-center your-goals">Active Goals</div>
        <?php
        
        foreach($active_goals as $active_goal){
        ?>
        <div class="col-xs-12 your-goals-dashborad">
            <header>
                <div class="col-xs-6">
                    <i class="fa fa-bullseye" style="font-weight:bold;"></i> <strong>Active Goals</strong>
                </div>
                <div class="col-xs-6 text-right dashborad-date">Start Date: <?php echo $active_goal['startDate'];?></div>
                <div class="clearfix"></div>
            </header>
            <?php 
            $targets = json_decode($active_goal['target'],true);
            $initValues = json_decode($active_goal['initValues'],true);
            foreach($targets as $targetIndex=>$targetVal){
                if($targetVal < $initValues[$targetIndex]){
                    $lossGain = 'Lose';
                    $diff = $initValues[$targetIndex] - $targetVal;
                }else{
                    $lossGain = 'Gain';
                    $diff = $targetVal - $initValues[$targetIndex];
                }
            ?>
            <article>
                <div class="col-xs-8"><?php  echo $this->Gym->getTargetKeys($targetIndex);?>   <?php echo $lossGain;?></div>
                <div class="col-xs-4 text-right"><?php echo $diff ;?> <?php echo ( $this->Gym->getUnit('imperial',$targetIndex) ) ? $this->Gym->getUnit('imperial',$targetIndex) : '';?></div>
                <div class="clearfix"></div>
            </article>
        <?php }?>
        </div>
        <?php }?>
    </div>
    <div class="clearfix"></div>
</div>
<!-- Workout Modal -->
<div id="WorkoutModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Workout Completed</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN CHART PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-bar-chart font-green-haze"></i>
                                    <span class="caption-subject bold uppercase font-green-haze" id="workout_date"></span>
                                    <span class="caption-helper">Completed</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="workout_chart" class="chart" style="height: 400px;"> </div>
                            </div>
                        </div>
                        <!-- END CHART PORTLET-->
                    </div>
                    <div class="col-md-6 col-sm-8">
                        <div class="portlet green-meadow box">
                            <div class="portlet-title">
                                <div class="caption">
                                    EMILY ACEVES 
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row static-info">
                                    <div class="col-md-12 value">
                                        <p><i class="fa fa-heartbeat"></i><span> AVERAGE HR</span> <span class="pull-right" id="averageHr"></span></p>
                                        <p><i class="fa fa-gamepad "></i><span>  GO POINTS</span> <span class="pull-right" id="points"></span></p>
                                        <p><i class="fa fa-free-code-camp"></i><span> CALORIES BURN</span><span class="pull-right" id="calorie"></span> </p>
                                        <p><i class="fa fa-clock-o"></i><span> WORK OUT DURATION</span><span class="pull-right" id="duration"></span></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-4">
                            <div class="easy-pie-chart">
                                <div class="number transactions" data-percent="55">
                                    <span id="avragePercentage"></span>% </div>
                                <span id="avragePercentage"></span>% </div>
                            <a class="title" href="javascript:;"> Average Percentage

                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>

<!-- Measurement Modal -->
<div id="MeasurementModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Measurements</h4>
            </div>
            <div class="modal-body">
                <div class="portlet light portlet-fit bordered">
                    <!--<div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Editable Form</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                <label class="btn btn-transparent dark btn-outline btn-circle btn-sm active">
                                    <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                                <label class="btn btn-transparent dark btn-outline btn-circle btn-sm">
                                    <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                            </div>
                        </div>
                    </div>-->
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12" id="measurement_workout_details_div">
                                <table id="user" class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td> WEIGHT </td>
                                            <td>
                                                <a href="javascript:;" id="weight" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Weight"> </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"> CALIPER </td>
                                        </tr>

                                        <tr>
                                            <td> BICEP </td>
                                            <td>
                                                <a href="javascript:;" id="caliperBicep" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Bicep"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> TRICEP </td>
                                            <td>
                                                <a href="javascript:;" id="triceps" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Tricep">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> SUBSCAPULRA </td>
                                            <td>
                                                <a href="javascript:;" id="subscapular" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Subscapular">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> ILIAC CREST </td>
                                            <td>
                                                <a href="javascript:;" id="iliacCrest" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Lliac Crest">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> BODY FAT </td>
                                            <td>
                                                <a href="javascript:;" id="bodyFat" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Body Fat">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> LEAN BODY MASS </td>
                                            <td>
                                                <a href="javascript:;" id="leanBodyMass" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Lean Body Mass">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> WATER WEIGHT </td>
                                            <td>
                                                <a href="javascript:;" id="waterWeight" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Water Weight"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> BONE DENSITY </td>
                                            <td>
                                                <a href="javascript:;" id="boneDensity" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Bone Density"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> CIRCUMFERENCE </td>
                                        </tr>
                                        <tr>
                                            <td> NECK </td>
                                            <td>
                                                <a href="javascript:;" id="neck" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Neck"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> CHEST </td>
                                            <td>
                                                <a href="javascript:;" id="chest" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Chest">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> BICEP </td>
                                            <td>
                                                <a href="javascript:;" id="circumferenceBicep" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Circumference Bicep"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> FOREARM </td>
                                            <td>
                                                <a href="javascript:;" id="forearm" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Forearm"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> WAIST </td>
                                            <td>
                                                <a href="javascript:;" id="waist" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Waist"> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> HIP </td>
                                            <td>
                                                <a href="javascript:;" id="hip" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Hip">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> THIGH </td>
                                            <td>
                                                <a href="javascript:;" id="thigh" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Thigh">  </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> CALF </td>
                                            <td>
                                                <a href="javascript:;" id="calf" data-type="text" data-pk="1" data-placement="right" data-placeholder="" data-original-title="Enter Your Calf">  </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div>
                            <img src="<?php echo $this->request->base; ?>/webroot/upload/profile-placeholder.png" width="150" title="" class="img-responsive">
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
        $('#calendars').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            lang: 'en',
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: <?php echo json_encode($cal_array); ?>,
            eventClick: function (event, jsEvent, view) {
                //console.log('event:',event);
                //console.log('jsEvent:',jsEvent);
                //console.log('view:',view);
                //console.log(event.start._i);

                var title = event.title;
                var titleUnique = event.titleUnique;
                var uniqueId = event.uniqueId;
                var date = event.start._i;
                var ajaxurl = "<?php echo $base_url; ?>" + "/GymAjax/get" + titleUnique + "Details";
                //alert('title: ' + title + ' ------- date: ' + date + ' ---- ajaxUrl: ' + ajaxurl);
                var curr_data = {date: date, title: titleUnique, id: uniqueId};
                $.ajax({
                    url: ajaxurl,
                    data: curr_data,
                    type: "POST",
                    dataType: "JSON",
                    success: function (result) {
                        if (result.status == 'success') {

                            if (result.title == 'Measurement') {
                                console.log(result.data);
                                $.each(result.data, function (index, value) {
                                    var unit = getUnit('imperial', index);
                                    if (unit !== false) {
                                        //console.log(unit);
                                        $('.modal-body #' + index).text(value + ' ' + unit);
                                    }
                                });
                            }
                            if (result.title == 'Workout') {
                                var ms = result.data.duration;
                                var y = 60 * 60 * 1000;
                                var h = ms / y;
                                var m = (ms - (h * y)) / (y / 60);
                                var s = (ms - (h * y) - (m * (y / 60))) / 1000;

                                $('#workout_date').text(moment(result.data.createdAt).format('ddd MMM DD, YYYY HH:mm'));
                                initializeChart(result.data.zonesDuration, result.data.duration);
                                $('#duration').text(result.data.duration);
                                $('#points').text(result.data.points);
                                $('#calorie').text(result.data.calorie + ' Kcal');
                                $('#averageHr').text(result.data.averageHr);
                                $('#avragePercentage').text((((result.data.averageHr) * 100) / result.data.averageMaxHr).toFixed(2));
                            }

                            $('#' + titleUnique + 'Modal').modal('show');

                        } else {
                            alert('There is no ' + titleUnique + 'added.');
                        }
                        return false;
                    }
                });
            }
        });

        $(".fc-state-highlight").wrapInner("<div class='today-date'></div>");
    });

    function getUnit(unitType, data) {
        var lbsItem = ["weight", "leanBodyMass", "boneDensity"];
        var milimeterItem = ["caliperBicep", "triceps", "subscapular", "iliacCrest"];
        var inchItem = ["neck", "chest", "circumferenceBicep", "forearm", "waist", "hip", "thigh", "calf"];
        var percentItem = ["bodyFat", "waterWeight"];

        if ($.inArray(data, lbsItem) !== -1)
            return (unitType == 'imperial') ? 'lbs' : 'kg';
        else if ($.inArray(data, milimeterItem) !== -1)
            return (unitType == 'imperial') ? 'mm' : 'mm';
        else if ($.inArray(data, inchItem) !== -1)
            return (unitType == 'imperial') ? 'in' : 'in';
        else if ($.inArray(data, percentItem) !== -1)
            return '%';
        else
            return false;
    }

</script>
<script>
    function initializeChart(zonesDuration,duration) {
        console.log(zonesDuration);
        //var value2=100;
        var zonesDurationArr = zonesDuration.split(',');
        AmCharts.makeChart("workout_chart", {
            "theme": "light",
            "type": "serial",
            "startDuration": 2,
            "fontFamily": 'Open Sans',
            "color": '#888',
            "dataProvider": [
                {
                    "country": "ZONE 1",
                    "visits": zonesDurationArr[0],
                    "color": "#575757",
                    "percent": ( (zonesDurationArr[0]*100) / duration ).toFixed(2) + "%"
                },
                {
                    "country": "ZONE 2",
                    "visits": zonesDurationArr[1],
                    "color": "#2199BE",
                    "percent": ( (zonesDurationArr[1]*100) / duration ).toFixed(2) + "%"
                }, {
                    "country": "ZONE 3",
                    "visits": zonesDurationArr[2],
                    "color": "#3CC24F",
                    "percent": ( (zonesDurationArr[2]*100) / duration ).toFixed(2) + "%"
                }, {
                    "country": "ZONE 4",
                    "visits": zonesDurationArr[3],
                    "color": "#F7A80A",
                    "percent": ( (zonesDurationArr[3]*100) / duration ).toFixed(2) + "%"
                }, {
                    "country": "ZONE 5",
                    "visits": zonesDurationArr[4],
                    "color": "#EA4221",
                    "percent": ( (zonesDurationArr[4]*100) / duration ).toFixed(2) + "%"
                }],
            "valueAxes": [{
                    "position": "left",
                    "axisAlpha": zonesDurationArr[0],
                    "gridAlpha": 0
                }],
            "graphs": [{
                    "balloonText": "[[category]]: <b>[[percent]]</b>",//
                    "colorField": "color",
                    "fillAlphas": 0.85,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "topRadius": 1,
                    "valueField": "visits"
                }],
            "depth3D": 40,
            "angle": 10,
            "chartCursor": {
                "categoryBalloonEnabled": false,
                "cursorAlpha": 0,
                "zoomable": false
            },
            "categoryField": "country",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "gridAlpha": 0

            },
            "exportConfig": {
                "menuTop": "20px",
                "menuRight": "20px",
                "menuItems": [{
                        "icon": '<?php echo $this->request->base; ?>/lib/3/images/export.png',
                        "format": 'png'
                    }]
            }
        }, 0);
    }

    jQuery('.chart_5_chart_input').off().on('input change', function () {
        var property = jQuery(this).data('property');
        var target = chart;
        chart.startDuration = 0;

        if (property == 'topRadius') {
            target = chart.graphs[0];
        }

        target[property] = this.value;
        chart.validateNow();
    });

    $('#workout_chart').closest('.portlet').find('.fullscreen').click(function () {
        chart.invalidateSize();
    });
</script>