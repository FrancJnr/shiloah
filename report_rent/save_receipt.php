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
    $tablemas ="invoice_rct_mas";
    $tabledet ="invoice_rct_det";
    $sqlmas="";$sqldet="";$sno="";$iid ="0";$toaddress="";$buildingmasid=0;$grouptenantmasid=0;$premise="";$crperiod="";$remarks="";$words="";
    $companymasid=0;$creditnoteno=0;$companyname="";$pinno=0;$vatno=0;$taxcreditnoteno="-";$rowtotal=0;
    $totalvalue=0;$totalvat=0;$totalamount=0;    
    foreach($_GET as $key=>$val) {
        $invoicedescmasid = substr($key,0,16);
        $sno = strstr($key, '_', true);
        $value = strstr($key, '_', true);
        $vat = strstr($key, '_', true);
        $amount = strstr($key, '_', true);
        if(($invoicedescmasid != "invoicedescmasid") and ($sno !="sno") and ($value !="value") and ($vat!="vat") and ($amount!="total"))
        {
            if($key == "companymasid"){
                $companymasid = $val;
                $sql = "select companyname from mas_company where companymasid = $companymasid";
                $result = mysql_query($sql);
                if($result !=null)
                {
                    $row = mysql_fetch_assoc($result);
                    $companyname = $row['companyname'];                       
                }
                $cols[] = "companymasid";
                $vals[] = $val;
            }else if($key == "rctno"){
                $creditnoteno = $val;
                $taxcreditnoteno = $creditnoteno."/".date('Y');                
                $val = mysql_real_escape_string($taxcreditnoteno);
                $sqlchkinv = "select rctno from invoice_rct_mas where rctno='$val' AND companymasid = $companymasid;";
                $resultchkinv = mysql_query($sqlchkinv);
                
                if($result != null)
                {
                    $cnt_chkinv = mysql_num_rows($resultchkinv);
                    if($cnt_chkinv >=1)
                    {                        
                        echo "<h2>Receipt raised Already. Duplicate of receipt no $val found. !!!";
                        exit;
                    }           
                }
                $cols[] = "rctno";
                $vals[] = "'".$val."'";
            }
            else if($key == "toaddress"){
                $toaddress = $val;
            }
            else if($key == "hid_grouptenantmasid"){
                $grouptenantmasid = $val;
                $cols[] = "grouptenantmasid";
                $vals[] = $val;
            }
            else if($key == "premise"){
                $premise = $val;
            }
            else if($key == "totalvalue"){
                $totalvalue = $val;
                $cols[] = "totalvalue";
                $vals[] = $val;
            }
            else if($key == "totalvat"){
                $totalvat = $val;
                $cols[] = "totalvat";
                $vals[] = $val;
            }
			else if($key == "chqnum"){
                $rcptchqnum = $val;
				$cols[] = "chqnum";
                $vals[] = $val;
            }
			else if($key == "paymentof"){
               $paymentof = $val;
				$cols[] = "rmks";
                $vals[] = "'".$paymentof."'";

            }
            else if($key == "totalamount"){
                $rowtotal = $val;
                $cols[] = "totalamount";
                $vals[] = $val;
            }               
            if(($key == "crdate"))
            {                    
                $cols[] = "rctdate";
                $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                $crperiod = date('d/m/Y', strtotime($val));
            }          
        }       
        if($key == "remarks")
        {
            $words = $val;
            $cols[] = "createdby";
            $vals[] ="'". $_SESSION['myusername']."'";
            $cols[] = "createddatetime";
            $vals[] = "'".$datetime."'";
            $sqlmas = 'INSERT INTO `'.$tablemas.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
            
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
                $iid = mysql_insert_id();
                $sqlupdate = "UPDATE invoice_no_rct SET invrctno = invrctno+1 where companymasid = $companymasid ";
                $resultch = mysql_query($sqlupdate);
            }
            
        }
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
    foreach($_GET as $key=>$val)
    {             
        
        $invoicedescmasid = substr($key,0,16);
        $invoiceno = substr($key,0,9);
        $sno = strstr($key, '_', true);
        $value = strstr($key, '_', true);
        $vat = strstr($key, '_', true);
        $amount = strstr($key, '_', true);        
        if($sno !="sno") 
        {                
            if($invoicedescmasid =="invoicedescmasid")
            {
                $cols[] = "`invoicedescmasid`";
                $vals[] = "'".$val."'";
                $desc="";
                $sql ="select invoicedesc from invoice_desc where invoicedescmasid=".$val;
                $result = mysql_query($sql);
                if($result != null)
                {
                    $row = mysql_fetch_assoc($result);
                    $desc = $row['invoicedesc'];
                }
              //  $rentdetails .='<tr>
                //                    <td width="8%" align="center" border="1">'.$j.'.</td>
                 //                   <td width="30%" border="1">'.$desc.'</td>';
                $rdcount++;
                $j++;
            }
            else if($invoiceno =="invoiceno")
            {
                $cols[] = "`invoiceno`";                
                $vals[] = "'".mysql_real_escape_string($val)."'";
              ///  $rentdetails .='<td width="15%" align="right" border="1">'.$val.'</td>';                                    
            }
            else if($value =="value")
            {
                $cols[] = "`value`";
                $vals[] = "'0'";
               // $rentdetails .='<td width="18%" align="right" border="1">'.number_format($val, 0, '.', ',').'</td></tr>';                                    
            }
            else if($vat =="vat"){
                $cols[] = "`vat`";
                $vals[] = "'0'";
              //  $rentdetails .='<td width="16%" align="right">'.number_format($val, 0, '.', ',').'</td>';                                    
            }
            else if($amount =="total"){
                $cols[] = "`total`";
                $vals[] = "'".$val."'";
            //  $rentdetails .='<td width="18%" align="right" border="1">'.number_format($val, 0, '.', ',').'</td></tr>';
                
                $cols[] = "`invoicerctmasid`";
                $vals[] = "'".$iid."'";
                $sqldet = 'INSERT INTO `'.$tabledet.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
               
                if($iid <=0)
                {                    
                    $custom = array('result'=> "Un expected error while inserting data in to $sqldet",'s'=>'error');
                    $response_array[] = $custom;
                    echo '{"error":'.json_encode($response_array).'}';
                    exit;
                }
                else
                {     
                    mysql_query($sqldet);
                   // mysql_query($sqldet);
                }     
            
            }            
        }
        else
        {
            $cols="";
            $vals="";
        }
    }   
 
   /// $rentdetails .=' <tr>
      ///                      <td align="right" colspan="3" border="1">Grand Total</td>				
      ///                      <td align="right" border="1">'.number_format($rowtotal, 0, '.', ',').'</td>
                           
         ///           </tr></table>';

    if($rowtotal =="")
    $rowtotal=0;
    
    $outstring="";$i=0;
    $toaddress = explode("," , $toaddress);
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
        if ($paymentof == ""){
    $tenantdetails ='<table width="100%" cellpadding="1px">		
   <tr><td width="100%"><i>RECEIVED from: </i> <u>'.$outstring.'</u></td></tr>
    <tr><td width="100%"> <i>The sum of shillings: </i> <u><b>'.convert_number_to_words($rowtotal).'</b></u></td></tr>   
	
        <tr><td width="100%"><br><br><i>Being payment of: </i>        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </td></tr> 
    </table>';
	} else{
		$tenantdetails ='<table width="100%" cellpadding="1px">		
   <tr><td width="100%"><i>RECEIVED from: </i> <u>'.$outstring.'</u></td></tr>
    <tr><td width="100%"> <i>The sum of shillings: </i> <u><b>'.convert_number_to_words($rowtotal).'</b></u></td></tr>   
	
        <tr><td width="100%"><br><br><i>Being payment of: </i>        <u>'. $paymentof .'</u></td></tr> 
    </table>';
	}
    
    
    $premisesdetails='<table width="100%" cellpadding="5" border="1">
                        <tr>
                            <td width="25%" bgcolor="#dddddd">Premises / Shop</td>
                            <td width="75%">'.$premise.'</td>
                        </tr>                                            
    </table>';
	if ($rcptchqnum >0){
 
	$remarks='<table width="100%" cellpadding="1px">
                <tr><td align="left" width="45%" >Shs.  <u><b>'.number_format($rowtotal).'  /=</b></u></td><td style="border-bottom:1px solid #000;height:25px"></td><td width="20%" height="30px" rowspan="3" style="border:1px dashed #000;border-radius: 2px;text-align:center"><br><br><br>stamp<br><br> </td> </tr> 
                <tr><td align="left" width="50%" ><b>Cheque No : '.$rcptchqnum.'</b></td><td width="30%" style="text-align: left"> for and behalf of </td></tr> 
                <tr><td width="45%"></td><td align="left"><b>'.strtoupper($companyname).'</b></td></tr>
                
    </table>';
	}
	else{
		$rcptchqnum="";
		    $remarks='<table width="100%" cellpadding="1px">
                 
                <tr><td align="left" width="45%" >Shs.  <u><b>'.number_format($rowtotal).'  /=</b></u></td><td style="border-bottom:1px solid #000;height:25px"></td><td width="20%" height="30px" rowspan="3" style="border:1px dashed #000;border-radius: 2px;text-align:center"><br><br><br>stamp<br><br> </td> </tr> 
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
                    <td align="right">'.$taxcreditnoteno.'</td>
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
                    <td align="right">'.$taxcreditnoteno.'</td>
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
    $companyname = $companyname."_".$creditnoteno;
    $pdf->Output("../../pms_docs/receipts/".$companyname.".pdf","F");
	//$pdf->Output("P:/receipts/".$companyname.".pdf","F"); // P is a Network drive to shiloah test 
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