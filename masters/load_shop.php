<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";
$table = "mas_shop";
$companymasid = $_SESSION['mycompanymasid'];

if($load == "loadShop")
{
    $sql = "select * from $table where companymasid=$companymasid";
}
else if($load == "loadBlock")
{
    $sql = "select * from mas_block where companymasid=$companymasid";
}
else if($load == "loadBuilding")
{
    $sql = "SELECT * FROM mas_building where companymasid=$companymasid";
}
else if($load == "loadBuildingBlock")
{
    $buildingmasid = $_GET['itemval'];
    $sql = "SELECT b.blockmasid,b.blockname, a.buildingname\n"
        . "FROM mas_building a\n"
        . "INNER JOIN mas_block b ON a.buildingmasid = b.buildingmasid\n"
        . "WHERE b.companymasid = $companymasid\n"
        . "AND b.buildingmasid = $buildingmasid";
}
else if($load == "loadBlockFloor")
{
    $blockmasid = $_GET['itemval'];
    $sql = "SELECT a.floormasid,a.floorname \n"
        . "FROM mas_floor a\n"
        . "INNER JOIN mas_block b ON a.blockmasid = b.blockmasid\n"
        . "WHERE a.companymasid = $companymasid\n"
        . "AND a.blockmasid = $blockmasid";
}
else if($load == "details")
{
    $sql = "SELECT * FROM $table where shopmasid =".$load= $_GET['itemval']." and  companymasid=$companymasid";
}
else if($load == "loadFloorBuilding")
{
    $sql = "select a.buildingmasid , b.buildingname\n"
    . "from mas_floor a\n"
    . "inner join mas_building b on a.buildingmasid = b.buildingmasid\n"
    . "where a.floormasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadShopBlock")
{
    $sql = "select b.blockmasid , b.blockname\n"
    . "from mas_shop a\n"
    . "inner join mas_block b on a.blockmasid = b.blockmasid\n"
    . "where a.shopmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadShopFloor")
{
    $sql = "select b.floormasid , b.floorname\n"
    . "from mas_shop a\n"
    . "inner join mas_floor b on a.floormasid = b.floormasid\n"
    . "where a.shopmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
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