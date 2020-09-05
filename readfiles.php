<?php
	$ip = 'SYSTEM-ADMIN';
	$host = gethostbyaddr( $ip );
	//die(`whoami`);
	if ( $ip == $host )
		die( 'Unable to resolve hostname from ip '.$ip );

	$path = '//'.$host.'\C:\xampp\backup\\';
	
	if ( !is_dir($path) )
		die( $path. ' is not a directory' );

	$dir = opendir($path);
	if ( $dir == FALSE )
		die( 'Cannot read '.$path );
		
	while (($file = readdir($dir)) !== FALSE)
		echo "filename: $file : filetype: ".filetype( $path.$file)."<br>";
		
	closedir( $dir );
?>