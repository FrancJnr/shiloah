<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$table = "mas_block";
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_GET['buildingmasid'];
$blockmasid = $_GET['blockmasid'];
$blockname = $_GET['blockname'];

if($action == "Save")
{           
            
       $sql="SELECT * FROM ".$table." WHERE blockname ='".$blockname."'";
       $resultis = mysql_query($sql);
        if($resultis == true)
        {
            $m = "That block name already exists";
           // return;
        }
        else
        {
           // $custom = array('msg'=>$m,'s'=>"Success");    
        
    
    
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
            $cols[] = "companymasid";
            $vals[] = "'".$companymasid."'";
            $cols[] = "createdby";
            $vals[] = "'".$createdby."'";
            $cols[] = "createddatetime";
            $vals[] = "'".$datetime."'";
            
            $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
            $custom = array('msg'=>$sql,'s');	
            $m="Data Saved Successfully";
        }
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
        $where ="blockmasid ='$blockmasid'";
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