<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";

if($load == "loadLedger")
{
    $sql = "select * from mas_ledger order by ledger asc";
}
else if($load == "ledgerDetails")
{
    $sql = "select * from mas_ledger where ledgermasid =".$load= $_GET['itemval'];
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