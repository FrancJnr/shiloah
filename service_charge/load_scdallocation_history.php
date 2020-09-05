<?php
include('../config.php');
session_start();
try
{
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];

//if($_GET['fromdt'] == $_GET['todt'])
//$firstdate = $_GET['fromdt'];
//else
//$firstdate = $_GET['fromdt'].' to '.$_GET['todt'];

$fromdt = $_GET['fromdt'];
$fromdt = explode(" ",$fromdt);
$fromdt = $fromdt[1] . '-' . $fromdt[0] . '-01';
$fromdt = date('Y-m-d', strtotime($fromdt));

//$todt = $_GET['todt'];
//$todt = explode(" ",$todt);
//$todt = $todt[1] . '-' . $todt[0] . '-01';
//$todt = date('Y-m-d', strtotime($todt));
//
//// date diff
//$diff = abs(strtotime($todt) - strtotime($fromdt));
//$years = floor($diff / (365*60*60*24));
//$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
//
//// no of months between dates
//$date1 = strtotime($fromdt);
//$date2 = strtotime($todt);
//
//if($date1 == $date2)
//    $months = 0;
//else
//    $months = 1;
//
//while (strtotime('+1 MONTH', $date1) < $date2) {    
//    $date1 = strtotime('+1 MONTH', $date1);
//    $months++;
//}


$d1 = date('Y-m', strtotime($fromdt));
$d2 = $d1;

$buildingname = "";
$s = "select buildingname from mas_building where buildingmasid =$buildingmasid";
$r = mysql_query($s);
while($ro = mysql_fetch_assoc($r)){
    $buildingname = strtoupper($ro["buildingname"]);
}
    
$table ="<p class='printable'><table class='table6'>";
$table .="<tr><th colspan='18'>Service Charge Deposit Allocation</th></tr>";
$table .="<tr><td colspan='18'><b> $buildingname <br><br> SCD ALLOCATION - ".$_GET['fromdt']."</b></td></tr>";
$table .="<tr>";
$table .="<th>S.No</th>";
$table .="<th>Tenant</th>";
$table .="<th>Shop Code</th>";
$table .="<th>Sqrft</th>";
$table .="<th>Mnths.Chrgd</th>";
$table .="<th>Inv No</th>";
$table .="<th>Inv Sc</th>";
$table .="<th>Prv Sc</th>";

$dtm = date('Y-m', strtotime($d1));

$sqlm=" select TIMESTAMPDIFF(MONTH, rk.olfd, rk.oltd)+1  as monthsdiff from (
            select min(ol.fd) as olfd,max(ol.td) as oltd from (
                select max(date_format(e.fromdate,'%Y-%m-%d')) as fd,max(date_format(e.todate,'%Y-%m-%d')) as td
                        from group_tenant_mas a                                        
                        inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                        inner join mas_building d on d.buildingmasid = c.buildingmasid
                        inner join invoice e on e.grouptenantmasid = a.grouptenantmasid
                        inner join mas_shop f on f.shopmasid =c.shopmasid
                        where c.buildingmasid ='$buildingmasid'  and e.sc>0 and
                        date_format(e.createddatetime,'%Y-%m') = '$dtm'
                union
                select max(date_format(e.fromdate,'%Y-%m-%d')) as fromdate, max(date_format(e.todate,'%Y-%m-%d')) as todate
                        from group_tenant_mas a                                        
                        inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                        inner join mas_building d on d.buildingmasid = c.buildingmasid
                        inner join advance_rent e on e.grouptenantmasid = a.grouptenantmasid
                        inner join mas_shop f on f.shopmasid =c.shopmasid
                        where c.buildingmasid ='$buildingmasid'  and e.sc>0 and
                        date_format(e.createddatetime,'%Y-%m') = '$dtm'
            ) ol
        ) rk;";
$monthsdiff=0;
$resultm=mysql_query($sqlm);
if($resultm !=null)
{
    ////$flpdt2 = date('Y-m-d', strtotime($_GET['flpdt2']));
    $rowm = mysql_fetch_assoc($resultm);
    $monthsdiff = $rowm['monthsdiff'];
}

