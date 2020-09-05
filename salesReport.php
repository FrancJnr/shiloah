<?php
		session_start();
		if (! isset($_SESSION['myusername'])){
			header("location:index.php");
		}
		include('config.php');
		include('MasterRef_Folder.php');
        
                
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTFD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Sales Report</title>
        <style type="text/css">
            .right {
                text-align: right;
            }
        </style>
<script src="jquery/jquery.min.js"></script>
<!--<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/table2CSV.js"></script>-->
<script language="javascript" type="text/javascript">

        $(document).ready(function(){
            //alert('Voila!');
            

           do_sums(); 

      var ded=($('.tblmail').html());
      
//     var a = confirm("Would you like to send mail?");
//	if (a== true)
//	{
//         //alert($('.tblmail').html()); 
//         //$('#htm').append($('.tblmail').html());
//          //parent.top.$('div[name=masterdivtest]').html("<iframe name='tenantiframe' src='masters/mas_enquiry_updated.php?action=new' id='the_iframe2' scrolling='yes' width='100%'></iframe>");   
//           //var url="sendmail.php?table="+$('#htm').html();		
//			var url="sendmail.php?item=maildetails&itemval="+$('#htm').html();
//                        alert(url)
//			$.getJSON(url,function(data){
//				$.each(data.error, function(i,response){
//					if(response.s == "Success")
//					{
//					
//                                            alert('be back')
//                                        }
//                                    });
//                                    
//                        }); 
//    
//
//
//       }
//    

//     function getCSVData(){
//  var csv_value=$('.tblmail').table2CSV({delivery:'value'});
//  //$("#csv_text").val(csv_value);  
//}               

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
try
{    
    //$invoice_date_from = $_GET['invdtfrom'];    
    //$invdtfrom = explode("-",$_GET['invdtfrom']);
    //$invdtfrom = $invdtfrom[2]."-".$invdtfrom[1]."-".$invdtfrom[0];
    $invdtfrom = date('Y-m-01');//, strtotime("2016-03-02"));
    //$from = date('01-m-Y')."  to ". date('d-m-Y');
    
    //$invoice_date_to = $_GET['invdtto'];    
    //$invdtto = explode("-",$_GET['invdtto']);
    //$invdtto = $invdtto[2]."-".$invdtto[1]."-".$invdtto[0];
   //$invdtto =  date("Y-m-d",strtotime("2017-12-15"));
   //$period =  date("d-m-Y",strtotime("2017-12-15"));
    $invdtto =  date("Y-m-d",strtotime("-1 day"));
    $period =  date("d-m-Y",strtotime("-1 day"));

    // where query
    $foo=false;
    
    $sqldt = "date_format(e.createddatetime,'%Y-%m-%d') = '$invdtto'";
    //$sqldt = "date_format(e.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
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
    $tablemain .="<p class='title'><u>MEGA PROPERTIES GROUP SALES REPORT  - $period</p>";    
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
                $sql2 ="select buildingmasid , buildingname from mas_building where companymasid= $companymasid  and buildingmasid not in (10,11) order by buildingmasid;";
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
                    $invhead="Rent";
                    $table1="";
                    //$lineamounts=0;$linevats=0;$linetots=0;   
                    for($j=0;$j<2;$j++)
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
                            //invoiced
                            $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*16/100))) as rentvat,sum(sc) as sc,sum(round((sc*16/100))) as scvat
                                        ,sum(rent+round((rent*16/100))+sc+round((sc*16/100))) as total
                                        from group_tenant_mas a                                        
                                        inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                                        inner join mas_building d on d.buildingmasid = c.buildingmasid
                                        inner join invoice e on e.grouptenantmasid = a.grouptenantmasid
                                        where c.buildingmasid ='$buildingmasid' and c.companymasid ='$companymasid' and 
                                        $sqldt;";
                            $resultdet = mysql_query($sqldet);
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $rent +=$rowdet['rent']; 
                                    $rentvat +=$rowdet['rentvat'];
                                    $sc +=$rowdet['sc']; $scvat +=$rowdet['scvat'];
                                    $total +=$rowdet['total']; 
                                    //$totalstotal+=$rent+$rentvat+$sc
                                }
                              //  $totals=$total;
                            }
                            //advance_rent
                            $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*16/100))) as rentvat,sum(sc) as sc,sum(round((sc*16/100))) as scvat
                                ,sum(rent+round((rent*16/100))+sc+round((sc*16/100))) as total
                                from group_tenant_mas a
                                inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                                inner join mas_building d on d.buildingmasid = c.buildingmasid
                                inner join advance_rent e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt;";
                            $resultdet = mysql_query($sqldet);
                            $rent2 =0; $rentvat2=0;$sc2=0; $total2=0;
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $rent +=$rowdet['rent']; 
                                    $rentvat +=$rowdet['rentvat'];
                                    $sc +=$rowdet['sc']; 
                                    $scvat +=$rowdet['scvat'];
                                    $total +=$rowdet['total'];  
                                    
                                    //$rent2 +=$rent1+$rent; $rentvat2=0;$sc2 += $sc1+$sc; $total2+=$total1+$total;
                                   //$tatuli+=$rent+$sc;
                                }
                               // $sctotals=$total;
                            }
							
							 //credit notes
                            $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,creditnoteno,sum(totalvalue) as totalvaluecr,sum(round((totalvalue*16/100))) as totalvatcr
                                ,sum(totalvalue+round((totalvalue*16/100))as totalcr
                                from group_tenant_mas a
                                inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                                inner join mas_building d on d.buildingmasid = c.buildingmasid
                                inner join  invoice_cr_mas e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt;";
                            $resultdet = mysql_query($sqldet);
                            $totalvaluecr =0; $totalvatcr=0;
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $totalvaluecr +=$rowdet['totalvaluecr']; 
                                    $totalvatcr +=$rowdet['totalvatcr'];
                                    $totalcr +=$rowdet['totalcr'];  
                                    
                                    //$rent2 +=$rent1+$rent; $rentvat2=0;$sc2 += $sc1+$sc; $total2+=$total1+$total;
                                   //$tatuli+=$rent+$sc;
                                }
                               // $sctotals=$total;
                            }
							
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
                            $totalvaluecr =0; $totalvatcr=0;
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    $totalvaluedr +=$rowdet['totalvaluedr']; 
                                    $totalvatdr +=$rowdet['totalvatdr'];
                                    $totaldr +=$rowdet['totaldr'];  
                                    
                                   
                                }
                          
                            }
                             if($j==0)
                                {                                    
                                    
                                    $table1 .="<td class='showa right' id='$tblclass".$n++."'>".number_format($rent)."</td>";                                            
                                    $table1 .="<td class='showb right' id='$tblclass".$n++."'>".number_format($rentvat)."</td>";
                                    $renttot = $rent+$rentvat;
                                    $table1 .="<td class='showc right' id='$tblclass".$n++."'>".number_format($renttot)."</td>";
                                   // $table1.="<tr> class='rowDataSd' id='$tblclass".$n++."'>".number_format($renttot, 0, '.', ',')."</tr>";
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";                                            
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";
                                    //$renttot = $rent+$rentvat;
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";
                                    
                                    $invhead="Sc";
                                    //line tot calc
                                    $lineamount +=$rent;
									$lineamountcr +=$totalvaluecr;
									$lineamountdr +=$totalvaluedr;
                                    $linevat +=$rentvat;
									$linevatcr +=$totalvatcr;
									$linevatdr +=$totalvatdr;
                                    $linetot += $rent + $rentvat;
									$linetotcr += $totalvaluecr + $totalvatcr;
									$linetotdr += $totalvaluedr + $totalvatdr;
                                    
                               
                                    
//                                    $crosstot +=$linetot+= $linetot;
//                                    $crossamount +=$lineamount+=$lineamount;
//                                    $crossvat +=$linevat+=$linevat;
                                }
                                else
                                {
                                    $linesc =$sc;
                                    $table1 .="<td class='showa right' id='$tblclass".$n++."'>".number_format($sc)."</td>";                                            
                                    $table1 .="<td class='showb right' id='$tblclass".$n++."'>".number_format($scvat)."</td>";
                                    $sctot = $sc+$scvat;
                                    $table1 .="<td class='showc right' id='$tblclass".$n++."'>".number_format($sctot)."</td>";                                                                        
                                    
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";                                            
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";
                                    //$sctot = $sc+$scvat;
                                    //$table1 .="<td class='$tblclass".$n++."'>".$tblclass.$n."</td>";                                                                        
                                    
                                    //line tot calc
                                    $lineamount +=$sc;
                                    $linevat +=$scvat;
                                    $linetot += $sc + $scvat;  
                                    
//                                    $crosstot +=$linetot+= $linetot;
//                                    $crossamount +=$lineamount+=$lineamount;
//                                    $crossvat += $linevat+=$linevat;
                                }
                        }
                        //line total                        
                        $table1 .="<td class='showa right' id='$tblclass".$n++."'>".number_format($lineamount)."</td>";
                        $table1 .="<td class='showb right' id='$tblclass".$n++."'>".number_format($linevat)."</td>";
                        $table1 .="<td class='showc right' id='$tblclass".$n++."'>".number_format($linetot)."</td>";
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
                            $table2 .="<tr style='background-color: #A7C942;' valign='bottom' align='center'>";
                            $table2 .="<td style='text-align: left;font-weight:bold;'>".$row3['invoicedesc']."</td>";
                        }else{
							
						
							$table2 .="<tr style='background-color: #A7C942;' class='showall' valign='bottom' align='center'>";
                            $table2 .="<td><b>Cross Total</b></td>";	
							
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
                                    $table2 .="<td class='showa right' id='$tblclass".$n++."'> ".number_format($inv_val)." </td>";
                                    $table2 .="<td class='showb right' id='$tblclass".$n++."'> ".number_format($inv_vat)." </td>";
                                    $table2 .="<td class='showc right' id='$tblclass".$n++."'> ".number_format($inv_tot)."</td>";                                    
                                    
                                    $lineamount +=$inv_val;
                                    $linevat +=$inv_vat;
                                    $linetot += $inv_val + $inv_vat;
                                    
//                                    $crossamount +=$lineamount+=$lineamount;
//                                    $crossvat +=$linevat+=$linevat;
//                                    $crosstot +=$linetot+=$linetot;
                                    
                                   
                                }
                                else
                                {
                                
                                   //GRAND TOTAL - building
                                    $kp = "id='tot".$tbl.$n++."'";
                                    $table2 .="<td class='suma' id='tot".$tbl.$n++."'></td>";                                    
                                    $table2 .="<td class='sumb' id='tot".$tbl.$n++."'></td>";                                    
                                    $table2 .="<td class='sumc' id='tot".$tbl.$n++."'></td>";
                                }
								
								
                        }
                         //line total
                        if($j < $rcount3)
                        {                            
                            $table2 .="<td class='showa right' id='$tblclass".$n++."'>1".number_format($lineamount)."</td>";
                            $table2 .="<td class='showb right' id='$tblclass".$n++."'>".number_format($linevat)."</td>";
                            $table2 .="<td class='showc right' id='$tblclass".$n++."'>".number_format($linetot)."</td>";
                        }
                        else
                        {
                            //GRAND TOTAL - total                            
                            $table2 .="<td class='suma' id='tot".$tbl.$n++."'></td>";                            
                            $table2 .="<td class='sumb' id='tot".$tbl.$n++."'></td>";                            
                            $table2 .="<td class='sumc' id='tot".$tbl.$n++."'></td>";
                        }
                        if($lineamount > 0)
                        {
                            $bool = true;
                            $table2 .="</tr>";
                            $table0  .=$table2; 
                        }
                    }   

                     $table2 .="<tr style='background-color: #A7C942;' class='showall' valign='bottom' align='center'>";
                     $table2 .="<td><b>Debit Notes</b></td>";	

						$table2 .="<tr style='background-color: #A7C942;' class='showall' valign='bottom' align='center'>";
                        $table2 .="<td><b>Credit Notes</b></td>";	

							
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
	$subject = "Sales Report - $period";        
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    $headers .= "From:MEGA PMS ERP" . "\r\n";
    //////$headers .= 'Cc: dipak@shiloahmega.com,mitesh@shiloahmega.com,arulraj@shiloahmega.com,juma@shiloahmega.com'. "\r\n";
    //$message = '<html><head><style type="text/css"></style></head><body><table>';
    //$_POST='<script language="javascript" type="text/javascript">$('.tblmail').html()</script>';
	//$Tost='.tblmail'
	//foreach ($tablemain as $list){
    //$message .=$tablemain;
	//$message .=$tblclass."NGA";
	//$message .=$table0."NGANGA";
	
    //}
    
    //$message .= '</table></body></html>';
  //  echo $message;
    /* if(($message = file_get_contents("salesReport.php")) === false) {
        $message = "";
    }else {
	$message = file_get_contents("salesReport.php");	
	 }*/
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
	 // /* 'jacobshavia@gmail.com' => 'jacob'  */
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
        echo "</br><center><h3>No Sales Found for the period - $period";
   }
 //    echo  $tablemain;  
