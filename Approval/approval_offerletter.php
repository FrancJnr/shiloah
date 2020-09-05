<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Approval</title>
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
		$companymasid = $_SESSION['mycompanymasid'];
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#dataManipDiv').hide();
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]			
	});
       $('[id^="btnFinalize"]').live('click', function() {
            var r=confirm("Can you confirm this Approval?");
	    if (r == true)
	    {
                var $a = $(this).attr('name');
                var url="save_approval.php?action=finalize&grouptenantmasid="+$(this).attr('val');
                var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
                    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
                            var n = "#status"+$a;
                            $(n).html('Finalized');
                            var n = "#btnCancel"+$a;
                            $(n).attr('disabled',false);
                            var n = "#btnFinalize"+$a;
                            $(n).attr('disabled',true);
                        }				
                        else
                        {
                            alert(response.msg);
                        }                          
                    });             
                });
            }
            else
            return false;
        })
        $('[id^="btnCancel"]').live('click', function() {
            var r=confirm("Can you confirm this?");
	    if (r == true)
	    {
                var $a = $(this).attr('name');
                var url="save_approval.php?action=cancel&grouptenantmasid="+$(this).attr('val');
                var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
                    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
                            $('#cc').html(response.msg);
			    //var n = "#status"+$a;
                            //$(n).html('Draft');			    
                            var n = "#btnCancel"+$a;
                            $(n).attr('disabled',true);			    
                            //var n = "#btnFinalize"+$a;
                            //$(n).attr('disabled',false);
                        }				
                        else
                        {
                            $('#cc').html(response.msg);
                        }                        
                    });             
                });
            }
            else
            return false;
        })
	$('#btnFinalize').click(function(){
		
	});
	$('#btnCancel').click(function(){

	});
	$('#btnView').click(function(){

	});
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Cancel Offerletter</h1>
</br>
<div id="exampleDiv" width="100%">
	<span id='cc'></span> </br></br>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
		<thead>
			<tr>
				<th>Index</th>							
				<th>Tenant</th>
				<th>Shop</th>
				<th>Doc</th>
				<th>Cancel Offerletter</th>
			</tr>
		</thead>
		<tbody id="tbodyContent">
		<?php		
		 $sql = "select a.grouptenantmasid,c.tenantmasid,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,c.renewalfromid,d.shopcode,d.size from waiting_list a
			inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
			inner join mas_tenant c on c.tenantmasid =  b.tenantmasid			
			inner join mas_shop d on d.shopmasid = c.shopmasid
			inner join trans_offerletter e on e.tenantmasid = c.tenantmasid
			where c.active='1' and c.companymasid=$companymasid
			union
			select a1.grouptenantmasid,c1.tenantmasid,c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,c1.renewalfromid,d1.shopcode,d1.size from waiting_list a1
			inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
			inner join rec_tenant c1 on c1.tenantmasid =  b1.tenantmasid
			inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
			inner join trans_offerletter e1 on e1.tenantmasid = c1.tenantmasid
			where c1.active='1' and c1.companymasid=$companymasid
			group by doc;";
		 $result = mysql_query($sql);
		 $i=1;;
		 if($result != null)
		 {
		    while($row = mysql_fetch_assoc($result))
		    {			
			$grpmasid = $row['grouptenantmasid'];
			$tr =  "<tr>
				<td class='center'>".$i."</td>
				<td>".$row['leasename']."</td>
				<td>".$row['shopcode']."</td>
				<td>".$row['doc']."</td>
			        <td class='center'><button type='button' id=btnCancel$i name='".$i."'  val='".$grpmasid."'>Cancel Offletter</button></td>";
			echo $tr;
			$i++;
		    }
		 }
		?>
		</tbody>
		<tfoot>
			<tr>
				<th>Index</th>							
				<th>Tenant</th>
				<th>Shop</th>
				<th>Doc</th>
				<th>Cancel Offerletter</th>
			</tr>
		</tfoot>
	</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Finalize 	
			</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
</div>
</div> <!--Main Div-->
</form>
</body>
</html>
