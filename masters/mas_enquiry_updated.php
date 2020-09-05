<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Enquiry Master</title>
<!--        <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">-->
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="../shopstable.css">
        <!--<link rel="stylesheet" type="text/css" href="../css/msdropdown/dd.css" />-->
        <!--<script src="../js/msdropdown/jquery.dd.min.js"></script>-->
        <!--<script src="../js/msdropdown/jquery.dd.js"></script>-->
        <!--<link rel="stylesheet" type="text/css" href="../css/msdropdown/flags.css" />-->
     
      
        <!--<script src="../bootbox.js"></script>-->
        <script src="../js/jquery-2.1.4.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>   
        
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    
    include('../MasterRef_Folder.php');
    $companymasid  = $_SESSION['mycompanymasid'];
    $companyname = $_SESSION['mycompany'];
    echo $companymasid;
    echo "<br>";
    echo $companyname;
    function loadBuilding($companymasid)
    {
        $sql = "select buildingname, buildingmasid from mas_building where companymasid=$companymasid";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }
    function loadTenantType()
		{
			$sql = "select tenanttype, tenanttypemasid from mas_tenant_type";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['tenanttypemasid'].">".$row['tenanttype']."</option>");		
				}
			}
		}
		function loadAgeMaster()
		{
			$sql = "select age, agemasid from mas_age where active =1 and age not like 'Per%' and age not like 'per%'";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['agemasid'].">".$row['age']."</option>");		
				}
			}
		}

                
		function loadShoptype()
		{
			$sql = "select shoptype, shoptypemasid from mas_shoptype where active =1 order by shoptype asc";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['shoptypemasid'].">".$row['shoptype']."</option>");		
				}
			}
		}
                
		function loadOrgtype()
		{
			$sql = "select orgtype, orgtypemasid from mas_orgtype where active =1 order by orgtype asc";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['orgtypemasid'].">".$row['orgtype']."</option>");		
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
    
    
    $('#enqflpdiv').hide();        
    $("#dataManipDiv").hide();
