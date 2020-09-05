<?php
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];

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
$shopsizeid="";
$totalDeposit=0;
$tenantmasid=0;
$leasedeposit=0;
$shops="";
$groupmasid=0;
$groupmasid = $_GET['grouptenantmasid'];
$company_poboxno=0;

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
			    . ", poboxno as company_poboxno,pincode,city FROM mas_company where companymasid =". $companymasid;
			$result = mysql_query($viewSql);
			
			$sqlv1= "select a.tenantmasid,a.tenanttypemasid,a.salutation,a.leasename,a.tradingname,
				a.tenantcode,a.companymasid,a.buildingmasid,a.blockmasid,a.floormasid,
				a.shopmasid,a.shoptypemasid,a.orgtypemasid,a.nob,
				a.agemasidlt,a.agemasidrc,a.agemasidcp,a.creditlimit,
				a.latefeeinterest,a.doo,a.doc,a.pin,a.regno,a.address1,a.address2,a.city,
				a.state,a.pincode,a.country,a.poboxno,a.telephone1,a.telephone2,a.fax,a.emailid,
				a.website,a.remarks,a.cpname1,a.cpdesignation1,a.cpnid1,a.cpphone1,a.cpname2,a.cpdesignation2,a.cpnid2,a.cpphone2,
				a.createdby,a.createddatetime,a.modifiedby,a.modifieddatetime,a.active,a.renewal,
				a.renewalfromid,a.shopoccupied,
				b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size,
				DATE_FORMAT( a.doo, '%d-%m-%Y' ) as 'tenantdoo',
				DATE_FORMAT( a.doc, '%d-%m-%Y' ) as 'tenantdoc'
				FROM mas_tenant a
				INNER JOIN mas_age b ON b.agemasid = a.agemasidlt
				INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc
				INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid
				INNER JOIN mas_block d ON d.blockmasid = a.blockmasid
				INNER JOIN mas_floor e ON e.floormasid = a.floormasid
				INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid				
				WHERE a.tenantmasid = $tenantmasid and  a.companymasid=$companymasid and a.active='1'
				union
				select a.tenantmasid,a.tenanttypemasid,a.salutation,a.leasename,a.tradingname,
				a.tenantcode,a.companymasid,a.buildingmasid,a.blockmasid,a.floormasid,
				a.shopmasid,a.shoptypemasid,a.orgtypemasid,a.nob,
				a.agemasidlt,a.agemasidrc,a.agemasidcp,a.creditlimit,
				a.latefeeinterest,a.doo,a.doc,a.pin,a.regno,a.address1,a.address2,a.city,
				a.state,a.pincode,a.country,a.poboxno,a.telephone1,a.telephone2,a.fax,a.emailid,
				a.website,a.remarks,a.cpname1,a.cpdesignation1,a.cpnid1,a.cpphone1,a.cpname2,a.cpdesignation2,a.cpnid2,a.cpphone2,
				a.createdby,a.createddatetime,a.modifiedby,a.modifieddatetime,a.active,a.renewal,
				a.renewalfromid,a.shopoccupied,
				b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size,
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
			
			//create tenant view
			$viewSql =  "create view view_offerletter_tenant as ".$sqlv1;
			$result = mysql_query($viewSql);
						
			$sqlv2= "select a.tenantmasid,a.cpname,a.cptypemasid,a.cpnid,a.cpmobile,a.cplandline,a.cpemailid,a.documentname from mas_tenant_cp a
				inner join mas_tenant b on b.tenantmasid = a.tenantmasid
				WHERE a.tenantmasid =$tenantmasid and  b.companymasid=$companymasid and  a.documentname='1' and b.active='1'
				union
				select a.tenantmasid,a.cpname,a.cptypemasid,a.cpnid,a.cpmobile,a.cplandline,a.cpemailid,a.documentname from mas_tenant_cp a
				inner join rec_tenant b on b.tenantmasid = a.tenantmasid
				WHERE a.tenantmasid =$tenantmasid and  b.companymasid=$companymasid and  a.documentname='1' and b.active='1';";
			
			//create tenant_contact person view
			$viewSql =  "create view view_tenant_cp as ".$sqlv2;
			$result = mysql_query($viewSql);
			
			// create tenant rent tbl view
			$viewSql = "create view view_offerletter_rent as select b.*,DATE_FORMAT( b.fromdate, '%d-%m-%Y' ) as 'rent_fromdate' ,DATE_FORMAT( b.todate, '%d-%m-%Y' ) as 'rent_todate' from \n"
			    . "trans_offerletter a\n"
			    . "inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid\n"
			    . "WHERE a.tenantmasid =".$tenantmasid;
			$result = mysql_query($viewSql);
			
			// lease start date end date
			$lease_fromdate="";$lease_todate="";
			$leasedatesql = "select date_format(min(fromdate),'%d-%m-%Y') as leasefromdate, date_format(max(todate),'%d-%m-%Y') as leasetodate from  trans_offerletter a
					inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
					WHERE a.tenantmasid ='$tenantmasid';";
			$result = mysql_query($leasedatesql);
			while ($row = mysql_fetch_assoc($result))
			{
				$lease_fromdate= $row['leasefromdate'];
				$lease_todate= $row['leasetodate'];
			}
			
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
			//$sql = "SELECT * FROM view_offerletter_company";
			$test1 = "SELECT * FROM mas_company where companymasid ='$companymasid' ";
			$result = mysql_query($test1);
			//$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$company_poboxno = $row['poboxno'];
				$companyaddress = $row['address1']."<br> ".$row['address2']."<br>".
						"P.O.Box No: ".$row['poboxno'].",".$row['city']."<br>".
						$row['state'].",".$row['pincode']."<br>".
						$row['country'];
			        $companycity = $row['city'];
					$company_areacode = $row['pincode'];
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
				if($poboxno !="")
				{
					preg_match('/\d+/', $poboxno, $number);  // select only mo's				
					$poboxno = $number[0];
				}
				//$companycity = $row['city'];
				$tenant_poboxno = $poboxno;
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

 
$div0="<span id='span'>Print Preview Lease for  <font color='blue'>$tenantname ($tenantcode)</font>&nbsp;
	<button type='button' id='btnPreview' name='0'>Save & Print</button></span>
        <p class='printable' style='fonr-face='verdana;color=red;'><table width='100%' border=0><tbody><tr><td>";
$div1="";
	
$sql = "select * from rpt_lease where grouptenantmasid =$groupmasid";
$result = mysql_query($sql);
$rowcount = mysql_num_rows($result);

if($rowcount >0)
{
	$sql = "delete * from rpt_lease where grouptenantmasid =$groupmasid";
	$result = mysql_query($sql);
	$rowcount =0;
}

