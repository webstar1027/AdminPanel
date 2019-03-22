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

//HEADER
$i=1;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Member ID');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Name');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Mobile Number');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Location');
//$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Status');
//DATA
/*$i++;
$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $user->id);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user->name);
 * 
 */


//if u have a collection of users just loop
//DATA
foreach($users as $user){
    $i++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $user['member_id']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user['first_name']." ".$user['last_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $user['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $user['mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $this->Gym->get_user_name($user['associated_licensee']));
   // $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user['first_name']);
}


// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Group\'s Members List');

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
