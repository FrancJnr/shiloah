<?php

$db_host="localhost";
$db_name="orangehrm_mysql";
/*$username="shiloah";
$password="shiloah123"; */
$username="hr";
$password="trymenot#123";

$db_con=mysql_connect($db_host,$username,$password);
$connection_string=mysql_select_db($db_name);
// Connection
mysql_connect($db_host,$username,$password);
mysql_select_db($db_name);


	
?>
