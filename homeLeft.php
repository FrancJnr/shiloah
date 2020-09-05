<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>home left</title>
	<!-- Include style css files and jquery json files info. -->
	<?php
		include('MasterRef.php');
		include('config.php');
		session_start();
		$username = strtolower($_SESSION['myusername']);
	?>
	
<script type="text/javascript">
	$(document).ready(function(){		
		$("#slectModule").hide();
		//$("#menuDiv").hide();
		//$("#slectModule").change(function (){
		//	//alert($("#slectModule option:selected").val());
		//	if($("#slectModule option:selected").val() !="")
		//	{
		//		$("#menuDiv").show();
		//	}
		//	else
		//	{
		//		$("#menuDiv").hide();
		//	}
		//});		
		$('#menuToggle').click(function(){
			var $a = $('#menuToggle').text();
			if ($a=='Hide Menu')
			$('#menuToggle').text('Show Menu');
			else
			$('#menuToggle').text('Hide Menu');
			
			$('#menuTd').toggle();
		});
		$('[id^="M"]').live('click', function() {
			var $newLinkRef = $(this).attr("value");
			$(this).attr("target","rightFrame");
			$(this).attr("href","homeRight.php?newLink="+$newLinkRef);
			
			//
			//var $url="<a target='content' href=homeRight.php?newLink="+$newLinkRef+" />";
			//$url.trigger("click");
			//alert(url);
			//return false;
		});		
	});
</script>
 <script type="text/javascript">
        $(document).ready(function() {
		
	//var images = ['images/Mega_City_Front.jpg', 'images/Mega_Plaza_Front.jpg', 'images/Mega_City_Side.jpg'];
	//var i = 0;
	//
	//setInterval(function(){
	//    $('body').css('background-image', function() {
	//	if (i >= images.length) {
	//	    i=0;
	//	}
	//	return 'url(' + images[i++] + ')'; 
	//    });
	//}, 5000);	
	//	
	$("#documents a").click(function() {
		    addTab($(this));
	});

        $('#tabs a.tab').live('click', function() {
                // Get the tab name
                var newTabContentname = $(this).attr("id") + "_newTabContent";

                // hide all other tabs
                $("#newTabContent p").hide();
                $("#tabs li").removeClass("current");

                // show current tab
                $("#" + newTabContentname).show();
                $(this).parent().addClass("current");
        });

            $('#tabs a.remove').live('click', function() {
                // Get the tab name
                var tabid = $(this).parent().find(".tab").attr("id");

                // remove tab and related newTabContent
                var newTabContentname = tabid + "_newTabContent";
                $("#" + newTabContentname).remove();
                $(this).parent().remove();

                // if there is no current tab and if there are still tabs left, show the first one
                if ($("#tabs li.current").length == 0 && $("#tabs li").length > 0) {

                    // find the first tab    
                    var firsttab = $("#tabs li:first-child");
                    firsttab.addClass("current");

                    // get its link name and show related newTabContent
                    var firsttabid = $(firsttab).find("a.tab").attr("id");
                    $("#" + firsttabid + "_newTabContent").show();
                }
            });
        });
        function addTab(link) {
            // If tab already exist in the list, return	    
            if ($("#" + $(link).attr("rel")).length != 0)
                return;
            
            // hide other tabs
            $("#tabs li").removeClass("current");
            $("#newTabContent p").hide();
            
            // add new tab and related newTabContent
            $("#tabs").append("<li class='current'><a class='tab' id='" +
                $(link).attr("rel") + "' href='#'>" + $(link).html() + 
                "</a><a href='#' class='remove'><img src='images/delete.png'></a></li>");
	    var $path = $(link).attr("value");
            $("#newTabContent").append("<p style='border-style: dotted; border-color: red'; id='" + $(link).attr("rel") + "_newTabContent'>" +
				       
				       $(link).attr("location") +"<iframe  src='"+ $path+"' /> </p> ");
            
            // set the newly added tab as current
            $("#" + $(link).attr("rel") + "_newTabContent").show();
	    var active = $tabs.tabs('option', 'active');
        }
	
    </script>
    <style type="text/css">
        select,#tabs,#newTabContent { font-family:Lucida Sans, Lucida Sans Unicode, Arial, Sans-Serif; font-size:13px; margin:0px auto;}
        #tabs {margin:0; padding:0; list-style:none; min-height: 100%;overflow:auto;}
	#tabs li { float:left; display:block; padding:5px; background-color:#d1d1d1; margin-right:5px;font-family:Tahoma;}
        #tabs li a { color:#408080; text-decoration:none; }
        #tabs li.current { background-color:#408080;}
        #tabs li.current a { color:#ffffff; text-decoration:none;}
        #tabs li a.remove { color:#ffffff; margin-left:10px;}
        #newTabContent {color:#ffffff;background-color:#408080;}
        #newTabContent p {margin: 0; padding:10px 20px 500px 20px;}
        
        #main {width:200%; margin:10px auto; overflow:hidden;margin-top:20px;
             -moz-border-radius:10px;  -webkit-border-radius:10px; padding:10px;}
	     
        #wrapper, #doclist {float:left; margin:20px 30px 0 0;}
        #doclist { width:10%; border-right:solid 0px #dcdcdc;}
        #doclist ul { margin:0; list-style:none;}
        #doclist li { margin:10px 0; padding:0;}
        #documents { margin:0; padding:0;}
        
        #wrapper {width:100%;margin-top:20px;}
