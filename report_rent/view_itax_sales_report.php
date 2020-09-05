<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    
	<title>i-Tax Report</title>

<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../js/table2CSV.js"></script>
<script type="text/javascript" language="javascript">

function getCSVData(){
  var csv_value=$('#tableitax').table2CSV({delivery:'value'});
  $("#csv_text").val(csv_value);  
}
</script>

<style type="text/css">
            
body{
  font:1.2em normal Arial,sans-serif;
  color:#34495E;
}

h1{
  text-align:center;
  text-transform:uppercase;
  letter-spacing:-2px;
  font-size:2.5em;
  margin:20px 0;
}

.dataManipDiv{
  width:99%;
  margin:auto;
}

#tableitax{
  border-collapse:collapse;
  width:99%;
}

#tableitax{
  border:2px solid #1ABC9C;
}

#tableitax thead{
  background:#1ABC9C;
}

.purple{
  border:2px solid #9B59B6;
}

.purple thead{
  background:#9B59B6;
}

thead{
  color:white;
}

th,td{
  text-align:center;
  padding:5px 0;
}

tbody tr:nth-child(even){
  background:#ECF0F1;
}

tbody tr:hover{
background:#BDC3C7;
  color:#FFFFFF;
}

.fixed{
  top:0;
  position:fixed;
  width:auto;
  display:none;
  border:none;
}

.scrollMore{
  margin-top:600px;
}

.up{
  cursor:pointer;
}   

table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>
</head>

