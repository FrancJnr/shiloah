<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];

$sql="";
$table = "mas_tenant";
$table_enquiry="mas_enquiry_updated";
$companymasid = $_SESSION['mycompanymasid'];

if($load == "loadTenant")
{
     $sql = "select distinct 
            a.tenantmasid,a.tenanttypemasid,a.salutation, a.leasename, a.tradingname, a.tenantcode, a.companymasid, a.buildingmasid, a.blockmasid,
            a.floormasid, a.shopmasid, a.shoptypemasid, a.orgtypemasid, a.nob, a.agemasidlt, a.agemasidrc, a.agemasidcp, a.creditlimit,
            a.latefeeinterest, a.doo, a.doc, a.pin, a.regno, a.address1, a.address2, a.city, a.state, a.pincode, a.country, a.poboxno,
            a.telephone1, a.telephone2, a.fax, a.emailid, a.website, a.remarks, a.createdby, a.createddatetime, a.modifiedby, a.modifieddatetime,
            a.active
            from mas_tenant a where a.companymasid=$companymasid and a.active ='1'
            and a.tenantmasid not in (select c.tenantmasid from mas_tenant c inner join group_tenant_mas b on b.tenantmasid=c.tenantmasid where b.grouptenantmasid not in (select grouptenantmasid from waiting_list)  and c.tenantmasid in(select tenantmasid from trans_offerletter)) order by leasename asc";
  
     
     
     
}else if($load == "loadTenantEdit")
{
    $sql = "select 
            a.tenantmasid,a.tenanttypemasid,a.salutation, a.leasename, a.tradingname, a.tenantcode, a.companymasid, a.buildingmasid, a.blockmasid,
            a.floormasid, a.shopmasid, a.shoptypemasid, a.orgtypemasid, a.nob, a.agemasidlt, a.agemasidrc, a.agemasidcp, a.creditlimit,
            a.latefeeinterest, a.doo, a.doc, a.pin, a.regno, a.address1, a.address2, a.city, a.state, a.pincode, a.country, a.poboxno,
            a.telephone1, a.telephone2, a.fax, a.emailid, a.website, a.remarks, a.createdby, a.createddatetime, a.modifiedby, a.modifieddatetime,
            a.active
            from mas_tenant a where a.companymasid=$companymasid and a.active ='1' and a.tenantmasid not in (select c.tenantmasid from mas_tenant c inner join group_tenant_mas b on b.tenantmasid=c.tenantmasid where b.grouptenantmasid not in (select grouptenantmasid from waiting_list)  and c.tenantmasid in(select tenantmasid from trans_offerletter)) order by a.leasename asc";
     
     
}
else if($load == "loadTenantType")
{
    $sql = "SELECT * FROM mas_tenant_type";
}
else if($load == "loadBuilding")
{
    $sql = "SELECT * FROM mas_building where companymasid=$companymasid";
}
else if($load=="loadBuildingFromEnquiry"){
   $sql = "SELECT a.buildingname, b.buildingmasid FROM mas_building a\n"
            ."INNER JOIN mas_enquiry_updated b\n"
            ."ON a.buildingmasid=b.buildingmasid WHERE b.enquirymasid=".$load= $_GET['enquirymasid']." AND b.companymasid=".$companymasid;
    
//    $sql = "SELECT a*, b.buildingmasid FROM  mas_building a\n"
//    ."INNER JOIN mas_enquiry_updated b ON a.buildingmasid=b.buildingmasid\n"
//    . "WHERE b.enquirymasid=".$load= $_GET['enquirymasid']." AND a.companymasid=$companymasid order by a.buildingname asc";
          //  ."WHERE a.companymasid=$companymasid and b.enquirymasid='".$_GET['enquirymasid']."'"; 
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
    //$sql = "select a.shopmasid,a.shopcode \n"
    //        . "from mas_shop a\n"
    //        . "INNER JOIN mas_floor b ON a.floormasid = b.floormasid\n "
    //        . "where a.shopmasid not in (select shopmasid from mas_tenant where active='1')\n "
    //        . "AND a.floormasid = $floormasid "
    //        . "AND a.companymasid = $companymasid";
    $sql = "select a.shopmasid,a.shopcode, a.size, a.active \n"
            . "from mas_shop a\n"
            . "INNER JOIN mas_floor b ON a.floormasid = b.floormasid\n "
            . "where a.floormasid = $floormasid";
}
else if($load == "loadShopSize")
{
    $shopmasid = $_GET['itemval'];

    $sql = "select size from mas_shop where shopmasid = $shopmasid";
}
else if($load == "loadTenantBuilding")
{
    $sql = "select b.buildingmasid , b.buildingname\n"
    . "from mas_tenant a\n"
    . "inner join mas_building b on a.buildingmasid = b.buildingmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid order by buildingname asc";
}
else if($load == "loadTenantBlock")
{
    $sql = "select b.blockmasid , b.blockname\n"
    . "from mas_tenant a\n"
    . "inner join mas_block b on a.buildingmasid = b.buildingmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid order by blockname asc";
}
//loadTenantBlockFromEnquiry
else if($load == "loadTenantBlockFromEnquiry")
{
    $sql = "select b.blockmasid, b.blockname\n"
    . "from mas_enquiry_updated a\n"
    . "inner join mas_block b on a.blockmasid = b.blockmasid\n"
    . "where a.enquirymasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid order by b.blockname asc";
}

