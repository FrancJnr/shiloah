<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";
$table = "mas_tenant";
$companymasid = $_SESSION['mycompanymasid'];

if($load == "loadTenantDraft")
{
$sql= "select c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active = '1' 
            union
            select c1.leasename,c1.tradingname, c1.tenantcode,c1.renewalfromid,d1.shopcode, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid 
            where c1.companymasid=$companymasid and c1.active = '1' 
            order by leasename;";
}
else if($load == "loadtenantforadvancerent")
{
$sql= "select c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active = '1' and c.shopoccupied='1' and a.grouptenantmasid not in (select grouptenantmasid from waiting_list)
            union
            select c1.leasename,c1.tradingname, c1.tenantcode,c1.renewalfromid,d1.shopcode, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid 
            where c1.companymasid=$companymasid and c1.active = '1' and c1.shopoccupied='1' and a1.grouptenantmasid not in (select grouptenantmasid from waiting_list)
            order by leasename;";
}
else if($load == "manual_invoice")
{
    $compmasid= $_GET['compmasid'];
    $sql= "select c.leasename ,c.tradingname ,c.renewalfromid,d.shopcode,d.size, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            where c.companymasid = $compmasid
            union
            select c1.leasename ,c1.tradingname ,c1.renewalfromid,d1.shopcode,d1.size, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid
            where c1.companymasid = $compmasid
            order by leasename ;";
    
}
else if($load == "loadTenantFinalized")
{
    $sql = "select * from $table where companymasid=$companymasid and tenanttypemasid='1' and tenantmasid in
    (select tenantmasid from trans_offerletter where editpermission=0) order by leasename";
}
else if($load == "loadDailyTenant")
{
    $sql = "select licensename,licensemasid,licensecode from mas_daily_license where editpermission =1 order by licensename";
}
else if($load == "grouptenant")
{   
    $id = $_GET['itemval'];
    $sql= "select b.leasename,a.tenantmasid,b.tenantcode,c.shopcode,c.size from group_tenant_det a
            inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
            inner join mas_shop c on  c.shopmasid = b.shopmasid
            where a.grouptenantmasid = $id and b.active='1'
            union
            select b.leasename,a.tenantmasid,b.tenantcode,c.shopcode,c.size from group_tenant_det a
            inner join rec_tenant b on  b.tenantmasid = a.tenantmasid
            inner join mas_shop c on  c.shopmasid = b.shopmasid
            where a.grouptenantmasid = $id and b.active='1';"; 
   
}
else if($load == "grouptenant_contact")
{   
    $id = $_GET['itemval'];
    $sql= "select d.cptype,a.cpname from mas_tenant_cp a 
            inner join mas_tenant b on b.tenantmasid = a.tenantmasid
            inner join group_tenant_det c on c.tenantmasid = b.tenantmasid
            inner join mas_cptype d on d.cptypemasid = a.cptypemasid
            where c.grouptenantmasid = '$id' and documentname ='1';"; 
   
}
else if($load == "tenantdetails")
{   
    $id = $_GET['itemval'];
    $sql= " select i.offerlettermasid,k.tenanttype,b.tenantmasid,i.offerlettermasid,a.grouptenantmasid,
            b.leasename,b.tradingname,b.nob,b.renewalfromid,b.active,b.shopoccupied,date_format(b.doc,'%d-%m-%Y') as doc,c.shopcode,c.size,d.buildingname,e.companyname,
            f.cpname,f.cpnid,f.cpmobile,f.cplandline,f.cpemailid,h.grouptenantmasid as waitinglist,
            b.address1,b.address2,b.telephone1,b.telephone2,b.poboxno,b.pincode,b.city,b.pin,l.tenancyrefcode,b.includedby,date_format(b.includedon,'%d-%m-%Y') as includedon,
            date_format(min(j.fromdate),'%d-%m-%Y') as docdt,date_format(max(j.todate) ,'%d-%m-%Y') as expdt,
            case 
                when max(j.todate) < curdate() then '0'
                when max(j.todate) > curdate() then '1'
            end as leasestate,
            g.age as rentcycle,g1.age as leaseterm from group_tenant_det a
            inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
            inner join mas_shop c on  c.shopmasid = b.shopmasid
            inner join mas_building d on d.buildingmasid = c.buildingmasid
            inner join mas_company e on e.companymasid = d.companymasid
            inner join mas_tenant_cp f on f.tenantmasid = b.tenantmasid
            inner join mas_age g on g.agemasid = b.agemasidrc
            inner join mas_age g1 on g1.agemasid = b.agemasidlt
            left join waiting_list h on h.grouptenantmasid = a.grouptenantmasid
            left join trans_offerletter i on i.tenantmasid = b.tenantmasid
            left join trans_offerletter_rent j on j.offerlettermasid = i.offerlettermasid
            left join mas_tenant_type k on k.tenanttypemasid = b.tenanttypemasid
            left join mas_tenancyrefcode l on l.tenantmasid = a.tenantmasid
            where a.grouptenantmasid = $id and f.documentname='1';"; 
   
}
else if($load == "tenantdetails_rent")
{   
    $id = $_GET['itemval'];
    
    $table="Rent:</br>";    
    $sql = "select date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,a.yearlyhike,a.amount,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from trans_offerletter_rent a
            inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
            inner join group_tenant_det d on d.tenantmasid = c.tenantmasid
            where d.grouptenantmasid =$id;";
    $result = mysql_query($sql);
    if($result != null)
    {
        $table .="<table>";
        $table .="<tr>";
        $table .="<th>From Dt</th><th>To Dt</th><th>Increment</th><th>Rent</th><th>Created by</th><th>Created On</th>";
        $table .="</tr>";
        while($row = mysql_fetch_assoc($result))
        {
            $table .="<tr>";
            $table .="<td>".$row['fromdate']."</td>";
            $table .="<td>".$row['todate']."</td>";
            $table .="<td>".$row['yearlyhike']."</td>";
            $rent = $row['amount'];
            $table .="<td>".number_format($rent, 0, '.', ',')."</td>";
            $table .="<td>".$row['createdby']."</td>";
            $table .="<td>".$row['createdon']."</td>";            
            $table .="</tr>";
        }
        $table .="</table>";
    }
    
    $table .="</br>Sc:</br>";    
    $sql = "select date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,a.yearlyhike,a.amount,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from trans_offerletter_sc a
            inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
            inner join group_tenant_det d on d.tenantmasid = c.tenantmasid
            where d.grouptenantmasid =$id;";
    $result = mysql_query($sql);
    if($result != null)
    {
        $table .="<table>";
        $table .="<tr>";
        $table .="<th>From Dt</th><th>To Dt</th><th>Increment</th><th>Rent</th><th>Created by</th><th>Created On</th>";
        $table .="</tr>";
        while($row = mysql_fetch_assoc($result))
        {
            $table .="<tr>";
            $table .="<td>".$row['fromdate']."</td>";
            $table .="<td>".$row['todate']."</td>";
            $table .="<td>".$row['yearlyhike']."</td>";
            $sc = $row['amount'];
            $table .="<td>".number_format($sc, 0, '.', ',')."</td>";
            $table .="<td>".$row['createdby']."</td>";
            $table .="<td>".$row['createdon']."</td>";            
            $table .="</tr>";
        }
        $table .="</table>";
    }
    $table .="</br>Deposit:</br>";
    $sql = "select a.rentdeposit,a.scdeposit,a.stampduty,a.leegalfees,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from trans_offerletter_deposit a
            inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
            inner join group_tenant_det d on d.tenantmasid = c.tenantmasid
            where d.grouptenantmasid =$id;";
    $result = mysql_query($sql);
    if($result != null)
    {
        $table .="<table>";
        $table .="<tr>";
        $table .="<th>Rent</th><th>Sc</th><th>Stamp duty</th><th>Legal Fees</th><th>Created by</th><th>Created On</th>";
        $table .="</tr>";
        while($row = mysql_fetch_assoc($result))
        {
            $table .="<tr>";
            $rentdeposit = $row['rentdeposit'];
            $table .="<td>".number_format($rentdeposit, 0, '.', ',')."</td>";
            $scdeposit = $row['scdeposit'];
            $table .="<td>".number_format($scdeposit, 0, '.', ',')."</td>";
            
            $total1 = $rentdeposit +$scdeposit;            
            
            $stampduty = $row['stampduty'];
            $table .="<td>".number_format($stampduty, 0, '.', ',')."</td>";
            $legal = $row['leegalfees'];
            $table .="<td>".number_format($legal, 0, '.', ',')."</td>";
            
            $total2 = $stampduty +$legal;
            
            $table .="<td>".$row['createdby']."</td>";
            $table .="<td>".$row['createdon']."</td>";            
            $table .="</tr>";
        }
        $table .="<tr>";
            $table .="<td colspan='2' style='text-align:right;'> Rent+ Sc = ".number_format($total1, 0, '.', ',')."</td>";
            $table .="<td colspan='2' style='text-align:right;'> Stamp + Legal = ".number_format($total2, 0, '.', ',')."</td>";            
        $table .="</tr>";
        $total3 = $total1+$total2;
        $table .="<tr>";
            $table .="<td colspan='4' style='text-align:right;'> Total Deposit= ".number_format($total3, 0, '.', ',')."</td>";            
        $table .="</tr>";
        $table .="</table>";
    }
    $custom = array('msg'=> $table,'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
else if($load == "rec_tenantdetails")
{   
   $id = $_GET['itemval'];
   $sql= " select  b.tenantmasid,b.leasename,b.tradingname,b.nob,b.renewalfromid,b.active,b.shopoccupied,c.shopcode,c.size,d.buildingname,e.companyname,
            f.cpname,f.cpnid,f.cpmobile,f.cplandline,f.cpemailid,h.grouptenantmasid as waitinglist,
            b.telephone1,b.telephone2,b.poboxno,b.pincode,b.city,date_format(b.createddatetime,'%d-%m-%y') as createddatetime,b.createdby,
            g.age as rentcycle,g1.age as leaseterm from group_tenant_det a
            inner join rec_tenant b on  b.tenantmasid = a.tenantmasid
            inner join mas_shop c on  c.shopmasid = b.shopmasid
            inner join mas_building d on d.buildingmasid = c.buildingmasid
            inner join mas_company e on e.companymasid = d.companymasid
            inner join rec_tenant_cp f on f.tenantmasid = b.tenantmasid
            inner join mas_age g on g.agemasid = b.agemasidrc
            inner join mas_age g1 on g1.agemasid = b.agemasidlt
            left join waiting_list h on h.grouptenantmasid = a.grouptenantmasid
            where a.grouptenantmasid = $id and f.documentname='1';"; 
}
else if($load == "rec_tenantdetails_rent")
{   
    $id = $_GET['itemval'];
    
    $table="Rent:</br>";    
    $sql = "select date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,a.yearlyhike,a.amount,a.createdby,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from rec_trans_offerletter_rent a
            inner join rec_trans_offerletter b on b.offerlettermasid = a.offerlettermasid
            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
            inner join group_tenant_det d on d.tenantmasid = c.tenantmasid
            where d.grouptenantmasid =$id;";
    $result = mysql_query($sql);
    $foo=true;
    if($result != null)
    {
        $table .="<table>";
        $table .="<tr>";
        $table .="<th>From Dt</th><th>To Dt</th><th>Increment</th><th>Rent</th><th>Created by</th><th>Created On</th>";
        $table .="</tr>";
        while($row = mysql_fetch_assoc($result))
        {
            $table .="<tr>";
            $table .="<td>".$row['fromdate']."</td>";
            $table .="<td>".$row['todate']."</td>";
            $table .="<td>".$row['yearlyhike']."</td>";
            $rent = $row['amount'];
            $table .="<td>".number_format($rent, 0, '.', ',')."</td>";            
            $table .="<td>".$row['createdby']."</td>";
            $table .="<td>".$row['createdon']."</td>";            
            $table .="</tr>";
            $foo= false;
        }
        $table .="</table>";
    }    
    
    $table .="</br>Sc:</br>";    
    $sql = "select date_format(a.fromdate,'%d-%m-%Y') as fromdate,date_format(a.todate,'%d-%m-%Y') as todate,a.yearlyhike,a.amount,a.createdby,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from rec_trans_offerletter_sc a
            inner join rec_trans_offerletter b on b.offerlettermasid = a.offerlettermasid
            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
            inner join group_tenant_det d on d.tenantmasid = c.tenantmasid
            where d.grouptenantmasid =$id;";
    $result = mysql_query($sql);
    if($result != null)
    {
        $table .="<table>";
        $table .="<tr>";
        $table .="<th>From Dt</th><th>To Dt</th><th>Increment</th><th>Rent</th><th>Created by</th><th>Created On</th>";
        $table .="</tr>";
        while($row = mysql_fetch_assoc($result))
        {
            $table .="<tr>";
            $table .="<td>".$row['fromdate']."</td>";
            $table .="<td>".$row['todate']."</td>";
            $table .="<td>".$row['yearlyhike']."</td>";
            $sc = $row['amount'];
            $table .="<td>".number_format($sc, 0, '.', ',')."</td>";
            $table .="<td>".$row['createdby']."</td>";
            $table .="<td>".$row['createdon']."</td>";            
            $table .="</tr>";
            $foo= false;
        }
        $table .="</table>";
    }
    
    if($foo == true)
    $table="";
    
    $custom = array('msg'=> $table,'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
else if($load == "tenantdetails_invoice")
{   
    $id = $_GET['itemval'];
    $rid=0; // renewal tenant group masid
    $s="select grouptenantmasid from group_tenant_det where tenantmasid = (select a.renewalfromid from mas_tenant a
        inner join group_tenant_det b on b.tenantmasid = a.tenantmasid
        where b.grouptenantmasid =$id and a.renewal ='0');";
    $r = mysql_query($s);
    if($r != null)
    {
        while($ro = mysql_fetch_assoc($r))
        {
            $rid = $ro['grouptenantmasid'];
        }
    }
    if($rid > 0)
        $id .=",".$rid ;
    
    $gr_rent_tot=0;
    $gr_sc_tot=0;    
    $table="<table cellspacing='1' cellpading='1'><tr><th style='color:blue;'>INVOICE</th><th style='color:brown;'>ADVANCE INVOICE</th></tr>";
    $table .="<tr><td>";    
        $sql="select @n:=@n+1 sno,a.rent, a.sc,
                date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',date_format(a.todate,'%d-%m-%Y') as 'invto',a.invoiceno,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon
                from invoice a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid            
                ,(select @n:= 0) AS n
                where a.grouptenantmasid in ($id);";
        $result = mysql_query($sql);    
        $renttot=0;$sctot=0;
        if($result != null)
        {
            $table .="<table>";
            $table .="<tr>";
                $table .="<th>Sno</th><th>From</th><th>To</th><th>Inv No</th><th>Rent</th><th>Sc</th><th>Created By</th><th>Created On</th>";
            $table .="</tr>";
            while($row = mysql_fetch_assoc($result))
            {
                $table .="<tr>";
                $table .="<td>".$row['sno']."</td>";
                $table .="<td>".$row['invfrom']."</td>";
                $table .="<td>".$row['invto']."</td>";
                $table .="<td>".$row['invoiceno']."</td>";
                $rent = $row['rent'];
                $renttot +=$rent;
                $table .="<td>".number_format($rent, 0, '.', ',')."</td>";          
                $sc = $row['sc'];
                $sctot +=$sc;
                $table .="<td>".number_format($sc, 0, '.', ',')."</td>";
                $table .="<td>".$row['createdby']."</td>";
                $table .="<td>".$row['createdon']."</td>";
                $table .="</tr>";
            }
            $gr_rent_tot +=$renttot;
            $gr_sc_tot +=$sctot;
            $table .="<tr>";
            $table .="<td colspan='4' align='right'>Total</td>";
            $table .="<td>".number_format($renttot, 0, '.', ',')."</td>";          
            $table .="<td>".number_format($sctot, 0, '.', ',')."</td>";          
            $table .="</tr>";
            $table .="</table>";
        }        
    $table .="</td>";
    //advance invoice
    $table .="<td valign='top'>";  
        $sql="select @n:=@n+1 sno,a.rent, a.sc,
                date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',date_format(a.todate,'%d-%m-%Y') as 'invto',a.invoiceno,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon
                from advance_rent a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid            
                ,(select @n:= 0) AS n
                where a.grouptenantmasid in ($id);";
        $result = mysql_query($sql);
        $renttot=0;$sctot=0;
        if($result != null)
        {
            $table .="<table>";
            $table .="<tr>";
                $table .="<th>Sno</th><th>From</th><th>To</th><th>Inv No</th><th>Rent</th><th>Sc</th><th>Created By</th><th>Created On</th>";
            $table .="</tr>";
            while($row = mysql_fetch_assoc($result))
            {
                $table .="<tr>";
                $table .="<td>".$row['sno']."</td>";
                $table .="<td>".$row['invfrom']."</td>";
                $table .="<td>".$row['invto']."</td>";
                $table .="<td>".$row['invoiceno']."</td>";
                $rent = $row['rent'];
                $renttot +=$rent;
                $table .="<td>".number_format($rent, 0, '.', ',')."</td>";          
                $sc = $row['sc'];
                $sctot +=$sc;
                $table .="<td>".number_format($sc, 0, '.', ',')."</td>";
                $table .="<td>".$row['createdby']."</td>";
                $table .="<td>".$row['createdon']."</td>";
                $table .="</tr>";
            }
            $gr_rent_tot +=$renttot;
            $gr_sc_tot +=$sctot;
            $table .="<tr>";
            $table .="<td colspan='4' align='right'>Total</td>";
            $table .="<td>".number_format($renttot, 0, '.', ',')."</td>";          
            $table .="<td>".number_format($sctot, 0, '.', ',')."</td>";
            $table .="</tr>";
            $table .="</table>";
        }
    $table .="</td></tr>";    
    //CREDIT NOTE
    $table .="<tr><th colspan='2' style='color:red;'>CREDIT NOTE</th></tr>";
    $table .="<tr><td colspan='2'>";
        
        $sql="select @n:=@n+1 sno,a.creditnoteno,date_format(a.crdate,'%d-%m-%Y') as crdate,c.invoiceno,d.invoicedesc,c.value,c.vat,c.total,
                a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from invoice_cr_mas a
                inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                inner join invoice_cr_det c on c.invoicecrmasid = a.invoicecrmasid
                inner join invoice_desc d on d.invoicedescmasid = c.invoicedescmasid
                ,(select @n:= 0) AS n
                where a.grouptenantmasid in($id);";
        $result = mysql_query($sql);
        $amttot=0;$vattot=0;$totaltot=0;
        if($result != null)
        {
            $table .="<table>";
            $table .="<tr>";
                $table .="<th>Sno</th><th>Cr.No</th><th>Cr.Date</th><th>Inv No</th><th>Desc</th><th>Amount</th><th>Vat</th><th>total</th><th>Created By</th><th>Created On</th>";
            $table .="</tr>";
            while($row = mysql_fetch_assoc($result))
            {
                $table .="<tr>";
                $table .="<td>".$row['sno']."</td>";
                $table .="<td>".$row['creditnoteno']."</td>";
                $table .="<td>".$row['crdate']."</td>";
                $table .="<td>".$row['invoiceno']."</td>";
                $table .="<td>".$row['invoicedesc']."</td>";
                $amt = $row['value'];
                $amttot +=$amt;
                $table .="<td>".number_format($amt, 0, '.', ',')."</td>";          
                $vat = $row['vat'];
                $vattot +=$vat;
                $table .="<td>".number_format($vat, 0, '.', ',')."</td>";
                $total = $row['total'];
                $totaltot +=$total;
                $table .="<td>".number_format($total, 0, '.', ',')."</td>";                
                
                $table .="<td>".$row['createdby']."</td>";
                $table .="<td>".$row['createdon']."</td>";
                $table .="</tr>";
            }            
            $table .="<tr>";
            $table .="<td colspan='5' align='right'>Total</td>";
            $table .="<td>".number_format($amttot, 0, '.', ',')."</td>";          
            $table .="<td>".number_format($vattot, 0, '.', ',')."</td>";
            $table .="<td>".number_format($totaltot, 0, '.', ',')."</td>";          
            $table .="</tr>";
            $table .="</table>";
        }
    $table .="<tr><td>";
    //MANUAL INVOICE
    $table .="<tr><th colspan='2' style='color:black;'>MANUAL INVOICE</th></tr>";
    $table .="<tr><td colspan='2'>";
        
        //$sql="select @n:=@n+1 sno,a.creditnoteno,date_format(a.crdate,'%d-%m-%Y') as crdate,c.invoiceno,d.invoicedesc,c.value,c.vat,c.total,
        //        a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createdon from invoice_cr_mas a
        //        inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
        //        inner join invoice_cr_det c on c.invoicecrmasid = a.invoicecrmasid
        //        inner join invoice_desc d on d.invoicedescmasid = c.invoicedescmasid
        //        ,(select @n:= 0) AS n
        //        where a.grouptenantmasid in($id);";
        //$result = mysql_query($sql);
        //$amttot=0;$vattot=0;$totaltot=0;
        //if($result != null)
        //{
        //    $table .="<table>";
        //    $table .="<tr>";
        //        $table .="<th>Sno</th><th>Cr.No</th><th>Cr.Date</th><th>Inv No</th><th>Desc</th><th>Amount</th><th>Vat</th><th>total</th><th>Created By</th><th>Created On</th>";
        //    $table .="</tr>";
        //    while($row = mysql_fetch_assoc($result))
        //    {
        //        $table .="<tr>";
        //        $table .="<td>".$row['sno']."</td>";
        //        $table .="<td>".$row['creditnoteno']."</td>";
        //        $table .="<td>".$row['crdate']."</td>";
        //        $table .="<td>".$row['invoiceno']."</td>";
        //        $table .="<td>".$row['invoicedesc']."</td>";
        //        $amt = $row['value'];
        //        $amttot +=$amt;
        //        $table .="<td>".number_format($amt, 0, '.', ',')."</td>";          
        //        $vat = $row['vat'];
        //        $vattot +=$vat;
        //        $table .="<td>".number_format($vat, 0, '.', ',')."</td>";
        //        $total = $row['total'];
        //        $totaltot +=$total;
        //        $table .="<td>".number_format($total, 0, '.', ',')."</td>";                
        //        
        //        $table .="<td>".$row['createdby']."</td>";
        //        $table .="<td>".$row['createdon']."</td>";
        //        $table .="</tr>";
        //    }            
        //    $table .="<tr>";
        //    $table .="<td colspan='5' align='right'>Total</td>";
        //    $table .="<td>".number_format($amttot, 0, '.', ',')."</td>";          
        //    $table .="<td>".number_format($vattot, 0, '.', ',')."</td>";
        //    $table .="<td>".number_format($totaltot, 0, '.', ',')."</td>";          
        //    $table .="</tr>";
        //    $table .="</table>";
        //}
    $table .="<tr><td>";
        
    $table .="</table";
    //$table .="</br><u>Invoiced Total:</u> ";
    //
    //$table .="  Rent: ".number_format($gr_rent_tot, 0, '.', ',');
    //$table .="  Sc: ".number_format($gr_sc_tot, 0, '.', ',');
    //    
    $table .="</br></br>Credit Note:</br>"; 
    
    $custom = array('msg'=> $table,'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
else if($load == "loadofferletterforlease")
{
    $sql= "select c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            where c.companymasid=$companymasid and c.active ='1'            
            union
            select c1.leasename,c1.tradingname,c1.tenantcode,c1.renewalfromid,d1.shopcode, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid 
            where c1.companymasid=$companymasid and c1.active ='1'            
            order by leasename;";
    
}
else if($load == "loadtenantdetails")
{    
    //for auto complete
    if (isset($_GET['term']))
    {
        $term =  trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends;
    }
    $whr="";$and="";$sql="";
    if (isset($_GET['companymasid']))
    {
        $companymasid = $_GET['companymasid'];
        $whr=" where c.companymasid = $companymasid ";
        if (isset($_GET['buildingmasid']))
        {
            $buildingmasid = $_GET['buildingmasid'];
            if($buildingmasid >0)
            $whr = " and c.buildingmasid='$buildingmasid'";
        }
    }
    else
    {
        if (isset($_GET['buildingmasid']))
        {
            $buildingmasid = $_GET['buildingmasid'];
            if($buildingmasid >0)
            $whr = " where c.buildingmasid='$buildingmasid'";
        }       
    }    
    if (isset($_GET['tenantstate']))
    {
        $tenantstate = $_GET['tenantstate'];
        
        if($buildingmasid >0)
        {
            if($tenantstate ==0)
                $and= " and c.active='1' and c.shopoccupied='1'";
            else  if($tenantstate ==1)        
                $and= " and c.active='0' and c.shopoccupied='1'";
            else if($tenantstate ==2)
                $and= " and c.active='0' and c.shopoccupied='0'";
        }
        else
        {
            if($tenantstate ==0)
                $and= " where c.active='1' and c.shopoccupied='1'";
            else  if($tenantstate ==1)        
                $and= " where c.active='0' and c.shopoccupied='1'";
            else if($tenantstate ==2)
                $and= " where c.active='0' and c.shopoccupied='0'";
        }
        
        $whr .=$and;
    }
    if (isset($_GET['searchtype']))
    {
        $searchtype = $_GET['searchtype'];                
        
        if($searchtype ==0)// by tenancycode
        {
            $sql= " select tenancyrefcode,leasename,tenantmasid,leasename,tradingname,tenantcode,renewalfromid,shopcode,size,grouptenantmasid,buildingname,active,shopoccupied,waitinglistmasid
                    from (select g.tenancyrefcode,c.tenantmasid,c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,
                            d.shopcode,d.size, a.grouptenantmasid,e.buildingname,c.active,c.shopoccupied,f.waitinglistmasid from group_tenant_det a
                            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                            inner join mas_shop d on  d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            left join waiting_list f on f.grouptenantmasid = a.grouptenantmasid     
                            left join mas_tenancyrefcode g on g.tenantmasid = c.tenantmasid
                            union
                            select g.tenancyrefcode,c.tenantmasid,c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,
                            d.shopcode,d.size, a.grouptenantmasid,e.buildingname,c.active,c.shopoccupied,f.waitinglistmasid from group_tenant_det a
                            inner join rec_tenant c on c.tenantmasid = a.tenantmasid
                            inner join mas_shop d on  d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            left join waiting_list f on f.grouptenantmasid = a.grouptenantmasid
                            left join mas_tenancyrefcode g on g.tenantmasid = c.tenantmasid
                    )  as t
                    where tenancyrefcode like '%".$term."%'order by tenantmasid desc;";
        }
        else // by tenant name
        {
            $sql= " select tenancyrefcode,leasename,tenantmasid,leasename,tradingname,tenantcode,renewalfromid,shopcode,size,grouptenantmasid,buildingname,active,shopoccupied,waitinglistmasid
                    from (select g.tenancyrefcode,c.tenantmasid,c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,
                            d.shopcode,d.size, a.grouptenantmasid,e.buildingname,c.active,c.shopoccupied,f.waitinglistmasid from group_tenant_det a
                            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                            inner join mas_shop d on  d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            left join waiting_list f on f.grouptenantmasid = a.grouptenantmasid
                            left join mas_tenancyrefcode g on g.tenantmasid = c.tenantmasid
                            $whr
                            union
                            select g.tenancyrefcode,c.tenantmasid,c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,
                            d.shopcode,d.size, a.grouptenantmasid,e.buildingname,c.active,c.shopoccupied,f.waitinglistmasid from group_tenant_det a
                            inner join rec_tenant c on c.tenantmasid = a.tenantmasid
                            inner join mas_shop d on  d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            left join waiting_list f on f.grouptenantmasid = a.grouptenantmasid
                            left join mas_tenancyrefcode g on g.tenantmasid = c.tenantmasid
                            $whr
                    )  as t
                    where leasename like '%".$term."%' or tradingname like '%".$term."%'  order by tenantmasid desc;";
        }
    }    
    ////$json[]=array(
    ////                'id'=> 1,
    ////                'label'=> $whr,
    ////                'rel'=> $buildingmasid,
    ////                'value'=> $tenantstate,                      
    ////            );
    ////echo json_encode($json);
    ////exit;
   
    
   $query=mysql_query($sql);
   $json=array();
   $renewalfromid =0;$active=0;$shopoccupied=0;
   while($row=mysql_fetch_array($query))
   {
        $renewal="";$waitinglist="";
        $tenancyrefcode=$row["tenancyrefcode"];
        $renewalfromid = $row["renewalfromid"];
        $tenantmasid = $row["tenantmasid"];
        $active = $row["active"];
        $shopoccupied = $row["shopoccupied"];
        if($row["waitinglistmasid"]!="")
            $waitinglist="Waitinglist";        
                
        $sqlrec = "select active,shopoccupied from rec_tenant where tenantmasid =$tenantmasid; ";
        $resultrec = mysql_query($sqlrec);
        if($resultrec != null)
        {            
            if(mysql_num_rows($resultrec) >= 1)
            {
                $rowrec = mysql_fetch_assoc($resultrec);
                $active = $rowrec["active"];
                $shopoccupied = $rowrec["shopoccupied"];        
            }
        }
        
        $bool = true;
        
        $sql1 = "select max(a.todate) as todate from trans_offerletter_rent a
                inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                where c.tenantmasid ='$tenantmasid';";
        $result = mysql_query($sql1);
        if($result != null)
        {
            $row1 = mysql_fetch_assoc($result);
            $today = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datetime)) . " + 0 Months"));
            $today = strtotime($today);
            $expirydt  = $row1["todate"];
            $expirydt  = strtotime($expirydt);
            if ($expirydt < $today) {
               $bool = false;
            }
        }
        
        if($renewalfromid > 0)
        {
           $renewal= " **RENEWED** ";
        }
        
        if($bool == false)
        {
            $renewal .=" (Expired)";
        }
        $msg ="**";
        
        if($active ==1 and $shopoccupied==1)
        $msg = " active and occupied";
        
        if($active ==0 and $shopoccupied==1)
        $msg = " partialy discharged and occupied ";
        
        if($waitinglist!="")
        $msg .= " waitinglist";
        
        if($row["tradingname"] !="")
            $row["leasename"] .=" T/A ".$row["tradingname"];
            
            $json[]=array(
                    'id'=> $row["grouptenantmasid"],
                    'label'=> $row["leasename"]."".$renewal." ".$row["shopcode"]." ".$row["size"]." (sq)"." ".$msg." ".$tenancyrefcode,
                    'rel'=> $row["buildingname"],
                    'value'=> $row["leasename"]."".$renewal." ".$row["shopcode"]." ".$row["size"]." (sq)"." Code: ".$tenancyrefcode  
                    //'value'=> $row["leasename"]."  ".$row["shopcode"]                    
                );
            
            //$json[]=array(
            //        'id'=> $row["grouptenantmasid"],
                    //'label'=> $row["leasename"]."".$renewal." - ".$row["shopcode"]." - ".$row["size"]." sqrft"." Status =".$active."-".$shopoccupied." - ".$waitinglist,
            //        'rel'=> $row["buildingname"],
            //        'value'=> $row["leasename"]."".$renewal." - ".$row["shopcode"]." - ".$row["size"]." sqrft"  
            //        //'value'=> $row["leasename"]."  ".$row["shopcode"]                    
            //    ); 
    }
 
    echo json_encode($json);
    exit;
    
}
else if($load == "loadleaseforsurrender")
{
   //for auto complete
    if (isset($_GET['term']))
    {
        $term =  trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends;
    }
    $whr="";
    if (isset($_GET['companymasid']))
    {
        $companymasid = $_GET['companymasid'];
        $whr=" where c.companymasid = $companymasid ";
        if (isset($_GET['buildingmasid']))
        {
            $buildingmasid = $_GET['buildingmasid'];
            if($buildingmasid >0)
            $whr = " and e.buildingmasid='$buildingmasid'";
        }
    }
    else
    {
        if (isset($_GET['buildingmasid']))
        {
            $buildingmasid = $_GET['buildingmasid'];
            if($buildingmasid >0)
            $whr = " where e.buildingmasid='$buildingmasid'";
        }
    }
    
    
    $sql= " select leasename,tenantmasid,leasename,tradingname,tenantcode,renewalfromid,shopcode,size,grouptenantmasid,buildingname,active,shopoccupied
            from (select c.tenantmasid,c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,
            d.shopcode,d.size, a.grouptenantmasid,e.buildingname,c.active,c.shopoccupied from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            inner join mas_building e on e.buildingmasid = d.buildingmasid            
            where c.active='1' and c.shopoccupied='1'
            union
            select c.tenantmasid,c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,
            d.shopcode,d.size, a.grouptenantmasid,e.buildingname,c.active,c.shopoccupied from group_tenant_mas a
            inner join rec_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            inner join mas_building e on e.buildingmasid = d.buildingmasid            
            where c.active='1' and c.shopoccupied='1')  as t
            where leasename like '%".$term."%' or tradingname like '%".$term."%'  order by tenantmasid desc;";
   $query=mysql_query($sql);
   $json=array();
   $renewalfromid =0;$active=0;$shopoccupied=0;
   while($row=mysql_fetch_array($query))
   {
        $renewal="";
        $renewalfromid = $row["renewalfromid"];
        $tenantmasid = $row["tenantmasid"];
        $active = $row["active"];
        $shopoccupied = $row["shopoccupied"];
        
        $sqlrec = "select active,shopoccupied from rec_tenant where tenantmasid =$tenantmasid; ";
        $resultrec = mysql_query($sqlrec);
        if($resultrec != null)
        {            
            if(mysql_num_rows($resultrec) >= 1)
            {
                $rowrec = mysql_fetch_assoc($resultrec);
                $active = $rowrec["active"];
                $shopoccupied = $rowrec["shopoccupied"];        
            }
        }
        
        if($active ==1)
            $active="Active";
            
        if($shopoccupied ==1)
            $shopoccupied="Occupied";        
        
        $bool = true;
        
        $sql1 = "select max(a.todate) as todate from trans_offerletter_rent a
                inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                where c.tenantmasid ='$tenantmasid';";
        $result = mysql_query($sql1);
        if($result != null)
        {
            $row1 = mysql_fetch_assoc($result);
            $today = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datetime)) . " + 0 Months"));
            $today = strtotime($today);
            $expirydt  = $row1["todate"];
            $expirydt  = strtotime($expirydt);
            if ($expirydt < $today) {
               $bool = false;
            }
        }
        
        if($renewalfromid > 0)
        {
           $renewal= " **RENEWED** ";
        }
        
        if($bool == false)
        {
            $renewal .=" (Expired)";
        }
        
        if($row["tradingname"] !="")
            $row["leasename"] .=" T/A ".$row["tradingname"];
            
            $json[]=array(
                    'id'=> $row["grouptenantmasid"],
                    'label'=> $row["leasename"]."".$renewal." - ".$row["shopcode"]." - ".$row["size"]." sqrft"." Status =".$active."-".$shopoccupied,
                    'rel'=> $row["buildingname"],
                    'value'=> $row["leasename"]."".$renewal." - ".$row["shopcode"]." - ".$row["size"]." sqrft"  
                    //'value'=> $row["leasename"]."  ".$row["shopcode"]                    
                );
    }
 
    echo json_encode($json);
    exit;
   // //for auto complete
   //if (isset($_GET['term']))
   //{
   //   $term =  trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends;
   //}
   // $sql= "select c.tenantmasid,c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas  a
   //         inner join mas_tenant c on c.tenantmasid = a.tenantmasid
   //         inner join mas_shop d on  d.shopmasid = c.shopmasid
   //         where c.companymasid=$companymasid and c.active ='1'            
   //         and a.grouptenantmasid not in (select grouptenantmasid from rpt_surrender_lease)
   //         and c.leasename like '%".$term."%' or c.tradingname like '%".$term."%' 
   //         union
   //         select c1.tenantmasid,c1.leasename,c1.tradingname,c1.tenantcode,c1.renewalfromid,d1.shopcode, a1.grouptenantmasid from group_tenant_mas a1
   //         inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
   //         inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid 
   //         where c1.companymasid=$companymasid and c1.active ='1'            
   //         and a1.grouptenantmasid not in (select grouptenantmasid from rpt_surrender_lease)
   //         and c1.leasename like '%".$term."%' or c1.tradingname like '%".$term."%'
   //         order by leasename;";	       
   //$query=mysql_query($sql);
   //$json=array();
   //$renewalfromid =0;   
   //while($row=mysql_fetch_array($query)){
   //     $renewal="";
   //     $renewalfromid = $row["renewalfromid"];
   //     $tenantmasid = $row["tenantmasid"];
   //     
   //     $bool = true;
   //     
   //     $sql1 = "select max(a.todate) as todate from trans_offerletter_rent a
   //             inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
   //             inner join mas_tenant c on c.tenantmasid = b.tenantmasid
   //             where c.tenantmasid ='$tenantmasid';";
   //     $result = mysql_query($sql1);
   //     if($result != null)
   //     {
   //         $row1 = mysql_fetch_assoc($result);
   //         $today = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datetime)) . " + 0 Months"));
   //         $today = strtotime($today);
   //         $expirydt  = $row1["todate"];
   //         $expirydt  = strtotime($expirydt);
   //         if ($expirydt < $today) {
   //            $bool = false;
   //         }
   //     }
   //     
   //     if($renewalfromid > 0)
   //     {
   //        $renewal= " **RENEWED** ";
   //     }
   //     
   //     if($bool == false)
   //     {
   //         $renewal .=" (Expired)";
   //     }
   //     
   //     if($row["tradingname"] !="")
   //         $row["leasename"] .=" T/A ".$row["tradingname"];
   //         
   //         $json[]=array(
   //                 'id'=> $row["grouptenantmasid"],
   //                 'label'=> $row["leasename"]."".$renewal." - ".$row["shopcode"],                    
   //                 'value'=> $row["leasename"]."  ".$row["shopcode"]                    
   //             );        
   //     
   // 
   // }
   //
   // echo json_encode($json);
   // exit;
    
}
else if($load == "loaddistresstenant")
{
    $sql= "select c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            where c.companymasid=$companymasid and c.active ='1'
            and a.grouptenantmasid not in (select grouptenantmasid from rpt_distress)
            union
            select c1.leasename,c1.tradingname,c1.tenantcode,c1.renewalfromid,d1.shopcode, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid 
            where c1.companymasid=$companymasid and c1.active ='1'
            and a1.grouptenantmasid not in (select grouptenantmasid from rpt_distress)
            order by leasename;";
    
}
else if($load == "loadSimpleTenant")
{
    $sql= "select c.doc,c.leasename,c.tradingname,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active ='1' and c.tenanttypemasid='2'
            union
            select c.doc,c.leasename,c.tradingname,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join rec_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active ='1' and c.tenanttypemasid='2'
            order by leasename;";
}
else if($load == "loadKioskAndWashroomTenant")
{
    $sql= "select c.doc,c.leasename,c.tradingname,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active ='1' and c.tenanttypemasid='2'
            union
            select c.doc,c.leasename,c.tradingname,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join rec_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active ='1' and c.tenanttypemasid='2'
            order by leasename;";
}
else if($load == "loadBankTenant")
{
    $sql= "select c.doc,c.leasename,c.tradingname,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active ='1' and c.tenanttypemasid='4'
            union
            select c.doc,c.leasename,c.tradingname,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join rec_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid 
            where c.companymasid=$companymasid and c.active ='1' and c.tenanttypemasid='4'
            order by leasename;";
}

