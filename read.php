<?php
$uncpath = '//SYSTEM-ADMIN\backup';
$dh = opendir($uncpath);
echo "<pre>\n";
var_dump($dh, error_get_last());
echo  "\n</pre>";