<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('List Deleted Members');
?>
<style>
    .mt-element-ribbon .ribbon{
        margin: 0px;
    }
    
</style>

<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Deleted Members List"); ?>
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
                            
                             <!--<a href="<?php echo $this->Gym->createurl("PheramorUser", "addMember"); ?>" class="btn sbold green"><?php echo __("Add Member"); ?> <i class="fa fa-plus"></i></a>-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        &nbsp;
                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    

                    <tr>
                    <th style="width:15% !important"><?php echo __("Photo"); ?></th>
                    <th><?php echo __("Name"); ?></th>
                    <th><?php echo __("Email"); ?></th>					
                    <th><?php echo __("Gender"); ?></th>
                    <th><?php echo __("Credits"); ?></th>
                    <th><?php echo __("Location"); ?></th>
                    <th><?php echo __("Deleted By"); ?></th>
                    <th><?php echo __("Status"); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                    <?php
                  // echo '<pre>';print_r($data);
                   foreach ($data as $row) {
                    $profile_data=@$row['pheramor_user_profile'][0];
                    if ($profile_data["gender"] == 1) {
                       $gender = '<span class="label label-success">Male</span>';
                    }else{
                        $gender = '<span class="label label-warning">Female</span>';
                    }
                    if($row['is_deleted']==1){
                        $is_deleted = '<span class="label label-info">Client</span>';
                    }else{
                        $is_deleted = '<span class="label label-warning">Super Admin</span>';
                    }
                    
                    $location_key=$row['location_key'];
                    $loc_color='#659be0;';
                     $location_key='Pheramor'; 
                   // echo $location_key; die;
                    $profileimage=$this->Pheramor->getProfileImage($row['id']);
                    
                    $imgstr = substr(strrchr($profileimage, '/'), 1);
                  // echo $str; 
                    if(empty($profileimage)) {
                        $profileimage=$this->request->base.'/upload/profile-placeholder.png';
                    }else{
                        //$profileimage=$this->request->base.'/upload/thumbnails/'.$imgstr;
                        $profileimage=$profileimage;
                    }
                    echo "<tr class='odd gradeX'>
					<td ><img src='{$profileimage}' width='100' class='membership-img img-circle'></td>
					<td>{$profile_data['first_name']} {$profile_data['last_name']}</td>
					<td>{$row['email']}</td>";
                                        echo "<td>" . $gender . "</td><td><div class='mt-element-ribbon bg-grey-steel'>
                                                                    <div class='ribbon ribbon-round ribbon-border-dash ribbon-color-danger uppercase'>Total : {$this->Pheramor->totalCredits($row['id'])}</div>
                                                                   </div></td><td><span style='border-radius:25px !important;border:0;background:{$loc_color}' class='btn-success btn-sm'>".ucfirst($location_key)."</span></td>";
                         echo "<td>{$is_deleted}</td>";
                        echo "<td><a class='btn btn-success btn-flat' onclick=\"return confirm('Are you sure,you want to restore this account?');\" href='" . $this->request->base . "/PheramorUser/restoreDeletedMember/{$row['id']}'>" . __('Restore') . "</a>";
                   
                    echo "</td>
                                        
                                        
					</tr>";
                }
                ?>
                   
                    


                </tbody>
            </table>
        </div>
    </div>

    
    
    	
    
</div>

<style>
 .portlet.light .dataTables_wrapper .dt-buttons {
    margin-top: -45px;
}
</style>