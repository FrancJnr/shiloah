<?php
header("Content-type: text/xml");
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$sqlGet ="";
//$nk =0;
//foreach ($_GET as $k=>$v) {
//    $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
//    $nk++;
//}
//$custom = array('msg'=> $sqlGet ,'s'=>'error');
//$response_array[] = $custom;
//echo '{"error":'.json_encode($response_array).'}';
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
$totalDeposit=0;$shoplocation="";
$sc=0;$jk=1;
foreach ($_GET as $k=>$v) {	
	$len =  strlen(trim($k));
	if($len >= 11)
	{		
		if($k  =="grouptenantmasid")
		{
			//$sql = "SELECT * FROM  `group_tenant_mas` WHERE  `grouptenantmasid` ='$v';";
			//$result = mysql_query($sql);
			//while ($row = mysql_fetch_assoc($result))
			//{
			//	$grptenantmasid  = $row["tenantmasid"];
			//}
			$grptenantmasid = $v;
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
			
			 $sqlv1= "SELECT a.*, b.age AS term, b1.age AS rentcycle,b.fulldesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size, \n"
				. "DATE_FORMAT( a.doo, '%d-%m-%Y' ) as 'tenantdoo',  \n"
				. "DATE_FORMAT( a.doc, '%d-%m-%Y' ) as 'tenantdoc'  \n"
				. " FROM mas_tenant a\n"
				. " INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
				. " INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
				. " INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
				. " INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
				. " INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
				. " INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
				. " WHERE a.tenantmasid = $tenantmasid "
				//. " WHERE a.tenantmasid = $tenantmasid and a.active ='1'"
				. " and  a.companymasid=$companymasid;";
			//$result = mysql_query($sqlv1);					
			//if($result !=null)
			//{
			//    $rcount = mysql_num_rows($result);
			//    if($rcount ==0) 
			//    {                    
			//	 $sqlv1= "SELECT a.*, b.age AS term, b1.age AS rentcycle,b.fulldesc,c.buildingname,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size, \n"
			//		. "DATE_FORMAT( a.doo, '%d-%m-%Y' ) as 'tenantdoo',  \n"
			//		. "DATE_FORMAT( a.doc, '%d-%m-%Y' ) as 'tenantdoc'  \n"
			//		. " FROM rec_tenant a\n"
			//		. " INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
			//		. " INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
			//		. " INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
			//		. " INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
			//		. " INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
			//		. " INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
			//		. " WHERE a.tenantmasid = $tenantmasid and a.active ='1'"
			//		. " and  a.companymasid=$companymasid;";
			//    }
			//}
			
			//create tenant view
			$viewSql =  "create view view_offerletter_tenant as ".$sqlv1;
			$result = mysql_query($viewSql);
			
			$sqlv2= "select a.* from mas_tenant_cp a inner join mas_tenant b on b.tenantmasid = a.tenantmasid "
					. " WHERE a.tenantmasid = $tenantmasid "
					//. " WHERE a.tenantmasid = $tenantmasid and b.active='1'"
					. " and  b.companymasid=$companymasid"
					. " and  a.documentname='1';";
			//$result = mysql_query($sqlv2);					
			//if($result !=null)
			//{
			//    $rcount = mysql_num_rows($result);
			//    if($rcount ==0) 
			//    {                    
			//	$sqlv2= "select a.* from rec_tenant_cp a inner join rec_tenant b on b.tenantmasid = a.tenantmasid "
			//		. " WHERE a.tenantmasid = $tenantmasid and b.active='1'"
			//		. " and  b.companymasid=$companymasid"
			//		. " and  a.documentname='1';";
			//    }
			//}
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
				$shopid .= $floordescription." , Shop No: ".$shopcode;
				//$shopsizeid .= "(".$floordescription." , Shop No: ".$shopcode." , Size: ".$row['size']." sqrft)";
				$shoplocation .= $jk.") ".$floordescription." , Shop No: ".$shopcode." , Size: ".$row['size']." sqrft. ";
				$shopsizeid =$floordescription;
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
				$fulldescofterm = $row['fulldesc'];
				$doo = $row['tenantdoo'];
				$doc = $row['tenantdoc'];
				$latefeeinterest = $row['latefeeinterest'];
				
				$rentcycle = strtolower($row["rentcycle"]);
				$jk++;
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
			$tenantRent .="<table cellpadding='3' cellspacing='0' border='1' width='100%'><tr align='center'>"
				."<thead><th>From</th>"
			        ."<th>To</th>"
				."<th>Amount (KSH)</th>"
			        ."<th>Period</th>"
				."</thead></tr>";
			$sql = "SELECT * FROM view_offerletter_rent";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$amtrent = $row['amount'];
				$offerlettermasid = $row['offerlettermasid'];
				$tenantRent .="<tr tr align='center'>"
						."<td>".$row['rent_fromdate']."</td>"
						."<td>".$row['rent_todate']."</td>";
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
				$tenantRent .="<td>".number_format($amtrent,0, '.', ',')."</td>"
						."<td>Per Month</td>"
						."</tr>";
			}
			$tenantRent .="</table><br>";
			//load tenant scrvice charge details
			$tenantSc .="<table cellpadding='3' cellspacing='0' border='1'width='100%'><tr tr align='center'>"
				."<thead><th>From</th>"
			        ."<th>To</th>"
				."<th>Amount (KSH)</th>"
			        ."<th>Period</th>"
				."</thead></tr>";
			$sql = "SELECT * FROM view_offerletter_sc";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$amtsc = $row['amount'];
				$tenantSc .="<tr align='center'>"
						."<td>".$row['sc_fromdate']."</td>"
						."<td>".$row['sc_todate']."</td>";
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
				$tenantSc .= "<td>".number_format($amtsc,0, '.', ',')."</td>"
						."<td>Per Month</td>"
						."</tr>";
						$sc =$row['amount'];
			}
			$tenantSc .="</table> <br>";
			
			//load tenant deposit details
			$sql = "SELECT * FROM view_offerletter_deposit";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$n = 1;
				if($row['depositmonthrent'] >0)
				{
					$tenantDeposit .="<tr>"					
							."<td>".$row['depositmonthrent']." Months Security Deposit for rent </td>"
							."<td align='right'>".number_format($row['rentdeposit'], 0, '.', ',')."</td>"			
							."</tr>";$n++;
				}
				if($row['depositmonthsc'] > 0)
				{
					$tenantDeposit .="<tr>"							
							."<td>".$row['depositmonthsc']." Months security deposit for Service Charge</td>"
							."<td align='right'>".number_format($row['scdeposit'], 0, '.', ',')."</td>"
							."</tr>";$n++;
				}
				if($row['advancemonthrent'] > 0)
				{
					$tenantDeposit .="<tr>"							
							."<td>".$row['advancemonthrent']." Month Advance rent with VAT</td>"
							."<td align='right'>".number_format($row['rentwithvat'], 0, '.', ',')."</td>"
							."</tr>";$n++;
				}
				if($row['advancemonthsc'] >0)
				{
					$tenantDeposit .="<tr>"							
							."<td>".$row['advancemonthsc']." Month Advance Service Charge with VAT</td>"
							."<td align='right'>".number_format($row['scwithvat'], 0, '.', ',')."</td>"
							."</tr>";$n++;
				}
				if($row['leegalfees'] >0)
				{
					$tenantDeposit .="<tr>"							
							."<td>Legal Fees with VAT</td>"
							."<td align='right'>".number_format($row['leegalfees'], 0, '.', ',')."</td>"
							."</tr>";$n++;
				}
				if($row['stampduty'] >0)
				{
					$tenantDeposit .="<tr>"							
							."<td>Stamp Duty</td>"
							."<td align='right'>".number_format($row['stampduty'], 0, '.', ',')."</td>"
							."</tr>";$n++;
				}
				if($row['registrationfees'] >0)
				{
					$tenantDeposit .="<tr>"							
							."<td>Registration Fees</td>"
							."<td align='right'>".number_format($row['registrationfees'], 0, '.', ',')."</td>"
							."</tr>";$n++;
				}
				if($row['depositTotal'] >0)
				{
				$tenantDeposit .="<tr>"
						."<td>Total</td>"
						."<td align='right'>Kshs.<strong>".number_format($row['depositTotal'], 0, '.', ',')."</strong></td>"
						."</tr>";
				}
				$totalDeposit +=$row['depositTotal'];
				$n = 0;
			}
			$tenantDeposit .="</table><br>";
			
			
			$headerContent = "<tr>    
						<td width='40%'><img src='../images/mp_logo.jpg' height='50px'></td>
						<td width='35%'><img src='../images/mc_logo.jpg' height='50px'></td>
						<td align='right'><img src='../images/mm_logo.jpg' height='50px'></td>    
					</tr>
					<tr><td colspan='3' align='center'><h1><font size=6><u>SHILOAH INVESTMENTS LTD.</u></font></h1></td></tr>
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
		
	}// end if length check
}// end for each

