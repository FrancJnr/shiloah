<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";
$table = "mas_block";
$companymasid = $_SESSION['mycompanymasid'];
if($load == "loadBlock")
{
    $sql = "select * from $table where companymasid=$companymasid";
}
else if($load == "loadBuilding")
{
    $sql = "SELECT * FROM mas_building where companymasid=$companymasid";
}
else if($load == "details")
{
    $sql = "SELECT * FROM $table where blockmasid =".$load= $_GET['itemval']." and  companymasid=$companymasid";
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