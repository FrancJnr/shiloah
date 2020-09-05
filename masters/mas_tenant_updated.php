<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    
	<title>Tenant Master</title>
          <link rel="stylesheet" type="text/css" href="../styles.css">
        
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
	
                    header("location:../index.php");
		}
                
		include('../config.php');
		include('../MasterRef_Folder.php');
                
                
                //$action=$_GET['action'];
                
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
		function loadAgeMasterRc()
		{
			$sql = "select age, agemasid from mas_age where active =1 and age like 'Per%' and age like 'per%'";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['agemasid'].">".$row['age']."</option>");		
				}
			}
		}
                //tenantmasid
		function loadCptype()
		{
			$sql = "select cptype, cptypemasid from mas_cptype order by cptype asc";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['cptypemasid'].">".$row['cptype']."</option>");		
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
                function loadShop()
                        {
                            $sql = "select shopmasid, shopcode from mas_shop";
                            $result = mysql_query($sql);
                            if($result != null)
                            {
                                    while($row = mysql_fetch_assoc($result))
                                    {
                                            echo("<option value=".$row['shopmasid'].">".$row['shopcode']."</option>");		
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
                
        //ENQUIRY FUNCTIONS        
                
       function loadBuilding()
    {
        $sql = "select buildingname, buildingmasid from mas_building order by buildingname asc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }
//         Loading from Enquiry List
    
          function loadEnquiryList()
    {
        $sql = "select companyname, enquirymasid from mas_enquiry_updated order by companyname asc";
//          $sql = "select companyname, enquirymasid from mas_enquiry_updated order by companyname desc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['enquirymasid'].">".$row['companyname']."</option>");		
                }
        }
    }
        
          
       
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
 
	$('#tradingnamerow').hide();
	$('#shopDetails').hide();        
		$("#doo").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
		$("#doc").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});	
        $('#cpTr').hide();
        $('#show1').click(function(){
            $('#cpTr').toggle();
        });
        $('#r1').hide();
        $('#r2').hide();
        $('#r3').hide();
        $('#r4').hide();
        $('#r5').hide();
	$('#r6').hide();
	$('#r7').hide();
	$('#r8').hide();
	$('#r9').hide();
	$('#r10').hide();
	$('#r11').hide();
	$('#r12').hide();
	$('#r13').hide();
        $('#show2').click(function(){
            $('#r1').toggle();
            $('#r2').toggle();
            $('#r3').toggle();
            $('#r4').toggle();
            $('#r5').toggle();
            $('#r6').toggle();
            $('#r7').toggle();
            $('#r8').toggle();
            $('#r9').toggle();
            $('#r10').toggle();
            $('#r11').toggle();
            $('#r12').toggle();
            $('#r13').toggle();
        });
	$('#dataManipDiv').hide();
	oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		//"sPaginationType": "full_numbers"			
	});
        $('#tenantname').blur(function() {
            $(this).val($(this).val().toUpperCase());
        });
   // }
	$('#btnNew').click(function(){
		 clearDynTable();
		
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Tenant");
		
		$("#tblHeaderCp").css('background-color', '#fc9;');
		$("#tblheaderCp1").css('background-color', '#fc9');
		$("#tblheaderCp2").css('background-color', '#fc9');
		$("#tblheaderCp3").css('background-color', '#fc9');
		
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#tenantcpDiv").show();
		$("#selectTenant").hide();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
		$('input[type=text]').val('');
		$('#latefeeinterest').val('2');		
		$('#tenanttypemasid').val('0');                
		$('#shopmasid').val('0');                
		$('#shoptypemasid').val('0');
		$('#orgtypemasid').val('0');
		$('#agemasidlt').val('0');
		$('#agemasidrc').val('0');
		$('#agemasidcp').val('0');
                var url="load_tenant.php?item=loadBuilding";					
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
//                 var url="load_tenant.php?item=loadBlock";					
//		$.getJSON(url,function(data){
//			$.each(data.error, function(i,response){
//				if(response.s == "Success")
//				{
//					$('#blockmasid').empty();
//					$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
//					$.each(data.myResult, function(i,response){						
//						$('#blockmasid').append( new Option(response.blockname,response.blockmasid,true,false) );
//					});
//				}
//				else
//				{
//					alert(response.s);
//				}
//			});		
//		});
		$('#blockmasid').empty();
		$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
		$('#floormasid').empty();
		$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
//		$('#shopmasid').empty();
//		$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
//		
	});

	$('#orgtypemasid').change(function(){
		var a = $('#orgtypemasid').val();
		if(a ==2){
			$('#tradingnamerow').show();
		}
		else{
			$('#tradingnamerow').hide();
			$('#tradingname').val("");
		}
	});	
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Tenant");
		
		$("#tblHeaderCp").css('background-color', '#4ac0d5');
		$("#tblHeaderCp").text("Edit contact persron");
		
		$("#tblheaderCp1").css('background-color', '#4ac0d5');
		$("#tblheaderCp2").css('background-color', '#4ac0d5');
		$("#tblheaderCp3").css('background-color', '#4ac0d5');
		
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#tenantcpDiv").show();
		$("#selectTenant").show();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');
		$("#active").removeAttr('checked')
		$('#shoptypemasid').val('0');
		$('#orgtypemasid').val('0');
		//$('#salutation').val('0');
		$('#agemasidlt').val('0');
		$('#agemasidrc').val('0');
		$('#agemasidcp').val('0');
		$('#buildingmasid').empty();
		$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
		$('#blockmasid').empty();
		$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
		$('#floormasid').empty();
		$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
		$('#shopmasid').empty();
		$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
		var url="load_tenant.php?item=loadTenantEdit";					
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
                                        $('#tenantmasid').empty();
					$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
					$.each(data.myResult, function(i,response){
						var a = response.leasename+"("+response.tenantcode+")";
                                                $('#tenantmasid').append( new Option(a,response.tenantmasid,true,false) );
					});
				}
				else
				{
					alert(response.s);
					$('form').submit();
				}
			});		
		});
		

	});
	$('#creditlimit').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#latefeeinterest').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#btnView').click(function(){
		$('form').submit();
	});
        $('#btnBack').click(function(){
            
      var a = confirm("Would You Like to go back to enquiry ?");
	if (a== true)
	{
          parent.top.$('div[name=masterdivtest]').html("<iframe name='tenantiframe' src='masters/mas_enquiry_updated.php?action=new' id='the_iframe2' scrolling='yes' width='100%'></iframe>");   
        }
    });  
          // alert( parent.top.find("div[name='masterdivtest']"));
          //alert( $("div[name='masterdivtest']", window.parent.document).html());
          //alert (window.parent.$("div[name='masterdivtest']").innerHTML)
        
