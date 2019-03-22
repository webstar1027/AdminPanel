<?php
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
//echo "<pre>";
//print_r($users); die;
//die;

$i=1;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Member Name');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Member Email');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Product Name');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Product Price');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Discount Amount');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Paid Amount');
$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Payment Date');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Payment Status');

if (!empty($users)) {
     foreach ($users as $row) {
          $i++;
         $product_name=$this->Pheramor->get_subscription_names($row['product_id']);
         if ($row['payment_status'] == 1) {$paystaus='Paid';}else{$paystaus='Failed';}
          $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $row['first_name']." ".$row['last_name']);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $row['email']);
          $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $product_name[0]['subscription_title']);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, "$ ".$row['product_amount']);
          $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, "$ ".$row['discount_amount']);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, "$ ".$row['paid_amount']);
          $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, date('Y-m-d h:i:s A', strtotime($row['payment_date'])));
          $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $paystaus);
        
    }
}
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('User Data');
// Create a new worksheet, after the default sheet
//





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