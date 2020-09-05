<?php

include('../config.php');
session_start();
try{
    $load= $_GET['item'];
    $companymasid = $_SESSION['mycompanymasid'];
    $occupied_sqrft=0;$vacant_sqrft=0;$exp_sqrft=0;$total_sqrft=0;
    if($load == "shopstatus")
    {        
	$buildingmasid = $_GET['buildingmasid'];
        $sql = "select  buildingname,buildingmasid from mas_building where buildingmasid=$buildingmasid";    
        $result = mysql_query($sql);        
        $table="<table style='background-color:#cdf5f1;font-size: 6em;'cellpadding='1' cellspacing='2'>";
        $j=1;
        if($result != "")
        {            
            $row = mysql_fetch_assoc($result);
            $buildingmasid = $row['buildingmasid'];
            $buildingname = $row['buildingname'];            
            $table .="<tr><th colspan='10' style='font-align:left;height:50px;'>$buildingname</td></tr>";
            
                $sql1 = "select floormasid,floordescription from mas_floor where buildingmasid =$buildingmasid";    
                $result1 = mysql_query($sql1);
                if($result1 != "")
                {
                    while ($row1 = mysql_fetch_assoc($result1))
                    {
                        $floormasid = $row1['floormasid'];
                        $floordescription = $row1['floordescription'];
                        $table .="<tr style='height:2px;'><th colspan='10' style='font-align:left;background-color:#cdf5f1;height:20px;'>$floordescription</th></tr>";
                        $sql2 = "select shopmasid,shopcode,size from mas_shop where floormasid = '$floormasid' and active ='1'";    
                        $result2 = mysql_query($sql2);
                        $i=0;
                        $table .="<tr>";
                        if($result2 != "")
                        {
                            while($row2 = mysql_fetch_assoc($result2))
                            {
                                $tenantmasid="";
				$tenant="";
                                $shopmasid = $row2["shopmasid"];
                                $shopcode = $row2["shopcode"];
                                $size = $row2["size"];
                                
                                $sql3 = "select tenantmasid,leasename,tradingname from mas_tenant a
                                            inner join mas_shop b on b.shopmasid = a.shopmasid
                                            where b.shopmasid='$shopmasid' and a.active='1' and a.shopoccupied='1'
                                            union
                                        select tenantmasid,leasename,tradingname from rec_tenant a
                                            inner join mas_shop b on b.shopmasid = a.shopmasid
                                            where b.shopmasid='$shopmasid' and a.active='1' and a.shopoccupied='1'"; 
                                $result3 = mysql_query($sql3);
                                if($result3 != "")
                                {
                                    while($row3 = mysql_fetch_assoc($result3))
                                    {                                        
					if($row3["tradingname"]!="")
					    $tenant =$row3["leasename"]." T/A ".$row3["tradingname"];
					else
					    $tenant =$row3["leasename"];
					$tenantmasid =$row3["tenantmasid"];
					
					$bool = true;
					$sqlexp = "select max(a.todate) as todate from trans_offerletter_rent a
						inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
						inner join mas_tenant c on c.tenantmasid = b.tenantmasid
						where c.tenantmasid ='$tenantmasid';";
					$resultexp = mysql_query($sqlexp);
					if($resultexp != null)
					{
					    $rowexp = mysql_fetch_assoc($resultexp);
					    $today = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datetime)) . " + 0 Months"));
					    $today = strtotime($today);
					    $expirydt  = $rowexp["todate"];
					    $expdt1 = date("d.m.Y", strtotime(date("Y-m-d", strtotime($expirydt)) . " + 0 Months"));
					    $expiry_date  = $rowexp["todate"];
					    $expirydt  = strtotime($expirydt);
					    if ($expirydt < $today) {
					       $bool = false;
					    }
					}
                                    }
                                }
                                if($tenant =="")
				{
                                    //$table .="<td style='background-color:red;color:white;'>".$shopcode."<br>Sqrft ".$size."<br>".$tenant."</td>";				    
				    $table .="<td style='background-color:red;color:white;width:150px;height:150px;'><br>VACANT</br>".$shopcode."<br>Sqrft ".$size."</td>";
				    $vacant_sqrft +=$size;
				    $total_sqrft +=$size;
				}
                                else
				{
                                    //$table .="<td style='background-color:green;color:white;'>".$shopcode."<br>Sqrft ".$size."<br>".$tenant."</td>";
				    if($bool == true) // IF LEASE EXPIRED THEN
				    {
					$table .="<td style='background-color:green;color:yellow;width:150px;height:150px;'><br>".$tenant."</br> Expiry Dt: ".$expdt1."<button type='button' id='btnDetails' name='btnDetails' value='$tenantmasid'>".$shopcode."<br>Sqrft ".$size."</button></td>";
					$occupied_sqrft +=$size;
					$total_sqrft +=$size;
					
				    }
				    else
				    {
					$table .="<td style='background-color:red;color:yellow;width:150px;height:150px;'><br>".$tenant."</br> Lease expired on ".$expdt1."<button type='button' id='btnDetails' name='btnDetails' value='$tenantmasid'>".$shopcode."<br>Sqrft ".$size."</button></td>";
					$exp_sqrft +=$size;
					$total_sqrft +=$size;
				    }
				}
                                $i++;
                                if($i==10)
                                {
                                    $i=0;
                                    $table .="</tr>";
                                    $table .="<tr>";
                                }
                            }                            
                        }
                        $table .="</tr>";   
                    }                    
                }
        }
	$span ="<br><center><span>$buildingname Total Sqrft: <b>".number_format($total_sqrft, 0, '.', ',')."</b>&nbsp;&nbsp;&nbsp;Expired n Occupied Sqrft:<b>".number_format($exp_sqrft, 0, '.', ',')."</b>&nbsp;&nbsp;&nbsp;Vacant Sqrft:<b>".number_format($vacant_sqrft, 0, '.', ',')."</b></span>";
        $table .="</table>";
	$p = "<p class='printable'></br>".$table."</p>";
        $custom = array('divContent'=> $span.$p,'s'=>'Success');    
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
    }
}
catch (Exception $err)
{
    
    $custom = array(
	'divContent'=> "</br>Error: ".$err->getMessage().", Line No:".$err->getLine(),
	's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>