else if($load == "loadTenantFloor")
{
   $sql = "select b.floormasid , b.floorname\n"
    . "from mas_tenant a\n"
    . "inner join mas_floor b on a.blockmasid = b.blockmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid order by floorname asc";  
}
//loadTenantFloorFromEnquiry
else if($load == "loadTenantFloorFromEnquiry")
{
    $sql = "select a.floormasid, b.floorname\n"
    . "from mas_enquiry_updated a\n"
    . "inner join mas_floor b on a.floormasid = b.floormasid\n"
    . "where a.enquirymasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid order by floorname asc";
   
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
//else if($load == "loadShop")
//{
//    $sql = "select b.shopmasid , b.shopcode\n"
//    . "from mas_tenant a\n"
//    . "inner join mas_shop b on a.shopmasid = b.shopmasid\n"
//    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
//
// //. "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
// //   . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
//}
//loadTenantShopFromEnquiry
else if($load == "loadTenantShopFromEnquiry")
{
//    $sql = "select b.shopmasid , b.shopcode\n"
//    . "from mas_enquiry_updated a\n"
//    . "inner join mas_shop b on a.shopmasid = b.shopmasid\n"
//    . "where a.enquirymasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";

  $sql = "select shopmasid , shopcode from mas_shop where companymasid=".$companymasid."\n"
          . " and blockmasid=".$_GET['blockmasid']." and floormasid=".$_GET['floormasid']."\n"
          ." and buildingmasid=".$_GET['buildingmasid'];
    

//. "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
 //   . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
}

else if($load == "loadShopTypeFromEnquiry")
{
  $sql = "select b.shoptype, b.shoptypemasid from mas_enquiry_updated a inner join mas_shoptype b on a.shoptypemasid=b.shoptypemasid where b.active =1 and a.enquirymasid=".$_GET['tenantmasid']." order by b.shoptype asc";
}
else if($load == "loadShopCodeFromEnquiry")
{
  $sql = "select a.shopmasid, b.shopcode from mas_enquiry_updated a inner join mas_shop b on a.shopmasid=b.shopmasid where a.enquirymasid=".$_GET['tenantmasid']." order by b.shopcode asc";
}
//loadshoptype
else if($load == "loadShopType")
{
 $sql = "select shoptype, shoptypemasid from mas_shoptype where active =1 order by shoptype asc";
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
else if($load == "detailsfromenquiry")
{
    $sql = "SELECT a.*, b.age, b.agemasid \n"
    . "FROM mas_enquiry_updated a \n"
    . "inner join mas_age b on a.agemasidlt = b.agemasid \n"
    . " where enquirymasid =".$load= $_GET['itemval']
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
               // if($row['documentname']=="0")
               // $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' /></td>";
              //  else
                $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' checked/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpname1000$i' value='$row[cpname]'/></td>";
                $tbl .= $ts;
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpnid1000$i' value='$row[cpnid]'/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpmobile1000$i' value='$row[cpmobile]'/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cplandline1000$i' value='$row[cplandline]' /></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpemailid1000$i' value='$row[cpemailid]' /></td>";
                $tbl .= "<td><button type='button' class='remove'>Remove</button></td>";
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
else if($load == "detailsCPfromEnquiry")
{
    $sql ="select * from mas_enquiry_updated where enquirymasid =".$load= $_GET['itemval'];
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
                if($row['cpname']=="0")
                $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' /></td>";
                else
                $tbl .= "<tr><td><input type='radio' style='width: 150px;' name='documentname' checked/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpname1000$i' value='$row[cpname]'/></td>";
                $tbl .= $ts;
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpnid1000$i' value=''/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpmobile1000$i' value='$row[mobile]'/></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cplandline1000$i' value='$row[telephone]' /></td>";
                $tbl .= "<td><input type='text' style='width: 150px;' name='cpemailid1000$i' value='$row[emailid]' /></td>";
                $tbl .= "<td><button type='button' class='remove'>Remove</button></td>";
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
            $custom = array('msg'=>$sql,'s'=>"No Tenants for editing");
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