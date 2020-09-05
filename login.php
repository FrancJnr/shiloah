<?php

try{		
	include('config.php');	
	session_start();
	$username = $_GET['txtUsername'];
	$password = $_GET['txtPassword'];

	$response_array = array();
	$_SESSION['myusername'] ="";
	// To protect MySQL injection (more detail about MySQL injection)
	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	
	$sql = "select * from mas_user where username='$username' and password='$password' and active ='1'";
	$result=mysql_query($sql);
	$cnt = mysql_num_rows($result);

	
	
	if($cnt > 0) // if $result <> null
	{		
		$_SESSION['myusername'] = $username;		
		while ($row = mysql_fetch_assoc($result))
		{
			$_SESSION['empmasid'] = $row['empmasid'];
			$_SESSION['myusermasid'] = $row['usermasid'];		
		}
		
	//$custom = array('msg'=> "prabhu" ,'s'=>$_SESSION['myusername']);
	//$response_array[] = $custom;
	//echo '{"error":'.json_encode($response_array).'}';
	//exit;
	//
		// reliance centre users
		$search_array_shil = array(
				'george' => 1,
				'wycliffe' => 2, 'phoebe' => 3,
				'fred' => 4, 'evans' => 5			
			);
		$search_array_grand = array('mohammed' => 1, 'typina' => 2);
		if (array_key_exists($username, $search_array_grand))
		
			$company_qry="select * from mas_company where companymasid ='2' and active ='1'";
			
		else if (array_key_exists($username, $search_array_shil))
		
			$company_qry="select * from mas_company where companymasid ='1' and active ='1'";
			
		else
			$company_qry="select * from mas_company where active ='1'";
		
		$result=mysql_query($company_qry);
		if($result != null) // if $result <> null
		{
				$rowcount = mysql_num_rows($result);
				if($rowcount > 0)
				{
					while($obj = mysql_fetch_object($result))
					{
						$arr[] = $obj;
					}
					$custom = array('msg'=>"",'s'=>"Success");
					$response_array[] = $custom;
					echo '{"error":'.json_encode($response_array).'}';
				}
				else
				{
					$custom = array('msg'=>"",'s'=>"No Company listed in DB Contact System Admin");
					$response_array[] = $custom;
					echo '{"error":'.json_encode($response_array).'}';
				}
		}
	}
	else
	{
		$custom = array('msg'=>"",'s'=>"Not a valid username or password");		
		$response_array[] = $custom;
		echo '{"error":'.json_encode($response_array).'}';
	}
	
}
catch (Exception $err)
{        
	$custom = array('msg'=>"",'s'=>"Error: ".$err->getMessage().", Line No:".$err->getLine());
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
}
?>