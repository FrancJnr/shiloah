<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";

if($load == "expgroup")
{
    $load= $_GET['itemval'];
    $sql = "select b.expgroup from mas_exp_ledger a 
            inner join mas_exp_group b on b.expgroupmasid =  a.expgroupmasid
            where a.expledgermasid='$load' group by a.expgroupmasid";
}
else if($load == "transexpmas")
{
    $sql = "select 
            date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') as todate,
            active,buildingmasid,totalamount from trans_exp_mas where transexpmasid=
            ".$load= $_GET['itemval'];
}
else if($load == "transexpdet")
{
    $sql = "select a.*,d.expgroup from trans_exp_det a
            inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
            inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
            inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
            where a.transexpmasid=".$load= $_GET['itemval'];
}
else if($load == "expdetails")
{
    $sqlmas = "select 
            date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') as todate,
            active,buildingmasid,totalamount from trans_exp_mas where transexpmasid=
            ".$load= $_GET['itemval'];
    $resultmas = mysql_query($sqlmas);
    $i=1;
    $tbl="--";
    if($resultmas != null)
    {
        while($rowmas = mysql_fetch_assoc($resultmas))
            {
                $k="";
                $sqldet1 = "select a.*,d.expgroup,c.expledger from trans_exp_det a
                        inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
                        inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
                        inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
                        where a.transexpmasid=".$load= $_GET['itemval'];
                $resultdet = mysql_query($sqldet1);
                if($resultdet != null)
                {
                        while($rowdet = mysql_fetch_assoc($resultdet))
                        {                                
                                $tbl .= "<tr><td><input type='text' name='sno_0".$i."' maxlength='255' value='".$i."' /></td>";
                                $tbl .= "<td>";  
                                $tbl .= "<select id='expledgermasid_0".$i."' name='expledgermasid_0".$i."' class='ledger'  style='width:250px;'>";
                                $sqlselect = "select * from mas_exp_ledger where active='1' order by expledger asc";
                                $resultselect = mysql_query($sqlselect);
                                if($resultselect != null)
                                {
                                        while($rowselect = mysql_fetch_assoc($resultselect))
                                        {
                                            if($rowdet['expledgermasid'] == $rowselect['expledgermasid'])
                                                $tbl.="<option value=".$rowselect['expledgermasid']." selected>".$rowselect['expledger']."</option>";
                                            else
                                                $tbl.="<option value=".$rowselect['expledgermasid'].">".$rowselect['expledger']."</option>";
                                        }                                        
                                }
                                $tbl .="</select></td>";
                                $tbl .= "<td id='expgrouptd' name='expgrouptd_0".$i."'>".$rowdet['expgroup']."</td>";
                                $tbl .= "<td><input type='text' class='numbersonly' name='amount_0".$i."' value=".$rowdet['amount']." maxlength='255' /></td>";
                                if($i>1)
                                $tbl .= "<td><img src='../images/delete.png' class='del_table_row'></td> </td>";                                
                                $tbl .= "</tr>";
                                $i++;
                        }
                }                
            }
    }    
    $custom = array('msg'=>$tbl,'s'=>"Success"); 
    $response_array [] = $custom;
    echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
    exit;
}
else if($load == "loaddaterange")
{
    $sql = "select transexpmasid,
            date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') as todate,
            active,buildingmasid,totalamount from trans_exp_mas where buildingmasid =
            ".$load= $_GET['itemval'];
}
else if($load == "loadexpreport")
{
    // T E S T //
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
    $buildingname = "";
    $city = "";
    $companyname = "";
    $buildingmasid = $_GET['buildingmasid'];
    $sql = "select a.buildingname, a.city, b.companyname from mas_building a
            inner join mas_company b on b.companymasid = a.companymasid where a.buildingmasid = $buildingmasid;" ;
    $result = mysql_query($sql);
    if($result != null)
    {
        $row = mysql_fetch_assoc($result);
        $buildingname = strtoupper($row['buildingname']);
        $companyname = strtoupper($row['companyname']);
        $city = strtoupper($row['city']);
    }
    //$m1 = $_GET["m1"];$m2 = $_GET["m2"];$y1 = $_GET["y1"];
    //$m3 = $_GET["m3"];$m4 = $_GET["m4"];$y2 = $_GET["y2"];
    
    $dt1 = $_GET["m1"]." to ".$_GET["m2"] ." ".$_GET["y1"];
    $dt2 = $_GET["m3"]." to ".$_GET["m4"] ." ".$_GET["y2"];
    
    $m1 =$_GET["y1"]."-".$_GET["m1"];
    $m2 = $_GET["y1"]."-".$_GET["m2"];
    
    $m3 =$_GET["y2"]."-".$_GET["m3"];
    $m4 = $_GET["y2"]."-".$_GET["m4"];    
    
    
    function diff_months($dt1,$dt2)
    {
        $tmpDate = new DateTime($dt1);
        $tmpEndDate = new DateTime($dt2);
        $outArray="";
        while($tmpDate <= $tmpEndDate)
        {
            $outArray .= strtolower($tmpDate->format('Y M')).";";
            $tmpDate->modify('+1 Month');
        }               
        return $outArray;
    }
    
    // PERIOD 1
    $period1 = diff_months($m1,$m2);
    $months="";
    $sqlExec = explode(";",$period1);
    $transexpmasid1="";$fromdate1="";$todate1="";$n1=0;
    for($i=0;$i<count($sqlExec);$i++)
    {
        if($sqlExec[$i] != "")
        {
            ////$months .= $sqlExec[$i]."</br>";           
            $sql1 = "select transexpmasid,date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') 
                        as todate, active,buildingmasid,totalamount from trans_exp_mas where date_format(fromdate,'%Y %b') = ('$sqlExec[$i]')
                        and buildingmasid=$buildingmasid;";            
            $result = mysql_query($sql1);                
            if($result != null)
            {
                $row = mysql_fetch_assoc($result);
                $transexpmasid1 .= $row['transexpmasid'].",";
                $fromdate1= date("Y", strtotime($row['fromdate']));
                $todate1=$row['todate'];
                $n1++;
            }
        }// array split loop
    }
    $transexpmasid1 = rtrim($transexpmasid1,",");
    
    
    // PERIOD 2
    $period2 = diff_months($m3,$m3);
    $months="";
    $sqlExec = explode(";",$period2);
    $transexpmasid2="";$fromdate2="";$todate2="";$n2=0;
    for($i=0;$i<count($sqlExec);$i++)
    {
        if($sqlExec[$i] != "")
        {
            $sql2 = "select transexpmasid,date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') 
                        as todate, active,buildingmasid,totalamount from trans_exp_mas where date_format(fromdate,'%Y %b') = ('$sqlExec[$i]')
                        and buildingmasid=$buildingmasid;";         
            $result = mysql_query($sql2);                
            if($result != null)
            {
                $row = mysql_fetch_assoc($result);
                $transexpmasid2 .= $row['transexpmasid'].",";
                $fromdate2=$row['fromdate'];
                $todate2=$row['todate'];
                $n2++;
            }
        }// array split loop
    }
    $transexpmasid2 = rtrim($transexpmasid2,",");
    
    
    
    
    $sqlGrp = "select expgroupmasid,expgroup from mas_exp_group where active='1';";            
    $resultGrp = mysql_query($sqlGrp);    
    
    $tblhead ="<p class='printable'><table width='95%'><tr><th>Apportionment of Service Charge to Tenants</th><tr>";
    
            $tblhead .="<tr>
                <td> $companyname <br> <br>$buildingname - $city <br><br> 
                    Period : ".$dt1."</b>
                </td>
            </tr>";
            //Period : ".$sql1." to ".date("Y", strtotime($todate1))."</b>
    $expgroupmasid="";$expgroup="";$tbl="";
    
    if($n1 > 1)
        $n1 .= " Months ";
    else
        $n1 .= " Month ";
    
    if($n2 > 1)
        $n2 .= " Months ";
    else
        $n2 .= " Month ";
        
    
    if($resultGrp != null)
    {        
        $tblhead .= "<tr><td>
                        <br><u>SUMMARY OF EXPENSES: </u><br>
                        <table  width='80%'><tr><th width='70%'>Expense Ledger</th><th>$n1 to ".$todate1."</th><th>$n2 to ".$todate2."</th></tr>";
         $grandtot1=0;$grandtot2=0;
        while($rowGrp = mysql_fetch_assoc($resultGrp))
        {
            $i=1;$rowtot1=0;$rowtot2=0;      
            $expgroupmasid =$rowGrp['expgroupmasid'];
            $expgroup=$rowGrp['expgroup'];
            $sqlexp = "select distinct a.expledgermasid,c.expledger from trans_exp_det a
                                    inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
                                    inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
                                    inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
                                    where d.expgroupmasid = '$expgroupmasid' order by expledgermasid";
            $resultexp = mysql_query($sqlexp);
            if($resultexp != null)
            {                                
                $rcountexp = mysql_num_rows($resultexp);
                if($rcountexp > 0)
                {
                    $tbl .="<table width='80%'><tr><th colspan='3' align='left'><u>".$expgroup."</u></th></tr>";
                    $tbl .="<tr><td width='70%'> </td><td align='right'>Kshs.</th><td align='right'>Kshs.</td></tr>";
                    while($rowexp = mysql_fetch_assoc($resultexp))
                    {
                        $a=false;$amount1="0";$amount2="0";$n=0;
                        $expledgermasid = $rowexp['expledgermasid'];
                        $sqldet1 = "select a.amount from trans_exp_det a
                                    inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
                                    inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
                                    inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
                                    where a.expledgermasid = '$expledgermasid' and a.transexpmasid in($transexpmasid1);";
                        $resultdet1 = mysql_query($sqldet1);
                        if($resultdet1 != null)
                        {
                            $rowdet1 = mysql_fetch_assoc($resultdet1);
                            $amount1=$rowdet1['amount'];
                            $rowtot1 +=$amount1;
                            $n = mysql_num_rows($resultdet1);
                            $grandtot1 +=$rowdet1['amount'];
                            if($n>0)
                            $a=true;
                        }
                        $sqldet2 = "select a.amount from trans_exp_det a
                                    inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
                                    inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
                                    inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
                                    where a.expledgermasid = '$expledgermasid' and a.transexpmasid in($transexpmasid2);";
                        $resultdet2 = mysql_query($sqldet2);
                        if($resultdet2 != null)
                        {
                            $rowdet2 = mysql_fetch_assoc($resultdet2);
                            $amount2 =$rowdet2['amount'];
                            $rowtot2 +=$amount2;
                            $grandtot2 +=$rowdet2['amount'];
                            $n = mysql_num_rows($resultdet2);
                            if($n>0)
                            $a=true;
                        }
                        if($a==true)
                        {
                            $tbl .= "<tr>";
                            $tbl .= "<td>".$rowexp['expledger']."</td>";
                            $tbl .= "<td align='right'>".number_format($amount1, 0, '.', ',')."</td>";
                            $tbl .= "<td align='right'>".number_format($amount2, 0, '.', ',')."</td>";
                            $tbl .= "</tr>";
                        }
                    }
                    $tbl .="<tr align='right'><td>Total :</td><td>".number_format($rowtot1, 0, '.', ',')."</td>";
                    $tbl .="<td align='right'>".number_format($rowtot2, 0, '.', ',')."</td></tr>";                                
                    $tbl .="</table><br>";
                }
            }//if
             if($rowtot1 !=0 or $rowtot2 !=0)
                $tblhead .=$tbl;
                
                $tbl="";
        }        
        //grand total
        $tblhead .="<tr><td colspan='3'>
                        <table width='80%'>
                            <tr><td width='70%' align='right'> TOTAL APPORTIONED COSTS : </td>
                            <td align='right'>".number_format($grandtot1, 0, '.', ',')."</td>
                            <td align='right'>".number_format($grandtot2, 0, '.', ',')."</td>
                        </table>                        
                    </td></tr>";
    }
    else
    {
        $tblhead .= "<tr><td> No Data Available</td></tr>";
    }
    $tblhead .="</table></p>";
    
    $custom = array('msg'=>$tblhead,'s'=>"error"); 
    $response_array [] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
   }
    catch (Exception $err)
    {
        $custom = array('msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),'s'=>'error');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit;
    }
}
//else if($load == "loadexpreport")
//{
//    $buildingname = "";
//    $city = "";
//    $companyname = "";
//    $buildingmasid = $_GET['buildingmasid'];
//    $sql = "select a.buildingname, a.city, b.companyname from mas_building a
//            inner join mas_company b on b.companymasid = a.companymasid where a.buildingmasid = $buildingmasid;" ;
//    $result = mysql_query($sql);
//    if($result != null)
//    {
//        $row = mysql_fetch_assoc($result);
//        $buildingname = strtoupper($row['buildingname']);
//        $companyname = strtoupper($row['companyname']);
//        $city = strtoupper($row['city']);
//    }
//    $transexpmasid11=$_GET['transexpmasid1'];
//    $transexpmasid12=$_GET['transexpmasid2'];    
//
//    $sqlmas1 = "select 
//            date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') as todate,
//            active,buildingmasid,totalamount from trans_exp_mas where transexpmasid=
//            ".$transexpmasid11;
//    $sqlmas2 = "select 
//            date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') as todate,
//            active,buildingmasid,totalamount from trans_exp_mas where transexpmasid=
//            ".$transexpmasid12;
//    
//    $resultmas2 = mysql_query($sqlmas2);
//    
//    $resultmas1 = mysql_query($sqlmas1);    
//    $tbl ="";
//    if($resultmas1 != null)
//    {
//        
//        while($rowmas1 = mysql_fetch_assoc($resultmas1))
//            {                                               
//                // no of months between dates
//                $date1 = strtotime($rowmas1['fromdate']);
//                $date2 = strtotime($rowmas1['todate']);
//                $months1 = 1;   $months2 = 1;  $date3=0;$date4=0;                
//                if($months1 <=1)
//                    $months1 .= " Month ";
//                else
//                    $months1 .= " Months ";
//                while (strtotime('+1 MONTH', $date1) < $date2) {
//                    $months1++;
//                    $date1 = strtotime('+1 MONTH', $date1);
//                }
//                if($resultmas2 != null)
//                {
//                    while($rowmas2 = mysql_fetch_assoc($resultmas2))
//                    {
//                        $date3 = strtotime($rowmas2['fromdate']);
//                        $date4 = strtotime($rowmas2['todate']);
//                        if($months2 <=1)
//                            $months2 .= " Month ";
//                        else
//                            $months2 .= " Months ";
//                        while (strtotime('+1 MONTH', $date3) < $date4) {
//                            $months2++;
//                            $date3 = strtotime('+1 MONTH', $date3);
//                        }
//                        $date3 = $rowmas2['fromdate'];
//                        $date4 = $rowmas2['todate'];
//                    }
//                }
//                
//                $grandtot1=0;$grandtot2=0;
//                $tblhead ="<p class='printable'><table width='95%'><tr><th>Apportionment of Service Charge to Tenants</th><tr>";
//                $tblhead .="<tr><td> $companyname <br> $buildingname - $city <br> 
//                    Period : ".date("d-M-Y", strtotime($rowmas1['fromdate']))." to ".date("t-M-Y", strtotime($rowmas1['todate']))."</b></td></tr><td>
//                    <br><u>SUMMARY OF EXPENSES: </u><br>
//                    <table  width='80%'><tr><th width='70%'></th><th>$months1 to ".$rowmas1['todate']."</th><th>$months2 to ".$date4."</th></tr></table>";                
//                $sqlexpgroup = "select expgroupmasid,expgroup from mas_exp_group where active='1' order by expgroupmasid;";
//                $resultexpgroup = mysql_query($sqlexpgroup);
//                if($resultexpgroup != null)
//                {
//                    while($rowexpgroup = mysql_fetch_assoc($resultexpgroup))
//                    {                           
//                        $i=1;$rowtot1=0;$rowtot2=0;                        
//                        $expgroupmasid = $rowexpgroup['expgroupmasid'];
//                        $expgroup = $rowexpgroup['expgroup'];
//                        $sqlexp = "select distinct a.expledgermasid,c.expledger from trans_exp_det a
//                                    inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
//                                    inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
//                                    inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
//                                    where d.expgroupmasid = '$expgroupmasid' order by expledgermasid";
//                        $resultexp = mysql_query($sqlexp);
//                        if($resultexp != null)
//                        {                                
//                            $rcountexp = mysql_num_rows($resultexp);
//                            if($rcountexp > 0)
//                            {
//                                $tbl .="<table width='80%'><tr><th colspan='3' align='left'><u>".$expgroup."</u></th></tr>";
//                                $tbl .="<tr><td width='70%'> </td><td align='right'>Kshs.</th><td align='right'>Kshs.</td></tr>";
//                                while($rowexp = mysql_fetch_assoc($resultexp))
//                                {
//                                    $a=false;$amount1="0";$amount2="0";$n=0;
//                                    $expledgermasid = $rowexp['expledgermasid'];
//                                    $sqldet1 = "select a.amount from trans_exp_det a
//                                                inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
//                                                inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
//                                                inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
//                                                where a.expledgermasid = '$expledgermasid' and a.transexpmasid ='$transexpmasid11';";
//                                    $resultdet1 = mysql_query($sqldet1);
//                                    if($resultdet1 != null)
//                                    {
//                                        $rowdet1 = mysql_fetch_assoc($resultdet1);
//                                        $amount1=$rowdet1['amount'];
//                                        $rowtot1 +=$amount1;
//                                        $n = mysql_num_rows($resultdet1);
//                                        $grandtot1 +=$rowdet1['amount'];
//                                        if($n>0)
//                                        $a=true;
//                                    }
//                                    $sqldet2 = "select a.amount from trans_exp_det a
//                                                inner join trans_exp_mas b on b.transexpmasid = a.transexpmasid
//                                                inner join mas_exp_ledger c on c.expledgermasid = a.expledgermasid
//                                                inner join mas_exp_group d on d.expgroupmasid =  c.expgroupmasid
//                                                where a.expledgermasid = '$expledgermasid' and a.transexpmasid ='$transexpmasid12';";
//                                    $resultdet2 = mysql_query($sqldet2);
//                                    if($resultdet2 != null)
//                                    {
//                                        $rowdet2 = mysql_fetch_assoc($resultdet2);
//                                        $amount2 =$rowdet2['amount'];
//                                        $rowtot2 +=$amount2;
//                                        $grandtot2 +=$rowdet2['amount'];
//                                        $n = mysql_num_rows($resultdet2);
//                                        if($n>0)
//                                        $a=true;
//                                    }
//                                    if($a==true)
//                                    {
//                                        $tbl .= "<tr>";
//                                        $tbl .= "<td>".$rowexp['expledger']."</td>";
//                                        $tbl .= "<td align='right'>".number_format($amount1, 0, '.', ',')."</td>";
//                                        $tbl .= "<td align='right'>".number_format($amount2, 0, '.', ',')."</td>";
//                                        $tbl .= "</tr>";
//                                    }
//                                }
//                                $tbl .="<tr align='right'><td>Total :</td><td>".number_format($rowtot1, 0, '.', ',')."</td>";
//                                $tbl .="<td align='right'>".number_format($rowtot2, 0, '.', ',')."</td></tr>";                                
//                                $tbl .="</table><br>";
//                            }
//                        }//if                        
//                        if($rowtot1 !=0 or $rowtot2 !=0)
//                        $tblhead .=$tbl;                        
//                        $tbl="";
//                    }
//                }
//                $tblhead .="</td><tr><td>
//                        <table width='80%'>
//                            <tr><td width='70%' align='right'> TOTAL APPORTIONED COSTS : </td>
//                            <td align='right'>".number_format($grandtot1, 0, '.', ',')."</td>
//                            <td align='right'>".number_format($grandtot2, 0, '.', ',')."</td>
//                        </table>                        
//                    </td></tr>
//                        </table></p>";
//            }
//    }    
//    $custom = array('msg'=>$tblhead,'s'=>"Success"); 
//    $response_array [] = $custom;
//    echo '{
//               "myResult":'.json_encode($response_array).',
//               "error":'.json_encode($response_array).
//           '}';
//    exit;
//}
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