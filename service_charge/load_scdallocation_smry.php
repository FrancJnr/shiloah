<?php
include('../config.php');
set_time_limit(-1);
session_start();

try
{
	 ob_start();
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];

if($_GET['fromdt'] == $_GET['todt'])
$firstdate = $_GET['fromdt'];
else
$firstdate = $_GET['fromdt'].' to '.$_GET['todt'];

$fromdt = $_GET['fromdt'];
$fromdt = explode(" ",$fromdt);
$fromdt = $fromdt[1] . '-' . $fromdt[0] . '-01';
$fromdt = date('Y-m-d', strtotime($fromdt));

$todt = $_GET['todt'];
$todt = explode(" ",$todt);
$todt = $todt[1] . '-' . $todt[0] . '-01';
$todt = date('Y-m-d', strtotime($todt));

// date diff
$diff = abs(strtotime($todt) - strtotime($fromdt));
$years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

// no of months between dates
$date1 = strtotime($fromdt);
$date2 = strtotime($todt);

if($date1 == $date2)
    $months = 0;
else
    $months = 1;

while (strtotime('+1 MONTH', $date1) < $date2) {    
    $date1 = strtotime('+1 MONTH', $date1);
    $months++;
}


$d1 = date('Y-m', strtotime($fromdt));
$d2 = $d1;

$buildingname = "";
$companyname="";
$s = "select a.buildingname,b.companyname from mas_building a
    inner join mas_company b on b.companymasid = a.companymasid
    where a.buildingmasid ='$buildingmasid';";
$r = mysql_query($s);
while($ro = mysql_fetch_assoc($r)){
    $buildingname = strtoupper($ro["buildingname"]);
    $companyname = strtoupper($ro["companyname"]);
}
    
