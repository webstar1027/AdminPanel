<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Notes List');
?>

<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Member Note List"); ?>
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
                           if($session["role_name"] == "staff_member"){
                                ?>
                                    <a href="<?php echo $this->Gym->createurl("CustomerNotes","addCustomerNotes"); ?>" class="btn sbold green"><?php echo __("Add Customer Notes"); ?> <i class="fa fa-plus"></i></a>

                            <?php } else { echo "<p>&nbsp;</p>";}?>

                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>


                    <tr>
                        <th><?php echo __("Title");?></th>
                        <th><?php echo __("Comment");?></th>
                        <th><?php echo __("Note For");?></th>
                        <th><?php echo __("Class");?></th>
                        <th><?php echo __("Added By");?></th>
                        <th><?php echo __("Asso. Licensee");?></th>
                        <th><?php echo __("Action");?></th>
                    </tr>

                </thead>
                <tbody>
                    <?php
			foreach($data as $row)
			{
                            //echo '<pre>';print_r($row);die;
				echo "<tr>";
                                    echo "<td>{$row['note_title']}</td>
                                        <td>{$row['comment']}</td>
                                        <td>". ucwords($row['NoteForCN']['first_name'] . " " . $row['NoteForCN']['last_name'])."</td>
                                        <td>". ($row['gym_clas']['name']) ."</td>
                                        <td>". ucwords($row['CreatedByCN']['first_name'] . " " . $row['CreatedByCN']['last_name']) ."</td>
                                        <td>". ucwords($row['AssociatedLicenseeCN']['first_name'] . " " . $row['AssociatedLicenseeCN']['last_name']) ."</td>
                                        <td>";
                                         echo "<div class='btn-group'>
                                               <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                   <i class='fa fa-angle-down'></i>
                                               </button>";
                                             echo "<ul class='dropdown-menu pull-right' role='menu'>";
                                       if($session["id"] == $row['CreatedByCN']['id'] || $session["role_id"] == 1 || $session["role_id"] == 2){
                                          
                                           echo "<li>
                                                       <a href='".$this->request->base ."/customer-notes/edit-customer-notes/{$row['id']}'>
                                                           <i class='icon-pencil'></i> Edit Customer Note </a>
                                                   </li>
                                                   <li>
                                                       <a href='{$this->request->base}/customer-notes/delete-customer-notes/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                           <i class='icon-trash'></i> Delete Customer Note </a>
                                                   </li>";

                                       }
                                       echo "<li>
                                                        <a href='javascript:void(0)' id='{$row['id']}' data-url='".$this->request->base ."/GymAjax/view_customer_notes' class='view_jmodal' >
                                                           <i class='icon-eye'></i> View Customer Note</a>
                                                    </li></ul>";
                                        echo "</div>";
                                        echo  "</td>";
                                echo  "</tr>";
			}
			?>
                   
                        
                   </tbody>
            </table>
        </div>
    </div>
</div>

