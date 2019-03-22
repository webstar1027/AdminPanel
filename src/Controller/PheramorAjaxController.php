<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Routing\Router;

Class PheramorAjaxController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent("PHMFunction");
        $this->loadComponent("Stripe");
        $this->loadComponent("Aws");
        $this->request->data = $_REQUEST; 
        $this->autoRender = false;
    }

    /**
     * Delete Admin profile Image
     * @Method Delete Admin Profile Image
     * @Date 28 Aug 2017
     * @Author RNF Technologies  
     */
   public function deleteImageByProfileId() {
        if ($this->request->is("ajax")) {
            $session = $this->request->session()->read("User");
            $sessions = $this->request->session();
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $profile_img=$this->request->webroot."upload/profile-placeholder.png";
            $update = $conn->execute("UPDATE pheramor_user_profile set image = '$profile_img' WHERE id = '$id' ");
            $sessions->write("User.profile_img",$profile_img);
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }
    
     /**
     * View Subscription Details
     * @Method viewSubscription
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
     public function viewSubscription() {

        if ($this->request->is("ajax")) {
            $session = $this->request->session()->read("User");
            $id = $this->request->data["id"];
            $member_tbl = TableRegistry::get("PheramorSubscription");
            $row = $member_tbl->find()->contain(["PheramorSubscriptionCategory"])->where(["PheramorSubscription.id" => $id])->first();
            if ($row["subscription_status"]== 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                 $status = "<span class='label label-warning'>Inactive</span>";
            } 
?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="gridSystemModalLabel"><?php echo __("Subscription Detail"); ?></h3>
            </div>
            <div class="modal-body">		
                <div class="panel panel-white form-horizontal">

                    <div class="form-group">
                        <label class="col-sm-3"><?php echo __("Subscription Name"); ?> : </label>
                        <div class="col-sm-9"> <?php echo $row['subscription_title']; ?> </div>
                    </div>
                    <div class="form-group">
                        <label for="start_date" class="col-sm-3"><?php echo __("Subscription Category"); ?> : </label>
                        <div class="col-sm-9"> <?php echo $row['pheramor_subscription_category']["category_name"]; ?> </div>
                    </div>
                    <div class="form-group">
                        <label for="start_date" class="col-sm-3"><?php echo __("Subscription Period"); ?> : </label>
                        <div class="col-sm-9"> <?php echo $row['subscription_length'] . ' ' . ucwords($row['subscription_type']); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="col-sm-3"><?php echo __("Amount"); ?> : </label>
                      <div class="col-sm-9"> $<?php echo $row['subscription_amount']; ?> </div>

                    </div>
                    <div class="form-group">
                        <label for="end_date" class="col-sm-3"><?php echo __("Status"); ?> : </label>
                      <div class="col-sm-9"> <?php echo $status; ?> </div>

                    </div>
                    <div class="form-group">
                        <label for="licensee_title" class="col-sm-3"><?php echo __("Description"); ?> : </label>
                        <div class="col-sm-9"> <?php echo $row["subscription_desc"]; ?> </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("Close"); ?></button>				
            </div>	
            <?php
        }
    }
    
     /**
     * Add Subscription Category by Ajax
     * @Method addCategory
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
    
    public function addCategory() {
        $category_table = TableRegistry::get("PheramorSubscriptionCategory");
        $categories = $category_table->find("all")->where(['is_deleted'=>'0']);
        $categories = $categories->toArray();
        if ($this->request->is('ajax')) {
            ?>	
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel"><?php echo __("Add/Remove Category"); ?></h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="cat_name form-control" placeholder="<?php echo __("Enter category name"); ?>">
            </div>
            <div class="col-sm-4">
                <button class="add-category btn btn-flat btn-success" data-url="<?php echo $this->request->base . "/PheramorAjax/saveCategory"; ?>"><?php echo __("Add Category"); ?></button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 table table-striped">
                <table class="table" id="category_list">
                    <thead>
                        <tr>
                            <th><?php echo __("Category"); ?></th>
                            <th><?php echo __("Action"); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($categories as $category) {
                            echo "<tr id='row-{$category->id}'>
                            <td>{$category->category_name}</td>
                            <td><button class='del-category btn btn-flat btn-danger' del-id='{$category->id}' data-url='{$this->request->base}/PheramorAjax/deleteCategory'>" . __("Delete") . "</button></td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("Close"); ?></button>				
    </div>
            <?php
        }
    }
    
     /**
     * Display Password Field by Ajax
     * @Method showPasswordField
     * @Date 28 Nov 2017
     * @Author RNF Technologies  
     */
    public function showPasswordField(){
        
         if ($this->request->is('ajax')) {
            ?>	
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel"><?php echo __("Enter Password"); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-7 col-sm-offset-1">
                        <input type="password" class="generic_pass_val form-control" placeholder="<?php echo __("Enter Password"); ?>">
                    </div>
                    <div class="col-sm-4">
                        <button class="add-generic-password btn btn-flat btn-success" data-url="<?php echo $this->request->base . "/PheramorAjax/checkGenericPassword"; ?>"><?php echo __("Submit"); ?></button>
                    </div>
                    <div class="col-md-11 col-sm-offset-1" id="generic_response"></div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closed" data-dismiss="modal"><?php echo __("Close"); ?></button>				
            </div>


         <?php } 
        
    }
     /**
     * Check Generic Data Password Field by Ajax
     * @Method checkGenericPassword
     * @Date 28 Nov 2017
     * @Author RNF Technologies  
     */
    public function checkGenericPassword()
    {
              $return = array();
              if ($this->request->is('ajax')) {
              $password = $this->request->data['password'];
              $general_setting_tbl = TableRegistry::get("PheramorGeneralSetting");
              $setting_data = $general_setting_tbl->find()->where(['id' => 1])->first();
              $match_pass = $setting_data->generic_data_pass;
              if($password==$match_pass){
                  $user_id=$this->request->data['user_id'];
                  $generic_info_tbl = TableRegistry::get("PheramorGeneticInformation");
                  $mem_generic_data = $generic_info_tbl->find()->where(['user_id' => $user_id])->first();
                  
                  $style1=@$mem_generic_data->HLA_A_1 ? "readonly" : '';
                  $style2=@$mem_generic_data->HLA_A_2 ? "readonly" : '';
                  $style3=@$mem_generic_data->HLA_B_1 ? "readonly" : '';
                  $style4=@$mem_generic_data->HLA_B_2 ? "readonly" : '';
                  $style5=@$mem_generic_data->HLA_C_1 ? "readonly" : '';
                  $style6=@$mem_generic_data->HLA_C_2 ? "readonly" : '';
                  $style7=@$mem_generic_data->HLA_DPB1_1 ? "readonly" : '';
                  $style8=@$mem_generic_data->HLA_DPB1_2 ? "readonly" : '';
                  $style9=@$mem_generic_data->HLA_DQB1_1 ? "readonly" : '';
                  $style10=@$mem_generic_data->HLA_DQB1_2 ? "readonly" : '';
                  $style11=@$mem_generic_data->HLA_DRB_1 ? "readonly" : '';
                  $style12=@$mem_generic_data->HLA_DRB_2 ? "readonly" : '';
                  $style0=@$mem_generic_data->pheramor_kit_ID ? "" : '';
                  
                  $return='<div class="row">
                      <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">Pheramor Kit ID
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->pheramor_kit_ID.'"  '.$style0.'  placeholder="Enter Pheramor kit ID"  name="pheramor_kit_ID">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter pheramor kit ID....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-A-1
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_A_1.'" '.$style1.' placeholder="Enter HLA-A-1"  name="HLA_A_1">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-A-1....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-A-2
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_A_2.'" '.$style2.' placeholder="Enter HLA-A-2"  name="HLA_A_2">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-A-2....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-B-1
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_B_1.'" '.$style3.' placeholder="Enter HLA-B-1"  name="HLA_B_1">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-B-1....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-B-2
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_B_2.'" '.$style4.' placeholder="Enter HLA-B-2"  name="HLA_B_2">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-B-2....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-C-1
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_C_1.'" '.$style5.' placeholder="Enter HLA-C-1"  name="HLA_C_1">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-C-1....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-C-2
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_C_2.'" '.$style6.' placeholder="Enter HLA-C-2"  name="HLA_C_2">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-C-2....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-DPB1-1
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_DPB1_1.'" '.$style7.' placeholder="Enter HLA-DPB1-1"  name="HLA_DPB1_1">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-DPB1-1....</span>
                             </div>
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-DPB1-2
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_DPB1_2.'" '.$style8.' placeholder="Enter HLA-DPB1-2"  name="HLA_DPB1_2">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-DPB1-2....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-DQB1-1
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_DQB1_1.'" '.$style9.'  placeholder="Enter HLA-DQB1-1"  name="HLA_DQB1_1">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-DQB1-1....</span>
                             </div>
                        </div>
                    </div>
                   <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-DQB1-2
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_DQB1_2.'" '.$style10.'  placeholder="Enter HLA-DQB1-2"  name="HLA_DQB1_2">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-DQB1-2....</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-DRB-1
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_DRB_1.'" '.$style11.'  placeholder="Enter HLA-DRB-1"  name="HLA_DRB_1">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-DRB-1....</span>
                             </div>
                        </div>
                    </div>
                      <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label" for="form_control_1">HLA-DRB-2
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control validate[required] required_input" value="'.@$mem_generic_data->HLA_DRB_2.'" '.$style12.'  placeholder="Enter HLA-DRB-2"  name="HLA_DRB_2">
                                    <div class="form-control-focus"> </div>
                                   <span class="help-block">Enter HLA-DRB-2....</span>
                             </div>
                        </div>
                    </div>


                </div>
              </div>';
                echo $return;
              }else{
                  echo false;
              }
              
          }
    }
   /**
     * Soft Delete Subscription Category by Ajax
     * @Method deleteCategory
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
    
   public function deleteCategory() {
        $category_table = TableRegistry::get("PheramorSubscriptionCategory");
        $did = $this->request->data['did'];
        $this->request->data['is_deleted'] = '1';
        $row = $category_table->get($did);
        $row = $category_table->patchEntity($row,$this->request->data);
        if ($category_table->save($row)) {
            echo true;
        } else {
            echo false;
        }
    }
    
    /**
     * Save Subscription Category by Ajax
     * @Method saveCategory
     * @Date 29 Aug 2017
     * @Author RNF Technologies  
     */
    
    public function saveCategory() {
        $return = array();
        $category_table = TableRegistry::get("PheramorSubscriptionCategory");
        $category = $category_table->newEntity();
        $name = $this->request->data['name'];
        $category->category_name = $name;
        $category->created_date = date('Y-m-d H:i:s');
        $category->updated_date = date('Y-m-d H:i:s');
        $category->is_deleted = '0';
        $category->activated = '1';
        $category->created_by = '4';
       
        
        if ($category_table->save($category)) {
            $id = $category->id;
            $return[0] = "<tr id='row-{$id}'><td>{$name}</td><td><button del-id='{$id}' class='del-category btn btn-flat btn-danger' data-url='{$this->request->base}/PheramorAjax/deleteCategory'>" . __("Delete") . "</button></td></tr>";
            $return[1] = "<option value='{$id}'>{$name}</option>";
            echo json_encode($return);
        } else {
            echo false;
        }
    }
    
     /**
     * Check emailExist by Ajax
     * @Method emailExist
     * @Date 13 Sep 2017
     * @Author RNF Technologies  
     */
    
     public function emailExist() {
        $this->request->data = $_REQUEST;
        $email = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $member_tbl = TableRegistry::get("pheramorUser");
        $query = $member_tbl->find()->where(["email" => $email])->first();
        $is_deleted=$query->is_deleted;
        $count = intval(count($query));
        if ($count == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            if($is_deleted > 0){
                $arrayToJs[2] = "Your account has been deleted, Please restore your account."; 
            }
            echo json_encode($arrayToJs);
        } else {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = true;   // RETURN TRUE
            echo json_encode($arrayToJs);
        }
    }
    
 /**
     * Check emailExist by Ajax
     * @Method emailExist
     * @Date 13 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function emailExist1() {
        //$loggedUserId = $this->request->session()->read("User.id");
        $this->request->data = $_REQUEST;
        $email = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $itsId = $this->request->data['itsId'];
        $member_tbl = TableRegistry::get("pheramorUser");
        if (isset($itsId) && $itsId != '') {
            $query = $member_tbl->find()->where(["email" => $email, "id !=" => $itsId])->first();
        } else {
            $query = $member_tbl->find()->where(["email" => $email])->first();
        }
        
       
        $count = intval(count($query));
        if ($count == 1) {
            $is_deleted=$query->is_deleted;
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            if($is_deleted > 0){
                $arrayToJs[2] = "Your account has been deleted, Please restore your account."; 
            }
            //$arrayToJs[2] = "aaaaaaaaaaa";  // RETURN TRUE
            echo json_encode($arrayToJs);
        } else {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = true;   // RETURN TRUE
            echo json_encode($arrayToJs);
        }
    }
    
   /**
     * Check Age by Ajax
     * @Method DobValid
     * @Date 13 Sep 2017
     * @Author RNF Technologies  
     */
    
    
    public function DobValid() {
        //$loggedUserId = $this->request->session()->read("User.id");
        $this->request->data = $_REQUEST;
        $today=date('Y-m-d');
        $dob = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $mydob=date('Y-m-d',strtotime($dob));
        $count = $this->age($mydob);
                
      
        if ($count >= 18) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = true;  // RETURN TRUE
            echo json_encode($arrayToJs);
        } else {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;   // RETURN TRUE
            echo json_encode($arrayToJs);
        }
    }
    
    
    /**
     * Check Age by Ajax
     * @Method DobValid
     * @Date 13 Sep 2017
     * @Author RNF Technologies  
     */
    
    private function age($birthday) {

        list($year, $month, $day) = explode("-", $birthday);
        $year_diff = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff = date("d") - $day;
        if ($day_diff < 0 && $month_diff == 0)
            $year_diff--;
        if ($day_diff < 0 && $month_diff < 0)
            $year_diff--;
        return $year_diff;
    }

    /**
     * deleteImageByUserId by Ajax
     * @Method deleteImageByUserId
     * @Date 13 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteImageByUserId() {
        if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $update = $conn->execute("UPDATE pheramor_user_profile set image = '/pheramor/upload/profile-placeholder.png' WHERE user_id = '$id' ");
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }



 /**
     * delete event image by Ajax
     * @Method deleteImageByEventId
     * @Date 21 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteImageByEventId() {
        if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $update = $conn->execute("UPDATE pheramor_events set image = '/pheramor/upload/profile-placeholder.png' WHERE id = '$id' ");
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }
    
    /**
     * delete event gallery image by Ajax
     * @Method deleteImageByEventGalleryId
     * @Date 21 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteImageByEventGalleryId() {
        if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $update = $conn->execute("delete from pheramor_event_gallery  WHERE id = '$id' ");
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }
    
    /**
     * delete cafe gallery image by Ajax
     * @Method deleteImageByCafeGalleryId
     * @Date 03 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function deleteImageByCafeGalleryId() {
        if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $update = $conn->execute("delete from pheramor_cafe_gallery  WHERE id = '$id' ");
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }
    
     /**
     * delete cafe image by Ajax
     * @Method deleteImageByCafeId
     * @Date 22 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteImageByCafeId() {
        if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $update = $conn->execute("UPDATE pheramor_cafe set image = '/pheramor/upload/profile-placeholder.png' WHERE id = '$id' ");
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }
    
    
    
    /**
     * Updated credit Settings by Ajax
     * @Method deleteImageByEventId
     * @Date 22 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function updateCreditSetting(){
         if ($this->request->is("ajax")) {
            $did=1;
            $return = array();
            $pheramor_credit_table = TableRegistry::get("PheramorCreditSetting");
            $row = $pheramor_credit_table->get($did);
            $this->request->data['updated_date']=date('Y-m-d H:i:s');
            $update = $pheramor_credit_table->patchEntity($row, $this->request->data);
            if ($saveResult = $pheramor_credit_table->save($update)) {
               $return['status'] = 'success';
            } else {
                $return['status'] = 'fail';
            }
            echo json_encode($return);
            die;
         }
    }
    
    
    /**
     * Updated credit Settings by Ajax
     * @Method deleteImageByEventId
     * @Date 22 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function updatePaymentSetting(){
         if ($this->request->is("ajax")) {
            $did=1;
            $return = array();
            $pheramor_setting_table = TableRegistry::get("PheramorGeneralSetting");
            $row = $pheramor_setting_table->get($did);
          //  $this->request->data['updated_date']=date('Y-m-d H:i:s');
            $update = $pheramor_setting_table->patchEntity($row, $this->request->data);
            if ($saveResult = $pheramor_setting_table->save($update)) {
               $return['status'] = 'success';
            } else {
                $return['status'] = 'fail';
            }
            echo json_encode($return);
            die;
         }
    }
    
    /**
     * Check discountCodeExist by Ajax
     * @Method discountCodeExist
     * @Date 25 Sep 2017
     * @Author RNF Technologies  
     */
    
      public function discountCodeExist() {
        $this->request->data = $_REQUEST;
        $code = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $discount_code_tbl = TableRegistry::get("PromotionalDiscountCode");
        $discount_tbl = TableRegistry::get("DiscountCode");
        $query = $discount_code_tbl->find()->where(["code" => $code])->first();
        $count = intval(count($query));
        if ($count == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            echo json_encode($arrayToJs);
        } else {
            $query_p = $discount_tbl->find()->where(["code" => $code])->first();
            $count_p = intval(count($query_p));
            if ($count_p == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            echo json_encode($arrayToJs);
           }else{
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = true;   // RETURN TRUE
            echo json_encode($arrayToJs);
           }
        }
    }
    
    
     /**
     * Check discountCodeExist1 by Ajax
     * @Method discountCodeExist1
     * @Date 25 Sep 2017
     * @Author RNF Technologies  
     */
    public function discountCodeExist1() {
        $this->request->data = $_REQUEST;
        $code = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $itsId = $this->request->data['itsId'];
        $discount_code_tbl = TableRegistry::get("PromotionalDiscountCode");
        $discount_tbl = TableRegistry::get("DiscountCode");
        if (isset($itsId) && $itsId != '') {
            $query = $discount_code_tbl->find()->where(["code" => $code, "id !=" => $itsId])->first();
        } else {
            $query = $discount_code_tbl->find()->where(["code" => $code])->first();
        }
        $count = intval(count($query));
        if ($count == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            echo json_encode($arrayToJs);
        } else {
            $query_p = $discount_tbl->find()->where(["code" => $code])->first();
            $count_p = intval(count($query_p));
            if ($count_p == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            echo json_encode($arrayToJs);
           }else{
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = true;   // RETURN TRUE
            echo json_encode($arrayToJs);
           }
        }
    }
    
    
     /**
     * Check discountCodeValid by Ajax
     * @Method discountCodeValid
     * @Date 26 Sep 2017
     * @Author RNF Technologies  
     */
    public function discountCodeValid() {
        $this->request->data = $_REQUEST;
        $code = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $itsId = @$this->request->data['itsId'];
        $discount_code_tbl = TableRegistry::get("DiscountCode");
        $pramotional_code_tbl = TableRegistry::get("PromotionalDiscountCode");
        if (isset($itsId) && $itsId != '') {
            $query = $discount_code_tbl->find()->where(["code" => $code, "id !=" => $itsId])->first();
        } else {
            $query = $discount_code_tbl->find()->where(["code" => $code])->first();
        }
        $count = intval(count($query));
        if ($count == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            echo json_encode($arrayToJs);
        } else {
            $query_p = $pramotional_code_tbl->find()->where(["code" => $code])->first();
            $count_p = intval(count($query_p));
            if ($count_p == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
            echo json_encode($arrayToJs);
           }else{
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = true;   // RETURN TRUE
            echo json_encode($arrayToJs);
           }
        }
    }
    
     /**
     * Check setDefaultCard by Ajax
     * @Method setDefaultCard
     * @Date 29 Sep 2017
     * @Author RNF Technologies  
     */
    
        public function setDefaultCard() {
        if ($this->request->is("ajax")) {
            $session = $this->request->session()->read("User");
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $mid = $this->request->data["mid"];
            // Stripe payment gateway default setting 
            
            $card_info_table = TableRegistry::get("PheramorUserCardInfo");
            $card = $card_info_table->get($id);
            $default_set = $this->Stripe->defaultSetcard($card->customer_id,$card->card_token);
            $update = $conn->execute("UPDATE pheramor_user_card_info set is_default = '0' WHERE user_id = '$mid' ");
            $update = $conn->execute("UPDATE pheramor_user_card_info set is_default = '1' WHERE id = '$id' ");
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }
    
     /**
     * Check select users for send notification
     * @Method sendPromotionalCode
     * @Date 29 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function sendPromotionalCode() { 
         $p_id=$this->request->data['id'];
         
            
         $use_tbl = TableRegistry::get("PheramorUser");
         $data = $use_tbl->find("all")->contain(["PheramorUserProfile"])->where(['is_deleted' => 0,'activated'=>1,'role_name'=>'member'])->toArray();
        ?>
        <link href="<?php echo $this->request->base;?>/css/assets/global/plugins/jquery-multi-select/css/multi-select.css" rel="stylesheet" id="style_components" type="text/css" />
        <script src="<?php echo $this->request->base;?>/js/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js" type="text/javascript"></script>      
        <script src="<?php echo $this->request->base;?>/js/assets/pages/scripts/components-multi-select.min.js" type="text/javascript"></script>      
            
            <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Select Members for Send Notification</h4>
                    </div>
                    <div class="modal-body form">
                        <form  class="form-horizontal form-row-seperated" method="post" action="<?php echo $this->request->base; ?>/PromotionalDiscountCode/sendNotification">
                                                            <div class="form-body">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Select Member</label>
                                                                    <div class="col-md-9">
                                                                        <input type="hidden" name="PCODE" value="<?Php echo $p_id;?>">
                                                                        <select multiple="multiple" class="multi-select" id="my_multi_select2" name="my_multi_select1[]">
                                                                            <optgroup label="Select All">
                                                                            <?php
                                                                            if(!empty($data)){
                                                                                foreach($data as $dataval){ //print_r($dataval);?>
                                                                                  <option value="<?php echo $dataval->id;?>"><?php echo $dataval['pheramor_user_profile'][0]['first_name']." ".$dataval['pheramor_user_profile'][0]['last_name'];?></option>
                                                                            <?php } } ?>
                                                                             </optgroup>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                            <div class="form-actions">
                                                                <div class="row">
                                                                    <div class="col-md-offset-3 col-md-9">
                                                                        <button type="submit" class="btn green">
                                                                            <i class="fa fa-check"></i> Submit</button>
                                                                       <button type="button" class="btn grey-salsa btn-outline" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                    </div>
                    
               

  <?php }
    
    
   /**
     * Check membership amount
     * @Method getAmountByMemberships
     * @Date 06 Oct 2017
     * @Author RNF Technologies  
     */
    
  public function getAmountByMemberships() {
      if ($this->request->is('ajax')) {
            $discount=0.00;
            $discount_code='';
            $session = $this->request->session()->read("User");
            $mid = $this->request->data['mid'];
            $pheramor_subscription_tbl = TableRegistry::get("PheramorSubscription");
            $row = $pheramor_subscription_tbl->get($mid)->toArray();
            $cat_id = $row['subscription_cat_id'];
            if ($this->PHMFunction->getCategoryType($cat_id) == 'product') {
                $user_id = $this->request->data['user_id'];
                $price_arr = json_decode($row['subscription_amount']);
                $actualprice = $this->PHMFunction->getUserRegisterWeek($user_id, $price_arr);
                $sellingprice=$actualprice;
                $referal_tbl = TableRegistry::get("PheramorUserReferred");
                $refer_data = $referal_tbl->find("all")->where(['refer_to' => $user_id,'is_credit'=>'0'])->first();
                if(!empty($refer_data)){
                  $discount_per = 20;
                  $discount= $actualprice * ($discount_per / 100);
                  $discount_code = $this->PHMFunction->getUserReferCode($refer_data->refer_from);
                }
                
                $sellingprice = $actualprice - $discount;
                echo $actualprice.'@@'.$discount.'@@'.$sellingprice.'@@'.$discount_code;
                
            } else {
                echo $row['subscription_amount'];
            }


            die;
       }
    }
  
   /**
     * Check membership End date and price
     * @Method getMembershipEndDatePrice
     * @Date 06 Oct 2017
     * @Author RNF Technologies  
     */
    
    
  public function getMembershipEndDatePrice() {
        $this->loadComponent("PHMFunction");
        $format = $this->PHMFunction->date_format();
        $date = $this->request->data["date"];
        if (!$date) {
            echo $date;
            die;
        }
        $discount=0;
        $date = str_replace("/", "-", $date);
        $membership_id = $this->request->data["membership"];
        $date1 = date($format, strtotime($date));
        $sdate = date('Y-m-d', strtotime($date));
        $membership_table = TableRegistry::get("PheramorSubscription");
        $membership_payment_table = TableRegistry::get("PheramorPayment");
        $row = $membership_table->get($membership_id)->toArray();
        $period = $row["subscription_length"];
        $last_date = $end_date = date($format, strtotime("+ {$period} {$row['subscription_type']}", strtotime($date1)));
        $due = $row["subscription_amount"];
        $mid = $this->request->data["user_id"];
         /// Discount Code Here
        
        $exist_member = $membership_payment_table->find()->where(["plan_status" => '1', "payment_status" => '1', "user_id"=>$mid])->first();
        if(!empty($exist_member))
        {
            $start = strtotime($exist_member['start_date']);
            $end = strtotime($exist_member['end_date']);
            $days_between = floor(abs($end - $start) / 86400);
            if ($exist_member['paid_amount'] > 0) {
                $paid_amount = $exist_member['paid_amount'];
                $dayamount = number_format($paid_amount / $days_between, 2);
                $current = strtotime($sdate);
                $remain_date = floor(abs($end - $current) / 86400);
                $remain_amt = number_format($remain_date * $dayamount, 2);
                $due = $due - $remain_amt;
            
            }
        }
        
        $discount_info_tbl = TableRegistry::get("UserSubscriptionDiscount");
        $mem_discount_data = $discount_info_tbl->find()->where(['user_id' => $mid,'subscription_id'=>$membership_id])->first();
        if(count($mem_discount_data))
        {
           $discount=$mem_discount_data['discount_amt'];
        }
        $due=$due-$discount;
        echo $last_date . '@@' . $due.'@@'.$discount;
        die;
       // echo $last_date . '@@' . $due;
       // die;
    }
  
   /**
     * Check membership maxRefundAmount
     * @Method maxRefundAmount
     * @Date 09 Oct 2017
     * @Author RNF Technologies  
     */
    
   public function maxRefundAmount() {
        $this->request->data = $_REQUEST;
        $reques = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $mp_id = $this->request->data['mp_id'];
        $amount = $this->request->data['amount'];
        $total_refund=$this->PHMFunction->get_total_refunded_amt($mp_id);
        $mp_table = TableRegistry::get("PheramorPayment");
        $memshipPaymentDetails = $mp_table->get($mp_id)->toArray();
        $paid_amount=$memshipPaymentDetails['paid_amount'];
        $refunded_amount = $paid_amount-$total_refund; 
       
        if ($amount > $refunded_amount) {
                $arrayToJs[0] = $fieldId;
                $arrayToJs[1] = false;  // RETURN FALSE
                echo json_encode($arrayToJs);
                die;
            
        }else {
           
            $arrayToJs[0] = $fieldId;
            if($amount >0){
            $arrayToJs[1] = true;   // RETURN TRUE
            }else{
            $arrayToJs[1] = false;  // RETURN FALSE
            } 
            echo json_encode($arrayToJs);
            die;
        }
    }

  
   /**
     * Upload User Profile Gallery
     * @Method updateProfileGallery
     * @Date 12 Oct 2017
     * @Author RNF Technologies  
     */
  
    public function updateProfileGallery(){
        
      //  $delete_key=array(array('Key' => '1506066166_967826.jpg')); // delete im
       // $this->Aws->delete($delete_key); die;
        
    //  $data=$this->Aws->movefile('/var/www/html/pheramor/webroot/upload/1506066166_967826.jpg');
        // print_r($data);
      //  die;
			
         $session = $this->request->session()->read("User");
         $conn = ConnectionManager::get('default');
         //print_r($_FILES); die;
       
         // $data=$this->Aws->uploadfile($temp,$image_name_actual);
        
       // echo  $data['ObjectURL'];
         //  echo "<pre>"; print_r($data); die;
         $user_id=$this->request->data['user_id'];
        $gallery_query = "SELECT id from pheramor_user_gallery where user_id='$user_id'";
        $gallery_query = $conn->execute($gallery_query);
        $gallery_data = $gallery_query->fetchAll('assoc');
        foreach ($gallery_data as $imgdata) {
            if (!empty($_FILES["profile_img_$imgdata[id]"]['name'])) {
                $image = $this->PHMFunction->mutliuploadImageUsers($_FILES["profile_img_$imgdata[id]"],$imgdata['id']);
//                if (!empty($image)) {
//                    $insert = $conn->execute("update pheramor_user_gallery set name='" . $image . "' where  id='" . $imgdata['id'] . "'");
//                }
            }
        }
        echo $this->userAjaxGallery($user_id); 
        die;
         
       /*if (isset($_FILES["profile_img"]['name'])) {
            $no_files = count($_FILES["profile_img"]['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $image = $this->PHMFunction->mutliuploadImageUser($_FILES["profile_img"], $i);
                
                if (!empty($image))
                    $insert = $conn->execute("insert into pheramor_user_gallery set user_id='" . $user_id . "',name='" .$image ."', gallery_type='image' ,created_date='" . date('Y-m-d H:i:s') . "', created_by='".$session['id']."'");
            }
        }*/
    }
    
    /**
     * Ajax Gallery Html Load
     * @Method userAjaxGallery
     */
    
    public function userAjaxGallery($user_id){
        
       $conn = ConnectionManager::get('default');
       $gallery_query = "SELECT * from pheramor_user_gallery where user_id='$user_id'";
       $gallery_query = $conn->execute($gallery_query);
       $gallery_data = $gallery_query->fetchAll('assoc');
      // print_r($gallery_data); die;
       $html ='';
       $cc=0;
       foreach($gallery_data  as $imgdata){
            $image = (!empty($imgdata['name'])) ? $imgdata['name'] : $this->request->webroot."upload/no-image.png";
             if(!empty($imgdata['name'])){
                 $checked='';
                 if($imgdata['is_profile']==1){ $checked='checked';}
            
               $html .='<div class="col-md-3">
                   <div class="fileinput fileinput-exists" data-provides="fileinput">
                   <h4>'.$imgdata['category_name'].'</h4>
                   <div class="fileinput-new thumbnail"  style="width: 200px; height: 150px;">
                       <img src="'.$image.'" alt="" id="gallertyImg_'.$imgdata['id'].'" > </div>
                   <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> 
                       <img src="'.$image.'" alt="" style="height:200px;" > 
                   </div>
                   <div>

                   <span class="btn default btn-file" id="gallertyID_'.$imgdata['id'].'" style="display:none;">
                    <span class="fileinput-new"> Select image </span>
                    <span class="fileinput-exists"> Change </span>
                    <input type="file" name="profile_img[]"> </span>
                    <a href="javascript:;" class="btn red fileinput-exists del-gallery" data-id="'.$imgdata['id'].'" data-dismiss="fileinput"> Remove </a>
                   <input data-id="'.$imgdata['id'].'" type="radio" name="is_profile" value="1" '.$checked.' data-on-text="Profile" data-off-text="No" class="make-switch switch-radio1">

                   </div>

                 </div>
              </div>';
             }else{
               $html .='<div class="col-md-3">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                              <h4>'.$imgdata['category_name'].'</h4>
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="'.$this->request->webroot . 'upload/no-image.png" alt=""> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                            <div>
                                <span class="btn default btn-file">
                                    <span class="fileinput-new"> Select image </span>
                                    <span class="fileinput-exists"> Change </span>
                                    <input type="file" name="profile_img-'.$imgdata['id'].'"> </span>
                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>

                            </div>

                        </div>
                    </div>';  
                 
             }
              if($cc==3){ $html .='</div><div class="col-md-12">';}
               $cc++;
        }
        return $html;
    }
    
    /**
     * Delete User Profile Gallery
     * @Method deleteProfileGallery
     * @Date 12 Oct 2017
     * @Author RNF Technologies  
     */
   public function deleteProfileGallery() {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $user_id = $this->request->data['user_id'];
        $id = $this->request->data['id'];
        $gallery_table = TableRegistry::get("PheramorUserGallery");
        $galleryDetails = $gallery_table->get($id)->toArray();
        $imgname1=$galleryDetails['name'];
        $imgname2=$galleryDetails['profile_thumb'];
        $imgname3=$galleryDetails['profile_thumb_blur'];
        $key1= str_replace('https://pheramorapp.s3.amazonaws.com/', '', $imgname1);
        $key2= str_replace('https://pheramorapp.s3.amazonaws.com/', '', $imgname2);
        $key3= str_replace('https://pheramorapp.s3.amazonaws.com/', '', $imgname3);
        $delete = $conn->execute("update  pheramor_user_gallery set  name='', profile_thumb='', profile_thumb_blur='' where user_id='$user_id' and id='$id'");
        $delete_key=array(array('Key' => $key1),array('Key'=>$key2),array('Key'=>$key3)); // delete im
        $this->Aws->delete($delete_key); 
        echo $this->userAjaxGallery($user_id);
       // die;
        die;
    }

    /**
     * Update UserInterst
     * @Method deleteProfileGallery
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateUserInterest() {
        $session = $this->request->session()->read("User");
        $conn = ConnectionManager::get('default');
        $user_id = $this->request->data['user_id'];
        $movies_array = $this->request->data['movies'];
        $musics_array = $this->request->data['musics'];
        //$tags_array = $this->request->data['tags'];
        $games_array = $this->request->data['games'];
        $drinks_array = $this->request->data['drinks'];
        $foods_array = $this->request->data['foods'];
        $hobbies_array = $this->request->data['hobbies'];
        $sports_array = $this->request->data['sports'];
        $hashtags_array = $this->request->data['hashtags_data'];
        $books_array = $this->request->data['books'];
        $this->updateBooksinterest($user_id,$books_array);
        $this->updateMoviesinterest($user_id,$movies_array);
        $this->updateMusicinterest($user_id,$musics_array);
       // $this->updateTagsinterest($user_id,$tags_array);
        $this->updateGamesinterest($user_id,$games_array);
        $this->updateDrinksinterest($user_id,$drinks_array);
        $this->updateFoodsinterest($user_id,$foods_array);
        $this->updateHobbiesinterest($user_id,$hobbies_array);
        $this->updateSportsinterest($user_id,$sports_array);
        $this->updateUserHastgas($user_id,$hashtags_array);
        echo "sucess";
        
        
    }
    
     /**
     * Update User HashTags
     * @Method updateUserHastgas
     * @Date 3 Jan 2018
     * @Author RNF Technologies  
     */ 
    public function updateUserHastgas($user_id, $hashtags_array) {
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($hashtags_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_hash_tags where user_id='" . $user_id . "' ");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_hash_tags set hash_tags='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "'  ");
        else
            $update2 = $conn->execute("insert into pheramor_user_hash_tags set hash_tags='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "' ");
    }
    /**
     * Update UserInterst Sports
     * @Method updateSportsinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateSportsinterest($user_id, $sports_array) {
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($sports_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='sport'");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='sport' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='sport' ");
    }
    
    
     /**
     * Update UserInterst Hobbies
     * @Method updateHobbiesinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateHobbiesinterest($user_id, $hobbies_array) {
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($hobbies_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='hobby'");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='hobby' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='hobby' ");
    }
    
    
    
    /**
     * Update UserInterst Foods
     * @Method updateMoviesinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateFoodsinterest($user_id, $foods_array) {
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($foods_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='food'");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='food' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='food' ");
    }
    
    
    
   /**
     * Update UserInterst Games
     * @Method updateMoviesinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateGamesinterest($user_id, $games_array) {
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($games_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='game'");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='game' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='game' ");
    }

    /**
     * Update UserInterst Drinks
     * @Method updateMoviesinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateDrinksinterest($user_id, $drinks_array) {
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($drinks_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='drink'");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='drink' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='drink' ");
    }
     /**
     * Update UserInterst Books
     * @Method updateBooksinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    
    public function updateBooksinterest($user_id, $books_array){
        
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($books_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='book'");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='book' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='book' ");
 
    }
    /**
     * Update UserInterst Movies
     * @Method updateMoviesinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateMoviesinterest($user_id, $movies_array) {
        $conn = ConnectionManager::get('default');
        $movies_array_string = '';
        foreach ($movies_array as $movname) {
            $movies_array_string .= $movname;
            $movies_array_string .= ',';
        }
        $movies_array_string = substr(trim($movies_array_string), 0, -1);
        $selectMovies = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='movie'");
        if ($selectMovies->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$movies_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='movie' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$movies_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='movie' ");
    }

    
    /**
     * Update UserInterst Music
     * @Method updateMusicinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateMusicinterest($user_id, $music_array) {
        $conn = ConnectionManager::get('default');
        $music_array_string = '';
        foreach ($music_array as $musicname) {
            $music_array_string .= $musicname;
            $music_array_string .= ',';
        }
        $music_array_string = substr(trim($music_array_string), 0, -1);
        $selectMusic = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='music'");
        if ($selectMusic->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$music_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='music' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$music_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='music' ");
    }
    /**
     * Update UserInterst Tags
     * @Method updateTagsinterest
     * @Date 13 Oct 2017
     * @Author RNF Technologies  
     */ 
    public function updateTagsinterest($user_id, $tags_array) {
        $conn = ConnectionManager::get('default');
        $tags_array_string = '';
        foreach ($tags_array as $tagname) {
            $tags_array_string .= $tagname;
            $tags_array_string .= ',';
        }
        $tags_array_string = substr(trim($tags_array_string), 0, -1);
        $selectTags = $conn->execute("select * from pheramor_user_interest where user_id='" . $user_id . "' and interest_type='tags'");
        if ($selectTags->count() > 0)
            $update2 = $conn->execute("update pheramor_user_interest set interest_id='$tags_array_string',created_date='" . date("Y-m-d H:i:s") . "' where user_id='" . $user_id . "' and interest_type='tags' ");
        else
            $update2 = $conn->execute("insert into pheramor_user_interest set interest_id='$tags_array_string' , created_date='" . date("Y-m-d H:i:s") . "', user_id='" . $user_id . "', interest_type='tags' ");
    }

    /**
     * Display Product Invoice
     * @Method viewProductInvoice
     * @Date 24  Oct 2017
     * @Author RNF Technologies  
     */ 
    
    public function viewProductInvoice($mp_id){
         $this->loadComponent("PHMFunction");
         $payment_tbl = TableRegistry::get("PheramorProductPayment");
         $setting_tbl = TableRegistry::get("PheramorGeneralSetting");
         $sys_data = $setting_tbl->find()->select(["company_name", "company_address", "company_logo", "date_format", "company_phone"])->hydrate(false)->toArray();
         $sys_data[0]["company_logo"] = (!empty($sys_data[0]["company_logo"])) ? $this->request->base . "/webroot/upload/" . $sys_data[0]["company_logo"] : $this->request->base . "/webroot/img/Thumbnail-img.png";
         $data = $payment_tbl->find("all")->contain(["PheramorUser"])->where(["PheramorProductPayment.id" => $mp_id])->hydrate(false)->toArray();
    //print_r($data);

        $session = $this->request->session();
        $float_l = ($session->read("User.is_rtl") == "1") ? "right" : "left";
        $float_r = ($session->read("User.is_rtl") == "1") ? "left" : "right";
        $host = $_SERVER['HTTP_HOST'];
        if ($host == 'localhost')
            $host .= '/pheramor';

        // echo "<pre>";print_r($data); die;
        if ($data[0]['payment_status'] == 1) {
            $status = "Paid";
        } else {
            $status = "Failed";
        }
       
        $user_data=$this->PHMFunction->get_user_details($data[0]['user_id']);
        $user_profile_data=$user_data['pheramor_user_profile'][0];

        //$url = (isset($data[0]['gym_product']['image']) && $data[0]['gym_product']['image'] != "") ? $data[0]['gym_product']['image'] : $this->request->webroot . "upload/no_image_placeholder.png";
        ?>
        <div class="modal-header">
            <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->


            <a class="btn btn-danger active pull-right" target="_blank" title="PDF" href="http://<?php echo $host; ?>/PheramorProductPayment/pdf-view/2/<?php echo $mp_id; ?>">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </a>

            <h4 class="modal-title" id="gridSystemModalLabel"><?php echo __("Invoice"); ?></h4>
        </div>
        <div class="modal-body">
            <div id="invoice_print"> 
                <table width="100%" border="0">
                    <tbody>
                        <tr>
                            <td width="70%">
                                <img style="max-height:80px;" src="<?php echo $this->request->webroot.'img/pheramore.png';//$sys_data[0]["gym_logo"]; ?>">
                            </td>
                            <td align="right" width="24%">
                                <h5><?php
                                    $issue_date = $data[0]['created_date']->format($sys_data[0]['date_format']);
                                    $issue_date = date($sys_data[0]['date_format'], strtotime($issue_date));
                                    echo __('Issue Date') . " : " . $issue_date;
                                    ?></h5>
                                <h5><?php
                                    echo __('Status') . " : ";
                                    echo "<span class='label label-success'>";
                                    echo __($status);
                                    echo "</span>";
                                   
                                    ?>
                                </h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table width="100%" border="0">
                    <tbody>
                        <tr>
                            <td align="<?php echo $float_l; ?>">
                                <h4><?php echo __('Payment To'); ?> </h4>
                            </td>
                            <td align="<?php echo $float_r; ?>">
                                <h4><?php echo __('Bill To'); ?> </h4>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="<?php echo $float_l; ?>">
                                <?php
                                echo $sys_data[0]["company_name"] . "<br>";
                                echo $sys_data[0]["company_address"] . ",";
                                 echo $sys_data[0]["company_phone"] . "<br>";
                                ?>
                            </td>
                            <td valign="top" align="<?php echo $float_r; ?>">
                                <?php
                                $member_id = $user_profile_data["member_id"];
                                echo $user_profile_data["first_name"] . " " . $user_profile_data["last_name"] . "<br>";
                                echo $user_profile_data["address"] . ",";
                                echo $user_profile_data["city"] . ",";
                                 echo $user_profile_data["state"] . ",";
                                echo $user_profile_data["zipcode"] . ",<BR>";
                               
                                //echo $user_profile_data["country"] . ",";
                                echo $user_profile_data["mobile"] . "<br>";
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                       
                            <th class="text-center"> <?php echo __('Product Name'); ?></th>
                            <th class="text-center"> <?php echo __('Product Price'); ?></th>
                            <th class="text-center"> <?php echo __('Discount Amount'); ?></th>
                            <th class="text-center"> <?php echo __('Paid Amount'); ?></th>
                            <th class="text-center"><?php echo __('Status'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                    <td class="text-center">1</td>
                 
                    <td class="text-center"><?php echo $this->PHMFunction->get_subscription_name($data[0]["product_id"]); ?></td>
                    <td class="text-center"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo $data[0]["product_amount"]; ?></td>
                    <td class="text-center"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo $data[0]["discount_amount"]; ?></td>
                    <td class="text-center"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo number_format((float) ($data[0]["paid_amount"]), 2, '.', ''); ?></td>
                    <td class="text-center"><?php echo $status ?></td>
                    </tbody>
                </table>
                <hr>
                <table width="100%" border="0">
                    <tbody>
                        <tr>
                            <td width="70%" align="<?php echo $float_r; ?>"><strong><?php echo __('Grand Total :'); ?></strong></td>
                            <td width="10%" align="<?php echo $float_r; ?>"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo number_format((float) ($data[0]["paid_amount"]), 2, '.', ''); ?></td>
                            <td width="20%" >&nbsp;</td>
                        </tr>


                    </tbody>			
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("Close"); ?></button>				
            </div>
        </div>
    <?php } 
    
    /**
     * Display Subscription Invoice
     * @Method viewInvoice
     * @Date 25  Oct 2017
     * @Author RNF Technologies  
     */ 
    
        public function viewInvoice($mp_id){
         $this->loadComponent("PHMFunction");
         $payment_tbl = TableRegistry::get("PheramorPayment");
         $setting_tbl = TableRegistry::get("PheramorGeneralSetting");
         $sys_data = $setting_tbl->find()->select(["company_name", "company_address", "company_logo", "date_format", "company_phone"])->hydrate(false)->toArray();
         $sys_data[0]["company_logo"] = (!empty($sys_data[0]["company_logo"])) ? $this->request->base . "/webroot/upload/" . $sys_data[0]["company_logo"] : $this->request->base . "/webroot/img/Thumbnail-img.png";
         $data = $payment_tbl->find("all")->contain(["PheramorUser"])->where(["PheramorPayment.id" => $mp_id])->hydrate(false)->toArray();
    //print_r($data);

        $session = $this->request->session();
        $float_l = ($session->read("User.is_rtl") == "1") ? "right" : "left";
        $float_r = ($session->read("User.is_rtl") == "1") ? "left" : "right";
        $host = $_SERVER['HTTP_HOST'];
        if ($host == 'localhost')
            $host .= '/pheramor';

        // echo "<pre>";print_r($data); die;
        if ($data[0]['payment_status'] == 1) {
            $status = "Paid";
        } else {
            $status = "Failed";
        }
       
        $user_data=$this->PHMFunction->get_user_details($data[0]['user_id']);
        $user_profile_data=$user_data['pheramor_user_profile'][0];

        //$url = (isset($data[0]['gym_product']['image']) && $data[0]['gym_product']['image'] != "") ? $data[0]['gym_product']['image'] : $this->request->webroot . "upload/no_image_placeholder.png";
        ?>
        <div class="modal-header">
            <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->


            <a class="btn btn-danger active pull-right" target="_blank" title="PDF" href="http://<?php echo $host; ?>/PheramorProductPayment/pdf-views/2/<?php echo $mp_id; ?>">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </a>

            <h4 class="modal-title" id="gridSystemModalLabel"><?php echo __("Invoice"); ?></h4>
        </div>
        <div class="modal-body">
            <div id="invoice_print"> 
                <table width="100%" border="0">
                    <tbody>
                        <tr>
                            <td width="70%">
                                <img style="max-height:80px;" src="<?php echo $this->request->webroot.'img/pheramore.png';//$sys_data[0]["gym_logo"]; ?>">
                            </td>
                            <td align="right" width="24%">
                                <h5><?php
                                    $issue_date = $data[0]['created_date']->format($sys_data[0]['date_format']);
                                    $issue_date = date($sys_data[0]['date_format'], strtotime($issue_date));
                                    echo __('Issue Date') . " : " . $issue_date;
                                    ?></h5>
                                <h5><?php
                                    echo __('Status') . " : ";
                                    echo "<span class='label label-success'>";
                                    echo __($status);
                                    echo "</span>";
                                   
                                    ?>
                                </h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table width="100%" border="0">
                    <tbody>
                        <tr>
                            <td align="<?php echo $float_l; ?>">
                                <h4><?php echo __('Payment To'); ?> </h4>
                            </td>
                            <td align="<?php echo $float_r; ?>">
                                <h4><?php echo __('Bill To'); ?> </h4>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="<?php echo $float_l; ?>">
                                <?php
                                echo $sys_data[0]["company_name"] . "<br>";
                                echo $sys_data[0]["company_address"] . ",";
                                 echo $sys_data[0]["company_phone"] . "<br>";
                                ?>
                            </td>
                            <td valign="top" align="<?php echo $float_r; ?>">
                                <?php
                                $member_id = $user_profile_data["member_id"];
                                echo $user_profile_data["first_name"] . " " . $user_profile_data["last_name"] . "<br>";
                                echo $user_profile_data["address"] . ",";
                                echo $user_profile_data["city"] . ",";
                                 echo $user_profile_data["state"] . ",";
                                echo $user_profile_data["zipcode"] . ",<BR>";
                               
                                //echo $user_profile_data["country"] . ",";
                                echo $user_profile_data["mobile"] . "<br>";
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                       
                            <th class="text-center"> <?php echo __('Subscription Name'); ?></th>
                            <th class="text-center"> <?php echo __('Subscription Price'); ?></th>
                            <th class="text-center"> <?php echo __('Discount Amount'); ?></th>
                            <th class="text-center"> <?php echo __('Paid Amount'); ?></th>
                            <th class="text-center"><?php echo __('Status'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                    <td class="text-center">1</td>
                 
                    <td class="text-center"><?php echo $this->PHMFunction->get_subscription_name($data[0]["subscription_id"]); ?></td>
                    <td class="text-center"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo $data[0]["subscription_amount"]; ?></td>
                    <td class="text-center"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo $data[0]["discount_amount"]; ?></td>
                    <td class="text-center"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo number_format((float) ($data[0]["paid_amount"]), 2, '.', ''); ?></td>
                    <td class="text-center"><?php echo $status ?></td>
                    </tbody>
                </table>
                <hr>
                <table width="100%" border="0">
                    <tbody>
                        <tr>
                            <td width="70%" align="<?php echo $float_r; ?>"><strong><?php echo __('Grand Total :'); ?></strong></td>
                            <td width="10%" align="<?php echo $float_r; ?>"><?php echo $this->PHMFunction->get_currency_symbol(); ?> <?php echo number_format((float) ($data[0]["paid_amount"]), 2, '.', ''); ?></td>
                            <td width="20%" >&nbsp;</td>
                        </tr>


                    </tbody>			
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("Close"); ?></button>				
            </div>
        </div>
    <?php } 
    
    /**
     * Check getUserGenericData by Ajax
     * @Method getUserGenericData
     * @Date 07 March 2018
     * @Author RNF Technologies  
     */
    
    public function getUserGenericData(){
        
        $requestData = $_REQUEST;
      
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'pgi.id',
            1 => 'pgi.pheramor_kit_ID',
            2 => 'puf.first_name',
            3 => 'pu.email',
            4 => 'pgi.user_id',
            5 => 'pgi.created_date',
            6 => 'pgi.pheramor_kit_ID'
            
           
                // 2=> 'activated'
        );

        $sql = "SELECT id";
        $sql .= " FROM pheramor_genetic_information";
        $query = $conn->execute($sql);
        $totalData = $query->count();

        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


        $sql = "SELECT pgi.id,pgi.created_date,pgi.user_id,pgi.pheramor_kit_ID,pu.email,puf.first_name,puf.last_name FROM pheramor_genetic_information pgi  LEFT JOIN pheramor_user pu on pgi.user_id=pu.id";
        $sql .= " LEFT JOIN pheramor_user_profile puf on pu.id=puf.user_id Where 1";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND (pgi.pheramor_kit_ID LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR puf.first_name LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR pu.email LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR pgi.created_date LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
       //echo $sql;
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
        $query = $conn->execute($sql);



        /* $music_table = TableRegistry::get("PheramorMusic");
          $music_data =$music_table->find("all")->where(['is_deleted'=>'0'])->toArray();
          $totalData = intval(count($music_data)); */
        $data = array();


       // $totalFiltered = $totalData;
        // print_r($query); die;
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
           //print_r($row); die;
           $view_url = Router::url(['controller' => 'PheramorAjax', 'action' => 'viewUserGeneticData/' . $row['id']]);
           $action = "<a href='javascript:void(0)' class='view_jmodal btn btn-sm btn-circle btn-default btn-editable' data-url='{$view_url}' id='{$row['id']}' ><i class='fa fa-search'></i> View</a>";
           
           
            if (!empty($row['user_id'])) {
                $status = '<div class="mt-element-ribbon bg-grey-steel">
                                                <div class="ribbon ribbon-round ribbon-border-dash ribbon-color-primary uppercase">Associated</div>
                                                 </div>';
                $action .= "<div class='portlet mt-element-ribbon light portlet-fit resetgenticID' data-id='{$row['user_id']}' data-kit-id='{$row['id']}' >
                                                    <div class='ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-primary uppercase'>
                                                        <div class='ribbon-sub ribbon-clip ribbon-right'></div>reset genetic ID</div>
                                                   </div>";
            } else {
                $status = '<div class="mt-element-ribbon bg-grey-steel">
                                                <div class="ribbon ribbon-round ribbon-border-dash ribbon-color-warning uppercase">Pending</div>
                                                 </div>';
                if($this->PHMFunction->checkGenticData($row['id'])){
                    
                     $action .= "<div class='portlet mt-element-ribbon light portlet-fit' >
                                                    <div class='ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase'>
                                                        <div class='ribbon-sub ribbon-clip ribbon-right'></div>Good</div>
                                                   </div>"; 
                }else{
                     $action .= "<div class='portlet mt-element-ribbon light portlet-fit resetgenticID' data-id='{$row['user_id']}' data-kit-id='{$row['id']}' >
                                                    <div class='ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-warning uppercase'>
                                                        <div class='ribbon-sub ribbon-clip ribbon-right'></div>reset genetic ID</div>
                                                   </div>"; 
                }
                
                  
            }
            
           // $delete_url = Router::url(['controller' => 'PheramorHashtags', 'action' => 'deleteHashtag/' . $row['id']]);
          
            
            $nestedData[] = $k;
            $nestedData[] = $row['pheramor_kit_ID'];
            $nestedData[] = ($row['first_name'])?$row['first_name']." ".$row['last_name']:'--';
            $nestedData[] = ($row['email'])?$row['email']:'--';
          //  $nestedData[] = "<strong>" . $this->PHMFunction->movieCategory($row['parent']) . "</strong>";
            $nestedData[] = $status;
            $nestedData[] = $row['created_date'];
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }



        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
        // print_r($music_data); die;
        
    }
    /**
     * Check setProfileImage by Ajax
     * @Method setProfileImage
     * @Date 25 Oct 2017
     * @Author RNF Technologies  
     */
    
        public function setProfileImage() {
        if ($this->request->is("ajax")) {
            $session = $this->request->session()->read("User");
            $conn = ConnectionManager::get('default');
            $id = $this->request->data["id"];
            $mid = $this->request->data["mid"];
            // Stripe payment gateway default setting 
            
            $gallery_info_table = TableRegistry::get("PheramorUserGallery");
            $card = $gallery_info_table->get($id);
            $profileimg=$card->profile_thumb;
            //echo "<pre>";print_r($card); die;
           // $default_set = $this->Stripe->defaultSetcard($card->customer_id,$card->card_token);
            $update = $conn->execute("UPDATE pheramor_user_gallery set is_profile = '0' WHERE user_id = '$mid' ");
            $update = $conn->execute("UPDATE pheramor_user_gallery set is_profile = '1' WHERE id = '$id' ");
            $update = $conn->execute("UPDATE pheramor_user_profile set image = '$profileimg' WHERE user_id = '$mid' ");
            $return = array();
            $return['status'] = 'success';
            echo json_encode($return);
            die;
        }
    }
    
    
    /**
     * CList Music data by Ajax
     * @Method setMusicData
     * @Date 31 Oct 2017
     * @Author RNF Technologies  */
    
      function setMusicData() {

        $requestData = $_REQUEST;
        //print_r($requestData); die;
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'title',
            1 => 'title',
            2 => 'parent',
            3 => 'activated',
            4 => 'title'
                // 2=> 'activated'
        );

        $sql = "SELECT id,title, parent, activated ";
        $sql .= " FROM pheramor_music where is_deleted='0'";
        $query = $conn->execute($sql);
        $totalData = $query->count();

        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


        $sql = "SELECT id,title, parent, activated ";
        $sql .= " FROM pheramor_music WHERE is_deleted='0' ";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( title LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR activated LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
        $query = $conn->execute($sql);



        /* $music_table = TableRegistry::get("PheramorMusic");
          $music_data =$music_table->find("all")->where(['is_deleted'=>'0'])->toArray();
          $totalData = intval(count($music_data)); */
        $data = array();


     //   $totalFiltered = $totalData;
        // print_r($query); die;
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            //print_r($row); die;

            if ($row['activated'] == 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                $status = "<span class='label label-warning'>Inactive</span>";
            }
            $edit_url = Router::url(['controller' => 'PheramorMusic', 'action' => 'index/' . $row['id']]);
            $delete_url = Router::url(['controller' => 'PheramorMusic', 'action' => 'deleteMusic/' . $row['id']]);
            $action = "<div class='btn-group'>
        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
            <i class='fa fa-angle-down'></i>
        </button>
        <ul class='dropdown-menu pull-right' role='menu'>

            <li>
                <a href='{$edit_url}'>
                    <i class='icon-pencil'></i> Edit Music </a>
            </li>
            <li>
                <a href='{$delete_url}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                    <i class='icon-trash'></i> Delete Music </a>
            </li>

            </ul>
            </div>";
            $nestedData[] = $k;
            $nestedData[] = $row['title'];
            $nestedData[] = "<strong>" . $this->PHMFunction->musicCategory($row['parent']) . "</strong>";
            $nestedData[] = $status;
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }



        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
        // print_r($music_data); die;
    }

     /**
     * CList Movie data by Ajax
     * @Method setMovieData
     * @Date 31 Oct 2017
     * @Author RNF Technologies  */
    
      function setMovieData() {

        $requestData = $_REQUEST;
        //print_r($requestData); die;
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'title',
            1 => 'title',
            2 => 'parent',
            3 => 'activated',
            4 => 'title'
                // 2=> 'activated'
        );

        $sql = "SELECT id,title, parent, activated ";
        $sql .= " FROM pheramor_movies where is_deleted='0'";
        $query = $conn->execute($sql);
        $totalData = $query->count();

        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


        $sql = "SELECT id,title, parent, activated ";
        $sql .= " FROM pheramor_movies WHERE is_deleted='0'";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( title LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR activated LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
        $query = $conn->execute($sql);



        /* $music_table = TableRegistry::get("PheramorMusic");
          $music_data =$music_table->find("all")->where(['is_deleted'=>'0'])->toArray();
          $totalData = intval(count($music_data)); */
        $data = array();


       // $totalFiltered = $totalData;
        // print_r($query); die;
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            //print_r($row); die;

            if ($row['activated'] == 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                $status = "<span class='label label-warning'>Inactive</span>";
            }
            $edit_url = Router::url(['controller' => 'PheramorMovies', 'action' => 'index/' . $row['id']]);
            $delete_url = Router::url(['controller' => 'PheramorMovies', 'action' => 'deleteMovie/' . $row['id']]);
            $action = "<div class='btn-group'>
        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
            <i class='fa fa-angle-down'></i>
        </button>
        <ul class='dropdown-menu pull-right' role='menu'>

            <li>
                <a href='{$edit_url}'>
                    <i class='icon-pencil'></i> Edit Movies </a>
            </li>
            <li>
                <a href='{$delete_url}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                    <i class='icon-trash'></i> Delete Movies </a>
            </li>

            </ul>
            </div>";
            $nestedData[] = $k;
            $nestedData[] = $row['title'];
            $nestedData[] = "<strong>" . $this->PHMFunction->movieCategory($row['parent']) . "</strong>";
            $nestedData[] = $status;
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }



        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
        // print_r($music_data); die;
    }
    
    /**
     * CList Books data by Ajax
     * @Method setBookData
     * @Date 31 Oct 2017
     * @Author RNF Technologies  */
    
      function setBookData() {
      ini_set('memory_limit', '256M');
        $requestData = $_REQUEST;
      //  echo "<pre>";print_r($requestData); die;
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'name',
            1 => 'name',
            2 => 'parent',
            3 => 'status',
            4 => 'name'
                // 2=> 'activated'
        );

        $sql = "SELECT id,name, parent, status ";
        $sql .= " FROM pheramor_books where is_deleted='0' limit 0,100";
        $query = $conn->execute($sql);
        $totalData = $query->count();

        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
       $sql = "SELECT id,name, parent, status ";
        $sql .= " FROM pheramor_books WHERE is_deleted='0'";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR status LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
      //  echo $sql; die;
        $query = $conn->execute($sql);



        /* $music_table = TableRegistry::get("PheramorMusic");
          $music_data =$music_table->find("all")->where(['is_deleted'=>'0'])->toArray();
          $totalData = intval(count($music_data)); */
        $data = array();


       // $totalFiltered = $totalData;
        // print_r($query); die;
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            //print_r($row); die;

            if ($row['status'] == 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                $status = "<span class='label label-warning'>Inactive</span>";
            }
            $edit_url = Router::url(['controller' => 'PheramorBooks', 'action' => 'index/' . $row['id']]);
            $delete_url = Router::url(['controller' => 'PheramorBooks', 'action' => 'deleteBook/' . $row['id']]);
            $action = "<div class='btn-group'>
        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
            <i class='fa fa-angle-down'></i>
        </button>
        <ul class='dropdown-menu pull-right' role='menu'>

            <li>
                <a href='{$edit_url}'>
                    <i class='icon-pencil'></i> Edit Books </a>
            </li>
            <li>
                <a href='{$delete_url}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                    <i class='icon-trash'></i> Delete Books </a>
            </li>

            </ul>
            </div>";
            $nestedData[] = $k;
            $nestedData[] = $row['name'];
            $nestedData[] = "<strong>" . $this->PHMFunction->bookCategory($row['parent']) . "</strong>";
            $nestedData[] = $status;
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }



        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
        // print_r($music_data); die;
    }
    
    
    /**
     * CList Hobbies data by Ajax
     * @Method setHobbiesData
     * @Date 31 Oct 2017
     * @Author RNF Technologies  */
    
      function setHobbiesData() {

        $requestData = $_REQUEST;
      
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'name',
            1 => 'name',
            2 => 'status',
            3 => 'name'
           
                // 2=> 'activated'
        );

        $sql = "SELECT id,name, status ";
        $sql .= " FROM pheramor_hobbies where is_deleted='0'";
        $query = $conn->execute($sql);
        $totalData = $query->count();

        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


        $sql = "SELECT id,name, status ";
        $sql .= " FROM pheramor_hobbies WHERE is_deleted='0'";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
           // $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR status LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
        $query = $conn->execute($sql);



        /* $music_table = TableRegistry::get("PheramorMusic");
          $music_data =$music_table->find("all")->where(['is_deleted'=>'0'])->toArray();
          $totalData = intval(count($music_data)); */
        $data = array();


       // $totalFiltered = $totalData;
        // print_r($query); die;
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            //print_r($row); die;

            if ($row['status'] == 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                $status = "<span class='label label-warning'>Inactive</span>";
            }
            $edit_url = Router::url(['controller' => 'PheramorHobbies', 'action' => 'index/' . $row['id']]);
            $delete_url = Router::url(['controller' => 'PheramorHobbies', 'action' => 'deleteHobbie/' . $row['id']]);
            $action = "<div class='btn-group'>
        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
            <i class='fa fa-angle-down'></i>
        </button>
        <ul class='dropdown-menu pull-right' role='menu'>

            <li>
                <a href='{$edit_url}'>
                    <i class='icon-pencil'></i> Edit Hobbies </a>
            </li>
            <li>
                <a href='{$delete_url}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                    <i class='icon-trash'></i> Delete Hobbies </a>
            </li>

            </ul>
            </div>";
            $nestedData[] = $k;
            $nestedData[] = $row['name'];
          //  $nestedData[] = "<strong>" . $this->PHMFunction->movieCategory($row['parent']) . "</strong>";
            $nestedData[] = $status;
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }



        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
        // print_r($music_data); die;
    }
    
    
     /**
     * CList Games data by Ajax
     * @Method setGamesData
     * @Date 31 Oct 2017
     * @Author RNF Technologies  */
    
      function setGamesData() {

        $requestData = $_REQUEST;
      
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'name',
            1 => 'name',
            2 => 'status',
            3 => 'name'
           
                // 2=> 'activated'
        );

        $sql = "SELECT id,name, status ";
        $sql .= " FROM pheramor_games where is_deleted='0'";
        $query = $conn->execute($sql);
        $totalData = $query->count();
       $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
       $sql = "SELECT id,name, status ";
        $sql .= " FROM pheramor_games WHERE is_deleted='0'";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
           // $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR status LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        $query = $conn->execute($sql);

        $data = array();


       
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            if ($row['status'] == 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                $status = "<span class='label label-warning'>Inactive</span>";
            }
            $edit_url = Router::url(['controller' => 'PheramorGames', 'action' => 'index/' . $row['id']]);
            $delete_url = Router::url(['controller' => 'PheramorGames', 'action' => 'deleteGame/' . $row['id']]);
            $action = "<div class='btn-group'>
        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
            <i class='fa fa-angle-down'></i>
        </button>
        <ul class='dropdown-menu pull-right' role='menu'>

            <li>
                <a href='{$edit_url}'>
                    <i class='icon-pencil'></i> Edit Games </a>
            </li>
            <li>
                <a href='{$delete_url}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                    <i class='icon-trash'></i> Delete Games </a>
            </li>

            </ul>
            </div>";
            $nestedData[] = $k;
            $nestedData[] = $row['name'];
            $nestedData[] = $status;
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }
         $json_data = array(
            "draw" => intval($requestData['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data   // total data array
        );
         echo json_encode($json_data);  // send data as json format
      
    }
    
    /**
     * CList Sports data by Ajax
     * @Method setGamesData
     * @Date 31 Oct 2017
     * @Author RNF Technologies  */
    
      function setSportsData() {

        $requestData = $_REQUEST;
      
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'name',
            1 => 'name',
            2 => 'status',
            3 => 'name'
           
                // 2=> 'activated'
        );

        $sql = "SELECT id,name, status ";
        $sql .= " FROM pheramor_sports where is_deleted='0'";
        $query = $conn->execute($sql);
        $totalData = $query->count();
       $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
       $sql = "SELECT id,name, status ";
        $sql .= " FROM pheramor_sports WHERE is_deleted='0'";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
           // $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR status LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        $query = $conn->execute($sql);

        $data = array();


       
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            if ($row['status'] == 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                $status = "<span class='label label-warning'>Inactive</span>";
            }
            $edit_url = Router::url(['controller' => 'PheramorSports', 'action' => 'index/' . $row['id']]);
            $delete_url = Router::url(['controller' => 'PheramorSports', 'action' => 'deleteSport/' . $row['id']]);
            $action = "<div class='btn-group'>
        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
            <i class='fa fa-angle-down'></i>
        </button>
        <ul class='dropdown-menu pull-right' role='menu'>

            <li>
                <a href='{$edit_url}'>
                    <i class='icon-pencil'></i> Edit Sprots </a>
            </li>
            <li>
                <a href='{$delete_url}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                    <i class='icon-trash'></i> Delete Sports </a>
            </li>

            </ul>
            </div>";
            $nestedData[] = $k;
            $nestedData[] = $row['name'];
            $nestedData[] = $status;
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }
         $json_data = array(
            "draw" => intval($requestData['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data   // total data array
        );
         echo json_encode($json_data);  // send data as json format
      
    }
    
    
    public function setMusicSelectData()
    {
        $requestData = $_REQUEST;
        $where='';
        if(!empty($requestData['q'])){
            $where .= " AND title LIKE '%".$requestData['q']."%'"; 
        }
        $conn = ConnectionManager::get('default');
        $sql = "SELECT id,title";
        $sql .= " FROM pheramor_movies where is_deleted='0' AND parent!='0' $where  limit 10";
        $query = $conn->execute($sql);
        
        //Music
        $music_table = TableRegistry::get("PheramorMusic");
        
        $music_cat = "SELECT * from pheramor_music where is_deleted='0' and parent='0'";
       $music_cat = $conn->execute($music_cat);
       $musics_cat_data = $music_cat->fetchAll('assoc');
       foreach ($musics_cat_data as $mcatdata) {
          $musics[$mcatdata['title']] = $music_table
                            ->find("list", ["keyField" => "id", "valueField" => "title"])
                            ->where(['parent' => $mcatdata['id'],'is_deleted'=>0])->hydrate(false)->toArray(); 
       }
       print_r($musics); die;
       /* $sql = "SELECT items.id, items.title FROM items 
		WHERE title LIKE '%".$_GET['q']."%'
		LIMIT 10"; 
        $result = $mysqli->query($sql);*/
        $json = [];
        foreach($musics as $row){
             $json[] = ['id'=>$row['id'], 'text'=>$row['title']];
        }
        echo json_encode($json);
    }
    
    
    
    /**
     * Delete Membership Discount by Ajax
     * @Method deleteDiscount
     * @Date 13 Nov 2017
     * @Author RNF Technologies  */
     
    public function deleteDiscount(){
        if ($this->request->is("ajax")) {
           $conn = ConnectionManager::get('default');
           $id = $this->request->data["id"];
           $user_id = $this->request->data["mid"];
           $conn->execute("delete from user_subscription_discount   WHERE user_id = '" . $user_id . "' and id='" . $id . "'  ");
                 
          
        }
   } 
    
    /**
     * Update Membership Discount by Ajax
     * @Method updateDiscountAmount
     * @Date 13 Nov 2017
     * @Author RNF Technologies  */ 
    public function updateDiscountAmount(){
        
       if ($this->request->is("ajax")) {
           
            $conn = ConnectionManager::get('default');
            $discount_list = $this->request->data['discount_list'];
            $discount_amt = $this->request->data["discount_amt"];
            $membership_id = $this->request->data["selected_membership"];
            $user_id = $this->request->data["user_id"];
            /// membership amount
            $session = $this->request->session()->read("User");
            $sucess=0;
            $failed=0;
            if(!empty($discount_list)){
            foreach($discount_list as $discount){
                $discount_list = array();
                $discount = json_decode($discount);
                $discount_amt=$discount[0];
                $membership_id=$discount[1];
                if($discount_amt >0)
                {
                    $stmt = $conn->execute("SELECT discount_amt FROM user_subscription_discount WHERE user_id = '" . $user_id . "' and   subscription_id='" . $membership_id . "'");
                    if ($stmt->count()) {
                        $conn->execute("UPDATE user_subscription_discount SET  discount_amt='" . $discount_amt . "'  WHERE user_id = '" . $user_id . "' and subscription_id='" . $membership_id . "'");
                        
                    } else {
                        $conn->execute("INSERT INTO user_subscription_discount (user_id, subscription_id, discount_amt,created_date) VALUES ('" . $user_id . "','" . $membership_id . "','" . $discount_amt . "','" . date('Y-m-d h:i:s') . "')");
                        
                    }
                    $sucess++;
                   // echo json_encode(array("status" => "success"));
                    //die;
                }else{
                    $failed++;
                  //echo json_encode(array("status" => "fail"));
                   //die;   
                }
                
                }
                
                if($sucess>0){
                    echo json_encode(array("status" => "success"));
                    die;
                }else{
                    echo json_encode(array("status" => "fail"));
                    die;  
                }
            }
            
            
           
           
        } 
        
    }
    
    
    /** 
     * Update Generic Data Password by Ajax
     * @Method updateGenericPassword
     * @Date 15 May 2018
     * @author RNF Technologies      
     * 
     */
    
    public function updateGenericPassword()
    {
         if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            $current_password = $this->request->data['current_password'];
            $password = $this->request->data["password"];
            $stmt = $conn->execute("SELECT *  FROM pheramor_general_setting WHERE generic_data_pass = '" .trim($current_password) . "' and   id='1'");
            //echo "SELECT *  FROM pheramor_general_setting WHERE generic_data_pass = '" .trim($current_password) . "' and   id='1'"; die;
            if ($stmt->count()) {
               // die('ashok');
                $conn->execute("UPDATE pheramor_general_setting SET  generic_data_pass='" . $password . "'  WHERE id = '1' ");
                $status='success';
              } else {
                $status='fail';
               }
           echo json_encode(array("status" => $status));
           die;
         }

        
    }
    
    
     /**
     * Get Membership Category type
     * @Method getCategoryType
     * @Date 15 Nov 2017
     * @Author RNF Technologies  */ 
    
    public function getCategoryType()
    {
         if ($this->request->is("ajax")) {
           $conn = ConnectionManager::get('default');
           $id = $this->request->data["id"];
           $category_table = TableRegistry::get("PheramorSubscriptionCategory");
           $categories = $category_table->find("all")->select(['category_type'])->where(['id'=>$id]);
           $categories = $categories->first();
           echo json_encode($categories);
        }
    }
    
    
     /**
     * Clint Hashtags data by Ajax
     * @Method setHashtagsData
     * @Date 17 Nov 2017
     * @Author RNF Technologies  */
    
      function setHashtagsData() {

        $requestData = $_REQUEST;
      
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'title',
            1 => 'title',
            2 => 'status',
            3 => 'title'
           
                // 2=> 'activated'
        );

        $sql = "SELECT id,title, status ";
        $sql .= " FROM pheramor_hashtags where is_deleted='0'";
        $query = $conn->execute($sql);
        $totalData = $query->count();

        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


        $sql = "SELECT id,title, status ";
        $sql .= " FROM pheramor_hashtags WHERE is_deleted='0'";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( title LIKE '" . $requestData['search']['value'] . "%' ";
           // $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR status LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
        $query = $conn->execute($sql);



        /* $music_table = TableRegistry::get("PheramorMusic");
          $music_data =$music_table->find("all")->where(['is_deleted'=>'0'])->toArray();
          $totalData = intval(count($music_data)); */
        $data = array();


       // $totalFiltered = $totalData;
        // print_r($query); die;
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            //print_r($row); die;

            if ($row['status'] == 1) {
                $status = "<span class='label label-success'>Acive</span>";
            } else {
                $status = "<span class='label label-warning'>Inactive</span>";
            }
            $edit_url = Router::url(['controller' => 'PheramorHashtags', 'action' => 'index/' . $row['id']]);
            $delete_url = Router::url(['controller' => 'PheramorHashtags', 'action' => 'deleteHashtag/' . $row['id']]);
            $action = "<div class='btn-group'>
        <button class='btn btn-xs green dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions
            <i class='fa fa-angle-down'></i>
        </button>
        <ul class='dropdown-menu pull-right' role='menu'>

            <li>
                <a href='{$edit_url}'>
                    <i class='icon-pencil'></i> Edit Hashtags </a>
            </li>
            <li>
                <a href='{$delete_url}' onclick=\"return confirm('Are you sure,you want to delete this record?');\">
                    <i class='icon-trash'></i> Delete Hashtags </a>
            </li>

            </ul>
            </div>";
            $nestedData[] = $k;
            $nestedData[] = $row['title'];
          //  $nestedData[] = "<strong>" . $this->PHMFunction->movieCategory($row['parent']) . "</strong>";
            $nestedData[] = $status;
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }



        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
        // print_r($music_data); die;
    }
    
    /**
     * Save User Generic data
     * @Method updateUserGeneticData
     * @Date 21 Nov 2017
     * @Author RNF Technologies  */ 
    
    public function updateUserGeneticData(){
        
         if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            $pheramor_kit_ID=$this->request->data['pheramor_kit_ID'];
            $user_id = $this->request->data['user_id'];
            $pheramor_genetic_table = TableRegistry::get("PheramorGeneticInformation");
           // $row = $pheramor_genetic_table->get($did);
             $stmt = $conn->execute("SELECT id FROM pheramor_genetic_information  WHERE user_id = '" . $user_id . "'");
            if ($stmt->count()) {
               $data=$stmt->fetch('assoc');
               $did=$data['id'];
               $row = $pheramor_genetic_table->get($did);
               $update = $pheramor_genetic_table->patchEntity($row, $this->request->data());
               $pheramor_genetic_table->save($update);
               
            } else {
                 $genetic = $pheramor_genetic_table->newEntity();
                 $this->request->data['created_date']= date('Y-m-d H:i:s');
                 $this->request->data['updated_date']= date('Y-m-d H:i:s');
                 $genetic = $pheramor_genetic_table->patchEntity($genetic, $this->request->data());
                 $pheramor_genetic_table->save($genetic);
               
            }
             echo $pheramor_kit_ID;
            
         }
    }
    
     /**
     * Save User Generic data
     * @Method updateUserGeneticData
     * @Date 21 Nov 2017
     * @Author RNF Technologies  */ 
    
    function setGeneticData() {

        $requestData = $_REQUEST;
      
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'dissimilarity',
            1 => 'generic_type',
            2 => 'generic_type',
            3 => 'dissimilarity',
            4 => 'generic_type'
           
                // 2=> 'activated'
        );

        $sql = "SELECT id,gene1,gene2,dissimilarity,generic_type ";
        $sql .= " FROM pheramor_generic_master ";
        $query = $conn->execute($sql);
        $totalData = $query->count();
       $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
       $sql = "SELECT id,gene1,gene2,dissimilarity,generic_type ";
        $sql .= " FROM pheramor_generic_master WHERE 1=1";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND ( generic_type LIKE '" . $requestData['search']['value'] . "%' ";
           // $sql .= " OR parent LIKE '" . $requestData['search']['value'] . "%' ";

            $sql .= " OR dissimilarity LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        $query = $conn->execute($sql);

        $data = array();


       
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
            
            $nestedData[] = $k;
            $nestedData[] = '***********';
            $nestedData[] = '***********';
            $nestedData[] = $row['dissimilarity'];
            $nestedData[] = $row['generic_type'];

            $data[] = $nestedData;
            $k++;
        }
         $json_data = array(
            "draw" => intval($requestData['draw']), 
            "recordsTotal" => intval($totalData), 
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data   // total data array
        );
         echo json_encode($json_data);  // send data as json format
      
    }
   
    
    
     /**
     * Get Neighborhood data
     * @Method getNeighborhoodData
     * @Date 01 Dec 2017
     * @Author RNF Technologies  */ 
    
   /* public function getNeighborhoodData(){
       if ($this->request->is("ajax")) {
       $zipcode = $this->request->data["zipcode"];  
       $filename = "http://labs.rnftechnologies.org:5000/getNeighborhood?zipcode=".$zipcode;
       $filestring = file_get_contents($filename);
       echo $filestring;
       }
        
    }
    */
     /**
     * Get Neighborhood data
     * @Method getNeighborhoodData
     * @Date 01 Dec 2017
     * @Author RNF Technologies  */ 
    
    public function getNeighborhoodData(){
       if ($this->request->is("ajax")) {
       $zipcode = $this->request->data["zipcode"];  
       /*  $filename = "http://labs.rnftechnologies.org:5000/getNeighborhood?zipcode=".$zipcode;
       $filestring = file_get_contents($filename);
       echo $filestring;
      */
        $zipcode_table = TableRegistry::get("HoustonZipcode");
        $neighborhood = $zipcode_table->find("all")->select(['neighborhood'])->where(['zipcode'=>$zipcode]);
        $neighborhood = $neighborhood->first();
        echo json_encode($neighborhood);
       
       }
        
    }
    
     /**
     * Update Tracking Order Status
     * @Method updateOrderStatus
     * @Date 05 Jan 2018
     * @Author RNF Technologies  */ 
   
    public function updateOrderStatus(){
        
      if ($this->request->is("ajax")) {
          $conn = ConnectionManager::get('default');
          $id = $this->request->data["id"];  
          $user_id = $this->request->data["user_id"];  
          $order_status = $this->request->data["step"];
          $conn->execute("UPDATE pheramor_product_payment SET  order_status='" . $order_status . "'  WHERE user_id = '" . $user_id . "' and id='" . $id . "'");
          ?>        
        <div class="mt-element-step">

                        <div class="row step-line">
                            <?php
                            $myorder = $this->PHMFunction->orderTrackingStatus($user_id);
                            // print_r($myorder); 
                            $orderStatusID = '';
                            if (empty($myorder)) {
                                $orderStatus = 0;
                            } else {
                                $orderStatus = $myorder['order_status'];
                                $orderStatusID = $myorder['id'];
                            }
                            // $orderStatus=$this->Pheramor->orderTrackingStatus($user_id);
                            $orderHead = array('1' => "Shipping and Registration", '2' => 'Return after Registration', '3' => 'Under Sequence', '4' => 'Ready');
                            $orderDesc = array('1' => "Your Pheramor Kit is on its way", '2' => 'Your perfect date is one DNA test away!', '3' => 'Your results are being analyzed!', '4' => 'Good job!');
                            for ($i = 1; $i <= 4; $i++) {

                                if ($i == 1) {
                                    $cssstyle = 'first';
                                } elseif ($i == 4) {
                                    $cssstyle = 'last';
                                } else {
                                    $cssstyle = '';
                                }
                                if ($orderStatus == $i) {
                                    $activestatus = 'active';
                                } elseif ($i < $orderStatus) {
                                    $activestatus = 'done';
                                } else {
                                    $activestatus = '';
                                }
                                ?>
                                <div class="col-md-3 mt-step-col <?php echo $cssstyle . "  " . $activestatus; ?>">
                                    <div class="mt-step-number bg-white chorderstatus" style="cursor:pointer;"  data-id='<?Php echo $orderStatusID; ?>' data-step='<?Php echo $i; ?>'><?Php echo $i; ?></div>
                                    <div class="mt-step-title uppercase font-grey-cascade"><?php echo $orderHead[$i]; ?></div>
                                    <div class="mt-step-content font-grey-cascade"><?php echo $orderDesc[$i]; ?></div>
                                </div>
            <?php } ?>
            </div>
        </div>
          <?php 
          
      }  
        
        
    }
    
       /**
     * Check masterTitleUnique by Ajax
     * @Method titleExist
     * @Date 17 Jan 2018
     * @Author RNF Technologies  
     */
    
     public function masterTitleUnique() {
        $this->request->data = $_REQUEST;
        $name = $this->request->data['fieldValue'];
        $fieldId = $this->request->data['fieldId'];
        $itsId = @$this->request->data['edit_id'];
        $table_name = $this->request->data['tbl_name'];
        $tbl = TableRegistry::get($table_name);
        if (isset($itsId) && $itsId != '') {
            $query = $tbl->find()->where([$fieldId => $name,'is_deleted'=>0,"id !=" => $itsId])->first();
        } else {
             $query = $tbl->find()->where([$fieldId => $name,'is_deleted'=>0])->first();
        }
        
       
        $count = intval(count($query));
        if ($count == 1) {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = false;  // RETURN TRUE
             echo json_encode($arrayToJs);
        } else {
            $arrayToJs[0] = $fieldId;
            $arrayToJs[1] = true;   // RETURN TRUE
            echo json_encode($arrayToJs);
        }
    }
    
    /// New file
    
   public function viewUserGeneticData() {
        if ($this->request->is("ajax")) {
            $id = $this->request->data["id"];
            $genetic_table = TableRegistry::get("PheramorGeneticInformation");
            $data = $genetic_table->find()->where(["id" => $id])->hydrate(false)->first();
           // print_r($data); die;
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel"><?php echo __("User Genetic Data"); ?></h4>
            </div>
            <div class="modal-body" id="main_gen_data">
                
                <div class="row">
                    <div class="col-sm-7 col-sm-offset-1">
                        <input type="hidden" value="<?php echo $id;?>" id="pheramor_id">
                        <input type="password" class="generic_pass_val form-control" placeholder="<?php echo __("Enter Password"); ?>">
                    </div>
                    <div class="col-sm-4">
                        <button class="add-generic-password btn btn-flat btn-success" data-url="<?php echo $this->request->base . "/PheramorAjax/checkUserGenericPassword"; ?>"><?php echo __("Submit"); ?></button>
                    </div>
                    <div class="col-md-11 col-sm-offset-1" id="generic_response"></div>
                </div>
                <!--<div class="row">
                    <div class="col-sm-12 table table-striped">
                        <div class="portlet box green">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-comments"></i>Pheramor ID : <?php echo $data['pheramor_kit_ID'];?> </div>
                                          </div>
                                        <div class="portlet-body">
                                            <div class="table-scrollable">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>HLAA-1</th>
                                                            <th>HLAA-2</th>
                                                            <th>HLAB-1</th>
                                                            <th>HLAB-2</th>
                                                            <th>HLAC-1</th>
                                                            <th>HLAC-2</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if (!empty($data)) { ?>
                                                        <tr>
                                                            <td><?php echo ($data['HLA_A_1'])?$data['HLA_A_1']:'--';?></td>
                                                            <td><?php echo ($data['HLA_A_2'])?$data['HLA_A_2']:'--';?></td>
                                                            <td><?php echo ($data['HLA_B_1'])?$data['HLA_B_1']:'--';?></td>
                                                             <td><?php echo ($data['HLA_B_2'])?$data['HLA_B_2']:'--';?></td>
                                                             <td><?php echo ($data['HLA_C_1'])?$data['HLA_C_1']:'--';?></td>
                                                            <td><?php echo ($data['HLA_C_2'])?$data['HLA_C_2']:'--';?></td>
                                                        </tr>
                                                            <?Php } ?>
                                                       
                                                    </tbody>
                                                </table>
                                                
                                                
                                            </div>
                                        </div>
                            
                            <div class="portlet-body">
                                            <div class="table-scrollable">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>HLADPB1-1</th>
                                                            <th>HLADPB1-2</th>
                                                            <th>HLADQB1-1</th>
                                                            <th>HLADQB1-2</th>
                                                            <th>HLADRB-1</th>
                                                            <th>HLADRB-2</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?php echo ($data['HLA_DPB1_1'])?$data['HLA_DPB1_1']:'--';?></td>
                                                            <td><?php echo ($data['HLA_DPB1_2'])?$data['HLA_DPB1_2']:'--';?></td>
                                                            <td><?php echo ($data['HLA_DQB1_1'])?$data['HLA_DQB1_1']:'--';?></td>
                                                            <td><?php echo ($data['HLA_DQB1_2'])?$data['HLA_DQB1_2']:'--';?></td>
                                                            <td><?php echo ($data['HLA_DRB_1'])?$data['HLA_DRB_1']:'--';?></td>
                                                            <td><?php echo ($data['HLA_DRB_2'])?$data['HLA_DRB_2']:'--';?></td>
                                                        </tr>
                                                            
                                                       
                                                    </tbody>
                                                </table>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                       
                    </div>
                </div>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo __("Close"); ?></button>				
            </div>
            <?php
        }
    }
    
     /**
     * Check Generic Data Password Field by Ajax
     * @Method checkGenericPassword
     * @Date 28 Nov 2017
     * @Author RNF Technologies  
     */
    public function checkUserGenericPassword()
    {
              $return = array();
              if ($this->request->is('ajax')) {
              $password = $this->request->data['password'];
              $general_setting_tbl = TableRegistry::get("PheramorGeneralSetting");
              $setting_data = $general_setting_tbl->find()->where(['id' => 1])->first();
              $match_pass = $setting_data->generic_data_pass;
              if($password===$match_pass){
                  $id=$this->request->data['id'];
                  $generic_info_tbl = TableRegistry::get("PheramorGeneticInformation");
                  $mem_generic_data = $generic_info_tbl->find()->where(['id' => $id])->first();
                  
                  $style1=@$mem_generic_data->HLA_A_1 ? $mem_generic_data->HLA_A_1 : '--';
                  $style2=@$mem_generic_data->HLA_A_2 ? $mem_generic_data->HLA_A_2 : '--';
                  $style3=@$mem_generic_data->HLA_B_1 ? $mem_generic_data->HLA_B_1 : '--';
                  $style4=@$mem_generic_data->HLA_B_2 ? $mem_generic_data->HLA_B_2 : '--';
                  $style5=@$mem_generic_data->HLA_C_1 ? $mem_generic_data->HLA_C_1 : '--';
                  $style6=@$mem_generic_data->HLA_C_2 ? $mem_generic_data->HLA_C_2 : '--';
                  $style7=@$mem_generic_data->HLA_DPB1_1 ? $mem_generic_data->HLA_DPB1_1 : '--';
                  $style8=@$mem_generic_data->HLA_DPB1_2 ? $mem_generic_data->HLA_DPB1_2 : '--';
                  $style9=@$mem_generic_data->HLA_DQB1_1 ? $mem_generic_data->HLA_DQB1_1 : '--';
                  $style10=@$mem_generic_data->HLA_DQB1_2 ? $mem_generic_data->HLA_DQB1_2 : '--';
                  $style11=@$mem_generic_data->HLA_DRB_1 ? $mem_generic_data->HLA_DRB_1 : '--';
                  $style12=@$mem_generic_data->HLA_DRB_2 ? $mem_generic_data->HLA_DRB_2 : '--';
                  $style0=@$mem_generic_data->pheramor_kit_ID ? $mem_generic_data->pheramor_kit_ID : '';
                  
                   $return='<div class="row">
                    <div class="col-sm-12 table table-striped">
                        <div class="portlet box green">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-comments"></i>Pheramor ID : '.$style0.' </div>
                                          </div>
                                        <div class="portlet-body">
                                            <div class="table-scrollable">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>HLAA-1</th>
                                                            <th>HLAA-2</th>
                                                            <th>HLAB-1</th>
                                                            <th>HLAB-2</th>
                                                            <th>HLAC-1</th>
                                                            <th>HLAC-2</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>'.$style1.'</td>
                                                            <td>'.$style2.'</td>
                                                            <td>'.$style3.'</td>
                                                            <td>'.$style4.'</td>
                                                            <td>'.$style5.'</td>
                                                            <td>'.$style6.'</td>
                                                             
                                                        </tr>
                                                           
                                                       
                                                    </tbody>
                                                </table>
                                                
                                                
                                            </div>
                                        </div>
                            
                            <div class="portlet-body">
                                            <div class="table-scrollable">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>HLADPB1-1</th>
                                                            <th>HLADPB1-2</th>
                                                            <th>HLADQB1-1</th>
                                                            <th>HLADQB1-2</th>
                                                            <th>HLADRB-1</th>
                                                            <th>HLADRB-2</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                           <td>'.$style7.'</td>
                                                            <td>'.$style8.'</td>
                                                            <td>'.$style9.'</td>
                                                            <td>'.$style10.'</td>
                                                            <td>'.$style11.'</td>
                                                            <td>'.$style12.'</td>
                                                        </tr>
                                                            
                                                       
                                                    </tbody>
                                                </table>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                       
                    </div>
                </div>';
                  
                echo $return;
              }else{
                  echo false;
              }
              
          }
    }
    
    
    /**
     * Save User Shipping Address
     * @Method updateUserGeneticData
     * @Date 22 March 2018
     * @Author RNF Technologies  */ 
    
    public function updateUserShippingAddress(){
        
         if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
            
            $user_id = $this->request->data['user_id'];
            $user_shipping_table = TableRegistry::get("PheramorUserShippingAddress");
            $stmt = $conn->execute("SELECT id FROM pheramor_user_shipping_address  WHERE user_id = '" . $user_id . "'");
            if ($stmt->count()) {
               $data=$stmt->fetch('assoc');
               $did=$data['id'];
               $row = $user_shipping_table->get($did);
               $update = $user_shipping_table->patchEntity($row, $this->request->data());
               $user_shipping_table->save($update);
               
            } else {
                 $ship = $user_shipping_table->newEntity();
                 $this->request->data['created_date']= date('Y-m-d H:i:s');
                // $this->request->data['updated_date']= date('Y-m-d H:i:s');
                 $ship = $user_shipping_table->patchEntity($ship, $this->request->data());
                 $user_shipping_table->save($ship);
               
            }
            $json_data=array('status'=>'sucess');
            echo json_encode($json_data);
            
         }
    }
    
    
    
    /**
     * Reset User Genetic data and order status
     * @Method resetUserGenericID
     * @Date 28 March 2018
     * @Author RNF Technologies  */   
    
   public function resetUserGenericID(){
      
        //die;
        if ($this->request->is("ajax")) {
            $conn = ConnectionManager::get('default');
             $user_id = $this->request->data['user_id'];
             $kit_id = $this->request->data['kit_id'];
             if(!empty($user_id)){
                $update_order = $conn->execute("UPDATE pheramor_product_payment set order_status='0'  WHERE user_id = '" . $user_id . "'");
                //$delete_kit = $conn->execute("Delete from  pheramor_genetic_information   WHERE user_id = '" . $user_id . "'");
                $update_kit = $conn->execute("update pheramor_genetic_information  set user_id ='0'  WHERE user_id = '" . $user_id . "'");
                
             }else{
                 $delete_kit = $conn->execute("Delete from  pheramor_genetic_information   WHERE id = '" . $kit_id . "'"); 
                 
             }
        }
     } 
    
     /**
     * Check getUserGenericData by Ajax
     * @Method getUserGenericData
     * @Date 07 March 2018
     * @Author RNF Technologies  
     */
    
    public function getCustonNotificationData(){
        
        $requestData = $_REQUEST;
      
       
        $conn = ConnectionManager::get('default');
        $columns = array(
// datatable column index  => database column name
            0 => 'pgi.id',
            1 => 'puf.first_name',
            2 => 'pu.email',
            3 => 'pgi.device_type',
            4 => 'pgi.message',
            5 => 'pgi.datetime',
            6 => 'pgi.is_sent'
            
           
                // 2=> 'activated'
        );

        if(empty($requestData['columns'][5]['search']['value']))
        {
            $endDate=date('Y-m-d');
            $startDate=date('Y-m-d',(strtotime ( '-29 days' , strtotime ( $endDate) ) ));
            $requestData['columns'][5]['search']['value']=$startDate.'@@'.$endDate;
        }
        
        $datearr =explode('@@',$requestData['columns'][5]['search']['value']);
        $from=$datearr[0];
        $to=$datearr[1];
         $wh =" AND (DATE_FORMAT(pgi.datetime,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(pgi.datetime,'%Y-%m-%d') <= '$to')";
        
        $sql = "SELECT pgi.id";
        $sql .= " FROM push_notification_history pgi where pgi.notification_type='Pheramor' $wh";
        $query = $conn->execute($sql);
        $totalData = $query->count();

        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


        $sql = "SELECT pgi.id,pgi.datetime,pgi.user_id,pgi.device_type,pgi.message,pgi.is_sent,pu.email,puf.first_name,puf.last_name FROM push_notification_history pgi  LEFT JOIN pheramor_user pu on pgi.user_id=pu.id";
        $sql .= " LEFT JOIN pheramor_user_profile puf on pu.id=puf.user_id Where pgi.notification_type='Pheramor' $wh";
        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql .= " AND (pgi.device_type LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR puf.first_name LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR pu.email LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR pgi.datetime LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $query = $conn->execute($sql);
        $totalFiltered = $query->count(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
       //echo $sql;
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
        $query = $conn->execute($sql);



        /* $music_table = TableRegistry::get("PheramorMusic");
          $music_data =$music_table->find("all")->where(['is_deleted'=>'0'])->toArray();
          $totalData = intval(count($music_data)); */
        $data = array();


       // $totalFiltered = $totalData;
        // print_r($query); die;
        $k = $requestData['start'] + 1;
        foreach ($query as $row) {  // preparing an array
            $nestedData = array();
           //print_r($row); die;
          // $view_url = Router::url(['controller' => 'PheramorAjax', 'action' => 'viewUserGeneticData/' . $row['id']]);
          
            if (!empty($row['is_sent'])) {
              $action= "<div class='portlet mt-element-ribbon light portlet-fit'  >
                                                    <div class='ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-primary uppercase'>
                                                        <div class='ribbon-sub ribbon-clip ribbon-right'></div>Sent</div>
                                                   </div>";
            } else {
                
                     $action= "<div class='portlet mt-element-ribbon light portlet-fit resetgenticID'  >
                                                    <div class='ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-warning uppercase'>
                                                        <div class='ribbon-sub ribbon-clip ribbon-right'></div>Pending</div>
                                                   </div>"; 
               
                
                  
            }
            
           // $delete_url = Router::url(['controller' => 'PheramorHashtags', 'action' => 'deleteHashtag/' . $row['id']]);
          
            
            $nestedData[] = $k;
            $nestedData[] = ($row['first_name'])?$row['first_name']." ".$row['last_name']:'--';
            $nestedData[] = ($row['email'])?$row['email']:'--';
            $nestedData[] = ($row['device_type'])?$row['device_type']:'--';;
          //  $nestedData[] = "<strong>" . $this->PHMFunction->movieCategory($row['parent']) . "</strong>";
            $nestedData[] = ($row['message'])?$row['message']:'--';;
            $nestedData[] = $row['datetime'];
            $nestedData[] = $action;

            $data[] = $nestedData;
            $k++;
        }



        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
        // print_r($music_data); die;
        
    }
    
    
  /**
     * SEND CUSTON NOTIFICATION by Ajax
     * @Method sendCustomNotification
     * @Date 05 April 2018
     * @Author RNF Technologies  
     */
    public function sendCustomNotification(){
        
        if ($this->request->is("ajax")) {
            
           $users = $this->request->data['my_multi_select1'];
           $rendered = $this->request->data['notification_message'];
           $message_send=$rendered;
            if (!empty($users)) {
              foreach ($users as $udata) {
                    $sent=0;
                    $message = array('message' => $rendered, 'type' => 'Pheramor', 'user_id' => $udata);
                    @$push_deatils = $this->PHMFunction->user_push_details($udata);
                    if (@$push_deatils !== false) {
                        if (!empty(@$push_deatils['device_address'])) {
                            if ($push_deatils['device_type'] == 'an') {
                                $this->PHMFunction->android_notification(array($push_deatils['device_address']), $message);
                                $sent=1;
                            } else {
                                $this->PHMFunction->ios_notification(array($push_deatils['device_address']), $message);
                                $sent=1;
                            }
                            // Save Notification History
                        }
                    }
                    if(empty(@$push_deatils['device_type'])){@$push_deatils['device_type']='none';}
                  //  $clean_string = $conn->quote($message_send);
                    //$save_noti_query = "INSERT INTO push_notification_history (user_id, device_address, device_type, message, notification_type,datetime) VALUES ('" . $udata . "','" . $push_deatils['device_address'] . "','" . $push_deatils['device_type'] . "',$clean_string,'" . $message['type'] . "','" . date('Y-m-d H:i:s') . "')";
                    //$conn->execute($save_noti_query);
                    $push_notification_table = TableRegistry::get("PushNotificationHistory");
                    $notifidata = $push_notification_table->newEntity();
                    $notifidata->user_id = $udata;
                    $notifidata->device_address = @$push_deatils['device_address'];
                    $notifidata->device_type = $push_deatils['device_type'];
                    $notifidata->message = $message_send;
                    $notifidata->notification_type = $message['type'];
                    $notifidata->datetime = date('Y-m-d H:i:s');
                    $notifidata->is_sent = $sent;
                    if ($push_notification_table->save($notifidata)) {
                        $mess=1;
                    }else{
                        $mess=1;
                    }
                    
                }
            }
            
          echo json_encode(array('msg'=>$mess));  // send data as json format
        }
    }
    
      
  /**
     * Filter Member Listing by Ajax
     * @Method filterMemberNotification
     * @Date 18 May 2018
     * @Author RNF Technologies  
     */
    
    public function filterMemberNotification() {

        if ($this->request->is("ajax")) {

            $city = $this->request->data['city'];
            $gender = $this->request->data['gender'];
            $bone_marrow_donor = $this->request->data['bone_marrow_donor'];
            $generic_kit = $this->request->data['generic_kit'];
            $orientation = $this->request->data['orientation'];
            $age = $this->request->data['age'];
            $email = $this->request->data['email'];
            $kit_id = isset($this->request->data['kit_id'])?$this->request->data['kit_id']:'';
            //echo $kit_id; die;
            $conn = ConnectionManager::get('default');

            $where = '';

            if ($city != '') {
                $where .= " AND pup.city like '%$city%' ";
            }
            if ($gender != '') {
                $where .= " AND pup.gender = '$gender' ";
            }
            if ($bone_marrow_donor != '') {
                $where .= " AND pup.bone_marrow_donor = '$bone_marrow_donor' ";
            }
            if ($orientation != '') {
                $where .= " AND pup.show_me = '$orientation' ";
            }
            if ($email != '') {
                $where .= " AND pu.email = '$email' ";
            }
            if ($age != '') {
                $agearr = explode(',', $age);
                $agefrom = $agearr[0];
                $ageto = $agearr[1];
                $where .= " AND ( (YEAR(CURDATE()) - YEAR(pup.dob) >= '$agefrom') AND  (YEAR(CURDATE()) - YEAR(pup.dob) <= '$ageto'))";
            }
            $search_query = "SELECT pu.id,pup.first_name,pup.last_name,pu.email from pheramor_user pu INNER JOIN pheramor_user_profile pup on pu.id=pup.user_id where pu.role_name = 'member' and is_deleted='0' and activated='1'  $where  order by pup.first_name";
            $search_query = $conn->execute($search_query);
            $search_query = $search_query->fetchAll('assoc');

            if (!empty($search_query)) {
                $list_array = array();
                foreach ($search_query as $result) {

                    if ($generic_kit != '') {
                         
                        if($this->PHMFunction->check_kit_connected($result['id'],$generic_kit,$kit_id)){
                            
                            $list_array[$result['id']] = $result['first_name'] . " " . $result['last_name'] . ' ( ' . $result['email'] . ')';
                        }
                        
                        
                    } else {
                        $list_array[$result['id']] = $result['first_name'] . " " . $result['last_name'] . ' ( ' . $result['email'] . ')';
                    }
                }
            }
        }
        echo json_encode($list_array);

        //$str=' <optgroup label="Select All"><option value="">aaaa</option></optgroup>';
        //  echo json_encode(array('status'=>$str));
    }

    /////////////////////////////
    
    
    
    
    
    
    

    


}