else if($load == "documentstatus")
{   
    $grouptenantmasid = $_GET['itemval'];
    $sql ="select a.grouptenantmasid , b.leasename,b.tradingname,b.tenantcode,j.buildingname,
      group_concat(c.shopcode,' , ',c.size,':') as premises,
      b.pincode,b.poboxno,b.city,
      DATE_FORMAT( b.doo, '%d-%m-%Y' ) AS doo,
      DATE_FORMAT( b.doc, '%d-%m-%Y' ) AS doc,      
      @t2:= DATE_ADD(b.doc,interval @t1:=g.age year) as a,									
      DATE_FORMAT( DATE_ADD(b.doc,interval @t1:=g.age year), '%d-%m-%Y' ) AS b,		   
      DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
      g.age AS term, g1.age AS period,i.cpname,      
      DATE_FORMAT( k.createddatetime, '%d-%m-%Y' ) AS doccrdate,
      DATE_FORMAT( k.modifieddatetime, '%d-%m-%Y' ) AS docmoddate,
      DATE_FORMAT( m.createddatetime, '%d-%m-%Y' ) AS leasecrdate,
      DATE_FORMAT( m.modifieddatetime, '%d-%m-%Y' ) AS leasemoddate,
      n.leasestatus,
      DATE_FORMAT( n.offletttotenantdate, '%d-%m-%Y' ) AS offletttotenantdate ,DATE_FORMAT( n.offlettretrundate, '%d-%m-%Y' ) AS offlettretrundate ,
      DATE_FORMAT( n.leasetotenantdate, '%d-%m-%Y' ) AS leasetotenantdate,DATE_FORMAT( n.leasereturndate, '%d-%m-%Y' ) AS leasereturndate ,      
      DATE_FORMAT( n.leasetolandlorddate, '%d-%m-%Y' ) AS leasetolandlorddate,
      DATE_FORMAT( n.leasetobankdate, '%d-%m-%Y' ) AS leasetobankdate,DATE_FORMAT( n.leasereturnfrombankdate, '%d-%m-%Y' ) AS leasereturnfrombankdate,
      DATE_FORMAT( n.leasetodutyassdate, '%d-%m-%Y' ) AS leasetodutyassdate,
      DATE_FORMAT( n.leasedutypaiddate, '%d-%m-%Y' ) AS leasedutypaiddate,DATE_FORMAT( n.leasereturnfromassdate, '%d-%m-%Y' ) AS leasereturnfromassdate,
      DATE_FORMAT( n.leasefinaltotenantdate, '%d-%m-%Y' ) AS leasefinaltotenantdate,DATE_FORMAT( n.leasefinalreturndate, '%d-%m-%Y' ) AS leasefinalreturndate,
      n.istenantpinno,DATE_FORMAT( n.leasefileddate, '%d-%m-%Y' ) AS leasefileddate,
      n.remarks
      from group_tenant_det a
      inner join mas_tenant b on b.tenantmasid = a.tenantmasid
      inner join mas_shop c on c.shopmasid = b.shopmasid
      inner join group_tenant_mas d on d.grouptenantmasid = a.grouptenantmasid
      inner join mas_block e on e.blockmasid =  c.blockmasid
      inner join mas_floor f on f.floormasid = c.floormasid
      inner join mas_building j on j.buildingmasid =c.buildingmasid
      
      inner join mas_age g ON g.agemasid = b.agemasidlt
      inner join mas_age g1 ON g1.agemasid = b.agemasidrc
      inner join mas_building h ON h.buildingmasid = c.buildingmasid
      inner join mas_tenant_cp i ON i.tenantmasid = b.tenantmasid
      
      left join rpt_offerletter k on k.grouptenantmasid = a.grouptenantmasid
      left join rpt_lease m on m.grouptenantmasid = a.grouptenantmasid
      left join trans_document_status n on n.grouptenantmasid = a.grouptenantmasid
      
      where a.grouptenantmasid =$grouptenantmasid and i.documentname='1' and c.companymasid=$companymasid order by b.leasename;";
      
       //$custom = array('msg'=>$sql,'s'=>$sql);
       //     $response_array [] = $custom;
       //     echo '{
       //         "error":'.json_encode($response_array).
       //     '}';
       //     exit;
}
else if($load == "loadBuilding")
{
    $sql = "SELECT * FROM mas_building where companymasid=$companymasid";
}
else if($load == "loadBuildingBlock")
{
    $buildingmasid = $_GET['itemval'];
    $sql = "SELECT b.blockmasid,b.blockname, a.buildingname\n"
        . "FROM mas_building a\n"
        . "INNER JOIN mas_block b ON a.buildingmasid = b.buildingmasid\n"
        . "WHERE b.companymasid = $companymasid\n"
        . "AND b.buildingmasid = $buildingmasid";
}
else if($load == "loadBlockFloor")
{
    $blockmasid = $_GET['itemval'];
    $sql = "SELECT a.floormasid,a.floorname \n"
        . "FROM mas_floor a\n"
        . "INNER JOIN mas_block b ON a.blockmasid = b.blockmasid\n"
        . "WHERE a.companymasid = $companymasid\n"
        . "AND a.blockmasid = $blockmasid";
}
else if($load == "loadFloorShop")
{
    $floormasid = $_GET['itemval'];
    $sql = "select a.shopmasid,a.shopcode \n"
            . "from mas_shop a\n"
            . "INNER JOIN mas_floor b ON a.floormasid = b.floormasid\n "
            . "where a.shopmasid not in (select shopmasid from mas_tenant)\n "
            . "AND a.floormasid = $floormasid "
            . "AND a.companymasid = $companymasid";
}
else if($load == "loadTenantBuilding")
{
    $sql = "select b.buildingmasid , b.buildingname\n"
    . "from mas_tenant a\n"
    . "inner join mas_building b on a.buildingmasid = b.buildingmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadTenantBlock")
{
    $sql = "select b.blockmasid , b.blockname\n"
    . "from mas_tenant a\n"
    . "inner join mas_block b on a.buildingmasid = b.buildingmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadTenantFloor")
{
    $sql = "select b.floormasid , b.floorname\n"
    . "from mas_tenant a\n"
    . "inner join mas_floor b on a.blockmasid = b.blockmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "loadTenantShop")
{
    $sql = "select b.shopmasid , b.shopcode\n"
    . "from mas_tenant a\n"
    . "inner join mas_shop b on a.shopmasid = b.shopmasid\n"
    . "where a.tenantmasid=".$load= $_GET['itemval']." and  a.companymasid=$companymasid";
}
else if($load == "details")
{
    $sql = "SELECT *,\n"
    . "DATE_FORMAT( doo, \"%d-%m-%Y\" ) as \"d1\" ,\n"
    . "DATE_FORMAT( doc, \"%d-%m-%Y\" ) as \"d2\"\n"
    . "FROM mas_tenant  where tenantmasid =".$load= $_GET['itemval']
    . " and  companymasid=$companymasid and active=1";
}
else if($load == "acyear")
{
    $buildingmasid= $_GET['itemval'];
    
    //$sql = "select date_format(a.acyearfrom,'%M %Y') as acyearfrom ,date_format(a.acyearto,'%M %Y') as acyearto 
    //        from mas_acyear a
    //        inner join mas_building b on b.companymasid = a.companymasid  
    //        where b.buildingmasid ='$buildingmasid' and a.active='1'";
            
    $sql = "SELECT max(yearStart) as yst,max(yearEnd) as yend FROM(
             select distinct date_format(CURDATE(),'01 %M %Y') as yearStart,date_format(Max(b.todate),'%d %M %Y') as yearEnd from trans_offerletter a
             inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
             inner join mas_tenant c on c.tenantmasid = a.tenantmasid
             inner join mas_building d on d.buildingmasid = c.buildingmasid
             where d.buildingmasid =$buildingmasid and a.active='1'
             union
             select distinct date_format(CURDATE(),'01 %M %Y') as yearStart,date_format(Max(b.todate),'%d %M %Y') as yearEnd from trans_offerletter a
             inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
             inner join rec_tenant c on c.tenantmasid = a.tenantmasid
             inner join mas_building d on d.buildingmasid = c.buildingmasid
             where d.buildingmasid =$buildingmasid and a.active='1') as t";
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
            $custom = array('msg'=>$sql,'s'=>"Success"); 
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