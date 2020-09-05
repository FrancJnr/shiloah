<?php
header("Content-type: text/xml");
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];

////$nk =0;$sqlGet="";
////foreach ($_GET as $k=>$v) {
////    $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
////    $nk++;
////}
////$custom = array('msg'=> $sqlGet ,'s'=>'error');
////$response_array[] = $custom;
////echo '{"error":'.json_encode($response_array).'}';
////exit;

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
				$amtrent = $row['amount'];
				$offerlettermasid = $row['offerlettermasid'];
				$tenantRent .="<tr>"
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
				$tenantRent .="<td align='right'>".number_format($amtrent,0, '.', ',')."</td>"
						."<td>Per Month</td>"
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
				$amtsc = $row['amount'];
				$tenantSc .="<tr>"
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
				$tenantSc .= "<td align='right'>".number_format($amtsc,0, '.', ',')."</td>"
						."<td>Per Month</td>"
						."</tr>";
						$sc =$row['amount'];
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
						<td width='40%'><img src='../images/mp_logo.jpg' height='50px'></td>
						<td width='35%'><img src='../images/mc_logo.jpg' height='50px'></td>
						<td align='right'><img src='../images/mm_logo.jpg' height='50px'></td>  
					</tr>
					<tr><td colspan='3' align='center'><h1><font size=6>GRAND VENTURES LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
					$buildingmunicipaladd = "WOODVALE GROVE, WESTLANDS, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
					$rowMunicipalAddress= "";
				}else if(strtoupper($buildingname) == "KATANGI"){
					$headerContent = "
					<tr><td colspan='3' align='center'><h1><font size=6>KATANGI DEVELOPERS LTD.</font></h1></td></tr>
					<tr>    
						<td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.com</td>
						<td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
						<td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
					</tr>";
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
					
				if($tradingname ==""){
					$leaseclass = " incorporated in the Republic of Kenya with a limited liability";
					$tradingas="";
					$leasebreak ="<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
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

$shops = rtrim($shops,",");

$div0="<span id='span'>Print Preview Rectification of Lease for  <font color='blue'> M/s. $tenantname ($tenantcode)</font>&nbsp;
	<button type='button' id='btnPreview' name='0'>Save & Print</button></span>
        <p class='printable'><table width='100%' border=0><tbody><tr><td>";
$div1="";
	
$sql = "select * from rpt_rect_lease where grouptenantmasid =$groupmasid";
$result = mysql_query($sql);
$rowcount = mysql_num_rows($result);

if ($rowcount == 0)
{
//Insert New lease
    $div1 .="<li class='ui-state-default'>
                <table width='100%' border=0>
                    <tr>
                        <td id='content1' valign='middle' align='justify' style='font-size:12px;'>
                            Deed of Rectification of a Sub-Lease
                        </td>
                    </tr>				
                </table>
            </li>";
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
                                                                <strong>".$tenantname."<br>".$tradingname."</strong><br><br>
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
                                        I, ____________________Advocate CERTIFY that the above named personns appeared
					before me on this _____ Day of _________________ ".convert_number(date('Y'))." and being
                                        identified to me acknowledged the above signature to be his/her and that he/she had freely and voluntarily executed this
                                        instrument and understood its contents.
                                </td>
                        </tr>
                </table>
        </li>";
   
    }
	if($pledged == true)
	{
	$div1 .="<br><br><br><br>";
	$div1 .="<li class='ui-widget-content'>
			<table width='100%' border=0>
				<tr>
					<td  align='center' style='valign='middle'><strong><u>MEMORANDUM OF CHARGES AND ENCUMBRANCES</u></strong></td>
				</tr><tr>
					<td valign='middle' align='justify'>
						We <strong>$bank</strong> being the proprietor of the charge registered over Title Number
						<strong>$buildingmunicipaladd</strong> hereby give our consent to the creation and registration of the lease in
						favour of <strong>".$tenantname."</strong> provided that the said Lease does not prejudice in any
						way our rights, interests and preference therein.
					</td>
				</tr>
				<tr>
					<td  valign='middle' style='height:50px;'><strong>SEALED WITH THE COMMON SEAL OF</strong></td>
				</tr>
				<tr>
					<td  valign='middle' style='height:50px;'><strong>EQUITY BANK LIMITED</strong></td>
				</tr>
				<tr>
					<td  valign='middle' style='height:50px;'><strong>IN THE PRESENCE OF:</strong></td>
				</tr>
				<tr>
					<td  valign='middle' style='height:50px;'><strong>DIRECTOR_______________________</strong></td>
				</tr>
				<tr>
					<td  valign='middle' style='height:50px;'><strong>SECRETARY_______________________</strong></td>
				</tr>
				<tr>
					<td  valign='middle' style='height:50px;'>REGISTERED THIS _______ DAY OF __________________________________ ".convert_number(date('Y'))."</td>
				</tr>
				<tr>
					<td  valign='middle' align='center' style='height:50px;'>_______________________<br>Land Registrar</td>
				</tr>
				<tr>
					<td  valign='middle'  style='height:30px;font-weight:bold;'>
					<u>DRAWN BY:</u><br><br>
					WASUNA & CO. ADVOCATES<br>
					NATIONAL BANK BUILDING<br>
					SOUTH PODIUM, 2ND FLOOR<br>
					HARAMBEE AVENUE<br>
					P.O. BOX 34992 00100<br>
					NAIROBI<br>
					KENYA<br>
					</td>
				</tr>
			</table>
		</li>";
	}
	$div1 .="</ul>";
	
	////INSERT NEW LEASE
	$instr = $div1;
	$instr = "'".mysql_real_escape_string($instr)."'";
	$insert = "insert into rpt_rect_lease (grouptenantmasid,rowcontent) values ($groupmasid,$instr)";
	mysql_query($insert);
}
else
{
	////RETRIEVE OLD LEASE
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