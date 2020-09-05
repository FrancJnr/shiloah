<?php
ob_start(); 
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasidz=$_GET['grouptenantmasid'];
$tenantmasidz=$_GET['tenantmasid'];

try
{
$sql = "SELECT * FROM draft_document1 WHERE section='bank-agreement' AND grouptenantmasid=".$grouptenantmasidz." AND tenantmasid=".$tenantmasidz."  ORDER BY draftid DESC LIMIT 1";


$result1 = mysql_query($sql);
if($result1==true){
$rowprint = mysql_fetch_assoc($result1);

} else{
    
    echo mysql_error();
}    

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
						<td width='35%'>Mega Plaza Block 'B' 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
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
						<td width='35%'>Mega Plaza Block 'B' 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
					$buildingmunicipaladd = "WOODVALE GROVE, WESTLANDS, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
					$rowMunicipalAddress= "";
				}else if(strtoupper($buildingname) == "KATANGI"){
					$headerContent = "
					<tr><td colspan='3' align='center'><h1><font size=6>KATANGI DEVELOPERS LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.com</td>
						<td width='35%'>Mega Plaza Block 'B' 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
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
						<td width='35%'>Mega Plaza Block 'B' 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
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
                
		$row1="b)	To bear and pay all rates taxes and other charges of every nature and kind which now are or may hereafter be assessed or
			imposed on the said premises or any part thereof or on the Lessor or the Lessee in respect thereof  or by the Government of
			Kenya or any Municipal Township Local or other Authority the Head rent payable under the Valuation for Rating Act (Chapter 266) and the
			Rating Act (Chapter 267) or any Act or Acts amending or replacing the same only excepted PROVIDED ALWAYS that if in respect of any year of the said
			term the rate or rates payable under the said Acts or either of them shall be increased beyond that or those payable in respect of the year
			<strong>".convert_number(date('Y'))."<strong> the Lessee will forthwith on demand pay to the Lessor a proportionate share of such increase;";
		$row2="";
		$row3="";
		$row4="";

	}// end if length check
}// end for each

    $leasename = $tenantname;    
    if($tradingname !="")    
        $leasename  .= " (TA) ".$tenantname;
        
    $div0="<span id='span'>Print Preview Simple Agreement for  <font color='blue'><u>$tenantname</u></font>&nbsp;
            <button type='button' id='btnPreview' name='0'>Save & Print</button></span>
            <p class='printable'><table width='100%' border=0><tbody><tr><td>";
    $div1="";
    
   

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
           $this->SetFont('dejavusans', 'I', 8);
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
    
    //CONTENTS
    $pa=$rowprint['pa'];
    $pdf->AddPage();	    
    $pdf->SetFont('dejavusans','B',23);
    $pdf->writeHTML($pa, true, false, true, false, '');
    $pdf->ln(0.2);    
    $pdf->SetFont('dejavusans','',8.5);
    $pb=$rowprint['pb'];
    $pdf->writeHTML($pb, true, false, true, false, '');
    $pdf->ln(7);    
    $pdf->SetFont('dejavusans','',9.5);
    $pdf->writeHTML('<strong>'.Date("F d, Y").'</strong>', true, false, true, false, '');
    $pdf->ln(7);    
 
    $pdf->SetFont('dejavusans','BU',15);
    $pc=$rowprint['pc'];
    $pdf->Cell(0,2,$pc,0,0,'C');
    $pdf->ln();    
    $pd=$rowprint['pd'];
    $pdf->writeHTML($pd, true, false, true, false, '');
    $pdf->ln();
    $pdf->SetFont('dejavusans','',9.5);
    $p1=$rowprint['p1'];
    $pdf->writeHTMLCell(0, 0, '', '', $p1, 0, 1, false, true, 'J', false);
    $pdf->ln(1);  
    $p2=$rowprint['p2'];
    $pdf->writeHTML($p2, true, false, true, false, '');
    $pdf->ln(1);
    $p3=$rowprint['p3'];
    $pdf->writeHTMLCell(0, 0, '', '', $p3, 0, 1, false, true, 'J', false); 

    if($companyname =="SHILOAH INVESTMENTS LTD")
    {
            $pdf->SetXY(10, 263);
            $pdf->writeHTML("<hr>", true, false, true, false, '');
            $pdf->SetXY(15, 264);
            $pdf->Image('../images/mp_logo.jpg', '', '', 12, 12, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetXY(95, 264);
            $pdf->Image('../images/mc_logo.jpg', '', '', 12, 12, '', '', '', false, 200, '', false, false, 0, false, false, false);
            $pdf->SetXY(180, 264);
            $pdf->Image('../images/mm_logo.jpg', '', '', 12, 12, '', '', '', false, 300, '', false, false, 0, false, false, false);
    }
    else
    {
        $pdf->SetXY(10, 269);
        $pdf->writeHTML("<hr>", true, false, true, false, '');
    }
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(true);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $p4=$rowprint['p4'];
    $p4a=$rowprint['p4a'];
    $pdf->setHtmlHeader($p4);
    $pdf->setHtmlFooter($p4a);
    $pdf->AddPage();
    $pdf->SetFont('dejavusans','B',9);
    $p4b="NOW THIS AGREEMENT WITNESSETH";
    $pdf->Cell(0,2,$p4b,0,0,'C');
    $pdf->ln();   
    $pdf->SetFont('dejavusans','',8.8);
    $p5=$rowprint['p5'];
    $pdf->writeHTMLCell(0, 0, '', '', $p5, 0, 1, false, true, 'J', false);
    $pdf->ln(1);
    $pdf->AddPage();
    
    $pdf->SetFont('dejavusans','B',9);
    $p5a="SCHEDULE – I";
    $pdf->Cell(0,2,$p5a,0,0,'C');
    $pdf->ln(); 
    $pdf->SetFont('dejavusans','BU',8.8);
    $p5a ="SCOPE OF WORK FOR SPECIALIZED HOUSE KEEPING TO BE ENSURED BY THE CONTRACTOR";
    $pdf->Cell(0,2,$p5a,0,0,'C');
    $pdf->ln();  
    
    $pdf->SetFont('dejavusans','',8.8);
    $p6=$rowprint['p6'];
    $pdf->writeHTMLCell(0, 0, '', '', $p6, 0, 1, false, true, 'J', false);
    $pdf->ln(1);
    
    $pdf->AddPage();
    $pdf->SetFont('dejavusans','B',9);
    $p5a="SCHEDULE – II";
    $pdf->Cell(0,2,$p5a,0,0,'C');
    $pdf->ln(); 
    
    $pdf->SetFont('dejavusans','',8.8);
    $p7=$rowprint['p7'];
    $pdf->writeHTMLCell(0, 0, '', '', $p7, 0, 1, false, true, 'J', false);
    $pdf->ln(1);
    
    $pdf->AddPage();
    $pdf->SetFont('dejavusans','B',9);
    $p5a="SCHEDULE – III";
    $pdf->Cell(0,2,$p5a,0,0,'C');
    $pdf->ln(); 
    
    $pdf->SetFont('dejavusans','',8.8);
    $p8=$rowprint['p8'];
    $pdf->writeHTMLCell(0, 0, '', '', $p8, 0, 1, false, true, 'J', false);
    $pdf->ln(1);
    
    $pdf->SetFont('dejavusans','',8.8);
    $p9=$rowprint['p9'];
    $pdf->writeHTMLCell(0, 0, '', '', $p9, 0, 1, false, true, 'J', false);
    $pdf->ln(1);
    
    $pdf->AddPage();
    $pdf->SetFont('dejavusans','B',9);
    $p5a="SCHEDULE – IV";
    $pdf->Cell(0,2,$p5a,0,0,'C');
    $pdf->ln(); 
//    $p9b="<strong>TERMS OF PAYMENT</strong><br><br><span  style='line-height:1.6px;text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;'>
//For the first One (1) month, (starting ".date("Y-m-d")." to ".date("Y-m-d",strtotime("+29 days")).") 
//the Contractor shall not remit any payment to the company for the services at <strong>".  strtoupper($companyname)."</strong>
//washrooms however at the end of One (1) month survey period, 
//the contractor shall pay the Company Kshs. 10,000/= + VAT per month.</span>";
//    $pdf->SetFont('dejavusans','',8.8);
//    
//    $pdf->writeHTMLCell(0, 0, '', '', $p9b, 0, 1, false, true, 'J', false);
//    $pdf->ln(1);
//    $pdf->AddPage();
//    $p10=$rowprint['p10'];   
//    $pdf->writeHTMLCell(0,0, $p10."\n", 0, 'J');
//    $pdf->ln(1);
//    $pdf->SetFont('dejavusans','',9);
//    
    if ($tradingname == "")
    {
        $p10=$rowprint['p10'];  
        
    }
    else
    {
        $p10=$rowprint['p10'];     
    }
    $pdf->SetFont('dejavusans','',9);
    $pdf->writeHTMLCell(0, 0, '', '', $p10, 0, 1, false, true, 'J', false);
    $pdf->ln(1);
    if($renewalfromid<=0)
        $filename = $leasename." - (".$shopcode.")";
    else
        $filename = $leasename." - (".$shopcode."-RENEWED)";
    $filename = clean($filename);
    $createdby = $_SESSION['myusername'];
    $createddatetime = $datetime;

    
    $sql = "select * from rpt_bank_agreement where grouptenantmasid =$groupmasid";
    $result = mysql_query($sql);
    $rowcount = mysql_num_rows($result);
    
    if($rowcount <=0)
    {
        $pdf->Output("../../pms_docs/banktemplate/".$filename.".pdf","F");
        $insert = "insert into rpt_bank_agreement (grouptenantmasid,createdby,createddatetime) values ($groupmasid,'$createdby','$createddatetime')";
        mysql_query($insert);
    }
    
    $pdf->Output($leasename, 'I');
    exit;  
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