<?php
include('../config.php');
session_start();
try{
	
$companymasid = $_SESSION['mycompanymasid'];
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
			
			$companyname = $_SESSION['mycompany'];
			
			
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
				$tenantname = $row['leasename'];
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
				$shopid .= $floordescription.",Shop No:".$shopcode;
				$shopsizeid .= "(".$floordescription.",Shop No:".$shopcode.",Size:".$row['size']." sqrft)";
				$tenantRent .= "<strong><u>".$buildingname.",".$floordescription.",Shop No:".$shopcode.",Size:".$row['size']." sqrft</u></strong><br><br>";
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
			$tenantRent .="<table cellpadding='3' cellspacing='0' border='1' width='100%'><tr>"
				."<thead><th>From</th>"
			        ."<th>To</th>"
				."<th>Amount (KSH)</th>"
			        ."<th>Period</th>"
				."</thead></tr>";
			$sql = "SELECT * FROM view_offerletter_rent";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$offerlettermasid = $row['offerlettermasid'];
				$tenantRent .="<tr>"
						."<td>".$row['rent_fromdate']."</td>"
						."<td>".$row['rent_todate']."</td>"
						."<td align='right'>".number_format($row['amount'],0, '.', ',')."</td>"
						."<td>$rentcycle</td>"
						."</tr>";
			}
			$tenantRent .="</table><br>";
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


$div0="<span id='span'>Print Preview Simple Agreement for  <font color='blue'><u> M/s. $tenantname</u></font>&nbsp;
	<button type='button' id='btnPreview' name='0'>Save & Print</button></span>
	<p class='printable'><table width='100%' border=0><tbody><tr><td>";
$div1="";

$sql = "select * from rpt_simple_agreement where grouptenantmasid =$groupmasid";
$result = mysql_query($sql);
$rowcount = mysql_num_rows($result);