//            alert($('.ui-tabs-nav', window.parent.document).html());
//              $('.ui-tabs-nav', window.parent.document).hide();
	//	window.parent.top.$('div[name=masterdivtest]').html("<iframe name='enquiryiframe' src='master/mas_enquiry_updated.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");    
//	      $([name="masterdivtest"], window.parent.document).html("<iframe name='enquiryiframe' src='master/mas_enquiry_updated.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>"); 
         // });
      $('#btnSaveAndProceed').click(function(){
          
          	if(jQuery.trim($("#tenanttypemasid").val()) == "")
		{
			alert("Please select Tenant Type");return false;
		}
                if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please select building");return false;
		}
		if(jQuery.trim($("#blockmasid").val()) == "")
		{
			alert("Please select block");return false;
		}
		if(jQuery.trim($("#floormasid").val()) == "")
		{
			alert("Please select floor");return false;
		}
		if(jQuery.trim($("#shopmasid").val()) == "")
		{
			alert("Please select shop");return false;
		}
		if(jQuery.trim($("#shoptypemasid").val()) == "")
		{
			alert("Please select Shop type");return false;
		}
		if(jQuery.trim($("#salutation").val()) == "")
		{
			//alert(jQuery.trim($("#salutation").val()));
                      alert("Please select the salutation");return false;
		}
               if(jQuery.trim($("#salutation").val()) == "Other.")
		{
		if(jQuery.trim($("#othersalutation").val()) == " ")
		{
		alert("Please enter the salutation");return false;	
                     
		}	
                     
		}
               
		if(jQuery.trim($("#leasename").val()) == "")
		{
			alert("Please enter lease name");return false;
		}
		if(jQuery.trim($("#orgtypemasid").val()) == "")
		{
			alert("Please select Org type");return false;
		}
		if(jQuery.trim($("#nob").val()) == "")
		{
			alert("Please enter Nature of Business");return false;
		}
		if(jQuery.trim($("#agemasidlt").val()) == "")
		{
			alert("Please select Lease Term");return false;
		}
		if(jQuery.trim($("#agemasidrc").val()) == "")
		{
			alert("Please select Rent cycle");return false;
		}
		if(jQuery.trim($("#agemasidcp").val()) == "")
		{
			alert("Please select Credit period");return false;
		}
		if(jQuery.trim($("#creditlimit").val()) == "")
		{
			alert("Please enter credit limit");return false;
		}
		if(jQuery.trim($("#latefeeinterest").val()) == "")
		{
			alert("Please enter late fee interest");return false;
		}
		if(jQuery.trim($("#doo").val()) == "")
		{
			alert("Please enter Date of occupation");return false;
		}
		if(jQuery.trim($("#doc").val()) == "")
		{
			alert("Please enter Date of comencement");return false;
		}
		//if(jQuery.trim($("#pin").val()) == "")
		//{
		//	alert("Please enter PIN No");return false;
		//}
		var cptblrowsize = $('#dynTable tr').size();	
               // alert();
		if(cptblrowsize ==3)
		{
			alert("Please enter contact person details");
                        return false;
		}
		var a = confirm("Please make sure you filled all details including contact person. can u confirm this?");
		if (a== true)
		{
			var url="save_tenant.php?action=Save";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
                                            
						if(response.s =="Success")
						{
							
//                                        
                                         if(response.msg=="Data Saved Successfully"){
                                             
                                          //parent.top.$('div[name=masterdivtest]').html("<iframe name='tenantiframe' src='transaction/trans_offerletter.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");    
                                            var a = confirm("Would You Like to go to Offer Letters ?");
                                            if (a== true)
                                            {
                                             parent.top.$('div[name=masterdivtest]').html("<iframe name='tenantiframe' src='transaction/trans_offerletter_new.php?action=new' id='the_iframe2' scrolling='yes' width='100%'></iframe>");   
                                            }
//                                          var a = confirm("Would You Like to proceed to Offer Letters ?");
//                                            if (a== true)
//                                            {
//                                             parent.top.$('div[name=masterdivtest]').html("<iframe name='tenantiframe' src='transaction/trans_offerletter.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");    
//                                            }
//                                      
//                                  
                                            }else{
                                     
                                          return;
                                        }
                                      
                                           }
						else
						{
				               alert(response.msg);
					
						
                                            }
						
					});
			});
      }
            
   
    });  
	 
	$('#btnSave').click(function(){
		if(jQuery.trim($("#tenanttypemasid").val()) == "")
		{
			alert("Please select Tenant Type");return false;
		}
                if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please select building");return false;
		}
		if(jQuery.trim($("#blockmasid").val()) == "")
		{
			alert("Please select block");return false;
		}
		if(jQuery.trim($("#floormasid").val()) == "")
		{
			alert("Please select floor");return false;
		}
		if(jQuery.trim($("#shopmasid").val()) == "")
		{
			alert("Please select shop");return false;
		}
		if(jQuery.trim($("#shoptypemasid").val()) == "")
		{
			alert("Please select Shop type");return false;
		}
		if(jQuery.trim($("#salutation").val()) == "")
		{
			//alert(jQuery.trim($("#salutation").val()));
                      alert("Please select the salutation");return false;
		}
               if(jQuery.trim($("#salutation").val()) == "Other")
		{
		if(jQuery.trim($("#othersalutation").val()) == " ")
		{
		alert("Please enter the salutation");return false;	
                     
		}	
                     
		}
               
		if(jQuery.trim($("#leasename").val()) == "")
		{
			alert("Please enter lease name");return false;
		}
		if(jQuery.trim($("#orgtypemasid").val()) == "")
		{
			alert("Please select Org type");return false;
		}
		if(jQuery.trim($("#nob").val()) == "")
		{
			alert("Please enter Nature of Business");return false;
		}
		if(jQuery.trim($("#agemasidlt").val()) == "")
		{
			alert("Please select Lease Term");return false;
		}
		if(jQuery.trim($("#agemasidrc").val()) == "")
		{
			alert("Please select Rent cycle");return false;
		}
		if(jQuery.trim($("#agemasidcp").val()) == "")
		{
			alert("Please select Credit period");return false;
		}
		if(jQuery.trim($("#creditlimit").val()) == "")
		{
			alert("Please enter credit limit");return false;
		}
		if(jQuery.trim($("#latefeeinterest").val()) == "")
		{
			alert("Please enter late fee interest");return false;
		}
		if(jQuery.trim($("#doo").val()) == "")
		{
			alert("Please enter Date of occupation");return false;
		}
		if(jQuery.trim($("#doc").val()) == "")
		{
			alert("Please enter Date of comencement");return false;
		}
		//if(jQuery.trim($("#pin").val()) == "")
		//{
		//	alert("Please enter PIN No");return false;
		//}
		var cptblrowsize = $('#dynTable tr').size();		
		if(cptblrowsize ==4)
		{
			alert("Please enter contact person details");return false;
		}
		var a = confirm("Please make sure you filled all details including contact person. can u confirm this?");
		if (a== true)
		{
			var url="save_tenant.php?action=Save";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
                                            
						if(response.s =="Success")
						{
							
//                                        
                                         if(response.msg=="Data Saved Successfully"){
                                             
                                          //parent.top.$('div[name=masterdivtest]').html("<iframe name='tenantiframe' src='transaction/trans_offerletter.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");    
//                                         
//                                          var a = confirm("Would You Like to proceed to Offer Letters ?");
//                                            if (a== true)
//                                            {
//                                             parent.top.$('div[name=masterdivtest]').html("<iframe name='tenantiframe' src='transaction/trans_offerletter.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");    
//                                            }
                                        alert(response.msg);
                                  
                                            }else{
                                     
                                          return;
                                        }
                                      
                                           }
						else
						{
				               alert(response.msg);
					
						
                                            }
						
					});
			});
		}
	});
        
        
	function clearDynTable()
	{
		$('#dynTable tr:gt(1)').remove();
		$tbl  = "<tr class='prototype'><td><input type='radio' style='width: 150px;' name='documentname' checked/></td>";
		$tbl += "<td><input type='text' style='width: 150px;' placeholder='Enter Name' name='cpname'/></td>";
		$tbl += "<td id='cpy'><select style='width: 150px;' name='cptypemasid' id='cpyy'><option value=''>----Designation----</option><?php loadCptype();?></select>";
		$tbl += "<td><input type='number' style='width: 150px;' name='cpnid' placeholder='National ID'/></td>";
		$tbl += "<td><input type='number' style='width: 150px;' name='cpmobile' placeholder='Mobile Number'/></td>";
		$tbl += "<td><input type='number' style='width: 150px;' name='cplandline' placeholder='Office Number'/></td>";
		$tbl += "<td><input type='email' style='width: 150px;' name='cpemailid' placeholder='Email'/></td>";
		$tbl += "<td><button type='button' class='remove'>Remove</button></td><tr>";
		$('#cprowbody').append($tbl);
		$('#cc').html('');
	}
	$('#btnUpdate').click(function(){
		if(jQuery.trim($("#tenanttypemasid option:selected").val()) == "")
		{
			alert("Please select Tenant Type");return false;
		}
		if($("#tenantmasid option:selected").val()== "")
		{
			alert("Please select tenant");return false;
		}
                if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please select building");return false;
		}
		if(jQuery.trim($("#blockmasid").val()) == "")
		{
			alert("Please select block");return false;
		}
		if(jQuery.trim($("#floormasid").val()) == "")
		{
			alert("Please select floor");return false;
		}
		if(jQuery.trim($("#shopmasid").val()) == "")
		{
			alert("Please select shop");return false;
		}
		if(jQuery.trim($("#shoptypemasid").val()) == "")
		{
			alert("Please select Shop type");return false;
		}
		if(jQuery.trim($("#salutation").val()) == "")
		{
			alert("Please enter salutation");return false;
		}
		if(jQuery.trim($("#leasename").val()) == "")
		{
			alert("Please enter lease name");return false;
		}
		if(jQuery.trim($("#orgtypemasid").val()) == "")
		{
			alert("Please select Org type");return false;
		}
		if(jQuery.trim($("#nob").val()) == "")
		{
			alert("Please enter Nature of Business");return false;
		}
		if(jQuery.trim($("#agemasidlt").val()) == "")
		{
			alert("Please select Lease Term");return false;
		}
		if(jQuery.trim($("#agemasidrc").val()) == "")
		{
			alert("Please select Rent cycle");return false;
		}
		if(jQuery.trim($("#agemasidcp").val()) == "")
		{
			alert("Please select Credit period");return false;
		}
		if(jQuery.trim($("#creditlimit").val()) == "")
		{
			alert("Please enter credit limit");return false;
		}
		if(jQuery.trim($("#latefeeinterest").val()) == "")
		{
			alert("Please enter late fee interest");return false;
		}
		if(jQuery.trim($("#doo").val()) == "")
		{
			alert("Please enter Date of occupation");return false;
		}
		if(jQuery.trim($("#doc").val()) == "")
		{
			alert("Please enter Date of comencement");return false;
		}
		if(jQuery.trim($("#pin").val()) == "")
		{
			alert("Please enter PIN No");return false;
		}
		var cptblrowsize = $('#dynTable tr').size();		
		if(cptblrowsize ==4)
		{
			alert("Please enter contact person details");return false;
		}
		var a = confirm("Pls Make sure you filled all details including contact person. can u confirm this?");
		if (a== true)
		{
		var url="save_tenant.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{
					$('input[type=text]').val('');
					$('#shoptypemasid').val('');
					$('#orgtypemasid').val('');
					$('#agemasidlt').val('');
					$('#agemasidrc').val('');
					$('#agemasidcp').val('');
					$('#tenanttypemasid').empty();
					$('#tenanttypemasid').append( new Option("-----Select Tenant Type-----","",true,false) );
					$('#buildingmasid').empty();
					$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
					$('#blockmasid').empty();
					$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
					$('#floormasid').empty();
					$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
					$('#shopmasid').empty();
					$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
					var url="load_tenant.php?item=loadTenant";					
					$.getJSON(url,function(data){
						$.each(data.error, function(i,response){
							if(response.s == "Success")
							{
								$('#tenantmasid').empty();
								$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
								$.each(data.myResult, function(i,response){
									var a = response.leasename+" ("+response.tenantcode+")";
									$('#tenantmasid').append( new Option(a,response.tenantmasid,true,false) );
								});
							}
							else
							{
								//alert(response.s);
								$('#cc').html(response.msg);
							}
						});		
					});
					$('#dynTable tr:gt(1)').remove();
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
//        $("#salutation").change(function(){
//        var $salute = $('#salutation').val();
//       // alert("Other1"); 
//        $('#salutation').focus();
//        if($salute=="Other."){
//          // alert("Other."); 
//          var saluteinput="<td><input type='text' id='othersalutation' placeholder='Enter Salutation' name='othersalutation'></td>";
//          $('#salutations').append(saluteinput);
//        }
//       
//   
//        });
//          window.bldmasid="";
//          window.flrmasid="";
//          window.blkmasid="";
           //var datainputz=new Array();
	$("#tenantmasid").change(function(){
		clearDynTable();
              
		var $tenantmasid = $('#tenantmasid').val();
            
           //  alert($tenantmasid);
		//$('#buildingmasid').focus();
		if($tenantmasid !="")
		{
                           var url="load_tenant.php?item=loadBuildingFromEnquiry&enquirymasid="+$tenantmasid;					
							$.getJSON(url,function(data){
								$.each(data.error, function(i,response){
									if(response.s == "Success")
									{
										$('#buildingmasid').empty();
										//$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
										$.each(data.myResult, function(i,response){
											$('#buildingmasid').append( new Option(response.buildingname,response.buildingmasid,true,false) );											
										});
										$('#buildingmasid').attr('readonly');
									
                                                                               bldmasid=$('#buildingmasid').val();
                                                                           //  alert(bldmasid);
                                                                       //datainputz.push($('#buildingmasid').val());
                                                                      //var datainputfz=new Array();
                                                                      
                                                                        }
									else
									{
										alert(response.s);
									}
								});		
							});
                                                     //  alert($('#buildingmasid').val());
                          //$('#blockmasid').focus();
                         var url="load_tenant.php?item=loadTenantBlockFromEnquiry&itemval="+$tenantmasid;
                                                        $.getJSON(url,function(data){
                                                                $.each(data.error, function(i,response){
                                                                        if(response.s == "Success")
                                                                        {
                                                                                 $('#blockmasid').empty();
										 //$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
                                                                                $.each(data.myResult, function(i,response){
                                                                                   // alert(response.blockname);
                                                                                        $('#blockmasid').append( new Option(response.blockname,response.blockmasid,true,false) );
                                                                                });
                                                                        
                                                                                //window.blkmasid=$('#blockmasid').val();
                                                                       }
                                                                        else
                                                                        {
                                                                                alert(response.s);
                                                                        }
                                                                });		
                                                        });
			 var url="load_tenant.php?item=loadTenantFloorFromEnquiry&itemval="+$tenantmasid;
                                                        $.getJSON(url,function(data){
                                                                $.each(data.error, function(i,response){
                                                                        if(response.s == "Success")
                                                                        {
                                                                                 $('#floormasid').empty();
										  //$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
                                                                                $.each(data.myResult, function(i,response){
                                                                                        $('#floormasid').append( new Option(response.floorname,response.floormasid,true,false) );
                                                                                });
                                                                        
                                                                               flrmasid=$('#floormasid').val();
                                                                             // alert($flrmasid);
                                                                            }
                                                                        else
                                                                        {
                                                                                alert(response.s);
                                                                        }
                                                                });		
                                                        });
                                                       // $(document).focus();
                                                       //$(window).scrollTop();
                                                        //alert("refresh");
                                                        //confirm("please proceed");
			 var url="load_tenant.php?item=loadShopTypeFromEnquiry&tenantmasid="+$tenantmasid;
                                                      //alert(url);
                                                        $.getJSON(url,function(data){
                                                                $.each(data.error, function(i,response){
                                                                        if(response.s == "Success")
                                                                        {
                                                                                 $('#shoptypemasid').empty();
                                                                                 //echo("<option value=".$row['shoptypemasid'].">".$row['shoptype']."</option>");		
										 //$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
                                                                                $.each(data.myResult, function(i,response){
                                                                                        $('#shoptypemasid').append( new Option(response.shoptype,response.shoptypemasid,true,false) );
                                                                                });
                                                                        }
                                                                        else
                                                                        {
                                                                              alert(response.s);   
//                                                                              if(response.s=="No Tenants for editing"){
//                                                                                  
//                                                                                  alert("There is on shop in that floor");
//                                                                              }else{
//                                                                                   alert(response.s);
//                                                                              }
                                                                    
                                                                
                                                                        }
                                                                });		
                                                        });
                            var url="load_tenant.php?item=loadShopCodeFromEnquiry&tenantmasid="+$tenantmasid;
                         //alert(url);
                           $.getJSON(url,function(data){
                                   $.each(data.error, function(i,response){
                                           if(response.s == "Success")
                                           {
                                                    $('#shopmasid').empty();
                                                
                                                   $.each(data.myResult, function(i,response){
                                                       $('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
                                                   
                                               });
                                           }
                                           else
                                           {
                                                 alert(response.s);   


                                           }
                                   });		
                           });
//                                                         
			var url="load_tenant.php?item=detailsfromenquiry&itemval="+$tenantmasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            $.each(data.myResult, function(i,response){
						    //$("#tenanttypemasid").val(response.tenanttypemasid);
                                                    //$("#buildingmasid").val(response.buildingmasid);
                                                    //$("#blockmasid").val(response.blockmasid);
                                                    //$("#floormasid").val(response.floormasid);
                                                   // $("#shopmasid").val(response.shopmasid);
						   // $("#shoptypemasid").val(response.shoptypemasid);
						    $("#orgtypemasid").val(response.orgtypemasid);
						    $("#salutation").val(response.salutation);
						    $("#leasename").val(response.companyname);
                                                    $("#tradingname").val(response.tradingname);
						    var z=$("#orgtypemasid").val();
						    if(z==2){
							$("#tradingname").val(response.tradingname);
							$("#tradingnamerow").show();
						    }
						    else
						    {
							$("#tradingname").val('');
							$("#tradingnamerow").hide();
						    }
						    $("#nob").val(response.nob);
						    $("#shoptypemasid").val(response.shoptypemasid);
                                                    $('#agemasidlt').append( new Option(response.age,response.agemasid,true,false) );
//						    $("#agemasidrc").val(response.agemasidrc);
						    $("#agemasidcp").val(response.agemasidcp);
//						    $("#creditlimit").val(response.creditlimit);
						    //$("#latefeeinterest").val(response.latefeeinterest);
//						    $("#doo").val(response.d1);
//						    $("#doc").val(response.d2);
						    $("#pin").val(response.pin);
//						    $("#regno").val(response.regno);
						    $("#address1").val(response.address1);
							$("#address2").val(response.address2);
							$("#poboxno").val(response.poboxno);
							$("#city").val(response.city);
							$("#state").val(response.state);
							$("#pincode").val(response.postalcode);
							$("#country").val(response.country);
							$("#telephone1").val(response.telephone);
							$("#telephone2").val(response.mobile);
							//$("#fax").val(response.fax);
							$("#emailid").val(response.emailid);
							//$("#website").val(response.website);
							$("#remarks").val(response.remarks);
							
                                                        //contact person details
                                                        var url="load_tenant.php?item=detailsCPfromEnquiry&itemval="+$tenantmasid;
							$.getJSON(url,function(data){
								$.each(data.error, function(i,response){
									if(response.s == "Success")
									{
										$('#cprowbody').append(response.msg);
									}
								});
							});
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
                                               $('input[type=text]').val('');
//						$('#buildingmasid').val('');
						$('#agemasidlt').val('');
//						$('#agemasidrc').val('');
//						$('#agemasidcp').val('');
//						$('#buildingmasid').empty();
//						$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
//						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
//						$('#blockmasid').empty();
//						$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
//						$('#floormasid').empty();
//						$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
//						$('#shopmasid').empty();
//						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
					}
				});             
                        });
		}
		else
		{
                        $('input[type=text]').val('');
			$('#buildingmasid').val('');
			$('#agemasidlt').val('');
			$('#agemasidrc').val('');
			$('#agemasidcp').val('');
			$('#shoptypemasid').val('');
			$('#buildingmasid').empty();
			$('#buildingmasid').append( new Option("-----Select Building-----","",true,false) );
			$('#blockmasid').empty();
			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
			$('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
//			$('#shopmasid').empty();
//			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
		}
	});  
	$("#buildingmasid").focus(function(){
		var $buildingmasid = $('#buildingmasid').val();
		//$('#shopDetails').show();
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
			//alert("Please select Building");
                        $('#blockmasid').empty();
			$('#blockmasid').append( new Option("-----Select Block-----","",true,false) );
			$('#floormasid').empty();
			$('#floormasid').append( new Option("-----Select Floor-----","",true,false) );
//			$('#shopmasid').empty();
//			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
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
//			$('#shopmasid').empty();
//			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
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
						$('#shopmasid').empty();
						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
						$.each(data.myResult, function(i,response){
							$('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
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
//                        $('#shopmasid').empty();
//			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
		}
        });	
//        $("#shopmasid").change(function(){
//		var $shopmasid = $('#shopmasid').val();
//		if($shopmasid !="")
//		{
//			var url="load_tenant.php?item=loadTenantShop&itemval="+$floormasid;					
//			$.getJSON(url,function(data){
//				$.each(data.error, function(i,response){
//					if(response.s == "Success")
//					{
//						$('#shopmasid').empty();
//						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
//						$.each(data.myResult, function(i,response){
//							$('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
//						});
//					}
//					else
//					{
//						alert(response.msg);
//						$('#shopmasid').empty();
//						$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
//					} 
//				});             
//                        });
//		}
//		else
//		{
//                        $('#shopmasid').empty();
//			$('#shopmasid').append( new Option("-----Select Shop-----","",true,false) );
//		}
//        });	
        	$("#shoptypemasid").change(function(){
		var $shoptypemasid = $('#shoptypemasid').val();
                //alert($shoptypemasid);
		if($shoptypemasid =="")
		{
                    //alert($shoptypemasid+" i");
			var url="load_tenant.php?item=loadShopType";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#shoptypemasid').empty();
						$('#shoptypemasid').append( new Option("-----Select Shop-----","",true,false) );
						$.each(data.myResult, function(i,response){
							//$('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
					        $('#shoptypemasid').append( new Option(response.shoptype,response.shoptypemasid,true,false) );
                                            });
					}
					else
					{
						alert(response.msg);
						//$('#shoptypemasid').empty();
						$('#shoptypemasid').append( new Option("-----Select Shop Type-----","",true,false) );
					} 
				});             
                        });
		}
		else
		{
                return;
//                $('#shoptypemasid').empty();
//                $('#shoptypemasid').append( new Option("-----Select Shop Type-----","",true,false) );
//		var url="load_tenant.php?item=loadShopType";					
//			$.getJSON(url,function(data){
//				$.each(data.error, function(i,response){
//					if(response.s == "Success")
//					{
//						$('#shoptypemasid').empty();
//						$('#shoptypemasid').append( new Option("-----Select Shop-----","",true,false) );
//						$.each(data.myResult, function(i,response){
//							//$('#shopmasid').append( new Option(response.shopcode,response.shopmasid,true,false) );
//					        $('#shoptypemasid').append( new Option(response.shoptype,response.shoptypemasid,true,false) );
//                                            });
//					}
//					else
//					{
//						return;
////						alert(response.msg);
////						//$('#shoptypemasid').empty();
////						$('#shoptypemasid').append( new Option("-----Select Shop Type-----","",true,false) );
//					} 
//				});             
//                        });
//             
                   }                                                                                          
        });
