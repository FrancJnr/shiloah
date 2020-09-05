<?php
include('../config.php');
session_start();
//$tenantmasid="";
//$group=0;
//$sqlArray="";
//$cnt =1;
//foreach ($_GET as $k=>$v) {
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
$sizetotal="";
$shopsizeid="";$floor="";
$totalDeposit=0;
$tenantmasid=0;
$companymasid=0;
$leasedeposit=0;
$shops="";
$groupmasid=0;
$tenantmasid = $_GET['tenantmasid'];
			$cnt++;			
			
			
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
				WHERE a.tenantmasid = $tenantmasid and a.active='1';";			
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
					WHERE a.tenantmasid = $tenantmasid and a.active='1';";			
				}
			}
			//create tenant view
			$viewSql =  "create view view_offerletter_tenant as ".$sqlv1;
                                                                        
                                
			$result = mysql_query($viewSql);
			
			$sqlv2= "select a.* from mas_tenant_cp a inner join mas_tenant b on b.tenantmasid = a.tenantmasid "
					. " WHERE a.tenantmasid =".$tenantmasid
					. " and  b.active='1'"
					. " and  a.documentname='1'";
			$result = mysql_query($sqlv2);
			if($result !=null)
			{
				if(mysql_num_rows($result)<=0)
				{
					$sqlv2= "select a.* from rec_tenant_cp a inner join rec_tenant b on b.tenantmasid = a.tenantmasid "
						. " WHERE a.tenantmasid =".$tenantmasid					
						. " and  b.active='1'"
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
			
				
                        
		
			////load tenant details
			$sql = "SELECT * FROM view_offerletter_tenant";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{				
				//$tenantmasid = $row['tenantmasid'];				
                                $leasename = $row['salutation']." ".strtoupper($row['leasename']);
                                $companymasid = $row['companymasid'];
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
                        //load company details
                        ////////create company view
			$viewSql = "create view view_offerletter_company as SELECT * ,\n"
			    . "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
			    . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
			    . "FROM mas_company where companymasid =". $companymasid;
			$result = mysql_query($viewSql);
			$sql = "SELECT * FROM view_offerletter_company";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$companyname = strtoupper($row['companyname']);		
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
//if ($rowcount == 0)
//{      
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
    $tname = $leasename." T/A ".$tradingname;
    else
    $tname = $leasename;
    
    $tenantdetails ='<table width="100%">		
		<tr style="line-height: 1.8px;">
			<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;<font size="9.5px"><b>'.$tname.'</b></font>,						
				<br>&nbsp;&nbsp;&nbsp;&nbsp;P.O BOX NO:'.$tenantpobox.',
				<br>&nbsp;&nbsp;&nbsp;&nbsp;'.$tenantcity.'				
				<br><br>Kind Attn    :'.$tenantcpname.'.
			</td>			
		</tr>
		</table>';
     $p0 ='<table width="100%" >
		<tr style="line-height: 1.8px;">		  
                    <td align="justify">Dear Sir/Madam,<br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                       <u><font size="11.5px">REF: LEASE OF PREMISES IN &nbsp;'.$buildingname.' , '.$buildingmunicipaladd.' , '.$shops.'.</font></u>
                       <br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                       We <strong>'.$companyname.'</strong> ,'.$companyshortaddress.' are the owners of the premises known as <strong>'.$buildingname.'</strong> in ,
                       '.$buildingmunicipaladd.' and subject to the consent of any registered chargee of '.$buildingname.', we are prepared to enter into a lease, in
                       the form of our standard lease, which includes (among others) the following terms and conditions.
                    </td>		
		</tr>
		</table>';
    $x = 1;
    $p1 ='<b>'.$x.'. LAND LORD:</b>
            <ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li><strong>'.strtoupper($companyname).'</strong> ,'.$companyshortaddress.'.</li>
            <ol>';
	$x++;    
    $p2 ='<b>'.$x.'. TENANT:</b>
            <ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li><strong>'.$leasename.'</strong> ,'.$tenantshortaddress.'.</li>
            <ol>';
	$x++;    
    $p3 ='<b>'.$x.'. PREMISES:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>The premises are situated on the '.rtrim($floor,',').' measuring <strong>'.$sizetotal.'</strong> square feet approximately.</li>
            <ol>';
	$x++;    
    $p4 ='<b>'.$x.'. TERM:</b>
            <ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>'.$fulldescofterm.'.</li>
            <ol>';    
    $pdf->AddPage();	    
    $pdf->SetFont('dejavusans','B',23);
    $pdf->writeHTML('<table><tr align="center" style="font-stretch: expanded;"><th>'.$companyname.'.</th></tr></table><hr>', true, false, true, false, '');
    $pdf->ln(0.2);    
    $pdf->SetFont('dejavusans','',8.5);
    $pdf->writeHTML($address, true, false, true, false, '');
    $pdf->ln(7);    
    $pdf->SetFont('dejavusans','',9.5);
    $pdf->writeHTML('<strong>'.Date("F d, Y").'</strong>', true, false, true, false, '');
    $pdf->ln();
    $pdf->SetFont('dejavusans','',10);
    $pdf->writeHTML($tenantdetails, true, false, true, false, '');
    $pdf->ln();
    $pdf->SetFont('dejavusans','',9.5);
    $pdf->writeHTML($p0, true, false, true, false, '');
    $pdf->ln();
    $pdf->SetFont('dejavusans','',10);
    $pdf->writeHTML($p1, true, false, true, false, '');
    $pdf->ln(3);
    $pdf->writeHTML($p2, true, false, true, false, '');
    $pdf->ln(3);
    $pdf->writeHTML($p3, true, false, true, false, '');
    $pdf->ln(3);
    $pdf->writeHTML($p4, true, false, true, false, '');    
    $pdf->SetXY(10, 263);
    $pdf->writeHTML("<hr>", true, false, true, false, '');
    if($companyname =="SHILOAH INVESTMENTS LTD")
    {
            $pdf->SetXY(15, 264);
            $pdf->Image('../images/mp_logo.jpg', '', '', 12, 12, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetXY(95, 264);
            $pdf->Image('../images/mc_logo.jpg', '', '', 12, 12, '', '', '', false, 200, '', false, false, 0, false, false, false);
            $pdf->SetXY(180, 264);
            $pdf->Image('../images/mm_logo.jpg', '', '', 12, 12, '', '', '', false, 300, '', false, false, 0, false, false, false);
    }
    
    //$pdf->Output();
    //exit;
    
	$x++;    
    $p5 ='<b>'.$x.'. DATE OF OCCUPATION:</b>
            <ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>'.$doo.'.</li>
            <ol>';
	$x++;    
    $p6 ='<b>'.$x.'. DATE OF COMMENCEMENT:</b>
            <ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>'.$doc.'.</li>
            <ol>';
	$x++;    
    $p7 ='<b>'.$x.'. RENT:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			'.$tenantRent.'
			The said rent will be paid <strong>'.$rentcycle.' in advance</strong>. If the rent shall not be paid on the due date whether formally demanded or not, the Tenant
			shall pay the Landlord a penalty on any such sums at the rate of '.$latefeeinterest.' % per month from the date when it fell due to the date when it is paid.
		</li>
            <ol>';
	$x++;    
    $p8 ='<b>'.$x.'. FORM OF LEASE:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			The Lease will be in the landlords Standard Form of Lease for <strong> '.strtoupper($companyname).'</strong> as shall be prepared by the Landlords Advocates
		which shall be deemed to have been accepted by the Tenant upon acceptance of the terms of this letter.
		</li>
            <ol>';
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(true);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->setHtmlHeader("TENANT NAME:<b> ".$leasename."</b>");
    $pdf->setHtmlFooter("<b>".$companyname." <br>LETTER OF OFFER TO TENANCY</b>");
    $pdf->AddPage();    
    $pdf->writeHTML($p5, true, false, true, false, '');
    $pdf->ln(3);
    $pdf->writeHTML($p6, true, false, true, false, '');
    $pdf->ln(3);
    $pdf->writeHTML($p7, true, false, true, false, '');
    $pdf->ln(3);
    $pdf->writeHTML($p8, true, false, true, false, '');
    $pdf->ln(3);
     if((strtoupper($buildingname) != "KATANGI") and ($sc >0 ))
    {
    $x++;
    $p9 ='<b>'.$x.'. SERVICE CHARGE:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			You will also pay Actual Service Charge determined in the manner stated in the Lease to reimburse the Landlords for a fair proportion of the operation
				expenses of the building that shall include but not be limited to the following: -
		</li>
		<li>
			<ol align="justify" style="line-height: 1.8px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-roman;">
				<li>Cost of all water used in the common areas.</li>
				<li>Cost of all the electricity consumed by security lights and in the common areas.</li>
				<li>Cost of providing security services.</li>
				<li>Cost of cleaning the propertys common areas.</li>
				<li>Premium payable for fire and public liability insurance.</li>
				<li>Management expenses.</li>
				<li>Cost of maintaining and re-decorating the common areas.</li>
				<li>Cost of internal repairs and maintenance of the demised premises not being of a structural nature.</li>
				<li>Cost of maintaining the fire fighting systems and any other mechanical systems or installations.</li>
				<li>Rates and ground rent.</li>
				<li>Book-keeping and audit fees for the rent and service charge account.</li>
				<li>Cost of refuse collection.</li>
				<li>Cost of pesticides and sanitary services.</li>				
				<li>Any other cost which the lessor may deem fit in its sole discretion to levy as service charge from time to time.</br></li>
			</ol>
		</li>
		</ol>
		<ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
		<li>
			PROVIDED THAT in the event that the sums paid by the Lessee under this clause are less than the actual sum of service charge expended by the Lessor as
			proved by the Lessor'."'".'s annual audited accounts (AND for the purposes of this sub-clause the parties hereunto agree that the statement of the Lessor'."'".'s
			auditors as to the amount expended in respect of service charge shall be final and conclusive) the Lessee shall within seven days (7) days from the date of
			receipt of a demand from the Lessor reimburse to the Lessor the difference between the sums already paid by the Lessee and the actual aggregate amount
			expended by the Lessor in providing such service.
		</li>		
            <ol>';
	$x++;    
	$p10 ='<b>'.$x.'. SERVICE CHARGE DEPOSIT:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			Subject to <strong>SERVICE CHARGE DETAILS</strong> above deposit on account of the service charge shall be paid in advance as below stated.
			<br>The Service Charge deposit without deductions will be: -
			<br>
			'.$tenantSc.'
		</li>
            <ol>';	
	$pdf->writeHTML($p9, true, false, true, false, '');
	$pdf->ln(3);
	$pdf->writeHTML($p10, true, false, true, false, '');
	$pdf->ln(3);
    }
    $x++; 
    $p11 ='<br><b>'.$x.'. STATUTORY TAXES:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			To pay the rent herein before reserved at the time and manner aforesaid without any deductions and the rent shall be subject to Value Added Tax and any
			other statutory taxes which may be levied on the rent, service charge and on any other sums payable herein and to pay all other sums as provided in the
			sub- lease.
		</li>
            <ol>';
    $pdf->writeHTML($p11, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p12 ='<br><b>'.$x.'. PERMITTED USE:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			User of the premises will be restricted for <strong> '.strtoupper($nob).' ONLY</strong> and such use shall not be changed without the
			Landlord'."'".'s prior written consent.<br>
			The use of the premises will have to be in accordance with the design of the building and in fulfillment
			of '.$buildingcouncil.'  requirements PROVIDED
			that the Lessee, its servants and/or agents <strong><u>shall not</u></strong> be entitled to use the common areas for his/her/its business.
		</li>
            <ol>';
    $pdf->writeHTML($p12, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p13 ='<br><b>'.$x.'. PROHIBITION ON TRANSFER, SUB-LETTING, e.t.c:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
		Not to assign transfer sublet charge or otherwise part with the possession of the demised premises or any part thereof without the written consent of the
		Lessor, and of the Chargees having a security over the Building first had and obtained AND IT IS HEREBY EXPRESSLY AGREED AND
		DECLARED by and between the parties hereto that upon any breach by the Lessee of this covenant and agreement it shall be lawful for the Lessor to re-enter
		upon the demised premises without notice and thereupon the said term shall determine absolutely PROVIDED ALWAYS that in the event of such a determination
		of the term hereby created the Lessee shall remain liable to the Lessor for payment of all the rentals, service charge and/or any other sum payable under
		the terms and conditions of this Letter Of Offer and for the entire period of the tenancy AND IT IS HEREBY FURTHER EXPRESSLY AGREED AND DECLARED by and
		between the parties hereto that the Lessor shall be entitled to withhold its consent absolutely to any assignment transfer subletting or parting with
		possession of the demised premises by the Lessee if the result of such assignment transfer subletting or parting with possession would in the opinion of
		the Lessor (which shall be final) be to bring the tenure of the demised premises or the said term or any balance thereof within the protection of the
		Landlord and Tenant (shops, hotels and catering Establishments) Act (Chapter 301) or any Act or Acts for the time being in force amending or replacing the
		same or any similar Act. It shall not be implied in any circumstances that the consent of the Lessor or of an acceptance of any assignee transferee
		sub-Lessee or occupant as tenant or a release of the Lessee from the further performance by the Lessee of the covenants and agreements on the Lessee'."'".'s part
		herein contained will be or is liable to be forth-coming the Lessor hereby expressly reserving to themselves the right in their absolute and uncontrolled
		discretion and without assigning any reason therefor to withhold their consent if they consider that either their interest or those of the other tenants of
		the buildings would be impaired by giving the same nor shall the consent by the Lessor to any assignment transfer or subletting be in anyway construed as
		relieving the Lessee from obtaining the express consent in writing of the Lessor to any further assignment transfer or subletting. If the Lessor shall give
		such consent the instrument of assignment shall be prepared by the Lessor'."'".'s advocate and executed by the parties and all costs in connection with the
		preparation and completion thereof including the Advocates costs stamp duties and registration fees shall be borne by the Lessee AND IT IS HEREBY FURTHER
		AGREED THAT where the Lessee is a private limited company then for the purpose of this sub-clause a transfer of the beneficial interest in more than fifty
		percent (50%) of the issued share capital of the Lessee or allotment or issue or transfer of any share of the Lessee to anyone other than any current
		member of the Company shall be deemed to constitute an assignment and transfer of the demised premises;
		</li>
            <ol>';
    $pdf->writeHTML($p13, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p14 ='<br><b>'.$x.'. RESTRICTION OF SIGNS AND NOTICES,etc.:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			The placement of any sign, notice or advertisement so as to be visible from the exterior of the premises is prohibited without the prior consent of the
			Landlord. Any sign or notice advertisement must comply with the Landlord'."'".'s specifications.
		</li>
            <ol>';
    $pdf->writeHTML($p14, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p15 ='<br><b>'.$x.'. LAYOUT AND PARTIONING:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			The landlord'."'".'s prior approval is required to the design and layout of the interior of the premises including partitioning and to any changes, which you may
			wish to make during the term of the lease.
			<br>
			Before commencing any improvements or alterations to the interior you must submit plans of the intended layout and design, specifying the materials to be
			used. You will be required to pay the cost of the Landlords and its Consultants incurred in considering the proposed plans. The costs of partitioning and
			any other internal improvements and alterations are the responsibility of the tenant.
			<br>
			On determination of Lease, the partitioning shall become the property of the Landlord provided that if the Landlord shall give notice to the tenant, the Tenant
			shall remove the partitioning and reinstate the premises to their original condition at the Tenant'."'".'s expense.
		</li>
            <ol>';
    $pdf->writeHTML($p15, true, false, true, false, '');
    $pdf->ln(3);
    $x++; 
    $p15 ='<br><b>'.$x.'. UTILITIES:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			<b>Water Charges:</b>
			<ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-roman;">
				<li>
				 To pay to the appropriate water authority any sums or charges payable in respect of the installation of any separate supply of water and water meter
				installed by or at the request of the Lessee in the demised premises and in respect of water consumed thereon and to observe and perform all regulations
				and to keep the Lessor indemnified in respect thereof OR;<br>
				</li>
				<li>
				To pay the Lessor in addition to the said rent by way of reimbursement to the Lessor all water charges in respect of water consumed by the Lessee in
				the said demised premises the said water charges to be computed according to an appropriate method adopted by the Lessor;
				</li>
			</ol>
			<br><br>
			<b>Telephone Charges:</b>
			<ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-roman;">
				<li>
				 To pay to the appropriate telephone authority the costs of installing and connecting such telephones as the Lessee may require on the demised premises
				and all rentals and other charges payable in respect of such telephones and its use in respect of the term hereby granted;
				</li>				
			</ol>
			<br><br>
			<b>Electricity Charges:</b>
			<ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-roman;">
				<li>
				To pay to the appropriate electricity authority any sums or charges payable in respect of the installation of any separate supply of electricity and
				electricity meter installed by or at the request of the Lessee in the demised premises and in respect of electricity consumed thereon and to observe and
				perform all regulations and to keep the Lessor indemnified in respect thereof;<br>
				</li>
				<li>
				To pay the Lessor in addition to the said rent by way of reimbursement to the Lessor all electricity charges in respect of electricity consumed by the
				Lessee in the said demised premises the said electricity charges to be computed according to an appropriate method adopted by the Lessor;<br>
				</li>
				<li>
				Not to install any equipment with a capacity above 4 Kilowatts or 13 Amperes at 240 volts or to load any socket outlet sub-circuit above the capacity
				of 13 Amperes without the prior consent in writing of the Lessor who shall be entitled as condition of giving such consent to require the Lessee to pay
				such additional sum of money as may suffice to cover installation and/or additional installation and the additional charges for electricity caused by such
				use;
				</li>				
			</ol>
		</li>
            <ol>';
    $pdf->writeHTML($p15, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p17 ='<br><b>'.$x.'. INTERNAL REPAIRS:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			You will be responsible for repairs to the interiors of the premises. These include repairs to finishes, partitions, doors, windows and internal fixtures
			and fittings.
			<br>
			You will also be responsible for painting the interior of the premises after two years from the date of commencement of the lease and on the determination
			of lease.
		</li>
            <ol>';
    $pdf->writeHTML($p17, true, false, true, false, '');
    $pdf->ln(3);
   
    $x++; 
    $p18 ='<br><b>'.$x.'. INSURANCE OF PLATE-GLASS:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			If the premises contain any plate-glass you shall be responsible for insuring it.
		</li>
            <ol>';
    $pdf->writeHTML($p18, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p19 ='<br><b>'.$x.'. LEGAL COSTS AND EXPENSES:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			All costs and expenses of preparing and completing the lease including legal fees of the Advocates of the Lessor/Landlord, stamp duty, other disbursement
			and value added tax will be for your account. Upon your confirmation that you wish to proceed, you will be required to pay a deposit on account of legal
			costs and expenses. The balance (if any) will be paid upon demand by the Landlord or its advocates.
		</li>
            <ol>';
    $pdf->writeHTML($p19, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p20 ='<br><b>'.$x.'. GUARANTEE:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: none;">
                <li>
			If the Tenant occupying the premise is a company personal guarantees of two Directors to be given in respect of payment of rents.
			For a partnership,personal guarantees of the partners to be given in respect of payments of rents.
		</li>
            <ol>';
    $pdf->writeHTML($p20, true, false, true, false, '');
    $pdf->ln(3);
    
    $x++; 
    $p21 ='<br><b>'.$x.'. ACCEPTANCE OF OFFER LETTER:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-alpha;">
                <li>
			If you wish to proceed on the basis of the terms set out in this letter, please sign the confirmation below and return the duplicate to us together
			with required payments as set out below within seven (7) days from the date hereof failing which this offer shall be deemed to be lapsed without any
			further notices or references.
			<br>
		</li>
		<li>
			Upon receipt of your confirmation and the deposits required, we will arrange for the sub-lease to be prepared and sent to you for execution. It is
			hereby further agreed that you shall communicate to the Lessor your approval or amendments on the sub-lease within seven (7) days from the date of receipt
			of the sub-lease. Upon expiry of the said seven (7) days you shall execute and return the sub-lease to the lessor in triplicate within a further seven (7)
			days thereafter failing which this agreement shall be terminated for all intents and purposes.
			<br>
		</li>
		<li>
			In the event of such a determination of the term hereby created as set out in paragraph (b) above the Lessee shall remain liable to the Lessor for
			payment of all the rentals, service charge and/or any other sum payable under the terms and conditions of this agreement and for the entire period of this
			agreement and the lessor shall at its own discretion be entitled to re-enter the premises hereby let to the lessee and to enjoy the premises in their
			former state and the lessee hereby waives the right of re-entry into the premises.
			<br>
		</li>
		<li>
			Notwithstanding that this matter remains subject to Lease, the landlord will be entitled to retain out of the deposits paid under this letter any costs
			and expenses incurred in preparing and negotiating the Lease.
		</li>
            <ol>';
    $pdf->writeHTML($p21, true, false, true, false, '');
    $pdf->ln(5);
    
    $pdf->writeHTML("Your's truly,", true, false, true, false, '');
    $pdf->ln(2);
    $pdf->writeHTML("<strong>For ".$companyname."</strong>,", true, false, true, false, '');
    $pdf->ln(15);
    $pdf->writeHTML("[Authorised Signatory]", true, false, true, false, '');
    $pdf->ln(3);
    
    $p22 ='<br>PLEASE NOTE:</b>
            <ol align="justify" style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;">
                <li>
			If you are a sole trader, the duplicate letter must be signed together with a copy of the I.D. / passport, P.I.N. certificate.
			<br>
		</li>
		<li>
			If you carry on business in partnership, the duplicate must be signed by all the partners. You must return the duplicate with a copy of Certificate of
			Registration under the Business Names Act a copy of the I.D. / passport, P.I.N. certificate.
			<br>
		</li>
		<li>
			If you are a limited liability company, the duplicate letter must be signed by two directors and you must return them with a copy of your Certificate of
			Incorporation under the Companies Act, Company board resolution, I.D. copies of the directors of the company and P.I.N. certificate.
			<br>
		</li>
		<li>
			If you are a foreign company registered in Kenya under the Companies Act, you must return the duplicate with a copy of your Certificate of Compliance
			under the Companies Act Company board resolution, I.D. copies of the directors of the company and P.I.N. certificate.
		</li>
            <ol>';
    $pdf->writeHTML($p22, true, false, true, false, '');
    $pdf->AddPage();
    $pdf->ln(5);
    $pdf->writeHTML("We confirm that we wish to proceed on the basis of the terms set out in this letter.", true, false, true, false, '');
    $pdf->ln(3);
     if($renewalfromid >0){
	$prevdeposit =0;
	$sqlp="select rentdeposit+scdeposit as 'prevdeposit'from
	       trans_offerletter a inner join trans_offerletter_deposit b on b.offerlettermasid = a.offerlettermasid
	       WHERE a.tenantmasid ='$renewalfromid';";	      
	$resultp=mysql_query($sqlp);
	if($resultp!=null)
	{
		while($rowp = mysql_fetch_assoc($resultp))
		{
			$prevdeposit =$rowp['prevdeposit'];
		}
	}
	if($prevdeposit >0)
	{
		$pdf->writeHTML("Carry forward from previous deposit amount Kshs ".number_format($prevdeposit, 0, '.', ',')."/= .", true, false, true, false, '');
		$pdf->ln(3);
	}
    }
	if($totalDeposit !=0)
	{
		$pdf->writeHTML("We enclose our cheque for the sum of <strong>KShs.".number_format($totalDeposit, 0, '.', ',')."/= </strong> as set out herein below.", true, false, true, false, '');
		$pdf->ln(2);
		$pdf->writeHTML($tenantDeposit, true, false, true, false, '');
		$pdf->ln(2);		
	}
  $html ='
	<br>For<strong> '.$leasename.'<br><br></strong>
	<br><br><br><br>
	<table width="100%" colspacing="3">
		<tr>
			<td align="center">_______________________________<br>SIGNED</td>
			
			<td align="center">_______________________________<br>DATE</td>
		</tr>
		<tr>
			<td colspan="2" style="height:70px;">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">_______________________________<br>SIGNED</td>
			
			<td align="center">_______________________________<br>DATE</td>
		</tr>	
	</table>	
	';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->ln(5);

    if($renewalfromid<=0)
        $filename = $leasename." - (".$tenantcode.")";
    else
        $filename = $leasename." - (".$tenantcode."-RENEWED)";
    
    $filename = clean($filename);

    // Save It onto directory  
    //$pdf->Output("../../pms_docs/offerletters/".$filename.".pdf","F");
    
    ////Insert into  rpt_offerletter
    $createdby = $_SESSION['myusername'];
    $createddatetime = $datetime;	
    $insert = "insert into rpt_offerletter (offerlettermasid,grouptenantmasid,createdby,createddatetime) values ($offerlettermasid,$groupmasid,'$createdby','$createddatetime')";
    //mysql_query($insert);

    ////Insert into document status
    $insert_doc_status ="insert into trans_document_status(grouptenantmasid,createdby,createddatetime) values ('$groupmasid','$createdby','$createddatetime');";
    //mysql_query($insert_doc_status);
    
    ////to show with file name pdf
    $pdf->Output($filename, 'I');
    exit;    
//}
//else
//{	
//        ////RETRIEVE OLD OFFERLETTER
//	while($row = mysql_fetch_assoc($result))
//	{
//		//$div1 = $row['rowcontent'];                
//                
//	}        
//}

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