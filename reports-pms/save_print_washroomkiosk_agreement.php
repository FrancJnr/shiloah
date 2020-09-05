<?php
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
foreach ($_GET as $k=>$v) {
          
              
           $tenament=substr($k, 0, 11);   
          
            if( $tenament=="tenantmasid"){
                
             $tenamentvalue=$v;   
            }
        };

?>
<!DOCTYPE html>
<html>
<head>  
<script src="../jquery/jquery-1.7.1.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="../jqueryte/jquery-te-1.4.0.css">
<script type="text/javascript" src="../jqueryte/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="../jqueryte/demo/demo.css">
<link type="text/css" rel="stylesheet" href="../jqueryte/jquery-te-1.4.0.css">

<script type="text/javascript" language="javascript">
  $(document).ready(function() {
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$("#status").click(function()
	{
            //alert("touche");
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
       $("#save").click(function()
	{ 
          ajaxPOSTTest();
        });
       $("#print").click(function()
	{
          var url = "print_washroomkiosk_agreement_pdf.php?grouptenantmasid="+<?php echo $_GET['grouptenantmasid'];?>+"&tenantmasid="+<?php echo $tenamentvalue;?>;
        // window.open(url, "Print PDF", "width=800,height=800,toolbar:false,");
         window.open(url,  "windowOpenTab", "width=800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
         
         return false;
        });

     
    function ajaxPOSTTest() {
      //  alert("haha");
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxPOSTTestRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxPOSTTestRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxPOSTTestRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }

        ajaxPOSTTestRequest.onreadystatechange = ajaxCalled_POSTTest;
        
    
      
        var url = "save_draft_document.php?action=Save&section=washroom-agreement&grouptenantmasid="+<?php echo $_GET['grouptenantmasid'];?>+"&tenantmasid="+<?php echo $tenamentvalue;?>;
        var params =$("form").serialize();
        ajaxPOSTTestRequest.open("POST", url, true);
        ajaxPOSTTestRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajaxPOSTTestRequest.send(params);
    }

    //Create a function that will receive data sent from the server
    function ajaxCalled_POSTTest() {
        if (ajaxPOSTTestRequest.readyState == 4) {
//            document.getElementById("output").innerHTML = ajaxPOSTTestRequest.responseText;
        alert(ajaxPOSTTestRequest.responseText);
        }
    }

        
        });
</script>

</head>

<body>
 
    
<?php
try
{
   
$sqlArray="";
$cnt =1;
$group=0;
$tenantRent="<br>";
$tenantSc ="<br>";
$tenantDeposit ="<br>";
$shopid="";
$sizetotal="";
$shopsizeid="";
$totalDeposit=0;
$tenantmasid=0;
$leasedeposit=0;
$grptenantmasid=0;
$groupmasid=0;
$groupmasid = $_GET['grouptenantmasid'];

foreach ($_GET as $k=>$v) {	
	$len =  strlen(trim($k));
	if($len >= 11)
	{		
		if($k  =="grouptenantmasid")
		{
			
			$sql = "SELECT * FROM  `group_tenant_mas` WHERE  `grouptenantmasid` ='$v';";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$grptenantmasid  = $row["tenantmasid"];
			}
				//$custom = array('msg'=> $grptenantmasid.$k.$v ,'s'=>'error');
				//	$response_array[] = $custom;
				//	echo '{"error":'.json_encode($response_array).'}';
				//	exit;
		}
		$k = str_split($k,11);
		if($k[0] =="tenantmasid")
		{		
			$group++;
			$sqlArray.= $cnt."--> KEY: ".$k[0]."; VALUE: ".$v."--->$group<BR>";
			//$tenantmasid[] = $v;
			$tenantmasid = $v;				
			$cnt++;			
			////////create company view
			$viewSql = "create view view_offerletter_company as SELECT * ,\n"
			    . "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
			    . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
			    . "FROM mas_company where companymasid =". $companymasid;
			$result = mysql_query($viewSql);
			
			//create tenant view
			   $viewSql =  "create view view_offerletter_tenant as "
			    . "SELECT a.*, b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size, \n"
			    . "DATE_FORMAT( a.doo, '%d-%m-%Y' ) as 'tenantdoo',  \n"
			    . "DATE_FORMAT( a.doc, '%d-%m-%Y' ) as 'tenantdoc'  \n"
			    . " FROM mas_tenant a\n"
			    . " INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
			    . " INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
			    . " INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
			    . " INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
			    . " INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
			    . " INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
			    . " WHERE a.tenantmasid =".$tenantmasid
			    . " and  a.companymasid=$companymasid";
			$result = mysql_query($viewSql);
			
			//create tenant_contact person view
			   $viewSql =  "create view view_tenant_cp as select a.* from mas_tenant_cp a inner join mas_tenant b on b.tenantmasid = a.tenantmasid "
					. " WHERE a.tenantmasid =".$tenantmasid
					. " and  b.companymasid=$companymasid"
					. " and  a.documentname='1'";
			$result = mysql_query($viewSql);
			
			// create tenant rent tbl view
			$viewSql = "create view view_offerletter_rent as select b.*,DATE_FORMAT( b.fromdate, '%d-%m-%Y' ) as 'rent_fromdate' ,DATE_FORMAT( b.todate, '%d-%m-%Y' ) as 'rent_todate' from \n"
			    . "trans_offerletter a\n"
			    . "inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid\n"
			    . "WHERE a.tenantmasid =".$tenantmasid;
			$result = mysql_query($viewSql);
			
			// create tenant scrvice charge tbl view
			$viewSql= "create view view_offerletter_sc as select b.*, DATE_FORMAT( b.fromdate, '%d-%m-%Y' ) as 'sc_fromdate' ,DATE_FORMAT( b.todate, '%d-%m-%Y' ) as 'sc_todate' from \n"
			    . "trans_offerletter a\n"
			    . "inner join trans_offerletter_sc b on b.offerlettermasid = a.offerlettermasid\n"
			    . "WHERE a.tenantmasid =".$tenantmasid;
			$result = mysql_query($viewSql);
			
			// create tenant deposit tbl view
			$viewSql = "create view view_offerletter_deposit as select b.* from \n"
			    . "trans_offerletter a\n"
			    . "inner join trans_offerletter_deposit b on b.offerlettermasid = a.offerlettermasid\n"
			    . "WHERE a.tenantmasid =".$tenantmasid;
			$result = mysql_query($viewSql);
			
			$companyname = strtoupper($_SESSION['mycompany']);
			
			
			//load company details
			$sql = "SELECT * FROM view_offerletter_company";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$companyaddress = $row['address1']."<br> ".$row['address2']."<br>".
						"P.O.Box No: ".$row['poboxno'].",".$row['city']."<br>".
						$row['state'].",".$row['pincode']."<br>".
						$row['country'];
			        $companycity = $row['city'];
				$companyshortaddress = "P.O.Box: ".$row['poboxno']." - ".$row['pincode'].",".$row['city'];
				$companyaddress2 = $row['address1'].",".$row['address2'].",".
						"P.O.Box No: ".$row['poboxno'].",".$row['city'].",".
						$row['state'].",".$row['pincode'].",".
						$row['country'];
				$companytelephone1 =$row['telephone1'];
				$companytelephone2 =$row['telephone2'];
				$companyfax =$row['fax'];
				$companyemailid =$row['emailid'];
				$companywebsite =$row['website'];
			}
			////load tenant details
			$sql = "SELECT * FROM view_offerletter_tenant";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{				
				//$tenantmasid = $row['tenantmasid'];
				$tenantname = $row['salutation']." ".strtoupper($row['leasename']);
				$tradingname = $row['tradingname'];
				$tenantcode = $row['tenantcode'];
				$address1 = $row['address1'];
				$address2 = $row['address2'];
				$city = $row['city'];
			        $tenantcity = $row['city'];
				$state = $row['state'];
				$country = $row['country'];
				$pincode = $row['pincode'];
				$poboxno = $row['poboxno'];
				preg_match('/\d+/', $poboxno, $number);  // select only mo's	
				$poboxno = $number[0];
				
				$companycity = $row['city'];
				$companypoboxno = $poboxno;
				$companypincode = $row['pincode'];
				$leaseterm = $row['term'];
				$rentdesc = $row['rentdesc'];
				$regno = $row['regno'];
				
				$tenantshortaddress ="P.O.Box NO: $poboxno - $pincode, $city";
				$tenantaddress = "P.O.Box No: ".$poboxno.",".$address1.",".$address2.",".$city.",".$state.",".$pincode.",".$country;
				$cpname1 = $row['cpname1'];
				$cpphone1 = $row['cpphone1'];
				$cpphone2 = $row['cpphone2'];
			        $nob = $row['nob'];
				$emailid = $row['emailid'];
				$buildingname = strtoupper($row['buildingname']);
				$buildingcity = $row['buildingcity'];
				$blockname = $row['blockname'];
				$flooname = $row['floorname'];
				$floordescription = strtoupper($row['floordescription']);
				$shopcode = strtoupper($row['shopcode']);
                                $renewalfromid = $row['renewalfromid'];
                                
				$shopid .= $floordescription.",Shop No:".$shopcode;
				$shopsizeid .= "(".$floordescription.",Shop No:".$shopcode.",Size:".$row['size']." sqrft)";
				$tenantRent .= '<strong><u>'.$buildingname.','.$floordescription.',Shop No:'.$shopcode.',Size:'.$row['size'].' sqrft</u></strong><br><br>';
				$tenantSc .= "<strong><u>".$buildingname.",".$floordescription.",Shop No:".$shopcode.",Size:".$row['size']." sqrft</u></strong><br><br>";
				$tenantDeposit .= "<strong><u>".$buildingname.",".$floordescription.",Shop No:".$shopcode.",Size:".$row['size']." sqrft</u></strong><br><br>";
				if($cnt > 2)
				{
					$shopid .=",";
					$shopsizeid .=",";
				}
				$sizetotal += $row['size'];
				$size = $row['size'];
				$term = $row['term'];
				$doo = $row['tenantdoo'];
				$doc = $row['tenantdoc'];
				$latefeeinterest = $row['latefeeinterest'];
				
				$rentcycle = $row["rentcycle"];	
			}
			//load tenant_cp details
			$sql = "SELECT * FROM view_tenant_cp";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
			  $tenantcpname = $row['cpname'];
			  $tenantcpmobile = $row['cpmobile'];
			  $tenantcplandline = $row['cplandline'];
			  $tenantcpemailid = $row['cpemailid'];
			  $tenantcpnid = $row['cpnid'];
			}
			$tenantcptable ="<table>
					<tr>
						<td>To:&nbsp;<br>$tenantcpname</td>
					</tr>
					<tr>
						<td><strong>$tenantname</strong></td>
					</tr>
					<tr>
						<td>$tenantshortaddress</td>
					</tr>
					<tr>
						<td>$country.</td>
					</tr>
					<tr>
						<td>Landline:&nbsp;$tenantcplandline&nbsp;&nbsp;&nbsp;Mobile:&nbsp;$tenantcpmobile</td>
					</tr>		
					<tr>
						<td>Email:&nbsp;$tenantcpemailid</td>
					</tr>
					</table>";
			//load tenant rent details
			$tenantRent .='<table cellpadding="3" cellspacing="0" border="1" width=100%><tr align="center" style="font-weight:bold;" ><th>From</th><th>To</th><th>Amount (KSH)</th><th>Period</th></tr>';
			$sql = "SELECT * FROM view_offerletter_rent";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$offerlettermasid = $row['offerlettermasid'];
				$tenantRent .='<tr style="text-align:center;"><td>'.$row['rent_fromdate'].'</td>
                                                    <td>'.$row['rent_todate'].'</td>
						    <td align="right">'.number_format($row['amount'],0, '.', ',').'</td>
						    <td>'.$rentcycle.'</td>
						    </tr>';
			}
			$tenantRent .='</table>';
			//load tenant scrvice charge details
			$tenantSc .="<table cellpadding='3' cellspacing='0' border='1'width='100%'><tr>"
				."<thead><th>From</th>"
			        ."<th>To</th>"
				."<th>Amount (KSH)</th>"
			        ."<th>Period</th>"
				."</thead></tr>";
			$sql = "SELECT * FROM view_offerletter_sc";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$tenantSc .="<tr>"
						."<td>".$row['sc_fromdate']."</td>"
						."<td>".$row['sc_todate']."</td>"
						."<td align='right'>".number_format($row['amount'],0, '.', ',')."</td>"
						."<td>$rentcycle</td>"
						."</tr>";
			}
			$tenantSc .="</table> <br>";
			//load tenant deposit details
			$tenantDeposit .="<table cellpadding='3' cellspacing='0' border='1' width='100%'><tr>"
				."<thead><th>Index</th>"
			        ."<th>Description</th>"
			        ."<th>Amount (KSH)</th>"
				."</thead></tr>";
			$sql = "SELECT * FROM view_offerletter_deposit";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$n = 1;
				$tenantDeposit .="<tr>"
						."<td align='center'>$n.</td>"
						."<td>".$row['depositmonthrent']." Months Security Deposit for rent </td>"
						."<td align='right'>".number_format($row['rentdeposit'], 0, '.', ',')."</td>"			
						."</tr>";$n++;
				$totalDeposit +=$row['rentdeposit'];
				$tenantDeposit .="<tr>"
						."<td align='center'>$n.</td>"
						."<td>".$row['depositmonthsc']." Months security deposit Scr.Chrg</td>"
						."<td align='right'>".number_format($row['scdeposit'], 0, '.', ',')."</td>"
						."</tr>";$n++;
				$totalDeposit +=$row['scdeposit'];
				//// total deposti rectified on 11.09.2014 after charles found
				////if( $row['advancemonthrent'] == 0)
				////{
				////	$row['advancemonthrent']="";
				////}	$val="0";
				////$tenantDeposit .="<tr>"
				////		."<td align='center'>$n.</td>"
				////		."<td>".$row['advancemonthrent']." Month Advance rent with VAT</td>"
				////		."<td align='right'>".number_format($row['rentwithvat'], 0, '.', ',')."</td>"
				////		."</tr>";$n++;	
				////$tenantDeposit .="<tr>"
				////		."<td align='center'>$n.</td>"
				////		."<td>".$row['advancemonthsc']." Month Advance Scr.Chrg with VAT</td>"
				////		."<td align='right'>".number_format($row['scwithvat'], 0, '.', ',')."</td>"
				////		."</tr>";$n++;
				////$tenantDeposit .="<tr>"
				////		."<td align='center'>$n.</td>"
				////		."<td>Leegal Fees with VAT</td>"
				////		."<td align='right'>".number_format($row['leegalfees'], 0, '.', ',')."</td>"
				////		."</tr>";$n++;
				////$tenantDeposit .="<tr>"
				////		."<td align='center'>$n.</td>"
				////		."<td>Stamp Duty</td>"
				////		."<td align='right'>".number_format($row['stampduty'], 0, '.', ',')."</td>"
				////		."</tr>";$n++;
				////$tenantDeposit .="<tr>"
				////		."<td colspan='2'>Total</td>"
				////		."<td align='right'>Kshs.<strong>".number_format($row['depositTotal'], 0, '.', ',')."</strong></td>"
				////		."</tr>";
				////$totalDeposit +=$row['depositTotal'];
				$n = 0;
			}
			$tenantDeposit .="</table><br>";
			
			if($tradingname ==""){
				$leaseclass = " incorporated in the Republic of Kenya with a limited liability";
				$tradingas="";
				$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
				$tradingtitle="";
			}
			else{
				if($regno =="")
					$leaseclass = "";
				else
					$leaseclass = "registred in the Republic of Kenya ";
				$tradingas = "<tr>
						<td colspan='3' align='center' style='height:10px' valign='middle'><font style='font-size:25px;font-weight:bolder;'>T/A</font></td>
					</tr>
					<tr>
						<td colspan='3' align='center' style='height:10px' valign='middle'><font style='font-size:20px;font-weight:bolder;'><u>$tradingname</u></font></td>
					</tr>
					";
				$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
				$tradingtitle="- T/A ".$tradingname;
			}
			
			$headerContent = "<tr>    
						<td width='40%'><img src='../images/mega_plaza_logo.jpg' height='50px'></td>
						<td width='35%'><img src='../images/mega_city_logo.jpg' height='50px'></td>
						<td align='right'><img src='../images/mega_city_logo.jpg' height='50px'></td>    
					</tr>
					<tr><td colspan='3' align='center'><h1><font size=6>SHILOAH INVESTMENTS LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.com</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
			
			$buildingmunicipaladd="";
				if(strtoupper($buildingname) == "MEGA PLAZA"){
					$buildingmunicipaladd = "Kisumu Municipality Block 7/380";
					$rowMunicipalAddress= "Municipality Block 7/380";
				}
				else if(strtoupper($buildingname) == "MEGA CITY"){
					$buildingmunicipaladd = "Kisumu Municipality Block 9/134 &amp; 9/135";
					$rowMunicipalAddress= "Municipality Block 9/134 &amp; 9/135";
				}
				else if(strtoupper($buildingname) == "MEGA MALL"){
					$buildingmunicipaladd = "Kakamega Municipality Block 111/97";
					$rowMunicipalAddress= "Municipality Block 111/97";
				}
				else if(strtoupper($buildingname) == "RELAINCE CENTRE"){
					$headerContent = "<tr>    
						<td width='40%'><img src='../images/mega_plaza_logo.jpg' height='50px'></td>
						<td width='35%'><img src='../images/mega_city_logo.jpg' height='50px'></td>
						<td align='right'><img src='../images/mega_city_logo.jpg' height='50px'></td>    
					</tr>
					<tr><td colspan='3' align='center'><h1><font size=6>GRANDWAYS VENTURES LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.com</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
					$buildingmunicipaladd = "WOODVALE GROVE, WESTLANDS, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
					$rowMunicipalAddress= "";
				}else if(strtoupper($buildingname) == "KATANGI"){
					$headerContent = "
					<tr><td colspan='3' align='center'><h1><font size=6>KATANGI DEVELOPERS LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.com</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
				}
				else if(strtoupper($buildingname) == "MEGA CENTRE"){
					$headerContent = "<tr>    
						<td width='40%'><img src='../images/mp_logo.jpg' height='50px'></td>
						<td width='35%'><img src='../images/mc_logo.jpg' height='50px'></td>
						<td align='right'><img src='../images/mm_logo.jpg' height='50px'></td>    
					</tr>
					<tr><td colspan='3' align='center'><h1><font size=6>GRANDWAYS VENTURES LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
					$buildingmunicipaladd = "Kitale Municipality Block 7/14";
					$rowMunicipalAddress= "Municipality Block 7/14";
					$buildingcouncil = "Kitale Municipal Council";
				}
				$pledged = false;
					if(strtoupper($buildingname) == "MEGA PLAZA"){
						$buildingmunicipaladd = "Kisumu Municipality Block 7/380";
						$bank="NIC BANK ";	
						$pledged =true;	
					}
					else if(strtoupper($buildingname) == "MEGA CITY"){
						$buildingmunicipaladd = "Kisumu Municipality Block 9/134 & 9/135";
						$bank="EQUITY BANK ";
						$pledged =true;	
					}
					else if(strtoupper($buildingname) == "MEGA MALL"){
						$buildingmunicipaladd = "Kakamega Municipality Block III/97";
						$bank="";	
						$pledged =false;	
					}
					else if(strtoupper($buildingname) == "RELIANCE CENTER"){
						$buildingmunicipaladd = "WOODVALE GROVE, WESTLANDS, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
						$bank=" BANK OF AFRICA ";	
						$pledged =true;	
					}
					else if(strtoupper($buildingname) == "MEGA CENTER"){
						$buildingmunicipaladd = "Kitale Municipality 7/14";
						$bank="";	
						$pledged =false;	
					}
					
				if($tradingname ==""){
					$leaseclass = " incorporated in the Republic of Kenya with a limited liability";
					$tradingas="";
					$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
					$tradingtitle="";
				}
				else{
					if($regno =="")
						$leaseclass = "";
					else
						$leaseclass = "registred in the Republic of Kenya ";
					$tradingas = "<tr>
							<td colspan='3' align='center' style='height:10px' valign='middle'><font style='font-size:25px;font-weight:bolder;'>T/A</font></td>
						</tr>
						<tr>
							<td colspan='3' align='center' style='height:10px' valign='middle'><font style='font-size:20px;font-weight:bolder;'><u>$tradingname</u></font></td>
						</tr>
						";
					$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
					$tradingtitle="- T/A ".$tradingname;
				}
				
		}// end if chicking tenantmasid
		

		session_write_close();
		$sql ="drop view view_offerletter_company";
		mysql_query($sql);
		$sql ="drop view view_offerletter_tenant";
		mysql_query($sql);
		$sql ="drop view view_tenant_cp";
		mysql_query($sql);
		$sql ="drop view view_offerletter_rent";
		mysql_query($sql);
		$sql ="drop view view_offerletter_sc";
		mysql_query($sql);
		$sql ="drop view view_offerletter_deposit";
		mysql_query($sql);
                
		//$row1="b)	To bear and pay all rates taxes and other charges of every nature and kind which now are or may hereafter be assessed or
		//	imposed on the said premises or any part thereof or on the Lessor or the Lessee in respect thereof  or by the Government of
		//	Kenya or any Municipal Township Local or other Authority the Head rent payable under the Valuation for Rating Act (Chapter 266) and the
		//	Rating Act (Chapter 267) or any Act or Acts amending or replacing the same only excepted PROVIDED ALWAYS that if in respect of any year of the said
		//	term the rate or rates payable under the said Acts or either of them shall be increased beyond that or those payable in respect of the year
		//	<strong>".convert_number(date('Y'))."<strong> the Lessee will forthwith on demand pay to the Lessor a proportionate share of such increase;";
		//$row2="";
		//$row3="";
		//$row4="";

	}// end if length check
}// end for each

    $leasename = $tenantname;    
    if($tradingname !="")    
        $leasename  .= " (TA) ".$tenantname;
        
    $div0="<span id='span'>Print Preview Simple Agreement for  <font color='blue'><u>$tenantname</u></font>&nbsp;
            <button type='button' id='btnPreview' name='0'>Save & Print</button></span>
            <p class='printable'><table width='100%' border=0><tbody><tr><td>";
    $div1="";
    

    //CONTENTS
    $address ='<table cellpadding="2" >		
		<tr height="70px">    
			<td width="35%">P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
			<td width="35%">Mega Plaza Block "A" 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
			<td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
		</tr>	
		</table>';
    
    $pa='<table><tr align="center" style="font-stretch: expanded;"><th>'.$companyname.'.</th></tr></table><hr>';
    $pb=$address;
    $pc='AGREEMENT FOR HOUSE KEEPING SERVICES';
    $pd="";
    $p0="";
    $div1 ='<span  style="line-height:1.6px;text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;">
                        This agreement is executed on '.convert_number(date('d')).' day of '.convert_number(date('m')).' '.convert_number(date('Y')).', 
                            BETWEEN <strong>'.strtoupper($companyname).'</strong> and of P.O Box Number 2501-40100 Kisumu
                        (hereinafter referred to as the “Company” which term shall include its successors / assigns) of the First Part., 
                        and <strong>'.$tenantname.'</strong> Trading as <strong>'.$tradingname.'</strong> and of P.O BOX NO'.$poboxno.' - '.$pincode.',
                            '.$city.' </strong> in the Republic of Kenya(carrying on the business of <strong>'.$nob.'</strong> services hereinafter called and referred to as “Contractor” 
                        which term shall include his / their successors / assigns) of the Second Part.<br><br>
                        WHEREAS the Company is desirous  of availing specialized <strong>'.$nob.'</strong> services in its 
                        ground floor Common Area Washrooms at <strong>'.strtoupper($companyname).'</strong> on for a period of <strong>'.strtoupper($leaseterm).'</strong> or such 
                        extended period of services of any contract for specialized <strong>'.$nob.'</strong> in the <strong>'.strtoupper($companyname).'</strong> 
                        Common area toilets at the ground floor (hereinafter referred to as Washrooms) and,  <br><br>
                        
                        WHEREAS the Contractor has agreed and undertakes to render specialized <strong>'.$nob.'</strong> services as per requirement 
                        and to the full satisfaction of the Company as per the terms and conditions and as per the scope of work to be assigned
                        by the Company mentioned herein below. </span>';
      $p1=$div1;   
      $p2="";
      $p3="";
      $p4="Contractor Name: ".$leasename;
      $p4a="<b>".$companyname." <br> WASHROOM AGREEMENT</b>";
      
     
      $p5 ="<ol align='justify' style='line-height:1.6px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: numeric;'>
                <li>
                .   The Contractor agrees and undertakes to render the Specialized <strong>".$nob."</strong>
                    Services in the common area washrooms at the ground floor as per scope of work as detailed in Schedule – I, 
                    and as per the terms and conditions as detailed in Schedule – II, and shall receive payment thereof, 
                    as detailed in Schedule – III.
                <br>
                </li>
                <li>
                  The agreement shall be deemed to have come in to force only for a period of  <strong>".$leaseterm."</strong> 
                with effect from <strong>".date("Y-m-d")."</strong> and shall remain valid up to <strong>".date("Y-m-d",strtotime("+1 year"))."</strong> 
                and it may be extended for such other extended period for future and on such terms and conditions as may mutually be agreed upon. 
                On expiry of the tenure of the agreement or on termination of the contract for any reason whatsoever as per the terms and conditions, 
                the Contractor shall deliver the articles or other equipments or any other property of the Company in its / his possession in good condition,
                and clear the outstanding bills with KPLC, KIWASCO etc.  	
                <br>
                </li>
                 <li>
                  The Schedules I, II and III to this agreement shall form part of and be read as part of this agreement. 
                In witness where of the parties hereto have executed those on the day month and year above mentioned.  	
                <br>
                </li>
                 <li>
                  The Contractor shall maintain regular and proper books of accounts and other records, 
                 document, etc. supported by the vouchers so that the same may be available for inspection by any authorized person.  	
                <br>
                </li>
                 <li>
                   In case the Contractor assigns or sub-assigns this contract without written approval of the Company 
                and or attempts to do so or in case the performance of Contractor is found to be unsatisfactory or violated / contravened  
                any of  the terms  and  conditions  contained herein and schedule hereto, the Company shall have the right to 
                terminate the agreement without giving any notice to the Contractor and without prejudice to its right to recover 
                damages caused to the Company from the security deposit or otherwise. 	
                <br>
                </li>
                <li>
                  The Operations Manager of the Company shall be the sole authority to decide and judge 
                the quality of the service rendered by the Contractor.  	
                <br>
                </li>
                 <li>
                    All questions relating to the performance of the obligations under this agreement and to the 
                quality of materials used in house-keeping and all the dispute and differences which shall arise either 
                during or after the agreement period or other matters arising out of or relating to this agreement or 
                payments to be made in pursuance thereof shall be decided by the Operations Manager of the Company 
                whose decision shall be binding on the contractor. The Contractor hereby agrees to be bound by the 
                decision of the Operation Manager.	
                <br>
                </li>
                 <li>
                   The Contractor shall bear all the costs and expenses in respect of all charges, 
                stamp duties etc relating to this preparation and execution of this agreement. 	
                <br>
                </li>
                 <li>
                   The Contractor shall maintain good standard of services as indicated. 
                The performance of the contractor will be reviewed on monthly basis and in 
                case the services are not found up to the mark the contract will be terminated even 
                before the expiry of contract period by giving one month’s notice. 	
                <br>
                </li>
                <li>
                  The Contractor shall be responsible for proper maintenance and safety of all taps, mirrors, 
                toilet seat-covers, basins, hand-drying machines, door locks, tissue paper dispensers, soap dispensers, 
                materials, stocks, fixtures, etc. The cost of missing items / shortages of stocks / materials etc. 
                shall be deducted from the contractor’s deposit / any others sum / due to the contractors.  	
                <br>
                </li>
                 <li>
                   The contractor shall pay a security deposit of Kshs. <strong>100,000</strong>/= towards the washroom facilities in 
                <strong>".strtoupper($companyname)."</strong>, or a performance guarantee of a similar amount in lieu thereof from a 
                    bank acceptable to the Company prior to commencement of service under this agreement. 
                    The Company shall be entitled to adjust or appropriate the said security deposit or the proceeds of guarantee towards 
                    loss or damage caused by the Contractor or his employees or the amount of value of shortage or breakage or 
                    damage in the items / furniture & fixtures etc. entrusted to or caused to other assets of the Company by the 
                    Contractor or his employees or against any other liability of the Contractor such as failure to pay his monthly 
                    remittance to the Company.  The security deposit that may be made by the Contractor with the Company shall not 
                    carry any interest. 	
                <br>
                </li>
                 <li>
                 The employees of the Contractor, their management, control, duty rosters, administration, etc. 
                will be dealt with and decided by the contractor being their employer and engaged by them, but they shall be 
                disciplined and maintain decorum of the Company.   	
                <br>
                </li>
                 <li>
                   The Contractor shall issue appointment letters to all the persons employed by him in connection with performance 
                of his contract for house-keeping services, furnish proof by submitting copies of such letters received by the employees. 
                The appointment letter shall make clear that the concerned employee is the employee of the 
                Contractor only and <strong>".strtoupper($companyname)."</strong> where house-keeping services are rendered has no 
                obligation or any relationship to employment or otherwise whatsoever with him/them. 
                The Contractor will pay salary, allowances, etc. to his employees as per rule at his end and the 
                Company will not be responsible for payment of anything to the employee of the Contractor / Contractor. 
                In this regard, the Contractor shall keep the Company indemnified against claims, salaries, allowances, 
                annual leaves, death, injury etc to his employees. 	
                <br>
                </li>
                <li>
                   The Company shall have the right to terminate the agreement after giving One (1) 
                month notice in advance or by the efflux of time or mutually agreed upon as the case may be or by making payment 
                in lieu equivalent to the notice period. The Contractor shall also have the option to terminate the agreement 
                after giving One (1) month notice or by efflux of time or mutually agreed upon as the case may be or by making 
                payment in lieu equivalent to the notice period. 	
                <br>
                </li>
                 <li>
                   Nothing contained in this agreement is intended to be nor shall be construed to be a grant, 
                demise or assignment in the law of premises or any part thereof by the Company to the 
                Contractor or his employees and the contractor and his employees shall vacate the same and 
                handover all the Company’s furniture, fixtures, goods, materials, etc. in good condition on the 
                termination of the agreement period either by efflux of time or otherwise. 	
                <br>
                </li>
                 <li>
                   The Company shall have the right to withhold reasonable sums from the amounts payable 
                to the contractor under this contract or the security deposit or the proceeds of guarantee if 
                the contractor commits breach of any of the terms and conditions of this agreement or fails to 
                produce sufficient proof to the satisfaction of the Company, of payment of all statutory and other 
                dues or compliance with other obligations. 	
                <br>
                </li>
                 <li>
                   On termination of the contract by the Company on the ground of performance, 
                and / or decision as per the terms, the Company shall be entitled to engage the services of any other person, 
                agency or Contractor to meet its requirement, without prejudice to its rights including claim for damages against the Contractor. 	
                <br>
                </li>
            </ol>";


   $p5a ="<table>
             <tr>
                <td valign='middle' align='center'>
                <strong>SCHEDULE – 1</strong><br>
                </td>
                <td valign='middle' align='center'>
                    <strong><u>SCOPE OF WORK FOR SPECIALIZED HOUSE KEEPING TO BE ENSURED BY THE CONTRACTOR</u></strong>
            </td>
         </tr>
        </table>";
   
