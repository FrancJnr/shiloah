<?php
include('../config.php');
session_start();
try{
$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;$mnthlyrent=0;
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$companymasid = $_SESSION['mycompanymasid'];
$months = $_GET['months'];

$buildingname = "";$shortname = "";$rect_date="";$orig_cycle="";$leasename="";$table="";
$sqrftTotal=0;


$table ="<p class='printable'><table class='table6'>";
$table .="<tr align='center'>";
$table .="<th>Index</th>";
$table .="<th>Shop</th>";
$table .="<th>Sqft</th>";
$table .="<th>Tenant</th>";
$table .="<th>Cycle</th>";
$table .="<th>From</th>";
$table .="<th>To</th>";
$table .="<th>Rent</th>";
$table .="<th>Vat 14%</th>";
$table .="<th>Rent+Vat</th>";
$table .="<th>Scr Chrg</th>";
$table .="<th>Vat 14%</th>";
$table .="<th>Sc+Vat</th>";
$table .="<th>Monthly Rent></th>";
$i=0;
$rentgtotal=0;$scgtotal=0;$invfromdt=0;
$rentvatgtotal=0;$scvatgtotal=0;
$rentandvatgtotal=0;$scandvatgtotal=0;
$grandtotal=0;$totsize=0;$expdt=0;$doc=0;

$rent=0;$sc=0;
$rentvat=0;$scvat=0;
$rentandvat=0;$scandvat=0;
$rowtotal="";$chksql="";$firstdate="";$tenantcode="";$filename="";

        $cnt=0;$tmas="";$rm =0;$fv=0;	
	$grouptenantmasid = $_GET['grouptenantmasid'];
        
        $shop="";$size="";$leasename="";$rentcycle="";$rct="0";$monthdiff=0;$period="";$renewal=0;
	$prdfrom="";$prdto="";	
	$tenancyrefcode = gettenancyrefcode($grouptenantmasid);
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
	//chk
	$d1 = $_GET['fromdate'];	
	for($v =1; $v <=$months;$v++)// iterate no of months
	{
		$d2 = date("Y m", strtotime(date("Y-m-d", strtotime($d1)) . " +  ".$v." Months" . " -1 Day"));
		
		
		$sqlchk = "select date_format(todate,'%m %Y') as stdt, months,
			   date_format(date_add(date_add(fromdate,interval @t1:=months month),interval -1 day) ,'%m %Y')as enddt 
			   from advance_rent where grouptenantmasid =$grouptenantmasid and date_format(fromdate,'%Y %m') = '$d2';";
		$resultchk=mysql_query($sqlchk);		
		if($resultchk !=null)
		{
			$rcount = mysql_num_rows($resultchk);
			if($rcount > 0) 
			{
					
				//$custom = array('result'=>$sqlchk.$d1.$d2,'s'=>'Success');
				$custom = array('result'=>'<br><h5><center><font color="red">Advance invoice already available for the period(s).</font>','s'=>'Success');
				$response_array[] = $custom;
				echo '{"error":'.json_encode($response_array).'}';
				exit;
			}
		}
		$sqlchk = "select date_format(todate,'%m %Y') as stdt
			    from invoice where grouptenantmasid = $grouptenantmasid and '$d2' between date_format(fromdate,'%Y %m') and  date_format(todate,'%Y %m');";
		$resultchk=mysql_query($sqlchk);		
		if($resultchk !=null)
		{
			$rcount = mysql_num_rows($resultchk);
			if($rcount > 0) 
			{
					
				//$custom = array('result'=>$sqlchk.$d1.$d2,'s'=>'Success');
				$custom = array('result'=>'<br><center><h5><font color="red">Regular invoice already available for the period(s).</font>','s'=>'Success');
				$response_array[] = $custom;
				echo '{"error":'.json_encode($response_array).'}';
				exit;
			}
		}
	}	
	$result1 = mysql_query($sql1);
	$cnt = mysql_num_rows($result1);
	$sql2="";
	while($row1 = mysql_fetch_assoc($result1))
	{	
	    $fromdate = explode(" ",$_GET['fromdate']);
	    $cnt1=0;
	    $d1=0;$m1=0;$d2=0;$m2=0; $ex="";
	    $tenantmasid = $row1['tenantmasid'];
	    $sql2="select b.shopcode ,b.size,a.leasename,a.tradingname,c.age as 'rentcycle',a.renewal,a.tenantcode,a.buildingmasid,e.companymasid,
		    a.poboxno,a.pincode,a.city,d.cpname,d.cpmobile,d.cplandline from mas_tenant a
		    inner join mas_shop b on b.shopmasid = a.shopmasid
		    inner join mas_age c on c.agemasid = a.agemasidrc
		    inner join mas_tenant_cp d on d.tenantmasid = a.tenantmasid
		    inner join mas_building e on e.buildingmasid = a.buildingmasid
		    where a.tenantmasid =$tenantmasid and a.active ='1' and d.documentname='1';";
		    
		$result2=mysql_query($sql2);
		if($result2 !=null)
		{
		    $rcount1 = mysql_num_rows($result2);
		    if($rcount1 ==0) 
		    {
			 $sql2="select b.shopcode ,b.size,a.leasename,a.tradingname,c.age as 'rentcycle',a.renewal,a.tenantcode,a.buildingmasid,e.companymasid,
				a.poboxno,a.pincode,a.city,d.cpname,d.cpmobile,d.cplandline from rec_tenant a
				inner join mas_shop b on b.shopmasid = a.shopmasid
				inner join mas_age c on c.agemasid = a.agemasidrc
				inner join rec_tenant_cp d on d.tenantmasid = a.tenantmasid
				inner join mas_building e on e.buildingmasid = a.buildingmasid
				where a.tenantmasid =$tenantmasid and a.active ='1' and d.documentname='1';";
			$rct ="1";
		    }
		}
		$result2 = mysql_query($sql2);
		    
		$tsql1 = "select date_format(a.fromdate,'%d') as 'dt' from trans_offerletter_rent  a 
				inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
				inner join mas_tenant c on c.tenantmasid = b.tenantmasid
				where b.tenantmasid= $tenantmasid and b.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by b.offerlettermasid;";
		$tresult1 = mysql_query($tsql1);
		if($tresult1 !=null)
		{		     
		    $rcount = mysql_num_rows($tresult1);
		    if($rcount ==0) 
		    {
			$tsql1 = "select date_format(a.fromdate,'%d') as 'dt' from rec_trans_offerletter_rent  a 
				inner join rec_trans_offerletter b on b.offerlettermasid = a.offerlettermasid
				inner join mas_tenant c on c.tenantmasid = b.tenantmasid
				where b.tenantmasid= $tenantmasid group by b.offerlettermasid;";
		    }		    
		}
		$tresult1 = mysql_query($tsql1);
		if($tresult1 !=null)
		{
		    while($trow = mysql_fetch_assoc($tresult1))
		    {
			$fromdate = $trow['dt'].$fromdate[0]."-".$fromdate[1];                                    
		    }
		    $fromdate = date("Y-m-d", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Day"));
		    $fortheperiodof = date("d-m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Day"));     
		}
		$firstdate = $fromdate;
		
	
		while($row2 = mysql_fetch_assoc($result2)) //iterate no of tenant in group
		{
			
			$buildingmasid = $row2['buildingmasid'];
			$building_companymasid = $row2['companymasid'];
			$tenantpoboxno = $row2['poboxno'];
			$tenantpincode = $row2['pincode'];
			$tenantcity  = $row2['city'];
			$tenantcpname = $row2['cpname'];
			$tenantmobile = $row2['cpmobile'];
			$tenantlandline = $row2['cplandline'];
			
			$s = "select buildingname,shortname,isvat from mas_building where buildingmasid =$buildingmasid";
			$r = mysql_query($s);
			while($ro = mysql_fetch_assoc($r))
			    {
			        $buildingname = strtoupper($ro["buildingname"]);
				$shortname = strtoupper($ro["shortname"]);
				$isvat =$ro["isvat"];
			    }
			
			$tenantcode = $row2['tenantcode'];
			$renewal=$row2['renewal'];			
	
			$shop .=$row2['shopcode'].",";
			$size .=$row2['size'].",";
			$totsize +=$row2['size'];
			
			//if($tenantmasid=='15')
			//{			    
			//    $custom = array('result'=>$sql1,'heading'=>'INVOICE NO: 0000 000','s'=>'Success');
			//    $response_array[] = $custom;
			//    echo '{"error":'.json_encode($response_array).'}';
			//    exit;
			//}
			
		    for($jk =1; $jk <=$months;$jk++)// iterate no of months
		    {			
			    $leasename = $row2['leasename'];
			
			//if($row2['tradingname'] !="")
			//$leasename = $row2['tradingname'];
			//else
			//$leasename = $row2['leasename'];
			
			$rentcycle = strtolower($row2['rentcycle']);
			$period  = strtolower($row2['rentcycle'])."<br>";
			                       
			$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,	
				DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.shortdesc,e.age as 'oldcycle',
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
				where a.tenantmasid =$tenantmasid 
				and '$fromdate' between b.fromdate and b.todate
				and '$fromdate' between f.fromdate and f.todate
				and c.active ='1'
				and a.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by a.offerlettermasid;";
			        $result3 = mysql_query($sql3);
				
			        if($result3 !=null){
					
				    $rcount = mysql_num_rows($result3);
				    if($rcount ==0) 
				    {                    
					$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,
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
						    $sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,
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
								$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,		
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
				$tradingname = $row3['tradingname'];
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
				$rent +=$row3['rent'];
				$sc +=$row3['sc'];
				$invperiod = $row3['invperiod'];				
				$fromdate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($firstdate)) . " +  ".$jk." Months"));	
				$invperiod = $row3['invperiod'];			
			    }
		    }// for			
		} // while 2
	}// while 1	
	 $i++;
	    
	    if ($cnt > 1)
	    $table .="<tr bgcolor='yellow'>";
	    else 
	    $table .="<tr>";
	    	    
	    $invfromdt = strtotime($firstdate);
	    $expdt = strtotime($expdt); 	    
	    
	    if ($expdt < $invfromdt) {
		$sql4="select doc from mas_tenant where tenantmasid = '$tenantmasid' and active='1';";
		$result4 = mysql_query($sql4);
		while($row4 = mysql_fetch_assoc($result4))
		{
		    $doc = $row4['doc'];
		}
		$doc = strtotime($doc);
		
		if ($doc > $invfromdt)
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
	    $leasename .= " (".$tenancyrefcode.")";
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
		$date1 = date("d-m-Y", strtotime(date("Y-m-d", strtotime($firstdate)) . " 0 Day"));
		//$date2 = date("d-m-Y", strtotime(date("Y-m-d", strtotime($firstdate)) . " $months Months"));		
		$date2 = date("d-m-Y", strtotime(date("Y-m-d", strtotime($fromdate)) . " -1 Day"));
		
		
		$filename = $leasename."_".$shortname."_(".$period.")";
		
	   //if($monthdiff <= 0)
	   //{		
		$table .="<td>".$date1."</td>";
		$table .="<td>".$date2."</td>";
		
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
	   //}
	   //else
	   //{
	   //  $table .="<td colspan='10'></td>";	
	   //}
	    $table .="</tr>";

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
$table .= "</table>";

	$invno = getinvno($building_companymasid);
	////$invno =$_GET['invno'];
	 $custom = array('result'=>$table,'heading'=>'INVOICE NO: '.$invno,'s'=>'Success');
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
}
catch (Exception $err)
{
    
    $custom = array(
                'result'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>