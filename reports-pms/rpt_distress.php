<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Distress Notice</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');    
    function loadtenant()
    {
         $companymasid = $_SESSION['mycompanymasid'];
	 $sql= "select c.leasename ,c.tradingname,c.tenantcode,c.renewalfromid,d.shopcode, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            where c.companymasid=$companymasid and c.active ='1'            
            union
            select c1.leasename,c1.tradingname,c1.tenantcode,c1.renewalfromid,d1.shopcode, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid 
            where c1.companymasid=$companymasid and c1.active ='1'            
            order by leasename;";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        $tradingname= $row['tradingname'];
			if($tradingname !="")
			{
				$leasename  = $row['leasename']." T/A ".$tradingname;
			}
			else
			{
				$leasename  = $row['leasename'];
			}
			echo("<option value=".$row['grouptenantmasid'].">".$leasename."</option>");		
                }
        }
    }
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $("#dataManipDiv").hide();
    oTable = $('#example').dataTable({
        "bJQueryUI": true,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        //"sPaginationType": "full_numbers"			
    });
   $("#createddate").datepicker({
        showOn: "button",
        buttonImage: "../images/calendar.gif",
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat:"dd-mm-yy",
        showButtonPanel: true,       
        onSelect: function(dateStr) {
            var d = $.datepicker.parseDate('dd-mm-yy', dateStr);
            var dy = $('#graceperiod').val();            
            d.setDate(d.getDate() + parseInt(dy)); // Add days
            $('#expirydate').datepicker('setDate',d);
	    $('#outstandingamt').focus();
        }
    });   
   $("#expirydate").datepicker({	
        dateFormat:"dd-mm-yy",
        beforeShow: function(i) { if ($(i).attr('readonly')) { return false; } }
    });  
    //loadtenant("loaddistresstenant");	
    function loadtenant(itemtype)
    {
        var url="load_report.php?item="+itemtype;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#grouptenantmasid').empty();
                    $('#grouptenantmasid').append( new Option("Select","",true,false) );
                    $.each(data.myResult, function(i,response){
                        var t = response.tradingname;
                        var c ="";
                        if(t !=""){
                            var c = " ( T/A "+ response.tradingname +" )";
                        }
                            var b = response.leasename + c;
                        var r = response.renewalfromid;
                        if(r <=0)
                            var a = b+" ("+response.shopcode+")";
                        else
                        var a = b+" ("+response.shopcode+" RENEWED)" ;
                        $('#grouptenantmasid').append( new Option(a,response.grouptenantmasid,true,false) );
                    });
                }
                else
                {
                    $('#grouptenantmasid').empty();
                    $('#grouptenantmasid').append( new Option("Select","",true,false) );
                    alert(response.s);
                }
            });		
        });
    }
    $("#grouptenantmasid").autocomplete({
            source:"load_report.php?item=loadtenantdetails&searchtype=1",
            minLength:1,
            select: function(event, ui) {				
                    $("#hid_itemid").val(ui.item.id);
		    $("#hid_relname").val(ui.item.rel);
            }
        });  
    //$('[id^="grouptenantmasid"]').live('change', function() {		
    //    $('#grouptenant').empty();
    //    $('#divContent').empty();
    //    var str= $('#grouptenantmasid option:selected').text();		
    //    var temp = new Array();
    //    temp = str.split("-"); //split -
    //    temp = temp[1].split(")"); //split ')'
    //    temp[0]; // building shortname from lease name and tenant code
    //    var url="load_report.php?item=grouptenant&itemval="+$(this).val()+"&buildingshortname="+temp[0];	
    //    $.getJSON(url,function(data){
    //        $.each(data.error, function(i,response){
    //            $.each(data.myResult, function(i,response){
    //                $('#grouptenant').append(response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.tenantcode+")<input type='hidden' name='tenantmasid"+response.tenantmasid+"' value='"+response.tenantmasid+"'><br><br>");					
    //            });
    //        });		
    //    });		
    //});	
    $('#btnNew').click(function(){
        $("#tblheader").css('background-color', '#fc9');	
        $("#exampleDiv").hide();
	$("#dataManipDiv").show();        
	$("#editTr").hide()
	$("#newTr").show();
	$("#grouptenantmasid")[0].focus();
	$('input[type=text]').val('');
        $('input[type=select]').val('');    
    });
    $('[id^="btnAddNew"]').live('click', function() {
        $("#tblheader").css('background-color', '#fc9');	
        $("#exampleDiv").hide();
	$("#dataManipDiv").show();
	 $("#usertbl").show();
	$("#editTr").hide()
	$("#newTr").show();
	$("#grouptenantmasid")[0].focus();
	$('input[type=text]').val('');
        $('input[type=select]').val('');
	var $a = $(this).attr('name');
	$('#hidrefdistressmasid').val($a);
    });
    $('#btnSave').click(function(){
	if(jQuery.trim($("#grouptenantmasid").val()) == "")
	{
		alert("Please select Tenant");
		$("#grouptenantmasid").focus();
		return false;
	}
	if(jQuery.trim($("#paymentfor").val()) == "")
	{
		alert("Please select Payment For");
		$("#paymentfor").focus();
		return false;
	}		
	if(jQuery.trim($("#graceperiod").val()) == "")
	{
		alert("Please enter Grace Period");
		$("#graceperiod").focus();
		return false;
	}
	if(jQuery.trim($("#createddate").val()) == "")
	{
		alert("Please select Distress Date");
		$("#createddate").focus();
		return false;
	}
	if(jQuery.trim($("#outstandingamt").val()) == "")
	{
		alert("Please enter outstanding amount");
		$("#outstandingamt").focus();
		return false;
	}
	if(jQuery.trim($("#expirydate").val()) == "")
	{
		alert("Please enter expiry date");
		$("#expirydate").focus();
		return false;
	}
	var a = confirm("Can you confirm this ?");
	if (a== true)
	{            
	    var $h = $('#hidrefdistressmasid').val();
	    var url="save_distress.php?action=Save&refdistressmasid="+$h;	    
            var dataToBeSent = $("form").serialize();	    
            $.getJSON(url,dataToBeSent, function(data){
                $.each(data.error, function(i,response){
                    if(response.s =="Success")
                    {
                        $("#cc").html(response.msg);
			$('input[type=text]').val('');
                        $('#grouptenantmasid').val('');
			$('#createddate').focus();
			$.each(data.myResult, function(i,response){										
				$("#cc").html(response.msg);
			});
                    }
                    else
                    {					
                        $("#cc").html(response.msg);
                    }				
                });
            });
	}
    });
    $('[id^="btnHide"]').live('click', function() {
	$(this).text("Show");
	$(this).attr("id","btnShow");
	$("#usertbl").hide();	
        $("#editTr").hide()
	$(this).css('background', '#0095cd');
    });
    $('[id^="btnShow"]').live('click', function() {
	$(this).text("Hide");
	$(this).attr("id","btnHide");
	$(this).css('background', 'red');
	
	$("#tblheader").css('background-color', '#4ac0d5');		
        $("#usertbl").show();	
        $("#editTr").show()
        $("#newTr").hide();        
        $("#grouptenantmasid").focus();
        var $a = $(this).attr('name');
	$('#hidrefdistressmasid').val($a);
        var url="load_distress.php?item=showdetails&distressmasid="+$(this).attr('val');
        var dataToBeSent = $("form").serialize();	
        $.getJSON(url,dataToBeSent, function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                    
		    $.each(data.myResult, function(i,response){			
			$("#hid_itemid").val(response.grouptenantmasid);
		        $("#hid_relname").val(response.buildingname);
			
			$('#grouptenantmasid').val(response.leasename);
			$('#graceperiod').val(response.graceperiod);
                        $('#createddate').val(response.createddate);                                                
                        $('#expirydate').val(response.expirydate);
			$('#outstandingamt').val(response.outstandingamt);
			$('#paymentfor').val(response.paymentfor);			
                        
			$('#subject').val(response.subject);	
                        $('#para1').val(response.para1);
			$('#para2').val(response.para2);
			
                        $act = response.active;
			 $("#active").removeAttr('checked')
                        if($act == "1")
                        {
                            $("#active").attr('checked','checked');
                        }
			var $a = response.distressmasid;			
                        $("#hiddistressmasid").val($a);
                    });
                }				
                else
                {                            
                    $("#cc").html(response.msg);		    
                }
                 
            });             
        });           
    });
    $('[id^="btnFollow"]').live('click', function() {        
        $("#exampleDiv").hide();
        $("#dataManipDiv").show();
	$("#usertbl").hide();
        var $a = $(this).attr('name');
        var url="load_distress.php?item=showlist&distressmasid="+$(this).attr('val');
        var dataToBeSent = $("form").serialize();	
        $.getJSON(url,dataToBeSent, function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                    
		    $.each(data.myResult, function(i,response){
			
			$("#hid_itemid").val(response.grouptenantmasid);
		        $("#hid_relname").val(response.buildingname);
			
			$('#grouptenantmasid').val(response.leasename);
			$('#graceperiod').val(response.graceperiod);
                        $('#createddate').val(response.createddate);                                                
                        $('#expirydate').val(response.expirydate);
			$('#outstandingamt').val(response.outstandingamt);
			$('#paymentfor').val(response.paymentfor);			
                        
			$('#subject').val(response.subject);	
                        $('#para1').val(response.para1);
			$('#para2').val(response.para2);
			
                        $act = response.active;
			 $("#active").removeAttr('checked')
                        if($act == "1")
                        {
                            $("#active").attr('checked','checked');
                        }
			var $a = response.distressmasid;			
                        $("#hiddistressmasid").val($a);
                    });
                }				
                else
                {                            
                    //$("#cc").html(response.msg);
		    $("#divDetails").html(response.msg);
                }
                 
            });             
        });           
    })
    $('#btnUpdate').click(function(){				
        var a = confirm("Can you confirm this ?");
        if (a== true)
        {
            var url="save_distress.php?action=Update";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){
                $.each(data.error, function(i,response){
                    if(response.s =="Success")
                    {
                        //$('input[type=text]').val('');
                        //$('input[type=select]').val('');                        
                        $("#cc").html(response.msg);
                    }
                    else
                    {						
                        $("#cc").html(response.msg);
                    }
                });
            });
        }
    });
    $('#btnView').click(function(){
	$('form').submit();
    });
    $('[id^="btnPrint"]').live('click', function() {
	    var r = confirm("can you confirm this?");
	    if(r==true)
	    {						
		var dataToBeSent = $("form").serialize();		
		var distressmasid=$(this).attr('val');		 
		window.open("print_distress.php?distressmasid="+distressmasid, "Print PDF", "width=800,height=800,toolbar:false,");
		return false;
	    }
		//var url="view_invoice.php";
		//var dataToBeSent = $("form").serialize();
		// $("#cc").html(dataToBeSent);
		//$.getJSON(url,dataToBeSent, function(data){				
		//	$.each(data.error, function(i,response){
		//	    if(response.s == "Success")
		//	    {
		//		
		//	    }			    
		//	});
		//});
	});
    $('#graceperiod').change(function(){
	$('#createddate').val("");
        if($(this).val()<= 0)
        {
             $('#graceperiod').val("7");	     
        }	
    });
    $(".decimalonly").keydown(function isNumberKey(evt)
	{
	   var charCode = (evt.which) ? evt.which : event.keyCode
	   if (charCode > 31 && (charCode < 48 || (charCode > 57 && charCode != 190 && charCode != 110)))
	      return false;
      
	   return true;
	});
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
	
	 //Only allow 0-9, '.' and backspace (charCode 0 in Firefox)	
    });    
    $('#btnLetter').click(function(){
	fillsub();fillpara1();fillpara2();
    });
    function fillsub()
    {	
	var buildingname = $('#hid_relname').val();
	var paymentfor = $('#paymentfor').val();		
	var txt = "Sub: "+paymentfor +" Outstanding For "+buildingname+".";	
	$("#subject").val(txt);	
    }
    function fillpara1()
    {	
	var buildingname = $('#hid_relname').val();
	var graceperiod = $('#graceperiod').val();
	var paymentfor = $('#paymentfor').val();
	var outstandingamt = $('#outstandingamt').val();	
	var txt = "Please note that the total amount of " +paymentfor+" outstanding for the above premises "+buildingname +" is Kshs "+commafy(outstandingamt)+"/=.";	
	$("#para1").val(txt);	
    }
    function fillpara2()
    {		
	var graceperiod = $('#graceperiod').val();	
	var outstandingamt = $('#outstandingamt').val();	
	var txt = "Take notice that unless the outstanding amount Kshs "+ commafy(outstandingamt)+" /= is paid to us within the next grace period ("+graceperiod+") days from today we will levy distress on you and by copy of this letter we are instructing our lawyer to do the same.";			
	$("#para2").val(txt);	
    }
	function removeComma(val)
	{
		return String(val).replace(/\,/g, '');
		//return val;
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
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="GET">
<div id="container">
<center><h1>Distress Notice</h1></center><br>
<div id="menuDiv" width="100%" align="right">
<table>
<tr>
        <td> <button class="buttonNew" type="button" id="btnNew"> New Distress </button> </td>        
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
                    <th>Tenant</th>
		    <th>Building</th>
		    <th>Size</th>                    
		    <th>Follow</th>                    
            </tr>
    </thead>
    <tbody id="tbodyContent">
	<?php
            $sql = "select c.leasename,c.tradingname,d.distressmasid,f.refdistressmasid,d.grouptenantmasid,g.size,h.buildingname,
			date_format(d.createddate,'%d-%m-%Y') as createddate,d.graceperiod,d.outstandingamt,date_format(d.expirydate,'%d-%m-%Y') as expirydate,            d.active from group_tenant_mas a
			inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        inner join mas_tenant c on c.tenantmasid = b.tenantmasid                                        
			inner join rpt_distress d on d.grouptenantmasid = a.grouptenantmasid
			inner join rpt_distress_det f on f.distressmasid = d.distressmasid
			inner join mas_shop g on g.shopmasid = c.shopmasid
			inner join mas_building h on h.buildingmasid = g.buildingmasid
			where c.active='1' and f.defaultid ='1'
			union
			select c.leasename,c.tradingname,d.distressmasid,f.refdistressmasid,d.grouptenantmasid,g.size,h.buildingname,
			date_format(d.createddate,'%d-%m-%Y') as createddate,d.graceperiod,d.outstandingamt,date_format(d.expirydate,'%d-%m-%Y') as expirydate,            d.active from group_tenant_mas a
			inner join  group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                        inner join rec_tenant c on c.tenantmasid = b.tenantmasid                                        
			inner join rpt_distress d on d.grouptenantmasid = a.grouptenantmasid
			inner join rpt_distress_det f on f.distressmasid = d.distressmasid
			inner join mas_shop g on g.shopmasid = c.shopmasid
			inner join mas_building h on h.buildingmasid = g.buildingmasid
			where c.active='1' and f.defaultid ='1'
			group by a.grouptenantmasid order by leasename;";
            $result=mysql_query($sql);
            if($result != null) // if $result <> false
            {
                if (mysql_num_rows($result) > 0)
                {
                    $i=1;
                    while ($row = mysql_fetch_assoc($result))
                    {									
                        $refdistressmasid=$row["refdistressmasid"];
			$tradingname = $row["tradingname"];
			if($tradingname !="")
                        $tenant = $row["leasename"]." T/A ".$tradingname;
			else
			$tenant = $row["leasename"];
                        $createddate = $row["createddate"];
                        $graceperiod=$row["graceperiod"];
			
                        $oustandingamt=$row["outstandingamt"];
                        $expirydate = $row["expirydate"];                                    
                        $active = $row["active"];
                        if($active == 1)
                        {
                            $active = "active";
                        }
                        else
                        {
                            $active = "-";
                        }
                        $tr =  "<tr>
                        <td class='center'>".$i++."</td>                        
                        <td>".$tenant."</td>
                        <td>".$row["buildingname"]."</td>
                        <td>".$row["size"]."</td>
                        <td align='center'>
                            <button type='button' id=btnFollow$i name='".$refdistressmasid."' val='".$refdistressmasid."'>Follow</button>								
                        </td>";
                        //<td align='center'>
                            //<button type='button' id=btnPrintDoc$i name='".$distressmasid."' val='".$distressmasid."'>Print</button>								
                        //</td>";
                        echo $tr;
                    }
                }
            }		
?>
</tbody>    
</table>
</div>
<div id='divDetails'>
	
</div>
<div id="details">
<div id="dataManipDiv">
<table id="usertbl" class="table2" width="70%">
    <thead>
    <tr>
        <th id="tblheader" align="left" colspan="2">
                Distress Notice
        </th>
    </tr>
    </thead>
    <tbody>        
    <tr>
	 <td>
                Enter Tenant Name <font color="red">*</font>
        </td>
        <td>            
	   <input type="text" id="grouptenantmasid" name="grouptenantmasid" style="text-align:left;width:94%;"/>
	   <input type="hidden" id="hid_itemid" name="hid_itemid" value="0"/>
	   <input type="hidden" id="hid_relname" name="hid_relname" value="0"/>
            </br></br>
	    <span class='span_cont' id='grouptenant'></span>
        </td>
    </tr>
     <tr>	 
        <td colspan='2'>
                Grace Period <font color="red">*</font>
		<input type='text' id='graceperiod' class="numbersonly" name='graceperiod' value="7" style="text-align:right;width:100px;"/>
		&nbsp;&nbsp;Date <font color="red">*</font>
		<input type='text' name='createddate' id='createddate' value='' style="text-align:left;width:100px;"/>
		&nbsp;&nbsp; To &nbsp;&nbsp;
		<input type='text' id='expirydate' name='expirydate' readonly style="text-align:left;width:100px;"/>
		&nbsp;&nbsp;Amount <font color="red">*</font>
		<input type='text' id='outstandingamt' name='outstandingamt' class="numbersonly" style="text-align:right;width:100px;"/> /- KSH
        </td>
    </tr>    
    <tr>
	 <td>
                Paymentfor:
        </td>
        <td>	    
            <select id="paymentfor" name="paymentfor">
		<option value="" selected>Select payment for</option>
		<option value="Rent & Service Charge Deposit">Rent & Service Charge Deposit</option>
		<option value="Rent">Rent</option>
		<option value="Service Charge">Service Charge</option>
		<option value="Electrycity">Electrycity</option>
		<option value="Water">Water</option>
		<option value="Legal">Legal</option>
		<option value="Others">Others</option>
	    </select>
	    <button type="button" id="btnLetter">Format Letter</button>
        </td>
    </tr>
    <tr>
	 <td>
                Sub:
        </td>
        <td>
	    <input type="text" id="subject" name="subject" value="" style="text-align:left;width:94%;"/>
        </td>
    </tr>
    <tr>
	 <td>
                Para 1
        </td>
        <td>
	    <textarea id='para1' name='para1' rows='3' cols='110'></textarea>            
        </td>
    </tr>
    <tr>
	 <td>
                Para 2
        </td>
        <td>
	    <textarea id='para2' name='para2' rows='3' cols='110'></textarea>            
        </td>
    </tr>    
    <tr>
	<td>
		Active
	</td>
	<td>
		<input type="checkbox" id="active" name="active" checked>
		<input type="hidden" id="hiddistressmasid" name="hiddistressmasid" value='0' />
		<input type="hidden" id="hidrefdistressmasid" name="hidrefdistressmasid" value='0' />
	</td>
    </tr>
    <tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Save</button>
		</td>
	</tr>
   <tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update</button>
		</td>
	</tr>
</table>
</div>

</div> <!--Main Div-->
<label id="cc"></label>
</form>
</body>
</html>
    