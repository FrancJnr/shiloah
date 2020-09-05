<?php    
    include('../config.php');
    session_start();
	$companymasid = $_SESSION['mycompanymasid'];
try{
    $buildingmasid=0;$buildingname="";    
    $fromdt = explode("-",$_GET['fromdt']);
    $d1  = date("M", strtotime($_GET['fromdt']));
    $fromdt = $fromdt[2]."-".$fromdt[1]."-".$fromdt[0];    
        
    $todt = explode("-",$_GET['todt']);
    $d2  = date("M Y", strtotime($_GET['todt']));    
    $todt = $todt[2]."-".$todt[1]."-".$todt[0];
    
    $period = $d1 ." to ". $d2;
    
    //$fromdt="2015-01-01";
    //$todt="2015-12-31";    
    
    $table="<p class='printable'><table class='table6' style='width:100%;'>";
    $table .="<tr><td style='width:100%;color:red;font-weight: bold;' colspan='12'>Tenancy Expiry List : $period</td></tr>";       
    $sql ="select * from mas_building where companymasid=".$companymasid;
    $result =mysql_query($sql);    
    if($result)
    {
        $chk=false;
        while($row =mysql_fetch_assoc($result))
        {
            $i=1;
            $buildingmasid = $row["buildingmasid"];
            $buildingname = $row["buildingname"];
            
            for ($k=0;$k<=2;$k++)
            {
                $i=1;
                $shoptype="";
                if($k==0)
                {
                    $shoptype=" a.shoptypemasid not in (13,19) ";    
                }
                else
                {
                    $shoptype=" a.shoptypemasid in (13,19) ";    
                }
                    $sql1= "select tenantmasid,leasename,tradingname,shopcode,size,date_format(doc,'%d-%m-%Y') as doc,age,expdt,rent,rtsqrft,sc,shoptype
                    from (
                            select a.tenantmasid,a.leasename,a.tradingname,shopcode,size,doc,age,f.amount as rent,round(f.amount/size) as rtsqrft,f1.amount as sc,g.shoptype,
                            date_format(DATE_ADD(DATE_ADD(a.doc, INTERVAL age YEAR),interval -1 day),'%d-%m-%Y') as Expdt from mas_tenant a 
                            inner join mas_shoptype b on b.shoptypemasid = a.shoptypemasid
                            inner join mas_shop c on c.shopmasid = a.shopmasid
                            inner join mas_age d on d.agemasid = a.agemasidlt
                            inner join trans_offerletter e on e.tenantmasid =a.tenantmasid
                            inner join trans_offerletter_rent f on f.offerlettermasid = e.offerlettermasid							
                            inner join trans_offerletter_sc f1 on f1.offerlettermasid = e.offerlettermasid
                            inner join mas_shoptype g on g.shoptypemasid =a.shoptypemasid
                            where a.active='1' and a.shopoccupied='1' and a.buildingmasid='$buildingmasid' and a.renewal='0' and $shoptype
                            and DATE_ADD(DATE_ADD(a.doc, INTERVAL age YEAR),interval -1 day)  between	f.fromdate  and f.todate
                            and DATE_ADD(DATE_ADD(a.doc, INTERVAL age YEAR),interval -1 day)  between	f1.fromdate  and f1.todate
                            group by e.offerlettermasid
                            union
                            select a.tenantmasid,a.leasename,a.tradingname,shopcode,size,doc,age,f.amount as rent,round(f.amount/size) as rtsqrft,f1.amount as sc,g.shoptype,
                            date_format(DATE_ADD(DATE_ADD(a.doc, INTERVAL age YEAR),interval -1 day),'%d-%m-%Y') as Expdt from rec_tenant a 
                            inner join mas_shoptype b on b.shoptypemasid = a.shoptypemasid
                            inner join mas_shop c on c.shopmasid = a.shopmasid
                            inner join mas_age d on d.agemasid = a.agemasidlt
                            inner join trans_offerletter e on e.tenantmasid =a.tenantmasid
                            inner join trans_offerletter_rent f on f.offerlettermasid = e.offerlettermasid
                            inner join trans_offerletter_sc f1 on f1.offerlettermasid = e.offerlettermasid
                            inner join mas_shoptype g on g.shoptypemasid =a.shoptypemasid
                            where a.active='1' and a.shopoccupied='1' and a.buildingmasid='$buildingmasid' and a.renewal='0' and $shoptype
                            and DATE_ADD(DATE_ADD(a.doc, INTERVAL age YEAR),interval -1 day)  between	f.fromdate  and f.todate
                            and DATE_ADD(DATE_ADD(a.doc, INTERVAL age YEAR),interval -1 day)  between	f1.fromdate  and f1.todate
                            group by e.offerlettermasid
                        )
                    as t
                    where date_format(DATE_ADD(DATE_ADD(doc, INTERVAL age YEAR),interval -1 day),'%Y-%m-%d')  between '$fromdt' and '$todt'
                    order by date_format(DATE_ADD(DATE_ADD(doc, INTERVAL age YEAR),interval -1 day),'%Y-%m-%d');";
                    
                    $result1 =mysql_query($sql1);
                    $boo=false;
                    if($result1)
                    {
                        $rcount = mysql_num_rows($result1);
                        if($rcount>0)
                        {
                            $boo=true;$chk=true;$rent=0;$sc=0;
                            if($k==0)
                            {
                             $table .="<tr><th colspan='12' style='width:100%;'>$buildingname - SHOP DETAILS</th></tr>";   
                            }
                            else
                            {
                                $table .="<tr><th colspan='12' style='width:100%;'>$buildingname - KIOSKS AND TROLLEYS</th></tr>";   
                            }
                            $table .="<tr><th style='width:2%;'>S.No</th><th>Leasename</th><th>Type</th><th>Shop Code</th>
                                <th style='width:5%;'>Size</th><th>DOC</th><th>Term</th><th style='width:10%;'>Last.Rent</th><th>Rt/Sqrft</th><th>Sc</th><th>Expiry</th></tr>";
                            while($row1 =mysql_fetch_assoc($result1))
                            {
                               $table .="<tr>";
                                    $table .="<tr>";
                                        $table .="<td>".$i."</td>";
                                        $table .="<td>".$row1["leasename"]."</td>";
                                        $table .="<td>".$row1["shoptype"]."</td>";
                                        $table .="<td>".$row1["shopcode"]."</td>";
                                        $table .="<td>".$row1["size"]."</td>";
                                        $table .="<td>".$row1["doc"]."</td>";
                                        $table .="<td>".$row1["age"]."</td>";                                
                                        $table .="<td>".number_format($row1["rent"], 0, '.', ',')."</td>";
                                        $table .="<td>".$row1["rtsqrft"]."</td>";
                                        $table .="<td>".$row1["sc"]."</td>";
                                        $table .="<td style='color:red;'>".$row1["expdt"]."</td>";
                                        $rent +=$row1["rent"];
                                        $sc +=$row1["sc"];
                                $table .="</tr>";
                               $table .="</tr>";
                               $i++;                   
                            }
                            $table .="<tr><td colspan='7'></td><td>".number_format($rent, 0, '.', ',')."</td>
                                            <td>-</td>
                                            <td>".number_format($sc, 0, '.', ',')."</td><td>-</td></tr>";            
                        }
                    }
                    //if($boo==true)
                    //$table .="<tr><th colspan='8'></th></tr>";
                    $k++;
            }
        }
        if($chk==false)
        $table .="<tr><th colspan='8'>No expiry list found for the period.</th></tr>";            
    }
    $table .="</table></p>";
    $custom = array('divContent'=>$table,'s'=>'Success');
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