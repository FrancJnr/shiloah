<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Building Master</title>
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
	 $('#buildingname').blur(function() {
            $(this).val($(this).val().toUpperCase());
        });
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Building");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecBuilding").hide();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
		$('input[type=text]').val('');
		
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Building");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecBuilding").show();
		$("#buildingmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');	
		$("#active").removeAttr('checked')
		
		var url="load_building.php?item=load";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#buildingmasid').empty();
						$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#buildingmasid').append( new Option(response.buildingname,response.buildingmasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
	});
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if(jQuery.trim($("#buildingname").val()) == "")
		{
			alert("Please enter building name");
			$("#buildingname")[0].focus();return false;
		}
		if(jQuery.trim($("#shortname").val()) == "")
		{
			alert("Please enter building short name");
			$("#shortname")[0].focus();return false;
		}
		if(jQuery.trim($("#municipaladdress").val()) == "")
		{
			alert("Please enter building Municipal Address");
			$("#municipaladdress")[0].focus();return false;
		}
		var a = confirm("Can you confirm this ?");
		if (a== true)
		{
			var url="save_building.php?action=Save";
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
						//$("#cc").html(response.msg);
						
					}
					alert(response.msg);
					//$("#cc").html(response.msg);
				});
			});
		}
	});
	
	$('#btnUpdate').click(function(){		
		if($("#buildingmasid option:selected").val()== "")
		{
			alert("Please select Building");
			$("#buildingmasid")[0].focus();return false;
		}
		if(jQuery.trim($("#buildingname").val()) == "")
		{
			alert("Please enter building name");
			$("#buildingname")[0].focus();return false;
		}
		if(jQuery.trim($("#shortname").val()) == "")
		{
			alert("Please enter building short name");
			$("#shortname")[0].focus();return false;
		}
		if(jQuery.trim($("#municipaladdress").val()) == "")
		{
			alert("Please enter building Municipal Address");
			$("#municipaladdress")[0].focus();return false;
		}
		var a = confirm("Can you confirm this ?");
		if (a== true)
		{
			var url="save_building.php?action=Update";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');
						$('input[type=select]').val('');						
						alert(response.msg);
					}
					else
					{
						alert(response.s);
						//$("#cc").html(response.msg);
					}					
				});
			});
		}
	});
	
	$("#buildingmasid").change(function(){
		var $buildingmasid = $('#buildingmasid').val();
		$('#buildingname').focus();
		if($buildingmasid !="")
		{
			var url="load_building.php?item=details&itemval="+$buildingmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#buildingname").val(response.buildingname);
							$("#shortname").val(response.shortname);
							$("#address1").val(response.address1);
							$("#address2").val(response.address2);
							$("#municipaladdress").val(response.municipaladdress);
							$("#city").val(response.city);
							$("#state").val(response.state);
							$("#pincode").val(response.pincode);
							$("#country").val(response.country);							
							$isvat = response.isvat;
							if($isvat == "1")
							{
								$("#isvat").attr('checked','checked');
							}
							else
							{
								$("#isvat").removeAttr('checked')
							}
							$pledged = response.pledged;
							if($pledged == "1")
							{
								$("#pledged").attr('checked','checked');
							}
							else
							{
								$("#pledged").removeAttr('checked')
							}
							$("#pledgedinbank").val(response.pledgedinbank);
						});
					}
					else
					{
						alert(response.s);
						$('input[type=text]').val('');	
					}
				});             
                        });
		}
		else
		{
			alert("Please select Building");
			$('input[type=text]').val('');	
		}
	}); 
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Building Master</h1>
<div id="menuDiv" width="100%" align="right">
<table>
		<tr>
			<td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
			<td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>
			<td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
		</tr>
	</table>
</div>
<br>
<div id="exampleDiv" width="100%">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>														
							<th>Building</th>
							<th>Created</th>
							<th>Date</th>
							<th>Modified</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$companymasid  = $_SESSION['mycompanymasid'];
					$companyname = $_SESSION['mycompany'];
					$sql = "select * from mas_building where companymasid=$companymasid";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									$buildingname = $row["buildingname"];
									$cby = $row["createdby"];
									$cdt = $row["createddatetime"];
									if(strtotime($cdt) != 0)
									{
										//$cdt = date_format(new DateTime($cdt), "D d-F-Y H:i:s");
										$cdt = date_format(new DateTime($cdt), "d-m-Y");
										//E.g. Fri 03-August-2012 13:51:37
									}
									else
									{
										$cdt="";
									}
									$mby = $row["modifiedby"];
									$mdt = $row["modifieddatetime"];
									if(strtotime($mdt) != 0)
									{
										//$mdt = date_format(new DateTime($mdt), "D d-F-Y H:i:s");
										$mdt = date_format(new DateTime($mdt), "d-m-Y");
									}
									else
									{
										$mdt = "";
									}
									$tr =  "<tr>
									<td class='center'>".$i++."</td>									
									<td>".$buildingname."</td>
									<td>".$cby."</td>
									<td>".$cdt."</td>
									<td>".$mby."</td>
									<td>".$mdt."</td>
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
							<th>Building</th>
							<th>Created</th>
							<th>Date</th>
							<th>Modified</th>
							<th>Date</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New Building	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecBuilding">
		<td>
			Select Building <font color="red">*</font>
		</td>
		<td>
			<select id="buildingmasid" name="buildingmasid">
				<option value="" selected>--Select Building--</option>
			</select>
		</td>
	</tr>	
	<tr>
		<td>
			Building Name  <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="buildingname" name="buildingname">
		</td>
	</tr>
	<tr>
		<td>
			Shortname  <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="shortname" name="shortname">
		</td>
	</tr>
	<tr>
		<td>
			Survey No
		</td>
		<td>
			<input type="text" id="address1" name="address1">
		</td>
	</tr>
	<tr>
		<td>
			P.O.Box
		</td>
		<td>
			<input type="text" id="address2" name="address2">
		</td>
	</tr>
	<tr>
		<td>
			Municipal Address <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="municipaladdress" name="municipaladdress">
		</td>
	</tr>
	<tr>
		<td>
			City
		</td>
		<td>
			<input type="text" id="city" name="city">
		</td>
	</tr>
	<tr>
		<td>
			County
		</td>
		<td>
			<input type="text" id="state" name="state">
		</td>
	</tr>
	<tr>
		<td>
			Postal code
		</td>
		<td>
			<input type="text" id="pincode" name="pincode">
		</td>
	</tr>
	<tr>
		<td>
			Country
		</td>
		<td>
			<input type="text" id="country" name="country">
		</td>
	</tr>
	<tr>
		<td>
			Is Vat Available
		</td>
		<td>
			<input type="checkbox" id="isvat" name="isvat" checked>
		</td>
	</tr>
	<tr>
		<td>
			Is Building Pledged
		</td>
		<td>
			<input type="checkbox" id="pledged" name="pledged" checked>
		</td>
	</tr>
	<tr>
		<td>
			Building Pledged In Bank 
		</td>
		<td>
			<input type="text" id="pledgedinbank" name="pledgedinbank">
		</td>
	</tr>	
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Create New Building</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Building</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->

<label id="cc"></label>
</form>
</body>
</html>
