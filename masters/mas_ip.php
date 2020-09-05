<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>IP Master</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadcompany()
    {
        $sql = "select companymasid, companyname from mas_company order by companymasid;";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['companymasid'].">".$row['companyname']."</option>");		
                }
        }
    }
?>
<script type="text/javascript" languip="javascript">
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
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New IP");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecIP").hide();
		$("#ip").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#ip").val("");
		$("#username").val("");
		$("#active").attr('checked','checked');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing IP");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecIP").show();
		$("#ipmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$("#ip").val("");
		$("#username").val("");
		$("#active").removeAttr('checked')
		
		var url="load_ip.php?item=loadip";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#ipmasid').empty();
						$('#ipmasid').append( new Option("-----Select ip-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#ipmasid').append( new Option(response.ipno,response.ipmasid,true,false) );
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
		if(jQuery.trim($("#ipno").val()) == "")
		{
			alert("Please Enter IP No");
			$("#ipno").focus();
			return false;
		}
		var url="save_ip.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
					    //$('input[type=text]').val('');
                                            $('#cc').html(response.msg);
					}
					else
					{
					    //alert(response.s);
                                            $('#cc').html(response.s);
					}
				});
		});
	});
	
	$('#btnUpdate').click(function(){
		if($("#ipmasid option:selected").val()== "")
		{
			alert("Please select IP");return false;
		}
		if(jQuery.trim($("#ipno").val()) == "")
		{
			alert("Please Enter IP"); return false;
		}
		var url="save_ip.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#ipno").val("");
						$("#username").val("");
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
	
	$("#ipmasid").change(function(){
		var $ipmasid = $('#ipmasid').val();
		$('#ip').focus();
		if($ipmasid !="")
		{
			var url="load_ip.php?item=ipdetails&itemval="+$ipmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#ipno").val(response.ipno);
                                                        $("#companymasid").val(response.companymasid);
							$("#username").val(response.username);
							$("#systemname").val(response.systemname);
							$("#invfilepath").val(response.invfilepath);
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
						$("#ipno").val("");
						$("#username").val("");
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{
			alert("Please select Currency");
			$("#ipno").val("");
			$("#username").val("");
			$("#active").removeAttr('checked')
		}
	});
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>IP Master</h1>
<div id="menuDiv" width="100%" align="right">
<table>
		<tr>
			<td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
			<td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>
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
							<th>Username</th>
							<th>Company</th>							
                                                        <th>Inv Filepath</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select a.*,b.companyname from mas_ip a inner join mas_company b on b.companymasid = a.companymasid;";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									//echo $row['table_name'];
									$ip = $row["ipno"];
                                                                        $companyname = $row['companyname'];
									$username = $row["username"];
									$systemname = $row["systemname"];
									$invfilepath = "//".$ip."/".$systemname."/".$row["invfilepath"];									
									$cby = $row["createdby"];
									$cdt = $row["createddatetime"];
									if(strtotime($cdt) != 0)
									{
										$cdt = date_format(new DateTime($cdt), "d-m-Y");
									}
									else
									{
										$cdt="";
									}
									$mby = $row["modifiedby"];
									$mdt = $row["modifieddatetime"];
									if(strtotime($mdt) != 0)
									{										
										$mdt = date_format(new DateTime($mdt), "d-m-Y");
									}
									else
									{
										$mdt = "";
									}
									$active = $row["active"];
									if($active == 1)
									{
										$active = "active";
									}
									else
									{
										$active = "disabled";
									}
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$username."</td>
									<td>".$companyname."</td>																		                                                                        
									<td>".$invfilepath."</td>									
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
							<th>Username</th>
							<th>Company</th>							
                                                        <th>Inv Filepath</th>
							<th>Active</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New IP	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecIP">
            <td>
                    Select IP <font color="red">*</font>
            </td>
            <td>
                    <select id="ipmasid" name="ipmasid">
                            <option value="" selected>--Select IP--</option>
                    </select>
            </td>
	</tr>
        <tr>
		<td>
			Company <font color="red">*</font>
		</td>
		<td>
			<select id="companymasid" name="companymasid">
                            <option value="" selected>--Select Company--</option>
                            <?php loadcompany();?>
                    </select>
		</td>
	</tr>
	<tr>
		<td>
			IP <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="ipno" name="ipno">
		</td>
	</tr>
	<tr>
		<td>
			Username
		</td>
		<td>
			<input type="text" id="username" name="username">
		</td>
	</tr>
	<tr>
		<td>
			System Name
		</td>
		<td>
			<input type="text" id="systemname" name="systemname">
		</td>
	</tr>
	<tr>
		<td>
			ETR Destination Folder Name
		</td>
		<td>
			<input type="text" id="invfilepath" name="invfilepath">
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
			<button type="button" id="btnSave">Create New IP</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update IP</button>
		</td>
	</tr>
	</tbody>
</table>
</div>
<span id='cc'></span>
</div> <!--Main Div-->
</form>
</body>
</html>
