<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"lease_report.csv\"");
$data=    htmlspecialchars_decode(stripslashes($_REQUEST['csv_text']));
echo $data; 
?>