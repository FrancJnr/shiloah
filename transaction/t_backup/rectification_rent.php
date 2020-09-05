<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Rectification of Rent</title>
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
	});
	$(".dateofrectification").datepicker({ dateFormat: "mm/dd/yy" });
	$('[id^="renttype"]').live('blur', function() {		
		//var a = $('#renttype').val();
		//if(a =="rent")
		//{
		//	$('#basicrentval').show().focus();
		//	$('#basicrentval').css("background-color", "#78c8e2").select();
		//	
		//}
		//else if(a=="rentpersqrft")
		//{
		//	$('#basicrentval').show().focus();
		//	$('#basicrentval').css("background-color", "yellow").select();
		//}
		//else
		//{
		//	$('#basicrentval').hide();
		//}
	});	
	$('#basicrentval').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#basicrentval').live('blur', function() {
		//var a = $('#renttype').val();
		//var n = parseFloat(removeComma($(this).val()));		
		//n = Math.round(n);		
		//if(a =="rent")
		//{
		//	$('#rentval0').val(commafy(n.toFixed(2)));
		//}
		//else if(a=="rentpersqrft")
		//{
		//	var b = parseFloat($('td#shopsize').html());
		//	var c = Math.round(n*b);
		//	$('#rentval0').val(commafy(c.toFixed(2)));
		//}
		//	this.value = commafy(n.toFixed(2));
		//	calcRent();
		//	var t = $('#sctype').val();
		//	if(t =="sc")
		//	{
		//		var a = parseFloat(removeComma($('#rentval0').val()));
		//		var b = parseFloat(removeComma($('#basicscval').val()));
		//		var c = Math.round(a*b/100);
		//		$('#servicechrgval0').val(commafy(c.toFixed(2)));
		//		calcSc();
		//	}
		//	if ($(this).val()=="") 
		//	$(this).val('0');
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
		fillDeposit();
	}
	$('[id^="sctype"]').live('blur', function() {		
		//var a = $('#sctype').val();
		//if(a =="sc")
		//{
		//	$('#basicscval').show().focus();
		//	$('#basicscval').css("background-color", "#78c8e2").select();
		//	
		//}
		//else if(a=="scpersqrft")
		//{
		//	$('#basicscval').show().focus();
		//	$('#basicscval').css("background-color", "yellow").select();
		//}
		//else
		//{
		//	$('#basicscval').hide();
		//}				
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
	$('#rtsqrft').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
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
		}
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
	function removeComma(val)
	{
		return String(val).replace(/\,/g, '');		
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
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Alter Rent");
                $("#header2").css('background-color', '#c8dbc9');
                $("#header3").css('background-color', '#c8dbc9');
		$("#header4").css('background-color', '#c8dbc9');
                $("#tblScHeader").css('background-color', '#9cccf3');
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();				
		$("#age").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#age").val("");
		$("#description").val("");
		$('#recofferlettermasid').hide();
		$('#offerlettermasid').show();
		loadOfferletter();
		clearForm();
	});
	function loadOfferletter()
	{
		var url="load_rec_rent.php?item=loadOfferLetter";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#offerlettermasid').empty();
						$('#offerlettermasid').append( new Option("-----Select offer letter-----","",true,false) );		
						$.each(data.myResult, function(i,response){							
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
	$('#btnEdit').click(function(){
		$('cc').html('');
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Rent ");
		$("#header2").css('background-color', '#d3d3d3');
                $("#header3").css('background-color', '#d3d3d3');
		$("#header4").css('background-color', '#d3d3d3');
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();		
		$('#tenantmasid').hide()
		$("#agemasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$("#offerletter").val("");
		$("#description").val("");
		$('#recofferlettermasid').show();
		$('#offerlettermasid').hide();
		loadrecofferletter();
		clearForm();
	});
	
	function loadrecofferletter()
	{
		var url="load_rec_rent.php?item=loadrecofferletter";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#recofferlettermasid').empty();
						$('#recofferlettermasid').append( new Option("-----Select offer letter-----","",true,false) );		
						$.each(data.myResult, function(i,response){
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
							//var a = response.leasename+" ("+response.tenantcode+")";
							$('#recofferlettermasid').append( new Option(a,response.offerlettermasid,true,false) );
						});
					}
					else
					{						
						$('#cc').html(response.msg);
						$('#recofferlettermasid').empty();
						$('#recofferlettermasid').append( new Option("-----No Offer Letter-----","",true,false) );	
					}
				});		
			});
	}
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if($('#offerlettermasid').val() =="" )
		{
			alert("Please select tenant");
			return false;
		}
		if(($('#basicrentval').val() ==0 ) || ($('#basicrentval').val() ==""))
		{
			alert("Please enter Rent Details");
			return false;
		}		
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			$('#hidFromdateRent0').val($('#dateofrectificationrent').val());
			$('#hidFromdateSc0').val($('#dateofrectificationrent').val());
			///alert($('#hidFromdateRent0').val());			
			var url="save_rec_rent.php?action=Save";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{							
							$('#cc').html(response.msg);
							$('#tenantDetails').html('');
							$('#rentbody').html('');
							$('#servicechrgbody').html('');							
							$('#premises').html('');
							$('#shopsize').html('');
							$('#leasename').html('');
							$('#term').html('');
							$('#term').val('');
							$('#doo').html('');
							$('#doc').html('');
							$('#selectOptionTblRent').hide();
							$('#selectOptionTblSc').hide();							
							loadTenant();
						}
						else
						{							
							$('#cc').html(response.msg);
						}												
					});
			});
		}
		else
		{
			return false;
		}
	});
	
	$('#btnUpdate').click(function(){
		if($('#recofferlettermasid').val() =="" )
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
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			$('#hidFromdateRent0').val($('#dateofrectificationrent').val());
			$('#hidFromdateSc0').val($('#dateofrectificationrent').val());
			
			var $offerlettermasid = $('#offerlettermasid').val();
			var url="save_rec_rent.php?action=Update&offerlettermasid="+$offerlettermasid;
			
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{							
							$('#cc').html(response.msg);
							$('#tenantDetails').html('');
							$('#rentbody').html('');
							$('#servicechrgbody').html('');							
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
			var url="load_rec_rent.php?item=detailsOfferLetter&itemval="+$offerlettermasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
					var i=0;					
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
                                                    $('#doo').html(response.doo);
                                                    $('#doc').html(response.doc);
                                                    var str = response.term;
                                                    var strArray = str.split(' ');
                                                    var cnt =strArray[0];
                                                    var s  = strArray[1].toLowerCase();
                                                    var url="load_rec_rent.php?item=loadTransDetFromOfferLetter&itemval="+$offerlettermasid;
						    $('#rentbody').html('');
						    $('#servicechrgbody').html('');						    
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
									
									$('#selectOptionTblSc').show();
									$('#selectOptionTblRent').show();									
									
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
						alert(response.msg);
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
	
	$("#recofferlettermasid").change(function(){
		var $recofferlettermasid = $('#recofferlettermasid').val();
		clearForm();
		if($recofferlettermasid !="")
		{
			var url="load_rec_rent.php?item=detailsRecTenant&itemval="+$recofferlettermasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            var i=0;                                           
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
						    $('#cc').html('');
                                                    var url="load_rec_rent.php?item=loadTransDetFromRecOfferLetter&itemval="+$recofferlettermasid;
						    $('#rentbody').html('');
						    $('#servicechrgbody').html('');						    
                                                    $.getJSON(url,function(data){
                                                	$.each(data.error, function(i,response){
                                                		if(response.s == "Success")
                                                		{									
									////$('#cc').html(response.msg);
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
									
									$('#selectOptionTblSc').show();
									$('#selectOptionTblRent').show();																		
                                                                }
								else
								{
									$('#cc').html(response.msg);
								}
                                                        });
                                                    });
                                                        i++;
                                            });
					}
					else
					{
					       $('#cc').html(response.msg);
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
	function clearForm()
	{
		$('#tenantDetails').html('');
                $('#rentbody').html('');
		$('#servicechrgbody').html('');		
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
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Rectification of Rent</h1>
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
							<th>Premisis</th>
							<th>Term</th>
							<th>Rent Cycle</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$companymasid = $_SESSION['mycompanymasid'];
					$sql = "select b.leasename,b.tradingname,e.shopcode 'shop',g.age as 'term',i.age as 'rentcycle' from
					rec_trans_offerletter a
						inner join rec_tenant b on b.tenantmasid = a.tenantmasid
						inner join mas_shop e on e.shopmasid = b.shopmasid
						inner join mas_age g on g.agemasid = b.agemasidlt
						inner join mas_age i on i.agemasid = b.agemasidrc
					where a.editpermission = '1'
					and a.companymasid=$companymasid and  b.active='1'                                
					union
					select d.leasename,d.tradingname,f.shopcode as 'shop',h.age as 'term',j.age as 'rentcycle' from
					rec_trans_offerletter c
						inner join mas_tenant d on d.tenantmasid = c.tenantmasid
						inner join mas_shop f on f.shopmasid = d.shopmasid
						inner join mas_age h on h.agemasid = d.agemasidlt
						inner join mas_age j on j.agemasid = d.agemasidrc
					where c.editpermission = '1'
					and c.companymasid= $companymasid and  d.active='1'                
					order by leasename asc";
					$result = mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;$tradingname="";
							   while ($row = mysql_fetch_assoc($result))
								   {
									$leasename = $row["leasename"];
									$premisis = $row["shop"];
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
							<th>Premisis</th>
							<th>Term</th>
							<th>Rent Cycle</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table2" width='100%'>
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan=4>
				Alter Rent
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecTenant">
		<td>
			Select Tenant: <font color="red">*</font>
		</td>
		<td>			
			<select id="offerlettermasid" name="offerlettermasid">
				<option value="" selected>--Select Offerletter--</option>
			</select>
			<select id="recofferlettermasid" name="recofferlettermasid">
				<option value="" selected>--Select Offerletter--</option>
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
                     <b>Service Chrage Chart</b>
                </td>
		<td colspan=3>
			<div id="selectOptionTblSc">
				<select id='sctype' name='sctype'>
					<option selected='selected' value=''>Please select Service Chrg Type</option>
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
	<tr id="newTr">
		<td>
			
		</td>
		<td align='center'>
			
		</td>
		<td align='center' colspan=2>
			<button type="button" id="btnSave">Save</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
		</td>
		<td colspan=3>
			<button type="button" id="btnUpdate">Update</button>
		</td>
	</tr>
	</tbody>
</table>

</div>
</div> <!--Main Div-->
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</body>
</html>
