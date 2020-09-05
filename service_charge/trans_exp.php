<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Expenses Entry</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');   
    function loadbuilding()
    {
        $sql = "select buildingmasid, buildingname from mas_building where buildingmasid !='6' order by buildingmasid"; // exclude katangi
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }
    function loadexpledger()
    {
	    $sql = "select * from mas_exp_ledger where active='1'";
	    $result = mysql_query($sql);
	    if($result != null)
	    {
		    while($row = mysql_fetch_assoc($result))
		    {
			    echo("<option value=".$row['expledgermasid'].">".$row['expledger']."</option>");		
		    }
	    }
    }
    function loadexpledgerdet($temp)
    {
	    $sql = "select * from mas_exp_ledger where active='1'";
	    $result = mysql_query($sql);
	    if($result != null)
	    {
		    while($row = mysql_fetch_assoc($result))
		    {			    
			if($temp == $row['expledgermasid'])
			    echo("<option value=".$row['expledgermasid']." selected>".$row['expledger']."</option>");
			else
			    echo("<option value=".$row['expledgermasid'].">".$row['expledger']."</option>");
		    }
	    }	    
    }
    function loadexpgroup()
    {
	    $sql = "select * from mas_exp_group where active='1'";
	    $result = mysql_query($sql);
	    if($result != null)
	    {
		    while($row = mysql_fetch_assoc($result))
		    {
			    echo("<option value=".$row['expgroupmasid'].">".$row['expgroup']."</option>");		
		    }
	    }
    }
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#dataManipDiv').hide();
    oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"
	});
    $('#buildingmasid').focus();
    $('.datepick').datepicker({
            showOn: "button",
	    buttonImage: "../images/calendar.gif",
	    buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true,
	    dateFormat:"M yy",
	    showButtonPanel: true,
	    onClose: function() {
		var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
		var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
		$(this).datepicker('setDate', new Date(iYear, iMonth));		
	    },
	    beforeShow: function() {
		if ((selDate = $(this).val()).length > 0) 
		{
		   var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
		    var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
		   $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
		   $(this).datepicker('setDate', new Date(iYear, iMonth, 1));               
		}
	    }
    });
    $('#btnNew').click(function(){
	$("#tblheader").css('background-color', '#fc9');
	$("#tblsubheader").css('background-color', '#fc9');
	$("#tblheader").text("New Expense Entry");
	$("#exampleDiv").hide();
	$("#dataManipDiv").show();
	$("#selectexpgroup").hide();
	$("#tr_transexpmasid").hide();	
	$("#buildingmasid").focus();
	$("#editTr").hide()
	$("#newTr").show();
	$("#expgroup").val("");		
	$('#totalamount').val("");
	$('#btnSave').show();
	$('#btnUpdate').hide();
    });   
    $('#btnView').click(function(){
	$('form').submit();
    });
    $('[id^="btnEdit"]').live('click', function() {
	$("#tblheader").css('background-color', '#4ac0d5');
	$("#tblsubheader").css('background-color', '#4ac0d5');
	$("#transexpdet").html("");
	$("#tblheader").text("Edit Expense Entry");
	//alert($(this).attr('val'));
	$("#hidtransexpmasid").val($(this).attr('val'));
	$("#exampleDiv").hide();
	$("#dataManipDiv").show();
	$("#tr_transexpmasid").show();
	$('#btnSave').hide();
	$('#btnUpdate').show();
	var $transexpmasid = $(this).attr('val');		
	var $a = $(this).attr('name');
	var url="load_exp.php?item=transexpmas&itemval="+$transexpmasid;	
	$.getJSON(url,function(data){	    
	    $.each(data.error, function(i,response){		
		if(response.s == "Success")
		{                                            			
		    $.each(data.myResult, function(i,response){			    
			var dt = "<font size='10px'>"+response.fromdate+" <font color='red'>to</font> " + response.todate;
			$('#transexpmasid').html(dt);
			$act = response.active;
			if($act == "1")
			{
			    $("#active").attr('checked','checked');
			}
			else
			{
			    $("#active").removeAttr('checked')
			}
			$('#buildingmasid').val(response.buildingmasid);
			$('#fromdate').val(response.fromdate);
			$('#todate').val(response.todate);			
			$('#totalamount').val(response.totalamount);
		    });
		}
	    });
	});
	var url="load_exp.php?item=expdetails&itemval="+$transexpmasid;	
	$.getJSON(url,function(data){
	    $.each(data.error, function(i,response){
		if(response.s == "Success")
		{			    			
		    $('#transexpdet').append(response.msg);
		}
	    });
	});
    });
