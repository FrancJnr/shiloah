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
    $transexpmasid = $_GET['transexpmasid'];
    $firstdate ="";
    $fromdt="";
    $todt="";
        
    
    $sql="select buildingmasid,fromdate, todate from trans_exp_mas where transexpmasid in ($transexpmasid) and active='1';";
    $result = mysql_query($sql);
    if($result != null)
    {
        while($row = mysql_fetch_assoc($result))
        {
            $buildingmasid = $row['buildingmasid'];
            $fromdt = $row['fromdate'];
            $todt   = $row['todate'];
            $firstdate = date('M-Y', strtotime($row['fromdate'])) ." to ".date('M-Y', strtotime($row['todate']));
        }    
    }
    
    //expledgermasid ='14' expledgermasid exp id
    $direct_gen_cost=0;
    $sql = "select sum(b.amount) as 'directgencost' from trans_exp_mas a
            inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
            inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
            inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
            where c.expledgermasid ='14' and a.transexpmasid in($transexpmasid);";
    $result = mysql_query($sql);
    if($result !=null)
    {
        while($rowmc = mysql_fetch_assoc($result))
        {
            $direct_gen_cost += $rowmc['directgencost'];    
        }
        
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
    
    $gencost_dbval=0;
    $gencost_t=0;
                
    $table ="<p class='printable'><table class='table6'>";
    $table .="<tr><th colspan='18'>Genarator Apportionment</th></tr>";
    $table .="<tr><td colspan='18'><b> $buildingname <br><br> ".$firstdate."</b></td></tr>";
    $table .="<tr>
                <td colspan='2'>DIRECT GENCOST:</td><td>$direct_gen_cost</td>                
                <td colspan='9'></td>
            </tr>";
    $table .="<tr>";
    $table .="<th>S.No</th>";
    $table .="<th>Tenant</th>";
    $table .="<th>Sqrft</th>";
    $table .="<th>Mnths</th>";
    $table .="<th>Total Sqrft</th>";
    $table .="<th>Chrgd Gen Cost</th>";
    $table .="</tr>";

   

    $n =1;$cnt=0;
    $grandtsqrft=0;$grandtotal=0;$rowtotal1[0]=0;$rowtotal2[0]=0;$rowtotal3[0]=0;$tk="";
    $sql1 = "select c.leasename,c.tradingname, a.grouptenantmasid,d.size
                from advance_rent a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_age f on f.agemasid = c.agemasidrc 
                where d.buildingmasid='$buildingmasid' 
            UNION
            select c1.leasename,c1.tradingname, a1.grouptenantmasid,d1.size
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
    $result1b = mysql_query($sql1);
    $totalsqrft_g=0;
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
           if($row1a['tradingname'] =="")
                $leasename = $row1a['leasename'];
            else
                $leasename = $row1a['leasename'] ." T/A ".$row1a['tradingname'];
                
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
                            $sql3 ="select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, date_format(fromdate,'%d') as fd, round(sc/@s) as mnthlyscprev
                                    from advance_rent
                                    where grouptenantmasid = '$grouptenantmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m')
                                    union
                                    select @s:=TIMESTAMPDIFF(MONTH, fromdate, todate)+1 as md, date_format(fromdate,'%d') as fd,round(sc/@s) as mnthlyscprev
                                    from invoice
                                    where grouptenantmasid = '$grouptenantmasid' and '$d2' between date_format(fromdate,'%Y-%m') and  date_format(todate,'%Y-%m') limit 1;";
                            $result3 = mysql_query($sql3);
                            if($result3 !=null)
                            {
                                $rcount3 = mysql_num_rows($result3);
                                if($rcount3 >0)
                                {
                                    $row3 = mysql_fetch_assoc($result3);
                                    if(($row3['md'] >= 1) and ($row3['fd'] ==1))
                                    {
                                        $sc1 = $row3['mnthlyscprev'];
                                        if ($sc1>0)
                                        $mnthschrgd1 ++;                                    
                                        $linetotal +=$sc1;
                                    }
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
                                    $mnthschrgd1 ++;                                    
                                    $cls = "row".$s;
                                    $linetotal +=$sc;
                                    $td .="<td class='row".$s."' align='right'>".number_format($sc, 0, '.', ',')."</td>";
                                    $rowtotal2[$s] = $s;                            
                                    $s++;
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
                $totalsqrft = $mnthschrgd1*$size;
                $totalsqrft_g +=$totalsqrft;
            }            
        }
        
        // display tenants
        while($row1a = mysql_fetch_assoc($result1b)) // no of tenants available for the building
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
           if($row1a['tradingname'] =="")
                $leasename = $row1a['leasename'];
            else
                $leasename = $row1a['leasename'] ." T/A ".$row1a['tradingname'];
                
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
                                    $mnthschrgd1 ++;                                    
                                    $cls = "row".$s;
                                    $linetotal +=$sc;
                                    $td .="<td class='row".$s."' align='right'>".number_format($sc, 0, '.', ',')."</td>";
                                    $rowtotal2[$s] = $s;                            
                                    $s++;
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
                
                //chrged Generator cost
                $gcost = $direct_gen_cost*$totalsqrft/$totalsqrft_g;
                $gencost='gencost'.$n;            
                $table .="<td id=$gencost name=$gencost idd = $n class='gencost'>".number_format($gcost, 0, '.', ',')."</td>";
                $gencost_t +=$gcost;
            }            
        }  
    }    
$table .="<tr>";
$table .="<td align='right' colspan='2'><b>GRAND TOTAL:</b></td>";
$table .="<td align='right' id='totalsqrft' name='totalsqrft' ></td>";//total sqrft
$table .="<td align='right'><input type='hidden' id='lastrow' name='lastrow' value='$n' size='50px;'/></td>";//total months chrged
$table .="<td align='right' id='totalsqrftmc' name='totalsqrftmc'></td>";//total sqrft charged
$table .="<td align='right' id='gencost' name='gencost'>$gencost_t</td>";//total gen cost

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