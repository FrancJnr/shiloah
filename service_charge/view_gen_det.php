<?php
include('../config.php');
session_start();

//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {
//	    $k = preg_replace('/[^a-z]/i', '', $k); 
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$custom = array('msg'=> $sqlArray ,'s'=>'error');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;

try
{
    $companymasid = $_SESSION['mycompanymasid'];
    $buildingmasid = $_GET['buildingmasid'];    
    $firstdate ="";
    $fromdt="";
    $todt="";


    function diff_months($dt1,$dt2)
    {
        $tmpDate = new DateTime($dt1);
        $tmpEndDate = new DateTime($dt2);
        $outArray="";
        while($tmpDate <= $tmpEndDate)
        {
            $outArray .= strtolower($tmpDate->format('Y M')).";";
            $tmpDate->modify('+1 Month');
        }               
        return $outArray;
    }
    
    // PERIOD 1
    $m1 =$_GET["y1"]."-".$_GET["m1"];
    $m2 = $_GET["y1"]."-".$_GET["m2"];
    
    $period1 = diff_months($m1,$m2);
    $months="";
    $sqlExec = explode(";",$period1);
    $transexpmasid="";$fromdate1="";$todate1="";$n1=0;
    for($i=0;$i<count($sqlExec);$i++)
    {
        if($sqlExec[$i] != "")
        {
            ////$months .= $sqlExec[$i]."</br>";           
            $sql = "select transexpmasid,date_format(fromdate,'%M %Y') as fromdate,date_format(todate,'%M %Y') 
                        as todate, active,buildingmasid,totalamount from trans_exp_mas where date_format(fromdate,'%Y %b') = ('$sqlExec[$i]')
                        and buildingmasid=$buildingmasid;";            
            $result = mysql_query($sql);                
            if($result != null)
            {
                $row = mysql_fetch_assoc($result);
                $transexpmasid .= $row['transexpmasid'].",";
                $fromdate1=$row['fromdate'];
                $todate1=$row['todate'];
                $n1++;
            }
        }// array split loop
    }
    $transexpmasid = rtrim($transexpmasid,",");    
    
    $sql="select fromdate, todate from trans_exp_mas where transexpmasid in ($transexpmasid) and active='1';";
    $result = mysql_query($sql);
    if($result != null)
    {
        while($row = mysql_fetch_assoc($result))
        {
            $fromdt = $row['fromdate'];
            $todt   = $row['todate'];
            $firstdate = date('M-Y', strtotime($row['fromdate'])) ." to ".date('M-Y', strtotime($row['todate']));
        }    
    }
    
    //expgroupmasid ='3' direct cost id
    //expledgermasid='14' generator running cost id
    $appgencot=0;
    $sqlg = "select b.amount as 'gencost' from trans_exp_mas a
            inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
            inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
            inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
            where c.expgroupmasid ='3'  and b.expledgermasid='14' and a.transexpmasid ='$transexpmasid';";
    ////echo $insertmas;	
    $resultg = mysql_query($sqlg);
    if($resultg !=null)
    {
        $rowg = mysql_fetch_assoc($resultg);
        $appgencot= $rowg['gencost'];
    }
    
    // date diff
    $diff = abs(strtotime($todt) - strtotime($fromdt));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    
    // no of months between dates
    $date1 = strtotime($fromdt);
    $date2 = strtotime($todt);
    
    
    if($date1 == $date2)
        $months = 0;
    else
        $months = 1;
    
    while (strtotime('+1 MONTH', $date1) < $date2) {    
        $date1 = strtotime('+1 MONTH', $date1);
        $months++;
    }
    
    
    $d1 = date('Y-m', strtotime($fromdt));
    $d2 = $d1;
    
    $buildingname = ""; $shortname ="";
    $s = "select buildingname,shortname from mas_building where buildingmasid =$buildingmasid;";
    $r = mysql_query($s);
    while($ro = mysql_fetch_assoc($r)){
        $buildingname = strtoupper($ro["buildingname"]);
        $shortname = strtoupper($ro["shortname"]);    
    }
    $td="";
    
    $kwvalue ="0.9";
    $transexpgenmasid=0;
    // trans_gen_mas
    $sqlgenmas = "SELECT transexpgenmasid,kwvalue FROM trans_exp_gen_mas where transexpmasid  ='$transexpmasid';";
    $resultgenmas = mysql_query($sqlgenmas);
    if($resultgenmas !=null)
    {
        $rc = mysql_num_rows($resultgenmas);
        if($rc>0)
        {
            //TEST
            ////header("Location: view_gen_det_dir.php?transexpmasid=$transexpmasid");
            
            $rowgenmas = mysql_fetch_assoc($resultgenmas);
            $kwvalue =$rowgenmas['kwvalue'];
            $transexpgenmasid=$rowgenmas['transexpgenmasid'];
        }
        else
        {
            //redirect
            header("Location: view_gen_det_dir.php?transexpmasid=$transexpmasid");
        }
    }
    $rowtotal=0;
    $sqlgendet = "select * from trans_exp_gen_det where transexpgenmasid ='$transexpgenmasid';";
    $resultgendet = mysql_query($sqlgendet);
    if($resultgendet !=null)
    {
        $rowtotal=mysql_num_rows($resultgendet);
    }
    
    $connkw_dbval_t=0;
    $addikw_dbval_t=0;
    
    $totalkw_dbval_t=0;
    $totalkva_dbval_t=0;
    $totalkvapc_dbval_t=0;
    
    $chrgddircost_dbval_t=0;
    $chrgdcomcost_dbval_t=0;
    $gencost_dbval_t=0;
                
    $table ="<p class='printable'><table class='table6'>";
    $table .="<tr><th colspan='18'>Genarator Apportionment</th></tr>";
    $table .="<tr><td colspan='18'><b> $buildingname <br><br> ".$firstdate."</b></td></tr>";
    $table .="<tr>
                <td colspan='2'>GENCOST:</td><td>$appgencot</td>
                <td colspan='3'>KW Value:</td><td>$kwvalue</td>
                <td colspan='6'></td>
            </tr>";
    $table .="<tr>";
    $table .="<th>S.No</th>";
    $table .="<th>Tenant</th>";
    $table .="<th>Sqrft</th>";
    $table .="<th>Mnths</th>";
    $table .="<th>Total Sqrft</th>";
    $table .="<th>Connected KW</th>";
    $table .="<th>Additional KW</th>";
    $table .="<th>Total KW</th>";
    $table .="<th>Total KVA</th>";
    $table .="<th>KVA%</th>";
    $table .="<th>Chrgd Dir Cost</th>";
    $table .="<th>Chrgd Common Cost</th>";
    $table .="<th>Chrgd Gen Cost</th>";
    $table .="</tr>";

   

    $n =1;$cnt=0;
    $grandtsqrft=0;$grandtotal=0;$rowtotal1[0]=0;$rowtotal2[0]=0;$rowtotal3[0]=0;$tk="";
    $sql1 = "select c.tenantmasid,c.leasename,c.tradingname, a.grouptenantmasid,d.size
                from advance_rent a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_age f on f.agemasid = c.agemasidrc 
                where d.buildingmasid='$buildingmasid' 
            UNION
            select c1.tenantmasid,c1.leasename,c1.tradingname, a1.grouptenantmasid,d1.size
                from invoice a1
                inner join group_tenant_mas b1 on b1.grouptenantmasid = a1.grouptenantmasid
                inner join mas_tenant c1 on c1.tenantmasid = b1.tenantmasid
                inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
                inner join mas_building e1 on e1.buildingmasid = d1.buildingmasid
                inner join mas_age f1 on f1.agemasid = c1.agemasidrc
                where d1.buildingmasid='$buildingmasid' 
            order by leasename ;";   
    $result1 = mysql_query($sql1);
    $result1a = mysql_query($sql1);
    if($result1 !=null)
    {        
        //calculate total charged sqrft        
        while($row1 = mysql_fetch_assoc($result1)) // no of tenants available for the building
        {        
            $mnthschrgd=0;$td="";$totalsqrft=0;$sc=0;$linetotal=0;$s=0;
            $grouptenantmasid=$row1['grouptenantmasid'];
            $size=0;
            $sqlsize ="select sum(c.size) as size from group_tenant_det a
                        inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
                        inner join mas_shop c on  c.shopmasid = b.shopmasid
                        where  a.grouptenantmasid = '$grouptenantmasid'
                        union
                        select sum(c1.size) as size from group_tenant_det a1
                        inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
                        inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
                        where  a1.grouptenantmasid = '$grouptenantmasid';";
            $rs= mysql_query($sqlsize);
            $ro = mysql_fetch_assoc($rs);
            $size = $ro['size'];
            
            $leasename = $row1['leasename'];
            $grandtd="";
            //manual invoice sc dep         
            //$sql4="" 
            for($i=0;$i<=$months;$i++)// no of months charged
            {                    
                $d2 = date("Y-m", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
                
                //advance rent sc dep
                $sql2="select a.rent, a.sc,d.size,
                            date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',
                            date_format(a.todate,'%d-%m-%Y') as 'invto',                               
                            @mnths:= TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as dt,
                            a.sc /@mnths as 'mnthlysc',    
                            a.invoiceno
                            from advance_rent a
                            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_shop d on d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            inner join mas_age f on f.agemasid = c.agemasidrc
                            where a.sc>0 and a.grouptenantmasid='$grouptenantmasid' and
                            '$d2' between date_format(a.fromdate,'%Y-%m') and date_format(a.todate,'%Y-%m') limit 1;";
                $result2 = mysql_query($sql2);
                if($result2 !=null)
                {                
                    $rcount = mysql_num_rows($result2);
                    if($rcount ==0) 
                    {
                        //regular invoice rent sc dep
                        $sql2="select a.rent, a.sc,d.size,
                            date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',
                            date_format(a.todate,'%d-%m-%Y') as 'invto',                               
                            @mnths:= TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as dt,
                            a.sc /@mnths as 'mnthlysc',    
                            a.invoiceno
                            from invoice a
                            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_shop d on d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            inner join mas_age f on f.agemasid = c.agemasidrc
                            where a.sc>0 and a.grouptenantmasid='$grouptenantmasid' and
                            '$d2' between date_format(a.fromdate,'%Y-%m') and date_format(a.todate,'%Y-%m') limit 1;";
                    }
                }                
                $result2 = mysql_query($sql2);
                if($result2 !=null)
                {
                    $rcount = mysql_num_rows($result2);
                    if($rcount ==0) 
                    {                                                    
                        $rowtotal2[$s] = $s;                            
                        $s++;
                    }
                    $k = false;
                    while($row2 = mysql_fetch_assoc($result2))
                    {                                                
                        $k=true;
                        $mnthschrgd++;
                        $totalsqrft = $mnthschrgd*$size;                            
                    }
                    if($k==true){
                        if($sc >0){                            
                            $linetotal +=$sc;                           
                            $rowtotal2[$s] = $s;                            
                            $s++;
                        }                        
                    }
                }                
            } 
            if($mnthschrgd >0)
            {                
                $grandtsqrft +=$totalsqrft;                            
            }        
        }          
        
        // display tenants
        while($row1a = mysql_fetch_assoc($result1a)) // no of tenants available for the building
        {
            $mnthschrgd1=0;$td="";$totalsqrft=0;$sc=0;$sc1="";$linetotal=0;$s=0;
            $grouptenantmasid=$row1a['grouptenantmasid'];
             $size=0;
            $sqlsize ="select sum(c.size) as size from group_tenant_det a
                        inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
                        inner join mas_shop c on  c.shopmasid = b.shopmasid
                        where  a.grouptenantmasid = '$grouptenantmasid'
                        union
                        select sum(c1.size) as size from group_tenant_det a1
                        inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
                        inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
                        where  a1.grouptenantmasid = '$grouptenantmasid';";
            $rs= mysql_query($sqlsize);
            $ro = mysql_fetch_assoc($rs);
            $size = $ro['size'];
             $tenantmasid = $row1a['tenantmasid'];  
            $sqltenant = "select leasename,tradingname,tenancyrefcode from mas_tenant a
                            left join mas_tenancyrefcode b on b.tenantmasid  =a.tenantmasid
                            where a.tenantmasid ='$tenantmasid'                                
                            union
                            select leasename,tradingname,tenancyrefcode from rec_tenant a
                            left join mas_tenancyrefcode b on b.tenantmasid  =a.tenantmasid
                            where a.tenantmasid ='$tenantmasid'";
            $leasename="-";
            $resulttenant = mysql_query($sqltenant);
            if($resulttenant)
            {
                while ($rowt = mysql_fetch_assoc($resulttenant))
                {
                    if($rowt['tradingname'] =="")
                        $leasename = $rowt['leasename']." (".$rowt["tenancyrefcode"].")";
                    else
                        $leasename = $rowt['leasename'] ." T/A ".$rowt['tradingname']." (".$rowt["tenancyrefcode"].")";   
                }                
            }
                
            $grandtd="";
            //manual invoice sc dep         
            //$sql4=""
            $g=0;$f=0;
            for($i=0;$i<=$months;$i++)// no of months charged
            {                    
                $sc=0;                    
                $d2 = date("Y-m", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
                $sql2 ="select a.rent, a.sc,a.fromdate,a.todate,a.grouptenantmasid,c.tenantmasid,c.renewalfromid,
                        date_format(a.fromdate,'%m-%Y') as 'invfrom',
                        date_format(a.todate,'%m-%Y') as 'invto',                               
                        case lower(f.shortdesc)
                                when 'mnthly' then round(a.sc/1)
                                when 'qtrly' then round(a.sc/3)                    
                                when 'half' then round(a.sc/6)
                                when 'yearly' then round(a.sc/12)
                        end as 'mnthlyscold',
                        @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as monthsdiff,
                        round(a.sc/@m) as mnthlyscold,a.sc as mnthlysc,                            
                        a.invoiceno
                        from advance_rent a
                        inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                        inner join mas_shop d on d.shopmasid = c.shopmasid
                        inner join mas_building e on e.buildingmasid = d.buildingmasid
                        inner join mas_age f on f.agemasid = c.agemasidrc
                        where a.sc>0 and a.grouptenantmasid = '$grouptenantmasid' and date_format(a.fromdate,'%Y-%m') = '$d2'
                        union
                        select a.rent, a.sc,a.fromdate,a.todate,a.grouptenantmasid,c.tenantmasid,c.renewalfromid,
                        date_format(a.fromdate,'%m-%Y') as 'invfrom',
                        date_format(a.todate,'%m-%Y') as 'invto',                               
                        case lower(f.shortdesc)
                                when 'mnthly' then round(a.sc/1)
                                when 'qtrly' then round(a.sc/3)                    
                                when 'half' then round(a.sc/6)
                                when 'yearly' then round(a.sc/12)
                        end as 'mnthlyscold',
                        @m:=TIMESTAMPDIFF(MONTH, a.fromdate, a.todate)+1 as monthsdiff,
                        round(a.sc/@m) as mnthlyscold,a.sc as mnthlysc,
                        a.invoiceno
                        from invoice a
                        inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                        inner join mas_shop d on d.shopmasid = c.shopmasid
                        inner join mas_building e on e.buildingmasid = d.buildingmasid
                        inner join mas_age f on f.agemasid = c.agemasidrc
                        where a.sc>0 and a.grouptenantmasid = '$grouptenantmasid' and date_format(a.fromdate,'%Y-%m')= '$d2'
                        group by a.grouptenantmasid;";
                $result2 = mysql_query($sql2);                
                if($result2 !=null)
                {
                    $rcount = mysql_num_rows($result2);                    
                    if($rcount ==0)
                    {
                        if($f<=$months)
                        {                            
                            $cls = "row".$s;
                            $sc1=0;
                            $sql3 ="select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, round(sc/@s) as mnthlyscprev
                                    from advance_rent
                                    where grouptenantmasid = '$grouptenantmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m')
                                    union
                                    select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, round(sc/@s) as mnthlyscprev
                                    from invoice
                                    where grouptenantmasid = '$grouptenantmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m') limit 1;";
                            $result3 = mysql_query($sql3);
                            if($result3 !=null)
                            {
                                $rcount3 = mysql_num_rows($result3);
                                if($rcount3 >0)
                                {
                                    $row3 = mysql_fetch_assoc($result3);                                
                                    $sc1 = $row3['mnthlyscprev'];
                                    if ($sc1>0)
                                    $mnthschrgd1 ++;                                    
                                    $linetotal +=$sc1;
                                }
                            }
                            $td .="<td class='row".$s."' align='right'>".number_format($sc1, 0, '.', ',')."</td>";
                            $rowtotal2[$s] = $s;                            
                            $s++;
                        }
                        $f++;
                    }
                    else
                    {                                                
                        while($row2 = mysql_fetch_assoc($result2))
                        {                                                                                                               
                            $tenantmasid = $row2['renewalfromid'];
                            $invno = $row2['invoiceno'];
                            $invsc = $row2['mnthlysc'];
                            $df1 = $row2['fromdate'];
                            $dtt = $row2['invfrom']."--".$row2['invto'];;
                            $md=$row2['monthsdiff'];                             
                            $sc = $row2['mnthlysc'];
                             
                            $rcount = mysql_num_rows($result2);
                            $sc = $sc/$md;                            
                            // split month value
                            for($x=0;$x<$md;$x++)
                            {                                                                                                
                                if($f<=$months)
                                {                                    
                                     $cls = "row".$s;
                                   ////CREDIT NOTE CHECK
                                    $scr=0;$crinvno=0;
                                    $sqlcr ="SELECT value as sc,invoiceno as crinvno FROM shiloahmsk.invoice_cr_det where invoiceno like ('%$invno%') and invoicedescmasid='31';"; // search for service charge deposit
                                    $resultcr = mysql_query($sqlcr);
                                    if($resultcr)
                                    {
                                        while ($rowcr = mysql_fetch_assoc($resultcr))
                                        {
                                            $scr+= $rowcr["sc"];
                                            $crinvno =$rowcr["crinvno"];
                                        }
                                    }                                    
                                    if($scr>0)
                                    {
                                        if($scr>=$sc)
                                                $sc = $scr-$scr;
                                            else if($sc>=$scr)
                                                $sc = $sc-$scr;                                   
                                        //if($sc>0)
                                        //{
                                            $linetotal +=$sc;
                                            $td .="<td class='row".$s."' align='right' style='color:red'>".number_format($sc, 0, '.', ',')."</td>";
                                            //$td .="<td class='row".$s."' align='right' style='color:red'>CR ".number_format($scr, 0, '.', ',')."</td>";
                                            //$td .="<td class='row".$s."' align='right' style='color:red'> CR $crinvno</td>";
                                            //$td .="<td class='row".$s."' align='right'>$mnthschrgd</td>";
                                            $rowtotal2[$s] = $s;                            
                                            $s++;
                                        //}
                                    }
                                    else
                                    {                                        
                                        $mnthschrgd1 ++;      
                                        $linetotal +=$sc;                                    
                                        $td .="<td class='row".$s."' align='right'>".number_format($sc, 0, '.', ',')."</td>";
                                        /////$td .="<td class='row".$s."' align='right'>$mnthschrgd</td>";
                                        $rowtotal2[$s] = $s;                            
                                        $s++;
                                    }
                                }                               
                                $f++;
                            }                            
                            $i +=$md-1;
                        }
                    }
                }
                else
                {
                    $td .="<td class='row".$s."' align='right'>null</td>";
                }                            
            }            
            if($mnthschrgd1 >0)
            {                                
                $sc = $linetotal;
                $totalsqrft = $mnthschrgd1*$size;   
                if($size =="")
                $size = $row1['size'];                            
                $table .="<tr>";
                $table .="<td align='center'>".$n++.".</td>";
                //grouptenantmasid
                $gmasid = "grouptenantmasid".$n;
                $table .="<td>$leasename<input type='hidden' id='$gmasid' name='$gmasid' value='$grouptenantmasid' /></td>";
                $sqrft = "sqrft".$n;
                $table .="<td class='totalsqrft'>$size<input type='hidden' id='$sqrft' name='$sqrft' value='$size' /></td>";
                
                $mnthschrgd = "mnthschrgd".$n;
                $table .="<td>$mnthschrgd1<input type='hidden' id='$mnthschrgd' name='$mnthschrgd' value='$mnthschrgd1' /></td>";                
                $table .="<td class='totalsqrftmc'>$totalsqrft</td>";
                
                
                // trans_gen_det
                $connkw_dbval=0;
                $addikw_dbval=0;
                
                $totalkw_dbval=0;
                $totalkva_dbval=0;
                $totalkvapc_dbval=0;
                
                $chrgddircost_dbval=0;
                $chrgdcomcost_dbval=0;
                $gencost_dbval=0;
                
                
                
                $sqlgendet = "select * from trans_exp_gen_det where transexpgenmasid ='$transexpgenmasid' and grouptenantmasid =$grouptenantmasid;";
                $resultgendet = mysql_query($sqlgendet);
                if($resultgendet !=null)
                {
                    
                    $rowgendet = mysql_fetch_assoc($resultgendet);
                    $connkw_dbval= $rowgendet['connkw'];
                    $connkw_dbval_t +=$connkw_dbval;
                    
                    $addikw_dbval= $rowgendet['addikw'];
                    $addikw_dbval_t +=$addikw_dbval;
                    
                    $totalkw_dbval=$rowgendet['totalkw'];
                    $totalkw_dbval_t +=$totalkw_dbval;
                    
                    $totalkva_dbval=$rowgendet['totalkva'];
                    $totalkva_dbval_t +=$totalkva_dbval;
                    
                    $totalkvapc_dbval=$rowgendet['totalkvapc'];
                    $totalkvapc_dbval_t +=$totalkvapc_dbval;
                    
                    $chrgddircost_dbval=$rowgendet['chrgddircost'];
                    $chrgddircost_dbval_t +=$chrgddircost_dbval;
                    
                    $chrgdcomcost_dbval=$rowgendet['chrgdcomcost'];
                    $chrgdcomcost_dbval_t +=$chrgdcomcost_dbval;
                    
                    $gencost_dbval=$rowgendet['gencost'];
                    $gencost_dbval_t +=$gencost_dbval;
                }   
                
                
                //connetd kw
                $connkw = 'connkw'.$n;                
                $table .="<td>$connkw_dbval</td>";
                
                //Additional kw
                $addikw = 'addikw'.$n;
                
                $hid_totalkw='hid_totalkw'.$n;
                $hid_totalkva='hid_totalkva'.$n;
                $hid_netkva='hid_netkva'.$n;
                
                $hid_chrgddircost='hid_chrgddircost'.$n;
                $hid_chrgdcomcost='hid_chrgdcomcost'.$n;
                $hid_gencost='hid_gencost'.$n;
                
                $table .="<td>$addikw_dbval</td>";
                
                //Total KW
                $totalkw='totalkw'.$n;
                $table .="<td id=$totalkw name=$totalkw idd = $n class='totalkw'>$totalkw_dbval</td>";
                //Total KVA
                $totalkva='totalkva'.$n;            
                $table .="<td id=$totalkva name=$totalkva idd = $n class='totalkva'>$totalkva_dbval</td>";                
                //KVA %
                $netkva='netkva'.$n;            
                $table .="<td id=$netkva name=$netkva idd = $n class='totalkvapc'>$totalkvapc_dbval%</td>";
                
                //chrged direcot generator cost
                $chrgddircost='chrgddircost'.$n;            
                $table .="<td id=$chrgddircost name=$chrgddircost idd = $n class='chrgddircost'>$chrgddircost_dbval</td>";
                //chrged common cost
                $chrgdcomcost='chrgdcomcost'.$n;            
                $table .="<td id=$chrgdcomcost name=$chrgdcomcost idd = $n class='chrgdcomcost'>$chrgdcomcost_dbval</td>";
                //chrged Generator cost
                $gencost='gencost'.$n;            
                $table .="<td id=$gencost name=$gencost idd = $n class='gencost'>$gencost_dbval</td>";
                
            }            
        }  
    }    
$sql="select expcomareamasid,expcomarea from mas_exp_com_area where expledgermasid ='14' and buildingmasid =$buildingmasid and active='1';"; //14= genrator ledger
$result = mysql_query($sql);
if($result != null)
{    
    $rcount = mysql_num_rows($result);
    $rcount = $rcount+$n;
    while($row = mysql_fetch_assoc($result))
    {        
        
        
        // trans_gen_det
        $commonkw_dbval=0;
        
        $totalkwcom_dbval=0;
        $totalkvacom_dbval=0;
        $totalkvapccom_dbval=0;
        
        $chrgddircostcom_dbval=0;
        $chrgdcomcostcom_dbval=0;
        $gencostcom_dbval=0;
        
        $sqlgendet = "select * from trans_exp_gen_det where transexpgenmasid ='$transexpgenmasid' and expcomareamasid =".$row['expcomareamasid'].";";
        $resultgendet = mysql_query($sqlgendet);
        if($resultgendet !=null)
        {
            $rowgendet = mysql_fetch_assoc($resultgendet);
            $commonkw_dbval= $rowgendet['commonkw'];                       
            if($rowtotal ==$n)
            {
                $totalkwcom_dbval=$rowgendet['totalkw'];
                
                $totalkvacom_dbval=$rowgendet['totalkva'];
                $totalkva_dbval_t +=$totalkvacom_dbval;
                
                $totalkvapccom_dbval=$rowgendet['totalkvapc'];
                $totalkvapc_dbval_t +=$totalkvapccom_dbval;
                
                $chrgddircostcom_dbval=$rowgendet['chrgddircost'];
                $chrgdcomcostcom_dbval=$rowgendet['chrgdcomcost'];
                $gencostcom_dbval=$rowgendet['gencost'];
            }
        }   
        
        $table .="<tr>";
            $table .="<td align='center'>".$n++.".</td>";
            $expcomareamasid = "expcomareamasid".$n;
            $table .="<td>".$row['expcomarea']."<input type='hidden' id='$expcomareamasid' name='$expcomareamasid' value=".$row['expcomareamasid']." /></td>";
            $table .="<td colspan='4' align='right'>Commona Area KW</td>";
            //Common Area KW
            $commonkw='commonkw'.$n;
            $hid_totalkw='hid_totalkwcom'.$n;
            $hid_totalkva='hid_totalkvacom'.$n;
            $hid_netkva='hid_netkvacom'.$n;
            
            $hid_chrgddircost='hid_chrgddircostcom'.$n;
            $hid_chrgdcomcost='hid_chrgdcomcostcom'.$n;
            $hid_gencost='hid_gencostcom'.$n;
            
            $table .="<td>$commonkw_dbval</td>";
            //Total KW
            $totalkwcom='totalkwcom'.$n;
            $table .="<td id=$totalkwcom name=$totalkwcom idd = $n class='totalkw'>$totalkwcom_dbval</td>";
            //Total KVA
            $totalkvacom='totalkvacom'.$n;            
            $table .="<td id=$totalkvacom name=$totalkvacom idd = $n class='totalkva'>$totalkvacom_dbval</td>";                
            //KVA %
            $netkvacom='netkvacom'.$n;            
            $table .="<td id=$netkvacom name=$netkvacom idd = $n class='totalkvapc'>$totalkvapccom_dbval%</td>";
            
                //chrged direcot generator cost
                $chrgddircost='chrgddircostcom'.$n;            
                $table .="<td id=$chrgddircost name=$chrgddircost idd = $n class='chrgddircost'>$chrgddircostcom_dbval</td>";
                //chrged common cost
                $chrgdcomcost='chrgdcomcostcom'.$n;            
                $table .="<td id=$chrgdcomcost name=$chrgdcomcost idd = $n class='chrgdcomcost'>$chrgdcomcostcom_dbval</td>";
                //chrged Generator cost
                $gencost='gencostcom'.$n;            
                $table .="<td id=$gencost name=$gencost idd = $n class='gencost'>$gencostcom_dbval</td>";
        $table .="</tr>";        
    }    
}
$table .="<tr>";
$table .="<td align='right' colspan='2'><b>GRAND TOTAL:</b></td>";
$table .="<td align='right' id='totalsqrft' name='totalsqrft' ></td>";//total sqrft
$table .="<td align='right'><input type='hidden' id='lastrow' name='lastrow' value='$n' size='50px;'/></td>";//total months chrged
$table .="<td align='right' id='totalsqrftmc' name='totalsqrftmc'></td>";//total sqrft charged
$table .="<td align='right'>$connkw_dbval_t</td>";//connected kw
$table .="<td align='right'>$addikw_dbval_t</td>";//additional kw chrged
$table .="<td align='right' id='totalkw' name='totalkw'>$totalkw_dbval_t</td>";//total KW
$table .="<td align='right' id='totalkva' name='totalkva'>$totalkva_dbval_t</td>";//total KVA
$table .="<td align='right' id='totalkvapc' name='totalkvapc'>$totalkvapc_dbval_t%</td>";//total KVA %

$table .="<td align='right' id='chrgddircost' name='chrgddircost'>$chrgddircost_dbval_t</td>";//total chrd cost
$table .="<td align='right' id='chrgdcomcost' name='chrgdcomcost'>$chrgdcomcost_dbval_t</td>";//total common cost
$table .="<td align='right' id='gencost' name='gencost'>$gencost_dbval_t</td>";//total gen cost

$table .="</tr>";
$table .="</table></p>";
$custom = array('msg'=>$table,'s'=>'Success');
$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
}
catch (Exception $err)
{
    $custom = array(
                'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>