//    $subject = "Sales Report - $period";        
//    $headers = "MIME-Version: 1.0" . "\r\n";
//    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
//    $headers .= "From:PMS Admin" . "\r\n";
//    //////$headers .= 'Cc: dipak@shiloahmega.com,mitesh@shiloahmega.com,arulraj@shiloahmega.com,juma@shiloahmega.com'. "\r\n";
//    $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
//                <html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
//                <style type="text/css"></style></head><body>';
//    $message .= $tablemain;
//    $message .= '</body></html>';
//    
//////    $address = "arulraj@shiloahmega.com";
//////    $mail->AddAddress($address, "Arulraj");	
//     $address = "jacobshavia@gmail.com";
//     $mail->AddAddress($address, "Jacob");	
//    $mail->isHTML(true)	;
//    $recipients = array(				   		       
	 // 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
	 // 'dipak@shiloahmega.com' => 'Dipak',
	//  'mitesh@shiloahmega.com' => 'Mitesh',
        //'juma@shiloahmega.com' => 'Prabhu',
//        'jacobshavia@gmail.com' => 'Jacob',
        //'ronald-finance@shiloahmega.com' => 'Ronald'
//    );        
//    foreach($recipients as $email => $name)
//    {
//       $mail->AddCC($email, $name);
////    }

    if($foo ==true)
   {        
//        $mail->Subject    = $subject;
//       // $msg=<script> $('.tblmail').html();</script>
//        $mail->MsgHTML();
//        if(!$mail->Send())
//        {		     		
//            echo($mail->ErrorInfo);
//        } else
//        {
//            echo("mail sent");
//        }
       echo $tablemain;
        //echo $message;
    }
    else
    {
        echo "</br><center><h3>No Sales Found for the period - $period";
   }
    
//    $custom = array(
//                'result'=> $tablemain,
//                's'=>'Success');
//    $response_array[] = $custom;
//    echo '{"error":'.json_encode($response_array).'}';
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