$table ="<p class='printable'><table class='table6'>";
$table .="<tr><th colspan='18'><b>$companyname</th></tr>";
$table .="<tr><td colspan='18'><b>SCD ALLOCATION : ".strtoupper(date('M Y', strtotime($_GET['fromdt']))." - ".date('M Y', strtotime($_GET['todt'])))."</b></td></tr>";
$table .="<tr><td colspan='18'><b>$buildingname</td></tr>";
$table .="<tr>";
$table .="<th>S.NO</th>";
$table .="<th>Ref.Id</th>";
$table .="<th>Tenant</th>";
$table .="<th>Code</th>";
$table .="<th>Shop Code</th>";
$table .="<th>Sqrft</th>";
$table .="<th>Mnths.Chrgd</th>";
for($i=0;$i<=$months;$i++)
{
    $d2 = strtoupper(date("M", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"))); 
    $table .="<th class='rowth' align='center'>$d2 Scd</th>";    
}
$table .="<th>Total</th>";
$dtm1 = date('Y-m', strtotime($fromdt));
$dtm2 = date('Y-m', strtotime($todt));

$sqlm=" select TIMESTAMPDIFF(MONTH, rk.olfd, rk.oltd)+1  as monthsdiff from (
            select min(ol.fd) as olfd,max(ol.td) as oltd from (
                select max(date_format(e.fromdate,'%Y-%m-%d')) as fd,max(date_format(e.todate,'%Y-%m-%d')) as td
                        from group_tenant_mas a                                        
                        inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                        inner join mas_building d on d.buildingmasid = c.buildingmasid
                        inner join invoice e on e.grouptenantmasid = a.grouptenantmasid
                        inner join mas_shop f on f.shopmasid =c.shopmasid
                        where c.buildingmasid ='$buildingmasid'  and e.sc>0 and
                        date_format(e.createddatetime,'%Y-%m') between '$dtm1' and '$dtm2'
                union
                select max(date_format(e.fromdate,'%Y-%m-%d')) as fromdate, max(date_format(e.todate,'%Y-%m-%d')) as todate
                        from group_tenant_mas a                                        
                        inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                        inner join mas_building d on d.buildingmasid = c.buildingmasid
                        inner join advance_rent e on e.grouptenantmasid = a.grouptenantmasid
                        inner join mas_shop f on f.shopmasid =c.shopmasid
                        where c.buildingmasid ='$buildingmasid'  and e.sc>0 and
                        date_format(e.createddatetime,'%Y-%m') between '$dtm1' and '$dtm2'
            ) ol
        ) rk;";
$monthsdiff=0;
$resultm=mysql_query($sqlm);
if($resultm !=null)
{    
    $rowm = mysql_fetch_assoc($resultm);
    $monthsdiff = $rowm['monthsdiff'];
}

//for($i=0;$i<$monthsdiff;$i++)
//{
//    $d2 = date("M-Y", strtotime(date("Y-m", strtotime($d1)) . " + $i Months")); 
//    $table .="<th class='rowth'>$d2</th>";    
//}

$table .="</tr>";

$n =1;$cnt=0;$invno="";$md="";$invsc="";$grandlinetotal=0;
    $grandtsqrft=0;$grandmnths=0;$grandinvtotal=0;$rowtotal1[0]=0;$rowtotal2[0]=0;$rowtotal3[0]=0;$tk="";
    $sql1 = "select c.leasename,c.tradingname, a.grouptenantmasid,d.size,d.shopcode,c.tenantmasid
                from advance_rent a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_age f on f.agemasid = c.agemasidrc 
                where d.buildingmasid='$buildingmasid' 
            UNION
            select c1.leasename,c1.tradingname, a1.grouptenantmasid,d1.size,d1.shopcode,c1.tenantmasid
                from invoice a1
                inner join group_tenant_mas b1 on b1.grouptenantmasid = a1.grouptenantmasid
                inner join mas_tenant c1 on c1.tenantmasid = b1.tenantmasid
                inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
                inner join mas_building e1 on e1.buildingmasid = d1.buildingmasid
                inner join mas_age f1 on f1.agemasid = c1.agemasidrc
                where d1.buildingmasid='$buildingmasid' 
            order by leasename ;";
    $result1 = mysql_query($sql1);
    if($result1 !=null)
    {        
        while($row1 = mysql_fetch_assoc($result1)) // no of tenants available for the building
        {
            //$row1 = mysql_fetch_assoc($result1);
             $mnthschrgd1=0;$mnthschrgd=0;$td1="";$td2="";$td="";$size="";$totalsqrft=0;$sc=0;$linetotal=0;$s=0;
            $grouptenantmasid=$row1['grouptenantmasid'];
            ////$grouptenantmasid='252';
            //SQRFT for grouptenantmasid
            $size=0;
            $sqlsize ="select sum(c.size) as size from group_tenant_det a
                        inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
                        inner join mas_shop c on  c.shopmasid = b.shopmasid
                        where  a.grouptenantmasid = '$grouptenantmasid'
                        union
                        select sum(c1.size) as size from group_tenant_det a1
                        inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
                        inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
                        where  a1.grouptenantmasid = '$grouptenantmasid';";
            $rs= mysql_query($sqlsize);
            $ro = mysql_fetch_assoc($rs);
            $size = $ro['size'];
            
            
            $tenantmasid = $row1['tenantmasid'];            
            $sqltenant = "select leasename,tradingname,tenancyrefcode from mas_tenant a
                            left join mas_tenancyrefcode b on b.tenantmasid  =a.tenantmasid
                            where a.tenantmasid ='$tenantmasid'                                
                            union
                            select leasename,tradingname,tenancyrefcode from rec_tenant a
                            left join mas_tenancyrefcode b on b.tenantmasid  =a.tenantmasid
                            where a.tenantmasid ='$tenantmasid'";
            $leasename="-";$tenancyrefcode="-";
            $resulttenant = mysql_query($sqltenant);
            if($resulttenant)
            {
                while ($rowt = mysql_fetch_assoc($resulttenant))
                {
                    if($rowt['tradingname'] =="")
                        $leasename = $rowt['leasename'];
                    else
                        $leasename = $rowt['leasename'] ." T/A ".$rowt['tradingname'];                        
                    $tenancyrefcode = $rowt["tenancyrefcode"];
                }                
            }
            $shopcode = $row1['shopcode'];
            $tenantmasid1 = $row1['tenantmasid'];
                
            $g=0;$f=0;
            for($i=0;$i<=$months;$i++)// no of months charged
            {                                            
                $sc=0;                    
                $d2 = date("Y-m", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
                $sql2 ="select a.rent, a.sc,a.fromdate,a.todate,a.grouptenantmasid,c.tenantmasid,c.renewalfromid,
                        date_format(a.fromdate,'%m-%Y') as 'invfrom',
                        date_format(a.todate,'%m-%Y') as 'invto',                               
                        case lower(f.shortdesc)
                                when 'mnthly' then round(a.sc/1)
                                when 'qtrly' then round(a.sc/3)                    
                                when 'half' then round(a.sc/6)
                                when 'yearly' then round(a.sc/12)
                        end as 'mnthlyscold',
                        @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as monthsdiff,
                        round(a.sc/@m) as mnthlyscold,a.sc as mnthlysc,                            
                        a.invoiceno
                        from advance_rent a
                        inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                        inner join mas_shop d on d.shopmasid = c.shopmasid
                        inner join mas_building e on e.buildingmasid = d.buildingmasid
                        inner join mas_age f on f.agemasid = c.agemasidrc
                        where a.sc>0 and a.grouptenantmasid = '$grouptenantmasid' and date_format(a.fromdate,'%Y-%m') = '$d2'
                        union
                        select a.rent, a.sc,a.fromdate,a.todate,a.grouptenantmasid,c.tenantmasid,c.renewalfromid,
                        date_format(a.fromdate,'%m-%Y') as 'invfrom',
                        date_format(a.todate,'%m-%Y') as 'invto',                               
                        case lower(f.shortdesc)
                                when 'mnthly' then round(a.sc/1)
                                when 'qtrly' then round(a.sc/3)                    
                                when 'half' then round(a.sc/6)
                                when 'yearly' then round(a.sc/12)
                        end as 'mnthlyscold',
                        @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as monthsdiff,
                        round(a.sc/@m) as mnthlyscold,a.sc as mnthlysc,
                        a.invoiceno
                        from invoice a
                        inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                        inner join mas_shop d on d.shopmasid = c.shopmasid
                        inner join mas_building e on e.buildingmasid = d.buildingmasid
                        inner join mas_age f on f.agemasid = c.agemasidrc
                        where a.sc>0 and a.grouptenantmasid = '$grouptenantmasid' and date_format(a.fromdate,'%Y-%m')= '$d2'
                        group by a.grouptenantmasid;";
                $result2 = mysql_query($sql2);                
                if($result2 !=null)
                {
                    $rcount = mysql_num_rows($result2);                    
                    if($rcount ==0)
                    {
                        if($f<=$months)
                        {                            
                            $cls = "row".$s;
                            $sc1=0;
                            $sql3 ="select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, date_format(fromdate,'%d') as fd, round(sc/@s) as mnthlyscprev
                                    from advance_rent
                                    where grouptenantmasid = '$grouptenantmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m')
                                    union
                                    select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, date_format(fromdate,'%d') as fd,round(sc/@s) as mnthlyscprev
                                    from invoice
                                    where grouptenantmasid = '$grouptenantmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m') limit 1;";
                            $result3 = mysql_query($sql3);
                            if($result3 !=null)
                            {
                                $rcount3 = mysql_num_rows($result3);
                                if($rcount3 >0)
                                {                                    
                                    $row3 = mysql_fetch_assoc($result3);
                                    if(($row3['md'] > 1))
                                    {
                                        $sc1 = $row3['mnthlyscprev'];
                                        if ($sc1>0)
                                        $mnthschrgd ++;                                    
                                        $linetotal +=$sc1;    
                                    }
                                    
                                }
                            }                            
                            $td .="<td class='row".$s."' align='right'>".number_format($sc1, 0, '.', ',')."</td>";                            
                            ///$td .="<td class='row".$s."' align='right'>".number_format($mnthschrgd, 0, '.', ',')."</td>";                            
                            //$td .="<td class='row".$s."' align='right'>$sql3</td>";
                            $rowtotal2[$s] = $s;                            
                            $s++;
                        }
                        $f++;
                    }
                    else
                    {                                                
                        while($row2 = mysql_fetch_assoc($result2))
                        {                                                                                                               
                            $tenantmasid = $row2['renewalfromid'];
                            $invno = $row2['invoiceno'];
                            $invsc = $row2['mnthlysc'];
                            $df1 = $row2['fromdate'];
                            $dtt = $row2['invfrom']."--".$row2['invto'];;
                            $md=$row2['monthsdiff'];                             
                            $sc = $row2['mnthlysc'];                            
                            $rcount = mysql_num_rows($result2);
                            $sc = round($sc/$md);                            
                            //if($grouptenantmasid == '252')
                            //$td .="<td class='row".$s."' align='right'>$sc</td>";
                            
                            // split month value
                            for($x=0;$x<$md;$x++)
                            {                                                                                                
                                if($f<=$months)
                                {                                                                       
                                    
                                    $cls = "row".$s;
                                    ////CREDIT NOTE CHECK
                                    $scr=0;$crinvno=0;
                                    $sqlcr ="SELECT value as sc,invoiceno as crinvno FROM shiloahmsk.invoice_cr_det where invoiceno like ('%$invno%') and invoicedescmasid='31';"; // search for service charge deposit
                                    $resultcr = mysql_query($sqlcr);
                                    if($resultcr)
                                    {
                                        while ($rowcr = mysql_fetch_assoc($resultcr))
                                        {
                                            $scr+= $rowcr["sc"];
                                            $crinvno =$rowcr["crinvno"];
                                        }
                                    }                                    
                                    if($scr>0)
                                    {
                                        if($scr>=$sc)
                                            $sc = $scr-$scr;
                                        else if($sc>=$scr)
                                            $sc = $sc-$scr;                                       
                                        //if($sc>0)
                                        //{
                                            $linetotal +=$sc;                                            
                                            $td .="<td class='row".$s."' align='right' style='color:red'>".number_format($sc, 0, '.', ',')."</td>";                                            
                                            //$td .="<td class='row".$s."' align='right' style='color:red'>CR ".number_format($scr, 0, '.', ',')."</td>";
                                            //$td .="<td class='row".$s."' align='right' style='color:red'> CR $crinvno</td>";
                                            //$td .="<td class='row".$s."' align='right'>$mnthschrgd</td>";
                                            $rowtotal2[$s] = $s;                            
                                            $s++;                                            
                                        //}
                                    }
                                    else
                                    {                                        
                                        $mnthschrgd ++;
                                        $linetotal +=$sc;                                    
                                        $td .="<td class='row".$s."' align='right'>".number_format($sc, 0, '.', ',')."</td>";
                                        /////$td .="<td class='row".$s."' align='right'>$mnthschrgd</td>";
                                        $rowtotal2[$s] = $s;                            
                                        $s++;
                                    }                                    
                                }                               
                                $f++;
                            }                            
                            $i +=$md-1;
                        }
                    }
                }
                else
                {
                    $td1 .="<td class='row".$s."' align='right'>null</td>";
                }                
            }            
            if($mnthschrgd >0)
            {
                
                $table .="<tr>";
                $table .="<td style='text-align:left'>".$n++.".</td>";
                $table .="<td>$grouptenantmasid</td>";
                $table .="<td>$leasename</td>";
                $table .="<td>$tenancyrefcode</td>";
                $table .="<td>$shopcode</td>";
                
                //$table .="<td>$leasename</td>";                
                if($size !="")
                $table .="<td align='center'>$size</td>";
                else
                $table .="<td align='center'>".$row1['size']."</td>";
                
                $table .="<td>$mnthschrgd</td>";
                ////$table .="<td>$invno</td>";
                //$table .="<td align='center'>$mnthschrgd</td>";
                //$table .="<td>$dtt</td>";
                ////$table .="<td>".number_format($invsc, 0, '.', ',')."</td>";
                $table .=$td;
                //$table .=$td2;
                $table .="<td><b>".number_format($linetotal, 0, '.', ',')."</b></td>";            
                $table .="</tr>";
                $grandmnths +=$mnthschrgd;
                $grandtsqrft +=$size;
                //$grandmnths +=$md;
                $grandinvtotal +=$invsc;
                $grandlinetotal +=$linetotal;
            }        
        }       
    }
