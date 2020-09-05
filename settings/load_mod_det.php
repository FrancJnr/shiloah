<?php

include('../config.php');
session_start();
$response_array = array();
$load= $_GET['item'];

if($load == "loadModuleDet")
{    
    $sql = "select a.moduledetmasid,c.modulename,b.moduleheader,a.filename,a.filepath from mas_module_det a
            inner join mas_module_header b on b.moduleheadermasid = a.moduleheadermasid
            inner join mas_module c on c.modulemasid = a.modulemasid
            where c.active='1';";
}
else if($load == "loadModuleDetails")
{    
    $sql = "select c.modulemasid,b.moduleheadermasid,a.filename,a.filepath from mas_module_det a
            inner join mas_module_header b on b.moduleheadermasid = a.moduleheadermasid
            inner join mas_module c on c.modulemasid = a.modulemasid
            where c.active='1' and a.moduledetmasid = ".$load= $_GET['itemval'].";";
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