<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$active = 0;


$table = "mas_bank";
if (isset($_GET['active']))
{
 
    $active = 1;
}


if($action == "Save")
{
		$createdby = $_SESSION['myusername'];
        $i=0;
        $key ="";
        foreach($_GET as $key=>$val) {
        
              
                if($key == "active")
                {    
                    $cols[] = "active";
                    $vals[] = "1";
                    
                }
				 if($key == "alias" || $key == "name")
                {    
                    $cols[] = "".$key."";
                    $vals[] = "'".$val."'";
                }
				
               /*  else
                {
                    $cols[] = "".$key."";
                    $vals[] = "'".$val."'";
                } */
   
            
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
}
else if($action == "Update")
 {
	 
	$modifiedby = $_SESSION['myusername'];
	$bankmasid = $_GET['bankmasid'];
      $where ="bankmasid =".$bankmasid;
        $j=0;
        foreach($_GET as $c => $v)
        {
                
                   
                    if($c == "active")
                    {    
                        $c ="active";
                        $v = "1";
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
					  if($c == "name" || $c =='alias')
                    {    
                   
						
                       $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
                  /*   else
                    {
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    } */
                
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

    

?>