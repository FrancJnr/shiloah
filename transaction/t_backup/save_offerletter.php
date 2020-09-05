<?php
include('../config.php');
session_start();
$action = $_GET['action'];

$leasename = $_GET['hidLeasename'];
$active = 0; 
$companymasid = $_SESSION['mycompanymasid'];
$tenantmasid = $_GET['tenantmasid'];
$table = "trans_offerletter";
if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}


$str = explode(" ",$leasename);
$str = strtoupper($str[0]);
$sqlAutoNo = "SELECT offerlettercode FROM ".$table." WHERE offerlettercode LIKE '$str%' ORDER BY offerlettercode DESC LIMIT 1";
$result=mysql_query($sqlAutoNo);


if($result != null)
{

    $row = mysql_fetch_array($result);
    $length = strlen($row['offerlettercode']);
    $cstr =  (int)substr($row['offerlettercode'],2,$length) + 1;
    $k = (int)strlen($cstr);
    if($k<=3)
    {
        $k =(int)4; // length of code starts from 5 digist after string E.g. GV00001
    }
    $codeno =str_pad($cstr,$k,"0",STR_PAD_LEFT);
    $offerlettercode=trim($str)."-OFFLET ".$codeno;
}

$sqlTerm = "SELECT b.age as 'term' FROM mas_tenant a inner join mas_age b on a.agemasidlt = b.agemasid WHERE a.tenantmasid=".$tenantmasid;
$result=mysql_query($sqlTerm);
$termCycle =0;
if($result != null)
{
    $row = mysql_fetch_array($result);
    $term = $row["term"];
    $term = explode(" ",$term);
    $termCycle = $term[0];
}
 
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
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
        $iid ="0";
	////Insert into table main
	mysql_query($sql);
	$iid = mysql_insert_id();	
	$mastableid ="offerlettermasid";
        $cols ="";
        $vals ="";
        $i=0;
        $j=0;
	$k=1;
	$m=1;
        $key ="";
	$sqldet="";
        $table_det ="trans_offerletter_rent";
	$sctype="";$pk=1;
        foreach($_GET as $key=>$val) {               
                if($i >7)
                {
		    if($val !="")
		    {
				if($j ==0)
				{
				      $cols[] = "FromDate";
				      $vals[] = "'".date('Y-m-d', strtotime($val))."'";
				}
				else if($j ==1)
				{
				     $cols[] = "ToDate";
				    $vals[] = "'".date('Y-m-d', strtotime("$val"))."'";
				}
				else if($j ==2)
				{
				    $cols[] = "yearlyhike";
				    $vals[] = "'".$val."'";
				}
				else if($j ==3)
				{
				     $cols[] = "amount";
				     $vals[] = "'".$val."'";
				}
				else 
				{				  
				  $vals[] = "'".$val."'";
				}
			  $j++;
			 if($j == 4)
			 {
			     $cols[] = $mastableid;
			     $vals[] =  "'".$iid."'";
			     $cols[] = "createdby";
			     $vals[] = "'".$createdby."'";
			     $cols[] = "createddatetime";
			     $vals[] = "'".$datetime."'";
			     $vals = str_replace(",","",$vals);
			     
			     if($iid != "")
			     {
                                $sqldet .='INSERT INTO `'.$table_det.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').');';
			     }
			     $cols = array();
			     $vals = array();
			     $j=0;
			     $k++;
			     if($k > $termCycle)
			    {
				$sqlArray[] = $sqldet;
				$sqldet ="";
				$k=1;
				$q = $i;
				$i =0;
				break;
			    }
			}
		    }
		}
            $i++;
        }
	$table_det="trans_offerletter_sc";
	 foreach($_GET as $key=>$val) {          // table 2    get remaining fields which are dynamically generated
                if($i > $q+2)
                {		   
		    if($val !="")
		    {
			if($j ==0)
			{
			    $cols[] = "FromDate";
			    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
			}
			else if($j ==1)
			{
			    $cols[] = "ToDate";
			    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
			}
			else if($j ==2)
			{
			
			    $cols[] = "yearlyhike";
			    $vals[] = "'".$val."'";
			}
			else if($j ==3)
			{
			     $cols[] = "amount";
			     $vals[] = "'".$val."'";
			}
			  $j++;
			 if($j ==4)
			 {
			     $cols[] = $mastableid;
			     $vals[] =  "'".$iid."'";
			     $cols[] = "createdby";
			     $vals[] = "'".$createdby."'";
			     $cols[] = "createddatetime";
			     $vals[] = "'".$datetime."'";
			     $vals = str_replace(",","",$vals);
			     
			     if($iid != "")
			     {
				$sqldet .='INSERT INTO `'.$table_det.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').');';
			     }
			     $cols = array();
			     $vals = array();
			     $j=0;
			     $k++;
		            if($k > $termCycle)
			    {
				$sqlArray[] = $sqldet;
				$sqldet ="";
				$k=1;
				$q = $i;
				$i =0;
				break;
			     
			    }
			}
		    }
		}
            $i++;
        }
	$table_det ="trans_offerletter_deposit";
	$a=true;
	$b=true;
	foreach($_GET as $key=>$val) {               // table 3
                if($i > $q)
                {
		   
		    if($key=="editleegalfees")
		    {
			 $cols[] = "".$key."";
			 $vals[] .= "'1'";
			 $a= false;
		    }
		    else if($key=="editstampduty")
		    {
			 $cols[] = "".$key."";
			 $vals[] .= "'1'";
			 $b=false;
		    }
		    else
		    {
			 $cols[] = "".$key."";
			 $vals[] = "'".$val."'";
		    }
		    
		}
		$i++;
	 }
	if($a==true)
	{
	   $cols[] .= "editleegalfees";
	   $vals[] .=  "'0'";
	}
	if($b==true)
	{
	  $cols[] .= "editstampduty";
	  $vals[] .= "'0'";
	}
	$cols[] = $mastableid;
	$vals[] =  "'".$iid."'";
	$cols[] = "createdby";
	$vals[] = "'".$createdby."'";
	$cols[] = "createddatetime";
	$vals[] = "'".$datetime."'"; // for table 3
	$vals = str_replace(",","",$vals);
	
	$sqldet .='INSERT INTO `'.$table_det.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').');';
	$sqlArray[] = $sqldet; // load last insert command

