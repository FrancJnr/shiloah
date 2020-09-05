<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";

if($load == "load")
{
    $sql = "select * from mas_company order by companyname";
}
else if($load == "details")
{
    //$sql = "select * from mas_company where companymasid =".$load= $_GET['itemval'];
    $sql = "SELECT * ,\n"
    . "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
    . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
    . "FROM mas_company where companymasid =".$load= $_GET['itemval'];
}
else if($load == "getCode")
{
    
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