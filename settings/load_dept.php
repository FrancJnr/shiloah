<?php

include('../config.php');
session_start();

$response_array = array();
$companymasid = $_SESSION['mycompanymasid'];
$load= $_GET['item'];
$custom = array('msg'=>"Success",'q'=>$load);
$sql="";

if($load == "dept")
{
   $sql = "select * from mas_department order by name ASC";  
}
$result =  mysql_query($sql);
    
    if($result != null) 
    {
        while($obj = mysql_fetch_object($result))
        {
            $arr[] = $obj;
        }
        $response_array [] = $custom;
        echo '{
            "myResult":'.json_encode($arr).',
            "error":'.json_encode($response_array).
        '}';				
    }
    else
    {
        $custom = array('msg'=>"No recodrs available");
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
?>
   