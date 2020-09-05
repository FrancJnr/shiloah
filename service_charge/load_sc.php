<?php
include('../config.php');
session_start();
try{
$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;$mnthlyrent=0;
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];

$fromdt = $_GET['fromdt'];
$fromdt = explode('/',$fromdt);
$fromdt = $fromdt[2] . '-' . $fromdt[1] . '-' . $fromdt[0];
$fromdt = date('Y-m-d', strtotime($fromdt));

$todt = $_GET['todt'];
$todt = explode('/',$todt);
$todt = $todt[2] . '-' . $todt[1] . '-' . $todt[0];
$todt = date('Y-m-d', strtotime($todt));

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
//$table .="<th><strong>Index</th>";
//$table .="<th><strong>Shopcode</th>";
//$table .="<th><strong>Sq.Ft</th>";
//$table .="<th><strong>Tenant</th>";
//$table .="<th><strong>Doc</th>";
//$table .="<th><strong>Term</th>";
//$table .="<th><strong>Rent Cycle</th>";
//$table .="<th><strong>Rent</th>";
//$table .="<th><strong>Sc</th>";
//$table .="<th><strong>Sc%</th></tr></thead>";


$sql= "select a.tenantmasid,a.leasename ,a.tradingname,a.doc,b.size,b.shopcode,c.age from mas_tenant a 
	inner join mas_shop b on b.shopmasid = a.shopmasid
	inner join mas_age c on c.agemasid = a.agemasidlt
	where a.buildingmasid='$buildingmasid' and a.tenantmasid in (select tenantmasid from trans_offerletter)
	order by doc;";
$result = mysql_query($sql);
if ($result!=null)
{
    $i=1;
    while($row = mysql_fetch_assoc($result))
    {
	
	if($row['tradingname'] !="")
	$row['leasename'] = $row['tradingname'];
	$table .="<tr>";
	//$table .="<td align='center'><strong>".$i++."</td>";
	//$table .="<td><strong>".$row['shopcode']."</td>";
	//$table .="<td><strong>".$row['size']."</td>";
	//$table .="<td><strong>".$row['leasename']."</td>";
	//$table .="<td><strong>".$row['doc']."</td>";
	//$table .="<td><strong>".$row['age']."</td>";
	$tenantmasid = $row['tenantmasid'];
	
	$sql1 = "select a.tenantmasid,b.leasename,b.tradingname,e.shopcode,g.shortdesc as 'rentcycle',c.amount as rent,d.amount as sc,d.yearlyhike
		from trans_offerletter a 
		inner join mas_tenant b on b.tenantmasid = a.tenantmasid
		inner join trans_offerletter_rent c on c.offerlettermasid = a.offerlettermasid
		inner join trans_offerletter_sc d on d.offerlettermasid = a.offerlettermasid
		inner join mas_shop e on e.shopmasid = b.shopmasid
		inner join mas_age f on f.agemasid = b.agemasidlt
		inner join mas_age g on g.agemasid = b.agemasidrc 
		where a.tenantmasid not in (select tenantmasid from rec_tenant)
		and '$fromdt' between c.fromdate and c.todate
		and '$fromdt' between d.fromdate and d.todate
		and b.buildingmasid='$buildingmasid'  and b.tenantmasid='$tenantmasid' 
		group by a.offerlettermasid;";
	$result1 = mysql_query($sql1);	
	if($result1 !=null)
	{
	    $rcount1 = mysql_num_rows($result1);
	    if($rcount1 ==0) 
	    {
		$sql1 = "select a.tenantmasid,b.leasename,b.tradingname,e.shopcode,g.shortdesc as 'rentcycle',c.amount as rent,d.amount as sc,d.yearlyhike
		from trans_offerletter a 
		inner join rec_tenant b on b.tenantmasid = a.tenantmasid
		inner join trans_offerletter_rent c on c.offerlettermasid = a.offerlettermasid
		inner join trans_offerletter_sc d on d.offerlettermasid = a.offerlettermasid
		inner join mas_shop e on e.shopmasid = b.shopmasid
		inner join mas_age f on f.agemasid = b.agemasidlt
		inner join mas_age g on g.agemasid = b.agemasidrc		
		where '$fromdt' between d.fromdate and d.todate
		and '$fromdt' between c.fromdate and c.todate
		and b.buildingmasid='$buildingmasid'  and b.tenantmasid='$tenantmasid' 
		group by a.offerlettermasid;";
	    }
	}
	$result1 = mysql_query($sql1);
	if($result1 !=null)
	{
	   
	    $rcount2 = mysql_num_rows($result1);	    
	    while($row1 = mysql_fetch_assoc($result1))
	    {
		//$table .="<td><strong>$row1[rentcycle]</td>";
		
		if(strtolower($row1['rentcycle']) == "qtrly")
		{
		    $row1["rent"]   = round($row1["rent"] /3);
		    $row1["sc"]   = round($row1["sc"] /3);
		}else if(strtolower($row1['rentcycle']) == "hafly")
		{
		    $row1["rent"]   = round($row1["rent"] /6);
		    $row1["sc"]   = round($row1["sc"] /6);
		}else if(strtolower($row1['rentcycle']) == "yearly")
		{
		    $row1["rent"]   = round($row1["rent"] /12);
		    $row1["sc"]   = round($row1["sc"] /12);
		}
		//$table .="<td><strong>$row1[rent]</td>";
		$table .="<td><strong>$row1[sc]</td>";
		//$table .="<td><strong>$row1[yearlyhike]</td>";
	    }
	    if($rcount2 <=0)
	    {
		//$table .="<td><strong>$row1[rentcycle]</td>";
		//$table .="<td><strong>0</td>";
		$table .="<td><strong>0</td>";
		//$table .="<td><strong>0</td>";
	    }
	}	
    }
}
$table .= "</table></p>";
$custom = array('divContent'=>$table,'heading'=>$buildingname.' Service Charge for the period of '.$fromdt." to ".$todt,'s'=>'Success');
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