<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Renewal of Tenant</title>
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
       $('[id^="btnRenewal"]').live('click', function() {	
            var r=confirm("Can you confirm this Approval?");
	    if (r == true)
	    {
                $('#cc').html("");
		var $a = $(this).attr('name');
                var url="save_renewal_tenant.php?action=renew&grouptenantmasid="+$(this).attr('val');
                var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
                    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
                            var n = "#status"+$a;
                            $(n).html('Renewed');
                            var n = "#btnCancel"+$a;
                            $(n).attr('disabled',true);
                            var n = "#btnRenewal"+$a;
                            $(n).attr('disabled',true);
			     $('#cc').html(response.msg);
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
                var url="save_renewal_tenant.php?action=cancel&grouptenantmasid="+$(this).attr('val');
                var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
                    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
                            var n = "#status"+$a;
                            $(n).html('-');
                            var n = "#btnCancel"+$a;
                            $(n).attr('disabled',true);
                            var n = "#btnRenewal"+$a;
                            $(n).attr('disabled',true);
			    $('#cc').html(response.msg);
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
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Renewal of Tenant</h1>
<div id="exampleDiv" width="100%">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>							
							<th>Tenants</th>
							<th>Shopcode</th>
							<th>Status</th>
                                                        <th>Renewal</th>
                                                        <th>Cancel</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
                                        <?php
					 $sql = "select grouptenantmasid from group_tenant_mas";
                                         $result = mysql_query($sql);
                                         $i=1;
                                         $tr = "";
                                         if($result != null)
                                         {
                                            while($row = mysql_fetch_assoc($result))
                                            {
						$grpmasid = $row['grouptenantmasid'];
						$sqldet = "select a.tenantmasid , b.leasename,c.shopcode,c.size,d.buildingname,
								case b.renewal
								when '0' then '-'
								when '1' then 'Renewed'
								end as 'status'
								from group_tenant_det a
								inner join mas_tenant b on b.tenantmasid = a.tenantmasid
								inner join mas_shop c on c.shopmasid = b.shopmasid
								inner join mas_building d on d.buildingmasid = c.buildingmasid								
								where a.grouptenantmasid =$grpmasid and b.active ='1'
								union
							select a1.tenantmasid , b1.leasename,c1.shopcode,c1.size,d1.buildingname,
								case b1.renewal
								when '0' then '-'
								when '1' then 'Renewed'
								end as 'status'
								from group_tenant_det a1
								inner join rec_tenant b1 on b1.tenantmasid = a1.tenantmasid
								inner join mas_shop c1 on c1.shopmasid = b1.shopmasid
								inner join mas_building d1 on d1.buildingmasid = c1.buildingmasid								
								where a1.grouptenantmasid =$grpmasid and b1.active ='1'
								order by leasename";
						$resultdet = mysql_query($sqldet);
						if($resultdet != null)
						{
						    $count = mysql_num_rows($resultdet);
						    $shp ="";
						    $j=1;
						    while($rowdet = mysql_fetch_assoc($resultdet))
						    {
							if($count > 1)
							{
								$shp  = $shp.",".$rowdet['shopcode']." sq:".$rowdet['size']." Combined";
								$j++;
							}
							else
							{
								$shp  = $shp.",".$rowdet['shopcode']." sq:".$rowdet['size'];
								$j++;
							}
							if($j > $count)
							{
								$shp = ltrim($shp,",");
								$tr =  "<tr>
								       <td class='center'>".$i."</td>
								       <td>".$rowdet['leasename']."</td>
								       <td>".$shp."</td>
								       <td id=status$i>".$rowdet['status']."</td>";
								if($rowdet['status'] =='-')
							       {
								   $tr .="<td class='center'><button type='button' id=btnRenewal$i name='".$i."'  val='".$grpmasid."'>Renewal</button></td>";
								   $tr .="<td class='center'><button type='button' id=btnCancel$i name='".$i."' disabled='disabled' val='".$grpmasid."'>Cancel</button></td>";
							       }
							       else
							       {
								   $tr .="<td class='center'><button type='button' id=btnRenewal$i name='".$i."'  disabled='disabled' val='".$grpmasid."'>Renewal</button></td>";
								   $tr .="<td class='center'><button type='button' id=btnCancel$i name='".$i."'  val='".$grpmasid."'>Cancel</button></td>";
							       }
							       echo $tr;
							       $i++;
							}
						    }
						}
                                            }
                                         }
					?>
                                        </tbody>
					<tfoot>
						<tr>
							<th>Index</th>							
							<th>Tenants</th>
							<th>Shopcode</th>
							<th>Status</th>
                                                        <th>Renewal</th>
                                                        <th>Cancel</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Renewal 	
			</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
</div>
<span id='cc'></span>
</div> <!--Main Div-->
</form>
</body>
</html>
