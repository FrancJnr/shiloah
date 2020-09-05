<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Legal</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    (function($) {
   $.fn.fixMe = function() {
      return this.each(function() {
         var $this = $(this),
            $t_fixed;
         function init() {
            $this.wrap('<div class="dataManipDiv" />');
          //  $this.wrap('<div class="exampleDiv" />');
          //  $this.wrap('<div class="menuDiv" />');
            $t_fixed = $this.clone();
            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
            resizeFixed();
         }
         function resizeFixed() {
            $t_fixed.find("th").each(function(index) {
               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
            });
         }
         function scrollFixed() {
            var offset = $(this).scrollTop(),
            tableOffsetTop = $this.offset().top,
            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
            if(offset < tableOffsetTop || offset > tableOffsetBottom)
               $t_fixed.hide();
            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
               $t_fixed.show();
         }
         $(window).resize(resizeFixed);
         $(window).scroll(scrollFixed);
         init();
      });
   };
})(jQuery);

//$(document).ready(function(){
   $("table").fixMe();
   $(".up").click(function() {
      $('html, body').animate({
      scrollTop: 0
   }, 2000);
 });
	$('#dataManipDiv').hide();
	
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"
	});    
	$('#btnView').click(function(){
		$('form').submit();
	});
	$('[id^="btnEdit"]').live('click', function() {
		$("#tblheader").css('background-color', '#4ac0d5');		
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();				
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');
		$('input[type=select]').val('');
		var $a = $(this).attr('name');
                var url="load_legal.php?action=details&grouptenantmasid="+$(this).attr('val');
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            var i=0;                                                                                      
                                            $.each(data.myResult, function(i,response){
							var tenantDetails = "Attn: "+response.cpname+"<br />"+response.leasename+"<br />"+response.poboxno+"-"+response.pincode+"<br />"+response.city;
							var premises = response.buildingname+" <br />"+response.blockname+"<br />"+response.floorname+" <br />"+response.shopcode;
							$('#tenantDetails').html(tenantDetails);
							$('#premises').html(premises);
							$('#shopsize').html(response.size+" Sqrft");
							$('#leasename').html(response.leasename+" ("+response.tenantcode+")");
							$("#hidLeasename").val(response.leasename);
							$('#term').html(response.term);
							$('#term').val(response.term);
							$('#doo').html(response.doo);
							$('#doc').html(response.doc);							
							$('#cc').html('');
							i++;							
                                            });
					}
					else
					{
					       $('#cc').html(response.msg);
                                               clearForm();
					}
				});             
                        });		
	});
	function clearForm()
	{		
		$('#premises').html('');
		$('#shopsize').html('');
		$('#leasename').html('');
		$('#term').html('');
		$('#term').val('');
		$('#doo').html('');
		$('#doc').html('');	
	}
	$('#btnSave').click(function(){
		if(jQuery.trim($("#age").val()) == "")
		{
			alert("Please Enter Age");
			$("#age").focus();
			return false;
		}
		var url="save_legal.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');						
					}
					else
					{
						alert(response.s);
					}
					alert(response.msg);
				});
		});
	});
	
	$('#btnUpdate').click(function(){
		if($("#agemasid option:selected").val()== "")
		{
			alert("Please select Age");return false;
		}
		if(jQuery.trim($("#age").val()) == "")
		{
			alert("Please Enter Age"); return false;
		}
		var url="save_legal.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#age").val("");
						$("#description").val("");
						$("#active").removeAttr('checked')
						alert(response.msg);
					}
					else
					{
						alert(response.s);
					}
				});
		});
	});
	
	$("#agemasid").change(function(){
		var $agemasid = $('#agemasid').val();
		$('#age').focus();
		if($agemasid !="")
		{
			var url="load_age.php?item=ageDetails&itemval="+$agemasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#age").val(response.age);
							$("#description").val(response.description);
							$act = response.active;
							if($act == "1")
							{
								$("#active").attr('checked','checked');
							}
							else
							{
								$("#active").removeAttr('checked')
							}
						});
					}
					else
					{
						alert(response.s);
						$("#age").val("");
						$("#description").val("");
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{
			alert("Please select Currency");
			$("#age").val("");
			$("#description").val("");
			$("#active").removeAttr('checked')
		}
	});
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1 style="color: red;">Legal Cases</h1>
<div id="menuDiv" width="100%" align="right">
<table>
		<tr>			
			<td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
		</tr>
	</table>
