<?php

include('../config.php');
session_start();
$response_array = array();
$load= $_GET['item'];

try{
    
if($load == "module")
{
    $usermasid = $_GET['itemval'];$username="";$empname="";
    $sql = "select a.empname,b.username from mas_employee a 
            inner join mas_user b on b.empmasid = a.empmasid
            where b.usermasid ='$usermasid';";
    $result = mysql_query($sql);
    if($result != null)
    {
        $row = mysql_fetch_assoc($result);
        $username =  $row['username'];
        $empname =   $row['empname'];
    }        
    $table ="<p><table width='100%'>";
    $table .="<tr><th>User Module</th></tr>";
    $table .="<tr><td>Name:   $empname<br>Username:   $username</br></td></tr>";
    $table .="<tr><th>Module:<select id='modulemasid' name='modulemasid' style='width: 225px;'>
            <option value='0' selected>----Select Module----</option>";    
    $sql= "select modulemasid,modulename from mas_module order by modulemasid;";
    $result = mysql_query($sql);
    if($result != null)
    {
        while($row = mysql_fetch_assoc($result))
        {                
            $table .="<option value=".$row['modulemasid'].">".$row['modulename']."</option>";		
        }
    }
    $table .="</select></th></tr>";    
    $table .="<tbody id='tblusermodule' name='tblusermodule'></tbody>";
    $table .="<tr><td align='right'><button type='button' id='btnUpdate' name='btnUpdate'>Update</button></td></tr>";
    $table .="<tr><th></th></tr>";
    $table .="</table></p>";    
    $hidusermasid = "<input type='hidden' id='hidusermasid' name='hidusermasid' value='$usermasid'/>";
    
    $custom = array('msg'=>$table.$hidusermasid,'s'=>"Success");
    $response_array [] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
else if($load == "usermoduledetails")
{
    $usermasid = $_GET['usermasid'];
    $modulemasid = $_GET['itemval'];    
    $table ="<tr><td>";
    $table  .="<table width='95%'>";    
    $table .="<tr><td colspan='4'>Files:</td></tr>";
    $table .="<tr><th>S.No</th><th>Module Name</th><th>File Name</th><th>Check All<input type=checkbox id='select_all' name='select_all'>
            <button type='button' id='btnUpdate'>Update</button></td></th></tr>";
    $sql= "select a.moduledetmasid,c.moduleheader,a.filename from mas_module_det a
            inner join mas_module b on b.modulemasid = a.modulemasid
            inner join mas_module_header c on c.moduleheadermasid = a.moduleheadermasid
            where a.modulemasid ='$modulemasid';";
    $result = mysql_query($sql);
    $r = false;
    if($result != null)
    {
        $i=1;
        while($row = mysql_fetch_assoc($result))
        {                
            $table .="<tr><td>$i</td><td>".$row['moduleheader']."</td><td>".$row['filename']."</td><td>";
            $moduledetmasid = $row['moduledetmasid'];
            $sqluser = "select moduledetmasid from mas_module_user
                        where usermasid ='$usermasid' and moduledetmasid = '$moduledetmasid' and active='1';";
            $resultuser = mysql_query($sqluser);
            $k = false;
            if($resultuser != null)
            {
                while($rowuser = mysql_fetch_assoc($resultuser))
                {
                    $k = true;
                }
            }            
            if($k == true)
            {
                $table .="<input type=checkbox id='moduledetmasid[]' name='moduledetmasid[]' value='$moduledetmasid' checked>";
            }
            else
            {
                $table .="<input type=checkbox id='moduledetmasid[]' name='moduledetmasid[]' value='$moduledetmasid'>";
            }
            $table .="</td></tr>";
            $i++;
            $r=true;
        }        
    }
    if($r == true)
    {
        $table .="</table>";
        $table .="</td></tr>";
    }
    else
    {
        $table ="<tr align='center'><td>NO DATA AVAILABLE !!!</td></tr>";
    }    
    $custom = array('msg'=>$table,'s'=>"Success");
    $response_array [] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
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