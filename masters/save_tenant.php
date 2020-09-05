<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$active = 0;
$leasename = $_GET['leasename'];
$pin = $_GET['pin'];
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid =$_GET['buildingmasid'];
$shopmasid= $_GET['shopmasid'];
//echo "shop mas id".$shopmasid;
$table = "mas_tenant";
if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
//print_r($_GET);
$str = strtoupper(substr($leasename,0,2));
$m="";
$sqlAutoNo = "SELECT tenantcode FROM ".$table." WHERE tenantcode LIKE '$str%' ORDER BY tenantcode DESC LIMIT 1";
$result=mysql_query($sqlAutoNo);
if($result != null)
{

    $row = mysql_fetch_array($result);
    $length = strlen($row['tenantcode']);
    $cstr =  (int)substr($row['tenantcode'],2,$length) + 1;
    $k = (int)strlen($cstr);
    if($k<=3)
    {
        $k =(int)4; // length of code starts from 5 digist after string E.g. GV00001
    }
    $codeno =str_pad($cstr,$k,"0",STR_PAD_LEFT);
    $tenantcode=trim($str).$codeno;
    
    $sqlbuildingshortname = "SELECT shortname FROM mas_building WHERE buildingmasid = $buildingmasid";
    $res = mysql_query($sqlbuildingshortname);
    if($res != null)
    {
	$row = mysql_fetch_array($res);
	$buildingshortname = strtoupper($row['shortname']);
    }
    $tenantcode .="-".$buildingshortname;
}

