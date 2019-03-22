
<?php
$session = $this->request->session()->read("User");
$this->Html->addCrumb('List Staff Member');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Staff Member List"); ?>
                </span>
                 
            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>
        <div class="portlet-body">
            <?php 
            if($session["role_name"]=='administrator')
            {?>
            <div class="col-md-12" style="padding-bottom: 20px;">
                <div class="btn-group col-md-12">
                     <?php echo $this->Form->create("staffList",["class"=>"validateForm form-horizontal","role"=>"form", "id"=>"staff_loc_id"]);?>
                        <div class="box-body">
                            <table class="table table-bordered">

                                <tr>
                                    <td width="30%">Search By Location Name</td>
                                    <td><?php echo @$this->Form->select("associated_location",$locations,["default"=>$locations,"empty"=>__("Select Location"),"class"=>"form-control selectpicker", "data-live-search"=>"true"]);?></td>
                                    <td><?php echo $this->Form->button(__("Search"),['class'=>"btn btn-flat btn-success col-md-offset-2","name"=>"search"]);?></td>
                                </tr>

                            </table>
                        </div>
                     <?php echo $this->Form->end();?>
                    
                </div>
               </div>
            <?php } ?>
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <?php
                             if($session["role_name"] == "administrator" || $session["role_name"] == "licensee" || $session["role_name"] == "subadmin" || $session["role_name"] == "admin"){
                                ?>

                                <a href="<?php echo $this->Gym->createurl("StaffMembers","addStaff"); ?>" class="btn sbold green"><?php echo __("Add Staff"); ?> <i class="fa fa-plus"></i></a>

                        <?php }else {echo "<p>&nbsp;</p>";} ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    <tr>
                    <th><?php echo __("Photo");?></th>
                    <th><?php echo __("Staff Member Name");?></th>
                    <th><?php echo __("Role");?></th>					
                    <th><?php echo __("Staff Member Email");?></th>					
                    <th><?php echo __("Mobile No.");?></th>					
                    <th><?php echo __("Action");?></th>
		    </tr>
                   
                </thead>
                <tbody>
                    <?php			
			foreach($data as $row)
			{
                            if(empty($row['image']))
                                $row['image'] = $this->request->webroot ."upload/profile-placeholder.png";			
				echo "
				<tr>					
                                    <td style='width: 15%'><img src='{$row['image']}' width='100' class='membership-img img-circle'></img></td>
                                    <td><a href='{$this->request->base}/staff-members/view-staff/{$row['id']}'>{$row['first_name']} {$row['last_name']}</a></td>
					<td>{$row['gym_role']['name']}</td>
					<td>{$row['email']}</td>
					<td>{$row['mobile']}</td>
                                    <td>";
                                    echo "<div class='btn-group'>
                                               <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                   <i class='fa fa-angle-down'></i>
                                               </button>";
                                     echo "<ul class='dropdown-menu pull-right' role='menu'>";
                                            if($session["role_name"] == "administrator" || $session["role_name"] == "licensee" || $session["role_name"] == "subadmin" || $session["role_name"] == "admin") {
                                              echo "<li>
                                                       <a href='{$this->Gym->createurl('StaffMembers','editStaff')}/{$row['id']}'>
                                                           <i class='icon-pencil'></i> Edit StaffMember </a>
                                                   </li>
                                                   <li>
                                                       <a href='{$this->Gym->createurl("StaffMembers","deleteStaff")}/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                           <i class='icon-trash'></i> Delete StaffMember </a>
                                                   </li>";
                                                   }
                                                   //echo "<li>
                                                      //<a href='javascript:void(0)' id={$row['id']} data-url='".$this->request->base ."/GymAjax/view_staff' class='view_jmodal'>
                                                           //<i class='icon-eye'></i> View StaffMember </a>
                                                   //</li>"; 
                                                   echo "<li>
                                                      <a href='{$this->request->base}/staff-members/view-staff/{$row['id']}' >
                                                           <i class='icon-eye'></i> View StaffMember </a>
                                                   </li>"; 
                                                   echo "</ul></div></td></tr>";
                                        
				
			} 
			?>
                   
                   
                </tbody>
            </table>
        </div>
    </div>
  </div>

