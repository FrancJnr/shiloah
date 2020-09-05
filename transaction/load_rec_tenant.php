<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";
$table = "mas_tenant";
$companymasid = $_SESSION['mycompanymasid'];

if($load == "loadTenant")
{   
    $sql = "select a.tenantmasid,a.leasename,b.shopcode,a.renewalfromid,a.tradingname from mas_tenant a
            inner join mas_shop b on b.shopmasid = a.shopmasid
            where a.companymasid=$companymasid and a.active ='1' order by a.leasename";
}
else if($load == "loadRecTenant")
{   
    $sql = "select a.tenantmasid,a.leasename,b.shopcode,a.renewalfromid,a.tradingname from mas_tenant a
            inner join mas_shop b on b.shopmasid = a.shopmasid
            where a.companymasid=$companymasid and a.active ='1' and
            a.tenantmasid not in (select tenantmasid from rec_tenant where active='1')order by a.leasename";
}
if($load == "editRecTenant")
{
    $sql = "select a.*,b.shopcode,b.size from rec_tenant a
            inner join mas_shop b on b.shopmasid = a.shopmasid
            where a.companymasid=$companymasid and a.active ='1' order by leasename";
}
else if($load == "loadTenantType")
{
    $sql = "SELECT * FROM mas_tenant_type";
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
else if($load == "loadFloorShop")
{
    $floormasid = $_GET['itemval'];
    $sql = "select a.shopmasid,a.shopcode \n"
            . "from mas_shop a\n"
            . "INNER JOIN mas_floor b ON a.floormasid = b.floormasid\n "
            . "where a.shopmasid not in (select shopmasid from mas_tenant)\n "
            . "AND a.floormasid = $floormasid "
            . "AND a.companymasid = $companymasid";
}
else if($load == "loadTenantBuilding")
{
    $sql = "select b.buildingmasid , b.buildingname\n"
    . "from mas_tenant a\n"
    . "inner join mas_building b on a.buildingmasid = b.buildingmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadTenantBlock")
{
    $sql = "select b.blockmasid , b.blockname\n"
    . "from mas_tenant a\n"
    . "inner join mas_block b on a.buildingmasid = b.buildingmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadTenantFloor")
{
    $sql = "select b.floormasid , b.floorname\n"
    . "from mas_tenant a\n"
    . "inner join mas_floor b on a.blockmasid = b.blockmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadTenantShop")
{
    $sql = "select b.shopmasid , b.shopcode\n"
    . "from mas_tenant a\n"
    . "inner join mas_shop b on a.shopmasid = b.shopmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";

 //. "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
 //   . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
}
else if($load == "details")
{
    $sql = "SELECT *,\n"
    . "DATE_FORMAT( doo, \"%d-%m-%Y\" ) as \"d1\" ,\n"
    . "DATE_FORMAT( doc, \"%d-%m-%Y\" ) as \"d2\"\n"
    . "FROM mas_tenant  where tenantmasid =".$load= $_GET['itemval']
    . " and  companymasid=$companymasid";
    //$sql .=" UNION select * from mas_tenant_cp where tenantmasid =".$load= $_GET['itemval'];
}
else if($load == "detailsedit")
{
    $sql = "SELECT *,\n"
    . "DATE_FORMAT( doo, \"%d-%m-%Y\" ) as \"d1\" ,\n"
    . "DATE_FORMAT( doc, \"%d-%m-%Y\" ) as \"d2\"\n"
    . "FROM rec_tenant  where tenantmasid =".$load= $_GET['itemval']
    . " and  companymasid=$companymasid";
    //$sql .=" UNION select * from mas_tenant_cp where tenantmasid =".$load= $_GET['itemval'];
}
else if($load == "detailsCP")
{
    $sql ="select * from mas_tenant_cp where tenantmasid =".$load= $_GET['itemval'];
    $result = mysql_query($sql);
    $i=1;
    $tbl="";
    if($result != null)
    {
        while($row = mysql_fetch_assoc($result))
            {
                $k="";
                $ts = "<td>";
                $ts .= "<select style='width: 150px;' name='cptypemasid1000$i'>";
                $sqlSelect = "select cptype, cptypemasid from mas_cptype";
                $resultSelect = mysql_query($sqlSelect);
                if($resultSelect != null)
                {
                        while($rowSelect = mysql_fetch_assoc($resultSelect))
                        {
                                if($rowSelect['cptypemasid'] == $row['cptypemasid'])
                                $ts.="<option value=".$rowSelect['cptypemasid']." selected>".$rowSelect['cptype']."</option>";
                                else
                                $ts.="<option value=".$rowSelect['cptypemasid'].">".$rowSelect['cptype']."</option>";
                        }
                }
                $ts .="</select></td>";
                if($row['documentname']=="0")
                $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' /></td>";
                else
                $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' checked/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpname1000$i' value='$row[cpname]'/></td>";
                $tbl .= $ts;
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpnid1000$i' value='$row[cpnid]'/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpmobile1000$i' value='$row[cpmobile]'/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cplandline1000$i' value='$row[cplandline]' /></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpemailid1000$i' value='$row[cpemailid]' /></td>";                
                $tbl .= "</tr>";
                $i++;
            }
    }
    $custom = array('msg'=>$tbl,'s'=>"Success"); 
    $response_array [] = $custom;
    echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
    exit;
}
else if($load == "detailseditcp")
{
    $sql ="select * from rec_tenant_cp where tenantmasid =".$load= $_GET['itemval'];
    $result = mysql_query($sql);
    $i=1;
    $tbl="";
    if($result != null)
    {
        while($row = mysql_fetch_assoc($result))
            {
                $k="";
                $ts = "<td>";
                $ts .= "<select style='width: 150px;' name='cptypemasid1000$i'>";
                $sqlSelect = "select cptype, cptypemasid from mas_cptype";
                $resultSelect = mysql_query($sqlSelect);
                if($resultSelect != null)
                {
                        while($rowSelect = mysql_fetch_assoc($resultSelect))
                        {
                                if($rowSelect['cptypemasid'] == $row['cptypemasid'])
                                $ts.="<option value=".$rowSelect['cptypemasid']." selected>".$rowSelect['cptype']."</option>";
                                else
                                $ts.="<option value=".$rowSelect['cptypemasid'].">".$rowSelect['cptype']."</option>";
                        }
                }
                $ts .="</select></td>";
                if($row['documentname']=="0")
                $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' /></td>";
                else
                $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' checked/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpname1000$i' value='$row[cpname]'/></td>";
                $tbl .= $ts;
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpnid1000$i' value='$row[cpnid]'/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpmobile1000$i' value='$row[cpmobile]'/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cplandline1000$i' value='$row[cplandline]' /></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpemailid1000$i' value='$row[cpemailid]' /></td>";                
                $tbl .= "</tr>";
                $i++;
            }
    }
    $custom = array('msg'=>$tbl,'s'=>"Success"); 
    $response_array [] = $custom;
    echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
    exit;
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