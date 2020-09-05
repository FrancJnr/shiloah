<?php
include('../config.php');
session_start();
//include('ip_test.php');
class MYPDF extends TCPDF {
        //Page header
        public function Header() {
            // Logo
            $image_file = "../images/mpg1.png";
            $this->Image($image_file, '', '', 39, 39, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // Set font
            //$this->SetFont('helvetica', 'B', 20);
            // Title
            //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }
             // Page footer
            public function Footer() {
                // Position at 15 mm from bottom
                $this->SetY(-15);
                // Set font
                $this->SetFont('dejavusans', 'I', 8);
                // Page number
                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }
        }
   // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);	
        $pdf->SetAutoPageBreak(TRUE, 0);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        $pdf->SetMargins(PDF_MARGIN_LEFT, '', PDF_MARGIN_RIGHT);
        // set default footer margin
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);   

$company = strtoupper($_SESSION["mycompany"]);

try{
$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;$mnthlyrent=0;
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$fromdate = explode(" ",$_GET['invdt']);
$firstdate = $_GET['invdt'];


$buildingname = "";$rect_date="";$orig_cycle=""; $shortname="";
$s = "select a.buildingname,a.companymasid,a.isvat,b.companyname
        from mas_building a
        inner join mas_company b on b.companymasid=a.companymasid
        where a.buildingmasid =$buildingmasid";
$r = mysql_query($s);
 while($ro = mysql_fetch_assoc($r))
    {
        $buildingname = strtoupper($ro["buildingname"]);
        $building_companymasid = $ro['companymasid'];
        $building_company_name = strtoupper($ro['companyname']);
	$isvat =$ro["isvat"];	
    }
$filename = $buildingname." - (".$firstdate.")";
$sqrftTotal=0;
$table ="<p class='printable'><table class='custom' style='padding: 0px;font-size: 10px;font-family: Verdana, Arial, Helvetica, sans-serif;margin: 8px 0px 8px 0px;'>";
$table .="<tr><th colspan='14'>$buildingname Rental Schedule For the Period of ".$firstdate."</th></tr>";
$table .="<tr align='center' style='font-weight:bold;color:brown;'>";
$table .="<td>Index</td>";
$table .="<td>Shop</td>";
$table .="<td>Sqft</td>";
$table .="<td>Inv No</td>";
$table .="<td>Tenant</td>";
$table .="<td>Cycle</td>";
$table .="<td>Period</td>";
$table .="<td>Rent</td>";
$table .="<td>Vat 14%</td>";
$table .="<td>Rent+Vat</td>";
$table .="<td>Scr Chrg</td>";
$table .="<td>Vat 14%</td>";
$table .="<td>Sc+Vat</td>";
$table .="<td>Monthly Rent</td>";
$i=0;
$rentgtotal=0;$scgtotal=0;$today=0;$chksql="";
$rentvatgtotal=0;$scvatgtotal=0;
$rentandvatgtotal=0;$scandvatgtotal=0;
$grandtotal=0;$totsize=0;$expdt=0;$doc=0;

$sql = "select a.grouptenantmasid ,b.tenantmasid, b.salutation,b.leasename,date_format(b.doc,'%d-%m-%Y') as doc,e.shoptype,e.shoptypemasid,d.companymasid, d.shortname from group_tenant_mas a 
	inner join mas_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	inner join mas_shoptype e on e.shoptypemasid = b.shoptypemasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' and b.shopoccupied='1' not in 
	    (
		select a1.grouptenantmasid from trans_tenant_discharge_op a1
                inner join  trans_tenant_discharge_ac b1 on b1.grouptenantmasid = a1.grouptenantmasid
                where a1.opapproval ='1' and b1.acapproval='1'
	    ) and a.grouptenantmasid not in (select grouptenantmasid from waiting_list)
	union
	select a.grouptenantmasid ,b.tenantmasid, b.salutation,b.leasename,date_format(b.doc,'%d-%m-%Y') as doc,e.shoptype,e.shoptypemasid,d.companymasid, d.shortname from group_tenant_mas a 
	inner join rec_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	inner join mas_shoptype e on e.shoptypemasid = b.shoptypemasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' and b.shopoccupied='1' not in 
	    (
		select a2.grouptenantmasid from trans_tenant_discharge_op a2
                inner join  trans_tenant_discharge_ac b2 on b2.grouptenantmasid = a2.grouptenantmasid
                where a2.opapproval ='1' and b2.acapproval='1'
	    ) and a.grouptenantmasid not in (select grouptenantmasid from waiting_list)
        order by shoptypemasid asc,leasename asc;";
$result = mysql_query($sql);
$jk=1;$m1=0;
while($row = mysql_fetch_assoc($result))
{
        $cnt=0;$tmas="";$rm =0;$fv=0;
	$grouptenantmasid = $row['grouptenantmasid'];
	$building_companymasid = $row['companymasid'];
	$shortname = $row['shortname'];
	$tenantmasid=$row['tenantmasid'];
	$fromdate = explode(" ",$_GET['invdt']);
	$advancerent=false;
	$shop="";$size="";$leasename="";$leasenom="";$codetenant="";$tradingname="";$shoptype="";$rentcycle="";$rct="0";$monthdiff=0;$period="";$renewal=0;
	$prdfrom="";$prdto="";
	$rent=0;$sc=0;
	$rentvat=0;$scvat=0;
	$rentandvat=0;$scandvat=0;
	$rowtotal="";
	$tenancyrefcode = gettenancyrefcode($grouptenantmasid);//gettenantcode($tenantmasid);
	//echo $tenancyrefcode."..........".$leasename."<br>";
	
	//$tenantcode = gettenantcode($tenantmasid);
	$sql1 = "select a.tenantmasid from group_tenant_det a  
		 inner join mas_tenant b on b.tenantmasid = a.tenantmasid
		 where a.grouptenantmasid = $grouptenantmasid  and b.active='1' and b.shopoccupied='1'";
		 
	$result1=mysql_query($sql1);
	if($result1 !=null)
	{
	    $rcount = mysql_num_rows($result1);
	    if($rcount ==0) 
	    {
		$sql1 = "select a.tenantmasid from group_tenant_det a  
			    inner join rec_tenant b on b.tenantmasid = a.tenantmasid
			    where a.grouptenantmasid = $grouptenantmasid  and b.active='1' and b.shopoccupied='1'";
	    }
	}	
	$jk=1;
	$result1 = mysql_query($sql1);
	$cnt = mysql_num_rows($result1);
	$sql2="";
	while($row1 = mysql_fetch_assoc($result1))
	{	
	    $cnt1=0;
	    $d1=0;$d2=0;$m2=0; $ex="";
	    $tenantmasid = $row1['tenantmasid'];
	     
	     $sql2="select b.shopcode ,b.size,a.salutation,a.leasename,a.tradingname,c.age as 'rentcycle',c.shortdesc,a.renewal,a.tenantcode,a.buildingmasid,
		    a.poboxno,a.pincode,a.city,d.cpname,d.cpmobile,d.cplandline from mas_tenant a
		    inner join mas_shop b on b.shopmasid = a.shopmasid
		    inner join mas_age c on c.agemasid = a.agemasidrc
		    inner join mas_tenant_cp d on d.tenantmasid = a.tenantmasid
		    where a.tenantmasid =$tenantmasid and a.active ='1'and d.documentname='1' limit 1;";
		    
		    
		    $result2=mysql_query($sql2);
		    if($result2 !=null)
		    {
			$rcount1 = mysql_num_rows($result2);
			if($rcount1 ==0) 
			{
			     $sql2="select b.shopcode ,b.size,a.salutation,a.leasename,a.tradingname,c.age as 'rentcycle',c.shortdesc,a.renewal,a.tenantcode,a.buildingmasid,
				    a.poboxno,a.pincode,a.city,d.cpname,d.cpmobile,d.cplandline from rec_tenant a
				    inner join mas_shop b on b.shopmasid = a.shopmasid
				    inner join mas_age c on c.agemasid = a.agemasidrc
				    inner join rec_tenant_cp d on d.tenantmasid = a.tenantmasid
				    where a.tenantmasid =$tenantmasid and a.active ='1'and d.documentname='1' limit 1;";
			    $rct ="1";
			}
                    }
		    $result2 = mysql_query($sql2);		    
		    if($jk==1)
                    {                            
                            $tsql1 = "select date_format(a.fromdate,'%d') as 'dt' from trans_offerletter_rent  a 
                                        inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
                                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                                        where  b.tenantmasid=$tenantmasid group by b.offerlettermasid;";
                            $tresult1 = mysql_query($tsql1);
                            if($tresult1 !=null)
                            {
                                $rcntdt = mysql_num_rows($tresult1);
				if($rcntdt == 0)
				continue;
				while($row3 = mysql_fetch_assoc($tresult1))
                                {
                                    $fromdate = $row3['dt'].$fromdate[0]."-".$fromdate[1];
                                }				
				$fromdate = date("Y-m-d", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Day"));
                            }
                    }
		    $jk++;
		    while($row2 = mysql_fetch_assoc($result2))
		    {
			$tenantpoboxno = $row2['poboxno'];
			$tenantpincode = $row2['pincode'];
			$tenantcity  = $row2['city'];
			$tenantcpname = $row2['cpname'];
			$tenantmobile = $row2['cpmobile'];
			$tenantlandline = $row2['cplandline'];
			//$codetenant= $row2['tenantcode'];
			$rentdesc = $row3['shortdesc'];
			$renewal=$row2['renewal'];
			$shop .=$row2['shopcode']."<br>";
			$size .=$row2['size']."<br>";
			$totsize +=$row2['size'];
			
			//if($row2['tradingname'] !="")
			//$leasename = $row2['tradingname'];
			//else
			//$leasename = $row2['leasename'];
                        
			$rentcycle = strtolower($row2['rentcycle']);
			$period  = strtolower($row2['shortdesc'])."<br>";
			
			$tmas .=$tenantmasid."<br>";
			//$m1=31;
			$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,c.salutation,c.leasename,c.tradingname,c.remarks,s.shoptype,	
				DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.age as 'oldcycle',e.shortdesc,
				round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
				DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
				@t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
				DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
				DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
				DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc
				from  trans_offerletter a
				inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
				inner join trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
				inner join mas_tenant c on c.tenantmasid = a.tenantmasid
				inner join mas_age d on d.agemasid = c.agemasidlt
				inner join mas_age e on e.agemasid = c.agemasidrc
				inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
				where a.tenantmasid =$tenantmasid 
				and '$fromdate' between b.fromdate and b.todate
				and '$fromdate' between f.fromdate and f.todate
				and c.active ='1'
				and a.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by a.offerlettermasid;";
				
			        $result3 = mysql_query($sql3);
				if($result3 !=null)
				{
				    $rcount = mysql_num_rows($result3);
				    if($rcount ==0) 
				    {                    
					$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,c.salutation,c.leasename,c.tradingname,c.remarks,s.shoptype,
						DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.shortdesc,
						round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
						DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
						@t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
						DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
						DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
						DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc,e1.age as 'oldcycle'
						from  rec_trans_offerletter a
						inner join rec_trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
						inner join rec_trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
						inner join mas_tenant c on c.tenantmasid = a.tenantmasid
						inner join mas_age d on d.agemasid = c.agemasidlt
						inner join mas_age e on e.agemasid = c.agemasidrc
						inner join mas_tenant c1 on c1.tenantmasid = c.tenantmasid 
						inner join mas_age e1 on e1.agemasid = c1.agemasidrc
						inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
						where a.tenantmasid =$tenantmasid 
						and '$fromdate' between b.fromdate and b.todate
						and '$fromdate' between f.fromdate and f.todate
						and c.active ='1' group by a.offerlettermasid;";
					     $result3 = mysql_query($sql3);
					    if($result3 !=null)
					    {
						$rcount = mysql_num_rows($result3);
						if($rcount ==0) 
						{                    
						    $sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,c.salutation,c.leasename,c.tradingname,c.remarks,s.shoptype,
							    DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.shortdesc,
							    round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
							    DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
							    @t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
							    DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
							    DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
							    DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc,e1.age as 'oldcycle'
							    from  trans_offerletter a
							    inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
							    inner join trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
							    inner join rec_tenant c on c.tenantmasid = a.tenantmasid
							    inner join mas_age d on d.agemasid = c.agemasidlt
							    inner join mas_age e on e.agemasid = c.agemasidrc
							    inner join mas_tenant c1 on c1.tenantmasid = c.tenantmasid 
							    inner join mas_age e1 on e1.agemasid = c1.agemasidrc
							    inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
							    where a.tenantmasid =$tenantmasid 
							    and '$fromdate' between b.fromdate and b.todate
							    and '$fromdate' between f.fromdate and f.todate
							    and c.active ='1'
							    and a.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by a.offerlettermasid;";
							$result3 = mysql_query($sql3);
							if($result3 !=null)
							{
							    $rcount = mysql_num_rows($result3);
							    if($rcount ==0) 
							    {                    
								$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,c.salutation,c.leasename,c.tradingname,c.remarks,s.shoptype,		
									DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.shortdesc,
									round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
									DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
									@t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
									DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
									DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
									DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc,e1.age as 'oldcycle'
									from  rec_trans_offerletter a
									inner join rec_trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
									inner join rec_trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
									inner join rec_tenant c on c.tenantmasid = a.tenantmasid
									inner join mas_age d on d.agemasid = c.agemasidlt
									inner join mas_age e on e.agemasid = c.agemasidrc
									inner join mas_tenant c1 on c1.tenantmasid = c.tenantmasid 
									inner join mas_age e1 on e1.agemasid = c1.agemasidrc
									inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
									where a.tenantmasid =$tenantmasid 
									and '$fromdate' between b.fromdate and b.todate
									and '$fromdate' between f.fromdate and f.todate
									and c.active ='1' group by a.offerlettermasid;";
							    }
							}
							
						}
					    }
				    }
				}				
			    $result3 = mysql_query($sql3);			    
			    while($row3 = mysql_fetch_assoc($result3))
			    {
				
				$expdt = $row3['expdt'];
				$tradingname="";
				if($row3['tradingname'] !="")
				{
				    $tradingname ="<br>&nbsp;&nbsp;&nbsp;&nbsp;(T/A) ".$row3['tradingname'];				    
				}
				$remarks  = $row3['remarks'];
				$leasename = $row3['salutation']." ".$row3['leasename'];
				$leasenom = $row3['leasename'];
				//$codetenant= $row3['tenantcode'];
				$shoptype=$row3['shoptype'];
				
				$rentcycle = strtolower($row3['rentcycle']);
				$period  = strtolower($row3['shortdesc'])."<br>";
				$oldcycle = $row3['oldcycle'];
				
			    	if(strtolower($row3['oldcycle']) == 'per quarter')            
			        {
				    $row3['rent'] = $row3['rent'] /3;
				    $row3['sc'] = $row3['sc'] /3;
			        }
				else if(strtolower($row3['oldcycle']) == 'per half')            
			        {
				    $row3['rent'] = $row3['rent'] /6;
				    $row3['sc'] = $row3['sc'] /6;
			        }
				else if(strtolower($row3['oldcycle']) == 'per year')            
			        {
				    $row3['rent'] = $row3['rent'] /12;
				    $row3['sc'] = $row3['sc'] /12;
			        }
			       if($rentcycle == 'per month')            
			       {                
				   $rm =1;$fv=1;
				   $monthdiff = fmod($row3['monthDiff'],0);               
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 1 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
				
			       }
			       else if($rentcycle == 'per quarter')
			       {			
				   $rm =3;$fv=3;
				   $monthdiff = fmod($row3['monthDiff'],3);               
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 3 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
			       }
			       else if($rentcycle == 'per half')
			       {
				   $rm =6;$fv=6;
				   $monthdiff = fmod($row3['monthDiff'],6);                 
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 6 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
			       }
			       else if($rentcycle == 'per year')
			       {
				   $rm =12;$fv=12;
				   $monthdiff = fmod($row3['monthDiff'],12);                 
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 12 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
				    ////   if($tenantmasid =='445'){//renewed jamii telecom megamall per year check up
				    ////	$custom = array('divContent'=>$d1."--".$d2,'s'=>'Success');
				    ////	$response_array[] = $custom;
				    ////	echo '{"error":'.json_encode($response_array).'}';
				    ////	exit;
				    ////    }
			       }
			       
			        //chk in advance invoice available-----------------------------------------------------
				    for($f=0;$f<$fv;++$f){				   
					$m1 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($fromdate)) . " + $f Months"));
					$sqlchk = "select months,todate			   
						    from advance_rent where grouptenantmasid =$grouptenantmasid and
						    '$m1' between  fromdate and  todate ;";
					$resultchk=mysql_query($sqlchk);		
					if($resultchk !=null)
					{				    
					    $rcount = mysql_num_rows($resultchk);
					    if($rcount > 0) 
					    {																	    						
						$rm = $rm-1;
						$advancerent=true;
					    }
					}									    
				   }
			       //chk in advance invoice available-------------------------------------------------------
			       
				//if($advancerent ==true)
				//{
				//    $rent +=$row3['rent']*$rm;
				//    $sc +=$row3['sc']*$rm;
				//}
				//else
				//{
				//    $rent +=$row3['rent'];
				//    $sc +=$row3['sc'];				   
				//}
				$rent +=$row3['rent']*$rm;
				$sc +=$row3['sc']*$rm;
			       $invperiod = $row3['invperiod'];
			    }
		    }
	}
	
