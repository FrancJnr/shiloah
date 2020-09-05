<?php
include('../config.php');
session_start();
//$columns = $columns-2;// enable to avoid modifiedby , modifieddatetime columns
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** Include PHPExcel */
require_once '../Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Pechimuthu Prabhu")
                ->setLastModifiedBy("Pechimuthu Prabhu")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Status Document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {
//	    //$k = preg_replace('/[^a-z]/i', '', $k); 
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
    $water_total=0;$gen_total=0;
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


    ////$transexpmasid = $_GET['transexpmasid'];
    
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

  
    
//expgroupmasid ='1' common cost id in mas_exp_group
$commoncosttotal=0;
$commoncost=0;
$sql = "select sum(b.amount) as 'commoncost' from trans_exp_mas a
        inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
        inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
        inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
        where c.expgroupmasid ='1' and a.transexpmasid in ($transexpmasid);";
$result = mysql_query($sql);
if($result !=null)
{
    while($rowcc = mysql_fetch_assoc($result))
    {
        $commoncost += $rowcc['commoncost'];    
    }
    
}

//expgroupmasid ='2' management cost id in mas_exp_group
$managementcost=0;
$managementcosttotal=0;
$sql = "select sum(b.amount) as 'managementcost' from trans_exp_mas a
        inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
        inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
        inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
        where c.expgroupmasid ='2' and a.transexpmasid in($transexpmasid);";
$result = mysql_query($sql);
if($result !=null)
{
    while($rowmc = mysql_fetch_assoc($result))
    {
        $managementcost += $rowmc['managementcost'];    
    }
    
}

//expgroupmasid ='3' direct cost id in mas_exp_group
$directcost=0;
$directcosttotal=0;

$sql = "select sum(b.amount) as 'directcost' from trans_exp_mas a
        inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
        inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
        inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
        where c.expgroupmasid ='3' and a.transexpmasid in($transexpmasid);";
$result = mysql_query($sql);
if($result !=null)
{
    while($rowmc = mysql_fetch_assoc($result))
    {
        $directcost += $rowmc['directcost'];    
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

//expledgermasid ='15' water expledgermasid id
$direct_water_cost=0;
$sql = "select sum(b.amount) as 'directwatercost' from trans_exp_mas a
        inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
        inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
        inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
        where c.expledgermasid ='15' and a.transexpmasid in($transexpmasid);";
$result = mysql_query($sql);
if($result !=null)
{
    while($rowmc = mysql_fetch_assoc($result))
    {
        $direct_water_cost += $rowmc['directwatercost'];    
    }
    
}

//$directcosttotal=0;
$totalcosttotal=0;
$scdeposittotal=0;
$nettotal=0;


// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0);
PHPExcel_Calculation::getInstance()->cyclicFormulaCount = 1;
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(10);
$objPHPExcel->getActiveSheet()->setPrintGridlines(TRUE);

$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'Shiloah Investments Ltd - Water Cost')
            ->setCellValue('E1', 'transexpmasid')
            ->setCellValue('F1', $transexpmasid);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E1:F1')
            ->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
            


$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Building : '.$buildingname);
$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Period   : '.$firstdate);

//$objPHPExcel->getActiveSheet()->getStyle('D:H')->getNumberFormat()->setFormatCode('0.00');
//$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode('0.00%');

$objPHPExcel->getActiveSheet()
            ->setCellValue('A4', 'Sno')
            ->setCellValue('B4', 'Tenant ')
            ->setCellValue('C4', 'Sqrft ')            
            ->setCellValue('D4', 'Months ')
            ->setCellValue('E4', 'Total Sq ')
            ->setCellValue('F4', 'Submeter No ')
            ->setCellValue('G4', 'Installed Date ');
            $nxtcol = 'H';
            $j = 5; // starts from 5th row
            $st=5;
//$objPHPExcel->getActiveSheet()->getStyle('F4:I4')
//            ->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A4:S4')->getFont()->setBold(true);
$td="";
$table ="<p class='printable'><table>";
$table .="<tr><th colspan='18'>Service Charge Deposit Report</th></tr>";
$table .="<tr><td colspan='18'><b> $buildingname <br><br> ".$firstdate."</b></td></tr>";
$table .="<tr>";
$table .="<th>S.No</th>";
$table .="<th>Tenant</th>";
$table .="<th>Shopcode</th>";
$table .="<th>Sqrft</th>";
$table .="<th>Mnths</th>";
$table .="<th>Total Sqrft</th>";
$table .="<th>Common Cost</th>";
$table .="<th>Management Cost</th>";
$table .="<th>Water Cost</th>";
$table .="<th>Gen Cost</th>";
$table .="<th>Direct Cost</th>";
$table .="<th>Total Cost</th>";
$table .="<th>SC Deposit</th>";
$table .="<th>Net Cost</th>";
//$table .="<th>Invoice %</th>";
$st1= $nxtcol;
$st2= $nxtcol;
//for($i=0;$i<=$months;$i++)
//{
//    $d2 = date("M-Y", strtotime(date("Y-m", strtotime($d1)) . " + $i Months")); 
//    $table .="<th class='rowth'>$d2</th>";
//    // next column
//    $objPHPExcel->getActiveSheet()->setCellValue($nxtcol.'4', $d2);    
//    $nxtcol++;
//}

for($i=0;$i<$months;$i++)
{
    $st2++;
}
$objPHPExcel->getActiveSheet()->getStyle('F3:'.$st2.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F3:'.$st2.'3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells('F3:'.$st2.'3');
$objPHPExcel->getActiveSheet()->setCellValue('F3', 'Submeter Reading');

$tenantusage = $nxtcol;
$objPHPExcel->getActiveSheet()->setCellValue($nxtcol.'4', 'Tenant Usage');
$objPHPExcel->getActiveSheet()->getStyle($nxtcol.'4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$nxtcol++;
$commusage = $nxtcol;
$objPHPExcel->getActiveSheet()->setCellValue($nxtcol.'4', 'Common Usage');
$objPHPExcel->getActiveSheet()->getStyle($nxtcol.'4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

$objPHPExcel->getActiveSheet()->getStyle($tenantusage.'3:'.$nxtcol.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($tenantusage.'3:'.$nxtcol.'3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells($tenantusage.'3:'.$nxtcol.'3');
$objPHPExcel->getActiveSheet()->setCellValue($tenantusage.'3', 'Total Submeter Reading');

$nxtcol++;

$sccost = $nxtcol;
$rate = $nxtcol;

$objPHPExcel->getActiveSheet()->setCellValue($nxtcol.'4', 'Rate');
$nxtcol++;
$objPHPExcel->getActiveSheet()->setCellValue($nxtcol.'4', 'Submeter Cost');
$objPHPExcel->getActiveSheet()->getStyle($nxtcol.'4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

//$objPHPExcel->getActiveSheet()->getStyle($sccost.'3:'.$nxtcol.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle($sccost.'3:'.$nxtcol.'3')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->mergeCells($sccost.'3:'.$nxtcol.'3');
//$objPHPExcel->getActiveSheet()->setCellValue($sccost.'3', 'Water Cost');

$sccost++;
$table .="</tr>";
    $n =1;$cnt=0;
    $sqrfttotal=0;$grandmnths=0;
    $grandtsqrft=0;$grandtotal=0;$rowtotal1[0]=0;$rowtotal2[0]=0;$rowtotal3[0]=0;$tk="";
    $sql1 = "select c.tenantmasid,c.leasename,c.tradingname, a.grouptenantmasid,d.size,d.shopcode
                from advance_rent a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_age f on f.agemasid = c.agemasidrc 
                where d.buildingmasid='$buildingmasid' 
            UNION
            select c1.tenantmasid,c1.leasename,c1.tradingname, a1.grouptenantmasid,d1.size,d1.shopcode
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
            $mnthschrgd1=0;$td="";$totalsqrft=0;$sc=0;$sc1="";$linetotal=0;$s=0;
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
            $tenantmasid = $row1['tenantmasid'];            
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
            $shopcode = $row1['shopcode'];
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
                                    if(($row3['md'] >= 1) and ($row3['fd'] >=1))
                                    {                                                                    
                                        $sc1 = $row3['mnthlyscprev'];
                                        if ($sc1>0)
                                        $mnthschrgd1 ++;                                    
                                        $linetotal +=$sc1;
                                    }
                                }
                            }
                            //$td .="<td class='row".$s."' align='right'>".number_format($sc1, 0, '.', ',')."</td>";
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
                            
                            //if($grouptenantmasid == '261')
                            //$td .="<td class='row".$s."' align='right'>$sc</td>";
                            
                            // split month value
                            for($x=0;$x<$md;$x++)
                            {                                                                                                
                                if($f<=$months)
                                {                                    
                                    $mnthschrgd1 ++;                                    
                                    $cls = "row".$s;
                                    $linetotal +=$sc;
                                    //$td .="<td class='row".$s."' align='right'>".number_format($sc, 0, '.', ',')."</td>";
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
                    //$td .="<td class='row".$s."' align='right'>null</td>";
                }                            
            }            
            if($mnthschrgd1 >0)
            {                                
                $totalsqrft = $mnthschrgd1*$size;   
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
            $sqltenant = "select leasename,tradingname from mas_tenant where tenantmasid ='$tenantmasid'
                            union
                           select leasename,tradingname from rec_tenant where tenantmasid ='$tenantmasid'";
            $leasename="-";
            $resulttenant = mysql_query($sqltenant);
            if($resulttenant)
            {
                while ($rowt = mysql_fetch_assoc($resulttenant))
                {
                    if($rowt['tradingname'] =="")
                        $leasename = $rowt['leasename'];
                    else
                        $leasename = $rowt['leasename'] ." T/A ".$rowt['tradingname'];   
                }                
            }
            $shopcode = $row1a['shopcode'];    
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
                                    if(($row3['md'] > 1))
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
                            
                            //if($grouptenantmasid == '261')
                            //$td .="<td class='row".$s."' align='right'>$sc</td>";
                            
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
                $objPHPExcel->getActiveSheet()
                            ->setCellValue('A'.$j, $n)
                            ->setCellValue('B'.$j, $leasename)                            
                            ->setCellValue('C'.$j, number_format($size, 0, '.', ','))
                            ->setCellValue('D'.$j, $mnthschrgd1)
                            ->setCellValue('E'.$j, $totalsqrft)
                            ->setCellValue($tenantusage.$j, '=SUM('.$st1.$j.':'.$st2.$j.')')                            
                            ->setCellValue($sccost.$j, '=('.$tenantusage.$j.'*'.$rate.$j.')');                
                $j++;    
                $table .="<tr>";
                $table .="<td align='center'>".$n++.".</td>";
                $table .="<td>$leasename</td>";
                $table .="<td>$shopcode</td>";
                $table .="<td>$size</td>";
                $table .="<td>$mnthschrgd1</td>";
                $table .="<td>$totalsqrft</td>";
                $sqrfttotal +=$size;
                $grandmnths +=$mnthschrgd1;
                ////$table .="<td>".$mnthschrgd1."*".$size."=".$totalsqrft."</td>";
                
                ////$custom = array('msg'=> $commoncost." - ".$grandtsqrft."-"  ,'s'=>'error');
                ////$response_array[] = $custom;
                ////echo '{"error":'.json_encode($response_array).'}';
                ////exit;
                $capp =0;
                if(($commoncost>0) and ($grandtsqrft >0)){
                    $capp = $commoncost*$totalsqrft/$grandtsqrft;
                    $capp = round($capp,2,PHP_ROUND_HALF_EVEN);
                    //$table .="<td>".$totalsqrft."*".$commoncost."/".$grandtsqrft."</td>";
                    $table .="<td align='right'>".number_format($capp, 0, '.', ',')."</td>";
                }
                else{
                    $table .="<td>0</td>";
                }
                $mapp=0;
                if(($managementcost>0) and ($grandtsqrft >0)){
                    $mapp = $managementcost*$totalsqrft/$grandtsqrft;
                    $mapp = round($mapp,2,PHP_ROUND_HALF_EVEN);
                    $table .="<td align='right'>".number_format($mapp, 0, '.', ',')."</td>";
                }
                else{
                    $table .="<td>0</td>";
                }
                
                //gencost
                $boo_gen=false;
                $gencost=0;
                $sqlgenmas = "select * from trans_exp_gen_mas where transexpmasid ='$transexpmasid';";
                $resultgenmas = mysql_query($sqlgenmas);
                if($resultgenmas !=null)
                {
                    $rcount = mysql_num_rows($resultgenmas);
                    if($rcount <=0)                    
                    $boo_gen=true;
                }                
                $sqlgen = "select b.gencost from trans_exp_gen_mas a
                        inner join trans_exp_gen_det b on b.transexpgenmasid = a.transexpgenmasid
                        where a.transexpmasid in($transexpmasid) and b.grouptenantmasid='$grouptenantmasid' limit 1;";
                $resultgen = mysql_query($sqlgen);
                if($resultgen !=null)
                {
                    while($rowgen = mysql_fetch_assoc($resultgen))
                    {
                        $gencost +=$rowgen['gencost'];                        
                    }                    
                    
                }
                if($boo_gen == true)// if take from direct cost without apportionment of generator
                {                    
                    //if($grandtsqrft>0)
                    //$gencost = $direct_gen_cost*$totalsqrft/$grandtsqrft;
                    //$gencost = round($gencost,0,PHP_ROUND_HALF_EVEN);                                                                    
                }
    
    
                //water cost
                $watercost=0;
                $boo_water = false;
                $sqlwatermas = "select * from trans_exp_water_mas where transexpmasid ='$transexpmasid';";
                $resultwatermas = mysql_query($sqlwatermas);
                if($resultwatermas !=null)
                {
                    $rcount = mysql_num_rows($resultwatermas);
                    if($rcount <=0)
                    $boo_water = true;                   
                }
                $sqlwater = "select b.watercost from trans_exp_water_mas a
                        inner join trans_exp_water_det b on b.transexpwatermasid = a.transexpwatermasid
                        where a.transexpmasid in($transexpmasid) and b.grouptenantmasid='$grouptenantmasid' limit 1;";
                $resultwater = mysql_query($sqlwater);
                if($resultwater !=null)
                {
                    while($rowwater = mysql_fetch_assoc($resultwater))
                    {
                        $watercost +=$rowwater['watercost'];
                    }
                }
                if($boo_water == true)// if take from direct cost without apportionment of generator
                {                    
                    if($grandtsqrft>0)
                    $watercost = $direct_water_cost*$totalsqrft/$grandtsqrft;
                    //$watercost = round($watercost,0,PHP_ROUND_HALF_EVEN);
                    //$watercost = round($watercost);                                                                 
                }
                
                $dapp =  $gencost+$watercost;                
                //$table .="<td>".$gencost."+".$watercost."</td>";                
                $table .="<td>".number_format($watercost, 0, '.', ',')."</td>";
                $table .="<td>".number_format($gencost, 0, '.', ',')."</td>";
                $table .="<td align='right'>".number_format($dapp, 0, '.', ',')."</td>";
                
                $totalcost = $capp+$mapp+$dapp;
                $table .="<td>".round($totalcost)."</td>";
                
                $table .="<td>".number_format($sc, 0, '.', ',')."</td>";
                //$sc1 .="= ".$sc;
                //$table .="<td>$sc1</td>";
                $netcost =$totalcost -$sc;
                $table .="<td>".number_format($netcost, 0, '.', ',')."</td>";
                //invoice %
                //$table .="<td><input type='text' name='$grouptenantmasid' id='$grouptenantmasid' value='0' style='width:36%;'/>&nbsp;<b>%</td>";
                $table .="</tr>";                
                $commoncosttotal +=$capp;
                $managementcosttotal +=$mapp;
                $directcosttotal +=$dapp;
                $totalcosttotal +=$totalcost;
                $scdeposittotal +=$sc;
                $nettotal +=$netcost;
                
                $water_total +=$watercost;
                $gen_total +=$gencost;
            }           
        }       
    }
$v=0;
$table .="<tr>";
$table .="<td align='right' colspan='3'><b>GRAND TOTAL:</b></td>";
$table .="<td align='right'><b>".number_format($sqrfttotal, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($grandmnths, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($grandtsqrft, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($commoncosttotal, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($managementcosttotal, 0, '.', ',')."</b></td>";

$table .="<td align='right'><b>".number_format($water_total, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($gen_total, 0, '.', ',')."</b></td>";

$table .="<td align='right'><b>".number_format($directcosttotal, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($totalcosttotal, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($scdeposittotal, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($nettotal, 0, '.', ',')."</b></td>";
$table .="</tr>";
$table .="</table></p>";

$f = $j-1;
$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$j, '')
            ->setCellValue('B'.$j, 'GRAND TOTAL')
            ->setCellValue('C'.$j, '')
            ->setCellValue('D'.$j, '')
            ->setCellValue('E'.$j, number_format($grandtsqrft, 0, '.', ','));
for($i=0;$i<=$months;$i++)
{
    $objPHPExcel->getActiveSheet()
                ->setCellValue($st1.$j, '=SUM('.$st1.'5:'.$st1.$f.')');
    $st1++;                
}
$objPHPExcel->getActiveSheet()->setCellValue($st1.$j, '=SUM('.$st1.'5:'.$st1.$f.')');// tenant usage
$st1++;
$objPHPExcel->getActiveSheet()->setCellValue($st1.$j, '=SUM('.$st1.'5:'.$st1.$f.')');// common area usage
$st1++;
$st1++;
$objPHPExcel->getActiveSheet()->setCellValue($st1.$j, '=SUM('.$st1.'5:'.$st1.$f.')');// submeter cost


$end =$j;

for ($col = 'A'; $col != $nxtcol; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);        
}
//$objPHPExcel->getActiveSheet()->setTitle("Water_Cost_$buildingname");
//$objPHPExcel->setActiveSheetIndex(0);
//$filename = 'Water_Cost_'.$shortname;
//
//ob_start();
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
//$data = ob_get_contents();
//ob_end_clean();
//$message="Water Cost";
////**************************** EMAIL *************************//
//
//ini_set('SMTP','192.168.0.1');// DEFINE SMTP MAIL SERVER
//require_once('../PHPMailer/class.phpmailer.php');
//$mail = new PHPMailer(); // defaults to using php "mail()"
//$mail->SetFrom('info@shiloahmega.com', 'PMS Admin');
//$mail->AddReplyTo('info@shiloahmega.com', 'PMS Admin');
//
//$address = "juma@shiloahmega.com";
//$mail->AddAddress($address, "Prabhu");
//
////$recipients = array(   
////   'arulraj@shiloahmega.com' => 'Arul Raj'   
////);
////foreach($recipients as $email => $name)
////{
////   $mail->AddCC($email, $name);
////}
//
//$mail->Subject    = "Water Cost $buildingname  $firstdate";
//$mail->MsgHTML($message);
//$mail->AddStringAttachment($data, $filename. '.xlsx');
//
//if(!$mail->Send()) {
//   //echo "Mailer Error: " . $mail->ErrorInfo;
//  $custom = array('divContent'=>"Mailer Error: " . $mail->ErrorInfo,'s'=>'error');
//} else {
//    $custom = array('divContent'=>"<font color=green> Success: Water cost Mail Sent with attachment !!!</font>",'s'=>'Success');
//}
//$response_array[] = $custom;
//echo '{"error":'.json_encode($response_array).'}';
//exit;
////************************** EMAIL *************************//

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