if($action == "Save"){
	
       /* $sql="SELECT leasename, tradingname FROM ".$table." WHERE  buildingmasid = $buildingmasid AND leasename = ".$leasename." or tradingname=".$leasename;
       //$sql="SELECT * FROM ".$table." WHERE shopmasid = ".$shopmasid;
       
       $resultis = mysql_query($sql);
 
        if($resultis == true)
        { 
         // $m="";
//            $row = mysql_fetch_array($resultis);
//           if($_GET['leasename']==$row['leasename']){
//          //$m = "That shop is already occupied by ".$row['leasename'];
//           
               $msg = "There is already a tenant with that lease name";
//           }
          //echo count($_GET)."  <br> ";
        
         $m .=$msg;  
           
     //   }else{ */
	 
	   $sqlin="SELECT leasename FROM ".$table." WHERE  buildingmasid = $buildingmasid AND leasename = ".$leasename." and shopmasid=".$shopmasid;
       $resultis = mysql_query($sqlin);
 
        if($resultis == true||$resultis!=null)
        { 
        
            $row = mysql_fetch_array($resultis);
           if($_GET['leasename']==$row['leasename']){
              //$m = "That shop is already occupied by ".$row['leasename'];
           
               $msg = "There is already a tenant with that leasename assigned to that shop!";
               $m .=$msg; 
			   $custom = array('msg'=>$m,'s'=>"Success");   
			           $response_array[] = $custom;
        echo '{
                "error":'.json_encode($response_array).
            '}';
               return;
               }  
        }
	$createdby = $_SESSION['myusername'] ;
        $i=0;
        $key ="";
        foreach($_GET as $key=>$val) {
            if($i > 2)
            {
                if($key == "doo")
                {    
                    $cols[] = "doo";
                    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                    //break 1;
                }
                else if($key == "doc")
                {    
                    $cols[] = "doc";
					$vals[] = "'".date('Y-m-d', strtotime($val))."'";
                    //break 1;
                } 
                else if($key == "active")
                {    
                    $cols[] = "active";
                    $vals[] = "1";
		    break;
                }
                else if($key=="salutation"&&$val=="Other.")
                {  
//                    $key="salutation";
//                    $cols[] = "".$key."";
//                    $vals[] = "'".$val."'";
//
//                    
                    break;
                }else if($key=="othersalutation"&&$val=="Other.")
                {  
                    $key="salutation";
                    $cols[] = "".$key."";
                    $vals[] = "'".$val."'";
                
                    break;
                }
                else
                {
                    $cols[] = "".$key."";
                    $vals[] = "'".$val."'";
                }                
            }
            if($i == 3)
            {
                $cols[] = "tenantcode";
                $vals[] = "'".$tenantcode."'";
            }
            
            $i++;
        }
        if(!isset($_GET['active'])) //if not checked it wont appear in the $_GET array
        {
            $cols[] = "active";
            $vals[] = "0";
        }
	$cols[] = "companymasid";
        $vals[] = "'".$companymasid."'";
        $cols[] = "createdby";
        $vals[] = "'".$createdby."'";
        $cols[] = "createddatetime";
        $vals[] = "'".$datetime."'";
        $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
	$iid ="0";
	// Insert into table main
	mysql_query($sql);
	$iid = mysql_insert_id();
	$mastableid ="tenantmasid";
	$table_det="mas_tenant_cp";
	$cols="";
	$vals="";
	$sqlDet="";
    	$i=0;$j=1;$a=1;$b=1;$c=1;$d=1;
	
	foreach($_GET as $key=>$val)
	{
	   
	    //if($i>=43)
            if($i>=36)
	    {
	    $key = preg_replace('/[^a-z]/i', '', $key);
	    if($key =="cpname")
	    {
		
                $cols[] = "".$key."";		
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cptypemasid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }else if($key == "othercptypemasid")
            {    
                $sql="INSERT INTO `mas_cptype` `cptype` VALUES ".$val;
                mysql_query($sql);
	        $val = mysql_insert_id();
                $cols[] = "cptypemasid";
                $vals[] = "'".$val."'";

            }
	    elseif($key =="cpnid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cpmobile")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cplandline")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	     elseif($key =="documentname")
	    {
		$cols[] = "".$key."";
		$vals[] = "'1'";
	    }
	     elseif($key =="cpemailid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
		$cols[] = $mastableid;
		$vals[] =  "'".$iid."'";
		$sqlDet .= 'INSERT INTO `'.$table_det.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')'.";";
		$cols="";
		$vals="";
	    }
		
	    }
	    $i++;
	}
	$sqlExec = explode(";",$sqlDet);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		$result = mysql_query($sqlExec[$i]); //trans_offerletter_rent
	     }
	}

	$m .="Data Saved Successfully";
      // }
}
else if($action == "Update"){
	$modifiedby = $_SESSION['myusername'];
	$tenantmasid = $_GET['tenantmasid'];
	$where ="tenantmasid ='$tenantmasid'";
	
	$grouptenantmasid =0;
	$sql_group = "select grouptenantmasid from group_tenant_det where tenantmasid = '$tenantmasid';";
	$result_group=mysql_query($sql_group);
	while ($row_group = mysql_fetch_assoc($result_group))
	{
	    $grouptenantmasid = $row_group['grouptenantmasid'];
	}
	
	////delete offerletter transaction of this tenant	cascade delete performed in child tables.
	mysql_query("delete from trans_offerletter where tenantmasid =$tenantmasid");
	////delete rect offerletter transaction of this tenant	cascade delete performed in child tables.
	mysql_query("delete from rect_trans_offerletter where tenantmasid =$tenantmasid");	
	////delete offerletter document of this tenant
	mysql_query("delete from rpt_offerletter where grouptenantmasid =$grouptenantmasid");
	////delete  document status this tenant
	mysql_query("delete from trans_document_status where grouptenantmasid =$grouptenantmasid");
	////delete lease document of this tenant 
	mysql_query("delete from rpt_lease where grouptenantmasid =$grouptenantmasid");
	////delete rect lease document of this tenant 
	mysql_query("delete from rpt_rect_lease where grouptenantmasid =$grouptenantmasid");
	////delete lease document of this tenant 
	mysql_query("delete from rpt_simple_agreement where grouptenantmasid =$grouptenantmasid");
	////delete tenant group if any 
	//mysql_query("delete from group_tenant_mas where tenantmasid =$tenantmasid");
        $j=0;
        foreach($_GET as $c => $v)
        {
                if($j > 2)
                {
                    if($c == "doo")
                    {    
                        $c ="doo";
                        $v = date('Y-m-d', strtotime($v));
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
                    else if($c == "doc")
                    {    
                        $c ="doc";
						$v = date('Y-m-d', strtotime($v));
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
		    else if($c == "remarks")
                    {    
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			break;
                    }
                    else
                    {
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
                }
            $j++;
        }
        if(!isset($_GET['active'])) //if not checked it wont appear in the $_GET array
        {
            $c ="active";
            $v = "0";
            $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        }
	else
	{
	    $c ="active";
            $v = "1";
            $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	}
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
	$result = mysql_query($sql);
	$key="";
	$val="";
	$i=0;
	$sqlDet="";
	$table_det ="mas_tenant_cp";
	$mastableid="tenantmasid";
        foreach($_GET as $key=>$val)
	{
	   
	    if($i>41)
	    {
	    $key = preg_replace('/[^a-z]/i', '', $key);
	   
	    if($key =="cpname")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cptypemasid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cpnid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cpmobile")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	     elseif($key =="cplandline")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="documentname")
	    {
		$cols[] = "".$key."";
		$vals[] = "'1'";
	    }
	     elseif($key =="cpemailid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
		$cols[] = $mastableid;
		$vals[] =  "'".$tenantmasid."'";
		$sqlDet .= 'INSERT INTO `'.$table_det.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')'.";";
		$cols="";
		$vals="";
	    }
	    }
	    $i++;
	}
	$sqlDel ="delete from mas_tenant_cp where tenantmasid =$tenantmasid";
	mysql_query($sqlDel);
	if($sqlDet !="")
	{
	    $sqlExec = explode(";",$sqlDet);
	    for($i=0;$i<count($sqlExec);$i++)
	    {
	         if($sqlExec[$i] != "")
	         {
		  	$result = mysql_query($sqlExec[$i]); //trans_offerletter_rent
		}
	    }
	}
	//$custom = array('msg'=>$sql.$sqlDel.$sqlDet,'s'=>'error');
	//$response_array[] = $custom;
	//echo '{"error":'.json_encode($response_array).'}';
	//exit;
	$m="Data Updated Successfully";
        
	
}
if($result == false)
        {
            $custom = array('msg'=>mysql_error(),'s'=>$sql);
        }
        else
        {
            $custom = array('msg'=>$m,'s'=>"Success");    
        }

        $response_array[] = $custom;
        echo '{
                "error":'.json_encode($response_array).
            '}';
//if($result == false)
//{
//    $custom = array('msg'=>mysql_error(),'s'=>$sql);
//}
//else
//{
//    $custom = array('msg'=>$m,'s'=>"Success");    
//}
//
//$response_array[] = $custom;
//echo '{
//	"error":'.json_encode($response_array).
//    '}';
//
//    
 // $result = mysql_query($sql);

?>  