//$custom = array('msg'=> $sqlArray ,'s'=>'error');
//				$response_array[] = $custom;
//				echo '{"error":'.json_encode($response_array).'}';
//				exit;
$premisistable ="";
$premisistable .="<span><b>Building: $buildingname</b></span><br><br>";
$premisistable .="<span><b>Block No: $buildingmunicipaladd</b></span><br><br>";
$premisistable .="<span><b>Shop    : $shoplocation</b></span>";
$row1 =" <b><u>REF: LEASE OF PREMISES IN</u></b><br><br>$premisistable <br>
            <br>We <strong>".$companyname."</strong> ,".$companyshortaddress." are the owners of the premises known as <strong>".$buildingname."</strong> in ,
            ".$buildingmunicipaladd." and subject to the consent of any registered chargee of <strong>".$buildingname."</strong>, we are prepared to enter into a lease, in
            the form of our standard lease, which includes (among others) the following terms and conditions.";

//$row1 =" <strong><u>REG: LEASE OF PREMISES on the ".rtrim($shopid,',')." at  ".strtoupper($buildingname)." , ".strtoupper($buildingcity).".</u></strong><br>
//            We <strong>".$companyname."</strong> ,".$companyshortaddress." the owners of the premises known as <strong>".$buildingname."</strong> in ,
//            ".$buildingmunicipaladd." and subject to the consent of any registered chargee of <strong>".$buildingname."</strong>, we are prepared to enter into a lease, in
//            the form of our standard lease, which includes (among others) the following terms and conditions.";

