<?php
include('../config.php');
session_start();
require_once '../Classes/PHPExcel/IOFactory.php';
try{
    
$buildingmasid = $_POST['buildingmasid'];
$sqlGet ="";
$nk =0;
if (! empty($_FILES['excelfile']))
{
    $sqlGet ="1";
    
    while(list($key,$val) = each($_FILES['excelfile']))
    {
        if(!empty($val)) // this will check if any blank field is entered
	{
            $a = array(' ','#','$','%','^','&','*','?');
	    $b = array('','No.','Dollar','Percent','','and','','');
	    $c = array('');
	    /////$path1 = str_replace($a,$b,$path1); // to revome special chracters if any in file path
			
	    $filename = str_replace($a,$b,$val);  // filename stores the value            
            $sqlGet.= $nk."; Name: ".$key."; Value: ".$val."<BR>";
            $nk++;	   
	    
//	    $custom = array('msg'=> $_FILES["excelfile"]["tmp_name"] ,'s'=>'Success');
//            $response_array[] = $custom;
//            echo '{"error":'.json_encode($response_array).'}';
//            exit;
	    
	    $filePath = $_FILES["excelfile"]["tmp_name"];
            //$filePath = "C:/".$filename;
            
            //$custom = array('msg'=> $filePath ,'s'=>'Success');
            //$response_array[] = $custom;
            //echo '{"error":'.json_encode($response_array).'}';
            //exit;
	    $table=" Generator Cost Apportionment <br>";
            //$table .= "<br>buildingmasid = ".$buildingmasid."<br>";
        
            $objPHPExcel = PHPExcel_IOFactory::load($filePath);
            $where ="";$transexpmasid="";$kwvalue=0;$gentotalsum=0;
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheetTitle     = $worksheet->getTitle();
                $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $nrColumns = ord($highestColumn) - 64;
                //$table .="<br>The worksheet ".$worksheetTitle." has ";
                //$table .= $nrColumns . ' columns (A-' . $highestColumn . ') ';
                //$table .= ' and ' . $highestRow . ' row.';                               
		$table .= '<tr>';
		for ($col = 4; $col < 6; ++ $col) {
		    $cell = $worksheet->getCellByColumnAndRow($col, 1); // first row
		    $val = $cell->getFormattedValue();
		    $where .= $val;
		    $transexpmasid = $val;
		    if($col <=4)
		    $where .= "=";                        
		}        
		for ($col = 5; $col < 8; ++ $col) {
		    $cell = $worksheet->getCellByColumnAndRow($col, 3);
		    $val = $cell->getFormattedValue();		    
		    $kwvalue = $val;		    
		}				
		$delqry ="delete from trans_exp_gen_mas where transexpmasid ='".$transexpmasid."'";
		mysql_query($delqry);
		
		//expgroupmasid ='3' direct cost id
		//expledgermasid='14' generator running cost id
		$gencost=0;
		$sqlg = "select b.amount as 'gencost' from trans_exp_mas a
			inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
			inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
			inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
			where c.expgroupmasid ='3'  and b.expledgermasid='14' and a.$where;";
		////echo $insertmas;	
		$resultg = mysql_query($sqlg);
		if($resultg !=null)
		{
		    $rowg = mysql_fetch_assoc($resultg);
		    $gencost= $rowg['gencost'];
		}		
                //$table .= 'Generator Cost: '.$gencost.'<br>'.$where.'<br>Data : <table border="1">';
		$table .= '<table border="1">';
		$table .= "<tr><th>Sno<th>Tenant<th>Sqrft<th>Connected Kw<th>Additional Kw<th>Total Kw<th>Common Area Usage<th>KW to KVA<th>KVA %<th>Direct Cost<th>Common Area Cost<th>Genarator Cost";
		$s=0; // common area cost
		$totsqrft=0;
		for ($r = $highestRow; $r <= $highestRow; ++ $r) {
                    ////$table .= '<tr>';
                    for ($c = 2; $c < $highestColumnIndex; ++ $c) {
                        $cell = $worksheet->getCellByColumnAndRow($c, $r);
                        $val = $cell->getFormattedValue();
			$val = str_replace(',', '', $val);
                        ////$table .= '<td>'.$val;
			if($c==2){
			    $totsqrft=$val;			    
			}
			if($c==6)
			    $s=$val;
			if($c==7){
			    $s +=$val;
			    $s1 =$val;
			}
			if($c==7){			    			    
			    $s = number_format($s1/$s*100, 2, '.', ',');
			    //////$s1k = round($s/100*$gencost,0,PHP_ROUND_HALF_EVEN);
			    $s = round($s/100*$gencost,0,PHP_ROUND_HALF_EVEN);			    
			    
			}
			
                    }		    
                }
		
		
		////insert into db////
		$createdby = $_SESSION['myusername'];$iid=0;	$charged=0;$comm=0;
		$totalchrgedcost=0;$totalcommcost=0;$totalgencost=0;	
			
		$insertmas = "insert into trans_exp_gen_mas (transexpmasid,kwvalue,totalsqrft,createdby,createddatetime) values
			   ('$transexpmasid','$kwvalue','$totsqrft','$createdby','$datetime')";
		//echo $insertmas;	
		//mysql_query($insertmas);
		//$iid = mysql_insert_id();
		
		$insertdet = "";
		$sqrft=0;
                for ($row = 5; $row <= $highestRow; ++ $row) {
		    $insertvalues="";
                    $table .= '<tr>';
                    for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getFormattedValue();
			$val = str_replace(',', '', $val);
			if($col ==2)
			$sqrft=$val;
                        $table .= '<td>'.$val;
			//if($col >0){
			    if($val =="")
				$val=0;
			    $insertvalues .= "'".$val."',";
			//}
                    }
		    
		    //charged gen cost
		    //$val = str_replace($a,$c,$val);
		    $charged = round($gencost*$val/100,0,PHP_ROUND_HALF_EVEN);
		    if($sqrft != $totsqrft)
		    {
			$totalchrgedcost +=$charged;
			//$table .= '<td>'.$gencost."*".$val."/100=".$charged;
			$table .= '<td align="right">'.number_format($charged, 0, '.', ',');
		    }
		    else
		    {
			$table .= '<td align="right">'.number_format($totalchrgedcost, 0, '.', ',');
		    }					   
		    
		    // common cost aport
			$comm = round($s*$sqrft/$totsqrft,0,PHP_ROUND_HALF_EVEN);			
			if($sqrft != $totsqrft)
			{
			    $totalcommcost +=$comm;
			    //$table .= '<td>'.$s."*".$sqrft."/".$totsqrft."=".$comm;
			    //$table .= '<td>'.$s1k;
			    $table .= '<td align="right">'.number_format($comm, 0, '.', ',');
			}
			else
			{
			    $table .= '<td align="right">'.number_format($totalcommcost, 0, '.', ',');
			}
			
			
		    //total gen cost
			$gentotalcost = $charged + $comm;
			if($sqrft != $totsqrft)
			{
			    $totalgencost += $gentotalcost;
			    //$table .= '<td>'.$charged."+".$comm;						    
			    $table .= '<td align="right">'.number_format($gentotalcost, 0, '.', ',');
			}
			else
			{
			    $totalgencost -= $s;
			    $table .= '<td align="right">'.number_format($totalgencost, 0, '.', ',');
			}
		    
		    //insert trans_exp_gen_det
		    $insertvalues =rtrim($insertvalues,",");
			if($row < $highestRow){
			{
			    $insertdet = "insert into trans_exp_gen_det
			    (transexpgenmasid,grouptenantmasid,tenant,sqrft,conkw,addikw,totkw,comareausage,kwatokva,kvapercentage,chrgddircost,chrgdcomcost,gencost) values
			    ('$iid',$insertvalues,'$charged','$comm','$gentotalcost')";
			    //mysql_query($insertdet);
			    //echo $insertdet;			   
			}			
		    }
                }		
            }	    
	    echo $table;
	    //echo $insertmas."<br>".$insertdet;
            exit;
        }
    }    
}
else
{
    $custom = array('msg'=> "No Files" ,'s'=>'Success');
    echo json_encode($custom);
}

}
catch (Exception $err)
{
    echo $err->getMessage().", Line No:".$err->getLine();
}
?>