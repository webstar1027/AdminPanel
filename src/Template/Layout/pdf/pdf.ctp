<?php
// inclusion de la librairie TCPDF
 require_once ROOT . DS . 'vendor' . DS. 'tcpdf' . DS . 'tcpdf.php'; 
 if(isset($print_style)&&($print_style==1))
 {
    $PDF='P';
 }else{
    $PDF='P'; 
 }
 class MYPDF extends TCPDF {

    //Page header
   /* public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
    */
    
     public function Header() {
        // Logo
        $image_file =  K_PATH_IMAGES.'pheramore.png';; // *** Very IMP: make sure this image is available on given path on your server
        $this->Image($image_file,15,6,50);
        // Set font
        $this->SetFont('helvetica', 'B', 10);
    
        // Line break
        $this->Ln();        
        $this->Cell(323, 5, 'Report Invoice', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);        
        $this->Cell(322, 5, 'Pheramor Inc.', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);        
        $this->Cell(332, 5, 'www.pheramor.com', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        // We need to adjust the x and y positions of this text ... first two parameters
         $html = '<hr>';
         $this->SetY(25);
         $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
        
    }

    // Page footer
    public function Footer() {
        // Position at 25 mm from bottom
        
         $html = '<hr>';
         $this->SetY(-20);
        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        
        $this->Cell(0, 0, 'Product Company - Pheramor Inc, Phone : (866) 944-4607', 0, 0, 'C');
        $this->Ln();
        $this->Cell(0,0,'www.pheramor.com - T : (866) 944-4607 - E : info@pheramor.com', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
// Création d'un document TCPDF avec les variables par défaut
 $pdf = new MYPDF($PDF, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Pheramor');
$pdf->SetTitle($title);
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
define ('PDF_HEADER_STRINGS', " Pheramor Inc\nwww.pheramor.com");
// set default header data
$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRINGS);



// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 15, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
$html = $this->fetch('content');

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
 



/************* Water MArk Start Here ************/
 // Awal Water mark
$tipoLetra = "Helvetica";
$tamanoLetra = 35;
$estiloLetra = "B";
// Calcular ancho de la cadena
$widthCadena = $pdf->GetStringWidth(trim("Pheramor Inc."), $tipoLetra, $estiloLetra, $tamanoLetra, false );
$factorCentrado = round(($widthCadena * sin(deg2rad(45))) / 2 ,0);
// Get the page width/height
$myPageWidth = $pdf->getPageWidth();
$myPageHeight = $pdf->getPageHeight();
// Find the middle of the page and adjust.
$myX = ( $myPageWidth / 2 ) - $factorCentrado;
$myY = ( $myPageHeight / 2 ) + $factorCentrado;
// Set the transparency of the text to really light
$pdf->SetAlpha(0.09);
// Rotate 45 degrees and write the watermarking text
$pdf->StartTransform();
$pdf->Rotate(45, $myX, $myY);
$pdf->SetFont($tipoLetra, $estiloLetra, $tamanoLetra);
$pdf->Text($myX, $myY ,trim("Pheramor Inc."));
$pdf->StopTransform();
// Reset the transparency to default
$pdf->SetAlpha(1);

 /*************************************/


  //  $pdf->AddPage();
// voilà l'astuce, on récupère la vue HTML créée par CakePHP pour alimenter notre fichier PDF
  //  $pdf->writeHTML($this->fetch('content'), TRUE, FALSE, TRUE, FALSE, '');
// on ferme la page
    $pdf->lastPage();
    //$filename=1;
// On indique à TCPDF que le fichier doit être enregistré sur le serveur ($filename étant une variable que vous aurez pris soin de définir dans l'action de votre controller)
       if($ftype==1){
             $pdf->IncludeJS("print();");
             $pdf->Output(ROOT . DS  . 'webroot' . DS . 'pdf' . DS . $filename . '.pdf', 'I');
        }else if($ftype==2){
             //$pdf->IncludeJS("print();");
             $pdf->Output('invice_'.time().'.pdf', 'I');
        }else{
            $pdf->Output($filename . '.pdf', 'D');
        }      
           // 
             
            // 
	
  
?>
