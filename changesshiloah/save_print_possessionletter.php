    
<?php
include('../config.php');
require_once '../PHPWord.php';
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasid=$_GET['grouptenantmasid'];
              
$sql = "select tenantmasid from group_tenant_mas where grouptenantmasid=".$grouptenantmasid;
     $result = mysql_query($sql);
     if($result != null)
     {
             while($row = mysql_fetch_assoc($result))
             {
              $tenantmasid=$row['tenantmasid'];       	
             
              $_SESSION['tenantmasid']=$tenantmasid;
             }
     }
                   
     //$tenantmasid=  $grouptenantmasid;             
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
          var url = "print_possessionletter_pdf.php?grouptenantmasid="+<?php echo $grouptenantmasid;?>+"&tenantmasid="+<?php echo  $_SESSION['tenantmasid'];?>;
        
         window.open(url,  "windowOpenTab", "width=800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
         
         return false;
        });

     function ajaxPOSTTest2() {
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
        
    
      
        var url = "print_possessionletter_pdf.php?grouptenantmasid="+<?php echo $grouptenantmasid;?>+"&tenantmasid="+<?php echo  $_SESSION['tenantmasid'];?>;
        var params =$("form").serialize();
        ajaxPOSTTestRequest.open("POST", url, true);
        ajaxPOSTTestRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajaxPOSTTestRequest.send(params);
    }  	
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
        
    
      
        var url = "save_draft_document.php?action=Save&section=possessionletter&grouptenantmasid="+<?php echo $grouptenantmasid;?>+"&tenantmasid="+<?php echo  $_SESSION['tenantmasid'];?>;
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
$shopsizeid="";$floor="";
$totalDeposit=0;
$tenantmasid=0;
$leasedeposit=0;
$shops="";
//$groupmasid=0;
$groupmasid = $grouptenantmasid;


	
    foreach ($_GET as $k=>$v) {	
//	$len =  strlen(trim($k));
//	if($len >= 11)
//	{		
//		if($k  =="grouptenantmasid")
//		{
//		         $kb=$_SESSION['tenantmasid'];
//			$sql = "SELECT * FROM  `group_tenant_mas` WHERE  `grouptenantmasid` ='$kb';";
//			$result = mysql_query($sql);
//			while ($row = mysql_fetch_assoc($result))
//			{
//				$grptenantmasid  = $row["tenantmasid"];
//			}
				//$custom = array('msg'=> $grptenantmasid.$k.$v ,'s'=>'error');
				//	$response_array[] = $custom;
				//	echo '{"error":'.json_encode($response_array).'}';
				//	exit;
//		}
                
//   		$k = str_split($k,11);
//		if($k[0] =="tenantmasid")
//		{		
			$group++;
			$sqlArray.= $cnt."--> KEY: tenantmasid; VALUE: ".$tenantmasid."--->$group<BR>";
			//$tenantmasid[] = $v;
			$tenantmasid = $_SESSION['tenantmasid'];				
			$cnt++;			
			////////create company view
			$viewSql = "create view view_offerletter_company as SELECT * ,\n"
			    . "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
			    . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
			    . "FROM mas_company where companymasid =". $companymasid;
			$result = mysql_query($viewSql);
			
			$sqlv1= "SELECT a.*, b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,b.fulldesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size,
				DATE_FORMAT( a.doo, '%d-%m-%Y' ) as 'tenantdoo',
				DATE_FORMAT( a.doc, '%d-%m-%Y' ) as 'tenantdoc' 
				FROM mas_tenant a
				INNER JOIN mas_age b ON b.agemasid = a.agemasidlt
				INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc
				INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid
				INNER JOIN mas_block d ON d.blockmasid = a.blockmasid
				INNER JOIN mas_floor e ON e.floormasid = a.floormasid
				INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid				
				WHERE a.tenantmasid =$tenantmasid and  a.companymasid=$companymasid and a.active='1';";			
			$result = mysql_query($sqlv1);
			if($result !=null)
			{
				if(mysql_num_rows($result)<=0)
				{
				$sqlv1= "SELECT a.*, b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,b.fulldesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size,
					DATE_FORMAT( a.doo, '%d-%m-%Y' ) as 'tenantdoo',
					DATE_FORMAT( a.doc, '%d-%m-%Y' ) as 'tenantdoc' 
					FROM rec_tenant a
					INNER JOIN mas_age b ON b.agemasid = a.agemasidlt
					INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc
					INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid
					INNER JOIN mas_block d ON d.blockmasid = a.blockmasid
					INNER JOIN mas_floor e ON e.floormasid = a.floormasid
					INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid				
					WHERE a.tenantmasid = $tenantmasid and  a.companymasid=$companymasid and a.active='1';";			
				}
			}
			//create tenant view
			$viewSql =  "create view view_offerletter_tenant as ".$sqlv1;
			$result = mysql_query($viewSql);
			
			$sqlv2= "select a.* from mas_tenant_cp a inner join mas_tenant b on b.tenantmasid = a.tenantmasid "
					. " WHERE a.tenantmasid =".$tenantmasid
					. " and  b.companymasid=$companymasid and b.active='1'"
					. " and  a.documentname='1'";
			$result = mysql_query($sqlv2);
			if($result !=null)
			{
				if(mysql_num_rows($result)<=0)
				{
					$sqlv2= "select a.* from rec_tenant_cp a inner join rec_tenant b on b.tenantmasid = a.tenantmasid "
						. " WHERE a.tenantmasid =".$tenantmasid					
						. " and  b.companymasid=$companymasid and b.active='1'"
						. " and  a.documentname='1'";
				}
			}
			//create tenant_contact person view
			$viewSql =  "create view view_tenant_cp as ".$sqlv2;
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
                                $leasename = $row['salutation']." ".strtoupper($row['leasename']);
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
				
				//preg_match('/\d+/', $poboxno, $number);  // select only mo's	
				//$poboxno = $number[0];
				
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
				//$shopid .= $floordescription.",Shop No:".$shopcode;
				$shopid = $floordescription;
                                $floor .= "(".$floordescription.")";
				$shopsizeid .= "(".$floordescription.",Shop No:".$shopcode.",Size:".$row['size']." sqrft)";
				$tenantRent .= "<strong><u>".$buildingname.",".$floordescription.",Size:".$row['size']." sqrft</u></strong><br><br>";
				$tenantSc .= "<strong><u>".$buildingname.",".$floordescription.",Size:".$row['size']." sqrft</u></strong><br><br>";
				$s ="select offerlettermasid from trans_offerletter where tenantmasid =".$row['tenantmasid'];
				$r =mysql_query($s);
				$c = mysql_num_rows($r);
				if ($c > 0)
				{
					while($rk =mysql_fetch_assoc($r))
					{
						$s ="select deposittotal from trans_offerletter_deposit where offerlettermasid =".$rk['offerlettermasid'];
						$r1 =mysql_query($s);
						while($rk1 =mysql_fetch_assoc($r1))
						{
							if($rk1['deposittotal'] >0)
							{
								$tenantDeposit .= '<strong><u>'.$buildingname.','.$floordescription.',Size:'.$row['size'].' sqrft</u></strong><br><br>
										<table cellpadding="3" cellspacing="0" border="1" width="100%"><tr style="font-weight:bold;" align="center">										
										<th>Description</th>
										<th>Amount (KSH)</th>
										</tr>';
							}
						}
					}
				}
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
                                $fulldescofterm = $row['fulldesc'];
				$latefeeinterest = $row['latefeeinterest'];
				
				$rentcycle = strtolower($row["rentcycle"]);
				$shops .= "(".$floordescription .",".$shopcode.",MEASURING ". $size ." SQRFT) ,";
                                $renewalfromid = $row['renewalfromid'];
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
			}
			$tenantcptable ='<table>
					<tr>
						<td>To:&nbsp;<br>$tenantcpname</td>
					</tr>
					<tr>
						<td><strong>$leasename</strong></td>
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
					</table>';
			//load tenant rent details
			$tenantRent .='<table cellpadding="3" cellspacing="0" border="1" width="100%"><tr align="center" style="font-weight: bold">
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Amount (KSH)</th>
                                        <th>Period</th>
                                        </tr>';
			$sql = "SELECT * FROM view_offerletter_rent";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$amtrent = $row['amount'];
				$offerlettermasid = $row['offerlettermasid'];
				$tenantRent .='<tr align="center">
                                                <td>'.$row["rent_fromdate"].'</td>
                                                <td>'.$row["rent_todate"].'</td>';
				if($rentcycle == "per quarter")
				{
					$amtrent = $amtrent /3;
				}
				else if ($rentcycle == "per half")
				{
					$amtrent = $amtrent /6;
				}
				else if ($rentcycle == "per year")
				{
					$amtrent = $amtrent /12;
				}	
				$tenantRent .='<td>'.number_format($amtrent,0, ".", ",").'</td><td>Per Month</td></tr>';
			}
			$tenantRent .='</table><br><br>';
			//load tenant scrvice charge details
			$tenantSc .='<table cellpadding="3" cellspacing="0" border="1" width="100%"><tr align="center" style="font-weight: bold">
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Amount (KSH)</th>
                                        <th>Period</th>
                                        </tr>';
			$sql = "SELECT * FROM view_offerletter_sc";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$amtsc = $row["amount"];
				$tenantSc .='<tr align="center">
						<td>'.$row["sc_fromdate"].'</td>
						<td>'.$row["sc_todate"].'</td>';
				if($rentcycle == "per quarter")
				{
					$amtsc = $amtsc /3;
				}
				else if ($rentcycle == "per half")
				{
					$amtsc = $amtsc /6;
				}
				else if ($rentcycle == "per year")
				{
					$amtsc = $amtsc /12;
				}	
				$tenantSc .= '<td>'.number_format($amtsc,0, ".", ",").'</td>
						<td>Per Month</td>
						</tr>';
						$sc =$row["amount"];
			}
			$tenantSc .='</table><br><br>';
			//load tenant deposit details
			//$tenantDeposit .='<table cellpadding="3" cellspacing="0" border="1" width="100%">
			//			<tr align="center" style="font-weight:bold;">						
			//			<th>Description</th>
			//			<th>Amount (KSH)</th>
			//			</thead></tr>';
			$sql = "SELECT * FROM view_offerletter_deposit";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$n = 1;
				if($row['depositmonthrent'] >0)
				{
					$tenantDeposit .='<tr>'						
						.'<td>'.$row["depositmonthrent"].' Months Security Deposit for rent </td>'
						.'<td align="right">'.number_format($row['rentdeposit'], 0, '.', ',').'</td>'			
						.'</tr>';$n++;
						$totalDeposit +=$row['rentdeposit'];
				}
				if($row['depositmonthsc'] > 0)
				{
					$tenantDeposit .='<tr>'						
						.'<td>'.$row["depositmonthsc"].' Months security deposit Scr.Chrg</td>'
						.'<td align="right">'.number_format($row['scdeposit'], 0, '.', ',').'</td>'
						.'</tr>';$n++;
						$totalDeposit +=$row['scdeposit'];
				}
				if($row['advancemonthrent'] > 0)
				{
					$tenantDeposit .='<tr>'
						.'<td>'.$row["advancemonthrent"].' Month Advance rent with VAT</td>'
						.'<td align="right">'.number_format($row['rentwithvat'], 0, '.', ',').'</td>'
						.'</tr>';$n++;
						$totalDeposit +=$row['rentwithvat'];
				}
				if($row['advancemonthsc'] > 0)
				{
					$tenantDeposit .='<tr>'						
						.'<td>'.$row['advancemonthsc'].' Month Advance Scr.Chrg with VAT</td>'
						.'<td align="right">'.number_format($row['scwithvat'], 0, '.', ',').'</td>'
						.'</tr>';$n++;
						$totalDeposit +=$row['scwithvat'];
				}
				if($row['leegalfees'] > 0)
				{
					$tenantDeposit .='<tr>'						
						.'<td>Legal Fees with VAT</td>'
						.'<td align="right">'.number_format($row['leegalfees'], 0, '.', ',').'</td>'
						.'</tr>';$n++;
						$totalDeposit +=$row['leegalfees'];
				}
				if($row['stampduty'] > 0)
				{
					$tenantDeposit .='<tr>'						
						.'<td>Stamp Duty</td>'
						.'<td align="right">'.number_format($row['stampduty'], 0, '.', ',').'</td>'
						.'</tr>';$n++;
						$totalDeposit +=$row['stampduty'];
				}
				if($row['registrationfees'] > 0)
				{
					$tenantDeposit .='<tr>'						
						.'<td>Registration Fees</td>'
						.'<td align="right">'.number_format($row['registrationfees'], 0, '.', ',').'</td>'
						.'</tr>';$n++;
						$totalDeposit +=$row['registrationfees'];
				}
				if($row['depositTotal'] > 0)
				{
					$tenantDeposit .='<tr>'
						.'<td>Total</td>'
						.'<td align="right">Kshs.<strong>'.number_format($totalDeposit, 0, '.', ',').'</strong></td>'
						.'</tr>';
				}
				//$totalDeposit +=$row['depositTotal'];
				$n = 0;
			}
			$tenantDeposit .='</table><br>';
			
			if($tradingname ==""){
				$leaseclass = " incorporated in the Republic of Kenya with a limited liability";
				$tradingas="";
				$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
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
				$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
				$tradingtitle="- T/A ".$tradingname;
			}
			
			$headerContent = "<tr>    
						<td width='40%'><img src='../images/mp_logo.jpg' height='50px'></td>
						<td width='35%'><img src='../images/mc_logo.jpg' height='50px'></td>
						<td align='right'><img src='../images/mm_logo.jpg' height='50px'></td>  
					</tr>
					<tr><td colspan='3' align='center'><h1><font size=6>SHILOAH INVESTMENTS LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
			
			$buildingmunicipaladd="";
			$buildingcouncil="";
				if(strtoupper($buildingname) == "MEGA PLAZA"){
					$buildingmunicipaladd = "Kisumu Municipality Block 7/380";
					$rowMunicipalAddress= "Municipality Block 7/380";
					$buildingcouncil = "Kisumu Municipal Council";
				}
				else if(strtoupper($buildingname) == "MEGA CITY"){
					$buildingmunicipaladd = "Kisumu Municipality Block 9/134 &amp; 9/135";
					$rowMunicipalAddress= "Municipality Block 9/134 &amp; 9/135";
					$buildingcouncil = "Kisumu Municipal Council";
				}
				else if(strtoupper($buildingname) == "MEGA MALL"){
					$buildingmunicipaladd = "Kakamega Municipality Block 111/97";
					$rowMunicipalAddress= "Municipality Block 111/97";
					$buildingcouncil = "Kakamega Municipal Council";
				}
				else if(strtoupper($buildingname) == "RELAINCE CENTRE"){
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
					$buildingmunicipaladd = "WOODVALE GROVE, WESTLANDS, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
					$rowMunicipalAddress= "";
					$buildingcouncil = "NAIROBI City Council";
				}else if(strtoupper($buildingname) == "KATANGI"){
					$headerContent = "
					<tr><td colspan='3' align='center'><h1><font size=6>KATANGI DEVELOPERS LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
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
					$buildingmunicipaladd = strtoupper($buildingmunicipaladd);
				if($tradingname ==""){
					$leaseclass = " incorporated in the Republic of Kenya with a limited liability";
					$tradingas="";
					$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
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
					$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
					$tradingtitle="- T/A ".$tradingname;
				}
				
				$leasebreak ="<br><br><br>";
//		}// end if chicking tenantmasid
		

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
		$row1="b)	To bear and pay all rates taxes and other charges of every nature and kind which now are or may hereafter be assessed or
			imposed on the said premises or any part thereof or on the Lessor or the Lessee in respect thereof  or by the Government of
			Kenya or any Municipal Township Local or other Authority the Head rent payable under the Valuation for Rating Act (Chapter 266) and the
			Rating Act (Chapter 267) or any Act or Acts amending or replacing the same only excepted PROVIDED ALWAYS that if in respect of any year of the said
			term the rate or rates payable under the said Acts or either of them shall be increased beyond that or those payable in respect of the year
			<strong>".convert_number(date('Y'))."<strong> the Lessee will forthwith on demand pay to the Lessor a proportionate share of such increase;";
		$row2="";
		$row3="";
		$row4="";
		
//	}// end if length check
}// end for each

