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

try{

    $action = $_GET['action'];
    $leasename = $_GET['hidLeasename'];
    $active = 0; 
    $companymasid = $_SESSION['mycompanymasid'];
   
    
    if (isset($_GET['active']))
    {
       // if $_GET['active'] = on
        $active = 1;
    }
if($action == "Save")
    {
	
	$offerlettermasid = $_GET['offerlettermasid'];
	$sql ="select tenantmasid,offerlettercode from trans_offerletter where offerlettermasid =$offerlettermasid";
	$result =mysql_query($sql);
	while($row = mysql_fetch_assoc($result))
	{
	    $tenantmasid=$row['tenantmasid'];
	    $offerlettercode =$row['offerlettercode'];
	}
    
	//rectified trans  offerltter
	$table = "rec_trans_offerletter";
	$createdby = $_SESSION['myusername'];
	
	$cols[] = "offerlettermasid";
        $vals[] = "'".$offerlettermasid."'";
	
        $i=0;
        $key ="";
        foreach($_GET as $key=>$val) {
            if($i > 1)
            {
		if(($key == "renttype") || ($key == "basicrentval") ||($key == "rentpersqrft")|| ($key == "sctype")||($key == "basicscval")||($key == "scpersqrft"))
		 {    
		    $cols[] = "".$key."";
		    $vals[] = "'".str_replace(",","",$val)."'";
		 }
            }
            if($i == 3)
            {
                $cols[] = "offerlettercode";
                $vals[] = "'".$offerlettercode."'";
            }
            
            $i++;
        }
        if(!isset($_GET['active'])) //if not checked it wont appear in the $_GET array
        {
            $cols[] = "active";
            $vals[] = "1";
        }
	$cols[] = "tenantmasid";
        $vals[] = "'".$tenantmasid."'";
	$cols[] = "companymasid";
        $vals[] = "'".$companymasid."'";
        $cols[] = "createdby";
        $vals[] = "'".$createdby."'";
        $cols[] = "createddatetime";
        $vals[] = "'".$datetime."'";
        $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').');';
	 
	$iid ="0";	
	////Insert into table main
	mysql_query($sql);
	$iid = mysql_insert_id();
	
        $cols ="";
        $vals ="";
	$key ="";
	$sqldet=""; $i=0;$j=0;$k=0;
	$table_det ="rec_trans_offerletter_rent";
        
	foreach($_GET as $key=>$val) {
	    if( $i > 6)
	    {		
		if($key == "hidFromdateRent".$j)
		{
		    $cols[] = "FromDate";
		    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
		}
		else if($key == "hidTodateRent".$j)
		{
		    $cols[] = "ToDate";
		    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
		}   
		else if($key == "rentpercentage".$j)
		{
		    $cols[] = "yearlyhike";
		    $vals[] = "'".str_replace(",","",$val)."'";
		}
		else if($key == "rentval".$j)
		{
		    $cols[] = "amount";
		    $vals[] = "'".str_replace(",","",$val)."'";
		}
		else if($key == "hidofferletterrentmasid".$j)
		{
		    $j++;
		    $cols[] = "offerlettermasid";
		    $vals[] =  "'".$offerlettermasid."'";
		    
		    $cols[] = "createdby";
		    $vals[] = "'".$createdby."'";
		    $cols[] = "createddatetime";
		    $vals[] = "'".$datetime."'";
		    $vals = str_replace(",","",$vals);
		    $sqldet .= 'INSERT INTO `'.$table_det.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').');';
		    $cols ="";
		    $vals ="";
		}
	        if($key == "sctype")
		{		   		    
		    $table_det ="rec_trans_offerletter_sc";
		    //break;    
		}
		if($key == "hidFromdateSc".$k)
		{
		    $cols[] = "FromDate";
		    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
		}
		else if($key == "hidTodateSc".$k)
		{
		    $cols[] = "ToDate";
		    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
		}
		else if($key == "servicechrgpercentage".$k)
		{
		    $cols[] = "yearlyhike";
		    $vals[] = "'".str_replace(",","",$val)."'";
		}
		else if($key == "servicechrgval".$k)
		{
		    $cols[] = "amount";
		    $vals[] = "'".str_replace(",","",$val)."'";
		}
		else if($key == "hidofferletterscmasid".$k)
		{
		    $k++;
		    $cols[] = "offerlettermasid";
		    $vals[] =  "'".$offerlettermasid."'";
		    $cols[] = "createdby";
		    $vals[] = "'".$createdby."'";
		    $cols[] = "createddatetime";
		    $vals[] = "'".$datetime."'";
		    $vals = str_replace(",","",$vals);
		    $sqldet .= 'INSERT INTO `'.$table_det.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').');';
		    $cols ="";
		    $vals ="";
		}
	    }
	    $i++;	    
	}	
	$sqlArray[] = $sqldet;	
	$sqlExec = explode(";",$sqlArray[0]);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		$result = mysql_query($sqlExec[$i]); //trans_offerletter_rent
	     }
	}	
	
	$m="Rent altered successfully.";
	if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>"Error");
	}
	else
	{
	    $custom = array('msg'=>$m,'s'=>"Success");    
	}
	
	$updmas ="Update trans_offerletter set active ='0' where tenantmasid ='$tenantmasid';";
	$result = mysql_query($updmas);
	
	////$custom = array('msg'=> $sql.$sqldet ,'s'=>'error');	
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
    }
