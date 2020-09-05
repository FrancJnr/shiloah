<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";

if($load == "invoicedescription")
{
    $sql = "select * from invoice_desc order by invoicedesc asc";
}
else if($load == "invoicedescdetails")
{
    $sql = "select * from invoice_desc where invoicedescmasid =".$load= $_GET['itemval'];
}
else if($load == "emaildescdetails")
{
    $sql = "select * from mas_email where id =".$load= $_GET['itemval'];
}
else if ($load == "emaildetails")
{
	$sql = "select * from mas_email ";
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