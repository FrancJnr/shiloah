<?php
include('../config.php');
session_start();
$response_array = array();

$load = $_GET['item'];
$buildingmasid = $_GET['buildingmasid'];
$sql="";
if($load == "loadtenant")
{
    $sql = "select b.leasename,b.tradingname,c.shopcode,c.size,a.grouptenantmasid
            from group_tenant_det a
            inner join mas_tenant b on b.tenantmasid = a.tenantmasid
            inner join mas_shop c on c.shopmasid = b.shopmasid
            inner join mas_building d on d.buildingmasid = c.buildingmasid            
            where b.buildingmasid ='$buildingmasid' and b.active='1'
            union
            select b.leasename,b.tradingname,c.shopcode,c.size,a.grouptenantmasid
            from group_tenant_det a
            inner join rec_tenant b on b.tenantmasid = a.tenantmasid
            inner join mas_shop c on c.shopmasid = b.shopmasid
            inner join mas_building d on d.buildingmasid = c.buildingmasid            
            where b.buildingmasid ='$buildingmasid' and b.active='1' order by leasename,tradingname;";
    //$custom = array('msg'=>$sql,'s'=>"Success");
    //$response_array [] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
    //exit;
}
else if($load == "ageDetails")
{
    $sql = "select * from mas_age where agemasid =".$load= $_GET['itemval'];
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