//    $('#ownerordir').hide();
//    $('#leaseorconame').hide();
    $('.dirorowner').hide();
    $('.coorleasename').hide();
    oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"sPaginationType": "full_numbers"			
	});
    $(".jquerydt").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
     
     
     
    $('#btnNew').click(function(){
        $('#shoploader').hide();
	$('#enqflpdiv').hide();    
        $('#tblheader').hide();  
        $("#tblheader").css('background-color', '#fc9');
	$("#enqtblheader").css('background-color', '#fc9');
	$("#tblheader").text("Create New Enquiry");
	$("#enqtblheader").text("Follow-up Entry");
        $("#exampleDiv").hide();
	$("#dataManipDiv").show();
        $("#dataManipDiv").css('{ width: 100%;height: 500px;}');
        $("#usertbl").css('{ width: 100%;height: 100%; }');
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
	if(jQuery.trim($("#orgtypemasid").val()) == "")
	{
		alert("Please select company type");
		$("#orgtypemasid").focus();return false;
	}
	if(jQuery.trim($("#nob").val()) == "")
	{
		alert("Please enter nature of business");
		$("#nob").focus();return false;
	}
	
	if(jQuery.trim($("#buildingmasid").val()) == "")
	{
		alert("Please select building");
		$("#buildingmasid").focus();return false;
	}
        if(jQuery.trim($("#pin").val()) == "")
	{
		alert("Please enter PIN number");
		$("#pin").focus();return false;
	}if((!jQuery.trim($("#pin").val()) == "")&&($("#pin").val().length != 11))
	{
		alert("Please enter the correct 11 digit PIN number");
		$("#pin").focus();return false;
	}
	var a = confirm("Please Confirm?");
	if (a== true)
	{
		var url="save_enquiry_updated.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{
					$('input[type=text]').val('');
					$('input[type=select]').val('');
					//$("#active").removeAttr('checked')
					
                                        // alert(response.msg);
                                       if(response.msg=="Data Saved Successfully"){  
                                        	
                                        $('form').submit();
                                                
                                        }else{
                                          
                                          return
                                      }
                                
				}
				else
				{

					$("#cc").html(response.msg);
                    alert(response.msg);
					
				}				
			});
		});
	}
	
   
    });
     $('[id^="btnEmail"]').live('click', function() {
      //alert($('#emailing').val());  
      //alert($('#contacting').text());  
      var email =$(this).attr('val');
      
     // var contact =$(this).val('val');
     
       var a = confirm("Would you like to email contact on "+email+"?");
        if (a== true)
        {
          var emailid= email;
          //var contact=$('#contacting').text();  
          parent.top.$('div[name=masterdivtest]').html("<iframe name='communicationiframe' src='email/mailer_template.php?action=contacting&emailid="+emailid+" id='the_iframe2' scrolling='yes' width='100%'></iframe>");   
        
    
           }else{
            return;
        }
     });
    $('#btnSaveAndProceed').click(function(){
      //alert($('#area').text());
      //alert("woln: "+document.getElementById('#area').innerHTML());
	if(jQuery.trim($("#enquiryreceivedon").val()) == "")
	{
		alert("Please Enter Date");
		$("#enquiryreceivedon").focus();return false;
	}
	if(jQuery.trim($("#companyname").val()) == "")
	{
		alert("Please select company name");
		$("#companyname").focus();return false;
	}
//        if(jQuery.trim($("#tradingname").val()))
//	{
//           
//		alert("Please select trading name");
//		$("#tradingname").focus();return false;
//	}
	if(jQuery.trim($("#orgtypemasid").val()) == "")
	{
		alert("Please select company type");
		$("#orgtypemasid").focus();return false;
	}
	if(jQuery.trim($("#nob").val()) == "")
	{
		alert("Please enter nature of business");
		$("#nob").focus();return false;
	}
	
	if(jQuery.trim($("#buildingmasid").val()) == "")
	{
		alert("Please select building");
		$("#buildingmasid").focus();return false;
	}
        if(jQuery.trim($("#pin").val()) == "")
	{
		alert("Please enter PIN number");
		$("#pin").focus();return false;
	}if((!jQuery.trim($("#pin").val()) == "")&&($("#pin").val().length != 11))
	{
		alert("Please enter the correct 11 digit PIN number");
		$("#pin").focus();return false;
	}
	var a = confirm("Can you confirm this ?");
	if (a== true)
	{
		var url="save_enquiry_updated.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{
					$('input[type=text]').val('');
					$('input[type=select]').val('');
					//$("#active").removeAttr('checked')
					//$("#cc").html(response.msg);
                                       //  alert(response.msg);
                                       if(response.msg=="Data Saved Successfully"){  
//                                        alert( "1"+parent.top.$('div[name=masterdivtest]').html());
//                                       $('.ui-tabs-nav', window.parent.document).hide();
//                                       parent.top.$('div[name=masterdivtest]').html("<iframe name='enquiryiframe' src='masters/mas_tenant_updated.php?action=new' id='the_iframe2' scrolling='yes' width='100%'></iframe>"); 
//                                      
                                  
                                        var a = confirm("Enquiry Created Successfully\nWould You Like to Proceed to Tenant Creation? ");
                                            if (a== true)
                                            {
                                                //var name=parent.top.$('[id^="the_iframe"]').attr('name');
                                                //alert( parent.top.$('div[name=masterdivtest]').html());
                                              parent.top.$('div[name=masterdivtest]').html("<iframe  src='masters/mas_tenant_updated.php?action=new' id='the_iframe2' scrolling='yes' width='100%'></iframe>");   
                                            
  
                                            }else{
                                                return;
                                            }
                          
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

	$('[id^="btnEdit"]').live('click', function() {
		$('#enqflpdiv').show();        
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#enqtblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Enquiry");
		$("#enqtblheader").text("Follow-up Entry");
		$("#exampleDiv").remove();
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
                                        $('#tradingname').val(response.tradingname);
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
 

//From Tenant Master Details

$("#orgtypemasid").change(function(){
    //alert($(this).val());
    //var string = $(this).val().toUpperCase();
//if( string.indexOf('COMPANY') >= 0||string.indexOf('LTD') >= 0){
 $('.coorleasename').show();
 $('.dirorowner').show();
   //$('#dirname2').show(); 
    $('.coorleasename').append("<td>Lease Name</b> <font color='red'>*</font></td><td><input type='text' id='companyname' name='companyname'  placeholder='Enter lease name' /></td>");
    $('.dirorowner').append("<td>Trading Name<font color='red'>*</font></td><td id='dir1'><input type='text' color='blue' id='tradingname' name='tradingname' placeholder='Enter trading name' /></td></tr>");  
if($(this).val()==1){
     $('.dirorowners').show(); 
    $('.dirorowner').replaceWith("<tr class='dirorowner'><td> Director's Name<font color='red'>*</font></td><td id='dir1'><input type='text' color='blue' id='dirname' name='dirname' placeholder='Name of first director' /></td></tr>\n\
<tr class='dirorowners'><td> Second Director's Name<font color='red'>*</font></td><td><input type='text' color='blue' id='dirname2' name='dirname2' placeholder='Name of second director'/></td></tr>");  
    //$('#dir1').append("<tr><td><input type='text' color='blue' id='dirname2' name='dirname2' placeholder='Name of second director'/></td></tr>");
    $('.coorleasename').replaceWith("<tr class='coorleasename'><td>Company Name</b> <font color='red'>*</font></td><td><input type='text' id='companyname' name='companyname'  placeholder='Enter company name' /></td></tr>");
  }else{
    $('.dirorowners').hide(); 
    $('.coorleasename').replaceWith("<tr class='coorleasename'><td>Lease Name</b> <font color='red'>*</font></td><td><input type='text' id='companyname' name='companyname'  placeholder='Enter lease name' /></td></tr>");
    $('.dirorowner').replaceWith("<tr class='dirorowner'><td>Trading Name<font color='red'>*</font></td><td id='dir1'><input type='text' color='blue' id='tradingname' name='tradingname' placeholder='Enter trading name' /></td></tr>");  
  }
    
    
});
//$('[id^="shopmasid"]').live('click', function() {
//
//alert("Google");
//
//});

        $("#salutation").change(function(){
        var $salute = $('#salutation').val();
      
        $('#salutation').focus();
        if($salute=="Other."){
          //$('#salutation').remove();
          var saluteinput="<input type='text' id='salutation' placeholder='Enter other salutation' name='salutation'>";
          $('#salutation').replaceWith(saluteinput);
        } 
        });
        $("#shoptypemasid").change(function(){
        var type = $('#shoptypemasid option:selected').text();
         var types = jQuery.trim(type);
        //alert(types)
        $('#shoptypemasid').focus();
        if(types.indexOf("Others")>= 0){
          //$('#shoptypemasid').remove();
         // var $shoptypeparent=//$('#shoptypemasid').parent();
          var shoptypeinput="<input type='text' id='othershoptypemasid' placeholder='Enter other shop type' name='othershoptypemasid'>";
         
         $('#shoptypemasid').replaceWith(shoptypeinput);
         //$('#shoptypemasid').hide();
         //$('#shoptypes').append(shoptypeinput);
        }
       
          });
//         $("#shoptypemasid").change(function(){
//        var $salute = $('#shoptypemasid').val().toUpperCase();
//      
//        $('#shoptypemasid').focus();
//        
////if( string.indexOf('COMPANY') >= 0||string.indexOf('LTD') >= 0){
//        if($salutestring.indexOf("Other")>= 0){
//          var saluteinput="<td id='shoptypes'><input type='text' id='othershoptypemasid' placeholder='Enter anothe shop type' name='othershoptypemasid'></td>";
//          $('#shoptypemasid').append(saluteinput);
//        }else{
//          $('#shoptypes').remove();  
//            
//        }
//       
//   
//        });

//	$("#tenantmasid").change(function(){
//		clearDynTable();
//		var $tenantmasid = $('#tenantmasid').val();
//		$('#buildingmasid').focus();
//		if($tenantmasid !="")
//		{
//                           var url="load_tenant.php?item=loadBuilding";					
//							$.getJSON(url,function(data){
//								$.each(data.error, function(i,response){
//									if(response.s == "Success")
//									{
//										$('#buildingmasid').empty();
//										//$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
//										$.each(data.myResult, function(i,response){
//											$('#buildingmasid').append( new Option(response.buildingname,response.buildingmasid,true,false) );											
//										});
//										$('#buildingmasid').attr('readonly');
//									}
//									else
//									{
//										alert(response.s);
//									}
//								});		
//							});
//                         var url="load_tenant.php?item=loadTenantBlock&itemval="+$tenantmasid;
//                                                        $.getJSON(url,function(data){
//                                                                $.each(data.error, function(i,response){
//                                                                        if(response.s == "Success")
//                                                                        {
//                                                                                 $('#blockmasid').empty();
//										 //$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
//                                                                                $.each(data.myResult, function(i,response){
//                                                                                        $('#blockmasid').append( new Option(response.blockname,response.blockmasid,true,false) );
//                                                                                });
//                                                                        }
//                                                                        else
//                                                                        {
//                                                                                alert(response.s);
//                                                                        }
//                                                                });		
//                                                        });
//			 var url="load_tenant.php?item=loadTenantFloor&itemval="+$tenantmasid;
//                                                        $.getJSON(url,function(data){
//                                                                $.each(data.error, function(i,response){
//                                                                        if(response.s == "Success")
//                                                                        {
//                                                                                 $('#floormasid').empty();
//										  //$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
//                                                                                $.each(data.myResult, function(i,response){
//                                                                                        $('#floormasid').append( new Option(response.floorname,response.floormasid,true,false) );
//                                                                                });
//                                                                        }
//                                                                        else
//                                                                        {
//                                                                                alert(response.s);
//                                                                        }
//                                                                });		
//                                                        });
////			 var url="load_tenant.php?item=loadTenantShop&itemval="+$tenantmasid;
////                                                        $.getJSON(url,function(data){
////                                                                $.each(data.error, function(i,response){
////                                                                        if(response.s == "Success")
////                                                                        {
////                                                                                 $('#shopmasid').empty();
////										 //$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
////                                                                                $.each(data.myResult, function(i,response){
////                                                                                        $('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
////                                                                                });
////                                                                        }
////                                                                        else
////                                                                        {
////                                                                                alert(response.s);
////                                                                        }
////                                                                });		
////                                                        });
////			var url="load_tenant.php?item=details&itemval="+$tenantmasid;
////			$.getJSON(url,function(data){
////				$.each(data.error, function(i,response){
////					if(response.s == "Success")
////					{
////                                            $.each(data.myResult, function(i,response){
////						    $("#tenanttypemasid").val(response.tenanttypemasid);
////                                                    $("#buildingmasid").val(response.buildingmasid);
////                                                    $("#blockmasid").val(response.blockmasid);
////                                                    $("#floormasid").val(response.floormasid);
////                                                    //$("#shopmasid").val(response.shopmasid);
////						    $("#shoptypemasid").val(response.shoptypemasid);
////						    $("#orgtypemasid").val(response.orgtypemasid);
////						    //$("#salutation").val(response.salutation);
////						    //$("#leasename").val(response.leasename);
////						    var z=$("#orgtypemasid").val();
////						    if(z==2){
////							$("#tradingname").val(response.tradingname);
////							$("#tradingnamerow").show();
////						    }
////						    else
////						    {
////							$("#tradingname").val('');
////							$("#tradingnamerow").hide();
////						    }
////						    $("#nob").val(response.nob);
////						    $("#agemasidlt").val(response.agemasidlt);
////						    $("#agemasidrc").val(response.agemasidrc);
////						    $("#agemasidcp").val(response.agemasidcp);
////						    $("#creditlimit").val(response.creditlimit);
////						    $("#latefeeinterest").val(response.latefeeinterest);
////						    $("#doo").val(response.d1);
////						    $("#doc").val(response.d2);
////						    $("#pin").val(response.pin);
////						    $("#regno").val(response.regno);
////						    $("#address1").val(response.address1);
////							$("#address2").val(response.address2);
////							$("#poboxno").val(response.poboxno);
////							$("#city").val(response.city);
////							$("#state").val(response.state);
////							$("#pincode").val(response.pincode);
////							$("#country").val(response.country);
////							$("#telephone1").val(response.telephone1);
////							$("#telephone2").val(response.telephone2);
////							$("#fax").val(response.fax);
////							$("#emailid").val(response.emailid);
////							$("#website").val(response.website);
////							$("#remarks").val(response.remarks);
////							var url="load_tenant.php?item=detailsCP&itemval="+$tenantmasid;
////							$.getJSON(url,function(data){
////								$.each(data.error, function(i,response){
////									if(response.s == "Success")
////									{
////										$('#cprowbody').append(response.msg);
////									}
////								});
////							});
////						    $act = response.active;
////							if($act == "1")
////							{
////								$("#active").attr('checked','checked');
////							}
////							else
////							{
////								$("#active").removeAttr('checked')
////							}
////                                            });					  
////					}
////					else
////					{
////						alert(response.s);
////                                               $('input[type=text]').val('');
////						$('#buildingmasid').val('');
////						$('#agemasidlt').val('');
////						$('#agemasidrc').val('');
////						$('#agemasidcp').val('');
////						$('#buildingmasid').empty();
////						$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
////						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
////						$('#blockmasid').empty();
////						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
////						$('#floormasid').empty();
////						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
////						$('#shopmasid').empty();
////						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
////					}
//				//});             
//                       // });
//		}
//		else
//		{
//                        $('input[type=text]').val('');
//			$('#buildingmasid').val('');
//			$('#agemasidlt').val('');
//			$('#agemasidrc').val('');
//			$('#agemasidcp').val('');
//			$('#shoptypemasid').val('');
//			$('#buildingmasid').empty();
//			$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
//			$('#blockmasid').empty();
//			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
//			$('#floormasid').empty();
//			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
//			$('#shopmasid').empty();
//			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
//		}
//	});  
	$("#buildingmasid").change(function(){
		var $buildingmasid = $('#buildingmasid').val();
		if($buildingmasid !="")
		{
			var url="load_tenant.php?item=loadBuildingBlock&itemval="+$buildingmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#blockmasid').empty();
						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
						$('#floormasid').empty();
						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
						$.each(data.myResult, function(i,response){
						$('#blockmasid').append( new Option(response.blockname,response.blockmasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
						$('#blockmasid').empty();
						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
						$('#floormasid').empty();
						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
						$('#shopmasid').empty();
						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
					}
				});             
                        });
		}
		else
		{
                        $('#blockmasid').empty();
			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
			$('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
			$('#shopmasid').empty();
			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
		}
        });
	$("#blockmasid").change(function(){
		var $blockmasid = $('#blockmasid').val();
		if($blockmasid !="")
		{
			var url="load_tenant.php?item=loadBlockFloor&itemval="+$blockmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#floormasid').empty();
						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
						$.each(data.myResult, function(i,response){
							$('#floormasid').append( new Option(response.floorname,response.floormasid,true,false) );
						});
					}
					else
					{
						alert(response.msg);
						$('#floormasid').empty();
						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
						$('#shopmasid').empty();
						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
					}
				});             
                        });
		}
		else
		{
                        $('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
			$('#shopmasid').empty();
			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
		}
        });
		$("#floormasid").change(function(){
		var $floormasid = $('#floormasid').val();
		if($floormasid !="")
		{
			var url="load_tenant.php?item=loadFloorShop&itemval="+$floormasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                               / //          //  alert("success");
                           //$('#shoploader').empty();
                                                $('#floors').empty();
                                                $('#shoploader').show();
                                                
						$('#shopmasid').empty();
						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
						$.each(data.myResult, function(i,response){
				                $('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
						if(response.active=="0"){
                                            $('#floors').append("<div class='square'><div class='content'><div class='table'>\n\
                        <div id='intern' class='table-cell' style='color:white !important; background-color: green !important;'><input type='radio' value='"+response.shopmasid+"' name='shopmasid[]'> shop: "+ response.shopcode +"  size: <b>"+response.size+"</b></div></div></div></div>");
                                                
                                            }else{
                                             $('#floors').append("<div class='square'><div class='content'><div class='table'>\n\
                        <div id='intern' class='table-cell' style='color:white !important; background-color: orange !important;'><input type='radio' value='"+response.shopmasid+"' name='shopmasid[]'> shop: "+ response.shopcode +"  size: <b>"+response.size+"</b></div></div></div></div>");        
                                                   
                                                }
                                                 });
					 
                                    
                                
                                       }
					else
					{
						alert(response.msg);
						$('#shopmasid').empty();
						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
					} 
				});             
                        });
		}
		else
		{
                        $('#shopmasid').empty();
			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
		}
        });
      
       $('#btnCloseShops').click(function(){
       
        $('#shoploader').hide();
        });
        
     $('#btnLoad').click(function(){
    
    var floorid=$('#floormasid').val();
    if(floorid!=""){
    var url="load_tenant.php?item=loadFloorShop&itemval="+floorid;	
   // alert(url);
    $.getJSON(url,function(data){
        $.each(data.error, function(i,response){
            if(response.s == "Success")
            {
                
                    $.each(data.myResult, function(i,response){
                            
                        //$('#floors').append( new Option(response.shopcode,response.shopmasid,true,false) );
                    
                       $('#floors').append("<div class='square'><div class='content'><div class='table'>\n\
                        <div id='intern' class='table-cell' style='color:white !important;'> shop: "+ response.shopcode +"  size: "+ response.size +"</div></div></div></div>");
                  });

            }
            else
            {
                    alert(response.msg);
            } 
        });             
          }); 

     }else{
     
     alert("Select Floor First");
     } 
     
     
     });
     
        
        