//        var $table = $('.table1');
//        $table.floatThead();
//        var $demo1 = $('table.table1');
//        $demo1.floatThead({
//	scrollContainer: function($demo1){
//		return $table.closest('#dataManipDiv');
//            }
//        });
        
        
//        $('a#change-dom').click(function(){ //click to remove
//                $(this).parent().remove();$('table.table1')
//                //DOM has changed. must reflow floatThead
//                $demo1.floatThead('reflow');
//        });
//        
        
        
        
        
// }       
});





 </script>
        
 
  <script type="text/javascript" language="javascript">
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
//});
     });
</script>   
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="get">
<!--<div id="container">-->
<center><h1>Tenant Master</h1></center><br>
<div id="menuDiv"  width="100%" align="right">
<table>
		<tr>
			
                        <td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
			<!--<td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>-->
			<td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
                        <td> <button class="buttonNew" type="button" id="btnBack"> Back </button> </td>
		</tr>
</table>
</div>
<br>
<div id="exampleDiv" width="100%">
				<table  class="display" id="example" width="100%">
					<thead>
						<tr>
                                                    <th>Index</th>							
                                                    <th>Building</th>
                                                    <th>Tenant</th>
                                                    <th>Shop</th>
                                                    <th>Created</th>
                                                    <th>Date</th>
                                                    <th>Modified</th>
                                                    <th>Date</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
                                            
			<?php
					$companymasid  = $_SESSION['mycompanymasid'];
			        	$companyname = $_SESSION['mycompany'];
					$sql = "select a.*,b.companyname,c.buildingname, c.shortname,d.shopcode,e.grouptenantmasid, e.tenantmasid\n"
						. "from mas_tenant a\n"
						. "inner join mas_company b on a.companymasid = b.companymasid\n"
						. "inner join mas_building c on a.buildingmasid = c.buildingmasid\n"
                                                . "inner join group_tenant_mas e on a.tenantmasid = e.tenantmasid\n"
						. "inner join mas_shop d on a.shopmasid = d.shopmasid where a.companymasid='".$companymasid."' and e.grouptenantmasid not in (SELECT grouptenantmasid FROM trans_tenant_discharge_ac) and e.grouptenantmasid not in (SELECT grouptenantmasid FROM trans_tenant_discharge_op) group by a.shopmasid";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									$buildingname = $row["shortname"];
                                                                        $shopcode = $row["shopcode"];
									if($row['tradingname'] !="")
									$leasename = $row["tradingname"]."(".$row["tenantcode"].")";
									else
									$leasename = $row["leasename"]."(".$row["tenantcode"].")";
									
									$cby = $row["createdby"];
									$cdt = $row["createddatetime"];
									if(strtotime($cdt) != 0)
									{
										//$cdt = date_format(new DateTime($cdt), "D d-F-Y H:i:s");
										$cdt = date_format(new DateTime($cdt), "d-m-Y");
										//E.g. Fri 03-August-2012 13:51:37
									}
									else
									{
										$cdt="";
									}
									$mby = $row["modifiedby"];
									$mdt = $row["modifieddatetime"];
									if(strtotime($mdt) != 0)
									{
										//$mdt = date_format(new DateTime($mdt), "D d-F-Y H:i:s");
										$mdt = date_format(new DateTime($mdt), "d-m-Y");
									}
									else
									{
										$mdt = "";
									}
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$buildingname."</td>
                                                                        <td>".$leasename."</td>
									<td>".$shopcode."</td>
									<td>".$cby."</td>
									<td>".$cdt."</td>
									<td>".$mby."</td>
									<td>".$mdt."</td>
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
                                                        <th>Building</th>
                                                        <th>Tenant</th>
							<th>Shop</th>
							<th>Created</th>
							<th>Date</th>
							<th>Modified</th>
							<th>Date</th>
						</tr>
					</tfoot>
				</table>
