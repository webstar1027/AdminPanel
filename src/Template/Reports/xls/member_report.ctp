<?php

//require_once(ROOT . DS .'vendor' . DS  . 'chart' . DS . 'GoogleCharts.class.php');
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("creator name");

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);
//HEADER
$i=1;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Name');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Phone');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Gender');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Credits');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'ZipCode');
$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Birth Date');

$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Race');
$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Religion');
$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Show Me');
$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Age Range');
$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Enable Notification');
$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Address');
$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'City');
$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'State');

$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'Neighborhood');
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'Profession');
$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'About Me');
$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Status Message');
$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Height');
$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, 'Weight');
$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, 'Body Type');
$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, 'Birth Pill');
$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'Bone Marrow Donor');


$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'Bone Marrow Donor');


$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, 'Pheramor Kit');
$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, 'Status');
$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, 'Registration Date');
$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, 'Movies');
$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, 'Music');
$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, 'Books');
$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, 'Games');
$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, 'Drinks');
$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, 'Foods');
$objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, 'Hobbies');
$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, 'Sports');
$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i, 'Hash Tags');
$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, 'Facebook Music');
$objPHPExcel->getActiveSheet()->setCellValue('AL'.$i, 'Facebook Movie');
$objPHPExcel->getActiveSheet()->setCellValue('AM'.$i, 'Facebook Game');
$objPHPExcel->getActiveSheet()->setCellValue('AN'.$i, 'Facebook Sport');
$objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, 'Facebook Book');
$objPHPExcel->getActiveSheet()->setCellValue('AP'.$i, 'Disable Account Reason');
//DATA
 //echo $this->Pheramor->getInterestMusicByUserID(13,'pheramor_music','title','music');
/*$i++;
$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $user->id);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user->name);
 * 
 */
//print_r($users); die;

//if u have a collection of users just loop
//DATA
foreach($users as $user){
    $i++;
    if($user['gender']==1){$gender='Male';}else{$gender='Female';}
     if($user['is_deleted']=='0'){
          if($user['activated']==1){$status='Active';}else{$status='Inactive';}
     }else{
         $status='Disable';
     }
    if($user['enable_notification']==1){$notification='Yes';}else{$notification='No';}
    if($user['birth_pill']=='1'){$birth_pill='Yes';}else{$birth_pill='No';}
    if($user['bone_marrow_donor']==1){$donor='Yes';}else{$donor='No';}
    if($user['disable_acc_reason']!=''){ $reason = $user['disable_acc_reason'];}else{$reason = ' --- ';}
    
    
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$user['first_name']." ".$user['last_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $user['phone']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $gender);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $this->Pheramor->totalCredits($user['user_id']));
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $this->Pheramor->getZipCodeByID($user['zipcode']));
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, date('Y-m-d',strtotime($user['dob'])));
    
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $this->Pheramor->getRaceName($user['race']));
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $this->Pheramor->getReligionName($user['religion']));
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $this->Pheramor->getShowMe($user['show_me']));
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, preg_replace('/[ ,]+/', ' - ', trim($user['age_range'])));
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $notification);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $user['address']);
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $user['city']);
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $user['state']);
    
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $user['neighborhood']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $user['profession']);
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $user['about_me']);
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $user['about_status']);
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $user['height'].' cm');
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $user['weight'].' lbs');
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $this->Pheramor->getBodytypeName($user['body_type']));
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, $birth_pill);
    $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $donor);
    
    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $this->Pheramor->getKitByUserID($user['user_id']));
    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $status);
    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$i,  date('Y-m-d',strtotime($user['created_date'])));
    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_movies','title','movie'));
    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_music','title','music'));
    $objPHPExcel->getActiveSheet()->setCellValue('AD'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_books','name','book'));
    $objPHPExcel->getActiveSheet()->setCellValue('AE'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_games','name','game'));
    $objPHPExcel->getActiveSheet()->setCellValue('AF'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_drinks','name','drink'));
    $objPHPExcel->getActiveSheet()->setCellValue('AG'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_food','title','food'));
    $objPHPExcel->getActiveSheet()->setCellValue('AH'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_hobbies','name','hobby'));
    $objPHPExcel->getActiveSheet()->setCellValue('AI'.$i,  $this->Pheramor->getInterestMusicByUserID($user['user_id'],'pheramor_sports','name','sport'));
    $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i,  $this->Pheramor->getInterestHashTagByUserID($user['user_id']));
    $objPHPExcel->getActiveSheet()->setCellValue('AK'.$i,  $this->Pheramor->getInterestFacebookByUserID($user['user_id'],'music'));
    $objPHPExcel->getActiveSheet()->setCellValue('AL'.$i,  $this->Pheramor->getInterestFacebookByUserID($user['user_id'],'movie'));
    $objPHPExcel->getActiveSheet()->setCellValue('AM'.$i,  $this->Pheramor->getInterestFacebookByUserID($user['user_id'],'game'));
    $objPHPExcel->getActiveSheet()->setCellValue('AN'.$i,  $this->Pheramor->getInterestFacebookByUserID($user['user_id'],'sport'));
    $objPHPExcel->getActiveSheet()->setCellValue('AO'.$i,  $this->Pheramor->getInterestFacebookByUserID($user['user_id'],'book'));
    $objPHPExcel->getActiveSheet()->setCellValue('AP'.$i, $reason);
   
}


// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('User Data');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//call the function in the controller with $output_type = F and $file with complete path to the file, to generate the file in the server for example attach to email
if (isset($output_type) && $output_type == 'F') {
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($file);
 } else {
    // Redirect output to a client's web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$file.'"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}
?>
