<?php
include('../config.php');
session_start();
try{
    $companymasid = $_SESSION['mycompanymasid'];
    $buildingmasid = $_GET['buildingmasid'];
    $dateFrom =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($_GET['acyearFrom']))));
    $dateTo =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($_GET['acyearTo']))));
    $active = $_GET['active'];
    
    $renttype= $_GET['renttype'];   
    $rpttype= $_GET['rpttype'];   
    
    $buildingname="";
    $sql = "select buildingname from mas_building where buildingmasid = $buildingmasid";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $buildingname = $row["buildingname"];
    
    
    $table="<p class='printable'><table class='table6'><tr><td colspan='30' style='font-weight:bold;'>".strtoupper($buildingname)." TENANCY MASTER DETAILS</td></tr>";
    $table.="<tr><td colspan='7'></td>";        

    ////$sql = "SELECT max(yearStart) as yst,max(yearEnd) as yend FROM(
    ////         select distinct YEAR(CURDATE()) as yearStart,Max(YEAR(b.todate)) as yearEnd from trans_offerletter a
    ////         inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
    ////         inner join mas_tenant c on c.tenantmasid = a.tenantmasid
    ////         inner join mas_building d on d.buildingmasid = c.buildingmasid
    ////         where d.buildingmasid =$buildingmasid and a.active='1'
    ////         union
    ////         select distinct YEAR(CURDATE()) as yearStart,Max(YEAR(b.todate)) as yearEnd from trans_offerletter a
    ////         inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
    ////         inner join rec_tenant c on c.tenantmasid = a.tenantmasid
    ////         inner join mas_building d on d.buildingmasid = c.buildingmasid
    ////         where d.buildingmasid =$buildingmasid and a.active='1') as t";

    ////draw table col for the date from and to
    $sql = "SELECT max(yearStart) as yst,max(yearEnd) as yend FROM(
             select distinct date_format('$dateFrom','%Y') as yearStart,date_format('$dateTo','%Y') as yearEnd from trans_offerletter a
             inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
             inner join mas_tenant c on c.tenantmasid = a.tenantmasid
             inner join mas_building d on d.buildingmasid = c.buildingmasid
             where d.buildingmasid =$buildingmasid and a.active='1'
             union
             select distinct date_format('$dateFrom','%Y') as yearStart,date_format('$dateTo','%Y') as yearEnd from trans_offerletter a
             inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
             inner join rec_tenant c on c.tenantmasid = a.tenantmasid
             inner join mas_building d on d.buildingmasid = c.buildingmasid
             where d.buildingmasid =$buildingmasid and a.active='1') as t";
    $th="";
    $result = mysql_query($sql);
    if($result != null)
    {
        $row = mysql_fetch_assoc($result);
        $st = $row["yst"];
        $end = $row["yend"];
        for($i=$st;$i<=$end;$i++)
        {
            //year iterate starts
            $table.="<td width='20px' style='font-weight:bold;'>$i</td>";                                      
            $th .="<th>RENT   SC</th>";
        }
    }
    $table.="</tr>";
    
    $table.="<tr>";
        $table.="<th align='center'>Sno</th>";
        $table.="<th>Shop</th>";
        $table.="<th>Sqrft</th>";
        $table.="<th>Tenant</th>";
        $table.="<th>Cycle</th>";
        $table.="<th>Commence</th>";
        $table.="<th>Expiry</th>";
        $table.=$th;       
    $table.="</tr>";
    
    $j=0;$tenant="";$tenantmasid="";$tenantstatus="";$commdt="";
    
    
    // report Type active or full tenancies
    if($active == 0)
        $active =" a.active ='1' and shopoccupied='1' and ";
    else
        $active ="";
        
    $sql = "select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,b.size,d.shortdesc as rentcycle,a.active as tenantstatus from mas_tenant a
            inner join mas_shop b on b.shopmasid = a.shopmasid
            inner join mas_age c on c.agemasid = a.agemasidlt
            inner join mas_age d on d.agemasid = a.agemasidrc
            where $active a.renewal='0' and a.buildingmasid = $buildingmasid
            union
            select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,b.size,d.shortdesc as rentcycle,a.active as tenantstatus from rec_tenant a
            inner join mas_shop b on b.shopmasid = a.shopmasid
            inner join mas_age c on c.agemasid = a.agemasidlt
            inner join mas_age d on d.agemasid = a.agemasidrc
            where $active a.renewal='0' and a.buildingmasid = $buildingmasid
            order by leasename";
    $result = mysql_query($sql);
    $result1 = mysql_query($sql);
    if($result != null)
    {
        while($row = mysql_fetch_assoc($result))
        {
            $table1="";
            $tenantmasid=$row['tenantmasid'];
            $tenantstatus = $row['tenantstatus'];
            $foo = true;
            //commence, expiry
            //$sqldt = "select a.offerlettermasid, date_format(min(b.fromdate),'%d-%m-%Y') as commdt,max(b.todate) as todate,date_format(max(b.todate),'%d-%m-%Y') as expdt from trans_offerletter a 
            //            inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
            //            where a.tenantmasid =$tenantmasid and a.active='1';";
            $sqldt = "select a.offerlettermasid, date_format(min(b.fromdate),'%d-%m-%Y') as commdt,max(b.todate) as todate,date_format(max(b.todate),'%d-%m-%Y') as expdt from trans_offerletter a 
                        inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
                        where a.tenantmasid =$tenantmasid and a.active='1'
                        and a.offerlettermasid not in (select offerlettermasid from rec_trans_offerletter where tenantmasid =$tenantmasid);";
            $resultdt = mysql_query($sqldt);
            $offerlettermasid ="";$td="";
            if($resultdt != null)
            {
                $rowdt = mysql_fetch_assoc($resultdt);
                $td .="<td>".$rowdt['commdt']."</td>";
                $commdt = date("Y-m-d", strtotime(date("Y-m-d", strtotime($rowdt['commdt']))));
                
                //check expired tenacies in the list
                if($rowdt['todate'] < date("Y-m-d"))
                $foo = false;
                
                $td .="<td>".$rowdt['expdt']."</td>";
                    
                $offerlettermasid =$rowdt['offerlettermasid'];
            }
            
            
            //fill data in table row
            
            if($row['tradingname'] !="")
                $tenant = $row['leasename'] ." (T/A) ". $row['tradingname'];
            else
                $tenant = $row['leasename'];
                
            if($foo == true)
            {
                 $j++;
                if($tenantstatus == 1 )
                {
                    if($commdt >= $dateFrom)
                        $table1.="<tr style='color: green;'>"; // active tenants
                    else
                        $table1.="<tr>"; // active tenants
                    
                }
                else
                {
                    $table1.="<tr style='color: red;'>"; // discharged or renewed
                }
            }
            else
            {                                
                $table1.="<tr style='color: red; background: black;'>";  // tenancy period completed but still ocupying shops
                //$table1.="<tr style='color: red;'>";  // tenancy period completed but still ocupying shops
            }
            
            //tenant iterates
            $table1.="<td align='center'>$j</td>";
            $table1.="<td>".$row['shopcode']."</td>";
            $table1.="<td>".$row['size']."</td>";
                ////$table1.="<td>$tenant.' - '.$tenantmasid</td>";
            $table1.="<td>$tenant</td>";
            $table1.="<td>".$row['rentcycle']."</td>";            
            $table1.=$td; // add commencement and expiry td's
            $tst="";$foo1=false;
            $lastrent=0;$lastsc=0;
            for($i=$st;$i<=$end;$i++)
            {                                
                $table1.="<td>";                
                    $table1.="<table width='100%'>";
                    $table1.="<tr>";
                        //year wise rent iterate starts
                        $sqlyear = "select a.amount as rent from trans_offerletter_rent a where a.offerlettermasid ='$offerlettermasid' and date_format(a.fromdate,'%Y') = '$i';";
                        $resultyear = mysql_query($sqlyear);
                        $rent=0;
                        if($resultyear != null)
                        {
                            $rowyear = mysql_fetch_assoc($resultyear);
                            if(mysql_num_rows($resultyear) > 0 )
                            {
                                $rent +=$rowyear['rent'];
                                $foo1=true;
                            }                            
                        }
                        if($rent > 0)
                        {
                            if($renttype == 1)
                            {
                                if(strtolower($row['rentcycle']) =='qtrly')
                                {
                                    $rent = $rent/3;
                                }
                                else if(strtolower($row['rentcycle']) =='hafly')
                                {
                                    $rent = $rent/6;
                                }
                                else if(strtolower($row['rentcycle']) =='yearly')
                                {
                                    $rent = $rent/12;
                                }
                                $lastrent=$rent;
                            }
                            $table1.="<td align='right'>".number_format($rent, 0, '.', ',')."</td>";                            
                        }
                        else
                        {
                            if($rpttype == 1)
                            {
                                //// 10 % increase from last drawn rent for projections
                                $rent = $lastrent*1.1;
                                $lastrent=$rent;
                                $table1.="<td align='right' style='color: red;'>".number_format($rent, 0, '.', ',')."</td>";                            
                            }
                            else
                            {
                                $table1.="<td align='right'>".number_format($rent, 0, '.', ',')."</td>";                            
                            }
                        }

                        
                        //year wise sc iterate starts
                        
                        $sqlyear = "select a.amount as sc from trans_offerletter_sc a where a.offerlettermasid ='$offerlettermasid' and date_format(a.fromdate,'%Y') = '$i';";
                        $resultyear = mysql_query($sqlyear);
                        $sc=0;
                        if($resultyear != null)
                        {
                            $rowyear = mysql_fetch_assoc($resultyear);
                            if(mysql_num_rows($resultyear) > 0 )
                            {
                                $sc +=$rowyear['sc'];
                            }                            
                        }
                        
                        if($sc > 0)
                        {
                            if($renttype == 1)
                            {
                                if(strtolower($row['rentcycle']) =='qtrly')
                                {
                                    $sc = $sc/3;
                                }
                                else if(strtolower($row['rentcycle']) =='hafly')
                                {
                                    $sc = $sc/6;
                                }
                                else if(strtolower($row['rentcycle']) =='yearly')
                                {
                                    $sc = $sc/12;
                                }
                                $lastsc = $sc;
                            }
                            $table1.="<td align='right'>".number_format($sc, 0, '.', ',')."</td>";                            
                        }
                        else
                        {
                            if($rpttype == 1)
                            {
                                // 10 % increase from last drawn sc for projections
                                $sc = $lastsc*1.1;
                                $lastsc=$sc;
                                $table1.="<td align='right' style='color: red;'>".number_format($sc, 0, '.', ',')."</td>";                                
                            }
                            else
                            {
                                $table1.="<td align='right'>".number_format($sc, 0, '.', ',')."</td>";                            
                            }
                        }
                        
                    
                    $table1.="</tr>";
                    $table1.="</table>";
                $table1.="</td>";
                
                // row total into array
                $colname1 = $i."rent";                
                $arrayItem [] = array('name'=>$colname1,'value'=>$rent);
                $colname2 = $i."sc";
                $arrayItem [] = array('name'=>$colname2,'value'=>$sc);
                
            }
            $table1.="</tr>";
            if($foo1 == true)
                $table .=$table1;
            
        }
        //$rec_trans_offerletter        
        while($row = mysql_fetch_assoc($result1))
        {
            $rec_table="";
            $tenantmasid=$row['tenantmasid'];
            $tenantstatus = $row['tenantstatus'];
            $foo = true;
            //commence, expiry
            $sqldt = "select a.offerlettermasid, date_format(min(b.fromdate),'%d-%m-%Y') as commdt,max(b.todate) as todate,date_format(max(b.todate),'%d-%m-%Y') as expdt from rec_trans_offerletter a 
                        inner join rec_trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
                        where a.tenantmasid =$tenantmasid and a.active='1';";
            $resultdt = mysql_query($sqldt);
            $offerlettermasid ="";$td="";$nr=0;
            if($resultdt != null)
            {
                $nr= mysql_num_rows($resultdt);
                $rowdt = mysql_fetch_assoc($resultdt);                
                $td .="<td>".$rowdt['commdt']."</td>";
                $commdt = date("Y-m-d", strtotime(date("Y-m-d", strtotime($rowdt['commdt']))));
                
                //check expired tenacies in the list
                if($rowdt['todate'] < date("Y-m-d"))
                $foo = false;
                
                $td .="<td>".$rowdt['expdt']."</td>";
                    
                $offerlettermasid =$rowdt['offerlettermasid'];
            }
            //fill data in table row
           
            
            if($row['tradingname'] !="")
                $tenant = $row['leasename'] ." (T/A) ". $row['tradingname'];
            else
                $tenant = $row['leasename'];
        
                
            if($foo == true)
            {
                if($tenantstatus == 1 )
                {
                    if($commdt >= $dateFrom)
                        $rec_table.="<tr style='color: green;'>"; // active tenants
                    else
                        $rec_table.="<tr>"; // active tenants
                    
                }
                else
                {
                    $rec_table.="<tr style='color: red;'>"; // discharged or renewed
                }
                 $j++;
            }
            else
            {                                
                $rec_table.="<tr style='color: red; background: black;'>";  // tenancy period completed but still ocupying shops
                //$rec_table.="<tr style='color: red;'>";  // tenancy period completed but still ocupying shops
            }
            
            //tenant iterates
            $rec_table.="<td align='center'>$j</td>";
            $rec_table.="<td>".$row['shopcode']."</td>";
            $rec_table.="<td>".$row['size']."</td>";
                ////$rec_table.="<td>$tenant.' - '.$tenantmasid</td>";
            $rec_table.="<td>$tenant</td>";
            $rec_table.="<td>".$row['rentcycle']."</td>";            
            $rec_table.=$td; // add commencement and expiry td's
            $tst="";$foo1=false;
            for($i=$st;$i<=$end;$i++)
            {                                
                $rec_table.="<td>";                
                    $rec_table.="<table width='100%'>";
                    $rec_table.="<tr>";
                        //year wise rent iterate starts
                        $sqlyear = "select a.amount as rent from rec_trans_offerletter_rent a where a.offerlettermasid ='$offerlettermasid' and date_format(a.fromdate,'%Y') = '$i';";
                        $resultyear = mysql_query($sqlyear);
                        $rent=0;
                        if($resultyear != null)
                        {
                            $rowyear = mysql_fetch_assoc($resultyear);
                            if(mysql_num_rows($resultyear) > 0 )
                            {
                                $rent +=$rowyear['rent'];
                                $foo1=true;
                            }                            
                        }
                        if($rent > 0)
                        {
                            if($renttype == 1)
                            {
                                if(strtolower($row['rentcycle']) =='qtrly')
                                {
                                    $rent = $rent/3;
                                }
                                else if(strtolower($row['rentcycle']) =='hafly')
                                {
                                    $rent = $rent/6;
                                }
                                else if(strtolower($row['rentcycle']) =='yearly')
                                {
                                    $rent = $rent/12;
                                }
                                $lastrent=$rent;
                            }
                            $rec_table.="<td align='right'>".number_format($rent, 0, '.', ',')."</td>";                            
                        }
                        else
                        {
                            if($rpttype == 1)
                            {
                                // 10 % increase from last drawn rent
                                $rent = $lastrent*1.1;
                                $lastrent=$rent;
                                $rec_table.="<td align='right' style='color: red;'>".number_format($rent, 0, '.', ',')."</td>";                            
                            }
                            else
                            {
                                $rec_table.="<td align='right'>".number_format($rent, 0, '.', ',')."</td>";                            
                            }
                        }
                        
                        //year wise sc iterate starts
                        $sqlyear = "select a.amount as sc from rec_trans_offerletter_sc a where a.offerlettermasid ='$offerlettermasid' and date_format(a.fromdate,'%Y') = '$i';";
                        $resultyear = mysql_query($sqlyear);
                        $sc=0;
                        if($resultyear != null)
                        {
                            $rowyear = mysql_fetch_assoc($resultyear);
                            if(mysql_num_rows($resultyear) > 0 )
                            {
                                $sc +=$rowyear['sc'];
                            }                            
                        }
                        if($sc > 0)
                        {
                            if($renttype == 1)
                            {
                                if(strtolower($row['rentcycle']) =='qtrly')
                                {
                                    $sc = $sc/3;
                                }
                                else if(strtolower($row['rentcycle']) =='hafly')
                                {
                                    $sc = $sc/6;
                                }
                                else if(strtolower($row['rentcycle']) =='yearly')
                                {
                                    $sc = $sc/12;
                                }
                                $lastsc = $sc;
                            }
                            $rec_table.="<td align='right'>".number_format($sc, 0, '.', ',')."</td>";                            
                        }
                        else
                        {
                            if($rpttype == 1)
                            {
                                // 10 % increase from last drawn sc
                                $sc = $lastsc*1.1;
                                $lastsc=$sc;
                                $rec_table.="<td align='right' style='color: red;'>".number_format($sc, 0, '.', ',')."</td>";                            
                            }
                            else
                            {
                                $rec_table.="<td align='right'>".number_format($sc, 0, '.', ',')."</td>";                            
                            }
                        }                        
                    $rec_table.="</tr>";
                    $rec_table.="</table>";
                $rec_table.="</td>";
                
                // row total into array
                $colname1 = $i."rent";                
                $arrayItem [] = array('name'=>$colname1,'value'=>$rent);
                $colname2 = $i."sc";
                $arrayItem [] = array('name'=>$colname2,'value'=>$sc);
                
            }
            $rec_table.="</tr>";
            if($foo1 == true)
                $table .=$rec_table;
            
        } 
        $sqlGet ="";
        $nk =0;
        foreach ($arrayItem as $k=>$v) {
              foreach ($v as $a=>$b) {
                $sqlGet.= $nk."; Name: ".$a."; Value: ".$b."<BR>";      
                //if($a == "2013rent")
                //$sqlGet .= $b;
                $nk++;
              }    
        }
        //echo $sqlGet;        
        //
        $custom = array('divContent'=> $table,'s'=>'Success');        
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit;
    }    
    
    
    $table.="</table></p>";
    $custom = array('divContent'=> "<br>".$table,'s'=>'Success');
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