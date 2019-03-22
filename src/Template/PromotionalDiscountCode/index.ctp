<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('Promotional Code List');
echo $this->Html->script('assets/global/plugins/jquery-knob/js/jquery.knob.js');
echo $this->Html->script('assets/pages/scripts/components-knob-dials.min.js');
 
?>
  
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> <?php echo __("Promotional Code List"); ?>
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
                            <a href="<?php echo $this->Gym->createurl("PromotionalDiscountCode","addDiscountCode"); ?>" class="btn sbold green"><?php echo __("Add Promotional Code"); ?> <i class="fa fa-plus"></i></a>
                       </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <table class="mydataTable table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>


                    <tr>
                                <th><?php echo __("S.N.");?></th>
                                <th><?php echo __("Code");?></th>
                                <th><?php echo __("Discount");?></th>
                                <th><?php echo __("Used Count");?></th>
                                <th><?php echo __("Subscription");?></th>
                                <th><?php echo __("Valid From");?></th>
                                <th><?php echo __("Expires On");?></th>
                                <th><?php echo __("Status");?></th>
                                <th><?php echo __("Action");?></th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $k=1;
			foreach($data as $row)
			{
                            $membershipIds = $row['subscription_id'];
                            //$membershipIds = implode(',', $membershipIdsArr);
                            $membershipArr = $this->Pheramor->get_subscription_names($membershipIds);
                            $usedcode = $this->Pheramor->total_used_promocode($row['code']);
                            $usedcodeHTML =' <input class="knob" data-height="50" data-max="100" data-width="100" data-angleoffset=-125 data-anglearc=250 data-readOnly=true data-fgcolor="#66EE66" value="'.$usedcode.'">
                                                 ';
                            //echo '<pre>';print_r($membershipArr);die;
                            $membershipList = '';
                            foreach($membershipArr as $membership){
                                $membershipList .= '<span class="badge">'.$membership['subscription_title'].'</span>';
                            }
                            
                            $expires_on = (date($this->Gym->getSettings("date_format"),strtotime($row['valid_to'])));
				echo "<tr>";
                                
				echo "<td>{$k}</td><td>{$row['code']}</td>
                                    <td>{$row['discount']}%</td>
                                    <td>{$usedcodeHTML}</td>
                                    <td>".$membershipList."</td>
                                    <td>".date($this->Gym->getSettings("date_format"),strtotime($row['valid_from']))." </td>
                                    <td>".$expires_on." </td>
                                    <td>".(($row['activated']) ? '<span class="label label-success">Active</span>' :'<span class="label label-warning">Inactive</span>')."</td>
                                    <td>";
                                     echo "<div class='btn-group'>
                                               <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                                   <i class='fa fa-angle-down'></i>
                                               </button>";
				              echo "<ul class='dropdown-menu pull-right' role='menu'>
                                                   <li>
                                                       <a href='".$this->request->base ."/PromotionalDiscountCode/edit-discount-code/{$row['id']}'>
                                                           <i class='icon-pencil'></i> Edit Promotional Code </a>
                                                   </li>
                                                   <li>
                                                       <a href='{$this->request->base}/PromotionalDiscountCode/delete-discount-code/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                                           <i class='icon-trash'></i> Delete Promotional Code </a>
                                                   </li>
                                                   <li>
                                                       <a href='javascript:void(0)' id='{$row['id']}' data-url='".$this->request->base ."/PheramorAjax/sendPromotionalCode' class='view_jmodal' >
                                                           <i class='icon-share'></i> Invite Members </a>
                                                   </li>
                                                    
                                                     </ul>";
				
				
				echo  "</div></td>";
				echo  "</tr>";
                                $k++;
			}
			?>
                   
                </tbody>
            </table>
        </div>
    </div>
</div>

