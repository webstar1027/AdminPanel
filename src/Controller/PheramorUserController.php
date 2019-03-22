<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
//use Cake\Mailer\Email;
// use GoogleCharts;

Class PheramorUserController extends AppController {

    public function initialize() {
        parent::initialize();
        
        $session = $this->request->session()->read("User");
        $this->set("session", $session);
          $this->loadComponent("PHMFunction");
    }
  
    public function index()
    {
        return $this->redirect(["action" => "memberList"]);
    }
    private function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1)
            return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    private function getToken($user_id, $length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        //$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max - 1)];
        }
      return $user_id . $token;
    }

    /**
     * Display Member Lists
     * @Method memberLists
     * @Date 13 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function memberList() {
        
       //$this->PHMFunction->generateThumb(WWW_ROOT .'upload/', WWW_ROOT."upload/thumbnails/",$thumb_img_width='255', $file_name='1508929820_176777.jpg');
        $session = $this->request->session()->read("User");
        $uid = intval($session["original_id"]);
        $conn = ConnectionManager::get('default');
        $data = $this->PheramorUser->find()->contain(["PheramorUserProfile"])->Where(["role_name" => "member",'is_deleted'=>0])
                ->hydrate(false)->toArray();
        $this->set("data", $data);
        
       }
    
     /**
     * Active Member Lists
     * @Method activateMember
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    
     public function activateMember($aid) {
        $this->autoRender = false;
        $row = $this->PheramorUser->get($aid);
        $row->activated = 1;
        if ($this->PheramorUser->save($row)) {
            $this->Flash->success(__("Success! Member activated successfully."));
            return $this->redirect(["action" => "memberList"]);
        }
    }
    
    /**
     * Delete Member Lists
     * @Method deleteMember
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    
    public function deleteMember($id=null) {
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "memberList"]);
        }
        $session = $this->request->session()->read("User");
        $row = $this->PheramorUser->get($id);
        $row->is_deleted = 2;
        if ($this->PheramorUser->save($row)) {
            $this->Flash->success(__("Success! Record Deleted Successfully."));
            return $this->redirect($this->referer());
        }
    }
    
     /**
     * Add Member Lists
     * @Method addMember
     * @Date 12 Sep 2017
     * @Author RNF Technologies  
     */
    
    
   public function addMember() {
        $conn = ConnectionManager::get('default');
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $this->set("title", __("Add Member"));
        
        // Race Listing
        
        $race = $this->PheramorUser->PheramorRace->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("race", $race);
        
        //Religion Listing
        
        $religion = $this->PheramorUser->PheramorReligion->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("religion", $religion);
        
        /// Body Type Listing
        
        $body_type = $this->PheramorUser->PheramorBodyType->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("body_type", $body_type);
        
        // orinetation Listing
        $orienation = $this->PheramorUser->PheramorOrientation->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("orienation", $orienation);
        
         // Zip code Listing
        
        $zipcode = $this->PheramorUser->HoustonZipcode->find("list", ["keyField" => "zipcode", "valueField" => "zipcode"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("zipcode", $zipcode);
        
        
        
        if ($this->request->is("post")) {
           
         //   print_r($this->request->data); die;
            $plainPassword= $this->request->data['password'];
            $this->request->data['role_id'] = 2;
            $this->request->data['role_name'] = 'member';
            $this->request->data['created_by'] = $session["id"];
            $this->request->data['created_date'] = date("Y-m-d H:i:s");
            $this->request->data['updated_date'] = date("Y-m-d H:i:s"); 
            $this->request->data['is_deleted'] = 0;
            $this->request->data['is_agree'] = 1;
            $this->request->data['login_type'] = 'email';
           
          
            $member = $this->PheramorUser->newEntity();
            $member = $this->PheramorUser->patchEntity($member, $this->request->data);

            if ($saveResult = $this->PheramorUser->save($member)) {
                   $conn->execute("UPDATE pheramor_user set password = '" . md5($plainPassword) . "',  app_password = '" . md5($plainPassword) . "' WHERE id = '" . $member->id . "'");
                    $profile= $this->PheramorUser->PheramorUserProfile->newEntity();
                   // print_r($profile); die;
                   // $show_me=implode(", ", $this->request->data['show_me']);
                    $showM = $this->request->data['show_me1']?$this->request->data['show_me1']:0;
                    $showF = $this->request->data['show_me2']?$this->request->data['show_me2']:0;
                    $show_me = $showM.','.$showF;
                    
                    $val = $this->PHMFunction->getLnt($this->request->data['zipcode']);
                    $profile_data['latitude']= $val['lat'];
                    $profile_data['longitude']= $val['lng'];
                    //$image = $this->PHMFunction->uploadImage($this->request->data['image']);
                   // $profile_data['image'] = (!empty($image)) ? $image : $this->request->webroot."upload/profile-placeholder.png";
                    $profile_data['other_race'] = (isset($this->request->data['other_race']) && !empty($this->request->data['other_race'])) ? $this->request->data['other_race'] : '';
                    $profile_data['other_religion'] = (isset($this->request->data['other_religion']) && !empty($this->request->data['other_religion'])) ? $this->request->data['other_religion'] : '';
                    if($this->request->data['race']!=5){ $profile_data['other_race']='';}
                    if($this->request->data['religion']!=9){$profile_data['other_religion']='';}
                    if(empty($this->request->data['race'])){  $this->request->data['race'] =4; }
                    if(empty($this->request->data['religion'])){ $this->request->data['religion']=5; }
                    
                    $profile_data['first_name']= $this->request->data['first_name'];
                    $profile_data['last_name']= $this->request->data['last_name'];
                    $profile_data['dob'] = date("Y-m-d", strtotime($this->request->data['dob']));
                    $profile_data['gender']= $this->request->data['gender'];
                    $profile_data['race']= $this->request->data['race'];
                     $profile_data['body_type']= $this->request->data['body_type'];
                    $profile_data['orientation']= $this->request->data['orientation'];
                    $profile_data['religion']= $this->request->data['religion'];
                    $profile_data['show_me']= $show_me;
                    $profile_data['kit_ID']= 0;
                    $profile_data['age_range']= $this->request->data['age_range'];
                   // $profile_data['enable_notification']= $this->request->data['enable_notification'];
                    $profile_data['address']= $this->request->data['address'];
                    $profile_data['city']= $this->request->data['city'];
                    $profile_data['state']= $this->request->data['state'];
                    $profile_data['country']= $this->request->data['country'];
                    $profile_data['zipcode']= $this->request->data['zipcode'];
                    $profile_data['phone']= $this->request->data['phone'];
                    $profile_data['neighborhood']= $this->request->data['neighborhood'];
                    $profile_data['profession']= $this->request->data['profession'];
                    $profile_data['about_me']= $this->request->data['about_me'];
                    $profile_data['about_status']= $this->request->data['about_status'];
                    $profile_data['facebook']= $this->request->data['facebook'];
                    $profile_data['twitter']= $this->request->data['twitter'];
                    $profile_data['Instagram']= $this->request->data['Instagram'];
                    $profile_data['bone_marrow_donor']= $this->request->data['bone_marrow_donor'];
                    $profile_data['updated_date']= date("Y-m-d H:i:s"); 
                    $profile_data['user_id']= $member->id;
                    
                   
                   
                    $profile = $this->PheramorUser->PheramorUserProfile->patchEntity($profile, $profile_data);
              
                     $saveResult = $this->PheramorUser->PheramorUserProfile->save($profile);
  
                    // Save User Gallery with category name without image
                    
                    if($this->request->data['gender']==1){
                        $mydatasave=array('Fav Hobby','Globe-trotting', 'Smiling', 'Straight Chilling', 'Suit & Tie', 'W: a Pet');
                    }else{
                        $mydatasave=array('Chilling','Fav Hobby',"Glam\'d Up",'Selfie', 'Smiling' ,'Traveling');
                    }
                    for($l=0;$l<=5;$l++)
                    {
                        $insert = $conn->execute("insert into pheramor_user_gallery set category_name='".$mydatasave[$l]."' , name='', created_date='" . date("Y-m-d H:i:s") . "',  created_by='4', user_id='" . $member->id . "', gallery_type='image' ");   
                    }
                    
                    /*** Generate Referal Code  **/
                    
                    $this->PHMFunction->generateReferralCode($member->id);
                    
                
               /* if ($this->GymMember->ReferralCode->save($referralCode)) {
                    $mailArrUser = [
                        "template" => "registration_user_mail",
                        "subject" => "GoTribe : Registration Confirmation",
                        "emailFormat" => "html",
                        "to" => $saveResult['email'],
                        "viewVars" => [
                            'name' => $saveResult['first_name'] . ' ' . $saveResult['last_name'],
                            'email' => $saveResult['email'],
                            'username' => $saveResult['username'],
                            'contact' => $saveResult['mobile'],
                            'password' => $plainPassword
                        ]
                    ];
                    $associated_licensee = $this->GYMFunction->get_user_detail($saveResult['associated_licensee']);
                    $mailArrAdmin = [
                        "template" => "registration_admin_mail",
                        "subject" => "GoTribe : User Registered",
                        "emailFormat" => "html",
                        "to" => $associated_licensee['email'],
                        "viewVars" => [
                            'name' => $saveResult['first_name'] . ' ' . $saveResult['last_name'],
                            'email' => $saveResult['email'],
                            'username' => $saveResult['username'],
                            'contact' => $saveResult['mobile'],
                            'password' => $plainPassword,
                            'adminName' => $associated_licensee['first_name'] . ' ' . $associated_licensee['last_name'],
                        ]
                    ];
                    if ($this->GYMFunction->sendEmail($mailArrUser) && $this->GYMFunction->sendEmail($mailArrAdmin)) {
                        $this->Flash->success(__("Success! Record Saved Successfully."));
                    }
                }
               */
               
            } else {
                if ($member->errors()) {
                    foreach ($member->errors() as $error) {
                        foreach ($error as $key => $value) {
                            $this->Flash->error(__($value));
                        }
                    }
                }
            }
            return $this->redirect(["action" => "memberList"]);
        }
    }
    
    
    
     /**
     * Edit Member Lists
     * @Method editMember
     * @Date 15 Sep 2017
     * @Author RNF Technologies  
     */
    
   public function editMember($id=null) {
        $conn = ConnectionManager::get('default');
        $this->set("edit", true);
        $this->set("title", __("Edit Member"));
        $this->set("eid", $id);
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "memberList"]);
        }
        $session = $this->request->session()->read("User");
        $data = $this->PheramorUser->find()->contain(["PheramorUserProfile"])->Where(["PheramorUser.id" => $id])->first();
       //echo '<pre>';print_r($data);die;
        $race = $this->PheramorUser->PheramorRace->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("race", $race);
        
        //Religion Listing
        
         $religion = $this->PheramorUser->PheramorReligion->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
         $this->set("religion", $religion);
         
          /// Body Type Listing
        
        $body_type = $this->PheramorUser->PheramorBodyType->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("body_type", $body_type);
        
        // orinetation Listing
        $orienation = $this->PheramorUser->PheramorOrientation->find("list", ["keyField" => "id", "valueField" => "name"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("orienation", $orienation);
         
         // Zip code Listing
        
        $zipcode = $this->PheramorUser->HoustonZipcode->find("list", ["keyField" => "zipcode", "valueField" => "zipcode"])->where(["is_deleted" => '0'])->hydrate(false)->toArray();
        $this->set("zipcode", $zipcode);
        
         $this->set("data", $data);
         $this->render("addMember");

        if ($this->request->is("post")) {
            //echo '<pre>';print_r($this->request->data);die;
            
            $data_check = $this->PheramorUser->find()->contain(["PheramorUserProfile"])->Where(["PheramorUser.id" => $id])->first();
            //$genderChange=pheramor_user_profile  
            if($data_check['pheramor_user_profile'][0]->gender!=$this->request->data['gender']){
                $this->genderChange($id,$this->request->data['gender']);
            }
            
            
            $row = $this->PheramorUser->get($id);
            $this->request->data['updated_date'] = date("Y-m-d H:i:s"); 
            
            $update = $this->PheramorUser->patchEntity($row, $this->request->data);
            $change_password = false;
            if( $update->dirty('password') ){
                $change_password = true;
                $plainPassword = $this->request->data['password'];
            }
            if ($saveResult = $this->PheramorUser->save($update)) {
                if($change_password){
                    $conn->execute("UPDATE pheramor_user set password = '" . md5($plainPassword) . "' WHERE id = '" . $id . "'");
                 }
                   $profile_id=$this->request->data['profile_id'];
                   $profile = $this->PheramorUser->PheramorUserProfile->get($profile_id);
                   
                    $showM = $this->request->data['show_me1']?$this->request->data['show_me1']:0;
                    $showF = $this->request->data['show_me2']?$this->request->data['show_me2']:0;
                    $show_me = $showM.','.$showF;
                    $val = $this->PHMFunction->getLnt($this->request->data['zipcode']);
                    $profile_data['latitude']= $val['lat'];
                    $profile_data['longitude']= $val['lng'];
                   
                  //  $image = $this->PHMFunction->uploadImage($this->request->data['image']);
                  //  if($image != ""){
                        // $profile_data['image'] = $image;
                   // }else{
                        //unset($this->request->data['image']);
                   // }
                   
                    $profile_data['other_race'] = (isset($this->request->data['other_race']) && !empty($this->request->data['other_race'])) ? $this->request->data['other_race'] : '';
                    $profile_data['other_religion'] = (isset($this->request->data['other_religion']) && !empty($this->request->data['other_religion'])) ? $this->request->data['other_religion'] : '';
                    if($this->request->data['race']!=5){ $profile_data['other_race']='';}
                    if($this->request->data['religion']!=9){$profile_data['other_religion']='';}
                    
                    if(empty($this->request->data['race'])){  $this->request->data['race'] =4; }
                    if(empty($this->request->data['religion'])){ $this->request->data['religion']=5; }
                    
                    
                    $profile_data['first_name']= $this->request->data['first_name'];
                    $profile_data['last_name']= $this->request->data['last_name'];
                    $profile_data['dob'] = date("Y-m-d", strtotime($this->request->data['dob']));
                    $profile_data['gender']= $this->request->data['gender'];
                    $profile_data['race']= $this->request->data['race'];
                    $profile_data['body_type']= $this->request->data['body_type'];
                    $profile_data['orientation']= $this->request->data['orientation'];
                    $profile_data['religion']= $this->request->data['religion'];
                    $profile_data['show_me']= $show_me;
                    $profile_data['age_range']= $this->request->data['age_range'];
                    //$profile_data['enable_notification']= $this->request->data['enable_notification'];
                    $profile_data['address']= $this->request->data['address'];
                    $profile_data['city']= $this->request->data['city'];
                    $profile_data['state']= $this->request->data['state'];
                    $profile_data['country']= $this->request->data['country'];
                    $profile_data['zipcode']= $this->request->data['zipcode'];
                    $profile_data['phone']= $this->request->data['phone'];
                    $profile_data['neighborhood']= $this->request->data['neighborhood'];
                    $profile_data['profession']= $this->request->data['profession'];
                    $profile_data['about_me']= $this->request->data['about_me'];
                    $profile_data['about_status']= $this->request->data['about_status'];
                    $profile_data['facebook']= $this->request->data['facebook'];
                    $profile_data['twitter']= $this->request->data['twitter'];
                    $profile_data['Instagram']= $this->request->data['Instagram'];
                    $profile_data['height']= $this->request->data['height'];
                    $profile_data['weight']= $this->request->data['weight'];
                    $profile_data['bone_marrow_donor']= $this->request->data['bone_marrow_donor'];
                    if(isset($this->request->data['birth_pill'])){
                     $profile_data['birth_pill']= $this->request->data['birth_pill'];
                    }
                    $profile_data['user_id']= $id;
                   
                   
                   $profile = $this->PheramorUser->PheramorUserProfile->patchEntity($profile, $profile_data);
                   $saveResult = $this->PheramorUser->PheramorUserProfile->save($profile);
                $this->Flash->success(__("Success! Record Saved Successfully."));
               
                return $this->redirect(["action" => "memberList"]);
            } else {
                if ($update->errors()) {
                    foreach ($update->errors() as $error) {
                        foreach ($error as $key => $value) {
                            $this->Flash->error(__($value));
                        }
                    }
                }
            }
        }
    }

   /**
     * View Member Lists
     * @Method viewMember
     * @Date 15 Sep 2017
     * @Author RNF Technologies  
     */

    public function viewMember($id = null) {
        if ($id === null) {
            $this->Flash->error(__("Error! Record not found."));
            return $this->redirect(["action" => "memberList"]);
        }
        $conn = ConnectionManager::get('default');
        $data = $this->PheramorUser->find()->contain(["PheramorUserProfile"])->Where(["PheramorUser.id" => $id])->first();
        $this->set("data", $data);
       $credit_query = "SELECT * from pheramor_user_credits where user_id='$id'";
       $credit_query = $conn->execute($credit_query);
       $credit_data = $credit_query->fetchAll('assoc');
       $this->set("credit_data", $credit_data);
       
       
       $card_info_query = "SELECT * from pheramor_user_card_info where user_id='$id'";
       $card_info_query = $conn->execute($card_info_query);
       $card_data = $card_info_query->fetchAll('assoc');
       $this->set("card_data", $card_data);
       
        ############### Sunscription module code here ###########

       $subscription = $this->PheramorUser->PheramorPayment->find("all")->contain(["PheramorSubscription"])
                        ->where(["PheramorPayment.user_id" => $id,"PheramorSubscription.subscription_cat_id !="=>9])->order([
                            'PheramorPayment.payment_status' => 'DESC',
                            'PheramorPayment.plan_status' => 'ASC',
                            'PheramorPayment.start_date' => 'ASC',
                            'PheramorPayment.id' => 'DESC'
                        ])->hydrate(false)->toArray();

        $this->set("subscription", $subscription);
        
          ############### Product module code here ###########
          $product = $this->PheramorUser->PheramorProductPayment->find("all")->contain(["PheramorSubscription"])
                        ->where(["PheramorProductPayment.user_id" => $id,"PheramorSubscription.subscription_cat_id"=>9])->order([
                            'PheramorProductPayment.payment_status' => 'DESC',
                            'PheramorProductPayment.id' => 'DESC'
                        ])->hydrate(false)->toArray();

        $this->set("product", $product);
        
        
        ############ Upload Photo Gallery #################
        
       $gallery_query = "SELECT * from pheramor_user_gallery where user_id='$id'";
       $gallery_query = $conn->execute($gallery_query);
       $gallery_data = $gallery_query->fetchAll('assoc');
       $this->set("gallery_data", $gallery_data);
       
       ###### User Interest Data  code here ######
        // Books
       
       $books_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='book'";
       $books_interest_query = $conn->execute($books_interest_query);
       $books_interest_data = $books_interest_query->fetch('assoc');
       $this->set("books_interest_data", $books_interest_data);
       
       
       // Movie
       
       $movies_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='movie'";
       $movies_interest_query = $conn->execute($movies_interest_query);
       $movies_interest_data = $movies_interest_query->fetch('assoc');
       $this->set("movies_interest_data", $movies_interest_data);
       
       ///Music
       
       $music_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='music'";
       $music_interest_query = $conn->execute($music_interest_query);
       $music_interest_data = $music_interest_query->fetch('assoc');
       $this->set("music_interest_data", $music_interest_data);
       $associated_music = array();
       $marry=explode(',',$music_interest_data['interest_id']);
            foreach($marry as $mdat){
                $associated_music[] = $mdat;
            }
      // print_r($associated_music); die;
        ///Tags
       
       $tags_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='tags'";
       $tags_interest_query = $conn->execute($tags_interest_query);
       $tags_interest_data = $tags_interest_query->fetch('assoc');
       $this->set("tags_interest_data", $tags_interest_data);
       
       //Games
       
       $game_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='game'";
       $game_interest_query = $conn->execute($game_interest_query);
       $game_interest_data = $game_interest_query->fetch('assoc');
       $this->set("game_interest_data", $game_interest_data);
       
       //Sports
       
       $sport_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='sport'";
       $sport_interest_query = $conn->execute($sport_interest_query);
       $sport_interest_data = $sport_interest_query->fetch('assoc');
       $this->set("sport_interest_data", $sport_interest_data);
       
       //Drinks
       
       $drink_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='drink'";
       $drink_interest_query = $conn->execute($drink_interest_query);
       $drink_interest_data = $drink_interest_query->fetch('assoc');
       $this->set("drink_interest_data", $drink_interest_data);
       
       //Foods
       
       $food_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='food'";
       $food_interest_query = $conn->execute($food_interest_query);
       $food_interest_data = $food_interest_query->fetch('assoc');
       $this->set("food_interest_data", $food_interest_data);
       
       //Hobbies
       
       $food_interest_query = "SELECT * from pheramor_user_interest where user_id='$id' and interest_type='hobby'";
       $food_interest_query = $conn->execute($food_interest_query);
       $hobbies_interest_data = $food_interest_query->fetch('assoc');
       $this->set("hobbies_interest_data", $hobbies_interest_data);
       
        /// Hashtags
       $hashtag_query = "SELECT * from pheramor_user_hash_tags where user_id='$id' ";
       $hashtag_query = $conn->execute($hashtag_query);
       $user_hashtags_data = $hashtag_query->fetch('assoc');
       $this->set("user_hashtags_data", $user_hashtags_data);
       
        /// Facebook Interest
       
       $fbi_query = "Select GROUP_CONCAT(interest_data)as edata,interest_type from pheramor_user_fb_interest  where user_id='$id' group by interest_type";
       $fbi_query = $conn->execute($fbi_query);
       $user_fbi_data = $fbi_query->fetchAll('assoc');
       $this->set("user_fbi_data", $user_fbi_data);
       
       
       
       
       $movies_table = TableRegistry::get("PheramorMovies");
       $music_table = TableRegistry::get("PheramorMusic");
       $tags_table = TableRegistry::get("PheramorTags");
       $hashtag_table = TableRegistry::get("PheramorHashtags");
       $game_table = TableRegistry::get("PheramorGames");
       $sport_table = TableRegistry::get("PheramorSports");
       $food_table = TableRegistry::get("PheramorFood");
       $drink_table = TableRegistry::get("PheramorDrinks");
       $hobby_table = TableRegistry::get("PheramorHobbies");
       $books_table = TableRegistry::get("PheramorBooks");
       
       ///Books
      /* $book_cat = "SELECT * from pheramor_books where is_deleted='0' and parent='0'";
       $book_cat = $conn->execute($book_cat);
       $book_cat_data = $book_cat->fetchAll('assoc');
       foreach ($book_cat_data as $mcatdata) {
          $books[$mcatdata['name']] = $books_table
                            ->find("list", ["keyField" => "id", "valueField" => "name"])
                            ->where(['parent' => $mcatdata['id'],'is_deleted'=>0])->hydrate(false)->toArray(); 
       }
       
       
       /// Music
       $music_cat = "SELECT * from pheramor_music where is_deleted='0' and parent='0'";
       $music_cat = $conn->execute($music_cat);
       $musics_cat_data = $music_cat->fetchAll('assoc');
       foreach ($musics_cat_data as $mcatdata) {
          $musics[$mcatdata['title']] = $music_table
                            ->find("list", ["keyField" => "id", "valueField" => "title"])
                            ->where(['parent' => $mcatdata['id'],'is_deleted'=>0])->hydrate(false)->toArray(); 
       }
       /// Food
       $food_cat = "SELECT * from pheramor_food where is_deleted='0' and parent='0'";
       $food_cat = $conn->execute($food_cat);
       $food_cat_data = $food_cat->fetchAll('assoc');
       foreach ($food_cat_data as $mcatdata) {
          $foods[$mcatdata['title']] = $food_table
                            ->find("list", ["keyField" => "id", "valueField" => "title"])
                            ->where(['parent' => $mcatdata['id'],'is_deleted'=>0])->hydrate(false)->toArray(); 
       }
       
       /// Movies
       $movie_cat = "SELECT * from pheramor_movies where is_deleted='0' and parent='0'";
       $movie_cat = $conn->execute($movie_cat);
       $movie_cat_data = $movie_cat->fetchAll('assoc');
       foreach ($movie_cat_data as $mcatdata) {
          $movies[$mcatdata['title']] = $movies_table
                            ->find("list", ["keyField" => "id", "valueField" => "title"])
                            ->where(['parent' => $mcatdata['id'],'is_deleted'=>0])->hydrate(false)->toArray(); 
       }*/
       
       
       $musics=$music_table->find("list", ["keyField" => "id", "valueField" => "title"])->where(['is_deleted' => 0])->hydrate(false)->toArray();
       $movies=$movies_table->find("list", ["keyField" => "id", "valueField" => "title"])->where(['is_deleted' => 0])->hydrate(false)->toArray();
       $foods=$food_table->find("list", ["keyField" => "id", "valueField" => "title"])->where(['is_deleted' => 0])->hydrate(false)->toArray();
       $books=$books_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(['is_deleted' => 0])->hydrate(false)->toArray();
       
       
       $tags_data=$tags_table->find("list", ["keyField" => "id", "valueField" => "tag"])->where(['is_deleted' => 0])->hydrate(false)->toArray(); 
       $hashtags_data=$hashtag_table->find("list", ["keyField" => "id", "valueField" => "title"])->where(['is_deleted' => 0])->hydrate(false)->toArray(); 
       $game_data=$game_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(['is_deleted' => 0])->hydrate(false)->toArray(); 
       $sport_data=$sport_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(['is_deleted' => 0])->hydrate(false)->toArray(); 
       $drink_data=$drink_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(['is_deleted' => 0])->hydrate(false)->toArray();
       $hobby_data=$hobby_table->find("list", ["keyField" => "id", "valueField" => "name"])->where(['is_deleted' => 0])->hydrate(false)->toArray();
      
       $this->set("books_data", $books);
       $this->set("movies_cat_data", $movies);
       $this->set("musics_cat_data", $musics);
       $this->set("tags_data", $tags_data);
       $this->set("game_data", $game_data);
       $this->set("sport_data", $sport_data);
       $this->set("drink_data", $drink_data);
       $this->set("food_data", $foods);
       $this->set("hobbies_data", $hobby_data);
       $this->set("hashtags_data", $hashtags_data);
      // echo "<pre>"; print_r($musics); die;
      $subscription_table = TableRegistry::get("PheramorSubscription");
       $membership = $subscription_table
                        ->find("list", ["keyField" => "id", "valueField" => "subscription_title"])
                        ->where([
                            'subscription_status' => 1,
                            "subscription_cat_id !="=>9,
                            "is_deleted" => "0"
                        ])->hydrate(false)->toArray();
        $this->set("membership",$membership);
        
        $discount_info_tbl = TableRegistry::get("UserSubscriptionDiscount");
        $mem_discount_data = $discount_info_tbl->find()->where(['user_id' => $id])->hydrate(false)->toArray();
        $this->set("mem_discount_data", @$mem_discount_data);
        
        ///Get Generic data
        
        $generic_info_tbl = TableRegistry::get("PheramorGeneticInformation");
        $mem_generic_data = $generic_info_tbl->find()->where(['user_id' => $id])->first();
        $this->set("mem_generic_data", @$mem_generic_data);
        
         // USERS' REFERRAL HISTORY
        $q = "SELECT pheramor_user_profile.image,pheramor_user_referred.is_credit,pheramor_user_referred.created_at, pheramor_user.email, CONCAT(pheramor_user_profile.first_name, ' ',pheramor_user_profile.last_name) AS name "
              ."FROM pheramor_user_referred INNER JOIN pheramor_user on pheramor_user_referred.refer_to = pheramor_user.id INNER JOIN pheramor_user_profile on pheramor_user.id=pheramor_user_profile.user_id "
                ."WHERE pheramor_user_referred.refer_from = $id";
                
        $stmt = $conn->execute($q);
        $my_referrals = $stmt->fetchAll('assoc');
        $this->set("my_referrals", $my_referrals);
        
        
       /// User Shipping Address
        
        $shipping_address_tbl = TableRegistry::get("PheramorUserShippingAddress");
        $address_data = $shipping_address_tbl->find()->where(['user_id' => $id])->first();
        $this->set("ship_address_data", @$address_data);
        
    }

     /**
     * Get Latitude and Longitude from Zipcode
     * @Method getLnt
     * @Date 18 Sep 2017
     * @Author RNF Technologies  
     */
    
    private function getLnt($zip) {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($zip)."&sensor=false";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        return $result3[0];
    }

     /**
     * Display Deleted Member Lists
     * @Method deletedMember
     * @Date 30 Oct 2017
     * @Author RNF Technologies  
     */
    
    public function deletedMember() {
        
        //$this->PHMFunction->generateThumb(WWW_ROOT .'upload/', WWW_ROOT."upload/thumbnails/",$thumb_img_width='255', $file_name='1508929820_176777.jpg');
        $session = $this->request->session()->read("User");
        $uid = intval($session["original_id"]);
        $conn = ConnectionManager::get('default');
        $data = $this->PheramorUser->find()->contain(["PheramorUserProfile"])->Where(["role_name" => "member",'is_deleted > '=>0])
                ->hydrate(false)->toArray();
        $this->set("data", $data);
        
       }
       
    /**
     * Restore Deleted Member
     * @Method restoreDeletedMember
     * @Date 30 Oct 2017
     * @Author RNF Technologies  
     */
    public function restoreDeletedMember($id=null) {
        if($id === null || !is_numeric($id)){
            $this->Flash->error(__("Error! No Record Found."));
            return $this->redirect(["action" => "deletedMember"]);
        }
        $session = $this->request->session()->read("User");
        $row = $this->PheramorUser->get($id);
        $row->is_deleted = 0;
        if ($this->PheramorUser->save($row)) {
            $this->Flash->success(__("Success! Restore Deleted Record Successfully."));
            return $this->redirect($this->referer());
        }
    }
     
   /**
     * Update Gallery on gender Change
     * @Method genderChange
     * @Date 06 March 2017
     * @Author RNF Technologies  
     */
    private function genderChange($id, $gender){
        
         $conn = ConnectionManager::get('default');
        $delete = $conn->execute("delete from pheramor_user_gallery where user_id='" . $id . "'");
        $update = $conn->execute("UPDATE pheramor_user_profile set image = '' WHERE user_id = '" . $id . "'");
        if ($gender == 1) {
            $mydatasave = array('Fav Hobby', 'Globe-trotting', 'Smiling', 'Straight Chilling', 'Suit & Tie', 'W: a Pet');
        } else {
             $mydatasave = array('Chilling', 'Fav Hobby', "Glam\'d Up", 'Selfie', 'Smiling', 'Traveling');
        }
        for ($l = 0; $l <= 5; $l++) {
            $insert = $conn->execute("insert into pheramor_user_gallery set category_name='" . $mydatasave[$l] . "' , name='', created_date='" . date("Y-m-d H:i:s") . "',  created_by='4', user_id='" . $id . "', gallery_type='image' ");
        }
    }  
    
    
    
    
    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
