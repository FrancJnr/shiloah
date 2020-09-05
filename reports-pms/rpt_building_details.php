<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>BUILDING DETAILS</title>
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
?>
<style type="text/css" media="screen">
@import "../css/Site.css";
@import "../css/TableTools.css";
div.dataTables_wrapper { font-size: 13px; }
table.display thead th, table.display td { font-size: 13px; }
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers",
                "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		//"sDom": 'T<"clear">lfrtip',
                "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay )
                {
                        /*
                         * Calculate the total market share for all browsers in this table (ie inc. outside
                         * the pagination)
                         */
                        var iTotalMarket = 0;
                        for ( var i=0 ; i<aaData.length ; i++ )
                        {
                                iTotalMarket += parseFloat(aaData[i][6]);
                        }
                        
                        /* Calculate the market share for browsers on this page */
                        var iPageMarket = 0;
                        for ( var i=iStart ; i<iEnd ; i++ )
                        {
                                iPageMarket += parseFloat(aaData[ aiDisplay[i] ][6]);
                        }
                        
                        /* Modify the footer row to match what we want */
                        var nCells = nRow.getElementsByTagName('th');
                        //nCells[1].innerHTML = iTotalMarket +" ("+iPageMarket+")";
                        nCells[1].innerHTML = iTotalMarket; //PAGE TOTAL
                        nCells[3].innerHTML = iPageMarket; // FILTER TOTAL                        
                }
	});
	$('#btnPrint').click(function(){
		$('.printable').print();
	});
});
</script>
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>BUILDING WISE SHOP DETAILS</h1><br><br>
<button id='btnPrint'>Print</button>
<div id="exampleDiv" width="100%">
<p class='printable'>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
        <thead>
                <tr>
                        <th>Index</th>							
                        <th>Building</th>
                        <th>Block</th>
                        <th>Floor</th>
                        <th>Shop</th>
                        <th>Tenant</th>
                        <th>Sqrft</th>                        
                </tr>
        </thead>
        <tbody id="tbodyContent">
        <?php
        $tr="";$i=1;
        $companymasid  = $_SESSION['mycompanymasid'];
        $sql = " select a.shopcode,a.buildingmasid,a.blockmasid,a.floormasid,a.facing,a.size,\n"
            . " b.buildingname,c.blockname,d.floordescription from mas_shop a \n"
            . " inner join mas_building b on b.buildingmasid=a.buildingmasid\n"
            . " inner join mas_block c on c.blockmasid = a.blockmasid\n"
            . " inner join mas_floor d on d.floormasid = a.floormasid\n"
            . " where a.companymasid=$companymasid";
        $result = mysql_query($sql);
        if($result !=null )
        {
            while($row = mysql_fetch_assoc($result))
            {
               $tr =  "<tr>
                            <td class='center'>".$i++."</td>
                            <td>".$row['buildingname']."</td>
                            <td>".$row['blockname']."</td>
                            <td>".$row['floordescription']."</td>
                            <td>".$row['shopcode']."</td>
                            <td>".$row['facing']."</td>
                            <td>".$row['size']."</td>
                      </tr>
                ";
                echo $tr;
            }
        }
        ?>
        </tbody>
       <tfoot>
		<tr>
			<th style="text-align:right" colspan="4"><strong>Page Total:</strong></th>
                        <th id='total'></th>
                        <th style="text-align:right"><strong>Filter Total:</strong></th>
                        <th id='total'></th>
		</tr>
	</tfoot>
    </table>
</p>
</div>
</form>
</body>
</html>