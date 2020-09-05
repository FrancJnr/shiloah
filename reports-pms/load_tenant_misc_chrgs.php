<?php
include('../config.php');
session_start();
try{
    $companymasid = $_SESSION['mycompanymasid'];
    $buildingmasid = $_GET['buildingmasid'];
    $whr="";$bool=false;
    
    //if (isset($_GET['dtFrom']))
    //{                
    //    $bool = true;
    //    $dateFrom =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($_GET['dtFrom']))));   
    //}
    //if (isset($_GET['dtTo']))
    //{
    //    $dateTo =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($_GET['dtTo']))));
    //    if($bool==true)
    //    $whr =" and d.createddatetime between '" .$dateFrom."' and '".$dateTo."'";
    //}
    
    
    $buildingname="";
    $sql = "select buildingname from mas_building where buildingmasid = $buildingmasid";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $buildingname = $row["buildingname"];
    
    
    $table="<p class='printable'><table class='table6'><tr><td colspan='30' style='font-weight:bold;'>TENANCY MISC CHRGS - ".strtoupper($buildingname)."</td></tr>";        
    $table.="<tr>";
        $table.="<td colspan='5'></td>";
        $table.="<td colspan='4'>Offer Letter</td>";
        $table.="<td colspan='4'>Invoice</td>";
        $table.="<td colspan='2'></td>";
    $table.="</tr>";
    
    $table.="<tr>";
            $table.="<th align='center'>Sno</th>";        
            $table.="<th>Tenant</th>";            
            $table.="<th>Shop</th>";
            $table.="<th>Sqrft</th>";
            $table.="<th>Doc</th>";
            
            $table.="<th>Legal</th>";
            $table.="<th>Stamp</th>";
            $table.="<th>Rgn</th>";
            $table.="<th>Total</th>";
            
            $table.="<th>Inv.No</th>";
            $table.="<th>Legal</th>";
            $table.="<th>Stamp</th>";
            $table.="<th>Rgn</th>";
            $table.="<th>Total</th>";
            $table.="<th>Diff</th>";
    $table.="</tr>";    
    
    $sql="select renewalfromid,leasename,tradingname,e.shopcode,e.size,concat(leegalfees,',' ,stampduty,',',registrationfees) as offerletter, 
            date_format(doc,'%d-%m-%Y') as doc,
            (select group_concat(distinct a1.amount) from invoice_man_det a1 
                  inner join invoice_man_mas b1 on b1.invoicemanmasid = a1.invoicemanmasid
                  where a1.invoicedescmasid in('27','28','30') and b1.grouptenantmasid = d.grouptenantmasid) as invoice,
            (select group_concat(distinct b1.invoiceno,',') from invoice_man_det a1 
                  inner join invoice_man_mas b1 on b1.invoicemanmasid = a1.invoicemanmasid
                  where a1.invoicedescmasid in('27','28','30') and b1.grouptenantmasid = d.grouptenantmasid) as invoiceno
            from trans_offerletter_deposit a
            inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
            inner join group_tenant_det d on d.tenantmasid = c.tenantmasid
            inner join mas_shop e on e.shopmasid = c.shopmasid
            where  c.active='1' and c.shopoccupied='1' and c.buildingmasid='$buildingmasid'  and grouptenantmasid not in 
            (select grouptenantmasid from waiting_list where grouptenantmasid = d.grouptenantmasid)
            union
            select renewalfromid,leasename,tradingname,e.shopcode,e.size,concat(leegalfees,',' ,stampduty,',',registrationfees) as offerletter, 
            date_format(doc,'%d-%m-%Y') as doc,
            (select group_concat(distinct a1.amount) from invoice_man_det a1 
                  inner join invoice_man_mas b1 on b1.invoicemanmasid = a1.invoicemanmasid
                  where a1.invoicedescmasid in('27','28','30') and b1.grouptenantmasid = d.grouptenantmasid) as invoice,
            (select group_concat(distinct b1.invoiceno,',') from invoice_man_det a1 
                  inner join invoice_man_mas b1 on b1.invoicemanmasid = a1.invoicemanmasid
                  where a1.invoicedescmasid in('27','28','30') and b1.grouptenantmasid = d.grouptenantmasid) as invoiceno
            from trans_offerletter_deposit a
            inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
            inner join rec_tenant c on c.tenantmasid = b.tenantmasid
            inner join group_tenant_det d on d.tenantmasid = c.tenantmasid
            inner join mas_shop e on e.shopmasid = c.shopmasid
            where  c.active='1' and c.shopoccupied='1' and c.buildingmasid='$buildingmasid'  and grouptenantmasid not in 
            (select grouptenantmasid from waiting_list where grouptenantmasid = d.grouptenantmasid)
            order by leasename;";            
    $result = mysql_query($sql);
    if($result != null)
    {
        $k=1;
            while ($row = mysql_fetch_assoc($result))
            {                
               
                $table.="<tr>";
                    $table.="<td align='center'>$k</td>";
                    if (($row['tradingname'])!="")
                    $row['leasename'] .=" T/A ".$row['tradingname'];
                    if($row['renewalfromid'] >0)
                    $row['leasename'] .=" (Renewed)";
                    $table.="<td>".$row['leasename']."</td>";                    
                    $table.="<td>".$row['shopcode']."</td>";
                    $table.="<td>".$row['size']."</td>";
                    $table.="<td>".$row['doc']."</td>";
                    $s="";$total1=0;
                    $sqlExec = explode(",",$row['offerletter']);
                    for($i=0;$i<count($sqlExec);$i++)
                    {
                         if($sqlExec[$i] != "")
                         {                                                       
                            $table.="<td>".number_format($sqlExec[$i], 0, '.', ',')."</td>";                                                        
                            $total1 += $sqlExec[$i];
                         }
                    }
                    $table.="<td>".number_format($total1, 0, '.', ',')."</td>";
                    $table.="<td>".$row['invoiceno']."</td>";
                    $s="";$total2=0;                    
                    $sqlExec = explode(",",$row['invoice']);
                    
                    if(count($sqlExec) ==0)
                    {
                            $table.="<td colspan='4'>-</td>";                            
                    }
                    else if(count($sqlExec) ==1)
                    {
                        $table.="<td colspan='2'>-</td>";                            
                    }
                    else if(count($sqlExec) ==2)
                    {
                        $table.="<td>-</td>";
                    }                    
                    $boo = false;
                    if(count($sqlExec)>3)
                    {
                        $table1 ="<tr><td colspan='10'></td>";
                        $boo = true;
                    }
                    for($i=0;$i<count($sqlExec);$i++)
                    {
                        
                         if($sqlExec[$i] != "")
                         {                            
                            //$table.="<td>".count($sqlExec)."</td>";
                            if($i<=2)
                                $table.="<td>".number_format($sqlExec[$i], 0, '.', ',')."</td>";
                            else
                                $table1.="<td>".number_format($sqlExec[$i], 0, '.', ',')."</td>";
                            $total2 += $sqlExec[$i];
                         }
                    }
                        if($boo ==true)
                        $table .= $table1;
                        $table.="<td>".number_format($total2, 0, '.', ',')."</td>";
                        $diff = $total1 - $total2;
                        $table.="<td>".number_format($diff, 0, '.', ',')."</td>";
                        if($total2<=0)
                        $table.="<td>".number_format($total1, 0, '.', ',')."</td>";                    
                    
                    
                $table.="</tr>";
                $k++;
            }        
    }
    $table.="</table></p>";
    $custom = array('divContent'=> "<br>".$table,'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
catch (Exception $err)
{
    $custom = array(
                'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>