else if($action == "Update")
    {	
	$table ="rec_trans_offerletter";
	$where="offerlettermasid='".$load=$_GET['recofferlettermasid']."'";
	$sql="";	
	
	//table MAIN update
	foreach($_GET as $c => $v)
        {
	    if($c == "renttype")
	    {
		$c = "renttype";
		$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	    }
	    else if($c == "basicrentval")
	    {
		$c = "basicrentval";
		$v = str_replace(",","",$v);
		$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	    }
	    else if($c == "sctype")
	    {
		$c = "sctype";
		$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	    }
	    else if($c == "basicscval")
	    {
		$c = "basicscval";
		$v = str_replace(",","",$v);
		$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		$sql .= 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where.";";
		$args="";
		$c="";
		$v="";
	    }
	}
	
	$table_det1 ="rec_trans_offerletter_rent";
	$modifiedby = $_SESSION['myusername'];	
	$where="";
        $j=0;
	$k=0;
	$sql1="";	
	$sqlArray="";
	
	//table det 1 rent update
        foreach($_GET as $c => $v)
        {
                if($j > 4)
                {
		   if($c == "hidFromdateRent".$k)
		    {
			$c = "FromDate";
			$v = "'".date('Y-m-d', strtotime($v))."'";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "$v");
		    }
		    else if($c == "hidTodateRent".$k)
		    {
			$c = "ToDate";
			$v = "'".date('Y-m-d', strtotime($v))."'";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "$v");
		    }
		     else if($c == "rentpercentage".$k)
		    {
			$c = "yearlyhike";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		    else if($c == "rentval".$k)
		    {
			$c = "amount";
			$v = str_replace(",","",$v);
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		      else if($c == "hidofferletterrentmasid".$k)
		    {
			$where="recofferletterrentmasid=".$v;
			$k++;

			$c ="modifiedby";
			$v = $_SESSION['myusername'];
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$c ="modifieddatetime";
			$v = $datetime;
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$sql1 .= 'UPDATE `'.$table_det1.'` SET '.implode($args, ',').' WHERE '.$where.";";
			$args ="";
		    }
                }
            $j++;
        }
	
	//table det 2 sc update
	$table_det2 ="rec_trans_offerletter_sc";
	$where="";
	$j=0;
	$k=0;
	$sql2="";
        foreach($_GET as $c => $v)
        {
                if($j > 4)
                {
		   
		   if($c == "hidFromdateSc".$k)
		    {
			$c = "FromDate";
			$v = "'".date('Y-m-d', strtotime($v))."'";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "$v");
		    }
		    else if($c == "hidTodateSc".$k)
		    {
			$c = "ToDate";
			$v = "'".date('Y-m-d', strtotime($v))."'";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "$v");
		    }
		     else if($c == "servicechrgpercentage".$k)
		    {
			$c = "yearlyhike";
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		    else if($c == "servicechrgval".$k)
		    {
			$c = "amount";
			$v = str_replace(",","",$v);
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		    else if($c == "hidofferletterscmasid".$k)
		    {
			$where="recofferletterscmasid = '".$v."'";
			$k++;
			$c ="modifiedby";
			$v = $_SESSION['myusername'];
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$c ="modifieddatetime";
			$v = $datetime;
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$sql2 .= 'UPDATE `'.$table_det2.'` SET '.implode($args, ',').' WHERE '.$where.";";
			$args ="";
		    
		    }
                }
            $j++;
        }
	$sqlDetQry = $sql.$sql1.$sql2;
	
	$s="";
	$sqlExec = explode(";",$sqlDetQry);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		$s .= $sqlExec[$i].";</br>";		
		$result = mysql_query($sqlExec[$i]); 
	     }
	}
	
	$m="Rent Updated Successfully";
	
	if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>"Error");
	}
	else
	{
	    $custom = array('msg'=>$m,'s'=>"Success");    
	}
	
	////$custom = array('msg'=>$sqlDetQry,'s'=>'error');
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