if ($rowcount == 0)
{   
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

   $PDF_PAGE_FORMAT= 'A4';

    ////$pdf = new MYPDF('L', 'cm', 'A4', true, 'UTF-8', false);
    
    // create new PDF document
    //$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // remove default header/footer
    //$pdf->setPrintHeader(false);
    ////$pdf->setPrintFooter(false);   
    ////$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    
    // set header and footer fonts
    //$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // set default monospaced font
    //$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // set default footer margin
    //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    //set auto page breaks
    ////$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // set default font subsetting mode
    //$pdf->setFontSubsetting(true);
    
    // main page 1
    //$pdf->AddPage();
    //$pdf->SetFont('dejavusans','B',16);
    //$pdf->Cell(40,10,'DATED THE');
    //$pdf->Cell(50);
    //$pdf->Cell(40,10,'DAY OF');
    //$pdf->Cell(50);
    //$pdf->Cell(40,10,date('Y'));
    //$pdf->Cell(40);        
    //$pdf->ln(60);
    
    //$pdf->SetFont('dejavusans','BU',22);
    //$pdf->Cell(0,10,'SUB-LEASE',0,0,'C');
    //$pdf->ln(30);
    //$pdf->Cell(0,10,$companyname,0,0,'C');
    //$pdf->ln(30);
    //$pdf->SetFont('dejavusans','B',18);
    //$pdf->Cell(0,10,'AND',0,0,'C');
    //$pdf->ln(30);
    //$pdf->SetFont('dejavusans','BU',17);
    //$pdf->Cell(0,10,$tenantname,0,0,'C');
    if($tradingname =="")
    {
	//$pdf->ln(30);
    }
    else
    {
	//$pdf->SetFont('dejavusans','B',13);
	//$pdf->ln(15);
	//$pdf->Cell(0,10," (T/A) ",0,0,'C');
	//$pdf->ln(15);
	//$pdf->Cell(0,10,$tradingname,0,0,'C');
	//$pdf->ln(15);
    }
    
    //$pdf->SetFont('dejavusans','B',14);
    //$pdf->Cell(0,10,$buildingmunicipaladd,0,0,'C');
    //$pdf->ln(30);
    //$pdf->SetFont('dejavusans','BU',22);
    //$pdf->Cell(0,10,$buildingname,0,0,'C');
    //$pdf->ln(30);
    //$pdf->SetFont('dejavusans','B',14);
    //$pdf->Cell(0,10,$shops,0,0,'C');
    
    ////content page 2  
    //$pdf->AddPage();
    
    //$pdf->SetFont('dejavusans','B',9);    
    //$pdf->Cell(5,10,'DATE RECEIVED FOR REGISTRATION');
    //$pdf->Cell(70);
    //$pdf->Cell(40,10,'PRESENTRATION BOOK No:');
    //$pdf->Cell(25);
    //$pdf->Cell(40,10,'REGISTRATION FEES:');
    //$pdf->ln(12);    
    //$pdf->Cell(5,10,'-----------------------------');
    //$pdf->Cell(70);
    //$pdf->Cell(40,10,'----------------------------');
    //$pdf->Cell(25);
    //$pdf->Cell(40,10,'KSHS PAID-----------------------');
    //$pdf->ln(8);
    //$pdf->Cell(5,10,'');
    //$pdf->Cell(70);
    //$pdf->Cell(40,10,'');
    //$pdf->Cell(25);
    //$pdf->Cell(40,10,'RECEIPT NO---------------------');
    //$pdf->ln();
    //$pdf->SetFont('dejavusans','BU',10);
    //$pdf->Cell(0,10,'REPUBLIC OF KENYA',0,0,'C');//$pdf->ln(6);
    //$pdf->Cell(0,10,'IN THE MATTER OF THE LAND ACT NO.6 OF 2012',0,0,'C');//$pdf->ln(6);
    //$pdf->Cell(0,10,'IN THE MATTER OF THE LAND REGISTRATION ACT NO.3 OF 2012',0,0,'C');//$pdf->ln(6);    
    //$pdf->Cell(0,10,'AND IN THE MATTER OF THE REGISTERED LAND ACT CAP 300',0,0,'C');//$pdf->ln(6);    
    //$pdf->Cell(0,10,'(REPEALED)',0,0,'C');//$pdf->ln(6);
    //$pdf->Cell(0,10,"TITLE ".$buildingmunicipaladd,0,0,'C');//$pdf->ln(10);
    //$pdf->Cell(0,10,'SUB-LEASE',0,0,'C');//$pdf->ln(10);
    //$pdf->Cell(0,10,'MEMORANDUM',0,0,'L');//$pdf->ln(12);
    //$pdf->SetFont('dejavusans','',9);
    
    $txt ="         Form of Sub-lease approved Under Section 108 of the Registered Land Act (Now Repealed) Under reference [CLR/R 24/VOL LXXV/99] and Adopted for use pursuant to Section 108 of the Land Registration Act 2012 to conform with the Land Registration Act 2012 and the Land Act 2012.";    
    //$pdf->MultiCell(0, 6, $txt."\n", 0, 'J');//$pdf->ln(3);
    
    $html = "<span  style='text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;'>
    We, <strong>".strtoupper($companyname)."</strong> a company incorporated in the Republic of Kenya with a limited liability and of Post Office Box Number 2501-40100 KISUMU in the Republic of Kenya (hereinafter called 'the Lessor' which expression shall where the context so admits or requires include its successors and assigns) HEREBY LEASE to M/S <strong>".strtoupper($tenantname)."</strong> ".$leaseclass." and of $tenantshortaddress in the Republic of Kenya (hereinafter called 'the Lessee' which expression shall where the context so admits or requires include their successors and assigns) ALL THAT area forming part of the building  known as ".strtoupper($buildingname)." erected on the above mentioned titles and specifically the <strong>".rtrim($shopid,',')." at ".strtoupper($buildingname)." </strong>and the same is more particularly delineated in red on the plan annexed hereto comprising a total of <strong>".$sizetotal." square feet </strong>approximately (hereinafter called 'the demised premises') TO BE HELD by the Lessee as tenant for a term of <strong>$leaseterm</strong>  from the <strong>$doc</strong> YIELDING AND PAYING therefore and thereat during the said term:-</span>";
    //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    //$pdf->ln(2);
    
    $txt ="The rent (excluding service charge) without deductions will be:-";    
    //$pdf->MultiCell(0, 6, $txt."\n", 0, 'J');//$pdf->ln(3);
        
    //$pdf->writeHTML($tenantRent, true, false, true, false, '');


    $html = "<b><u>For the Space of $sizetotal Square Feet:-</u></b>";
    //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    //$pdf->ln(6);
    
    $html = '<span  style="text-align:justify;letter-spacing:+0.100mm;font-stretch:100%;">
    In all cases payable clear of all deductions by <b>'.$rentdesc.'ly</b> payments in advance on the first day of
    each $rentdesc in every year without any deduction whatsoever except as authorized by any statutory
    enactment for the time-being in force. The Lessee shall deposit with the Lessor a <b> deposit of KShs.'.number_format($totalDeposit, 0, '.', ',').'/=</b>
    to be held by the Lessor during the period of the lease free of interest as security for the payment of rent,
    service charge, undertaking repairs that may have to be carried out within the demised premises and the performance
    of all the Lessee'."'".'s obligations and covenants herein contained. If the rent or any other monies payable by the Lessee
    under this lease shall not be paid on its due date the Lessee shall pay to the Lessor penalty for late payment on the
    said monies to be calculated at'. $latefeeinterest.'% per month until payment thereof in full <u>SUBJECT HOWEVER</u> to the following terms and
    conditions and covenants:-</span>';
    //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    //$pdf->ln(6);

    
    //$pdf->SetFont('dejavusans','B',9);
    $txt ='1)       THE LESSEE TO THE INTENT THAT THE OBLIGATIONS HEREINAFTER SET OUT MAY CONTINUE THROUGHOUT THE CONTINUANCE OF THE TERM HEREBY CREATED COVENANTS AND AGREES WITH THE LESSOR AS FOLLOWS:';    
    //$pdf->MultiCell(0,10, $txt."\n", 0, 'J');
    //$pdf->ln(6);    
    //$pdf->SetFont('dejavusans','',8.8);
    $html ='<ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-alpha;">
            <li>To pay the rent herein before reserved at the time and manner aforesaid and to pay all other sums as provided in the Lease;
                <br>
            </li>
            <li>To bear and pay all rates taxes and other charges of every nature and kind which now are or may hereafter be assessed or
                imposed on the said premises or any part thereof or on the Lessor or the Lessee in respect thereof  or by the Government of
                Kenya or any Municipal Township Local or other Authority the Head rent payable under the Valuation for Rating Act (Chapter 266) and the
                Rating Act (Chapter 267) or any Act or Acts amending or replacing the same only excepted PROVIDED ALWAYS that if in respect of any year of the said
                term the rate or rates payable under the said Acts or either of them shall be increased beyond that or those payable in respect of the year
                <b>'.date('Y').'</b> the Lessee will forthwith on demand pay to the Lessor a proportionate share of such increase;
                <br>
            </li>
            <li>To pay to the Lessor in addition to the said rent by way of reimbursement to the Lessor of the operating
                expenses of <b>'.strtoupper($buildingname).'</b> a SERVICE CHARGE to be calculated as a percentage which the floor space of the demised premises
                bears to the total space of <b>'.strtoupper($buildingname).'</b> available for leasing. Such service charge shall be paid MONTHLY IN ADVANCE TOGETHER
                WITH BUT NOT INCLUDED IN THE RENTALS hereby reserved PROVIDED THAT the minimum of such service charge shall be
                <strong>10% of the monthly rent </strong> herein reserved AND PROVIDED THAT in the event that the sums paid by the Lessee under this clause is
                less than the actual sum of service charge expended by the Lessor as proved by the Lessors annual audited accounts
                (AND for the purposes of this sub-clause the parties hereunto agree that the statement of the Lessor'."'".'s auditors as to
                the amount expended in respect of service charge shall be final and conclusive) the Lessee shall within seven (7)
                days from the date of receipt of a demand from the Lessor reimburse to the Lessor the difference between the sums
                already paid by the Lessee and the actual aggregate amount expended by the Lessor in providing such service
                PROVIDED ALWAYS the said service charge shall be charges in operational expenses for <b>'.strtoupper($buildingname).'</b> in respect of the
                aggregate amount from time to time expended but not limited to :-<br><br>                
                Service Charge Deposit without deductions:-<br>'.$tenantSc.'
                (i)	Cost of all water used in the common areas.<br><br>
                (ii)	Cost of all the electricity consumed by security lights and in the common areas.<br><br>
                (iii)	Cost of providing security services.<br><br>
                (iv)	Cost of cleaning the property'."'".'s common areas.<br><br>
                (v)	Premium payable for fire and public liability insurance.<br><br>
                (vi)	Management expenses.<br><br>
                (vii)	Cost of maintaining and re-decorating the common areas.<br><br>
                (viii)	Cost of internal repairs and maintenance of the demised premises including cost of the repairs and replacements of a structural nature of the building.<br><br>
                (ix)	Cost of maintaining the fire fighting systems and any other mechanical systems or installations. <br><br>
                (x)	Rates and ground rent.<br><br>
                (xi)	Book-keeping and audit fees for the rent and service charge account.<br><br>
                (xii)	Cost of refuse collection.<br><br>
                (xiii)	Cost of pesticides and sanitary services.<br><br>                
                (xiv)	Any other cost which the lessor may deem fit in its sole discretion to levy as service charge from time to time.
                <br>                
            </li>
             <li>To pay the rent herein before reserved at the time and manner  aforesaid without any deductions and the rent shall be subject to Value
		Added Tax and any other statutory taxes which may be levied on the rent, service charge and on any other sums payable
		herein and to pay all other sums as provided in the sub- lease;
                <br>
            </li>
            <li>To insure and keep insured all plate glass (if any) forming part of the demised premises against breakage
                damage or destruction in full replacement value thereof in joint names of the Lessee and the Lessor with some insurance
                company or with underwriters of good repute to be approved by the Lessor and pay all premiums necessary for maintaining
                such insurance within seven (7) days after the same shall become due and payable AND to produce to the Lessor on demand
                the policy of such and the receipt for each such payment and to cause all money received by virtue of any such insurance
                to be forthwith laid out in reinstating the said glass with glass of the same quality and thickness and to make up any
                deficiency out of the Lessee'."'".'s own money PROVIDED ALWAYS that if the Lessee shall at any time fail to keep such
                insurance on foot the Lessor shall be at liberty to do all things necessary to effect and maintain such insurance and
                any moneys expended by the Lessor for the purpose shall be payable by the Lessee on the date fixed for payment of the
                rent next after the demand for the same shall have been made to the Lessee and shall be recoverable from the Lessee as
                rent;
                <br>
                <ol style="list-style-type: lower-roman">
                    <li>To pay to the appropriate water authority any sums or charges payable in respect of the installation of any
                    separate supply of water and water meter installed by or at the request of the Lessee in the demised premises
                    and in respect of water consumed thereon and to observe and perform all regulations and to keep the Lessor
                    indemnified in respect thereof OR; <br>
                    </li>
                    <li>To pay the Lessor in addition to the said rent by way of reimbursement to the Lessor all water charges in
                    respect of water consumed by the Lessee in the said demised premises the said water charges to be computed
                    according to an appropriate method adopted by the Lessor;
                    </li>
                </ol>
            </li>            
             <li>To pay to the appropriate telephone authority the costs of installing and connecting such telephones as the
		Lessee may require on the demised premises and all rentals and other charges payable in respect of
                of such telephones and its use in respect of the term hereby granted;
                <br>
                <ol style="list-style-type: lower-roman">
                    <li>To pay to the appropriate electricity authority any sums or charges payable in respect of the installation
                        of any separate supply of electricity and electricity meter installed by or at the request of the Lessee in the demised
                        premises and in respect of electricity consumed thereon and to observe and perform all regulations and to keep the
                        Lessor indemnified in respect thereof;<br>
                    </li>
                    <li> To pay the Lessor in addition to the said rent by way of reimbursement to the Lessor all electricity charges in
                        respect of electricity consumed by the Lessee in the said demised premises the said electricity charges to be computed
                        according to an appropriate method adopted by the Lessor;<br>
                    </li>
                </ol>
                 <br>
            </li>             
            <li>Not to install any equipment with a capacity above 4 Kilowatts or 13 Amperes at 240 volts or to
                load any socket outlet sub-circuit above the capacity of 13 Amperes without the prior consent
                of the Lessor in writting who shall be entitled as condition of giving such consent to require the Lessee to pay such
                additional sum of money as may suffice to cover installation and/or
                additional installation and the additional charges for electricity caused by such use;<br>
            </li>
            <li>To keep the interior of the demised premises including all
                doors, windows, floors, ceilings, glass, fanlights, sanitary and water apparatus and fittings clean
                and in good repair order and condition (fair wear and tear and all acts of God only excepted) also to make good any stoppage of or damage to drains caused or suffered by the Lessee or a member of its servants licensees or visitors and at the expiration or soon determination of the term hereby granted (and subject to sub-clause 1(k) below) peaceably and quietly yield up the demised premises to the Lessor in such state of repair order and condition as the same were at the commencement of the said term (excepting only as aforesaid) and with all locks and keys and fastenings complete;<br>
            </li>
             <li>Not to drive any nails screws bolts or wedges in the floors, walls or ceilings of the premises nor injure cut
		or maim any of the walls, floors, ceilings, doors, windows, fixtures, fittings or fastenings of the premises
		without the prior consent in writing of the Lessor AND will not make any exterior alterations in
		the outside appearance of the premises or permit or suffer any of the foregoing to be done
		PROVIDED ALWAYS that in any case where works shall have been carried out by the Lessor at the request of the Lessee in pursuant of
                this sub-clause the Lessee shall if the Lessor so requires reimburse to the Lessor on demand the expense for restoring
                the affected portion of the premises to its or their former condition at the expiration or soon determination of the said
                term AND PROVIDED ALSO that the Lessee will at the sole cost and expense of the Lessee and subject to the prior written
                consent of the Lessor having been obtained to such work (in respect of which the Lessee shall submit to the Lessor such
                dimensions architectural and (if necessary) electrical drawings as the Lessor may require for the purpose of considering
                the application and (if approved) on carrying of the work erect such additional partitions similar to those originally
                installed by the Lessor on or before the commencement of the said term as shall be desired by the Lessee for the Lessee'."'".'s business
                together with all the requisite shelves and counters and other fixtures hereinafter collectively referred to as '."'".'
                the Lessee'."'".'s fixture'."'".'s but the same shall prior to the expiration or sooner determination of the said term (unless otherwise agreed with the Lessor in writing) be removed by the Lessee at the sole expense of the Lessee who shall also reimburse the Lessor on demand with all sums certified by the Lessor'."'".'s architects as being necessarily incurred in making good any damage occasioned by such installation or removal AND PROVIDED FURTHER that if the Lessor shall so consent as aforesaid the Lessee will duly apply to the proper authority for any necessary consent or permission which may be required and to produce such consent or permission when obtained to the Lessor;<br>
            </li>
            <li>To make good any damage caused to the building or to the demised premises by the removal by the Lessee or the
		Lessee'."'".'s servants, employees, agents or others of any furniture goods or other articles into or out of the
		buildings or to the demised premises or to its fixtures or resulting from fire explosion, air conditioning or
		electrical short circuits flow or leakage or water or steam by bursting or leaking of pipes or plumbing works
		or from any other cause of any other kind or nature whatsoever due to neglect, improper or negligent conduct or
		other cause attributable to the Lessee, the Lessee'."'".'s servants, employees, agents, visitors or licensees;<br>
            </li>
             <li>At the end of every second year and in the month before the determination of the said term (whenever determined)
		well and sufficiently to clean off if necessary and paint with two coats of plastic emulsion or other paint
		and in such manner and style and of such colour as the Lessor may in its uncontrolled discretion determine all
		the inside parts of the premises previously or usually painted and at the same time to wash distemper
		(with plastic distemper if the Lessor so requires) all such parts of the interior of the premises previously or
		usually washed or distempered or whitewashed and to clean off re-sand and polish or re-coat with polythene wood-seal all polished wood (if any) in a properly and workman like manner;<br>
            </li>
            <li>
                To permit the caretaker employed by the Lessor to enter the demised premises in the ordinary course of his duty ;<br>
            </li>
            <li>
                To permit the Lessor and any person authorized by it respectively after giving reasonable prior notice to the Lessee (or immediately in case of need) to enter upon the demised premise:-
                <br>
            </li>
            <li>
                To permit the Lessor and any person authorized by it respectively after giving reasonable prior notice to the Lessee (or immediately in case of need) to enter upon the demised premise:-
                <br>
                <ol style="list-style-type: lower-roman">
                    <li>
                        For the purpose of carrying out therein and effecting any repairs to the Buildings for which the Lessor may be liable under its covenants in that behalf hereinafter contained or which the Lessor may consider desirable or necessary and also to or on any adjoining or neighboring premises now or hereafter belonging to the Lessor PROVIDED ALWAYS that the rent hereby reserved shall not be in any way abated while such repairs or other things as aforesaid are being done nor shall the Lessor be liable to the Lessee in any way for loss or interruption of business of the Lessee arising there from or to it otherwise however;
                        <br>
                    </li>
                    <li>
                        To view the condition of the same and to give notice in writing to the Lessee of all wants of reparation for which the Lessee is liable AND within fourteen (14) days next after every such notice (or immediately in case of need) the Lessee shall make good all such wants of reparation and in default thereof shall permit the Lessor to execute the necessary work the cost of which including any surveyor'."'".'s fees incurred shall be paid by the Lessee to the Lessor on demand;
                        <br>
                    </li>
                </ol>
            </li>
             <li>
                To use the demised premises solely for <b>'.strtoupper($nob).'</b> and not to convert use or occupy or permit or suffer to be used the demised premises or any part thereof into or for any other purpose or business whatsoever and not to use the same for any residential purpose or for any illegal or immoral purposes and IT IS HEREBY DECLARED AND AGREED that upon breach by the Lessee of the terms of this clause the Lessor may thereupon at any time enter upon the demised premises and if the Lessor shall do so the term hereby created shall determine absolutely PROVIDED ALWAYS that in the event of such a determination of the term hereby created the Lessee shall remain liable to the Lessor for payment of all the rentals, service charge and or any other sum payable under the terms and conditions of this sub-lease  and for the entire period of the sub-lease.
                <br>
            </li>
             <li>
                Not to assign, transfer, sublet, charge or otherwise part with the possession of the demised premises or any part thereof without the written consent of the Lessor, and of the Chargees having a security over the Building first had and obtained AND IT IS HEREBY EXPRESSLY AGREED AND DECLARED by and between the parties hereto that upon any breach by the Lessee of this covenant and agreement it shall be lawful for the Lessor to reenter upon the demised premises without notice and thereupon the said term shall determine absolutely PROVIDED ALWAYS that in the event of such a determination of the term hereby created the Lessee shall remain liable to the Lessor for payment of all the rentals, service charge and or any other sum payable under the terms and conditions of this sub-lease  and for the entire period of the sub-lease AND IT IS HEREBY FURTHER EXPRESSLY AGREED AND DECLARED by and between the parties hereto that the Lessor shall be entitled to withhold its consent absolutely to any assignment transfer subletting or parting with possession of the demised premises by the Lessee if the result of such assignment transfer subletting or parting with possession would in the opinion of the Lessor (which shall be final) be to bring the tenure of the demised premises or the said term or any balance thereof within the protection of the Landlord and Tenant (shops, hotels and catering Establishments) Act (Chapter 301) or any Act or Acts for the time being in force amending or replacing the same or any similar Act. It shall not be implied in any circumstances that the consent of the Lessor or of an acceptance of any assignee, transferee, sub-Lessee or occupant as tenant or a release of the Lessee from the further performance by the Lessee of the covenants and agreements on the Lessee'."'".'s part herein contained will be or is liable to be forth-coming the Lessor hereby expressly reserving to themselves the right in their absolute and uncontrolled discretion and without assigning any reason therefore to withhold their consent if they consider that either their interest or those of the other tenants of the buildings would be impaired by giving the same nor shall the consent by the Lessor to any assignment transfer or subletting be in anyway construed as relieving the Lessee from obtaining the express consent in writing of the Lessor to any further assignment, transfer or subletting. If the Lessor shall give such consent the instrument of assignment shall be prepared by the Lessor'."'".'s advocate and executed by the parties and all costs in connection with the preparation and completion thereof including the Advocates costs, stamp duties and registration fees shall be borne by the Lessee  AND IT IS HEREBY FURTHER AGREED THAT where the Lessee is a private limited company then for the purpose of this sub-clause a transfer of the beneficial interest in more than fifty percent (50%) of the issued share capital of the Lessee or allotment or issue or transfer of any share of the Lessee to anyone other than any current member of the Company shall be deemed to constitute an assignment and transfer of the demised premises;
                <br>
            </li>
            <li>
                To comply forthwith in all respect with the provisions of every enactment ( which expressions in this sub  clause includes every Act of parliament now or hereinafter enacted and every instrument regulation and by-law and every notice, order or direction and every license consent or permission made or given there under so far as the same shall effect the demised premises and to indemnify the Lessor in respect of all such matters as aforesaid;
                <br>
            </li>
            <li>
                To supply a copy to the Lessor of any notice or discretion or license consent or permission relating to the demised premises within seven (7) days of the receipt thereof by the Lessee;
                <br>
            </li>
             <li>
                To perform and observe and also procure performance and observance by the Lessee'."'".'s servants, agent,s licensees and invitees of the rules and regulations (including but not limited to regulations as to the opening and closing of the entrance doors) as the Lessor may make from time to time for the management of the demised premises or of the building .The Lessee shall accept as final and binding the decision of the Lessor upon any matter arising out of such rules and regulations provided the same is reasonable;
                <br>
            </li>
            <li>
                Not to permit or suffer to be done in or upon the demised premises or any part thereof anything which would or might be or become a nuisance, annoyance, inconvenience or disturbance to any person whatsoever and to indemnify the lessor against any costs,charges and expenses incurred by the Lessor in abating such nuisance and execution of all such works as may be necessary for abating a nuisance or for remedying such nuisance;
                <br>
            </li>
            <li>
                To indemnify the Lessor against any such actions, claims or demands arising out of leakage or overflow of water or any noxious substance from the demised premises;
                <br>
            </li>
            <li>
                That no signboard, advertisement, placard, neon sign or name place shall be exhibited on the exterior of the demised premises (or the interior thereof so as to be visible from the outside ) except such as shall have been previously approved by the Lessor and that no articles shall be hung or exposed outside the demised premises;
                <br>
            </li>
            <li>
                Not to permit any internal combustion fires to be burned in the premises;
                <br>
            </li>
            <li>
                That nothing shall be brought or done upon the demised premises which may invalidate or render any additional premium for any insurance of the building or of any adjoining premises and in case of any additional premium becoming payable the amount thereof shall be repaid by the Lessee to the Lessor on demand;
                <br>
            </li>
            <li>
                That no fore court staircase lift or passageway leading to the demised premises shall be damaged or obstructed or used in such manner as to cause in the opinion of the Lessor any nuisance damage or annoyance;
                <br>
            </li>
             <li>
                Where applicable no goods or furniture or other equipment shall be carried in the lifts (if any) of the building unless previous arrangements have been made with the caretaker of the building in respect of such carriage AND not to allow or suffer or permit in any circumstance the total weight of any one load in any such passenger lift or lifts to exceed the margin of the safety prescribed therefore AND ALSO to observe at all times the rules which may be made by the Lessor from time to time for the operation of such lift or lifts;
            </li>
        </ol>        
        <ol class="customlist" align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type:none;">
             <li>
                aa)     Where applicable to use the yard provided by the Lessor for loading and unloading only and not for parking and not to permit the same to be used by vehicles of carrying capacity of more than three quarters of a ton;
                <br>
            </li>
            <li>
                bb)     Not to load the floors beyond a proper margin of safety (which shall be the sole responsibility of the Lessee to ascertain);
                <br>
            </li>
            <li>
                cc)     To indemnify the Lessor against all actions claims and demands arising or resulting directly or consequently from exceeding at any time the maximum floor stress on the demised premises;
                <br>
            </li>
            <li>
                dd)     Not to permit or suffer to be done anything whereby any insurance of the building against loss or damage by fire may become void or voidable or whereby the rate of premium for any such insurance may be increased and without prejudice to the generality of the foregoing not to store nor permit or suffer to be stored upon the demised premises any inflammable material other than such as may be permitted by the by  laws of the appropriate Municipal Authority and the insurers of the Lessor and to repay to the Lessor all sums paid by way of additional or increased premiums and expenses incurred by it in or about such insurance or the renewal thereof rendered necessary by a breach of the covenants AND in the event of any insurance moneys being withheld or becoming wholly or partially irrecoverable by reason of any breach of this covenant to indemnify the Lessor in respect of costs of rebuilding or re-instating the building or any part thereof;
                <br>
            </li>
            <li>
                ee)     That except with the previous consent in writing of the Lessor and in accordance with drawings and specifications previously submitted to any architect approved by the Lessor at the cost of the Lessee, no alteration or addition whatsoever shall be made in or to the demised premises PROVIDED ALWAYS that the Lessor may as a condition of giving any consent require the Lessee to enter into such covenants with the Lessor as the Lessor shall reasonably require in regard to the execution of any alteration or addition to the demised premises and the reinstatement thereof at the determination of the term hereby granted or otherwise;
                <br>
            </li>
            <li>
                ff)     That no window or other opening belonging to the demised premises or any adjacent premises shall be stopped or darkened or obstructed and no new window, doorway, or other opening or path passage drainage or encroachment or easement shall be made into against or upon the demised premises AND in case any such window, doorway, opening, path, passage, drain or other encroachment or easement shall be made or attempted to be made by any third party then the Lessor may adopt such means as may be reasonably be required for preventing any encroachment or the acquisition of such easement by any third party;
                <br>
            </li>
            <li>
                gg)     No payments by the Lessee howsoever made referable or on account of a period subsequent to the determination of the term hereby created (whether by effluxion of time or otherwise) shall constitute, deemed or be construed as payment or acceptance of rent and the same shall not have the effect of creating a tenancy of the demised premises in favour of the Lessee except where a lease or tenancy in favour of the Lessee is expressly and in writing created and entered into by the Lessor;
                <br>
            </li>
            <li>
                hh)     To pay all costs, charges and expenses (including Advocates costs and surveyors fees) incurred by the Lessor for the purpose of or incidental to the preparation and service of a schedule of dilapidation at the determination of the term hereby granted;
                <br>
            </li>
            <li>
                ii)     To install at the Lessee'."'".'s own expense in the demised premises such firefighting equipment and appliances as shall be necessary or required and approved by the Lessor if in the opinion of the Lessor (which opinion shall be final) the trade business or occupation of the Lessee is such as to necessitate such additional equipment and appliances over and above that and those supplied by the Lessor (if any);
                <br>
            </li>
            <li>
               jj)      If by reason of any act or omission of the Lessee, Lessee'."'".'s agents, servants, employees, licensees or visitors the '."'".'sprinkler system'."'".' now or at any time hereafter installed by the Lessor the building or any of the appliances belonging to such '."'".'sprinkler system'."'".' shall be damaged or not be in proper working order then the Lessee shall forthwith at the Lessee'."'".'s own cost and expense restore the same into good working order and condition AND if the Chief Fire Officer of the ".$buildingcouncil." or any other officer of competent jurisdiction or the insurers for the time being of the Lessor shall require or recommend that any charges or modifications, alterations or additional sprinkler heads or other equipment be made or installed or supplied or if any such changes, modifications, alterations additional sprinkler heads or other equipment become necessary to prevent the imposition of any penalty or charge against the full allowances of a '."'".'sprinkler system'."'".' in the fire insurance rate as fixed by the Lessor'."'".'s insurers the Lessee shall at the Lessee'."'".'s own expense promptly make and supply such changes modifications alterations additional sprinkler Heads or other equipment ;
                <br>
            </li>
            <li>
               kk)      Not to hold or permit or suffer to be held any sale by auction on the demised premises;
                <br>
            </li>
            <li>
               ll)      To ensure that at no time does the Lessee'."'".'s use of electric current on the demised premises exceed the capacity of the existing wiring installation in the building;
                <br>
            </li>
            <li>
               mm)      To perform and observe all covenants, agreements, conditions, restrictions, stipulations and provisions contained in the Grant (as to all which the Lessee shall be deemed to be aware of )under which the said piece of land upon which the building is erected is held and not at any time to do or permit or suffer anything whereby the title to the said piece of land may be avoided or forfeited AND at all times keep indemnified the Lessor and its successors and assigns from  and against all actions, proceedings, costs, damages, claims, demands and liability for or in respect of any breach which may be committed during the said term or any of the said covenants, agreements, conditions, restrictions, stipulations and provisions;To ensure that at no time does the Lessee'."'".'s use of electric current on the demised premises exceed the capacity of the existing wiring installation in the building;
                <br>
            </li>
            <li>
               nn)      To permit the Lessor or its agent or agents at any time during the three (3) months immediately preceding the termination of the term of this lease to enter upon the demised premises and to affix and retain upon any part of the demised premises a notice for re-letting the same and to permit all persons authorized by the Lessor or its agent or agents to view the demised premises at reasonable hours in the day time without interruption and if during the last month of the lease the Lessee shall have removed all or substantially all  of the Lessee'."'".'s properties from the demised premises the Lessor may re-enter the demised premises without being liable to make any abatement in the rent hereby reserved and without incurring any liability to the Lessee for any compensation and any such acts of the Lessor shall have no effect upon this lease ;
                <br>
            </li>
            <li>
               oo)      To give immediate notice to the Lessor in case of fire or accidents in the demised premises or in the building and of all defects therein or in any fixtures or equipment herein ;
                <br>
            </li>
            <li>
               pp)      To pay and make good to the Lessor all and every loss and damage whatsoever and howsoever incurred or sustained by the Lessor as a consequence of every breach or non-observance of the Lessee'."'".'s covenants herein contained and to indemnify the Lessor and the Lessor'."'".'s estate and effects from and against all actions, claims, liability costs and expenses hereby arising ; 
                <br> <br> 
            </li>            
        </ol>';

    //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    //$pdf->ln(2);
    
    //$pdf->SetFont('dejavusans','B',9);
    $txt ='2)       THE LESSOR HEREBY COVENANTS WITH THE LESSEE AS FOLLOWS:';    
    //$pdf->MultiCell(0,10, $txt."\n", 0, 'J');
    //$pdf->ln(5);
   
    //$pdf->SetFont('dejavusans','',8.8);
    $html ='<ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-alpha;">
            <li>
            Subject to clause 1 (b) the Lessor will punctually pay the rates, taxes and assessments including the ground rent and site value in respect of the demised premises;
                <br>
            </li>
            <li>
            That the Lessor will insure and keep insured the demised premises against fire in a sum sufficient to cover the current market value of the building.
                <br>
            </li>
            <li>
            That the Lessee paying the rent hereby reserved and performing and observing the several covenants on its part herein contained shall peaceably hold and enjoy  the demised premises during the said term without any interruption by the Lessor or by any person rightfully claiming under or in trust of the Lessor.
                <br>
            </li>
             <li>
             To provide and maintain security services by employing security guards for '.$buildingname.' and the car park thereof (if any) PROVIDED such provision of security services shall not act as a warranty or guarantee on the part of the Lessor to indemnify the Lessee for injury, damage or loss caused by burglary, theft or otherwise howsoever caused and the Lessee is therefore under an obligation to take out requisite insurance cover against such injury, loss or burglary as it deems necessary.
                <br> <br> 
            </li>
    </ol>';
   
    //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    //$pdf->ln(2);
    
    //$pdf->SetFont('dejavusans','B',9);
    $txt ='3)       PROVIDED ALWAYS AND IT IS HEREBY AGREED BY AND BETWEEN THE PARTIES THAT';    
    //$pdf->MultiCell(0,10, $txt."\n", 0, 'J');
    //$pdf->ln(6);
    
   //$pdf->SetFont('dejavusans','',8.8);
    $html ='<ol align="justify" style="letter-spacing:+0.100mm;font-stretch:100%;list-style-type: lower-alpha;">
            <li>
            If the rent hereby reserved or any part thereof shall at any time be unpaid for Seven (7) days after becoming payable (whether lawfully demanded or not) or if any covenant on the part of the Lessee herein contained shall not be performed and observed or if the Lessee (being a company) in whom for the time being the term hereby created shall be vested to go into liquidation whether compulsory or voluntary or if the Lessee being a person or persons in whom for the time being the term hereby created be vested shall become bankrupt or enter into any agreements with his or her creditors for liquidation of  his ,her ,their debts by composition or otherwise or suffer any distress or process of execution to be levied upon his or her goods then in any of the said cases it shall be lawful for the Lessor at any time thereafter re-enter andor levy distress upon the demised premises or any part thereof  in the name of the whole by any action or proceeding or by force or otherwise and to enjoy them in their former estate and thereupon this tenancy shall  absolutely determine but without prejudice to the right of action of the Lessor in respect of any antecedent breach of any of the agreements on the part of the Lessee herein contained AND the Lessee hereby waives any rights to notice or re-entry or forfeiture under any law for the time being in force PROVIDED ALWAYS that in the event of such a determination of the term hereby created the tenant shall remain liable to the Landlord for payment of all the rentals, service charge andor any other sum payable under the terms and conditions of this agreement  and for the entire period of the agreement.
                <br>
            </li>
            <li>
            If the Lessee shall make default in paying any sum referred to in this lease such sum shall be recoverable (whether formally demanded or not) as if the same were in arrears AND the power of the Lessor to levy distress upon the demised premises for rent or any other outstanding sum in arrears including such sum as aforesaid shall extend to and include any Lessees fixtures and fittings not otherwise distrainable by law which may from time to time be thereon.
                <br>
            </li>
            <li>
            If the demised premises are so damaged or destroyed by fire so as to be unfit for occupation or use in whole or in a part and the insurance in respect thereof has not become vitiated by any act or omission of the Lessee then the rent hereby reserved or a proper proportion thereof according to the extent of the damage shall from the date of such damage or destruction be suspended and in such an event the lease shall determine unless otherwise agreed between the Lessor and the Lessee in writing. 
                <br>
            </li>            
            <li>
            The Lessor the owners and builders of the building shall not be liable for any loss, damage or injury to the Lessee, the family, employees, servants, agents, visitors or licensees of the Lessee or the property of any such persons caused by:-
                <br>
                <ol style="list-style-type: lower-roman">
                    <li>
                        any defects in the demised premises or in the building or in any defect or electric wiring or of the installation thereof gas pipes stream pipes or from broken stairs or from bursting, leaking or running over of any tank,tub,washstand,water closet or waste pipe drains or any other pipe or tank in upon or about the demised premises nor from the escape of steam or hot water from any boiler or radiator provided the same is not attributable to any act or omission of the Lessor, the family, employees, servants, agents, visitors or licensees of the Lessor;
                        <br>
                    </li>
                    <li>
                        any defective or negligent working constructions or maintenance of the lifts(if any ) or the lighting or equipment of other parts of the structures of the building provided the same is not attributable to any act or omission of the Lessor, the family, employees, servants, agents, visitors or licensees of the Lessor;
                        <br>
                    </li>
                    <li>
                        any lack or shortage of  water, electricity or drainage;
                        <br>
                    </li>
                    <li>
                        any act or default (negligent or otherwise) of servants of the Lessor employed in any capacity whatsoever;
                        <br>
                    </li>
                    <li>
                        any act or default of any other Lessees or tenants of the building or any portion thereof including servants or agents or licensees of such other Lessees or tenants;
                        <br>
                    </li>
                    <li>
                        any burglary, theft or office breaking;
                        <br>
                    </li>
                    <li>
                        any fire explosion, falling plaster, steam, rain or leak from any part of the building or from the pipes, appliances or plumbing works or from the roofs or from any other place or by dampness however occurring provided the same is not attributable to any act or omission of the Lessor, the family, employees, servants, agents, visitors or licensees of the Lessor; PROVIDED that if any dispute under this sub-clause shall arise between the parties the matter shall be referred to an arbitrator to be appointed by the Chairman for the time being of the Chartered Institute of Arbitrators of Kenya whose award shall be final and binding upon the parties hereto and such reference shall be deemed to be Arbitration within the meaning of the Arbitration Act of 1995 or of any Act or Acts amending or replacing the same
                        <br>
                    </li>                    
                </ol>
            </li>
            <li>
            The Lessee shall indemnify the Lessor against all claims, actions and proceedings by the Lessee'."'".'s employees, servants, licensees, agents and others in respect of loss damage or injury;
                <br>
            </li>
            <li>
            Section 60 of the Land Act No.6 of 2012 Laws of Kenya is hereby excluded from this Lease. Sections 65 and 66 of the Land Act No.6 of 2012 shall be applicable to this lease in so far as they are not inconsistent or expressly varied under this Lease;
                <br>
            </li>
            <li>
            The Lessee shall not be entitled to any right of access or light or air to the demised premises which would restrict or interfere with the free user of any adjoining or neighboring premises for building or any other purpose;
                <br>
            </li>
            <li>
            Notwithstanding anything herein contained the Lessor shall not be liable to the Lessee nor shall the Lessee have any claim against the Lessor in respect of :-
                <br>
                <ol style="list-style-type: lower-roman">
                    <li>
                        any interruption in any of the services herein-before mentioned by reason of repair or maintenance or any installation or apparatus or damage thereto or destruction thereof by fire, water, act of God or other cause beyond the Lessor'."'".'s control or by reasons of mechanical or other defect or breakdown or frost or other inclement conditions or unavoidable shortage of fuel, material, water or labour;
                        <br>
                    </li>
                    <li>
                        any act or omission or negligence of any porter, attendant or other servant of the Lessor in or about the performance or purported performance of any day relating to the provision of the said services or any of them;
                        <br>    
                    </li>
                </ol>
            </li>
            <li>
            The Lessor and the Lessor'."'".'s agents have made no representations or promises with respect to the building or the demised premises save and except as herein expressly set forth .The taking of possession of the demised premises by the Lessee shall be conclusive evidence as against the Lessee that the Lessee accepts the same as they are and that the building, the demised premises, all fixtures, fittings and all equipment apparatus therein were in good and satisfactory condition at the time such possession was taken and that the taking of possession by the Lessee was upon reasonable prior inspection of the same;
                <br>
            </li>
            <li>
            Each and every of the Lessee'."'".'s covenants herein shall remain in full force both at law and in equity notwithstanding that the Lessor shall have waived or released in any manner whatsoever a similar covenant or covenants affecting other Lessees of the building;
                <br>
            </li>
            <li>
            The failure of the Lessor to seek redress for breach of or to insist upon the strict compliance of the terms of this lease or any of the rules and regulations shall not prevent the Lessor to act upon any subsequent act which would have originally constituted a breach and the receipt by the Lessor of any rent with the knowledge of such breach shall not be deemed to be a waiver of such breach;
                <br>
            </li>
            <li>
            No provision in this lease shall be waived or varied by either party hereto except by agreement in writing which document shall if in the case so required be duly registered in the Land Registry at <b>'.$buildingcity.'</b> at the sole cost and expense of the Lessee;
                <br>
            </li>
            <li>
            This lease shall be determined at the expiry of the term hereinbefore granted unless otherwise terminated. The lessee shall if it so desires, notify the lessor not less than three (3) months prior to the expiry of the term of its desire to renew the lease on such terms as may be agreed by the parties.
                <br>
            </li>
            <li>
            In the event of a determination of the sub-lease prior to the term hereby created the Lessee shall remain liable to the Lessor for payment of all the rentals, service charge andor any other sum payable under the terms and conditions of this sub-lease and for the entire period of the sub-lease.
                <br>
            </li>
            <li>
            The Lessor'."'".'s Advocate'."'".'s fees and disbursements, stamp duty and registration fees, surveyors fees (if any) in connection and preparation of this lease and two counterpart thereof shall be paid by the Lessee. If any such amount of legal costs remains unpaid (whether demanded or not) for a period of seven (7) days from the date of execution of this lease, then the Lessor'."'".'s Advocates, shall be at liberty to commence legal proceedings against the Lessee for recovery of the said legal costs on Advocate  Client basis under the Advocates (Remuneration) (Amendment) Order 2006, including but not limited to taxation of the Advocate-Client bill of costs against the lessee.  
                <br>
            </li>
            <li>
            All notices required under this lease shall be in writing and shall in the case of notice to the Lessee be sufficiently served if addressed to the Lessee and delivered to the Lessee at the demised premises or sent by pre-paid registered post. In case of notice to the Lessor be sufficiently served if addressed and delivered at it or its authorized agents or posted to it or such agent by registered post so that any notice so posted shall be deemed to have been served within Seven (7) days following the date of posting.
                <br>
                <ol style="list-style-type: lower-roman">
                    <li>
                The singular shall include the plural and all covenants and agreements expressed to be made by the Lessor or the Lessee shall where there are two or more persons included in the expressions '."'".' the Lessor'."'".' or (as the case may be) '."'".' the Lessee'."'".' be deemed to be made jointly and severally;
                    <br>
                </li>
                <li>
                    The word '."'".'monthly'."'".' means One (1) calendar month;
                    <br>
                </li>
                <li>
                The neuter gender shall include the masculine or the feminine where the  context so admits; 
                    <br>
                </li>
                <li>
                The expression '."'".'the demised premises'."'".' means the premises hereby demised including the fixtures, fittings, cleaning rooms, toilets, passage ways and rights and all alterations, additions and improvements thereto.
                    <br>
                </li>   
                </ol>
            </li>                    
    </ol>';
   
    //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    //$pdf->ln(2);
   
    
    
       
    
    
    //// Guranatee PDF Page
    $gpdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // remove default header/footer
    $gpdf->setPrintHeader(false);    
    
    // set header and footer fonts
    $gpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // set default monospaced font
    $gpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // set default footer margin
    $gpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    //set auto page breaks
    //$gpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // set default font subsetting mode
    $gpdf->setFontSubsetting(true);    
    
    $gpdf->AddPage();
    $gpdf->SetFont('dejavusans','B',16);
    $gpdf->Cell(40,10,'DATED THE');
    $gpdf->Cell(50);
    $gpdf->Cell(40,10,'DAY OF');
    $gpdf->Cell(50);
    $gpdf->Cell(40,10,date('Y'));
    $gpdf->Cell(40);        
    $gpdf->ln(60);
       
    $gpdf->Cell(0,10,$companyname,0,0,'C');
    $gpdf->ln();
    $gpdf->SetFont('dejavusans','BU',12);
    $gpdf->Cell(0,10,'( LESSOR )',0,0,'C');
    $gpdf->ln(30);
    $gpdf->SetFont('dejavusans','B',18);
    $gpdf->Cell(0,10,'AND',0,0,'C');
    $gpdf->ln(30);
    $leasenamelength = strlen($tenantname);
    if($leasenamelength <= 40)
    {
	$gpdf->SetFont('dejavusans','BU',17);
    }
    else
    {
	$gpdf->SetFont('dejavusans','BU',14);
    }
    $gpdf->Cell(0,10,$tenantname,0,0,'C');
    $gpdf->ln();
    $gpdf->SetFont('dejavusans','BU',12);
    $gpdf->Cell(0,10,'( LESSEE )',0,0,'C');
    $gpdf->SetFont('dejavusans','BU',17);
    if($tradingname =="")
    {
	$gpdf->ln(30);
    }
    else
    {
	$gpdf->SetFont('dejavusans','B',13);
	$gpdf->ln(15);
	$gpdf->Cell(0,10," (T/A) ",0,0,'C');
	$gpdf->ln(15);
	$gpdf->Cell(0,10,$tradingname,0,0,'C');
	$gpdf->ln();
	$gpdf->SetFont('dejavusans','BU',12);
	$gpdf->Cell(0,10,'( LESSEE )',0,0,'C');	
	$gpdf->ln(15);
	$gpdf->SetFont('dejavusans','B',13);
    }
    $gpdf->SetFont('dejavusans','BU',17);
    $grouptenant_contact = $_GET['grouptenant_contact'];
    $gpdf->Cell(0,10,$grouptenant_contact,0,0,'C');
    $gpdf->ln();    
    $gpdf->SetFont('dejavusans','BU',12);
    $gpdf->Cell(0,10,'( GUARANTORS )',0,0,'C');
    
    $gpdf->ln(30);
    $gpdf->SetFont('dejavusans','',8);
    $gpdf->Cell(0,10,'____________________________________________________________________________________________________________',0,0,'C');
    $gpdf->ln();
    $gpdf->SetFont('dejavusans','B',22);
    $gpdf->Cell(0,10,'DEED OF GUARANTEE',0,0,'C');
    $gpdf->ln();
    $gpdf->SetFont('dejavusans','',8);
    $gpdf->Cell(0,10,'____________________________________________________________________________________________________________',0,0,'C');
    $gpdf->ln(30);
    //$gpdf->SetFont('dejavusans','B',14);
    //$gpdf->Cell(0,10,$buildingmunicipaladd,0,0,'C');
    //$gpdf->ln(15);
    //$gpdf->SetFont('dejavusans','BU',22);
    //$gpdf->Cell(0,10,$buildingname,0,0,'C');
    //$gpdf->ln(30);
    //$gpdf->SetFont('dejavusans','B',14);
    //$gpdf->Cell(0,10,$shops,0,0,'C');
    
    $grouptenant_contact =$_GET['grouptenant_contact'];
    $gpdf->AddPage();    
    $gpdf->SetFont('dejavusans','',11);    
    $p1 ='<table width="100%" >
		<tr style="line-height: 2.0px;">		  
                    <td align="justify">
			THIS DEED OF GUARANTEE made the___________day of______________'.date('Y').'
			between <b>'.$companyname.'</b> a limited liability company incorporated in the
			Republic of Kenya and of <b> Post Office Box Number '.$company_poboxno.' - '.$company_areacode.' ,'.$companycity.'</b> in the said
			Republic (hereinafter called the Lessee which expression shall include and extend to
			its successors and assigns) of the one part and of <b> Post Office Box Number '.$tenant_poboxno.' - '.$pincode.','.$city.'</b>
			IN CONSIDERATION of your having agreed at our request and instance to grant a Lease to the
			within named Lessee <b>'.$tenantname.'</b>) of the Premises known as <b>'.$buildingname.'</b> and upon the
			within terms and conditions, we the undersigned  HEREBY JOINTLY AND SEVERALLY GUARANTEE the payment by the
			within named Lessee to you of the within rent and service charge and all monies payable under the
			terms of the Lease and the performance and observance by the <b>'.$tenantname.'</b> (Lessee) of the terms of the within written Lease
			upon the following conditions:--
                    </td>		
		</tr>
		</table>';
    $gpdf->ln(2); 
    $gpdf->writeHTML($p1, true, false, true, false, '');
    $gpdf->ln();
    
    $p2 ='<ol align="justify" style="line-height: 2.0px;letter-spacing:+0.100mm;font-stretch:100%;list-style-type:decimal;">
            <li>
		If <b>'.$tenantname.'</b> (Lessee) shall make default in payment of any rent, service charge or other monies payable under the terms of the Lease we will upon the written request by you pay you all the rent service charge and other monies which shall so be in arrears.
		<br>
            </li>
	    <li>
		If <b>'.$tenantname.'</b> (Lessee) shall make default in the performance or observance of any of the provisions on its part contained in within-written Lease we will pay you all losses, damage, expenses and costs which you shall be entitled to recover by reason of such default.
		<br>
            </li>
	    <li>
		This guarantee shall continue for the duration of the (<b>'.$lease_fromdate.'</b>  to <b>'.$lease_todate.'</b>) Lease and extend to the acts and defaults of the <b>'.$tenantname.'</b> (Lessee) during that period but during that period it shall not be revocable or discharged by the death of any one of us.
		<br>
            </li>
	     <li>
		We expressly agree that this Guarantee shall not be vacated or prejudiced by you neglecting or forbearing promptly to enforce or giving time for payment of the said rents or other monies due or delaying to take any steps to enforce the performance or observance of any of the provisions on the part of  <b>'.$tenantname.'</b> (Lessee) contained in the Lease.
		<br>
            </li>
	   </ol>';
    $gpdf->writeHTMLCell(0, 0, '', '', $p2, 0, 1, false, true, 'J', false);
    $gpdf->ln(2);
    
    
    $gpdf->AddPage();    
    //$txt ='IN WITNESS WHEREOF we have hereunto set our respective hands this___________day of______________'.date('Y').'';    
    //$gpdf->MultiCell(0,10, $txt."\n", 0, 'J');
    $gpdf->ln(5);
    $gpdf->SetFont('dejavusans','',11);
        
        $html ='<table width="100%" border="0" style="line-height: 2.0px;">
        <tr>
            <td colspan="2" style="height:25px;" align="justify"><u>IN WITNESS WHEREOF</u> we have hereunto set our respective hands this____day of___________ '.date("Y").'. <br><br></td>            
        </tr>        
	<tr>
            <td style="height:15px;">Signed by the said Guarantors</td>
            <td>]</td>
        </tr>	
        <tr><td style="height:15px;"></td><td>]</td></tr>
	<tr><td style="height:15px;"></td><td>]</td></tr>
	<tr>
            <td style="height:15px;">NAME: _____________________________<br>ID NO: _____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>]</td>
        </tr>        
	<tr><td style="height:15px;"></td><td>]</td></tr>
	<tr><td style="height:15px;"></td><td>]</td></tr>
	<tr>
            <td style="height:15px;">NAME:_____________________________<br>ID NO:_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>]</td>
        </tr>
	<tr><td style="height:15px;"></td><td>]</td></tr>
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
            <td colspan="2" style="height:60px;">I, ____________________________ certify that the above Licensee
            appeared before me on this _______ day of __________________ '.date("Y").' and being identified to me acknowledged the above signatures to be his/hers/theirs and that he/she/they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:35px;"><br><br><b>______________________________________<br><i>
                            Signature and designation of the person certifying</i>
        </b></td>
        </tr>
        </table>';  ;      
    
    
    $gpdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, false, true, 'J', false);
    $gpdf->ln(2);

    if($renewalfromid<=0)
        $filename = $tenantname." - (".$tenantcode.")";
    else
        $filename = $tenantname." - (".$tenantcode."-RENEWED)";
        
    $filename .=" - Guarantee ";
    $filename = clean($filename);
    $gpdf->Output("../../pms_docs/leases/".$filename.".pdf","F");    
        
    ////to show with file name pdf
    $gpdf->Output($filename, 'I');
    exit;    

}

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