if ($rowcount == 0) // insert new
{
 $div1 ="<ul id='sortable1' class='connectedSortable'> <li class='ui-widget-content'>
		<table width='100%' border=0>
		    <tr>
                            <td valign='middle' align='center'>
                                    <br><br>
                                        <strong><u>OCCUPATION - LICENCE (SIMPLE) AGREEMENT</u></strong>
                                    <br><br>
                            </td>
                    </tr>
                    <tr>
                        <td valign='middle' align='justify' style='line-height: 2.5em;'>
                            We,<strong>$companyname</strong> a company incorporated in the Republic of
                            Kenya with a limited liability and of $companyaddress2 in the Republic of Kenya
                            (hereinafter called  'the Licensor' which expression shall where the context so admits or
                            requires include its successors and assigns)
                            <u>HEREBY GRANT THIS LICENCE</u> to <strong>".$tenantname." (ID NO. $tenantcpnid) of P.O BOX NO.$poboxno - $pincode,
                            $city </strong>in the Republic of Kenya (hereinafter called 'the Licensee'  which expression shall where the context
                            so admits or requires include his/her/their personal representatives and assigns) TO OCCUPY space for
                            $nob near the Trolleys (hereinafter called the premises) situated at <strong>$buildingname</strong> erected on
                            $buildingmunicipaladd (hereinafter known as the building) TO BE HELD by the Licensee
                            for the license period and amount as follows. (The Amount(KSH) in the below mentioned table hereinafter known
                            as the 'License Fee') is subject to VAT.<br><br>
                                 $tenantRent
                            <br>
                            The license fee in all cases is payable clear of all deductions by weekly in advance on first day of
                            each week without any deductions whatsoever except as authorized by any statutory enactment for the
                            time-being in force. The Licensor has acknowledged to carry forward a deposit of
                            <strong>KShs.".number_format($totalDeposit, 0, '.', ',')."/=</strong> to be
                            held by the Licensor during the license period free of interest as security for the payment of license
                            fee and undertaking repairs that may have to be carried out within the premises or the building and the
                            performance of all the Licensee's obligations and covenants herein contained. If the license fee or any
                            other monies payable by the Licensee under this lease shall not be paid on its due date the Licensee shall
                            pay to the Licensor penalty for late payment on the said monies to be calculated at $latefeeinterest% per month until payment
                            thereof in full <strong><u>SUBJECT HOWEVER</u></strong> to the following terms and conditions and covenants or part thereof:-
                        </td>
                    </tr>
                </table>
            </li>";
	$div1 .="<li class='ui-state-default'>
	             <table>
			<tr>			
			<td id='content1' valign='middle' align='justify' style='line-height: 2.5em'>
				1.     Either of the party to this agreement can terminate the said agreement:-<br>
				In the event of the Licensee willing to vacate the premises it shall deliver to the Licensor One (1) day's
				written notice or by payment of 1 day's license fee to the licensor in lieu of such notice;
				In the event of the Licensor willing to vacate the premises it shall deliver to the Licensee One (1) day's
				written notice or one (1) day's license fee to the licensor in lieu of such notice.
<br>
2.	The Licensee shall unconditionally vacate the premises forthwith upon the determination of this agreement or earlier as provided in this agreement.
<br>
3.	The Licensee to the intent that the obligations hereinafter set out may continue  throughout the continuance of the term hereby created FURTHER COVENANTS AND AGREES with the Licensor as follows:-     
<br>
a)	This agreement shall not be construed, impliedly or expressly, to convert the tenure or occupation of the premises or the said term or any balance thereof within the protection of the Landlord and Tenant (shops, hotels and catering Establishments) Act (Chapter 301) or any Act or Acts for the time being in force amending or replacing the same or any similar Act and shall not have the effect of creating a tenancy of the premises where the premises will be situated AND FURTHER this agreement shall be deemed at all times a simple license agreement between the licensor and the licensee solely for the occupation of the premises and at all times the interest and title to the premises shall solely be vested in the Licensor .
<br>
b)	To pay the license fee herein before reserved at the time and manner aforesaid and to pay all other sums as provided in this agreement;
<br>
c)	To operate strictly between the times of 9 AM TO 8 PM and the licensor shall have the sole discretion to suspend this license granted to the licensee at any time without any notice or without assigning any reasons thereof.  
<br>
d)	To keep the trolley and the building in a clean state at all times.
<br>
e)	To keep the exterior and interior of the premises including all its fittings clean and in good repair order and condition (fair wear and tear and all acts of God only excepted) also to make good any stoppage of or damage to premises caused or suffered by the Licensee or a member of its servants Licensee or visitors and at the expiration or soon determination of the term hereby granted peaceably and quietly yield up the premises to the Licensor in such state of repair order and condition as the same were at the commencement of the said term (excepting only as aforesaid) and with all locks and keys and fastenings complete;
<br>
f)	To make good any damage caused to the building <strong>($buildingname)</strong> where the premises shall be situated by the removal by the Licensee or
the Licensee's servants employees agents or others of any furniture goods or other articles into or out of the premises or to its fixtures
or resulting from fire explosion air conditioning or electrical short circuits flow or leakage or water or steam by bursting or leaking of
pipes or plumbing works or from any other cause of any other kind or nature whatsoever due to carelessness, omission, neglect, improper or
negligent conduct or other cause attributable to the Licensee the Licensee's servants employees agents visitors or Licensees;
<br>
h)	To use the premises solely for the purpose of <strong> $nob ONLY</strong> by the Licensee AND not to convert use or occupy or permit or
suffer to be used the premises or any part thereof into or for any other purpose or business whatsoever AND to use the same only for the
purpose hereby authorized and not to use the same for any illegal or immoral purposes and IT IS <u>HEREBY DECLARED AND AGREED</u> that upon breach
by the Licensee of the terms of this clause the Licensor may thereupon at any time repossess the premises and if the Licensor shall do so
the term hereby created shall determine absolutely.
<br>
i)	Not to assign, transfer, sublet, charge or otherwise part with the possession of the premises or any part thereof without the
written consent of the Licensor AND IT IS HEREBY EXPRESSLY AGREED AND DECLARED by and between the parties hereto that upon any breach by
the Licensee of this covenant and agreement it shall be lawful for the Licensor to reenter upon the premises without notice and thereupon
the said term shall determine absolutely.
<br>
j)	To comply forthwith in all respect with the provisions of every enactment (which expressions in this sub  clause includes every
Act of parliament now or hereinafter enacted and every instrument regulation and by law and every notice order or direction and
every license consent or permission made or given thereunder so far as the same shall effect the premises and to indemnify the Licensor in
respect of all such matters as aforesaid;
<br>
k)	To supply a copy to the Licensor of any notice or license consent or permission relating to the premises within seven (7) days of the receipt thereof by the Licensee;
<br>
l)	To perform and observe and also procure performance and observance by the Licensee's servants agents licensees and invitees of the rules and regulations (including but not limited to regulations as to the opening and closing of the entrance doors) as the Licensor may make from time to time for the management of the premises or of the building .The Licensee shall accept as final and binding the decision of the Licensor upon any matter arising out of such rules and regulations;
<br>
m)	Not to permit or suffer to be done in or upon the premises or any part of the building anything which would or might be or become a nuisance annoyance inconvenience or disturbance to any person whatsoever (including the tenants of the building and any other premises licensees) and to indemnify the Licensor against any costs charges and expenses incurred by the Licensor in abating such nuisance and execution of all such works as may be necessary for abating a nuisance or for remedying such nuisance;
<br>
n)	Not to permit or suffer to be done anything whereby any insurance of the building where the premises is situated may become void or voidable against loss by fire or damage or whereby the rate of premium for any such insurance may be increased;
<br>
o)	Not to permit any internal combustion or fires to be burned in the premises;
<br>
p)	That no fore court staircase lift or passageway leading to the building shall be damaged or obstructed or used in such manner as to cause in the opinion of the Licensor any nuisance damage or annoyance;
<br>
q)	Where applicable no goods or furniture or other equipment shall be carried in the lifts (if any) of the building unless previous arrangements have been made with the caretaker of the building in respect of such carriage AND not to allow or suffer or permit in any circumstance the total weight of any one load in any such passenger lift or lifts to exceed the margin of the safety prescribed therefor AND ALSO to observe at all times the rules which may be made by the Licensor from time to time for the operation of such lift or lifts;
<br>
r)	Not to hold or permit or suffer to be held any sale by auction in the premises or the building;
<br>
s)	That except with the previous consent in writing of the Licensor and in accordance with drawings and specifications approved by the Licensor at the cost of the Licensee no alteration or addition whatsoever shall be made in or to the premises PROVIDED ALWAYS that the Licensor may as a condition of giving any consent require the Licensee to enter into such covenants with the Licensor as the Licensor shall reasonably require in regard to the execution of any alteration or addition to the premises and the reinstatement thereof at the determination of the term hereby granted or otherwise;
<br>
t)      No payments by the Licensee howsoever made referable or on account of a period subsequent to the determination of the term hereby created (whether by effluxion of time or otherwise) shall constitute deemed or be construed as payment or acceptance of license fee and the same shall not have the effect of creating an occupation license of the premises in favour of the Licensee except where an agreement in favour of the Licensee is expressly and in writing created and entered into by the Licensor;
<br>
u)	To give immediate notice to the Licensor if the premises be or become infested with vermin and to cause the same at the Licensee's own expense to be exterminated from time to time to the satisfaction of the Licensor and to employ such exterminators and such exterminating company or companies as shall be approved by the Licensor;
<br>
<strong><u>PROVIDED ALWAYS AND IT IS HEREBY AGREED:-</u></strong>
<br>
(a)	If the <strong>licensee fee hereby reserved</strong> or any part thereof shall at any time be unpaid for Seven (7) days after becoming payable (whether lawfully demanded or not) or if any covenant on the part of the Licensee herein contained shall not be performed and observed or if the Licensee (if being a company) in whom for the time being the term hereby created shall be vested to go into liquidation whether compulsory or voluntary or if the Licensee being a person or persons in whom for the time being the term hereby created be vested shall become bankrupt or enter into any agreements with his or her creditors for liquidation of  his ,her ,their debts by composition or otherwise or suffer any distress or process of execution to be levied upon his or her goods then in any of the said case
<strong> it shall be lawful for the Licensor</strong> at any time thereafter to  <strong>repossess
and trolley</strong> or any part thereof  in the name of the whole by any action or proceeding or by force or otherwise and to enjoy them in their former state and thereupon this agreement shall  absolutely determine but without prejudice to the right of action of the Licensor in respect of any antecedent breach of any of the agreements on the part of the Licensee herein contained AND the Licensee hereby waives any rights to notice or re-entry or forfeiture under any law for the time being in force PROVIDED:-
<br>
i).	In the event of such a determination of the term hereby created the Licensee shall remain liable to the Licensor for payment of
all the licensee fees and/or any other sums payable under the terms and conditions of this agreement and for the entire period of this
agreement AND;
<br>
ii).	The licensor shall be entitled to retain the premises together with all contents therein contained as a lien till payment of all sums due under this agreement by the licensee to the licensor AND;
<br>
iii).      If any sum due and payable by the licensee to the licensor shall remain unpaid for a period of seven (7) days from the date of termination of this agreement as provided herein, the licensor shall in its unfettered and sole discretion be entitled to sell the contents which were retained by the licensor by public auction or private treaty for recovery of any sums due under this agreement from the licensee;
<br>
(b)	The Licensor, the owners and builders of the building shall not be liable for any loss damage or injury to the Licensee, the family, employees, servants, agents, visitors or licensees of the Licensee or the property of any such persons caused by :-
<br>
i)      any defects in the premises or in the building or in any defect or electric wiring or of the installation thereof gas pipes stream pipes or from broken stairs or from bursting leaking or running over of any tank tub washstand water closet or waste pipe drains or any other pipe or tank in upon or about the building or the premises nor from the escape of steam or hot water from any boiler or radiator;
<br>
ii)	any defective or negligent working constructions or maintenance of the lifts(if any ) or the lighting or equipment of other parts of the structures of the premises or the building provided the same is not attributable to any act or omission of the Licensor the family employees servants agents visitors or licensees of the Licensor;
<br>
iii)	any lack or shortage of  water electricity or drainage;
<br>
iv)	any act or default (negligent or otherwise) of servants of the Licensor employed in any capacity whatsoever;
<br>
v)	any act or default of any other Licensees or tenants of the building or any portion thereof including servants or agents or licensees of such other Licensees or tenants;
<br>
vi)	any fire, burglary or theft of the goods of the licensee from the premises;
<br>
vii)	any fire explosion falling plaster steam rain or leak from any part of the building of from the pipes appliances or plumbing works or from the roofs or from any other place or by dampness however occurring provided the same is not attributable to any act or omission of the Licensor the family employees servants agents visitors or licensees of the Licensor;
<br>
c)	The Licensee shall unconditionally and irrevocably indemnify the Licensor against all claims actions and proceedings by the Licensee's employees servants Licensee's agents and others in respect of loss damage or injury;
<br>
d)	Each and every of the Licensee's covenants herein shall remain in full force both at law and in equity notwithstanding that the Licensor shall have waive or released in any manner whatsoever a similar covenant or covenants affecting other Licensees of other Premises or the Licensee's of the building;
<br>
e)	No provision in this agreement shall be waived or varied by either party hereto except by agreement in writing;
<br>
5.	All notices required under this agreement shall be in writing and shall in the case of notice to the Licensee be sufficiently served if addressed to the Licensee and delivered to the Licensee at the premises or sent by pre-paid registered post and in case of notice to the Licensor be sufficiently served if addressed and delivered to him or his authorized agents or posted to him or such agent by registered post so that any notice so posted shall be deemed to have been served within Seven (7) days following the date of posting.
<br>
6.	The Licensee hereby accepts this agreement subject to the above conditions, restrictions and stipulations.
<br>
			</td>
			</tr>			
				
		     </table>
		</li><br><br><br>";
		
