<?php
include('../config.php');
session_start();
$response_array = array();
$load= $_GET['item'];
$sql="";
$companymasid = $_SESSION['mycompanymasid'];
		
if($load=="partial")
{    
   // $sql ="select buildingmasid,buildingname from mas_building order by buildingmasid;";
   $sql = "select buildingmasid, buildingname from mas_building WHERE companymasid =".$companymasid." order by buildingname asc";
    $result = mysql_query($sql);
    if($result != null)
    {
        $table ="<table class='table6'>";
        while($row = mysql_fetch_assoc($result))
        {            
            $table .="<tr><td>".strtoupper($row['buildingname'])."</td></tr>";
            $table .="<tr><td>";
            $buildingmasid = $row['buildingmasid'];
            $sql1 ="select c.leasename,c.tradingname,d.shopcode,d.size,e.buildingname,
                    a.outstandingpayment,a.asc,a.baltilldt,a.securitydeposit,a.payorrefund,a.acremarks from trans_tenant_discharge_ac a
                    inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                    inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                    inner join mas_shop d on d.shopmasid = c.shopmasid
                    inner join mas_building e on e.buildingmasid = d.buildingmasid
                    where a.acdischargetype='0' and d.buildingmasid ='$buildingmasid'
                    order by e.buildingmasid;";
            $result1 = mysql_query($sql1);
            if($result1 != null)
            {
                $table .="<table>";
                $table .="<tr>";
                $table .="<th>Sno</th><th>Tenant</th><th>Shop</th><th>Sqrft</th><th>Outstanding</th><th>ASC</th><th>Balance</th>
                            <th>Security Deposit</th><th>Collec/Refund</th><th>Remarks</th>";
                $table .="</tr>";
                $sno=1;
                while($row1 = mysql_fetch_assoc($result1))
                {
                    
                    if ($row1['tradingname'] !="")
                    $row1['leasename'] .= " T/A ".$row1['tradingname'];
                    
                    $table .="<tr>";
                        $table .="<td>$sno</td>";
                        $table .="<td>".strtoupper($row1['leasename'])."</td>";
                        $table .="<td>".strtoupper($row1['shopcode'])."</td>";
                        $table .="<td>".strtoupper($row1['size'])."</td>";
                        $table .="<td>".strtoupper($row1['outstandingpayment'])."</td>";
                        $table .="<td>".strtoupper($row1['asc'])."</td>";
                        $table .="<td>".strtoupper($row1['baltilldt'])."</td>";
                        $table .="<td>".strtoupper($row1['securitydeposit'])."</td>";
                        $table .="<td>".strtoupper($row1['payorrefund'])."</td>";
                        $table .="<td>".strtoupper($row1['acremarks'])."</td>";
                    $table .="</tr>";
                    $sno++;
                }                
                $table .="</table>";
            }
            $table .="</tr></td>";            
        }
        $table .="</table>";
        $custom = array('msg'=>$table,'s'=>"Success");
        $response_array [] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit;
    }    
}
?>