<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Enquiry Master</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadBuilding()
    {
        $sql = "select buildingname, buildingmasid from mas_building";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }
    
    
?>
<script type="text/javascript" language="javascript">
$(document).focus();
$(document).keydown(function(e){
    if(e.ctrlKey || e.metaKey)
    {
        if (String.fromCharCode(e.charCode||e.keyCode)=="1")	
        {
	    $("#btnNew").click();
            return false;
        }
	if (String.fromCharCode(e.charCode||e.keyCode)=="2")	
        {
	    $("#btnEdit").click();
            return false;
        }
	if (String.fromCharCode(e.charCode||e.keyCode)=="3")	
        {
	    $("#btnView").click();
            return false;
        }
    }
});
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
    
    
    $('#enqflpdiv').hide();        
    $("#dataManipDiv").hide();
    oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		//"sPaginationType": "full_numbers"			
	});
    $(".jquerydt").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
     
     
     
    $('#btnNew').click(function(){
	$('#enqflpdiv').hide();    
        $('#tblheader').hide();  
       // $("#tblheader").css('background-color', '#fc9');
	$("#enqtblheader").css('background-color', '#fc9');
	//$("#tblheader").text("Create New Enquiry");
	$("#enqtblheader").text("Follow-up Entry");
        $("#exampleDiv").hide();
	$("#dataManipDiv").show();
	$("#editTr").hide()
	$("#newTr").show();
	$("#enquiryreceivedon")[0].focus();
	$('input[type=text]').val('');
	$('input[type=select]').val('');
	$("#cc").html("");

    });
 
  
    $('#btnSave').click(function(){
	if(jQuery.trim($("#enquiryreceivedon").val()) == "")
	{
		alert("Please Enter Date");
		$("#enquiryreceivedon").focus();return false;
	}
	if(jQuery.trim($("#companyname").val()) == "")
	{
		alert("Please select  company name");
		$("#companyname").focus();return false;
	}
	//if(jQuery.trim($("#orgtype").val()) == "")
	//{
	//	alert("Please select company type");
	//	$("#orgtype").focus();return false;
	//}
	if(jQuery.trim($("#nob").val()) == "")
	{
		alert("Please enter nature of business");
		$("#nob").focus();return false;
	}
	//if(jQuery.trim($("#dirname").val()) == "")
	//{
	//	alert("Please enter director name");
	//	$("#dirname").focus();return false;
	//}
	//if(jQuery.trim($("#cpname").val()) == "")
	//{
	//	alert("Please enter contact person name");
	//	$("#cpname").focus();return false;
	//}
	//if(jQuery.trim($("#address").val()) == "")
	//{
	//	alert("Please enter address");
	//	$("#address").focus();return false;
	//}
	//if(jQuery.trim($("#city").val()) == "")
	//{
	//	alert("Please enter city");
	//	$("#city").focus();return false;
	//}
	//if(jQuery.trim($("#poboxno").val()) == "")
	//{
	//	alert("Please enter poboxno");
	//	$("#poboxno").focus();return false;
	//}
	//if(jQuery.trim($("#postalcode").val()) == "")
	//{
	//	alert("Please enter postalcode");
	//	$("#postalcode").focus();return false;
	//}
	//if(jQuery.trim($("#country").val()) == "")
	//{
	//	alert("Please enter country");
	//	$("#country").focus();return false;
	//}
	if(jQuery.trim($("#buildingmasid").val()) == "")
	{
		alert("Please select building");
		$("#buildingmasid").focus();return false;
	}
	//if(jQuery.trim($("#floorname").val()) == "")
	//{
	//	alert("Please select floorname");
	//	$("#floorname").focus();return false;
	//}
	var a = confirm("Can you confirm this ?");
	if (a== true)
	{
		var url="save_enquiry.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{
					$('input[type=text]').val('');
					$('input[type=select]').val('');
					//$("#active").removeAttr('checked')
					//$("#cc").html(response.msg);
                                         alert(response.msg);
                                       if(response.msg=="Data Saved Successfully"){  
                                       // alert( "1"+parent.top.$('div[name=masterdivtest]').html());
                                       parent.top.$('div[name=masterdivtest]').html("<iframe name='enquiryiframe' src='masters/mas_tenant_updated.php?action=new' id='the_iframe2' scrolling='yes' width='100%'></iframe>"); 
                                      }else{
                                          
                                          return
                                      }
                                        // alert("2"+ parent.top.$('div[name=masterdivtest]').html());

				}
				else
				{
					//alert(response.s);
					$("#cc").html(response.msg);
                                        alert(response.msg);
                                        // var nextButton = "<button type='button' id='btnNext'>Tenant Master</button>"; 
                                       // $("#cc").html(nextButton);
					
				}				
			});
		});
	}
	});