</div>
<style>
/*    #dataManipDiv { width: 100%;height: 500px;}
     #usertbl { width: 100%;height: 100%; 
     
     }*/
/*     table.floatThead-table {
        border-top: none;
        border-bottom: none;
        background-color: #fff;
    }*/
     
</style>
<div id="dataManipDiv">
<table id="usertbl" class="table1"  cellpadding="0" cellspacing="0" border="0"  style="padding: 2px 2px;
				width: 100%;">
   
<!--	<tr style="header:fixed;">-->
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New Tenant	
			</th>
		</tr>
<!--	</tr>-->
	<tbody>
            
         <tr id="selectTenantFromEnquiry">
		<td>
			Select Enquirer from Enquiry List <font color="red">*</font>
		</td>
		<td>
			<select id="tenantmasid" name="tenantmasid">
				<option value="" selected>----Select Enquirer----</option>
                                <?php  loadEnquiryList();?>
			</select>
		</td>
	</tr>
	 <tr id="selectTenantType">
		<td>
			Select Tenant Type <font color="red">*</font>
		</td>
		<td>
			<select id="tenanttypemasid" name="tenanttypemasid">
				<option value="" selected>----Select Tenant Type----</option>
				<?php loadTenantType();?>
			</select>
		</td>
	</tr>
        <tr id="selectBuilding">
		<td>
			Select Building <font color="red">*</font>
		</td>
		<td>
			<select id="buildingmasid" name="buildingmasid">
				<option value="" selected>----Select Building----</option>
			</select>
		</td>
	</tr>
	<tr id="selectBlock">
		<td>
			Select Block (i.e. MC-Mega City represent Building) <font color="red">*</font>
		</td>
		<td>
			<select id="blockmasid" name="blockmasid">
				<option value="" selected>----Select Block----</option>
			</select>
		</td>
	</tr>
	<tr id="selectFloor">
		<td>
			Select Floor (i.e. FG represent Floor Ground) <font color="red">*</font>
		</td>
		<td>
			<select id="floormasid" name="floormasid">
				<option value="" selected>----Select Floor----</option>
			</select>
		</td>
		</tr>
        <tr id="selectShop">
		<td>
			Select Shop (i.e. FMA 4C -MP represent Floor, Shop & Building) <font color="red">*</font>
		</td>
		<td>
			<select id="shopmasid" name="shopmasid">
				<option value="" selected>----Select Shop----</option>
                                <?php loadShop();?>
			</select>
		</td>
		</tr>
		<tr>
	<td>
		Select Shop Type <font color="red">*</font>
	</td>
	<td>
		<select id="shoptypemasid" name="shoptypemasid">
			<option value="" selected>----Select Shop Type----</option>
			           <?php loadShoptype(); ?>
		</select>
	</td>
	</tr>
	<tr id="selectOrgtype">
		<td>
			Select Organization Type <font color="red">*</font>
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
			<input id="salutation" type="text" name="salutation"> 
		</td>
	</tr>
       
