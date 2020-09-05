<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Offer Letter</title>
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
	$('#dataManipDiv').hide();
	$('#rtsqrft').attr('readonly', true);
	$('#selectOptionTblSc').hide();
	$('#selectOptionTblRent').hide();
	$('#basicrentval').hide();
	$('#basicscval').hide();
	oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		//"sPaginationType": "full_numbers"			
	});
	$('[id^="renttype"]').live('blur', function() {
		//var a = $('select.renttype option:selected').val();
		var a = $('#renttype').val();
		if(a =="rent")
		{
			$('#basicrentval').show().focus();
			$('#basicrentval').css("background-color", "#78c8e2").select();
			
		}
		else if(a=="rentpersqrft")
		{
			$('#basicrentval').show().focus();
			$('#basicrentval').css("background-color", "yellow").select();
		}
		else
		{
			$('#basicrentval').hide();
		}
	});
	$('#editleegalfees').live('click', function() {
		
		var a = $('#editleegalfees').attr('checked');
		if (a== true)
		{
			$('#deposit5').attr('readonly',false);
			$('#deposit5').css("background-color", "yellow").select();
			fillDeposit();
		}
		else
		{	$('#deposit5').attr('readonly',true);	
			$('#deposit5').css("background-color", "#f9eae6");
		}
		calcRent();//to recalculate leegal fees
		var a = $('#deposit5').val();
		$('#deposit5').val(commafy(a));
	});
	$('#editstampduty').live('click', function() {
		
		var a = $('#editstampduty').attr('checked');
		if (a== true)
		{
			$('#deposit6').attr('readonly',false);
			$('#deposit6').css("background-color", "yellow").select();
			fillDeposit();
		}
		else
		{		
			$('#deposit6').attr('readonly',true);
			$('#deposit6').css("background-color", "#f9eae6");
			calcRent();//to recalculate stamp duty
		}
	});
	$('#basicrentval').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#basicrentval').live('blur', function() {
		var a = $('#renttype').val();
		var n = parseFloat(removeComma($(this).val()));		
		n = Math.round(n);		
		if(a =="rent")
		{
			$('#rentval0').val(commafy(n.toFixed(2)));
		}
		else if(a=="rentpersqrft")
		{
			var b = parseFloat($('td#shopsize').html());
			var c = Math.round(n*b);
			$('#rentval0').val(commafy(c.toFixed(2)));
		}
			this.value = commafy(n.toFixed(2));
			calcRent();
			var t = $('#sctype').val();
			if(t =="sc")
			{
				var a = parseFloat(removeComma($('#rentval0').val()));
				var b = parseFloat(removeComma($('#basicscval').val()));
				var c = Math.round(a*b/100);
				$('#servicechrgval0').val(commafy(c.toFixed(2)));
				calcSc();
			}
			if ($(this).val()=="") 
			$(this).val('0');
	});
	$('[id^="rentpercentage"]').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('[id^="rentpercentage"]').live('blur', function() {
		calcRent();
		if ($(this).val()=="") 
	        $(this).val('0');
	});
	function calcRent()
	{
		var i=1;
		var z=0;
		$('[id^="rentval"]').each(function(){
			//first value
			var a = "#rentval"+z;
			var value = parseFloat(removeComma($(a).val()));
			
			//second value
			var m = "#rentpercentage"+i;
			var c = parseFloat($(m).val());
			var r =Math.round((value*c/100)+value);
			
			//final value
			var b = "#rentval"+i;
			$(b).val(commafy(r.toFixed(2)));

			i++;
			z++;
		});
		var lastRentId = "#rentval"+(z-1);
		//alert($(lastRentId).val());
		calcdeposit5(lastRentId);
		calcdeposit6(lastRentId);
		fillDeposit();
	}
	$('[id^="sctype"]').live('blur', function() {
		//var a = $('select.sctype option:selected').val();
		var a = $('#sctype').val();
		if(a =="sc")
		{
			$('#basicscval').show().focus();
			$('#basicscval').css("background-color", "#78c8e2").select();
			
		}
		else if(a=="scpersqrft")
		{
			$('#basicscval').show().focus();
			$('#basicscval').css("background-color", "yellow").select();
		}
		else
		{
			$('#basicscval').hide();
		}
	});
	$('#basicscval').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#basicscval').live('blur', function() {
		var a = $('#sctype').val();		
		if(a =="sc")
		{
			var a = parseFloat(removeComma($('#rentval0').val()));
			var b = parseFloat($(this).val());
			var c = Math.round(a*b/100);
			$('#servicechrgval0').val(commafy(c.toFixed(2)));
			
		}
		else if(a=="scpersqrft")
		{
			var a = parseFloat(removeComma($(this).val()));
			var b = parseFloat($('td#shopsize').html());
			var c = Math.round(a*b);
			$('#servicechrgval0').val(commafy(c.toFixed(2)));
		}
		calcSc();
	});
	$('[id^="servicechrgpercentage"]').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('[id^="servicechrgpercentage"]').live('blur', function() {
		calcSc();
		if ($(this).val()=="") 
		$(this).val('0');
	});
	function calcSc()
	{
		var i=1;
		var z=0;
		$('[id^="servicechrgval"]').each(function(){
			//first value
			var a = "#servicechrgval"+z;
			var value = parseFloat(removeComma($(a).val()));
			
			//second value
			var m = "#servicechrgpercentage"+i;
			var c = parseFloat($(m).val());
			var r =Math.round((value*c/100)+value);
			
			//final value
			var b = "#servicechrgval"+i;
			$(b).val(commafy(r.toFixed(2)));
			
			i++;
			z++;
		});
		fillDeposit();
	}
	$('#deposit5').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit5').live('blur', function() {
		fillDeposit();
		this.value = commafy(this.value);
	})
	$('#deposit6').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit6').live('blur', function() {
		fillDeposit();
		this.value = commafy(this.value);
	})
	$('#depositmonthrent').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#depositmonthrent').live('blur', function() {
		fillDeposit();
	})
	$('#depositmonthsc').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#depositmonthsc').live('blur', function() {
		fillDeposit();
	})
	$('#advancemonthrent').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#advancemonthrent').live('blur', function() {
		fillDeposit();
	})
	$('#advancemonthsc').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#advancemonthsc').live('blur', function() {
		fillDeposit();
	})
	$('#leegalfeevat').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#leegalfeevat').live('blur', function() {
		var i=1;
		$('[id^="rentval"]').each(function(){
				i++;
		});
		var lastRentId = "#rentval"+(i-2);
		calcdeposit5(lastRentId);
		fillDeposit();
	})
	$('#stampdutyvat').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#stampdutyvat').live('blur', function() {
		var i=1;
		$('[id^="rentval"]').each(function(){
				i++;
		});
		var lastRentId = "#rentval"+(i-2);
		calcdeposit6(lastRentId);
		fillDeposit();
	})
	$('#rtsqrft').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit7').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit7').live('blur', function() {
		fillDeposit();
	})
	function fillDeposit()
	{
		if (($('#rentval0').val() !=0) || ($('#servicechrgval0').val() !=0))
		{
			rentcycle = $('#rentcycle').val();
			rentval=0;sc=0;
			if(rentcycle == "Per Quarter")
			{
				rent =Math.round(parseFloat(removeComma($('#rentval0').val()))/3);
				sc = Math.round(parseFloat(removeComma($('#servicechrgval0').val()))/3);
			}
			else if (rentcycle == "Per Year")
			{
				rent =Math.round(parseFloat(removeComma($('#rentval0').val()))/12);
				sc = Math.round(parseFloat(removeComma($('#servicechrgval0').val()))/12);
			}
			else if (rentcycle == "Per Month")
			{
				rent = $('#rentval0').val();
				sc = $('#servicechrgval0').val();
			}			
			
			$('#deposit1').val(commafy(removeComma(rent)*$('#depositmonthrent').val()));
			$('#deposit2').val(commafy(removeComma(sc)*$('#depositmonthsc').val()));
			
			var a = parseFloat(removeComma(rent)*removeComma($('#advancemonthrent').val()));
			var v = '14';//VAT
			var b = Math.round((a+(a*v/100)));
			b = b.toFixed(2);
			$('#deposit3').val(commafy(b));
			
			var a = parseFloat(removeComma(sc)*removeComma($('#advancemonthsc').val()));
			var v = '14';//VAT
			var b = Math.round((a+(a*v/100)));
			b = b.toFixed(2);
			$('#deposit4').val(commafy(b));
			
			$('#deposit8').val(commafy(depositTotal()));
			//$('#deposit1').val(commafy(removeComma($('#rentval0').val())*$('#depositmonthrent').val()));
			//$('#deposit2').val(commafy(removeComma($('#servicechrgval0').val())*$('#depositmonthsc').val()));
			//
			//var a = parseFloat(removeComma($('#rentval0').val())*removeComma($('#advancemonthrent').val()));
			//var v = '14';//VAT
			//var b = Math.round((a+(a*v/100)));
			//b = b.toFixed(2);
			//$('#deposit3').val(commafy(b));
			//
			//var a = parseFloat(removeComma($('#servicechrgval0').val())*removeComma($('#advancemonthsc').val()));
			//var v = '14';//VAT
			//var b = Math.round((a+(a*v/100)));
			//b = b.toFixed(2);
			//$('#deposit4').val(commafy(b));
			//
			//$('#deposit8').val(commafy(depositTotal()));
		}
	}
	function depositTotal()
	{
		var v1 = parseFloat(removeComma($('#deposit1').val()));
		var v2 = parseFloat(removeComma($('#deposit2').val()));
		var v3 = parseFloat(removeComma($('#deposit3').val()));
		var v4 = parseFloat(removeComma($('#deposit4').val()));
		var v5 = parseFloat(removeComma($('#deposit5').val()));
		var v6 = parseFloat(removeComma($('#deposit6').val()));
		var v7 = parseFloat(removeComma($('#deposit7').val()));
		var total = (v1+v2+v3+v4+v5+v6+v7);
		////var total = (v1+v2);
		total = total.toFixed(2);
		return total;
	}
	$('#rtsqrftsc').live('blur', function() {
			var sqVal = parseFloat($('td#shopsize').html());
			var b = parseFloat($('#rtsqrftsc').val());
			var c = Math.round(sqVal*b);
			var x = "#servicechrgval0";
			var j = $(x).val(commafy(c));
			var i=1;
			var z=1;
			$('[id^="servicechrgval"]').each(function(){
				var value = parseFloat(removeComma($(this).val()));
				var n = "#servicechrgval"+i;
				$(n).val(commafy(scCalc(value,i,z)));
				i++;
				z++;
			});
		fillDeposit();
	});
        //edit legal fee
