<?php
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {	    
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}        
//$custom = array('result'=> $sqlArray ,'s'=>'Success');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;

try
{        
    $invoicedescmasid="";    
    foreach($_GET as $k=>$v)
    {
        $invoicedescmasidfield = strstr($k, '_', true);
        if($invoicedescmasidfield == "invoicedescmasid")
        {
            $invoicedescmasid .=$v.",";
        }
    }
    $invoicedescmasid = rtrim($invoicedescmasid,',');
    
    $buildingmasid = $_GET['buildingmasid'];
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
    
    if($buildingmasid !=0)
    {
        if($grouptenantmasid !=0)
        {
            $sqldt = " c.buildingmasid = '$buildingmasid' and b.grouptenantmasid ='$grouptenantmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";    
        }
        else
        {
            $sqldt = " c.buildingmasid = '$buildingmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";   
        }        
    }
    else
    {
        $sqldt = " c.companymasid = '$companymasid'  and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
    }
    //*************************************************************************************************************************
    //ADVANCE RENT
    $tablemain="";
    $tablemain .="<p class='printable'>";        
        $tblinvoice ="<table id='myTable' class='table6'>";
        $tblinvoice .="<thead> <tr>
                            <th colspan='13'> ADVANCE RENT | $invoice_date_from TO $invoice_date_to</th>
                            <th><input type='button' id='btnToggle1' value='show' style='cursor:hand;'></th>
                        </tr>";
        $tblinvoice .="<tr>";
        $tblinvoice .="<th>Sno</th><th>Tenant</th><th>Code</th><th>Shop</th><th>Invoice No</th><th>From date</th><th>To Date</th>
                        <th>Rent</th><th>Vat</th><th>SC</th><th>Vat</th><th>Total</th><th>Created</th><th>Remarks</th>";
        $tblinvoice .="</tr></thead>";
	if($invdtfrom < '2020-04-01' and $invdtto < '2020-04-01'){
		$sql = "select tenancyrefcode,c.leasename,c.tradingname,d.shopcode,sum(d.size) as size,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                a.rent,round((a.rent*16/100)) as rentvat,a.sc,round((a.sc*16/100)) as scvat,
                a.rent+round((a.rent*16/100))+a.sc+round((a.sc*16/100)) as total from advance_rent a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_tenancyrefcode h on h.grouptenantmasid = a.grouptenantmasid
                where $sqldt group by b.grouptenantmasid order by a.invoiceno;";
	}else{
        $sql = "select tenancyrefcode,c.leasename,c.tradingname,d.shopcode,sum(d.size) as size,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                a.rent,round((a.rent*14/100)) as rentvat,a.sc,round((a.sc*14/100)) as scvat,
                a.rent+round((a.rent*14/100))+a.sc+round((a.sc*14/100)) as total from advance_rent a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_tenancyrefcode h on h.grouptenantmasid = a.grouptenantmasid
                where $sqldt group by b.grouptenantmasid order by a.invoiceno;";
				}
        $rent=0;$rentvat=0;$sc=0;$scvat=0;$total=0;$bool=true;
        $result = mysql_query($sql);
        if($result !=null)
        {
            $n=1;
            while($row = mysql_fetch_assoc($result))
            {
                $bool=false;
                $size = $row['shopcode']."<br>".$row['size'];
                $tblinvoice .="<tbody> <tr  class='rows1' style=display:none;>";
                    $tblinvoice .="<td>".$n."</td>";
                    if($row['tradingname'] =="")
                        $tblinvoice .="<td>".$row['leasename']."</td>";
                    else
                    $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";
                    $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
                    $tblinvoice .="<td>$size</td>";
                    $tblinvoice .="<td>".$row['invoiceno']."</td>";
                    $tblinvoice .="<td>".$row['fromdate']."</td>";
                    $tblinvoice .="<td>".$row['todate']."</td>";
                    $rent += $row['rent'];$rentvat += $row['rentvat'];                    
                    $tblinvoice .="<td>".number_format($row['rent'], 0, '.', ',')."</td>";
                    $tblinvoice .="<td>".number_format($row['rentvat'], 0, '.', ',')."</td>";
                    $sc += $row['sc'];$scvat += $row['scvat'];
                    $tblinvoice .="<td>".number_format($row['sc'], 0, '.', ',')."</td>";
                    $tblinvoice .="<td>".number_format($row['scvat'], 0, '.', ',')."</td>";
                    $total += $row['total'];
                    $tblinvoice .="<td>".number_format($row['total'], 0, '.', ',')."</td>";
                     $tblinvoice .="<td>".$row['createddatetime']."</td>";
                     $tblinvoice .="<td>".$row['createdby']."</td>";
                $tblinvoice .="</tr>";
                $n++;
            }
        }
        $tblinvoice .="<tr  class='rows1' style=display:none;>";
        $tblinvoice .="<td colspan='7'></td>
                        <td>".number_format($rent, 0, '.', ',')."</td>
                        <td>".number_format($rentvat, 0, '.', ',')."</td>
                        <td>".number_format($sc, 0, '.', ',')."</td>
                        <td>".number_format($scvat, 0, '.', ',')."</td>
                        <td>".number_format($total, 0, '.', ',')."</td>";
        $tblinvoice .="</tr></tbody> ";
        $tblinvoice .="</table>";
        
        //*************************************************************************************************************************
        //REGULAR INVOICE
        if($bool == true)
            $tblinvoice="";        
        $tblinvoice .="<table class='table6'>";
        $tblinvoice .="<tr>
                            <th colspan='13'>REGULAR INVOICE | $invoice_date_from TO $invoice_date_to</th>
                            <th><input type='button' id='btnToggle2' value='show' style='cursor:hand;'></th>
                        </tr>";
        $tblinvoice .="<tr>";
        $tblinvoice .="<th>Sno</th><th>Tenant</th><th>Code</th><th>Shop</th><th>Invoice No</th><th>From date</th><th>To Date</th>
                        <th>Rent</th><th>Vat</th><th>SC</th><th>Vat</th><th>Total</th><th>Created</th><th>Remarks</th>";
        $tblinvoice .="</tr>";
		if($invdtfrom < '2020-04-01' and $invdtto < '2020-04-01'){
        $sql = "select tenancyrefcode,c.leasename,c.tradingname,d.shopcode,sum(d.size) as size ,a.invoiceno,
		date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,
		date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                a.rent,(a.rent*16/100) as rentvat,a.sc,(a.sc*16/100) as scvat,
                a.rent+(a.rent*16/100)+a.sc+(a.sc*16/100) as total from invoice a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                left join mas_tenancyrefcode h on h.grouptenantmasid = a.grouptenantmasid
                where $sqldt group by b.grouptenantmasid order by a.invoiceno;";
		}else{
		$sql = "select tenancyrefcode,c.leasename,c.tradingname,d.shopcode,sum(d.size) as size ,a.invoiceno,
		date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,
		date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                a.rent,(a.rent*14/100) as rentvat,a.sc,(a.sc*14/100) as scvat,
                a.rent+(a.rent*14/100)+a.sc+(a.sc*14/100) as total from invoice a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                left join mas_tenancyrefcode h on h.grouptenantmasid = a.grouptenantmasid
                where $sqldt group by b.grouptenantmasid order by a.invoiceno;";	
		}
        $rent=0;$rentvat=0;$sc=0;$scvat=0;$total=0;$bool=true;$size=0;
        $result = mysql_query($sql);
        if($result !=null)
        {
            $n=1;$rcount =mysql_num_rows($result);
            while($row = mysql_fetch_assoc($result))
            {
                $bool=false;               
                    $size = $row['shopcode']."<br>".$row['size'];
                        $tblinvoice .="<tr  class='rows2' style=display:none;>";
                        $tblinvoice .="<td>".$n."</td>";
                        if($row['tradingname'] =="")
                            $tblinvoice .="<td>".$row['leasename']."</td>";
                        else
                            $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";
                        $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
                        $tblinvoice .="<td>$size</td>";
                        $tblinvoice .="<td>".$row['invoiceno']."</td>";
                        $tblinvoice .="<td>".$row['fromdate']."</td>";
                        $tblinvoice .="<td>".$row['todate']."</td>";
                        $rent += $row['rent'];$rentvat += $row['rentvat'];                    
                        $tblinvoice .="<td>".number_format($row['rent'], 0, '.', ',')."</td>";
                        $tblinvoice .="<td>".number_format($row['rentvat'], 0, '.', ',')."</td>";
                        $sc += $row['sc'];$scvat += $row['scvat'];
                        $tblinvoice .="<td>".number_format($row['sc'], 0, '.', ',')."</td>";
                        $tblinvoice .="<td>".number_format($row['scvat'], 0, '.', ',')."</td>";
                        $total += $row['total'];
                        $tblinvoice .="<td>".number_format($row['total'], 0, '.', ',')."</td>";
                        $tblinvoice .="<td>".$row['createddatetime']."</td>";
                        $tblinvoice .="<td>".$row['createdby']."</td>";
                    $tblinvoice .="</tr>";
                    $n++;
            }
        }
        $tblinvoice .="<tr  class='rows2' style=display:none;>";
        $tblinvoice .="<td colspan='7'></td>
                        <td>".number_format($rent, 0, '.', ',')."</td>
                        <td>".number_format($rentvat, 0, '.', ',')."</td>
                        <td>".number_format($sc, 0, '.', ',')."</td>
                        <td>".number_format($scvat, 0, '.', ',')."</td>
                        <td>".number_format($total, 0, '.', ',')."</td>";
        $tblinvoice .="</tr>";
        $tblinvoice .="</table>";
        
        //*************************************************************************************************************************
        // MANUAL INVOICES
        
        if($buildingmasid !=0)
        {
            if($grouptenantmasid !=0)
            {
                $sqldt = " e.buildingmasid = '$buildingmasid' and e.grouptenantmasid ='$grouptenantmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";    
            }
            else
            {
                $sqldt = " e.buildingmasid = '$buildingmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";   
            }        
        }
        else
        {
            $sqldt = " e.companymasid = '$companymasid'  and date_format(a.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
        }
        if($bool == true)
        {
            $tblinvoice="";                
        }
        if($invoicedescmasid !="")
        {
            $tblinvoice="";
        }
        
            $whr="";$value=0;$vat=0;$amount=0;$valuetot=0;$vattot=0;$amounttot=0;
            $tblinvoice .="<table class='table6'>";        
            $tblinvoice .="<tr>
                                <th colspan='9'> MANUAL INVOICE | $invoice_date_from TO $invoice_date_to</th>
                                <th><input type='button' id='btnToggle3' value='show' style='cursor:hand;'></th>
                           </tr>";        
            $tblinvoice .="<tr><th>Sno</th><th>Tenant</th><th>Code</th><th>Shop</th><th>Invoice No</th><th>From date</th><th>To Date</th>
                            <th>Inv Details</th><th>Dated</th><th>Remarks</th></tr>";
        if($invoicedescmasid !="")
        {            
            
            if($buildingmasid !=0)
                {
                    if($grouptenantmasid !=0)
                    {
                        $sqldt = " e.buildingmasid = '$buildingmasid' and e.grouptenantmasid ='$grouptenantmasid' and date_format(b.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";    
                    }
                    else
                    {
                        $sqldt = " e.buildingmasid = '$buildingmasid' and date_format(b.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";   
                    }        
                }
                else
                {
                    $sqldt = " e.companymasid = '$companymasid'  and date_format(b.createddatetime,'%Y-%m-%d') between '$invdtfrom' and '$invdtto'";
                }
            $sql ="select NULL as tenancyrefcode, NULL as b.grouptenantmasid,toaddress,b.leasename,NULL as tradingname,NULL as shopcode,NULL as size,c.invoicedesc,a.value,a.vat,a.amount,c.invoicedesc,
                    invoiceno,date_format(b.fromdate,'%d-%m-%Y') as fromdate, date_format(b.todate,'%d-%m-%Y') as todate,date_format(b.createddatetime,'%d-%m-%Y') as createddatetime,b.createdby
                    from invoice_man_det a 
                    inner join invoice_man_mas b on b.invoicemanmasid = a.invoicemanmasid
                    inner join invoice_desc c on c.invoicedescmasid = a.invoicedescmasid
                    right outer join group_tenant_det d on d.grouptenantmasid = b.grouptenantmasid
                    right outer join mas_tenant e on e.tenantmasid = d.tenantmasid
                    right outer join mas_shop f on f.shopmasid = e.shopmasid
                    right outer join mas_building  g on g.buildingmasid = f.buildingmasid
                    right outer join mas_tenancyrefcode h on h.grouptenantmasid = b.grouptenantmasid
                    where $sqldt and a.invoicedescmasid in ($invoicedescmasid) and b.grouptenantmasid=0 group by invoiceno 
		
		UNION ALL
		
		select tenancyrefcode,b.grouptenantmasid,toaddress,e.leasename,e.tradingname,shopcode,sum(size) as size,c.invoicedesc,a.value,a.vat,a.amount,c.invoicedesc,
                    invoiceno,date_format(b.fromdate,'%d-%m-%Y') as fromdate, date_format(b.todate,'%d-%m-%Y') as todate,date_format(b.createddatetime,'%d-%m-%Y') as createddatetime,b.createdby
                    from invoice_man_det a 
                    inner join invoice_man_mas b on b.invoicemanmasid = a.invoicemanmasid
                    inner join invoice_desc c on c.invoicedescmasid = a.invoicedescmasid
                    right outer join group_tenant_det d on d.grouptenantmasid = b.grouptenantmasid
                    right outer join mas_tenant e on e.tenantmasid = d.tenantmasid
                    right outer join mas_shop f on f.shopmasid = e.shopmasid
                    right outer join mas_building  g on g.buildingmasid = f.buildingmasid
                    right outer join mas_tenancyrefcode h on h.grouptenantmasid = b.grouptenantmasid
                    where $sqldt and a.invoicedescmasid in ($invoicedescmasid) group by invoiceno order by invoiceno;";
        //$custom = array('result'=> $sql,'s'=>'Success');
        //$response_array[] = $custom;
        //echo '{"error":'.json_encode($response_array).'}';
        //exit;
    //print_r($sql);
            $result = mysql_query($sql);
            if($result !=null)
            {                        
                $n=1;
                while($row = mysql_fetch_assoc($result))
                {                                                                                                                                                            
                    $tblinvoice .="<tr class='rows3' style=display:none;>";
                    $grouptenantmasid = $row['grouptenantmasid'];
                    $bool =false;
                    $tblinvoice .="<td>$n</td>";
                    if($grouptenantmasid !=0)
                    {
                        $size = $row['shopcode']."<br>".$row['size'];
                        if($row['tradingname'] =="")
                            $tblinvoice .="<td>".$row['leasename']."</td>";
                        else
                            $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";
                        $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
                        $tblinvoice .="<td>$size</td>";
                    }
                    else
                    {
                        $tblinvoice .="<td colspan='3'>".$row['toaddress']."</td>";
                    }                        
                        $tblinvoice .="<td>".$row['invoiceno']."</td>";
                        $tblinvoice .="<td>".$row['fromdate']."</td>";
                        $tblinvoice .="<td>".$row['todate']."</td>";
                        
                        $bool =false;
                        $value =$row['value'];
                        $valuetot +=$row['value'];
                        
                        $vat =$row['vat'];
                        $vattot +=$row['vat'];
                        
                        $amount =$row['amount'];
                        $amounttot +=$row['amount'];
                        
                        //$tblinvoice .="<td>".$row['invoicedesc']."
                        //                                    <table width='100%'><tr>
                        //                                    <td>".number_format($value, 0, '.', ',')."</td>
                        //                                    <td>".number_format($vat, 0, '.', ',')."</td>
                        //                                    <td>".number_format($amount, 0, '.', ',')."</td>
                        //                                    </tr></table>
                        //                                </td>";
                        $tblinvoice .="<td>
                                                            <table width='100%'><tr>
                                                            <td>".number_format($value, 0, '.', ',')."</td>
                                                            <td>".number_format($vat, 0, '.', ',')."</td>
                                                            <td>".number_format($amount, 0, '.', ',')."</td>
                                                            </tr></table>
                                                        </td>";           
                        $tblinvoice .="<td>".$row['createddatetime']."</td>";
                        $tblinvoice .="<td>".$row['invoicedesc']."</td>";
                    $tblinvoice .="</tr>";
                    $n++;
                }
            }
        }
        else //if invoicedesc = 0
        {            
           /*  $sql = "select tenancyrefcode,toaddress,leasename,c.tradingname,d.shopcode,sum(d.size) as size,invoiceno,date_format(fromdate,'%d-%m-%Y') as fromdate,
                    date_format(todate,'%d-%m-%Y') as todate,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                    a.invoicemanmasid,g.invoicedesc,f.value,f.vat,f.amount from invoice_man_mas a
                    left outer join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                    left outer join mas_tenant c on c.tenantmasid = b.tenantmasid
                    left outer join mas_shop d on d.shopmasid = c.shopmasid
                    left outer join mas_building e on e.buildingmasid = d.buildingmasid
                    inner join invoice_man_det f on f.invoicemanmasid =a.invoicemanmasid
                    inner join invoice_desc g on g.invoicedescmasid = f.invoicedescmasid
                    left outer join mas_tenancyrefcode h on h.grouptenantmasid = b.grouptenantmasid
                    where  $sqldt 
                    group by invoiceno,f.invoicemanmasid

                     UNION ALL 
					  *//* 
					 $sql = "select NULL as tenancyrefcode,toaddress,leasename,NULL as tradingname,NULL as shopcode,NULL as size,invoiceno,date_format(fromdate,'%d-%m-%Y') as fromdate,
                    date_format(todate,'%d-%m-%Y') as todate,date_format(createddatetime,'%d-%m-%Y') as createddatetime,createdby,
                    invoicemanmasid,g1.invoicedesc,f1.value,f1.vat,f1.amount from invoice_man_mas a1
                    right outer join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
                    right outer join mas_tenant e on e.tenantmasid = b1.tenantmasid
                    right outer join mas_shop d1 on d1.shopmasid = e.shopmasid
                    right outer join mas_building e on e1.buildingmasid = d1.buildingmasid
                    inner join invoice_man_det f1 on f1.invoicemanmasid =a1.invoicemanmasid
                    inner join invoice_desc g1 on g1.invoicedescmasid = f1.invoicedescmasid
                    right outer join mas_tenancyrefcode h1 on h1.grouptenantmasid = b1.grouptenantmasid
                    where  a1.grouptenantmasid=0 and $sqldt 
                    group by invoiceno,f1.invoicemanmasid order by invoiceno; ";
					die($sql); */
			 $sql = "select a.toaddress,c.leasename,c.tradingname,d.shopcode,sum(d.size) as size,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,
                    date_format(a.todate,'%d-%m-%Y') as todate,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime,a.createdby,
                    a.invoicemanmasid,g.invoicedesc,f.value,f.vat,f.amount from invoice_man_mas a
                    left join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                    left join mas_tenant c on c.tenantmasid = b.tenantmasid
                    left join mas_shop d on d.shopmasid = c.shopmasid
                    left join mas_building e on e.buildingmasid = a.buildingmasid
                    inner join invoice_man_det f on f.invoicemanmasid =a.invoicemanmasid
                    inner join invoice_desc g on g.invoicedescmasid = f.invoicedescmasid
                   
                    where  $sqldt 
                    group by f.invoicemanmasid order by a.invoiceno; ";		
            $result = mysql_query($sql);            
            if($result !=null)
            {                        
                $n=1;$z=1;
                while($row = mysql_fetch_assoc($result))
                {                                                                                                                                                                                
                        $bool =false;
                        $tblinvoice .="<tr class='rows3' style=display:none;>";
                        $grouptenantmasid = $row['grouptenantmasid'];
                        $bool =false;
                        $tblinvoice .="<td>".$n."</td>";
                        if($grouptenantmasid !=0)
                        {
                            $size = $row['shopcode']."<br>".$row['size'];
                            if($row['tradingname'] =="")                            
                                $tblinvoice .="<td>".$row['leasename']."</td>";
                            else
                                $tblinvoice .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";                                
                            $tblinvoice .="<td>".$row['tenancyrefcode']."</td>";
                            $tblinvoice .="<td>$size</td>";
                        }
                        else
                        {
                            $tblinvoice .="<td colspan='3'>".$row['toaddress']."</td>";
                        }                        
                            $tblinvoice .="<td>".$row['invoiceno']."</td>";
                        $tblinvoice .="<td>".$row['fromdate']."</td>";
                        $tblinvoice .="<td>".$row['todate']."</td>";                        
                        
                        $value =$row['value'];
                        $valuetot +=$row['value'];
                        
                        $vat =$row['vat'];
                        $vattot +=$row['vat'];
                        
                        $amount =$row['amount'];
                        $amounttot +=$row['amount'];
                        
                        //$tblinvoice .="<td>".$row['invoicedesc']."
                        //                                    <table width='100%'><tr>
                        //                                    <td>".number_format($value, 0, '.', ',')."</td>
                        //                                    <td>".number_format($vat, 0, '.', ',')."</td>
                        //                                    <td>".number_format($amount, 0, '.', ',')."</td>
                        //                                    </tr></table>
                        //                                </td>";
                        $tblinvoice .="<td>
                                                            <table width='100%'><tr>
                                                            <td>".number_format($value, 0, '.', ',')."</td>
                                                            <td>".number_format($vat, 0, '.', ',')."</td>
                                                            <td>".number_format($amount, 0, '.', ',')."</td>
                                                            </tr></table>
                                                        </td>";           
                        $tblinvoice .="<td>".$row['createddatetime']."</td>";
                        $tblinvoice .="<td>".$row['invoicedesc']."</td>";
                    $tblinvoice .="</tr>";
                    $n++;
                }
            }           
        }
    $tblinvoice .="<tr class='rows3' style=display:none;>";
    $tblinvoice .="<td colspan='7'></td>
                    <td><table width='100%'><tr>
                        <td>".number_format($valuetot, 0, '.', ',')."</td>
                        <td>".number_format($vattot, 0, '.', ',')."</td>
                        <td>".number_format($amounttot, 0, '.', ',')."</td>
                    </tr></table></td>";
    $tblinvoice .="</tr>";
    $tblinvoice .="</table>";  
    //*************************************************************************************************************************
    // CREDIT NOTE        
    if($bool == true)
    $tblinvoice="";
        $tblinvoice .="<table class='table6'>";        
        $tblinvoice .="<tr>
                        <th colspan='9'> CREDIT NOTE | $invoice_date_from TO $invoice_date_to</th>
                        <th><input type='button' id='btnToggle4' value='show' style='cursor:hand;'></th>
                        </tr>";        
        $tblinvoice .="<tr><th>Sno</th><th>Tenant</th><th>Code</th><th>Shop</th><th>Creditnote No</th><th>From date</th><th>To Date</th>
                            <th>Inv Details</th><th>Dated</th><th>Remarks</th></tr>";
        $sql="select a.creditnoteno,date_format(a.crdate,'%d-%m-%Y') as crdate,c.invoiceno,d.invoicedesc,c.value,c.vat,c.total,
                a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from invoice_cr_mas a
                left outer join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join invoice_cr_det c on c.invoicecrmasid = a.invoicecrmasid
                inner join invoice_desc d on d.invoicedescmasid = c.invoicedescmasid;";
        $result = mysql_query($sql);
        $amttot=0;$vattot=0;$totaltot=0;
        if($result != null)
        {
            while($row = mysql_fetch_assoc($result))
            {                                                                                                                                                                                
                $bool =false;
                //$tblinvoice .="<tr class='rows4' style=display:none;>";
                //$tblinvoice .="<td>".$row['sno']."</td>";
                //$tblinvoice .="</tr>";
            }
        }
        
        $tblinvoice .="</table>";              
        $tablemain .=$tblinvoice;
    $tablemain .="</p>";    
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