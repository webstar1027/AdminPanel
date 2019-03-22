<?php
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Import Generic Data');
echo $this->Html->css('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css');
echo $this->Html->script('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js');
?>
<style>
 .btn.default:not(.btn-outline) {
    color: #666;
    background-color: #e1e5ec !important;
    border-color: #e1e5ec !important;
}
.btn.red:not(.btn-outline) {
    color: #fff;
    background-color: #e7505a !important;
    border-color: #e7505a !important;
}
</style>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> Upload Generic Data
                </span>
                 
            </div>
            
        </div>
        <div class="portlet-body">
          <?php
            echo $this->Form->create($category, ["type" => "file", "class" => "validateForm form-horizontal", "novalidate" => "novalidate"]);
            ?>

            <div class="form-body">
                <div class="form-group form-md-line-input">
                    <label class="col-md-2 control-label" for="form_control_1">Upload File
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-4">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="input-group input-large">
                            <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                <span class="fileinput-filename"> </span>
                            </div>
                            <span class="input-group-addon btn default btn-file">
                                <span class="fileinput-new "> Select file </span>
                                <span class="fileinput-exists"> Change </span>
                                <input type="file" name="user_genetic_data" class="validate[required]"> </span>
                              <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                        </div>
                    </div>
                                                                 
                    </div>
                    <label class="col-md-4 control-label" for="form_control_1">Download sample data here
                        <span class="" aria-required="true" style="font-size:23px;"><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/upload/generic/sample-generic-data.csv"><i class="fa fa-download"></i></a></span>
                    </label>

                </div>
               
            </div>
            
             <div class="row">
                <div class="col-md-offset-2 col-md-6">
                    <button type="submit" class="btn green" name="add_category">Submit</button>
                    <a href="<?php echo $this->Gym->createurl("PheramorImport","importGenericData"); ?>" class="btn default">Reset</a> 
                </div>
            </div>
            <p>&nbsp;</p>     
        </form>
        
            
            
        </div>
    </div>

    
    
    	
    
</div>

<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Generic Data List"); ?>
                </span>
                 
            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>
        <div class="portlet-body">
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    

                    <tr>
                        <th><?php echo __("S.N."); ?></th>
                        <th><?php echo __("Author Name"); ?></th>
                        <th><?php echo __("File"); ?></th>
                        <th><?php echo __("Uploaded Date"); ?></th>
                         <th><?php echo __("File Name"); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                   <?php
                   
                    $k = 1;
                    foreach ($category_data as $category) {
                       $downloadfile="http://".$_SERVER['HTTP_HOST'].$category->file_name;
                        $author=$this->Pheramor->get_user_details($category->author_id);
                        $name=$author->pheramor_user_profile[0]['first_name']." ".$author->pheramor_user_profile[0]['last_name']; 
                        echo "<tr class='gradeX odd'><td>{$k}</td><td>{$name}</td>";
                         echo "<td><a href='{$downloadfile}'>Download</td>";
                         echo "<td>" . date('Y-m-d H:i:s',strtotime($category->created_date)). "</td>";
                          echo "<td>" . $category->upload_file_name. "</td>";
                         echo "</tr>";
                        $k++;
                    }
                    ?>

                    


                </tbody>
            </table>
        </div>
    </div>

    
    
    	
    
</div>

