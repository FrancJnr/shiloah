<?php
include('../config.php');
//$tab_name = $_POST["tab_name"];
$sqlGet ="";
$nk =0;
if (! empty($_FILES['upload']))
{
    while(list($key,$val) = each($_FILES['upload']['name']))
    {
        if(!empty($val)) // this will check if any blank field is entered
	{
            $a = array(' ','#','$','%','^','&','*','?');
	    $b = array('','No.','Dollar','Percent','','and','','');
	    /////$path1 = str_replace($a,$b,$path1); // to revome specila chracters if any in file path
			
	    $filename = str_replace($a,$b,$val);  // filename stores the value
            $sqlGet.= $nk."; Name: ".$key."; Value: ".$val."<BR>";
            $nk++;
        }
    }    
}

    $filePath = "C:/".$filename;
    require_once '../Classes/PHPExcel/IOFactory.php';
    $objPHPExcel = PHPExcel_IOFactory::load($filePath);
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $worksheetTitle     = $worksheet->getTitle();
        $highestRow         = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $nrColumns = ord($highestColumn) - 64;
        
        $fileContent = "<br>The worksheet ".$worksheetTitle." has ";
        $fileContent .= $nrColumns . ' columns (A-' . $highestColumn . ') ';
        $fileContent .= ' and ' . $highestRow . ' row.';
        $tab_name = explode(".",$filename,3);
        $insert = "insert into $tab_name[0]  (";
        $cols="";
        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
            $cell = $worksheet->getCellByColumnAndRow($col, 1);
            $cols .= $cell->getValue().",";
        }
        $cols = rtrim($cols,',');
        $fileContent =$insert.$cols.") values (";
        $cell="";
        $vals="";
        $sql="";
        for ($row = 2; $row <= $highestRow; ++ $row) {            
                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $vals .= "'".$cell->getValue()."',";
                }
                $vals = rtrim($vals, ",");// remove trailing chr
                $fileContent =$insert.$cols.") values (".$vals.")";
               // mysql_query($fileContent);
                if(mysql_error()){
                    $fileContent = mysql_error();
                }
                else{
                    $sql .=$fileContent.";";
                    $vals="";
                    $fileContent =$sql;
                }
        }
    }
try{
    $sqlExec = explode(";",$fileContent);
	for($i=0;$i<count($sqlExec);$i++)
	{
	     if($sqlExec[$i] != "")
	     {
		//$result = mysql_query($sqlExec[$i]); 
	     }
	}
    $result = false;
    if($result == false)
    {
        $fileContent = mysql_error()."<br>".$sql;
    }
    else
    {
        $fileContent = "<strong><u>Data Insert Success</u></strong><br>".$sql;
        
    }
    
}
catch (Exception $err)
{
    $fileContent=$err->getMessage()."@ Line: ".$err->getLine()."<BR>".$sql;
}
echo $fileContent;
exit;
?>