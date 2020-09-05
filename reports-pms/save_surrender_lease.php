<?php
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$dateofsurrender = $_REQUEST['dateofsurrender'];
$dtyear = date("Y", strtotime(date("d-m-Y", strtotime($dateofsurrender)) . " + 0 Day"));

//$tenantmasid="";
//$group=0;
//$sqlArray="";
//$cnt =1;
//foreach ($_REQUEST as $k=>$v) {
//	if($cnt >1)
//	{
//		$k = str_split($k,11);
//		if($k[0] =="tenantmasid")
//		{		
//			$group++;
//			$sqlArray.= $cnt."--> KEY: ".$k[0]."; VALUE: ".$v."--->$group<BR>";
//			$tenantmasid .= ",".$v;			
//		}
//	}
//	$cnt++;
//}
//echo $tenantmasid;
//exit;

try
{
$sqlArray="";
$cnt =1;
$group=0;
$tenantRent="<br>";
$tenantSc ="<br>";
$tenantDeposit ="<br>";
$shopid="";
$shopsize="";
$sizetotal="";
$shopsizeid="";
$totalDeposit=0;
$tenantmasid=0;
$leasedeposit=0;
$shops="";
$groupmasid=0;
$groupmasid = $_REQUEST['hid_itemid']; // grouptenantmasid

foreach ($_REQUEST as $k=>$v) {	
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
			
			 $sqlv1= "SELECT a.*, b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size, \n"
				. "DATE_FORMAT( a.doo, '%d-%m-%Y' ) as 'tenantdoo',  \n"
				. "DATE_FORMAT( a.doc, '%d-%m-%Y' ) as 'tenantdoc'  \n"
				. " FROM mas_tenant a\n"
				. " INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
				. " INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
				. " INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
				. " INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
				. " INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
				. " INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
				. " WHERE a.tenantmasid = $tenantmasid \n"
				. " and  a.companymasid=$companymasid";			
			
			//create tenant view
			$viewSql =  "create view view_offerletter_tenant as ".$sqlv1;
			$result = mysql_query($viewSql);
						
			$sqlv2= "select a.* from mas_tenant_cp a inner join mas_tenant b on b.tenantmasid = a.tenantmasid "
					. " WHERE a.tenantmasid =".$tenantmasid
					. " and  b.companymasid=$companymasid "
					//. " and  b.companymasid=$companymasid and b.active='1'"
					. " and  a.documentname='1'";
			
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
				
				$tenantname = $row['salutation']." ".strtoupper($row['leasename']);
				//echo $tenantname;
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
                      $shopsize=$row['size']." sqrft";
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
								$tenantDeposit .= "<strong><u>".$buildingname.",".$floordescription.",Size:".$row['size']." sqrft</u></strong><br><br>";
								$tenantDeposit .="<table cellpadding='3' cellspacing='0' border='1' width='100%'><tr>"
								."<thead>"
								."<th>Description</th>"
								."<th>Amount (KSH)</th>"
								."</thead></tr>";
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
				$tenantDeposit .="<tr>"
						."<td align='center'>$n.</td>"
						."<td>".$row['depositmonthsc']." Months security deposit Scr.Chrg</td>"
						."<td align='right'>".number_format($row['scdeposit'], 0, '.', ',')."</td>"
						."</tr>";$n++;
				//if( $row['advancemonthrent'] == 0)
				//{
				//	$row['advancemonthrent']="";
				//}	$val="0";
				$tenantDeposit .="<tr>"
						."<td align='center'>$n.</td>"
						."<td>".$row['advancemonthrent']." Month Advance rent with VAT</td>"
						."<td align='right'>".number_format($row['rentwithvat'], 0, '.', ',')."</td>"
						."</tr>";$n++;	
				$tenantDeposit .="<tr>"
						."<td align='center'>$n.</td>"
						."<td>".$row['advancemonthsc']." Month Advance Scr.Chrg with VAT</td>"
						."<td align='right'>".number_format($row['scwithvat'], 0, '.', ',')."</td>"
						."</tr>";$n++;
				$tenantDeposit .="<tr>"
						."<td align='center'>$n.</td>"
						."<td>Leegal Fees with VAT</td>"
						."<td align='right'>".number_format($row['leegalfees'], 0, '.', ',')."</td>"
						."</tr>";$n++;
				$tenantDeposit .="<tr>"
						."<td align='center'>$n.</td>"
						."<td>Stamp Duty</td>"
						."<td align='right'>".number_format($row['stampduty'], 0, '.', ',')."</td>"
						."</tr>";$n++;
				$tenantDeposit .="<tr>"
						."<td colspan='2'>Total</td>"
						."<td align='right'>Kshs.<strong>".number_format($row['depositTotal'], 0, '.', ',')."</strong></td>"
						."</tr>";
				$totalDeposit +=$row['depositTotal'];
				$n = 0;
			}
			$tenantDeposit .="</table><br>";
			
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

$shops = rtrim($shops,",");

 
$div0="<span id='span'>Print Preview Lease for  <font color='blue'> M/s. $tenantname ($tenantcode)</font>&nbsp;
	<button type='button' id='btnPreview' name='0'>Save & Print</button></span>
        <p class='printable' style='fonr-face='verdana;color=red;'><table width='100%' border=0><tbody><tr><td>";
$html ='';
	
$sql = "select grouptenantmasid,createdby,date_format(createddatetime,'%d-%m-%Y') as condate from rpt_surrender_lease where grouptenantmasid=$groupmasid";
$result = mysql_query($sql);
$rowcount = mysql_num_rows($result);

class MYPDF extends TCPDF {		
		 // Page footer
		public function Footer() {
		    // Position at 15 mm from bottom
		    $this->SetY(-15);
		    // Set font
		    $this->SetFont('helvetica', 'I', 8);
		    // Page number
		    $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
	}
	
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// remove default header/footer
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set default footer margin
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set default font subsetting mode
$pdf->setFontSubsetting(true);
// ---------------------------------------------------------
$pdf->AddPage();

$pdf->SetFont('dejavusans','BU',10);
$pdf->Cell(0,10,'REPUBLIC OF KENYA',0,0,'C');$pdf->ln(6);
$pdf->Cell(0,10,'IN THE MATTER OF THE LAND ACT NO.6 OF 2012',0,0,'C');$pdf->ln(6);
$pdf->Cell(0,10,'IN THE MATTER OF THE LAND REGISTRATION ACT NO.3 OF 2012',0,0,'C');$pdf->ln(6);    
$pdf->Cell(0,10,'AND IN THE MATTER OF THE REGISTERED LAND ACT CAP 300',0,0,'C');$pdf->ln(6);    
$pdf->Cell(0,10,'(REPEALED)',0,0,'C');$pdf->ln(6);
$pdf->Cell(0,10,"TITLE ".$buildingmunicipaladd,0,0,'C');$pdf->ln(10);
$pdf->Cell(0,10,'SUB-LEASE',0,0,'C');$pdf->ln(10);
$pdf->Cell(0,10,'MEMORANDUM',0,0,'L');$pdf->ln(12);
$pdf->SetFont('dejavusans','',9);
$txt ="         Form of Sub-lease approved Under Section 108 of the Registered Land Act (Now Repealed) Under reference [CLR/R 24/VOL LXXV/99] and Adopted for use pursuant to Section 108 of the Land Registration Act 2012 to conform with the Land Registration Act 2012 and the Land Act 2012.";
$pdf->MultiCell(0, 6, $txt."\n", 0, 'J');$pdf->ln(3);

if ($rowcount == 0) // insert new
{	
    $html .='<table width="100%" border=0 style="line-height:1.5px;"> 
                <tr>
			<td valign="middle" align="center">
                                  <strong><u>SURRENDER OF SUB-LEASE</u></strong><br><br>
                        </td>
                </tr>
		<tr>
                            <td valign="middle" align="left" >
                                   <b><u>
				   On '.rtrim($shopid,",").' MEASURING '.$sizetotal.' SQUARE FEET AT '.strtoupper($buildingname).','.strtoupper($buildingmunicipaladd).'.
				   </u></b>
                                   <br><br>
                            </td>
                </tr>		
		<tr>
                            <td valign="middle" align="justify" >
                                   I/WE <b>'.$tenantname.' '.$tradingtitle.' </b> In consideration of * forfeiture of our right and
                                   obligations under the Sub lease (the receipt whereof is hereby acknowledged)* HEREBY SURRENDERS
                                   the sub lease for the Shop comprised in the above mentioned title and the Sub-Lessor HEREBY ACCEPTS
                                   the said surrender.
                                   <br><br>
                            </td>
                    </tr><tr>
                            <td valign="middle" align="left">
                                 Dated this '.$dateofsurrender.'
                                 <br>
                            </td>
                    </tr>
		</table>';

    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    $pdf->ln(2);
    
    if ($tradingname == "")
    {
        $html ='<table width="100%" border="0">
        <tr>
            <td colspan="2" style="height:20px;line-height:1.5px;" align="justify">IN WITNESS WHEREFORE the Lessor and the Lessee have caused their Common Seal to be affixed on
            this Lease on the ____day of___________ '.date("Y").'. <br><br></td>            
        </tr>
	<tr>
            <td style="height:15px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;" >The Lessor: <b>'.$companyname.'</b></td>
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
            <td style="height:15px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:20px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]<BR></td>
        </tr>
        <tr>
            <td style="height:20px;">In the presence of: -</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="height:15px;">ADVOCATE</td>            
        </tr><tr>
            <td colspan="2" align="center" style="height:10px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;line-height:1.5px;">I, ____________________________ certify that the above directors of the Lessor
            appeared before me on this _______ day of __________________ '.date("Y").' and being  known to me acknowledged the above
            signatures to be theirs and that they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:15px;"><br><br><b>______________________________________<br>
            <i>Signature and designation of the person certifying</i></b></td>
        </tr></table>';
	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);	
	$pdf->ln(2);
	$pdf->AddPage();  
	$pdf->ln(20);
	$pdf->SetFont('dejavusans','',9);   
        $html ='<table>
         <tr>
            <td style="height:15px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">The Lessee: <b>'.$tenantname.'</b></td>
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
            <td style="height:15px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:20px;"><BR>In the presence of: -</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="height:30px;">ADVOCATE</td>            
        </tr>
         <tr>
            <td colspan="2" align="center" style="height:30px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;line-height:1.5px;">I, ____________________________ certify that the above directors of the Lessee
            appeared before me on this _______ day of __________________ '.date("Y").' and being  known to me acknowledged the above
            signatures to be theirs and that they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:35px;"><br><br><b>______________________________________<br><i>
                            Signature and designation of the person certifying</i>
        </b></td>
        </tr>
        </table>';    
        
    }
    else
    {
         $html ='<table width="100%" border="0">
        <tr>
            <td colspan="2" style="height:25px;line-height:1.5px;" align="justify">IN WITNESS WHEREFORE the Lessor and the Lessee have caused their Common Seal to be affixed on
            this Lease on the ____day of___________ '.date("Y").'. <br><br></td>            
        </tr>
	<tr>
            <td style="height:15px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">The Lessor: <b>'.$companyname.'</b></td>
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
            <td style="height:15px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;"><BR>In the presence of: -</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="height:15px;">ADVOCATE</td>            
        </tr>
         <tr>
            <td colspan="2" align="center" style="height:15px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;line-height:1.5px;">I, ____________________________ certify that the above directors of the Lessee
            appeared before me on this _______ day of __________________ '.date("Y").' and being  known to me acknowledged the above
            signatures to be theirs and that they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:15px;"><br><br><b>______________________________________<br><i>
                            Signature and designation of the person certifying</i>
        </b></td>
        </tr>
        </table>';
	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);		
	$pdf->AddPage();  
	$pdf->ln(15);
	$pdf->SetFont('dejavusans','',9);   
        $html ='<table>    
         <tr>
            <td style="height:30px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:30px;">The Lessee: <br><b>'.$tenantname.'</b><br> T/A <br> <b>'.$tradingname.'</b></td>
            <td>]</td>
        </tr>
         <tr>
            <td style="height:30px;">In the presence of: -</td>
            <td>]</td>
        </tr>
       <tr>
            <td style="height:30px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:30px;"></td>
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
            <td colspan="2" style="height:30px;">ADVOCATE</td>            
        </tr>
         <tr>
            <td colspan="2" align="center" style="height:30px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;">I, ____________________________ certify that the above directors of the Lessee
            appeared before me on this _______ day of __________________ '.date("Y").' and being identified to me acknowledged the above signatures to be his/hers/theirs and that he/she/they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:35px;"><br><br><b>______________________________________<br><i>
                            Signature and designation of the person certifying</i>
        </b></td>
        </tr>
        </table>';        
    }
    
	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
	$pdf->ln(2);
	
	if($renewalfromid<=0)
		$filename = $tenantname." - (".$tenantcode.")";
	else
		$filename = $tenantname." - (".$tenantcode."-RENEWED)";
		$filename = clean($filename);
	$pdf->Output("../../pms_docs/surrenders/".$filename.".pdf","F");
	
	$createdby = $_SESSION['myusername'];
	$createddatetime = $datetime;
	
	$insert = "insert into rpt_surrender_lease (grouptenantmasid,createdby,createddatetime) values ($groupmasid,'$createdby','$createddatetime')";
	mysql_query($insert);
	
	// tenant discharge operation
	$insert = "insert into trans_tenant_discharge_op (grouptenantmasid) values ($groupmasid)";
	mysql_query($insert);
	
	// tenant discharge accounts
	$insert = "insert into trans_tenant_discharge_ac (grouptenantmasid) values ($groupmasid)";
	mysql_query($insert);
	
	$pdf->Output($filename, 'I');
	//exit;
	$message="The lease of ".$tenantname."of ".$buildingname." block ".$blockname."
            floor ".$flooname." shop ".$shopcode." of size ".$shopsize." has been surrendered";
	require_once('../PHPMailer/class.phpmailer.php');
        $mail = new PHPMailer(); // defaults to using php "mail()"
        
        $mail->CharSet = "UTF-8"; 
        $mail->IsSMTP(); // send via SMTP 
        $mail->Host = "mail.busgateway.is.co.za"; // SMTP servers 
        $mail->SMTPAuth = true; // turn on SMTP authentication 
        $mail->Username = "info@shiloahmega.com"; // SMTP username 
        $mail->Password = "MegaProps@2501"; // SMTP password 
        $mail->From = "info@shiloahmega.com"; 
        $mail->FromName = "MEGA PMS ERP";
        $mail->IsHTML(true);

       // $address = 'shailesh@shiloahmega.com';
		 $address = 'info@shiloahmega.com';
        $mail->AddAddress($address,"Operations");
		$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('5','4','8','9') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
        // $recipients = array(
	
	   // 'prabakaran-accounts@shiloahmega.com' => 'Praba',
	   // 'dipak@shiloahmega.com' => 'Dipak',
	   // 'mitesh@shiloahmega.com' => 'Mitesh',
	   // 'suresh@shiloahmega.com' => 'Suresh',
       // 'arulraj@shiloahmega.com' => 'Arulraj'
	  
        // ); 
        foreach($recipients as $email => $name)
        {
           $mail->AddCC($email, $name);
        }
        $mail->Subject    = "Surrender Of Lease";
         $mail->MsgHTML($message);
	//$mail->AddAttachment('../../pms_docs/surrenders/'.$filename.'pdf', ); // attachment 
        $mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/pms_docs/surrenders/'.$filename.'pdf', $name = 'test',  $encoding = 'base64', $type = 'application/pdf');
        if(!$mail->Send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
//          echo "Message sent!";
        }
        
    //**************************** EMAIL *************************//
	
	//exit;


}
else
{
	while($row = mysql_fetch_assoc($result))
	{		
		$cby = strtoupper($row['createdby']);
		$condate = $row['condate'];
	}
	echo "Surrender Lease already Created by $cby on $condate;";
	exit;
}	   
	
	//$custom = array(
	//	    'divContent'=> "Surrender Lease Created previously",
	//	    's'=>'Success');
	//$response_array[] = $custom;
	//echo '{"error":'.json_encode($response_array).'}';
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