iframe {
	width:100%;	
  margin-top: 10px;
  margin-bottom: 30px;
  border: 2px red solid;
 /* -moz-border-radius: 12px;
  -webkit-border-radius: 12px;
  border-radius: 12px;

  -moz-box-shadow: 4px 4px 14px #000;
  -webkit-box-shadow: 4px 4px 14px #000;
  box-shadow: 4px 4px 14px #000;*/
  filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=.2);
}
body { 
	background:rgb(238, 238, 238);	
	/* background: url(images/M1.jpg) no-repeat center center fixed; 
	 -webkit-background-size: cover; 
	 -moz-background-size: cover; 
	 -o-background-size: cover; 
	 background-size: cover; 
	 width: 100%; 
	 height: 100%;*/
}
#accordion1 .ui-widget-content {height:400px}
#accordion2 .ui-widget-content {height:120px}
#accordion3 .ui-widget-content {height:400px}
#accordion4 .ui-widget-content {height:120px}
</style>
</head>
<body>	
<table width='100%'  id='menuTr'>
	<tr>
		<td>
			<button id='menuToggle'>Hide Menu</button>
		</td>
	</tr>
<tr>
<td valign='top' width='10%' id='menuTd'>
<div id="doclist">
<div style="width: 250px;"id="menuDiv">
	
	<div id="accordion">				  
				    <?php
					if ($username == 'admin')
					{
				    ?>
					<!--<h3><a href="#">Excel Upload</a></h3>
					<div id="excelUpload">
						<ul id="documents">
							<li><a href="#" rel="Document1E" location="Excel Upload&nbsp;&nbsp;>>&nbsp;&nbsp;Download Excel" value="excel/downloadExcel.php">Download Excel</a></li>
							<li><a href="#" rel="Document2E" location="Excel Upload&nbsp;&nbsp;>>&nbsp;&nbsp;Upload Excel" value="excel/uploadExcel.php">Upload Excel</a></li>
						</ul>
					</div>-->
				     <?php } ?>
					<h3><a href="#">Masters</a></h3>
					<div id="masters">
						<ul id="documents">
							<?php
								$search_array = array(
										      'george' => 1,
										      'wycliffe' => 2, 'phoebe' => 3,
										      'fred' => 4, 'evans' => 5,
										      'mohammed' => 6, 'typina' => 7
										      );
								if (array_key_exists($username, $search_array)) {							
							?>
							<li><a href="#" rel="Document0" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;ENQUIRY" value="masters/mas_enquiry.php">Enquiry</a></li>
							<li><a href="#" rel="Document1" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Legal" value="masters/mas_Legal.php">Legal</a></li>
							<?php } else { ?>							
							<li><a href="#" rel="Document2" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;ENQUIRY" value="masters/mas_enquiry.php">Enquiry</a></li>
							<li><a href="#" rel="Document3" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Legal" value="masters/mas_Legal.php">Legal</a></li>
							<li><a href="#" rel="Document4" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Currency" value="masters/mas_currency.php">Currency</a></li>
							<li><a href="#" rel="Document5" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Age" value="masters/mas_age.php">Age</a></li>
							<li><a href="#" rel="Document6" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Company" value="masters/mas_company.php">Company</a></li>
							<li><a href="#" rel="Document7" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;A/c Year" value="masters/mas_acyear.php">A/c Year</a></li>
							<li><a href="#" rel="Document8" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Building" value="masters/mas_building.php">Building</a></li>
							<li><a href="#" rel="Document9" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Block" value="masters/mas_block.php">Block</a></li>
							<li><a href="#" rel="Document10" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Floor" value="masters/mas_floor.php">Floor</a></li>
							<li><a href="#" rel="Document11" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Shop Type" value="masters/mas_shoptype.php">Shop type</a></li>
							<li><a href="#" rel="Document12" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Shop" value="masters/mas_shop.php">Shop</a></li>
							<li><a href="#" rel="Document13" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Org Type" value="masters/mas_orgtype.php">Org type</a></li>
							<li><a href="#" rel="Document14" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Tenant" value="masters/mas_tenant.php">Tenant</a></li>
							<li><a href="#" rel="Document15" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Daily Licence" value="masters/mas_daily_licence.php">Daily Licence</a></li>
							<li><a href="#" rel="Document16" location="Masters&nbsp;&nbsp;>>&nbsp;&nbsp;Invoice Desc" value="masters/invoice_desc.php">Invoice Desc</a></li>
						</ul>
					</div>
					<h3><a href="#">Transactions</a></h3>
					<div id="transactions">
						<ul id="documents">
							<li><a href="#" rel="Document1T" location="Transactions&nbsp;&nbsp;>>&nbsp;&nbsp;Offer Letter" value="transaction/trans_offerletter.php">Offer Letter</a></li>
							<li><a href="#" rel="Document3T" location="Transactions&nbsp;&nbsp;>>&nbsp;&nbsp;Rectification Tenant" value="transaction/rectification_tenant.php">Rectification Tenant</a></li>
							<li><a href="#" rel="Document2T" location="Transactions&nbsp;&nbsp;>>&nbsp;&nbsp;Renewal of tenant" value="transaction/renewal_tenant.php">Renewal of Tenant</a></li>
							<li><a href="#" rel="Document4T" location="Transactions&nbsp;&nbsp;>>&nbsp;&nbsp;Rectification Rent" value="transaction/rectification_rent.php" style='color: red;'>Rectification of Rent</a></li>
							
						</ul>
					</div>
					<h3><a href="#">Group</a></h3>
					<div id="docs">
						<ul id="documents">
							<li><a href="#" rel="Document1D" location="Group&nbsp;&nbsp;>>&nbsp;&nbsp;Group Offerletter" value="Group/offerletter.php">Group Offerletter</a></li>
							<li><a href="#" rel="Document2D" location="Group&nbsp;&nbsp;>>&nbsp;&nbsp;Waiting List" value="Group/waiting_list.php">Waiting List</a></li>							
						</ul>
					</div>
					<h3><a href="#">Documents</a></h3>
					<div id="documentsList">
						<ul id="documents">
							<li><a href="#" rel="Documents1DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Print Offerletter" value="reports-pms/print_offerletter.php">Print Offerletter</a></li>
							<!--<li><a href="#" rel="Documents1DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Print Offerletter" value="reports-pms/rpt_offerletter.php">Print Offerletter</a></li>-->
							<li><a href="#" rel="Documents21DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Print Lease" value="reports-pms/print_lease.php">Print Lease</a></li>
							<li><a href="#" rel="Documents3DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Rectification of Lease" value="reports-pms/rpt_rectification.php">Rectification of Lease</a></li>
							<li><a href="#" rel="Documents31DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Document Status" value="reports-pms/document_status.php" style='color: red;'>Document_Status</a></li>
							<li><a href="#" rel="Documents4DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Surrender Lease" value="reports-pms/rpt_surrender_lease.php">Surrender of Lease</a></li>
							<li><a href="#" rel="Documents5DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Simple Agreement" value="reports-pms/rpt_simple_agreement.php">Simple Agreement</a></li>
							<li><a href="#" rel="Documents6DPMS" location="Documents&nbsp;&nbsp;>>&nbsp;&nbsp;Daily Occupation- Licence" value="reports-pms/rpt_daily_licence.php">Daily Occupation- Licence</a></li>							
						</ul>
					</div>
					<h3><a href="#">Discharge</a></h3>
					<div id="approval">
						<ul id="documents">
							<li><a href="#" rel="Document1DIS" location="Approval&nbsp;&nbsp;>>&nbsp;&nbsp;Cancel Offerletter" value="approval/approval_offerletter.php">Cancel Offerletter</a></li>
							<li><a href="#" rel="Document2DIS" location="Approval&nbsp;&nbsp;>>&nbsp;&nbsp;Tenant Discharge" value="approval/tenant_discharge.php">Tenant Discharge</a></li>
						</ul>
					</div>
					<h3><a href="#">Rent</a></h3>
					<div id="approval">
						<ul id="documents">							
							<li><a href="#" rel="Document0R" location="Rent&nbsp;&nbsp;>>&nbsp;&nbsp;Monthly Schedule" value="report_rent/rpt_monthly_rental.php">Monthly Schedule</a></li>
							<li><a href="#" rel="Document1RE" location="Rent&nbsp;&nbsp;>>&nbsp;&nbsp;Advance Invoice" value="report_rent/rpt_advance_rent.php">Advance Rent</a></li>
							<li><a href="#" rel="Document2R" location="Rent&nbsp;&nbsp;>>&nbsp;&nbsp;Invoice" value="report_rent/rpt_invoice.php"><font color="red">Rental Invoice</font></a></li>
							<li><a href="#" rel="Document3R" location="Rent&nbsp;&nbsp;>>&nbsp;&nbsp;Manual Invoice" value="report_rent/rpt_manual_invoice.php">Manual</a></li>							
							<li>------Reports-----</li>
							<li><a href="#" rel="Document4R" location="Rent&nbsp;&nbsp;>>&nbsp;&nbsp;Invoiced" value="report_rent/rpt_invd.php">Invoices - Raised</a></li>
							<li><a href="#" rel="Document5R" location="Rent&nbsp;&nbsp;>>&nbsp;&nbsp;Print Invoice" value="report_rent/print_invoice.php">Print Invoice</a></li>							
						</ul>
					</div>
					<h3><a href="#">Weekly_Updates</a></h3>
					<div id="weekly">
						<ul id="documents">							
							<li><a href="#" rel="Document0WU" location="Weekly&nbsp;&nbsp;>>&nbsp;&nbsp;Pending Documents" value="weekly_updates/document_status.php">Pending Documents</a></li>
							<li><a href="#" rel="Document1WU" location="Weekly&nbsp;&nbsp;>>&nbsp;&nbsp;Tenant Expiry List" value="weekly_updates/expiry_list.php">Tenant Expiry List</a></li>
							<li><a href="#" rel="Document2WU" location="Weekly&nbsp;&nbsp;>>&nbsp;&nbsp;Tenant Waiting List" value="weekly_updates/waiting_list.php">Tenant Waiting List</a></li>
							<li><a href="#" rel="Document3WU" location="Weekly&nbsp;&nbsp;>>&nbsp;&nbsp;Enquiry List" value="weekly_updates/enquiry_list.php">Enquiry List</a></li>
							<li><a href="#" rel="Document4WU" location="Weekly&nbsp;&nbsp;>>&nbsp;&nbsp;Invoice Status" value="weekly_updates/invoice_status.php">Invoice Status</a></li>							
						</ul>
					</div>
					<h3><a href="#">Reports</a></h3>
					<div id="reports">
						<ul id="documents">							
							<li><a href="#" rel="Document1R" location="Reports&nbsp;&nbsp;>>&nbsp;&nbsp;Legal Report" value="reports-pms/rpt_legal.php">Legal Report</a></li>							
							<li><a href="#" rel="Document3R" location="Reports&nbsp;&nbsp;>>&nbsp;&nbsp;Shop Details" value="reports-pms/rpt_shop_details.php">Shop Details</a></li>
							<li><a href="#" rel="Document4R" location="Reports&nbsp;&nbsp;>>&nbsp;&nbsp;Building Details" value="reports-pms/rpt_building_details.php">Building Details</a></li>
							<li><a href="#" rel="Document5R" location="Reports&nbsp;&nbsp;>>&nbsp;&nbsp;Tenancy Master Report" value="reports-pms/rpt_tenancy_master.php">Tenancy Master Report</a></li>
							<li><a href="#" rel="Document6R" location="Reports&nbsp;&nbsp;>>&nbsp;&nbsp;Building Master Report" value="reports-pms/rpt_building_master.php">Building Master Report</a></li>
							<li><a href="#" rel="Document7R" location="Reports&nbsp;&nbsp;>>&nbsp;&nbsp;Rental Schedule" value="reports-pms/rpt_rental_schedule.php">Rental - Shopwise</a></li>							
						</ul>
					</div>
					<h3><a href="#">Service Charge</a></h3>
					<div id="approval">
						<ul id="documents">
							<li><a href="#" rel="Document1SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Expense Group" value="service_charge/mas_exp_group.php">Expense Group</a></li>
							<li><a href="#" rel="Document2SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Expense Ledger" value="service_charge/mas_exp_ledger.php">Expense Ledger</a></li>							
							<li><a href="#" rel="Document3SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Service Charge Deposit List" value="service_charge/sc_deposit.php">Service Charge Deposit</a></li>
							<li><a href="#" rel="Document4SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Expense Entry" value="service_charge/trans_exp.php">Expense Entry</a></li>
							<li><a href="#" rel="Document5SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Expense Report" value="service_charge/rpt_exp.php">Expense Report</a></li>
							<li><a href="#" rel="Document6SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Common Area" value="service_charge/mas_com_area.php">Common Area</a></li>
							<li><a href="#" rel="Document7SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Genarator Cost Apportionment" value="service_charge/trans_gen_cost.php">Gen Cost Apportionment</a></li>
							<li><a href="#" rel="Document8SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Water Cost Apportionment" value="service_charge/trans_water_cost.php">Water Cost Apportionment</a></li>
							<li><a href="#" rel="Document9SC" location="SC&nbsp;&nbsp;>>&nbsp;&nbsp;Collectable Cost" value="service_charge/rpt_cost.php">Collectable Cost</a></li>
						</ul>
					</div>
					<!--<h3><a href="#">Modules</a></h3>
					<div id="user">
						<ul id="documents">
							<li><a href="#" rel="Document0Mod" location="Modules&nbsp;&nbsp;>>&nbsp;&nbsp;Module & Files" value="modules/module.php">Module & Files</a></li>						
						</ul>
					</div>
					<h3><a href="#">User</a></h3>
					<div id="user">
						<ul id="documents">
							<li><a href="#" rel="Document0U" location="Users&nbsp;&nbsp;>>&nbsp;&nbsp;Create User Group" value="user/usergroup.php">User Group</a></li>
							<li><a href="#" rel="Document1U" location="Users&nbsp;&nbsp;>>&nbsp;&nbsp;Create User" value="user/user.php">User</a></li>
							<li><a href="#" rel="Document2U" location="Users&nbsp;&nbsp;>>&nbsp;&nbsp;User Rights" value="user/user_rights.php">User Rights</a></li>
						</ul>
					</div>-->
					<?php } ?>
				</div>
</br>
</div>
</div>

</td>
<td>
	<div id="wrapper">
        <ul id="tabs">
            <!-- Tabs go here -->
        </ul>
		<div id="newTabContent">
		    <!-- Tab newTabContent goes here -->
		</div>
	</div>
</td>
</table>
</body>
</html>
