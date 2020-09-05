<?php
include('../config.php');
session_start();
//include('ip_test.php');

//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {
//	    ////$k = preg_replace('/[^a-z]/i', '', $k); 
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$custom = array('result'=> $sqlArray ,'s'=>'error');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;
class MYPDF extends TCPDF {
        //Page header
        public function Header() {
            // Logo
            //$image_file = "../images/mpg1.png";
           // $this->Image($image_file, '', '', 39, 39, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // Set font
            //$this->SetFont('helvetica', 'B', 20);
            // Title
            //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }
             // Page footer
            public function Footer() {
                // Position at 15 mm from bottom
                $this->SetY(-15);
                // Set font
                $this->SetFont('dejavusans', 'I', 8);
                // Page number
                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }
        }

       
   // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage('P', 'A4');
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);	
        $pdf->SetAutoPageBreak(TRUE, 0);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        $pdf->SetMargins(PDF_MARGIN_LEFT, '', PDF_MARGIN_RIGHT);
        // set default footer margin
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);   

$company = strtoupper($_SESSION["mycompany"]);

try{
    
    $sqlmas="";$sqldet="";$sno="";$iid ="0";$edittoaddress="";$buildingmasid=0;$grouptenantmasid=0;$editpremise="";$crperiod="";$remarks="";$words="";
    $companymasid=0;$creditnoteno=0;$companyname="";$pinno=0;$vatno=0;$taxcreditnoteno="-";$rowtotal=0;
    $totalvalue=0;$totalvat=0;$totalamount=0; 
	$tablemas ="invoice_rct_mas";
    $tabledet ="invoice_rct_det";
    $recptno = $_GET['tenantrecpt'];   
    $editrcptamnt = $_GET['editrcptamnt'];   
    $where ="rctno = '$recptno'";
    $companymasid = $_GET['companyeditrcpt'];
    $grouptenantmasid = $_GET['tenanteditrcpt'];
	$editpayof =  $_GET['editpayof'];
	$accountedtrcpt =  $_GET['accountedtrcpt'];
	$editincvenum =  $_GET['editincvenum'];
	$edittoaddress = $_GET['edittoaddress'];
	$editpremise = $_GET['editpremise'];
	$editchqnum = $_GET['editchqnum'];
	//companyeditrcpt
	
		$sql = "select companyname from mas_company where companymasid = $companymasid";
                $result3 = mysql_query($sql);
                if($result3 !=null)
                {
                    $row = mysql_fetch_assoc($result3);
                    $companyname = $row['companyname'];                       
                }
     
           // $sqlmas = 'INSERT INTO `'.$tablemas.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
           $sql1 = mysql_query("Select grouptenantmasid FROM group_tenant_det WHERE tenantmasid = '$grouptenantmasid'");
		 $grouptenantmasid1 = 0;
		 while($row1=  mysql_fetch_assoc($sql1)){
			 $grouptenantmasid1 = $row1['grouptenantmasid'];
			 
		 }
		 //die($grouptenantmasid1);
		   $sqlmas = "UPDATE `invoice_rct_mas` SET companymasid = '$companymasid',grouptenantmasid = '$grouptenantmasid1',rmks = '$editpayof' WHERE $where";
  // $sqlmas  = 'INSERT INTO `'.$tablemas.'` (`'.implode('`, `', $cols.'`) VALUES ("'.implode('", "', $vals).'")';
				//die($sqlmas);
            $result = mysql_query($sqlmas);
            if($result == false)
            {                
                $custom = array('result'=> mysql_error(),'s'=>'error');
                $response_array[] = $custom;
                echo '{"error":'.json_encode($response_array).'}';
                exit;
            }
            else
            {
					$sql2 = mysql_query("Select invoicerctmasid FROM invoice_rct_mas WHERE $where");
		 //$grouptenantmasid1 = 0;
		 while($row2=  mysql_fetch_assoc($sql2)){
			 $invoicerctmasid = $row2['invoicerctmasid'];
			 $sqlupdate = mysql_query("UPDATE invoice_rct_det SET invoicedescmasid = '$accountedtrcpt' ,invoiceno = '$editincvenum' WHERE invoicerctmasid = '$invoicerctmasid' ");
            

		 }
			
                $custom = array('result'=> mysql_error(),'s'=>'error');
                $response_array[] = $custom;
                echo '{"Success":'.json_encode($response_array).'}';
               // exit;
            }
						
        
    
    $total = 
    $cols="";
    $vals="";$rdcount=0;
    $sqldet="";
    //$rentdetails='<table width="100%" cellpadding="3" >
     //               <tr align="center">
      //                      <td bgcolor="#dddddd" width="8%" border="1">S.No</td>
      //                      <td bgcolor="#dddddd" width="30%" border="1">Description</td>
//							<td bgcolor="#dddddd" width="15%" border="1">Invoice No</td>
      //                      <td bgcolor="#dddddd" width="18%" border="1">Value</td>
                            
       //                     <td width="20%" rowspan="5"><table height="30px" cellpadding="0"><tr><td ></td><td style="border:1px dashed #000;border-radius: 2px" width="80%"><br><br><br>Stamp<br><br></td></tr></table></td>
        //            </tr>';   
    $j=1;
   
 
   /// $rentdetails .=' <tr>
      ///                      <td align="right" colspan="3" border="1">Grand Total</td>				
      ///                      <td align="right" border="1">'.number_format($rowtotal, 0, '.', ',').'</td>
                           
         ///           </tr></table>';

    if($rowtotal =="")
    $rowtotal=0;
    
    $outstring="";$i=0;
    $toaddress = explode("," , $edittoaddress);
    foreach ($toaddress as $toaddress) {
        if($i==0)
            $outstring .="<b>".$toaddress . "</b><br>";
        else
            $outstring .= $toaddress . "<br>";
        $i++;
    }
       $address ='<table cellpadding="2" >		
		<tr>    
			<td width="35%">P.O.Box 2501-40100,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
			<td width="35%">Mega Plaza Block "B" 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
			<td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400 </td> 
		</tr>	
		</table>';
        if ($editpayof == ""){
    $tenantdetails ='<table width="100%" cellpadding="1px">		
   <tr><td width="50%"><i>RECEIVED from: </i> <u>'.$outstring.'</u></td></tr>
    <tr><td width="100%"> <i>The sum of shillings: </i> <u><b>'.convert_number_to_words($editrcptamnt).'</b></u></td></tr>   
	
        <tr><td width="100%"><br><br><i>Being payment of: </i>        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </td></tr> 
    </table>';
	} else{
		$tenantdetails ='<table width="100%" cellpadding="1px">		
   <tr><td width="100%"><i>RECEIVED from: </i> <u>'.$outstring.'</u></td></tr>
    <tr><td width="100%"> <i>The sum of shillings: </i> <u><b>'.convert_number_to_words($editrcptamnt).'</b></u></td></tr>   
	
        <tr><td width="100%"><br><br><i>Being payment of: </i><u>'. $editpayof .'</u></td></tr> 
    </table>';
	}
    
    
    $premisesdetails='<table width="100%" cellpadding="5" border="1">
                        <tr>
                            <td width="25%" bgcolor="#dddddd">Premises / Shop</td>
                            <td width="75%">'.$editpremise.'</td>
                        </tr>                                            
    </table>';   
	if ($editchqnum >0){
 
	$remarks='<table width="100%" cellpadding="1px">
                 companyname
                <tr><td align="left" width="45%" >Shs.  <u><b>'.number_format($editrcptamnt).'  /=</b></u></td><td style="border-bottom:1px solid #000;height:25px"></td><td width="20%" height="30px" rowspan="3" style="border:1px dashed #000;border-radius: 2px;text-align:center"><br><br><br>stamp<br><br> </td> </tr> 
                <tr><td align="left" width="50%" ><b>Cheque No : '.$editchqnum.'</b></td><td width="30%" style="text-align: left"> for and behalf of </td></tr> 
                <tr><td width="45%"></td><td align="left"><b>'.strtoupper($companyname).'</b></td></tr>
                
    </table>';
	}
	else{
		$editchqnum="";
		    $remarks='<table width="100%" cellpadding="1px">
                 
                <tr><td align="left" width="45%" >Shs.  <u><b>'.number_format($editrcptamnt).'  /=</b></u></td><td style="border-bottom:1px solid #000;height:25px"></td><td width="20%" height="30px" rowspan="3" style="border:1px dashed #000;border-radius: 2px;text-align:center"><br><br><br>stamp<br><br> </td> </tr> 
                <tr><td align="left" width="50%" ><b>Cash / Cheque No</b></td><td width="30%" style="text-align: left"> for and behalf of </td></tr> 
                <tr><td width="45%"></td><td align="left"><b>'.strtoupper($companyname).'</b></td></tr>
                
    </table>';
	}



	
    $bottom='';    
    //$pdf->AddPage();	
     $pdf->SetY(1);
   $pdf->SetFont('times');
    $pdf->writeHTML('<table style="line-height: 1.2px;letter-spacing:+0.100mm;margin-top:1px"><tr><th colspan="2" width="70%" style="width: 50%;font-stretch:80%;text-align:right;font-size:18px;font-weight:bold;">'.strtoupper($companyname).'.</th><th style="text-align:right;"><img src="megaicon.png"  width="42" height="42" ></th></tr><tr style="font-size:9px" >    
			<td width="35%" style="font-size:8px;" >P.O.Box 2501-40100,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
			<td width="35%">Mega Plaza Block "B" 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
			<td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400 </td> 
		</tr></table><hr>', true, false, true, false, '');
   // $pdf->SetFont('dejavusans','',9);
   // $pdf->writeHTML($address, true, false, true, false, '');
    //$pdf->SetFont('dejavusans','',9.5);
	$pdf->SetFont('dejavusans', '', 7.5, '', false);
    $pinno ="PIN NO: ";
   
    $sqlpin = "select pin,vatno from mas_company where companymasid=$companymasid;";
    $resultpin = mysql_query($sqlpin);
    if($resultpin !=null)
    {
        while($row = mysql_fetch_assoc($resultpin))
        {
            $pinno .=$row['pin'];
          
        }
    }
 
         $pdf->writeHTML('<table width="100%"><tr><td width="40%"><b>'.$pinno.'</b></td><td width="25%" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><b>RECEIPT</b></td><td align="right" width="40%">			
            <table cellpadding="5" border="1" style="font-weight:bold;">
                <tr>
                    <td bgcolor="#dddddd" width="35%">Receipt #</td>
                    <td align="right">'.$recptno.'</td>
                </tr>
                 <tr>
                    <td bgcolor="#dddddd" width="35%">Date</td>
                    <td align="right">'.date("d/m/Y", strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +  0 Months")).'</td>
                </tr>				
        </table>
        </td></tr></table>', true, false, true, false, ''); 
        //$pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');    

    //$pdf->SetFont('dejavusans','BU',13);
	//$pdf->SetFont('dejavusans', 'BU', 13, '', false);
	//$pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>RECEIPT</th></tr></table>', true, false, true, false, '');	
    $pdf->SetFont('dejavusans','',7.5);
    $pdf->writeHTML($tenantdetails, true, false, true, false, '');
    //$pdf->writeHTML($premisesdetails, true, false, true, false, '');

        
    ////$pdf->writeHTML($rentdetails, true, false, true, false, '');
    

    $pdf->writeHTML($remarks, true, false, true, false, '');
    $pdf->writeHTML($bottom, true, false, true, false, '');
    
    //$pdf->SetY(-25);
    
    $pdf->writeHTML("<hr>", true, false, true, false, '');
    if($companymasid =='1'){
    $pdf->writeHTML('<table width="100%" style="margin-bottom:0px">
            <tr><td width="40%"><img src="../images/mp_logo.jpg"  width="25" height="25"></td>
            <td width="45%"><img src="../images/mc_logo.jpg"  width="25" height="25">
            </td><td width="10%"><img src="../images/mm_logo.jpg"  width="25" height="25"></td></tr>
            
            </table>', true, false, true, false, ''); 
   
    }
	    /// PAGE 2
	
	//$pdf->AddPage();
   // $pdf->SetY(1);
	$pdf->SetFont('times');
    $pdf->writeHTML('<table style="line-height: 1.2px;letter-spacing:+0.100mm;margin-top:1px"><tr><br><br><br><br><br><br><br><br><br><br><br><br><br><th colspan="2" width="70%" style="width: 50%;font-stretch:80%;text-align:right;font-size:18px;font-weight:bold;">'.strtoupper($companyname).'.</th><th style="text-align:right;"><img src="megaicon.png"  width="42" height="42" ></th></tr><tr style="font-size:9px" >    
			<td width="35%" style="font-size:8px;" >P.O.Box 2501-40100,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
			<td width="35%">Mega Plaza Block "B" 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
			<td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400 </td> 
		</tr></table><hr>', true, false, true, false, '');
   // $pdf->SetFont('dejavusans','',9);
   // $pdf->writeHTML($address, true, false, true, false, '');
    //$pdf->SetFont('dejavusans','',9.5);
	$pdf->SetFont('dejavusans', '', 7.5, '', false);
	
	         $pdf->writeHTML('<table width="100%"><tr><td width="40%"><b>'.$pinno.'</b></td><td width="25%" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><b>RECEIPT</b></td><td align="right" width="40%">			
            <table cellpadding="5" border="1" style="font-weight:bold;">
                <tr>
                    <td bgcolor="#dddddd" width="35%">Receipt #</td>
                    <td align="right">'.$recptno.'</td>
                </tr>
                 <tr>
                    <td bgcolor="#dddddd" width="35%">Date</td>
                    <td align="right">'.date("d/m/Y", strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +  0 Months")).'</td>
                </tr>				
        </table>
        </td></tr></table>', true, false, true, false, ''); 
        //$pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');    

    //$pdf->SetFont('dejavusans','BU',13);
	//$pdf->SetFont('dejavusans', 'BU', 13, '', false);
	//$pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>RECEIPT</th></tr></table>', true, false, true, false, '');	
    $pdf->SetFont('dejavusans','',7.5);
    $pdf->writeHTML($tenantdetails, true, false, true, false, '');
    //$pdf->writeHTML($premisesdetails, true, false, true, false, '');

        
    ////$pdf->writeHTML($rentdetails, true, false, true, false, '');
    

    $pdf->writeHTML($remarks, true, false, true, false, '');
    $pdf->writeHTML($bottom, true, false, true, false, '');
    
    $pdf->SetY(-25);
    
    $pdf->writeHTML("<hr>", true, false, true, false, '');
	if($companymasid =='1'){
    $pdf->writeHTML('<table width="100%" style="margin-bottom:0px">
            <tr><td width="40%"><img src="../images/mp_logo.jpg"  width="25" height="25"></td>
            <td width="45%"><img src="../images/mc_logo.jpg"  width="25" height="25">
            </td><td width="10%"><img src="../images/mm_logo.jpg"  width="25" height="25"></td></tr>
            
            </table>', true, false, true, false, ''); 
   
    }
    /////$invcrno_query = setcreditnoteno($companymasid,$creditnoteno,$datetime);
    ////$custom = array('result'=> $sqlmas."<br>".$sqldet.$companyname.$invcrno_query,'s'=>'error');
    ////$response_array[] = $custom;
    ////echo '{"error":'.json_encode($response_array).'}';        
    
   /// setcreditnoteno($companymasid,$creditnoteno,$datetime);
    strstr($recptno, '/', true);
    $companyname = $companyname."_".strstr($recptno, '/', true)."_edited";
    
   // if (file_exists("../../pms_docs/receipts/".$companyname.".pdf")) unlink("../../pms_docs/receipts/".$companyname.".pdf");
    ob_clean();
    $pdf->Output("../../pms_docs/receipts/".$companyname.".pdf","F");
    //$pdf->Output($invfilepath.$companyname.".pdf","F");
    $pdf->Output($companyname, 'I');
    exit;    

}
catch (Exception $err)
{
    $custom = array('result'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),'s'=>'error');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
?>