$p6="<br><br><strong>GENERAL</strong><br><ol align='justify' style='line-height:1.6px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: numeric;'>
       <li>
House Keeping contract will include the entire common area washrooms on the ground floor at <strong>".strtoupper($companyname)."</strong>.
 <br>
 </li>
 <li>
While Ground floor washrooms shall be used by customers at <strong>".strtoupper($companyname)."</strong>, the Contractor shall deploy his staff to ensure that only tenants at <strong>".strtoupper($companyname)."</strong> are the ones to access the facility freely.
<br>
 </li>
 <li>
The Contractor shall issue identification tags to all tenants at <strong>".strtoupper($companyname)."</strong>, and the same shall be used to access Mezzanine floor washrooms freely. The tags shall bear the word “Not transferable”.
<br>
 </li>
 <li>
The Contractor shall provide adequate number of personnel to handle the work in the washrooms per day and night.
<br>
 </li>
 <li>
The staff deployed will be trained in House Keeping / management services, bear good conduct and physically fit for the work.
<br>
 </li>
 <li>
All the workers will wear uniform in clean condition while attending to their duties and carry their photo identity cards displayed prominently for which Contractor will provide required personal protective gears to his employees. 
<br>
 </li>
 <li>
Desired level of cleanliness in the common area washrooms of <strong>".strtoupper($companyname)."</strong> will be maintained and for this all materials / instruments / tools, etc. will be provided by the Contractor. The supervisor of the Contractor will attend to complaints on urgent basis round the clock. 
<br>
 </li>
 <li>
