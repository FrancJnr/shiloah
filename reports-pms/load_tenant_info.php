<?php
include('../config.php');
session_start();
try{
    $companymasid = $_SESSION['mycompanymasid'];
    $buildingmasid = $_GET['buildingmasid'];                
    $fields="";
    foreach($_GET as $k=>$v)
    {
        $column = strstr($k, '_', true);
        if($column == "column1")
        {
            $fields .=$v.",";
        }
    }
    $buildingname="";
    $sql = "select buildingname from mas_building where buildingmasid = $buildingmasid";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $buildingname = $row["buildingname"];
    
    if($fields =="")
    {        
        $table="<p class='printable'><table class='table6'><tr><td colspan='30' style='font-weight:bold;'>TENANCY INFO - ".strtoupper($buildingname)."</td></tr>";        
        $table.="<tr>";
            $table.="<th align='center'>Sno</th>";        
            $table.="<th>Tenant</th>";
            $table.="<th>Address</th>";
            $table.="<th>Code</th>";
            $table.="<th>Pin</th>";
            $table.="<th>Shop</th>";
            $table.="<th>Sqrft</th>";
            $table.="<th>Cycle</th>";
            $table.="<th>Doc</th>";        
        $table.="</tr>";    
        
        $sql ="select b.leasename,b.tradingname,b.pin,c.tenancyrefcode,d.shopcode,d.size,e.age,date_format(b.doc,'%d-%m-%Y') as doc,renewalfromid,
                b.address1,b.city,b.state,b.pincode,b.country,b.poboxno,b.telephone1,b.telephone2,b.fax,b.emailid,b.website    
                from group_tenant_det a
                inner join mas_tenant b on b.tenantmasid = a.tenantmasid
                inner join mas_tenancyrefcode c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid =b.shopmasid
                inner join mas_age e on e.agemasid = b.agemasidrc
                where b.active='1' and shopoccupied='1' and b.buildingmasid ='$buildingmasid'
                union 
                select b.leasename,b.tradingname,b.pin,c.tenancyrefcode,d.shopcode,d.size,e.age,date_format(b.doc,'%d-%m-%Y') as doc,renewalfromid,
                b.address1,b.city,b.state,b.pincode,b.country,b.poboxno,b.telephone1,b.telephone2,b.fax,b.emailid,b.website    
                from group_tenant_det a
                inner join rec_tenant b on b.tenantmasid = a.tenantmasid
                inner join mas_tenancyrefcode c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid =b.shopmasid
                inner join mas_age e on e.agemasid = b.agemasidrc
                where b.active='1' and shopoccupied='1' and b.buildingmasid ='$buildingmasid' order by leasename;";    
        $result = mysql_query($sql);
        if($result != null)
        {
            $i=0;
                while ($row = mysql_fetch_assoc($result))
                {
                    $i++;
                    $table.="<tr>";
                        $table.="<td align='center'>$i</td>";
                        if (($row['tradingname'])!="")
                        $row['leasename'] .=" T/A ".$row['tradingname'];
                        
                        if($row['renewalfromid'] >0)
                        $row['leasename'] .=" (Renewed)";
                        
                        $table.="<td>".$row['leasename']."</td>";
                        
                         $table.="<td>".
                                        $row['address1'].",<br>".
                                        "PO Box.No -".$row['poboxno'].",</br>".
                                        $row['city']."-".$row['pincode'].",</br>PH: ".
                                        $row['telephone1']."-".$row['telephone2'].",</br>EMAIL: ".
                                        $row['emailid']
                                        ."</td>";
                        if($row['tenancyrefcode']!="")
                            $table.="<td>".$row['tenancyrefcode']."</td>";
                        else
                            //$table.="<td style='background-color:red'>".$row['tenantmasid']."</td>";
                            $table.="<td>--</td>";                   
                        $table.="<td>".$row['pin']."</td>";
                        $table.="<td>".$row['shopcode']."</td>";
                        $table.="<td>".$row['size']."</td>";
                        $table.="<td>".$row['age']."</td>";
                        $table.="<td>".$row['doc']."</td>";                    
                    $table.="</tr>";
                }        
        }
        $table.="</table></p>";
        $custom = array('divContent'=> "<br>".$table,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
    }
    else // fields
    {       
       
        $column = rtrim($fields,',');
        $table="<p class='printable'><table class='table6'><tr><thead><td colspan='30' style='font-weight:bold;'>TENANCY INFO - ".strtoupper($buildingname)."</td></thead></tr>";        	
        $table.="<tr><thead>";
        $table.="<th align='center'>Sno</th>";
        $sqlExec = explode(",",$column);
	for($i=0;$i<count($sqlExec);$i++)
	{
	    if($sqlExec[$i] != "")
            {
                $table.="<th>".$sqlExec[$i]."</th>";    
	    }
	}
        $table.="</thead></tr>";
        $sql="select case (tradingname)
                when tradingname ='' then concat(salutation,' ',leasename,' (T/A) ',tradingname)
                when tradingname !='' then concat(salutation,' ',leasename)end as leasename,
                shopcode,size as sqrft,date_format(doc,'%d-%m-%Y') as doc,date_format(doo,'%d-%m-%Y') as doo,                
                shoptype,orgtype,nob,d.age as leaseperiod,e.age as rentcycle,d.description,f.age as creditperiod,
                case d.description
                    when 'months' then date_format(DATE_ADD(a.doc, INTERVAL d.age MONTH),'%d-%m-%Y')
                    when 'years' then date_format(DATE_ADD(a.doc, INTERVAL d.age YEAR),'%d-%m-%Y') 
                end as doe,concat(cpname,', ',cpmobile,', ',cplandline) as contactpersondetails,i.tenancyrefcode,
                creditlimit,latefeeinterest,pin,regno, concat(address1,' , ',address2,' , ',city,',',pincode) as address,concat(telephone1,' , ',telephone2) as phoneno 
                from mas_tenant a
                inner join mas_shop b on b.shopmasid = a.shopmasid
                inner join mas_orgtype c on c.orgtypemasid =a.orgtypemasid
                inner join mas_age d on d.agemasid = a.agemasidlt
                inner join mas_age e on e.agemasid = a.agemasidrc
                inner join mas_age f on f.agemasid = a.agemasidcp
                inner join mas_shoptype g on g.shoptypemasid = a.shoptypemasid
                inner join mas_tenant_cp h on h.tenantmasid = a.tenantmasid
                inner join mas_tenancyrefcode i on i.tenantmasid =  a.tenantmasid
                where (a.active='1' and a.shopoccupied='1') and a.buildingmasid=$buildingmasid and h.documentname='1'
                union
                select case (tradingname)
                when tradingname ='' then concat(salutation,' ',leasename,' (T/A) ',tradingname)
                when tradingname !='' then concat(salutation,' ',leasename)end as leasename,
                shopcode,size as sqrft,date_format(doc,'%d-%m-%Y') as doc,date_format(doo,'%d-%m-%Y') as doo,                
                shoptype,orgtype,nob,d.age as leaseperiod,e.age as rentcycle,d.description,f.age as creditperiod,
                case d.description
                    when 'months' then date_format(DATE_ADD(a.doc, INTERVAL d.age MONTH),'%d-%m-%Y')
                    when 'years' then date_format(DATE_ADD(a.doc, INTERVAL d.age YEAR),'%d-%m-%Y') 
                end as doe,concat(cpname,', ',cpmobile,', ',cplandline) as contactpersondetails,i.tenancyrefcode,
                creditlimit,latefeeinterest,pin,regno, concat(address1,' , ',address2,' , ',city,',',pincode) as address,concat(telephone1,' , ',telephone2) as phoneno 
                from rec_tenant a
                inner join mas_shop b on b.shopmasid = a.shopmasid
                inner join mas_orgtype c on c.orgtypemasid =a.orgtypemasid
                inner join mas_age d on d.agemasid = a.agemasidlt
                inner join mas_age e on e.agemasid = a.agemasidrc
                inner join mas_age f on f.agemasid = a.agemasidcp
                inner join mas_shoptype g on g.shoptypemasid = a.shoptypemasid
                inner join mas_tenant_cp h on h.tenantmasid = a.tenantmasid
                inner join mas_tenancyrefcode i on i.tenantmasid =  a.tenantmasid
                where (a.active='1' and a.shopoccupied='1') and a.buildingmasid=$buildingmasid and h.documentname='1';";         
        $result = mysql_query($sql);
        if($result)
        {
            $j=0;
            while ($row = mysql_fetch_assoc($result))
            {
                $j++;
                $table.="<tr><tbody>";
                    $table.="<td>".$j."</td>";
                    for($i=0;$i<count($sqlExec);$i++)
                    {
                        if($sqlExec[$i] != "")
                        {
                            $table.="<td>".ltrim($row[$sqlExec[$i]],',')."</d>";    
                        }
                    }
                $table.="</tbody></tr>";
            }
        }
        $table.="</table></p>";
        $custom = array('divContent'=> "<br>".$table,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';       
    }
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