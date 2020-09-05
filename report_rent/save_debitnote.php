<?php
include('../config.php');
session_start();
//include('ip_test.php');
//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {
//	    //$k = preg_replace('/[^a-z]/i', '', $k); 
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
    $tablemas ="invoice_dr_mas";
    $tabledet ="invoice_dr_det";
    $sqlmas="";$sqldet="";$sno="";$iid ="0";$toaddress="";$buildingmasid=0;$grouptenantmasid=0;$premise="";$rentperiod="";$remarks="";
    $companymasid=0;$debitnoteno=0;$companyname="";$pinno=0;$vatno=0;$taxinvoiceno="-";$rowtotal=0;
    $totalvalue=0;$totalvat=0;$totalamount=0;    
    $buildingname="";$buildingshortname="";
    $valuearray=array();
    $vatarray=array();
    $descarray=array();
    
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
            }else if($key == "debitnoteno"){
                $debitnoteno = $val;
                $taxinvoiceno = $debitnoteno."/".date('Y');                
                $val = mysql_real_escape_string($taxinvoiceno);
                $sqlchkinv = "select debitnoteno from invoice_dr_mas where debitnoteno='$val';";
                $resultchkinv = mysql_query($sqlchkinv);
                if($result != null)
                {
                    $cnt_chkinv = mysql_num_rows($resultchkinv);
                    if($cnt_chkinv >=1)
                    {                        
                        echo "<h2>Invoice raised Already. Duplicate of invoce no $val found. !!!";
                       // exit;
                    }           
                }
            }
            else if($key == "toaddress"){
                $toaddress = $val;
            }
            else if($key == "grouptenantmasid"){
                $grouptenantmasid = $val;
            }
            else if($key == "buildingmasid"){
                $buildingmasid = $val;
               // $buildingmasids = $val;
                $sqlbldname = "select buildingname, shortname from mas_building where buildingmasid=".$val;
                //die($sqlbldname);
                $resultchkbld = mysql_query($sqlbldname);
                if($resultchkbld != null)
                {
                    $cnt_chkbld = mysql_num_rows($resultchkbld);
                    if($cnt_chkbld >=1)
                    {                        
                    $row = mysql_fetch_assoc($resultchkbld);
                    $buildingname = $row['buildingname'];  
                    $buildingshortname = $row['shortname'];  
					
                    }           
                }
            }
            else if($key == "premise"){
                $premise = $val;
            }
             else if($key == "totalvalue"){
                $totalvalue = $val;
            }
            else if($key == "totalvat"){
                $totalvat = $val;
            }
            else if($key == "totalamount"){
                $rowtotal = $val;
            }   
            $cols[] = "".$key."";            
            if(($key == "fromdate") or ($key == "todate"))
            {                    
                $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                $rentperiod .= date('d/m/Y', strtotime($val))." - ";
            }
            else
            {                
                $vals[] = "'".$val."'";
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
    
    $rentdetails='<table width="100%" cellpadding="3" border="1">
                    <tr align="center">
                            <td bgcolor="#dddddd" width="10%">S.No</td>
                            <td bgcolor="#dddddd" width="30%">Description</td>
                            <td bgcolor="#dddddd" width="20%">Value</td>
                            <td bgcolor="#dddddd" width="20%">Vat</td>
                            <td bgcolor="#dddddd" width="20%">Amount</td>
                    </tr>';   
    $j=1;
    foreach($_GET as $key=>$val)
    {             
        $invoicedescmasid = substr($key,0,16);
        $sno = strstr($key, '_', true);
        $value = strstr($key, '_', true);
        $vat = strstr($key, '_', true);
        $amount = strstr($key, '_', true);        
        if($sno !="sno") 
        {                
            if($invoicedescmasid =="invoicedescmasid")
            {
                $cols[] = "invoicedescmasid";
                $vals[] = "'".$val."'";
                $desc="";
                $sql="select invoicedesc from invoice_desc where invoicedescmasid=".$val;
                $result = mysql_query($sql);
                if($result != null)
                {
                    $row = mysql_fetch_assoc($result);
                    $desc = $row['invoicedesc'];
                    array_push($descarray, $desc);
                }
                $rentdetails .='<tr>
                                    <td width="10%" align="center" >'.$j.'.</td>
                                    <td width="30%">'.$desc.'</td>';
                $j++;
            }
            else if($value =="value")
            {
                $cols[] = "value";
                $vals[] = "'".$val."'";
                $rentdetails .='<td width="20%" align="right">'.number_format($val, 0, '.', ',').'</td>';                                    
                array_push($valuearray, $val);
                
            }
            else if($vat =="vat"){
                $cols[] = "vat";
                $vals[] = "'".$val."'";
                $rentdetails .='<td width="20%" align="right">'.number_format($val, 0, '.', ',').'</td>';                                    
           
                 array_push($vatarray, $val);
                
                }
            else if($amount =="total"){
                $cols[] = "amount";
                $vals[] = "'".$val."'";
                $rentdetails .='<td width="20%" align="right">'.number_format($val, 0, '.', ',').'</td></tr>';
                
                $cols[] = "invoicedrmasid";
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
                    //////$custom = array('result'=> "Un expected error while inserting data in to $sqldet",'s'=>'error');
                    //////$response_array[] = $custom;
                    //////echo '{"error":'.json_encode($response_array).'}';
                    //////exit;
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
//START OF TALLY
    
$coname=strtoupper(ucwords($_SESSION["mycompany"]));
	//$coname="Katangi Developers Limited";
       //die("Esho: ".$grouptenantmasid);
/*     $sqltenantcode = "select tenantmasid from group_tenant_mas where grouptenantmasid=".$grouptenantmasid;
	$resultcode = mysql_query($sqltenantcode);
        //print_r($descarray);
        //die("slowly");
	if($resultcode !=null)
	{
	    while($row = mysql_fetch_assoc($resultcode))
	    {
		$tenantmasid=$row['tenantmasid'];
                $tenantcode=gettenantcode($tenantmasid);
	    }
	} */

$tenancyrefcode = gettenancyrefcode($grouptenantmasid);	
$debitac=strtoupper($tenancyrefcode);
$shortname=$buildingshortname;

$dateno = date('Y-m-d');
$datenow = str_replace('-', '', $dateno);
$dates=str_replace('-', 'at', date("j M Y - h:i", strtotime($dateno)));
$creditac="";
$crxml="";$valuetotal=0;$vatarrayfloat=0;
//$valuearrayfloat=0;$vatarrayfloat=0;
$topxml = '<?xml version="1.0"?>
<ENVELOPE>
 <HEADER>
 <TALLYREQUEST>Import Data</TALLYREQUEST>
 </HEADER>
 <BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>Vouchers</REPORTNAME>
    <STATICVARIABLES>
     <SVCURRENTCOMPANY>'.$coname.'</SVCURRENTCOMPANY>
    </STATICVARIABLES>
   </REQUESTDESC>
   <REQUESTDATA>
    <TALLYMESSAGE xmlns:UDF="TallyUDF">
     <VOUCHER VCHTYPE="Debit Note" ACTION="Create" OBJVIEW="Accounting Voucher View">
      <DATE>'.$datenow.'</DATE>
      <REFERENCEDATE>'.$datenow.'</REFERENCEDATE>
      <STATENAME/>
      <NARRATION>Debit Note # '.$taxinvoiceno.' from '.$rentperiod.' for '.$buildingname.'</NARRATION>
      <VOUCHERTYPENAME>Debit Note</VOUCHERTYPENAME>
      <REFERENCE>'.$taxinvoiceno.'</REFERENCE>
      <VOUCHERNUMBER>'.$taxinvoiceno.'</VOUCHERNUMBER>
      <BASICBASEPARTYNAME>'.$debitac.'</BASICBASEPARTYNAME>
      <CSTFORMISSUETYPE/>
      <CSTFORMRECVTYPE/>
      <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
      <BASICBUYERNAME>'.$debitac.'</BASICBUYERNAME>
      <BASICDATETIMEOFINVOICE>'.$dates.'</BASICDATETIMEOFINVOICE>
      <BASICDATETIMEOFREMOVAL>'.$dates.'</BASICDATETIMEOFREMOVAL>
      <VCHGSTCLASS/>
      <DIFFACTUALQTY>No</DIFFACTUALQTY>
      <ASORIGINAL>No</ASORIGINAL>
      <FORJOBCOSTING>No</FORJOBCOSTING>
      <ISOPTIONAL>No</ISOPTIONAL>
      <EFFECTIVEDATE>'.$datenow.'</EFFECTIVEDATE>
      <USEFOREXCISE>No</USEFOREXCISE>
      <USEFORINTEREST>No</USEFORINTEREST>
      <USEFORGAINLOSS>No</USEFORGAINLOSS>
      <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
      <USEFORCOMPOUND>No</USEFORCOMPOUND>
      <USEFORSERVICETAX>No</USEFORSERVICETAX>
      <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
      <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
      <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
      <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
      <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
      <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
      <ISCANCELLED>No</ISCANCELLED>
      <HASCASHFLOW>No</HASCASHFLOW>
      <ISPOSTDATED>No</ISPOSTDATED>
      <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
      <ISINVOICE>No</ISINVOICE>
      <MFGJOURNAL>No</MFGJOURNAL>
      <HASDISCOUNTS>No</HASDISCOUNTS>
      <ASPAYSLIP>No</ASPAYSLIP>
      <ISCOSTCENTRE>No</ISCOSTCENTRE>
      <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
      <ISVOID>No</ISVOID>
      <ISONHOLD>No</ISONHOLD>
      <ORDERLINESTATUS>No</ORDERLINESTATUS>
      <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
      <ISDELETED>No</ISDELETED>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>
      <SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
      <INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>
      <INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>
      <INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>
      <ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>
      <ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>
      <INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>';

//die();
for ($i=0; $i<count($descarray); $i++){
if($descarray[$i]=="Interest Charges"){
    $creditac="Interest Charges - Income ".$shortname;
  }else if($descarray[$i]=="Actual Service Charge") {
    $creditac="Actual Service Charge-".$shortname;
  }else if($descarray[$i]=="Legal Fees") {
     $creditac="Legal Charges-".$shortname;
  }else if($descarray[$i]=="Stamp Duty") {
    $creditac="Stamp Duty Regn Fee-".$shortname;
  }else if($descarray[$i]=="Direct Electricity Consumption") {
    $creditac="Electricity Charges Income-".$shortname;
  }else if($descarray[$i]=="Registration Fees") {
    $creditac="Stamp Duty Regn Fee-".$shortname;
  }else if($descarray[$i]=="Service Charge Deposit") {
    $creditac="Service Charge Deposit-".$shortname;
  }else if($descarray[$i]=="Rent") {
      $creditac="Rentals - ".$shortname; 
  }else if($descarray[$i]=="Other Sales") {
   $creditac="Other Sales";   
  }else if($descarray[$i]=="Direct Water Consumption") {
       $creditac="Water Charges Income-".$shortname;
  }else if($descarray[$i]=="Security Deposit") {
       $creditac="SD-".$debitac;
  }else if($descarray[$i]=="Misc Charges") {
       $creditac="Misc.Charges";
  }else if($descarray[$i]=="Other Sales (VAT Exempted)") {
   $creditac="Other Sales";      
  }
  
  $valuetotal+=floatval($valuearray[$i])+floatval($vatarray[$i]);
  $valuearrayfloat=floatval($valuearray[$i]);
  $vatarrayfloat+=floatval($vatarray[$i]);
 
 $crxml.='<ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>'.$creditac.'</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>'.$valuearrayfloat.'</AMOUNT>
       <VATEXPAMOUNT>'.$valuearrayfloat.'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      </ALLLEDGERENTRIES.LIST>';
     

}

  $drxml='<ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>'.$debitac.'</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>-'.$valuetotal.'</AMOUNT>
       <VATEXPAMOUNT>-'.$valuetotal.'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>
        <BILLTYPE>On Account</BILLTYPE>
        <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
        <AMOUNT>-'.$valuetotal.'</AMOUNT>
        <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
        <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      </ALLLEDGERENTRIES.LIST>';
	   
	 $vatxml='<ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>Vat on Sales</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>'.$vatarrayfloat.'</AMOUNT>
       <VATEXPAMOUNT>'.$vatarrayfloat.'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      </ALLLEDGERENTRIES.LIST>';
 
 $endxml='<VCHLEDTOTALTREE.LIST>      </VCHLEDTOTALTREE.LIST>
      <PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
      <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
     </VOUCHER>
    </TALLYMESSAGE>
   </REQUESTDATA>
  </IMPORTDATA>
 </BODY>
</ENVELOPE>';
    
 $allXML=$topxml.$drxml.$crxml.$vatxml.$endxml;

//$txt= '<textarea rows="20" cols="40" style="border:none;">'.$allXML.'</textarea>';
// die($txt);
  /*Actual code for importing to Tally goes here*/
 
	 	$server = '192.168.0.8:9000';
		$headers = array( "Content-type: text/xml" ,"Content-length: ".strlen($allXML) ,"Connection: close" );
		//$headers1 = array( "Content-type: text/xml" ,"Content-length: ".strlen($ascVATXML) ,"Connection: close" );
              
		$nodes = array($server, $server);
                $node_count = count($nodes);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $server);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $allXML);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               		
                $response_1 = curl_exec($ch);
                
                // Close request to clear up some resources
                curl_close($ch);

               echo  "DBNTXN: $response_1 <br>................................................................</br>"; 

//End of Tally POST
    
    
    
    
    
    
    $rentdetails .=' <tr>
                            <td align="right" colspan="2">Grand Total</td>				
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
                    <td bgcolor="#dddddd" width="35%">Debit Note #</td>
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
    $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>DEBIT NOTE</th></tr></table>', true, false, true, false, '');	
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
    setdebitnoteno($companymasid,$debitnoteno,$datetime);
    $companyname = $companyname."_".$debitnoteno;
    $pdf->Output("../../pms_docs/debitnote/".$companyname.".pdf","F");
    if($companymasid !=3)
    //////$pdf->Output($invfilepath.$companyname.".pdf","F");
    $pdf->Output($companyname, 'I');
    exit;
        
    //$custom = array('result'=> $sqlmas."<br>".$sqldet.$companyname,'s'=>'error');
    //$response_array[] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
    //exit;
}
catch (Exception $err)
{
    $custom = array('result'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),'s'=>'error');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
?>