for($i=0;$i<$monthsdiff;$i++)
{
    $d2 = date("M-Y", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
    $d2s = date("M", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
    $search_array[]=$d2s;
    $table .="<th class='rowth'>$d2</th>";    
}

$table .="</tr>";

$n =1;$cnt=0;$invno="";$md="";$invsc="";
    $grandtsqrft=0;$grandmnths=0;$grandinvtotal=0;$grandinvtotalprv=0;$rowtotal1[0]=0;$rowtotal2[0]=0;$rowtotal3[0]=0;$tk="";
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
            $mnthschrgd=0;$td="";$tdpr="<td></td>";$size="";$totalsqrft=0;$sc=0;$linetotal=0;$s=0;
            $grouptenantmasid=$row1['grouptenantmasid'];
            
            $rid=0; // renewal tenant group masid
            $sg="select grouptenantmasid from group_tenant_det where tenantmasid = (select a.renewalfromid from mas_tenant a
                inner join group_tenant_det b on b.tenantmasid = a.tenantmasid
                where b.grouptenantmasid =$grouptenantmasid); limit 1";
            $rg = mysql_query($sg);
            if($rg != null)
            {
                while($ro = mysql_fetch_assoc($rg))
                {
                    $rid = $ro['grouptenantmasid'];
                }
            }
            if($rid > 0)
            {
                $rid = $rid.",".$grouptenantmasid;
                $grouptenantmasid = $rid;
            }
            //$grouptenantmasid .=",".$rid ;
            
            $tenantmasid = $row1['tenantmasid'];            
            $sqltenant = "select leasename,tradingname,tenancyrefcode from mas_tenant a
                            left join mas_tenancyrefcode b on b.tenantmasid  =a.tenantmasid
                            where a.tenantmasid ='$tenantmasid'                                
                            union
                            select leasename,tradingname,tenancyrefcode from rec_tenant a
                            left join mas_tenancyrefcode b on b.tenantmasid  =a.tenantmasid
                            where a.tenantmasid ='$tenantmasid'";
            $leasename="-";
            $resulttenant = mysql_query($sqltenant);
            if($resulttenant)
            {
                while ($rowt = mysql_fetch_assoc($resulttenant))
                {
                    if($rowt['tradingname'] =="")
                        $leasename = $rowt['leasename']." (".$rowt["tenancyrefcode"].")";
                    else
                        $leasename = $rowt['leasename'] ." T/A ".$rowt['tradingname']." (".$rowt["tenancyrefcode"].")";   
                }                
            }
            $shopcode = $row1['shopcode'];
            $grandtd="";
            
            //for($i=0;$i<$monthsdiff;$i++)// no of months charged
            //{                                            
                $sqlExec = explode(",",$grouptenantmasid);
                $cnt = count($sqlExec);
                $t=1;
                for($x=0;$x<$cnt;$x++)
                {                    
                    
                    if($sqlExec[$x] != "")
                    {
                        $gpmasid = $sqlExec[$x];
                        //SQRFT for grouptenantmasid
                        $size=0;
                        $sqlsize ="select sum(c.size) as size from group_tenant_det a
                                    inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
                                    inner join mas_shop c on  c.shopmasid = b.shopmasid
                                    where  a.grouptenantmasid = '$gpmasid'
                                    union
                                    select sum(c1.size) as size from group_tenant_det a1
                                    inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
                                    inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
                                    where  a1.grouptenantmasid = '$gpmasid';";
                        $rs= mysql_query($sqlsize);
                        $ro = mysql_fetch_assoc($rs);
                        $size = $ro['size'];
                        
                        $d2 = $dtm;
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
                        where a.sc>0 and a.grouptenantmasid = '$gpmasid' and date_format(a.createddatetime,'%Y-%m') = '$d2'
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
                        where a.sc>0 and a.grouptenantmasid = '$gpmasid' and date_format(a.createddatetime,'%Y-%m')= '$d2'
                        group by a.grouptenantmasid;";
                        
                        $result2 = mysql_query($sql2);
                        if($result2 !=null)
                        {
                            $rcount = mysql_num_rows($result2);
                            if($rcount ==0)
                            {
                                $cls = "row".$s;
                                $sc1=0;
                                $sql3 ="select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, round(sc/@s) as mnthlyscprev,invoiceno,sc,fromdate,todate
                                        from advance_rent
                                        where grouptenantmasid = '$gpmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m')
                                        union
                                        select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, round(sc/@s) as mnthlyscprev,invoiceno,sc,fromdate,todate
                                        from invoice
                                        where grouptenantmasid = '$gpmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m') limit 1;";
                                $result3 = mysql_query($sql3);
                                if($result3 !=null)
                                {
                                    $rcount3 = mysql_num_rows($result3);
                                    if($rcount3 >0)
                                    {
                                        $row3 = mysql_fetch_assoc($result3);
                                        $invno = $row3['invoiceno'];
                                        $invsc = $row3['sc'];
                                        $f1 =$row3['fromdate'];
                                        $t1 =$row3['todate'];
                                        $sc = $row3['mnthlyscprev'];
                                       
                                        if ($sc>0)
                                        {                                                                                       
                                            $sql3m ="SELECT DATE_FORMAT(_date, '%Y-%m') AS month,
                                                            COUNT(1) AS days
                                                     FROM (
                                                             SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a) + (1000 * d.a) + (10000 * e.a)) DAY AS _date
                                                             FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
                                                             CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
                                                             CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
                                                             CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS d
                                                             CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS e
                                                          ) a
                                                     WHERE _date BETWEEN '$f1' AND '$t1'
                                                     GROUP BY MONTH(_date)
                                                     ORDER BY MONTH(_date);";
                                           
                                            $result3m = mysql_query($sql3m);
                                            if($result3m !=null)
                                            {
                                                $rcount3m = mysql_num_rows($result3m);
                                                if($rcount3m >0)
                                                {
                                                    $tdpinv=$invsc;
                                                    while($row3m = mysql_fetch_assoc($result3m))
                                                    {
                                                        $m3 = date("M", strtotime(date("Y-m", strtotime($row3m['month']))));
                                                        foreach ($search_array as $k=>$v)
                                                        {
                                                            if($v == $m3)
                                                            {
                                                                ////ENABLE THIS TO SHOW PREVIOUS MONTHS ALLOCATIONS
                                                                ////**********************************************
                                                                $mnthschrgd ++;                                    
                                                                $linetotal +=$sc;                                            
                                                                $td .="<td class='row".$s."' align='right'>".number_format($sc, 0, '.', ',')."</td>";
                                                                ////$td .="<td class='row".$s."' align='right'>**".$m3.$sc."</td>";
                                                                $rowtotal2[$s] = $s;                            
                                                                $s++;
                                                                $tdpinv -=$sc;
                                                            }
                                                        }    
                                                    }
                                                    $invsc=0;
                                                    $tdpr="<td>$tdpinv</td>";
                                                    $grandinvtotalprv +=$tdpinv;
                                                }
                                            }// end of sql3m   
                                        }
                                    }//
                                }                            
                            }
                            else
                            {
                                $k = false;
                                while($row2 = mysql_fetch_assoc($result2))
                                {                                                
                                    $k=true;
                                    if($row2['mnthlysc'] >0 ){
                                        $invno = $row2['invoiceno'];
                                        $invsc = $row2['mnthlysc'];
                                        $df1 = $row2['fromdate'];
                                        $dtt = $row2['invfrom']."--".$row2['invto'];;
                                        $md=$row2['monthsdiff'];                                   
                                        $sc = $row2['mnthlysc'];
                                        
                                        ////CREDIT NOTE CHECK
                                        $scr=0;
                                        $sqlcr ="SELECT value as sc FROM shiloahmsk.invoice_cr_det where invoiceno like ('%$invno%') and invoicedescmasid='31';"; // search for service charge deposit
                                        $resultcr = mysql_query($sqlcr);
                                        if($resultcr)
                                        {
                                            while ($rowcr = mysql_fetch_assoc($resultcr))
                                            {
                                                $scr+= $rowcr["sc"];
                                            }
                                        }
                                        if($scr>0)
                                        {
                                            if($scr>=$sc)
                                                $sc = $scr-$scr;
                                            else if($sc>=$scr)
                                                $sc = $sc-$scr;
                                        }                                        
                                        $mnthschrgd++;                                    
                                    }
                                }
                                if($k==true)
                                {
                                    if($sc >0){
                                        $boo = true;
                                        $sc = $sc/$md;
                                        $mnthschrgd=0;
                                        $cy1 = date('m', strtotime($d1));
                                        $cy2 = date('m', strtotime($df1));
                                        for($i=1;$i<=$monthsdiff;$i++)
                                        {                                         
                                            
                                            if($cy1 >= $cy2)
                                            {
                                                if($i<=$md)
                                                {
                                                    $mnthschrgd ++;
                                                    $td .="<td class='row".$s."'>".number_format($sc, 0, '.', ',')."</td>";
                                                    //$td1 = "row".$s;
                                                    //$td .="<td>$td1</td>";                                            
                                                }
                                                else
                                                {
                                                    if($boo==true)
                                                    {
                                                        //$td1 = "row".$s;
                                                        $td .="<td class='row".$s."'>0</td>";
                                                        //$td .="<td>$td1</td>"; 
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $td .="<td class='row".$s."'>*</td>"; // if invoice value not for the month
                                                $i--;
                                                $boo = false;
                                            }
                                            $cy1++;
                                            
                                            $linetotal +=$sc;                           
                                            $rowtotal2[$s] = $s;                            
                                            $s++;
                                        }                                                                                                           
                                    }                                
                                }
                            }
                        }                        
                    }
                    $t++;
                }
            //}            
            if($mnthschrgd >0)
            {
                $table .="<tr>";
                $table .="<td align='center'>".$n++.".</td>";
                $table .="<td>$leasename</td>";
                $table .="<td>$shopcode</td>";                
                if($size !="")
                $table .="<td>$size</td>";
                else
                $table .="<td>".$row1['size']."</td>";
                $table .="<td>$mnthschrgd</td>";
                $table .="<td>$invno</td>";                                
                $table .="<td>".number_format($invsc, 0, '.', ',')."</td>";
                $table .=$tdpr;                
                $table .=$td;                
                $table .="</tr>";
                $grandtsqrft +=$size;
                $grandmnths +=$md;
                $grandinvtotal +=$invsc;
                
            }        
        }       
    }
$table .="<tr>";
$table .="<td align='right' colspan='3'><b>GRAND TOTAL:</b></td>";
$table .="<td align='right'><b>".number_format($grandtsqrft, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($grandmnths, 0, '.', ',')."</b></td>";
$table .="<td align='right'></td>";
$table .="<td align='right'><b>".number_format($grandinvtotalprv, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($grandinvtotal, 0, '.', ',')."</b></td>";
for($j=0;$j<$monthsdiff;$j++)
{
    $tk1 = "tot".$j;
    $tk .="<td id='tot".$j."' align='right'>$tk1</td>";                      
}
$table .=$tk; 

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