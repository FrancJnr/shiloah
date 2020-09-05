<?php
ob_start(); 
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasidz=$_GET['licensemasid'];
$tenantmasidz=$_GET['licensemasid'];

try
{
$sql = "SELECT * FROM draft_document1 WHERE section='dailylease' AND grouptenantmasid=".$grouptenantmasidz." AND tenantmasid=".$tenantmasidz."  ORDER BY draftid DESC LIMIT 1";


$result1 = mysql_query($sql);
if($result1==true){
$rowprint = mysql_fetch_assoc($result1);

} else{
    
    echo mysql_error();
}      
   class MYPDF extends TCPDF {
	
	var $htmlHeader;
	var $htmlFooter;
	
	public function setHtmlHeader($htmlHeader) {
	    $this->htmlHeader = $htmlHeader;
	}

	public function setHtmlFooter($htmlFooter) {
	    $this->htmlFooter = $htmlFooter;
	}
	
        //Page header
       public function Header() {       
            $this->writeHTML($this->htmlHeader, true, false, true, false, '');
	    $this->writeHTML("<br><hr>", true, false, true, false, '');	    
       }
        // Page footer
       public function Footer() {           
           $this->SetFont('courier', 'I', 8);
	   $footer_text = $this->htmlFooter;	   
	
	   if ($this->print_footer && $this->page>1) {
		$this->writeHTMLCell(0, 0, 10, 280, "<hr>", 0, 0, 0, true, 'L', true);
		$this->writeHTMLCell(100, 0, 10, 282, $footer_text, 0, 0, 0, true, 'L', true);
	   }
	   
	   $this->Cell(0, 0, $this->getAliasRightShift().'Page '.$this->PageNo().'/'.$this->getAliasNbPages(), 0, 0, 'R');
       }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);

////set margins
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(15, 20, 15, true);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//$pdf->SetAutoPageBreak(TRUE, 0);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set default font subsetting mode
$pdf->setFontSubsetting(true);   

    $pdf->AddPage(); 
     $pa =$rowprint['pa'];
     $pb =$rowprint['pb'];	    
     $pc =$rowprint['pc'];
     //$pdf->SetFont('dejavusans','',9);
    //$p1=$rowprint['p1']; 
     //$pdf->MultiCell(0,10, $pb."\n", 0, 'J');
     //$pdf->ln(6);    
     //$pdf->SetFont('dejavusans','',8.8);

     //$p2=$rowprint['p2']; 
     //$pdf->writeHTMLCell(0, 0, '', '', $pb, 0, 1, false, true, 'J', false);
     //$pdf->ln(2);
    
    $pdf->SetFont('courier','',10);
    $pdf->writeHTML($pb, true, false, true, false, '');
    $pdf->ln(1);
    
    //$pdf->AddPage();
    //$pdf->ln(10);   
    $pdf->SetFont('courier','',10);
    //$pdf->SetXY(10, 263);
   // $p4a=$rowprint['p4a'];;
    $pdf->writeHTML($pc, true, false, true, false, '');
    
     $filename = $pa." - (Daily_License_No_".date('d-m-y h:m:s')."_".$tenantmasidz.")";
    
     $filename = clean($filename);
  
//    // Save It onto directory  
    $pdf->Output("../../pms_docs/dailylicense/".$filename.".pdf","F");
    
//    ////Insert into  rpt_offerletter
//    $createdby = $_SESSION['myusername'];
//    $createddatetime = $datetime;	
//    $insert = "insert into rpt_posession (offerlettermasid,grouptenantmasid,createdby,createddatetime) values ($offerlettermasid,$groupmasid,'$createdby','$createddatetime')";
//    mysql_query($insert);
//
//    ////Insert into document status
//    $insert_doc_status ="insert into trans_document_status(grouptenantmasid,createdby,createddatetime) values ('$groupmasid','$createdby','$createddatetime');";
//    mysql_query($insert_doc_status);
    
    ////to show with file name pdf
    // print_r($pdf);
    $pdf->Output($filename, 'I');
    //ob_end_clean();
    exit;    

}
catch (Exception $err)
{
	$custom = array(
		    'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
		    's'=>'Success');
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
}
?>
