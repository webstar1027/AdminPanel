<?php
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("creator name");
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
//echo "<pre>";
//print_r($users); die;
//die;
$checksheetcount=count($users);
$m=0;
$objPHPExcel->setActiveSheetIndex(0);
foreach($users as $user){
$i=1;

$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Name');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Check-in Date');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Time');

if (!empty($cafe_check_data)) {
     foreach ($cafe_check_data as $val) {
         if($val['cafe_id']==$user['id']){
             $user_data=$this->Pheramor->get_user_details($val['user_id']);
         $i++;
          $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $user_data['pheramor_user_profile'][0]['first_name']." ".$user_data['pheramor_user_profile'][0]['last_name']);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user_data['email']);
          $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('F j, Y', strtotime($val['check_in_time'])));
          $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, date('h:i:s A', strtotime($val['check_in_time'])));
         }
       
    }
}
// Rename sheet
$sheet_title=substr($user['title'],0,30);
$objPHPExcel->getActiveSheet()->setTitle($sheet_title);
$m++;
if($checksheetcount > $m)
{
$objPHPExcel->createSheet($m);
$objPHPExcel->setActiveSheetIndex($m);
}
// Create a new worksheet, after the default sheet
//
}




// Redirect output to a client’s web browser (Excel5)
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