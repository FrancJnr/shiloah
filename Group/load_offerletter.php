<?php
include('../config.php');
session_start();
$response_array = array();
$load= $_GET['item'];
$sql="";
$companymasid = $_SESSION['mycompanymasid'];
if($load == "loadTenantDraft")
{    
    $sql = " select a.offerlettermasid,a.tenantmasid,b.leasename,b.tradingname,b.renewalfromid,c.shopcode from trans_offerletter a
            inner join mas_tenant b on b.tenantmasid =  a.tenantmasid
            inner join mas_shop c on c.shopmasid = b.shopmasid
            where
            b.tenantmasid not in (select tenantmasid from group_tenant_det)  
            and a.companymasid=$companymasid and a.editpermission ='1'   and b.active ='1'
            
            union
            
            select a1.offerlettermasid,a1.tenantmasid,b1.leasename,b1.tradingname,b1.renewalfromid,c1.shopcode from trans_offerletter a1
            inner join rec_tenant b1 on b1.tenantmasid =  a1.tenantmasid
            inner join mas_shop c1 on c1.shopmasid = b1.shopmasid
            where
            b1.tenantmasid not in (select tenantmasid from group_tenant_det)  
            and a1.companymasid=$companymasid and a1.editpermission ='1'  and b1.active ='1'
            
            order by leasename";
}
else if($load == "loadTenantFinalized")
{
    $sql = "select * from mas_tenant where companymasid=$companymasid  and tenantmasid in
    (select tenantmasid from trans_offerletter where editpermission=0) order by leasename";
}
else if($load == "grouptenant")
{   
    $buildingshortname = $_GET['buildingshortname'];
    $id = $_GET['itemval'];
    
    $sql = "select a.tenantmasid,a.active,a.leasename,a.tradingname,a.renewalfromid,a.tenantcode,a.buildingmasid,b.shopcode,b.size from mas_tenant a
            inner join mas_shop b on b.shopmasid = a.shopmasid 
            where a.leasename in (select c.leasename from mas_tenant c where c.tenantmasid=$id) 
            and a.tenantmasid in (select d.tenantmasid from trans_offerletter d where d.editpermission=1) 
            and a.tenantmasid not in (select tenantmasid from group_tenant_det) 
              and a.buildingmasid in (select e.buildingmasid from mas_building e where e.buildingmasid=a.buildingmasid)
            and a.companymasid=$companymasid and a.active='1'

            union
            
            select a1.tenantmasid,a1.active,a1.leasename,a1.tradingname,a1.renewalfromid,a1.tenantcode,a1.buildingmasid,b1.shopcode,b1.size from rec_tenant a1
            inner join mas_shop b1 on b1.shopmasid = a1.shopmasid 
            where a1.leasename in (select c1.leasename from mas_tenant c1 where c1.tenantmasid=$id) 
            and a1.tenantmasid in (select d1.tenantmasid from trans_offerletter d1 where d1.editpermission=1) 
            and a1.tenantmasid not in (select tenantmasid from group_tenant_det) 
            and a1.buildingmasid in (select e1.buildingmasid from mas_building e1 where e1.buildingmasid=a1.buildingmasid)
            and a1.companymasid=$companymasid and a1.active='1'
            
            order by leasename";
            
        //    
        //$custom = array('msg'=>$sql,'s'=>"Success"); 
        //$response_array [] = $custom;
        //echo '{
        //    "error":'.json_encode($response_array).
        //'}';
        //exit;
}
else if($load == "loadgrouptenant")
{        
    $sql= "select c.leasename ,c.tradingname ,c.renewalfromid,d.shopcode,d.size, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            where c.companymasid=$companymasid and c.active='1'
            union
            select c1.leasename ,c1.tradingname ,c1.renewalfromid,d1.shopcode,d1.size, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid
            where c1.companymasid=$companymasid and c1.active='1'
            order by leasename ;";
}
else if($load == "grouptenantlist")
{        
    $id = $_GET['itemval'];
    
    $sql= " select b.leasename,b.active,a.tenantmasid,c.shopcode,c.size from group_tenant_det a
            inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
            inner join mas_shop c on  c.shopmasid = b.shopmasid
            where b.active='1' and a.grouptenantmasid = $id
            union
            select b1.leasename,b1.active,a1.tenantmasid,c1.shopcode,c1.size from group_tenant_det a1
            inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
            inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
            where b1.active='1' and a1.grouptenantmasid = $id
            ";
}

$result =  mysql_query($sql);    
if($result != null) 
{
    $cnt = mysql_num_rows($result);
    if($cnt > 0)
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
        $custom = array('msg'=>$sql,'s'=>$sql);
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
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