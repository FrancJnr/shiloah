<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
		
				$table = ( isset($_POST['tab_name']) ) ? $_POST['tab_name'] : $_GET['tab_name'];  
				$filename = $table.".xlsx";
				
				// Get all fields names in table "mytablename" in database "mydb".
				$fields = mysql_list_fields($db_name,$table);
				
				// Count the table fields and put the value into $columns.
				$columns = mysql_num_fields($fields);
				//$columns = $columns-2;// enable to avoid modifiedby , modifieddatetime columns
				error_reporting(E_ALL);
				
				date_default_timezone_set('Europe/London');
				
				/** Include PHPExcel */
				require_once '../Classes/PHPExcel.php';
				
				
				// Create new PHPExcel object
				$objPHPExcel = new PHPExcel();
				
				// Set document properties
				$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
				
				$j=1;
				$str=65;
				//// Put the name of all fields to $out.
				////for ($i = 0; $i < $columns; $i++) { --> include mas id
				for ($i = 1; $i < $columns; $i++) {
						$l=mysql_field_name($fields, $i);
						// Add some data
						$k = chr($str).$j;
						$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue($k, $l);
						$str++;
				}
				
				// Add some data
				//$objPHPExcel->setActiveSheetIndex(0)
				//	    ->setCellValue('A1', 'Hello')
				//	    ->setCellValue('B2', 'world!')
				//	    ->setCellValue('C1', 'Hello')
				//	    ->setCellValue('D2', 'world!');
				
				// Miscellaneous glyphs, UTF-8
				//$objPHPExcel->setActiveSheetIndex(0)
				//	    ->setCellValue('A4', 'Miscellaneous glyphs')
				//	    ->setCellValue('A5', 'PRABHU');
				
				// Rename worksheet
				//$objPHPExcel->getActiveSheet()->setTitle('Simple');
				$objPHPExcel->getActiveSheet()->setTitle($table);
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				
				
				// Redirect output to a client’s web browser (Excel2007)
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header("Content-Disposition: attachment;filename=$filename");
				header('Cache-Control: max-age=0');
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;
		}
		include('../MasterRef_Folder.php');
	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>download excel file</title>
	
<script type="text/javascript" language="javascript">
$(document).ready(function() {
		$("#tab_name").val("");
		oTable = $('#example').dataTable({
			"bJQueryUI": true,
			"bPaginate": false,
		});
		$("button").click(function(){
			var tblname = $("#tab_name").val($(this).attr('id'));
			var r = confirm('Can you confirm this?');
				if (r == true)
				{
						$("#myForm").submit();
						
				}
			return false;
		});
} );
</script>
</head>

<body id="dt_example">
	
<form id="myForm" name="myForm" action="" method="post">
	
<!--	<div id="contentMiddle">-->
<div id="container">
<h1>Download Excel</h1>
		<div id="demo">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>
							<th>Table</th>
							<th>Created</th>
							<th>Modified</th>
							<th>Download</th>
						</tr>
					</thead>
					<tbody>
				<?php
					$table ="'mas_block','mas_floor','mas_shop'";
					$sql="SELECT table_schema, table_name FROM INFORMATION_SCHEMA.TABLES where table_schema='shiloahmsk' and table_name in ($table);";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									//echo $row['table_name'];
									$table = $row["table_name"];
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$table."</td>
									<td>system admin</td>
									<td class='center'></td>
									<td class='center'>
										<!--<a id=".$table." href='#'>dowload</a>-->
										<button id=".$table.">Download</button>
									</td>
									</tr>";
									echo $tr;
								}
						}
					}		
				?>
					</tbody>
					<tfoot>
						<tr>
							<th>Index</th>
							<th>Table</th>
							<th>Created</th>
							<th>Modified</th>
							<th>Download</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
<?php

?>
<input type="hidden" id="tab_name" name="tab_name" />
</form>
	<!--</div>-->
</body>
</html>