	if(is_nan($monthdiff))
	$monthdiff =0;
	
	 if(($d1=='0') or ($d2=='0')or($rent=='0')){
	    continue;
	}
	
	if($monthdiff <= 0)
	{
	    $i++;
	    if ($cnt > 1)
	    $table .="<tr bgcolor='yellow'>";
	    else 
	    $table .="<tr>";	  	    
	    $today = strtotime($fromdate);
	    $expdt = strtotime($expdt);
	    
	    if ($expdt < $today) {
		
		$sql4="select doc from mas_tenant where tenantmasid = '$tenantmasid' and active='1';";
		$result4 = mysql_query($sql4);
		while($row4 = mysql_fetch_assoc($result4))
		{
		    $doc = $row4['doc'];
		}
		$doc = strtotime($doc);
		
		if ($doc > $today)
		    $table .="<tr bgcolor='green' style='color:white'>";
		else
		    $table .="<tr bgcolor='red' style='color:white'>";
		    
		if($renewal == '1')
		{		    
		    continue; // if renewd continue
		}
	    } 

	    
	    $table .="<td align='center'>".$i."</td>";
	    $table .="<td>".$shop."</td>";
	    $shoptype = strtolower($shoptype);
	    if($shoptype=="trolley")
	    $size=$shoptype;
	    else if($shoptype=="kiosk")
	    $size=$shoptype;
	    else if($shoptype=="others")
	    $size=$shoptype;
	    
	    $table .="<td>".$size."</td>";
	    
	    //if($i==1)
	    $invno = getinvno($building_companymasid);
	    $taxinvoiceno = $invno."/".date('Y');
            
	    $table .="<td>".$taxinvoiceno."</td>";
		    
	    $rid=0;
	    
	    $sql5="select renewalfromid from mas_tenant where tenantmasid = '$tenantmasid' and active='1';";
	    $result5 = mysql_query($sql5);
	    while($row5 = mysql_fetch_assoc($result5))
	    {
		$rid = $row5['renewalfromid'];
	    }
	    $leasename = $leasename.$tradingname;
	    if ($rid == 0){
		$table .="<td>".$leasename."</td>";		    
	    }
	    else
	    {
		$table .="<td>$leasename&nbsp;&nbsp;[<font color='blue'>Renewed</font>]</td>";		    
	    }	    	    
	    if ($rct == 1)
	    {
		$table .="<td bgcolor='#dbdbdb'>".$period."</td>";
	    }
	    else
	    {
		$table .="<td>".$period."</td>";
	    }
	    
	    $table .="<td>".$d1."<br>to<br>".$d2."</td>";		

	    $table .="<td dir=rtl>".number_format($rent, 0, '.', ',')."</td>";
	    $rentgtotal +=$rent;

	    if($isvat == 1)
	    {
		$rentvat = round($rent*14/100,0,PHP_ROUND_HALF_EVEN);
		$table .="<td dir=rtl>".number_format($rentvat, 0, '.', ',')."</td>";
		$rentvatgtotal +=$rentvat;
	    }
	    else
	    {
		$table .="<td dir=rtl> 0 </td>";
	    }
	    
	    $rentandvat = $rentvat +$rent;
	    $table .="<td dir=rtl>".number_format($rentandvat, 0, '.', ',')."</td>";
	    
	    $table .="<td dir=rtl>".number_format($sc, 0, '.', ',')."</td>";
	    $scgtotal +=$sc;
	    
	    if($isvat == 1)
	    {
		$scvat = round($sc*14/100,0,PHP_ROUND_HALF_EVEN);;
		$table .="<td dir=rtl>".number_format($scvat, 0, '.', ',')."</td>";
		$scvatgtotal +=$scvat;
	    }
	    else
	    {
		$table .="<td dir=rtl> 0 </td>";
	    }
	    
	    $scandvat = $scvat +$sc;
	    $table .="<td dir=rtl>".number_format($scandvat, 0, '.', ',')."</td>";
	
	    
	    $rowtotal=$rentandvat+$scandvat;
	    $table .="<td dir=rtl>".number_format($rowtotal, 0, '.', ',')."</td>";
	    $grandtotal +=$rowtotal;
		
	    $address ='<table cellpadding="2" >		
	    <tr height="70px">    
		    <td width="35%">P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.co.ke</td>
		    <td width="35%">Mega Plaza Block "B" 11th Floor<br>Oginga Odinga Road<br>Kisumu.</td>
		    <td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400</td>    
	    </tr>	
	    </table>';
	    $tenantcontactno = $tenantmobile.' / '. $tenantlandline;
	    $tenantcontactno = ltrim($tenantcontactno,' /');
	    $tenantcontactno = rtrim($tenantcontactno,'/ ');
	    $tenantpobox = $tenantpoboxno.' - '. $tenantpincode;
	    $tenantpobox = ltrim($tenantpobox,' -');
	    $tenantpobox = rtrim($tenantpobox,'- ');
		
	    $tenantdetails ='<table width="100%">		
	    <tr>
		    <td valign="top">TO:<br>&nbsp;&nbsp;&nbsp;&nbsp;<font size="9.5px"><b>'.$leasename.'</b></font>,
					    <br>&nbsp;&nbsp;&nbsp;&nbsp;Tenancy Code: '.$tenancyrefcode.',
					    <br>&nbsp;&nbsp;&nbsp;&nbsp;Phone    :'.$tenantcontactno.',
					    <br>&nbsp;&nbsp;&nbsp;&nbsp;P.O BOX NO:'.$tenantpobox.',
					    <br>&nbsp;&nbsp;&nbsp;&nbsp;'.$tenantcity.'.
				    
		    </td>
		    <td align="right">			
			    <table cellpadding="5" border="1" style="font-weight:bold;">
				    <tr>
					<td bgcolor="#dddddd" width="35%">Invoice #</td>
					<td align="right">'.$taxinvoiceno.'</td>
				    </tr>
				     <tr>
					<td bgcolor="#dddddd" width="35%">Date</td>
					<td align="right">'.date("d-m-Y", strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +  0 Months")).'</td>
				    </tr>
				     <tr>
					<td bgcolor="#dddddd" width="35%">Amount Due</td>
					<td align="right">KSHS '.number_format($rowtotal, 0, '.', ',').'/-</td>
				    </tr>
			    </table>
		    </td>
	    </tr>
	    </table>';
	    
	    $rentperiod="";
	    if($advancerent== true)
	    {
		$rm1 = $rm;
		$rm1 = $rm1-1;
		$rentperiod .=date("d-m-Y", strtotime(date("Y-m-d", strtotime($d1)) . " +  $rm1 Months"));
	    }
	    else
	    {
		$rentperiod .=date("d-m-Y", strtotime(date("Y-m-d", strtotime($d1)) . " +  0 Months"));
	    }
	    
	    $rentperiod .= ' to ';
	    $rentperiod .=date("d-m-Y", strtotime(date("Y-m-d", strtotime($d2)) . " +  0 Months"));
	    
	    $premisesdetails='<table width="100%" cellpadding="5" border="1">
		    <tr>
			<td width="25%" bgcolor="#dddddd">Premises / Shop</td>
			<td width="75%">'.$buildingname.', Shop No: '.rtrim($shop,",").'</td>
		    </tr>
		    <tr>
			<td width="25%" bgcolor="#dddddd">Rent Cycle</td>
			<td width="75%">'.$rentdesc.'</td>
		    </tr>
		    <tr>
			<td width="25%" bgcolor="#dddddd">Period</td>
			<td width="75%">'.$rentperiod.'</td>
		    </tr>
		</table>';	
	    $rentdetails='<table width="100%" cellpadding="3" border="1">
		    <tr align="center">
			    <td bgcolor="#dddddd" width="10%">S.No</td>
			    <td bgcolor="#dddddd" width="30%">Description</td>
			    <td bgcolor="#dddddd" width="20%">Value</td>
			    <td bgcolor="#dddddd" width="20%">Vat (14%)</td>
			    <td bgcolor="#dddddd" width="20%">Amount</td>
		    </tr>
		    <tr>
			    <td width="10%">1</td>
			    <td width="30%">Rent</td>
			    <td width="20%" align="right">'.number_format($rent, 0, '.', ',').'</td>
			    <td width="20%" align="right">'.number_format($rentvat, 0, '.', ',').'</td>
			    <td width="20%" align="right">'.number_format($rentandvat, 0, '.', ',').'</td>
		    </tr>
		    <tr>
			    <td width="10%">2</td>
			    <td width="30%">Service Charge Deposit</td>
			    <td width="20%" align="right">'.number_format($sc, 0, '.', ',').'</td>
			    <td width="20%" align="right">'.number_format($scvat, 0, '.', ',').'</td>
			    <td width="20%" align="right">'.number_format($scandvat, 0, '.', ',').'</td>
		    </tr>
		    <tr>
			    <td align="right" colspan="2">Grand Total</td>				
			    <td align="right">'.number_format($rent+$sc, 0, '.', ',').'</td>
			    <td align="right">'.number_format($rentvat+$scvat, 0, '.', ',').'</td>
			    <td align="right">'.number_format($rentandvat+$scandvat, 0, '.', ',').'</td>
		    </tr>
		</table>';
		
                $bottom='<table cellpadding="5" border="1" width="100%">
                    <tr height="30px">
                        <td colspan="5" valign="top">Terms:</td>                                                
                    </tr>
                     <tr height="60px">
                        <td colspan="5" valign="top" align="justify">                
                        1. All payments to be acknowledged by official receipts.<br><br>
                        2. Any disputes on this invoice should be lodged in writing within 7 days of the date hereof.<br><br>		
                        3. Interest will be charged on over due accounts, as provided in the agreement.<br><br>                
                        </td>                                                
                    </tr>
                </table>';                
        //echo $tenancyrefcode."..........".$leasename."<br>";
		$pdf->AddPage();	
                $pdf->ln(7);
                $pdf->SetFont('dejavusans','B',22);
                $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>'.$building_company_name.'.</th></tr></table><hr>', true, false, true, false, '');
                $pdf->ln(0.1);
                $pdf->SetFont('dejavusans','',9);
                $pdf->writeHTML($address, true, false, true, false, '');
                $pdf->ln(10);
                $pdf->SetFont('dejavusans','',9.5);
                $pinno ="PIN NO: ";
                $vatno="VAT NO: ";
                $sqlpin = "select pin,vatno from mas_company where companymasid=$building_companymasid;";
                $resultpin = mysql_query($sqlpin);
                if($resultpin !=null)
                {
                    while($row = mysql_fetch_assoc($resultpin))
                    {
                        $pinno .=$row['pin'];
                        $vatno .=$row['vatno'];
                    }
                }
                if($building_companymasid =='3')
                {
                    
                    $pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');
                    $pdf->ln(2);		    
                }
                else
                {
                    $pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');
                    $pdf->ln(2);	
                    $pdf->writeHTML('<b>'.$vatno.'</b>', true, false, true, false, '');
                    $pdf->ln(2);	
                }
                $pdf->SetFont('dejavusans','BU',15);
                $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>INVOICE</th></tr></table>', true, false, true, false, '');	
                $pdf->ln(8);
                $pdf->SetFont('dejavusans','',9.5);
                $pdf->writeHTML($tenantdetails, true, false, true, false, '');
                $pdf->ln(5);
                $pdf->writeHTML($premisesdetails, true, false, true, false, '');
                $pdf->ln(5);
                $pdf->writeHTML($rentdetails, true, false, true, false, '');
                $pdf->ln(5);
		$pdf->MultiCell(0, 0, "REMARKS IF ANY:"."\n".$remarks."\n", 1, 'J', 0, 0, '' ,'', true);
                $pdf->ln(20);    
                $pdf->writeHTML($bottom, true, false, true, false, '');
                
                $pdf->SetXY(10, 270);
                $pdf->writeHTML("<hr>", true, false, true, false, '');
                $companypinno="";
                $companyvatno="";
                if($building_companymasid =='1')//shiloah
                {
                        $pdf->SetXY(15, 272);
                        $pdf->Image('../images/mp_logo.jpg', '', '', 14, 14, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                        $pdf->SetXY(95, 272);
                        $pdf->Image('../images/mc_logo.jpg', '', '', 14, 14, '', '', '', false, 200, '', false, false, 0, false, false, false);
                        $pdf->SetXY(180, 272);
                        $pdf->Image('../images/mm_logo.jpg', '', '', 14, 14, '', '', '', false, 300, '', false, false, 0, false, false, false);
                        
                }	
        ////insert data into database while posting to Tally///
                $createdby = $_SESSION['myusername'];
                $createddatetime = $datetime;
                $taxinvoiceno =mysql_real_escape_string($taxinvoiceno);
                $datefrom =date("Y-m-d", strtotime(date("Y-m-d", strtotime($d1)) . " +  0 Months"));
                $dateto =date("Y-m-d", strtotime(date("Y-m-d", strtotime($d2)) . " +  0 Months"));
		$chk = "select grouptenantmasid,fromdate,todate,rent,sc from invoice where
				grouptenantmasid ='$grouptenantmasid' and
				fromdate='$datefrom' and todate='$dateto' and
				rent='$rent' and sc='$sc';";
				
				
		$resultchk = mysql_query($chk);
		$rcountchk=0;
		if($resultchk)
		{
		    $rcountchk = mysql_num_rows($resultchk);
		    if ($rcountchk<=0)
		    {
			$insert = "insert into invoice (grouptenantmasid,invoiceno,fromdate,todate,rent,rent_vat,sc,sc_vat,createdby,createddatetime) values
                        ($grouptenantmasid,'$taxinvoiceno','$datefrom','$dateto',$rent,$rentvat,$sc,$scvat,'$createdby','$createddatetime')";
			mysql_query($insert);		
			
			
      /* Actual code for importing to Tally goes here */
	  $coname=strtoupper(ucwords($_SESSION["mycompany"]));
	//$narration="";
	$debitac=strtoupper($tenancyrefcode);
	$rent=$rent;
        $sc=$sc;
        $scvat=$scvat;
        $totalvat=$scvat+$rentvat;
        $totalrent=$rent+$sc+$totalvat;

$shopno=rtrim($shop,",");
$dateno = date('Y-m-d');
$datenow = str_replace('-', '', $dateno);
$dates=str_replace('-', 'at', date("j M Y - h:i", strtotime($dateno)));

     
  $rentXML = '<?xml version="1.0"?>
 <ENVELOPE>
 <HEADER>
 <TALLYREQUEST>Import Data</TALLYREQUEST>
 </HEADER>
 <BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>Vouchers</REPORTNAME>
    <STATICVARIABLES>
     <SVCURRENTCOMPANY>'.$coname.'</SVCURRENTCOMPANY>
    </STATICVARIABLES>
   </REQUESTDESC>
   <REQUESTDATA>
    <TALLYMESSAGE xmlns:UDF="TallyUDF">
     <VOUCHER VCHTYPE="Sales" ACTION="Create" OBJVIEW="Accounting Voucher View">
      <DATE>'.$datenow.'</DATE>
      <STATENAME/>
      <NARRATION>Invoice # '.$taxinvoiceno.' Rent Period '.$rentperiod.' for '.$buildingname.' </NARRATION>
      <VOUCHERTYPENAME>Sales</VOUCHERTYPENAME>
      <REFERENCE>'.$taxinvoiceno.'</REFERENCE>
      <VOUCHERNUMBER>'.$taxinvoiceno.'</VOUCHERNUMBER>
      <BASICBASEPARTYNAME>'.$debitac.'</BASICBASEPARTYNAME>
      <CSTFORMISSUETYPE/>
      <CSTFORMRECVTYPE/>
      <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
      <BASICBUYERNAME>'.$debitac.'</BASICBUYERNAME>
      <BASICDATETIMEOFINVOICE>'.$dates.'</BASICDATETIMEOFINVOICE>
      <BASICDATETIMEOFREMOVAL>'.$dates.'</BASICDATETIMEOFREMOVAL>
      <VCHGSTCLASS/>
      <DIFFACTUALQTY>No</DIFFACTUALQTY>
      <ASORIGINAL>No</ASORIGINAL>
      <FORJOBCOSTING>No</FORJOBCOSTING>
      <ISOPTIONAL>No</ISOPTIONAL>
      <EFFECTIVEDATE>'.$datenow.'</EFFECTIVEDATE>
      <USEFOREXCISE>No</USEFOREXCISE>
      <USEFORINTEREST>No</USEFORINTEREST>
      <USEFORGAINLOSS>No</USEFORGAINLOSS>
      <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
      <USEFORCOMPOUND>No</USEFORCOMPOUND>
      <USEFORSERVICETAX>No</USEFORSERVICETAX>
      <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
      <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
      <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
      <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
      <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
      <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
      <ISCANCELLED>No</ISCANCELLED>
      <HASCASHFLOW>No</HASCASHFLOW>
      <ISPOSTDATED>No</ISPOSTDATED>
      <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
      <ISINVOICE>No</ISINVOICE>
      <MFGJOURNAL>No</MFGJOURNAL>
      <HASDISCOUNTS>No</HASDISCOUNTS>
      <ASPAYSLIP>No</ASPAYSLIP>
      <ISCOSTCENTRE>No</ISCOSTCENTRE>
      <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
      <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
      <ISVOID>No</ISVOID>
      <ISONHOLD>No</ISONHOLD>
      <ORDERLINESTATUS>No</ORDERLINESTATUS>
      <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
      <ISDELETED>No</ISDELETED>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>
      <SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
      <INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>
      <INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>
      <INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>
      <ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>
      <ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>
      <INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>
     
      <ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>'.$debitac.'</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>-'. $totalrent.'</AMOUNT>
       <VATEXPAMOUNT>-'.$totalrent.'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>
        <NAME>'.$taxinvoiceno.'</NAME>
        <BILLTYPE>New Ref</BILLTYPE>
        <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
        <AMOUNT>-'.$totalrent.'</AMOUNT>
        <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
        <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      </ALLLEDGERENTRIES.LIST>
      
      <ALLLEDGERENTRIES.LIST>
       <LEDGERNAME> Rental Income</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>'.$rent.'</AMOUNT>
       <VATEXPAMOUNT>'.$rent.'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      </ALLLEDGERENTRIES.LIST>
      
      <ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>Vat on Sales</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>'.$totalvat.'</AMOUNT>
       <VATEXPAMOUNT>'.$totalvat.'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      </ALLLEDGERENTRIES.LIST>
      
       <ALLLEDGERENTRIES.LIST>
       <LEDGERNAME>Service Charge Deposit-'.$shortname.'</LEDGERNAME>
       <VOUCHERFBTCATEGORY/>
       <GSTCLASS/>
       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
       <LEDGERFROMITEM>No</LEDGERFROMITEM>
       <ISPARTYLEDGER>No</ISPARTYLEDGER>
       <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
       <AMOUNT>'.$sc.'</AMOUNT>
       <VATEXPAMOUNT>'.$sc.'</VATEXPAMOUNT>
       <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
       <CATEGORYALLOCATIONS.LIST>       </CATEGORYALLOCATIONS.LIST>
       <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
       <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
       <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
       <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
       <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
       <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
       <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
       <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
       <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
       <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
       <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
       <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
       <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
       <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
       <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
       <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
       <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
       <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
       <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
       <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      </ALLLEDGERENTRIES.LIST>
      
      <VCHLEDTOTALTREE.LIST>      </VCHLEDTOTALTREE.LIST>
      <PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
      <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
     </VOUCHER>
    </TALLYMESSAGE>
   </REQUESTDATA>
  </IMPORTDATA>
 </BODY>
</ENVELOPE>';
  

	//echo $coname;
	//echo '\n';
	//echo $debitac;
	//echo '\n';
			
      /*Actual code for importing to Tally goes here*/
 
/* 	 	$server = 'localhost:9000';
		$headers = array( "Content-type: text/xml" ,"Content-length: ".strlen($rentXML) ,"Connection: close" );

		$nodes = array($server, $server);
        $node_count = count($nodes);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $server);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $rentXML);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$response_1 = curl_exec($ch);
// Close request to clear up some resources
        curl_close($ch); */

		// build the multi-curl handle, adding both $ch
  //$mh = curl_multi_init();
  //curl_multi_add_handle($mh, $ch);

  // execute all queries simultaneously, and continue when all are complete
/*   $running = null;
   do {
   curl_multi_exec($mh, $running);
  } while ($running);
    */
//     $active = null;
//execute the handles
/* do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
} */
  
   
  // all of our requests are done, we can now access the results
  //$response_1 = curl_multi_getcontent($ch);


		

//echo "TXN: $response_1 <br>................................................................</br>";

			/////set invoice no
			setinvno($building_companymasid,$invno,$datetime);
		}
		else
		{
		echo "Invoice Details Already Exists !!! Please check the building and period.";
		exit;
		}
		}                		
	    $table .="</tr>";
	    ////$invno = $invno+1;
	   }
}
$filename = preg_replace('/[^A-Za-z0-9() -]/', '', $filename);
$pdf->Output("../../pms_docs/invoices/".$filename.".pdf","F");
if($building_companymasid !=3)
$pdf->Output($filename.".pdf","F");
$pdf->Output($filename, 'I');    

//echo $table;
$rvgtot = $rentgtotal+$rentvatgtotal;
$scvgtot = $scgtotal+$scvatgtotal;
    //$custom = array('divContent'=>$table,'s'=>'Success');
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
}
?>