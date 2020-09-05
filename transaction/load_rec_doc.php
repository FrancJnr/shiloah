<?php
include('../config.php');
session_start();
$response_array = array();
$load= $_GET['item'];

if($load == "tenantdetails")
{
   $tenantmasid = $_GET['tenantmasid'];
   $sql = "select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,b.size,c.buildingname,date_format(a.doc,'%d-%m-%Y') as doc from mas_tenant a
                        inner join mas_shop b on b.shopmasid = a.shopmasid
                        inner join mas_building c on c.buildingmasid = b.buildingmasid
                        where a.active ='1' and a.tenantmasid='$tenantmasid'
                        union
                        select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,b.size,c.buildingname,date_format(a.doc,'%d-%m-%Y') as doc from rec_tenant a
                        inner join mas_shop b on b.shopmasid = a.shopmasid
                        inner join mas_building c on c.buildingmasid = b.buildingmasid
                        where a.active ='1' and a.tenantmasid='$tenantmasid';";
}
else if($load == "offerletterdetails")
{    
    //rent        
    $tenantmasid = $_GET['tenantmasid'];
    $table ="<table width='100%'>";
    $table .="<tr><td colspan='4'><input type='hidden' id='tenantmasid' name='tenantmasid' value=$tenantmasid></td></tr>";
    $offerlettermasid=0;
    $sql= "select offerlettermasid from trans_offerletter where tenantmasid ='$tenantmasid';";    
    $result =mysql_query($sql);
    if($result !=null)
    {
        $row =mysql_fetch_assoc($result);
        $offerlettermasid=$row['offerlettermasid'];        
    }    
    
    $sql= "select offerletterrentmasid,date_format(fromdate,'%d-%m-%Y') as fromdate,
                  date_format(todate,'%d-%m-%Y') as todate,yearlyhike,amount from trans_offerletter_rent where offerlettermasid ='$offerlettermasid';";    
    $result =mysql_query($sql);
    if($result !=null)
    {
        $table .="<tr>
                    <th>From date</th>
                    <th>To date</th>
                    <th>Increment</th>
                    <th>Rent</th>
                </tr>";
        $i=0;        
        while($row =mysql_fetch_assoc($result))
        {
            $i++;
            $table .="<tr>
                    <td class='rent'><input type='text' id='rent_fromdate$i' name='rent_fromdate$i' value =".$row['fromdate']." style='width:90px;' readonly/></td>
                    <td><input type='text' id='rent_todate$i' name='rent_todate$i' value =".$row['todate']." style='width:90px;' readonly/></td>
                    <td><input type='text' id='rent_yearlyhike$i' name='rent_yearlyhike$i' value =".$row['yearlyhike']." style='width:90px;' readonly/></td>
                    <td>
                        <input type='text' id='rent_amount$i' name='rent_amount$i' value =".$row['amount']." style='width:90px;align'/>
                        <input type='hidden' id='rent_offerletterrentmasid$i' name='rent_offerletterrentmasid$i' value=".$row['offerletterrentmasid'].">
                     </td>
                </tr>";
        }
    }
    
    $table .="</table>";    
    $table .="</br></br>";
    //sc
    $table .="<table width='100%'>";
    
    $sql= "select offerletterscmasid,date_format(fromdate,'%d-%m-%Y') as fromdate,
                  date_format(todate,'%d-%m-%Y') as todate,yearlyhike,amount from trans_offerletter_sc where offerlettermasid ='$offerlettermasid';";    
    $result =mysql_query($sql);
    if($result !=null)
    {
        $table .="<tr>
                    <th>From date</th>
                    <th>To date</th>
                    <th>Increment</th>
                    <th>Rent</th>
                </tr>";
        $i=0;        
        while($row =mysql_fetch_assoc($result))
        {
            $i++;
            $table .="<tr>
                    <td class='sc'><input type='text' id='sc_fromdate$i' name='sc_fromdate$i' value =".$row['fromdate']." style='width:90px;'/></td>
                    <td><input type='text' id='sc_todate$i' name='sc_todate$i' value =".$row['todate']." style='width:90px;'/></td>
                    <td><input type='text' id='sc_yearlyhike$i' name='sc_yearlyhike$i' value =".$row['yearlyhike']." style='width:90px;'/></td>
                    <td>
                        <input type='text' id='sc_amount$i' name='sc_amount$i' value =".$row['amount']." style='width:90px;align'/>
                        <input type='hidden' id='sc_offerletterscmasid$i' name='sc_offerletterscmasid$i' value=".$row['offerletterscmasid'].">
                     </td>
                </tr>";
        }
    }
    $table .="</table>";
    
    $custom = array('msg'=> $table,'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
else if($load == "changedoc")
{
    $table="<table>";
    $frmdt = $_GET['doc'];
    $todt1="";$todt2=""; $d1="";$d2="";
    $cnt = $_GET['rowcnt'];
    for($j=1;$j<=$cnt;$j++)
    {
        $StartingDate = $frmdt;
        $newEndingDate = date("d-m-Y", strtotime(date("d-m-Y", strtotime($frmdt)) . " + 1 Year"));
        $todt1 =$newEndingDate;
        $d = date('d',strtotime("-1 days $frmdt"));
        $y = date('d-m-Y', strtotime("+12 months $frmdt"));		     
        $todt2 = date('d-m-Y',strtotime("-1 days $y"));
        $d1 .=$frmdt." - " .$todt2."</br>";
        $vk = 'rowfrmdt'.$j;
        $table .="<tr>";
            $table .="<td class='rowfrmdt".$j."'>".$frmdt."</td>";
            $table .="<td class='rowtodt".$j."'>".$todt2."</td>";            
        $table .="</tr>";
        $frmdt = $todt1;
    }
    $table .="</table>";
    $custom = array('msg'=> $table,'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
$result =  mysql_query($sql);
    
    if($result != null) 
    {
        $cnt = mysql_num_rows($result);
        if($cnt > 0)
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
            $custom = array('msg'=>$sql,'s'=>$sql);
            $response_array [] = $custom;
            echo '{
                "error":'.json_encode($response_array).
            '}';
        }
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