<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Shop Master</title>
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
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		//"sPaginationType": "full_numbers"				
	});
        $('#blockname').blur(function() {
            $(this).val($(this).val().toUpperCase());
        });
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Block");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectShop").hide();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
		$('input[type=text]').val('');
                $('#blockmasid').empty();
		$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
		$('#floormasid').empty();
		$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
		       
                var url="load_shop.php?item=loadBuilding";					
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
		$("#tblheader").text("Edit Existing Block");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectShop").show();
		$("#blockmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');
		$("#active").removeAttr('checked')
		$('#buildingmasid').empty();
		$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
		$('#blockmasid').empty();
		$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
		$('#floormasid').empty();
		$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
		var url="load_shop.php?item=loadShop";					
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
                                        $('#shopmasid').empty();
					$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
					$.each(data.myResult, function(i,response){
                                                $('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
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
		if(jQuery.trim($("#floormasid").val()) == "")
		{
			alert("Please select floor");return false;
		}
		if(jQuery.trim($("#shopcode").val()) == "")
		{
			alert("Please enter shop name");return false;
		}
		if(jQuery.trim($("#size").val()) == "")
		{
			alert("Please enter size");return false;
		}
		var url="save_shop.php";
		$("#action").val("Save");
		$('form').ajaxSubmit({
			url:url,
			querystring:"?action='save'",
			data:$(this).serialize(),
			datatype:"json",
			beforeSubmit: function() {
			    $('#results').html('Submitting...');
			},
			success: function(data) {
			//alert(data);
			$('input[type=text]').val('');
			$('#buildingmasid').empty();
			$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
			$('#blockmasid').empty();
			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
			$('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
			var url="load_shop.php?item=loadBuilding";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                        alert(response.msg);
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
			    //var $out = $('#cc');
			    //$out.html('Your results:');
			    //$out.append('<p>'+ data +'</p>');
			}
		});
	});
	$('#size').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#btnUpdate').click(function(){
		if($("#shopmasid option:selected").val()== "")
		{
			alert("Please select shop");return false;
		}
                 if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please select building");return false;
		}
		if(jQuery.trim($("#blockmasid").val()) == "")
		{
			alert("Please select block");return false;
		}
		if(jQuery.trim($("#floormasid").val()) == "")
		{
			alert("Please select floor");return false;
		}
		if(jQuery.trim($("#shopcode").val()) == "")
		{
			alert("Please enter shop name");return false;
		}
		if(jQuery.trim($("#size").val()) == "")
		{
			alert("Please enter size");return false;
		}
		var url="save_shop.php";
		$("#action").val("Update");
		$('form').ajaxSubmit({
			url:url,
			querystring:"?action='Update'",
			data:$(this).serialize(),
			datatype:"json",
			beforeSubmit: function() {
			    $('#results').html('Submitting...');
			},
			success: function(data) {
			   alert(data);
			$('input[type=text]').val('');
			$('#buildingmasid').empty();
			$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
			$('#blockmasid').empty();
			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
			$('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
			var url="load_shop.php?item=loadShop";					
			$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
                                        $('#shopmasid').empty();
					$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
					$.each(data.myResult, function(i,response){
                                                $('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
					});
				}
				else
				{
					alert(response.s);
					
				}
			});		
		});
			    //var $out = $('#cc');
			    //$out.html('Your results:');
			    //$out.append('<p>'+ data +'</p>');
			}
		});
	});
	$("#shopmasid").change(function(){
		var $shopmasid = $('#shopmasid').val();
		$('#shopcode').focus();
		if($shopmasid !="")
		{
                        var url="load_shop.php?item=loadBuilding";					
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
                         var url="load_shop.php?item=loadShopBlock&itemval="+$shopmasid;
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
			 var url="load_shop.php?item=loadShopFloor&itemval="+$shopmasid;
                                                        $.getJSON(url,function(data){
                                                                $.each(data.error, function(i,response){
                                                                        if(response.s == "Success")
                                                                        {
                                                                                 $('#floormasid').empty();
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
			var url="load_shop.php?item=details&itemval="+$shopmasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            $.each(data.myResult, function(i,response){
                                                    $("#buildingmasid").val(response.buildingmasid);
                                                    $("#blockmasid").val(response.blockmasid);
                                                    $("#shopcode").val(response.shopcode);
						    $("#size").val(response.size);
						    $("#facing").val(response.facing);
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
                                                $('#buildingmasid').empty();
						$('#blockmasid').empty();
						$('#floormasid').empty();
						$('input[type=text]').val('');	
					}
				});             
                        });
		}
		else
		{
                        $('#buildingmasid').empty();
			$('#blockmasid').empty();
			$('#floormasid').empty();
                        $('input[type=text]').val('');	
		}
	});  
	$("#buildingmasid").change(function(){
		var $buildingmasid = $('#buildingmasid').val();
		if($buildingmasid !="")
		{
			var url="load_shop.php?item=loadBuildingBlock&itemval="+$buildingmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#blockmasid').empty();
						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
						$('#floormasid').empty();
						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
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
			//alert("Please select Building");
			$('input[type=text]').val('');
                        $('#blockmasid').empty();
			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
			$('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
		}
        });
	$("#blockmasid").change(function(){
		var $blockmasid = $('#blockmasid').val();
		if($blockmasid !="")
		{
			var url="load_shop.php?item=loadBlockFloor&itemval="+$blockmasid;					
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
						alert(response.msg);
						$('input[type=text]').val('');
						$('#floormasid').empty();
						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
					}
				});             
                        });
		}
		else
		{
			//alert("Please select Building");
			$('input[type=text]').val('');
                        $('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
		}
        });
	$("input:file").change(function (){
		var fileid = $(this).attr('id');
		var byteSize = this.files[0].size;
		var suffix = 'KB';
		if (byteSize > 1000) {
			byteSize = (byteSize / (1024*1024)).toFixed(2);
			suffix = 'MB';
			if(parseInt(byteSize) > 20)
			{
				alert("File size shuolbe below 17 MB, Uploaded file size:"+byteSize + ' ' + suffix);
				$(this).val('');
				return false;
			}
		}
		if(fileid =="shopimage[]")
		{		
			var AllowedExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
			var msg = "Only \'jpeg\', \'jpg\', \'png\', \'gif\', \'bmp\' are allowed."; 
		}
		else
		{
			var AllowedExtension = ['mp3', 'mp4'];
			var msg = "Only \'mp3\', \'mp4\' are allowed.";
		}
		if ($.inArray($(this).val().split('.').pop().toLowerCase(), AllowedExtension) == -1)
		{
			alert(msg);
			$(this).val('');
		} 				       
	});
});
</script>		
</head>

