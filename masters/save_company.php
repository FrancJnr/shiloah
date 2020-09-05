<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$active = 0;
$companyname = $_GET['companyname'];
$pin = $_GET['pin'];
//$acyearfrom = $_GET['acyearfrom'];
//$acyearfrom = $Date=date('Y-m-d H:i:s', strtotime($acyearfrom)) ;

$table = "mas_company";
if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}

$str = strtoupper(substr($companyname,0,2));
$sqlAutoNo = "SELECT companycode FROM ".$table." WHERE companycode LIKE '$str%' ORDER BY companycode DESC LIMIT 1 ";
$result=mysql_query($sqlAutoNo);
if($result != null)
{
    
    $cnt = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $length = strlen($row['companycode']);
    $cstr =  (int)substr($row['companycode'],2,$length) + 1;
    $k = (int)strlen($cstr);
    if($k<=5)
    {
        $k =(int)5; // length of code starts from 5 digist after string E.g. GV00001
    }
    $codeno =str_pad($cstr,$k,"0",STR_PAD_LEFT);
    $companycode=trim($str).$codeno;
}

//$q = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
//$custom = array('msg'=> "TEST4",'s'=>"error");
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
        $i=0;
        $key ="";
        foreach($_GET as $key=>$val) {
            if($i > 2)
            {
                if($key == "acyearfrom")
                {    
                    $cols[] = "acyearfrom";
                    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                    //break 1;
                }
                else if($key == "acyearto")
                {    
                    $cols[] = "acyearto ";
                    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                    //break 1;
                }
                else if($key == "active")
                {    
                    $cols[] = "active";
                    $vals[] = "1";
                    //break 1;
                }
                else
                {
                    $cols[] = "".$key."";
                    $vals[] = "'".$val."'";
                }
                //$jk .=  $key."-->".$val.",";
            }
            if($i == 3)
            {
                $cols[] = "companycode";
                $vals[] = "'".$companycode."'";
            }
            
            $i++;
        }
        if(!isset($_GET['active'])) //if not checked it wont appear in the $_GET array
        {
            $cols[] = "active";
            $vals[] = "0";
        }
        $cols[] = "createdby";
        $vals[] = "'".$createdby."'";
        $cols[] = "createddatetime";
        $vals[] = "'".$datetime."'";
        $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
        $custom = array('msg'=>$sql,'s');	
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$companymasid = $_GET['companymasid'];
        $where ="companymasid ='$companymasid'";
        $j=0;
        foreach($_GET as $c => $v)
        {
                if($j > 2)
                {
                    if($c == "acyearfrom")
                    {    
                        $c ="acyearfrom";
                        $v = date('Y-m-d', strtotime($v));
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
                    else if($c == "acyearto")
                    {    
                        $c ="acyearto";
                        $v = date('Y-m-d', strtotime($v));
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
                    else if($c == "active")
                    {    
                        $c ="active";
                        $v = "1";
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
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
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
        $custom = array('msg'=> $sql,'s');
	//$sql = "UPDATE `mas_age` SET `age`='$age',`description`='$description',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `agemasid`='$agemasid'";
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

    
    
/*
 //split columns from a table
    $fields = mysql_list_fields("shiloahmsk","mas_company");
    $columns = mysql_num_fields($fields);
    $out="";
    for ($i = 1; $i < $columns; $i++) {
	$l = mysql_field_name($fields, $i);
	$out .= "'".$l."','";
    }
    $out = substr_replace($out ,"",-2);
    $out = "(".trim($out).")";
*/
?>