<?php
include('../config.php');
session_start();
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
    $invoicemanmasid = $_GET['invoicemanmasid'];    
    $sql = "select * from invoice_man_mas where invoicemanmasid='$invoicemanmasid';";
    $result = mysql_query($sql);
    if($result !=null)
    {
        while($row = mysql_fetch_assoc($result))
        {
            $taxinvoiceno = $row['invoiceno'];            
            $toaddress = $row['toaddress'];
            $premise = $row['premise'];
            if(isset($_GET['dateoption']))                
                $pr = $_GET['dateoption'];
            else
                $pr ='1';
            if($pr=="0")
                $rentperiod =date('d-m-Y',strtotime($row['fromdate'])) ."  to  ".date('d-m-Y',strtotime($row['todate']));                
            else
                $rentperiod ='';
            $totalvalue = $row['totalvalue'];
            $totalvat  = $row['totalvat'];
            $rowtotal = $row['totalamount'];
            $remarks = $row['remarks'];
            $companymasid = $row['companymasid'];
            $sqldet ="select companyname from mas_company where companymasid ='$companymasid';";
            $resultdet = mysql_query($sqldet);
            if($resultdet !=null)
            {
                $rowdet = mysql_fetch_assoc($resultdet);
                $companyname=strtoupper($rowdet['companyname']);                
            }  
        }
    }
    $rentdetails='<table width="100%" cellpadding="3" border="1">
                <tr align="center">
                        <td bgcolor="#dddddd" width="10%">S.No</td>
                        <td bgcolor="#dddddd" width="30%">Description</td>
                        <td bgcolor="#dddddd" width="20%">Value</td>
                        <td bgcolor="#dddddd" width="20%">Vat</td>
                        <td bgcolor="#dddddd" width="20%">Amount</td>
                </tr>';
    $i=1;
    $sql = "select * from invoice_man_det where invoicemanmasid = $invoicemanmasid;";
    $result = mysql_query($sql);
    if($result !=null)
    {
        while($row = mysql_fetch_assoc($result))
        {            
            $invoicedesc="";
            $invoicedescmasid = $row['invoicedescmasid'];
            $sqldet ="select invoicedesc from invoice_desc where invoicedescmasid ='$invoicedescmasid';";
            $resultdet = mysql_query($sqldet);
            if($resultdet !=null)
            {
                $rowdet = mysql_fetch_assoc($resultdet);
                $invoicedesc=$rowdet['invoicedesc'];
            }     
            $rentdetails .=' <tr>
                            <td align="center">'.$i.'</td>				
                            <td>'.$invoicedesc.'</td>				
                            <td align="right">'.number_format($row['value'], 0, '.', ',').'</td>
                            <td align="right">'.number_format($row['vat'], 0, '.', ',').'</td>
                            <td align="right">'.number_format($row['amount'], 0, '.', ',').'</td>
                        </tr>';
            $i++;
        }
    }
    $rentdetails .=' <tr>
                            <td align="right" colspan="2">Grand Total</td>				
                            <td align="right">'.number_format($totalvalue, 0, '.', ',').'</td>
                            <td align="right">'.number_format($totalvat, 0, '.', ',').'</td>
                            <td align="right">'.number_format($rowtotal, 0, '.', ',').'</td>
                    </tr></table>';
    $address ='<table cellpadding="2" >		
		<tr height="70px">    
			<td width="35%">P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
			<td width="35%">Mega Plaza Block "B" 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
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
                    <td bgcolor="#dddddd" width="35%">Invoice #</td>
                    <td align="right">'.$taxinvoiceno.'</td>
                </tr>
                 <tr>
                    <td bgcolor="#dddddd" width="35%">Date</td>
                    <td align="right">'.date("d/m/Y", strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +  0 Months")).'</td>
                </tr>
                 <tr>
                    <td bgcolor="#dddddd" width="35%">Amount Due</td>
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
                        <tr>
                            <td width="25%" bgcolor="#dddddd">Period</td>
                            <td width="75%">'.$rentperiod.'</td>
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
                    1. All payments to be acknowledged by official receipts.<br><br>
                    2. Any disputes on this invoice should be lodged in writing within 7 days of the date hereof.<br><br>		
                    3. Interest will be charged on over due accounts, as provided in the agreement.<br><br>                
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
    $pdf->ln(10);
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
        $pdf->ln(2);		    
    }
    else
    {
        $pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');
        $pdf->ln(2);	
        $pdf->writeHTML('<b>'.$vatno.'</b>', true, false, true, false, '');
        $pdf->ln(2);	
    }
    $pdf->SetFont('dejavusans','BU',15);
    $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>INVOICE</th></tr></table>', true, false, true, false, '');	
    $pdf->ln(8);
    $pdf->SetFont('dejavusans','',9.5);
    $pdf->writeHTML($tenantdetails, true, false, true, false, '');
    $pdf->ln(5);
    $pdf->writeHTML($premisesdetails, true, false, true, false, '');
    $pdf->ln(5);
    $pdf->writeHTML($rentdetails, true, false, true, false, '');
    $pdf->ln(5);
    $pdf->writeHTML($remarks, true, false, true, false, '');
    $pdf->ln(5);
    $pdf->writeHTML($bottom, true, false, true, false, '');
    
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
    $companyname = $companyname."_".date('d-m-Y');    
    $pdf->Output($companyname, 'I');
    exit;
}
catch (Exception $err)
{
    $custom = array(
                'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>