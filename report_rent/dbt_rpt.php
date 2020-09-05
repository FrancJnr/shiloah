<?php
		session_start();
		if (! isset($_SESSION['myusername'])){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
        
                
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Debit Note Report</title>

<script src="jquery/jquery.min.js"></script>
<!--<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/table2CSV.js"></script>-->
<script language="javascript" type="text/javascript">

        $(document).ready(function(){
           
            

           do_sums(); 

      var ded=($('.tblmail').html());
      

    var sumcx=0; var sumax=0; var sumbx=0;        
  function sumOfColumns(table, columnIndex) {
    var tot = 0;
    table.find("tr").children("td:nth-child(" + columnIndex + ")")
    .each(function() {
        $this = $(this);
        if (!$this.hasClass("suma") &&!$this.hasClass("sumb") &&!$this.hasClass("sumc") 
            && $this.html() != "" && !isNaN($this.html())) {
           // tot += parseInt($this.html());
            tot +=parseInt($this.html().replace(/\,/g,''), 10);
           // alert(tot);
        }
    });
    return tot;
}

function do_sums() {
    //alert("ha");
    $("tr.showall").each(function(i, tr) {
        $tr = $(tr);
        $tr.children().each(function(i, td) {
            $td = $(td);
            var table = $td.parent().parent().parent();
            if ($td.hasClass("suma")) {
                //$td.text(sumOfColumns(table, i+1));
                sumax=sumOfColumns(table, i+1); 
                $td.text(sumax);
            }else
            if ($td.hasClass("sumb")) {
               // $td.text(sumOfColumns(table, i+1));
                sumbx=sumOfColumns(table, i+1);
                $td.text(sumbx)
            }else
            if ($td.hasClass("sumc")) {
                //$td.text(sumOfColumns(table, i+1));
            
                 sumcx=sumOfColumns(table, i+1);
                 
                $td.text(sumcx)
            }
        })
    });
    
    
}

 //ajaxPOSTTest();
 function ajaxPOSTTest() {
        //alert('s');
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxPOSTTestRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxPOSTTestRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxPOSTTestRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }

        ajaxPOSTTestRequest.onreadystatechange = ajaxCalled_POSTTest;
        
    
      
        var url="sendmail.php";
       
        var params =ded;//$("form").serialize();
      //  alert(ded);
        ajaxPOSTTestRequest.open("POST", url, true);
        ajaxPOSTTestRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajaxPOSTTestRequest.send(params);
    }

    //Create a function that will receive data sent from the server
    function ajaxCalled_POSTTest() {
        if (ajaxPOSTTestRequest.readyState == 4) {
//            document.getElementById("output").innerHTML = ajaxPOSTTestRequest.responseText;
       // alert(ajaxPOSTTestRequest.responseText);
        }
    }

            
        });
</script>
</head>

<!--<form id="htm">
  
    
</form>-->



