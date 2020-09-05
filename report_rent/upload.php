<?php

    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
     $companymasid=$_SESSION['mycompanymasid'];
    //include('../MasterRef_Folder.php');
  try{  
      if (! empty($_FILES['file'])){
          
               // print_r($_POST);
                $tenantmasid=$_POST['tenantmasid'];
                $docname=$_POST['docname'];
           $sql = "select buildingname, buildingmasid from mas_building where 
            companymasid=(select companymasid from mas_tenant where tenantmasid=$tenantmasid)";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                  $buildingname=$row['buildingname'];    
                  $buildingmaid=$row['buildingmasid'];
                }
        }
      
      
                $filePath = $_FILES["file"]["tmp_name"];
                
                $file = rand(1000,100000)."-".$filePath;
                $file_loc = $_FILES['file']['tmp_name'];
	        $file_size = $_FILES['file']['size'];
	        $file_type = $_FILES['file']['type'];
	        $folder="../../pms_docs/tenantdocs/";
                        // new file size in KB
                $new_size = $file_size/1024;  
                // new file size in KB

                // make file name in lower case
                $new_file_name = strtolower($file);
                // make file name in lower case

                $final_file=str_replace(' ','-',$new_file_name);
                $destination_path = getcwd().DIRECTORY_SEPARATOR;
                $destination_path=$destination_path.$folder;
                $target_path = $destination_path . basename( $_FILES["file"]["name"]);
               //move_uploaded_file($_FILES['file']['tmp_name'], $target_path)
               // echo $target_path;
                if(move_uploaded_file($file_loc,$target_path))
	        {
		$sql="INSERT INTO tenant_docs (file,type,size, docname, tenantmasid, buildingmasid) VALUES('$final_file','$file_type','$new_size','$docname','$tenantmasid','$buildingmaid')";
		mysql_query($sql);
		
	        echo 'success';
	        }
                else
                {
      
                   echo 'error while uploading file';
               
                }
                
                

        }
    
//        }
    
//    } 
    
   }
 catch (Exception $err)
 {
   echo $err->getMessage().", Line No:".$err->getLine();
   exit;
 }  

	