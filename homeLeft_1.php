<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>home left</title>
	<!-- Include style css files and jquery json files info. -->
	<?php
		include('MasterRef.php');
		include('config.php');
	?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#menuDiv").hide();
		$("#slectModule").change(function (){
			//alert($("#slectModule option:selected").val());
			if($("#slectModule option:selected").val() !="")
			{
				$("#menuDiv").show();
			}
			else
			{
				$("#menuDiv").hide();
			}
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
</head>
 <title>Dynamic tabs with jQuery - why and how to create them | JankoAtWarpSpeed Demos</title>
    <style type="text/css">
        body { font-family:Lucida Sans, Lucida Sans Unicode, Arial, Sans-Serif; font-size:13px; margin:0px auto;}
        #tabs { margin:0; padding:0; list-style:none; overflow:hidden; }
        #tabs li { float:left; display:block; padding:5px; background-color:#bbb; margin-right:5px;}
        #tabs li a { color:#fff; text-decoration:none; }
        #tabs li.current { background-color:#eae8e1;}
        #tabs li.current a { color:#000; text-decoration:none; }
        #tabs li a.remove { color:#f00; margin-left:10px;}
        #newTabContent { background-color:#eae8e1;}
        #newTabContent p { margin: 0; padding:20px 20px 100px 20px;}
        
        #main { width:900px; margin:0px auto; overflow:hidden;background-color:#F6F6F6; margin-top:20px;
             -moz-border-radius:10px;  -webkit-border-radius:10px; padding:30px;}
        #wrapper, #doclist { float:left; margin:0 20px 0 0;}
        #doclist { width:250px; border-right:solid 1px #dcdcdc;}
        #doclist ul { margin:0; list-style:none;}
        #doclist li { margin:10px 0; padding:0;}
        #documents { margin:0; padding:0;}
        
        #wrapper {width:950px; margin-top:20px;}
iframe {
	 width: 100%;
	 height: 100%;
  margin-top: 20px;
  margin-bottom: 30px;

  -moz-border-radius: 12px;
  -webkit-border-radius: 12px;
  border-radius: 12px;

  -moz-box-shadow: 4px 4px 14px #000;
  -webkit-box-shadow: 4px 4px 14px #000;
  box-shadow: 4px 4px 14px #000;
  filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=.2);
} 
    </style>
    
    <script type="text/javascript" src="jquery/jquery-1.7.1.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
	
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
                "</a><a href='#' class='remove'>x</a></li>");
	    var $path = $(link).attr("value");
            $("#newTabContent").append("<p id='" + $(link).attr("rel") + "_newTabContent'> <iframe   src='"+ $path+"' /> </p> ");
            
            // set the newly added tab as current
            $("#" + $(link).attr("rel") + "_newTabContent").show();
        }
    </script>
<body>
<div id="doclist">
	Module : <select id="slectModule">
<option value="" selected>--select module--</option>
<?php
	$sql = "select * from mas_module";
	$result=mysql_query($sql);
	if ($result != null)
	{
		$cnt = mysql_num_rows($result);
		if($cnt > 0) // if $result <> null
		{
			while ($row = mysql_fetch_array($result))
			{
				echo ("<option value=".$row["modulemasid"].">".$row["modulename"]."</option>");
			}
		}
	}
?>	
</select>
<br><br>
        <h2>Documents</h2>
        <ul id="documents">
            <li><a href="#" rel="Document1" value="masters/mas_currency.php" title="masters/mas_currency.php">Currency</a></li>
            <li><a href="#" rel="Document2" value="masters/mas_age.php" title="This is the newTabContent of Document2">Age</a></li>
            <li><a href="#" rel="Document3" title="This is the newTabContent of Document3">Document3</a></li>
            <li><a href="#" rel="Document4" title="This is the newTabContent of Document4">Document4</a></li>
            <li><a href="#" rel="Document5" title="This is the newTabContent of Document5">Document5</a></li>
        </ul>
	<div class="demo" id="menuDiv">
	<div id="accordion">
	    <h3><a href="#">Excel Upload</a></h3>
		<div id="excelUpload">
			<table cellpadding=2 cellspacing=2>
				<tr><td><a id="E1" target="content" href="excel/downloadExcel.php">Download Excel</a></td></tr>
				<tr><td><a id="E2" target="content" href="excel/uploadExcel.php">Upload Excel</a></td></tr>
			</table>
		</div>
		<h3><a href="#">Masters</a></h3>
		<div id="masters">
			<table cellpadding=2 cellspacing=2>
				<tr><td><a id="M1" target="content" value="Currency" href="masters/mas_currency.php">Currency</a></td></tr>
				<tr><td><a id="M2" target="content" href="masters/mas_age.php">Age</a><br></td></tr>
				<tr><td><a id="M3" target="content" href="masters/mas_company.php">Company</a><br></td></tr>
				<tr><td><a id="M4" target="content" href="masters/mas_acyear.php">Accounting Year</a><br></td></tr>
				<tr><td><a id="M5" target="content" href="masters/mas_building.php">Building</a><br></td></tr>
				<tr><td><a id="M6" target="content" href="masters/mas_block.php">Block</a><br></td></tr>
				<tr><td><a id="M7" target="content" href="masters/mas_floor.php">Floor</a><br></td></tr>
				<tr><td><a id="M7" target="content" href="masters/mas_shoptype.php">Shop Type</a><br></td></tr>
				<tr><td><a id="M8" target="content" href="masters/mas_shop.php">Shop</a><br></td></tr>
				<tr><td><a id="M9" target="content" href="masters/mas_orgtype.php">Org Type</a><br></td></tr>
				<tr><td><a id="M10" target="content" href="masters/mas_tenant.php">Tenant</a><br></td></tr>
			</table>
		</div>
		<h3><a href="#">Transactions</a></h3>
		<div id="transactions">
			<table cellpadding=2 cellspacing=2>
				<tr><td><a id="T1" target="content" href="transaction/trans_offerletter.php">Offer Letter</a></td></tr>
			</table>
		</div>
		<h3><a href="#">Reports</a></h3>
		<div id="reports">
		</div>
		<h3><a href="#">User</a></h3>
		<div id="user">
			<table cellpadding=2 cellspacing=2>
				<tr><td><a id="U1" target="content" href="user/user.php">User</a></td></tr>
				<tr><td><a id="U2" target="content" href="user/usercompany.php">Company Allocation</a></td></tr>
				<tr><td><a id="U3" target="content" href="user/usermodule.php">Module Allocation</a></td></tr>
			</table>
		</div>
	</div>

</div>
    </div>
    <div id="wrapper">
        <ul id="tabs">
            <!-- Tabs go here -->
        </ul>
        <div id="newTabContent">
            <!-- Tab newTabContent goes here -->
        </div>
<?php

?>

</body>
</html>
