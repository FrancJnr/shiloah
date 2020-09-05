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
//$custom = array('result'=> $sqlArray ,'s'=>'error');
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

$transexpmasid = $_GET['transexpmasid'];
$sql="select fromdate, todate from trans_exp_mas where transexpmasid='$transexpmasid' and active='1';";
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
$months = 1;

while (strtotime('+1 MONTH', $date1) < $date2) {
    $months++;
    $date1 = strtotime('+1 MONTH', $date1);
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

//expgroupmasid ='1' common cost cost id
$commoncosttotal=0;
$commoncost=0;
$sql = "select sum(b.amount) as 'commoncost' from trans_exp_mas a
        inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
        inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
        inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
        where c.expgroupmasid ='1' and a.transexpmasid='$transexpmasid';";
$result = mysql_query($sql);
if($result !=null)
{
    $rowcc = mysql_fetch_assoc($result);    
    $commoncost= $rowcc['commoncost'];
}


//expgroupmasid ='2' management cost cost id
$managementcost=0;
$managementcosttotal=0;
$sql = "select sum(b.amount) as 'managementcost' from trans_exp_mas a
        inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
        inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
        inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
        where c.expgroupmasid ='2' and a.transexpmasid='$transexpmasid';";
$result = mysql_query($sql);
if($result !=null)
{
    $rowmc = mysql_fetch_assoc($result);    
    $managementcost= $rowmc['managementcost'];
}

        
$directcosttotal=0;
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
$table .="<th>Sqrft</th>";
$table .="<th>Mnths</th>";
$table .="<th>Total Sqrft</th>";
$table .="<th>Common Cost</th>";
$table .="<th>Management Cost</th>";
$table .="<th>Direct Cost</th>";
$table .="<th>Total Cost</th>";
$table .="<th>SC Deposit</th>";
$table .="<th>Net Cost</th>";
$table .="<th>Invoice %</th>";
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
    $grandtsqrft=0;$grandtotal=0;$rowtotal1[0]=0;$rowtotal2[0]=0;$rowtotal3[0]=0;$tk="";
    $sql1 = "select c.leasename, a.grouptenantmasid,d.size
                from advance_rent a
                inner join group_tenant_mas b on b.grouptenantmasid = a.grouptenantmasid
                inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                inner join mas_shop d on d.shopmasid = c.shopmasid
                inner join mas_building e on e.buildingmasid = d.buildingmasid
                inner join mas_age f on f.agemasid = c.agemasidrc 
                where d.buildingmasid='$buildingmasid' 
            UNION
            select c1.leasename, a1.grouptenantmasid,d1.size
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
            $mnthschrgd=0;$td="";$size="";$totalsqrft=0;$sc=0;$linetotal=0;$s=0;
            $grouptenantmasid=$row1['grouptenantmasid'];            
            $leasename = $row1['leasename'];
            $grandtd="";
            //manual invoice sc dep         
            //$sql4="" 
            for($i=0;$i<$months;$i++)// no of months charged
            {                    
                $d2 = date("Y-m", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
                
                //advance rent sc dep
                $sql2="select a.rent, a.sc,d.size,
                            date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',
                            date_format(a.todate,'%d-%m-%Y') as 'invto',                               
                            case lower(f.shortdesc)
                                    when 'mnthly' then round(a.sc/1)
                                    when 'qtrly' then round(a.sc/3)                    
                                    when 'half' then round(a.sc/6)
                                    when 'yearly' then round(a.sc/12)
                            end as 'mnthlysc',a.invoiceno
                            from advance_rent a
                            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_shop d on d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            inner join mas_age f on f.agemasid = c.agemasidrc
                            where a.grouptenantmasid='$grouptenantmasid' and
                            '$d2' between date_format(a.fromdate,'%Y-%m') and date_format(a.todate,'%Y-%m');";
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
                            case lower(f.shortdesc)
                                    when 'mnthly' then round(a.sc/1)
                                    when 'qtrly' then round(a.sc/3)                    
                                    when 'half' then round(a.sc/6)
                                    when 'yearly' then round(a.sc/12)
                            end as 'mnthlysc',a.invoiceno
                            from invoice a
                            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_shop d on d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            inner join mas_age f on f.agemasid = c.agemasidrc
                            where a.grouptenantmasid='$grouptenantmasid' and
                            '$d2' between date_format(a.fromdate,'%Y-%m') and date_format(a.todate,'%Y-%m');";
                    }
                }                
                $result2 = mysql_query($sql2);
                if($result2 !=null)
                {
                    $rcount = mysql_num_rows($result2);
                    if($rcount ==0) 
                    {                        
                            $td .="<td class='row".$s."'>-</td>";
                            $rowtotal2[$s] = $s;                            
                            $s++;
                    }
                    $k = false;
                    while($row2 = mysql_fetch_assoc($result2))
                    {                                                
                        $k=true;
                        if($row2['mnthlysc'] >0 ){                                                        
                            if($rcount >1)
                            {
                                $size +=$row2['size'];
                                $sc += $row2['mnthlysc'];
                            }
                            else
                            {
                                $size =$row2['size'];
                                $sc = $row2['mnthlysc'];
                            }
                            $mnthschrgd++;
                            $totalsqrft = $mnthschrgd*$size;                            
                        }
                        else
                        {
                            $td .="<td class='row".$s."'>0</td>";
                        }                        
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
            $mnthschrgd=0;$td="";$size="";$totalsqrft=0;$sc=0;$linetotal=0;$s=0;
            $grouptenantmasid=$row1a['grouptenantmasid'];            
            $leasename = $row1a['leasename'];
            $grandtd="";
            //manual invoice sc dep         
            //$sql4="" 
            for($i=0;$i<$months;$i++)// no of months charged
            {                    
                $d2 = date("Y-m", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));
                
                //advance rent sc dep
                $sql2="select a.rent, a.sc,d.size,
                            date_format(a.fromdate,'%d-%m-%Y') as 'invfrom',
                            date_format(a.todate,'%d-%m-%Y') as 'invto',                               
                            case lower(f.shortdesc)
                                    when 'mnthly' then round(a.sc/1)
                                    when 'qtrly' then round(a.sc/3)                    
                                    when 'half' then round(a.sc/6)
                                    when 'yearly' then round(a.sc/12)
                            end as 'mnthlysc',a.invoiceno
                            from advance_rent a
                            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_shop d on d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            inner join mas_age f on f.agemasid = c.agemasidrc
                            where a.grouptenantmasid='$grouptenantmasid' and
                            '$d2' between date_format(a.fromdate,'%Y-%m') and date_format(a.todate,'%Y-%m');";
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
                            case lower(f.shortdesc)
                                    when 'mnthly' then round(a.sc/1)
                                    when 'qtrly' then round(a.sc/3)                    
                                    when 'half' then round(a.sc/6)
                                    when 'yearly' then round(a.sc/12)
                            end as 'mnthlysc',a.invoiceno
                            from invoice a
                            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                            inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                            inner join mas_shop d on d.shopmasid = c.shopmasid
                            inner join mas_building e on e.buildingmasid = d.buildingmasid
                            inner join mas_age f on f.agemasid = c.agemasidrc
                            where a.grouptenantmasid='$grouptenantmasid' and
                            '$d2' between date_format(a.fromdate,'%Y-%m') and date_format(a.todate,'%Y-%m');";
                    }
                }                
                $result2 = mysql_query($sql2);
                if($result2 !=null)
                {
                    $rcount = mysql_num_rows($result2);
                    if($rcount ==0) 
                    {                        
                            $td .="<td class='row".$s."'>-</td>";
                            $rowtotal2[$s] = $s;                            
                            $s++;
                    }
                    $k = false;
                    while($row2 = mysql_fetch_assoc($result2))
                    {                                                
                        $k=true;
                        if($row2['mnthlysc'] >0 ){                                                        
                            if($rcount >1)
                            {
                                $size +=$row2['size'];
                                $sc += $row2['mnthlysc'];
                            }
                            else
                            {
                                $size =$row2['size'];
                                $sc = $row2['mnthlysc'];
                            }
                            $mnthschrgd++;
                            $totalsqrft = $mnthschrgd*$size;                            
                        }
                        else
                        {
                            $td .="<td class='row".$s."'>0</td>";
                        }                        
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
                
                if($size =="")
                $size = $row1['size'];            
                $objPHPExcel->getActiveSheet()
                            ->setCellValue('A'.$j, $n)
                            ->setCellValue('B'.$j, $leasename)                            
                            ->setCellValue('C'.$j, number_format($size, 0, '.', ','))
                            ->setCellValue('D'.$j, $mnthschrgd)
                            ->setCellValue('E'.$j, $totalsqrft)
                            ->setCellValue($tenantusage.$j, '=SUM('.$st1.$j.':'.$st2.$j.')')                            
                            ->setCellValue($sccost.$j, '=('.$tenantusage.$j.'*'.$rate.$j.')');                
                $j++;    
                $table .="<tr>";
                $table .="<td align='center'>".$n++.".</td>";
                $table .="<td>$leasename</td>";
                $table .="<td>$size</td>";
                $table .="<td>$mnthschrgd</td>";
                $table .="<td>$totalsqrft</td>";
                
                ////$custom = array('msg'=> $commoncost." - ".$grandtsqrft."-"  ,'s'=>'error');
                ////$response_array[] = $custom;
                ////echo '{"error":'.json_encode($response_array).'}';
                ////exit;
                $capp =0;
                if(($commoncost>0) and ($grandtsqrft >0)){
                    $capp = $commoncost*$totalsqrft/$grandtsqrft;
                    $capp = round($capp,0,PHP_ROUND_HALF_EVEN);
                    //$table .="<td>".$totalsqrft."*".$commoncost."/".$grandtsqrft."</td>";
                    $table .="<td align='right'>".number_format($capp, 0, '.', ',')."</td>";
                }
                else{
                    $table .="<td>0</td>";
                }
                $mapp=0;
                if(($managementcost>0) and ($grandtsqrft >0)){
                    $mapp = $managementcost*$totalsqrft/$grandtsqrft;
                    $mapp = round($mapp,0,PHP_ROUND_HALF_EVEN);
                    $table .="<td align='right'>".number_format($mapp, 0, '.', ',')."</td>";
                }
                else{
                    $table .="<td>0</td>";
                }
                $directcost=0;
                //gencost
                $gencost=0;
                $sqlgen = "select b.gencost,b.tenant from trans_exp_gen_mas a
                        inner join trans_exp_gen_det b on b.transexpgenmasid = a.transexpgenmasid
                        where a.transexpmasid = '$transexpmasid' and b.grouptenantmasid='$grouptenantmasid';";
                $resultgen = mysql_query($sqlgen);
                if($resultgen !=null)
                {
                    $rowgen = mysql_fetch_assoc($resultgen);
                    $gencost =$rowgen['gencost'];                    
                    ////$table .="<td>$sqlgen</td>";
                }
                //watercost
                $watercost=0;
                $sqlgen = "select b.watercost,b.tenant from trans_exp_water_mas a
                        inner join trans_exp_water_det b on b.transexpwatermasid = a.transexpwatermasid
                        where a.transexpmasid = '$transexpmasid' and b.grouptenantmasid='$grouptenantmasid';";
                $resultgen = mysql_query($sqlgen);
                if($resultgen !=null)
                {
                    $rowgen = mysql_fetch_assoc($resultgen);
                    $watercost =$rowgen['watercost'];                    
                    ////$table .="<td>$sqlgen</td>";
                }
                $directcost= $gencost+$watercost;
                
                $table .="<td>$directcost</td>";
                //////$table .="<td>".$gencost."+".$watercost."</td>";
                $totalcost = $capp+$mapp+$directcost;
                $table .="<td>$totalcost</td>";
                $table .="<td>$linetotal</td>";
                $netcost =$totalcost -$linetotal;
                $table .="<td>$netcost</td>";
                //invoice %
                $table .="<td><input type='text' name='$grouptenantmasid' id='$grouptenantmasid' value='0' style='width:36%;'/>&nbsp;<b>%</td>";
                $table .="</tr>";                
                $commoncosttotal +=$capp;
                $managementcosttotal +=$mapp;
                $directcosttotal +=$directcost;
                $totalcosttotal +=$totalcost;
                $scdeposittotal +=$linetotal;
                $nettotal +=$netcost;
            }        
        }       
    }
$v=0;
$table .="<tr>";
$table .="<td align='right' colspan='4'><b>GRAND TOTAL:</b></td>";
$table .="<td align='right'><b>".number_format($grandtsqrft, 0, '.', ',')." Sqrft</b></td>";
$table .="<td align='right'><b>".number_format($commoncosttotal, 0, '.', ',')."</b></td>";
$table .="<td align='right'><b>".number_format($managementcosttotal, 0, '.', ',')."</b></td>";
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