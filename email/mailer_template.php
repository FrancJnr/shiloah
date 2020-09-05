<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Tenant Discharge</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
   $companymasid = $_SESSION['mycompanymasid'];	
   
     function loadEnquiryList()
    {
        $sql = "select companyname, enquirymasid from mas_enquiry_updated order by companyname desc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['enquirymasid'].">".$row['companyname']."</option>");		
                }
        }
    }
      function loadTenantList()
    {
        $sql = "select leasename, tenantmasid from mas_tenant where companymasid='1' order by leasename desc ";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['tenantmasid'].">".$row['leasename']."</option>");		
                }
        }
    }
   ?>
        <script type="text/javascript" language="javascript">
        
         $(document).ready(function() { 
             
            var action = <?php echo(json_encode($_GET['action'])); ?>;
            //var contact=<?php //echo(json_encode($_GET['contact'])); ?>;
            var emailid=<?php echo(json_encode($_GET['emailid'])); ?>;
             if(action=='contacting'){
                 
                 $('#txtSender').val("Enquiry Contact");
                 $("#txtEmail").val(emailid);
                 $("#txtSubject").val("Enquiry Update");
             
         }
             
             
//                $("input[type='radio']").each(function() {
//			
//                        $(this).attr('checked',true).val();
//		
//            
//            });
	//$("input[type='radio']")
//        if($("input[type='radio']").attr('checked',true).val()== "1")
//		{
//			//$('#enquiry').hide();
//		}else{
//                    
//                       // $('#tenancy').hide();
//                }
//         
           $("#tenantmasid").change(function(){
              // alert($(this).text());
              var conceptName = $(this).find(":selected").text();
              //alert(conceptName);
	      $('#txtSender').val(conceptName);
                
           });
            $("#tenantmasids").change(function(){
              // alert($(this).text());
              var conceptName = $(this).find(":selected").text();
              //alert(conceptName);
		$('#txtSender').val(conceptName);
                
           });
          $('input[type=radio][name=tenant]').change(function() {
                if (this.value == '1') {
                     $('#enquiry').hide();
                     $('#tenancy').show();
                }
                else if (this.value == '0') {
                     $('#tenancy').hide();
                     $('#enquiry').show();
                }
            });
	  $('#btnSend').click(function(){
		if(jQuery.trim($("#tenantmasid").val()) == "")
		{
			alert("Please select Tenant Contact");
                        return false;
		}
                if(jQuery.trim($("#txtEmail").val()) == "")
		{
			alert("Please Enter Email Address");
                        return false;
		}
		 if(jQuery.trim($("#txtMessage").val()) == "")
		{
			alert("Please Enter Mail Message");
                        return false;
		}
                
             
		var a = confirm("Please make sure you filled all details");
		if (a== true)
		{
                  <?php 
                  
                  
                  ?>  
                    
                    
	
		}
	});
         
     
 

       });

        </script>
</head>
<body>
<div style="color:#000;margin-top:20px;margin-left:auto;margin-right:auto;max-width:800px;background-color:#F4F4F4">
  <table style="font-family: Helvetica Neue,Helvetica,Arial, sans-serif; font-size:13px;background: #F4F4F4; width: 100%; border: 4px solid #bbbbbb;" cellpadding="10" cellspacing="5">
    <tbody>
      <tr>
        <th style="background-color:#ccc; font-size:16px;padding:5px;border-bottom:2px solid #fff;"> <img src='../images/megaicon.png' height='150' width='150'/></th>
      </tr>
      <tr>
        <td style="text-align: left;background-color:#F4F4F4" valign="top">Dear <b><input name="txtSender" id="txtSender" type="text"  value="" onfocus="this.value=''" /></b><br />
          <br />
          <!--<textarea id="txtMessage" type="text" name="txtMessage" cols="55" rows="5"></textarea></td>-->
      </tr>
      <tr>
        <td style="text-align: left;background-color:#F4F4F4" valign="top"><b>Contact Summary:</b><br />
          <br />
          <table style="font-family: Helvetica Neue,Helvetica,Arial, sans-serif; font-size:13px;" border="0" cellpadding="5" cellspacing="2" width="100%">
            <tbody>
                 <tr>
                 <div class="inline-group">
                    <label class="radio">
                      <input type="radio" name="tenant" value="1">
                      <i></i>Existing Tenant</label>
                    <label class="radio">
                      <input type="radio" name="tenant" value="0">
                      <i></i>Enquiry Tenant</label>
                </div>
                 </tr>
              <tr>
                <td style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;" width="150">Client Name:</td>
                <td id="tenancy" style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;">
                        <select id="tenantmasid" name="tenantmasid">
				<option value="" selected>----Select Tenant Contact----</option>
                                <?php  loadTenantList();?>
			</select></td>
                <td id="enquiry" style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;">
                        <select id="tenantmasids" name="tenantmasids">
				<option value="" selected>----Select Enquiry Contact----</option>
                                <?php loadEnquiryList();?>
			</select></td>        
              </tr>
              <tr>
                <td style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;">Client Email:</td>
                <td style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;"><input style="padding-left:135px;" placeholder="Enter Email Address" name="txtEmail" id="txtEmail" type="email"  value="" onfocus="this.value=''" /></td>
              </tr>
              <tr>
                <td style="text-align: center; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;">Email Subject:</td>
                <td style="text-align: center; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;"><input name="txtSubject" id="txtSubject" type="text"  value="" onfocus="this.value=''" /></td>
              </tr>
              <tr>
                <td style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;">Message:</td>
                <td style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;"><textarea id="txtMessage" type="text" name="txtMessage" cols="55" rows="5"></textarea></td>
                <td style="text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;"><button class="buttonView" type="button" id="btnSend"> Send Mail </button></td>
              
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td style="text-align: center; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;font-size:12px" valign="top"> This email is sent to you directly from <a href="[SITEURL]"><?php echo $_SESSION['mycompany']; ?></a><br />
          &copy;<?php echo date('Y'); ?><a href="[SITEURL]"><?php echo $_SESSION['mycompany']; ?></a>. All rights reserved.</td>
      </tr>
    </tbody>
  </table>
</div>
    
    
</body>
</html>