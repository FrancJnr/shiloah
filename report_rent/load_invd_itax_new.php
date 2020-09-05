<?php
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$buildings=rtrim($_GET['buildings'],",");


try
{        
    $invoicedescmasid="";    
//    foreach($_GET as $k=>$v)
//    {
//        $invoicedescmasidfield = strstr($k, '_', true);
//        if($invoicedescmasidfield == "invoicedescmasid")
//        {
//            $invoicedescmasid .=$v.",";
//        }
//    }
//    $invoicedescmasid = rtrim($invoicedescmasid,',');
    
   // $buildingmasid = $_GET['buildingmasid'];
    $grouptenantmasid=0;
    //$grouptenantmasid = $_GET['grouptenantmasid'];
    
    $invoice_date_from = $_GET['invdtfrom'];
    $invdtfrom = explode("-",$_GET['invdtfrom']);
    $invdtfrom = $invdtfrom[2]."-".$invdtfrom[1]."-".$invdtfrom[0];    
    
    $invoice_date_to = $_GET['invdtto'];
    $invdtto = explode("-",$_GET['invdtto']);
    $invdtto = $invdtto[2]."-".$invdtto[1]."-".$invdtto[0]; 
    
    // where query
    //$sqldt = "date_format(a.createddatetime,'%Y-%m-%d') = '$invdtfrom'";
    
    if(!empty($buildings))
    {
//        if($grouptenantmasid !=0)
//        {
//            $sqldt = " c.buildingmasid = '$buildingmasid' and b.grouptenantmasid ='$grouptenantmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";    
//        }
//        else
//        {
//           //in (".$buildings.")"
// $sqldt = " c.buildingmasid = '$buildingmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'"; 
            $sqldt = " c.buildingmasid in ($buildings) and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";         
//       }        
    }
    else
    {
            $sqldt = " c.companymasid = '$companymasid'  and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
    }
    //*************************************************************************************************************************
    //ADVANCE RENT
    $tablemain="";
    //select pin,vatno from mas_company where companymasid=
        $tablemain .="<p class='printable'>";        
        $tblinvoice ="<table id='myTable' class='table6'>";
        $tblinvoice .="<tr id='hidethis'>
                            <th colspan='12'> ADVANCE RENT | $invoice_date_from TO $invoice_date_to</th>
                            <th><input type='button' id='btnToggle1' value='show' style='cursor:hand;'></th>
                            <th><input type='button' id='btnExport1' value='Export'  style='cursor:hand;'></th>
                        </tr>";
        $tblinvoice .="<tr>";
        //Credit Note	Credit Note	Credit Note	Relevant Invoice Number	Relevant Invoice Date
        $tblinvoice .="<th>Sno</th><th>Pin</th><th>Purchaser</th><th>ETR Sno</th><th>Invoice Date</th>
            <th>Invoice No</th><th>Description</th>
            <th>Taxable Value(Ksh)</th><th>Amount of VAT (Ksh)(Taxable Value*14%)</th>
            <th></th><th></th><th></th><th></th>
            <th></th>";
 //  <th>Rent</th><th>Vat</th><th>SC</th><th>Vat</th><th>Total</th><th>Created</th><th>Remarks</th>";
        $tblinvoice .="</tr>";
        $sql = "select tenancyrefcode,c.leasename, c.tradingname,c.pin, x.vatno, d.shopcode, sum(d.size) as size,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                a.rent,round((a.rent*14/100)) as rentvat,a.sc,round((a.sc*14/100)) as scvat,
                a.rent+round((a.rent*14/100))+a.sc+round((a.sc*14/100)) as total from advance_rent a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_company x on x.companymasid=e.companymasid
                inner join mas_tenancyrefcode h on h.grouptenantmasid = a.grouptenantmasid
                where $sqldt group by b.grouptenantmasid order by a.invoiceno;";
        
        $rent=0;$rentvat=0;$sc=0;$scvat=0;$total=0;$bool=true; $nx=0;
        
        $result = mysql_query($sql);
        if($result !=null)
        {
            $n=1;
            while($row = mysql_fetch_assoc($result))
            {
               // $bool=false;
                $size = $row['shopcode']."<br>".$row['size'];
                $tblinvoice .="<tbody> <tr  class='rows1' style=display:none;>";
                    $tblinvoice .="<td>".$n."</td>";
                     $tblinvoice .="<td>".$row['pin']."</td>";
                    if($row['tradingname'] =="")
                        $tblinvoice .="<td>".$row['leasename']."</td>";
                    else
                      $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";
                      $tblinvoice .="<td>".$row['vatno']."</td>";
                   // $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
                   // $tblinvoice .="<td>$size</td>";
                    $tblinvoice .="<td>".$row['fromdate']."</td>";
                    $tblinvoice .="<td>".$row['invoiceno']."</td>";
                   // $tblinvoice .="<td>".$row['fromdate']."</td>";
                    $tblinvoice .="<td>Advance Rent & SCD for ".$size." </td>";
                   // $tblinvoice .="<td>".$row['todate']."</td>";
                    $rent += $row['rent'];
                    $rentvat += $row['rentvat'];    
                    // calculate totals for rent and sc and vat
                   // $tblinvoice .="<td>".number_format($row['rent'], 0, '.', ',')."</td>";
                   // $tblinvoice .="<td>".number_format($row['rentvat'], 0, '.', ',')."</td>";
                    $sc += $row['sc'];
                    $scvat += $row['scvat'];
                    
                    $tblinvoice .="<td>".number_format($row['sc']+$row['rent'], 0, '.', ',')."</td>";
                    $tblinvoice .="<td>".number_format($row['scvat']+$row['rentvat'], 0, '.', ',')."</td>";
                    $tblinvoice .="<td></td>";
                    $tblinvoice .="<td></td>";
                    $tblinvoice .="<td></td>";
                    $tblinvoice .="<td></td>";
                    $tblinvoice .="<td></td>";
                    //$total += $row['total'];
//                     $tblinvoice .="<td>".number_format($row['total'], 0, '.', ',')."</td>";
//                     $tblinvoice .="<td>".$row['createddatetime']."</td>";
//                     $tblinvoice .="<td>".$row['createdby']."</td>";
                $tblinvoice .="</tr>";
                $n++;
				$nx=n;
            }
        }
//        $tblinvoice .="<tr  class='rows1' style=display:none;>";
//        $tblinvoice .="<td colspan='7'></td>
//                        <td>".number_format($rent, 0, '.', ',')."</td>
//                        <td>".number_format($rentvat, 0, '.', ',')."</td>
//                        <td>".number_format($sc, 0, '.', ',')."</td>
//                        <td>".number_format($scvat, 0, '.', ',')."</td>
//                        <td>".number_format($total, 0, '.', ',')."</td>";
//        $tblinvoice .="</tr></tbody> ";
//        $tblinvoice .="</tbody>";
        //$tblinvoice .="</table>";
      //  echo $bool;
        //*************************************************************************************************************************
        //REGULAR INVOICE
        if($bool == true)
        /*$tblinvoice="";  
        $tblinvoice .="<table id='myTable1' class='table6'>";
        $tblinvoice .="<tr id='hidethis1'>
                            <th colspan='12'>REGULAR INVOICE | $invoice_date_from TO $invoice_date_to</th>
                            <th><input type='button' id='btnToggle2' value='show' style='cursor:hand;'></th>
                            <th><input type='button' id='btnExport2' value='Export'  style='cursor:hand;'></th>
                        </tr>";
        $tblinvoice .="<tr>";
        $tblinvoice .="<th>Sno</th><th>Pin</th><th>Purchaser</th><th>ETR Sno</th><th>Invoice Date</th>
            <th>Invoice No</th><th>Description</th>
            <th>Taxable Value(Ksh)</th><th>Amount of VAT (Ksh)(Taxable Value*14%)</th>
            <th></th><th></th><th></th><th></th>
            <th></th>";
        $tblinvoice .="</tr>";*/
        $sql= "select tenancyrefcode,c.leasename,c.tradingname,c.pin, x.vatno, d.shopcode,sum(d.size) as size ,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                a.rent,round((a.rent*14/100)) as rentvat,a.sc,round((a.sc*14/100)) as scvat,
                a.rent+round((a.rent*14/100))+a.sc+round((a.sc*14/100)) as total from invoice a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_company x on x.companymasid=e.companymasid
                inner join mas_tenancyrefcode h on h.grouptenantmasid = a.grouptenantmasid
                where $sqldt group by b.grouptenantmasid order by a.invoiceno;";
        $rent=0;$rentvat=0;$sc=0;$scvat=0;$total=0;$bool=true;$size=0; $ropin=array();
        $result = mysql_query($sql);
        
        if($result !=null)
        {
            $n=1;//$nx;
             //$nx=0;
            while($row = mysql_fetch_assoc($result))
            {
                //$ropin=$row["pin"];
                array_push($ropin,  mysql_escape_string($row["pin"]));
                //$bool=false;    
                //echo $row['pin']."<br>";
                        $size = $row['shopcode']."<br>".$row['size'];
                        $tblinvoice .="<tr  class='rows2' style=display:none;>";
                        $tblinvoice .="<td>".$n."</td>";
                        $tblinvoice .="<td>".$row['pin']."</td>";
                        //echo $ropin[$n-1]."\n";
                        if($row['tradingname'] =="")
                                                 
                          $tblinvoice .="<td>".$row['leasename']."</td>";
                        else
                         $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";
                       // $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
//                       // $tblinvoice .="<td>$size</td>";
                        $tblinvoice .="<td>".$row['vatno']."</td>";
                        $tblinvoice .="<td>".$row['fromdate']."</td>";
                        $tblinvoice .="<td>".$row['invoiceno']."</td>";
//                        //$tblinvoice .="<td>".$row['fromdate']."</td>";
                        $tblinvoice .="<td>Rent & SCD ".$size."</td>";
//                       //$tblinvoice .="<td>".$row['todate']."</td>";
                        $rent += $row['rent'];
                        $rentvat += $row['rentvat'];                    
                        //$tblinvoice .="<td>".number_format($row['rent'], 0, '.', ',')."</td>";
                        //$tblinvoice .="<td>".number_format($row['rentvat'], 0, '.', ',')."</td>";
                        $sc += $row['sc'];
                        $scvat += $row['scvat'];
                        $tblinvoice .="<td>".number_format($row['sc']+$row['rent'], 0, '.', ',')."</td>";
                        $tblinvoice .="<td>".number_format($row['scvat']+$row['rentvat'], 0, '.', ',')."</td>";
                        $tblinvoice .="<td>-</td>";
                        $tblinvoice .="<td>-</td>";
                        $tblinvoice .="<td>-</td>";
                        $tblinvoice .="<td></td>";
                        $tblinvoice .="<td></td>";
//                       // $total += $row['total'];
                        //$tblinvoice .="<td>".number_format($row['total'], 0, '.', ',')."</td>";
                        //$tblinvoice .="<td>".$row['createddatetime']."</td>";
                        //$tblinvoice .="<td>".$row['createdby']."</td>";
                        $tblinvoice .="</tr>";
                    $n++;
            }
            //echo count($ropin)."\n".$n;
        }
//        $tblinvoice .="<tr  class='rows2' style=display:none;>";
//        $tblinvoice .="<td colspan='7'></td>
//                        <td>".number_format($rent, 0, '.', ',')."</td>
//                        <td>".number_format($rentvat, 0, '.', ',')."</td>
//                        <td>".number_format($sc, 0, '.', ',')."</td>
//                        <td>".number_format($scvat, 0, '.', ',')."</td>
//                        <td>".number_format($total, 0, '.', ',')."</td>";
//        $tblinvoice .="</tr>";
       // $tblinvoice .="</table>";
        
        //*************************************************************************************************************************
        // MANUAL INVOICE
        
        if(!empty($buildings))
        {
            //echo "manualinv..".$buildings;
//            if($grouptenantmasid !=0)
//            {
//                $sqldt = " e.buildingmasid = '$buildingmasid' and e.grouptenantmasid ='$grouptenantmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";    
//            }
//            else
//            {
                $sqldt = " e.buildingmasid in ($buildings) and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";   
//            }        
        }
        else
        {
            $sqldt = " e.companymasid = '$companymasid'  and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
        }
       /* if($bool == true)
        {
            $tblinvoice="";                
        }
        if($invoicedescmasid !="")
        {
            $tblinvoice="";
        }
        */
            $whr="";$value=0;$vat=0;$amount=0;$valuetot=0;$vattot=0;$amounttot=0;
            /*$tblinvoice .="<table id='myTable2' class='table6'>";        
            $tblinvoice .="<tr id='hidethis2'>
                                <th colspan='12'> MANUAL INVOICE | $invoice_date_from TO $invoice_date_to</th>
                                <th><input type='button' id='btnToggle3' value='show' style='cursor:hand;'></th>
                                <th><input type='button' id='btnExport3' value='Export'  style='cursor:hand;'></th>
                           </tr>";        
            $tblinvoice .="<tr><th>Sno</th><th>Pin</th><th>Purchaser</th><th>ETR Sno</th><th>Invoice Date</th>
            <th>Invoice No</th><th>Description</th>
            <th>Taxable Value(Ksh)</th><th>Amount of VAT (Ksh)(Taxable Value*14%)</th>
            <th></th><th></th><th></th><th></th>
            <th></th></tr>";*/
        if($invoicedescmasid =="")
        {              
            $sql = "select tenancyrefcode,a.grouptenantmasid,a.toaddress,c.leasename,c.tradingname,c.pin, x.vatno, d.shopcode,sum(d.size) as size,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,
                    date_format(a.todate,'%d-%m-%Y') as todate,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                    a.invoicemanmasid,g.invoicedesc,f.value,f.vat,f.amount from invoice_man_mas a
                    left join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                    left join mas_tenant c on c.tenantmasid = b.tenantmasid
                    left join mas_shop d on d.shopmasid = c.shopmasid
                    left join mas_building e on e.buildingmasid = d.buildingmasid
                    inner join invoice_man_det f on f.invoicemanmasid =a.invoicemanmasid
                    inner join invoice_desc g on g.invoicedescmasid = f.invoicedescmasid
                    inner join mas_tenancyrefcode h on h.grouptenantmasid = b.grouptenantmasid
                    inner join mas_company x on x.companymasid=e.companymasid
                    where  $sqldt 
                    group by b.grouptenantmasid,f.invoicemanmasid order by a.invoiceno; ";
            $result = mysql_query($sql);            
            if($result !=null)
            {                        
                $n=1;
                while($row = mysql_fetch_assoc($result))
                {                                                                                                                                                                                
                        $bool =false;
                        $tblinvoice .="<tr class='rows3' style=display:none;>";
                        $grouptenantmasid = $row['grouptenantmasid'];
                        $bool =false;
                        $tblinvoice .="<td>".$n."</td>";
//                        if($grouptenantmasid !=0)
//                        {
                            $size = $row['shopcode']."<br>".$row['size'];
//                            $tblinvoice .="<td>".$n."</td>";
                            $tblinvoice .="<td>".$row['pin']."</td>";
                            if($row['tradingname'] =="")                            
                                $tblinvoice .="<td>".$row['leasename']."</td>";
                            else
                                $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";                                
                           // $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
                           // $tblinvoice .="<td>$size</td>";
                      //  }
//                        else
//                        {
//                            $tblinvoice .="<td colspan='3'>".$row['toaddress']."</td>";
//                        }                        
                        
                        $tblinvoice .="<td>".$row['vatno']."</td>";
                        $tblinvoice .="<td>".$row['fromdate']."</td>";
                        $tblinvoice .="<td>".$row['invoiceno']."</td>";
                        $tblinvoice .="<td>".$row['invoicedesc']." for ".$size."</td>";
                        
                        $value =$row['value'];
                        $valuetot +=$row['value'];
                        
                        $vat =$row['vat'];
                        $vattot +=$row['vat'];
                        
                        $amount =$row['amount'];
                        $amounttot +=$row['amount'];
                        
                        $tblinvoice .="<td>".number_format($value, 0, '.', ',')."</td>";
                        $tblinvoice .="<td>".number_format($vat, 0, '.', ',')."</td>";
                        $tblinvoice .="<td></td>";
                        $tblinvoice .="<td></td>";
                        $tblinvoice .="<td></td>";
                        $tblinvoice .="<td></td>";
                        $tblinvoice .="<td></td>";       
                                        
                        
//                        $value =$row['value'];
//                        $valuetot +=$row['value'];
//                        
//                        $vat =$row['vat'];
//                        $vattot +=$row['vat'];
//                        
//                        $amount =$row['amount'];
//                        $amounttot +=$row['amount'];
//                        
//                        $tblinvoice .="<td>
//                                                            <table width='100%'><tr>
//                                                            <td>".number_format($value, 0, '.', ',')."</td>
//                                                            <td>".number_format($vat, 0, '.', ',')."</td>
//                                                            <td>".number_format($amount, 0, '.', ',')."</td>
//                                                            </tr></table>
//                                                        </td>";           
//                        $tblinvoice .="<td>".$row['createddatetime']."</td>";
//                        $tblinvoice .="<td>".$row['invoicedesc']."</td>";
                    $tblinvoice .="</tr>";
                    $n++;
                }
            }           
        }
//    $tblinvoice .="<tr class='rows3' style=display:none;>";
//    $tblinvoice .="<td colspan='7'></td>
//                    <td><table width='100%'><tr>
//                        <td>".number_format($valuetot, 0, '.', ',')."</td>
//                        <td>".number_format($vattot, 0, '.', ',')."</td>
//                        <td>".number_format($amounttot, 0, '.', ',')."</td>
//                    </tr></table></td>";
//    $tblinvoice .="</tr>";
  //  $tblinvoice .="</table>";  
    //*************************************************************************************************************************
    // CREDIT NOTE  
     if(!empty($buildings))
        {
//            //echo "manualinv..".$buildings;
//            if($grouptenantmasid !=0)
//            {
//                $sqldt = " v.buildingmasid = '$buildingmasid' and v.grouptenantmasid ='$grouptenantmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";    
//            }
//            else
//            {
                $sqldt = " v.buildingmasid in ($buildings) and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";   
//            }        
        }
        else
        {
            $sqldt = " v.companymasid = '$companymasid'  and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
        }
   /*if($bool == true)
        {
            $tblinvoice="";                
        }
        if($invoicedescmasid !="")
        {
            $tblinvoice="";
        }
//        $tblinvoice="";
//        $tblinvoice .="<table class='table6'>";        
//        $tblinvoice .="<tr>
//                        <th colspan='9'> CREDIT NOTE | $invoice_date_from TO $invoice_date_to</th>
//                        <th><input type='button' id='btnToggle4' value='show' style='cursor:hand;'></th>
//                        </tr>";        
//        $tblinvoice .="<tr><th>Sno</th><th>Tenant</th><th>Code</th><th>Shop</th><th>Creditnote No</th><th>From date</th><th>To Date</th>
//                            <th>Inv Details</th><th>Dated</th><th>Remarks</th></tr>";
        */
        
        $whr="";$value=0;$vat=0;$amount=0;$valuetot=0;$vattot=0;$amounttot=0;
            $tblinvoice .="<table id='myTable3' class='table6'>";        
            $tblinvoice .="<tr id='hidethis3'>
                                <th colspan='12'> CREDIT NOTE | $invoice_date_from TO $invoice_date_to</th>
                                <th><input type='button' id='btnToggle4' value='show' style='cursor:hand;'></th>
                                <th><input type='button' id='btnExport4' value='Export'  style='cursor:hand;'></th>
                           </tr>";        
            $tblinvoice .="<tr><th>Sno</th><th>Pin</th><th>Purchaser</th><th>ETR Sno</th><th>Invoice Date</th>
            <th>Credit Note</th><th>Description</th>
            <th>Taxable Value(Ksh)</th><th>Amount of VAT (Ksh)(Taxable Value*14%)</th>
            <th></th><th></th><th>Invoice No</th>Relevant Invoice Date<th></th>
            <th></th></tr>";
        
        
        $sqll="select a.grouptenantmasid,y.leasename,y.tradingname,y.pin, x.vatno, w.shopcode, sum(w.size) as size, 
            a.creditnoteno,date_format(a.crdate,'%d-%m-%Y') as crdate,c.invoiceno,d.invoicedesc,c.value,c.vat,c.total,
                a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from invoice_cr_mas a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join invoice_cr_det c on c.invoicecrmasid = a.invoicecrmasid
                inner join invoice_desc d on d.invoicedescmasid = c.invoicedescmasid
                inner join mas_tenant y on y.tenantmasid = b.tenantmasid
                inner join mas_shop w on w.shopmasid = y.shopmasid
                inner join mas_building v on v.buildingmasid = w.buildingmasid
                inner join mas_tenancyrefcode h on h.grouptenantmasid = b.grouptenantmasid
                inner join mas_company x on x.companymasid=v.companymasid 
                where  $sqldt group by b.grouptenantmasid order by c.invoiceno;";
        $resulting = mysql_query($sqll);
        // echo $sqll;
        //$resulti = mysql_query($sql);   
       // echo mysql_error();
            if($resulting !=null)
            {                        
               // echo "baba moi2"; 
                
               // echo mysql_num_rows($resulting);
                if(mysql_num_rows($resulting)>0){
                    $n=1;
                while($row = mysql_fetch_assoc($resulting))
                {                                                                                                                                                                                
                        //$bool =false;
                     // echo "baba moi";  
                   
                    $tblinvoice .="<tr class='rows4' style=display:none;>";
                        $grouptenantmasid = $row['grouptenantmasid'];
                      //  $bool =false;
                        $tblinvoice .="<td>".$n."</td>";
//                        if($grouptenantmasid !=0)
//                        {
                            $size = $row['shopcode']."<br>".$row['size'];
//                            $tblinvoice .="<td>".$n."</td>";
                            $tblinvoice .="<td>".$row['pin']."</td>";
//                            $tblinvoice .="<td></td>";
                            if($row['tradingname'] =="")                            
                                $tblinvoice .="<td>".$row['leasename']."</td>";
                            else
                                $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";                                
                           // $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
                           // $tblinvoice .="<td>$size</td>";
                      //  }
//                        else
//                        {
//                            $tblinvoice .="<td colspan='3'>".$row['toaddress']."</td>";
//                        }                        
                        
                        $tblinvoice .="<td>".$row['vatno']."</td>";
                        $tblinvoice .="<td>".$row['crdate']."</td>";
                        $tblinvoice .="<td>".$row['creditnoteno']."</td>";
                        $tblinvoice .="<td>".$row['invoicedesc']." for ".$size."</td>";
                        //echo $row['vatno'];
                        $value =$row['value'];
                        $valuetot +=$row['value'];
                        
                        $vat =$row['vat'];
                        $vattot +=$row['vat'];
                        
//                        $amount =$row['amount'];
//                        $amounttot +=$row['amount'];
                        
                        $tblinvoice .="<td>".number_format($value, 0, '.', ',')."</td>";
                        $tblinvoice .="<td>".number_format($vat, 0, '.', ',')."</td>";
                        $tblinvoice .="<td></td>";
                        $tblinvoice .="<td></td>";
                        $tblinvoice .="<td>".$row['invoiceno']."</td>";
                        $tblinvoice .="<td>".$row['crdate']."</td>";
                        $tblinvoice .="<td></td>";       
                                        
                        
//                        $value =$row['value'];
//                        $valuetot +=$row['value'];
//                        
//                        $vat =$row['vat'];
//                        $vattot +=$row['vat'];
//                        
//                        $amount =$row['amount'];
//                        $amounttot +=$row['amount'];
//                        
//                        $tblinvoice .="<td>
//                                                            <table width='100%'><tr>
//                                                            <td>".number_format($value, 0, '.', ',')."</td>
//                                                            <td>".number_format($vat, 0, '.', ',')."</td>
//                                                            <td>".number_format($amount, 0, '.', ',')."</td>
//                                                            </tr></table>
//                                                        </td>";           
//                        $tblinvoice .="<td>".$row['createddatetime']."</td>";
//                        $tblinvoice .="<td>".$row['invoicedesc']."</td>";
                    $tblinvoice .="</tr>";
                    $n++;
                }
                }else{
                      $tblinvoice .="<tr class='rows4' style=display:none;>";
                      $tblinvoice .="<td>No credit note data for the selected period</td>";
                      $tblinvoice .="</tr>";
                }
            }       
       
        $tblinvoice .="</table>";              
        $tablemain .=$tblinvoice;
       
    $tablemain .="</p>";    
    $custom = array(
                'result'=> $tablemain,
                's'=>'Success');
    $response_array[] = $custom;
     //print_r($response_array);
     //$response_array=array_map('utf8_encode', $response_array);
    //echo json_encode($response_array);
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