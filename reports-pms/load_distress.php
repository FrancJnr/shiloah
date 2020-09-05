<?php
include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";

if($load == "showdetails")
{   
    $sql = "select concat(b.leasename,' T/A ',b.tradingname,' - ',c.shopcode,' - ',c.size,' sqrft ') as leasename,buildingname,
            e.distressmasid,e.grouptenantmasid,
            date_format(e.createddate,'%d-%m-%Y') as createddate,
            e.graceperiod,e.paymentfor,
            e.subject,para1,para2,
            e.outstandingamt,
            date_format(e.expirydate,'%d-%m-%Y') as expirydate,            
            e.active
            from group_tenant_det a
            inner join mas_tenant b on b.tenantmasid = a.tenantmasid
            inner join mas_shop c on c.shopmasid = b.shopmasid
            inner join mas_building d on d.buildingmasid = c.buildingmasid
            inner join rpt_distress e on e.grouptenantmasid = a.grouptenantmasid
            where e.distressmasid =".$load= $_GET['distressmasid'];
}
else if($load == "showlist")
{
    $distressmasid = $_GET['distressmasid'];
    $table="<table class='table6'>";
    $table .="<tr><th colspan='5'>Distress Details</th></tr>";    
    $table .="<tr><th>Sno</th><th>Tenant</th><th>Building</th><th>Size</th><th>Shop</th>
              <th>Creadted Date</th><th>Outstanding KES</th><th>Expiry Date</th><th>User</th><th>View</th><th>Print</th></tr>";
    $sql = "select c.leasename,c.tradingname,d.distressmasid,d.grouptenantmasid,g.size,g.shopcode,h.buildingname,
            date_format(d.createddate,'%d-%m-%Y') as createddate,d.graceperiod,d.outstandingamt,date_format(d.expirydate,'%d-%m-%Y') as expirydate,d.active,d.modifiedby
            from group_tenant_mas a
            inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
            inner join mas_tenant c on c.tenantmasid = b.tenantmasid                                        
            inner join rpt_distress d on d.grouptenantmasid = a.grouptenantmasid
            inner join rpt_distress_det f on f.distressmasid = d.distressmasid
            inner join mas_shop g on g.shopmasid = c.shopmasid
            inner join mas_building h on h.buildingmasid = g.buildingmasid
            where c.active='1' and f.refdistressmasid = $distressmasid
            union
            select c.leasename,c.tradingname,d.distressmasid,d.grouptenantmasid,g.size,g.shopcode,h.buildingname,
            date_format(d.createddate,'%d-%m-%Y') as createddate,d.graceperiod,d.outstandingamt,date_format(d.expirydate,'%d-%m-%Y') as expirydate,d.active,d.modifiedby
            from group_tenant_mas a
            inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
            inner join rec_tenant c on c.tenantmasid = b.tenantmasid                                        
            inner join rpt_distress d on d.grouptenantmasid = a.grouptenantmasid
            inner join rpt_distress_det f on f.distressmasid = d.distressmasid
            inner join mas_shop g on g.shopmasid = c.shopmasid
            inner join mas_building h on h.buildingmasid = g.buildingmasid
            where c.active='1' and f.refdistressmasid = $distressmasid
            order by leasename;";
    $tr="";
    $result=mysql_query($sql);
    if($result != null) // if $result <> false
    {
        if (mysql_num_rows($result) > 0)
        {
            $i=1;
            while ($row = mysql_fetch_assoc($result))
            {									                    
                $tr.="<tr>";
                    $tr.="<td>".$i++."</td>";
                    $tr.="<td>".$row['leasename']."</td>";
                    $tr.="<td>".$row['buildingname']."</td>";
                    $tr.="<td>".$row['size']."</td>";
                    $tr.="<td>".$row['shopcode']."</td>";
                    $tr.="<td>".$row['createddate']."</td>";
                    $val = $row['outstandingamt'];
                    $tr.="<td>".number_format($val, 0, '.', ',')."</td>";
                    $tr.="<td>".$row['expirydate']."</td>";
                    $tr.="<td>".$row['modifiedby']."</td>";
                    $tr.="<td align='center'>
                            <button type='button' id=btnShow$i name='".$row['distressmasid']."' val='".$row['distressmasid']."'>Show</button>								
                        </td>";
                    $tr.="<td align='center'>
                            <button type='button' id=btnPrintDoc$i name='".$row['distressmasid']."' val='".$row['distressmasid']."'>Print</button>								
                        </td>";
                $tr.="</tr>";
            }
        }
    }
    $table.=$tr;
    $table .="<tr><td colspan='9'><button type='button' id='btnAddNew' name='$distressmasid' val='$distressmasid'>Add Distress</button></td></tr>";
    $table.="</table>";
    $custom = array('msg'=>$table,'s'=>"error");
    $response_array [] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
else if($load == "loadtenantdetails")
{
    $sql = "select concat(b.leasename,' T/A ',b.tradingname,' - (',c.shopcode,'-',c.size,')') as leasename,buildingname from group_tenant_det a
            inner join mas_tenant b on b.tenantmasid = a.tenantmasid
            inner join mas_shop c on c.shopmasid = b.shopmasid
            inner join mas_building d on d.buildingmasid = c.buildingmasid
            where a.grouptenantmasid ='96'
            union
            select concat(b.leasename,' T/A ',b.tradingname,' - (',c.shopcode,'-',c.size,')') as leasename,buildingname from group_tenant_det a
            inner join rec_tenant b on b.tenantmasid = a.tenantmasid
            inner join mas_shop c on c.shopmasid = b.shopmasid
            inner join mas_building d on d.buildingmasid = c.buildingmasid
            where a.grouptenantmasid ='96';";
}
    
    $result =  mysql_query($sql);
    
    if($result != null) 
    {
        while($obj = mysql_fetch_object($result))
        {
            $arr[] = $obj;
        }
        $custom = array('msg'=>"",'s'=>"Success"); 
        $response_array [] = $custom;
        echo '{
            "myResult":'.json_encode($arr).',
            "error":'.json_encode($response_array).
        '}';				
    }
    else
    {
        $custom = array('msg'=>mysql_error(),'s'=>$sql);
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
?>