$row2  ="You will also pay Actual Service Charge determined in the manner stated in the Lease to reimburse the Landlords for a fair proportion of the operation
                expenses of the building that shall include but not be limited to the following: -
            </br>
                (i) Cost of all water used in the common areas.
            </br></br>
            
                (ii) Cost of all the electricity consumed by security lights and in the common areas.
            </br></br>
            
                (iii) Cost of providing security services.
            </br></br>
            
                (iv) Cost of cleaning the propertys common areas.
            </br></br>
            
                (v) Premium payable for fire and public liability insurance.
            </br></br>
            
                (vi) Management expenses.
            </br></br>
            
                (vii) Cost of maintaining and re-decorating the common areas.
            </br></br>
            
                (viii) Cost of internal repairs and maintenance of the demised premises not being of a structural nature.
            </br></br>
            
                (ix) Cost of maintaining the fire fighting systems and any other mechanical systems or installations.
            </br></br>
            
                (x) Rates and ground rent.
            </br></br>
            
                (xi) Book-keeping and audit fees for the rent and service charge account.
            </br></br>
            
                (xii) Cost of refuse collection.
            </br></br>
            
                (xiii) Cost of pesticides and sanitary services.
            </br></br>
            
                (xiv) Cost of providing vehicle parking facility.
            </br></br>
            
                (xv) Any other cost which the lessor may deem fit in its sole discretion to levy as service charge from time to time.
            </br></br>
            
                PROVIDED THAT in the event that the sums paid by the Lessee under this clause are less than the actual sum of service charge expended by the Lessor as
                proved by the Lessor's annual audited accounts (AND for the purposes of this sub-clause the parties hereunto agree that the statement of the Lessor's
                auditors as to the amount expended in respect of service charge shall be final and conclusive) the Lessee shall within seven days (7) days from the date of
                receipt of a demand from the Lessor reimburse to the Lessor the difference between the sums already paid by the Lessee and the actual aggregate amount
                expended by the Lessor in providing such service.";
$row3 =" To pay the rent herein before reserved at the time and manner aforesaid without any deductions and the rent shall be subject to Value Added Tax and any
        other statutory taxes which may be levied on the rent, service charge and on any other sums payable herein and to pay all other sums as provided in the
        sub- lease;";
$row4="  Not to assign transfer sublet charge or otherwise part with the possession of the demised premises or any part thereof without the written consent of the
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
    sub-Lessee or occupant as tenant or a release of the Lessee from the further performance by the Lessee of the covenants and agreements on the Lessee's part
    herein contained will be or is liable to be forth-coming the Lessor hereby expressly reserving to themselves the right in their absolute and uncontrolled
    discretion and without assigning any reason therefor to withhold their consent if they consider that either their interest or those of the other tenants of
    the buildings would be impaired by giving the same nor shall the consent by the Lessor to any assignment transfer or subletting be in anyway construed as
    relieving the Lessee from obtaining the express consent in writing of the Lessor to any further assignment transfer or subletting. If the Lessor shall give
    such consent the instrument of assignment shall be prepared by the Lessor's advocate and executed by the parties and all costs in connection with the
    preparation and completion thereof including the Advocates costs stamp duties and registration fees shall be borne by the Lessee AND IT IS HEREBY FURTHER
    AGREED THAT where the Lessee is a private limited company then for the purpose of this sub-clause a transfer of the beneficial interest in more than fifty
    percent (50%) of the issued share capital of the Lessee or allotment or issue or transfer of any share of the Lessee to anyone other than any current
    member of the Company shall be deemed to constitute an assignment and transfer of the demised premises;";
$row5="The placement of any sign, notice or advertisement so as to be visible from the exterior of the premises is prohibited without the prior consent of the
        Landlord. Any sign or notice advertisement must comply with the Landlord's specifications.";
$row6=" The landlord's prior approval is required to the design and layout of the interior of the premises including partitioning and to any changes, which you may
        wish to make during the term of the lease.
        </br>
        Before commencing any improvements or alterations to the interior you must submit plans of the intended layout and design, specifying the materials to be
        used. You will be required to pay the cost of the Landlords and its Consultants incurred in considering the proposed plans. The costs of partitioning and
        any other internal improvements and alterations are the responsibility of the tenant.
        </br>
        On determination of Lease, the partitioning shall become the property of the Landlord provided that if the Landlord shall give notice to the tenant, the Tenant
        shall remove the partitioning and reinstate the premises to their original condition at the Tenant's expense.";
