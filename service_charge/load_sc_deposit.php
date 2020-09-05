<?php
include('../config.php');
session_start();
try
{
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
$s = "select buildingname from mas_building where buildingmasid =$buildingmasid";
$r = mysql_query($s);
while($ro = mysql_fetch_assoc($r)){
    $buildingname = strtoupper($ro["buildingname"]);
}
    
$table ="<p class='printable'><table class='table6'>";
$table .="<tr><th colspan='18'>Service Charge Deposit Report</th></tr>";
$table .="<tr><td colspan='18'><b> $buildingname <br><br> ".$firstdate."</b></td></tr>";
$table .="<tr>";
$table .="<th>S.No</th>";
$table .="<th>Tenant</th>";
$table .="<th>Sqrft</th>";
$table .="<th>Mnths</th>";
$table .="<th>Total Sqrft</th>";
for($i=0;$i<=$months;$i++)
{
    $d2 = date("M-Y", strtotime(date("Y-m", strtotime($d1)) . " + $i Months")); 
    $table .="<th class='rowth'>$d2</th>";    
}
$table .="<th>Net Total</th>";
$table .="</tr>";
    $n =1;$cnt=0;
    $grandtsqrft=0;$grandtotal=0;$rowtotal1[0]=0;$rowtotal2[0]=0;$rowtotal3[0]=0;$tk="";
    $sql1 = "select c.leasename,c.tradingname, a.grouptenantmasid,d.size
                from advance_rent a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_age f on f.agemasid = c.agemasidrc 
                where d.buildingmasid='$buildingmasid' and c.renewal=0
            UNION
            select c1.leasename,c1.tradingname, a1.grouptenantmasid,d1.size
                from invoice a1
                inner join group_tenant_mas b1 on b1.grouptenantmasid = a1.grouptenantmasid
                inner join mas_tenant c1 on c1.tenantmasid = b1.tenantmasid
                inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
                inner join mas_building e1 on e1.buildingmasid = d1.buildingmasid
                inner join mas_age f1 on f1.agemasid = c1.agemasidrc
                where d1.buildingmasid='$buildingmasid' and c1.renewal=0
            order by leasename ;";
    $result1 = mysql_query($sql1);
    if($result1 !=null)
    {        
        while($row1 = mysql_fetch_assoc($result1)) // no of tenants available for the building
        {
            $mnthschrgd=0;$td="";$size="";$totalsqrft=0;$sc=0;$linetotal=0;$s=0;
            $grouptenantmasid=$row1['grouptenantmasid'];
            
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
            
            
            $rid=0; // renewal tenant group masid
            $sg="select grouptenantmasid from group_tenant_det where tenantmasid = (select a.renewalfromid from mas_tenant a
                inner join group_tenant_det b on b.tenantmasid = a.tenantmasid
                where b.grouptenantmasid =$grouptenantmasid);";
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
            
            if($row1['tradingname'] =="")
                $leasename = $row1['leasename'];
            else
                $leasename = $row1['leasename'] ." T/A ".$row1['tradingname'];
                
            $grandtd="";$md=""; 
            
            for($i=0;$i<=$months;$i++)// no of months charged
            {                                            
                $sqlExec = explode(",",$grouptenantmasid);
                $cnt = count($sqlExec);
                $t=1;
                for($x=0;$x<$cnt;$x++)
                {                    
                    
                    if($sqlExec[$x] != "")
                    {
                        $gpmasid = $sqlExec[$x];                 
                        $d2 = date("Y-m", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
                        $d=1;$m=1;                        
                        
                        //$whr =" date_format(a.createddatetime,'%Y-%m') = '$d2' group by a.grouptenantmasid;";
                        $whr =" date_format(a.createddatetime,'%Y-%m') = '$d2' ;";
                        
                        $sql2="select 
                                a.rent,
                                a.sc,
                                d.size,
                                date_format(a.fromdate, '%d-%m-%Y') as 'invfrom',
                                date_format(a.todate, '%d-%m-%Y') as 'invto',
                                case lower(f.shortdesc)
                                    when 'mnthly' then round(a.sc / 1)
                                    when 'qtrly' then round(a.sc / 3)
                                    when 'half' then round(a.sc / 6)
                                    when 'yearly' then round(a.sc / 12)
                                end as 'mnthlyscold1',
                                @m:=TIMESTAMPDIFF(MONTH,
                                    a.fromdate,
                                    a.todate) + 1 as monthsdiff,
                                a.sc as mnthlyscold2,
                                round(a.sc / @m) as mnthlysc,
                                a.invoiceno
                            from
                                advance_rent a
                                    inner join
                                group_tenant_det b ON b.grouptenantmasid = a.grouptenantmasid
                                    inner join
                                mas_tenant c ON c.tenantmasid = b.tenantmasid
                                    inner join
                                mas_shop d ON d.shopmasid = c.shopmasid
                                    inner join
                                mas_building e ON e.buildingmasid = d.buildingmasid
                                    inner join
                                mas_age f ON f.agemasid = c.agemasidrc
                            where
                                a.sc > 0 and a.grouptenantmasid = '$gpmasid'
                                    and date_format(a.createddatetime, '%Y-%m') = '$d2'
                            union
                            select 
                                a.rent,
                                a.sc,
                                d.size,
                                date_format(a.fromdate, '%d-%m-%Y') as 'invfrom',
                                date_format(a.todate, '%d-%m-%Y') as 'invto',
                                case lower(f.shortdesc)
                                    when 'mnthly' then round(a.sc / 1)
                                    when 'qtrly' then round(a.sc / 3)
                                    when 'half' then round(a.sc / 6)
                                    when 'yearly' then round(a.sc / 12)
                                end as 'mnthlyscold1',
                                @m:=TIMESTAMPDIFF(MONTH,
                                    a.fromdate,
                                    a.todate) + 1 as monthsdiff,
                                a.sc as mnthlyscold2,
                                round(a.sc / @m) as mnthlysc,
                                a.invoiceno
                            from
                                invoice a
                                    inner join
                                group_tenant_det b ON b.grouptenantmasid = a.grouptenantmasid
                                    inner join
                                mas_tenant c ON c.tenantmasid = b.tenantmasid
                                    inner join
                                mas_shop d ON d.shopmasid = c.shopmasid
                                    inner join
                                mas_building e ON e.buildingmasid = d.buildingmasid
                                    inner join
                                mas_age f ON f.agemasid = c.agemasidrc
                            where
                                a.sc > 0 and a.grouptenantmasid = '$gpmasid'
                                    and date_format(a.createddatetime, '%Y-%m') = '$d2' group by  a.grouptenantmasid;";
                        
                        //$sql2="select a.rent, a.sc,d.size,
                        //    date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',
                        //    date_format(a.todate,'%d-%m-%Y') as 'invto',                               
                        //    case lower(f.shortdesc)
                        //            when 'mnthly' then round(a.sc/1)
                        //            when 'qtrly' then round(a.sc/3)                    
                        //            when 'half' then round(a.sc/6)
                        //            when 'yearly' then round(a.sc/12)
                        //    end as 'mnthlyscold1',
                        //    @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as monthsdiff,
                        //    a.sc as mnthlyscold2,
                        //    round(a.sc/@m) as mnthlysc,
                        //    a.invoiceno
                        //    from advance_rent a
                        //    inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        //    inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                        //    inner join mas_shop d on d.shopmasid = c.shopmasid
                        //    inner join mas_building e on e.buildingmasid = d.buildingmasid
                        //    inner join mas_age f on f.agemasid = c.agemasidrc
                        //    where a.sc>0 and a.grouptenantmasid = $gpmasid and
                        //    $whr;";
                        //    $result2 = mysql_query($sql2);
                        //    if($result2 !=null)
                        //    {                
                        //        $rcount = mysql_num_rows($result2);
                        //        if($rcount ==0) 
                        //        {
                        //            $sql2="select a.rent, a.sc,d.size,
                        //                date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',
                        //                date_format(a.todate,'%d-%m-%Y') as 'invto',                               
                        //                case lower(f.shortdesc)
                        //                        when 'mnthly' then round(a.sc/1)
                        //                        when 'qtrly' then round(a.sc/3)                    
                        //                        when 'half' then round(a.sc/6)
                        //                        when 'yearly' then round(a.sc/12)
                        //                end as 'mnthlyscold1',
                        //                @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as monthsdiff,
                        //                a.sc as mnthlyscold2,
                        //                round(a.sc/@m) as mnthlysc,
                        //                a.invoiceno
                        //                from invoice a
                        //                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        //                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                        //                inner join mas_shop d on d.shopmasid = c.shopmasid
                        //                inner join mas_building e on e.buildingmasid = d.buildingmasid
                        //                inner join mas_age f on f.agemasid = c.agemasidrc
                        //                where a.sc>0 and a.grouptenantmasid = $gpmasid and
                        //                $whr;";
                        //        }
                        //    }                                               
                        $result2 = mysql_query($sql2);
                        if($result2 !=null)
                        {
                            $rcount = mysql_num_rows($result2);
                            if($rcount ==0) 
                            {                                                            
                                    if($t == $cnt)
                                    {                                                                               
                                        $td .="<td class='row".$s."'>-</td>";                                    
                                        $rowtotal2[$s] = $s;                            
                                        $s++;                                        
                                    }
                            }
                            $k = false;
                            while($row2 = mysql_fetch_assoc($result2))
                            {                                                
                                $k=true;                            
                                if($rcount >1)
                                {                                    
                                    $sc += $row2['mnthlyscold2'];
                                    $md +=$row2['monthsdiff'];
                                }
                                else
                                {                                    
                                    $sc = $row2['mnthlyscold2'];
                                    $md =$row2['monthsdiff'];
                                }
                                $mnthschrgd+=$md;                                
                            }
                            if($k==true){
                                if($sc >0){                                    
                                    $td .="<td class='row".$s."'>".number_format($sc, 0, '.', ',')."</td>";
                                    //$td .="<td class='row".$s."'>$sql2</td>";                                    
                                    $linetotal +=$sc;                           
                                    $rowtotal2[$s] = $s;                            
                                    $s++;
                                }                                
                            }                            
                        }                        
                    }
                    $t++;
                }
            }            
            if($mnthschrgd >0)
            {
                $table .="<tr>";
                $table .="<td align='center'>".$n++.".</td>";
                $table .="<td>$leasename</td>";
                if($size !="")
                $table .="<td>$size</td>";
                else
                $table .="<td>".$row1['size']."</td>";

                $table .="<td>$mnthschrgd</td>";
                $totalsqrft =$size*$mnthschrgd;
                $table .="<td>$totalsqrft</td>";
                $table .=$td;
                $table .="<td><b>".number_format($linetotal, 0, '.', ',')."</b></td>";            
                $table .="</tr>";
                $grandtsqrft +=$totalsqrft;
                $grandtotal +=$linetotal;            
            }        
        }       
    }
$table .="<tr>";
$table .="<td align='right' colspan='4'><b>GRAND TOTAL:</b></td>";
$table .="<td align='right'><b>".number_format($grandtsqrft, 0, '.', ',')." Sqrft</b></td>";
$rk = implode($rowtotal2, '-');
                $sqlExec = explode("-",$rk);
                for($j=0;$j<=sizeof($sqlExec)-1;$j++)
                {
                    if($sqlExec[$j] != "")
                    {                       
                       $tk .="<td id='tot".$j."' align='right'></td>";                      
                    }
                }                
$table .=$tk; 
$table .="<td align='right'><b>".number_format($grandtotal, 0, '.', ',')."</b></td>";
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