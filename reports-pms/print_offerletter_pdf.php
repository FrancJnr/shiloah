<?php
ob_start(); 
include('../config.php');
//require_once '../PHPWord.php';
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasidz=$_GET['grouptenantmasid'];
$tenantmasidz=$_GET['tenantmasid'];
$sc=0;
try
{
$sql = "SELECT * FROM draft_document1 WHERE section='offerletter' AND grouptenantmasid=".$grouptenantmasidz." AND tenantmasid=".$tenantmasidz."  ORDER BY draftid DESC LIMIT 1";
$sql2="SELECT * FROM draft_document2 WHERE section='offerletter' AND grouptenantmasid=".$grouptenantmasidz." AND tenantmasid=".$tenantmasidz."  ORDER BY draftid DESC LIMIT 1";

$result1 = mysql_query($sql);
$result2 = mysql_query($sql2);
//$rowprint=[];
//$row2print=[];
if($result1==true){
$rowprint = mysql_fetch_assoc($result1);

//$buildingname = $row["buildingname"];
} else{
    
    echo mysql_error();
}      
if($result2==true){
$row2print = mysql_fetch_assoc($result2);
//echo $row2['p11'];
//$buildingname = $row["buildingname"];
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
$shopsizeid="";$floor="";
$totalDeposit=0;
$prevdeposit =0;
$tenantmasid=0;
$leasedeposit=0;
$shops="";
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
				WHERE a.tenantmasid = $tenantmasid and  a.companymasid=$companymasid and a.active='1';";			
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
                                                                                                        
//                                           $rentcycle=  strtolower($rk['rentcycle']);              

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
				$tenantRent .='<td>'.number_format($amtrent,0, ".", ",").'</td><td>'.$rentcycle.'</td></tr>';
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
						<td width='35%'>Mega Plaza Block 'B' 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
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
						<td width='35%'>Mega Plaza Block 'B' 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
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
				}else if(strtoupper($buildingname) == "MEGA PLAZA2"){
					$buildingmunicipaladd = "Kisumu Municipality Block 7/380";
					$rowMunicipalAddress= "Municipality Block 7/380";
					$buildingcouncil = "Kisumu Municipal Council";
				}
				else{
                                    
                  $sql = "SELECT * FROM mas_building where buildingname  like '%$buildingname%'";
                  $result = mysql_query($sql);
					 while ($row = mysql_fetch_assoc($result))
					 {
									  $buildingmunicipaladd = $row['municipaladdress'];
									  $statusMessage = explode(" ", str_replace('&nbsp;'," ",$row['municipaladdress']));
									  
									  $size=sizeof($statusMessage);
									  $rowMunicipalAddress= $statusMessage[0];//rtrim($row['municipaladdress']);
									  $buildingcouncil =$statusMessage[$size-2].$statusMessage[$size-1]; //ltrm($row['municipaladdress'],2);; 

									 
					 }        
					
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
			<td width="35%">Mega Plaza Block "B" 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
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
    
    $p0 =$rowprint['p0'];
     
    $p1 =$rowprint['p1'];  
    $p2 =$rowprint['p2'];   
    $p3 =$rowprint['p3'];  
    $p4 =$rowprint['p4'];    
    $pdf->AddPage();	    
    $pdf->SetFont('dejavusans','B',23);
    $pa=$rowprint['pa'];
    $pdf->writeHTML($pa, true, false, true, false, '');
    $pdf->ln(0.2);    
    $pdf->SetFont('dejavusans','',8.5);
    $pb=$rowprint['pb'];;
    $pdf->writeHTML($pb, true, false, true, false, '');
    $pdf->ln(7);    
    $pdf->SetFont('dejavusans','',9.5);
    $pc=$rowprint['pc'];
    $pdf->writeHTML($pc, true, false, true, false, '');
    $pdf->ln();
    $pdf->SetFont('dejavusans','',10);
    $pd=$rowprint['pd'];;
    $pdf->writeHTML($pd, true, false, true, false, '');
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
    $p4a=$rowprint['p4a'];;
    $pdf->writeHTML($p4a, true, false, true, false, '');
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
    
	 
    $p5 =$rowprint['p5'];   
    $p6 =$rowprint['p6']; 
    $p7 =$rowprint['p7'];
    $p8 =$rowprint['p8'];
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(true);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    if($tradingname==""||$tradingname==null){
    $pdf->setHtmlHeader("TENANT NAME:<b> ".$leasename."</b>");
    }else{
    $pdf->setHtmlHeader("TENANT NAME:<b> ".$leasename."-T/A ".$tradingname."</b>");     
    }
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
   
    $p9 =$rowprint['p9'];
    $p10 =$rowprint['p10'];
	$pdf->writeHTML($p9, true, false, true, false, '');
	$pdf->ln(3);
	$pdf->writeHTML($p10, true, false, true, false, '');
	$pdf->ln(3);
    }
    
    $p11 =$row2print['p11'];
    $pdf->writeHTML($p11, true, false, true, false, '');
    $pdf->ln(3);
    
    
    $p12 =$row2print['p12'];
    $pdf->writeHTML($p12, true, false, true, false, '');
    $pdf->ln(3);

    $p13 =$row2print['p13'];
    $pdf->writeHTML($p13, true, false, true, false, '');
    $pdf->ln(3);
    
    $p14 =$row2print['p14'];
    $pdf->writeHTML($p14, true, false, true, false, '');
    $pdf->ln(3);
    
   
    $p15 =$row2print['p15'];
    $pdf->writeHTML($p15, true, false, true, false, '');
    $pdf->ln(3);
   
    $p16 =$row2print['p16'];
    $pdf->writeHTML($p16, true, false, true, false, '');
    $pdf->ln(3);
    
   
    $p17 =$row2print['p17'];
    $pdf->writeHTML($p17, true, false, true, false, '');
    $pdf->ln(3);
   
   
    $p18 =$row2print['p18'];
    $pdf->writeHTML($p18, true, false, true, false, '');
    $pdf->ln(3);
    
    
    $p19 =$row2print['p19'];
    $pdf->writeHTML($p19, true, false, true, false, '');
    $pdf->ln(3);
    
   
    $p20 =$row2print['p20'];
    $pdf->writeHTML($p20, true, false, true, false, '');
    $pdf->ln(3);
    
    
    $p21 =$row2print['p21'];
    $pdf->writeHTML($p21, true, false, true, false, '');
    $pdf->ln(5);
    
    $pdf->writeHTML("Your's truly,", true, false, true, false, '');
    $pdf->ln(2);
    $pdf->writeHTML("<strong>For ".$companyname."</strong>,", true, false, true, false, '');
    $pdf->ln(15);
    $pdf->writeHTML("[Authorised Signatory]", true, false, true, false, '');
    $pdf->ln(3);
    
    $p22 =$row2print['p22'];
    $pdf->writeHTML($p22, true, false, true, false, '');
    $pdf->AddPage();
    $pdf->ln(2);
    $pdf->writeHTML("We confirm that we wish to proceed on the basis of the terms set out in this letter.", true, false, true, false, '');
    $pdf->ln(1);
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
		$pdf->ln(1);
                $pdf->writeHTML("The new deposit amount Kshs ".number_format($totalDeposit, 0, '.', ',')."/= .", true, false, true, false, '');
		$pdf->ln(1);
                $pdf->writeHTML("Balance payable Kshs ".number_format($totalDeposit-$prevdeposit, 0, '.', ',')."/= .", true, false, true, false, '');
		$pdf->ln(1);
	}
       }
	if($totalDeposit !=0)
	{
		$pdf->writeHTML("We enclose our cheque for the sum of <strong>KShs.".number_format($totalDeposit-$prevdeposit, 0, '.', ',')."/= </strong> as set out herein below.", true, false, true, false, '');
		$pdf->ln(2);
		$pdf->writeHTML($tenantDeposit, true, false, true, false, '');
		$pdf->ln(2);		
	}
        
    $p23 =$row2print['p23'];
    $pdf->writeHTML($p23, true, false, true, false, '');
    $pdf->ln(5);

    if($renewalfromid<=0)
        $filename = $leasename." - (".$tenantcode.")";
    else
        $filename = $leasename." - (".$tenantcode."-RENEWED)";
    
    $filename = clean($filename);

    // Save It onto directory  
    $pdf->Output("../../pms_docs/offerletters/".$filename.".pdf","F");
    
    ////Insert into  rpt_offerletter
    $createdby = $_SESSION['myusername'];
    $createddatetime = $datetime;	
    $insert = "insert into rpt_offerletter (offerlettermasid,grouptenantmasid,createdby,createddatetime) values ($offerlettermasid,$groupmasid,'$createdby','$createddatetime')";
    mysql_query($insert);

    ////Insert into document status
    $insert_doc_status ="insert into trans_document_status(grouptenantmasid,createdby,createddatetime) values ('$groupmasid','$createdby','$createddatetime');";
    mysql_query($insert_doc_status);
    
    ////to show with file name pdf
    // print_r($pdf);
    $pdf->Output($filename, 'I');
    //ob_end_clean();
    exit;    
    //exit;    
    
//    header("Content-type: application/vnd.ms-word");
//    header("Content-Disposition: attachment; filename=document_name.doc");
//
//echo "<html>";
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
//echo "<body>";
//echo $headerContent;
//echo "</body>";
//echo "</html>";
    

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




<!--</body>
</html>-->