<?php
//echo $this->Html->css('select2.css');
//echo $this->Html->script('select2.min');
$bradcrumb = ($edit) ? 'Edit Notes' : 'Add Notes';
$this->Html->addCrumb('Notes List', array('controller' => 'CustomerNotes', 'action' => 'customerNotesList'));
$this->Html->addCrumb($bradcrumb);
//echo $this->Html->css('select2.css');
//echo $this->Html->script('select2.min');
?>

	<div class="col-md-12">	
             <div class="portlet light portlet-fit portlet-form bordered">
                 
                 <div class="portlet-title">
                     <div class="caption">
                         <i class=" icon-layers font-red"></i>
                         <span class="caption-subject font-red sbold uppercase"><?php echo __("Add Member Notes");?></span>
                     </div>
                     <div class="top">

                         <div class="btn-set pull-right">
                             <a href="<?php echo $this->Gym->createurl("CustomerNotes","customerNotesList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Member Notes List"); ?></a>

                         </div>
                     </div>

                 </div>
                 
                 
		<div class="portlet-body">
		<div class="box-body">					
		<form class="validateForm form-horizontal" method="post" role="form">		
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php  echo __("Note Title");?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" value="<?php echo ($edit) ? $data["note_title"] : ""; ?>" placeholder="Enter note title"  name="note_title">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter note title...</span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Note For"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <?php
                                echo @$this->Form->select("note_for", $note_for_arr, [ "class" => "form-control validate[required]","disabled"=>"disabled"]);
                                ?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select member for note...</span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Class"); ?>
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <?php 
                                    echo $this->Form->select("class_id",$classes,["empty"=>__("Select Class"),"default"=>($edit)?array($data['class_id']):"","class"=>"form-control"]);
                                 ?>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select class for note...</span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Start Date"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Select start date" name="start_date" class="hasDatepicker form-control validate[required]" value="<?php echo ($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data["start_date"])) : ""; ?>">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select start date...</span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("End Date"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Select end date" name="end_date" class="hasDatepicker form-control validate[required]" value="<?php echo ($edit) ? date($this->Gym->getSettings("date_format"), strtotime($data["end_date"])) : ""; ?>">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Select end date...</span>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1"><?php echo __("Comment"); ?>
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <textarea type="text" name="comment" id="comment" class="form-control validate[required]"><?php echo ($edit)?$data["comment"] : "";?></textarea>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Please enter comment...</span>
                            </div>
                        </div>
                        <hr>
                         <div class="form-group form-md-line-input">
                            <div class="col-md-offset-2 col-md-6">
                                <input type="submit" value="<?php echo __("Save"); ?>" name="save_note" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>
                        
                    </div>
                </form>
                   
		
		<!-- END -->
                </div>
                </div>
             </div>
        </div>
