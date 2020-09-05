<?php
include('../config.php');
session_start();
try{
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$invoicedt = explode(" ",$_GET['invdt']);
$dtformat = "01-".$invoicedt[0]."-".$invoicedt[1];
$dtformat = date("Y-m-d", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Day"));
$buildingname = "";
$s = "select buildingname from mas_building where buildingmasid =$buildingmasid";
$r = mysql_query($s);
 while($ro = mysql_fetch_assoc($r))
    {
        $buildingname = strtoupper($ro["buildingname"]);
    }
$sqrftTotal=0;
$table ="<p class='printable'><table border='1' cellspacing='2' cellpadding='2'>";
$table .="<tr align='center'>";
$table .="<td><strong>Index</td>";
$table .="<td><strong>Shop Code</td>";
$table .="<td><strong>Sq.Ft</td>";
$table .="<td><strong>Tenant</td>";
$table .="<td><strong>Period Frm</td>";
$table .="<td><strong>Period To</td>";
$table .="<td><strong>Rent Cycle</td>";
$table .="<td><strong>Rent</td>";
$table .="<td><strong>VAT 14%</td>";
$table .="<td><strong>Rent + VAT</td>";
$table .="<td><strong>Scr Chrg</td>";
$table .="<td><strong>VAT 14%</td>";
$table .="<td><strong>ScrChrg + VAT</td>";
$table .="<td><strong>Monthly Rent</td>";
$sql = " select 
    @n:=@n+1 'Index',d.shopcode as Shopcode,d.size as Sqrft,
    c.leasename as Tenant,DATE_FORMAT(c.doc,'%d-%m-%Y') as doc,
    g.shortdesc,h.age as term,
    f.offerlettermasid ,b.grouptenantmasid
    from group_tenant_det a
    inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
    inner join mas_tenant c on c.tenantmasid =  a.tenantmasid
    inner join mas_shop d on d.shopmasid = c.shopmasid
    inner join mas_building e on e.buildingmasid = d.buildingmasid
    inner join trans_offerletter f on f.tenantmasid = a.tenantmasid
    inner join mas_age g on g.agemasid = c.agemasidrc
    inner join mas_age h on h.agemasid = c.agemasidlt
     ,(select @n :=0) as n
    where d.buildingmasid=$buildingmasid and b.active='1' and c.active='1'
    order by  grouptenantmasid;";

$result = mysql_query($sql);
$rowcnt = mysql_num_rows($result);
$chk1 =0;$chk2 =0;
$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;
    while($row = mysql_fetch_assoc($result))
    {                
        $offmasid = $row["offerlettermasid"];
        $rentCycle=0;$monthdiff=0;$fromdt="";$todt="";$invperiod="";$d1=0;$m1=0;$d2=0;$m2=0;
        $monthlyRent ="";
        if($offmasid !="")
        {
            $sql1 = "select y.shopcode,y.size as sqrft, s.buildingname,x.leasename,v.age as term , g.shortdesc as rentCycle, DATE_FORMAT(x.doc,'%d-%m-%Y') as doc,\n"
            ."  group_concat(
                    a.amount,' ',round(a.amount*0.14),' ',
                    a.amount+round(a.amount*0.14),' ',
                    b.amount,' ',round(b.amount*0.14),' ',
                    b.amount+round(b.amount*0.14),' ',
                    a.amount+round(a.amount*0.14)+b.amount+round(b.amount*0.14),' '
                )
                as rentDetails,DATE_FORMAT(a.fromdate,'%d-%m-%Y') as fromdate,
                DATE_FORMAT(a.todate,'%d-%m-%Y') as todate,               
                DATE_FORMAT(CURDATE(),'%b-%d') as InvPeriod,
                round(DATEDIFF('$dtformat',a.fromdate) / 30) as monthDiff,g.age as rentCycle, DATE_ADD(CURDATE(),INTERVAL 1 MONTH) as 'cdate'
                from trans_offerletter_rent a \n"
            . " inner join trans_offerletter_sc b on b.offerletterscmasid = a.offerletterrentmasid\n"
            . " inner join trans_offerletter z on z.offerlettermasid = a.offerlettermasid\n"
            . " inner join mas_tenant x on x.tenantmasid = z.tenantmasid\n"
            . " inner join mas_building s on s.buildingmasid = x.buildingmasid\n"
            . " inner join mas_shop y on y.shopmasid = x.shopmasid\n"
            . " inner join mas_age v on v.agemasid = x.agemasidlt\n"
            . " inner join mas_age g on g.agemasid = x.agemasidrc\n"
            . " where a.offerlettermasid = $offmasid and x.active = 1 and '$dtformat' between a.fromdate and a.todate ORDER BY x.leasename ASC";            
            $result1 = mysql_query($sql1);
            $row1=mysql_fetch_assoc($result1);
            $monthlyRent = $row1['rentDetails'];
            $rentCycle = strtolower($row1['rentCycle']);
            $cdate = $row1['cdate'];
            if($rentCycle == 'per month')            
            {                
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
                $d1 = $d1 ."-".$m1;
                $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 1 Months"));
                $d2 = date('d-m-Y',strtotime("-1 days $d2"));   	           
            }
            else if($rentCycle == 'per quarter')
            {
                $monthdiff = fmod($row1['monthDiff'],3);               
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
                $d1 = $d1 ."-".$m1;
                $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 3 Months"));
                $d2 = date('d-m-Y',strtotime("-1 days $d2"));   	  
            }
            else if($rentCycle == 'per half')
            {
                $monthdiff = fmod($row1['monthDiff'],6);                 
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
                $d1 = $d1 ."-".$m1;
                $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 6 Months"));
                $d2 = date('d-m-Y',strtotime("-1 days $d2"));   
            }
            else if($rentCycle == 'per year')
            {
                $monthdiff = fmod($row1['monthDiff'],12);                 
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($dtformat)) . " + 0 Months"));
                $d1 = $d1 ."-".$m1;
                $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 12 Months"));
                $d2 = date('d-m-Y',strtotime("-1 days $d2"));  
            }            
            $invperiod = $row1['InvPeriod'];
        }
            
        $expiryDate="";
        $term = $row["term"];
        $term = explode(" ",$term);
        $yearcnt =$term[0];
        if ($yearcnt!=""){
                $expiryDate = date("d-m-Y", strtotime(date("d-m-Y", strtotime($row["doc"])) . " + $yearcnt Year") -"1 Day");
        }
        $str = explode(" ",$row['Shopcode']);
        $sqrftTotal += $row['Sqrft'];
        $t =true;
        if($row['Tenant'] =="")
        {
            $row['Tenant'] ="<strong><font color=red>VACANT SHOP";
            $monthdiff=1;
            $t=false;
        }
       if($a != $str[0]){
        $a = $str[0];
        if($i!=1)
            $table .="<tr bgcolor='#eee'><td colspan='18'>&nbsp;&nbsp;</td></tr>";
        }
        $chk1 = $row["grouptenantmasid"];
        $sq1 = "select c.shopcode,c.size from group_tenant_det a
                inner join mas_tenant b on b.tenantmasid = a.tenantmasid
                inner join mas_shop c on c.shopmasid = b.shopmasid
                where a.grouptenantmasid = $chk1";
        $re1 = mysql_query($sq1);
        $rc1 = mysql_num_rows($re1);
        
            if ($rc1 > 1) 
            {           
                    while ($ro1 = mysql_fetch_assoc($re1))
                    {
                        $shp .= ",(" . $ro1["shopcode"].",".$ro1["size"].") ";
                        
                    }
                    if($monthdiff <= 0)
                    {  
                        $shp  .=$monthlyRent;
                    }
            }
            else
            {
                $shp = "";
                $grptot =0;
            }        
        //if($chk1 != $chk2)
        //{         
            $shp = ltrim($shp,",");
            $shp ="<font color=red>".$shp."</font>";
            $table .="<tr><td align='center'>$i</td>
                <td align='center'>$row[Shopcode]</td>
                <td align='right'>$row[Sqrft]</td>
                <td>$row[Tenant]<br>$shp</td>";
                $table .="<td>$d1</td>
                <td>$d2</td>            
                <td>$row[shortdesc]</td>";
            if($monthdiff <= 0)
            {            
                $arr = explode(" ",$monthlyRent);        
                for($j=0;$j<count($arr);$j++)
                {
                    if($arr[$j] != "")
                    {
                        $table .="<td dir=rtl>";                        
                        $table .=$arr[$j];
                        //$table .=number_format($arr[$j], 0, '.', ',');
                        $table .="</td>";
                        if($j==6)
                        $gtot +=$arr[$j];
                    }
                }
            }
            else
            {
                 $table .="<td colspan='10' dir=rtl>0.00</td>";
            }
            $table .= "</tr>";
            $i++;
            $chk2 = $row["grouptenantmasid"];
            $shp ="";
        //}       
}
$table .= "<tr><td colspan='15' dir=rtl>GRAND TOATAL : ".number_format($gtot, 0, '.', ',')."</td></tr>";
$table .= "</table></p>";


$custom = array('divContent'=>$table,'heading'=>$buildingname.' Rental Schedule for the period of '.$dtformat,'s'=>'Success');
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