<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"itax_sales.csv\"");
$data=    htmlspecialchars_decode(stripslashes($_REQUEST['csv_text']));
echo $data; 
?>