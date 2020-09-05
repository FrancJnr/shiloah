<?php
include('../config.php');
session_start();
try{

    $companymasid = $_SESSION['mycompanymasid'];
    $buildingmasid = $_GET['buildingmasid'];
    $buildingname = "";
    
    $s = "select buildingname from mas_building where buildingmasid =$buildingmasid";
    $r = mysql_query($s);
     while($ro = mysql_fetch_assoc($r))
        {
            $buildingname = strtoupper($ro["buildingname"]);
        }
    $table ="<p class='printable'><table class='table6'><thead>";
    $table .="<tr align='center'>";
    $table .="<th><strong>Index</th>";
    $table .="<th><strong>Tenant</th>";
    $table .="<th><strong>Building</th>";    
    $table .="<th><strong>Commncement</th>";
    $table .="<th><strong>Expiry</th>";
    $table .="<th><strong>Lease Period</th>";
    $table .="<th><strong>Outstanding Amt</th>";
    $table .="<th><strong>Shop</th>";
    $table .="<th><strong>Is Shop Vacated</th></tr></thead>";
    $tr =  "<tr><td colspan='9'> NO LEGAL CASES </td></tr>";
    if($buildingmasid !=0)
    {
        $sql = "select a.aclegal, b.oplegal,d.leasename,d.tradingname,a.outstandingpayment,a.baltilldt,a.grouptenantmasid,
                DATE_FORMAT(d.doc, '%d-%m-%Y' ) AS doc,g.buildingname,h.shopcode,
                e.cpname,e.cpmobile,cplandline,cpemailid,f.age,
                @t2:= DATE_ADD(d.doc,interval @t1:=f.age year) as ag,									
                DATE_FORMAT( DATE_ADD(d.doc,interval @t1:=f.age year), '%d-%m-%Y' ) AS bg,		   
                DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,a.acapproval,b.opapproval,c.tenantmasid
                from trans_tenant_discharge_ac a
                inner join trans_tenant_discharge_op b on b.grouptenantmasid = a.grouptenantmasid
                inner join group_tenant_det c on c.grouptenantmasid = b.grouptenantmasid
                inner join mas_tenant d on d.tenantmasid = c.tenantmasid
                inner join mas_tenant_cp e on e.tenantmasid = d.tenantmasid
                inner join mas_age f on f.agemasid = d.agemasidlt
                inner join mas_building g on g.buildingmasid = d.buildingmasid
                inner join mas_shop h on h.shopmasid = d.shopmasid
                where d.buildingmasid = $buildingmasid and a.aclegal ='1' and b.oplegal='1' and e.documentname ='1' order by d.buildingmasid;";
        }else{
        $sql = "select a.aclegal, b.oplegal,d.leasename,d.tradingname,a.outstandingpayment,a.baltilldt,a.grouptenantmasid,
                DATE_FORMAT(d.doc, '%d-%m-%Y' ) AS doc,g.buildingname,h.shopcode,
                e.cpname,e.cpmobile,cplandline,cpemailid,f.age,
                @t2:= DATE_ADD(d.doc,interval @t1:=f.age year) as ag,									
                DATE_FORMAT( DATE_ADD(d.doc,interval @t1:=f.age year), '%d-%m-%Y' ) AS bg,		   
                DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,a.acapproval,b.opapproval,c.tenantmasid
                from trans_tenant_discharge_ac a
                inner join trans_tenant_discharge_op b on b.grouptenantmasid = a.grouptenantmasid
                inner join group_tenant_det c on c.grouptenantmasid = b.grouptenantmasid
                inner join mas_tenant d on d.tenantmasid = c.tenantmasid
                inner join mas_tenant_cp e on e.tenantmasid = d.tenantmasid
                inner join mas_age f on f.agemasid = d.agemasidlt
                inner join mas_building g on g.buildingmasid = d.buildingmasid
                inner join mas_shop h on h.shopmasid = d.shopmasid
                where a.aclegal ='1' and b.oplegal='1' and e.documentname ='1' order by d.buildingmasid;";
        }
        $result=mysql_query($sql);
        if($result != null) // if $result <> false
        {
            if (mysql_num_rows($result) > 0)
            {
                $i=1;$tr="";
                while ($row = mysql_fetch_assoc($result))
                {									
                    if($row['tradingname'] !="")
                    $row['leasename'] .= " (T/A) ".$row['tradingname'];
                    
                    $bal = $row['baltilldt'];
                    
                    $tr .=  "<tr>
                    <td align='center'>".$i++."</td>
                    <td>".$row['leasename']."</td>
                    <td>".$row['buildingname']."</td>                    
                    <td>".$row['doc']."</td>
                    <td>".$row['expdt']."</td>
                    <td>".$row['age']."</td>";
                    if($bal !="")
                    $tr .=  "<td align='right'>".number_format($bal, 0, '.', ',')."</td>";
                    else
                    $tr .=  "<td align='right'>$bal</td>";
                    $tr .=  "<td>".$row['shopcode']."</td>";
                    if($row['acapproval']=='1' and $row['opapproval']=='1')
                        $tr .="<td style='color:green;font-face:bold;'> Yes </td>";
                    else
                        $tr .="<td style='color:red;font-face:bold;'> No </td>";
                    
             }
            }
        }
    $table .=$tr;
    $table .= "</table></p>";
    if($buildingmasid !=0)
    $custom = array('divContent'=> $table,'heading'=>"Legal Cases for ".$buildingname,'s'=>'Success');
    else
    $custom = array('divContent'=> $table,'heading'=>"Legal Cases for All Buildings",'s'=>'Success');
    
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