<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Class Type List');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Class Type List"); ?>
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
                            if ($session["role_name"] == "administrator" || $session["role_name"] == "manager" || $session["role_name"] == "licensee") {
                                ?>

                                <a href="<?php echo $this->Gym->createurl("ClassType","addclassType"); ?>" class="btn sbold green"><?php echo __("Add ClassType"); ?> <i class="fa fa-plus"></i></a>

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
                        <th><?php echo __("Title");?></th>
                        <th><?php echo __("Created By");?></th>
                        <th><?php echo __("Status");?></th>
                        <th><?php echo __("Created Date");?></th>
                        <?php echo ($session["role_id"] != 3) ? "<th>" .  __("Action") . "</th>" : "";?>
                    </tr>
                   
                </thead>
                <tbody>
                   <?php   
                          $k=1;
                          foreach($data as $row)
                            {
                                    echo "<tr>";
                                    echo "<td>{$k}</td>";
                                    echo "<td>{$row['title']}</td>
                                             <td>{$row['gym_member']['first_name']}</td>
                                              <td>".(($row['status']) ? '<span class="label label-success">Active</span>' :'<span class="label label-warning">Inactive</span>')."</td>
                                              <td>".date($this->Gym->getSettings("date_format"),time($row['created_date'])) ."</td>";
                                              if($session["role_id"] != 3){    
                                              echo "<td>";
                                              echo "<div class='btn-group'>
                                               <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                   <i class='fa fa-angle-down'></i>
                                               </button>";
                                             //if ($session["role_name"] == "administrator") {
                                              if ($session["role_id"] != 1  && $row['created_by'] != $session["id"]) {
                                                  echo "<ul class='dropdown-menu pull-right' role='menu'>
                                                      <li>
                                                                <a href='javascript:void(0)' onclick=\"alert('You do not have permission to edit this record')\">
                                                                    <i class='icon-pencil'></i> Edit Class Type </a>
                                                                 </li>
                                                                 <li>
                                                                <a href='javascript:void(0)' onclick=\"alert('You do not have permission to delete this record')\">
                                                                    <i class='icon-trash'></i> Delete Class Type </a>
                                                            </li></ul>";
                                              }else{
                                               echo "<ul class='dropdown-menu pull-right' role='menu'>
                                                   <li>
                                                       <a href='{$this->Gym->createurl('ClassType','editclassType')}/{$row['id']}'>
                                                           <i class='icon-pencil'></i> Edit Class Type </a>
                                                   </li>
                                                   <li>
                                                       <a href='{$this->Gym->createurl("ClassType","deleteclassType")}/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                           <i class='icon-trash'></i> Delete Class Type </a>
                                                   </li></ul>";
                                                   }echo "</div></td>";
                                              }
                                                   echo "</tr>";
                                        $k++;
                                    
                            }
			   
		      ?>
                   
                    


                </tbody>
            </table>
        </div>
    </div>
  </div>