<body id="dt_example">
<form action="" enctype="multipart/formdata" method="post">
<div id="container">
<h1>Shop Master</h1>
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
							<th>Shop</th>
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
					$sql = "select a.*, b.buildingname\n"
                                                . "from mas_shop a\n"
                                                . "inner join mas_building b on a.buildingmasid = b.buildingmasid\n"
                                                . "where a.companymasid=$companymasid";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									$buildingname = $row["buildingname"];
                                                                        $shopcode = $row["shopcode"];
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
									<td>".$shopcode."</td>
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
							<th>Shop</th>
							<th>Created BY</th>
							<th>Date Time</th>
							<th>Modified BY</th>
							<th>Date Time</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New Shop	
			</th>
		</tr>
	</thead>
	<tbody>
		<tr id="selectShop">
	<td>
		Select Shop <font color="red">*</font>
	</td>
	<td>
		<select id="shopmasid" name="shopmasid">
			<option value="" selected>----Select Shop----</option>
		</select>
	</td>
	</tr>
        <tr id="selectBuilding">
		<td>
			Select Building <font color="red">*</font>
		</td>
		<td>
			<select id="buildingmasid" name="buildingmasid">
				<option value="" selected>----Select Building----</option>
			</select>
		</td>
	</tr>
	<tr id="selectBlock">
		<td>
			Select Block <font color="red">*</font>
		</td>
		<td>
			<select id="blockmasid" name="blockmasid">
				<option value="" selected>----Select Block----</option>
			</select>
		</td>
	</tr>
	<tr id="selectFloor">
	<td>
		Select Floor <font color="red">*</font>
	</td>
	<td>
		<select id="floormasid" name="floormasid">
			<option value="" selected>----Select Floor----</option>
		</select>
	</td>
	</tr>
	<tr>
		<td>
			Shop Code  <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="shopcode" name="shopcode">
		</td>
	</tr>
	<tr>
		<td>
			Shop Size (sqrft)<font color="red">*</font>
		</td>
		<td>
			<input type="text" id="size" name="size">
		</td>
	</tr>
	<tr>
		<td>
			Shop Facing
		</td>
		<td>
			<input type="text" id="facing" name="facing">
		</td>
	</tr>
	<tr>
		<td>
			Img 1
		</td>
		<td>
			<input type="file" id="shopimage[]" name="shopimage[]" />
		</td>
	</tr>
	<tr>
		<td>
			Img 2
		</td>
		<td>
			<input type="file" id="shopimage[]" name="shopimage[]" />
		</td>
	</tr>
	<tr>
		<td>
			Img 3
		</td>
		<td>
			<input type="file" id="shopimage[]" name="shopimage[]" />
		</td>
	</tr>
	<tr>
		<td>
			Img 4
		</td>
		<td>
			<input type="file" id="shopimage[]" name="shopimage[]" />
		</td>
	</tr>
	<tr>
		<td>
			Video
		</td>
		<td>
			<input type="file" id="video[]" name="video[]" />
		</td>
	</tr>
	<tr>
		<td>
			Active
		</td>
		<td>
			<input type="checkbox" id="active" name="active" checked>
		</td>
	</tr>
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Create New Shop</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Shop</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->

<label id="cc"></label>
<input type="hidden" name="action" id="action" value="" />
</form>
</body>
</html>
