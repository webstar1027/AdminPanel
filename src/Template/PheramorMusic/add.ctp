<?php
$bradcrumb = ($edit) ? 'Edit Category' : 'Add Category';
$this->Html->addCrumb('List Category', array('controller' => 'PheramorSubscriptionCategory', 'action' => 'index'));
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
                    <a href="<?php echo $this->Pheramor->createurl("PheramorSubscriptionCategory", "index"); ?>" class="btn blue"><i class="fa fa-bars"></i> <?php echo __("Category List"); ?></a>

                </div>
            </div>

        </div>
        <div class="portlet-body">
            <?php
            echo $this->Form->create($category, ["type" => "file", "class" => "validateForm form-horizontal", "novalidate" => "novalidate"]);
            ?>

            <div class="form-body">
                <div class="form-group form-md-line-input">
                    <label class="col-md-3 control-label" for="form_control_1">Category Name
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control validate[required]" value="<?php echo ($edit) ? $category_data['category_name'] : ""; ?>" placeholder="Enter Category Name"  name="category_name">
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Enter Category Name...</span>
                    </div>
                </div>
            </div>
            

            <div class="col-md-12">
                &nbsp;


            </div>
            <div class="form-group form-md-line-input">
                <label class="col-md-3 control-label" for="form_control_1">Category Description
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-9 form">
                    <?php echo $this->Form->textarea("category_desc", ["rows" => "15", "class" => "wysihtml5 form-control", "value" => ($edit) ? $category_data['category_desc'] : ""]); ?>

                    <div class="form-control-focus"> </div>
                    <span class="help-block">Enter Description...</span>
                </div>
            </div>
                  
            <div class="form-group form-md-line-input">
                 <label class="col-md-3 control-label" for="form_control_1">Category Status
                     <span class="required" aria-required="true">*</span>
                 </label>
                 <div class="col-md-3">
                     <div class="md-radio-inline">
                         <div class="md-radio">
                             <input type="radio" id="checkbox2_101"<?php echo (($edit && $category_data['activated'] == "1") ? "checked" : "") . ' ' . ((!$edit) ? "checked" : "") ?> value="1" name="activated" class="md-radiobtn">
                             <label for="checkbox2_101">
                                 <span></span>
                                 <span class="check"></span>
                                 <span class="box"></span> Active 
                             </label>
                         </div>
                         <div class="md-radio">
                             <input type="radio" id="checkbox2_111" <?php echo (($edit && $category_data['activated'] == "0") ? "checked" : ""); ?> value="0" name="activated" class="md-radiobtn">
                             <label for="checkbox2_111">
                                 <span></span>
                                 <span class="check"></span>
                                 <span class="box"></span>Inactive </label>
                         </div>

                     </div>
                 </div>
             </div>
               
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn green" name="add_category">Submit</button>
                    <button type="reset" class="btn default">Reset</button>
                </div>
            </div>
            <p>&nbsp;</p>
        </div>
       
        </form>
        <!-- END FORM-->
    </div>
</div>


</div>