$row7=" <strong><u>Water Charges</u></strong>
        </br></br>    (i) To pay to the appropriate water authority any sums or charges payable in respect of the installation of any separate supply of water and water meter
            installed by or at the request of the Lessee in the demised premises and in respect of water consumed thereon and to observe and perform all regulations
            and to keep the Lessor indemnified in respect thereof OR;
        </br>    (ii) To pay the Lessor in addition to the said rent by way of reimbursement to the Lessor all water charges in respect of water consumed by the Lessee in
            the said demised premises the said water charges to be computed according to an appropriate method adopted by the Lessor;
        </br></br>   <br> <strong><u>Telephone Charges.</u></strong>
        </br></br>    (i) To pay to the appropriate telephone authority the costs of installing and connecting such telephones as the Lessee may require on the demised premises
            and all rentals and other charges payable in respect of such telephones and its use in respect of the term hereby granted;
        </br></br>    <strong><u>Electricity Charges.</u></strong>
        </br></br>    (i) To pay to the appropriate electricity authority any sums or charges payable in respect of the installation of any separate supply of electricity and
            electricity meter installed by or at the request of the Lessee in the demised premises and in respect of electricity consumed thereon and to observe and
            perform all regulations and to keep the Lessor indemnified in respect thereof;
        </br>    (ii) To pay the Lessor in addition to the said rent by way of reimbursement to the Lessor all electricity charges in respect of electricity consumed by the
            Lessee in the said demised premises the said electricity charges to be computed according to an appropriate method adopted by the Lessor;
        </br>
            (iii) Not to install any equipment with a capacity above 4 Kilowatts or 13 Amperes at 240 volts or to load any socket outlet sub-circuit above the capacity
            of 13 Amperes without the prior consent in writing of the Lessor who shall be entitled as condition of giving such consent to require the Lessee to pay
            such additional sum of money as may suffice to cover installation and/or additional installation and the additional charges for electricity caused by such
            use;";
$row8="You will be responsible for repairs to the interiors of the premises. These include repairs to finishes, partitions, doors, windows and internal fixtures
    and fittings.
</br>    You will also be responsible for painting the interior of the premises after two years from the date of commencement of the lease and on the determination
    of lease.";
$row9=" If the premises contain any plate-glass you shall be responsible for insuring it.";
$row10 =" All costs and expenses of preparing and completing the lease including legal fees of the Advocates of the Lessor/Landlord, stamp duty, other disbursement
        and value added tax will be for your account. Upon your confirmation that you wish to proceed, you will be required to pay a deposit on account of legal
        costs and expenses. The balance (if any) will be paid upon demand by the Landlord or its advocates.";
$row11="If the Tenant occupying the premise is a company personal guarantees of two Directors to be given in respect of payment of rents.
	For a partnership,personal guarantees of the partners to be given in respect of payments of rents.";
$row12 ="(a) If you wish to proceed on the basis of the terms set out in this letter, please sign the confirmation below and return the duplicate to us together
        with required payments as set out below within seven (7) days from the date hereof failing which this offer shall be deemed to be lapsed without any
        further notices or references.
        </br></br>
        (b) Upon receipt of your confirmation and the deposits required, we will arrange for the sub-lease to be prepared and sent to you for execution. It is
        hereby further agreed that you shall communicate to the Lessor your approval or amendments on the sub-lease within seven (7) days from the date of receipt
        of the sub-lease. Upon expiry of the said seven (7) days you shall execute and return the sub-lease to the lessor in triplicate within a further seven (7)
        days thereafter failing which this agreement shall be terminated for all intents and purposes.
        </br></br>
        (c) In the event of such a determination of the term hereby created as set out in paragraph (b) above the Lessee shall remain liable to the Lessor for
        payment of all the rentals, service charge and/or any other sum payable under the terms and conditions of this agreement and for the entire period of this
        agreement and the lessor shall at its own discretion be entitled to re-enter the premises hereby let to the lessee and to enjoy the premises in their
        former state and the lessee hereby waives the right of re-entry into the premises.
        </br></br>
        (d) Notwithstanding that this matter remains subject to Lease, the landlord will be entitled to retain out of the deposits paid under this letter any costs
        and expenses incurred in preparing and negotiating the Lease.";



$sql = "select * from rpt_offerletter where grouptenantmasid =".$grptenantmasid." and active =1";
$result = mysql_query($sql);
$rowcount = mysql_num_rows($result);