<!--	<tr>
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
	</tr>-->
        
        <tr>
		<td>
			Company Name/Lease Name<font color="red">*</font>
		</td>
		<td>
			<input type="text" id="leasename" name="leasename">
		</td>
	</tr>
	<tr id='tradingnamerow'>
		<td>
			Trading Name
		</td>
		<td>
			<input type="text" id="tradingname" name="tradingname">
		</td>
	</tr>
	
	<tr>
		<td>
			Nature of Business <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="nob" name="nob">
		</td>
	</tr>
	 <tr>
	<td>
		Lease Term<font color="red">*</font>
	</td>
	<td>
		<select id="agemasidlt" name="agemasidlt">
			<!--<option value="" selected>----Lease Term----</option>-->
			<?php //loadAgeMaster();?>
		</select>
	</td>
	</tr>
        <tr>
	<td>
		Rent Cycle<font color="red">*</font>
	</td>
	<td>
		<select id="agemasidrc" name="agemasidrc">
			<option value="" selected>----Rent Cycle----</option>
			<?php loadAgeMasterRc();?>
		</select>
	</td>
	</tr>
        <tr>
		<td>
			Credit Period<font color="red">*</font>
		</td>
		<td>
			<select id="agemasidcp" name="agemasidcp">
				<option value="" selected>----Credit Period----</option>
				<?php loadAgeMaster();?>
			</select>	
	</td>
	</tr>
	<tr>
		<td>
			Credit Limit<font color="red">*</font>
		</td>
		<td>
			<input type="text" id="creditlimit" dir='rtl' name="creditlimit">&nbsp;<b>KSH</b>
		</td>
	</tr>
        <tr>
		<td>
			Late Fee Interest<font color="red">*</font>
		</td>
		<td>
			<input type="text" id="latefeeinterest" name="latefeeinterest" dir='rtl' val="2">&nbsp;&nbsp;<b>%</b>
		</td>
	</tr>
	 <tr>
		<td>
			Date of Occupation<font color="red">*</font>
		</td>
		<td>
			<input type="text" id="doo" name="doo">
		</td>
	</tr>
         <tr>
		<td>
			Date of Commencement<font color="red">*</font>
		</td>
		<td>
			<input type="text" id="doc" name="doc">
		</td>
	</tr>
	<tr>
		<td>
			PIN 
		</td>
		<td>
			<input type="text" id="pin" name="pin"><font color="red">*</font>
		</td>
	</tr>
	<tr>
		<td>
			REG No 
		</td>
		<td>
			<input type="text" id="regno" name="regno">
		</td>
	</tr>       
	<tr>
		<td><b>Address Details</b></td>
		<td><button type="button" id="show2"> >> </button></td>
	</tr>
	<tr id="r1">
		<td>
			Address 1
		</td>
		<td>
			<input type="text" id="address1" name="address1">
		</td>
	</tr>
	<tr id="r2">
		<td>
			Address 2
		</td>
		<td>
			<input type="text" id="address2" name="address2">
		</td>
	</tr>
	<tr id="r4">
		<td>
			City
		</td>
		<td>
			<input type="text" id="city" name="city">
		</td>
	</tr>
	<tr id="r5">
		<td>
			State
		</td>
		<td>
			<input type="text" id="state" name="state">
		</td>
	</tr>
	<tr id="r6">
		<td>
			Pin Code
		</td>
		<td>
			<input type="text" id="pincode" name="pincode">
		</td>
	</tr>
	<tr id="r7">
		<td>
			Country
		</td>
		<td>
			<input type="text" id="country" name="country">
		</td>
	</tr>
	<tr id="r3">
		<td>
			P.O.Box No
		</td>
		<td>
			<input type="text" id="poboxno" name="poboxno">
		</td>
	</tr>
	<tr id="r8">
		<td>
			Telephone 1
		</td>
		<td>
			<input type="text" id="telephone1" name="telephone1">
		</td>
	</tr>
	<tr id="r9">
		<td>
			Telephone 2
		</td>
		<td>
			<input type="text" id="telephone2" name="telephone2">
		</td>
	</tr>
	<tr id="r10">
		<td>
			Fax
		</td>
		<td>
			<input type="text" id="fax" name="fax">
	        </td>
	</tr>
	<tr id="r11">
		<td>
			Email Id
		</td>
		<td>
			<input type="text" id="emailid" name="emailid">
		</td>
	</tr>
	<tr id="r12">
		<td>
			Website
		</td>
		<td>
			<input type="text" id="website" name="website">
		</td>
	</tr>
	<tr id="r13">
		<td>
			Remarks
		</td>
		<td>
			<input type="text" id="remarks" name="remarks">
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
        <tr id="contactTr">
                <td>
			
		</td>
		<td>
		<button type='button' class="add">Add New Contact</button>	
		</td>

	</tr>
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Save New Tenant</button>
		</td>
	</tr>
 
   
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Tenant</button>
		</td>
	</tr>
         <tr id="saveAndProceed">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSaveAndProceed">Save & Proceed</button>
		</td>
	</tr>
	</tbody>