The ground floor washrooms shall be cleaned many times in a day at the following intervals:
<br>
 </li>
6am–7am, between 10am-11am, between 3pm–4pm; and between 6pm–7 pm.
<br>
<br>
Toiletries / Cleaning materials / instruments in sufficient quantity and good quality (as decided by the Company) to be provided by the Contractor will be as under:
<br>
<ol align='justify' style='letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-alpha;'>
 Hand washing Liquid Soap
<br>
<li> 
 Odonil, Naphthalene balls in toilets.
<br>
</li>
<li>
  Detergents, phenyl, acid.
<br>
  Glass Cleaners
<br>
</li>
<li>
  Brushes, Brooms Wipers, Sponges, Mops etc.
<br>
</li>
<li>
  Floor scrubbing, polishing machine, and;
<br>
</li>
<li>
   Toilet Deca rolls.
 </li>
<li></ol>
<br><br>
<li>
Specialized maintenance of fittings, buckets, sanitary wares, brackets etc. will be ensured.
<br>
</li>
 <li>
 Provision of the following specialized staff will be ensured :
<br>
</li>
<ol align='justify' style='letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-alpha;'>
 <li>
 Sweepers / cleaners
<br>
</li>
<li>
Supervisors to ensure proper house-keeping / and for attending / calls round the clock.
</li>
</ol><br><br><strong>DAILY SERVICES</strong><br><br><ol align='justify' style='line-height:1.6px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: numeric;'>
<li>
Removal of waste material / garbage from the dustbins, buckets, in the common area washrooms.
<br>
</li>
<li>
Dusting and cleaning of glass, partition, mirrors using glass cleaning chemicals to keep all such articles dust free at all time.
<br>
</li>
<li>
Acid cleaning and scrubbing of toilets, washbasins, sanitary fittings using detergents, deodorants and disinfectants at least thrice a day.
<br>
</li>
<li>
Cleaning / moping of floor area by detergents, disinfectants, etc in the morning or as and when required during the day.
<br>
</li>
<li>
Provision of toiletries (deca rolls) in the toilets in the morning after daily check up.
<br>
</li>
<li>
Provide liquid soap for each soap dispenser at all times.
<br>
</li>
</ol><br><br><strong>WEEKLY SERVICES</strong><br><br><ol align='justify' style='line-height:1.6px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: numeric;'>
<li>
 Mechanical washing and scrubbing of floor area with detergents, dust removing chemicals and polishing of the floor areas, etc.