<?php
include('../config.php');
session_start();
try{
$a="0";$i=1;$gtot=0;$shp="";$schp="";$grptot=0;$mnthlyrent=0;
$dt = date("M-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . " + 1 Months"));
$buildings=rtrim($_GET['buildings'],",");
$fromdate = explode(" ",$_GET['invdt']);
$firstdate = $_GET['invdt'];
$taxinvoiceno="";
//$fromdate =  date("Y-m-d",strtotime("2016-03-02"));
 //$firstdate =  date("d-m-Y",strtotime("2016-03-02"));
$buildingsarray=explode(",", $buildings);
//$sal = "select * from mas_building where companymasid=".$_SESSION['mycompanymasid']." and buildingmasid in (".$buildings.")";
//echo $sal;
$table ="<p class='printable'><table class='custom' id='tableitax' style='padding: 0px;font-size: 14px;font-family: Verdana, Arial, Helvetica, sans-serif;margin: 10px 0px 8px 0px; border-collapse: collapse;'>";

$buildingname = "";$rect_date="";$orig_cycle="";
//if(count($buildingsarray)>1){
    
$sal = "select * from mas_building where companymasid=".$_SESSION['mycompanymasid']." and buildingmasid in (".$buildings.")";
$ral = mysql_query($sal);
   
 while($rol = mysql_fetch_array($ral))
    {

 $buildingmasid=  $rol['buildingmasid']; 
$s = "select buildingname,companymasid,isvat from mas_building where companymasid=".$_SESSION['mycompanymasid']." and buildingmasid =".$rol['buildingmasid'];
$r = mysql_query($s);
 while($ro = mysql_fetch_assoc($r))
    {
        $buildingname = strtoupper($ro["buildingname"]);
	$building_companymasid = $ro['companymasid'];
	$isvat =$ro["isvat"];	
    }

$company = strtoupper($_SESSION["mycompany"]);
$companymasid = $_SESSION['mycompanymasid'];

                $pinno ="";
                $vatno="";
                $sqlpin = "select pin,vatno from mas_company where companymasid=$building_companymasid;";
                $resultpin = mysql_query($sqlpin);
                if($resultpin !=null)
                {
                    while($row = mysql_fetch_assoc($resultpin))
                    {
                        $pinno .=$row['pin'];
                        $vatno .=$row['vatno'];
                    }
                }
                if($building_companymasid =='3')
                {
                    	    
                }
                else
                {	
                }


$filename = $buildingname." - (".$firstdate.")";
$sqrftTotal=0;


$table .="<center><tr><td colspan='18'>$buildingname ".$firstdate."</td></tr></center>";
$table .="<tr align='center' style='font-weight:bold;color:brown;'>";
$table .="<td>PIN No. of Purchaser</td>";
$table .="<td>Name of Purchaser</td>";
$table .="<td>ETR Serial Number</td>";
$table .="<td>Invoice Date</td>";
$table .="<td>Invoice Number</td>";
$table .="<td>Description of Goods/Services</td>";
$table .="<td>Taxable Value (Ksh)</td>";
$table .="<td>Amount of VAT (Ksh)(Taxable Value*14%)</td>";
$table .="<td>Credit Note</td>";
$table .="<td>Credit Note</td>";
$table .="<td>Credit Note</td>";
$table .="<td>Relevant Invoice Number</td>";
$table .="<td>Relevant Invoice Date</td>";

$i=0;
$rentgtotal=0;$scgtotal=0;$today=0;$chksql=""; $ipin="";
$rentvatgtotal=0;$scvatgtotal=0;
$rentandvatgtotal=0;$scandvatgtotal=0;
$grandtotal=0;$totsize="";$expdt=0;$doc=0;

$sql = "select a.grouptenantmasid , b.salutation, b.pin, b.leasename, date_format(b.doc,'%d-%m-%Y') as doc,e.shoptype,e.shoptypemasid,d.companymasid from group_tenant_mas a 
	inner join mas_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	inner join mas_shoptype e on e.shoptypemasid = b.shoptypemasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' and b.shopoccupied='1' not in 
	    (
		select a1.grouptenantmasid from trans_tenant_discharge_op a1
                inner join  trans_tenant_discharge_ac b1 on b1.grouptenantmasid = a1.grouptenantmasid
                where a1.opapproval ='1' and b1.acapproval='1'
	    ) and a.grouptenantmasid not in (select grouptenantmasid from waiting_list)
	union
	select a.grouptenantmasid ,b.salutation, b.pin, b.leasename,date_format(b.doc,'%d-%m-%Y') as doc,e.shoptype,e.shoptypemasid,d.companymasid from group_tenant_mas a 
	inner join rec_tenant b on b.tenantmasid = a.tenantmasid
	inner join mas_shop c on c.shopmasid = b.shopmasid
	inner join mas_building d on d.buildingmasid = b.buildingmasid
	inner join mas_shoptype e on e.shoptypemasid = b.shoptypemasid
	where d.buildingmasid = $buildingmasid and a.grouptenantmasid and b.active = '1' and b.shopoccupied='1' not in 
	    (
		select a2.grouptenantmasid from trans_tenant_discharge_op a2
                inner join  trans_tenant_discharge_ac b2 on b2.grouptenantmasid = a2.grouptenantmasid
                where a2.opapproval ='1' and b2.acapproval='1'
	    ) and a.grouptenantmasid not in (select grouptenantmasid from waiting_list)
	order by shoptypemasid asc,leasename asc;";
$result = mysql_query($sql);
$jk=1;$m1=0;
while($row = mysql_fetch_assoc($result))
{
        $cnt=0;$tmas="";$rm =0;$fv=0;
	$grouptenantmasid = $row['grouptenantmasid'];
	$fromdate = explode(" ",$_GET['invdt']);
        $ipin=$row['pin'];
	$advancerent=false;
	$shop="";$size="";$leasename="";$tradingname="";$shoptype="";$rentcycle="";$rct="0";$monthdiff=0;$period="";$renewal=0;
	$prdfrom="";$prdto="";
	$rent=0;$sc=0;
	$rentvat=0;$scvat=0;
	$rentandvat=0;$scandvat=0;
	$rowtotal="";
	
	$sql1 = "select a.tenantmasid from group_tenant_det a  
		 inner join mas_tenant b on b.tenantmasid = a.tenantmasid
		 where a.grouptenantmasid = $grouptenantmasid  and b.active='1' and b.shopoccupied='1'";
		 
	$result1=mysql_query($sql1);
	if($result1 !=null)
	{
	    $rcount = mysql_num_rows($result1);
	    if($rcount ==0) 
	    {
		$sql1 = "select a.tenantmasid from group_tenant_det a  
			    inner join rec_tenant b on b.tenantmasid = a.tenantmasid
			    where a.grouptenantmasid = $grouptenantmasid  and b.active='1' and b.shopoccupied='1'";
	    }
	}	
	$jk=1;
	$result1 = mysql_query($sql1);
	$cnt = mysql_num_rows($result1);
	$sql2="";
	while($row1 = mysql_fetch_assoc($result1))
	{	
	    $cnt1=0;
	    $d1=0;$d2=0;$m2=0; $ex="";
	    $tenantmasid = $row1['tenantmasid'];
	     
	     $sql2="select b.shopcode ,b.size,a.salutation,a.leasename,a.tradingname,c.age as 'rentcycle',c.shortdesc,a.renewal,a.tenantcode,a.buildingmasid,
		    a.poboxno,a.pincode,a.city,d.cpname,d.cpmobile,d.cplandline from mas_tenant a
		    inner join mas_shop b on b.shopmasid = a.shopmasid
		    inner join mas_age c on c.agemasid = a.agemasidrc
		    inner join mas_tenant_cp d on d.tenantmasid = a.tenantmasid
		    where a.tenantmasid =$tenantmasid and a.active ='1' and d.documentname='1' limit 1;";
		    
		    
		    $result2=mysql_query($sql2);
		    if($result2 !=null)
		    {
			$rcount1 = mysql_num_rows($result2);
			if($rcount1 ==0) 
			{
			     $sql2="select b.shopcode ,b.size,a.salutation,a.leasename,a.tradingname,c.age as 'rentcycle',c.shortdesc,a.renewal,a.tenantcode,a.buildingmasid,
				    a.poboxno,a.pincode,a.city,d.cpname,d.cpmobile,d.cplandline from rec_tenant a
				    inner join mas_shop b on b.shopmasid = a.shopmasid
				    inner join mas_age c on c.agemasid = a.agemasidrc
				    inner join rec_tenant_cp d on d.tenantmasid = a.tenantmasid
				    where a.tenantmasid =$tenantmasid and a.active ='1' and d.documentname='1' limit 1;";
			    $rct ="1";
			}
                    }
		    $result2 = mysql_query($sql2);		    
		    if($jk==1)
                    {                            
                            $tsql1 = "select date_format(a.fromdate,'%d') as 'dt' from trans_offerletter_rent  a 
                                        inner join trans_offerletter b on b.offerlettermasid = a.offerlettermasid
                                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid
                                        where  b.tenantmasid=$tenantmasid group by b.offerlettermasid;";
                            $tresult1 = mysql_query($tsql1);
                            if($tresult1 !=null)
                            {
                                $rcntdt = mysql_num_rows($tresult1);
				if($rcntdt == 0)
				continue;
				while($row3 = mysql_fetch_assoc($tresult1))
                                {
                                    $fromdate = $row3['dt'].$fromdate[0]."-".$fromdate[1];
                                }				
				$fromdate = date("Y-m-d", strtotime(date("d-m-Y", strtotime($fromdate)) . " + 0 Day"));
                            }
                    }
		    $jk++;
		    while($row2 = mysql_fetch_assoc($result2))
		    {
		
			
			$rentdesc = $row3['shortdesc'];
			$renewal=$row2['renewal'];
			$shop .=$row2['shopcode']."<br>";
			$size .=$row2['size']."<br>";
			$totsize +=$row2['size'];
			
			if($row2['tradingname'] !="")
			$leasename = $row2['tradingname'];
			else
			$leasename = $row2['leasename'];
			$rentcycle = strtolower($row2['rentcycle']);
			$period  = strtolower($row2['shortdesc'])."<br>";
			
			$tmas .=$tenantmasid."<br>";
			//$m1=31;
			$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,y.grouptenantmasid, x.rent as invrent, x.sc as invsc,x.invoiceno as invoiceno,b.fromdate,c.salutation,c.leasename,c.tradingname,s.shoptype,	
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
                                inner join group_tenant_mas y on y.tenantmasid=a.tenantmasid
                                inner join invoice x on x.grouptenantmasid=y.grouptenantmasid
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
					$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,y.grouptenantmasid, x.rent as invrent, x.sc as invsc,x.invoiceno as invoiceno,b.fromdate,c.salutation,c.leasename,c.tradingname,s.shoptype,
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
                                                inner join group_tenant_mas y on y.tenantmasid=a.tenantmasid
                                                inner join invoice x on x.grouptenantmasid=y.grouptenantmasid
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
						    $sql3="select a.tenantmasid,y.grouptenantmasid, x.rent as invrent, x.sc as invsc,x.invoiceno as invoiceno, b.amount as rent,f.amount as sc,b.fromdate,c.salutation,c.leasename,c.tradingname,s.shoptype,
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
                                                            inner join group_tenant_mas y on y.tenantmasid=a.tenantmasid
                                                            inner join invoice x on x.grouptenantmasid=y.grouptenantmasid
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
								$sql3="select a.tenantmasid,b.amount as rent,f.amount as sc,y.grouptenantmasid, x.rent as invrent, x.sc as invsc,x.invoiceno as invoiceno,b.fromdate,c.salutation,c.leasename,c.tradingname,s.shoptype,		
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
                                                                        inner join group_tenant_mas y on y.tenantmasid=a.tenantmasid
                                                                        inner join invoice x on x.grouptenantmasid=y.grouptenantmasid
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
                                //echo $sql3;
			    $result3 = mysql_query($sql3);
			  
			    while($row3 = mysql_fetch_assoc($result3))
			    {
				
				$expdt = $row3['expdt'];
				$tradingname="";
				if($row3['tradingname'] !="")
				{
				    $tradingname ="<br>(T/A) ".$row3['tradingname'];
				}
//				$leasename =  $row3['salutation']." ".$row3['leasename'];
                                $leasename =  $row3['leasename'];
				$shoptype=$row3['shoptype'];
				
				$rentcycle = strtolower($row3['rentcycle']);
				$period  = strtolower($row3['shortdesc'])."<br>";
				$oldcycle = $row3['oldcycle'];

			    	if(strtolower($row3['oldcycle']) == 'per quarter')            
			        {
				    $row3['invrent'] = $row3['invrent'] /3;
				    $row3['invsc'] = $row3['invsc'] /3;

			        }
				else if(strtolower($row3['oldcycle']) == 'per half')            
			        {
				    $row3['invrent'] = $row3['invrent'] /6;
				    $row3['invsc'] = $row3['invsc'] /6;
			        }
				else if(strtolower($row3['oldcycle']) == 'per year')            
			        {
				    $row3['invrent'] = $row3['invrent'] /12;
				    $row3['invsc'] = $row3['invsc'] /12;
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
			       }
				    for($f=0;$f<$fv;++$f){
					//chk in advance invoice available---------------
					$m1 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($fromdate)) . " + $f Months"));
					$sqlchk = "select months,todate			   
						    from advance_rent where grouptenantmasid =$grouptenantmasid and
						    '$m1' between  fromdate and  todate ;";
					$resultchk=mysql_query($sqlchk);		
					if($resultchk !=null)
					{				    
					    $rcount = mysql_num_rows($resultchk);
					    if($rcount > 0) 
					    {																	    						
						$rm = $rm-1;
						$advancerent=true;
					    }
					}					
					//chk in advance invoice available-----------------
				   }

				$rent +=$row3['invrent']*$rm;
				$sc +=$row3['invsc']*$rm;
			       $invperiod = $row3['invperiod'];
                               $taxinvoiceno =$row3['invoiceno'];

			    }
		    }
	}
	
	 
	    if(is_nan($monthdiff))
	    $monthdiff =0;
	    
	     if(($d1=='0') or ($d2=='0')or($rent=='0')){
		continue;
	    }	
	if($monthdiff <= 0)
	   {
	       
	    $i++;
	    if ($cnt > 1)
	    $table .="<tr bgcolor='yellow'>";
	    else 
	    $table .="<tr>";	  	    
	    $today = strtotime($fromdate);
	    $expdt = strtotime($expdt);
	    
	    if ($expdt < $today) {
		
		$sql4="select doc from mas_tenant where tenantmasid = '$tenantmasid' and active='1';";
		$result4 = mysql_query($sql4);
		while($row4 = mysql_fetch_assoc($result4))
		{
		    $doc = $row4['doc'];
		}
		$doc = strtotime($doc);
		
		if ($doc > $today)
		    $table .="<tr bgcolor='green' style='color:white'>";
		else
		    $table .="<tr bgcolor='red' style='color:white'>";
		    
		if($renewal == '1')
		{		    
		    continue; // if renewd continue
		}
	    } 

	    
	    $table .="<td align='center'>".$ipin."</td>"; //PIN No. of Purchaser
	    //$table .="<td>".$shop."</td>";
	    $shoptype = strtolower($shoptype);
	    if($shoptype=="trolley")
	    $size=$shoptype;
	    else if($shoptype=="kiosk")
	    $size=$shoptype;
	    else if($shoptype=="others")
	    $size=$shoptype;
	    
	    //$table .="<td>".$size."</td>";
	    
	    if($i==1)
	    $invno = getinvno($building_companymasid);
	    
	   // $taxinvoiceno =$taxinvoiceno;// $invno."/".date('Y', strtotime($_GET['invdt']));//invoice no
	   
	    //$table .="<td>".$taxinvoiceno."</td>";
		    
	    $rid=0;
	    
	    $sql5="select renewalfromid from mas_tenant where tenantmasid = '$tenantmasid' and active='1';";
	    $result5 = mysql_query($sql5);
	    while($row5 = mysql_fetch_assoc($result5))
	    {
		    $rid = $row5['renewalfromid'];
	    }
	    $leasename = $leasename.$tradingname;
//	    if ($rid == 0){
		    $table .="<td>".$leasename."</td>";	//lease name as Name of Purchaser	    
//	    }
//	    else{
//		    $table .="<td>$leasename&nbsp;&nbsp;[<font color='blue'>Renewed</font>]</td>";	//Name of Purchaser	    
//		}
             $etr=$vatno ;  //ETR SNo: Logic
             $table .="<td bgcolor='#dbdbdb'>".$etr."</td>"; //ETR Serial Number
             
	    if ($rct == 1)
	    {
	       $table .="<td bgcolor='#dbdbdb'>".$d1."</td>";//Invoice Date
	    }
	    else
	    {
		$table .="<td>".$d1."</td>";//Invoice Date
	    }	  
		$rentgtotal +=$rent;
		
		
		if($isvat == 1)
		{
		    $rentvat = round($rent*14/100,0,PHP_ROUND_HALF_EVEN);
		    $rentvatgtotal +=$rentvat;
		}
		else
		{
		   // $table .="<td dir=rtl> 0 </td>";
		}
		
		$rentandvat = $rentvat +$rent;
		$scgtotal +=$sc;
		
		if($isvat == 1)
		{
		    $scvat = round($sc*14/100,0,PHP_ROUND_HALF_EVEN);;
		    //$table .="<td dir=rtl>".number_format($scvat, 0, '.', ',')."</td>";
		    $scvatgtotal +=$scvat;
		}
		else
		{
		    //$table .="<td dir=rtl> 0 </td>";
		}
		
		$scandvat = $scvat +$sc;
	        
                $table .="<td>".$taxinvoiceno."</td>";//Invoice Number
		
		$rowtotal=$rentandvat+$scandvat;

		$grandtotal +=$rowtotal;
  
		$rentperiod="";
		if($advancerent== true)
		{
		    $rm1 = $rm;
		    $rm1 = $rm1-1;
		    $rentperiod .=date("d-m-Y", strtotime(date("Y-m-d", strtotime($d1)) . " +  $rm1 Months"));
		}
		else
		{
		    $rentperiod .=date("d-m-Y", strtotime(date("Y-m-d", strtotime($d1)) . " +  0 Months"));
		}
		
                $rentperiod .= ' to ';
                $rentperiod .=date("d-m-Y", strtotime(date("Y-m-d", strtotime($d2)) . " +  0 Months"));
	
                $descr=$rentdesc." Rent & Service Charge for ".$buildingname." Shop No: ".rtrim($shop,',');
		$table .="<td>".$descr."</td>";//Invoice Number//Description of Goods / Services
                $table .="<td dir=rtl>".number_format($rowtotal-($rowtotal-($rowtotal/1.14)), 0, '.', ',')."</td>";//Taxable Value (Ksh)
                $table .="<td dir=rtl>".number_format(round($rowtotal-($rowtotal/1.14),0,PHP_ROUND_HALF_EVEN), 0, '.', ',')."</td>";//Amount of VAT (Ksh) (Taxable Value*16%)  
                $table .="<td>-</td>";
                $table .="<td>-</td>";
                 $table .="<td>-</td>";
                $table .="<td>".$taxinvoiceno."</td>";//Relevant Invoice Number
                $table .="<td>".$d2."</td>";//Relevant Invoice Date
                               
	    $table .="</tr>";
	    $invno = $invno+1;
	   }
}
}

}
catch (Exception $err)
{
    $custom = array(
                'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    print_r(mysql_error());
}
?>

<form action="getCSV.php" method ="post"> 
<input type="hidden" name="csv_text" id="csv_text">
<div id="tableholder"><?php  echo $table;?></div>
<input type="submit" value="Get CSV File" onclick="getCSVData()">
</form>


</body>
</html>