//        $("#shopmasid").change(function(){
//		var $shopmasid = $('#shopmasid').val();
//		if($shopmasid !="")
//		{
//		 var url="load_tenant.php?item=loadShopSize&itemval="+$shopmasid;					
//			$.getJSON(url,function(data){
//				$.each(data.error, function(i,response){
//					if(response.s == "Success")
//					{
//						
//                                         $.each(data.myResult, function(i,response){
//                                                //$('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
//                                          $('#area').val(response.size);
//                                   
//                                         });
//
//                                    
//                                
//                                       }
//					else
//					{
//						alert(response.msg);
//						 $('#area').empty();
//						//$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
//					} 
//				});             
//                        });	
//		}
//		else
//		{
//                 $('#area').empty();
//
//		}
//        });
      

});
$(document).ready(function() {
$("#country").msDropdown();
})
function showKeyCode(e) {
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
    
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="GET">
<!--<div id="container">-->
<center><h1>Enquiry Master</h1></center><br>
<div id="menuDiv" style="margin: auto;">
<table>
<tr>
        <td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>        
        <td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
</tr>
</table>
</div>
<br>
<label id="cc"></label>
<div id="exampleDiv" >
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin:auto !important;">
    <thead>
            <tr>            
                    <th style="color:black !important;">Index</th>							
                    <th style="color:black !important;">Building</th>		    
                    <th style="color:black !important;">For</th>		    
		    <th style="color:black !important;">Square Feet</th>
		    <th style="color:black !important;">Date</th>
                    <th style="color:black !important;">Contact</th>
                    <th style="color:black !important;">Mobile</th>
                    <th style="color:black !important;">Email</th>
		    <th style="color:black !important;">Edit</th>
            </tr>
    </thead>
    <tbody id="tbodyContent">
	<?php
	
	$sql = "SELECT a.enquirymasid,a.companymasid,date_format(a.enquiryreceivedon,'%d-%m-%Y')as enquiryreceivedon,a.companyname,a.orgtype,a.nob,a.dirname,a.tradingname, a.cpname,a.address,a.city,
		a.poboxno,a.postalcode,a.country,a.telephone,a.mobile,a.emailid,a.buildingmasid,a.floorname,a.area,a.period,a.referedby,
		a.remarks,a.createdby,a.createddatetime,a.modifiedby,a.modifieddatetime,a.active,b.buildingname 
		from mas_enquiry_updated a 
		inner join mas_building b on b.buildingmasid = a.buildingmasid
		order by a.companyname asc;";				
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
				    
				     $nob = $row["nob"];
				     $buildingname = $row["buildingname"];				     
				     $floorname = $row["floorname"];
				     $period = $row["period"];
				     $landline = $row["telephone"];
				     $mobile = $row["mobile"];
				     $emailid = $row["emailid"];
                                     $cpname = $row["cpname"];
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
                                     <td>".$cpname."</td>
                                     <td>".$mobile."</td>
				     <td><button type='button' id=btnEmail$i name='".$emailid."'  val='".$emailid."' >Email Contact</button></td>   
                                     <input type='hidden' id=cpname$i name='cpname' value='".$cpname."'>     
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
		    <th>Square Feet</th>
		    <th>Date</th>
                    <th>Contact</th>
                    <th>Mobile</th>
                    <th>Email</th>
		    <th>Edit</th>
            </tr>
    </tfoot>
</table>
</div>
<div id="enqflpdiv" align='right'>
	<table id='enqflptbl' class='table2' style="margin:auto">
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

</style>
<div id="dataManipDiv">
<table id="usertbl" class="table2" style="float:left;" width="40%">
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
               Individual or Company <font color="red">*</font>
        </td>
        <td>
        <select id="orgtypemasid" name="orgtypemasid">
      
	        <option value="" selected>----Select Organization Type----</option>
				<?php loadOrgtype();?>
	</select>
            
        </td>
    </tr>
        <tr>
		<td>
			Salutation <font color="red">*</font>
		</td>
		<td id="salutations">
			<select id="salutation" name="salutation">
				<option value="" selected>----Select Salutation----</option>
				<option value="Mr.">Mr.</option>
				<option value="Mrs.">Mrs.</option>
				<option value="Miss.">Miss.</option>				
				<option value="Dr.">Dr.</option>
				<option value="M/s.">M/s.</option>
				<option value="Prof.">Prof.</option>
				<option value="Rev.">Rev.</option>
				<option value="Lady.">Lady.</option>
				<option value="Sir.">Sir.</option>
				<option value="Captain.">Captain.</option>
				<option value="Major.">Major.</option>
				<option value="Judge.">Judge.</option>
				<option value="Ambassador.">Ambassador.</option>
				<option value="Governor.">Governor.</option>
                                <option value="Other.">Other</option>
			</select>
		</td>
	</tr>
     <tr class="coorleasename"></tr>

     <tr class="dirorowner"></tr>
     <tr>
	 <td>
                Nature Of Business <font color="red">*</font>
        </td>
         <td>
            <input type='text' id='nob' name='nob'  placeholder="Enter nature of business" />
        </td>
    </tr>
     <tr>
	 <td>
              Type of Shop<font color="red">*</font>
        </td>
        <td id="shoptypes">
        <select id="shoptypemasid" name="shoptypemasid">
			<option value="" selected>----Select Shop Type----</option>
			<?php loadShoptype();?>
	</select>
        </td>
    </tr>
   
 <tr>
	 <td>
                Contact Person Name <font color="red">*</font>
        </td>
        <td>
            <input type='text' id='cpname' name='cpname' placeholder="Enter name of contact person" />
        </td>
    </tr>

    <tr>
	<td>
                Contact Address <font color="red">*</font>
        </td>
        <td>
            <input type="text" id='address' name='address' cols=55 rows=5 placeholder="Enter physical location" />
        </td>
    </tr>
     <tr>
	 <td>
                City/County/State<font color="red">*</font>
        </td>
        <td>
            <input type='text' id='city' name='city' placeholder="Enter city, state or county" />
        </td>
    </tr>
      <tr>
	 <td>
                P.O.Box Number<font color="red">*</font>
        </td>
        <td>
            <input type='number' id='poboxno' name='poboxno' />
        </td>
    </tr>
    <tr>
	 <td>
                Postal Code<font color="red">*</font>
        </td>
        <td>
            <input type='number' id='postalcode' name='postalcode' />
        </td>
    </tr>
     <tr>
	 <td>
                Country<font color="red">*</font>
        </td>
        <td>
            <!--<input type='text' id='country' name='country' value='KENYA'/>-->
            <select name="country" id="country" style="width:300px;">
  <option value='India' data-image="../images/msdropdown/icons/blank.gif" data-imagecss="flag in" data-title="India" >India</option>
  <option value='Kenya' data-image="../images/msdropdown/icons/blank.gif" data-imagecss="flag ke" data-title="Kenya" selected="selected">Kenya</option>
  
            </select>
        </td>
    </tr>
    <tr>
	 <td>
                Land Line Telephone
        </td>
        <td>
            <input type='phone' id='telephone' name='telephone' />
        </td>
    </tr>
      <tr>
	 <td>
                Mobile Number
        </td>
        <td>
            <input type='phone' id='mobile' name='mobile' />
        </td>
    </tr>
       <tr>
	 <td>
                Full Email Address
        </td>
        <td>
            <input type='email' id='emailid' name='emailid' />
        </td>
     <tr>
        <td>
                Select Building <font color="red">*</font>
        </td>
        <td>
            <select id="buildingmasid" name="buildingmasid">
                    <option value="" selected>----Select Building----</option>
                    <?php loadBuilding($companymasid); ?>
            </select>
        </td>
     </tr>
     <tr>
		<td>
			Select Block <font color="red">*</font>
		</td>
		<td>
			<select id="blockmasid" name="blockmasid">
				<option value="" selected>----Select Block----</option>
			</select>
		</td>
    </tr>
    <tr>
            <td>
                    Select Floor <font color="red">*</font>
            </td>
            <td>
                    
                    <select id="floormasid" name="floormasid">
                            <option value="" selected>----Select Floor----</option>
                    </select>
            </td>
    </tr>;
     
    <tr id="shoploader">
	 <td>
                Shop <font color="red">*</font>
        </td>
        <td>
    <!--<button type="button" id="btnLoad" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Floor</button>-->
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">SHOPS/SPACES ON FLOOR</h4>
          <table width="30%">
              <tr><td>Occupied</td><td style='background:orange; color: black;'></td></tr>
              <tr><td>Available</td><td style='background:green; color: black;'></td></tr>
          </table>
        </div>
        <div class="modal-body" >
            <div id="floors" style="overflow-y:auto !important;" >

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnCloseShops" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
        
        </td>
    </tr>

    <tr>
		<td>
			PIN 
		</td>
		<td>
			<input type="text" id="pin" name="pin" placeholder="Enter 11 digit pin"><font color="red">*</font>
		</td>
	</tr>
    <tr>
	 <td>
                Period/Lease Term
        </td>
<!--        <td>
            <input type='text' id='period' name='period' />
        </td>-->
        <td>
          <!--<input type='text' id='agemasidlt' name='agemasidlt' placeholder="Enter lease term i.e. 5 years 6 months"/>-->
		<select id="agemasidlt" name="agemasidlt">
			<option value="" selected>----Select Lease Term----</option>
			<?php loadAgeMaster();?>
		</select>
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
			
		</td>Follow-up Entry
		<td>
			<button type="button" id="btnUpdate">Update</button>
		</td>
	</tr>
         <tr id="saveAndProceed">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSaveAndProceed">Save & Proceed To Tenancy</button>
		</td>
	</tr>
</table>
    

</div>

</div> <!--Main Div-->
<!--</div> Container Div-->
</form>
</body>
</html>
    