<br>
</li>
<li>
 Removal of cobwebs, dusts, termites, insects, pests, etc
<br>
</li>
<li>
 Windows sponging and cleaning
<br>
</li>
<li>
 Keeping sinks and basins and mirrors dust free.
<br>
</li>
<li>
 Cleaning of dustbins and buckets in the washrooms with detergents.
<br>
</li>
<li>
 Up-keeping of partition glasses and panes with utmost care and by application of glass cleaning chemicals.
<br>
</li>
<li>
 Acid cleaning of sanitary-wares.
<br>
</li>
<li>
 Polishing & oiling of door-closers, door handles, and other brass fittings with Silvo / Brasso / lubricants. Polishing of taps and other steel fittings in the toilets with Silvo / Brasso.
<br>
</li>
<li>
 Pest control of the entire washroom area, etc.
</li>
</ol>";
$p6a="<table>
             <tr>
                <td valign='middle' align='center'>
                <strong>SCHEDULE – II</strong><br>
                </td>
             </tr>
        </table>";

$p7="<strong>TERMS & CONDITIONS</strong><br><br><ol align='justify' style='line-height:1.6px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: numeric;'>
<li>
Charging for the specialized house-keeping services shall be done by the contractor for all users of the common area washrooms in <strong>".strtoupper($companyname)."</strong> apart from <strong>".strtoupper($companyname)."</strong> Tenants, who shall be provided with identification tags to access the Mezzanine floor washrooms free of charge.
<br> 
</li>
<li>
The Contractor undertakes to obtain any license, permit, consent, sanction etc as may be required or called for from / by local or any other authority for doing such work. The Contractor shall comply with all applicable laws, rules and regulations in force. The Contractor undertakes to obtain such permission / license as may be required under the Contract Labour (Regulation and Abolition) Act, 1970. 
The Contractor undertakes to produce the license / permission etc. so obtained to the Company or furnish copies thereof as and when required by the Company. The Contractor also undertakes to keep and get renewed such license, permission etc. from time to time. The Contractor shall be responsible for any contravention of the local, municipal, central, state, any other laws, rules, regulations, etc.
<br>
</li>
<li>
The Contractor agrees and undertakes to bear all taxes, rates, charges, levies or claims, whatsoever, as may be imposed by the Government or any local body or authority. The contractor also agrees to furnish such proof of payments of compliance of the obligation including registration certificates, receipts, licenses, etc. clearance certificates etc. as may be required by the Company from time to time.
<br>
</li>
<li>
The Contractor shall keep the Company indemnified against all the claims and liabilities, if any in clauses 2 & 3 as aforesaid.
<br>
</li>
<li>
The Contractor shall devote his full attention to the work of house-keeping and shall discharge his obligations under the agreement most diligently and honestly.
<br>
</li>
<li>
The Contractor shall provide personal protective equipment etc. to his staff engaged for the above services and all of them will wear the same in clean condition while on duty.
<br>
</li>
<li>
The Contractor’s employees will be allowed entry in to the specified areas of the premises with the specific permission of the Operations Manager or any other Officer authorized in this regard with valid photo identity card issued by the Contractor and displayed prominently. The Company reserves the right to grant permission or to refuse permission or to withdraw it where it has been granted earlier without assigning any reason. The Contractor shall ensure that his employees attend to their assigned duties and do not wander or roam around and not to pose disturbance to the Company, its Tenants, Staff, etc. and produce exhibit identity card.
<br>
</li>
<li>
The Contractor and all his employees shall at all times during the continuance of this agreement, obey and observe all the directions and instructions which may be given by the Company concerning any aspect of house-keeping services.  In case the Contractor does not render any of the services as contemplated in Schedule – I, the Company shall be entitled to deduct such amount as deemed appropriate as may be decided by the Operations Manager (whose decision will be final) in respect of the default from the amount payable to the Contractor. The employees of the Contractor, their management, control, duty rosters, administration, etc. will be dealt with and be decided exclusively by the contractor being their employer and engaged by them. 
<br>
</li>
<li>
In case the Contractor or any of his employees fails to fulfill his / their obligations for any day or any number of days to the satisfaction of the Company for any reason whatsoever, the contractor shall pay by way of liquidated damages, a sum of Kshs.1,000/= (One thousand only) per day for the entire numbers of such days and  the Company shall, without prejudice to its other rights and remedies shall be entitled to deduct such damages from the deposit paid by the Contractor.
<br>
</li>
<li>
<strong>The charges for electricity and water consumed in the common area washrooms in </strong>".strtoupper($companyname)." <strong>shall be borne by the Contractor, in which case the Contractor shall be provided with separate meter / sub-meters each for electricity and for water and shall pay as per the readings </strong> within the first week of the succeeding month, as per the invoice submitted by the Company. 
</li>
</ol>";     
$p7a="<table>
             <tr>
                <td valign='middle' align='center'>
                <strong>SCHEDULE – III</strong><br>
                </td>
             </tr>
        </table>";
