<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('List Events');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Event List"); ?>
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
                             <a href="<?php echo $this->Gym->createurl("PheramorEvents", "addEvent"); ?>" class="btn sbold green"><?php echo __("Add Event"); ?> <i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    

                    <tr>
                    <th><?php echo __("S.N."); ?></th>
                    <th style="width:15% !important"><?php echo __("Photo"); ?></th>
                    <th><?php echo __("Title"); ?></th>
                    <th><?php echo __("Start Date"); ?></th>					
                    <th><?php echo __("End Date"); ?></th>					
                    <th><?php echo __("Status"); ?></th>
                    <th><?php echo __("Event Type"); ?></th>
                    <th><?php echo __("Action"); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                    <?php
                    $k=1;
                    //echo '<pre>';print_r($data); die;
                   foreach ($data as $row) {
                       
                    if ($row["event_type"] == 'facebook') {
                       $event_type = '<span class="label label-primary">Facebook</span>';
                    }else{
                        $event_type = '<span class="label label-danger">Website</span>';
                    }
                       
                    if ($row["status"] == 1) {
                       $status = '<span class="label label-success">Active</span>';
                    }else{
                        $status = '<span class="label label-warning">Inactive</span>';
                    }
                    if(empty($row['image'])) {$row['image']=$this->request->base.'/upload/profile-placeholder.png';}
                    echo "<tr class='odd gradeX'>
                                        <td>{$k}</td>
					<td ><img src='{$row['image']}' width='100' class='membership-img img-circle'></td>
					<td>{$row['title']}</td>
					<td>".date($this->Gym->getSettings("date_format"), strtotime($row['start_date']))."</td>
                                        <td>".date($this->Gym->getSettings("date_format"), strtotime($row['end_date']))."</td>";
                                        echo "<td>" . $status . "</td><td>" . $event_type . "</td><td><div class='btn-group'>
                                        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                            <i class='fa fa-angle-down'></i>
                                        </button>
                                    <ul class='dropdown-menu pull-left' role='menu'>";
                                        echo "<li>
                                            <a href='{$this->request->base}/PheramorEvents/editEvent/{$row['id']}'>
                                                <i class='icon-pencil'></i> Edit Event </a>
                                        </li>
                                        <li>
                                            <a href='{$this->request->base}/PheramorEvents/deleteEvent/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                <i class='icon-trash'></i> Delete Event </a>
                                        </li>";
                                        echo "
                                    </ul>
                                </div></td>
                                    </tr>";
                                        $k++;
                }
                ?>
                   
                    


                </tbody>
            </table>
        </div>
    </div>

    
    
    	
    
</div>

