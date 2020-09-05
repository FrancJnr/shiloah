<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Block Master</title>
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
   /*  (function($) {
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
 }); */
	$('#dataManipDiv').hide();
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"			
	});
        $('#floorname').blur(function() {
            $(this).val($(this).val().toUpperCase());
        });
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Floor");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectFloor").hide();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
		$('input[type=text]').val('');
                $('#blockmasid').empty();
		$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
                var url="load_floor.php?item=loadBuilding";					
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
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Floor");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectFloor").show();
		$("#floormasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');	
		$("#active").removeAttr('checked')
                $('#buildingmasid').empty();
		$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
		$('#blockmasid').empty();
		$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
                var url="load_floor.php?item=loadFloor";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#floormasid').empty();
						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
						$.each(data.myResult, function(i,response){
							$('#floormasid').append( new Option(response.floorname,response.floormasid,true,false) );
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
                if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please select building");return false;
		}
		if(jQuery.trim($("#blockmasid").val()) == "")
		{
			alert("Please select block");return false;
		}
                if(jQuery.trim($("#floorname").val()) == "")
		{
			alert("Please enter floor name");return false;
		}
		if(jQuery.trim($("#floordescription").val()) == "")
		{
			alert("Please enter floor description");return false;
		}
		var url="save_floor.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{						
                                                $('#buildingmasid').val("");
                                                $('#blockmasid').empty();
                                                $('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
                                                $('input[type=text]').val('');						
					}
					else
					{
						alert(response.msg);
						$("#cc").html(response.msg);
						
					}
					//alert(response.msg);
					//$("#cc").html(response.msg);
				});
		});
	});
	
	$('#btnUpdate').click(function(){
                if(jQuery.trim($("#floormasid").val()) == "")
		{
			alert("Please select floor");return false;
		}
		if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please select building");return false;
		}
		if(jQuery.trim($("#blockmasid").val()) == "")
		{
			alert("Please select block");return false;
		}
                if(jQuery.trim($("#floorname").val()) == "")
		{
			alert("Please enter floor name");return false;
		}
		if(jQuery.trim($("#floordescription").val()) == "")
		{
			alert("Please enter floor description");return false;
		}
		var url="save_floor.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{
					$('#buildingmasid').empty();
					$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
					$('#blockmasid').empty();
					$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
					var url="load_floor.php?item=loadFloor";					
						$.getJSON(url,function(data){
							$.each(data.error, function(i,response){
								if(response.s == "Success")
								{
									$('#floormasid').empty();
									$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
									$.each(data.myResult, function(i,response){
										$('#floormasid').append( new Option(response.floorname,response.floormasid,true,false) );
									});
								}
								else
								{
									alert(response.s);
								}
							});		
						});
					 $('input[type=text]').val('');
					//alert(response.msg);
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
	});
	$("#buildingmasid").change(function(){
		var $buildingmasid = $('#buildingmasid').val();
		if($buildingmasid !="")
		{
			var url="load_floor.php?item=loadBuildingBlock&itemval="+$buildingmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#blockmasid').empty();
						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
						$.each(data.myResult, function(i,response){
							$('#blockmasid').append( new Option(response.blockname,response.blockmasid,true,false) );
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
                        $('#blockmasid').empty();
			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
		}
        });
	$("#floormasid").change(function(){
		var $floormasid = $('#floormasid').val();
		$('#floorname').focus();
		if($floormasid !="")
		{
                         var url="load_floor.php?item=loadBuilding";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                                $('#buildingmasid').empty();
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
                         var url="load_floor.php?item=loadFloorBlock&itemval="+$floormasid;
                                                        $.getJSON(url,function(data){
                                                                $.each(data.error, function(i,response){
                                                                        if(response.s == "Success")
                                                                        {
                                                                                 $('#blockmasid').empty();
                                                                                $.each(data.myResult, function(i,response){
                                                                                        $('#blockmasid').append( new Option(response.blockname,response.blockmasid,true,false) );
                                                                                });
                                                                        }
                                                                        else
                                                                        {
                                                                                alert(response.s);
                                                                        }
                                                                });		
                                                        });
			var url="load_floor.php?item=details&itemval="+$floormasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            $.each(data.myResult, function(i,response){
                                                    $("#buildingmasid").val(response.buildingmasid);
                                                    $("#blockmasid").val(response.blockmasid);
                                                    $("#floorname").val(response.floorname);
						    $("#floordescription").val(response.floordescription);
                                            });
					}
					else
					{
						alert(response.s);
                                                $('#blockmasid').empty();
                                                $('#buildingmasid').empty();
						$('input[type=text]').val('');	
					}
				});             
                        });
		}
		else
		{
			$('#blockmasid').empty();
                        $('#buildingmasid').empty();
                        $('input[type=text]').val('');	
		}
	}); 
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Floor Master</h1>
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
							<th>Block</th>
                                                        <th>Floor</th>
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
					$sql = "SELECT a.*, b.buildingname, c.blockname\n"
                                                . "from mas_floor a\n"
                                                . "inner join mas_building b on a.buildingmasid = b.buildingmasid\n"
                                                . "inner join mas_block c on a.blockmasid = c.blockmasid\n"
                                                . "where a.companymasid =a.companymasid=$companymasid";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									$buildingname = $row["buildingname"];
                                                                        $blockname = $row["blockname"];
                                                                        $floorname = $row["floorname"];
                                                                        
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
									<td>".$blockname."</td>
                                                                        <td>".$floorname."</td>
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
							<th>Block</th>
                                                        <th>Floor</th>
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
				Create New Floor	
			</th>
		</tr>
	</thead>
	<tbody>
        <tr id="selectFloor">
		<td>
			Select Floor <font color="red">*</font>
		</td>
		<td>
			<select id="floormasid" name="floormasid">
				<option value="" selected>--Select Floor--</option>
			</select>
		</td>
	</tr>
        <tr id="selectBuilding">
		<td>
			Select Building <font color="red">*</font>
		</td>
		<td>
			<select id="buildingmasid" name="buildingmasid">
				<option value="" selected>--Select Building--</option>
			</select>
		</td>
	</tr>
	<tr id="selectBlock">
		<td>
			Select Block <font color="red">*</font>
		</td>
		<td>
			<select id="blockmasid" name="blockmasid">
				<option value="" selected>--Select Block--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Floor Name  <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="floorname" name="floorname">
		</td>
	</tr>
	<tr>
		<td>
			Description  <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="floordescription" name="floordescription">
		</td>
	</tr>	
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Create New Floor</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Floor</button>
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