$shops = rtrim($shops,",");

 
$div0="<span id='span'>Print Preview Offerltter for  <font color='blue'>$leasename ($tenantcode)</font>&nbsp;
	<button type='button' id='btnPreview' name='0'>Save & Print</button></span>
        <p class='printable' style='fonr-face='verdana;color=red;'><table width='100%' border=0><tbody><tr><td>";
$div1="";
	
$sql = "select * from rpt_offerletter where grouptenantmasid =$groupmasid";
$result = mysql_query($sql);
$rowcount = mysql_num_rows($result);

if($rowcount >0)
{
	$sql = "delete * from rpt_offerletter where grouptenantmasid =$groupmasid";
	$result = mysql_query($sql);
	$rowcount =0;
}

    $address ='<table cellpadding="2" >		
		<tr height="70px">    
			<td width="35%">P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.co.ke</td>
			<td width="35%">Mega Plaza Block "A" 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
			<td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
		</tr>	
		</table>';
    $tenantcontactno = $tenantcpmobile.' / '. $tenantcplandline;
    $tenantcontactno = ltrim($tenantcontactno,' /');
    $tenantcontactno = rtrim($tenantcontactno,'/ ');
    if($tenantcontactno !="")
    {
	$tenantcity .=','."<br>&nbsp;&nbsp;&nbsp;&nbsp;Phone    :".$tenantcontactno.".";
    }
    else
    {
	$tenantcity .='.';
    }
    
    $tenantpobox = $poboxno.' - '. $pincode;
    $tenantpobox = ltrim($tenantpobox,' -');
    $tenantpobox = rtrim($tenantpobox,'- ');
    
    if($tradingname !="")
   // $tname = $leasename." T/A ".$tradingname;
        $tname="THE DIRECTOR,<br>".strtoupper ($tradingname);
    else
    //$tname = $leasename;
        $tname=strtoupper ($leasename);
    
    $tenantdetails ='<table width="100%">		
		<tr style="line-height: 1.8px;">
			<td valign="top"><font size="9.5px"><b>TO ATTN. '.$tname.'</b></font>,						
				<br><b>P.O BOX NO:'.$tenantpobox.',</b>
				<br><b>'.$tenantcity.'</b>				
