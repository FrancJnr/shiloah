<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$table = "draft_document1";
$table2 = "draft_document2";
$section = $_GET['section'];


//$words = preg_replace('/[0-9]+/', '', $words);
$groupmasid = $_GET['licensemasid'];
$tenantmasid = $_GET['licensemasid'];
//echo $tenantmasid;
if($action == "Save")
{
   // print_r($_GET);
        //    $createdby = $_SESSION['myusername'];
            $i=0;
            $j=0;
            $key ="";
            $output=array_slice($_POST, 0, 16, true);
            foreach($output as $key=>$val) {
                if($i >= 0)
                {
                    $cols[] = "".$key."";
                    $vals[] = "'".mysql_real_escape_string($val)."'";                
                }
                $i++;
            }
            $output1 = array_slice($_POST, 16, 27, true);
             foreach($output1 as $key=>$val) {
                if($j >= 0)
                {
                    $cols1[] = "".$key."";
                    $vals1[] = "'".mysql_real_escape_string($val)."'";                
                }
                $j++;
            }
	    $cols[] = "section";
            $vals[] = "'".mysql_real_escape_string($section)."'";
            
            $cols1[] = "section";
            $vals1[] = "'".mysql_real_escape_string($section)."'";
            
            $cols[] = "grouptenantmasid";
            $vals[] = "'".mysql_real_escape_string($groupmasid)."'";
            
            $cols1[] = "grouptenantmasid";
            $vals1[] = "'".mysql_real_escape_string($groupmasid)."'";
            
            $cols[] = "tenantmasid";
            $vals[] = "'".mysql_real_escape_string($tenantmasid)."'";
            
            $cols1[] = "tenantmasid";
            $vals1[] = "'".mysql_real_escape_string($tenantmasid)."'";
            
          
            //$sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
            
            $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
            
           // $custom = array('msg'=>$sql,'s');	
            $sql2 = 'INSERT INTO `'.$table2.'`('.implode($cols1, ',').') VALUES('.implode($vals1, ',').')';
            
            //$custom = array('msg'=>$sql,'s');	
            $m="Data Saved Successfully";
       // print_r($sql);
}
$result = mysql_query($sql);
$result1 = mysql_query($sql2);
if(($result == false)||($result1==false))
{
   // $custom = array('msg'=>mysql_error(),'s'=>$sql);
    print_r(mysql_error());
}
else
{
   // $custom = array('msg'=>$m,'s'=>"Success");    
    //print_r($custom);
    echo $m;
}


//if($result1 == false)
//{
//   // $custom = array('msg'=>mysql_error(),'s'=>$sql);
//    print_r(mysql_error());
//}
//else
//{
//    $custom = array('msg'=>$m,'s'=>"Success");    
//    print_r($custom);
//}
//
//$response_array[] = $custom;
//echo '{
//	"error":'.json_encode($response_array).
//    '}';

//print_r($response_array);
?>