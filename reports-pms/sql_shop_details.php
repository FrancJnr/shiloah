<?php
if($sqlid=='reg')
{
    $fromdate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($fromdate)) . " + $i Months"));    
}
else 
{    
    $fromdate = date("Y-m-d", strtotime($fromdate));    
}

$sql3=" select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,	
        DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.age as 'oldcycle',e.shortdesc,
        round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
        DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
        @t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
        DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
        DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
        DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc
        from  trans_offerletter a
        inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
        inner join trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
        inner join mas_tenant c on c.tenantmasid = a.tenantmasid
        inner join mas_age d on d.agemasid = c.agemasidlt
        inner join mas_age e on e.agemasid = c.agemasidrc
        inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
        where a.tenantmasid =$tenantmasid 
        and '$fromdate' between b.fromdate and b.todate
        and '$fromdate' between f.fromdate and f.todate
        and c.active ='1'
        and a.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by a.offerlettermasid;";
$result3 = mysql_query($sql3);
if($result3 !=null)
{
    $rcount = mysql_num_rows($result3);
    if($rcount ==0) 
    {                    
        $sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,
                DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.shortdesc,
                round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
                DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
                @t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
                DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
                DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
                DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc,e1.age as 'oldcycle'
                from  rec_trans_offerletter a
                inner join rec_trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
                inner join rec_trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
                inner join mas_tenant c on c.tenantmasid = a.tenantmasid
                inner join mas_age d on d.agemasid = c.agemasidlt
                inner join mas_age e on e.agemasid = c.agemasidrc
                inner join mas_tenant c1 on c1.tenantmasid = c.tenantmasid 
                inner join mas_age e1 on e1.agemasid = c1.agemasidrc
                inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
                where a.tenantmasid =$tenantmasid 
                and '$fromdate' between b.fromdate and b.todate
                and '$fromdate' between f.fromdate and f.todate
                and c.active ='1' group by a.offerlettermasid;";
        $result3 = mysql_query($sql3);
        if($result3 !=null)
        {
            $rcount = mysql_num_rows($result3);
            if($rcount ==0) 
            {                    
                $sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,
                        DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.shortdesc,
                        round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
                        DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
                        @t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
                        DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
                        DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
                        DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc,e1.age as 'oldcycle'
                        from  trans_offerletter a
                        inner join trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
                        inner join trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
                        inner join rec_tenant c on c.tenantmasid = a.tenantmasid
                        inner join mas_age d on d.agemasid = c.agemasidlt
                        inner join mas_age e on e.agemasid = c.agemasidrc
                        inner join mas_tenant c1 on c1.tenantmasid = c.tenantmasid 
                        inner join mas_age e1 on e1.agemasid = c1.agemasidrc
                        inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
                        where a.tenantmasid =$tenantmasid 
                        and '$fromdate' between b.fromdate and b.todate
                        and '$fromdate' between f.fromdate and f.todate
                        and c.active ='1'
                        and a.tenantmasid not in (select tenantmasid from rec_trans_offerletter) group by a.offerlettermasid;";
                $result3 = mysql_query($sql3);
                if($result3 !=null)
                {
                    $rcount = mysql_num_rows($result3);
                    if($rcount ==0) 
                    {                    
                        $sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,b.fromdate,	c.leasename,c.tradingname,s.shoptype,		
                                DATE_FORMAT('$fromdate','%b-%d') as invperiod,e.age as 'rentcycle',e.shortdesc,
                                round(DATEDIFF('$fromdate',b.fromdate) / 31) as monthDiff,
                                DATE_ADD('$fromdate',INTERVAL 1 MONTH) as 'cdate',
                                @t2:= DATE_ADD(c.doc,interval @t1:=d.age year) as ag,									
                                DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=d.age year), '%d-%m-%Y' ) AS bg,		   
                                DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
                                DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc,e1.age as 'oldcycle'
                                from  rec_trans_offerletter a
                                inner join rec_trans_offerletter_rent b on b.offerlettermasid = a.offerlettermasid
                                inner join rec_trans_offerletter_sc f on f.offerlettermasid = a.offerlettermasid
                                inner join rec_tenant c on c.tenantmasid = a.tenantmasid
                                inner join mas_age d on d.agemasid = c.agemasidlt
                                inner join mas_age e on e.agemasid = c.agemasidrc
                                inner join mas_tenant c1 on c1.tenantmasid = c.tenantmasid 
                                inner join mas_age e1 on e1.agemasid = c1.agemasidrc
                                inner join mas_shoptype s on s.shoptypemasid = c.shoptypemasid
                                where a.tenantmasid =$tenantmasid 
                                and '$fromdate' between b.fromdate and b.todate
                                and '$fromdate' between f.fromdate and f.todate
                                and c.active ='1' group by a.offerlettermasid;";
                    }
                }
            }
        }
    }
}

