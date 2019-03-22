<?php
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("creator name");
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
//echo "<pre>";
//print_r($users); die;
//die;

$i=1;
$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Name');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Refund Amount');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Date');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Refund Type');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Status');

foreach($users as $val){
           $i++;
           $user_data=$this->Pheramor->get_user_details($val['user_id']);
           if ($val['refund_status'] == 0) {
                $plan_status = "Failed";
            } else if ($val['refund_status'] == 1) {
                $plan_status = "Confirm";
            }
           $amount=number_format($val['refund_amount'],2);
          $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $user_data['pheramor_user_profile'][0]['first_name']." ".$user_data['pheramor_user_profile'][0]['last_name']);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user_data['email']);
          $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, '$'.$amount);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, date($this->Pheramor->getSettings("date_format"),strtotime($val['refund_date'])));
          $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $val['refund_type']);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $plan_status);
         
       
    
}
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('User Data');
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