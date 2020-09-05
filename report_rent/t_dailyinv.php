<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Daily Invoice Report</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    if($_SERVER['REQUEST_METHOD'] == "POST")  
    {
        print('<pre>');
        print_r($_POST);
        print('</pre>');
        exit;
    }
try
{    
    //$invdt = explode("-",$_GET['invdt']);
    //$invdt = $invdt[2]."-".$invdt[1]."-".$invdt[0];    
    $invdt ="2013-09-14";
    // where query
    $sqldt = "date_format(e.createddatetime,'%Y-%m-%d') = '$invdt'";
    
    ////$sqldt = " date_format(e.createddatetime,'%Y-%m-%d') between '$invdt' and '2013-09-30' ";    
    
    $tablemain = "<center><b><u>INVOICE REPORT FOR THE PERIOD ".$invdt."</u></br> ";
    $tablemain .="<p>";    
    $bool = false;
    $sql1 ="select companymasid , companyname from mas_company order by companymasid;";
    $result1 = mysql_query($sql1);
    if($result1 != null)
    {
        $tblclass="table"; $tbl=1;
        while($row1 = mysql_fetch_assoc($result1))
        {                
                $table0 ="</br>";
                $tblclass = "row".$tbl;
                $companyname = $row1['companyname'];
                $companymasid = $row1['companymasid'];$buildingmasid=0;
                $grandamt=0;
                $table0 .="<table class='table' width='100%' border='1'><tr ><th colspan='13'>$companyname</th></tr>";
                $sql2 ="select buildingmasid , buildingname from mas_building where companymasid= $companymasid order by buildingmasid;";
                $result2 = mysql_query($sql2);
                if($result2 != null)
                {
                    $table0 .="<tr valign='bottom' align='center'>";
                    $table0 .="<td width='13%'></td>";
                    $s=1;
                    while($row2 = mysql_fetch_assoc($result2))
                    {
                        $table0 .="<td align='center' colspan='3'>".$row2['buildingname'];                                                
                        $table0 .="</td>";   
                        $s++;                        
                        $arr[] = $row2['buildingmasid'];
                    }
                    $str  = implode($arr, ',');
                    $table0 .="<td valign='bottom' colspan='3' width='25%'>Line Total</td>";                   
                    $table0 .="</tr>";
                    
                    $table0 .="<tr valign='bottom' align='center'>";
                        $table0 .="<td width='15%'></td>";
                        for($i=0;$i<$s;$i++)
                        {
                            $table0 .="<td class='cols".$tbl."' align='bottom'>Amount</td>";
                            $table0 .="<td class='cols".$tbl."' align='bottom'>Vat</td>";
                            $table0 .="<td class='cols".$tbl."' align='bottom'>Total</td>";
                        }
                    $table0 .="</tr>";
                    $invhead="Rent";
                    $table1="";
                    for($j=0;$j<2;$j++)
                    {
                        $lineamount=0;$linevat=0;$linetot=0;                        
                        $table1 .="<tr valign='bottom' align='center'>";
                        $table1 .="<td> $invhead </td>";                                            
                        $sqlExec = explode(",",$str);
                        $n=0;
                        for($i=0;$i<count($sqlExec);$i++)
                        {                            
                            if($sqlExec[$i] != "")
                            {
                                $buildingmasid=$sqlExec[$i];                                
                            }
                            
                            $rent =0; $rentvat=0;$sc=0;$scvat=0;$total=0;
                            //invoiced
                            $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*14/100))) as rentvat,sum(sc) as sc,sum(round((sc*14/100))) as scvat
                                        ,sum(rent+round((rent*14/100))+sc+round((sc*14/100))) as total
                                        from group_tenant_mas a
                                        inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                                        inner join mas_building d on d.buildingmasid = c.buildingmasid
                                        inner join invoice e on e.grouptenantmasid = a.grouptenantmasid
                                        where c.buildingmasid ='$buildingmasid'  and   c.companymasid ='$companymasid' and 
                                        $sqldt ;";
                            $resultdet = mysql_query($sqldet);
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $rent +=$rowdet['rent']; $rentvat +=$rowdet['rentvat'];
                                    $sc +=$rowdet['sc']; $scvat +=$rowdet['scvat'];
                                    $total +=$rowdet['total'];                                    
                                }
                            }
                            //advance_rent
                            $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*14/100))) as rentvat,sum(sc) as sc,sum(round((sc*14/100))) as scvat
                                ,sum(rent+round((rent*14/100))+sc+round((sc*14/100))) as total
                                from group_tenant_mas a
                                inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                                inner join mas_building d on d.buildingmasid = c.buildingmasid
                                inner join advance_rent e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt ;";
                            $resultdet = mysql_query($sqldet);
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $rent +=$rowdet['rent']; $rentvat +=$rowdet['rentvat'];
                                    $sc +=$rowdet['sc']; $scvat +=$rowdet['scvat'];
                                    $total +=$rowdet['total'];                                  
                                }
                            }
                             if($j==0)
                                {                                    
                                    
                                    $table1 .="<td class='$tblclass".$n++."'>".number_format($rent, 0, '.', ',')."</td>";                                            
                                    $table1 .="<td class='$tblclass".$n++."'>".number_format($rentvat, 0, '.', ',')."</td>";
                                    $renttot = $rent+$rentvat;
                                    $table1 .="<td class='$tblclass".$n++."'>".number_format($renttot, 0, '.', ',')."</td>";
                                    
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";                                            
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";
                                    //$renttot = $rent+$rentvat;
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";
                                    
                                    $invhead="Sc";
                                    //line tot calc
                                    $lineamount +=$rent;
                                    $linevat +=$rentvat;
                                    $linetot += $rent + $rentvat;
                                }
                                else
                                {
                                    $linesc =$sc;
                                    $table1 .="<td class='$tblclass".$n++."'>".number_format($sc, 0, '.', ',')."</td>";                                            
                                    $table1 .="<td class='$tblclass".$n++."'>".number_format($scvat, 0, '.', ',')."</td>";
                                    $sctot = $sc+$scvat;
                                    $table1 .="<td class='$tblclass".$n++."'>".number_format($sctot, 0, '.', ',')."</td>";                                                                        
                                    
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";                                            
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";
                                    //$sctot = $sc+$scvat;
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";                                                                        
                                    
                                    //line tot calc
                                    $lineamount +=$sc;
                                    $linevat +=$scvat;
                                    $linetot += $sc + $scvat;                                    
                                }                               
                        }
                        //line total                        
                        $table1 .="<td class='$tblclass".$n++."'>".number_format($lineamount, 0, '.', ',')."</td>";
                        $table1 .="<td class='$tblclass".$n++."'>".number_format($linevat, 0, '.', ',')."</td>";
                        $table1 .="<td class='$tblclass".$n++."'>".number_format($linetot, 0, '.', ',')."</td>";
                        $table1 .="</tr>";
                        if($lineamount > 0)
                        {
                            $bool = true;
                        }
                    }
                    $arr="";
                    if($bool == true)
                    {
                        $table0  .=$table1;                        
                    }                    
                    // manual invoice
                    $sql3= "select a.invoicedescmasid,c.invoicedesc from invoice_man_det a
                            inner join invoice_man_mas e on e.invoicemanmasid  = a.invoicemanmasid
                            inner join invoice_desc c on c.invoicedescmasid = a.invoicedescmasid
                            where $sqldt
                            group by a.invoicedescmasid;";
                    $result3 = mysql_query($sql3);
                    $rcount3 =0;$grandtr=0;
                    if($result3 != null)
                    {
                        $rcount3 =mysql_num_rows($result3);
                        $grandtr=$rcount3;
                    }
                    
                    for($j=0;$j<=$rcount3;$j++)
                    {
                        $table2="";
                        $row3 = mysql_fetch_assoc($result3);                        
                        $invdescmasid = $row3['invoicedescmasid'];
                        
                        if($j < $rcount3)
                        {
                            $table2 .="<tr valign='bottom' align='center'>";
                            $table2 .="<td>".$row3['invoicedesc']."</td>";
                        }
                        else
                        {
                            $table2 .="<tr valign='bottom' align='center'>";
                            $table2 .="<td>GRAND TOTAL</td>";
                        }
                        
                        $lineamount=0;$linevat=0;$linetot=0;$d=1;
                        $n=0;                        
                        for($i=0;$i<count($sqlExec);$i++)
                        {
                            if($sqlExec[$i] != "")
                            {
                                $buildingmasid=$sqlExec[$i];                                
                            }
                            $sql4 = "select sum(b.value) as inv_val ,sum(b.vat) as inv_vat  from invoice_man_mas e
                                inner join invoice_man_det b on b.invoicemanmasid  = e.invoicemanmasid
                                inner join invoice_desc c on c.invoicedescmasid = b.invoicedescmasid
                                inner join mas_building d on d.buildingmasid = e.buildingmasid
                                where e.buildingmasid='$buildingmasid' and d.companymasid ='$companymasid'
                                and $sqldt
                                and c.invoicedescmasid='$invdescmasid'";
                            $result4 = mysql_query($sql4);
                            $inv_value=0;$inv_vat=0;$inv_tot=0;
                            $cnt=0;
                            if($result4 != null)
                            {
                                $cnt =  mysql_num_rows($result4);
                                $row4 = mysql_fetch_assoc($result4);
                                $inv_val = $row4['inv_val'];
                                $inv_vat = $row4['inv_vat'];
                                $inv_tot = $inv_val+$inv_vat;
                            }                           
                                if($j < $rcount3)
                                {
                                    $table2 .="<td class='$tblclass".$n++."'> ".number_format($inv_val, 0, '.', ',')." </td>";
                                    $table2 .="<td class='$tblclass".$n++."'> ".number_format($inv_vat, 0, '.', ',')." </td>";
                                    $table2 .="<td class='$tblclass".$n++."'> ".number_format($inv_tot, 0, '.', ',')."</td>";                                    
                                    
                                    $lineamount +=$inv_val;
                                    $linevat +=$inv_vat;
                                    $linetot += $inv_val + $inv_vat;
                                }
                                else
                                {
                                   //GRAND TOTAL - building
                                    //$kp = "id='tot".$tbl.$n++."'";
                                    $table2 .="<td id='tot".$tbl.$n++."'> - </td>";                                    
                                    $table2 .="<td id='tot".$tbl.$n++."'> - </td>";                                    
                                    $table2 .="<td id='tot".$tbl.$n++."'> - </td>";
                                }
                        }
                         //line total
                        if($j < $rcount3)
                        {                            
                            $table2 .="<td class='$tblclass".$n++."'>".number_format($lineamount, 0, '.', ',')."</td>";
                            $table2 .="<td class='$tblclass".$n++."'>".number_format($linevat, 0, '.', ',')."</td>";
                            $table2 .="<td class='$tblclass".$n++."'>".number_format($linetot, 0, '.', ',')."</td>";
                        }
                        else
                        {
                            //GRAND TOTAL - total                            
                            $table2 .="<td id='tot".$tbl.$n++."'> * </td>";                            
                            $table2 .="<td id='tot".$tbl.$n++."'> * </td>";                            
                            $table2 .="<td id='tot".$tbl.$n++."'> * </td>";
                        }
                        if($lineamount > 0)
                        {
                            $bool = true;
                            $table2 .="</tr>";
                            $table0  .=$table2; 
                        }
                    }                                        
                    $arr="";
                    $table0  .=$table2; 
                }                 
            $table0 .="</table></br></br>";            
            if($bool == true)
            {
                $tablemain  .=$table0;
                $bool = false;
                $tbl++;
            }            
        }
    }
    $tablemain .="<p>";
    $k = '<script type="text/javascript" language="javascript">
