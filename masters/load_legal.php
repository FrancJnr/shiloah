<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";

if($load == "details")
{
    $sql = "SELECT n.cpname,a.leasename,a.tenantcode, a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
    . "FROM mas_tenant a\n"
    . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
    . "LEFT OUTER JOIN mas_tenant_cp n ON n.tenantmasid = a.tenantmasid\n"
    . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
    . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
    . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
    . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
    . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
    . "WHERE n.documentname='1' and a.tenantmasid =".$load= $_GET['itemval']
    . " and a.companymasid=$companymasid and a.active='1'";
    $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {                    
            $sql = "SELECT n.cpname,a.leasename,a.tenantcode, a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
            . "FROM rec_tenant a\n"
            . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
            . "LEFT OUTER JOIN rec_tenant_cp n ON n.rectenantmasid = a.rectenantmasid\n"
            . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
            . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
            . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
            . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
            . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
            . "WHERE n.documentname='1' and a.tenantmasid =".$load= $_GET['itemval']
            . " and a.companymasid=$companymasid and a.active='1'";
        }
    }
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