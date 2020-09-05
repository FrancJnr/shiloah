<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    
	<title>i-Tax Report</title>

<script src="../js/jquery-2.1.4.min.js"></script>
<script src="../js/table2CSV.js"></script>
<script type="text/javascript" language="javascript">

function getCSVData(){
  var csv_value=$('#tableitax').table2CSV({delivery:'value'});
  $("#csv_text").val(csv_value);  
}
</script>

<style>
            
body{
  font:1.2em normal Arial,sans-serif;
  color:#34495E;
}

h1{
  text-align:center;
  text-transform:uppercase;
  letter-spacing:-2px;
  font-size:2.5em;
  margin:20px 0;
}

.dataManipDiv{
  width:99%;
  margin:auto;
}

#tableitax{
  border-collapse:collapse;
  width:99%;
}

#tableitax{
  border:2px solid #1ABC9C;
}

#tableitax thead{
  background:#1ABC9C;
}

.purple{
  border:2px solid #9B59B6;
}

.purple thead{
  background:#9B59B6;
}

thead{
  color:white;
}

th,td{
  text-align:center;
  padding:5px 0;
}

tbody tr:nth-child(even){
  background:#ECF0F1;
}

tbody tr:hover{
background:#BDC3C7;
  color:#FFFFFF;
}

.fixed{
  top:0;
  position:fixed;
  width:auto;
  display:none;
  border:none;
}

.scrollMore{
  margin-top:600px;
}

.up{
  cursor:pointer;
}   

table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>
</head>

