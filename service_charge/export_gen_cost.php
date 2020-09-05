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

// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0);
PHPExcel_Calculation::getInstance()->cyclicFormulaCount = 1;
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(8);
$objPHPExcel->getActiveSheet()->setPrintGridlines(TRUE);

$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'Shiloah Investments Ltd - Generator running & maintenance')
            ->setCellValue('E1', 'transexpmasid')
            ->setCellValue('F1', $transexpmasid);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E1:F1')
            ->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
            
$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Building : '.$buildingname);
$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Period   : '.$firstdate);
$objPHPExcel->getActiveSheet()->setCellValue('F3', 'Enter Value');

$objPHPExcel->getActiveSheet()->setCellValue('G3', '0.9');
 
//if($buildingmasid=='1'){// Mega Plaza    
//    $objPHPExcel->getActiveSheet()->setCellValue('G3', '0.98');
//}
//else if($buildingmasid=='2'){ // Mega City
//    $objPHPExcel->getActiveSheet()->setCellValue('G3', '0.9');
//}
//else if($buildingmasid=='3'){ // Mega Mall
//    $objPHPExcel->getActiveSheet()->setCellValue('G3', '0.8');
//}
//else if($buildingmasid=='5'){ // Reliance Centre     
//    $objPHPExcel->getActiveSheet()->setCellValue('G3', '0.8');
//}
//else if($buildingmasid=='7'){ // Mega Centre
//    $objPHPExcel->getActiveSheet()->setCellValue('G3', '0.8');
//}

  //$custom = array('result'=> $buildingmasid ,'s'=>'error');
  //  $response_array[] = $custom;
  //  echo '{"error":'.json_encode($response_array).'}';
  //  exit;
    
$objPHPExcel->getActiveSheet()->getStyle('D:H')->getNumberFormat()->setFormatCode('0.00');
$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode('0.00%');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A4', 'Id')
            ->setCellValue('B4', 'Tenant')
            ->setCellValue('C4', 'Sqrft')            
            ->setCellValue('D4', 'Connected KW')
            ->setCellValue('E4', 'Additional KW')
            ->setCellValue('F4', 'Total KW')            
            ->setCellValue('G4', 'Total KVA')
            ->setCellValue('H4', 'Comn area KVA')
            ->setCellValue('I4', 'KVA %');
            $j = 5; // starts from 5th row
            $st=5;
$objPHPExcel->getActiveSheet()->getStyle('F4:I4')
            ->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getFont()->setBold(true);



$table ="<p class='printable'><table>";
$table .="<tr><th colspan='18'>Service Charge Deposit Report</th></tr>";
$table .="<tr><td colspan='18'><b> $buildingname <br><br> ".$firstdate."</b></td></tr>";
$table .="<tr>";
$table .="<th>S.No</th>";
$table .="<th>Tenant</th>";
$table .="<th>Sqrft</th>";
$table .="<th>Connected KW</th>";
$table .="<th>Additional KW</th>";
$table .="<th>Total KW</th>";
$table .="<th>KW to KVA</th>";
$table .="<th>KVA %</th>";
$table .="<th>Common area usage</th>";
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
    if($result1 !=null)
    {               
        while($row1 = mysql_fetch_assoc($result1)) // no of tenants available for the building
        {
            $mnthschrgd=0;$td="";$size="";$totalsqrft=0;$sc=0;$linetotal=0;$s=0;
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
            
            if($row1['tradingname'] =="")
                $leasename = $row1['leasename'];
            else
                $leasename = $row1['leasename'] ." T/A ".$row1['tradingname'];
                
            $grandtd="";
            for($i=0;$i<=$months;$i++)// no of months charged
            {                    
                $d2 = date("Y-m", strtotime(date("Y-m", strtotime($d1)) . " + $i Months"));            
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
                                $sc += $row2['mnthlysc'];
                            }
                            else
                            {                             
                                $sc = $row2['mnthlysc'];
                            }
                            $mnthschrgd++;
                            //$totalsqrft = $mnthschrgd*$size;                            
                        }
                        else
                        {
                            $td .="<td class='row".$s."'>0</td>";
                        }                        
                    }
                    if($k==true){
                        if($sc >0){
                            $td .="<td class='row".$s."'>".number_format($sc, 0, '.', ',')."</td>";                            
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
                //$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('DCF7E6');
                //$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('DCF7E6');
                $objPHPExcel->getActiveSheet()
                            ->setCellValue('A'.$j, $grouptenantmasid)
                            ->setCellValue('B'.$j, $leasename)                            
                            ->setCellValue('C'.$j, number_format($size, 0, '.', ','))
                            ->setCellValue('D'.$j, '')
                            ->setCellValue('E'.$j, '')
                            ->setCellValue('F'.$j, '=SUM(D'.$j.':E'.$j.')')                                                        
                            ->setCellValue('G'.$j, '=F'.$j.'/G3');              
                $j++;                
                $table .="<tr>";
                $table .="<td align='center'>".$n++.".</td>";
                $table .="<td>$leasename</td>"; 
                $table .="<td>".number_format($size, 0, '.', ',')."</td>";                                                
                $table .="</tr>";
                $grandtsqrft +=$size;
            }        
        }       
    }
