<?php
include('../config.php');
session_start();
try{
    
    //$sqlGet ="TEST";
    //$nk =0;
    //foreach ($_GET as $k=>$v) {
    //    if($k =="modulemasid")
    //    {
    //        $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
    //        $nk++;
    //    }
    //}
    //$custom = array('msg'=> $sqlGet ,'s'=>'error');
    //$response_array[] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
    //exit;
    
    
    $modulemasid=0;
    foreach ($_GET as $k=>$v) {
        if($k =="modulemasid")
        {
            $modulemasid=$v;     
        }
    }    
    
    $usermasid = $_GET['hidusermasid'];    
    $moduledetmasid="";
    if(isset($_GET['moduledetmasid']))
    {
        $moduledetmasid = array();
        //update active =0 before insert or update
        $updsql = "update mas_module_user set active ='0' where usermasid ='$usermasid' and modulemasid='$modulemasid';";
        $moduledetmasid[] = $updsql;
        
        foreach($_GET['moduledetmasid'] as $val){            
            $sql = "select moduledetmasid from mas_module_user
                    where usermasid ='$usermasid' and moduledetmasid = '$val';";
            $result = mysql_query($sql);
            if($result != null)
            {
                $rcount = mysql_num_rows($result);
                if($rcount <=0)
                {
                   //insert new file
                    $moduledetmasid[] = (int) $val;
                    $inssql = "insert into mas_module_user (usermasid,
                               modulemasid,moduledetmasid,active) values ($usermasid,$modulemasid,$val,1)";
                    $moduledetmasid[] = $inssql;
                }
                else
                {
                   //update existing file status to non active
                   $updsql = "update mas_module_user set active ='1' where usermasid ='$usermasid' and moduledetmasid = '$val'
                              and modulemasid = $modulemasid";
                   $moduledetmasid[] = $updsql;
                }
            }            
        }
        $moduledetmasid = implode(';', $moduledetmasid);
    }
    //$sk="";
    $sqlArray[] = $moduledetmasid; 
    $sqlExec = explode(";",$sqlArray[0]);
    for($i=0;$i<count($sqlExec);$i++)
    {
	if($sqlExec[$i] != "")
            {
                $result = mysql_query($sqlExec[$i]); //trans_offerletter_rent
                //$sk .=$sqlExec[$i]."<br>";
	    }
    }
    
    ////$custom = array('msg'=> $sk ,'s'=>'error');
    ////$response_array[] = $custom;
    ////echo '{"error":'.json_encode($response_array).'}';
    ////exit;
    
    $m="Data Updated Successfully";
    if($result == false)
    {
        $custom = array('msg'=>mysql_error(),'s'=>"Error");
    }
    else
    {
        $custom = array('msg'=>$m,'s'=>"Success");    
    }
    ////$custom = array('msg'=> $usermasid."<br>".$moduledetmasid ,'s'=>'error');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
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