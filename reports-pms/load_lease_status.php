<?php
include('../config.php');
session_start();

try{
    $companymasid = $_SESSION['mycompanymasid'];
    $action = $_GET['item'];        
    if($action == "leasestatus")
    {   
        $buildingmasid = $_GET['buildingmasid'];
        $buildingname="";
	$sql = "select buildingname from mas_building where buildingmasid = $buildingmasid";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$buildingname = $row["buildingname"];
        
        $table="<p class='printable'><table class='table6'><tr><td colspan='30' style='font-weight:bold;'>List of tenants to raise LEASE: - ".strtoupper($buildingname)."</td></tr>";        
        $table.="<tr>";
            $table.="<th align='center'>Sno</th>";        
            $table.="<th>Tenant</th>";
            $table.="<th>Address</th>";
            $table.="<th>Code</th>";
            $table.="<th>Pin</th>";
            $table.="<th>Shop</th>";
            $table.="<th>Sqrft</th>";
            $table.="<th>Cycle</th>";
            $table.="<th>Doc</th>";        
        $table.="</tr>";    
        
        $sql ="select a.grouptenantmasid,b.leasename,b.tradingname,b.pin,c.tenancyrefcode,d.shopcode,d.size,e.age,date_format(b.doc,'%d-%m-%Y') as doc,renewalfromid,
                b.address1,b.city,b.state,b.pincode,b.country,b.poboxno,b.telephone1,b.telephone2,b.fax,b.emailid,b.website    
                from group_tenant_det a
                inner join mas_tenant b on b.tenantmasid = a.tenantmasid
                inner join mas_tenancyrefcode c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid =b.shopmasid
                inner join mas_age e on e.agemasid = b.agemasidrc
                where b.active='1' and shopoccupied='1' and b.buildingmasid ='$buildingmasid'
                union 
                select a.grouptenantmasid,b.leasename,b.tradingname,b.pin,c.tenancyrefcode,d.shopcode,d.size,e.age,date_format(b.doc,'%d-%m-%Y') as doc,renewalfromid,
                b.address1,b.city,b.state,b.pincode,b.country,b.poboxno,b.telephone1,b.telephone2,b.fax,b.emailid,b.website    
                from group_tenant_det a
                inner join rec_tenant b on b.tenantmasid = a.tenantmasid
                inner join mas_tenancyrefcode c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid =b.shopmasid
                inner join mas_age e on e.agemasid = b.agemasidrc
                where b.active='1' and shopoccupied='1' and b.buildingmasid ='$buildingmasid' order by leasename;";    
        $result = mysql_query($sql);
        if($result != null)
        {
            $i=0;
                while ($row = mysql_fetch_assoc($result))
                {
                    
		    $grouptenantmasid = $row["grouptenantmasid"];
		    $sqlchklease ="select grouptenantmasid from rpt_lease where grouptenantmasid = $grouptenantmasid;";
		    $resultchklease=mysql_query($sqlchklease);
		    if($resultchklease !=null)
		    {
			$rcountchklease = mysql_num_rows($resultchklease);
			if($rcountchklease <=0) 
			{
			    $i++;
			    $table.="<tr>";
			    $table.="<td align='center'>$i</td>";
			    if (($row['tradingname'])!="")
			    $row['leasename'] .=" T/A ".$row['tradingname'];
			    
			    if($row['renewalfromid'] >0)
			    $row['leasename'] .=" (Renewed)";
			    
			    $table.="<td>".$row['leasename']."</td>";
			    
			     $table.="<td>".
					    $row['address1'].",<br>".
					    "PO Box.No -".$row['poboxno'].",</br>".
					    $row['city']."-".$row['pincode'].",</br>PH: ".
					    $row['telephone1']."-".$row['telephone2'].",</br>EMAIL: ".
					    $row['emailid']
					    ."</td>";
			    if($row['tenancyrefcode']!="")
				$table.="<td>".$row['tenancyrefcode']."</td>";
			    else
				//$table.="<td style='background-color:red'>".$row['tenantmasid']."</td>";
				$table.="<td>--</td>";                   
			    $table.="<td>".$row['pin']."</td>";
			    $table.="<td>".$row['shopcode']."</td>";
			    $table.="<td>".$row['size']."</td>";
			    $table.="<td>".$row['age']."</td>";
			    $table.="<td>".$row['doc']."</td>";                    
			    $table.="</tr>";
			}
		    }
		    
		    
                }        
        }
        $table.="</table></p>";
        $custom = array('divContent'=> "<br>".$table,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
    }
}
catch (Exception $err)
{    
    $custom = array('divContent'=> "</br>Error: ".$err->getMessage().", Line No:".$err->getLine(),'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>
