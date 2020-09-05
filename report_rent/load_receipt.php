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
    if($action == "invno_receipt")
    {
        $companymasid = $_GET['companymasid'];
        $sql="select invrctno+1 as 'invno' from invoice_no_rct where companymasid =".$companymasid;
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
        $sql = "select invoicemanmasid,invoiceno,toaddress,premise,remarks from invoice_man_mas where companymasid =".$companymasid."
                and grouptenantmasid =0 order by invoiceno DESC;";        
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
        $sql = "select invoiceno,grouptenantmasid from invoice_man_mas where invoicemanmasid = '$invoicemanmasid'";
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
	else if($action == "getrcptdetails")
    {
        $tenantrecpt = $_GET['tenantrecpt'];
        
        $totalamount="";$chqnum="";
        $sql="select * FROM `invoice_rct_mas` WHERE rctno = '$tenantrecpt' ";
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            while ($row = mysql_fetch_assoc($result))
            {
                $totalamount = $row['totalamount'];
                $chqnum = $row['chqnum'];
            }
        }
        $custom = array('recpamount'=>$totalamount ,
                        'chqnum'=>$chqnum,

                        's'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';   
    }
    else if($action == "getselectinvdetails"){
           $descriptionacct = $_GET['descriptionacct'];
           $invcenum = $_GET['invcenum'];
		$building = $_GET['building'];
		$grouptenant = $_GET['grouptenant'];
        //$sql = "SELECT totalamount from invoice_man_mas LEFT JOIN invoice_man_det ON invoice_man_det.invoicemanmasid = invoice_man_mas.invoicemanmasid  WHERE invoice_man_det.invoicedescmasid = '$descriptionacct' AND invoice_man_mas.buildingmasid = '$building' AND invoice_man_det.invoicemanmasid = '$grouptenant'";
        if($building =='0'){
        $sql = "SELECT totalamount,sum(invoice_rct_det.total) as paid from invoice_man_mas 
		LEFT JOIN invoice_man_det ON invoice_man_det.invoicemanmasid = invoice_man_mas.invoicemanmasid 
		LEFT JOIN invoice_rct_det ON invoice_rct_det.invoiceno = invoice_man_mas.invoiceno 
		WHERE invoice_man_mas.invoiceno ='$invcenum' AND 
		invoice_man_det.invoicedescmasid = '$descriptionacct' AND 
		invoice_man_det.invoicemanmasid = '$grouptenant'";
        }else{
         // $sql ="SELECT totalamount,sum(invoice_rct_det.total) as paid from invoice_man_mas LEFT JOIN invoice_man_det ON invoice_man_det.invoicemanmasid = invoice_man_mas.invoicemanmasid LEFT JOIN invoice_rct_det ON invoice_rct_det.invoiceno = invoice_man_mas.invoiceno WHERE invoice_man_mas.invoiceno ='$invcenum' AND invoice_man_det.invoicedescmasid = '$descriptionacct' AND invoice_man_mas.buildingmasid = '$building'  AND invoice_man_mas.grouptenantmasid   = '$grouptenant'";
        $sql = "SELECT invoicedescmasid,sum(totalamount) as totalamount,sum(paid) as paid 
		FROM (SELECT invoice_man_det.invoicedescmasid,totalamount,sum(invoice_rct_det.total) as paid 
		from invoice_man_mas LEFT JOIN invoice_man_det ON invoice_man_det.invoicemanmasid = invoice_man_mas.invoicemanmasid
		LEFT JOIN invoice_rct_det ON invoice_rct_det.invoiceno = invoice_man_mas.invoiceno 
		WHERE invoice_man_mas.invoiceno ='$invcenum' AND invoice_man_mas.buildingmasid = '$building'  
		AND invoice_man_mas.grouptenantmasid   = '$grouptenant'
		UNION SELECT '32' as invoicedescmasid,(rent+sc) as amount,sum(invoice_rct_det.total)  from 
		advance_rent LEFT JOIN invoice_rct_det ON advance_rent.invoiceno = invoice_rct_det.invoiceno 
		WHERE grouptenantmasid = '$grouptenant' AND advance_rent.invoiceno = '$invcenum'
		union SELECT '32' as invoicedescmasid,(rent+sc),sum(invoice_rct_det.total) from invoice 
		LEFT JOIN invoice_rct_det ON invoice.invoiceno = invoice_rct_det.invoiceno 
		WHERE  grouptenantmasid = '$grouptenant' AND invoice.invoiceno = '$invcenum') t7 
		WHERE invoicedescmasid = '$descriptionacct'";
		}
		//die($sql);
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            $arraydata=array();
      while($row=  mysql_fetch_assoc($result)){
         
         $arraydata[]=$row;
     }
      
     echo json_encode($arraydata);            
        }
    }
	    else if($action =="pendingpostiingintally"){
        $sql = "SELECT a.invoicerctmasid as sno, d.leasename as debitledger, 
             d.tradingname as alias, a.rctdate, a.rctno, b.invoiceno, 
             a.totalamount,b.balance, a.is_java as intally FROM invoice_rct_mas a 
         INNER JOIN invoice_rct_det b ON a.invoicerctmasid = b.invoicerctmasid
         LEFT JOIN group_tenant_mas c ON c.grouptenantmasid = a.grouptenantmasid
         INNER JOIN mas_tenant d ON d.tenantmasid = c.tenantmasid WHERE a.is_java = '0'";
        $result =  mysql_query($sql);    
        if($result != null) 
        {
        $arraydata=array();
     $cnt = mysql_num_rows($result);
     
          echo json_encode(array('result'=>$cnt));            
        }
    }
	//changes here
  else if($action == "getaccountinvoicenum")
    {
        $descriptionacct = $_GET['descriptionacct'];
		$building = $_GET['building'];
		$grouptenant = $_GET['grouptenant'];
        $sql = "SELECT invoiceno, totalamount from invoice_man_mas 
		LEFT JOIN invoice_man_det ON invoice_man_det.invoicemanmasid = invoice_man_mas.invoicemanmasid
		WHERE invoice_man_det.invoicedescmasid = '$descriptionacct' AND 
		invoice_man_det.invoicemanmasid = '$grouptenant'";
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            $arraydata=array();
      while($row=  mysql_fetch_assoc($result)){
         
         $arraydata[]=$row;
     }
      
     echo json_encode($arraydata);            
        }
    }
    	    else if($action == "getaccounttenantinvoicenum")
    {
        $descriptionacct = $_GET['descriptionacct'];
		$building = $_GET['building'];
		$grouptenant = $_GET['grouptenant'];
		//$sql1 = mysql_query("Select grouptenantmasid FROM group_tenant_det WHERE tenantmasid = '$grouptenant'");
		// while($row1=  mysql_fetch_assoc($sql1)){
		//	 $grouptenant = $row1['grouptenantmasid'];
			
		// }
        $sql = "SELECT * FROM (SELECT invoicedesc,invoice_desc.invoicedescmasid,invoiceno from invoice_man_mas, invoice_desc , 
		invoice_man_det where invoice_man_mas.invoicemanmasid = invoice_man_det.invoicemanmasid AND 
		invoice_man_det.invoicedescmasid = invoice_desc.invoicedescmasid  AND invoice_man_mas.grouptenantmasid = '$grouptenant' 
		UNION SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid,invoiceno  from advance_rent where grouptenantmasid = '$grouptenant'
                union SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid,invoiceno from invoice where grouptenantmasid = '$grouptenant') 
				AS t7 WHERE  invoicedescmasid='$descriptionacct' ";
				// die($sql);
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            $arraydata=array();
      while($row=  mysql_fetch_assoc($result)){
         
         $arraydata[]=$row;
     }
      
     echo json_encode($arraydata);            
        }
    }
	else if($action == "getaccounttenanteditinvoicenum")
    {
        $descriptionacct = $_GET['descriptionacct'];
		$building = $_GET['building'];
		$grouptenant = $_GET['grouptenant'];
		$sql1 = mysql_query("Select grouptenantmasid FROM group_tenant_det WHERE tenantmasid = '$grouptenant'");
		 while($row1=  mysql_fetch_assoc($sql1)){
			 $grouptenant = $row1['grouptenantmasid'];
			
		 }
        $sql = "SELECT * FROM (SELECT invoicedesc,invoice_desc.invoicedescmasid,invoiceno from invoice_man_mas, invoice_desc , 
		invoice_man_det where invoice_man_mas.invoicemanmasid = invoice_man_det.invoicemanmasid AND 
		invoice_man_det.invoicedescmasid = invoice_desc.invoicedescmasid  AND invoice_man_mas.grouptenantmasid = '$grouptenant' 
		UNION SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid,invoiceno  from advance_rent where grouptenantmasid = '$grouptenant'
                union SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid,invoiceno from invoice where grouptenantmasid = '$grouptenant') 
				AS t7 WHERE  invoicedescmasid='$descriptionacct' ";
				// die($sql);
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            $arraydata=array();
      while($row=  mysql_fetch_assoc($result)){
         
         $arraydata[]=$row;
     }
      
     echo json_encode($arraydata);            
        }
    }
	
	else if($action == "getinvcedescriptonother")
    {
        $tenantmasid = $_GET['grouptenantmasid'];
	
        $sql = "SELECT invoicedesc,invoice_desc.invoicedescmasid from invoice_man_mas, invoice_desc , invoice_man_det where invoice_man_mas.invoicemanmasid = invoice_man_det.invoicemanmasid AND invoice_man_det.invoicedescmasid = invoice_desc.invoicedescmasid  AND invoice_man_mas.invoicemanmasid = '$tenantmasid' GROUP BY invoice_desc.invoicedescmasid";
        $result =  mysql_query($sql);    
        if($result != null) 
        {
            $arraydata=array();
      while($row=  mysql_fetch_assoc($result)){
         
         $arraydata[]=$row;
     }
      
     echo json_encode($arraydata);            
        }
    }
		        else if($action == "getinvcedescriptontenant")
    {
        $tenantmasid = $_GET['grouptenantmasid'];
	
        $sql = "SELECT * FROM (SELECT invoicedesc,invoice_desc.invoicedescmasid from invoice_man_mas, invoice_desc , invoice_man_det where invoice_man_mas.invoicemanmasid = invoice_man_det.invoicemanmasid AND invoice_man_det.invoicedescmasid = invoice_desc.invoicedescmasid  AND invoice_man_mas.grouptenantmasid = '$tenantmasid' GROUP BY invoice_desc.invoicedescmasid
                UNION SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid from advance_rent where grouptenantmasid = '$tenantmasid'
                union SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid from invoice where grouptenantmasid = '$tenantmasid') AS t7 GROUP BY invoicedescmasid ";

        $result =  mysql_query($sql); 
		if($result != null) 
        {
            $arraydata=array();
      while($row=  mysql_fetch_assoc($result)){
         
         $arraydata[]=$row;
     }
      
     echo json_encode($arraydata);            
        }
    }

			   else if($action == "getinvcedescripton")
    {
        $tenantmasid = $_GET['grouptenantmasid'];
	
         $sql = "SELECT * FROM (SELECT invoicedesc,invoice_desc.invoicedescmasid from invoice_man_mas, invoice_desc , invoice_man_det where invoice_man_mas.invoicemanmasid = invoice_man_det.invoicemanmasid AND invoice_man_det.invoicedescmasid = invoice_desc.invoicedescmasid  AND invoice_man_mas.grouptenantmasid = '$tenantmasid' GROUP BY invoice_desc.invoicedescmasid
                UNION SELECT 'Rent' as invoicedesc, '32' as invoicedescmasid FROM advance_rent WHERE advance_rent.rent > 0 AND grouptenantmasid = '$tenantmasid'
				UNION SELECT 'Actual Service Charge','26' as invoicedescmasid FROM advance_rent WHERE advance_rent.sc > 0 AND grouptenantmasid = '$tenantmasid' 
                UNION SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid from invoice WHERE grouptenantmasid = '$tenantmasid'
				union SELECT 'Rent' as invoicedesc ,'32' as invoicedescmasid from invoice_man_mas where grouptenantmasid = '$tenantmasid') AS t7 GROUP BY invoicedescmasid";

        $result =  mysql_query($sql);    
        if($result != null) 
        {
            $arraydata=array();
      while($row=  mysql_fetch_assoc($result)){
         
         $arraydata[]=$row;
     }
      
     echo json_encode($arraydata);            
        }
    }
    else if($action == "loandinvoiceno")
    {
        $grouptenantmasid = $_GET['grouptenantmasid'];
        $sql = "select invoiceno,grouptenantmasid from invoice where grouptenantmasid = '$grouptenantmasid'
                union
                select invoiceno,grouptenantmasid from advance_rent where grouptenantmasid = '$grouptenantmasid'
                union
                select invoiceno,grouptenantmasid from invoice_man_mas where grouptenantmasid = '$grouptenantmasid'";
			//	die($sql);
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
	else if ($action == "tenantdetailstoedit"){
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
            from mas_tenant b
                        
                        inner join mas_shop c on  c.shopmasid = b.shopmasid
                                    inner join mas_building d on d.buildingmasid = c.buildingmasid
                        where b.tenantmasid = '$grouptenantmasid'
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
                     <th>Value</th><th>Vat</th><th>Total</th><th>Balance</th><th>Remarks</th></tr>";        
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
					$sqlbalance = mysql_query("SELECT SUM(total) as paid FROM `invoice_rct_det` WHERE invoiceno = '".$row['invoiceno']."' ");
                    while($row12 = mysql_fetch_assoc($sqlbalance))
                {
                        $paid = $row12['paid'];
                }
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
						$table .="<td>".number_format($row['amount']-$paid)."</td>";
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