$result3 = mysql_query($sql3);
$rent =0;$sc=0;
while($row3 = mysql_fetch_assoc($result3))
{
    $rentcycle = strtolower($row3['rentcycle']);
    $period  = strtolower($row3['shortdesc'])."<br>";
    $oldcycle = $row3['oldcycle'];
    if(strtolower($row3['oldcycle']) == 'per quarter')            
    {
        $row3['rent'] = $row3['rent'] /3;
        $row3['sc'] = $row3['sc'] /3;
    }
    else if(strtolower($row3['oldcycle']) == 'per half')            
    {
        $row3['rent'] = $row3['rent'] /6;
        $row3['sc'] = $row3['sc'] /6;
    }
    else if(strtolower($row3['oldcycle']) == 'per year')            
    {
        $row3['rent'] = $row3['rent'] /12;
        $row3['sc'] = $row3['sc'] /12;
    }			
   if($rentcycle == 'per month')            
   {                
       $rm =1;$fv=1;
       $monthdiff = fmod($row3['monthDiff'],0);               
       $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
       $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
       $d1 = $d1 ."-".$m1;
       $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 1 Months"));
       $d2 = date('d-m-Y',strtotime("-1 days $d2"));
       $rent +=$row3['rent'];
       $sc +=$row3['sc'];
    
   }
   else if($rentcycle == 'per quarter')
   {			
       $rm =3;$fv=3;
       $monthdiff = fmod($row3['monthDiff'],3);               
       $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
       $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
       $d1 = $d1 ."-".$m1;
       $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 3 Months"));
       $d2 = date('d-m-Y',strtotime("-1 days $d2"));
       $rent +=$row3['rent'];
       $sc +=$row3['sc'];
   }
   else if($rentcycle == 'per half')
   {
       $rm =6;$fv=6;
       $monthdiff = fmod($row3['monthDiff'],6);                 
       $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
       $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
       $d1 = $d1 ."-".$m1;
       $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 6 Months"));
       $d2 = date('d-m-Y',strtotime("-1 days $d2"));
       $rent +=$row3['rent'];
       $sc +=$row3['sc'];
   }
   else if($rentcycle == 'per year')
   {
       $rm =12;$fv=12;
       $monthdiff = fmod($row3['monthDiff'],12);                 
       $d1= date("d", strtotime(date("d-m-Y", strtotime($row3['fromdate'])) . " + 0 Months"));
       $m1= date("m-Y", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Months"));
       $d1 = $d1 ."-".$m1;
       $d2 = date("d-m-Y", strtotime(date("d-m-Y", strtotime($d1)) . " + 12 Months"));
       $d2 = date('d-m-Y',strtotime("-1 days $d2"));       
       $rent +=$row3['rent'];
       $sc +=$row3['sc'];
   }     
    
    $invperiod = date('d-m-Y', strtotime($row3['fromdate']));
}
$rt_rent=0;$rt_sc=0;

if($rent >0)
{
    $renttd .="<td align='right'>".number_format($rent, 0, '.', ',')."</td>";
    //$renttd .="<td align='right'>$rentcycle</td>";
    if($size >0)
        $rt_rent = $rent/$size;
    $renttd .="<td align='right'>".number_format($rt_rent, 2, '.', ',')."</td>";
    
    $renttd .="<td align='right'>".number_format($sc, 0, '.', ',')."</td>";
    if($size >0)			    
        $rt_sc = $sc/$size;							    			    
    $renttd .="<td align='right'>".number_format($rt_sc, 2, '.', ',')."</td>";
}
else
{
    $renttd .="<td align='center' colspan='4'>Effective from :$doc1</td>";
}
    $renttd .="<td align='center'>$expdt1</td>";