if ($tradingname == "")
    {
    $div1 .="<li class='ui-widget-content'>
                <table width='100%' border=0>
                        <tr>
                                <td valign='middle'>
                                        <strong>IN WITNESS WHEREFORE</strong> the Lessor and Lessee have caused their Common Seal to be affixed on this
                                        Lease on the ____day of___________ <strong>".convert_number(date('Y'))."<strong>
                                </td>
                        </tr>
                        <tr>
                                <td valign='top'>
                                        <table width='98%'>
                                                <tr>
                                                        <td style='height:20px;'>
                                                                SEALED with the Common Seal of <br><br>
                                                                The Lessor: - <strong>$companyname</strong><br><br>
                                                                In the presence of:-<br><br><br>
                                                                ---------------------------------<br>
                                                                <strong>DIRECTOR</strong><br><br><br>
                                                                ---------------------------------<br>
                                                                <strong>DIRECTOR</strong><br><br><br>
                                                                SEALED with the Common Seal of <br><br>
                                                                The Lessee:- M/s. <strong>".$tenantname."</strong><br><br>
                                                                In the presence of:-<br><br><br><br><br>
                                                                ---------------------------------<br>
                                                                <strong>DIRECTOR</strong><br><br><br>
                                                                ---------------------------------<br>
                                                                <strong>DIRECTOR</strong>
                                                        </td>
                                                        <td style='height:20px;width=50px;'>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                -------------------------<br>
                                                                COMPANY SEAL<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                -------------------------<br>
                                                                COMPANY SEAL<br><br><br><br>
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                </table>
        </li>";
    $div1 .="<li class='ui-widget-content'>
                <table width='100%' border=0>
                        <tr>
                                <td colspan='3' align='center' style='valign='middle'><u>CERTIFICATE</u><br><br></td>
                        </tr><tr>
                                <td valign='middle' align='justify'>
                                        I, ____________________Advocate CERTIFY that the above named directors/principals of
					<strong>".$tenantname."$tradingtitle</strong> appeared
					before me on this _____ Day of _________________ ".convert_number(date('Y'))." and being
                                        identified to me acknowledged the above signature to be his/her and that he/she had freely and voluntarily executed this
                                        instrument and understood its contents.
                                </td>
                        </tr>
                </table>
        </li>";
   
    }
    else
    {
	 $div1 .="<li class='ui-widget-content'>
                <table width='100%' border=0>
                        <tr>
                                <td valign='middle'>
                                        <strong>IN WITNESS WHEREFORE</strong> the Lessor and Lessee have caused their Common Seal to be affixed on this
                                        Lease on the ____day of___________ <strong>".convert_number(date('Y'))."<strong>
                                </td>
                        </tr>
                        <tr>
                                <td valign='top'>
                                        <table width='98%'>
                                                <tr>
                                                        <td style='height:20px;'>
                                                                SEALED with the Common Seal of <br><br>
                                                                The Lessor: - <strong>$companyname</strong><br><br>
                                                                In the presence of:-<br><br><br>
                                                                ---------------------------------<br>
                                                                <strong>DIRECTOR</strong><br><br><br>
                                                                ---------------------------------<br>
                                                                <strong>DIRECTOR</strong><br><br><br>
                                                                SIGNED BY THE LESSEE:<br><br>
                                                                <strong>".$tenantname."<br>T/A ".$tradingname."</strong><br><br>
                                                                <br><br><br><br><br>
                                                                ---------------------------------<br>
                                                                In the Presense of:- <br><br><br>
                                                                ---------------------------------<br>
                                                                <strong>ADVOCATE</strong>
                                                        </td>
                                                        <td style='height:20px;width=50px;'>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                -------------------------<br>
                                                                COMPANY SEAL<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                ]<br>
                                                                -------------------------<br>
                                                                STAMP<br><br><br><br>
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                </table>
        </li>";
     $div1 .="<li class='ui-widget-content'>
                <table width='100%' border=0>
                        <tr>
                                <td colspan='3' align='center' style='valign='middle'><u>CERTIFICATE</u><br><br></td>
                        </tr>
			<tr>
                                <td valign='middle' align='justify'>
                                        I, ____________________Advocate CERTIFY that the above named personns appeared
					before me on this _____ Day of _________________ ".convert_number(date('Y'))." and being
                                        identified to me acknowledged the above signature to be his/her and that he/she had freely and voluntarily executed this
                                        instrument and understood its contents.
                                </td>
                        </tr>
                </table>
        </li>";
    }    