$p8="<strong>STATE OF WASHROOM FACILITIES</strong><br><br><ol align='justify' style='line-height:1.6px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: numeric;'>
<br>
Please note that we are handing over the following items in good tenable working condition:
<br>
<li>
Glass mirrors,
<br>
</li>
<li>
Hand drying machines,
<br>
</li>
<li>
Toilets each complete with seat covers in both male and female washrooms respectively,
<br>
</li>
<li>
Push taps, and;
<br>
</li>
<li>
Urinal units complete with cisterns in males’ washroom.
</li>
</ol>";

$p9="<span  style='line-height:1.6px;text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;'>
<br><strong>POINTS TO NOTE</strong><br>Please note that sanitary bins for the ladies belong to Rentokil Initial Kenya Limited. We are going to keep the bins for the next 3 months and once we are satisfied that you are capable of handling the toilets and have installed your own bins effectively, we shall write to Rentokil Initial for termination of their services.
<br><br>
As earlier discussed, all tenants shall use the mezzanine washrooms freely, and you will need to give a tag to each shop / office to be used as the identification to access the washrooms freely (only the tenants). Though the staff of <strong>".$companyname."</strong> shall maintain the washrooms at the mezzanine floor, 
you shall deploy your staff at the mezzanine washrooms to ensure that only tenants are the ones using the washrooms freely.
<br><strong>LIST OF TENANTS</strong><br><br>	
We have attached the list of all the existing tenants at Mega City for your use to make identification tags:
<br>The purpose of outsourcing washrooms is to add value on the image of the company, and also provide some opportunities to the Contractor / employees; hence it must be understood by both the employer / employees who are executing the work, which forms the basis for our mutual beneficial long term relations.
</span>";

