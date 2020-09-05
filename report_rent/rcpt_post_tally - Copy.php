  <?php
include('../config.php');
//include('../MasterRef_Folder.php');
session_start();  

if(isset($_REQUEST))
{
	
 $companyname=strtoupper(ucwords($_SESSION["mycompany"]));
 $phptimedate = date("Y-m-d H:i:s"); //today's date and time
 $user=ucwords($_SESSION["myusername"]);
 $billtype='Agst Ref';
//print_r($_POST['isselected']);die();
	
	
	if(!empty($_POST['isselected'])){
		 $res=''; $lineerror=''; $response_array = array();
$tallyfb="";

 $lineerror="";

		for($s=0;$s<count($_POST['tenantcode'])+1;$s++){
// Loop to store and display values of individual checked checkbox.

	if (in_array($s, $_POST['isselected'])) {
				
		 $date =  $_POST['rctdate'][$s-1];
		 $invoiceno=$_POST['invoiceno'][$s-1];
		 //echo $date.'___'.$_POST['rctdate'];
		 $dates = str_replace('-', '', date('Y-m-d'));

         /* $date = str_replace('-', '', date('Y-m-d', strtotime($rctdate[$s])));  */  
		 $date = str_replace('-', '', date('Y-m-d', strtotime($date))); 
		// echo $date;
		 $datem=str_replace('-', 'at', date("j M Y - h:i", strtotime($date)));
		 $vnumber =json_encode($_POST['rctno'][$s-1], JSON_UNESCAPED_SLASHES);
		 //Check if cash or cheque receipt
		// if($post['chqnum'][$s-1] == 0){ // IF CASH RECEIPT
		//	 $newText = strstr($vnumber, '/', true);
		//	 $narration = 'Rcpt.'.$newText;
		//	 }
			// else{ // IF CHEQUE RECEIPT
				 $newText = strstr($vnumber, '/', true);
			 $narration = 'Rcpt.'.$newText.' Chqno: '.$_POST['chqnum'][$s-1];
			// }
		 
		 $vname = 'Receipt';
		 //$vnumber = $row['rctno'];
		  
		  $amount = $_POST['totalamount'][$s-1];
		  $tenant =  $_POST['tenantcode'][$s-1];// $_POST['tradingname'][$s];
		 // $tenant=gettenancyrefcode($tenantinfo);//$tenantcode;
		  //echo "TENANT: ".$_POST['tradingname'][$s]."|____|";
		  //$res.= "\n".$_POST['tradingname'][$s]." ";
		  $ledgername=$_POST['bankid'];
		  $tenantname=$_POST['tradingname'][$s-1];
		  $tenantname=trim(str_replace( "&"," &amp; ",$tenantname));
		  $ledgername=trim(str_replace( "&"," &amp; ",$ledgername));
$res_str = "<?xml version='1.0'?>
 <ENVELOPE>
 <HEADER>
  <TALLYREQUEST>Import Data</TALLYREQUEST>
 </HEADER>
 <BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>Vouchers</REPORTNAME>
    <STATICVARIABLES>
     <SVCURRENTCOMPANY>{$companyname}</SVCURRENTCOMPANY>
    </STATICVARIABLES>
   </REQUESTDESC>
   <REQUESTDATA>
    <TALLYMESSAGE xmlns:UDF='TallyUDF'>
     <VOUCHER VCHTYPE='Receipt' ACTION='Create' OBJVIEW='Accounting Voucher View'>
      <DATE>{$date}</DATE>
      <STATENAME/>
      <NARRATION>{$narration}</NARRATION>
      <VOUCHERTYPENAME>Receipt</VOUCHERTYPENAME>
      <REFERENCE>{$vnumber}</REFERENCE>
      <VOUCHERNUMBER>{$vnumber}</VOUCHERNUMBER>
      <BASICBASEPARTYNAME>{$tenant}</BASICBASEPARTYNAME>
      <CSTFORMISSUETYPE/>
      <CSTFORMRECVTYPE/>
      <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
      <BASICBUYERNAME>{$tenant}</BASICBUYERNAME>
      <BASICDATETIMEOFINVOICE>{$datem}</BASICDATETIMEOFINVOICE>
      <BASICDATETIMEOFREMOVAL>{$datem}</BASICDATETIMEOFREMOVAL>
      <VCHGSTCLASS/>
      <DIFFACTUALQTY>No</DIFFACTUALQTY>
      <ASORIGINAL>No</ASORIGINAL>
      <FORJOBCOSTING>No</FORJOBCOSTING>
      <ISOPTIONAL>No</ISOPTIONAL>
      <EFFECTIVEDATE>{$date}</EFFECTIVEDATE>
	  <ENTEREDBY>{$user}</ENTEREDBY>  
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
      <INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>
      <ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>{$tenant}</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>{$amount}</AMOUNT>
       <VATEXPAMOUNT>{$amount}</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>
        <NAME>{$invoiceno}</NAME>
        <BILLCREDITPERIOD JD='42705' P='1 Days'>1 Days</BILLCREDITPERIOD>
        <BILLTYPE>{$billtype}</BILLTYPE>
        <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
        <AMOUNT>{$amount}</AMOUNT>
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
      </ALLLEDGERENTRIES.LIST>
      <ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>{$ledgername}</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>-{$amount}</AMOUNT>
       <VATEXPAMOUNT>-{$amount}</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
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
      </ALLLEDGERENTRIES.LIST>
   
      <VCHLEDTOTALTREE.LIST>      </VCHLEDTOTALTREE.LIST>
      <PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
      <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
     </VOUCHER>
    </TALLYMESSAGE>
   </REQUESTDATA>
  </IMPORTDATA>
 </BODY>
</ENVELOPE>";

$server = '192.168.0.8:9000';
		$headers = array( "Content-type: text/xml" ,"Content-length: ".strlen($res_str) ,"Connection: close" );
		//$headers1 = array( "Content-type: text/xml" ,"Content-length: ".strlen($ascVATXML) ,"Connection: close" );
              
		$nodes = array($server, $server);
                $node_count = count($nodes);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $server);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $res_str);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               		
                $response_1 = curl_exec($ch);
				curl_close($ch);
				$xml = simplexml_load_string( $response_1);
                                $json = json_encode($xml);
                               
                                $arr = json_decode($json,true);
                               if(isset($arr['LINEERROR'])){
                                 $lineerror = $arr['LINEERROR'];  
                                  $res .=  "\nTally: FAILED:  ".$lineerror; 
                               }else if(($arr['CREATED']==1) && ($arr['ERRORS']==0) && 
                                       ($arr['CANCELLED']==0) && ($arr['IGNORED']==0)){
         			             $res .=$arr['CREATED']."_".$arr['ERRORS']."_".$arr['CANCELLED']."_".$arr['IGNORED'];	
				//ERRORS		
				
$sqlupdate="UPDATE `invoice_rct_mas` SET `is_java`=1 WHERE `rctno`=".$vnumber;
//echo $vnumber.'__'.$tenant;

if(mysql_query($sqlupdate)!=null || !empty(mysql_query($sqlupdate))){

   //echo "Records were updated successfully.";
$res .= " Receipt No_: ".stripslashes($vnumber)." posted successfully\n";
} else {

    //echo "ERROR: Could not able to execute";
 $res .= " Receipt No_: ".$vnumber."  was  posted to Tally but not updated in the database \n";

}

}
                
                // Close request to clear up some resources
                //curl_close($ch);

              
//End of Tally POST	
		
		/* if(curl_errno($ch)){
			//var_dump($data);
			$msg='Failed to POST to Tally with Error: '.curl_errno($ch).'----------'.$res;
			$res.=$msg;
			//curl_close($ch);
			//die($msg)
		} else {
			
		
			 $res.= $response_1; 
			curl_close($ch);
		}  */
	//echo '<textarea rows="20" cols="40" style="border:none;">'.$res_str.'</textarea>';
	//die();
}
		}	
    

}else{
	//die( 'No receipt selected for posting, please select receipt!');
	$res.='No receipt selected for posting, please select receipt!';
}
//if(!empty($res)) {
		 // die($res);
        $custom = array('msg'=>$res,'s'=>"Success",'tallyfb'=>$res); 
        $response_array [] = $custom;
		echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
	 exit;
  //  } 
   // $custom = array('msg'=>$res,'s'=>"Success",'tallyfb'=>$res); 
   // $response_array [] = $custom;
	
	
	// Return Success Function
    /* function json_success($res) {
        $custom = array('msg'=>$res,'s'=>"Success",'tallyfb'=>$res); 
        $response_array [] = $custom;
        return json_encode($response_array);
    } */
	//echo  json_success($res);
/*      echo return '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}'; 
    exit;     */
	
	// $name = $_REQUEST['Name'];

    // Validation
   

    
//echo $res;    
//echo '<textarea rows="20" cols="40" style="border:none;">'.$res_str.'</textarea>';
//print_r('<root>'.$xmldata.'<root>');7
//die("End");

	/*Actual code for importing to Tally goes here*/
 
  
	 
			//
                }
                else{
                    echo  "No Receipts Selected : <a href='pdcheqs_operation.php'>Click to Login</a>";
    

	}
	
	// Return Success Function
    function json_success($msgs) {
        $return = array();
        $return['error'] = FALSE;
        $return['msg'] = $msgs;
        return json_encode($return);
    }

    // Return Error Function
    function json_error($msgs) {
        $return = array();
        $return['error'] = TRUE;
        $return['msg'] = $msgs;
        return json_encode($return);
    }
?>


    

</body>
</html> 