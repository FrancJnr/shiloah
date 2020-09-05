<?php
ob_start(); 
include('../config.php');
//require_once '../PHPWord.php';
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasidz=$_GET['grouptenantmasid'];
$tenantmasidz=$_GET['tenantmasid'];

try
{
$sql = "SELECT * FROM draft_document1 WHERE section='possessionletter' AND grouptenantmasid=".$grouptenantmasidz." AND tenantmasid=".$tenantmasidz."  ORDER BY draftid DESC LIMIT 1";
$sql2="SELECT * FROM draft_document2 WHERE section='possessionletter' AND grouptenantmasid=".$grouptenantmasidz." AND tenantmasid=".$tenantmasidz."  ORDER BY draftid DESC LIMIT 1";

$result1 = mysql_query($sql);
$result2 = mysql_query($sql2);
//$rowprint=[];
//$row2print=[];
if($result1==true){
$rowprint = mysql_fetch_assoc($result1);

} else{
    
    echo mysql_error();
}      
if($result2==true){
$row2print = mysql_fetch_assoc($result2);

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
    $p0 =$rowprint['p0'];
  	    
    $pdf->SetFont('courier','B',12);
    $pa=$rowprint['pa'];
    $pdf->writeHTML($pa, true, false, true, false, '');
//    $pdf->ln(0.2);    
    $pdf->SetFont('courier','',8.5);
    $pb=$rowprint['pb'];;
    $pdf->writeHTML($pb, true, false, true, false, '');
//    $pdf->ln(7);    
    $pdf->SetFont('courier','',9.5);
    $pc=$rowprint['pc'];
    $pdf->writeHTML($pc, true, false, true, false, '');
//    $pdf->ln();
    $pdf->SetFont('courier','',9.5);
    $pd=$rowprint['pd'];;
    $pdf->writeHTML($pd, true, false, true, false, '');
//    $pdf->ln();
    $pdf->SetFont('courier','',9.5);
    $pdf->writeHTML($p0, true, false, true, false, '');
    $pdf->ln();
    $pdf->SetFont('courier','',10);
    $pdf->SetXY(10, 263);
   // $p4a=$rowprint['p4a'];;
    //$pdf->writeHTML($p4a, true, false, true, false, '');
    
     $filename = $rowprint['p4a']." - (".$rowprint['p4'].")";
    
     $filename = clean($filename);
  
//    // Save It onto directory  
    $pdf->Output("../../pms_docs/possessionletters/".$filename.".pdf","F");
    
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