//    $('[id^="btnEdit"]').live('click', function() {
//	$("#tblheader").css('background-color', '#4ac0d5');
//	$("#tblsubheader").css('background-color', '#4ac0d5');
//	$("#transexpdet").html("");
//	$("#tblheader").text("Edit Expense Entry");
//	//alert($(this).attr('val'));
//	$("#exampleDiv").hide();
//	$("#dataManipDiv").show();
//	$("#tr_transexpmasid").show();	
//	var $transexpmasid = $(this).attr('val');		
//	var $a = $(this).attr('name');
//	
//	var url="load_exp.php?item=transexpmas&itemval="+$transexpmasid;	
//	$.getJSON(url,function(data){	    
//	    $.each(data.error, function(i,response){		
//		if(response.s == "Success")
//		{                                            			
//		    $.each(data.myResult, function(i,response){			    
//			var dt = "<font size='10px'>"+response.fromdate+" <font color='red'>to</font> " + response.todate;
//			$('#transexpmasid').html(dt);
//			$act = response.active;
//			if($act == "1")
//			{
//			    $("#active").attr('checked','checked');
//			}
//			else
//			{
//			    $("#active").removeAttr('checked')
//			}
//			$('#buildingmasid').val(response.buildingmasid);
//			$('#fromdate').val(response.fromdate);
//			$('#todate').val(response.todate);			
//			$('#totalamount').val(response.totalamount);			
//			var url="load_exp.php?item=transexpdet&itemval="+$transexpmasid;	
//			$.getJSON(url,function(data){	    
//			    $.each(data.error, function(i,response){		
//				if(response.s == "Success")
//				{				    
//				    var $a=""; var $i=1;
//				    $.each(data.myResult, function(i,response){
//					$expledgermasid = response.expledgermasid;					
//					var c = 'expledgermasid_0'+$i;					
//					$a +="<tr>";
//					$a +="<td><input type='text' name='sno_0"+$i+"' maxlength='255' value='"+$i+"'/></td>";
//					$a +="<td><select id='expledgermasid_0"+$i+"' name='expledgermasid_0"+$i+"' class='ledger'  style='width:250px;'>\
//						    <option value='' selected>----Select Invoice Desc----</option>";
//					    var url="load_exp_ledger.php?item=expledger";					
//					    $.getJSON(url,function(data){
//						    $.each(data.error, function(i,response){							
//							    if(response.s == "Success")
//							    {
//								$.each(data.myResult, function(i,response){
//								    $('#'+c).append( new Option(response.expledger,response.expledgermasid,true,false));								    								
//								    $("#expledger").val(response.expledger);
//								});								
//							    }
//							    else
//							    {
//								alert(response.s);
//							    }							    							    
//						    });						    
//					    });					
//					$a +="</select>"+$expledgermasid+"</td>\
//						<td id='expgrouptd' name='expgrouptd_0"+$i+"'>"+response.expgroup+"</td>";					
//					$a +="<td><input type='text' class='numbersonly' name='amount_0"+$i+"' value="+response.amount+" maxlength='255' /></td>";
//					if($i>1)
//					$a +="<td><img src='../images/delete.png' class='del_table_row'></td> ";
//					
//					$a +="</tr>";
//					$i++;
//				    });
//				    $("#transexpdet").append($a);				    
//				}
//				else
//				{
//				    alert(response.msg);                                                                                               
//				}
//			    });
//			});
//		    });
//		}
//		else
//		{
//		    alert(response.msg);                                                                                               
//		}
//	    });             
//	});	
//    });    
    $("[id^='btnSave']").live("click", function() {
	    if($("#expgroupmasid").val() =="")
	    {
		alert("Expense Group is mandatory.");
		$("#expgroupmasid").focus();
		return false;
	    }
	    if($("#expledgermasid").val() =="")
	    {
		alert("Expense Ledger is mandatory.");
		$("#expledgermasid").focus();
		return false;
	    }
	    if($("#totalamount").val() <=0 )
	    {
		alert("Not a valid entry.");
		$("#expledgermasid").focus();
		return false;
	    }
	    var r = confirm("Are you sure. You Want to Save?");
	    if(r==true)
	    {	      
		var url="save_exp.php?action=save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){				
		    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
			    $('#cc').html(response.result);			    
			}
			else
			{
			    $('#cc').html(response.result);	
			}
		    });
		});
	    }	    
    });
    $("[id^='btnUpdate']").live("click", function() {
	 if($("#expgroupmasid").val() =="")
	    {
		alert("Expense Group is mandatory.");
		$("#expgroupmasid").focus();
		return false;
	    }
	    if($("#expledgermasid").val() =="")
	    {
		alert("Expense Ledger is mandatory.");
		$("#expledgermasid").focus();
		return false;
	    }
	    if($("#totalamount").val() <=0 )
	    {
		alert("Not a valid entry.");
		$("#expledgermasid").focus();
		return false;
	    }
	    var r = confirm("Are you sure. You Want to Update?");
	    if(r==true)
	    {	      
		var url="save_exp.php?action=update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){				
		    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
			    $('#cc').html(response.result);			    
			}
			else
			{
			    $('#cc').html(response.result);	
			}
		    });
		});
	    }	  
    });
   $('[class^="numbersonly"]').live('blur', function() {
	$('#totalamount').val($get_total());
    });
   $('[class^="numbersonly"]').live('keyup', function() {
	$('#totalamount').val($get_total());
    });  
    $('[class^="ledger"]').live('change', function() {
	var a = $(this).attr("name");
	var b = $(this).closest('td').siblings('[id^="expgrouptd"]').attr("name");	
	var url="load_exp.php?item=expgroup&itemval="+$(this).val();					
	$.getJSON(url,function(data){
	    $.each(data.error, function(i,response){
		if(response.s == "Success")
		{		    
		    $.each(data.myResult, function(i,response){			    			    			
			$("'[name^='"+a+"']'").closest('td').siblings("[name='"+b+"']").text(response.expgroup);	
		    });
		}
		else
		{
		    $('#cc').html(response.s);
		}
	    });	    
	});	
    });   
    $('[id^="btnAdd"]').live("click", function(){ 
	//if($('#expense_item_table tr').size() <= 30){
	    $get_lastID();
	    $('#totalamount').val($get_total());
	    $('#expense_item_table tbody').append($newRow);
	//} else {
	//    alert("Reached Maximum Rows!");
	//};
    });
    $(".del_table_row").live("click", function(){ 
	$(this).closest('tr').remove();
	$('#totalamount').val($get_total());
	$lastChar = $lastChar-2;	
    });
    $get_lastID = function(){
	 var $id = $('#expense_item_table tr:last-child td:first-child input').attr("name");
	 var $val = $('#expense_item_table tr:last-child td:first-child input').attr("value");
	 $val =parseInt($val)+1;
	 $lastChar = parseInt($id.substr($id.length - 2), 10);
	 $lastChar = $lastChar + 1;
	 $newRow = "<tr>\
		    <td><input type='text' name='sno_0"+$lastChar+"' maxlength='255' value='"+$val+"'/></td> \
		    <td><select id='expledgermasid_0"+$lastChar+"' name='expledgermasid_0"+$lastChar+"' class='ledger'  style='width:250px;'>\
		    <option value='' selected>----Select Exp Ledger----</option><?php loadexpledger();?></select></td> \
		    <td id='expgrouptd' name='expgrouptd_0"+$lastChar+"'></td>\
		    <td><input type='text' class='numbersonly' name='amount_0"+$lastChar+"' maxlength='255' /></td> \
		    <td><img src='../images/delete.png' class='del_table_row'></td> \
		 </tr>"		
	return $newRow;
    }
    $get_total = function(){
	var total = 0;   
	$('.numbersonly').each( function(){
	    total += $(this).val() * 1;				
	});
	return commafy(total);
    }
    function commafy(nStr) {
    nStr += '';
	    var x = nStr.split('.');
	    var x1 = x[0];
	    var x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
		    x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
    }
     $(".numbersonly").keydown(function(event) {	
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }	
    });    
});
</script>
<!--<link href="style_progress.css" rel="stylesheet" type="text/css" />
--><style>
input[type=text]
{
    color: #979797;
    height: 28px;
    width: 120px;
    padding-left: 10px;
    text-decoration: none;    
    background-repeat: repeat-x;
    border-radius: 5px; /*up to date browsers support this, but you can add prefixes if you want*/
    border: 0;
}    
</style>
</head>
<body id="dt_example">
<h1>Expense Entry</h1>
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
    <div id="menuDiv" width="100%" align="right">
    <table>
		    <tr>
			    <td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>			    
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
				<th>Building</th>
				<th>Period</th>
				<th>Total Amount</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody id="tbodyContent">
	    <?php
		$sql = "select b.buildingname, date_format(a.fromdate,'%M-%Y') as fromdate,
			date_format(a.todate,'%M-%Y') as todate,a.totalamount,a.transexpmasid from trans_exp_mas a
			inner join mas_building b on b.buildingmasid = a.buildingmasid";
		$result=mysql_query($sql);
		if($result != null) 
		{
			if (mysql_num_rows($result) > 0)
			{
				$i=1;$amount=0;
				   while ($row = mysql_fetch_assoc($result))
					   {									
						$transexpmasid = $row["transexpmasid"];
						$buildingname = $row["buildingname"];
						$period = $row["fromdate"]." <b>to</b> ".$row["todate"];
						$amount = $row["totalamount"];
						$amount = number_format($amount, 0, '.', ',');
						$tr =  "<tr>
						<td class='center'>".$i++."</td>
						<td>".$buildingname."</td>
						<td>".$period."</td>
						<td> KSH ".$amount." /=</td>
						<td align='center'>
						    <button type='button' id=btnEdit$i name='".$transexpmasid."'  val='".$transexpmasid."'>Edit</button>								
						</td>";
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
				<th>Period</th>
				<th>Total Amount</th>
				<th>Edit</th>
			</tr>
		</tfoot>
	</table>
</div>
<div id="dataManipDiv">
    <table class="table2" width="70%">
	<thead>
	    <tr>
		<th id='tblheader'>New Expense Entry</th>
	    </tr>
	</thead>
        <tr>
	    <td>
	    <table>
	    <tr id="tr_transexpmasid">
		<td>
		    <span id="transexpmasid" name="transexpmasid"></span>
		    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <b>Active: </b><input type='checkbox' id='active' name='active' />
		</td>  
	    </tr>	
	    <tr>
		<td><b>Building :&nbsp;&nbsp;</b><font color="red">*</font>          
		    <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>                    
			<?php loadbuilding();?>
		    </select>
		</td>  
	    </tr>	
	    <tr>		
		<td>
			<b>From Date:&nbsp;&nbsp;</b><font color="red">*</font>
			<input type='text' name='fromdate' id='fromdate' class="datepick" value='<?php  echo date('M Y');?>' readonly/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>To Date:&nbsp;&nbsp;</b><font color="red">*</font>
			<input type='text' name='todate' id='todate' class="datepick" value='<?php  echo date('M Y');?>' readonly/>
		</td>
	    </tr>
	    </table>
	    </td>
	</tr>
	<tr>
		<td colspan="2">
		    <table id="expense_item_table" cellspacing="0" cellpadding="0" width="100%">
		    <thead>			
			<tr id='tblsubheader'>
			    <th>S.No</th>
			    <th>Expense Ledger</th>
			    <th width="30%">Group</th>
			    <th>Amount</th>			    
			</tr>
			</thead>
			<tbody id="transexpdet">
			    <tr>
				<td><input type="text" name="sno_01" maxlength="255" required value="1"/></td>
				<td>
				    <select class="ledger" id="expledgermasid" name="expledgermasid_01" style='width:250px;'>
					<option value="" selected>----Select Exp Ledger----</option>
					<?php loadexpledger();?>
				    </select>
				</td>
				<td id='expgrouptd' name='expgrouptd'></td>
				<td><input type="text" class="numbersonly" name="amount_01" maxlength="255" required /></td>				
				<td>&nbsp;</td>
			    </tr>
			</tbody>
		    </table>
		    <input type="button" value="Add Row" id="btnAdd" />
		    <table width="96.5%">
			<tr>			    			    
			    <td align="right">
				<b>Grand Total:&nbsp;&nbsp;</b>
				&nbsp;
				<input type="text" id="totalamount" name="totalamount" readonly/>				
			    </td>
			</tr>
		    </table>
		</td>
	</tr>		
         <tr>		
		<td colspan="2" align="right">		
		    <button type="button" id="btnSave">Save</button>
		    <button type="button" id="btnUpdate">Update</button>
		</td>
	</tr>        
    </table>    
    <font color=red><label id="cc"></label></font>
    <input type='hidden' id='hidtransexpmasid' name='hidtransexpmasid' value="0" />
    <br>
</div>
</form>
</body>
</html>

