<?php
include('../config.php');
session_start();
$response_array = array();
$action = $_GET['action'];
$companymasid = $_SESSION['mycompanymasid'];
$user =  $_SESSION['myusername'];

$grouptenantmasid = $_GET['hidgrouptenantmasid'];
$sql="";
$leasename = $_GET['hidLeasename'];

    //$sqlArray="";
    //$cnt =1;
    //        foreach ($_GET as $k=>$v) {
    //            $k = preg_replace('/[^a-z]/i', '', $k); 
    //            $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
    //            $cnt++;
    //        }
    //$custom = array('msg'=> $v ,'s'=>'error');
    //$response_array[] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
    //exit;

$to="";
try{
    
        $where ="grouptenantmasid ='$grouptenantmasid'";
        $table ="trans_document_status";
        $j=0;
       
        foreach($_GET as $c => $v)
        {
            if($j > 2)
            {
                if($c == "leasestatus")
                {    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "istenantpinno")
                {    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    
                }
                else if($c == "remarks")
                {    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }                
                else
                {
                    if(strlen($v) >=10)
                    {
                        $v = date('Y-m-d', strtotime(dmy_to_ymd($v)));
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
                }
            }
            $j++;
        };
        
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");        
        
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
                
        $m ="Data Updated Successfully";
        
//   $custom = array('msg'=> $sql ,'s'=>'error');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;
//    
    
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
        
} //try
catch (Exception $err)
{
    $custom = array(
                'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}

?>