$table .="<tr>";
$table .="<td align='center' colspan='3'><b>GRAND TOTAL:</b></td>";
$table .="<td align='center'><b>".number_format($grandtsqrft, 0, '.', ',')."</b></td>";
$table .="<td align='center'><b>".number_format($grandmnths, 0, '.', ',')."</b></td>";
for($j=0;$j<=$months;$j++)
{
    $tk1 = "tot".$j;
    $tk .="<td id='tot".$j."' align='right'>$tk1</td>";                      
}
$table .=$tk; 
$table .="<td align='right'><b>".number_format($grandlinetotal, 0, '.', ',')."</b></td>";
//$rk = implode($rowtotal2, '-');
//                $sqlExec = explode("-",$rk);
//                for($j=0;$j<=sizeof($sqlExec)-1;$j++)
//                {
//                    if($sqlExec[$j] != "")
//                    {                       
//                       $tk1 = "tot".$j;
//                       $tk .="<td id='tot".$j."' align='right'>$tk1</td>";                      
//                    }
//                }                
//$table .=$tk; 
//$table .="<td align='right'><b>".number_format($grandtotal, 0, '.', ',')."</b></td>";
$table .="</tr>";

$table .="</table></p>";
$custom = array('divContent'=>$table,'s'=>'Success');
$response_array[] = $custom;

echo '{"error":'.json_encode($response_array).'}';
 ob_end_flush();
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