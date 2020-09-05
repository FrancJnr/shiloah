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
            $image_file = "../images/mpg1.png";
            $this->Image($image_file, '', '', 39, 39, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
    $tablemas ="invoice_cr_mas";
    $tabledet ="invoice_cr_det";
    $sqlmas="";$sqldet="";$sno="";$iid ="0";$toaddress="";$buildingmasid=0;$grouptenantmasid=0;$premise="";$crperiod="";$remarks="";
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
            }else if($key == "creditnoteno"){
                $creditnoteno = $val;
                $taxcreditnoteno = $creditnoteno."/".date('Y');                
                $val = mysql_real_escape_string($taxcreditnoteno);
                $sqlchkinv = "select creditnoteno from invoice_cr_mas where creditnoteno='$val';";
                $resultchkinv = mysql_query($sqlchkinv);
                if($result != null)
                {
                    $cnt_chkinv = mysql_num_rows($resultchkinv);
                    if($cnt_chkinv >=1)
                    {                        
                        echo "<h2>Invoice raised Already. Duplicate of invoce no $val found. !!!";
                        exit;
                    }           
                }
                $cols[] = "creditnoteno";
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
            else if($key == "totalamount"){
                $rowtotal = $val;
                $cols[] = "totalamount";
                $vals[] = $val;
            }               
            if(($key == "crdate"))
            {                    
                $cols[] = "crdate";
                $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                $crperiod = date('d/m/Y', strtotime($val));
            }          
        }       
        if($key == "remarks")
        {
            $remarks = $val;
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
            }

        }
    }
    $total = 
    $cols="";
    $vals="";
    $sqldet="";$rdcount=0;
    $rentdetails='<table width="100%" cellpadding="3" border="1">
                    <tr align="center">
                            <td bgcolor="#dddddd" width="8%">S.No</td>
                            <td bgcolor="#dddddd" width="25%">Description</td>
                            <td bgcolor="#dddddd" width="15%">Invoice No</td>
                            <td bgcolor="#dddddd" width="18%">Value</td>
                            <td bgcolor="#dddddd" width="16%">Vat</td>
                            <td bgcolor="#dddddd" width="18%">Amount</td>
                    </tr>';   
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
                $rentdetails .='<tr>
                                    <td width="8%" align="center" >'.$j.'.</td>
                                    <td width="25%">'.$desc.'</td>';
                $rdcount++;
                $j++;
            }
            else if($invoiceno =="invoiceno")
            {
                $cols[] = "`invoiceno`";                
                $vals[] = "'".mysql_real_escape_string($val)."'";
                $rentdetails .='<td width="15%" align="right">'.$val.'</td>';                                    
            }
            else if($value =="value")
            {
                $cols[] = "`value`";
                $vals[] = "'".$val."'";
                $rentdetails .='<td width="18%" align="right">'.number_format($val, 0, '.', ',').'</td>';                                    
            }
            else if($vat =="vat"){
                $cols[] = "`vat`";
                $vals[] = "'".$val."'";
                $rentdetails .='<td width="16%" align="right">'.number_format($val, 0, '.', ',').'</td>';                                    
            }
            else if($amount =="total"){
                $cols[] = "`total`";
                $vals[] = "'".$val."'";
                $rentdetails .='<td width="18%" align="right">'.number_format($val, 0, '.', ',').'</td></tr>';
                
                $cols[] = "`invoicecrmasid`";
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
                }                
            }            
        }
        else
        {
            $cols="";
            $vals="";
        }
    }   
    $rentdetails .=' <tr>
                            <td align="right" colspan="3">Grand Total</td>				
                            <td align="right">'.number_format($totalvalue, 0, '.', ',').'</td>
                            <td align="right">'.number_format($totalvat, 0, '.', ',').'</td>
                            <td align="right">'.number_format($rowtotal, 0, '.', ',').'</td>
                    </tr></table>';
    $address ='<table cellpadding="2" >		
		<tr height="70px">    
			<td width="35%">P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
			<td width="35%">Mega Plaza Block "A" 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
			<td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
		</tr>	
		</table>';
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
    
    $tenantdetails ='<table width="100%">		
    <tr>
        <td valign="top">TO:<br>'.
                        $outstring.'
        </td>
        <td align="right">			
            <table cellpadding="5" border="1" style="font-weight:bold;">
                <tr>
                    <td bgcolor="#dddddd" width="35%">Credit Note #</td>
                    <td align="right">'.$taxcreditnoteno.'</td>
                </tr>
                 <tr>
                    <td bgcolor="#dddddd" width="35%">Date</td>
                    <td align="right">'.date("d/m/Y", strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +  0 Months")).'</td>
                </tr>
                 <tr>
                    <td bgcolor="#dddddd" width="35%">Amount</td>
                    <td align="right">KSHS '.number_format($rowtotal, 0, '.', ',').'/-</td>
                </tr>
        </table>
        </td>
    </tr>
    </table>';
    
    
    $premisesdetails='<table width="100%" cellpadding="5" border="1">
                        <tr>
                            <td width="25%" bgcolor="#dddddd">Premises / Shop</td>
                            <td width="75%">'.$premise.'</td>
                        </tr>                                            
    </table>';   
    $remarks='<table cellpadding="5" border="1" width="100%">
                <tr height="30px">
                    <td colspan="5" valign="top">REMARKS IF ANY:<br>'.$remarks.'</td>                                                
                </tr>                 
    </table>';    
    $bottom='<table cellpadding="5" border="1" width="100%">
                <tr height="30px">
                    <td colspan="5" valign="top">Terms:</td>                                                
                </tr>
                 <tr height="60px">
                    <td colspan="5" valign="top" align="justify">
                    <br><br>
                    1. Any disputes on this credit note should be lodged in writing within 7 days of the date hereof.<br><br>		
                    2. This credit note is not valid unless it is signed by the director of the company.<br><br>                
                    </td>                                                
                </tr>
    </table>';    
    $pdf->AddPage();	
    $pdf->ln(7);
    $pdf->SetFont('dejavusans','B',22);
    $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>'.strtoupper($companyname).'.</th></tr></table><hr>', true, false, true, false, '');
    $pdf->ln(0.1);
    $pdf->SetFont('dejavusans','',9);
    $pdf->writeHTML($address, true, false, true, false, '');
    $pdf->ln(11);
    $pdf->SetFont('dejavusans','',9.5);
    $pinno ="PIN NO: ";
    $vatno="VAT NO: ";
    $sqlpin = "select pin,vatno from mas_company where companymasid=$companymasid;";
    $resultpin = mysql_query($sqlpin);
    if($resultpin !=null)
    {
        while($row = mysql_fetch_assoc($resultpin))
        {
            $pinno .=$row['pin'];
            $vatno .=$row['vatno'];
        }
    }
    if($companymasid =='3')
    {
        
        $pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');
        $pdf->ln(4);		    
    }
    else
    {
        $pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');
        $pdf->ln(2);	
        $pdf->writeHTML('<b>'.$vatno.'</b>', true, false, true, false, '');
        $pdf->ln(2);	
    }
    $pdf->SetFont('dejavusans','BU',15);
    $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>CREDIT NOTE</th></tr></table>', true, false, true, false, '');	
    $pdf->ln(8);
    $pdf->SetFont('dejavusans','',9.5);
    $pdf->writeHTML($tenantdetails, true, false, true, false, '');
    $pdf->ln(5);
    //$pdf->writeHTML($premisesdetails, true, false, true, false, '');
    if($rdcount <=2)
        $pdf->ln(5);
    else
        $pdf->ln(1);
    $pdf->writeHTML($rentdetails, true, false, true, false, '');
    if($rdcount <=2)
        $pdf->ln(5);
    else
        $pdf->ln(1);
    $pdf->writeHTML($remarks, true, false, true, false, '');
    $pdf->ln(5);
    $pdf->writeHTML($bottom, true, false, true, false, '');
    
    // FOR SIGN
    if($rdcount <=2)
        $pdf->ln(10);
    else
        $pdf->ln(6);
    $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="right"><th>FOR '.strtoupper($companyname).'</th></tr></table>', true, false, true, false, '');	
    $pdf->ln(10);
    $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="right"><th>____________________________</th></tr><tr align="right"><th>DIRECTOR</th></tr></table>', true, false, true, false, '');	
    
    $pdf->SetXY(10, 270);
    $pdf->writeHTML("<hr>", true, false, true, false, '');    
    if($companymasid =='1')//shiloah
    {
            $pdf->SetXY(15, 272);
            $pdf->Image('../images/mp_logo.jpg', '', '', 14, 14, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetXY(95, 272);
            $pdf->Image('../images/mc_logo.jpg', '', '', 14, 14, '', '', '', false, 200, '', false, false, 0, false, false, false);
            $pdf->SetXY(180, 272);
            $pdf->Image('../images/mm_logo.jpg', '', '', 14, 14, '', '', '', false, 300, '', false, false, 0, false, false, false);
            
    }
        
    /////$invcrno_query = setcreditnoteno($companymasid,$creditnoteno,$datetime);
    ////$custom = array('result'=> $sqlmas."<br>".$sqldet.$companyname,'s'=>'error');
    ////$response_array[] = $custom;
    ////echo '{"error":'.json_encode($response_array).'}';
    ////exit;
    
    setcreditnoteno($companymasid,$creditnoteno,$datetime);
    $companyname = $companyname."_".$creditnoteno;
    $pdf->Output("../../pms_docs/manualcreditnote/".$companyname.".pdf","F");
    if($companymasid !=3)
    $pdf->Output($companyname.".pdf","F");
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