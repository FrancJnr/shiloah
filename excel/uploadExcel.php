<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
		require_once '../Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = PHPExcel_IOFactory::load("MyExcel.xlsx");
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
		    $worksheetTitle     = $worksheet->getTitle();
		    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
		    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		    $nrColumns = ord($highestColumn) - 64;
		    echo "<br>The worksheet ".$worksheetTitle." has ";
		    echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
		    echo ' and ' . $highestRow . ' row.';
		    echo '<br>Data: <table border="1"><tr>';
		    for ($row = 1; $row <= $highestRow; ++ $row) {
			echo '<tr>';
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
			    $cell = $worksheet->getCellByColumnAndRow($col, $row);
			    $val = $cell->getValue();
			    $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
			    echo '<td>' . $val . '<br>(Typ ' . $dataType . ')</td>';
			}
			echo '</tr>';
		    }
		    echo '</table>';
		}
		
$sqlGet ="";
$nk =0;
foreach ($_GET as $k=>$v) {
    $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
    $nk++;
}
$custom = array('msg'=> $sqlGet ,'s'=>'Success');
$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
		exit;
		/********************************************************************************************/
		/* Code at http://legend.ws/blog/tips-tricks/csv-php-mysql-import/
		/* Edit the entries below to reflect the appropriate values
		/********************************************************************************************/
		$databasehost = "localhost";
		$databasename = "shiloahmsk";
		$databasetable = "mas_company";
		$databaseusername ="shiloahmsk";
		$databasepassword = "mysql";
		$fieldseparator = ",";
		$lineseparator = "\n";
		$csvfile = "C:\Users\Admin\Documents\mas_company.csv";
		/********************************************************************************************/
		/* Would you like to add an ampty field at the beginning of these records?
		/* This is useful if you have a table with the first field being an auto_increment integer
		/* and the csv file does not have such as empty field before the records.
		/* Set 1 for yes and 0 for no. ATTENTION: don't set to 1 if you are not sure.
		/* This can dump data in the wrong fields if this extra field does not exist in the table
		/********************************************************************************************/
		$addauto = 0;
		/********************************************************************************************/
		/* Would you like to save the mysql queries in a file? If yes set $save to 1.
		/* Permission on the file should be set to 777. Either upload a sample file through ftp and
		/* change the permissions, or execute at the prompt: touch output.sql && chmod 777 output.sql
		/********************************************************************************************/
		$save = 1;
		$outputfile = "output.sql";
		/********************************************************************************************/
		
		
		if(!file_exists($csvfile)) {
			echo "File not found. Make sure you specified the correct path.\n";
			exit;
		}
		
		$file = fopen($csvfile,"r");
		
		if(!$file) {
			echo "Error opening data file.\n";
			exit;
		}
		
		$size = filesize($csvfile);
		
		if(!$size) {
			echo "File is empty.\n";
			exit;
		}
		
		$csvcontent = fread($file,$size);
		
		fclose($file);
		
		

		//$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
		//@mysql_select_db($databasename) or die(mysql_error());
		
		$lines = 0;
		$queries = "";
		$linearray = array();
		//preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
		//foreach(split($lineseparator,$csvcontent,1) as $line) {
		foreach(explode($lineseparator,$csvcontent,-1) as $line) {
		
			$lines++;
		
		         if( $lines > 1)
			 {
				$line = trim($line," \t");
				
				$line = str_replace("\r","",$line);
				
				/************************************************************************************************************
				This line escapes the special character. remove it if entries are already escaped in the csv file
				************************************************************************************************************/
				$line = str_replace("'","\'",$line);
				/***********************************************************************************************************/
				
				$linearray = explode($fieldseparator,$line);
				
				$linemysql = implode("','",$linearray);
				
				if($addauto)
					$query = "insert into $databasetable ($linemyfields) values('','$linemysql')";
				else
					$query = "insert into $databasetable ($linemyfields) values('$linemysql')";
				
				$queries .= $query . "\n";
			
			      if (!mysql_query($query))
		              {
		                 die('Error: ' . mysql_error());
		              }
			
				//@mysql_query($query);
				
				echo $query."\n";
			 }
			 else
			 {
				$line = trim($line," \t");
				
				$line = str_replace("\r","",$line);
				
				/************************************************************************************************************
				This line escapes the special character. remove it if entries are already escaped in the csv file
				************************************************************************************************************/
				$line = str_replace("'","\'",$line);
				/***********************************************************************************************************/
				
				$linearray = explode($fieldseparator,$line);
				
				$linemyfields = implode(",",$linearray);
			 }
		}
		
		return false;

		//@mysql_close($con);
		
		if($save) {
			
			if(!is_writable($outputfile)) {
				echo "File is not writable, check permissions.\n";
			}
			
			else {
				$file2 = fopen($outputfile,"w");
				
				if(!$file2) {
					echo "Error writing to the output file.\n";
				}
				else {
					fwrite($file2,$queries);
					fclose($file2);
				}
			}
			
		}
		
		echo "Found a total of $lines records in this csv file.\n";
        }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>upload excel file</title>
	
<script type="text/javascript" language="javascript">
$(document).ready(function() {
		$('button').attr('disabled','disabled');
		var btnid="";
		var fileid=""
				oTable = $('#example').dataTable({
				"bJQueryUI": true,
				"bPaginate": false,
				});
				$("input:file").change(function (){
				      var fileid = $(this).attr('id');
				      $("#tab_name").val(fileid);
				       btnid = $("#tab_name").val();
				       var fileName = $(this).val();
				       var GetExtension = fileName.substring(fileName.lastIndexOf('.'));
				       if (jQuery.trim(GetExtension).length > 0)
				       {  
						// Valid File Type  
						//var FileType = ".jpg , .png , .bmp";
						var FileType = ".xlsx";  
						// Check file type is valid or not  
						if (FileType.toLowerCase().indexOf(GetExtension) < 0)
						{
						   alert('Please select a valid file type of [<strong>example.csv</strong>] ', 'Alert Box');
						    $(this).val("");
						}  
						else
						{
						      $('button[id='+btnid+']').removeAttr('disabled');
						}  
				        }				       
				});

				$("button").click(function(){
					$("#tab_name").val($(this).attr('id'));					
					var r = confirm('Can you confirm this?');
						if (r == true)
						{
								//$('button[id='+btnid+']').attr('disabled','disabled');
								$('file[id='+fileid+']').val("");
								//$("#myForm").submit();
								
								var url="simple.php";
								$('form').ajaxSubmit({
									url:url,
									//querystring:"?tab_name="+$(this).attr('id'),
									data:$(this).serialize(),
									datatype:"json",
									beforeSubmit: function() {
									    $('#results').html('Submitting...');
									},
									success: function(data) {
										//alert(data);
										$('#cc').html(data);
									}
								});
						}
				      return false;
				});
			} );
</script>
</head>

<body id="dt_example">
<form action="" enctype="multipart/formdata" method="post">
<div id="container">
<h1>Upload Excel</h1>
<span id="cc"></span>
<div id="demo">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
			<thead>
				<tr>
					<th>Index</th>
					<th>Table</th>
					<th>Created</th>
					<th>Modified</th>
					<th>File</th>
					<th>Upload</th>
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
									<td></td>
									<td class='center'><input id= ".$table." name= upload[] type='file' />
									<td class='center'><button id=".$table.">Upload</button></td>
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
					<th>File</th>
					<th>Upload</th>
				</tr>
			</tfoot>
		</table>
</div>
</div>
		<input type="hidden" id="tab_name" name="tab_name" />
</form>
</body>
</html>