<?php
include('../config.php');
session_start();
try{
//$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;$mnthlyrent=0;
//$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$buildingmasid = $_GET['buildingmasid'];
//$fromdate = explode(" ",$_GET['invdt']);
//$firstdate = $_GET['invdt'];


$buildingname = "";$rect_date="";$orig_cycle="";
$s = "select buildingname,companymasid,isvat from mas_building where buildingmasid =$buildingmasid";
$r = mysql_query($s);
 while($ro = mysql_fetch_assoc($r))
    {
        $buildingname = strtoupper($ro["buildingname"]);
	$building_companymasid = $ro['companymasid'];
	$isvat =$ro["isvat"];	
    }

$company = strtoupper($_SESSION["mycompany"]);
$companymasid = $_SESSION['mycompanymasid'];

                $pinno ="PIN NO: ";
                $vatno="VAT NO: ";
                $sqlpin = "select pin,vatno, companyname from mas_company where companymasid=$building_companymasid;";
                $resultpin = mysql_query($sqlpin);
                if($resultpin !=null)
                {
                    while($row = mysql_fetch_assoc($resultpin))
                    {
                        $pinno .=$row['pin'];
                        $vatno .=$row['vatno'];
                        $coname =$row['companyname'];
                    }
                }



$table ="<p class='printable'><table class='custom' id='tableitax' style='padding: 0px;font-size: 14px;font-family: Verdana, Arial, Helvetica, sans-serif;margin: 10px 0px 8px 0px; border-collapse: collapse;'>";
$table .="<center><thead><th colspan='16'>LEASE STATUS FOR ".strtoupper($buildingname)." OF ".strtoupper($coname)."</th></thead></center>";
$table .="<tr align='center' style='font-weight:bold;color:black;'>";
$table .="<td>Sr.No.</td>";
$table .="<td>Date</td>";
$table .="<td>Tenant's Name</td>";
$table .="<td>Offer Letter Execution</td>";
$table .="<td>Offer Letter Return</td>";
$table .="<td>Lease Prepared</td>";
$table .="<td>Lease to Tenant</td>";
$table .="<td>Lease to Landlord</td>";
$table .="<td>Lease to Bank</td>";
$table .="<td>S/Duty Asse.</td>";
$table .="<td>S/Duty Paid</td>";
$table .="<td>PIN/Reg Cert</td>";
$table .="<td>Payment Received</td>";
$table .="<td>Outstanding</td>";
$table .="<td>Status</td>";
$table .="<td>Remarks</td>";
$table .="<tr>";

        $sql="select a.*, b.leasename, b.tradingname, b.shopmasid, c.buildingmasid, 
            e.shortname,DATE_FORMAT(b.doc,'%d-%m-%Y') as doc, DATE_FORMAT(b.includedon,'%d-%m-%Y') 
            as doi, g.tenantmasid from trans_document_status a inner join group_tenant_mas g on 
            g.grouptenantmasid = a.grouptenantmasid inner join mas_tenant b on 
            b.tenantmasid = g.tenantmasid inner join mas_shop c on 
            c.shopmasid = b.shopmasid inner join mas_building e on 
            e.buildingmasid = c.buildingmasid where b.companymasid =$companymasid and e.buildingmasid= $buildingmasid group by a.grouptenantmasid ";
//       " update mas_tenant set shopoccupied = '1' ,pin='$pinno',
//						includedby='$includedby',includedon='$datetime'
//						where tenantmasid=".$tenantmasid ." and active ='1'";
//	$sql = "select a.grouptenantmasid , b.leasename ,b.tradingname, e.shortname,c.shopcode , c.size,
//		DATE_FORMAT(b.doc,'%d-%m-%Y') as doc,d.age,
//		DATE_FORMAT( DATE_ADD(DATE_ADD(b.doc,interval @t1:=d.age year),interval -1 day), '%d-%m-%Y' ) AS 'expdt',g.remarks
//		from 
//		rpt_offerletter a
//		inner join group_tenant_mas o on o.grouptenantmasid = a.grouptenantmasid
//		inner join mas_tenant b on b.tenantmasid = o.tenantmasid
//		inner join mas_shop c on c.shopmasid = b.shopmasid
//		inner join mas_age d on d.agemasid = b.agemasidlt
//		inner join mas_building e on e.buildingmasid = c.buildingmasid
//		inner join mas_tenant_cp f on f.tenantmasid = b.tenantmasid
//		left join trans_document_status g on g.grouptenantmasid= a.grouptenantmasid
//		where b.companymasid =$companymasid 
//		group by grouptenantmasid;";
	    
			$result=mysql_query($sql);
			
			if($result != null) // if $result <> false
			{
				if (mysql_num_rows($result) > 0)
				{
					$i=1;
					while ($row = mysql_fetch_assoc($result))
						{
						    $grouptenantmasid = $row['grouptenantmasid'];
						    
						     if($row['tradingname'] !=""){
						    $leasename= $row['leasename'] .= " T/A ".$row['tradingname'];						
                                                     }else{
                                                     $leasename= $row['leasename'];   
                                                    }
                                                    if($row['istenantpinno']==0){
                                                        $istenantpinno="No";
                                                    }else{
                                                         $istenantpinno="Yes";
                                                    }
                                                     $tr =  "<tr align='center'>
						     <td class='center'>".$i++."</td>
                                                     <td align='left'>".$row['doc']."</td>
						     <td align='left'>".$leasename."</td>
						     <td>".$row['offletttotenantdate']."</td>
						     <td>".$row['offlettretrundate']."</td>
                                                     <td>".$row['leasedate']."</td>
                                                     <td>".$row['leasetotenantdate']."</td>
                                                     <td>".$row['leasetolandlorddate']."</td>
                                                     <td>".$row['leasetobankdate']."</td>
                                                     <td>".$row['leasetodutyassdate']."</td>
						     <td>".$row['leasedutypaiddate']."</td>
                                                     <td>".$istenantpinno."</td>
                                                     <td></td>
                                                     <td></td>
                                                     <td>".$row['leasestatus']."</td>
						     <td align='justify'>".$row['remarks']."</td>";						     
//						     $tr .="<td align='center'>
//								<button type='button' id=btnEdit$i name='".$grouptenantmasid."'  val='".$grouptenantmasid."'>Edit</button>								
//							</td>";
						     
						     //echo $tr;
                                                     $table .=$tr;
						
      
                                                     
                                                     
                                                     
                                                }
				}
			}







}

catch (Exception $err)
{
    $custom = array(
                'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    print_r(mysql_error());
}
?>

<form action="getCSV.php" method ="post"> 
<input type="hidden" name="csv_text" id="csv_text">
<div id="tableholder"><?php  echo $table;?></div>
<input type="submit" value="Get CSV File" onclick="getCSVData()">
</form>
</body>
</html>