//      $('#btnNext').click(function(){
//	
//     //re("mas_tenant.php");
//     header("location:mas_tenant.php");
//    });
	$('[id^="btnEdit"]').live('click', function() {
		$('#enqflpdiv').show();        
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#enqtblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Enquiry");
		$("#enqtblheader").text("Follow-up Entry");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectShop").show();
		$("#blockmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');
		$('input[type=select]').val('');
		$("#enquiryreceivedon").focus();
                var $a = $(this).attr('name');
                var url="load_enquiry.php?action=details&enquirymasid="+$(this).attr('val');
                var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
                    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
				$.each(data.myResult, function(i,response){
					$('#companyname').val(response.companyname)					
					$orgtype = response.orgtype
					$("input:radio").each(function() {
						if($(this).val() == $orgtype)
						{
							$(this).attr('checked','checked');
						}
					});
					
					$('#enquiryreceivedon').val(response.enquiryreceivedon);
					$('#nob').val(response.nob);
					$('#dirname').val(response.dirname);
					$('#cpname').val(response.cpname);
					$('#address').val(response.address);
					$('#city').val(response.city);
					$('#poboxno').val(response.poboxno);
					$('#postalcode').val(response.postalcode);
					$('#country').val(response.country);
					$('#telephone').val(response.telephone);
					$('#mobile').val(response.mobile);
					$('#emailid').val(response.emailid);
					$('#buildingmasid').val(response.buildingmasid);
					$('#floorname').val(response.floorname);
					$('#area').val(response.area);
					$('#period').val(response.period);
					$('#referedby').val(response.referedby);
					$('#remarks').val(response.remarks);
					$act = response.active;
					if($act == "1")
					{
						$("#active").attr('checked','checked');
					}
					else
					{
						$("#active").removeAttr('checked')
					}
					$("#hidenquirymasid").val($a);
				});
                        }				
                        else
                        {
                            //alert(response.msg);
			    $("#cc").html(response.msg);
                        }                         
                    });
		    //call follow up details
		    flpdetails();
		
		});           
        })
	function flpdetails()
	{
		var url="load_enquiry.php?action=flp_details_all&enquirymasid="+$("#hidenquirymasid").val();
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
			    $.each(data.error, function(i,response){				
				if(response.s == "Success")
				{
					$('#enqflptbl_body').html(response.msg);
				}
				else
				{
					$('#cc').html(response.s);
				}
			    });
			});
	}
	$('#btnUpdate').click(function(){
		if(jQuery.trim($("#enquiryreceivedon").val()) == "")
		{
			alert("Please Enter Date");
			$("#enquiryreceivedon").focus();return false;
		}
		if(jQuery.trim($("#companyname").val()) == "")
		{
			alert("Please select  company name");
			$("#companyname").focus();return false;
		}
		if(jQuery.trim($("#nob").val()) == "")
		{
			alert("Please Enter Nature of Business");
			$("#nob").focus();return false;
		}
		if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please select building");
			$("#buildingmasid").focus();return false;
		}
		var a = confirm("Can you confirm this ?");
		if (a== true)
		{
			var url="save_enquiry.php?action=Update";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');
						$('input[type=select]').val('');
						$('input[type=textarea]').val('');
						//$("#active").removeAttr('checked')
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
        
//        	$('#btnNext').click(function(){
//                 window.location.href = "masters/mas_tenant.php";
//	         });
	$('#btnSave_flp').click(function(){
		if(jQuery.trim($("#flpdt1").val()) == "")
		{
			alert("Please enter follow up date");
			$("#flpdt1").focus();return false;
		}
		if(jQuery.trim($("#flpremarks").val()) == "")
		{
			alert("Please enter follow up remakrs");
			$("#flpremarks").focus();return false;
		}
		var a = confirm("Can you confirm this ?");
		if (a== true)
		{			
			if($(this).text() =="Save"){
				var url="save_enquiry_flp.php?action=Save_flp";
			}
			else{
				var $b = $("#hidenquirydetmasid").val();			
				var url="save_enquiry_flp.php?action=Update_flp&enquirydetmasid="+$b;				
			}			
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{						
						$("#cc").html(response.msg);
						$('#flpdt1').val("");
						$('#flpremarks').val("");
						$('#flpdt2').val("");
						$('#btnSave_flp').text("Save");
						
						flpdetails();
					}
					else
					{
						$("#cc").html(response.msg);
					}
				});
			});
		}
	});
	$('[id^="enquirydetmasid"]').live('click', function() {
		var $a = $(this).attr('val');
		$("#hidenquirydetmasid").val($a);
		var url="load_enquiry.php?action=flp_details&enquirydetmasid="+$a;		
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
		    $.each(data.error, function(i,response){				
			if(response.s == "Success")
			{				
				$.each(data.myResult, function(i,response){
					$('#flpdt1').val(response.flpdt1);
					$('#flpremarks').val(response.flpremarks);
					$('#flpdt2').val(response.flpdt2);
					$('#flpstatus').val(response.flpstatus);
					$('#flpdt1').focus();
					$('#btnSave_flp').text("Update");
				});				
			}
			else
			{
				$('#cc').html(response.s);
			}
		    });
		});
		return false;
	});	
	$('#btnView').click(function(){
		$('form').submit();
	});
    
