<?php
include('../config.php');
session_start();
try{
$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;$mnthlyrent=0;
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$fromdate = explode(" ",$_GET['invdt']);
$firstdate = $_GET['invdt'];

$sqlrec="";

$buildingname = "";$rect_date="";$orig_cycle="";
$s = "select buildingname,isvat from mas_building where buildingmasid =$buildingmasid";
$r = mysql_query($s);
 while($ro = mysql_fetch_assoc($r))
    {
        $buildingname = strtoupper($ro["buildingname"]);
	$isvat =$ro["isvat"];	
    }
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

$sql = "select a.grouptenantmasid ,b.leasename,date_format(b.doc,'%d-%m-%Y') as doc,e.shoptype,e.shoptypemasid,d.companymasid from group_tenant_mas a 
	inner join mas_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	inner join mas_shoptype e on e.shoptypemasid = b.shoptypemasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' and b.shopoccupied='1' not in 
	    (
		select a1.grouptenantmasid from trans_tenant_discharge_op a1
                inner join  trans_tenant_discharge_ac b1 on b1.grouptenantmasid = a1.grouptenantmasid
                where a1.opapproval ='1' and b1.acapproval='1'
	    )
	union
	select a.grouptenantmasid ,b.leasename,date_format(b.doc,'%d-%m-%Y') as doc,e.shoptype,e.shoptypemasid,d.companymasid from group_tenant_mas a 
	inner join rec_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	inner join mas_shoptype e on e.shoptypemasid = b.shoptypemasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' and b.shopoccupied='1' not in 
	    (
		select a2.grouptenantmasid from trans_tenant_discharge_op a2
                inner join  trans_tenant_discharge_ac b2 on b2.grouptenantmasid = a2.grouptenantmasid
                where a2.opapproval ='1' and b2.acapproval='1'
	    )
	order by shoptypemasid asc,leasename asc;";
$result = mysql_query($sql);
$jk=1;$m1=0;
while($row = mysql_fetch_assoc($result))
{
        $cnt=0;$tmas="";$rm =0;$fv=0;
	$grouptenantmasid = $row['grouptenantmasid'];
	$building_companymasid = $row['companymasid'];
	$fromdate = explode(" ",$_GET['invdt']);
	$advancerent="";
	$shop="";$size="";$leasename="";$tradingname="";$shoptype="";$rentcycle="";$rct="0";$monthdiff=0;$period="";$renewal=0;
	$prdfrom="";$prdto="";
	$rent=0;$sc=0;
	$rentvat=0;$scvat=0;
	$rentandvat=0;$scandvat=0;$sqrft=0;
	$rowtotal="";
	
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
	     
	    $sql2="select b.shopcode ,b.size,a.leasename,a.tradingname,c.age as 'rentcycle',c.shortdesc,a.renewal from mas_tenant a
		    inner join mas_shop b on b.shopmasid = a.shopmasid
		    inner join mas_age c on c.agemasid = a.agemasidrc
		    where a.tenantmasid =$tenantmasid and a.active ='1';";
		    
		    $result2=mysql_query($sql2);
		    if($result2 !=null)
		    {
			$rcount1 = mysql_num_rows($result2);
			if($rcount1 ==0) 
			{
			     $sql2="select b.shopcode ,b.size,a.leasename,a.tradingname,c.age as 'rentcycle',c.shortdesc,a.renewal from rec_tenant a
				    inner join mas_shop b on b.shopmasid = a.shopmasid
				    inner join mas_age c on c.agemasid = a.agemasidrc
				    where a.tenantmasid =$tenantmasid and a.active ='1';";
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
			$renewal=$row2['renewal'];
			$shop .=$row2['shopcode']."<br>";
			$size .=$row2['size']."<br>";			
			$sqrft += $row2['size'];
			
			if($row2['tradingname'] !="")
			$leasename = $row2['tradingname'];
			else
			$leasename = $row2['leasename'];
			$rentcycle = strtolower($row2['rentcycle']);
			$period  = strtolower($row2['shortdesc'])."<br>";
			
			$tmas .=$tenantmasid."<br>";
			//$m1=31;
			$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,	
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
					$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,
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
						    $sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,
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
								$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,		
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
				    $tradingname =" <b>T/A</b> ";
				    $tradingname .= $row3['tradingname'];
				}
				$leasename = $row3['leasename'];
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
				   
			       }
				    for($f=0;$f<$fv;++$f){					
					//chk in advance invoice available---------------
					
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
					//chk in advance invoice available-----------------
				   }
				//   if($advancerent ==true)
				//   {
				//	$rent +=$row3['rent']*$rm;
				//	$sc +=$row3['sc']*$rm;
				//   }
				//   else
				//   {
				//	$rent +=$row3['rent'];
				//	$sc +=$row3['sc'];				   
				//   }
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
	    $sqlrec .=$sql3.$monthdiff;
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
		else{
		   //$table .="<tr bgcolor='red' style='color:white'>";
		   $table .="<tr>";		    
		}   
		if($renewal == '1')
		{		    
		    continue; // if renewd continue
		}
	    } 

	    
	    $table .="<td align='center'>".$i."</td>";
	    $table .="<td>".$shop."</td>";
	    $shoptype = strtolower($shoptype);
	    
	    if(($shoptype=="trolley") || ($shoptype=="kiosk") || ($shoptype=="others"))
	    $size=$shoptype." sq: ".$sqrft;
	    
	    $table .="<td>".$size."</td>";
	    
	    if($i==1)
	    $invno = getinvno($building_companymasid);
	   
	    $table .="<td>".$invno."/".date('Y')."</td>";
		    
	    $rid=0;
	    
	    $sql5="select renewalfromid from mas_tenant where tenantmasid = '$tenantmasid' and active='1';";
	    $result5 = mysql_query($sql5);
	    while($row5 = mysql_fetch_assoc($result5))
	    {
		    $rid = $row5['renewalfromid'];
	    }
	    	
	    
	$leasename = $leasename.$tradingname;
	
	// chek tenant following lease or not
	//$sqlchklease ="select grouptenantmasid from trans_document_status where grouptenantmasid = $grouptenantmasid and leasedate is not null;";
	$sqlchklease ="select grouptenantmasid from rpt_lease where grouptenantmasid = $grouptenantmasid;";
	$resultchklease=mysql_query($sqlchklease);
	if($resultchklease !=null)
	{
	    $rcountchklease = mysql_num_rows($resultchklease);
	    if($rcountchklease >0) 
	    {
		$leasename = "<font color='green'>".$leasename."</font>";
	    }
	    else
	    {
		$leasename = "<font color='blue'>".$leasename."</font>";
	    }
	}
	    
	    
	    if ($rid == 0){
		    $table .="<td>".$leasename."</td>";		    
	    }
	    else{
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
	    
	    $table .="<td>".$d1." <b>to</b> ".$d2."</td>";		
	    
	    
	    $totsize +=$sqrft;
	    $rent = round($rent,0,PHP_ROUND_HALF_EVEN);
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
	    
	    
	    $sc = round($sc,0,PHP_ROUND_HALF_EVEN);
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
	    $invno = $invno+1;
	}	
	$table .="</tr>";
}

