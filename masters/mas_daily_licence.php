<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>DAILY OCCUPATION - LICENSE AGREEMENT</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');		
?>
<style>
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
//    (function($) {
//   $.fn.fixMe = function() {
//      return this.each(function() {
//         var $this = $(this),
//            $t_fixed;
//         function init() {
//            $this.wrap('<div class="dataManipDiv" />');
//          //  $this.wrap('<div class="exampleDiv" />');
//          //  $this.wrap('<div class="menuDiv" />');
//            $t_fixed = $this.clone();
//            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
//            resizeFixed();
//         }
//         function resizeFixed() {
//            $t_fixed.find("th").each(function(index) {
//               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
//            });
//         }
//         function scrollFixed() {
//            var offset = $(this).scrollTop(),
//            tableOffsetTop = $this.offset().top,
//            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
//            if(offset < tableOffsetTop || offset > tableOffsetBottom)
//               $t_fixed.hide();
//            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
//               $t_fixed.show();
//         }
//         $(window).resize(resizeFixed);
//         $(window).scroll(scrollFixed);
//         init();
//      });
//   };
//})(jQuery);

//$(document).ready(function(){
//   $("table").fixMe();
//   $(".up").click(function() {
//      $('html, body').animate({
//      scrollTop: 0
//   }, 2000);
// });
	$("#fromdt").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"dd-mm-yy"
	});
	$("#todt").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"dd-mm-yy"
	});
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"			
	});
	$('#dataManipDiv').hide();
	
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Daily Licence");
		$('#exampleDiv').hide();
		$('#dataManipDiv').show();
		$('#selectLicensee').hide();
		$('#licensenameTR').show();
                $('#saveTr').show();
                $('#updateTr').hide();
		loadBuilding();
		$('input[type=text]').val('');
		$('input[type=select]').val('');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Daily Licence");
		$('#exampleDiv').hide();
		$('#dataManipDiv').show();
		$('#selectLicensee').show();
		$('#licensenameTR').show();
                $('#saveTr').hide();
                $('#updateTr').show();
		loadLicense();
		$('input[type=text]').val('');
		$('input[type=select]').val('');
	});
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	function loadBuilding()
	{
		var url="load_daily_licence.php?item=loadBuilding";					
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
	}
	function loadLicense()
	{
		var url="load_daily_licence.php?item=loadLicense";					
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
					$('#licensemasid').empty();
					$('#licensemasid').append( new Option("-----Select Licensee-----","",true,false) );
					$.each(data.myResult, function(i,response){
						var a = response.licensename+"("+response.licensecode+")";
                                                $('#licensemasid').append( new Option(a,response.licensemasid,true,false) );
					});
				}
				else
				{
					alert(response.s);
				}
			});		
		});
	}
	$('#rtperday').keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#rtperday').blur(function(){
		var a = $('#totaldays').val();
		var b = $(this).val();
		$('#rentamount').val(Math.round(a*b));
	});
	$('#rentamount').keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#rentamount').blur(function(){
		var a = $('#totaldays').val();
		var b = $(this).val();
		$('#rtperday').val(Math.round(b/a));
	});
	$('#fromdt').change(function(){
		calcDateDiff();
	});
	$('#todt').change(function(){
		calcDateDiff();
	});
	function calcDateDiff()
	{
		var startDt = $('#fromdt').datepicker('getDate');
		//if(strDt != 0 )
		//{
			var endDt = $('#todt').datepicker('getDate');
			var diff = endDt - startDt;
			var days = diff / 1000 / 60 / 60 / 24;
			days = days+1
			if( days < 0 )
			{
				//alert(days);
				$('#totaldays').val(0);
				//alert("'From Date' should  less than 'To Date'");
			}
			else
			{
				//alert(days);
				$('#totaldays').val(Math.round(days));
			}		
			var a = $('#totaldays').val();
			var b = $('#rtperday').val();
			$('#rentamount').val(Math.round(a*b));
		//}
	}
	$('#btnSave').click(function(){
            
		if($("#licensename").val() == "")
		{
			alert("Licensee Details Mandatory.");
			//$("input[id='licensee']").focus() ;
			$('#licensename')[0].focus() ;
			return false;
		}
		if($("#buildingmasid option:selected").val() == "")
		{
			alert("Building Details Mandatory.");
			$('#buildingmasid')[0].focus() ; return false;
		}
		if($("#area").val() == "")
		{
			alert("Space Details Mandatory.");
			$('#area')[0].focus();return false;
		}
		if($("#fromdt").val() == "")
		{
			alert("From Date Mandatory.");
			$('#fromdt')[0].focus();return false;
		}
		if($("#todt").val() == "")
		{
			alert("To Date Mandatory.");
			$("input[id='todt']").focus() ;return false;
		}
		if( ($("#rentamount").val() == "") || ($("#rentamount").val() == 0))
		{
			alert("Rent Details Mandatory.");
			$("input[id='rentamount']").focus() ;return false;
		}		
		if($("#totaldays").val() <= 0)
		{
			alert("No of Days Can't be Zero.");
			$("input[id='fromdt']").focus() ;return false;
		}
		var r = confirm("Can you confirm this?");
		if(r==true)
		{
			var url="save_daily_licence.php?action=Save";
			var dataToBeSent = $("form").serialize();		
			$.getJSON(url,dataToBeSent, function(data){				
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						
						$('#cc').html(response.msg);
					}
					else
					{
						$('#cc').html(response.s);
					}
				});
			});
		}
		$('input[type=text]').val('');
		$('#buildingmasid').val('');
	});
        $('#btnSaveAndProceed').click(function(){
		if($("#licensename").val() == "")
		{
			alert("Licensee Details Mandatory.");
			//$("input[id='licensee']").focus() ;
			$('#licensename')[0].focus() ;
			return false;
		}
		if($("#buildingmasid option:selected").val() == "")
		{
			alert("Building Details Mandatory.");
			$('#buildingmasid')[0].focus() ; return false;
		}
		if($("#area").val() == "")
		{
			alert("Space Details Mandatory.");
			$('#area')[0].focus();return false;
		}
		if($("#fromdt").val() == "")
		{
			alert("From Date Mandatory.");
			$('#fromdt')[0].focus();return false;
		}
		if($("#todt").val() == "")
		{
			alert("To Date Mandatory.");
			$("input[id='todt']").focus() ;return false;
		}
		if( ($("#rentamount").val() == "") || ($("#rentamount").val() == 0))
		{
			alert("Rent Details Mandatory.");
			$("input[id='rentamount']").focus() ;return false;
		}		
		if($("#totaldays").val() <= 0)
		{
			alert("No of Days Can't be Zero.");
			$("input[id='fromdt']").focus() ;return false;
		}
		var r = confirm("Can you confirm this?");
		if(r==true)
		{
			var url="save_daily_licence.php?action=Save";
			var dataToBeSent = $("form").serialize();		
			$.getJSON(url,dataToBeSent, function(data){				
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						
						$('#cc').html(response.msg);
                        var a = confirm("Daily license saved successfully\nWould you like to view this document? ");
                   if (a== true)
                   {
                       //var name=parent.top.$('[id^="the_iframe"]').attr('name');
                      
                     parent.top.$('div[name=masterdivtest]').html("<iframe  src='reports-pms/rpt_daily_licence.php?action=new' id='the_iframe' scrolling='yes' width='100%'></iframe>");   


                   }else{
                       return;
                   }
					}
					else
					{
						$('#cc').html(response.s);
					}
				});
			});
		}
		$('input[type=text]').val('');
		$('#buildingmasid').val('');
	});
	$('#btnUpdate').click(function(){
		if($("#licensemasid").val() == "")
		{
			alert("Please Select License.");			
			$('#licensemasid')[0].focus() ;
			return false;
		}
		if($("#licensename").val() == "")
		{
			alert("Licensee Details Mandatory.");
			//$("input[id='licensee']").focus() ;
			$('#licensename')[0].focus() ;
			return false;
		}
		if($("#buildingmasid option:selected").val() == "")
		{
			alert("Building Details Mandatory.");
			$('#buildingmasid')[0].focus() ; return false;
		}
		if($("#area").val() == "")
		{
			alert("Space Details Mandatory.");
			$('#area')[0].focus();return false;
		}
		if($("#fromdt").val() == "")
		{
			alert("From Date Mandatory.");
			$('#fromdt')[0].focus();return false;
		}
		if($("#todt").val() == "")
		{
			alert("To Date Mandatory.");
			$("input[id='todt']").focus() ;return false;
		}
		if( ($("#rentamount").val() == "") || ($("#rentamount").val() == 0))
		{
			alert("Rent Details Mandatory.");
			$("input[id='rentamount']").focus() ;return false;
		}
		if($("#totaldays").val() <= 0)
		{
			alert("No of Days Can't be Zero.");
			$("input[id='fromdt']").focus() ;return false;
		}
		var r = confirm("Can you confirm this?");
		if(r==true)
		{
			var url="save_daily_licence.php?action=Update";
			var dataToBeSent = $("form").serialize();		
			$.getJSON(url,dataToBeSent, function(data){				
				$.each(data.error, function(i,response){					
					if(response.s == "Success")
					{
						
						$('#cc').html(response.msg);
					}
					else
					{
						$('#cc').html(response.s);
					}
				});
			});
		}
		$('input[type=text]').val('');
		$('#buildingmasid').val('');
		loadLicense();
	});
	$('#licensemasid').change(function(){
		$('input[type=text]').val('');
		$('input[type=select]').val('');
		loadBuilding();
		var id = $('#licensemasid option:selected').val();		
		if(id !="")
		{
			loadLicenseDetails(id);
		}
	});
	function loadLicenseDetails(val)
	{		
		var url="load_daily_licence.php?item=loadLicenseDetails&itemval="+val;			
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
					$.each(data.myResult, function(i,response){
					    $('#licensename').val(response.licensename);
					    $('#poboxno').val(response.poboxno);
					    $('#pincode').val(response.pincode);
					    $('#city').val(response.city);
					    $('#phoneno').val(response.phoneno);
					    $('#emailid').val(response.emailid);
					    $('#buildingmasid').val(response.buildingmasid);
					    $('#area').val(response.area);
					    $('#fromdt').val(response.fromdt);
					    $('#todt').val(response.todt);
					    $('#totaldays').val(response.totaldays);
					    $('#rtperday').val(response.rtperday);
					    $('#rentamount').val(response.rentamount);
					    $('#servicechrgamount').val(response.servicechrgamount);
					    $('#depositamount').val(response.depositamount);
					    
					});
				}
				else
				{
					alert(response.s);
					$('#cc').html(response.s);
				}
			});		
		});
	}
});
</script>
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>DAILY OCCUPATION - LICENCE AGREEMENT</h1>
<div id="menuDiv" width="100%" align="right">
<table>
		<tr>
			<td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
			<td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>
			<td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
		</tr>
	</table>
