<?php
include('../config.php');
session_start();

//inserted in php.ini max_input_vars =5000 for longer POST array submission
//POST
//$sqlArray="";
//$cnt =1;
//	foreach ($_POST as $k=>$v) {
//	    ////$k = preg_replace('/[^a-z]/i', '', $k); 
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$custom = array('msg'=> $sqlArray ,'s'=>'error');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;
try
{
    $action = $_POST['btnSave'];
    $insert_mas="";$insert_det="";
    $transexpmasid = $_POST['hid_transexpmasid'];
    $transexpgenmasid = $_POST['hid_transexpgenmasid'];
    $kwvalue = $_POST['kwvalue'];
    $totalsqrft = $_POST['hid_totalsqrft'];
    $createdby = $_SESSION['myusername'];
    $createddatetime = $datetime;
    
    if($action == "Save")
    {
        $iid=0;
	
	$delqry ="delete from trans_exp_gen_mas where transexpgenmasid ='".$transexpgenmasid."';";		
	mysql_query($delqry);

        $insert_mas ="insert into trans_exp_gen_mas (transexpmasid,kwvalue,totalsqrft,createdby,createddatetime) values
                     ('$transexpmasid','$kwvalue','$totalsqrft','$createdby','$createddatetime');";
        $result = mysql_query($insert_mas);
        $iid = mysql_insert_id();
	
        //// count starts from
        $n=2;
        foreach($_POST as $key=>$val)
        {             	    
            if($key =="grouptenantmasid".$n)
            {
                $cols[] = "transexpgenmasid";
                $vals[] = "'".$iid."'";
                
                $cols[] = "grouptenantmasid";
                $vals[] = "'".$val."'";
            }
	    else if($key =="expcomareamasid".$n)
            {
                $cols[] = "transexpgenmasid";
                $vals[] = "'".$iid."'";
		$cols[] = "expcomareamasid";
                $vals[] = "'".$val."'";
            }
	    else if($key =="sqrft".$n)
            {
                $cols[] = "sqrft";
                $vals[] = "'".$val."'";
            }
	    else if($key =="mnthschrgd".$n)
            {
                $cols[] = "mnthschrgd";
                $vals[] = "'".$val."'";
            }
            else if($key =="connkw".$n)
            {
                $cols[] = "connkw";
                $vals[] = "'".$val."'";
            }
            else if($key =="addikw".$n)
            {
                $cols[] = "addikw";
                $vals[] = "'".$val."'";
            }	    
            else if(($key =="hid_totalkw".$n) or ($key =="hid_totalkwcom".$n))
            {
                $cols[] = "totalkw";
                $vals[] = "'".$val."'";
            }
	    else if($key =="commonkw".$n)
            {
                $cols[] = "commonkw";
                $vals[] = "'".$val."'";
            }
            else if(($key =="hid_totalkva".$n)or ($key =="hid_totalkvacom".$n))
            {
                $cols[] = "totalkva";
                $vals[] = "'".$val."'";
            }
            else if(($key =="hid_netkva".$n) or ($key =="hid_netkvacom".$n)) 
            {
                $cols[] = "totalkvapc";
                $vals[] = "'".$val."'";
	    }
	    else if (($key =="hid_chrgddircost".$n)  or ($key =="hid_chrgddircostcom".$n))
            {
                $cols[] = "chrgddircost";
                $vals[] = "'".$val."'";
	    }
	    else if(($key =="hid_chrgdcomcost".$n) or ($key =="hid_chrgdcomcostcom".$n))
            {
                $cols[] = "chrgdcomcost";
                $vals[] = "'".$val."'";
	    }
	    else if(($key =="hid_gencost".$n) or ($key =="hid_gencostcom".$n))
            {
                $cols[] = "gencost";
                $vals[] = "'".$val."'";              
                $insert_det = 'Insert into `trans_exp_gen_det`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
                    
                $result = mysql_query($insert_det);
                if($result == false)
                {                
                    echo mysql_error();
		    exit;
                }
		$cols="";
		$vals="";
		$n++;
            }	    
        }
	//echo $delqry."</br>".$insert_mas."</br>".$insert_det;
	echo "<font color=green>Saved Successfully</font>";
	echo "<button type='button' id='btnBack' name='btnBack'  onclick='history.back(-1)'>Back</button>";
	exit;
    }
    else  if($action == "Delete")
    {
        mysql_query("delete from trans_exp_gen_mas where transexpgenmasid in ($transexpgenmasid)");
	echo "<font color=red>Deleted Successfully</font>";
	echo "<button type='button' id='btnBack' name='btnBack'  onclick='history.back(-1)'>Back</button>";
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