if ($rowcount == 0)
{
//TENANTMASID == GROUPTENANTMASID
//Insert New offerleteter 
$divContent="";
$createdby = $_SESSION['myusername'];
$createddatetime = $datetime;
$insert = "insert into rpt_offerletter (offerlettermasid,grouptenantmasid,row1,row2,row3,row4,row5,row6,row7,row8,row9,row10,row11,row12,createdby,createddatetime) values (
	  '".$offerlettermasid."','"
	  .$grptenantmasid."','"
	  .mysql_real_escape_string($row1)."','"
	  .mysql_real_escape_string($row2)."','"
	  .mysql_real_escape_string($row3)."','"
	  .mysql_real_escape_string($row4)."','"
	  .mysql_real_escape_string($row5)."','"
	  .mysql_real_escape_string($row6)."','"
	  .mysql_real_escape_string($row7)."','"
	  .mysql_real_escape_string($row8)."','"
	  .mysql_real_escape_string($row9)."','"
	  .mysql_real_escape_string($row10)."','"
	  .mysql_real_escape_string($row11)."','"
	  .mysql_real_escape_string($row12)."','"
	  .$createdby."','"
	  .$createddatetime."')";
	  $iid ="0";
mysql_query($insert);
$iid = mysql_insert_id();
$divContent =mysql_error();

$insert_doc_status ="insert into trans_document_status(grouptenantmasid) values ('$grptenantmasid');";
mysql_query($insert_doc_status);
$divContent =mysql_error();

}

$qry = "select * from rpt_offerletter where grouptenantmasid =".$grptenantmasid." and active =1";

$result = mysql_query($qry);

