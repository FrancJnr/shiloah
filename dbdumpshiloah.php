<?php
   $dbhost = 'localhost:3036';
   $dbuser = 'root';
   $dbpass = '';
   
   $backup_file = $dbname . date("Y-m-d-H-i-s") . '.gz';
   $command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass ". "shiloahmsk | gzip > $backup_file";
   
   system($command);
?>