<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>RECEIPT</title>
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
	//if($companymasid == '2') // GRANDWAYS VENTURES LTD
	    $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid = '$companymasid' order by companymasid;";
	//else
	 //   $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid != '2' order by companymasid;";
	
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
	//$('#totalvalue').val('');$('#totalvat').val('');
	$('#totalamount').val('');
	var url="load_receipt.php?action=invno_receipt";
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#rctno').val(response.result);
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
	//$('#totalvalue').val('');$('#totalvat').val('');
	$('#totalamount').val('');
	$('#toaddress').val('');
	$('#premise').val('');
	$('#td_invlist').html('');
	$('#grouptenantmasid').val('');
	$('#hid_grouptenantmasid').val('');
	$("#invoice_item_table").find("tr:gt(1)").remove();
	$("#invoicedescmasid").val('');
	$('.invoicenoclass').empty();
	$('.invoicenoclass').append( new Option("Select","",true,false));
	//invnocheck($(this).val());
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
	var url="load_receipt.php?action=others";
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {			
			$('#oth_grouptenantmasid').empty();
			$('#oth_grouptenantmasid').append( new Option("Select","",true,false));
			$.each(data.myResult, function(i,response){
			  
			    var invoicemanmasid = response.invoicemanmasid		
				var toaddress= response.invoiceno+'_'+response.toaddress;				
			    $('#oth_grouptenantmasid').append( new Option(toaddress,invoicemanmasid,true,false) );
				//changes here
			    //$('#hid_grouptenantmasid').val(0);
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
	//$('#totalvalue').val('');$('#totalvat').val('');
	$('#totalamount').val('');
	$("#invoice_item_table").find("tr:gt(1)").remove();
	$("#invoicedescmasid").val('');
	var invoicemanmasid = $(this).val();
	$("#hid_grouptenantmasid").val(invoicemanmasid);
	var url="load_receipt.php?action=others_det&invoicemanmasid="+$(this).val();
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {						
			$.each(data.myResult, function(i,response){
			    $('#toaddress').val(response.toaddress);
			    $('#premise').val(response.premise);			    
			});
		//invnocheck($(this).val());			
			loadinvoiceno(invoicemanmasid,"invoiceno",$('hid_segmentid').val());
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});
	var url="load_receipt.php?action=invoicedetail_others&invoicemanmasid="+invoicemanmasid;	    
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
		                      $(".invdesc").empty();
            $.getJSON("load_receipt.php?action=getinvcedescriptonother&grouptenantmasid="+invoicemanmasid, function(data) {
          $(".invdesc").append("<option value=''>--Select--</option>");
    // alert(data);
    $.each(data, function(i, item) {
       
    $(".invdesc").append("<option value="+item.invoicedescmasid+">"+item.invoicedesc+"</option>");     
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
	    var url="load_receipt.php?action=invoicedetails&grouptenantmasid="+grouptenantmasid;	    
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
	    var url="load_receipt.php?action=tenantdetails&grouptenantmasid="+grouptenantmasid;
	    //$('#cc').html(url);	
	    $.getJSON(url,function(data){
	    $.each(data.error, function(i,response){		    
			if(response.s == "Success")
			{
                            $('#companymasid').val(response.companymasid);
			    //invnocheck(response.companymasid);
			    $('#toaddress').val(response.tenantaddress);
			    $('#premise').val(response.buildingaddress);		    
			}			
		});
	    });	 
            $("#invoicedescmasid").empty();
            $.getJSON("load_receipt.php?action=getinvcedescripton&grouptenantmasid="+grouptenantmasid, function(data) {
   $("#invoicedescmasid").append("<option value=''>--Select--</option>");
    // alert(data);
    $.each(data, function(i, item) {
       
    $("#invoicedescmasid").append("<option value="+item.invoicedescmasid+">"+item.invoicedesc+"</option>");
  
        
       });  
	 });   	    
	}
    }
	 $('[class^="total"]').live('keyup', function() {
	var samount = 0;
     $(".total").each(function(){
        samount += +$(this).val().replace(/[^0-9\.]+/g,"");
    });
    $("#totalamount").val(samount);
    });
   
    function loadinvoiceno(masid,id,seg)
    {        		
	if(seg=='1')
	    var url="load_receipt.php?action=loandinvoiceno&grouptenantmasid="+masid;
	else
	    var url="load_receipt.php?action=loandinvoiceno_others&invoicemanmasid="+masid;
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
			
           /*  var url="load_receipt.php?action=pendingpostiingintally";
            $.getJSON(url,function(data){
                $.each(data, function(i, item) {
                    //alert(data.result);
                    if(data.result > 0){
                        alert(data.result+" Receipts Pending Posting in Tally");
                    }else{
                        var dataToBeSent = $("form").serialize();
                   window.open("view_receipt.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
                    return false;
                    }
                });
                }); */
			
	       var dataToBeSent = $("form").serialize();
	       window.open("view_receipt.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
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
 if($('#remarks').val() =="")
	    {
		alert("Remarks is mandatory.");
		$("#remarks").focus();
		return false;
	    }	   
//13374 is the code for unidentified receipt
//($("#oth_grouptenantmasid").val() != '13374' || $("#oth_grouptenantmasid").val() != '14236')	   14342
            if($("#invoicedescmasid").val() =="" && $("#oth_grouptenantmasid").val() != '13374' && $("#oth_grouptenantmasid").val() != '14236' && $("#oth_grouptenantmasid").val() != '14342' )
	    {
			if($("#oth_grouptenantmasid").val() != '14862') {
		alert("Invoice Description is mandatory.");
		$("#invoicedescmasid").focus();
		return false;
		}
	    }
	    if($('#invoiceno').val() =="" && $("#oth_grouptenantmasid").val() != '13374' && $("#oth_grouptenantmasid").val() != '14236' && $("#oth_grouptenantmasid").val() != '14342' )
	    {
			if($("#oth_grouptenantmasid").val() != '14862') {
		alert("Invoice is mandatory.");
		$("#invoiceno").focus();
		return false;
			}
	    }
	    if($('.value').val() =="" && $("#oth_grouptenantmasid").val() != '13374' && $("#oth_grouptenantmasid").val() != '14236' && $("#oth_grouptenantmasid").val() != '14342')
	    {
			if($("#oth_grouptenantmasid").val() != '14862') {
		alert("Invoice Value is mandatory.");
		$("#value").focus();
		return false;
			}
	    }
	   
	    var r = confirm("Are you sure. You Want to Save?");
	    if(r==true)
	    {
		if($('#hid_segmentid').val()=='2')
		    $("#hid_grouptenantmasid").val(0);
	        var dataToBeSent = $("form").serialize();
	        window.open("save_receipt.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
			 // include in shiloah
			 $("#buildingmasid").val('');
			 var recpno = parseInt($("#rctno").val())+1;
			 $("#rctno").val(recpno);
			 $("#grouptenantmasid").val('');
			 $("#toaddress").val('');
			 $("#premise").val('');
			 $("#invoicedescmasid").val('');
			  $("#td_invlist").empty();
			  $("#invoiceno").val('');
			  $("#value").val('');
			  $("#vat").val('');
			  $(".total").val('');
			  $("#totalamount").val('0');
			  $("#paymentof").val('');
			  $("#oth_grouptenantmasid").val('');

			return false;
	    }
// reload frame	    
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
	var $id = a.substring(17);
	alert($("#hid_segmentid").val());
     //changes here
           if ($("#hid_segmentid").val()=='2'){
        
        $('[name="invoiceno_'+$id+'"]').empty();
        var $grouptenant = $('#hid_grouptenantmasid').val();
		//alert($grouptenant);
     $.getJSON("load_receipt.php?action=getaccountinvoicenum&descriptionacct="
	 +$(this).val()+"&building="+$('#buildingmasid').val()+"&grouptenant="+$grouptenant, function(data) {
   $('[name="invoiceno_'+$id+'"]').append("<option value=''>--Select--</option>");
    // alert(data);
    $.each(data, function(i, item) {
       
    $('[name="invoiceno_'+$id+'"]').append("<option value="+item.invoiceno+">"+item.invoiceno+"</option>");
  
        
       });  
	 });
     }else{
                $('[name="invoiceno_'+$id+'"]').empty();
        var $grouptenant = $('#hid_grouptenantmasid').val();
            $.getJSON("load_receipt.php?action=getaccounttenantinvoicenum&descriptionacct="+$(this).val()+"&building="+$('#buildingmasid').val()+"&grouptenant="+$grouptenant, function(data) {
   $('[name="invoiceno_'+$id+'"]').append("<option value=''>--Select--</option>");
    // alert(data);
    $.each(data, function(i, item) {
       
    $('[name="invoiceno_'+$id+'"]').append("<option value="+item.invoiceno+">"+item.invoiceno+"</option>");
  
        
       });  
	 }); 
     }
    });    
	
	    $('[class^="invoicenoclass"]').live('change', function() {
	var a = $(this).attr("name");	
	var b = $(this).val();
	//calcvat(a,b);	
	var $id = a.substring(10);
     
           if ($("#hid_segmentid").val()=='2'){
        
        //$('[name="invoiceno_'+$id+'"]').empty();
        var $grouptenant = $('#hid_grouptenantmasid').val();
        var descacct = $('[name="invoicedescmasid_'+$id+'"]').val();
            $.getJSON("load_receipt.php?action=getselectinvdetails&descriptionacct="+descacct+"&invcenum="+$(this).val()+"&building="+$('#buildingmasid').val()+"&grouptenant="+$grouptenant, function(data) {
  
    // alert(data);
    $.each(data, function(i, item) {
   
         $('[name="value_'+$id+'"]').val(commafy(item.totalamount));
         var bal = parseFloat(item.totalamount - item.paid);
          $('[name="vat_'+$id+'"]').val(commafy(bal));
       });  
	 });
     }else{
              //  $('[name="invoiceno_'+$id+'"]').empty();
        var $grouptenant = $('#hid_grouptenantmasid').val();
        var descacct = $('[name="invoicedescmasid_'+$id+'"]').val();
            $.getJSON("load_receipt.php?action=getselectinvdetails&descriptionacct="+descacct+"&invcenum="+$(this).val()+"&building="+$('#buildingmasid').val()+"&grouptenant="+$grouptenant, function(data) {
      $.each(data, function(i, item) {
       
    $('[name="value_'+$id+'"]').val(commafy(item.totalamount));
         var bal = parseFloat(item.totalamount - item.paid);
          $('[name="vat_'+$id+'"]').val(commafy(bal));
        
       });  
	 }); 
     }
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
	//$('#totalvalue').val($get_total("value"));
	//$('#totalvat').val($get_total("vat"));
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
			var vat = response.vat;
			var val1 = $('[name="value_'+str2+'"]').val()
			//var val2 = Math.round(val1*vat/100);
			var val2 = 0;
			$('[name="vat_'+str2+'"]').val(val2);
			var val3 = parseInt(val1)+parseInt(val2);
			//$('[name="total_'+str2+'"]').val(val3);//changed for purposes of clarity
			$('[name="total_'+str2+'"]').val(val1);
			
			//$('#totalvalue').val($get_total("value"));
			//$('#totalvat').val($get_total("vat"));
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
	//$('#totalvalue').val($get_total("value"));
	//$('#totalvat').val($get_total("vat"));
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
	 var descid = 'invoicedescmasid_0'+$lastChar;
	 var segmentid = $('#hid_segmentid').val();
	 $newRow = "<tr>\
		     <td><input type='text' name='sno_0"+$lastChar+"' maxlength='255' value='"+$val+"'/></td> \
		     <td><select id='invoicedescmasid_0"+$lastChar+"' name='invoicedescmasid_0"+$lastChar+"' class='invdesc' style='width:200px;'>"+loaddesc(grouptenantmasid,descid,segmentid)+"</select></td> \
		     <td><select id='invoiceno_0"+$lastChar+"' name='invoiceno_0"+$lastChar+"' class='invoicenoclass' style='width:150px;'><option value='' selected>Select</option>"+loadinvoiceno(grouptenantmasid,invoiceid,$('#hid_segmentid').val())+"</select></td> \
			<td><input type='text' class='value' id='value' name='value_0"+$lastChar+"' maxlength='255' readonly /></td> \
		     <td><input type='text' class='vat' id='vat' name='vat_0"+$lastChar+"' maxlength='255' readonly /></td> \
		     <td><input type='text' class='total' name='total_0"+$lastChar+"' maxlength='255' ></td> \
		     <td><img src='../images/delete.png' class='del_table_row'></td> \
		 </tr>"
	 return $newRow;
    }
    $('[class^="value"]').live('blur', function() {	
	//$('#totalvalue').val($get_total("value"));
	//$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
    });  
    $get_total = function(a){	
	var total = 0;   
	$('[class^="'+a+'"]').each( function(){
	    total += $(this).val() * 1;				
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
    function loaddesc(grouptenantmasid,id,segmentid){
        if(segmentid == 2){
                             $("#"+id).empty();
            $.getJSON("load_receipt.php?action=getinvcedescriptonother&grouptenantmasid="+grouptenantmasid, function(data) {
   $("#"+id).append("<option value=''>--Select--</option>");
    // alert(data);
    $.each(data, function(i, item) {
       
    $("#"+id).append("<option value="+item.invoicedescmasid+">"+item.invoicedesc+"</option>");
  
        
       });  
	 });
         
  }else{
       $("#"+id).empty();
            $.getJSON("load_receipt.php?action=getinvcedescriptontenant&grouptenantmasid="+grouptenantmasid, function(data) {
   $("#"+id).append("<option value=''>--Select--</option>");
    // alert(data);
    $.each(data, function(i, item) {
       
    $("#"+id).append("<option value="+item.invoicedescmasid+">"+item.invoicedesc+"</option>");
  
        
       });  
	 });
  }
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
    <h1 align='CENTER' style="background-color:#ff6f6f;color: #000000" >Receipt</h1>
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
		Receipt No <input type="text" id="rctno" name="rctno" style="font-size: larger;" readonly/>
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
			    <th>Amount</th>
			    <th>Balance</th>
			    <th>To Pay</th>			    
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
				    <select class="invoicenoclass" id="invoiceno" name="invoiceno_01" style='width:150px;'>
					<option value="" selected>Select</option>					
				    </select>
				</td>
				<td><input type="text" class='value' id="value" name="value_01" maxlength="255" readonly  /></td>
				<td><input type="text" class='vat' id="vat" name="vat_01" maxlength="255" readonly  /></td>
				<td><input type="text" class='total' name="total_01" maxlength="255" /></td>
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
			<!--	<input type="text" id="totalvalue" name="totalvalue" />
				&nbsp;
				<input type="text" id="totalvat" name="totalvat" />-->
				&nbsp;
				<input type="text" id="totalamount" name="totalamount"/>
			    </td>
			</tr>
		    </table>
		</td>
	</tr>
<tr>
		<td>Cheque Number</td>
		<td>		    
		    <input type="text" id="chqnum" name="chqnum" style="width:550px;display:inline;" value='0'/>
		   
		</td>
	</tr>	
	<tr>
		<td>
			Being Payment of:
		</td>
		<td>
			<textarea cols=100 rows=3 id="paymentof" name="paymentof"></textarea>
		</td>
	</tr>	
	<tr style='display:none'>
		<td>
			Remarks:<font color="red">*</font>
		</td>
		<td>
			<textarea cols=100 rows=5 id="remarks" name="remarks" value="remarks">remarks</textarea>
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
    <p id='heading' align='center'></p>
    <div id='display'>
    
    </div>
        <span class='span_cont'>Available Receipts:</span><div style='height:8px'></div>
    <?php
    $directory="../../pms_docs/receipts/";
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
    </form>
    &nbsp;&nbsp;
    <br>
</form>
</body>
</html>