<!--				<br><br>Kind Attn    :'.$tenantcpname.'.-->
			</td>			
		</tr>
		</table>';
     $p0 ='<table width="100%" >
		<tr style="line-height: 1.8px;">		  
                    <td align="justify">Dear Sir/Madam,<br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                       <u><b><font size="11.5px">REF: LEASE OF PREMISES IN &nbsp;'.$buildingname.' , '.$buildingmunicipaladd.' , '.$shops.'.</font></b></u>
                       <br><br>&nbsp;&nbsp;&nbsp;&nbsp;As per our final negotiation and the terms and conditions stated in our letter of offer, 
                      we are handing over the possession of the above said property to you in good working condition with Electrical fittings.<br>
                      Please note that you will be responsible for the Electrical Fittings and glasses of the above said area from today.<br>
                      You are kindly requested to sign the letter and return one copy to us.<br><br>
                      <b>Thanking you</b><br><br>
                      Yours faithfuly,<br><br><br>
                      <strong>FOR: '.strtoupper($companyname).'</strong><br><br>
                       <u><strong>MARKETING MANAGER</strong></u><br>We hereby certify that we have received the possession of the above said property and verified the fittings and glasses and 
                        are in good and working condition.<br><br><b>______________________________________________________</b><br>
                        <strong>FOR: '.strtoupper($tradingname).'</strong>
                    </td>		
		</tr>
		</table>';
    $x = 1;
    

    $pa='<table><tr align="center" style="font-stretch: expanded;"><th>'.$companyname.'.</th></tr></table><hr>';

    $pb=$address;

    $pc='<strong>'.Date("F d, Y").'</strong>';

    $pd=$tenantdetails;

//    $p4a="<hr>";

	$x++;    

 
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
<form>
   
   <textarea name="pa" class="jqte-test"><?php echo $pa; ?></textarea>
   <textarea  name="pb" class="jqte-test"><?php echo $pb; ?></textarea>
   <textarea  name="pc" class="jqte-test"><?php echo $pc; ?></textarea>
   <textarea  name="pd" class="jqte-test"><?php echo $pd; ?></textarea>
   <textarea  name="p0" class="jqte-test"><?php echo $p0; ?></textarea>
   <textarea  name="p4a" class="jqte-test"><?php echo $leasename; ?></textarea>
   <textarea  name="p4" class="jqte-test"><?php echo $tenantcode; ?></textarea>
  
</form>

</body>
</html>