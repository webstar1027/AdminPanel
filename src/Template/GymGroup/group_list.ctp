<?php 
$session = $this->request->session()->read("User");
$session = $this->request->session()->read("User");
$this->Html->addCrumb('List Group');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Group List"); ?>
                </span>
                 
            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <?php
                            if ($session["role_name"] == "administrator" || $session["role_name"] == "licensee" || $session["role_name"] == "manager" || $session["role_name"] == "subadmin" || $session["role_name"] == "admin") {
                                ?>

                                <a href="<?php echo $this->Gym->createurl("GymGroup","addGroup"); ?>" class="btn sbold green"><?php echo __("Add Group"); ?> <i class="fa fa-plus"></i></a>

                        <?php } ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    

                    <tr>
                        <th><?php echo __("Serial No."); ?></th>
                        <!--<th style="width:18%"><?php echo __("Photo");?></th>-->
                        <th><?php echo __("Group Name");?></th>
                        <th><?php echo __("Total Group Members");?></th>					
                        <th><?php echo __("Action");?></th>
                    </tr>
                   
                </thead>
                <tbody>
                   <?php         $k=1;
                                 $total_count=0;
				foreach($data as $row)
			           {
				$image = ($row['image'] == "") ? "no_image_placeholder.png" : $row['image'];
                                $total_count=$total_count+$this->Gym->get_total_group_members($row["id"]);
				echo "
				<tr>
                                        <td>{$k}</td>
					<!--<td><img src='".$this->request->webroot ."upload/{$image}' width='100' height='100' class='membership-img img-circle'></img></td>-->
					<td>{$row['name']}</td>
					<td>".$this->Gym->get_total_group_members($row["id"])."</td>
                                        <td>";
                                        echo "<div class='btn-group'>
                                               <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                   <i class='fa fa-angle-down'></i>
                                               </button>
                                               <ul class='dropdown-menu pull-right' role='menu'>
                                                   <li>
                                                    <a href='javascript:void(0)' data-url='".$this->request->base ."/GymAjax/viewGroupMember/{$row['id']}' class='view_jmodal' id={$row['id']}>
                                                        <i class='icon-eye'></i> View Group</a>
                                                   </li>
                                                    <li>
                                                    <a href='{$this->Gym->createurl('GymGroup','pdfView')}/2/{$row['id']}'>
                                                        <i class='fa fa-file-pdf-o'></i> Download PDF</a>
                                                   </li>
                                                       <li>
                                                    <a href='{$this->Gym->createurl('GymGroup','exportExcel')}/{$row['id']}'>
                                                        <i class='fa fa-file-excel-o'></i> Download Excel</a>
                                                   </li>";
                                                       if ( ($session["role_id"] != 1  && $row['created_by'] != $session["id"] ) || ($row['id'] == 1 || $row['id'] == 2) ) {
                                                           echo "<li>
                                                                <a href='javascript:void(0)' onclick=\"alert('You do not have permission to edit this record')\">
                                                                    <i class='icon-pencil'></i> Edit Group </a>
                                                                 </li>
                                                                 <li>
                                                                <a href='javascript:void(0)' onclick=\"alert('You do not have permission to delete this record')\">
                                                                    <i class='icon-trash'></i> Delete Group </a>
                                                            </li>";
                                                       }else{
                                                           echo "<li>
                                                       <a href='{$this->Gym->createurl('GymGroup','editGroup')}/{$row['id']}'>
                                                           <i class='icon-pencil'></i> Edit Group </a>
                                                   </li>
                                                   <li>
                                                       <a href='{$this->Gym->createurl("GymGroup","deleteGroup")}/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                           <i class='icon-trash'></i> Delete Group </a>
                                                   </li>";
                                                  }

                                               echo " </ul></div></td></tr>";
                                        $k++;
			        }
				?>
                   
                    


                </tbody>
                  <thead>
                    

                    <tr>
                        <th></th>
                        <!--<th style="width:18%"><?php echo __("Photo");?></th>-->
                        <th></th>
                        <th><?php echo __("Total : ").$total_count;?></th>					
                        <th></th>
                    </tr>
                   
                </thead>          

            </table>
        </div>
    </div>

    
    
    	
    
</div>