$p9a="<table>
             <tr>
                <td valign='middle' align='center'>
                <strong>SCHEDULE – IV</strong><br>
                </td>
             </tr>
        </table>";
//$p9b="<strong>TERMS OF PAYMENT</strong><br><br><span  style='line-height:1.6px;text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;'>
//For the first One (1) month, (starting ".date("Y-m-d")." to ".date("Y-m-d",strtotime("+29 days")).") 
//the Contractor shall not remit any payment to the company for the services at <strong>".  strtoupper($companyname)."</strong>
//washrooms however at the end of One (1) month survey period, 
//the contractor shall pay the Company Kshs. 10,000/= + VAT per month.</span>";

//    $txt ='The licensee hereby accepts this license subject to the above conditions, restrictions and stipulations.';    
//    $p6=$txt;
    if ($tradingname == "")
    {
        $html ='<strong>TERMS OF PAYMENT</strong><br><br><span  style="line-height:1.6px;text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;">
For the first One (1) month, (starting '.date("Y-m-d").' to '.date("Y-m-d",strtotime("+29 days")).') 
the Contractor shall not remit any payment to the company for the services at <strong>'.strtoupper($companyname).'</strong>
washrooms however at the end of One (1) month survey period, 
the contractor shall pay the Company Kshs. 10,000/= + VAT per month.</span><br><br><table width="100%" border="0">
        <tr>
            <td style="height:15px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">The Licensor: <b>'.$companyname.'</b></td>
            <td>]</td>
        </tr>
         <tr>
            <td style="height:15px;">In the presence of: -</td>
            <td>]</td>
        </tr>
       <tr>
            <td style="height:15px;"></td>
            <td>]</td>
        </tr>       
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]&nbsp;&nbsp;&nbsp;&nbsp;__________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;
            COMPANY SEAL</td>
        </tr>        
        <tr>
            <td style="height:25px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:25px;">In the presence of: -</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="height:25px;">ADVOCATE</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:25px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;">I, ____________________________ certify that the above directors of the Company
            appeared before me on this _______ day of __________________ '.date("Y").' and being  known to me acknowledged the above
            signatures to be theirs and that they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:35px;"><br><br><b>______________________________________<br>
            <i>Signature and designation of the person certifying</i>
