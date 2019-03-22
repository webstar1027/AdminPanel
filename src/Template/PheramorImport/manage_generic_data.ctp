<?php 
$session = $this->request->session()->read("User");

$this->Html->addCrumb('User Genetic Data');

?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("User Genetic Data"); ?>
                </span>
                 
            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>
        <div class="portlet-body">

          
             <table id="employee-grid"  cellpadding="0" cellspacing="0" border="0" class="employee-grid mydataTable table table-striped table-bordered table-hover table-checkable order-column" width="100%">
					<thead>
						<tr>
                                                        <th>S.N.</th>
							<th>Pheramor ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
							<th>Status</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
						</tr>
					</thead>
	  </table>
            
            
        </div>
    </div>

    
    
    	
    
</div>
<script type="text/javascript" language="javascript" >
$(document).ready(function() {
        
        
        var dataTable = $('.employee-grid').DataTable( {
                "processing": true,
                "serverSide": true,
                "bRetrieve": true,
               // "columnDefs": [
                   // { "orderable": false, targets: [0,4] }
                //  ],
                "ajax":{
                        url :"<?php echo $this->request->base . '/PheramorAjax/getUserGenericData'; ?>", // json datasource
                        type: "post",  // method  , by default get
                        error: function(){  // error handling
                                $(".employee-grid-error").html("");
                                $(".employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display","none");

                        }
                }
        } );
        
       
        
} );



 $("body").on("click",".add-generic-password",function(){
	var password = $(".generic_pass_val").val();
        var mid = $("#pheramor_id").val();
	var ajaxurl = $(this).attr("data-url");
	if(password != "")
	{
		var curr_data = { password : password, id:mid};
		$.ajax({
			url : ajaxurl,
			type : "POST",
			data : curr_data,
			success : function(response){					
					if(response)
					{
                                              $("#main_gen_data").html(response);
                                       }else{
                                            $("#generic_response").html('<span style="color:red;">Your password is not valid!</span>');
                                           
                                        }
			}
		});		
	}else{
		alert("Please Enter Generic Data Password.");
	}
	
});


/// Reset Gentic KIT ID


$("body").on("click",".resetgenticID",function(e){
if (confirm('Are you sure you want to reset this generic ID?')) {
    e.preventDefault();
    var user_id= $(this).attr('data-id');
    var kit_id= $(this).attr('data-kit-id');
     
      // $('#employee-grid').DataTable().row(DeletedRow).remove().draw();
//       var row = $(this).closest("tr").get(0);
//         oTable.fnDeleteRow(DeletedRow);
//         console.log(oTable);
        
       // 
    
         
       $.ajax({
            url: '<?php echo $this->request->base . '/PheramorAjax/resetUserGenericID'; ?>',
            type: "POST",
            data: {
                'user_id':user_id,'kit_id':kit_id
            },
            success: function () {
            //  $(".page-content-inner").load('<?php echo $this->request->base . '/pheramor-import/manage-generic-data'; ?>');
              
           var oTable = $('#employee-grid').dataTable();
           var  DeletedRow = $(this).parents('tr');
           var nRow = $(this).parents('tr')[0];
       // $('table tr:eq(1)').remove();
            oTable.fnDeleteRow(DeletedRow);
     //   oTable.fnDeleteRow(nRow);
        
       

            }
        });
    }



});


                </script>