<?php
include('../config.php');
session_start();

$action = $_POST['action'];
$table = "mas_shop";
$companymasid = $_SESSION['mycompanymasid'];
$buildingmasid = $_POST['buildingmasid'];
$floormasid = $_POST['floormasid'];
$shopmasid = $_POST['shopmasid'];
$shopcode= $_POST['shopcode'];
$target_path = "../images/shopimages/";
$custom ="";
$m="";
if($action == "Save")
{
            
/*      $sql="SELECT * FROM ".$table." WHERE shopmasid ='".$shopmasid."' and buildingmasid=".$buildingmasid." and companymasid=".$companymasid." 
	 and floormasid=".$floormasid;
       $resultis = mysql_query($sql);
        if($resultis == true)
        {
            $m .= "That shop already exists";
           // return;
        }
        else
        {
     */
    
    $createdby = $_SESSION['myusername'];
            $i=0;
            $key ="";
            foreach($_POST as $key=>$val) {
                if($i > 1)
                {
		    if(($key != "shopimage") and ($key != "video")and ($key != "action") and ($key != "active"))
		    {
			$cols[] = "".$key."";
			$vals[] = "'".$val."'";
		    }
                }
                $i++;
            }
	    if(!isset($_POST['active'])) //if not checked it wont appear in the $_GET array
	    {
	        $cols[] = "active";
	        $vals[] = "0";
	    }
	    else
	    {
		$cols[] = "active";
	        $vals[] = "1";
	    }
            $cols[] = "companymasid";
            $vals[] = "'".$companymasid."'";
            $cols[] = "createdby";
            $vals[] = "'".$createdby."'";
            $cols[] = "createddatetime";
            $vals[] = "'".$datetime."'";
	    $j=1;
	    if (! empty($_FILES['shopimage']))
	    {
		while(list($key,$val) = each($_FILES['shopimage']['name']))
		{
		    if(!empty($val)) // this will check if any blank field is entered
		    {   
			$a = array(' ','#','$','%','^','&','*','?');
			$b = array('','No.','Dollar','Percent','','and','','');
			/////$path1 = str_replace($a,$b,$path1); // to revome specila chracters if any in file path
			
			$filename = $val;  // filename stores the value
			$filetosave = $target_path.$filename;  // upload directory path is set
			$filetosave = str_replace($a,$b,$filetosave);
			$newfilename = file_exist_rename($target_path,$filename);
			$filetosave = $target_path.$newfilename;
			if(copy($_FILES['shopimage']['tmp_name'][$key], $filetosave))//  upload the file to the server
			{
			    chmod("$filetosave",0777);  // set permission to the file.			
			    $cols[] = "img".$j;
			    $vals[] = "'".$newfilename."'";			
			}
			else
			{
			    $m ="Error while copying file check read write permossion on $target_path";
			    $custom = array('msg'=>$m,'s'=>"Error");
			    $response_array[] = $custom;
			    echo json_encode($response_array);
			}
		    }
		    $j++;
		}
	    }
	    $j=0;
	    if (! empty($_FILES['video']))
	    {
		while(list($key,$val) = each($_FILES['video']['name']))
		{
		    if(!empty($val)) // this will check if any blank field is entered
		    {   
			$a = array(' ','#','$','%','^','&','*','?');
			$b = array('','No.','Dollar','Percent','','and','','');
			/////$path1 = str_replace($a,$b,$path1); // to revome specila chracters if any in file path
			
			$filename = $val;  // filename stores the value
			$filetosave = $target_path.$filename;  // upload directory path is set
			$filetosave = str_replace($a,$b,$filetosave);
			$newfilename = file_exist_rename($target_path,$filename);
			$filetosave = $target_path.$newfilename;
			if(copy($_FILES['video']['tmp_name'][$key], $filetosave))//  upload the file to the server
			{
			    chmod("$filetosave",0777);  // set permission to the file.			
			    $cols[] = "video";
			    $vals[] = "'".$newfilename."'";			
			}
			else
			{
			    $m ="Error while copying file check read write permossion on $target_path";
			    $custom = array('msg'=>$m,'s'=>"Error");
			    $response_array[] = $custom;
			    echo json_encode($response_array);
			}
		    }
		    $j++;
		}
	    }
            $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
            $custom = array('msg'=>$sql,'s');	
            $m="Data Saved Successfully";
      //  }
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
        $where ="shopmasid ='$shopmasid'";
        $j=0;
        foreach($_POST as $c => $v)
        {
            if($j > 2)
            {
		if(($c != "shopimage") and ($c != "video")and ($c != "action") and ($c != "active"))
		{
		    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		}
            }
            $j++;
        }
	 if(!isset($_POST['active'])) //if not checked it wont appear in the $_GET array
	{
	    $c = "active";
	    $v = "0";
	    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	}
	else
	{
	    $c = "active";
	    $v = "1";
	    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	}
	
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	$j=1;
	if (! empty($_FILES['shopimage']))
	{
	    while(list($key,$val) = each($_FILES['shopimage']['name']))
	    {
		if(!empty($val)) // this will check if any blank field is entered
		{   
		    $a = array(' ','#','$','%','^','&','*','?');
		    $b = array('','No.','Dollar','Percent','','and','','');
		    /////$path1 = str_replace($a,$b,$path1); // to revome specila chracters if any in file path
		    
		    $filename = $val;  // filename stores the value
		    $filetosave = $target_path.$filename;  // upload directory path is set
		    $filetosave = str_replace($a,$b,$filetosave);
		    $newfilename = file_exist_rename($target_path,$filename);
		    $filetosave = $target_path.$newfilename;
		    if(copy($_FILES['shopimage']['tmp_name'][$key], $filetosave))//  upload the file to the server
		    {			
			chmod("$filetosave",0777);  // set permission to the file.			
			$c = "img".$j;
			$v = "'".$newfilename."'";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : $v);
		    }
		    else
		    {
			$m ="Error while copying file check read write permossion on $target_path";
			$custom = array('msg'=>$m,'s'=>"Error");
			$response_array[] = $custom;
			echo json_encode($response_array);
		    }
		}
		$j++;
	    }
	}
	$j=0;
	if (! empty($_FILES['video']))
	{
	    while(list($key,$val) = each($_FILES['video']['name']))
	    {
		if(!empty($val)) // this will check if any blank field is entered
		{   
		    $a = array(' ','#','$','%','^','&','*','?');
		    $b = array('','No.','Dollar','Percent','','and','','');
		    /////$path1 = str_replace($a,$b,$path1); // to revome specila chracters if any in file path
		    
		    $filename = $val;  // filename stores the value
		    $filetosave = $target_path.$filename;  // upload directory path is set
		    $filetosave = str_replace($a,$b,$filetosave);
		    $newfilename = file_exist_rename($target_path,$filename);
		    $filetosave = $target_path.$newfilename;
		    if(copy($_FILES['video']['tmp_name'][$key], $filetosave))//  upload the file to the server
		    {
			chmod("$filetosave",0777);  // set permission to the file.			
			$c = "video";
			$v = "'".$newfilename."'";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : $v);
		    }
		    else
		    {
			$m ="Error while copying file check read write permossion on $target_path";
			$custom = array('msg'=>$m,'s'=>"Error");
			$response_array[] = $custom;
			echo json_encode($response_array);
		    }
		}
		$j++;
	    }
	}
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
        
	$m="Data Updated Successfully";
}

function file_exist_rename($path, $filename){
	if ($pos = strrpos($filename, '.')) {
		$name = substr($filename, 0, $pos);
		$ext = substr($filename, $pos);
	} else {
		$name = $filename;
	}
		$newpath = $path.'/'.$filename;
		$newname = $filename;
		$counter = 0;
		while (file_exists($newpath)) {
			$newname = $name .'_'. $counter . $ext;
			$newpath = $path.'/'.$newname;
			$counter++;
		}
	return $newname;
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
echo json_encode($response_array);
?>