if(mysql_num_rows($result) >0)
{
	$rowno =0;
while($x = mysql_fetch_assoc($result))
{

$btnSpace="&nbsp;&nbsp;&nbsp;<input type='button' id='btnBreak' value='space' />&nbsp;&nbsp;&nbsp;<input type='button' id='btnRemoveBreak' value='remove space' />";

//$div1="<span>Print Preview Offerletter of <font color='blue'> M/s. $tenantname ($tenantcode)</font>&nbsp;<button type='button' id='btnPreview' name='0'>Print Preview</button>&nbsp;&nbsp;<button type='button' id='btnPrint' name='0'>Print</button>&nbsp;</span>
$div1="<span>Print Preview Offerletter of <font color='blue'> M/s. $tenantname ($tenantcode)</font>&nbsp;&nbsp;<button type='button' id='btnPrint' name='0'>Save & Print</button>&nbsp;</span>
<p class='printable'><table width='100%' border=0><tbody><tr><td>";
$div1 .="<ul id='sortable1' class='connectedSortable'>
		<li class='ui-widget-content'>
			<table width='100%'>";
			$div1 .=$headerContent."
			</table><br>
			<strong>".Date("F d, Y")."</strong><br>
		</li>
		<li class='ui-state-default'>
			$tenantcptable
		</li>
		<li class='ui-state-default'>
			<table width='100%' border=0>
				<tr>
					<td><strong>Dear Sirs,</td>
					<td align='right' id='btnSpace'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content1' align='justify'>".trim($x['row1'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit1' name='1'>Edit</button> &nbsp
						<button type='button' id='btnReset1' name='1'>Reset</button>&nbsp
						<div id='divText1' style='height: 0px;visibility: hidden;'>
							<textarea id='editor1' rows='0' cols='0'>
							       
							</textarea>
						<button type='button' id='btnUpdate1' name='1'>Update</button>
						</div>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval1'  name ='rowindexval1' value='$rowno.' class='offerletterText' readonly><strong><u>LANDLORD</u></strong>
					<span id='rowindex1' name='1' style='visibility:hidden'>".trim($x['rowindex1']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>					
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td><strong>".strtoupper($companyname)."</strong> ,".$companyshortaddress.".</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval2'   name ='rowindexval2' value='$rowno.' class='offerletterText' readonly><strong><u>TENANT</u></strong><span id='rowindex2' name='2' style='visibility:hidden'>".trim($x['rowindex2']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>										
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td><strong>M/S ".$tenantname."</strong> ,".$tenantshortaddress.".</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval3'   name ='rowindexval3' value='$rowno.' class='offerletterText'' readonly><strong><u>PREMISES</u></strong><span id='rowindex3' name='3' style='visibility:hidden'>".trim($x['rowindex3']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td>The premises are situated on the ".rtrim($shopsizeid,',')." measuring <strong>".$sizetotal."</strong> square feet approximately.</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval4'   name ='rowindexval4' value='$rowno.' class='offerletterText' readonly><strong><u>TERM</u></strong><span id='rowindex4' name='4' style='visibility:hidden'>".trim($x['rowindex4']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td> $fulldescofterm </td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval5'   name ='rowindexval5' value='$rowno.' class='offerletterText' readonly><strong><u>DATE OF OCCUPATION</u></strong><span id='rowindex5' name='5' style='visibility:hidden'>".trim($x['rowindex5']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>					
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td>  ".$doo.".</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval6'   name ='rowindexval6' value='$rowno.' class='offerletterText' readonly><strong><u>DATE OF COMMENCEMENT</u></strong><span id='rowindex6' name='6' style='visibility:hidden'>".trim($x['rowindex6']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>					
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td>  ".$doc.".</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval7'   name ='rowindexval7' value='$rowno.' class='offerletterText' readonly><strong><u>RENT</u></strong><span id='rowindex7' name='7' style='visibility:hidden'>".trim($x['rowindex7']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td>  ".$tenantRent."
									The said rent will be paid <strong>$rentcycle in advance</strong>. If the rent shall not be paid on the due date whether formally demanded or not, the Tenant
									shall pay the Landlord a penalty on any such sums at the rate of ".$latefeeinterest." % per month from the date when it fell due to the date when it is paid.
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval8'   name ='rowindexval8' value='$rowno.' class='offerletterText' readonly><strong><u>FORM OF LEASE</u></strong><span id='rowindex8' name='8' style='visibility:hidden'>".trim($x['rowindex8']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td>
									The Lease will be in the landlords Standard Form of Lease for <strong>".strtoupper($companyname)."</strong> as shall be prepared by the Landlords Advocates
									which shall be deemed to have been accepted by the Tenant upon acceptance of the terms of this letter.
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		if((strtoupper($buildingname) != "KATANGI") and ($sc >0 ))
		{
		$rowno +=1;
		$div1 .="<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval9'   name ='rowindexval9' value='$rowno.' class='offerletterText' readonly><strong><u>SERVICE CHARGE</u></strong><span id='rowindex9' name='9' style='visibility:hidden'>".trim($x['rowindex9']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content2' align='justify'>".trim($x['row2'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit2' name='2'>Edit</button> &nbsp
						<button type='button' id='btnReset2' name='2'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox2' name='2' ".$x['removerow2']."/>
						<div id='divText2' style='height: 0px;visibility: hidden;'>
						<textarea id='editor2' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate2' name='2'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval10'   name ='rowindexval10' value='$rowno.' class='offerletterText' readonly><strong><u>SERVICE CHARGE DEPOSIT</u></strong><span id='rowindex10' name='10' style='visibility:hidden'>".trim($x['rowindex10']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td>
									Subject to <strong>'SERVICE CHARGE DETAILS'</strong> above deposit on account of the service charge shall be paid in advance as below stated.
								       </br>The Service Charge deposit without deductions will be: -
								       </br>
								       ".$tenantSc."
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		 }
		 else
		 {
			$rowno +=1;
		 }
		
		$div1 .="<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval12'   name ='rowindexval12' value='$rowno.' class='offerletterText' readonly><strong><u>STATUTOTY TAXES</u></strong><span id='rowindex12' name='12' style='visibility:hidden'>".trim($x['rowindex12']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content3' align='justify'>".trim($x['row3'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit3' name='3'>Edit</button> &nbsp
						<button type='button' id='btnReset3' name='3'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox3' name='3' ".$x['removerow3']."/>
						<div id='divText3' style='height: 0px;visibility: hidden;'>
						<textarea id='editor3' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate3' name='3'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval11'   name ='rowindexval11' value='$rowno.' class='offerletterText' readonly><strong><u>PERMITTED USE</u></strong><span id='rowindex11' name='11' style='visibility:hidden'>".trim($x['rowindex11']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td>
									User of the premises will be restricted for <strong> ".strtoupper($nob)." ONLY</strong> and such use shall not be changed without the
									Landlord's prior written consent.</br>
									The use of the premises will have to be in accordance with the design of the building and in fulfillment
									of ".$buildingcouncil."  requirements PROVIDED
									
									that the Lessee, its servants and/or agents <strong><u>shall not</u></strong> be entitled to use the common areas for his/her/its business.
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval13'   name ='rowindexval13' value='$rowno.' class='offerletterText' readonly><strong><u>PROHIBITION ON TRANSFER, SUB-LETTING, e.t.c</u></strong><span id='rowindex13' name='13' style='visibility:hidden'>".trim($x['rowindex13']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content4' align='justify'>".trim($x['row4'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit4' name='4'>Edit</button> &nbsp
						<button type='button' id='btnReset4' name='4'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox4' name='4' ".$x['removerow4']."/>
						<div id='divText4' style='height: 0px;visibility: hidden;'>
						<textarea id='editor4' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate4' name='4'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval14'   name ='rowindexval14' value='$rowno.' class='offerletterText' readonly><strong><u>RESTRICTION OF SIGNS AND NOTICES,etc.</u></strong><span id='rowindex14' name='14' style='visibility:hidden'>".trim($x['rowindex14']).".</span></td>
					<td align='right' id='btnSpace'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content5' align='justify'>".trim($x['row5'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit5' name='5'>Edit</button> &nbsp
						<button type='button' id='btnReset5' name='5'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox5' name='5' ".$x['removerow5']."/>
						<div id='divText5' style='height: 0px;visibility: hidden;'>
						<textarea id='editor5' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate5' name='5'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval15'   name ='rowindexval15' value='$rowno.' class='offerletterText' readonly><strong><u>LAYOUT AND PARTIONING</u></strong><span id='rowindex15' name='15' style='visibility:hidden'>".trim($x['rowindex15']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content6' align='justify'>".trim($x['row6'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit6' name='6'>Edit</button> &nbsp
						<button type='button' id='btnReset6' name='6'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox6' name='6' ".$x['removerow6']."/>
						<div id='divText6' style='height: 0px;visibility: hidden;'>
						<textarea id='editor6' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate6' name='6'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval16'   name ='rowindexval16' value='$rowno.' class='offerletterText' readonly><strong><u>UTILITIES</u></strong><span id='rowindex16' name='16' style='visibility:hidden'>".trim($x['rowindex16']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content7' align='justify'>".trim($x['row7'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit7' name='7'>Edit</button> &nbsp
						<button type='button' id='btnReset7' name='7'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox7' name='7' ".$x['removerow7']."/>
						<div id='divText7' style='height: 0px;visibility: hidden;'>
						<textarea id='editor7' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate7' name='7'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval17'   name ='rowindexval17' value='$rowno.' class='offerletterText' readonly><strong><u>INTERNAL REPAIRS</u></strong><span id='rowindex17' name='17' style='visibility:hidden'>".trim($x['rowindex17']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content8' align='justify'>".trim($x['row8'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit8' name='8'>Edit</button> &nbsp
						<button type='button' id='btnReset8' name='8'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox8' name='8' ".$x['removerow8']."/>
						<div id='divText8' style='height: 0px;visibility: hidden;'>
						<textarea id='editor8' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate8' name='8'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval18'   name ='rowindexval18' value='$rowno.' class='offerletterText' readonly><strong><u>INSURANCE OF PLATE-GLASS</u></strong><span id='rowindex18' name='18' style='visibility:hidden'>".trim($x['rowindex18']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content9' align='justify'>".trim($x['row9'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit9' name='9'>Edit</button> &nbsp
						<button type='button' id='btnReset9' name='9'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox9' name='9' ".$x['removerow9']."/>
						<div id='divText9' style='height: 0px;visibility: hidden;'>
						<textarea id='editor9' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate9' name='9'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval19'   name ='rowindexval19' value='$rowno.' class='offerletterText' readonly><strong><u>LEGAL COSTS AND EXPENSES</u></strong><span id='rowindex19' name='19' style='visibility:hidden'>".trim($x['rowindex19']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content10' align='justify'>".trim($x['row10'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit10' name='10'>Edit</button> &nbsp
						<button type='button' id='btnReset10' name='10'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox10' name='10' ".$x['removerow10']."/>
						<div id='divText10' style='height: 0px;visibility: hidden;'>
						<textarea id='editor10' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate10' name='10'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval20'   name ='rowindexval20' value='$rowno.' class='offerletterText' readonly><strong><u>GUARANTEE</u></strong><span id='rowindex20' name='20' style='visibility:hidden'>".trim($x['rowindex20']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content11' align='justify'>".trim($x['row11'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit11' name='11'>Edit</button> &nbsp
						<button type='button' id='btnReset11' name='11'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox11' name='11' ".$x['removerow11']."/>
						<div id='divText11' style='height: 0px;visibility: hidden;'>
						<textarea id='editor11' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate11' name='11'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval21'   name ='rowindexval21' value='$rowno.' class='offerletterText' readonly><strong><u>ACCEPTANCE OF OFFERLETTER</u></strong><span id='rowindex21' name='21' style='visibility:hidden'>".trim($x['rowindex21']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content12' align='justify'>".trim($x['row12'])."<br><br><strong>Yours truly, <br><br><br>
								<strong>For ".$companyname."<br><br><br><br>[Authorised Signatory]</strong><br><br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit12' name='12'>Edit</button> &nbsp
						<button type='button' id='btnReset12' name='12'>Reset</button>&nbsp
						<!--&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox12' name='12' ".$x['removerow12']."/>-->
						<div id='divText12' style='height: 0px;visibility: hidden;'>
						<textarea id='editor12' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate12' name='12'>Update</button>
					</td>
				</tr>
				<tr>					
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td align='justify'>
								PLEASE NOTE:
								<p >
									1. If you are a sole trader, the duplicate letter must be signed together with a copy of the I.D. / passport, P.I.N. certificate.
								</p>
								<p>
									2. If you carry on business in partnership, the duplicate must be signed by all the partners. You must return the duplicate with a copy of Certificate of
									Registration under the Business Names Act a copy of the I.D. / passport, P.I.N. certificate.
								</p>
								    <p>
									3. If you are a limited liability company, the duplicate letter must be signed by two directors and you must return them with a copy of your Certificate of
									Incorporation under the Companies Act, Company board resolution, I.D. copies of the directors of the company and P.I.N. certificate.
								    </p>
								    <p>
									4. If you are a foreign company registered in Kenya under the Companies Act, you must return the duplicate with a copy of your Certificate of Compliance
									under the Companies Act Company board resolution, I.D. copies of the directors of the company and P.I.N. certificate.
								    </p>
								    
								    <p>
								    We confirm that we wish to proceed on the basis of the terms set out in this letter.";
								if($totalDeposit !=0)
								{
									$div1 .="We enclose our cheque for the sum of    <strong>KShs.".number_format($totalDeposit, 0, '.', ',')."/= as set out herein below.</strong>
											    </p>
											    $tenantDeposit
											    <br>";
								}
								else
								{
									$div1 .="</p>";
								}
								$div1 .="
									<strong><strong>For ".$tenantname."<br><br></strong></strong>								    
								    <br><br>								    
									<table width='100%' colspacing='3'>
										<tr>
											<td align='center'>_______________________________<br>SIGNED</td>
											
											<td align='center'>_______________________________<br>DATE</td>
										</tr>
										<tr style='height:80px;'>
										</tr>
										<tr>
											<td align='center'>_______________________________<br>SIGNED</td>
											
											<td align='center'>_______________________________<br>DATE</td>
										</tr>
									</table>								
								</td>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval22'   name ='rowindexval22' value='$rowno.' class='offerletterText' readonly><span id='rowindex22' name='22' style='visibility:hidden'>".trim($x['rowindex22']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content13' align='justify'>".trim($x['row13'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit13' name='13'>Edit</button> &nbsp
						<button type='button' id='btnReset13' name='13'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox13' name='13' ".$x['removerow13']."/>
						<div id='divText13' style='height: 0px;visibility: hidden;'>
						<textarea id='editor13' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate13' name='13'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval23'   name ='rowindexval23' value='$rowno.' class='offerletterText' readonly><span id='rowindex23' name='23' style='visibility:hidden'>".trim($x['rowindex23']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>					
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content14' align='justify'>".trim($x['row14'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit14' name='14'>Edit</button> &nbsp
						<button type='button' id='btnReset14' name='14'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox14' name='14' ".$x['removerow14']."/>
						<div id='divText14' style='height: 0px;visibility: hidden;'>
						<textarea id='editor14' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate14' name='14'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval24'   name ='rowindexval24' value='$rowno.' class='offerletterText' readonly><span id='rowindex24' name='24' style='visibility:hidden'>".trim($x['rowindex24']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content15' align='justify'>".trim($x['row15'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit15' name='15'>Edit</button> &nbsp
						<button type='button' id='btnReset15' name='15'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox15' name='15' ".$x['removerow15']."/>
						<div id='divText15' style='height: 0px;visibility: hidden;'>
						<textarea id='editor15' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate15' name='15'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval25'   name ='rowindexval25' value='$rowno.' class='offerletterText' readonly><span id='rowindex25' name='25' style='visibility:hidden'>".trim($x['rowindex25']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content16' align='justify'>".trim($x['row16'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit16' name='16'>Edit</button> &nbsp
						<button type='button' id='btnReset16' name='16'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox16' name='16' ".$x['removerow16']."/>
						<div id='divText16' style='height: 0px;visibility: hidden;'>
						<textarea id='editor16' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate16' name='16'>Update</button>
					</td>
				</tr>
			</table>
		</li>";
		$rowno +=1;
		$div1 .="
		<li class='ui-state-default'>
			<table width='100%'>
				<tr>
					<td><input type='text' id ='rowindexval26'   name ='rowindexval26' value='$rowno.' class='offerletterText' readonly><span id='rowindex26' name='26' style='visibility:hidden'>".trim($x['rowindex26']).".</span></td>
					<td align='right' id='btnSpace' style='height:0px;'>$btnSpace</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%'>
							<tr>
								<td width='1%'></td>
								<td id='content17' align='justify'>".trim($x['row17'])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id='controlpanel'>
					<td colspan='2'>
						<button type='button' id='btnEdit17' name='17'>Edit</button> &nbsp
						<button type='button' id='btnReset17' name='17'>Reset</button>&nbsp
						&nbsp;&nbsp;<span id='disablethis'>Disable this</span><input type='checkbox' id='chkBox17' name='17' ".$x['removerow17']."/>
						<div id='divText17' style='height: 0px;visibility: hidden;'>
						<textarea id='editor17' rows='0' cols='0'>
								       
						</textarea>
						<button type='button' id='btnUpdate17' name='17'>Update</button>
					</td>
				</tr>
			</table>
		</li>
		
	</ul>";
$div1 .="</td></tr></tbody></table></p>";
} //end of while

}//END
//if (isset($_GET['item']))
//{
//    $action =  $_GET['item'];       
//    switch ($action)
//    {
//    case "reset1":
//            $p1 =$row1;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//    case "reset2":
//            $p1 =$row2;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//     case "reset4":
//            $p1 =$row3;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//    case "reset4":
//            $p1 =$row4;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//     case "reset5":
//            $p1 =$row5;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//    case "reset6":
//            $p1 =$row6;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//     case "reset7":
//            $p1 =$row7;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//     case "reset8":
//            $p1 =$row8;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//    case "reset9":
//            $p1 =$row9;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//    case "reset10":
//            $p1 =$row10;
//            $custom = array( 'p'=> $p1,'s'=>'Success');
//        break;
//    case "reset11":
//        $p1 =$row11;
//        $custom = array( 'p'=> $p1,'s'=>'Success');
//    break;
//    case "reset12":
//        $p1 =$row12;
//        $custom = array( 'p'=> $p1,'s'=>'Success');
//    break;
// 
//    }

$custom = array(
            'divContent'=> $div1,
            's'=>'Success');
$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
//exit;
	// ENABLE THIS TO CHECK
	//exit;
	
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