<?php    
    include('../config.php');
    session_start();
     $companymasid = $_SESSION['mycompanymasid'];
	
    $dy = "-2";//report period     
    $firstdate = date("d-m-Y");
    $table= "<u>Invoice Status</u><br>Period: ".date("d-m-Y", strtotime(date("Y-m-d", strtotime($firstdate)) . "$dy Day"))."<br><br>";
    $sqlcomp = "select companymasid,companyname from mas_company where companymasid=".$companymasid;
	//$sqlcomp = "select companymasid,companyname from mas_company where companymasid=";
    $resultcomp = mysql_query($sqlcomp);
    if($resultcomp != null)
    {        
        while($rowcomp = mysql_fetch_assoc($resultcomp))
        {
            $companymasid= $rowcomp['companymasid'];
            $companyname = $rowcomp['companyname'];
            $sql = "select buildingmasid, buildingname from mas_building where companymasid = $companymasid";            
            $result = mysql_query($sql);
            if($result != null)
            {        
                $rent=0;$rentvat=0;$sc=0;$scvat=0;$total=0;
                $table .= "<b>".$companyname.":</b><br>";
                $table .="<table border='1' width='80%'><tr><th width='20%'></th>
                        <th width='10%'>Rent</th><th width='10%'>Vat</th><th width='8%'>Sc</th><th width='8%'>Vat</th>
                        <th width='10%'>KPLC</th><th width='10%'>Water</th><th width='10%'>Legal</th><th width='10%'>Stamp Duty</th>
                        <th width='20%'>Total</th>";
                while($row = mysql_fetch_assoc($result))
                {                    
                    $rent1=0;$rentvat1=0;$sc1=0;$scvat1=0;$total1=0;
                    $buildingmasid = $row['buildingmasid'];                    
                    $table .="<tr><th>";
                    $table .=$row['buildingname'];
                    $table .="</th>";
                        //invoiced
                        $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*16/100))) as rentvat,sum(sc) as sc,sum(round((sc*16/100))) as scvat
                            ,sum(rent+round((rent*16/100))+sc+round((sc*16/100))) as total,isvat
                            from group_tenant_mas a
                            inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_building d on d.buildingmasid = c.buildingmasid
                            inner join invoice e on e.grouptenantmasid = a.grouptenantmasid
                            where c.buildingmasid ='$buildingmasid' and  
                            date_format(e.createddatetime,'%Y-%m-%d') = DATE_ADD(CURDATE(), INTERVAL ".$dy." DAY);";
                        $resultdet = mysql_query($sqldet);
                        if($resultdet != null)
                        {        
                            while($rowdet = mysql_fetch_assoc($resultdet))
                            {
                                $rent +=$rowdet['rent'];
				
				if($rowdet['isvat']==0)	
				$rentvat +=$rowdet['rentvat'];
				
                                $sc +=$rowdet['sc']; $scvat +=$rowdet['scvat'];
                                $total +=$rowdet['total'];
                                
                                $rent1 +=$rowdet['rent'];
				
				if($rowdet['isvat']==0)	
				$rentvat1 +=$rowdet['rentvat'];
				
                                $sc1 +=$rowdet['sc']; $scvat1 +=$rowdet['scvat'];
                                $total1 +=$rowdet['total'];
                            }
                        }
                        //advance_rent
                        $sqldet ="select a.grouptenantmasid ,c.tenantmasid,d.buildingname,invoiceno,sum(rent) as rent,sum(round((rent*16/100))) as rentvat,sum(sc) as sc,sum(round((sc*16/100))) as scvat
                            ,sum(rent+round((rent*16/100))+sc+round((sc*16/100))) as total,isvat
                            from group_tenant_mas a
                            inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_building d on d.buildingmasid = c.buildingmasid
                            inner join advance_rent e on e.grouptenantmasid = a.grouptenantmasid
                            where c.buildingmasid ='$buildingmasid' and  
                            date_format(e.createddatetime,'%Y-%m-%d') = DATE_ADD(CURDATE(), INTERVAL ".$dy." DAY);";
                        $resultdet = mysql_query($sqldet);
                        if($resultdet != null)
                        {        
                            while($rowdet = mysql_fetch_assoc($resultdet))
                            {
                                $rent +=$rowdet['rent'];
				
				if($rowdet['isvat']==0)				
				$rentvat +=$rowdet['rentvat'];
				
                                $sc +=$rowdet['sc']; $scvat +=$rowdet['scvat'];
                                $total +=$rowdet['total'];
                                
                                $rent1 +=$rowdet['rent'];
				
				if($rowdet['isvat']==0)				
				$rentvat1 +=$rowdet['rentvat'];
				
				
                                $sc1 +=$rowdet['sc']; $scvat1 +=$rowdet['scvat'];
                                $total1 +=$rowdet['total'];
                            }
                        }
                    $table .="<td align='right'>".number_format($rent1, 0, '.', ',')."</td>";		    
                    $table .="<td align='right'>".number_format($rentvat1, 0, '.', ',')."</td>";
                    $table .="<td align='right'>".number_format($sc1, 0, '.', ',')."</td>";
                    $table .="<td align='right'>".number_format($scvat1, 0, '.', ',')."</td>";
                    $table .="<td align='right'>".number_format($total1, 0, '.', ',')."</td>";                                                            
                    $table .="</tr>";            
                }
                $table .="<tr align='right'><th>Total</th>";
                $table .="<td>".number_format($rent, 0, '.', ',')."</td>
                          <td>".number_format($rentvat, 0, '.', ',')."</td>
                          <td>".number_format($sc, 0, '.', ',')."</td>
                          <td>".number_format($scvat, 0, '.', ',')."</td>
                          <td>".number_format($total, 0, '.', ',')."</td>";
                $table .="</tr></table><br>";            
	    }
        }
    }    
    echo $table;
?>