</div>
<div id="exampleDiv" width="100%">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>							
                                                        <th>Licensee</th>
							<th>Building</th>
							<th>From</th>
							<th>To</th>
							<th>Status</th>
							
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					 $sql = "select a.*,b.buildingname, "
						. " DATE_FORMAT( fromdt, \"%d-%m-%Y\" ) as \"fromdt\" ,\n"
						. " DATE_FORMAT( todt, \"%d-%m-%Y\" ) as \"todt\"\n"
						. " FROM mas_daily_license a"
						. " inner join mas_building b on b.buildingmasid = a.buildingmasid ";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							while ($row = mysql_fetch_assoc($result))
							{																
								$editpermission = $row["editpermission"];
								if($editpermission == 1)
								{
									$editpermission = "Open";
								}
								else
								{
									$editpermission = "Closed";
								}
								$tr =  "<tr>
								<td class='center'>".$i++."</td>
								<td>".$row["licensename"]." (".$row["licensecode"].")</td>
								<td>".$row["buildingname"]."</td>
								<td>".$row["fromdt"]."</td>
								<td>".$row["todt"]."</td>								
								<td>".$editpermission."</td>
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
                                                        <th>Licensee</th>
							<th>Building</th>
							<th>From</th>
							<th>To</th>
							<th>Status</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table2" width="55%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Select Licensee 
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id='selectLicensee'>
		<td>
			Select Licensee <font color='red'>*</font>
		</td>
		<td>
			<select id="licensemasid" name="licensemasid">
				<option value="" selected>----Select Licensee----</option>
			</select>
		</td>
	</tr>
	<tr id='licensenameTR'>
		<td>
			Licensee <font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='licensename' id='licensename' />
		</td>
	</tr>
	<tr>
		<td>
			Po.Box No <font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='poboxno' id='poboxno' />
		</td>
	</tr>
	<tr>
		<td>
			Postal Code <font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='pincode' id='pincode' />
		</td>
	</tr>
	<tr>
		<td>
			City <font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='city' id='city' />
		</td>
	</tr>
	<tr>
		<td>
			Landline / Mobile Phone
		</td>
		<td>
			<input type='text' name='phoneno' id='phoneno' />
		</td>
	</tr>
	<tr>
		<td>
			E-Mail Id
		</td>
		<td>
			<input type='text' name='emailid' id='emailid' />
		</td>
	</tr>
	<tr>
		<td>
			Select Building <font color='red'>*</font>
		</td>
		<td>
			<select id="buildingmasid" name="buildingmasid">
				<option value="" selected>----Select Building----</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Meeting Area<font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='area' id='area' />
		</td>
	</tr>
	<tr>
		<td>
			From Date <font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='fromdt' id='fromdt' />
		</td>
	</tr>
	<tr>
		<td>
			To Date <font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='todt' id='todt' />
		</td>
	</tr>
	<tr>
		<td>
			No. of Days 
		</td>
		<td>
			<input type='text' name='totaldays' id='totaldays' readonly />
		</td>
	</tr>
	<tr>
		<td>
			Rt / Day 
		</td>
		<td>
			<input type='text' name='rtperday' id='rtperday'  /> KSHS.
		</td>
	</tr>
	<tr>
		<td>
			Rent Amount 
		</td>
		<td>
			<input type='text' name='rentamount' id='rentamount'  /> KSHS.
		</td>
	</tr>
	<tr>
		<td>
			Service Chrg
		</td>
		<td>
			<input type='text' name='servicechrgamount' id='servicechrgamount'  /> KSHS.
		</td>
	</tr>
	<tr>
		<td>
			Deposit Amount
		</td>
		<td>
			<input type='text' name='depositamount' id='depositamount'  /> KSHS.
		</td>
	</tr>
	<tr id="saveTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Save</button>
                        <button type="button" id="btnSaveAndProceed">Save And Proceed</button>
		</td>
             
	</tr>
	<tr id="updateTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update</button>
		</td>
	</tr>
	</tbody>
    </table>
</div>
</div><!--Main Div-->
<div id="divContent">
	
</div>

</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
&nbsp;&nbsp;<font color=red><label id="st"></label></font>
</body>
</html>
