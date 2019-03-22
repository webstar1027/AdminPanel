<?php
$bradcrumb = ($edit) ? 'Edit Group' : 'Add Group';
$this->Html->addCrumb('List Groups', array('controller' => 'GymGroup', 'action' => 'GroupList'));
$this->Html->addCrumb($bradcrumb);
?>
<script>
    $(document).ready(function () {

        var box_height = $(".box").height();
        var box_height = box_height + 300;
        $(".content-wrapper").css("height", box_height + "px");
        $(".content-wrapper").css("min-height", "500px");
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
                    <a href="<?php echo $this->Gym->createurl("GymGroup", "GroupList"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Group List"); ?></a>

                </div>
            </div>

        </div>

        <div class="portlet-body">
            <div class="box-body">

                <?php
                echo $this->Form->create("addgroup", ["type" => "file", "class" => "validateForm"]);
                ?>

                <div class="form-body">

                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Group Name
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control validate[required]" value="<?php echo ($edit) ? $data['name'] : ""; ?>" placeholder="Enter Group Name"  name="name">
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Group Name...</span>
                        </div>
                    </div>
                    
                    <div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Group Description
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9 form">
                              <?php  echo $this->Form->textarea("description", ["rows" => "15", "class" => "wysihtml5 form-control", "value" => ($edit) ? $data['description'] : ""]); ?>
                                 
                               <div class="form-control-focus"> </div>
                            <span class="help-block">Enter Group Description...</span>
                        </div>
                    </div>

                    <!--<div class="form-group form-md-line-input">
                        <label class="col-md-3 control-label" for="form_control_1">Group Image
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div class="col-md-9">
                            <?php echo $this->Form->file("image", ["class" => "form-control"]); ?>
                            <div class="form-control-focus"> </div>
                            <span class="help-block">Enter group image...</span>
                            <?php
                            $url = (isset($data['image']) && $data['image'] != "") ? $this->request->webroot . "upload/" . $data['image'] : $this->request->webroot . "upload/no_image_placeholder.png";
                            echo "<br><br>";
                            echo "<img src='{$url}' class='membership-img' width='100'>";
                            echo "<br><br>";
                            ?>
                        </div>
                    </div>-->
                        <?php
                        echo $this->Form->button(__("Save Group"), ['class' => "btn btn-flat btn-primary", "name" => "add_group"]);
                        echo '&nbsp;<button type="reset" class="btn default">Reset</button>';
                        echo $this->Form->end();
                        ?>


                </div>	

            </div>
        </div>
    </div>
</div>

