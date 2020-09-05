<?php
ob_start(); 
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasidz=$_GET['grouptenantmasid'];
$tenantmasidz=$_GET['tenantmasid'];

try
{
$sql = "SELECT * FROM draft_document1 WHERE section='lease' AND grouptenantmasid=".$grouptenantmasidz." AND tenantmasid=".$tenantmasidz."  ORDER BY draftid DESC LIMIT 1";


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
			
			 $sqlv1= "select a.tenantmasid,a.tenanttypemasid,a.salutation,a.leasename,a.tradingname,
				a.tenantcode,a.companymasid,a.buildingmasid,a.blockmasid,a.floormasid,
				a.shopmasid,a.shoptypemasid,a.orgtypemasid,a.nob,
				a.agemasidlt,a.agemasidrc,a.agemasidcp,a.creditlimit,
				a.latefeeinterest,a.doo,a.doc,a.pin,a.regno,a.address1,a.address2,a.city,
				a.state,a.pincode,a.country,a.poboxno,a.telephone1,a.telephone2,a.fax,a.emailid,
				a.website,a.remarks,a.cpname1,a.cpdesignation1,a.cpnid1,a.cpphone1,a.cpname2,a.cpdesignation2,a.cpnid2,a.cpphone2,
				a.createdby,a.createddatetime,a.modifiedby,a.modifieddatetime,a.active,a.renewal,
				a.renewalfromid,a.shopoccupied,
				b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,c.buildingname,c.municipaladdress,c.pledged,c.pledgedinbank,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size,
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
				b.age AS term, b1.age AS rentcycle,b1.description as rentdesc,c.buildingname,c.municipaladdress,c.pledged,c.pledgedinbank,c.city as buildingcity ,d.blockname, e.floorname, e.floordescription,f.shopcode, f.size,
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
			$viewSql = "create view view_offerletter_sctype as select sctype,basicscval from \n"
			    . "trans_offerletter a\n"			    
			    . "WHERE a.tenantmasid =".$tenantmasid;
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
			
			
			//load sctype details
			$sctype=0;$basicscval=0;$scterm="";
			$sql = "SELECT * FROM view_offerletter_sctype";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$sctype=$row['sctype'];
				$basicscval=$row['basicscval'];
			}
			if($sctype =="sc")
			{
				$scterm= $basicscval."% of the monthly rent";
			}
			else
			{
				$scterm= $basicscval." ksh per sqrft";
			}
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
				if($poboxno !="")
				{
					preg_match('/\d+/', $poboxno, $number);  // select only mo's				
					$poboxno = $number[0];
				}
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
				
				$buildingmunicipaladd = strtoupper($row['municipaladdress']);
				$pledged = $row['pledged'];
				$pledgedinbank = strtoupper($row['pledgedinbank']);
				
				$buildingcity = $row['buildingcity'];
				$blockname = $row['blockname'];
				$flooname = $row['floorname'];
				$floordescription = strtoupper($row['floordescription']);
				$shopcode = strtoupper($row['shopcode']);
				//$shopid .= $floordescription.",Shop No:".$shopcode;
				$shopid = $floordescription;
				$shopsizeid .= "(".$floordescription.",Shop No:".$shopcode.",Size:".$row['size']." sqrft)";
				//$tenantRent .= "<strong><u>".$buildingname.",".$floordescription.",Size:".$row['size']." sqrft</u></strong><br><br>";
				//$tenantSc .= "<strong><u>".$buildingname.",".$floordescription.",Size:".$row['size']." sqrft</u></strong><br><br>";
				
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
                                              //     $rentcycle=  strtolower($rk['rentcycle']);
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
			if($renewalfromid >0)
			{
				$sql ="drop view view_offerletter_deposit";
				mysql_query($sql);
				// create tenant deposit tbl view
				$viewSql = "create view view_offerletter_deposit as select b.* from \n"
				    . "trans_offerletter a\n"
				    . "inner join trans_offerletter_deposit b on b.offerlettermasid = a.offerlettermasid\n"
				    . "WHERE a.tenantmasid =".$renewalfromid;
				$result = mysql_query($viewSql);
			}
			
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
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";			
			$buildingcouncil="";
				if(strtoupper($buildingname) == "MEGA PLAZA"){
					////$buildingmunicipaladd = "Kisumu Municipality Block 7/380";
					$rowMunicipalAddress= "Municipality Block 7/380";
					$buildingcouncil = "Kisumu Municipal Council";
				}
				else if(strtoupper($buildingname) == "MEGA CITY"){
					////$buildingmunicipaladd = "Kisumu Municipality Block 9/134 &amp; 9/135";
					$rowMunicipalAddress= "Municipality Block 9/134 &amp; 9/135";
					$buildingcouncil = "Kisumu Municipal Council";
				}
				else if(strtoupper($buildingname) == "MEGA MALL"){
					////$buildingmunicipaladd = "Kakamega Municipality Block 111/97";
					$rowMunicipalAddress= "Municipality Block 111/97";
					$buildingcouncil = "Kakamega Municipal Council";
				}
				else if(strtoupper($buildingname) == "RELAINCE CENTRE"){
					$headerContent = "<tr>    
						<td width='40%'><img src='../images/mp_logo.jpg' height='50px'></td>
						<td width='35%'><img src='../images/mc_logo.jpg' height='50px'></td>
						<td align='right'><img src='../images/mm_logo.jpg' height='50px'></td>  
					</tr>
					<tr><td colspan='3' align='center'><h1><font size=6>GRANDWAYS VENTURE LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
					////$buildingmunicipaladd = "WOODVALE GROVE, WESTLANDS, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
					$rowMunicipalAddress= "";
					$buildingcouncil = "NAIROBI City Council";
				}else if(strtoupper($buildingname) == "KATANGI"){
					$headerContent = "
					<tr><td colspan='3' align='center'><h1><font size=6>KATANGI DEVELOPERS LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
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
					<tr><td colspan='3' align='center'><h1><font size=6>GRANDWAYS VENTURE LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
					////$buildingmunicipaladd = "Kitale Municipality Block 7/14";
					$rowMunicipalAddress= "Municipality Block 7/14";
					$buildingcouncil = "Kitale Municipal Council";
				}				
					//if(strtoupper($buildingname) == "MEGA PLAZA"){
					//	$buildingmunicipaladd = "Kisumu Municipality Block 7/380";
					//	$pledgedinbank=" CENTRAL BANK OF AFRICA LIMITED ";							
					//}
					//else if(strtoupper($buildingname) == "MEGA CITY"){
					//	$buildingmunicipaladd = "Kisumu Municipality Block 9/134 & 9/135";
					//	$pledgedinbank="EQUITY BANK ";
					//	$pledged =true;	
					//}
					//else if(strtoupper($buildingname) == "MEGA MALL"){
					//	$buildingmunicipaladd = "Kakamega Municipality Block III/97";
					//	$pledgedinbank="";	
					//	$pledged =false;	
					//}
					//else if(strtoupper($buildingname) == "RELIANCE CENTRE"){
					//	$buildingmunicipaladd = "Woodvale Grove, Westlands, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
					//	$pledgedinbank=" BANK OF AFRICA ";	
					//	$pledged =true;	
					//}
					//else if(strtoupper($buildingname) == "MEGA CENTRE"){
					//	$buildingmunicipaladd = " Kitale Municipality Block 7/14 ";
					//	$pledgedinbank=" ";	
					//	$pledged =true;	
					//}				
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
		$sql ="drop view view_offerletter_sctype";
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

    //$pdf = new MYPDF('L', 'cm', 'A4', true, 'UTF-8', false);
    
    // create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // remove default header/footer
    $pdf->setPrintHeader(false);
    //$pdf->setPrintFooter(false);   
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    
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
    
    // main page 1
    $pdf->AddPage();
    $pdf->SetFont('dejavusans','B',16);
    $pdf->Cell(40,10,'DATED THE');
    $pdf->Cell(50);
    $pdf->Cell(40,10,'DAY OF');
    $pdf->Cell(50);
    $pdf->Cell(40,10,date('Y'));
    $pdf->Cell(40);        
    $pdf->ln(60);
    
    $pdf->SetFont('dejavusans','BU',22);
    $pdf->Cell(0,10,'SUB-LEASE',0,0,'C');
    $pdf->ln(30);
    $pdf->Cell(0,10,$companyname,0,0,'C');
    $pdf->ln(30);
    $pdf->SetFont('dejavusans','B',18);
    $pdf->Cell(0,10,'AND',0,0,'C');
    $pdf->ln(30);
    $leasenamelength = strlen($tenantname);
    if($leasenamelength <= 40)
    {
	$pdf->SetFont('dejavusans','BU',17);
    }
    else
    {
	$pdf->SetFont('dejavusans','BU',14);
    }
    $pdf->Cell(0,10,$tenantname,0,0,'C');
    if($tradingname =="")
    {
	$pdf->ln(30);
    }
    else
    {
	$pdf->SetFont('dejavusans','B',13);
	$pdf->ln(15);
	$pdf->Cell(0,10," (T/A) ",0,0,'C');
	$pdf->ln(15);
	$pdf->Cell(0,10,$tradingname,0,0,'C');
	$pdf->ln(15);
    }
    
    $pdf->SetFont('dejavusans','B',10);
    $pdf->Cell(0,10,$buildingmunicipaladd,0,0,'C');
    $pdf->ln(30);
    $pdf->SetFont('dejavusans','BU',22);
    $pdf->Cell(0,10,$buildingname,0,0,'C');
    $pdf->ln(30);
    $pdf->SetFont('dejavusans','B',14);
    $pdf->Cell(0,10,$shops,0,0,'C');
    
    ////content page 2  
    $pdf->AddPage();
    
    $pdf->SetFont('dejavusans','B',9);    
    $pdf->Cell(5,10,'Form LRA62');
    $pdf->Cell(70);
    $pdf->Cell(40,10,'');
    $pdf->Cell(25);
    $pdf->Cell(40,10,'r.76(1)');
   // $pdf->ln(8);    
    //$pdf->Cell(5,10,'-----------------------------');
    //$pdf->Cell(70);
    //$pdf->Cell(40,10,'----------------------------');
    //$pdf->Cell(25);
    //$pdf->Cell(40,10,'KSHS PAID-----------------------');
   // $pdf->ln(8);
    //$pdf->Cell(5,10,'');
	// $pdf->Cell(70);
   // $pdf->Cell(40,10,'');
	//$pdf->Cell(25);
   // $pdf->Cell(40,10,'RECEIPT NO---------------------');
    $pdf->ln();
    $pdf->SetFont('dejavusans','BU',10);
    $pdf->Cell(0,10,'REPUBLIC OF KENYA',0,0,'C');$pdf->ln(6);
    $pdf->Cell(0,10,'THE LAND  REGISTRATION (GENERAL) REGULATIONS, 2017',0,0,'C');$pdf->ln(6);
    //$pdf->Cell(0,10,'IN THE MATTER OF THE LAND REGISTRATION ACT NO.3 OF 2012',0,0,'C');$pdf->ln(6);    
   // $pdf->Cell(0,10,'AND IN THE MATTER OF THE REGISTERED LAND ACT CAP 300',0,0,'C');$pdf->ln(6);    
   // $pdf->Cell(0,10,'(REPEALED)',0,0,'C');$pdf->ln(6);
   // $pdf->Cell(0,10,"TITLE ".$buildingmunicipaladd,0,0,'C');$pdf->ln(10);
   $pdf->ln();
    $pdf->Cell(0,10,'LEASE',0,0,'C');$pdf->ln(10);
    $pdf->Cell(0,10,'TITLE NUMBER:'.$buildingmunicipaladd,0,0,'L');$pdf->ln(12);
    $pdf->SetFont('dejavusans','',9);
    
    //$pa=$rowprint['pa'];
   // $pdf->MultiCell(0, 6, $pa."\n", 0, 'J');$pdf->ln(3);
	///$pdf->MultiCell(0, 6,$pa."\n", 0,'J');$pdf->ln(3);

    $pa=$rowprint['pa']; 
    $pdf->writeHTML($pa, true, false, false, false,'');  $pdf->ln(3); 
	
    $pb=$rowprint['pb'];
    $pdf->writeHTMLCell(0, 0, '', '', $pb, 0, 1, false, true, 'J', false);
    $pdf->ln(2);

     $pdf->SetFont('dejavusans','',8.8);

    $pc=$rowprint['pc']; 
    $pdf->writeHTMLCell(0, 0, '', '', $pc, 0, 1, false, true, 'J', false);  $pdf->ln(3); 
		
    //$pdf->writeHTML($tenantRent, true, false, true, false, '');

    $pd=$rowprint['pd'];
    $pdf->writeHTMLCell(0, 0, '', '', $pd, 0, 1, false, true, 'J', false);
    $pdf->ln(6);
    $pdf->AddPage();
    
    if($renewalfromid<=0)
    {

        $p0=$rowprint['p0']; 
    }
    else
    {

         $p0=$rowprint['p0']; 
    }
        
    
    
    $pdf->writeHTMLCell(0, 0, '', '', $p0, 0, 1, false, true, 'J', false);
    $pdf->ln(6);

    
    $pdf->SetFont('dejavusans','B',9);
    $p1=$rowprint['p1']; 
    $pdf->MultiCell(0,10, $p1."\n", 0, 'J');
    $pdf->ln(6);    
    $pdf->SetFont('dejavusans','',8.8);

    $p2=$rowprint['p2']; 
    $pdf->writeHTMLCell(0, 0, '', '', $p2, 0, 1, false, true, 'J', false);
    $pdf->ln(2);
    
    $pdf->SetFont('dejavusans','B',9);

    $p3=$rowprint['p3']; 
    $pdf->MultiCell(0,10, $p3."\n", 0, 'J');
    $pdf->ln(5);
   
    $pdf->SetFont('dejavusans','',8.8);

    $p4=$rowprint['p4']; 
    $pdf->writeHTMLCell(0, 0, '', '', $p4, 0, 1, false, true, 'J', false);
    $pdf->ln(2);
    
    $pdf->SetFont('dejavusans','B',9);

    $p4a=$rowprint['p4a']; 
    $pdf->MultiCell(0,10, $p4a."\n", 0, 'J');
    $pdf->ln(6);
    
   $pdf->SetFont('dejavusans','',8.8);

    $p5=$rowprint['p5']; 
    $pdf->writeHTMLCell(0, 0, '', '', $p5, 0, 1, false, true, 'J', false);
    $pdf->ln(2);
   
    $pdf->AddPage();

    $p6=$rowprint['p6']; 
    $pdf->writeHTMLCell(0, 0, '', '', $p6, 0, 1, false, true, 'J', false);
    $pdf->ln(1);
	$pdf->AddPage();
    $pdf->SetFont('dejavusans','',9);
    if ($tradingname == "")
    {

         $p7=$rowprint['p7']; 
    }
    else
    {

        $p7=$rowprint['p7']; 
    }
    
    $pdf->writeHTMLCell(0, 0, '', '', $p7, 0, 1, false, true, 'J', false);
    $pdf->ln(2);
    
    
	if($pledged == 1)
	{

            $p8=$rowprint['p8']; 
            $pdf->AddPage();
            $pdf->ln(2);
            $pdf->writeHTMLCell(0, 0, '', '', $p8, 0, 1, false, true, 'J', false);
            $pdf->ln(2);
	}
	else if($pledged == 0)
	{

            $p8=$rowprint['p8']; 
            $pdf->AddPage();
            $pdf->ln(2);
            $pdf->writeHTMLCell(0, 0, '', '', $p8, 0, 1, false, true, 'J', false);
            $pdf->ln(2);
	}
    
    if($renewalfromid<=0)
        $filename = $tenantname." - (".$tenantcode.")";
    else
        $filename = $tenantname." - (".$tenantcode."-RENEWED)";
        
    $filename .=" - Lease ";
    $filename = clean($filename);
    $pdf->Output("../../pms_docs/leases/".$filename.".pdf","F");
    
    ////INSERT NEW LEASE
    $createdby = $_SESSION['myusername'];
    $createddatetime = $datetime;	
    $insert = "insert into rpt_lease (grouptenantmasid,createdby,createddatetime) values ($groupmasid,'$createdby','$createddatetime')";
    mysql_query($insert);
        
    ////to show with file name pdf
	//ob_clean();
    $pdf->Output($filename, 'I');
    exit;
    ////to download pdf
    ////$pdf->Output('lease.pdf','D');
    ////to show with file name pdf
    ///$pdf->Output('example_001.pdf', 'I');

}
else
{	
        ////RETRIEVE OLD LEASE
	while($row = mysql_fetch_assoc($result))
	{
		//$div1 = $row['rowcontent'];                
                
	}        
}
//$div2 ="<table>
//	   <tr id='controlpanel'>
//		   <td>
//			   <button type='button' id='btnEdit1' name='1'>Edit</button> &nbsp					
//			   <div id='divText1' style='height: 0px;visibility: hidden;'>
//				   <textarea id='editor1' rows='0' cols='0'>
//					  
//				   </textarea>
//			   <button type='button' id='btnUpdate1' name='1'>Update</button>
//			   </div>
//		   </td>
//	   </tr>	
//	</table>";
//	
//	$custom = array(
//		    'divContent'=> $div0.$div1.$div2,
//		    's'=>'Success');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
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