</b></td>
        </tr>
        <tr>
            <td colspan="2" style="height:25px;">&nbsp;</td>            
        </tr>      
         <tr>
            <td style="height:15px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">The Licensee: <b>'.$tenantname.'</b></td>
            <td>]</td>
        </tr>
         <tr>
            <td style="height:15px;">In the presence of: -</td>
            <td>]</td>
        </tr>
       <tr>
            <td style="height:15px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;"></td>
            <td>]</td>
        </tr>       
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]&nbsp;&nbsp;&nbsp;&nbsp;__________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;
            COMPANY SEAL</td>
        </tr>        
        <tr>
            <td style="height:25px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">In the presence of: -</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="height:15px;">ADVOCATE</td>            
        </tr>
         <tr>
            <td colspan="2" align="center" style="height:15px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;">I, ____________________________ certify that the above directors of the Licensee
            appeared before me on this _______ day of __________________ '.date("Y").' and being  known to me acknowledged the above
            signatures to be theirs and that they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:35px;"><br><br><b>______________________________________<br><i>
                            Signature and designation of the person certifying</i>
        </b></td>
        </tr>
        </table>';    
      $p10=$html;  
    }
    else
    {
        $html ='<strong>TERMS OF PAYMENT</strong><br><br><span  style="line-height:1.6px;text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;">
For the first One (1) month, (starting '.date("Y-m-d").' to '.date("Y-m-d",strtotime("+29 days")).') 
the Contractor shall not remit any payment to the company for the services at <strong>'.  strtoupper($companyname).'</strong>
washrooms however at the end of One (1) month survey period, 
the contractor shall pay the Company Kshs. 10,000/= + VAT per month.</span><br><br><table width="100%" border="0">
        
        <tr>
            <td style="height:15px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">The Licensor: <b>'.$companyname.'</b></td>
            <td>]</td>
        </tr>
         <tr>
            <td style="height:15px;">In the presence of: -</td>
            <td>]</td>
        </tr>
       <tr>
            <td style="height:15px;"></td>
            <td>]</td>
        </tr>       
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]&nbsp;&nbsp;&nbsp;&nbsp;__________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;
            STAMP</td>
        </tr>        
        <tr>
            <td style="height:25px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:25px;">In the presence of: -</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="height:25px;">ADVOCATE</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:25px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;">I, ____________________________ certify that the above directors of the Licensor
            appeared before me on this _______ day of __________________ '.date("Y").' and being identified to me acknowledged the above signatures to be his/hers/theirs and that he/she/they had freely and voluntarily executed this instrument and understood its contents..</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:15px;"><br><br><b>______________________________________<br>
            <i>Signature and designation of the person certifying</i>
