<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['action'];
$sql="";
$table = "mas_enquiry_updated";
$companymasid = $_SESSION['mycompanymasid'];
if($load == "load")
{
    $sql = "select * from ".$table." where companymasid='".$companymasid;
}
else if($load == "details")
{
    $sql = "SELECT * FROM $table where enquirymasid =".$load= $_GET['enquirymasid'];
}
else if($load == "flp_details_all")
{
    $sql = "select *,@a:=@a+1 sno from mas_enquiry_det ,(select @a:= 0) AS a where enquirymasid= ".$load= $_GET['enquirymasid'] ." order by enquirydetmasid desc ";
    $tr="";
    $result = mysql_query($sql);
    if($result != null)
    {            
            $status="";
            $rcount = mysql_num_rows($result);
            while($row = mysql_fetch_assoc($result))
            {
                $tr .="<tr>";
                $tr .="<td align='center'><a href='#' id='enquirydetmasid' name='enquirydetmasid' val='".$row['enquirydetmasid']."'>".$rcount--.". </a></td>";
                $tr .="<td>Dated: ".date('d-m-Y', strtotime($row['flpdt1'])).".</br>Remarks: ".$row['flpremarks'].".</br>Next follow-up date: ".date('d-m-Y', strtotime($row['flpdt2'])).". </td>";                
                $tr .="</tr>";
                $status = $row['flpstatus'];
            }
            
            if($status == '1')
            {
                $tr .="<tr><td colspan='2' align='center'>Follow-up Status : <font color='green'><b>OPEN</td></tr>";
            }
            else if($status == '0')
            {
                $tr .="<tr><td colspan='2' align='center'>Follow-up Status : <font color='red'><b>CLOSED</td></tr>";
            }
            else if($status == "")
            {
                $tr .="<tr><td colspan='2' align='center'>Follow-up Status : <font color='red'><b>Not Available</td></tr>";
            }
            
    }
    if($tr == "")
        $tr="<tr><td colspan='2'><font color='red' >No Follow up found</td></tr>";
    if($result != null) 
    {
        $custom = array('msg'=>$tr,'s'=>"Success");
        $response_array [] = $custom;
        echo '{
                "error":'.json_encode($response_array).
        '}';
    }
    else
    {
        $custom = array('msg'=>mysql_error(),'s'=>$sql);
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
    exit;
}
else if($load == "flp_details")
{
    $sql = "select enquirydetmasid,date_format(flpdt1,'%d-%m-%Y') as flpdt1,flpremarks,date_format(flpdt2,'%d-%m-%Y') as flpdt2,flpstatus from mas_enquiry_det where enquirydetmasid =".$load= $_GET['enquirydetmasid'];
}
$result =  mysql_query($sql);
    
    if($result != null) 
    {
        while($obj = mysql_fetch_object($result))
        {
            $arr[] = $obj;
        }
        $custom = array('msg'=>"",'s'=>"Success"); 
        $response_array [] = $custom;
        echo '{
            "myResult":'.json_encode($arr).',
            "error":'.json_encode($response_array).
        '}';				
    }
    else
    {
        $custom = array('msg'=>mysql_error(),'s'=>$sql);
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
?>