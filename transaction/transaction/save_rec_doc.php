<?php
include('../config.php');
session_start();

//$sqlGet ="";
//$nk =0;
//foreach ($_GET as $k=>$v) {
//    $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
//    $nk++;
//}
//$custom = array('msg'=> $sqlGet ,'s'=>'error');
//$response_array[] = $custom;
//echo '{"error":'.json_encode($response_array).'}';
//exit;

try
{
    $action = $_GET['action'];
    if($action=='Save')
    {
        $fromdate1 ="rent_fromdate1";
        $i=1;$sql="";
        foreach($_GET as $c => $v)
        {
            // mas_tenant, rec_tenant update doc
            if($c=="doc")
            {                    
                $v = date('Y-m-d', strtotime($v));
                $val[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
            }
            else if($c=="tenantmasid")
            {                
                $sql .= 'UPDATE `mas_tenant` SET '.implode($val, ',').' WHERE tenantmasid ='.$v.';';                
                $sql .= 'UPDATE `rec_tenant` SET '.implode($val, ',').' WHERE tenantmasid ='.$v.';';
            }
            
            
            // trans_offerletter_rent update
            $filter = strstr($c, '_', true);
            if($filter =="rent")
	    {                
                if($c == "rent_fromdate$i")
                {                    
                    $c ="fromdate";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");                    
                }
                else if($c == "rent_todate$i")
                {
                    $c ="todate";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");                                         
                }
                else if($c == "rent_yearlyhike$i")
                {                   
                    $c ="yearlyhike";                    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "rent_amount$i")
                {                   
                    $c ="amount";                    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    
                    $c ="modifiedby";
                    $v = $_SESSION['myusername'];
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                
                    $c ="modifieddatetime";
                    $v = $datetime;
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "rent_offerletterrentmasid$i")
                {                                       
                    $where = "offerletterrentmasid ='$v'";
                    $sql .= 'UPDATE `trans_offerletter_rent` SET '.implode($args, ',').' WHERE '.$where.";";                    
                    $args="";
                    $i++;        
                }               
            }            
        }
        $i=1;$args="";
        foreach($_GET as $c => $v)
        {
            // trans_offerletter_sc update
            $filter = strstr($c, '_', true);            
            if($filter =="sc")
	    {
                if($c == "sc_fromdate$i")
                {                    
                    $c ="fromdate";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");                    
                }
                else if($c == "sc_todate$i")
                {
                    $c ="todate";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");                                         
                }
                else if($c == "sc_yearlyhike$i")
                {                   
                    $c ="yearlyhike";                    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "sc_amount$i")
                {                   
                    $c ="amount";                    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    
                    $c ="modifiedby";
                    $v = $_SESSION['myusername'];
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                
                    $c ="modifieddatetime";
                    $v = $datetime;
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "sc_offerletterscmasid$i")
                {                                       
                    $where = "offerletterscmasid ='$v'";
                    $sql .= 'UPDATE `trans_offerletter_sc` SET '.implode($args, ',').' WHERE '.$where.";";                    
                    $args="";
                    $i++;        
                }               
            }            
        }
        $sqlArray[] = $sql;
        $sqlsts="";
        $sqlExec = explode(";",$sqlArray[0]);
	for($i=0;$i<count($sqlExec);$i++)
	{
	    if($sqlExec[$i] != "")
	    {
		//$sqlsts .=$sqlExec[$i];
                //$custom = array('msg'=>$sqlsts,'s'=>"Success");
                
                $result = mysql_query($sqlExec[$i]); //trans_offerletter_rent
                if($result == false)
                {
                    $custom = array('msg'=>mysql_error(),'s'=>$sqlExec[$i]);
                    $response_array[] = $custom;
                    echo '{"error":'.json_encode($response_array).'}';
                    exit; 
                }
                else
                {
                    $custom = array('msg'=>"DOC Changed Successfully.",'s'=>"Success");        
                }
	    }
	}        
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit; 
    }
}
catch (Exception $err)
{
    $custom = array(
                    'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                    's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}	
?>