</table>

<script>
		$(document).ready(function() {
//                     $("#shoptypemasid").change(function(){
//        var type = $('#shoptypemasid option:selected').text();
//         var types = jQuery.trim(type);
//        //alert(types)
//        $('#shoptypemasid').focus();
//        if(types.indexOf("Others")>= 0){
//          $('#shoptypemasid').remove();
//         // var $shoptypeparent=//$('#shoptypemasid').parent();
//          var shoptypeinput="<input type='text' id='shoptypemasid' placeholder='Enter other shop type' name='shoptypemasid'>";
//          $('#shoptypes').append(shoptypeinput);
//        }
//       
//          });
               
                  
                  
                  
                  
                     // $('select[name^=cptypemasid]')
                    //$(".thdyna").hide();
                     $("#add").hide();
			var id = 0;
			var i = 0;
			// Add button functionality
			$("#usertbl button.add").click(function() {
                           // alert($('#dynTable select[name^=cptypemasid]').attr('name'))
                            //alert($('#cpyy').attr('name'))
                            $(".thdyna").show();
                            $("#add").show();
                            $("#tblHeaderCp").show();
                            $(".prototype").show();
                        });
                        
			$("table.dynatable button.add").click(function() {	
                                id++;
				i++;
				var master = $(this).parents("table.dynatable");
				
				// Get a new row based on the prototype row
				var prot = master.find(".prototype").clone(true);
				prot.find("input[type='text']").each(function() {
					var a = $(this).attr('name');
					$(this).attr('name',a+i);
					$(this).attr('value',a+i);
				});
				prot.find("input[type='radio']").each(function() {
					$(this).attr('checked',true);
				});
				prot.find("select").each(function() {
					var a = $(this).attr('name');
					$(this).attr('name',a+i);
				});
				prot.attr("class", "")
				////prot.find(".id").html(id);
				
				master.find("tbody").append(prot);
			});
			
			// Remove button functionality
			$("table.dynatable button.remove").live("click", function() {
				$(this).parents("tr").remove();
//                                if($('table.dynatable tr').length==0){
//                                 $(".thdyna").hide();  
//                                }
				//length =  $("table.dynatable tr").not('tr:first').length-1;
			});
			//return false;
		
//              $('#dynTable select[name^=cptypemasid]').on('change','select', function () {
//                  alert('helo'); 
//              });
              
              $('#dynTable select[name^=cptypemasid]').live('change','select',function(){
                  //alert('huu')
              
                var optionSelected = $("option:selected", this);
                 var valueSelected = optionSelected.text();
                // alert(valueSelected);
                if(valueSelected=="Other"){
                  //alert(valueSelected+" ino");  
                  $('#cpyy').remove();
         // var $shoptypeparent=//$('#shoptypemasid').parent();
              var otherinput="<input type='text' id='cpyy' placeholder='Enter other designation' name='othercptypemasid'>";
              $('#cpy').append(otherinput);
                }


        });

});

                
                
		</script>
		<style>
			.dynatable {
				border: solid 1px #ffffff; 
				border-collapse: collapse;
			}
			.dynatable th,
			.dynatable td {
				border: solid 1px #ffffff; 
				padding: 2px 2px;
				width: 100%;
				text-align: center;
			}
			.dynatable .prototype, #tblHeaderCp {
				display:none;
			}
		</style>
<table id="dynTable" class="dynatable">
		<!--<thead>-->
				<tr>
					<th id='tblHeaderCp' colspan=8></th>
				</tr>
			<!--</thead>-->
			<!--<thead>-->
				<tr>
					<th class="thdyna">Attn:</th>
					<th class="thdyna">Name</th>
					<th class="thdyna">Designation</th>
					<th class="thdyna">National Id</th>
					<th class="thdyna">Mobile</th>
					<th class="thdyna">LandLine</th>
					<th class="thdyna">Email</th>
					<th id="add"><button type='button' class="add">Add</button></th>
				</tr>
			<!--</thead>-->
			<tbody id='cprowbody'>
			</tbody>
		</table>

</div>

<!--</div> <!--Main Div-->
<label id="cc"></label>
</form>
<!--  <div id="dialog-form" title="Add Designation">
  <form>
    <fieldset>
      <label for="name">Salutation/Designation</label>
      <input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all">
       Allow form submission with keyboard without duplicating the dialog button 
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>-->
</body>
</html>
