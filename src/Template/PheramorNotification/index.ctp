<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('List Notification Message');
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Notification Message List"); ?>
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
                             <a href="<?php echo $this->Gym->createurl("PheramorNotification", "addNotification"); ?>" class="btn sbold green"><?php echo __("Add Notification Message"); ?> <i class="fa fa-plus"></i></a>
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
                    <th><?php echo __("Notification Title"); ?></th>
                    <th><?php echo __("Notification Label"); ?></th>	
                    <th><?php echo __("Notification Type"); ?></th>	
                    <th><?php echo __("Status"); ?></th>
                    <th><?php echo __("Action"); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                    <?php
                    $k=1;
                    //echo '<pre>';print_r($data); die;
                   foreach ($data as $row) {
                       
                    if ($row["status"] == 1) {
                       $status = '<span class="label label-success">Active</span>';
                    }else{
                        $status = '<span class="label label-warning">Inactive</span>';
                    }
                  
                    echo "<tr class='odd gradeX'>
                                        <td>{$k}</td>
				        <td>{$row['notification_title']}</td>
					<td>{$row['notification_label']}</td>
                                        <td>{$row['notification_type']}</td>";
                                        echo "<td>" . $status . "</td><td><div class='btn-group'>
                                        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                            <i class='fa fa-angle-down'></i>
                                        </button>
                                    <ul class='dropdown-menu pull-left' role='menu'>";
                                        echo "<li>
                                            <a href='{$this->request->base}/PheramorNotification/editNotification/{$row['id']}'>
                                                <i class='icon-pencil'></i> Edit Notification </a>
                                        </li>";
                                         if($row['notification_type']=='Custom'){
                                        echo"<li>
                                            <a href='{$this->request->base}/PheramorNotification/deleteNotification/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                <i class='icon-trash'></i> Delete Notification </a>
                                        </li>";
                                         }
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

