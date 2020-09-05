<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>home top</title>
	<!-- Include style css files and jquery json files info. -->
	<?php
		session_start();
		include('MasterRef.php');
		include('config.php');
		$sql = "select empname from mas_employee where empmasid = '".$_SESSION['empmasid']."'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$empname = $row['empname'];
		}
		//echo($_SESSION['mycompany']."------".$_SESSION['mycompanymasid']);
	?>
	<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		//var myDate = new Date();
		//var displayDate = (myDate.getDate())+ '-' + (myDate.getMonth()+1)  + '-' + myDate.getFullYear();
		//$("#dateTd").append(displayDate);
		var MyDate = new Date();
		var MyDateString;
		//MyDate.setDate(MyDate.getDate() + 20);
		MyDateString = ('0' + MyDate.getDate()).slice(-2) + '-'
				+ ('0' + (MyDate.getMonth()+1)).slice(-2) + '-'		
				+ MyDate.getFullYear();
		$("#dateTd").append(MyDateString);
	});
	</script>
<style>
body { 
	background:rgba(213,246,255,1);
	/*background: url(images/sky.jpg) no-repeat center center fixed; 
	 -webkit-background-size: cover; 
	 -moz-background-size: cover; 
	 -o-background-size: cover; 
	 background-size: cover; 
	 filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.earth_1920X1200.jpg', sizingMethod='scale'); 
	 -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='earth_1920X1200.jpg', sizingMethod='scale')"; 
	 width: 100%; 
	 height: 100%;
	color:#000000;
	font-family: Courier New;
	font-size: 20px;*/
} 
</style>
</head>
<body bgcolor='#f0f0f0'>
	<div align="middle">
		<table>
			<tr>
				<td><h1 style='font-family: Arial;font-size: 20px;'><u><?php echo(strtoupper($_SESSION['mycompany']));?>.</u></td>
			</tr>
		</table>
	</div>
	<div align="right">
		<table>
			<tr style='font-weight:bolder;'>
				<td colspan='4'>A/c Year: <?php echo $_SESSION['fiscalyear']; ?></td>				
			</tr>
			<tr style='font-weight:bolder;'>
				<td><font color='green'>Welcome Mr.<?php echo $empname; ?></font></td>
				<td colspan ="2" id="dateTd"> | Date  : </td>
				<td> | <a target="_top" href="index.php" ><font color='red'>Logout</font></a></td>
			</tr>
		</table>
	</div>
	<div align="left">
		<table>			
			<!--<tr>
				<td colspan="3">
				<?php
					if($_SESSION['fiscalyearto'] != "0")
					{
						$date1 = date("y-m-d");
						$date2 =  date_format(new DateTime($_SESSION['fiscalyearto']), "y-m-d");
						$days = (strtotime($date2) - strtotime($date1)) / (60 * 60 * 24);
						if($days <= 86)
						{
							echo "Days left to change A/c year:  ".$days." days";
						}
					}
					else
					{
						echo "No Accounting Year Please set to Procedd";
					}
					
				?>
				</td>
			</tr>-->
		</table>
		<hr color="brown">
	</div>	
</body>
</html>
