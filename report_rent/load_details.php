<?php    
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');        
    $invno=0;
    $action=$_GET['action'];
    if($action == "invno")
    {
        $companymasid = $_GET['companymasid'];
        $sql="select invendno+1 as 'invno' from invoice_no where companymasid =".$companymasid;
        $result = mysql_query($sql);
        if($result !=null)
        {
            $row = mysql_fetch_assoc($result);
            $invno=$row['invno'];
        }
        
        ////$custom = array('result'=>$invno,'s'=>'Success');
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
            $custom = array('result'=>$invno,'s'=>"Success"); 
            $response_array [] = $custom;
            echo '{
                "myResult":'.json_encode($arr).',
                "error":'.json_encode($response_array).
            '}';				
        }
        else
        {
            $custom = array('result'=>mysql_error(),'s'=>$sql);
            $response_array [] = $custom;
            echo '{
                "error":'.json_encode($response_array).
            '}';
        }
    }
    else if($action == "debitnoteno")
    {
        $companymasid = $_GET['companymasid'];
        $sql="select invdrendno+1 as 'invno' from invoice_no_dr where companymasid =".$companymasid;
        $result = mysql_query($sql);
        if($result !=null)
        {
            $row = mysql_fetch_assoc($result);
            $invno_debitnote=$row['invno'];
        }
        
        ////$custom = array('result'=>$invno_debitnote,'s'=>'Success');
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
            $custom = array('result'=>$invno_debitnote,'s'=>"Success"); 
            $response_array [] = $custom;
            echo '{
                "myResult":'.json_encode($arr).',
                "error":'.json_encode($response_array).
            '}';				
        }
    }
    else if($action == "tenantdetails")
    {
        $grouptenantmasid = $_GET['grouptenantmasid'];
        $tenancyrefcode = gettenancyrefcode($grouptenantmasid);
        $tenantaddress="";$buildingaddress="";$buildingmasid="";$companymasid="";
        $shop="";$remarks="";
        //$sql = "select @a:=@a+1 sno, 
        //        case tradingname 
        //                when tradingname ='' then concat(a.leasename ,' (T/A) ',a.tradingname)
        //                when tradingname <>'' then concat(a.leasename)  
        //        end as tenant,
        //        a.poboxno,a.pincode,a.city,a.buildingmasid,a.companymasid,
        //        b.buildingname,c.shopcode from mas_tenant a
        //        inner join mas_building b on b.buildingmasid = a.buildingmasid
        //        inner join mas_shop c on c.shopmasid = a.shopmasid
        //        inner join mas_shoptype d on d.shoptypemasid =  a.shoptypemasid
        //        ,(select @a:= 0) AS a where a.active='1' and a.tenantmasid= '$tenantmasid' order by leasename";
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
                $tenantaddress .= "Tenancy Refcode: ".$tenancyrefcode.",";
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
    else if($action == "vat")
    {
        $invoicedescmasid= $_GET['invoicedescmasid'];
        $vat=0;
        $sql = "select vat from invoice_desc where invoicedescmasid=$invoicedescmasid;";
        $result = mysql_query($sql);
        if($result != null)
        {
            $row=mysql_fetch_assoc($result);
            $vat = $row['vat'];
            if($vat =="")
            $vat=0;
        }
        $custom = array('vat'=>$vat,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
    }
    
?>