//	function calcdeposit5(a)
//	{
//		var v= $('#editleegalfees').attr('checked');
//		if( v== false)
//		{
//			var b = parseFloat(removeComma($(a).val()));
//			var c = $('#leegalfeevat').val();
//			var d = Math.round(((b*12*(c/100))*1.14));
//			d = d.toFixed(2);
//			$('#deposit5').val(commafy(d));
//		}
//		
//	}
        function calcdeposit5(a)
	{
		var v= $('#editleegalfees').attr('checked');
                rentcycle = $('#rentcycle').val();
		if( v== false&&rentcycle == "Per Quarter")
		{
			var b = parseFloat(removeComma($(a).val()));
			var c = $('#leegalfeevat').val();
                        var d = Math.round(((b*(12/3)*(c/100))));
			//var d = Math.round(((b*12*4*(c/100))*1.14));
			d = d.toFixed(2);
			$('#deposit5').val(commafy(d));
		}else if( v== false&&rentcycle == "Per Year")
		{
			var b = parseFloat(removeComma($(a).val()));
			var c = $('#leegalfeevat').val();
			//var d = Math.round(((b*12*4*(c/100))*1.14));
                        var d = Math.round(((b*(c/100))));
			d = d.toFixed(2);
			$('#deposit5').val(commafy(d));
		}else if( v== false)
		{
			var b = parseFloat(removeComma($(a).val()));
			var c = $('#leegalfeevat').val();
			var d = Math.round(((b*12*(c/100))));
			d = d.toFixed(2);
			$('#deposit5').val(commafy(d));
		}
		
	}
