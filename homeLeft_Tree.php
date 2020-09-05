<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>homeTop</title>
	<!-- Include style css files and jquery json files info. -->
	<?php
		include('MasterRef.php');
		include('config.php');
	?>
</head>
<body>
<ul id="browser" class="filetree">
		<li><span class="folder">Excel Upload</span>
			<ul>
				<li>
					<span class="file">
						<a id="importEcel"  target="content" href="excel/importExcel.php">Import Excel</a>
					</span>
					<span class="file">
						<a id="exportExcel"  target="content" href="excel/exportExcel.php">Export Ecel</a>
					</span>
				</li>
			</ul>
		</li>
		<li><span class="folder">PMS</span>
			<ul>
				<li><span class="folder">Transactions</span>
					<ul id="folder21">
						<li>
							<span class="file">Offer Letter</span>
							<span class="file">Invoice</span>
						</li>
					</ul>
				</li>
				<li><span class="folder">Reports</span>
					<ul id="folder21">
						<li>
							<span class="file">Invoice</span><br>
							<span class="file">Cost Centre </span>
						</li>
					</ul>
				</li>
			</ul>
		</li>
		<li><span class="folder">Payroll</span>
			<ul>
				<li><span class="folder">Transactions</span>
					<ul id="folder21">
						<li>
							<span class="file">Offer Letter</span>
							<span class="file">Invoice</span>
						</li>
					</ul>
				</li>
				<li><span class="folder">Reports</span>
					<ul id="folder21">
						<li>
							<span class="file">Invoice</span>
							<span class="file">Cost Centre </span>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
</body>
</html>
