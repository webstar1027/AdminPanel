<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('List Access Roles');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Access Roles List"); ?>
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
                            if ($session["role_name"] == "administrator") {
                                ?>
                                    <a href="<?php echo $this->Gym->createurl("GymAccessRoles","addAccessRoles"); ?>" class="btn sbold green"><?php echo __("Add Access Roles"); ?> <i class="fa fa-plus"></i></a>

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
                        <th><?php echo __("Role Name");?></th>
                        <th><?php echo __("Role Slug");?></th>
                        <th><?php echo __("Role Status ");?></th>					
                        <th><?php echo __("Action");?></th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $k = 1;
                    foreach ($data as $row) {
                        $status = ($row['status'] == "1") ? "<span class='label label-sm label-success'> Active </span>" : "<span class='label label-sm label-danger'>Deactive</span>";
                        echo "<tr>";
                        echo "<td>{$k}</td>";
                       echo "<td>{$row['name']}</td>
					<td>{$row['slug']}</td>
					<td>{$status}</td>
			 <td>";
                       
                            echo "<div class='btn-group'>
                                               <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                   <i class='fa fa-angle-down'></i>
                                               </button>";
                                                if ($session["role_name"] == "administrator") {
                                               echo "<ul class='dropdown-menu pull-right' role='menu'>
                                                   <li>
                                                       <a href='{$this->Gym->createurl('GymAccessRoles','editAccessRoles')}/{$row['id']}'>
                                                           <i class='icon-pencil'></i> Edit Role </a>
                                                   </li>
                                                   <li>
                                                       <a href='{$this->Gym->createurl('GymAccessRoles', 'deleteAccessRoles')}/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                           <i class='icon-trash'></i> Delete Role </a>
                                                   </li></ul>";
                                                  }
                                                 
                                        echo "</div>";
                       
                        echo "</td></tr>";
                        $k++;
                    }
                    ?>




                </tbody>
            </table>
        </div>
    </div>
</div>
