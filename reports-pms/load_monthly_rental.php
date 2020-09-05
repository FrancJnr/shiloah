<?php
include('../config.php');
session_start();
try{
$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;$mnthlyrent=0;
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$invoicedt = explode(" ",$_GET['invdt']);
$dtformat = "01-".$invoicedt[0]."-".$invoicedt[1];

$dtformat = date("Y-m-d", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Day"));
$fortheperiodof = date("d-m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Day"));

$buildingname = "";$rect_date="";$orig_cycle="";

$s = "select buildingname from mas_building where buildingmasid =$buildingmasid";
$r = mysql_query($s);
 while($ro = mysql_fetch_assoc($r))
    {
        $buildingname = strtoupper($ro["buildingname"]);
    }
$sqrftTotal=0;
$table ="<p class='printable'><table><thead>";
$table .="<tr align='center'>";
$table .="<th><strong>Index</th>";
$table .="<th><strong>Shop Code</th>";
$table .="<th><strong>Sq.Ft</th>";
$table .="<th><strong>Tenant</th>";
$table .="<th><strong>Rent Cycle</th>";
$table .="<th><strong>Period Frm</th>";
$table .="<th><strong>Period To</th>";
$table .="<th><strong>Rent</th>";
$table .="<th><strong>VAT 14%</th>";
$table .="<th><strong>Rent + VAT</th>";
$table .="<th><strong>Scr Chrg</th>";
$table .="<th><strong>VAT 14%</th>";
$table .="<th><strong>ScrChrg + VAT</th>";
$table .="<th><strong>Monthly Rent</td></tr></thead>";
$i=0;
$rentgtotal=0;$scgtotal=0;$today=0;
$rentvatgtotal=0;$scvatgtotal=0;
$rentandvatgtotal=0;$scandvatgtotal=0;
$grandtotal=0;$totsize="";$expdt=0;$doc=0;

$sql = "select a.grouptenantmasid ,b.leasename from group_tenant_mas a 
	inner join mas_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' not in 
	    (
		select a1.grouptenantmasid from trans_tenant_discharge_op a1
                inner join  trans_tenant_discharge_ac b1 on b1.grouptenantmasid = a1.grouptenantmasid
                where a1.opapproval ='1' and b1.acapproval='1'
	    ) 
	union
	select a.grouptenantmasid ,b.leasename from group_tenant_mas a 
	inner join rec_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' not in 
	    (
		select a2.grouptenantmasid from trans_tenant_discharge_op a2
                inner join  trans_tenant_discharge_ac b2 on b2.grouptenantmasid = a2.grouptenantmasid
                where a2.opapproval ='1' and b2.acapproval='1'
	    ) order by leasename;";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
        $cnt=0;$tmas="";
	$grouptenantmasid = $row['grouptenantmasid'];
	
	$shop="";$size="";$leasename="";$rentcycle="";$rct="0";$monthdiff=0;$period="";$renewal=0;
	$prdfrom="";$prdto="";
	$rent=0;$sc=0;
	$rentvat=0;$scvat=0;
	$rentandvat=0;$scandvat=0;
	$rowtotal="";
	
	$sql1 = "select a.tenantmasid from group_tenant_det a  
		 inner join mas_tenant b on b.tenantmasid = a.tenantmasid
		 where a.grouptenantmasid = $grouptenantmasid  and b.active='1'";
		 
	$result1=mysql_query($sql1);
	if($result1 !=null)
	{
	    $rcount = mysql_num_rows($result1);
	    if($rcount ==0) 
	    {
		$sql1 = "select a.tenantmasid from group_tenant_det a  
			    inner join rec_tenant b on b.tenantmasid = a.tenantmasid
			    where a.grouptenantmasid = $grouptenantmasid  and b.active='1'";
	    }
	}
	
	$result1 = mysql_query($sql1);
	$cnt = mysql_num_rows($result1);
	$sql2="";
	while($row1 = mysql_fetch_assoc($result1))
	{	
	    $cnt1=0;
	    $d1=0;$m1=0;$d2=0;$m2=0; $ex="";
	    $tenantmasid = $row1['tenantmasid'];
	    $sql2="select b.shopcode ,b.size,a.leasename,a.tradingname,c.age as 'rentcycle',a.renewal from mas_tenant a
		    inner join mas_shop b on b.shopmasid = a.shopmasid
		    inner join mas_age c on c.agemasid = a.agemasidrc
		    where a.tenantmasid =$tenantmasid and a.active ='1';";
		    
		    $result2=mysql_query($sql2);
		    if($result2 !=null)
		    {
			$rcount1 = mysql_num_rows($result2);
			if($rcount1 ==0) 
			{
			     $sql2="select b.shopcode ,b.size,a.leasename,a.tradingname,c.age as 'rentcycle',a.renewal from rec_tenant a
				    inner join mas_shop b on b.shopmasid = a.shopmasid
				    inner join mas_age c on c.agemasid = a.agemasidrc
				    where a.tenantmasid =$tenantmasid and a.active ='1';";
			    $rct ="1";
			}
                    }
		    
		    $result2 = mysql_query($sql2);
		    while($row2 = mysql_fetch_assoc($result2))
		    {
			$renewal=$row2['renewal'];
			$shop .=$row2['shopcode']."<br>";
			$size .=$row2['size']."<br>";
			$totsize +=$row2['size'];
			
			if($row2['tradingname'] !="")
			$leasename = $row2['tradingname'];
			else
			$leasename = $row2['leasename'];
			$rentcycle = strtolower($row2['rentcycle']);
			$period  = strtolower($row2['rentcycle'])."<br>";
			
			$tmas .=$tenantmasid."<br>";
			$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,	
				DATE_FORMAT('$dtformat','%b-%d') as invperiod,e.age as 'rentcycle',e.age as 'oldcycle',
				round(DATEDIFF('$dtformat',b.fromdate) / 30) as monthDiff,
				DATE_ADD('$dtformat',INTERVAL 1 MONTH) as 'cdate',
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
				where a.tenantmasid =$tenantmasid 
				and '$dtformat' between b.fromdate and b.todate
				and '$dtformat' between f.fromdate and f.todate
				and c.active ='1'
				and a.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by a.offerlettermasid;";
			        $result3 = mysql_query($sql3);
				if($result3 !=null)
				{
				    $rcount = mysql_num_rows($result3);
				    if($rcount ==0) 
				    {                    
					$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,
						DATE_FORMAT('$dtformat','%b-%d') as invperiod,e.age as 'rentcycle',
						round(DATEDIFF('$dtformat',b.fromdate) / 30) as monthDiff,
						DATE_ADD('$dtformat',INTERVAL 1 MONTH) as 'cdate',
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
						where a.tenantmasid =$tenantmasid 
						and '$dtformat' between b.fromdate and b.todate
						and '$dtformat' between f.fromdate and f.todate
						and c.active ='1' group by a.offerlettermasid;";
					     $result3 = mysql_query($sql3);
					    if($result3 !=null)
					    {
						$rcount = mysql_num_rows($result3);
						if($rcount ==0) 
						{                    
						    $sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,
							    DATE_FORMAT('$dtformat','%b-%d') as invperiod,e.age as 'rentcycle',
							    round(DATEDIFF('$dtformat',b.fromdate) / 30) as monthDiff,
							    DATE_ADD('$dtformat',INTERVAL 1 MONTH) as 'cdate',
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
							    where a.tenantmasid =$tenantmasid 
							    and '$dtformat' between b.fromdate and b.todate
							    and '$dtformat' between f.fromdate and f.todate
							    and c.active ='1'
							    and a.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by a.offerlettermasid;";
							$result3 = mysql_query($sql3);
							if($result3 !=null)
							{
							    $rcount = mysql_num_rows($result3);
							    if($rcount ==0) 
							    {                    
								$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,		
									DATE_FORMAT('$dtformat','%b-%d') as invperiod,e.age as 'rentcycle',
									round(DATEDIFF('$dtformat',b.fromdate) / 30) as monthDiff,
									DATE_ADD('$dtformat',INTERVAL 1 MONTH) as 'cdate',
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
									where a.tenantmasid =$tenantmasid 
									and '$dtformat' between b.fromdate and b.todate
									and '$dtformat' between f.fromdate and f.todate
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
				
				
				if($row3['tradingname'] !="")
				$leasename = $row3['tradingname'];
				else
				$leasename = $row3['leasename'];
				$rentcycle = strtolower($row3['rentcycle']);
				$period  = strtolower($row3['rentcycle'])."<br>";
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
				   $monthdiff = fmod($row3['monthDiff'],0);               
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 1 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
				   $rent +=$row3['rent'];
				   $sc +=$row3['sc'];
				
			       }
			       else if($rentcycle == 'per quarter')
			       {
				   $monthdiff = fmod($row3['monthDiff'],3);               
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 3 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
				   
				   $rent +=$row3['rent']*3;
				    $sc +=$row3['sc']*3;
				
				   
			       }
			       else if($rentcycle == 'per half')
			       {
				   $monthdiff = fmod($row3['monthDiff'],6);                 
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 6 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
				    $rent +=$row3['rent']*6;
				    $sc +=$row3['sc']*6;
			       }
			       else if($rentcycle == 'per year')
			       {
				   $monthdiff = fmod($row3['monthDiff'],12);                 
				   $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
				   $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
				   $d1 = $d1 ."-".$m1;
				   $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 12 Months"));
				   $d2 = date('d-m-Y',strtotime("-1 days $d2"));
				   $rent +=$row3['rent']*12;
				   $sc +=$row3['sc']*12;
			       }
			       $invperiod = $row3['invperiod'];
			    }
		    }
	}
	
	 $i++;
	    
	    if ($cnt > 1)
	    $table .="<tr bgcolor='yellow'>";
	    else 
	    $table .="<tr>";	  	    
	    $today = strtotime($dtformat);
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

	    if(is_nan($monthdiff))
	    $monthdiff =0;	     

	    $table .="<td align='center'>".$i."</td>";
	    $table .="<td>".$shop."</td>";
	    $table .="<td>".$size."</td>";

	    $rid=0;
	    
	    $sql5="select renewalfromid from mas_tenant where tenantmasid = '$tenantmasid' and active='1';";
	    $result5 = mysql_query($sql5);
	    while($row5 = mysql_fetch_assoc($result5))
	    {
		    $rid = $row5['renewalfromid'];
	    }
	    
	    if ($rid == 0)
		    $table .="<td>".$leasename."</td>";
		else
		    $table .="<td>$leasename&nbsp;&nbsp;[<font color='blue'>Renewed</font>]</td>";
		
	    
	    if ($rct == 1)
	    {
		$table .="<td bgcolor='#dbdbdb'>".$period."</td>";
	    }
	    else
	    {
		$table .="<td>".$period."</td>";
	    }
	    
	   
	    
	   if($monthdiff <= 0)
	   {
		$table .="<td>$d1</td>";
		$table .="<td>$d2</td>";
		
		$table .="<td dir=rtl>".number_format($rent, 0, '.', ',')."</td>";
		$rentgtotal +=$rent;
		
		
		$rentvat = $rent*14/100;
		$table .="<td dir=rtl>".number_format($rentvat, 0, '.', ',')."</td>";
		$rentvatgtotal +=$rentvat;
		
		$rentandvat = $rentvat +$rent;
		$table .="<td dir=rtl>".number_format($rentandvat, 0, '.', ',')."</td>";
		
		$table .="<td dir=rtl>".number_format($sc, 0, '.', ',')."</td>";
		$scgtotal +=$sc;
		
		$scvat = $sc*14/100;
		$table .="<td dir=rtl>".number_format($scvat, 0, '.', ',')."</td>";
		$scvatgtotal +=$scvat;
		
		$scandvat = $scvat +$sc;
		$table .="<td dir=rtl>".number_format($scandvat, 0, '.', ',')."</td>";
	    
		
		$rowtotal=$rentandvat+$scandvat;
		$table .="<td dir=rtl>".number_format($rowtotal, 0, '.', ',')."</td>";
		$grandtotal +=$rowtotal;
	   }
	   else
	   {
	     $table .="<td colspan='10'></td>";	
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
    
    $custom = array('divContent'=>$table,'heading'=>$buildingname.' Rental Schedule for the period of '.$fortheperiodof,'s'=>'Success');
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
}
?>