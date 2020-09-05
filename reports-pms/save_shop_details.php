<?php
include('../config.php');
session_start();
try{
    $companymasid = $_SESSION['mycompanymasid'];
    $action = $_GET['item'];
    $rentcycle="";
    
    
if($action == "shopdetails")
{   
    $buildingmasid = $_GET['buildingmasid'];
    $sql = "select  buildingname from mas_building where buildingmasid=$buildingmasid";    
    $result = mysql_query($sql);
    $buildingname="";
    $samTbl="<table id='samtable' name='samtable' class='viewAll'>";
    $j=1;
    if($result != "")
    {
	
	
	$rb = mysql_fetch_assoc($result);
	$buildingname = $rb['buildingname'];
	$buildingsqrfttotal=0;$tbl="";$tblvacant="";	
	$tblexp="<tr><td colspan='10'>WAITING FOR RENEWAL</td></tr>";
	$tblwaitinglist="<tr><td colspan='10'>UNDER FINALAIZATION</td></tr>";
	$va=1;$wa=1;$reg=1;$exp=1;$grouptenantmasid=0;
	$row = mysql_fetch_assoc($result);		
	$months = 0;
	$tbl .="<tr><td colspan='4'>$buildingname &nbsp;&nbsp;-&nbsp;&nbsp;ACTIVE TENANCIES</td>";
	
	for($i=0;$i<=$months;$i++)
	{
	    $d2 = strtoupper(date("M Y", strtotime(date("Y-m", strtotime(date("Y-m"))) . " + $i Months"))); 
	    $tbl .="<th class='rowth' colspan='4' align='center'>$d2 </th>";	    
	}
	$tbl .="<tr><td colspan='4'></td><td colspan='6' align='center'>MONTHLY RENTAL FOR ACTIVE TENANCIES</td></tr>";	    
	$tbl .="<tr>
		<th style='text-align: center;' width='1%'>Sno</th>
		<th>Shop</th>
		<th>Size</th>
		<th>Tenant</th>
		<th>Cycle</th>
		<th>Rent/Mnth</th>
		<th>Rt/Sqrft</th>
		<th>Sc</th>
		<th>Rt/Sqrft</th>
		<th style='text-align: center;' width='5%'>Expiry</th>
		</tr>";
	$totalsqrft = 0;
	$sqltot = "select sum(size) as totalsqrft from mas_shop where buildingmasid='$buildingmasid' and active='1';";
	$resulttot = mysql_query($sqltot);
	if($resulttot != null)
	{
	    $rowtot = mysql_fetch_assoc($resulttot);
	    $totalsqrft =  $rowtot['totalsqrft'];
	}	
	$sql1 = "select @n:=@n+1 sno, a.shopcode,a.size,a.shopmasid from mas_shop a
		,(select @n:= 0) AS n
		where buildingmasid='$buildingmasid' and active='1';";
	
	$result1 = mysql_query($sql1);
	$resultV = mysql_query($sql1);
	
	$tblvacant ="";$n=1;$rTotal=0;
	$doc1 ="";$expdt1="--";
	//regular
	$totsqrft_reg=0;$rent_reg=0;$rt_rent_reg=0;$sc_reg=0;$rt_sc_reg=0;
	//expired and occupied
	$totsqrft_exp=0;$rent_exp =0;$rt_rent_exp =0;$sc_exp =0;$rt_sc_exp =0;
	//under finalaization or waiting list
	$totsqrft_wtg=0;$rent_wtg =0;$rt_rent_wtg =0;$sc_wtg =0;$rt_sc_wtg =0;
	//grand total
	$totsqrft_grnd=0;$rent_grnd =0;$rt_rent_grnd =0;$sc_grnd =0;$rt_sc_grnd =0;
	
	if($result1 != null)
	{
	    
	    while($row1 = mysql_fetch_assoc($result1))
	    {
		$shopmasid = $row1['shopmasid'];
		$size = $row1['size'];				
		$sql4 = "select a.leasename,a.tradingname,a.doc,a.shortdesc,a.tenantmasid
				from (
				select e.shortdesc,c.shopmasid,a.grouptenantmasid,c.tenantmasid,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,c.renewalfromid,d.shopcode,d.size from waiting_list a
					    inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
					    inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
					    inner join mas_shop d on d.shopmasid = c.shopmasid
					    inner join mas_age e on e.agemasid = c.agemasidrc
					    where c.active='1' and c.renewal =0 and c.buildingmasid ='$buildingmasid'
					    union
				select e1.shortdesc,c1.shopmasid,a1.grouptenantmasid,c1.tenantmasid,c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,c1.renewalfromid,d1.shopcode,d1.size from waiting_list a1
					    inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
					    inner join rec_tenant c1 on c1.tenantmasid =  b1.tenantmasid
					    inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
					    inner join mas_age e1 on e1.agemasid = c1.agemasidrc
					    where c1.active='1' and c1.renewal =0  and c1.buildingmasid ='$buildingmasid'
					    group by doc)  as a where a.shopmasid ='".$row1['shopmasid']."';";
			$result4 = mysql_query($sql4);
			if($result4 != null)
			{
			    $rowcnt4 = mysql_num_rows($result4);
			    if($rowcnt4 >0)
			    {				
				//WAITING LIST
				$nt=1;
				while($row4 = mysql_fetch_assoc($result4))
				{
				    
				    //rent for st				    
					$renttd="";$fromdate=date("Y-m-d", strtotime($row4['doc']));
					$tenantmasid = $row4['tenantmasid'];					
					for($i=0;$i<=$months;$i++)// no of months charged
					{
					    $sqlid ='wtg';
					    include "sql_shop_details.php";
					    $rent_wtg +=$rent;$rt_rent_wtg +=$rt_rent;
					    $sc_wtg +=$sc;$rt_sc_wtg +=$rt_sc;
					}
				    //--rent for end
				    if($row4['tradingname'] !="")
					$row4['leasename'] .= " T/A ".$row4['tradingname'];
				    $proposed_tenant = $row4['leasename']." DOC: ".$row4['doc'];
				    
				    $tblwaitinglist .="<tr class='waiting'>";
				    $tblwaitinglist .="<td style='text-align: center;font-weight:bold;' id='waiting".$wa."'>$wa</td>"."<td>".$row1['shopcode']."</td>";
				    if($nt==1)
				    {
					$tblwaitinglist .="<td align='right' id='size_waiting".$wa."'>".number_format($size, 0, '.', ',')."</td>";
					$totsqrft_wtg +=$size;
				    }
				    else
				    {
					$tblwaitinglist .="<td align='right'>-</td>";
				    }
				    $tblwaitinglist .="<td>".$proposed_tenant."</td>";
				    $tblwaitinglist .="<td style='text-align: right;'>".$row4['shortdesc']."</td>";
				    $tblwaitinglist .=$renttd;
				    $tblwaitinglist .="</tr>";
				    $nt++;
				}
				$wa++;				
			    }			   
			}
		
		//----
		$tenant = "<font color='red' >Vacant</font>";						
		$sql2 = "select a.leasename,a.tradingname,a.shopmasid,a.tenantmasid,a.renewalfromid,a.doc from mas_tenant a
			where a.shopmasid ='$shopmasid' and a.active='1' and shopoccupied ='1'
			union
			select a.leasename,a.tradingname,a.shopmasid,a.tenantmasid,a.renewalfromid,a.doc from rec_tenant a
			where a.shopmasid ='$shopmasid' and a.active='1' and shopoccupied ='1'; ";
		$result2 = mysql_query($sql2);
		if($result2 != null)
		{
		    $it1=0;
		    while($row2 = mysql_fetch_assoc($result2))
		    {
			$it1++;
			$doc = $row2['doc'];
			
			//if($doc > date('Y-m-d'))
			    //continue;
		    
			$tenantmasid = $row2['tenantmasid'];
			$renewalfromid = $row2['renewalfromid'];			
			
			//--
			$fromdate = explode(" ",date('M Y'));
			$sqldt = "select date_format(a.fromdate,'%d') as 'dt',c.doc from trans_offerletter_rent  a 
                                        inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
                                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                                        where  b.tenantmasid=$tenantmasid group by b.offerlettermasid;";
                        $resultdt = mysql_query($sqldt);
                        if($resultdt !=null)
                        {
                            $rcnt = mysql_num_rows($resultdt);
			    if($rcnt <=0)
			    {
				continue;   
			    }
			    while($rowdt = mysql_fetch_assoc($resultdt))
                            {
                                $fromdate = $rowdt['dt'].$fromdate[0]."-".$fromdate[1];
				$doc1 = date('d-m-Y', strtotime($rowdt['doc']));
                            }				
			    $fromdate = date("Y-m-d", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Day"));
			    
			}
			
			$bool = true;
			$sqlexp = "select max(a.todate) as todate from trans_offerletter_rent a
				inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
				inner join mas_tenant c on c.tenantmasid = b.tenantmasid
				where c.tenantmasid ='$tenantmasid';";
			$resultexp = mysql_query($sqlexp);
			if($resultexp != null)
			{
			    $rowexp = mysql_fetch_assoc($resultexp);
			    $today = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datetime)) . " + 0 Months"));
			    $today = strtotime($today);
			    $expirydt  = $rowexp["todate"];
			    $expdt1 = date("d.m.Y", strtotime(date("Y-m-d", strtotime($expirydt)) . " + 0 Months"));
			    $expiry_date  = $rowexp["todate"];
			    $expirydt  = strtotime($expirydt);
			    if ($expirydt < $today) {
			       $bool = false;
			    }
			}
			$renewal ="";
			if($renewalfromid > 0)
			{
			   $renewal = " <font color='blue'> - [RENEWED] ";
			}			
			if($bool == false)
			{
			    $renewal .=" <font color='red'> - (Expired) ";
			}
			
			//rent for st
			    $renttd="";
			    for($i=0;$i<=$months;$i++)// no of months charged
			    {
				$sqlid ='reg';
				include "sql_shop_details.php";
				$rent_reg +=$rent;$rt_rent_reg +=$rt_rent;
				$sc_reg +=$sc;$rt_sc_reg +=$rt_sc;
			    }
			//--rent for end
			
			//--
			$rt=0;
			if($size>0){
			    //$rt = $rent/$size;			    
			    //$rTotal +=$rt;			    
			}
			$rTotal +=$rent;
			
			if($row2["tradingname"] !="")
			{
			    $row2["leasename"] .=" T/A ".$row2["tradingname"];
			}
			
			//--expiry dt st
			
			
			$tenant = $row2['leasename'].$renewal;
			
			if($bool == false) // IF LEASE EXPIRED THEN
			{			    
			    $ren=0;
			    $s1 = "select renewal from mas_tenant where tenantmasid = '$tenantmasid' and tenantmasid not in (select renewal from rec_tenant where tenantmasid = '$tenantmasid') and active='1';";
			    $r1 = mysql_query($s1);
			    if($r1 != null)
			    {
				$rc1 = mysql_num_rows($r1);
				if($rc1 <= 0)
				{
				    $s1 = "select renewal from rec_tenant where tenantmasid = '$tenantmasid' and active='1';";
				}
			    }
			    $r1 = mysql_query($s1);
			    if($r1 != null)
			    {
				$ro1 = mysql_fetch_assoc($r1);
				$ren = $ro1["renewal"];
			    }
			    if($ren >0) // if not lease renewed and expired then
			    {
				$it1=0;
				continue;
			    }
			    else
			    {
				//EXPIRED LEASES
				//rent for st				    
				    $renttd="";$fromdate=date("Y-m-d", strtotime($expiry_date));
				    for($i=0;$i<=$months;$i++)// no of months charged
				    {
					$sqlid ='exp';
					include "sql_shop_details.php";
					$rent_exp +=$rent;$rt_rent_exp +=$rt_rent;
					$sc_exp +=$sc;$rt_sc_exp +=$rt_sc;
				    }
				//--rent for end
				$jkl = "'exp".$exp."'";
				$totsqrft_exp +=$size;
				$tblexp .="<tr class='exp'><td style='text-align: center;font-weight:bold;' id='exp".$exp."'>$exp</td>"."<td>".$row1['shopcode']."</td>"."<td align='right' id='size_exp".$exp."'>".number_format($size, 0, '.', ',')."</td>"."<td>".$tenant." - occupied</td>";
				$tblexp .="<td>".$rentcycle."</td>";
				$tblexp .=$renttd;
				$exp++;
				$it1=0;
				continue;
			        
			    }
			}
			//-expiry dt end
			
			// ACTIVE TENANCIES
			
			$tbl .="<tr class='reg'>";
    			$tbl .="<td style='text-align: center;font-weight:bold;' id='reg".$reg."'>$reg</td>"."<td>".$row1['shopcode']."</td>";
			if($it1==1)
			{
			    $tbl .="<td align='right' id='size_reg".$reg."'>".number_format($size, 0, '.', ',')."</td>";
			    $totsqrft_reg +=$size;
			}
			else
			{
			    $tbl .="<td align='right' id='size_reg".$reg."'>-</td>";    
			}
			$tbl .="<td>".$tenant."</td>";			
			$tbl .="<td align='right'>$rentcycle</td>";    
			$tbl .=$renttd;    
			$tbl .="</tr>";
			$reg++;
			$n++;
		    }
		    
		}
	    }
	    $vacantsqrft=0;$v=1;$rt=0;
	    while($rowV = mysql_fetch_assoc($resultV))
	    {
		$shopmasid = $rowV['shopmasid'];
		$size = $rowV['size'];						
		
		$sql2 = "select a.leasename,a.tradingname,a.shopmasid from mas_tenant a
			where a.shopmasid ='$shopmasid' and a.active='1'and a.shopoccupied='1'
			union
			select a.leasename,a.tradingname,a.shopmasid from rec_tenant a
			where a.shopmasid ='$shopmasid' and a.active='1' and a.shopoccupied='1'; ";
		$result2 = mysql_query($sql2);		
		if($result2 != null)
		{
		    $rowcnt = mysql_num_rows($result2);
		    if($rowcnt <=0)
		    {						
			$proposed_tenant = "VACANT SPACE";			
			$sql4 = "select a.leasename,a.doc
				from (
				select c.shopmasid,a.grouptenantmasid,c.tenantmasid,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,c.renewalfromid,d.shopcode,d.size from waiting_list a
					    inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
					    inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
					    inner join mas_shop d on d.shopmasid = c.shopmasid
					    where c.active='1' and c.renewal =0 and c.buildingmasid ='$buildingmasid'
					    union
				select c1.shopmasid,a1.grouptenantmasid,c1.tenantmasid,c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,c1.renewalfromid,d1.shopcode,d1.size from waiting_list a1
					    inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
					    inner join rec_tenant c1 on c1.tenantmasid =  b1.tenantmasid
					    inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
					    where c1.active='1' and c1.renewal =0  and c1.buildingmasid ='$buildingmasid'
					    group by doc)  as a where a.shopmasid ='".$rowV['shopmasid']."';";
			$result4 = mysql_query($sql4);
			if($result4 != null)
			{
			    $rowcnt4 = mysql_num_rows($result4);
			    if($rowcnt4 >0)
			    {
				$row4 = mysql_fetch_assoc($result4);
				$proposed_tenant = $row4['leasename']." DOC: ".$row4['doc'];
				
				////WAITING LISTS
				//$tblwaitinglist .="<tr class='waiting'>";
				//$tblwaitinglist .="<td style='text-align: center;font-weight:bold;' id='waiting".$wa."'></td>"."<td>".$rowV['shopcode']."</td>"."<td align='right' id='size_waiting".$wa."'>".number_format($size, 0, '.', ',')."</td>"."<td> <font color='red' >".$proposed_tenant."</font></td>";	    
				//$tblwaitinglist .="</tr>";
				//$wa++;
			    }
			    else
			    {
				//VACANT SHOPS
				$tblvacant .="<tr class='vacant'>";
				$tblvacant .="<td style='text-align: center;font-weight:bold;' id='vacant".$va."'></td>"."<td>".$rowV['shopcode']."</td>"."<td align='right' id='size_vacant".$va."'>".number_format($size, 0, '.', ',')."</td>"."<td> <font color='red' >".$proposed_tenant."</font></td>";	    
				$tblvacant .="</tr>";
				$va++;
			    }
			}
			$n++;$v++;
			$vacantsqrft +=$size;
		    }
		}
	    }
	    $occupied = $totalsqrft-$vacantsqrft;
	    if($occupied > 0)
		$rTotal = $rTotal/$occupied;
	    else
		$rTotal=0;
	}	
	$tblhead ="<table id='buildinglistTbl' class='table6' >";
	$tbdetails ="<tr>
		<td align='center' valign='middle' id='buildingname' colspan=5>
			<strong>".$row['buildingname']."</strong>
			Total Sqrft:<strong> ".number_format($totalsqrft, 0, '.', ',')."</strong>
			Occupied :<strong> ".number_format($occupied, 0, '.', ',')."</strong>
			Vacant  :<strong> ".number_format($vacantsqrft, 0, '.', ',')."</strong>
			Avg Rt/Sqrft  :<strong> ".number_format($rTotal,2, '.', ',')."</strong>
		</td>
		</tr>";
    }
    
    $tblvacant .= "<tr><td colspan='2' style='text-align: right;font-weight:bold;'>Sqrft: </td><td style='text-align: right;font-weight:bold;' id='tot_vacant'></td><td></td></tr>";         
    $total_reg = "<tr><td colspan='2' style='text-align: right;font-weight:bold;'>Sqrft: </td>
			<td style='text-align: right;font-weight:bold;'>".number_format($totsqrft_reg,0, '.', ',')."</td>
			<td colspan='2' style='text-align: right;font-weight:bold;'>Amount:</td>
			<td style='text-align: right;'>".number_format($rent_reg,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			<td style='text-align: right;'>".number_format($sc_reg,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			</tr>";
    $total_exp = "<tr><td colspan='2' style='text-align: right;font-weight:bold;'>Sqrft: </td>
			<td style='text-align: right;font-weight:bold;'>".number_format($totsqrft_exp,0, '.', ',')."</td>
			<td colspan='2' style='text-align: right;font-weight:bold;'>Amount:</td>
			<td style='text-align: right;'>".number_format($rent_exp,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			<td style='text-align: right;'>".number_format($sc_exp,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			</tr>";
    $total_wtg = "<tr><td colspan='2' style='text-align: right;font-weight:bold;'>Sqrft: </td>
			<td style='text-align: right;font-weight:bold;'>".number_format($totsqrft_wtg,0, '.', ',')."</td>
			<td colspan='2' style='text-align: right;font-weight:bold;'>Amount:</td>
			<td style='text-align: right;'>".number_format($rent_wtg,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			<td style='text-align: right;'>".number_format($sc_wtg,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			</tr>";
    $totsqrft_grnd = $totsqrft_reg+$totsqrft_exp+$totsqrft_wtg;
    $rent_grnd = $rent_reg+$rent_exp+$rent_wtg;
    $rt_rent_grnd = $rt_rent_reg+$rt_rent_exp+$rt_rent_wtg;
    $sc_grnd =$sc_reg+$sc_exp+$sc_wtg;
    $rt_sc_grnd =$rt_sc_reg+$rt_sc_exp+$rt_sc_wtg;
    $totalSqrft_grnd = "<tr><td colspan='2' style='text-align: right;font-weight:bold;'>Total Sqrft: </td>
			<td style='text-align: right;font-weight:bold;'>".number_format($totsqrft_grnd,0, '.', ',')."</td>
			<td colspan='2' style='text-align: right;font-weight:bold;'>Total Amount:</td>
			<td style='text-align: right;'>".number_format($rent_grnd,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			<td style='text-align: right;'>".number_format($sc_grnd,0, '.', ',')."</td><td style='text-align: right;'>-</td>
			</tr>";
    $thv = "<td colspan='4'>VACANT SPACE</td><tr><th>Sno</th><th>Shop</th><th>Size</th><th>Tenant</th></tr>";    
    
    $table = "<p class='printable'>".		
		$tblhead.$tbl.$total_reg.$tblexp.$total_exp.$tblwaitinglist.$total_wtg.$totalSqrft_grnd."</br>".
		$tblhead.$thv.$tblvacant."</br>".						
	    "</p>";
    $custom = array('divContent'=> $table,'totalsqrft'=>$totalsqrft,'s'=>'Success');    
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
else if($action ="buildingdetails")
{
    $buildingmasid="";$i=1;
    $tot1=0;$tot2=0;$tot3=0;$tot4=0;$tbl="";$rt=0;
    
    $sql = "select * from mas_building order by companymasid;";
    $result  = mysql_query($sql);
    if($result != null)
    {
	$tbl ="<p class='printable'><table id='buildinglistTbl' class='table6'>
		<tr><td align='center' valign='middle' id='buildingname' colspan=6><strong>Mega Properties Group Shop Details :</strong>
		&nbsp;ALL<input type=checkbox id='select_all' name='select_all' checked /> &nbsp;&nbsp;";
	$sqlco ="select companymasid,companyname from mas_company where active='1';";
	$resultco = mysql_query($sqlco);
	if($resultco != null)
	{
	    while($rowco = mysql_fetch_assoc($resultco))
	    {
		$companymasid = $rowco['companymasid'];
		$tbl .= $rowco['companyname']." <input type=checkbox id='$companymasid' name='$companymasid' checked /> &nbsp;&nbsp;";
	    }
	}
	$tbl .="</td></tr>
		<tr align='center'><th width='10%'>Sno</th><th width='20%'>Building</th><th class='colhead'>Total Sqrft</th><th class='colhead'>Occupied</th><th class='colhead'>Vacant</th><th class='colhead'>Avg Rt/Sqrft</th>";	    
	while($row = mysql_fetch_assoc($result))
	{
	    $buildingcompanymasid = $row['companymasid'];
	    $buildingmasid = $row['buildingmasid'];
	    $totsqrft=0;$usedsqrft=0;
	    
	    //total sqrft
	    $sql1 = "select sum(size) as totalsqrft from mas_shop where buildingmasid='$buildingmasid' and active='1';";
	    $result1 = mysql_query($sql1);
	    $row1 = mysql_fetch_assoc($result1);
	    $totsqrft = $row1['totalsqrft'];
	    $tot1 +=$totsqrft;
	    
	    //used sqrft
	    $sql2= "select sum(a.size) as usedsqrft from mas_shop a
		    inner join mas_tenant b on b.shopmasid = a.shopmasid
		    where a.buildingmasid='$buildingmasid' and b.active='1' and b.shopoccupied='1' and b.renewal='0'
		    union
		    select sum(a.size) as usedsqrft from mas_shop a
		    inner join rec_tenant b on b.shopmasid = a.shopmasid
		    where a.buildingmasid='$buildingmasid' and b.active='1' and b.shopoccupied='1' and b.renewal='0';";
	    $result2 = mysql_query($sql2);
	    
	    while($row2 = mysql_fetch_assoc($result2))
	    {
		$usedsqrft += $row2['usedsqrft'];
	    }
	    
	    $tot2 +=$usedsqrft;
	    
	    //vacant sqrft
	    $vacantsqrft = $totsqrft - $usedsqrft;
	    $tot3 +=$vacantsqrft;
	    
	    $tbl .= "<tr>";
	    $tbl .= "<td style='text-align: center;font-weight:bold;'>".$i."</td>";	    
	    $tbl .= "<td>".$row['buildingname']."</td>";
	    
	    
	    $tk = 'colvalue'.$buildingcompanymasid;
	    $tbl .= "<td align='right' class='colvalue".$buildingcompanymasid."1'> ".number_format($totsqrft, 0, '.', ',')."</td>";
	    $tbl .= "<td align='right' class='colvalue".$buildingcompanymasid."2'> ".number_format($usedsqrft, 0, '.', ',')."</td>";
	    $tbl .= "<td align='right' class='colvalue".$buildingcompanymasid."3'> ".number_format($vacantsqrft, 0, '.', ',')."</td>";
	    
	    //--
	    $sql1 = "select @n:=@n+1 sno, a.shopcode,a.size,a.shopmasid from mas_shop a
		,(select @n:= 0) AS n
		where buildingmasid='$buildingmasid' and active='1';";	
	    $result1 = mysql_query($sql1);
	    $resultV = mysql_query($sql1);
	    
	    $tblvacant ="";$n=1;$rTotal=0;
	    
	    if($result1 != null)
	    {
		
		while($row1 = mysql_fetch_assoc($result1))
		{
		    $shopmasid = $row1['shopmasid'];
		    $size = $row1['size'];				
		    
		    $tenant = "<font color='red' >Vacant</font>";						
		    $sql2 = "select a.leasename,a.tradingname,a.shopmasid,a.tenantmasid from mas_tenant a
			    where a.shopmasid ='$shopmasid' and a.active='1' and a.shopoccupied='1' and a.renewal='0'
			    union
			    select a.leasename,a.tradingname,a.shopmasid,a.tenantmasid from rec_tenant a
			    where a.shopmasid ='$shopmasid' and a.active='1' and a.shopoccupied='1' and a.renewal='0'; ";
		    $result2 = mysql_query($sql2);
		    if($result2 != null)
		    {
			while($row2 = mysql_fetch_assoc($result2))
			{
			    $tenantmasid = $row2['tenantmasid'];
			    //--
			    $fromdate = explode(" ",date('M Y'));
			    $sqldt = "select date_format(a.fromdate,'%d') as 'dt' from trans_offerletter_rent  a 
					    inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
					    inner join mas_tenant c on c.tenantmasid = b.tenantmasid
					    where  b.tenantmasid=$tenantmasid group by b.offerlettermasid;";
			    $resultdt = mysql_query($sqldt);
			    if($resultdt !=null)
			    {
				$rcnt = mysql_num_rows($resultdt);
				if($rcnt <=0)
				{
				    continue;   
				}
				while($rowdt = mysql_fetch_assoc($resultdt))
				{
				    $fromdate = $rowdt['dt'].$fromdate[0]."-".$fromdate[1];
				}				
				$fromdate = date("Y-m-d", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Day"));
			    }
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
				$rent =0;$sc=0;
				while($row3 = mysql_fetch_assoc($result3))
				{
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
				    $rent +=$row3['rent'];
				    $sc +=$row3['sc'];
				}
			    //--
			    $rt=0;
			    if($size>0){
				$rt = $rent/$size;			    
				$rTotal +=$rt;			    
			    }
			    $rTotal +=$rent;
			    if($row2['tradingname'] !="")
				$tenant = $row2['leasename']." T/A ".$row2['tradingname'];
			    else
			    {			  
				$tenant = $row2['leasename'];
			    }			
			    $n++;		
			}		   
		    }
		}
		if($usedsqrft >0)
		    $rTotal = $rTotal/$usedsqrft;
		else
		    $rTotal =0;
	    }
	    //--
	    
	    $tbl .= "<td align='right' class='colvalue".$buildingcompanymasid."3'> ".number_format($rTotal, 2, '.', ',')."</td>";
	    //$tbl .= "<td align='right' class='colvalue".$buildingcompanymasid."3'>$sql3</td>";
	    $tbl .= "</tr>";
	    $i++;
	}
	
	$tbl .= "<tr style='font-weight:bold;'>";
	$tbl .= "<td colspan='2'  style='text-align: right;'> Total Sqrft: </td>";
	$tbl .= "<td align='right' id='tot1'>".number_format($tot1, 0, '.', ',')."</td>";
	$tbl .= "<td align='right' id='tot2'>".number_format($tot2, 0, '.', ',')."</td>";
	$tbl .= "<td align='right' id='tot3'>".number_format($tot3, 0, '.', ',')."</td>";
	$tbl .= "<td align='right' id='tot4'>".number_format($tot4, 2, '.', ',')."</td>";
	$tbl .= "</tr>";
    }
    $custom = array('divContent'=> $tbl,'s'=>'Success');    
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    
}//end if

}
catch (Exception $err)
{
    
    $custom = array(
		'divContent'=> "</br>Error: ".$err->getMessage().", Line No:".$err->getLine(),
		's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>