</div>
<div id='demo'></div>
<br>
<div id="exampleDiv" width="100%">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>							
							<th>Tenant</th>
							<th>Building</th>
							<th>Shop No</th>
							<th>Commncement</th>
							<th>Expiry</th>
							<th>Lease Period</th>
							<th>Outstanding Amt</th>							
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select a.aclegal, b.oplegal,d.leasename,d.tradingname,a.outstandingpayment,a.grouptenantmasid,
							DATE_FORMAT(d.doc, '%d-%m-%Y' ) AS doc,g.buildingname,h.shopcode,
							e.cpname,e.cpmobile,cplandline,cpemailid,f.age,
							@t2:= DATE_ADD(d.doc,interval @t1:=f.age year) as ag,									
							DATE_FORMAT( DATE_ADD(d.doc,interval @t1:=f.age year), '%d-%m-%Y' ) AS bg,		   
							DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt
							from trans_tenant_discharge_ac a
							inner join trans_tenant_discharge_op b on b.grouptenantmasid = a.grouptenantmasid
							inner join group_tenant_det c on c.grouptenantmasid = b.grouptenantmasid
							inner join mas_tenant d on d.tenantmasid = c.tenantmasid
							inner join mas_tenant_cp e on e.tenantmasid = d.tenantmasid
							inner join mas_age f on f.agemasid = d.agemasidlt
							inner join mas_building g on g.buildingmasid = d.buildingmasid
							inner join mas_shop h on h.shopmasid = d.shopmasid
							where a.aclegal ='1' and b.oplegal='1' and e.documentname ='1' order by a.modifieddatetime;;";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {									
									if($row['tradingname'] !="")
									$row['leasename'] = $row['tradingname'];
									$grouptenantmasid = $row['grouptenantmasid'];
									
									$active ="<button type='button' id=btnEdit$i name='".$grouptenantmasid."'  val='".$grouptenantmasid."'>Edit</button>";
									$active .="<input type='hidden' id=btnEditText$i name='".$grouptenantmasid."'  value='".$grouptenantmasid."'>";
									
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$row['leasename']."</td>
									<td>".$row['buildingname']."</td>
									<td>".$row['shopcode']."</td>
									<td>".$row['doc']."</td>
									<td>".$row['expdt']."</td>									
									<td align='right'>".number_format($row['outstandingpayment'], 0, '.', ',')."</td>
									<td>".$active."</td>
									";
									echo $tr;
								}
						}
					}		
				?>
					</tbody>
					<tfoot>
						<tr>
							<th>Index</th>							
							<th>Tenant</th>
							<th>Building</th>
							<th>Shop No</th>
							<th>Commncement</th>
							<th>Expiry</th>
							<th>Lease Period</th>
							<th>Outstanding Amt</th>							
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv" width="100%">
<table id="usertbl" class="table1" width="100%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan=4>
				Legal Proceedings:
			</th>
		</tr>
	</thead>
	<tbody>	
	<tr>
		<td>
			Land Lord:
		</td>
		<td>
			<?php echo "<b>".strtoupper($_SESSION['mycompany']); ?>
		</td>
                <td>
			Tenant:
		</td>
		<td id="leasename">
			
		</td>
	</tr>
	<tr>
		<td>
			Tenant Address:
		</td>
		<td id="tenantDetails">
			
		</td>
		<td>
			Premises:
		</td>
		<td id=premises>
			
		</td>
	</tr>
        <tr>
		<td>
			Shop Size:
		</td>
		<td id=shopsize>
			
		</td>
                <td>
			Term:
		</td>
		<td id=term>
			
		</td>
	</tr>
        <tr>
		<td>
			Date of occupation:
		</td>
		<td id=doo>
			
		</td>
                <td>
			Date of commencement:
		</td>
		<td id=doc>
			
		</td>
	</tr>
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Create New Age</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Age</button>
		</td>
	</tr>
	</tbody>
</table>
</div>
</div> <!--Main Div-->

<label id="cc">hi</label>
</form>
</body>
</html>
