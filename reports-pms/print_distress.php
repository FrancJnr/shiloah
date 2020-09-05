<?php
include('../config.php');
session_start();
try{
    
class MYPDF extends TCPDF {
        //Page header
       public function Header() {
           // Logo
           $image_file = "../images/mpg1.png";
           $this->Image($image_file, '', '', 39, 39, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
           // Set font
           //$this->SetFont('helvetica', 'B', 20);
           // Title
           //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
       }
        // Page footer
       public function Footer() {
           // Position at 15 mm from bottom
           $this->SetY(-15);
           // Set font
           $this->SetFont('dejavusans', 'I', 8);
           // Page number
           $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
       }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);	
$pdf->SetAutoPageBreak(TRUE, 0);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, '', PDF_MARGIN_RIGHT);
// set default footer margin
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set default font subsetting mode
$pdf->setFontSubsetting(true);   
$company = strtoupper($_SESSION["mycompany"]);
$companymasid = $_SESSION['mycompanymasid'];

$pdf->AddPage();

$distressmasid= $_GET['distressmasid'];


$grouptenantmasid=0;$paymentfor = "";
$subject="";$para1="";$para2="";
$outstandingamt=0;$graceperiod=0;
$sql = "select grouptenantmasid,paymentfor,subject,para1,para2,outstandingamt,graceperiod,
        date_format(createddate,'%d-%m-%Y') as createddate from rpt_distress where distressmasid  = $distressmasid;";
$result = mysql_query($sql);
if($result !=null)
{
    $row = mysql_fetch_assoc($result);
    $grouptenantmasid=$row['grouptenantmasid'];
    $paymentfor = $row['paymentfor'];
    $subject=$row['subject'];
    $para1=$row['para1'];
    $para2=$row['para2'];
    $outstandingamt=$row['outstandingamt'];
    $graceperiod =$row['graceperiod'];
    $createddate =$row['createddate'];
}

$tenantaddress="";$buildingaddress="";$buildingmasid="";$companymasid="";$shop="";
$buildingname="";
$sql="select 
    case b.tradingname 
            when b.tradingname ='' then concat(b.leasename ,' (T/A) ',b.tradingname)
            when b.tradingname <>'' then concat(b.leasename)  
    end as tenant,
    b.poboxno,b.pincode,b.city,b.buildingmasid,b.companymasid,d.buildingname,c.shopcode,d.buildingname
    from group_tenant_det a
                inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
                inner join mas_shop c on  c.shopmasid = b.shopmasid
                inner join mas_building d on d.buildingmasid = c.buildingmasid
                where b.active='1' and a.grouptenantmasid = '$grouptenantmasid'
                union
    select 
    case b1.tradingname 
            when b1.tradingname ='' then concat(b1.leasename ,' (T/A) ',b1.tradingname)
            when b1.tradingname <>'' then concat(b1.leasename)  
    end as tenant,
    b1.poboxno,b1.pincode,b1.city,b1.buildingmasid,b1.companymasid,d1.buildingname,c1.shopcode,d1.buildingname
    from group_tenant_det a1
                inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
                inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
                inner join mas_building d1 on d1.buildingmasid = c1.buildingmasid
                where b1.active='1' and a1.grouptenantmasid = '$grouptenantmasid';";
$result =  mysql_query($sql);    
if($result != null) 
{
    while ($row = mysql_fetch_assoc($result))
    {
        $tenantaddress = "<b>".$row['tenant'].",</b><br>" ;            
        if($row['pincode'] == "")
                $tenantaddress .= "P.O.Box : ".$row['poboxno'].",<br>" ;
        else
            $tenantaddress .= "P.O.Box : ".$row['poboxno']." - ".$row['pincode'].",<br>" ;
        $tenantaddress .= $row['city'].".";
        $shop .=$row['shopcode']." ";
        
        $buildingaddress = $row['buildingname']." - Shop No:( ".$shop.")";
        $buildingmasid = $row['buildingmasid'];
        $companymasid= $row['companymasid'];
        $buildingname= strtoupper($row['buildingname']);
    }
}

$compnyname="";
$sql = "select companyname from mas_company where companymasid = $companymasid;";
$result = mysql_query($sql);
if($result != null)
{
    $row = mysql_fetch_assoc($result);
    $companyname = $row['companyname'];
}


$pdf->SetFont('dejavusans','',12);
$pdf->ln(30);
//$pdf->writeHTML("<center>".$_SESSION['mycompany']."</center>", true, false, true, false, '');


$pdf->SetFont('dejavusans','',10);
$pdf->ln(10);
$dt = date("d-m-Y", strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +  0 Months"));
$pdf->writeHTML("Date: ".$createddate, true, false, true, false, '');                

$pdf->ln(10);
$pdf->writeHTML("<b>TO</b>", true, false, true, false, '');

$pdf->ln(5);
$pdf->writeHTML($tenantaddress, true, false, true, false, '');


$pdf->ln(10);
$pdf->writeHTML("Dear Sir/Madam,", true, false, true, false, '');
$pdf->ln(5);
//$pdf->writeHTML("<b>SUB: $paymentfor Outstanding For $buildingname</b>", true, false, true, false, '');
$pdf->writeHTML("<b>$subject </b>", true, false, true, false, '');
$pdf->ln(5);
//$p1 ="Please note that the total amount of $paymentfor outstanding for the above premises $buildingname is Kshs.".number_format($outstandingamt, 0, '.', ',') ." /=.";
$p1 =$para1;
$pdf->writeHTML($p1, true, false, true, false, '');

$pdf->ln(5);
//$p2 ="Take notice that unless the outstanding amount Kshs.".number_format($outstandingamt, 0, '.', ',') ." /= is
//        paid to us within the next $graceperiod (".convert_number($graceperiod).") days from today we will levy
//        distress on you and by copy of this letter we are instructing our lawyer to do the same.";
$p2 =$para2;
$pdf->writeHTML($p2, true, false, true, false, '');

$pdf->ln(4);
$pdf->writeHTML("Thanking You,", true, false, true, false, '');
$pdf->ln(5);
$pdf->writeHTML("Yours truly,", true, false, true, false, '');
$pdf->ln(5);
$pdf->writeHTML("<b>For $companyname</b>", true, false, true, false, '');
$pdf->ln(20);
$pdf->writeHTML("<b>CREDIT CONTROLLER</b>", true, false, true, false, '');
$pdf->ln(10);
$pdf->writeHTML("Cc Wasuna & Co, Advocate.", true, false, true, false, '');
$pdf->ln(2);
$pdf->writeHTML("Cc Nyaluoyo Auctioneers, <br> P.O.Box 648, <br> KISUMU.", true, false, true, false, '');
$filename = $distressmasid;
$pdf->Output($filename, 'I');    
    //echo $table;
    //$custom = array('divContent'=>$table,'s'=>'Success');
    //$response_array[] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
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