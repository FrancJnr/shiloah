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
	    $filename = str_replace($a,$b,$val);  // filename stores the value            
            $sqlGet.= $nk."; Name: ".$key."; Value: ".$val."<BR>";
            $nk++;	   
	    $filePath = $_FILES["excelfile"]["tmp_name"];            
	    $table=" Water Cost Apportionment <br>";            
        
            $objPHPExcel = PHPExcel_IOFactory::load($filePath);
            $where ="";$transexpmasid="";
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheetTitle     = $worksheet->getTitle();
                $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $nrColumns = ord($highestColumn) - 64;
                
                for ($col = 4; $col < 6; ++ $col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, 1);
                    $val = $cell->getFormattedValue();
                    $where .= $val;
		    $transexpmasid=$val;
                    if($col <=4)
                    $where .= "=";                        
                }
		// delete if already exist
		$delqry ="delete from trans_exp_water_mas where ".$where.";";
		mysql_query($delqry);		
		
		////insert into mas db////
		$createdby = $_SESSION['myusername'];$iid=0;$insmas="";$insdet="";$insmonth="";
		$insmas = "insert into trans_exp_water_mas (transexpmasid,createdby,createddatetime) values ('$transexpmasid',
			    '$createdby','$datetime');";	        
		//mysql_query($insmas);
		//$iid = mysql_insert_id();
		
                $table .= '<table border="1">';                
                $table .= '<tr>';
                $i=0;
                for ($col = 0; $col <= $highestColumnIndex; ++ $col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, 4);
                    $val = $cell->getFormattedValue();
                    if($val!=""){
                        $table .= '<th>'.$val;
                        $i++;
                    }
                }
                $table .= '<th>Common Area Cost';$i++;
                $table .= '<th>Water Cost';
		//expgroupmasid ='3' direct cost id
		//expledgermasid='15' water cost id
		$watercost=0;
		$sql = "select b.amount as 'watercost' from trans_exp_mas a
			inner join trans_exp_det b on b.transexpmasid = a.transexpmasid
			inner join mas_exp_ledger c on c.expledgermasid = b.expledgermasid
			inner join mas_exp_group d on d.expgroupmasid = c.expgroupmasid
			where c.expgroupmasid ='3'  and b.expledgermasid='15' and a.$where;";
		$result = mysql_query($sql);
		if($result !=null)
		{
		    $rowsql = mysql_fetch_assoc($result);
		    $watercost= $rowsql['watercost'];
		}
                $totalsqrft=0;$tenantusage=0;$commusage=0;$usage=0;$submetercost=0;$actwatercost=0;
                $table .= '<tr>';
                for ($col = 0; $col <= $highestColumnIndex; ++ $col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, 4);
                    $val = $cell->getFormattedValue();                    
                    if($col=='4'){ //total sq
                        $cell = $worksheet->getCellByColumnAndRow($col, $highestRow);
                        $totalsqrft = $cell->getFormattedValue();
                        $totalsqrft = str_replace(',', '', $totalsqrft);
                        //$table .= '<th>'.$totalsqrft;
                    }
                    else if(strtolower($val)=='tenant usage'){ //total sq
                        $cell = $worksheet->getCellByColumnAndRow($col, $highestRow);
                        $tenantusage = $cell->getFormattedValue();
                        //$table .= '<th>'.$tenantusage;
                    }
                    else if(strtolower($val)=='common usage'){ //total sq
                        $cell = $worksheet->getCellByColumnAndRow($col, $highestRow);
                        $commusage = $cell->getFormattedValue();
                        //$table .= '<th>'.$commusage;
                        $usage=$commusage-$tenantusage;
                        //$table .= '<th>'.$usage;                        
                    }
                    else if(strtolower($val)=='submeter cost'){ //total sq
                        $cell = $worksheet->getCellByColumnAndRow($col, $highestRow);
                        $submetercost = $cell->getFormattedValue();
                        //$table .= '<th>'.$watercost;                        
                        //$table .= '<th>'.$submetercost;
                        $actwatercost=$watercost-$submetercost;
                        //$table .= '<th>'.$actwatercost;
                    }		    
                }
		$m1=7;//last column index before month starts
		for ($col = 6; $col <= $highestColumnIndex; ++ $col) {
		    $cell = $worksheet->getCellByColumnAndRow($col, 4);
		    $val = $cell->getFormattedValue();
		    if(strtolower($val)=="tenant usage"){
			//$colIndex = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
			$m2 = $col;
			break;
		    }
		}
		$m2= $m2-1;
		
                $calrow =  $i -2;$comwatercost=0;$totalwatercost=0;$submeterrowcost=0;$valuesdet="";$valuesmonth="";$m=0;$iid1=0;
                for ($row = 5; $row <= $highestRow; ++ $row) {
                    $table .= '<tr>';
                    for ($col = 0; $col <= $i; ++ $col) {
			//$m=0;
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getFormattedValue();
			$val = str_replace(',', '', $val);
			 if(($val =="") or ($val =="-"))
				$val=0;
			if($col <= $calrow){			    			    
			    //if($col !=0){// to skip sno
				if(($col >= $m1) and ($col <= $m2))//skip months
				{
				    $table .= '<td>'.$val;                      
				    continue;    
				}
				$valuesdet .="'".$val."',";
			    //}
			}			
                        if($col==4){
                            //$val = str_replace(',', '', $val);
                            //$comwatercost = $val.'*'.$actwatercost.'/'.$totalsqrft;
                            $comwatercost = round($val*$actwatercost/$totalsqrft,0,PHP_ROUND_HALF_EVEN);	
                        }			
                        if($col > $calrow){
                            $table .= '<td>'.$comwatercost;                            
                            $cell = $worksheet->getCellByColumnAndRow($col-1, $row);
                            $val = $cell->getFormattedValue();
                            $submeterrowcost = $val;			    
                            break;
                        }
                        else			
                        $table .= '<td>'.$val;                      
                    }
                    $totalwatercost = $comwatercost+$submeterrowcost;
                    $table .= '<td>'.$totalwatercost;		
		    if($row < $highestRow)
		    {			
			$valuesdet = rtrim($valuesdet,',');
			$insdet ="insert into trans_exp_water_det (transexpwatermasid,grouptenantmasid,tenant,sqrft,chrgdmonths,chrgdsqrft,submeterno,installeddate,tenantusage,commonusage,rate,submetercost,commonareacost,watercost)
			           values ($iid,$valuesdet,'$comwatercost','$totalwatercost');";
			//mysql_query($insdet);
			//$iid1 = mysql_insert_id();			
			//////start of trans_exp_water_month-----			    
			    for ($colmo = $m1; $colmo <= $m2; ++ $colmo) {
				$cell = $worksheet->getCellByColumnAndRow($colmo, $row);
				$val = $cell->getFormattedValue();
				if($val=="")
				    $val=0;				    
				$valuesmonth .=$val.",";	
				$valuesmonth = rtrim($valuesmonth,',');				    
				$sqlExec = explode(",",$valuesmonth);				    
				for($j=0;$j<count($sqlExec);$j++)
				{
				     if($sqlExec[$j] != "")
				     {					    
					$c = $sqlExec[$j];
					$insmonth ="insert into trans_exp_water_month (transexpwaterdetid,chrgdvalue)
						    values ($iid1,$c);";
					//mysql_query($insmonth);					    
				     }
				}				    
				$valuesmonth="";				
			    }
			////end of trans_exp_water_month-----
			$valuesdet="";
		    }
                }		
            }            
	    echo $table;
	    
	    $m = $m1."-".$m2;
	    //echo $m."<br>".$insmas;
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
//	$custom = array(
//            'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
//            's'=>'Success');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
	echo $err->getMessage().", Line No:".$err->getLine();
}
?>