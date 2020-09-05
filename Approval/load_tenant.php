<?php

include('../config.php');
$response_array = array();
session_start();
$load= $_GET['item'];
$sql="";
$table = "mas_tenant";
$companymasid = $_SESSION['mycompanymasid'];

////$custom = array('msg'=>$companymasid,'s'=>"error");
////   $response_array [] = $custom;
////   echo '{
////      "error":'.json_encode($response_array).
////   '}';
////   exit;

try{
    
if($load == "detailsTenant")
{
   $grouptenantmasid = $_GET['itemval'];
   $sql ="select a.grouptenantmasid , b.leasename,b.tradingname,j.buildingname,
      group_concat(c.shopcode,' , ',c.size,':') as premises,
      b.pincode,b.poboxno,b.city,
      DATE_FORMAT( b.doo, '%d-%m-%Y' ) AS doo,
      DATE_FORMAT( b.doc, '%d-%m-%Y' ) AS doc,    
      @t2:= DATE_ADD(b.doc,interval @t1:=g.age year) as a,									
      DATE_FORMAT( DATE_ADD(b.doc,interval @t1:=g.age year), '%d-%m-%Y' ) AS b,		   
      DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
      g.age AS term, g1.age AS period,i.cpname,
      DATE_FORMAT( k.nrodt, '%d-%m-%Y' ) AS nrodate,
      DATE_FORMAT( k.vacatingdt, '%d-%m-%Y' ) AS vacatingdate,
      DATE_FORMAT( k.modifieddatetime, '%d-%m-%Y,%H:%i:%s' ) AS opmoddate,
      DATE_FORMAT( m.modifieddatetime, '%d-%m-%Y,%H:%i:%s' ) AS acmoddate,
      DATE_FORMAT( m.chqdate, '%d-%m-%Y' ) AS acchqdate,
      k.*,m.*,o.tenancyrefcode
      from group_tenant_det a
      inner join mas_tenant b on b.tenantmasid = a.tenantmasid
      inner join mas_shop c on c.shopmasid = b.shopmasid
      inner join group_tenant_mas d on d.grouptenantmasid = a.grouptenantmasid
      inner join mas_block e on e.blockmasid =  c.blockmasid
      inner join mas_floor f on f.floormasid = c.floormasid
      inner join mas_building j on j.buildingmasid =c.buildingmasid
      
      inner join mas_age g ON g.agemasid = b.agemasidlt
      inner join mas_age g1 ON g1.agemasid = b.agemasidrc
      inner join mas_building h ON h.buildingmasid = c.buildingmasid
      inner join mas_tenant_cp i ON i.tenantmasid = b.tenantmasid
   
      left outer join trans_tenant_discharge_op k on k.grouptenantmasid = a.grouptenantmasid
      left outer join trans_tenant_discharge_ac m on m.grouptenantmasid = a.grouptenantmasid
      
      left outer join mas_tenancyrefcode o on o.tenantmasid = b.tenantmasid
      
      where a.grouptenantmasid =$grouptenantmasid and i.documentname='1' and c.companymasid=$companymasid order by b.leasename;";       
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
}
catch (Exception $err)
{
	$custom = array(
		    'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
		    's'=>'error');
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
}
?>