<?php
//include('config.php');
// SALES REPORT EMAIL SENT THRU BAT FILES....
$buildingmasid = $_GET['buildingmasid'];
$dbtTo = date("Y-m-d",strtotime($_GET['dbtTo']));
$dbtFrom = date("Y-m-d",strtotime($_GET['dbtFrom']));
try
{    
 
    $invdtfrom = date('Y-m-01');//, strtotime("2016-03-02"));
    $invdtto =  date("Y-m-d",strtotime("-1 day"));
    $period =  "BETWEEN ".date("d-m-Y",  strtotime($dbtFrom))."  AND ".date("d-m-Y",  strtotime($dbtTo))."";


    // where query
    $foo=false;
    
    $sqldt = "date_format(e.createddatetime,'%Y-%m-%d') BETWEEN '$dbtFrom' and '$dbtTo'";
    
    $message="";
    $tablemain="<style type='text/css'>
                  .title
                  {
                    font-size: 1em;
                    font-family: �Trebuchet MS�, Arial, Helvetica, sans-serif;
                    text-align:center
                  }
                  .tblmail {
                  font-family: �Trebuchet MS�, Arial, Helvetica, sans-serif;
                  width: 100%;
                  border-collapse: collapse;
                  }
                  .tblmail td, .tblmail th {
                  font-size: 0.7em;
                  border: 1px solid #98bf21;
                  padding: 3px 7px 2px 7px;
                }
                .tblmail th {
                  font-size: 0.9em;
                  text-align: left;
                  padding-top: 2px;
                  padding-bottom: 2px;
                  background-color: #A7C942;
                  color: #ffffff;
                }
                .tblmail tr.alt td {
                  color: #000000;
                  background-color: #EAF2D3;
                }
                .suma, .sumb, .sumc {
                    font-weight: bold;
                }

              </style>";
    
    //$tablemain .="<table class='tblmail'><tr><th style='text-align: center;font-weight:bold;'>INVOICE REPORT | DATE : $from</th></tr></table>";
    $tablemain .="<p class='title'><u>MEGA PROPERTIES GROUP CREDIT NOTE REPORT  - $period</p>";    
    //$message .= $tablemain;
	$bool = false;
    $sql1 ="select companymasid , companyname from mas_company order by companymasid;";
    $result1 = mysql_query($sql1);
    $invlist =0;
    $totals=0;
    $sctotals=0;
    function commify ($str) { 
        $n = strlen($str); 
        if ($n <= 3) { 
                $return=$str;
        } 
        else { 
                $pre=substr($str,0,$n-3); 
                $post=substr($str,$n-3,3); 
                $pre=commify($pre); 
                $return="$pre,$post"; 
        }
        return($return); 
}
    if($result1 != null)
    {
        $tblclass="table"; $tbl=1;
        while($row1 = mysql_fetch_assoc($result1))
        {                
                $table0 ="</br>";
                $invlist++;
                $tblclass = "row".$tbl;
                $companyname = $row1['companyname'];
                $companymasid = $row1['companymasid'];$buildingmasid=0;
                $grandamt=0;
                $table0 .="<table class='tblmail' id ='dailyinvoicelist'.$invlist.'><tr class='titlecols'><th style='font-size: 0.9em;
                  text-align: left;
                  padding-top: 2px;
                  padding-bottom: 2px;
                  background-color: #A7C942;
                  color: #ffffff;' colspan='25'>$companyname</th></tr>";
                $sql2 ="select buildingmasid , buildingname from mas_building where companymasid= $companymasid  and buildingmasid = '' order by buildingmasid;";
                $result2 = mysql_query($sql2);
                if($result2 != null)
                {
                    $table0 .="<tr style='background-color: #A7C942;' class='titlecols' valign='bottom' align='center'>";
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
                    
                    $table0 .="<tr style='background-color: #A7C942;' class='titlecols' valign='bottom' align='center'>";
                    $table0 .="<td width='15%'></td>";
                        for($i=0;$i<$s;$i++)
                        {
                            $table0 .="<td class='cols".$tbl."' align='bottom'>Amount</td>";
                            $table0 .="<td  class='cols".$tbl."' align='bottom'>Vat</td>";
                            $table0 .="<td  class='cols".$tbl."' align='bottom'>Total</td>";
                        }
                    $table0 .="</tr>";
                    $invhead="Debit Note";
                    $table1="";
                    //$lineamounts=0;$linevats=0;$linetots=0;   
                    for($j=0;$j<1;$j++)
                    {
                        
                        $lineamount=0;$linevat=0;$linetot=0;                        
                        $table1 .="<tr style='background-color: #A7C942;'  valign='bottom' align='center'>";
                        $table1 .="<td  style='text-align: left;font-weight:bold;'> $invhead </td>";  
                        $sqlExec = explode(",",$str);
                        $n=0;
                        for($i=0;$i<count($sqlExec);$i++)
                        {                            
                            if($sqlExec[$i] != "")
                            {
                                $buildingmasid=$sqlExec[$i];                                
                            }
                            
                            $rent =0; $rentvat=0;$sc=0;$scvat=0;$total=0;
                            $rent1 =0; $rentvat1=0;$sc1=0;$scvat1=0;$total1=0;
                         
							 //debit notes
                            $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,debitnoteno,sum(totalvalue) as totalvaluedr,sum(totalvat)) as totalvatdr
                                ,sum(totalvalue+totalvat)as totaldr
                                from group_tenant_mas a
                                inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                                inner join mas_building d on d.buildingmasid = c.buildingmasid
                                inner join  invoice_dr_mas e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt;";
                            $resultdet = mysql_query($sqldet);
                            $totalvaluecr =0; $totalvatcr=0;  $totalcr=0;
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $totalvaluecr +=$rowdet['totalvaluedr']; 
                                    $totalvatcr +=$rowdet['totalvatdr'];
                                    $totalcr +=$rowdet['totaldr'];  
                                    
                                    //$rent2 +=$rent1+$rent; $rentvat2=0;$sc2 += $sc1+$sc; $total2+=$total1+$total;
                                   //$tatuli+=$rent+$sc;
                                }
                               // $sctotals=$total;
                            }
							
							 //debit notes
                            /* $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,debitnoteno,sum(totalvalue) as totalvaluedr,sum(totalvat)) as totalvatdr
                                ,sum(totalvalue+totalvat)as totaldr
                                from group_tenant_mas a
                                inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                                inner join mas_building d on d.buildingmasid = c.buildingmasid
                                inner join  invoice_dr_mas e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt;";
                            $resultdet = mysql_query($sqldet);
                            $totalvaluedr =0; $totalvatdr=0; $totaldr=0;
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $totalvaluedr +=$rowdet['totalvaluedr']; 
                                    $totalvatdr +=$rowdet['totalvatdr'];
                                    $totaldr +=$rowdet['totaldr'];  
                                    
                                   
                                }
                          
                            } */
                             if($j==0)
                                {                                    
                                    
                                    $table1 .="<td class='showa' id='$tblclass".$n++."'>".number_format($totalvaluecr, 0, '.', '')."</td>";                                            
                                    $table1 .="<td class='showb' id='$tblclass".$n++."'>".number_format($totalvatcr, 0, '.', '')."</td>";
                                    $table1 .="<td class='showc' id='$tblclass".$n++."'>".number_format($totalcr, 0, '.', '')."</td>";
             
                                    
                                    //$invhead="Debit Note";
                                    //line tot calc
                                    //$lineamount +=$rent;
									$lineamount +=$totalvaluecr;
									$linevat +=$totalvatcr;
									                              
									$linetot += $totalvaluecr + $totalvatcr;
									
                                    
                               
                  
                                }
                                /* else
                                {
                                    $linesc =$sc;
                                    $table1 .="<td class='showa' id='$tblclass".$n++."'>".number_format($sc, 0, '.', '')."</td>";                                            
                                    $table1 .="<td class='showb' id='$tblclass".$n++."'>".number_format($scvat, 0, '.', '')."</td>";
                                    $sctot = $sc+$scvat;
                                    $table1 .="<td class='showc' id='$tblclass".$n++."'>".number_format($sctot, 0, '.', '')."</td>";                                                                        
                                                                                                         
                                    $lineamount +=$totalvaluedr;
									$linevat +=$totalvatdr;
									$linetot += $totalvaluedr + $totalvatdr;
                                    //line tot calc
                                    //$lineamount +=$sc;
                                    //$linevat +=$scvat;
                                    //$linetot += $sc + $scvat;  

                                } */
                        } 
                        //line total                        
                        $table1 .="<td class='showa' id='$tblclass".$n++."'>".number_format($lineamount, 0, '.', '')."</td>";
                        $table1 .="<td class='showb' id='$tblclass".$n++."'>".number_format($linevat, 0, '.', '')."</td>";
                        $table1 .="<td class='showc' id='$tblclass".$n++."'>".number_format($linetot, 0, '.', '')."</td>";
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
                     /*$sql3= "select a.invoicedescmasid,c.invoicedesc from invoice_man_det a
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
                     */
                    //for($j=0;$j<=$rcount3;$j++)
                   //{
                        $table2="";
                        //$row3 = mysql_fetch_assoc($result3);                        
                        //$invdescmasid = $row3['invoicedescmasid'];
                        
                        //if($j < $rcount3)
                       // {
                         //   $table2 .="<tr style='background-color: #A7C942;' valign='bottom' align='center'>";
                         //   $table2 .="<td style='text-align: left;font-weight:bold;'>".'Credit Note'."</td>";
                       // }else{
							
						
							$table2 .="<tr style='background-color: #A7C942;' class='showall' valign='bottom' align='center'>";
                            $table2 .="<td><b>Cross Total</b></td>";	
							
						//}
                        
                        
                        $lineamount=0;$linevat=0;$linetot=0;$d=1;
                        $n=0;                        
                        for($i=0;$i<count($sqlExec);$i++)
                        {
                            if($sqlExec[$i] != "")
                            {
                                $buildingmasid=$sqlExec[$i];                                
                            }
                       /*      $sql4 = "select sum(b.value) as inv_val ,sum(b.vat) as inv_vat  from invoice_man_mas e
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
                            }   */      

						//credit notes
  /*                           $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,creditnoteno,sum(totalvalue) as totalvaluecr,sum(round((totalvalue*16/100))) as totalvatcr
                                ,sum(totalvalue+round((totalvalue*16/100))) as totalcr
                                from group_tenant_mas a
                                inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                                inner join mas_building d on d.buildingmasid = c.buildingmasid
                                inner join  invoice_cr_mas e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt;";
								//die($sqldet);
                            $resultdet = mysql_query($sqldet);
							$rcount3 =mysql_num_rows($resultdet);
                            $totalvaluecr =0; $totalvatcr=0;  $totalcr=0;
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $totalvaluecr +=$rowdet['totalvaluecr']; 
                                    $totalvatcr +=$rowdet['totalvatcr'];
                                    $totalcr +=$rowdet['totalcr'];  
                             
                                }
                         
                            } */							
                                
								
								
								/* if($j < $rcount3)
                                {
                                    $table2 .="<td class='showa' id='$tblclass".$n++."'> ".number_format($totalvaluecr, 0, '.', '')." </td>";
                                    $table2 .="<td class='showb' id='$tblclass".$n++."'> ".number_format($totalvatcr, 0, '.', '')." </td>";
                                    $table2 .="<td class='showc' id='$tblclass".$n++."'> ".number_format($totalcr, 0, '.', '')."</td>";                                    
                                    
                                    $lineamount +=$totalvaluecr;
                                    $linevat +=$totalvatcr;
                                    $linetot += $totalvaluecr + $totalvatcr;
                                    
//                
                                   
                                }
                                else
                                { */
                                
                                   //GRAND TOTAL - building
                                    $kp = "id='tot".$tbl.$n++."'";
                                    $table2 .="<td class='suma' id='tot".$tbl.$n++."'></td>";                                    
                                    $table2 .="<td class='sumb' id='tot".$tbl.$n++."'></td>";                                    
                                    $table2 .="<td class='sumc' id='tot".$tbl.$n++."'></td>";
                             //   }
								
								
                        }
                         //line total
                       /*  if($j < $rcount3)
                        {                            
                            $table2 .="<td class='showa' id='$tblclass".$n++."'>".number_format($lineamount, 0, '.', '')."</td>";
                            $table2 .="<td class='showb' id='$tblclass".$n++."'>".number_format($linevat, 0, '.', '')."</td>";
                            $table2 .="<td class='showc' id='$tblclass".$n++."'>".number_format($linetot, 0, '.', '')."</td>";
                        }
                        else
                        { */
                            //GRAND TOTAL - total                            
                            $table2 .="<td class='suma' id='tot".$tbl.$n++."'></td>";                            
                            $table2 .="<td class='sumb' id='tot".$tbl.$n++."'></td>";                            
                            $table2 .="<td class='sumc' id='tot".$tbl.$n++."'></td>";
                        /* } */
                        if($lineamount > 0)
                        {
                            $bool = true;
                            $table2 .="</tr>";
                            $table0  .=$table2; 
                        }
                    //}   

                    /*  $table2 .="<tr style='background-color: #A7C942;' class='showall' valign='bottom' align='center'>";
                     $table2 .="<td><b>Debit Notes</b></td>";	

						$table2 .="<tr style='background-color: #A7C942;' class='showall' valign='bottom' align='center'>";
                        $table2 .="<td><b>Credit Notes</b></td>"; */	

							
                    $arr="";
                    $table0  .=$table2; 
                }                 
            $table0 .="</table>";            
            if($bool == true)
            {
                $tablemain  .=$table0;
				$message .=$tablemain;
                $bool = false;
                $tbl++;
                $foo=true;
            }            
        }
    }
	$subject = "Debit Note Report - $period";        
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    $headers .= "From:MEGA PMS ERP" . "\r\n";

     $address = "arulraj@shiloahmega.com";
	//$address = "jacobshavia@gmail.com";
     $mail->AddAddress($address, "Arulraj");	
   //  $address = "jacobshavia@gmail.com";
    // $mail->AddAddress($address, "Jacob");	
     $mail->IsHTML(true);
	 		$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('5','2','9','4') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
	
    // $recipients = array(	
     // 'creditcontrol-ho@shiloahmega.com' => 'Credit Ho',
	 // 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
	  // 'ronald-accounts@shiloahmega.com' => 'Ronald',
	 // 'arulraj@shiloahmega.com' => 'ArulRaj',
	 // 'dipak@shiloahmega.com' => 'Dipak',
	 // 'mitesh@shiloahmega.com' => 'Mitesh' 
	 // /*'jacobshavia@gmail.com' => 'jacob'*/  
    // );        
    foreach($recipients as $email => $name)
    {
       $mail->AddCC($email, $name);
    }

    if(is_array($_POST))
    {        
        $mail->Subject    = $subject;
       // $msg=<script> $('.tblmail').html();</script>
	   //$mail->Body=html_entity_decode($message);
       $mail->MsgHTML($message);
        if(!$mail->Send())
        {		     		
           echo($mail->ErrorInfo);
        } else
        {
            echo "mail sent";
        }
       // echo $tablemain;
        //echo $message;
    }
    else
    {
        echo "</br><center><h3>No Debit Notes Found for the period - $period";
   }
 
    if($foo ==true)
   {        

       echo $tablemain;
        //echo $message;
    }
    else
    {
        echo "</br><center><h3>No Debit Notes  Found for the period - $period";
   }

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

</html>