//    //ENQUIRY FOLLOW UP
//        $("#append").click( function() {
//		$(this).hide();
//		$(".inc").prepend('<div class="controls"><br>'+
//				  '<label>Date:</label><input type="text" class="jquerydt" id="flpdt" name="flpdt"><br>'+
//				  '<label>Remarks:</label><textarea id="flptxt" name="flptxt" cols="55" rows="5"></textarea>'+
//				  '&nbsp;<a href="#" class="save_this btn btn-safe">save</a>&nbsp;&nbsp;<a href="#" class="remove_this btn btn-danger">cancel</a><br><br></div>');		
//		return false;
//	});
// 
//	jQuery('.remove_this').live('click', function() {
//		jQuery(this).parent().remove();
//		$("#append").show();
//		return false;
//	});
 
});
function showKeyCode(e) {
	//alert( "keyCode for the key pressed: " + e.keyCode + "\n" );
    if(e.keyCode == 112)
    {
	event.preventDefault();
	$("button:first").trigger('click');
    } else if(e.keyCode == 113)
    {	
	event.preventDefault();
	$("button:second").trigger('click');
    }
}
</script>
        
  <script type="text/javascript" language="javascript">
   // $(document).ready(function() {   
        
	(function($) {
   $.fn.fixMe = function() {
      return this.each(function() {
         var $this = $(this),
            $t_fixed;
         function init() {
            $this.wrap('<div class="dataManipDiv" />');
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

$(document).ready(function(){
   $("table").fixMe();
   $(".up").click(function() {
      $('html, body').animate({
      scrollTop: 0
   }, 2000);
 });
});
   //  });
</script>   
        
 <style>
            
body{
  font:1.2em normal Arial,sans-serif;
  color:#34495E;
}

h1{
  text-align:center;
  text-transform:uppercase;
  letter-spacing:-2px;
  font-size:2.5em;
  margin:20px 0;
}

.dataManipDiv{
  width:99%;
  margin:auto;
}

#tableusr{
  border-collapse:collapse;
  width:99%;
}

#tableusr{
  border:2px solid #1ABC9C;
}

#tableusr thead{
  background:#1ABC9C;
}

.purple{
  border:2px solid #9B59B6;
}

.purple thead{
  background:#9B59B6;
}

thead{
  color:white;
}

th,td{
  text-align:center;
  padding:5px 0;
}

tbody tr:nth-child(even){
  background:#ECF0F1;
}

tbody tr:hover{
background:#BDC3C7;
  color:#FFFFFF;
}

.fixed{
  top:0;
  position:fixed;
  width:auto;
  display:none;
  border:none;
}

.scrollMore{
  margin-top:600px;
}

.up{
  cursor:pointer;
}     
</style>      
        

        
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="GET">
<!--<div id="container">-->
<center><h2>Enquiry Master</h2></center>
<div id="menuDiv" width="100%" align="right">
<table>
<tr>
        <td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>        
        <td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
</tr>
</table>
</div>
<br>
<label id="cc"></label>
<div id="exampleDiv" width="100%">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
    <thead>
            <tr>
                    <th>Index</th>							                    
                    <th>Building</th>
		    <th>For</th>		    
		    <th>Sqrft</th>
		    <th>Date</th>
		    <th>Edit</th>
            </tr>
    </thead>
    <tbody id="tbodyContent">
	<?php
	$companymasid  = $_SESSION['mycompanymasid'];
	$companyname = $_SESSION['mycompany'];
	$sql = "SELECT a.enquirymasid,a.companymasid,date_format(a.enquiryreceivedon,'%d-%m-%Y')as enquiryreceivedon,a.companyname,a.orgtype,a.nob,a.dirname,a.cpname,a.address,a.city,
		a.poboxno,a.postalcode,a.country,a.telephone,a.mobile,a.emailid,a.buildingmasid,a.floorname,a.area,a.period,a.referedby,
		a.remarks,a.createdby,a.createddatetime,a.modifiedby,a.modifieddatetime,a.active,b.buildingname 
		from mas_enquiry a 
		inner join mas_building b on b.buildingmasid = a.buildingmasid
		order by a.createddatetime desc;";				
	$result=mysql_query($sql);
	if($result != null) // if $result <> false
	{
		if (mysql_num_rows($result) > 0)
		{
			$i=1;
			   while ($row = mysql_fetch_assoc($result))
				{
				     $enquirymasid = $row["enquirymasid"];
				     $companyname = $row["companyname"];
				     $companyname .= " - " .$row["nob"];
				     $rcvdondt = $row["enquiryreceivedon"];
				     $sqrft  = $row["area"];
				     $cpname = $row["cpname"];
				     $nob = $row["nob"];
				     $buildingname = $row["buildingname"];				     
				     $floorname = $row["floorname"];
				     $period = $row["period"];
				     $landline = $row["telephone"];
				     $mobile = $row["mobile"];
				     $emailid = $row["emailid"];
				     $active = $row["active"];
					if($active == 1)
					{
						//$active = "<a href='mas_enquiry.php?id='".$enquirymasid."'>edit</a>";
						$active ="<button type='button' id=btnEdit$i name='".$enquirymasid."'  val='".$enquirymasid."'>Edit</button>";
						$active .="<input type='hidden' id=btnEditText$i name='".$enquirymasid."'  value='".$enquirymasid."'>";
						//$active = "<input type='submit' id='btnSumbit' name='btnSubmit' value='Edit' />";
					}
					else
					{
						$active = "closed";
						$active ="<button type='button' id=btnEdit$i name='".$enquirymasid."'  val='".$enquirymasid."'>Closed</button>";
						$active .="<input type='hidden' id=btnEditText$i name='".$enquirymasid."'  value='".$enquirymasid."'>";
					}
				     //$tr =  "<tr>
				     //<td class='center'>".$i++."</td>
				     //<td>".$companyname."</td>
				     //<td>".$cpname."</td>
				     //<td>".$nob."</td>
				     //<td>".$buildingname."</td>				     
				     //<td>".$floorname."</td>
				     //<td>".$period."</td>
				     //<td>".$landline."</td>
				     //<td>".$mobile."</td>
				     //<td>".$emailid."</td>
				     //<td>".$active."</td>
				     //";
				     $tr =  "<tr>
				     <td class='center'>".$i++."</td>
				     <td>".$buildingname."</td>				     
				     <td>".$companyname."</td>
				     <td>".$sqrft."</td>
				     <td>".$rcvdondt."</td>
				     <td>".$active."</td>";
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
                    <th>For</th>		    
		    <th>Sqrft</th>
		    <th>Date</th>
		    <th>Edit</th>
            </tr>
    </tfoot>
</table>
</div>
<div id="enqflpdiv" align='right'style='position:absolute;left:650px;width:100%;top:130px;'>
	<table id='enqflptbl' class='table2' width='40%' align='left'>
	<thead>
		<tr><th colspan='2' id="enqtblheader"></th>Follow-up</th></tr>
	</thead>	
		<tr>
			<td>
				<!--<div class="control-group">				
				<div class="inc">
				<div class="controls">
					<br>
					<button class="btn btn-info" type="submit" id="append" name="append">Add</button>
					<br>					
				</div>
				</div>
				</div>-->
				Follow-up Date:
			</td>
			<td><input type='text' id='flpdt1' name='flpdt1' class='jquerydt'/></td>
		</tr>
		<tr>
			<td>Remarks:</td>
			<td><textarea id='flpremarks' name='flpremarks' cols=55 rows=5></textarea></td>
		</tr>
		<tr>
			<td>Next follow-up date:</td>
			<td><input type='text' id='flpdt2' name='flpdt2' class='jquerydt'/></td>
		</tr>
		<tr>
			<td>Follow-up Status:</td>
			<td>
				<select id='flpstatus' name='flpstatus'>
					<option value='1' selected='selected'>Open</option>
					<option value='0'>Closed</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align='right'><button type="button" id="btnSave_flp">Save</button></td>
		</tr>
		<tr>
			<td colspan="2" align='center'><b><u>Details:</u></b></td>
		</tr>
	<tbody id='enqflptbl_body'>
	</tbody>
	</table>
	</br>			
</div>
<div id="details">
 <style>
   #dataManipDiv { width: 100%;height: 500px;}
   #usertbl { width: 100%;height: 100%; }
</style>
<div id="dataManipDiv">
<table id="usertbl" class="table2" width="100%">
   <thead  colspan='2' id="tblheader"> Create New Enquiry
 
    </thead>	
    <tbody>
    <tr>
	 <td>
                Enquiry Received ON <font color="red">*</font>
        </td>
        <td>
            <input type='text' id='enquiryreceivedon' name='enquiryreceivedon' class='jquerydt'/>
        </td>
    </tr>
    <tr>
	 <td>
                Company Name <font color="red">*</font>
        </td>
        <td>
            <input type='text' id='companyname' name='companyname' />
        </td>
    </tr>
    <tr>
	 <td>
                Company Type <font color="red">*</font>
        </td>
        <td>            
		<input type="radio" name="orgtype" id="orgtype" value="Ltd" /> Ltd 
		<input type="radio" name="orgtype" id="orgtype" value="Individual" /> Individual
		<input type="radio" name="orgtype" id="orgtype" value="Patrnership" /> Partnership
		<br />
		<input type="radio" name="orgtype" id="orgtype" value="Bank" /> Bank
		<input type="radio" name="orgtype" id="orgtype" value="College" /> College
		<input type="radio" name="orgtype" id="orgtype" value="Non Profit Org" /> Non Profit Org
        </td>
    </tr>
     <tr>
	 <td>
                Nature Of Business <font color="red">*</font>
        </td>
        <td>
            <input type='text' id='nob' name='nob' />
        </td>
    </tr>
    <tr>
	 <td>
                Director Name <font color="red">*</font>
        </td>
        <td>
            <input type='text' id='dirname' name='dirname' />
        </td>
    </tr>
    <tr>
	 <td>
                Contact Person Name <font color="red">*</font>
        </td>
        <td>
            <input type='text' id='cpname' name='cpname' />
        </td>
    </tr>
    <tr>
	 <td>
                Address <font color="red">*</font>
        </td>
        <td>
            <input type="text" id='address' name='address' cols=55 rows=5 />
        </td>
    </tr>
     <tr>
	 <td>
                City<font color="red">*</font>
        </td>
        <td>
            <input type='text' id='city' name='city' />
        </td>
    </tr>
      <tr>
	 <td>
                P.O.Box No<font color="red">*</font>
        </td>
        <td>
            <input type='text' id='poboxno' name='poboxno' />
        </td>
    </tr>
    <tr>
	 <td>
                Postal Code<font color="red">*</font>
        </td>
        <td>
            <input type='text' id='postalcode' name='postalcode' />
        </td>
    </tr>
     <tr>
	 <td>
                Country<font color="red">*</font>
        </td>
        <td>
            <input type='text' id='country' name='country' value='KENYA'/>
        </td>
    </tr>
      <tr>
	 <td>
                Land Line
        </td>
        <td>
            <input type='text' id='telephone' name='telephone' />
        </td>
    </tr>
      <tr>
	 <td>
                Mobile
        </td>
        <td>
            <input type='text' id='mobile' name='mobile' />
        </td>
    </tr>
       <tr>
	 <td>
                Email Id
        </td>
        <td>
            <input type='text' id='emailid' name='emailid' />
        </td>
    </tr>
     <tr id="selectBuilding">
        <td>
                Select Building <font color="red">*</font>
        </td>
        <td>
            <select id="buildingmasid" name="buildingmasid">
                    <option value="" selected>----Select Building----</option>
                    <?php loadBuilding(); ?>
            </select>
        </td>
     </tr>
    <tr>
	 <td>
                Floor <font color="red">*</font>
        </td>
        <td>
            <input type='text' id='floorname' name='floorname' />
        </td>
    </tr>
    <tr>
	 <td>
                Size Required (Sq.Ft)
        </td>
        <td>
            <input type='text' id='area' name='area' />
        </td>
    </tr>
    <tr>
	 <td>
                Period 
        </td>
        <td>
            <input type='text' id='period' name='period' />
        </td>
    </tr>
    <tr>
	 <td>
                Referred By
        </td>
        <td>
            <input type='text' id='referedby' name='referedby' />
        </td>
    </tr>
    <tr>
	 <td>
                Remarks 
        </td>
        <td>
            <textarea id='remarks' name='remarks' cols='75' rows='10'></textarea>
        </td>
    </tr>
    <tr>
	<td>
		Active
	</td>
	<td>
		<input type="checkbox" id="active" name="active" checked>
		<input type="hidden" id="hidenquirymasid" name="hidenquirymasid" value='' />
		<input type="hidden" id="hidenquirydetmasid" name="hidenquirydetmasid" value='' />
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
<!--</div> Container Div-->
</form>
</body>
</html>
    