$(document).ready(function() {    
    //var $tblcnt=0;
    //$(".table").each(function() {
    //    $tblcnt++;			    
    //});
    //alert($tblcnt);
    			var $tblcnt=0;
			$(".table").each(function() {
			    $tblcnt++;			    
			});									
                        //alert($tblcnt);
                        $i=0;
                        var col = "cols"+$i;
                        alert(col);
                        $("#tot10").html("125");
});	
</script>';
    echo $k;            
    echo $tablemain;
    ini_set('SMTP','192.168.0.1');// DEFINE SMTP MAIL SERVER
require_once('../PHPMailer/class.phpmailer.php');
$mail = new PHPMailer(); // defaults to using php "mail()"
$mail->SetFrom('info@shiloahmega.com', 'PMS Admin');
$mail->AddReplyTo('info@shiloahmega.com', 'PMS Admin');

		$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('7') AND active = '1' LIMIT 1";
$result12=mysql_query($sql12);
		while($row12 = mysql_fetch_array($result12 ))
    {
   $address = $row12['email']; 
	}
//$address = "juma@shiloahmega.com";
$mail->AddAddress($address, "Prabhu");

//$address = "juma@shiloahmega.com";
//$mail->AddAddress($address, "Prabhu");
//$recipients = array(
//   'dipak@shiloahmega.com' => 'Dipak',
//   'mitesh@shiloahmega.com' => 'Mitesh',
//   'arulraj@shiloahmega.com' => 'Arul Raj',
//   'juma@shiloahmega.com' => 'Prabhu'
//);
//foreach($recipients as $email => $name)
//{
//   $mail->AddCC($email, $name);
//}
$mail->Subject    = "Daily Invoice Status";
$mail->MsgHTML($tablemain);

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
    //$custom = array(
    //            'result'=> $tablemain,
    //            's'=>'Success');
    //$response_array[] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
    
}
catch (Exception $err)
{
    $custom = array(
                'result'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {    
    //var $tblcnt=0;
    //$(".table").each(function() {
    //    $tblcnt++;			    
    //});
    //alert($tblcnt);
//    			var $tblcnt=0;
//			$(".table").each(function() {
//			    $tblcnt++;			    
//			});						
//			$i=0;$j=0;
//			var totalsales = 0;			    
//			for($i=1;$i<=$tblcnt;$i++)
//			{			    
//			    var $clcnt=0
//			    var col = 'cols'+$i;			    
//			    $('.'+col).each(function() {
//				$clcnt++;				
//			    });
//			    for($j=0;$j<$clcnt;$j++)
//			    {
//				var sum = 0;			    
//				var c = 'row'+$i+$j;			    
//				$('.'+c).each(function() {				
//				    var value = removecomma($(this).text());				
//				    if(!isNaN(value) && value.length != 0) {
//					sum += parseFloat(value);
//					//$('#cc').html(value);
//				    }				    				    
//				});
//				var tot = 'tot'+$i+$j;		
//				$('#'+tot).html("<b>"+commafy(sum)+"</b>");
//			    }			   
//			}
//			// group total sales
//			var totsale ="<table width=100% border='1'>";
//			totsale +="<th colspan=20> Mega Properties Group  Sales: </th>";
//			var $t0=0;var $t1=0;var $t2=0;
//			for($i=1;$i<=$tblcnt;$i++)
//			{
//			    var $clcnt=0			    
//			    var col = 'cols'+$i;		    
//			    $('.'+col).each(function() {
//				$clcnt++;				
//			    });			    
//			    $('#cc').html("");
//			    $clcnt = $clcnt-1;
//			    //totsale +="<tr>";    
//			    for($j=0;$j<$clcnt;$j++)
//			    {				
//				totalsales=0;
//				var $d= $clcnt--;				
//				if($j<3)
//				{
//				    var c = 'tot'+$i+$d;
//				    var $txt = $('#'+c).text();
//				    //totsale +="<td id='grptot"+$j+"' align=right>"+$txt+"</td>";
//				    if($j==0)
//				    {
//					var value = removecomma($txt);
//					if(!isNaN(value) && value.length != 0) {
//					    $t0 += parseFloat(value);					  
//					}				    		
//				    }
//				    else if($j==1)
//				    {
//					var value = removecomma($txt);
//					if(!isNaN(value) && value.length != 0) {
//					    $t1 += parseFloat(value);					  
//					}				    		
//				    }
//				    else if($j==2)
//				    {
//					var value = removecomma($txt);
//					if(!isNaN(value) && value.length != 0) {
//					    $t2 += parseFloat(value);					  
//					}				    		
//				    }
//				}				
//			    }
//			    //totsale +="</tr>";			    
//			}
//			    totsale +="<tr>";
//			    totsale +="<td align=right>Total: </td>";
//			    totsale +="<td align=right>"+commafy($t2)+"</td>";
//			    totsale +="<td align=right>"+commafy($t1)+"</td>";
//			    totsale +="<td align=right>"+commafy($t0)+"</td>";
//			    totsale +="</tr>";
//			totsale +="</table>";
//			for($j=0;$j<3;$j++)
//			{
//			    var sum = 0;			    
//			    var c = 'grptot'+$j;
//			    var $t = $('[id^="grptot0"]').text();
//			    $('#cc').html($t);
//				//$('.'+c).each(function() {				    
//				//    var value = removecomma($(this).text());				
//				//    if(!isNaN(value) && value.length != 0) {
//				//	sum += parseFloat(value);
//				//	$('#cc').html(value);
//				//    }				    				    
//				//});
//				//var tot = 'tot'+$i+$j;		
//				//$('#'+tot).html("<b>"+commafy(sum)+"</b>");
//			}
//			$('#result').append(totsale);
//    function removecomma(val)
//    {
//        return String(val).replace(/\,/g, '');			    
//    }
//    function commafy(nStr) {
//        nStr += '';
//        var x = nStr.split('.');
//        var x1 = x[0];
//        var x2 = x.length > 1 ? '.' + x[1] : '';
//        var rgx = /(\d+)(\d{3})/;
//        while (rgx.test(x1)) {
//                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
//        }
//        return x1 + x2;
//    }
    ////$('form').submit();
});	
</script>
</head>
<body>
    <form id="myForm" name="myForm" action="#" method="post">
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>    
    
    <div id='result'>
    
    </div>
    <input type='text' id='n' name='n' value='123456789' />
    </form>
</body>
</html>
<?php
//**************************** EMAIL *************************//



//**************************** EMAIL *************************//
?>