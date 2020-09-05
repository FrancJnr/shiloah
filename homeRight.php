<?php
$newLink = "";
if (isset($_GET['newLink']))
{
	$newLink = $_GET['newLink'];
	setcookie("recentLinks", $newLink); 
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>home right</title>
	<!-- Include style css files and jquery json files info. -->
	<?php
		include('MasterRef.php');
		include('config.php');
		session_start();
	?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
</head>

<body>
<table id="shortcutTbl" class="table1" width="88%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan=4>
				Recent Files
			</th>
		</tr>
	</thead>
	<tbody id="recentLinkBody">
		<?php
			if($newLink !="")
			{
				$tr =  "<tr>
				<td>".$_COOKIE["recentLinks"]."</td>
				<td><a id='removeLink href='#'>Remove</a></td>";
				echo $tr;
			}
		?>
	</tbody>
</table>
<?php

?>

</body>
</html>