//	function calcdeposit6(a)
//	{
//		var v= $('#editstampduty').attr('checked');
//		if( v== false)
//		{
//			var b = parseFloat(removeComma($(a).val()));
//			var c = $('#stampdutyvat').val();
//			var d = Math.round(((b*12*(c/100))));
//			d = d.toFixed(2);
//			$('#deposit6').val(commafy(d));
//		}
//	}
        
            function calcdeposit6(a)
	{
		var v= $('#editstampduty').attr('checked');
                rentcycle = $('#rentcycle').val();
		if( v== false&&rentcycle == "Per Quarter")
		{
			var b = parseFloat(removeComma($(a).val()));
			var c = $('#stampdutyvat').val();
                        var d = Math.round(((b*(12/3)*(c/100))));
			d = d.toFixed(2);
			$('#deposit6').val(commafy(d));
		}else if( v== false&&rentcycle == "Per Year")
		{
			var b = parseFloat(removeComma($(a).val()));
			var c = $('#stampdutyvat').val();
                        var d = Math.round(((b*(c/100))));
			d = d.toFixed(2);
			$('#deposit6').val(commafy(d));
		}else if( v== false)
		{
			var b = parseFloat(removeComma($(a).val()));
			var c = $('#stampdutyvat').val();
			var d = Math.round(((b*12*(c/100))));
			d = d.toFixed(2);
			$('#deposit6').val(commafy(d));
		}
		
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
	$('#btnNew').click(function(){
            //alert("MMM");
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Offer Letter");
                $("#header2").css('background-color', '#c8dbc9');
                $("#header3").css('background-color', '#c8dbc9');
		$("#header4").css('background-color', '#c8dbc9');
                $("#tblScHeader").css('background-color', '#9cccf3');
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$('#tenantmasid').show()
		$("#offerlettermasid").hide();
		$("#age").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#age").val("");
		$("#description").val("");
		loadTenant();
		clearForm();
	});
	
	function loadTenant()
	{
		/// alert("MMMM");
                var url="load_offerletter.php?item=loadTenant"	
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
                                      //alert("MM");  
                                        
                                        $('#tenantmasid').empty();
					$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
					$.each(data.myResult, function(i,response){
					//alert("response.tradingname");	
//						if(response.tradingname !="")
//							var a = response.tradingname+" ("+response.tenantcode+")";
//						else
//							var a = response.leasename+" ("+response.tradingname+")";
						var t = response.tradingname;
                                                
							if(t !=""||t !=null)
							    var b = response.tradingname;
							else
							    var b = response.leasename;
							var r = response.renewalfromid;
							if(r <=0)
							    var a = b+" ("+response.shopcode+")";
							else
							var a = b+" ("+response.shopcode+" RENEWED)" ;
						$('#tenantmasid').append( new Option(a,response.tenantmasid,true,false) );
						
					});
				}
				else
				{
					//alert(response.s);
					alert("No new tenants found !!!");
					$('#tenantmasid').empty();
					$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
				}
			});		
		});
	}
	$('#btnEdit').click(function(){
		$('cc').html('');
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Offer Letter");
		$("#header2").css('background-color', '#d3d3d3');
                $("#header3").css('background-color', '#d3d3d3');
		$("#header4").css('background-color', '#d3d3d3');
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#offerlettermasid").show();
		$('#tenantmasid').hide()
		$("#agemasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$("#offerletter").val("");
		$("#description").val("");		
		loadOfferletter();
		clearForm();
	});
	function loadOfferletter()
	{
		var url="load_offerletter.php?item=loadOfferLetter";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#offerlettermasid').empty();
						$('#offerlettermasid').append( new Option("-----Select offer letter-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							//if(response.tradingname !="")
							//	var a = response.tradingname+" ("+response.tenantcode+")";
							//else
							//	var a = response.leasename+" ("+response.tenantcode+")";
							var t = response.tradingname;
							if(t !="")
							    var b = response.tradingname;
							else
							    var b = response.leasename;
							var r = response.renewalfromid;
							if(r <=0)
							    var a = b+" ("+response.shopcode+")";
							else
							var a = b+" ("+response.shopcode+" RENEWED)" ;
							$('#offerlettermasid').append( new Option(a,response.offerlettermasid,true,false) );
						});
					}
					else
					{
						alert(response.msg);
						$('#offerlettermasid').empty();
						$('#offerlettermasid').append( new Option("-----No Offer Letter-----","",true,false) );	
					}
				});		
			});
	}
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if($('#tenantmasid').val() =="" )
		{
			alert("Please select tenant");
			return false;
		}
		if(($('#basicrentval').val() ==0 ) || ($('#basicrentval').val() ==""))
		{
			alert("Please enter Rent Details");
			return false;
		}
		//if(($('#basicscval').val() ==0 ) || ($('#basicscval').val() ==""))
		//{
		//	alert("Please enter Service Chrg Details");
		//	return false;
		//}
		//if(($('#deposit5').val() ==0 ) || ($('#deposit5').val() ==""))
		//{
		//	alert("Please enter Leegal Fees");
		//	return false;
		//}
		//if(($('#deposit6').val() ==0 ) || ($('#deposit6').val() ==""))
		//{
		//	alert("Please enter Stamp Duty");
		//	return false;
		//}
		//if(($('#deposit7').val() ==0 ) || ($('#deposit7').val() ==""))
		//{
		//	alert("Please enter Registration Fees");
		//	return false;
		//}
		////if(($('#depositmonthrent').val() ==0 ) || ($('#depositmonthrent').val() ==""))
		////{
		////	alert("Please enter rent in advance for months");			
		////	return false;
		////}
		////if(($('#depositmonthsc').val() ==0 ) || ($('#depositmonthsc').val() ==""))
		////{
		////	alert("Please enter service charge in advance for months");
		////	return false;
		////}
		//if(($('#advancemonthrent').val() ==0 ) || ($('#advancemonthrent').val() ==""))
		//{
		//	alert("Please enter VAT for rent in advance");
		//	return false;
		//}
		//if(($('#advancemonthsc').val() ==0 ) || ($('#advancemonthsc').val() ==""))
		//{
		//	alert("Please enter VAT for service charge in advance");
		//	return false;
		//}
		//if(($('#leegalfeevat').val() ==0 ) || ($('#leegalfeevat').val() ==""))
		//{
		//	alert("Please enter VAT for Leegal Fee");
		//	return false;
		//}
		//if(($('#stampdutyvat').val() ==0 ) || ($('#stampdutyvat').val() ==""))
		//{
		//	alert("Please enter VAT for select Stamp Duty");
		//	return false;
		//}
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			var url="save_offerletter.php?action=Save";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{
							$('#depositmonthrent').val('3');
							$('#depositmonthsc').val('3');
							$('#advancemonthrent').val('14');
							$('#advancemonthsc').val('14');
							$('#leegalfeevat').val('4');
							$('#stampdutyvat').val('2.5');
							$('#cc').html(response.msg);
							$('#tenantDetails').html('');
							$('#rentbody').html('');
							$('#servicechrgbody').html('');
							$('#depositbody').html('');
							$('#premises').html('');
							$('#shopsize').html('');
							$('#leasename').html('');
							$('#term').html('');
							$('#term').val('');
							$('#doo').html('');
							$('#sp').val('');
							$('#doc').html('');
							$('#selectOptionTblRent').hide();
							$('#selectOptionTblSc').hide();
							loadTenant();
						
                                             
                                        
                                    
                                }
						else
						{
							//alert(response.msg);
							$('#cc').html(response.msg);
						}
						//alert(response.msg);
						$('#cc').html(response.msg);
					});
			});
		}
		else
		{
			return false;
		}
	});
	
	$('#btnUpdate').click(function(){
		if($('#offerlettermasid').val() =="" )
		{
			alert("Please select an offerletter");
			return false;
		}
		if(($('#rentval0').val() ==0 ) || ($('#rentval0').val() ==""))
		{
			alert("Please enter Rent details");
			return false;
		}
		var a = $('input[name=sctype]:checked').val();
		if(a == "sqrft")
		{
			if(($('#rtsqrft').val() ==0 ) || ($('#rtsqrft').val() ==""))
			{	
				alert("Please enter Rate/Sqrft");
				return false;
			}	
		}
		//if(($('#depositmonthrent').val() ==0 ) || ($('#depositmonthrent').val() ==""))
		//{
		//	alert("Please enter rent in advance for months");			
		//	return false;
		//}
		//if(($('#depositmonthsc').val() ==0 ) || ($('#depositmonthsc').val() ==""))
		//{
		//	alert("Please enter service charge in advance for months");
		//	return false;
		//}
		//if(($('#advancemonthrent').val() ==0 ) || ($('#advancemonthrent').val() ==""))
		//{
		//	alert("Please enter VAT for rent in advance");
		//	return false;
		//}
		//if(($('#advancemonthsc').val() ==0 ) || ($('#advancemonthsc').val() ==""))
		//{
		//	alert("Please enter VAT for service charge in advance");
		//	return false;
		//}
		//if(($('#leegalfeevat').val() ==0 ) || ($('#leegalfeevat').val() ==""))
		//{
		//	alert("Please enter VAT for Leegal Fee");
		//	return false;
		//}
		//if(($('#stampdutyvat').val() ==0 ) || ($('#stampdutyvat').val() ==""))
		//{
		//	alert("Please enter VAT for select Stamp Duty");
		//	return false;
		//}
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			var $offerlettermasid = $('#offerlettermasid').val();
			var url="save_offerletter.php?action=Update&offerlettermasid="+$offerlettermasid;
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{
							$('#depositmonthrent').val('3');
							$('#depositmonthsc').val('3');
							$('#advancemonthrent').val('14');
							$('#advancemonthsc').val('14');
							$('#leegalfeevat').val('4');
							$('#stampdutyvat').val('2.5');
							$('#cc').html(response.msg);
							$('#tenantDetails').html('');
							$('#rentbody').html('');
							$('#servicechrgbody').html('');
							$('#depositbody').html('');
							$('#premises').html('');
							$('#shopsize').html('');
							$('#leasename').html('');
							$('#term').html('');
							$('#term').val('');
							$('#doo').html('');
							$('#doc').html('');
							$('#selectOptionTblRent').hide();
							$('#selectOptionTblSc').hide();
							loadOfferletter();
						}
						else
						{
							//alert(response.msg);
							$('#cc').html(response.msg);
						}
						//alert(response.msg);
						$('#cc').html(response.msg);
					});
			});
		}
	});
	
	$("#offerlettermasid").change(function(){
		var $offerlettermasid = $('#offerlettermasid').val();
		clearForm();
		if($offerlettermasid !="")
		{
			var url="load_offerletter.php?item=detailsOfferLetter&itemval="+$offerlettermasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
					var i=0;
					//$("#rentbody").empty();
					$("#servicechrgbody").empty();
					 $.each(data.myResult, function(i,response){
						$('#cc').html('');
					         var tenantDetails = "Attn: "+response.cpname+"<br />"+response.leasename+"<br />"+response.poboxno+"-"+response.pincode+"<br />"+response.city;
                                                    var premises = response.buildingname+" <br />"+response.blockname+"<br />"+response.floorname+" <br />"+response.shopcode;
                                                    $('#tenantDetails').html(tenantDetails);
                                                    $('#premises').html(premises);
						    $('#shopsize').html(response.size+" Sqrft");
                                                    $('#leasename').html(response.leasename+" ("+response.tenantcode+" Trading Name: "+response.tradingname+" Floor: "+response.floorname+")");
						    $("#hidLeasename").val(response.leasename);
                                                    $('#term').html(response.term);
                                                    $('#doo').html(response.doo);
                                                    $('#doc').html(response.doc);
                                                    var str = response.term;
                                                    var strArray = str.split(' ');
                                                    var cnt =strArray[0];
                                                    var s  = strArray[1].toLowerCase();
                                                    var url="load_offerletter.php?item=loadTransDetFromOfferLetter&itemval="+$offerlettermasid;
						    $('#rentbody').html('');
						    $('#servicechrgbody').html('');
						    $('#depositbody').html('');
                                                    $.getJSON(url,function(data){
                                                	$.each(data.error, function(i,response){
                                                		if(response.s == "Success")
                                                		{
									var a = response.renttype;
									$('#renttype').val(a);
									$('#basicrentval').val(commafy(response.basicrentval));
									$('#basicrentval').show();
									if(a== "rent") 
									{
										$('#basicrentval').css("background-color", "#78c8e2");
									}
									else
									{
										$('#basicrentval').css("background-color", "yellow");
									}
									var b = response.sctype;
									$('#sctype').val(b);
									$('#basicscval').val(response.basicscval);
									$('#basicscval').show();
									if(b== "sc")
									{
										$('#basicscval').css("background-color", "#78c8e2");
									}
									else
									{
										$('#basicscval').css("background-color", "yellow");
									}
									var renttblRows =response.msg;
									$('#rentbody').append(renttblRows);
									
									var servicetblRows =response.tbl;
									$('#servicechrgbody').append(servicetblRows);
									
									var deposit =  response.deposit;
									$('#depositbody').append(deposit);
									
									$('#selectOptionTblSc').show();
									$('#selectOptionTblRent').show();
									var a = response.editleegalfees;
									if(a==1) 
									{
										$('#deposit5').css("background-color", "yellow");
									}
									var b = response.editstampduty;
									if(b==1) 
									{
										$('#deposit6').css("background-color", "yellow");
									}
									
                                                                }
								else
								{
									alert(response.msg);
								}
                                                        });
                                                    });
                                                        i++;
                                            });
					}
					else
					{
						
					alert(response.msg);
                                        clearForm();
					}
				});
			});
			var url="load_offerletter.php?item=detailsRent&itemval="+$offerlettermasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						
					}
					else
					{
					       $('#cc').html(response.msg);
					       //alert(response.msg);
                                               clearForm();
					}
				});
			});
		}
		else
		{
			clearForm();
		}
	});
	
	$("#tenantmasid").change(function(){
          alert("HAHA");
		var $tenantmasid = $('#tenantmasid').val();
		clearForm();
		if($tenantmasid !="")
		{
			var url="load_offerletter.php?item=detailsTenant&itemval="+$tenantmasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            var i=0;
                                           //$("#rentbody").empty();
                                           $("#servicechrgbody").empty();
                                            $.each(data.myResult, function(i,response){
						$('#cc').html('');
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
                                                    var str = response.term;
                                                    var strArray = str.split(' ');
                                                    var cnt =strArray[0];
                                                    var s  = strArray[1].toLowerCase();
						    
                                                    var url="load_offerletter.php?item=loadRentTbl&itemval="+$tenantmasid;
						    $('#rentbody').html('');
						    $('#servicechrgbody').html('');
						    $('#depositbody').html('');
						    $('#selectOptionTblSc').show();
						    $('#selectOptionTblRent').show();
						    //$('input:radio[name=sctype]')[0].checked = true;
						    //$('input:radio[name=sctype]')[1].checked = false;
                                                    $.getJSON(url,function(data){
                                                	$.each(data.error, function(i,response){
                                                		if(response.s == "Success")
                                                		{
									var renttblRows =response.msg;
									var servicetblRows =response.tbl;
									var deposit =  response.deposit;
									$('#rentbody').append(renttblRows);
									$('#servicechrgbody').append(servicetblRows);
									$('#depositbody').append(deposit);
                                                                }
                                                        });
                                                    });
                                                        i++;
                                            });
					}
					else
					{
						//alert(response.msg);
						$('#cc').html(response.msg);
                                                //$("#rentbody").empty();
                                               clearForm();
					}
				});             
                        });
		}
		else
		{
			//$('input[type=text]').val('');
			// $("#rentbody").empty();
			clearForm();
		}
	});
	function clearForm()
	{
		$('#tenantDetails').html('');
                $('#rentbody').html('');
		$('#servicechrgbody').html('');
		$('#depositbody').html('');
		$('#premises').html('');
		$('#shopsize').html('');
		$('#leasename').html('');
		$('#term').html('');
		$('#term').val('');
		$('#doo').html('');
		$('#doc').html('');
		$('#selectOptionTblSc').hide();
		$('#selectOptionTblRent').hide();
		$('#renttype').val('');
		$('#basicrentval').hide();
		$('#sctype').val('');
		$('#basicscval').hide();
	}
});
</script>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<!--<div id="container">-->
<center><h1>Offer Letter</h1></center><br>
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
<div id="exampleDiv">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>							
							<th>Tenant</th>
							<th>Premises</th>
							<th>Term</th>
							<th>Rent Cycle</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$companymasid = $_SESSION['mycompanymasid'];
					$sql = "select a.leasename,a.tradingname,a.tenantcode,b.buildingname,c.blockname,d.floorname,e.shopcode,f.age as 'term' , g.age as 'rentcycle' from\n"
					. "trans_offerletter t\n"
					. "inner join mas_tenant a on a.tenantmasid = t.tenantmasid\n"
					. "inner join mas_building b on b.buildingmasid = a.buildingmasid\n"
					. "inner join mas_block c on c.blockmasid = a.blockmasid\n"
					. "inner join mas_floor d on d.floormasid = a.floormasid\n"
					. "inner join mas_shop e on e.shopmasid = a.shopmasid\n"
					. "inner join mas_age f on f.agemasid = a.agemasidlt\n"
					. "inner join mas_age g on g.agemasid = a.agemasidrc\n"
					. "where t.companymasid=$companymasid and a.active='1'";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;$tradingname="";
							   while ($row = mysql_fetch_assoc($result))
								   {
									//if($row['tradingname'] != "")
									//$tenant = $row["tradingname"]." (".$row["tenantcode"].")";
									//else
									//$tenant = $row["leasename"]." (".$row["tenantcode"].")";
									//$premisis = $row["buildingname"].",<br>".$row["blockname"].",<br>".$row["floorname"].",<br>".$row["shopcode"].".";
									$leasename = $row["leasename"];
									$premisis = $row["shopcode"];
									$term = $row["term"];
                                                                        $rentcycle = $row["rentcycle"];
									if($row["tradingname"] !="")
                                                                        $tradingname ="  <b>T/A</b>  ".$row["tradingname"];
									$leasename .= $tradingname;
                                                                        
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$leasename."</td>
									<td>".$premisis."</td>
									<td>".$term."</td>
                                                                        <td>".$rentcycle."</td>";
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
							<th>Premises</th>
							<th>Term</th>
							<th>Rent Cycle</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table2" width='100%'>
	<!--<thead>-->
		<tr>
			<th id="tblheader" align="center" colspan=4>
				Create New Offer Letter	
			</th>
		</tr>
	<!--</thead>-->
	<tbody>
	<tr id="selecTenant">
		<td>
			Select Tenant: <font color="red">*</font>
		</td>
		<td>
			<select id="tenantmasid" name="tenantmasid">
				<option value="" selected>--Select Tenant--</option>
			</select>
			<select id="offerlettermasid" name="offerlettermasid">
				<option value="" selected>--Select Offer letter--</option>
			</select>
		</td>	
		<td>
			Date:
		</td>
                <td colspan=3>
			<?php echo date("d-F-Y");?>
			<input type="hidden" id="hidLeasename" name="hidLeasename" value="">
		</td>
	</tr>
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
         <tr>
		<td>
                     <b>Rent Chart</b>
                </td>
		<td colspan=3>
			<div id="selectOptionTblRent">
				<select id='renttype' name='renttype'>
					<option selected='selected' value=''>Please select Rent Type</option>
					<option value='rent'>Flat Rate</option>
					<option value='rentpersqrft'>Rt/Sqrft</option>
				</select>
				<input type="text" style="width:150px;" dir='rtl' id="basicrentval" name="basicrentval"/>
			</div>
                </td>
        </tr>
        <tr>
		<td colspan=4>
			<table id="renttbl" class=table2 width='98%'>
                            <tr>
                                <thead id="header2">
                                    <th>From</th>
                                    <th>To</th>
                                    <th>yearly hike %</th>
                                    <th>Amount (KSH)</th>
                                    <th>Period</th>
                                </thead>
                            </tr>
                            <tbody id=rentbody>
                            </tbody>
                        </table>
                </td>
	</tr>
         <tr>
		<td>
                    <b>Service Charge Chart</b>
                </td>
		<td colspan=3>
			<div id="selectOptionTblSc">
				<select id='sctype' name='sctype'>
					<option selected='selected' value=''>Please select Service Charge Type</option>
					<option value='sc'>% In Rent</option>
					<option value='scpersqrft'>Rt/Sqrft</option>
				</select>
				<input type="text" style="width:150px;" dir='rtl' id="basicscval" name="basicscval"/>
			</div>
                </td>
        </tr>
         <tr>
		<td colspan=4>
			<table id="servicechrgtbl" class=table2 width='98%'>
                            <tr>
                                <thead id="header3">
                                    <th>From</th>
                                    <th>To</th>
                                    <th>yearly hike %</th>
                                    <th>Amount (KSH)</th>
                                    <th>Period</th>
                                </thead>
                            </tr>
                            <tbody id=servicechrgbody>
                            </tbody>
                        </table>
                </td>
	</tr>
	   <tr>
		<td colspan='4'>
                     <b>Deposit</b>
                </td>
        </tr>
         <tr>
		<td colspan=4>
			<table id="deposittbl" class=table2 width='98%'>
                            <tr>
                                <!--<tr id="header4">-->
					<th>Index</th>
					<th>Description</th>
					<th>Month & VAT</th>
					<th>Amount (KSH)</th>
                                <!--</t>-->
                            </tr>
                            <tbody id=depositbody>
                            </tbody>
                        </table>
                </td>
	 </tr>
	<tr id="newTr">
		<td>
			
		</td>
		<td align='center'>
			<button type="reset" id="btnReset">Reset</button>
		</td>
		<td align='center' colspan=2>
			<button type="button" id="btnSave">Create New Offer Letter</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
		</td>
		<td colspan=3>
			<button type="button" id="btnUpdate">Update Offer Letter</button>
		</td>
	</tr>
	</tbody>
</table>

</div>
<!--</div> Main Div-->
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</body>
</html>