$v=0;
$sql="select expcomarea from mas_exp_com_area where expledgermasid ='14' and buildingmasid =$buildingmasid and active='1';"; //14= genrator ledger
$result = mysql_query($sql);
if($result != null)
{
    $v=$j;
    while($row = mysql_fetch_assoc($result))
    {
        
        $table .="<tr>";
        $table .="<td align='center'>".$n++.".</td>";
        $table .="<td>".$row['expcomarea']."</td>";        
        $table .="<td></td>";
        $table .="</tr>";
        //$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('DCF7E6');
        
        $objPHPExcel->getActiveSheet()
                            ->setCellValue('A'.$j, '0')
                            ->setCellValue('B'.$j, $row['expcomarea']);
        $j++;        
    }    
}
$table .="<tr>";
$table .="<td align='right' colspan='2'><b>GRAND TOTAL:</b></td>";
$table .="<td align='right'><b>".number_format($grandtsqrft, 0, '.', ',')." Sqrft</b></td>";
$table .="</tr>";
$table .="</table></p>";
$f = $j-1;
$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$j, '')
            ->setCellValue('B'.$j, 'GRAND TOTAL')
            ->setCellValue('C'.$j, number_format($grandtsqrft, 0, '.', ','))
            ->setCellValue('D'.$j, '=SUM(D5:D'.$f.')')
            ->setCellValue('E'.$j, '=SUM(E5:E'.$f.')')
            ->setCellValue('F'.$j, '=SUM(F5:F'.$f.')')            
            ->setCellValue('G'.$j, '=SUM(G5:G'.$f.')')
            ->setCellValue('H'.$j, '=SUM(H'.$v.':H'.$f.')')
            ->setCellValue('I'.$j, '=SUM(I5:I'.$f.')');
$end =$j;
for ($col = $st; $col< $end; $col++) {
    if($col < $f)
    {
        $objPHPExcel->getActiveSheet()                
            ->setCellValue('I'.$col, '=G'.$col.'/SUM(G'.$end.':H'.$end.')');
    }
    else
    {
        
        $objPHPExcel->getActiveSheet()                
            ->setCellValue('I'.$col, '=H'.$j.'/SUM(G'.$j.':H'.$j.')');
    }
}

for ($col = 'A'; $col != 'J'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);        
}

$objPHPExcel->getActiveSheet()->setTitle("Gen_Cost_$buildingname");
$objPHPExcel->setActiveSheetIndex(0);
$filename = 'Gen_Cost_'.$shortname;

ob_start();
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
$data = ob_get_contents();
ob_end_clean();
$message="Generator Maintenance and running cost";
//**************************** EMAIL *************************//



ini_set('SMTP','192.168.0.1');// DEFINE SMTP MAIL SERVER
require_once('../PHPMailer/class.phpmailer.php');
$mail = new PHPMailer(); // defaults to using php "mail()"
$mail->SetFrom('info@shiloahmega.com', 'PMS Admin');
$mail->AddReplyTo('info@shiloahmega.com', 'PMS Admin');
$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('7') AND active = '1' LIMIT 1";
$result12=mysql_query($sql12);
		while($row12 = mysql_fetch_array($result12 ))
    {
   $address = $row12['email']; 
	}
//$address = "juma@shiloahmega.com";
$mail->AddAddress($address, "Admin");

$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('9') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}

// $recipients = array(   
   // 'arulraj@shiloahmega.com' => 'Arul Raj'
   
// );
foreach($recipients as $email => $name)
{
   $mail->AddCC($email, $name);
}
$mail->Subject    = "Generator Cost $buildingname  $firstdate";
$mail->MsgHTML($message);
$mail->AddStringAttachment($data, $filename. '.xlsx');

if(!$mail->Send()) {
   //echo "Mailer Error: " . $mail->ErrorInfo;
  $custom = array('divContent'=>"Mailer Error: " . $mail->ErrorInfo,'s'=>'error');
} else {
    $custom = array('divContent'=>"<font color=green> Success: Generator cost Mail Sent with attachment !!!</font>",'s'=>'Success');
}
$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
exit;
//************************** EMAIL *************************//


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