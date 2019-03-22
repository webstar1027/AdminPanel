<?php 
$session = $this->request->session()->read("User");
$this->Html->addCrumb('List Members');
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
                <span class="caption-subject bold uppercase"> <?php echo __("Members List"); ?>
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
                             <a href="<?php echo $this->Gym->createurl("PheramorUser", "addMember"); ?>" class="btn sbold green"><?php echo __("Add Member"); ?> <i class="fa fa-plus"></i></a>
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
                    <th><?php echo __("Name"); ?></th>
                    <th><?php echo __("Email"); ?></th>					
<!--                    <th><?php echo __("Gender"); ?></th>-->
                    <th><?php echo __("Pheramor ID"); ?></th>
<!--                    <th><?php echo __("Total Credits"); ?></th>-->
                    <th><?php echo __("Subscription Status"); ?></th>
                    <th><?php echo __("Action"); ?></th>
                    <th><?php echo __("Status"); ?></th>
                    <th><?php echo __("Registration"); ?></th>
                    </tr>
                   
                </thead>
                <tbody>
                    <?php
                   
                    //echo '<pre>';print_r($data);
                   foreach ($data as $row) {
                    $profile_data=@$row['pheramor_user_profile'][0];
                    if ($profile_data["gender"] == 1) {
                       $gender = '<span class="label label-success">Male</span>';
                    }else{
                        $gender = '<span class="label label-warning">Female</span>';
                    }
                    
                    $profileimage=$this->Pheramor->getProfileImage($row['id']);
                    $KitID=$this->Pheramor->getKitByUserID($row['id']);
                    
                    $imgstr = substr(strrchr($profileimage, '/'), 1);
                  // echo $str; 
                    if(empty($profileimage)) {
                        $profileimage=$this->request->base.'/upload/profile-placeholder.png';
                    }else{
                        //$profileimage=$this->request->base.'/upload/thumbnails/'.$imgstr;
                        $profileimage=$profileimage;
                    }
                    $created_date='--';
                  // echo "<pre>"; print_r($profile_data);
                    if(!empty($row['created_date'])){
                         $created_date=date('Y-m-d', strtotime($row['created_date']));
                    }
                   
                    echo "<tr class='odd gradeX'>
					<td ><img src='{$profileimage}' width='100' height='100' class='membership-img img-circle'></td>
					<td><a href='{$this->request->base}/PheramorUser/viewMember/{$row['id']}'   data-wrl='{$this->request->base}/PheramorUser/viewMember/{$row['id']}' rel='group' >{$profile_data['first_name']} {$profile_data['last_name']}</a></td>
					<td>{$row['email']}</td>";
                                        echo "<td>" . $KitID . "</td>";
//                                        echo "<td><div class='mt-element-ribbon bg-grey-steel'>
//                                                                    <div class='ribbon ribbon-round ribbon-border-dash ribbon-color-danger uppercase'>Total : {$this->Pheramor->totalCredits($row['id'])}</div>
//                                                                   </div></td>";
                                   echo "<td>".$this->Pheramor->getUserPaymentCustomTags($row['id'])."</td>";     
                                                                   
                                echo "<td><div class='btn-group'>
                                <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
                                    <i class='fa fa-angle-down'></i>
                                </button>
                                <ul class='dropdown-menu pull-left' role='menu'>
                                    <li>
                                        <a href='{$this->request->base}/PheramorUser/viewMember/{$row['id']}'>
                                            <i class='icon-eye'></i> View / Manage </a>
                                    </li>";
                                    echo "<li>
                                        <a href='{$this->request->base}/PheramorUser/editMember/{$row['id']}'>
                                            <i class='icon-pencil'></i> Edit Member Details </a>
                                    </li>
                                    <li>
                                        <a href='{$this->request->base}/PheramorUser/deleteMember/{$row['id']}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                                            <i class='icon-trash'></i> Delete Member </a>
                                    </li>";
                                    echo "
                                </ul>
                            </div></td>
				 <td>";
                                    
                    if ($row["activated"] == 0) {
                        echo "<a class='btn btn-success btn-flat' onclick=\"return confirm('Are you sure,you want to activate this account?');\" href='" . $this->request->base . "/PheramorUser/activateMember/{$row['id']}'>" . __('Inactive') . "</a>";
                    } else {
                        echo "<span class='btn btn-flat btn-default'>" . __('Active') . "</span>";
                    }
                    echo "</td>";
                    echo "<td>{$created_date}</td>
                                        
                                        
					</tr>";
                }
                ?>
                   
                    


                </tbody>
            </table>
        </div>
    </div>

    
    
    	
    
</div>
<div id="tableInfo"></div>
<!--<input type="hidden" id="pnumber" value="0">
<input type="hidden" id="poffset" value="10">-->
<script>
$(document).ready(function(){
     var otable = $('#sample_1').DataTable();
     // var title = otable.order(1,'asc');
     // otable.column( '2:visible').order('asc').draw();
    $('#sample_1').on('draw.dt', function() {
        
    // do action here
     // var table = $('#sample_1').DataTable();
     var info = otable.page.info();
     var order = otable.order();
     //alert( 'Table is ordered by column: ' + order[0][0] + ', direction:' + order[0][1]);

     // var title = otable.column('1','asc').header();
       setCookie('pnumber', info.page);
       setCookie('poffset', info.length);
       setCookie('ocolumn', order[0][0]);
       setCookie('odirection', order[0][1]);
       //console.log(info.length);
    //  $("#pnumber").val(info.page);
     // $("#poffset").val(info.length);
//     console.log(info);
//      $('#tableInfo').html(
//            'Currently showing page '+(info.page+1)+' of '+info.pages+' pages.'
//        ); 
   });
   
    
   
 
   
<?php //if($_GET['page']=='1'){ ?>
        
   var ocol = Number(getCookie('ocolumn'));
    var odir = getCookie('odirection');
    var poff = Number(getCookie('poffset'));
    var pumb = Number(getCookie('pnumber'));
   // console.log(ocol+"/"+odir+'/'+poff+'/'+pumb);
    otable.column(ocol).order(odir).draw();
    otable.page.len(poff).draw();
    //alert(pumb);
    otable.page(pumb).draw(false);
   
<?php //}  ?>



//   $('a[rel="group"]').on('click', function(e) {
//     // e.preventDefault();
//      var wpurl = $(this).attr("data-wrl");
//      var punber=$("#pnumber").val();
//      var poffset=$("#poffset").val();
//      var wpurl=wpurl+"?punber="+punber+"&poffset="+poffset;
//      $(this).attr('href', wpurl);
//    });

       
      
});

function setCookie(key, value) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        }

function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}
</script>