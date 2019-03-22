<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('My Clients');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("My Clients"); ?>
                </span>
                 
            </div>
            <div class="actions">
                <div class="tools"> </div>
            </div>
        </div>
        <div class="portlet-body">
            <?php 
            if($session["role_name"]=='administrator'){?>
            <div class="col-md-12" style="padding-bottom: 20px;">
                <div class="btn-group col-md-12">
                     <?php echo $this->Form->create("memberList",["class"=>"validateForm form-horizontal","role"=>"form", "id"=>"staff_loc_id"]);?>
                        <div class="box-body">
                            <table class="table table-bordered">

                                <tr>
                                    <td width="30%">Search By Location Name</td>
                                    <td><?php echo @$this->Form->select("associated_location",$locations,["default"=>$location,"empty"=>__("Select Location"),"class"=>"form-control selectpicker", "data-live-search"=>"true"]);?></td>
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
                            if ($session["role_name"] == "administrator" || $session["role_name"] == "licensee" || $session["role_name"] == "manager" || $session["role_name"] == "subadmin" || $session["role_name"]=='admin') {
                                ?>

                                <a href="<?php echo $this->Gym->createurl("GymMember", "addMember"); ?>" class="btn sbold green"><?php echo __("Add Member"); ?> <i class="fa fa-plus"></i></a>

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
                    <th style="width:15% !important"><?php echo __("Photo"); ?></th>
                    <th><?php echo __("Member Name"); ?></th>
                    <th><?php echo __("Member ID"); ?></th>					
                    <!--<th width="18%"><?php echo __("Assign Class"); ?></th>-->					
                    <th><?php echo __("Membership Status"); ?></th>					
                    <th><?php echo __("Action"); ?></th>
                    <th><?php echo __("Status"); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                    <?php
                foreach ($data as $row) {
                   //echo '<pre>';print_r($row);
                    
                     if ($this->Gym->get_membership_status_by_userid($row['id'])) {
                        $memship_status = '<span class="label label-success">Active</span>';
                    } else {
                        $memship_status = '<span class="label label-warning">Discontinued</span>';
                    }



                    if ($this->Gym->get_member_assign_class($row['id']) > 0) {
                        $assign_label = "<i class='fa fa-pencil'></i>Update Assign Class";
                    } else {
                        $assign_label = "<i class='fa fa-plus'></i>Assign Class";
                    }
                    if(empty($row['image'])) {$row['image']=$this->request->base.'/webroot/upload/profile-placeholder.png';}
                    echo "<tr class='odd gradeX'>
					<td ><img src='{$row['image']}' width='100' class='membership-img img-circle'></td>
					<td><a href='{$this->request->base}/GymMember/viewMember/{$row['id']}'>{$row['first_name']} {$row['last_name']}</a></td>
					<td>{$row['member_id']}</td>
					<!--<td><a href='{$this->request->base}/GymMember/assign-member/{$row['id']}' title='Assign Classes'>" . $assign_label . "</a></td>-->";
                    //<td>{$row['membership_status']}</td>
                    echo "<td>" . $memship_status . "</td><td><div class='btn-group'>
                                <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                    <i class='fa fa-angle-down'></i>
                                </button>
                                <ul class='dropdown-menu pull-left' role='menu'>
                                    <li>
                                        <a href='{$this->request->base}/GymMember/viewMember/{$row['id']}'>
                                            <i class='icon-eye'></i> View / Manage </a>
                                    </li>";
                                     if ($session["role_name"] == "administrator" || $session["role_name"] == "licensee" || $session["role_name"] == "manager" || $session["role_name"] == "subadmin" || $session["role_name"]=='admin') {
                                    echo "<li>
                                        <a href='{$this->request->base}/GymMember/editMember/{$row['id']}'>
                                            <i class='icon-pencil'></i> Edit Member Details </a>
                                    </li>
                                    <li>
                                        <a href='{$this->request->base}/GymMember/deleteMember/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                            <i class='icon-trash'></i> Delete Member </a>
                                    </li>";
                                     } 
                                     echo "
                                </ul>
                            </div></td>
				 <td>";
                    if ($row["activated"] == 0) {
                        echo "<a class='btn btn-success btn-flat' onclick=\"return confirm('Are you sure,you want to activate this account?');\" href='" . $this->request->base . "/GymMember/activateMember/{$row['id']}'>" . __('Activate') . "</a>";
                    } else {
                        echo "<span class='btn btn-flat btn-default'>" . __('Activated') . "</span>";
                    }
                    echo "</td>
                                        
                                        
					</tr>";
                }
                ?>
                   
                    


                </tbody>
            </table>
        </div>
    </div>

    
    
    	
    
</div>

