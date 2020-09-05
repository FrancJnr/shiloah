<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Credit Note</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    //include('ip_test.php');
    function loadcompany()
    {
	$companymasid = $_SESSION['mycompanymasid'];
	if($companymasid == '2') // GRANDWAYS VENTURES LTD
	    $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid = '$companymasid' order by companymasid;";
	else
	    $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid != '2' order by companymasid;";
	
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['companymasid'].">".$row['companyname']."</option>");		
                }
        }
    }
    function loadbuilding()
    {
        $companymasid = $_SESSION['mycompanymasid'];
		$sql = "select buildingmasid, buildingname from mas_building WHERE companymasid =".$companymasid." order by buildingname asc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }  
    function loadinvoicedesc()
    {
	    $sql = "select * from invoice_desc where active='1'";
	    $result = mysql_query($sql);
	    if($result != null)
	    {
		    while($row = mysql_fetch_assoc($result))
		    {
			    echo("<option value=".$row['invoicedescmasid'].">".$row['invoicedesc']."</option>");		
		    }
	    }
    }  
function getBetween($string, $start = "", $end = ""){
    if (strpos($string, $start)) { // required if $start not exist in $string
        $startCharCount = strpos($string, $start) + strlen($start);
        $firstSubStr = substr($string, $startCharCount, strlen($string));
        $endCharCount = strpos($firstSubStr, $end);
        if ($endCharCount == 0) {
            $endCharCount = strlen($firstSubStr);
        }
        return substr($firstSubStr, 0, $endCharCount);
    } else {
        return '';
    }
}	
?>
<style>
.highlight {
    background-color: yellow;
}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    invnocheck($("#companymasid").val());
    $("#companymasid").focus();
//    $(".datepick").datepicker({
//            showOn: "button",
//	    buttonImage: "../images/calendar.gif",
//	    buttonImageOnly: true,
//	    changeMonth: true,
//	    changeYear: true,
//	    dateFormat:"dd M yy",
//	    showButtonPanel: true,
//	    onClose: function() {
//		var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//		var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
//		//$(this).datepicker('setDate', new Date(iYear, iMonth, iDate));		
//	    }
//    });
       
    $('[id^="companymasid"]').live('change', function() {
	$('#toaddress').val('');
	$('#premise').val('');
	$('#td_invlist').html('');
	$('#grouptenantmasid').val('')
	$('#hid_grouptenantmasid').val('')
	$("#invoice_item_table").find("tr:gt(1)").remove();
	$("#invoicedescmasid").val('');
	$('.invoicenoclass').empty();
	$('.invoicenoclass').append( new Option("Select","",true,false));
        invnocheck($(this).val());
	$('#cc').html('');
	document.getElementById('grouptenantmasid').style.display = 'inline';
	document.getElementById('oth_grouptenantmasid').style.display = 'none';
	$('#hid_segmentid').val('1');	
    });
    function invnocheck(itemtype){
	$('.value').val('');$('.vat').val('');$('.total').val('');
	$('#totalvalue').val('');$('#totalvat').val('');$('#totalamount').val('');
	var url="load_creditnote.php?action=invno_creditnote";
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#creditnoteno').val(response.result);
			$('#buildingmasid').empty();
			$('#buildingmasid').append( new Option("Select","-",true,false));			
			$('#buildingmasid').append( new Option("OTHERS","0",true,false) );
			$.each(data.myResult, function(i,response){
			    var a= response.buildingname;
			    var b = response.buildingmasid
			    $('#buildingmasid').append( new Option(a,b,true,false) );
			});
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});
    }
    $('[id^="buildingmasid"]').live('change', function() {
	$('.value').val('');$('.vat').val('');$('.total').val('');
	$('#totalvalue').val('');$('#totalvat').val('');$('#totalamount').val('');
	$('#toaddress').val('');
	$('#premise').val('');
	$('#td_invlist').html('');
	$('#grouptenantmasid').val('');
	$('#hid_grouptenantmasid').val('');
	$("#invoice_item_table").find("tr:gt(1)").remove();
	$("#invoicedescmasid").val('');
	$('.invoicenoclass').empty();
	$('.invoicenoclass').append( new Option("Select","",true,false));
	$('#cc').html('');
	$("#invoice_item_table").find("tr:gt(1)").remove();
	if($(this).val() ==0)
	{
	    document.getElementById('grouptenantmasid').style.display = 'none';
	    document.getElementById('oth_grouptenantmasid').style.display = 'inline';
	    others();
	    $('#hid_segmentid').val('2');
	    loadinvoiceno(invoicemanmasid,"invoiceno",$('#hid_segmentid').val());
	}
	else
	{
	    $('#hid_segmentid').val('1');	    
	    document.getElementById('grouptenantmasid').style.display = 'inline';
	    document.getElementById('oth_grouptenantmasid').style.display = 'none';
	}	
    });    
    function others()
    {	
	var url="load_creditnote.php?action=others";
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {			
			$('#oth_grouptenantmasid').empty();
			$('#oth_grouptenantmasid').append( new Option("Select","",true,false));
			$.each(data.myResult, function(i,response){
			    var toaddress= response.toaddress;
			    var invoicemanmasid = response.invoicemanmasid			    
			    $('#oth_grouptenantmasid').append( new Option(toaddress,invoicemanmasid,true,false) );
			    //$('#toaddress').val(response.toaddress);
			    //$('#premise').val(response.premise);
			    //$('#remarks').val(response.remarks);			    
			});			
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});
    }
    $('[id^="oth_grouptenantmasid"]').live('change', function() {
	var txt =$(this).find("option:selected").text();	
	$('.value').val('');$('.vat').val('');$('.total').val('');
	$('#totalvalue').val('');$('#totalvat').val('');$('#totalamount').val('');
	$("#invoice_item_table").find("tr:gt(1)").remove();
	$("#invoicedescmasid").val('');
	var invoicemanmasid = $(this).val();
	$("#hid_grouptenantmasid").val(invoicemanmasid);
	var url="load_creditnote.php?action=others_det&invoicemanmasid="+$(this).val();
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {						
			$.each(data.myResult, function(i,response){
			    $('#toaddress').val(response.toaddress);
			    $('#premise').val(response.premise);			    
			});			
			loadinvoiceno(invoicemanmasid,"invoiceno",$('hid_segmentid').val());
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});
	var url="load_creditnote.php?action=invoicedetail_others&invoicemanmasid="+invoicemanmasid;	    
	    $.getJSON(url, function(data){
		$.each(data.error, function(i,response){
			if(response.s == "Success")
			{
                            $('#td_invlist').html(response.result);			    
			}
			else
			{
			    $('#cc').html(response.result);
			}
		});
	    });
    });   
    $("#grouptenantmasid").autocomplete({	
	source: function(request, response) {
		$.ajax({
		    url: "../reports-pms/load_report.php",
		    dataType: "json",
		    data: {
			term: request.term,
			item: 'loadtenantdetails', 
			buildingmasid: $("#buildingmasid").val(),
			companymasid:$("#companymasid").val(),
			searchtype:1
		    },
		    success: function(data) {
			response(data);			
		    }
		});
	    },
	    minLength:1,
	    select: function(event, ui) {				
		$("#hid_grouptenantmasid").val(ui.item.id);
		gpidchange(ui.item.id);
	    }
    }); 
    function gpidchange(gpid)
    {
	$('#cc').html('');
	$('#toaddress').val('');
	$('#premise').val('');	
	$('#td_invlist').html("");    
	var grouptenantmasid = gpid;
	$("#invoice_item_table").find("tr:gt(1)").remove();
	
        loadinvoiceno(grouptenantmasid,"invoiceno",$('#hid_segmentid').val());
	if(grouptenantmasid == "")
	{	    
	    $('.invoicenoclass').empty();
	    $('.invoicenoclass').append( new Option("Select","",true,false));
	}
	else
	{
	    var url="load_creditnote.php?action=invoicedetails&grouptenantmasid="+grouptenantmasid;	    
	    $.getJSON(url, function(data){
		$.each(data.error, function(i,response){
			if(response.s == "Success")
			{
                            $('#td_invlist').html(response.result);			    
			}
			else
			{
			    $('#cc').html(response.result);
			}
		});
	    });
	    var url="load_creditnote.php?action=tenantdetails&grouptenantmasid="+grouptenantmasid;
	    //$('#cc').html(url);	
	    $.getJSON(url,function(data){
	    $.each(data.error, function(i,response){		    
			if(response.s == "Success")
			{
                            $('#companymasid').val(response.companymasid);
			    invnocheck(response.companymasid);
			    $('#toaddress').val(response.tenantaddress);
			    $('#premise').val(response.buildingaddress);		    
			}			
		});
	    });	    	    
	}
    }
    function loadinvoiceno(masid,id,seg)
    {        		
	if(seg=='1')
	    var url="load_creditnote.php?action=loandinvoiceno&grouptenantmasid="+masid;
	else
	    var url="load_creditnote.php?action=loandinvoiceno_others&invoicemanmasid="+masid;
	//$('#cc').html(url);
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                    
                    //$('#cc').html(response.result);
		    $('#'+id).empty();
                    $('#'+id).append( new Option("Select","",true,false) );                        
                    $.each(data.myResult, function(i,response){                        
                        var a  = response.invoiceno;
                        $('#'+id).append( new Option(a,a,true,false) );
                    });
                }                    
            });		
        });
    }
    $('[id^="btnDraft"]').live('click', function() {	
	   if ($("#grouptenantmasid").css("display") == "inline")
	   {
		if($("#grouptenantmasid").val() =="")
		{
		    alert("Please Select Tenant.");
		    $("#grouptenantmasid").focus();
		    return false;
		}
	   }
	   if ($("#oth_grouptenantmasid").css("display") == "inline")
	   {
		if($("#oth_grouptenantmasid").val() =="")
		{
		    alert("Please Select Tenant.");
		    $("#oth_grouptenantmasid").focus();
		    return false;
		}
	   }
            if($("#invoicedescmasid").val() =="")
	    {
		alert("Invoice Description is mandatory.");
		$("#invoicedescmasid").focus();
		return false;
	    }
	    if($('#invoiceno').val() =="")
	    {
		alert("Invoice is mandatory.");
		$("#invoiceno").focus();
		return false;
	    }
	    if($('.value').val() =="")
	    {
		alert("Invoice Value is mandatory.");
		$("#value").focus();
		return false;
	    }
	    if($('#remarks').val() =="")
	    {
		alert("Remarks is mandatory.");
		$("#remarks").focus();
		return false;
	    }
	    //var r = confirm("can you confirm this?");
	    //if(r==true)
	    //{
	        var dataToBeSent = $("form").serialize();
	        window.open("view_creditnote.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
	        return false;
	    //}		  
    });
    $('[id^="btnSave"]').live('click', function() {
	    if ($("#grouptenantmasid").css("display") == "inline")
	   {
		if($("#grouptenantmasid").val() =="")
		{
		    alert("Please Select Tenant.");
		    $("#grouptenantmasid").focus();
		    return false;
		}
	   }
	   if ($("#oth_grouptenantmasid").css("display") == "inline")
	   {
		if($("#oth_grouptenantmasid").val() =="")
		{
		    alert("Please Select Tenant.");
		    $("#oth_grouptenantmasid").focus();
		    return false;
		}
	   } 	    
            if($("#invoicedescmasid").val() =="")
	    {
		alert("Invoice Description is mandatory.");
		$("#invoicedescmasid").focus();
		return false;
	    }
	    if($('#invoiceno').val() =="")
	    {
		alert("Invoice is mandatory.");
		$("#invoiceno").focus();
		return false;
	    }
	    if($('.value').val() =="")
	    {
		alert("Invoice Value is mandatory.");
		$("#value").focus();
		return false;
	    }
	    if($('#remarks').val() =="")
	    {
		alert("Remarks is mandatory.");
		$("#remarks").focus();
		return false;
	    }
	    var r = confirm("Are you sure. You Want to Save?");
	    if(r==true)
	    {
		if($('#hid_segmentid').val()=='2')
		    $("#hid_grouptenantmasid").val(0);
	        var dataToBeSent = $("form").serialize();
	        window.open("save_creditnote.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
	        return false;
	    }	    
    });
	
	      $('#sendforsign').click(function(e){
		    if($(".esdsignchckbox:checked").length == 0){
			alert('No credit note selected ');
			} 
			else{
           alert('Sending to esd ');
		$('#sendforsign').hide();

        $("#loader").show();
		   
    var form = $('#myForm2');
    var url='credi_note_postesd.php';

       //var form = $(this);
            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                dataType: 'json',
                success: function(data) {
					//alert(data.error);
					//console.log(data);
					 $("#loader").hide();
			 $.each(data.error, function(i,response){
				alert(response.s);
				if(response.s == "Success")
				{
				 alert(response.s);	
				 $('#sendforsign').show();
				parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_creditnote.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");/* 
					$.each(data.myResult, function(i,response){			
            //alert(data.msg);					
					
				        $('#btnPostToTally').show();
						parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");
                    
					}); */
				}
				else
				{
					alert("Failed to post");
				}
                    /* if(data.error == true) {
                       
                        alert(data.msg);
				        $('#btnPostToTally').show();
						parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");
                    
							

                            
					} else {
						//$("#loader").hide();
                        alert(data.msg);
						$('#btnPostToTally').show();
						parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");
                        
                    } */
                });
            }
          
});   
	  e.preventDefault();
			}
}); 
	
	
    $('[type="checkbox"]').live('change', function() {
        if ($(this).is(":checked")) {            
            $(this).closest("tr").addClass("highlight");            
            var a = getValueUsingClass();
            $('#td_invlist_selected').html("<font color=red><b>"+a+"</font>");
        } else {            
            $(this).closest("tr").removeClass("highlight");
            var a = getValueUsingClass();
             $('#td_invlist_selected').html("<font color=red><b>"+a+"</font>");
        }
    });
    function getValueUsingClass(){
	/* declare an checkbox array */
	var chkArray = [];
	
	/* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
	$(".chk:checked").each(function() {
		chkArray.push($(this).attr('rel'));                
	});
	
	/* we join the array separated by the comma */
	var selected;        
	selected = chkArray.join('</br></br>') + " ";
	
	/* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
	if(selected.length > 1){
		//alert("You have selected " + selected);
                //$('#td_invlist_selected').html("<font color=red><b>"+selected+"</font>");
                return selected;
	}else{
		//alert("Please at least one of the checkbox");
                //$('#td_invlist_selected').html("");
                return "";
	}
    }
    $('[class^="invdesc"]').live('change', function() {
	var a = $(this).attr("name");	
	var b = $(this).val();
	calcvat(a,b);	
    }); 
 $('[id^="vatrate"]').live('change', function() {
	var str = $(this).attr("name");		
	var ret = str.split("_");
	var len = (ret.length);
	var str2="01";
	if (len > 1)
	{	    	    
	    str2 = ret[1];	
	}

	$('[name="value_'+str2+'"]').val(0);
	$('[name="vat_'+str2+'"]').val(0);
	$('[name="total_'+str2+'"]').val(0);	
	
	$('#totalvalue').val($get_total("value"));
	$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
    });	
    $('[id^="value"]').live('keyup', function() {
	var str = $(this).attr("name");		
	var ret = str.split("_");
	var len = (ret.length);
	var str2="01";
	if (len > 1)
	{	    	    
	    str2 = ret[1];	
	}
	var a = "invoicedescmasid_"+str2;
	var b = $('[name="invoicedescmasid_'+str2+'"]').val();	
	calcvat(a,b);
	$('#totalvalue').val($get_total("value"));
	$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
    });
    function calcvat(str,v)
    {	
	var ret = str.split("_");
	var len = (ret.length);
	var str2="01";
	if (len > 1)
	{	    	    
	    str2 = ret[1];	
	}
	var url="load_details.php?action=vat&invoicedescmasid="+v;		
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {			
			//var vat = response.vat;
			var vat = $('[name="vatrate_'+str2+'"]').val();
			var val1 = $('[name="value_'+str2+'"]').val()
			var val2 = Math.round(val1*vat/100);
			$('[name="vat_'+str2+'"]').val(val2);
			var val3 = parseInt(val1)+parseInt(val2);
			$('[name="total_'+str2+'"]').val(val3);
			
			$('#totalvalue').val($get_total("value"));
			$('#totalvat').val($get_total("vat"));
			$('#totalamount').val($get_total("total"));
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});	
    }
    
    $('#btnAdd').live("click", function(){
	if($('#invoice_item_table tr').size() <= 9){
	    $get_lastID();
	    $('#invoice_item_table tbody').append($newRow);
	} else {
	    alert("Reached Maximum Rows!");
	};
    });
    $(".del_table_row").live("click", function(){ 
	$(this).closest('tr').remove();
	$('#totalvalue').val($get_total("value"));
	$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
	$lastChar = $lastChar-2;	
    });
    $get_lastID = function(){	 
	 var $id = $('#invoice_item_table tr:last-child td:first-child input').attr("name");
	 var $val = $('#invoice_item_table tr:last-child td:first-child input').attr("value");
	 $val =parseInt($val)+1;
	 $lastChar = parseInt($id.substr($id.length - 2), 10);
	 $lastChar = $lastChar + 1;
	 var grouptenantmasid = $('#hid_grouptenantmasid').val();
	 var invoiceid = 'invoiceno_0'+$lastChar;
	 $newRow = "<tr>\
		     <td><input type='text' name='sno_0"+$lastChar+"' maxlength='255' value='"+$val+"'/></td> \
		     <td><select id='invoicedescmasid_0"+$lastChar+"' name='invoicedescmasid_0"+$lastChar+"' class='invdesc' style='width:200px;'><option value='' selected>Select</option><?php loadinvoicedesc();?></select></td> \
		     <td><select id='invoiceno_0"+$lastChar+"' name='invoiceno_0"+$lastChar+"' class='invoicenoclass' style='width:150px;'><option value='' selected>Select</option>"+loadinvoiceno(grouptenantmasid,invoiceid,$('#hid_segmentid').val())+"</select></td> \
		    <td><select id='vatrate' name='vatrate_0"+$lastChar+"' class='' style='width:100px;'><option value='0' >No VAT</option><option value='16' >16%</option><option value='14' >14%</option></select></td> \
			<td><input type='text' class='value' id='value' name='value_0"+$lastChar+"' maxlength='255' /></td> \
		     <td><input type='text' class='vat' id='vat' name='vat_0"+$lastChar+"' maxlength='255'readonly /></td> \
		     <td><input type='text' class='total' name='total_0"+$lastChar+"' maxlength='255' readonly/></td> \
		     <td><img src='../images/delete.png' class='del_table_row'></td> \
		 </tr>"
	 return $newRow;
    }
    $('[class^="value"]').live('blur', function() {	
	$('#totalvalue').val($get_total("value"));
	$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
    });  
    $get_total = function(a){	
	var total = 0;   
	$('[class^="'+a+'"]').each( function(){
	    total += parseFloat($(this).val());				
	});
	return total;
	//return commafy(total);
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
<style>
input[type=text]
{
    color: #ff6f6f;
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
<body id="dt_example" style="width: 90%">
<form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER' style="background-color:#ff6f6f;color: #000000" >Credit Note</h1>
    <br>
	<font color=red><label id="cc"></label></font>	
    <br>   
    <table class='table6'>	
        <tr>            
            <td colspan='2' style="text-align: center;">
                Company <select id="companymasid" name="companymasid" style='width: 225px;'>                    
				<?php loadcompany();?>
			    </select>
		Building <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
			    </select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Credit Note No <input type="text" id="creditnoteno" name="creditnoteno" style="font-size: larger;" readonly/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Date <input type='text' name='crdate' id='crdate' class="datepick" value='<?php  echo date('d-m-Y');?>' readonly/>
            </td>
        </tr>	        
	<tr>
		<td>Tenant<font color="red">*</font></td>
		<td>		    
		    <input type="text" id="grouptenantmasid" name="grouptenantmasid" style="width:550px;display:inline;"/>
		    
		    <input type="hidden" id="hid_grouptenantmasid" name="hid_grouptenantmasid" value="0"/>
		    
		    <select id="oth_grouptenantmasid" name="oth_grouptenantmasid" style='width:525px;display:none;' >
			<option value="0" selected>SUNDRY DEBTOR</option>			
		    </select>
		    
		    <input type="hidden" id="hid_segmentid" name="hid_segmentid" value="1"/>
		</td>
	</tr>
	<tr>
		<td>
			To:<font color='red'>*</font>
		</td>
		<td>			
			<textarea cols=100 rows=2 id="toaddress" name="toaddress"></textarea>
		</td>
	</tr>	
	<tr>
		<td>
			Premise:<font color='red'>*</font>
		</td>
		<td>			
			<textarea cols=100 rows=2 id="premise" name="premise"></textarea>
		</td>
	</tr>	
	<tr>
		<td>
			List of Invoices:
		</td>
		<td id='td_invlist'>			
			
		</td>
	</tr>
       <!-- <tr>
		<td>
			Selected Invoices:
		</td>
		<td id='td_invlist_selected'>

		</td>
	</tr>-->
	<tr>
		<td colspan="2">
		    <table id="invoice_item_table" class='table6'>
		    <thead>
			<tr>
			    <th>S.No</th>
			    <th>Description</th>
                            <th>Invoice No</th>
							<th>Tax Rate</th>
			    <th>Amount</th>
			    <th>Vat</th>
			    <th>Total</th>			    
			</tr>
			</thead>
			<tbody>
			    <tr>
				<td><input type="text" name="sno_01" maxlength="255" required value="1"/></td>
				<td>
				    <select class="invdesc" id="invoicedescmasid" name="invoicedescmasid_01" style='width:200px;'>
					<option value="" selected>Select</option>
					<?php loadinvoicedesc();?>
				    </select>
				</td>
				
                                <td>
				    <select class="invoicenoclass" id="invoiceno" name="invoiceno" style='width:150px;'>
					<option value="" selected>Select</option>					
				    </select>
				</td>
				<td> <select class="" id="vatrate" name="vatrate_01"  style="width:100px;" >
					<option value="0" selected>No VAT</option>
					<option value="14" selected>14%</option>
					<option value="16" selected>16%</option>					
				    </select></td>
				<td><input type="text" class='value' id="value" name="value_01" maxlength="255"  /></td>
				<td><input type="text" class='vat' id="vat" name="vat_01" maxlength="255" readonly /></td>
				<td><input type="text" class='total' name="total_01" maxlength="255" readonly /></td>
				<td>&nbsp;</td>
			    </tr>
			</tbody>
		    </table>
		    <input type="button" value="Add Row" id="btnAdd" />
		    <table width="96.5%" class='table6'>
			<tr>			    			    
			    <td style='text-align: right;'>
				Grand Total
				&nbsp;
				<input type="text" id="totalvalue" name="totalvalue" readonly />
				&nbsp;
				<input type="text" id="totalvat" name="totalvat" readonly />
				&nbsp;
				<input type="text" id="totalamount" name="totalamount" readonly/>
			    </td>
			</tr>
		    </table>
		</td>
	</tr>	
	<tr>
		<td>
			Remarks:<font color="red">*</font>
		</td>
		<td>
			<textarea cols=100 rows=5 id="remarks" name="remarks"></textarea>
		</td>
	</tr>	
         <tr>		
		<td colspan="2" align="right">
		    <button type="button" id="btnDraft">Draft</button>
                        &nbsp;&nbsp;&nbsp;
		    <button type="button" id="btnSave">Save</button>
		</td>
	</tr>        
    </table>	
    </form>
    <p id='heading' align='center'></p>
    <div id='display'>
    
    </div>
    <form id="myForm2" name="myForm2" action="" method="post">
        <span class='span_cont'>Available Credit Notes:</span><div style='height:8px'></div>
		<center><button class="buttonNew" name ="sendforsign" id="sendforsign"> Sign to ESD </button></center>
    <?php
    $directory="../../pms_docs/creditnote/";
    $sortOrder="newestFirst"; 
    $results = array(); 
    $handler = opendir($directory);
       
    if (glob($directory . "*.*") != false)
    {
        
    while ($file = readdir($handler)) {  
           if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess"){ 
               $currentModified = filemtime($directory."/".$file);	   
               $file_type[] = strtolower(substr($directory."/".$file, strrpos($directory."/".$file, '.')+1));
               $file_size[] = filesize($directory."/".$file);
               $file_names[] = $file; 
               $file_dates[] = $currentModified; 
           }    
       } 
           closedir($handler); 
    
       //Sort the date array by preferred order
    
       if ($sortOrder == "newestFirst"){ 
           arsort($file_dates);
       }else{ 
           asort($file_dates); 
       } 
        $w=1;
       //Match file_names array to file_dates array 
       $file_names_Array = array_keys($file_dates);     
       foreach ($file_names_Array as $idx => $name) $name=$file_names[$name]; 
       $file_dates = array_merge($file_dates); 
       $i = 0;  $date1=0;
       $date = date('d-m-Y', $file_dates[0]);   
       echo "<span class='span_cont'><u>".$date."</u></span><div style='height:8px'></div>";
       echo "<table class='table6'>";
       echo "<tr>
                    <th>S.No</th>
                    <th>File</th>
                    <th>Type</th>
                    <th>Size</th>
					<th>ESD</th>
					<th>Select</th>
                    <th>Remove</th>
            </tr>";
       //Loop through dates array and then echo the list 
       foreach ($file_dates as $file_dates){       
           $date = date('d-m-Y', $file_dates);       
           $j = $file_names_Array[$i];       
           $file = $file_names[$j];
           $type = $file_type[$j];
           $size = $file_size[$j];
           if($type=="btc")
            $type = "Dbf File";
           else if($type=="txt")
               $type = "Text File";
            else if($type=="zip")
              $type = "Zip File";
           
           $i++;
		   $creditnotenum = getBetween($file,"_","."); 
           if($date < $date1)
           {
                    echo "</table><div style='height:8px'></div>";
                    echo "<span class='span_cont'><u>".$date."</u></span><div style='height:8px'></div>";
                    echo "<table class='table6'>";
                    echo "<tr>
                            <th>S.No</th>
                            <th >File</th>
                            <th>Type</th>
                            <th>Size</th>
							<th>ESD</th>
							<th>Select</th>
                            <th>Remove</th>
                            </tr>";
                    $w=1;
           }
           $link = $directory.$file;
           echo "<tr>";
           echo "<td>$w</td>";
           echo "<td><a href='$link' target='_blank'>$file</a></td>";
           echo "<td>$type</td>";
		    echo "<td>".formatSizeUnits($size)."</td>";
			$companymasid = $_SESSION['mycompanymasid'];
		   $sql3 = "select * from invoice_cr_mas where creditnoteno = ".$creditnotenum." AND companymasid = '".$companymasid."'";
		  // die($sql3);
	    $result3 = mysql_query($sql3);
		
		if(mysql_num_rows($result3) > 0)
	    {
		    while($row3 = mysql_fetch_assoc($result3))
		    {
				if($row3['signed']=='2'){
				 echo "<td>Signed</td>";
				 echo "<td><input type='checkbox' class='esdsignchckbox' name='isselected[]' value='".$creditnotenum."' disabled='true'> </td>"; 
				 }else if($row3['signed']=='1'){
				echo "<td>Pending</td>";
					echo "<td><input type='checkbox' class='esdsignchckbox' name='isselected[]' value='".$creditnotenum."'> </td>"; 
				 }else{
					echo "<td></td>"; 
					echo "<td><input type='checkbox' class='esdsignchckbox' name='isselected[]' value='".$creditnotenum."'> </td>"; 
				 }
				 	
		    }
			
	    }else{
			echo "<td>".mysql_num_rows($result3)."</td>";
			echo "<td><input type='checkbox' class='esdsignchckbox' name='isselected[]' value='".$creditnotenum."' disabled='true'> </td>"; 
		}
       
			//<input type='hidden' name='narration[]' value='".$file."'>
           echo "<td align='center'><a href=\"deletefile.php?file=$link\"><img src='../images/delete.png'></a></td>";
           echo "</tr>";
           //echo  "File name: $file - Date Added:  $date'. $i <br/>";
           $w++;
           $date1 = $date;
       }
    }else
    {
            echo "No Files in the directory.";
    }
    function formatSizeUnits($bytes)
        {
            if ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }
            elseif ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            }
            elseif ($bytes >= 1024)
            {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            }
            elseif ($bytes > 1)
            {
                $bytes = $bytes . ' bytes';
            }
            elseif ($bytes == 1)
            {
                $bytes = $bytes . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }
    
            return $bytes;
    }
    
    ?>
    </div> <!--File Details-->
    
    &nbsp;&nbsp;
    <br>
</form>
</body>
</html>

