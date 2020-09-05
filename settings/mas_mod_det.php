<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Module Details</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadmodule()
    {
        $sql = "select * from mas_module";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['modulemasid'].">".$row['modulename']."</option>");		
                }
        }
    }
    function loadheader()
    {
        $sql = "select * from mas_module_header";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['moduleheadermasid'].">".$row['moduleheader']."</option>");		
                }
        }
    }
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
	    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	    "sPaginationType": "full_numbers"			
	});       
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New File");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectModulefile").hide();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
                $('#modulemasid').val("");
                $('#moduleheadermasid').val("");                
		$('input[type=text]').val('');                               
	});
        
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing File");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectModulefile").show();
		$("#moduledetmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
                $('#modulemasid').val("");
                $('#moduleheadermasid').val("");                                                
		$('input[type=text]').val('');
                var url="load_mod_det.php?item=loadModuleDet";					
                $.getJSON(url,function(data){
                    $.each(data.error, function(i,response){
                        if(response.s == "Success")
                        {
                            $('#moduledetmasid').empty();
                            $('#moduledetmasid').append( new Option("-----Select Module File-----","",true,false) );
                            $.each(data.myResult, function(i,response){
                                    $('#moduledetmasid').append( new Option(response.filename,response.moduledetmasid,true,false) );
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
		if(jQuery.trim($("#modulemasid").val()) == "")
		{
			alert("Please select module");return false;
		}
		if(jQuery.trim($("#moduleheadermasid").val()) == "")
		{
			alert("Please select header");return false;
		}
                if(jQuery.trim($("#filename").val()) == "")
		{
			alert("Please enter file name");return false;
		}
		if(jQuery.trim($("#filepath").val()) == "")
		{
			alert("Please enter file path");return false;
		}
		var url="save_mod_det.php?action=Save";
		var dataToBeSent = $("form").serialize();                
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){                                        
					if(response.s =="Success")
					{						
                                                //$('#modulemasid').val("");
                                                //$('#moduleheadermasid').val("");
                                                $('input[type=text]').val('');
                                                $('#filename').focus();
                                                $("#cc").html(response.msg);
					}
					else
					{						
						$("#cc").html(response.s);
						
					}					
				});
		});
	});
	
	$('#btnUpdate').click(function(){
                if(jQuery.trim($("#moduledetmasid").val()) == "")
		{
			alert("Please select Module File");return false;
		}
		if(jQuery.trim($("#modulemasid").val()) == "")
		{
			alert("Please select module");return false;
		}
		if(jQuery.trim($("#moduleheadermasid").val()) == "")
		{
			alert("Please select header");return false;
		}
                if(jQuery.trim($("#filename").val()) == "")
		{
			alert("Please enter file name");return false;
		}
		if(jQuery.trim($("#filepath").val()) == "")
		{
			alert("Please enter file path");return false;
		}
                var $moduledetmasid = $('#moduledetmasid').val();  
		var url="save_mod_det.php?action=Update&$moduledetmasid="+$moduledetmasid;
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{					
				    $('input[type=text]').val('');
                                    $('#filename').focus();
                                    $("#cc").html(response.msg);
				}
				else
				{
				    $("#cc").html(response.s);
				}				
			});
		});
	});	
	
        $("#moduledetmasid").change(function(){
		var $moduledetmasid = $('#moduledetmasid').val();                
		$('#filename').focus();
		if($moduledetmasid !="")
		{
                         var url="load_mod_det.php?item=loadModuleDetails&itemval="+$moduledetmasid;                         
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){                                    
					if(response.s == "Success")
					{                                                
						$.each(data.myResult, function(i,response){                                                        
                                                    $('#modulemasid').val(response.modulemasid);
                                                    $('#moduleheadermasid').val(response.moduleheadermasid);
                                                    $('#filename').val(response.filename);
                                                    $('#filepath').val(response.filepath);
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});                        
		}
		else
		{			
                        $('input[type=text]').val('');	
		}
	}); 
});
</script>		
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Module File Master</h1>
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
							<th>Module</th>
							<th>Header</th>
							<th>Name</th>
                                                        <th>Path</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select c.modulename,b.moduleheader,a.filename,a.filepath from mas_module_det a
                                                inner join mas_module_header b on b.moduleheadermasid = a.moduleheadermasid
                                                inner join mas_module c on c.modulemasid = a.modulemasid
                                                where c.active='1'";
					$result=mysql_query($sql);
					if($result != null) 
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {									
									$modulename = $row["modulename"];
									$moduleheader = $row['moduleheader'];
                                                                        $filename = $row['filename'];
                                                                        $filepath = $row['filepath'];
									
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$modulename."</td>
									<td>".$moduleheader."</td>                                                                        
									<td>".$filename."</td>
                                                                        <td>".$filepath."</td>
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
							<th>Module</th>
							<th>Header</th>
							<th>Name</th>
                                                        <th>Path</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New Module File	
			</th>
		</tr>
	</thead>
	<tbody>
        <tr id="selectModulefile">
		<td>
			Select Module File <font color="red">*</font>
		</td>
		<td>
			<select id="moduledetmasid" name="moduledetmasid">
				<option value="" selected>--Select Module File--</option>
			</select>
		</td>
	</tr>
        <tr id="selectModule">
		<td>
			Select Module <font color="red">*</font>
		</td>
		<td>
			<select id="modulemasid" name="modulemasid">
				<option value="" selected>--Select Module--</option>
                                <?php loadmodule();?>
			</select>
		</td>
	</tr>
	<tr id="selectBlock">
		<td>
			Select Header <font color="red">*</font>
		</td>
		<td>
			<select id="moduleheadermasid" name="moduleheadermasid">
				<option value="" selected>--Select Header--</option>
                                <?php loadheader();?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Module File Name  <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="filename" name="filename">
		</td>
	</tr>
	<tr>
		<td>
			File Path  <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="filepath" name="filepath">
		</td>
	</tr>	
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Create Module File</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Module File</button>
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
