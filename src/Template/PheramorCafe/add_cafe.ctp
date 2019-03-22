<?php
//echo '<pre>';print_r($staff);
$bradcrumb = ($edit) ? 'Edit Cafe' : 'Add Cafe';
$this->Html->addCrumb('List Cafe', array('controller' => 'PheramorCafe', 'action' => 'index'));
$this->Html->addCrumb($bradcrumb);
$session = $this->request->session()->read("User");

?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#start_date").datepicker({
            todayBtn: 1,
            autoclose: true,
            forceParse: false

         }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#end_date').datepicker('setStartDate', minDate);
        });

  });
</script>

<div class="col-md-12">	
    <div class="portlet light portlet-fit portlet-form bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase"><?php echo $title; ?></span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Gym->createurl("PheramorCafe", "index"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Cafe List"); ?></a>

                </div>
            </div>

        </div>
        <div class="portlet-body">
            <div class="box-body">
                <?php
                     echo $this->Form->create("adduser", ["type" => "file", "class" => "validateForm form-horizontal", "role" => "form"]);
                ?>
                <input type="hidden" id="itsId" value="<?php echo ($edit) ? $cafe['id'] : ''; ?>">
                 
                <div class="form-body">
                   <div class="form-group form-md-line-input">
                    <label class="col-md-3 control-label" for="form_control_1">Type
                        <span class="required" aria-required="true">*</span>
                    </label>
                     <div class="col-md-9">
                         <div class="col-md-9">
                       <select class="form-control validate[required]" name="cafe_type">
                           <option value="">Select Type</option>
                            <option value="Cafe"  <?php  if(($edit) && @$cafe['cafe_type']=='Cafe'){ echo "selected";} ?>>Cafe</option>
                            <option value="Restaurant"  <?php  if(($edit) && @$cafe['cafe_type']=='Restaurant'){ echo "selected";} ?>>Restaurant</option>
                            <option value="Lounge"  <?php  if(($edit) && @$cafe['cafe_type']=='Lounge'){ echo "selected";} ?>>Lounge</option>
                            <option value="Bakery"  <?php  if(($edit) && @$cafe['cafe_type']=='Bakery'){ echo "selected";} ?>>Bakery</option>
                            <option value="Bar"  <?php  if(($edit) && @$cafe['cafe_type']=='Bar'){ echo "selected";} ?>>Bar</option>
                           </select>
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Select Cafe Type...</span>
                    </div>
                  </div>
                </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Name
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo(($edit) ? $cafe['title'] : ''); ?>" placeholder="Enter cafe name"  name="title">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter cafe name....</span>
                        </div> </div>
                     </div>
                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Start Date
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control dob validate[required]" value="<?php echo (($edit) ? date($this->Gym->getSettings("date_format"), strtotime($event['start_date'])) : ''); ?>" placeholder="Enter start date"  id="start_date" name="start_date">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter start date....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">End Date
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control dob validate[required]" value="<?php echo (($edit) ? date($this->Gym->getSettings("date_format"), strtotime($event['end_date'])) : ''); ?>" placeholder="Enter end date"  id="end_date" name="end_date">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter end date....</span>
                        </div></div>
                    </div>-->
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Address
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo (($edit) ? $cafe['address'] : ''); ?>" placeholder="Enter address"  name="address">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter address....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">City
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required,custom[onlyLetterSp]]" value="<?php echo (($edit) ? $cafe['city'] : ''); ?>" placeholder="Enter city"  name="city">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter city....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">State
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required,custom[onlyLetterSp]]" value="<?php echo (($edit) ? $cafe['state'] : ''); ?>" placeholder="Enter state"  name="state">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter state....</span>
                        </div></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Zip code
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                         <div class="col-md-9">
                            <input type="text" class="form-control validate[required, custom[integer]]" value="<?php echo (($edit) ? $cafe['zipcode'] : ''); ?>" placeholder="Enter zipcode"  name="zipcode">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter zipcode....</span>
                        </div></div>
                    </div>
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Upload Image
                        </label>
                        <div class="col-md-9"> <div class="col-md-9">
                            <?php
                             echo $this->Form->file("image", ["id"=>"userfile","class" => "form-control validate[custom[validateMIME[image/jpeg|image/png]]]"]);
                            $image = ($edit && !empty($cafe['image'])) ? $cafe['image'] : $this->request->webroot."upload/profile-placeholder.png";
                              echo "<div id='img-div'><br><img width='100' src='{$image}'>";
                            if(!empty($cafe['image']) && $cafe['image'] != $this->request->webroot.'upload/profile-placeholder.png'){
                                echo '<div style="padding:10px;"><span id="del-img" class="label label-success">Remove</span></div>';
                             }
                             echo '</div>';
                            ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div></div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Upload Gallery
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                          <span id='spn_inputs'> 
                         <input type="file" name="myfile[]" id="userfile1" class="validate[custom[validateMIME1[image/jpeg|image/png]]]"><br>
                         <input type="file" name="myfile[]" id="userfile2" class="validate[custom[validateMIME2[image/jpeg|image/png]]]"><br>
                         <input type="file" name="myfile[]" id="userfile3" class="validate[custom[validateMIME3[image/jpeg|image/png]]]"><br>
                          </span>
                            <div class="form-control-focus"> </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="dv_add">  <a href="javascript:void(0);" class="btn blue" id="anc_add_more">Add More File</a></div>
                        </div>
                        <div class="col-md-3">&nbsp;</div>
                        <div class="col-md-9">
                        <?php 
                        if($edit && !empty($gallery_data))
                        {
                             foreach($gallery_data as $gdata)
                             {
                                  echo "<div class='col-md-2' id='gallery-{$gdata['id']}'><img width='100' src='{$gdata['image']}'>";
                                  echo '<div style="padding:10px;"><span data-id="'.$gdata['id'].'" class="del-gallery label label-success">Remove</span></div>';
                                  echo "</div>";
                                   
                             }
                           /// echo  '<div class="col-md-3"> Ashok Singh</div>';  echo  '<div class="col-md-3"> Ashok Singh</div>'; echo  '<div class="col-md-3"> Ashok Singh</div>'; echo  '<div class="col-md-9"> Ashok Singh</div>'; echo  '<div class="col-md-9"> Ashok Singh</div>';
                        }
                        
                        ?>
                        </div>
                        
                    </div>
                       <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Event URL
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                        <div class="col-md-9">
                            <input type="text" class="form-control  validate[custom[url]]" value="<?php echo (($edit) ?$event['event_URL'] : ''); ?>" placeholder="Enter event url "  name="event_URL" >
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Event URL....</span>
                        </div>  </div>
                    </div>-->
                   
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Description
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9 form">
                            <?php echo $this->Form->textarea("description", ["rows" => "15", "class" => "form-control", "value" => ($edit) ? $cafe['description'] : ""]); ?>

                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Description...</span>
                        </div>
                    </div>
                     <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Status
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-6">
                         <div class="col-md-12">
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_8" <?php echo (($edit && $cafe['status'] == '1') ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="status" class="md-radiobtn">
                                    <label for="checkbox111_8">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Active </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="checkbox111_9" <?php echo (($edit && $cafe['status'] == '0') ? "checked" : "") ; ?> value="0" name="status" class="md-radiobtn">
                                    <label for="checkbox111_9">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Inactive </label>
                                </div>

                            </div>
                        </div> </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <div class="col-md-offset-3 col-md-6">
                            <input type="submit" value="<?php echo __("Save"); ?>" name="add_member" class="btn btn-flat btn-primary">
                            <button type="reset" class="btn default">Reset</button>
                        </div>   
                    </div>

                </div>

                <?php echo $this->Form->end(); ?>
               
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function(){
    
    $('#del-img').click(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/PheramorAjax/deleteImageByCafeId'; ?>",
            data: {id: $("#itsId").val()},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#img-div").hide();
                    
                } else if (response.status == 'error') {
                   // $("#associated_trainer").empty().append("<option value=''>Select Trainer</option>");
                    alert(response.msg);
                }
                return false;
            },
            error: function (jqXHR, exception) {
                return false;
            }
        });
       });
       
       
       
        $('.del-gallery').click(function () {
        var ids=$(this).attr("data-id");
        $.ajax({
            type: "POST",
            url: "<?php echo $this->request->base . '/PheramorAjax/deleteImageByCafeGalleryId'; ?>",
            data: {id: ids},
            dataType: "JSON",
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#gallery-"+ids).hide();
                    
                } else if (response.status == 'error') {
                   // $("#associated_trainer").empty().append("<option value=''>Select Trainer</option>");
                    alert(response.msg);
                }
                return false;
            },
            error: function (jqXHR, exception) {
                return false;
            }
        });
       });
        
    
});    
    
</script>

 <script>
        /* JS for Uploader */
        $(function() {
            /* Append More Input Files */
            $('#anc_add_more').click(function() {
                $('#spn_inputs').append('<input type="file" name="myfile[]"><br>');
            });
        });

    </script>