</b></td>
        </tr>
        <tr>
            <td colspan="2" style="height:25px;">&nbsp;</td>            
        </tr>      
         <tr>
            <td style="height:15px;">SIGNED By The Licensee:</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:45px;"><b>'.$tenantname.'</b><br></td>
            <td>]</td>
        </tr>         
        <tr>
            <td style="height:35px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;__________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;
            STAMP</td>
        </tr>
        <tr>
            <td style="height:45px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            T/A <br> <b>'.$tradingname.'</b></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">In the presence of: -</td>
            <td>]</td>
        </tr>
        <tr>
            <td colspan="2" style="height:15px;">ADVOCATE</td>            
        </tr>
         <tr>
            <td colspan="2" align="center" style="height:15px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;">I, ____________________________ certify that the above directors of the Licensee
            appeared before me on this _______ day of __________________ '.date("Y").' and being identified to me acknowledged the above signatures to be his/hers/theirs and that he/she/they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:35px;"><br><br><b>______________________________________<br><i>
                            Signature and designation of the person certifying</i>
        </b></td>
        </tr>
        </table>';  
         $p10=$html;  
    }
    
    
}// end of try
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

<style type="text/css">
 ol,tr {
    /*color: blue !important;*/
    line-height: 18.5px !important;
   /* This will increase the rule score */
}
 #container {
  width: 800px;
  height: 50px;
  border: 0px solid black;
  text-align: center; /** center the divs **/
  font-size: 0; /** remove the unwanted space caused by display: inline-block in .child **/
}

#container .status {
  display: inline-block; /** set the divs side-by-side **/
  vertical-align: top;
  width: 100px;
  height: 40px;
  font-size: 12px; /** override font-size: 0 of #container, so that text will be visible again **/
  text-align: left; /** set text in the .child divs back to normal alignment **/
  border: 1px solid blue; /** for viewing purposes **/
  box-sizing: border-box;
}
</style>
<div id="container">
  <button id="status" class="status">Toggle</button>
  <button id="save" class="status" >Save</button>
  <button id="print" class="status">Print</button>
</div>

<?php

$sql = "SELECT * FROM draft_document1 WHERE section='washroom-agreement' AND grouptenantmasid=".$_GET['grouptenantmasid']." AND tenantmasid=".$tenamentvalue."  ORDER BY draftid DESC LIMIT 1";


$result1 = mysql_query($sql);
if($result1==true){
$rowprint = mysql_fetch_assoc($result1);

} else{
    
    echo mysql_error();
} 
if( !empty($rowprint))
{
    $pa =$rowprint['pa'];
    $pb =$rowprint['pb'];  
    $pc =$rowprint['pc'];   
    $pd =$rowprint['pd'];  
    $p0 =$rowprint['p0'];
    $p1 =$rowprint['p1'];  
    $p2 =$rowprint['p2'];   
    $p3 =$rowprint['p3'];  
    $p4 =$rowprint['p4'];   
    $p4a=$rowprint['p4a'];
    $p5 =$rowprint['p5'];   
    $p6 =$rowprint['p6']; 
    $p7 =$rowprint['p7'];
    $p8 =$rowprint['p8']; 
    $p9 =$rowprint['p9']; 
    $p10 =$rowprint['p10']; 
    
echo '<form>
   <textarea name="pa" class="jqte-test">'.$pa.'</textarea>
   <textarea  name="pb" class="jqte-test">'.$pb.'</textarea>
   <textarea  name="pc" class="jqte-test">'.$pc.'</textarea>
   <textarea  name="pd" class="jqte-test">'.$pd.'</textarea>
   <textarea  name="p0" class="jqte-test">'.$p0.'</textarea>
   <textarea  name="p1" class="jqte-test">'.$p1.'</textarea>
    <textarea  name="p2" class="jqte-test">'.$p2.'</textarea>
   <textarea  name="p3" class="jqte-test">'.$p3.'</textarea>
   <textarea  name="p4" class="jqte-test">'.$p4.'</textarea>
   <textarea  name="p4a" class="jqte-test">'.$p4a.'</textarea>
   <textarea  name="p5" class="jqte-test">'.$p5.'</textarea>
   <textarea  name="p6" class="jqte-test">'.$p6.'</textarea>
   <textarea  name="p7" class="jqte-test">'.$p7.'</textarea>
   <textarea  name="p8" class="jqte-test">'.$p8.'</textarea>
   <textarea  name="p9" class="jqte-test">'.$p9.'</textarea>
   <textarea  name="p10" class="jqte-test">'.$p10.'</textarea>
</form>';
}else{
 echo '<form>
   <textarea name="pa" class="jqte-test">'.$pa.'</textarea>
   <textarea  name="pb" class="jqte-test">'.$pb.'</textarea>
   <textarea  name="pc" class="jqte-test">'.$pc.'</textarea>
   <textarea  name="pd" class="jqte-test">'.$pd.'</textarea>
   <textarea  name="p0" class="jqte-test">'.$p0.'</textarea>
   <textarea  name="p1" class="jqte-test">'.$p1.'</textarea>
    <textarea  name="p2" class="jqte-test">'.$p2.'</textarea>
   <textarea  name="p3" class="jqte-test">'.$p3.'</textarea>
   <textarea  name="p4" class="jqte-test">'.$p4.'</textarea>
   <textarea  name="p4a" class="jqte-test">'.$p4a.'</textarea>
   <textarea  name="p5" class="jqte-test">'.$p5.'</textarea>
   <textarea  name="p6" class="jqte-test">'.$p6.'</textarea>
   <textarea  name="p7" class="jqte-test">'.$p7.'</textarea>
   <textarea  name="p8" class="jqte-test">'.$p8.'</textarea>
   <textarea  name="p9" class="jqte-test">'.$p9.'</textarea>
   <textarea  name="p10" class="jqte-test">'.$p10.'</textarea>
</form>';   
    

}

?>
<!--<form>
   <textarea name="pa" class="jqte-test"><?php echo $pa; ?></textarea>
   <textarea  name="pb" class="jqte-test"><?php echo $pb; ?></textarea>
   <textarea  name="pc" class="jqte-test"><?php echo $pc; ?></textarea>
   <textarea  name="pd" class="jqte-test"><?php echo $pd; ?></textarea>
   <textarea  name="p0" class="jqte-test"><?php echo $p0; ?></textarea>
   <textarea  name="p1" class="jqte-test"><?php echo $p1; ?></textarea>
    <textarea  name="p2" class="jqte-test"><?php echo $p2; ?></textarea>
   <textarea  name="p3" class="jqte-test"><?php echo $p3; ?></textarea>
   <textarea  name="p4" class="jqte-test"><?php echo $p4; ?></textarea>
   <textarea  name="p4a" class="jqte-test"><?php echo $p4a; ?></textarea>
   <textarea  name="p5" class="jqte-test"><?php echo $p5; ?></textarea>
   <textarea  name="p6" class="jqte-test"><?php echo $p6; ?></textarea>
   <textarea  name="p7" class="jqte-test"><?php echo $p7; ?></textarea>
   <textarea  name="p8" class="jqte-test"><?php echo $p8; ?></textarea>
   <textarea  name="p9" class="jqte-test"><?php echo $p9; ?></textarea>
   <textarea  name="p10" class="jqte-test"><?php echo $p10; ?></textarea>


</form>-->



</body>
</html>