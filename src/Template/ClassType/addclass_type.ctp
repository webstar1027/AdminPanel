<?php
$bradcrumb = ($edit) ? 'Edit Class Type' : 'Add Class Type';
$this->Html->addCrumb('Class Type List', array('controller' => 'ClassType', 'action' => 'classtypeList'));
$this->Html->addCrumb($bradcrumb);
?>
<div class="col-md-12">	
    <div class="portlet light portlet-fit portlet-form bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red sbold uppercase"><?php echo $title; ?></span>
            </div>
            <div class="top">

                <div class="btn-set pull-right">
                    <a href="<?php echo $this->Gym->createurl("ClassType", "ClasstypeList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Class Type List"); ?></a>

                </div>
            </div>

        </div>

        <div class="portlet-body">
            <div class="box-body">

                <form class="validateForm form-horizontal" method="post" role="form">	
                    <div class="form-body">

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Title
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" value="<?php echo ($edit) ? $data["title"] : ""; ?>" placeholder="Enter Class Title"  name="title">
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter Class Title...</span>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Description
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-9">
                                <textarea name="description" id="description" class="form-control" ><?php echo ($edit) ? $data["description"] : ""; ?></textarea>
                                <div class="form-control-focus"> </div>
                                <span class="help-block">Enter Class Description...</span>

                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label" for="form_control_1">Status
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-6">
                                <div class="md-radio-inline">
                                    <div class="md-radio">
                                        <input type="radio" id="checkbox1_8" <?php echo (($edit && $data['status'] == 1) ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="status" class="check_limit md-radiobtn">
                                        <label for="checkbox1_8">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Active </label>
                                    </div>
                                    <div class="md-radio">
                                        <input type="radio" id="checkbox1_9" <?php echo (($edit && $data['status'] == 0) ? "checked" : ""); ?> value="0" name="status" class="check_limit md-radiobtn">
                                        <label for="checkbox1_9">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Inactive </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <div class="col-md-offset-2 col-md-6">
                                <input type="submit" value="<?php echo __("Save"); ?>" name="save_classtype" class="btn btn-flat btn-primary">
                                <button type="reset" class="btn default">Reset</button>
                            </div>   
                        </div>
                        <?php
                        echo $this->Form->end();
                        ?>


                    </div>	

            </div>
        </div>
    </div>
</div>




