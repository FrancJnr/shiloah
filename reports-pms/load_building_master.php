<?php
include('../config.php');
session_start();
try{
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$sqrftTotal=0;
$table ="<p class='printable'><table border='1' cellspacing='2' cellpadding='2'>";
$table .="<tr align='center'>";
$table .="<td><strong>Index</td>";
$table .="<td><strong>Shop Code</td>";
$table .="<td><strong>Sq.Ft</td>";
$table .="<td><strong>Tenant</td>";
$table .="<td><strong>Commencement</td>";
$table .="<td><strong>Expiry</td>";
$table .="<td><strong>Rent Cycle</td>";
$sql = "select distinct YEAR(CURDATE()) as yearStart,Max(YEAR(b.todate)) as yearEnd from trans_offerletter a
inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
inner join mas_tenant c on c.tenantmasid = a.tenantmasid
inner join mas_building d on d.buildingmasid = c.buildingmasid
where d.buildingmasid =$buildingmasid";

$result = mysql_query($sql);
$row1 = mysql_fetch_assoc($result);
$yearStart = $row1['yearStart'];
$yearEnd = $row1['yearEnd'];
$yearCnt =0;
    for($i=$yearStart;$i<=$yearEnd;$i++)
    {
        $table .="<td>";
        $table .="<table width='100%' class='tbl'><tr>";
        $table .="<td align='center'>";
        $table .="YEAR ".$i;
        $table .="</td>";
        $table .="</tr><tr><td align='center'>";
        $table .="RENT    |     SC";
        $table .="</td>";
        $table .="</tr></table>";
        $table .="</td>";
        $yearCnt++;
    }
$table .="</tr>";
$sql = " select @n:=@n+1 'Index',a.shopcode as Shopcode,a.size as Sqrft, b.leasename as Tenant ,
        DATE_FORMAT(b.doc,'%d-%m-%Y') as doc,c.shortdesc,d.age as term,e.offerlettermasid
        from mas_shop a 
        left outer join mas_tenant b on b.shopmasid = a.shopmasid
        left outer join mas_age c on c.agemasid = b.agemasidrc
        left outer join mas_age d on d.agemasid = b.agemasidlt
        left outer join trans_offerletter e on e.tenantmasid = b.tenantmasid
        ,(select @n :=0) as n
        where a.buildingmasid=$buildingmasid and a.active='1';";
$result = mysql_query($sql);
$a="0";$i=1;
    while($row = mysql_fetch_assoc($result))
    {
        $offmasid = $row["offerlettermasid"];
        $yearlyRent ="";
        if($offmasid !="")
        {
              $sql1 = "select y.shopcode,y.size as sqrft, s.buildingname,x.leasename,v.age as term , g.shortdesc as rentCycle, DATE_FORMAT(x.doc,'%d-%m-%Y') as doc,\n"
            . " case lower(g.age)
                when 'per month' then group_concat(a.amount,' ',b.amount,' ')
                when 'per quarter' then group_concat(round(a.amount/3),' ',round(b.amount/3),' ')
                when 'per half' then group_concat(round(a.amount/6),' ',round(b.amount/6),' ')
                when 'per year' then group_concat(round(a.amount/12),' ',round(b.amount/12),' ')
                when 'per week' then group_concat(round(a.amount/7),' ',round(b.amount/7),' ')
                end as rentDetails
                from trans_offerletter_rent a \n"
            . " inner join trans_offerletter_sc b on b.offerletterscmasid = a.offerletterrentmasid\n"
            . " inner join trans_offerletter z on z.offerlettermasid = a.offerlettermasid\n"
            . " inner join mas_tenant x on x.tenantmasid = z.tenantmasid\n"
            . " inner join mas_building s on s.buildingmasid = x.buildingmasid\n"
            . " inner join mas_shop y on y.shopmasid = x.shopmasid\n"
            . " inner join mas_age v on v.agemasid = x.agemasidlt\n"
            . " inner join mas_age g on g.agemasid = x.agemasidrc\n"
            . " where a.offerlettermasid = $offmasid and x.active = 1 and  YEAR(a.todate) >= YEAR(CURDATE()) ORDER BY x.leasename ASC";
        //$sql="select * from mas_shop";
        $result1 = mysql_query($sql1);
        $row1=mysql_fetch_assoc($result1);
     
            $yearlyRent = $row1['rentDetails'];
        }
        $expiryDate="";
        $term = $row["term"];
        $term = explode(" ",$term);
        $yearcnt =$term[0];
//        $s = strtolower($term[1]);
//        if(($s == "years")  || ($s=="year"))
//	{
            if ($yearcnt!=""){
                $expiryDate = date("d-m-Y", strtotime(date("d-m-Y", strtotime($row["doc"])) . " + $yearcnt Year") -"1 Day");
            }
//        }
    
        $str = explode(" ",$row['Shopcode']);
        $sqrftTotal += $row['Sqrft'];
        if($row['Tenant'] =="")
        $row['Tenant'] ="<strong><font color=red>VACANT SHOP";
       if($a != $str[0]){
        $a = $str[0];
        if($i!=1)
        $table .="<tr><td colspan='18'>&nbsp;&nbsp;</td></tr>";
        $table .="<tr><td>$i</td>
            <td align='center'>$row[Shopcode]</td>
            <td align='right'>$row[Sqrft]</td>
            <td>$row[Tenant]</td>
            <td>$row[doc]</td>
            <td>$expiryDate</td>
            <td>$row[shortdesc]</td>";
            $arr = explode(",",$yearlyRent);        
        for($j=0;$j<count($arr);$j++)
        {
             if($arr[$j] != "")
             {
                $rentsc = explode(" ",$arr[$j]);
                $rent =  number_format($rentsc[0], 0, '.', ',');
                $sc =  number_format($rentsc[1], 0, '.', ',');
                $table .="<td>
                <table width='100%' class='tbl'>
                    <tr>
                    <td align='center'>".$rent."</td>
                    <td align='center'> | </td>
                     <td align='center'>$sc</td>
                    </tr>
                </table>        
                </td>";  
                //$table .="<td>$arr[$j]</td>";        
             }
        }
        //$table .="<td align='center' colspan='7'>$row2[rentDetails]</td>";        
        $table .= "</tr>";
            $i++;
       }
       else{
        $table .="<tr><td>$i</td>
            <td align='center'>$row[Shopcode]</td>
            <td align='right'>$row[Sqrft]</td>
            <td>$row[Tenant]</td>
            <td>$row[doc]</td>
            <td>$expiryDate</td>
            <td>$row[shortdesc]</td>";
             $arr = explode(",",$yearlyRent);        
        for($j=0;$j<count($arr);$j++)
        {
             if($arr[$j] != "")
             {
                $rentsc = explode(" ",$arr[$j]);
                $rent =  number_format($rentsc[0], 0, '.', ',');
                $sc =  number_format($rentsc[1], 0, '.', ',');
                $table .="<td>
                <table width='100%' class='tbl'>
                    <tr>
                    <td align='center'>".$rent."</td>
                    <td align='center'> | </td>
                     <td align='center'>$sc</td>
                    </tr>
                </table>        
                </td>";  
                //$table .="<td>$arr[$j]</td>";        
             }
        }
        //$table .="<td align='center' colspan='7'>$row2[rentDetails]</td>";        
        $table .= "</tr>";
            $i++;
        }
    }
$table .= "<tr>
        <td colspan='7' align=right>SUB TOTAL RENT AND SC</td>";
        $k=0;$arr="";
        while($k < $yearCnt)
        {
            $cnt =$k;
            $sqlTotal = "select sum(
    case lower(e.age)
    when 'per month' then a.amount
    when 'per quarter' then round(a.amount/3) 
    when 'per half' then round(a.amount/6)
    when 'per year' then round(a.amount/12)
    when 'per week' then round(a.amount/7) 
    end) 
    as rentDetails 
    from trans_offerletter_rent a
    inner join trans_offerletter_sc n on n.offerletterscmasid = a.offerletterrentmasid
    inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
    inner join mas_tenant c on c.tenantmasid = b.tenantmasid
    inner join mas_building d on d.buildingmasid = c.buildingmasid
    inner join mas_age e on e.agemasid = c.agemasidrc
    where  YEAR(a.fromdate) =  YEAR(CURDATE()) + $cnt and c.active='1' and c.buildingmasid =$buildingmasid
    union
    select sum(
    case lower(e.age)
    when 'per month' then n.amount
    when 'per quarter' then round(n.amount/3) 
    when 'per half' then round(n.amount/6)
    when 'per year' then round(n.amount/12)
    when 'per week' then round(n.amount/7) 
    end) 
    as scDetails 
    from trans_offerletter_rent a
    inner join trans_offerletter_sc n on n.offerletterscmasid = a.offerletterrentmasid
    inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
    inner join mas_tenant c on c.tenantmasid = b.tenantmasid
    inner join mas_building d on d.buildingmasid = c.buildingmasid
    inner join mas_age e on e.agemasid = c.agemasidrc
    where  YEAR(a.fromdate) = YEAR(CURDATE()) + $cnt and c.active='1' and c.buildingmasid =$buildingmasid";
    $table .="<td align=right><table width='100%' border ='0' class='tbl'><tr>";
                    $resultTotal = mysql_query($sqlTotal);
                    
                    while($rowTotal = mysql_fetch_assoc($resultTotal))
                    {
                        $arr .= $rowTotal['rentDetails'].',';
                        //if($rowTotal['rentDetails'] !="")
                        $table .="<td align='center'>".number_format($rowTotal['rentDetails'],0, '.', ',')." | </td>";
                    }
                    $arr .=";";
                     $table .="</table></td>";
                    $k++;
               }
    $table .="</td>           
        </tr>";
$table .= "<tr><td colspan='7' align=right><STRONG>GRAND TOTAL</STRONG></td>";
$grandTotal = explode(";",$arr);
for($i=0;$i<count($grandTotal);$i++)
{
     if($grandTotal[$i] != "")
     {
        $gt = explode(",",$grandTotal[$i]);
        $table .= "<td>";
        $gtv=0;
        for($j=0;$j<count($gt);$j++)
        {
            if($gt[$j] != "")
            {
                $gtv += $gt[$j];
            }
        }
        $gtv = number_format($gtv,0, '.', ',');
        $table .= "$gtv</td>";
     }
}
$table .= "</tr>";
$table .= "</table></p>";

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