//	$custom = array('msg'=>$sql.$sqlArray[0].$sqlArray[1].$sqlArray[2],'s'=>'error');
//        $response_array[] = $custom;
//        echo '{"error":'.json_encode($response_array).'}';
//        exit;
    
	$sqlExec = explode(";",$sqlArray[0]);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		$result = mysql_query($sqlExec[$i]); //trans_offerletter_rent
	     }
	}
	
	$sqlExec = explode(";",$sqlArray[1]);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		$result = mysql_query($sqlExec[$i]); ///trans_offerletter_sc
	     }
	}
	
	$sqlExec = explode(";",$sqlArray[2]);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		$result = mysql_query($sqlExec[$i]); //trans_offerletter_deposit
	     }
	}
	
//	$custom = array('msg'=> $sqlArray[0].$sqlExec[1],'s'=>'error');
//        $response_array[] = $custom;
//        echo '{"error":'.json_encode($response_array).'}';
//        exit;
	
	$m="Data Saved Successfully";
	if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>"Error");
	}
	else
	{
	    $custom = array('msg'=>$m,'s'=>"Success");    
	}
}else if($action == "Update")
{	
$sqlGet ="";
$offerlettermasid=$_GET['offerlettermasid'];
$sqlchk ="select a.tenantmasid,a.renewalfromid from mas_tenant a
	    inner join trans_offerletter b on b.tenantmasid =  a.tenantmasid
	    where b.offerlettermasid = '$offerlettermasid' and a.active='1' and a.shopoccupied='1'
	    union
	    select a.tenantmasid,a.renewalfromid from rec_tenant a
	    inner join trans_offerletter b on b.tenantmasid =  a.tenantmasid
	    where b.offerlettermasid = '$offerlettermasid' and a.active='1' and a.shopoccupied='1';";
$resultchk = mysql_query($sqlchk);
$rcountchk=0;
if($resultchk)
{
    $rcountchk = mysql_num_rows($resultchk);
    $rowchk = mysql_fetch_assoc($resultchk);
    $renewalfromid = $rowchk['renewalfromid'];
    if($renewalfromid<=0)
    {
	if($rcountchk>0)
	{
	    //$custom = array('msg'=> "***Access Denied.Running tenancy cant edit Offerletter.***" ,'s'=>'error');
	    //$response_array[] = $custom;
	    //echo '{"error":'.json_encode($response_array).'}';
	    //exit;
	}
    }
}
//$nk =0;
//foreach ($_GET as $k=>$v) {
//    $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
//    $nk++;
//}
//$custom = array('msg'=> $sqlGet ,'s'=>'error');
//$response_array[] = $custom;
//echo '{"error":'.json_encode($response_array).'}';
//exit;
//	
	$table ="trans_offerletter";
	$where="offerlettermasid=".$load=$_GET['offerlettermasid'];
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
	$table_det1 ="trans_offerletter_rent";
	$modifiedby = $_SESSION['myusername'];	
	$where="";
        $j=0;
	$k=0;
	$sql1="";	
	$sqlArray="";
	//table 1 rent update
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
			$where="offerletterrentmasid=".$v;
			$k++;
			//$m++;
			$c ="modifiedby";
			$v = $_SESSION['myusername'];
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$c ="modifieddatetime";
			$v = $datetime;
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$sql1 .= 'UPDATE `'.$table_det1.'` SET '.implode($args, ',').' WHERE '.$where.";";
			$args ="";
			//break;
		    }
                }
            $j++;
        }
	
	//table 2 sc update
	$table_det2 ="trans_offerletter_sc";
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
			$where="offerletterscmasid = ".$v;
			$k++;
			$c ="modifiedby";
			$v = $_SESSION['myusername'];
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$c ="modifieddatetime";
			$v = $datetime;
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$sql2 .= 'UPDATE `'.$table_det2.'` SET '.implode($args, ',').' WHERE '.$where.";";
			$args ="";
			//break;
		    }
                }
            $j++;
        }
	
	
	$table_det3 ="trans_offerletter_deposit";
	$where="";
	$j=0;
	$sql3="";
	//table 3 deposit update
	$el = true;
	$es=true;
        foreach($_GET as $c => $v)
        {
                if($j > 4)
                {
		   if($c == "depositmonthrent")
		    {
			$v = str_replace(",","",$v);
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		    else if($c == "depositmonthsc")
		    {
			$v = str_replace(",","",$v);
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		    else if($c == "rentdeposit")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		    else if($c == "scdeposit")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		     else if($c == "advancemonthrent")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		     else if($c == "rentwithvat")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		     else if($c == "advancemonthsc")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		     else if($c == "scwithvat")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		     else if($c == "leegalfeevat")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		       else if($c == "editleegalfees")
		    {
			 $v = 1;
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			 $el=false;
		    }
		      else if($c == "leegalfees")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		        else if($c == "editstampduty")
		    {
			 $v = 1;
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			 $es=false;
		    }
		       else if($c == "stampdutyvat")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		        else if($c == "stampduty")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		        else if($c == "registrationfees")
		    {
			 $v = str_replace(",","",$v);
			 $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
			    else if($c == "depositTotal")
		    {
			$v = str_replace(",","",$v);
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
		        else if($c == "hidofferletterdepositmasid")
		    {
			$where ="offerletterdepositmasid= ".$v;
			$c ="modifiedby";
			$v = $_SESSION['myusername'];
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			$c ="modifieddatetime";
			$v = $datetime;
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			
			if($el==true)
			{
			    $c= "editleegalfees";
			    $v = "0";
			    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			}
			if($es==true)
			{
			    $c  = "editstampduty";
			    $v  = "0";
			    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			}
			 $sql3 .= 'UPDATE `'.$table_det3.'` SET '.implode($args, ',').' WHERE '.$where.";";
		    }
                }
            $j++;
        }
	$sqlDetQry = $sql.$sql1.$sql2.$sql3;
//	$custom = array('msg'=>$sql3,'s'=>'error');
//        $response_array[] = $custom;
//        echo '{"error":'.json_encode($response_array).'}';
//        exit;
        //table Main Update
	$s="";
	$sqlExec = explode(";",$sqlDetQry);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		$s .= $sqlExec[$i].";</br>";
		$result = mysql_query($sqlExec[$i]); //trans_offerletter_rent
	     }
	}
//	$custom = array('msg'=>$sql1.$sql2.$sql3,'s'=>'error');
//        $response_array[] = $custom;
//        echo '{"error":'.json_encode($response_array).'}';
//        exit;
	
	$m="Data Updated Successfully";
	if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>"Error");
	}
	else
	{
	    $custom = array('msg'=>$m,'s'=>"Success");    
	}
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