$rvgtot = $rentgtotal+$rentvatgtotal;
$scvgtot = $scgtotal+$scvatgtotal;



    $table .= "<tr>
    		<td colspan='2' dir=rtl> <b>Total Let Out Area</b></td>		
    		<td dir=rtl>".number_format($totsize, 0, '.', ',')."</td>
    		<td colspan='4' dir=rtl> <b>Grand Total</b></td>
    		<td dir=rtl>".number_format($rentgtotal, 0, '.', ',')."</td>
    		<td dir=rtl>".number_format($rentvatgtotal, 0, '.', ',')."</td>
    		<td dir=rtl>".number_format($rvgtot, 0, '.', ',')."</td>
    		
    		<td dir=rtl>".number_format($scgtotal, 0, '.', ',')."</td>
    		<td dir=rtl>".number_format($scvatgtotal, 0, '.', ',')."</td>
    		<td dir=rtl>".number_format($scvgtot, 0, '.', ',')."</td>
    		
    		<td dir=rtl>".number_format($grandtotal, 0, '.', ',')."</td>
    	    </tr>";
$table .= "</table></p>";
    
    //$custom = array('divContent'=>$sqlrec,'s'=>'Success');
    $custom = array('divContent'=>$table,'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
catch (Exception $err)
{
    $custom = array(
                'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine().", Line No:".$tsql1,
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>