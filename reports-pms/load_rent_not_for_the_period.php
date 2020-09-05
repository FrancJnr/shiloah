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
//$fromdt = explode(" ",$fromdt);
//$fromdt = $fromdt[1] . '-' . $fromdt[0] . '-01';
$fromdt = date('Y-m-d', strtotime($fromdt));

$todt = $_GET['todt'];
//$todt = explode(" ",$todt);
//$todt = $todt[1] . '-' . $todt[0] . '-01';
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
$table .="<tr><td colspan='18'><b>RENT NOT FOR THE PERIOD : ".strtoupper(date('d-M-Y', strtotime($_GET['fromdt']))." to ".date('d-M-Y', strtotime($_GET['todt'])))."</b></td></tr>";
$table .="<tr><td colspan='18'><b>$buildingname</td></tr>";
$table .="<tr><td colspan='3'></td><td colspan='7'><b>Invoice Details</td><td colspan='2'></td><td colspan='3'><b>Diff</td><td></td></tr>";
$table .="<tr>";
$table .="<th>S.NO</th>";
$table .="<th>Tenant</th>";
$table .="<th>Shop Code</th>";
$table .="<th>Sqrft</th>";
$table .="<th>Rent</th>";
$table .="<th>Sc</th>";
$table .="<th>Total</th>";
$table .="<th>Inv.No</th>";
$table .="<th>From.Dt</th>";
$table .="<th>To.Dt</th>";
$table .="<th>Chrgd Mnths</th>";
$table .="<th>Diff Mnths</th>";
$table .="<th>Rent Diff</th>";
$table .="<th>Sc Diff</th>";
$table .="<th>Total Diff</th>";
$table .="<th>Created On</th>";
$table .="</tr>";

$sql="select c.leasename,c.tradingname,d.size,d.shopcode,a.rent,a.sc, a.rent+a.sc as chrgd_total,a.invoiceno,
        date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,
        @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as chrgd_mnths,
        case 
            when date_format(fromdate,'%d')=1 then @m1:=FLOOR(DATEDIFF(todate,'$todt')/30)
            when date_format(fromdate,'%d')>1 then @m1:=round(DATEDIFF(a.todate,a.fromdate)/31)-2
        end as diff_mnths,
        @m1*round(a.rent/@m) as rm,@m1*round(a.sc/@m) as sm,
        @m1*round(a.rent/@m)+@m1*round(a.sc/@m) as unchrgd_total,
        a.createddatetime
        from invoice a
        inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
        inner join mas_shop d on d.shopmasid = c.shopmasid
        inner join mas_building e on e.buildingmasid = d.buildingmasid
        inner join mas_age f on f.agemasid = c.agemasidrc 
        where d.buildingmasid='$buildingmasid' and ( (a.createddatetime between '$fromdt' and '$todt') and todate > '$todt') and  datediff(todate, fromdate) >30
        union
        select c.leasename,c.tradingname,d.size,d.shopcode,a.rent,a.sc, a.rent+a.sc as chrgd_total,a.invoiceno,
        date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,
        @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as chrgd_mnths,
        case 
            when date_format(fromdate,'%d')=1 then @m1:=FLOOR(DATEDIFF(todate,'$todt')/30)
            when date_format(fromdate,'%d')>1 then @m1:=round(DATEDIFF(a.todate,a.fromdate)/31)-2
        end as diff_mnths,
        @m1*round(a.rent/@m) as rm,@m1*round(a.sc/@m) as sm,
        @m1*round(a.rent/@m)+@m1*round(a.sc/@m) as unchrgd_total,
        a.createddatetime
        from advance_rent a
        inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
        inner join mas_shop d on d.shopmasid = c.shopmasid
        inner join mas_building e on e.buildingmasid = d.buildingmasid
        inner join mas_age f on f.agemasid = c.agemasidrc 
        where d.buildingmasid='$buildingmasid' and ( (a.createddatetime between '$fromdt' and '$todt') and todate > '$todt') and  datediff(todate, fromdate) >30
        order by leasename;";
$result = mysql_query($sql);
$i=0;
if($result)
{
    while($row = mysql_fetch_assoc($result))
    {
        //if($row['diff_mnths'] >0)
        //{
            $i++;
            $table .="<tr>";
            $table .="<td>".$i."</td>";
            if($row['tradingname']!="")
                $row['leasename'] .="T/A".$row["tradingname"];
            $table .="<td>".$row['leasename']."</td>";
            $table .="<td>".$row['shopcode']."</td>";
            $table .="<td>".$row['size']."</td>";
                $table .="<td>".$row['rent']."</td>";
                $table .="<td>".$row['sc']."</td>";
                $table .="<td>".$row['chrgd_total']."</td>";
            $table .="<td>".$row['invoiceno']."</td>";
            $table .="<td>".$row['fromdate']."</td>";
            $table .="<td>".$row['todate']."</td>";
            $table .="<td>".$row['chrgd_mnths']."</td>";
                $table .="<td>".$row['diff_mnths']."</td>";
                $table .="<td>".$row['rm']."</td>";
                $table .="<td>".$row['sm']."</td>";
                $table .="<td>".$row['unchrgd_total']."</td>";       
            $table .="<td>".$row['createddatetime']."</td>";
            $table .="</tr>";
        //}
    }
}
$table .="<tr>";
$table .="<td align='center' colspan='4'><b>GRAND TOTAL:</b></td>";
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