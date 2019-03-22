<?php 
$session = $this->request->session()->read("User");

$this->Html->addCrumb('Add Machine Genetic Data');

?>
<style>
    .select2-container--bootstrap .select2-selection{
        border-bottom: 1px solid #c2cad8;
        border-left: none;
        border-right: none;
        border-top:none;
        box-shadow:none;
    }    
 </style>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Add Machine Genetic Data"); ?>
                </span>
                 
            </div>
            
        </div>
        <div class="portlet-body">
          <?php
            echo $this->Form->create($category, ["type" => "file", "class" => "validateForm form-horizontal", "novalidate" => "novalidate"]);
            ?>

            <div class="form-body">
                <div class="form-group form-md-line-input">
                    <label class="col-md-2 control-label" for="form_control_1">Genetic Category
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-4">
                        <select class="bs-select form-control validate[required]" name="generic_type">
                           <option value="">Select Genetic Category</option>
                            <option value="pheramor_HLAA">HLAA</option>
                            <option value="pheramor_HLAB">HLAB</option>
                            <option value="pheramor_HLAC">HLAC</option>
                            <option value="pheramor_HLADPB1">HLADPB1</option>
                            <!--<option value="pheramor_HLADPB2">HLADPB2</option>-->
                            <option value="pheramor_HLADQB1">HLADQB1</option>
                            <option value="pheramor_HLADRB">HLADRB</option>
                           </select>
                        <!--<input type="text" class="form-control " value="<?php echo ($edit) ? $cat_data['category_name'] : ""; ?>" placeholder="Enter Category Name"  name="category_name">-->
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Select Genetic Category...</span>
                    </div>
                    <label class="col-md-2 control-label" for="form_control_1">Gene1
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-4">
                        <input type="text" class="form-control validate[required]" value="" placeholder="Enter Gene1 Data"  name="gene1">
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Enter Gene1 Data...</span>
                    </div>
                </div>
                <div class="form-group form-md-line-input">
                    
                   <label class="col-md-2 control-label" for="form_control_1">Gene2
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-4">
                        <input type="text" class="form-control validate[required]" value="" placeholder="Enter Gene2 Data"  name="gene2">
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Enter Gene2 Data...</span>
                    </div> 
                    
                    <label class="col-md-2 control-label" for="form_control_1">Dissimilarity
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-4">
                        <input type="text" class="form-control validate[required]" value="" placeholder="Enter Dissimilarity Data"  name="dissimilarity">
                        <div class="form-control-focus"> </div>
                        <span class="help-block">Enter Dissimilarity Data...</span>
                    </div> 
                    
                </div>
            </div>
            
             <div class="row">
                <div class="col-md-offset-2 col-md-6">
                    <button type="submit" class="btn green" name="add_category">Submit</button>
                    <a href="<?php echo $this->Gym->createurl("PheramorImport","addMachineGeneticData"); ?>" class="btn default">Reset</a> 
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
                <span class="caption-subject bold uppercase"> <?php echo __("Machine Genetic Data"); ?>
                </span>
                 
            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>
        <div class="portlet-body">
         <table id="employee-grid"  cellpadding="0" cellspacing="0" border="0" class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" width="100%">
                <thead>
                        <tr>
                                <th>S.N.</th>
                                <th>Gene1</th>
                                <th>Gene2</th>
                                <th>Dissimilarity</th>
                                <th>Genetic Category</th>
                        </tr>
                </thead>
	</table>
           
        </div>
    </div>

    
    
    	
    
</div>
 
<script type="text/javascript" language="javascript" >
$(document).ready(function() {
        var dataTable = $('#employee-grid').DataTable( {
                "processing": true,
                "serverSide": true,
               // "columnDefs": [
                   // { "orderable": false, targets: [0,4] }
                //  ],
                "ajax":{
                        url :"<?php echo $this->request->base . '/PheramorAjax/setGeneticData'; ?>", // json datasource
                        type: "post",  // method  , by default get
                        error: function(){  // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="5">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display","none");

                        }
                }
        } );
} );
                </script>

