<?php

include('../config.php');
session_start();
$response_array = array();
//$itemval = $_GET['itemval'];
$load= $_GET['item'];
$sql="";
$companymasid = $_SESSION['mycompanymasid'];
if($load == "loadBuilding")
{
    $sql = "SELECT * FROM mas_building where companymasid=$companymasid";
}
else if($load == "loadLicense")
{
    $sql = "select licensename,licensemasid,licensecode from mas_daily_license where editpermission =1";
}
else if($load == "loadLicenseDetails")
{
    //$sql = "select * from mas_daily_license where editpermission =1 and licensemasid= ".$itemval = $_GET['itemval'];
    $sql = "select * ,"
           . " DATE_FORMAT( fromdt, \"%d-%m-%Y\" ) as \"fromdt\" ,\n"
           . " DATE_FORMAT( todt, \"%d-%m-%Y\" ) as \"todt\"\n"
           . " FROM mas_daily_license  where editpermission =1 and licensemasid =".$load= $_GET['itemval'];
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
        $custom = array('msg'=>mysql_error(),'s'=>mysql_error());
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
?>