$div1 .="</ul>";  

$createdby = $_SESSION['myusername'];
$createddatetime = $datetime;

$instr = $div1;
$instr = "'".mysql_real_escape_string($instr)."'";
$insert = "insert into rpt_simple_agreement (grouptenantmasid,rowcontent,createdby,createddatetime) values ($groupmasid,$instr,'$createdby','$createddatetime')";

//$custom = array(
//            'divContent'=> $insert,
//            's'=>'Error');
//$response_array[] = $custom;
//echo '{"error":'.json_encode($response_array).'}';
//exit;

mysql_query($insert);

}
else
{
	while($row = mysql_fetch_assoc($result))
	{
		$div1 = $row['rowcontent'];			
	}
	
}
$div2 ="<table>
	   <tr id='controlpanel'>
		   <td>
			   <button type='button' id='btnEdit1' name='1'>Edit</button> &nbsp					
			   <div id='divText1' style='height: 0px;visibility: hidden;'>
				   <textarea id='editor1' rows='0' cols='0'>
					  
				   </textarea>
			   <button type='button' id='btnUpdate1' name='1'>Update</button>
			   </div>
		   </td>
	   </tr>	
	</table>";	
   
$custom = array(
            'divContent'=> $div0.$div1.$div2,
            's'=>'Success');
$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
	
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