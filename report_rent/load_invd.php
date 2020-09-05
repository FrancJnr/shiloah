<?php
include('../config.php');
session_start();

//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {	    
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$t1= "<p class ='printable'>hi hello</p>";
//$custom = array('result'=> $t1 ,'s'=>'Success');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;

try
{    
    $invoice_date_from = $_GET['invdtfrom'];
    $invdtfrom = explode("-",$_GET['invdtfrom']);
    $invdtfrom = $invdtfrom[2]."-".$invdtfrom[1]."-".$invdtfrom[0];    
    
    $invoice_date_to = $_GET['invdtto'];
    $invdtto = explode("-",$_GET['invdtto']);
    $invdtto = $invdtto[2]."-".$invdtto[1]."-".$invdtto[0]; 
    
    // where query
    //$sqldt = "date_format(e.createddatetime,'%Y-%m-%d') = '$invdtfrom'";
    
    $sqldt = "date_format(e.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
    
    ////$sqldt = " date_format(e.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '2013-09-30' ";    
    
    //$tablemain = "<center><b><u>INVOICE REPORT</u></br> ";
    $tablemain="";
    $tablemain .="<p class='printable'><table class='table6'><tr>                                            
                                        <th style='text-align: center;font-weight:bold;'>
                                        MEGA PROPERTIES GROUP SALES REPORT | PERIOD : $invoice_date_from TO $invoice_date_to
                                        </th></tr></table>";    
    $bool = false;
    $sql1 ="select companymasid , companyname from mas_company order by companymasid;";
    $result1 = mysql_query($sql1);
    $invlist =0;
    if($result1 != null)
    {
        $tblclass="table"; $tbl=1;
        while($row1 = mysql_fetch_assoc($result1))
        {                
                $table0 ="";
                $invlist++;
                $tblclass = "row".$tbl;
                $companyname = $row1['companyname'];
                $companymasid = $row1['companymasid'];$buildingmasid=0;
                $grandamt=0;
                $table0 .="<table id ='dailyinvoicelist'.$invlist.' class='table6' border='1' width='100%'><tr ><th colspan='25'>$companyname</th></tr>";
                //$sql2 ="select buildingmasid , buildingname from mas_building where companymasid= $companymasid and buildingmasid not in (10,11) order by buildingmasid;";
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
                        $table1 .="<td style='text-align: left;font-weight:bold;'> $invhead </td>";                                            
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
							if($invdtfrom < '2020-04-01' and $invdtto < '2020-04-01'){
                            $sqldet ="select  e.grouptenantmasid,
												IF(c.tenantmasid  IS NULL or  c.tenantmasid  = '',  'None' ,  c.tenantmasid) as tenantmasid,
												IF(c.leasename  IS NULL or  c.leasename  = '',  'None' ,  c.leasename) as leasename,
												IF(d.buildingname  IS NULL or  d.buildingname  = '',  'None' ,  d.buildingname) as buildingname,
												e.invoiceno,
												e.rent as rent,
												round((e.rent*16/100)) as rentvat,
												e.sc as sc,
												round((e.sc*16/100)) as scvat,
												e.rent+round((e.rent*16/100))+e.sc+round((e.sc*16/100)) as total,
												IF(d.isvat IS NULL or  d.isvat  = '',  'None' ,  d.isvat) as isvat
                                        from invoice e 
										right outer join group_tenant_mas a on a.grouptenantmasid = e.grouptenantmasid										
                                        right outer join mas_tenant c on c.tenantmasid = a.tenantmasid
                                        right outer join mas_building d on d.buildingmasid = c.buildingmasid
                                        where c.buildingmasid ='$buildingmasid'  and  c.companymasid ='$companymasid' and 
                                        $sqldt;";
							}else{
								
								$sqldet ="select  e.grouptenantmasid,
												IF(c.tenantmasid  IS NULL or  c.tenantmasid  = '',  'None' ,  c.tenantmasid) as tenantmasid,
												IF(c.leasename  IS NULL or  c.leasename  = '',  'None' ,  c.leasename) as leasename,
												IF(d.buildingname  IS NULL or  d.buildingname  = '',  'None' ,  d.buildingname) as buildingname,
												e.invoiceno,
												e.rent as rent,
												round((e.rent*14/100)) as rentvat,
												e.sc as sc,
												round((e.sc*14/100)) as scvat,
												e.rent+round((e.rent*14/100))+e.sc+round((e.sc*14/100)) as total,
												IF(d.isvat IS NULL or  d.isvat  = '',  'None' ,  d.isvat) as isvat
                                        from invoice e 
										right outer join group_tenant_mas a on a.grouptenantmasid = e.grouptenantmasid										
                                        right outer join mas_tenant c on c.tenantmasid = a.tenantmasid
                                        right outer join mas_building d on d.buildingmasid = c.buildingmasid
                                        where c.buildingmasid ='$buildingmasid'  and  c.companymasid ='$companymasid' and 
                                        $sqldt;";
							}

                            $resultdet = mysql_query($sqldet);
							//die($sqldet);
							//echo 
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                   
									//echo  $rowdet['invoiceno'].': name: '.$rowdet['leasename'].' Build: '.$rowdet['buildingname'].' Rent: '.$rowdet['rent'].' SC: '.$rowdet['sc'];
									//echo '\n';
									$rent +=$rowdet['rent'];
                                    if($rowdet['isvat']>0)
                                    $rentvat +=$rowdet['rentvat'];
                                    $sc +=$rowdet['sc']; $scvat +=$rowdet['scvat'];
                                    $total +=$rowdet['total'];                                    
                                }
                            }
                            //advance_rent
							if($invdtfrom < '2020-04-01' and $invdtto < '2020-04-01'){
                            $sqldet ="select a.grouptenantmasid ,c.tenantmasid,c.leasename,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*14/100))) as rentvat,sum(sc) as sc,sum(round((sc*14/100))) as scvat
                                ,sum(rent+round((rent*16/100))+sc+round((sc*16/100))) as total,d.isvat
                                from group_tenant_mas a
                                right outer join mas_tenant c on c.tenantmasid = a.tenantmasid
                                right outer join mas_building d on d.buildingmasid = c.buildingmasid
                                right outer join advance_rent e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt;";
							}else{
							$sqldet ="select a.grouptenantmasid ,c.tenantmasid,c.leasename,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*14/100))) as rentvat,sum(sc) as sc,sum(round((sc*14/100))) as scvat
                                ,sum(rent+round((rent*14/100))+sc+round((sc*14/100))) as total,d.isvat
                                from group_tenant_mas a
                                right outer join mas_tenant c on c.tenantmasid = a.tenantmasid
                                right outer join mas_building d on d.buildingmasid = c.buildingmasid
                                right outer join advance_rent e on e.grouptenantmasid = a.grouptenantmasid
                                where c.buildingmasid ='$buildingmasid' and   c.companymasid ='$companymasid' and 
                                $sqldt;";	
							}
                            $resultdet = mysql_query($sqldet);
                            if($resultdet != null)
                            {        
                                while($rowdet = mysql_fetch_assoc($resultdet))
                                {
                                    //echo "ADVANCE INV:";
									//echo  'Inv. '.$rowdet['invoiceno'].': leasename: '.$rowdet['leasename'].' Buildingname: '.$rowdet['buildingname'].' Rent: '.$rowdet['rent'].' SC: '.$rowdet['sc'].'</br>';
									 
									$rent +=$rowdet['rent'];
                                    if($rowdet['isvat']>0)
                                    $rentvat +=$rowdet['rentvat'];
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
                            right outer join invoice_man_mas e on e.invoicemanmasid  = a.invoicemanmasid
                            right outer join invoice_desc c on c.invoicedescmasid = a.invoicedescmasid
                            where $sqldt
                            group by a.invoicedescmasid;";							
							
					//die($sql3);		
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
                            $table2 .="<td style='text-align: left;font-weight:bold;'>".$row3['invoicedesc']."</td>";
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
                                right outer join invoice_man_det b on b.invoicemanmasid  = e.invoicemanmasid
                                right outer join invoice_desc c on c.invoicedescmasid = b.invoicedescmasid
                                right outer join mas_building d on d.buildingmasid = e.buildingmasid
                                where e.buildingmasid='$buildingmasid' and d.companymasid ='$companymasid'
                                and $sqldt
                                and c.invoicedescmasid='$invdescmasid'";
								//die($sql4);
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
            $table0 .="</table>";            
            if($bool == true)
            {
                $tablemain  .=$table0;
                $bool = false;
                $tbl++;
            }            
        }
    }
    $tablemain .="</p>";
    
    //$to = "juma@shiloahmega.com";
    //$subject = date('d-m-Y')." - Invoice Report";        
    //$headers = "MIME-Version: 1.0" . "\r\n";
    //$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    //$headers .= "From:PMS Admin" . "\r\n";
    ////////$headers .= 'Cc: dipak@shiloahmega.com,mitesh@shiloahmega.com,arulraj@shiloahmega.com,juma@shiloahmega.com'. "\r\n";
    //$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    //            <html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    //            <style type="text/css"></style></head><body>';
    //$message .= $tablemain;
    //$message .= '</body></html>';
                
    //ini_set('SMTP','192.148.0.1');// DEFINE SMTP MAIL SERVER
    //mail($to,$subject,$message,$headers);
    
    $custom = array(
                'result'=> $tablemain,
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
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