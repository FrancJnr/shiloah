<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$table = "mas_building";
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$buildingname = $_GET['buildingname'];
$shortname = $_GET['shortname'];
if($action == "Save")
{
//       $sql="SELECT * FROM ".$table." WHERE buildingname ='".$buildingname."' OR shortname= '".$shortname."'";
//       $resultis = mysql_query($sql);
//       if($resultis == true)
//        {
//            $m = "That building name or short name already exists";
//           // return;
//        }
//        else
//        {
            $createdby = $_SESSION['myusername'];
            $i=0;
            $key ="";
            foreach($_GET as $key=>$val) {
                if($i > 2)
                {
                    $cols[] = "".$key."";
                    $vals[] = "'".$val."'";                
                }
                $i++;
            }
	    if(!isset($_GET['isvat'])) //if not checked it wont appear in the $_GET array
	    {
		$cols[] = "isvat";
		$vals[] = "0";
	    }
	    if(!isset($_GET['pledged'])) //if not checked it wont appear in the $_GET array
	    {
		$cols[] = "pledged";
		$vals[] = "0";
	    }
            $cols[] = "companymasid";
            $vals[] = "'".$companymasid."'";
            $cols[] = "createdby";
            $vals[] = "'".$createdby."'";
            $cols[] = "createddatetime";
            $vals[] = "'".$datetime."'";
            
            $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
            $custom = array('msg'=>$sql,'s');	
            $m="Data Saved Successfully";
       // }
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
        $where ="buildingmasid ='$buildingmasid'";
        $j=0;
        foreach($_GET as $c => $v)
        {
                if($j > 2)
                {
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
            $j++;
        }
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
	if(!isset($_GET['isvat'])) //if not checked it wont appear in the $_GET array
        {
            $c ="isvat";
            $v = "0";
            $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        }
	else
	{
	    $c ="isvat";
            $v = "1";
            $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	}
	
	if(!isset($_GET['pledged'])) //if not checked it wont appear in the $_GET array
        {
            $c ="pledged";
            $v = "0";
            $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        }
	else
	{
	    $c ="pledged";
            $v = "1";
            $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	}
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
        $custom = array('msg'=> $sql,'s');
	$m="Data Updated Successfully";
}
$result = mysql_query($sql);
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
?>