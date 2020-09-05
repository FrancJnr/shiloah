<?php
include('../config.php');
session_start();
try{
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$dateinput = $_GET['dateinput'];
$buildingname = "";
$s = "select buildingname from mas_building where buildingmasid =$buildingmasid";
$r = mysql_query($s);
while($ro = mysql_fetch_assoc($r))
    {
        $buildingname = strtoupper($ro["buildingname"]);
    }
$sqrftTotal=0;    
$table="<p class='printable'>";
$sql = " select @n:=@n+1 'Index',a.shopcode as Shopcode,a.size as Sqrft, b.leasename as Tenant ,
        DATE_FORMAT(b.doc,'%d-%m-%Y') as doc,c.shortdesc,d.age as term,e.offerlettermasid,g.buildingname
        from mas_shop a 
        left outer join mas_tenant b on b.shopmasid = a.shopmasid
        left outer join mas_building g on g.buildingmasid = a.buildingmasid
        left outer join mas_age c on c.agemasid = b.agemasidrc
        left outer join mas_age d on d.agemasid = b.agemasidlt
        left outer join trans_offerletter e on e.tenantmasid = b.tenantmasid
        ,(select @n :=0) as n
        where a.buildingmasid=$buildingmasid and a.active='1';";
$result = mysql_query($sql);
$a="0";$i=1;$gtot=0;
    while($row = mysql_fetch_assoc($result))
    {                
        $offmasid = $row["offerlettermasid"];
        $rentCycle=0;$monthdiff=0;$fromdt="";$todt="";$invperiod="";$d1=0;$m1=0;$d2=0;$m2=0;
        $monthlyRent ="";
        if($offmasid !="")
        {
            $sql1 = "select y.shopcode,y.size as sqrft, s.buildingname,x.leasename,v.age as term , g.shortdesc as rentCycle, DATE_FORMAT(x.doc,'%d-%m-%Y') as doc,\n"
            ."  group_concat(
                    a.amount,',',round(a.amount*0.14),',',
                    a.amount+round(a.amount*0.14),';',
                    b.amount,',',round(b.amount*0.14),',',
                    b.amount+round(b.amount*0.14),';',
                    a.amount+round(a.amount*0.14)+b.amount+round(b.amount*0.14),';'
                )
                as rentDetails,
                a.amount+round(a.amount*0.14)+b.amount+round(b.amount*0.14) as invtotal,
                DATE_FORMAT(a.fromdate,'%d-%m-%Y') as fromdate,
                DATE_FORMAT(a.todate,'%d-%m-%Y') as todate,               
                DATE_FORMAT(CURDATE(),'%b-%d') as InvPeriod,
                round(DATEDIFF(CURDATE(),a.fromdate) / 30) as monthDiff,g.age as rentCycle, DATE_ADD(CURDATE(),INTERVAL 1 MONTH) as 'cdate'
                from trans_offerletter_rent a \n"
            . " inner join trans_offerletter_sc b on b.offerletterscmasid = a.offerletterrentmasid\n"
            . " inner join trans_offerletter z on z.offerlettermasid = a.offerlettermasid\n"
            . " inner join mas_tenant x on x.tenantmasid = z.tenantmasid\n"
            . " inner join mas_building s on s.buildingmasid = x.buildingmasid\n"
            . " inner join mas_shop y on y.shopmasid = x.shopmasid\n"
            . " inner join mas_age v on v.agemasid = x.agemasidlt\n"
            . " inner join mas_age g on g.agemasid = x.agemasidrc\n"
            . " where a.offerlettermasid = $offmasid and x.active = 1 and DATE_ADD(CURDATE(),INTERVAL 1 MONTH) between a.fromdate and a.todate ORDER BY x.leasename ASC";
            //$sql="select * from mas_shop";
            $result1 = mysql_query($sql1);
            $row1=mysql_fetch_assoc($result1);
            $monthlyRent = $row1['rentDetails'];
            if($monthlyRent !="")
            $invtotal = number_format($row1['invtotal'], 0, '.', ',');
            $rentCycle = strtolower($row1['rentCycle']);
            if($rentCycle == 'per month')            
            {
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
                $d1 = $d1 ."-".$m1;
                $d2= date("d", strtotime(date("d-m-Y", strtotime($row1['todate'])) . " + 0 Months"));
                $m2= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 2 Months"));
                $d2 = $d2 ."-".$m2;
            }
            else if($rentCycle == 'per quarter')
            {
                $monthdiff = fmod($row1['monthDiff'],3);
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
                $d1 = $d1 ."-".$m1;
                $d2= date("d", strtotime(date("d-m-Y", strtotime($row1['todate'])) . " + 0 Months"));
                $m2= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 4 Months"));
                $d2 = $d2 ."-".$m2;
            }
            else if($rentCycle == 'per half')
            {
                $monthdiff = fmod($row1['monthDiff'],6);                 
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
                $d1 = $d1 ."-".$m1;
                $d2= date("d", strtotime(date("d-m-Y", strtotime($row1['todate'])) . " + 0 Months"));
                $m2= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 6 Months"));
                $d2 = $d2 ."-".$m2;
            }
            else if($rentCycle == 'per year')
            {
                $monthdiff = fmod($row1['monthDiff'],12);                 
                $d1= date("d", strtotime(date("d-m-Y", strtotime($row1['fromdate'])) . " + 0 Months"));
                $m1= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
                $d1 = $d1 ."-".$m1;
                $d2= date("d", strtotime(date("d-m-Y", strtotime($row1['todate'])) . " + 0 Months"));
                $m2= date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 12 Months"));
                $d2 = $d2 ."-".$m2;
            }
            //$fromdt = $row1['fromdate'];
            //$todt = $row1['todate'];
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
       $table .="<table border='0' cellspacing='2' cellpadding='2' width='100%' style='font:arial;'>";
$table .="
<tr>    
        <td width='40%'><img src='../images/mega_plaza_logo.jpg' height='50px'></td>
        <td width='35%'><img src='../images/mega_city_logo.jpg' height='50px'></td>
        <td align='right'><img src='../images/mega_city_logo.jpg' height='50px'></td>    
</tr>
<tr><td colspan='3' align='center'><h1><font size=6>SHILOAH INVESTMENTS LTD.</font></h1></td></tr>
<tr>    
        <td width='40%'>P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megaproperties.com</td>
        <td width='35%'>Mega Plaza Block 'A' 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
        <td align='right'>Tel: 057 - 2023550 / 2021269 / 2021333<br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
</tr>
<tr height='40px'>
    <td colspan='3' align='center'>
                    <u><font size=5>DEMAND NOTE</font></u>
    </td>
</tr>
<tr>
    <td valign='top' width='25%'>
        <strong>
        TO:<br>
        $row[Tenant],<br>
        P.O BOX NO.2252 - 40100,<br>
        KISUMU.
        </strong>
    </td>
     <td colspan='2' align='right' width='75%'>
        <table border='1' width='75%'>
            <tr>
                <td bgcolor='#dddddd' width='35%'>Invoice #</td>
                <td align='right'>123456</td>
            </tr>
             <tr>
                <td bgcolor='#dddddd' width='35%'>Date</td>
                <td align='right'>November 22,2012</td>
            </tr>
             <tr>
                <td bgcolor='#dddddd' width='35%'>Amount Due</td>
                <td align='right'>$invtotal</td>
            </tr>
        </table>
    </td>
</tr>
<tr height='12px'>
<td colspan='3'>
    <table width='100%' border='1'>
        <tr>
            <td width='25%' bgcolor='#dddddd'>Property / Premises</td>
            <td width='75%'>$buildingname,$row[Shopcode],$row[Sqrft]</td>
        </tr>
        <tr>
            <td width='25%' bgcolor='#dddddd'>Rent Cycle</td>
            <td width='75%'>$row[shortdesc]</td>
        </tr>
        <tr>
            <td width='25%' bgcolor='#dddddd'>Period</td>
            <td width='75%'>$d1-$d2</td>
        </tr>
    </table>
</td>    
</tr>
<tr>
    <td colspan='3'>
        <table cellpadding='5' cellspacing='5' border='1' width='100%'>
            <tr align='center' style='font-weight:bold;'>
                <td bgcolor='#dddddd' width='5%'>S.No</td>
                <td bgcolor='#dddddd' width='10%'>Item</td>
                <td bgcolor='#dddddd' width='10%'>Value</td>
                <td bgcolor='#dddddd' width='10%'>Vat 14%</td>                
                <td bgcolor='#dddddd' width='10%'>Amount</td>
            </tr>
        </table>
    </td>
</tr>
<tr height='120px'>
    <td colspan='3' valign='top'>
        <table cellpadding='1' cellspacing='1' border='0' width='100%'>";
        $gtot=0;
        if($monthdiff <= 0)
        {            
            $arr = explode(";",$monthlyRent);
            for($j=0;$j<count($arr);$j++)
            {
                if($j == 2)
                $gtot = $arr[$j];
                if($j < 2)
                {
                    $table .="<tr height='10%'><td bgcolor='#dddddd' width='5%' align='center'>$j</td>";
                    if($j==0)
                    $table .="<td bgcolor='#dddddd' width='10%'>Rent</td>";
                    if($j==1)
                    $table .="<td bgcolor='#dddddd' width='10%'>Service Chrg</td>";
                    if($arr[$j] != "")
                    {                        
                        $amt = explode(",",$arr[$j]);
                        for($x=0;$x<count($amt);$x++)
                        {
                            if($amt[$x] != "")
                            {                 
                                $table .="<td bgcolor='#dddddd' width='10%' align='right'>";
                                $table .=number_format($amt[$x], 0, '.', ',');
                                $table .="</td>";
                            }
                        }
                    }
                    $table .="</tr>";
                }
                else
                {
                       
                }
            }                
        }
        else
        {
             $table .="<tr><td colspan='7' dir=rtl>0.00</td></tr>";
        }        
        $i++;
        $gtot = number_format($gtot, 0, '.', ',');
        $table .="</table>
    </td>
</tr>

<tr>
    <td colspan='3'>
        <table cellpadding='5' cellspacing='5' border='1' width='100%'>
            <tr align='center' style='font-weight:bold;'>
                <td colspane='3'></td>                                
                <td bgcolor='#dddddd' width='10%'>Total</td>
                <td bgcolor='#dddddd' width='20%'>$gtot</td>
            </tr>
            <tr style='font-weight:bold;' height='30px'>
                <td colspan='5' valign='top'>Remarks:</td>                                                
            </tr>
             <tr style='font-weight:bold;' height='70px'>
                <td colspan='5' valign='top' align='justify'>
                1. Accounts rendered / interests charged / receipts will be reconciled and advised subsequently.<br>
                2. All payments to be acknowledged by official receipts.<br>
                3. Any queries on this demand note should be lodged in writing within 7 days of the date hereof.<br>
                4. Rental payments must be paid as specified in the lease/agreement.<br>
                5. Interest will be charged on over due accounts.<br>
                Due to implementation of new software, our demand note format has changed<br>

                </td>                                                
            </tr>
        </table>
    </td>
</tr>
</table>
<br><br><br><br>
";
        
    }
$table .="</p>";
$custom = array('divContent'=>$table,'heading'=>$buildingname.' Invoice Schedule for the period of '.$dt,'s'=>'Success');
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