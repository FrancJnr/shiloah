<?php    
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
try
{    
    $invno_creditnote=0;
    $action=$_GET['action'];
    if($action == "invno_creditnote")
    {
        $companymasid = $_GET['companymasid'];
        $sql="select invcrendno+1 as 'invno' from invoice_no_cr where companymasid =".$companymasid;
        $result = mysql_query($sql);
        if($result !=null)
        {
            $row = mysql_fetch_assoc($result);
            $invno_creditnote=$row['invno'];
        }
        
        ////$custom = array('result'=>$invno_creditnote,'s'=>'Success');
        ////$response_array[] = $custom;
        ////echo '{"error":'.json_encode($response_array).'}';
        
        $sql = "select buildingmasid, buildingname from mas_building where companymasid =".$companymasid." order by buildingmasid;";        
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            while($obj = mysql_fetch_object($result))
            {
                $arr[] = $obj;
            }
            $custom = array('result'=>$invno_creditnote,'s'=>"Success"); 
            $response_array [] = $custom;
            echo '{
                "myResult":'.json_encode($arr).',
                "error":'.json_encode($response_array).
            '}';				
        }
    }
    else if($action == "others")
    {
        $companymasid = $_GET['companymasid'];
        $sql = "select invoicemanmasid,toaddress,premise,remarks from invoice_man_mas where companymasid =".$companymasid."
                and grouptenantmasid =0 order by buildingmasid;";        
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            while($obj = mysql_fetch_object($result))
            {
                $arr[] = $obj;
            }
            $custom = array('result'=>"",'s'=>"Success"); 
            $response_array [] = $custom;
            echo '{
                "myResult":'.json_encode($arr).',
                "error":'.json_encode($response_array).
            '}';				
        }
    }
    else if($action == "others_det")
    {
        $invoicemanmasid = $_GET['invoicemanmasid'];
        $sql = "select toaddress,premise,remarks from invoice_man_mas where 
                invoicemanmasid = '$invoicemanmasid';";
        ////$custom = array('result'=>$sql,'s'=>"Success"); 
        ////    $response_array [] = $custom;
        ////    echo '{"error":'.json_encode($response_array).'}';
        ////    exit;
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            while($obj = mysql_fetch_object($result))
            {
                $arr[] = $obj;
            }
            $custom = array('result'=>"",'s'=>"Success"); 
            $response_array [] = $custom;
            echo '{
                "myResult":'.json_encode($arr).',
                "error":'.json_encode($response_array).
            '}';				
        }
    }
    else if($action == "loandinvoiceno_others")
    {
        $invoicemanmasid = $_GET['invoicemanmasid'];
        $sql = "select invoiceno,grouptenantmasid from invoice_man_mas where invoicemanmasid = '$invoicemanmasid';";
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
                $custom = array('result'=>$sql,'s'=>"Success"); 
                $response_array [] = $custom;
                echo '{
                    "myResult":'.json_encode($arr).',
                    "error":'.json_encode($response_array).
                '}';
            }            
        }
    }
    else if($action == "loandinvoiceno")
    {
        $grouptenantmasid = $_GET['grouptenantmasid'];
        $sql = "select invoiceno,grouptenantmasid from invoice where grouptenantmasid = '$grouptenantmasid'
                union
                select invoiceno,grouptenantmasid from advance_rent where grouptenantmasid = '$grouptenantmasid'
                union
                select invoiceno,grouptenantmasid from invoice_man_mas where grouptenantmasid = '$grouptenantmasid';";
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
                $custom = array('result'=>$sql,'s'=>"Success"); 
                $response_array [] = $custom;
                echo '{
                    "myResult":'.json_encode($arr).',
                    "error":'.json_encode($response_array).
                '}';
            }            
        }
    }
    else if($action == "tenantdetails")
    {
        $grouptenantmasid = $_GET['grouptenantmasid'];
        $tenancyrefcode = gettenancyrefcode($grouptenantmasid);
        $tenantaddress="";$buildingaddress="";$buildingmasid="";$companymasid="";
        $shop="";$remarks="";        
        $sql="select 
            case b.tradingname 
                    when b.tradingname ='' then concat(b.leasename ,' (T/A) ',b.tradingname)
                    when b.tradingname <>'' then concat(b.leasename)  
            end as tenant,b.remarks,
            b.poboxno,b.pincode,b.city,b.buildingmasid,b.companymasid,d.buildingname,c.shopcode
            from group_tenant_det a
                        inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
                        inner join mas_shop c on  c.shopmasid = b.shopmasid
                                    inner join mas_building d on d.buildingmasid = c.buildingmasid
                        where a.grouptenantmasid = '$grouptenantmasid'
                        union
            select 
            case b1.tradingname 
                    when b1.tradingname ='' then concat(b1.leasename ,' (T/A) ',b1.tradingname)
                    when b1.tradingname <>'' then concat(b1.leasename)  
            end as tenant,b1.remarks,
            b1.poboxno,b1.pincode,b1.city,b1.buildingmasid,b1.companymasid,d1.buildingname,c1.shopcode
            from group_tenant_det a1
                        inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
                        inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
                                    inner join mas_building d1 on d1.buildingmasid = c1.buildingmasid
                        where a1.grouptenantmasid = '$grouptenantmasid';";
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $tenantaddress = $row['tenant']."," ;
                $tenantaddress .= "Tenancy Refcode : ".$tenancyrefcode."," ;
                if($row['pincode'] == "")
                        $tenantaddress .= "P.O.Box : ".$row['poboxno']."," ;
                else
                    $tenantaddress .= "P.O.Box : ".$row['poboxno']." - ".$row['pincode']."," ;
                $tenantaddress .= $row['city'].".";
                $shop .=$row['shopcode']." ";
                $remarks .=$row['remarks']." ";                
                $buildingaddress = $row['buildingname']." - Shop no: ".$shop." ".$remarks;
                $buildingmasid = $row['buildingmasid'];
                $companymasid= $row['companymasid'];
            }
        }
        $custom = array('tenantaddress'=>$tenantaddress,
                        'buildingaddress'=>$buildingaddress,
                        'buildingmasid'=>$buildingmasid,
                        'companymasid'=>$companymasid,
                        's'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';   
    }
    else if($action == "invoicedetail_others")    
    {
        $sql="";$invtable="";$boo=false;$remarks="";
        $table ="<div style='height:100px;overflow:auto;'>";
        $table .="<table id='inv_list_tbl'>";
        $table .="<tr><th>Invoice No</th><th>Fromdate</th><th>Todate</th><th>Description</th>
                     <th>Value</th><th>Vat</th><th>Total</th><th>Remarks</th></tr>";        
        $invoicemanmasid = $_GET['invoicemanmasid'];
        $sql = "select a.invoiceno,c.invoicedesc,b.value,b.vat,b.amount,date_format(a.fromdate,'%d-%m-%Y') as fromdate,
                date_format(a.todate,'%d-%m-%Y') as todate from invoice_man_mas a 
                inner join invoice_man_det b on b.invoicemanmasid = a.invoicemanmasid
                inner join invoice_desc c on c.invoicedescmasid = b.invoicedescmasid               
                where a.invoicemanmasid ='$invoicemanmasid';";
        $result = mysql_query($sql);
        if($result != null)
        {
            if(mysql_num_rows($result) >=1)
            {                
                while($row = mysql_fetch_assoc($result))
                {
                    $boo=true;                                        
                    $table .="<tr>";
                        $invoiceno = $row['invoiceno'];                                                
                        $table .="<td>$invoiceno</td>";                                                
                        $table .="<td>".$row['fromdate']."</td>";
                        $table .="<td>".$row['todate']."</td>";
                        $table .="<td>".$row['invoicedesc']."</td>";
                        $table .="<td>".number_format($row['value'], 0, '.', ',')."</td>";                                                    
                        $table .="<td>".number_format($row['vat'], 0, '.', ',')."</td>";                                                    
                        $table .="<td>".number_format($row['amount'], 0, '.', ',')."</td>";
                        $table .="<td>Manual Invoice</td>";
                        
                    $table .="</tr>";
                }
            }
        }
        if($boo == false)
            $table .="<tr><td colspan='10'>NO INVOICES FOUND</td></tr>";
        
        $table .="</table></div>";
        $custom = array('result'=>$table,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit;
    }
    else if($action == "invoicedetails")
    {
        $sql="";$invtable="";$boo=false;$remarks="";
        $table ="<div style='height:100px;overflow:auto;'>";
        $table .="<table id='inv_list_tbl'>";
        $table .="<tr><th>Select</th><th>Invoice No</th><th>Tenant</th><th>Fromdate</th><th>Todate</th>
        <th>Rent</th><th>Vat</th><th>Sc</th><th>Vat</th><th>Total</th><th>Remarks</th></tr>";        
        $grouptenantmasid = $_GET['grouptenantmasid'];
        $j=1;
        for($i =0;$i<=1;$i++)
        {
            if($i ==0)
            {
                $invmasid = "a.invoicemasid as invoiceid ";
                $invtable="invoice";
                $remarks= "Regular Invoice";
            }
            else{
                $invmasid = "a.advancerentmasid as invoiceid ";
                $invtable="advance_rent";
                $remarks= "Advance Invoice";
            }            
            $sql = "select $invmasid,c.leasename,c.tradingname,d.shopcode,d.size,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,
                    date_format(a.todate,'%d-%m-%Y') as todate,a.rent,round((a.rent*14/100)) as rentvat,a.sc,round((a.sc*14/100)) as scvat,
                    a.rent+round((a.rent*14/100))+a.sc+round((a.sc*14/100)) as total from $invtable a
                    inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                    inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                    inner join mas_shop d on d.shopmasid = c.shopmasid
                    inner join mas_building e on e.buildingmasid = d.buildingmasid
                    where a.grouptenantmasid ='$grouptenantmasid';";
            $result = mysql_query($sql);
            if($result != null)
            {
                if(mysql_num_rows($result) >=1)
                {                   
                    while($row = mysql_fetch_assoc($result))
                    {
                        $boo=true;
                        $leasename = $row['leasename'];
                        if($row['tradingname'] != "")
                            $leasename .= $row['tradingname'];
                        $table .="<tr>";
                            $invoiceno = $row['invoiceno'];
                            $invoicemasid = $row['invoiceid'];
                            $table .="<td>".$j++."</td>";
                            //$table .="<td><input type='checkbox' class='chk' id ='$invoicemasid' name='$invoicemasid' rel='$invoiceno'></td>";
                            $table .="<td>$invoiceno</td>";
                            $table .="<td>$leasename</td>";
                            //$table .="<td>".$row['shopcode']."</td>";
                            //$table .="<td>".$row['size']."</td>";                            
                            $table .="<td>".$row['fromdate']."</td>";
                            $table .="<td>".$row['todate']."</td>";                            
                            $table .="<td>".number_format($row['rent'], 0, '.', ',')."</td>";
                            $table .="<td>".number_format($row['rentvat'], 0, '.', ',')."</td>";                            
                            $table .="<td>".number_format($row['sc'], 0, '.', ',')."</td>";
                            $table .="<td>".number_format($row['scvat'], 0, '.', ',')."</td>";                            
                            $table .="<td>".number_format($row['total'], 0, '.', ',')."</td>";
                            $table .="<td>$remarks</td>";
                        $table .="</tr>";
                    }
                }
            }
        }
        //$table .="<tr>$sql</tr>";        
        $sql = "select a.invoicemanmasid,c.leasename,c.tradingname,d.shopcode,d.size,a.invoiceno,date_format(a.fromdate,'%d-%m-%Y') as fromdate,
                   date_format(a.todate,'%d-%m-%Y') as todate,a.totalvalue,a.totalvat,a.totalamount from invoice_man_mas a
                   inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                   inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                   inner join mas_shop d on d.shopmasid = c.shopmasid
                   inner join mas_building e on e.buildingmasid = d.buildingmasid
                   where a.grouptenantmasid ='$grouptenantmasid';";
        $result = mysql_query($sql);
        if($result != null)
        {
            if(mysql_num_rows($result) >=1)
            {                
                while($row = mysql_fetch_assoc($result))
                {
                    $boo=true;
                    $leasename = $row['leasename'];
                    if($row['tradingname'] != "")
                        $leasename .= $row['tradingname'];
                    $table .="<tr>";
                        $invoiceno = $row['invoiceno'];
                        $invoicemanmasid = $row['invoicemanmasid'];
                        $table .="<td>".$j++."</td>";
                        //$table .="<td><input type='checkbox' class='chk' id ='$invoicemanmasid' name='$invoicemanmasid' rel='$invoiceno'></td>";
                        $table .="<td>$invoiceno</td>";
                        $table .="<td>$leasename</td>";
                        //$table .="<td>".$row['shopcode']."</td>";
                        //$table .="<td>".$row['size']."</td>";                        
                        $table .="<td>".$row['fromdate']."</td>";
                        $table .="<td>".$row['todate']."</td>";
                        $table .="<td colspan='2'>".number_format($row['totalvalue'], 0, '.', ',')."</td>";                                                    
                        $table .="<td colspan='2'>".number_format($row['totalvat'], 0, '.', ',')."</td>";                                                    
                        $table .="<td>".number_format($row['totalamount'], 0, '.', ',')."</td>";
                        $table .="<td>Manual Invoice</td>";
                        
                    $table .="</tr>";
                }
            }
        }
        if($boo == false)
            $table .="<tr><td colspan='10'>NO INVOICES FOUND</td></tr>";
        
        $table .="</table></div>";
        $custom = array('result'=>$table,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit;
    }
}
catch (Exception $err)
{
    $custom = array('result'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>