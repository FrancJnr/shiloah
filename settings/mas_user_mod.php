<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>User Module</title>
        <!--<link rel="stylesheet" type="text/css" href="../styles.css">-->
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');    
    function loadmodule()
    {        
	$sql= "select modulemasid,modulename from mas_module;";
	$result = mysql_query($sql);
        if($result != null)
        {
	    while($row = mysql_fetch_assoc($result))
            {                
		echo("<option value=".$row['modulemasid'].">".$row['modulename'].")</option>");		
            }
        }
    }
?>
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
//
////$(document).ready(function(){
//   $("table").fixMe();
//   $(".up").click(function() {
//      $('html, body').animate({
//      scrollTop: 0
//   }, 2000);
// });
    $("#dataManipDiv").hide();
    oTable = $('#example').dataTable({
	    "bJQueryUI": true,
	    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	    "sPaginationType": "full_numbers"			
    });    
//    oTable = $('#example').dataTable({
//		"bJQueryUI": true,			
//		"sPaginationType": "full_numbers"			
//	});
    $('#btnNew').click(function(){
        $("#tblheader").css('background-color', '#fc9');
	$("#tblheader").text("Tenant Discharge");
        $("#exampleDiv").hide();
	$("#dataManipDiv").show();	
	//$("#companyname")[0].focus();
	$('input[type=text]').val('');
	$('input[type=select]').val('');

    });
    $('[id^="modulemasid"]').live('change', function() {
        var  x = $(this).val();
	var y  = $('#hidusermasid').val();
        if(x==0)
            $('[id^="tblusermodule"]').html("");
        else
        {            
            var url="load_user_mod.php?item=usermoduledetails&itemval="+x+"&usermasid="+y;        
            $.getJSON(url,function(data){
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {                                        
                        $("#tblusermodule").html("");
                        $('#cc').html("");												                    
                        $("#tblusermodule").html(response.msg);                        
                    }
                    else
                    {
                        $('#cc').html(response.s);												
                    }
                });             
            });
        }
    });
   $('[id^="btnEdit"]').live('click', function() {	        
        $("#exampleDiv").hide();
        $("#dataManipDiv").show();		
        var $usermasid = $(this).attr('val');
        //clearForm();        
        if($usermasid !="")
        {
            module($usermasid)
        }		
    });
    function module(x)
    {
        var url="load_user_mod.php?item=module&itemval="+x;        
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                                        
                    $("#dataManipDiv").empty();
                    $('#cc').html("");												                    
                    $("#dataManipDiv").html(response.msg);                        
                }
                else
                {
                    $('#cc').html(response.s);												
                }
            });             
        });
    }    
    $('#btnView').click(function(){
	$('form').submit();
    });
    $('[id^="select_all"]').live('change', function() {
        var checkboxes = $(this).closest('form').find(':checkbox');
        if($(this).is(':checked')) {
            checkboxes.attr('checked', 'checked');
        } else {
            checkboxes.removeAttr('checked');
        }
    });
   $('[id^="btnUpdate"]').live('click', function() {
        var a = confirm("Can you confirm this ?");
	if (a== true)
	{
            var url="save_user_mod.php?action=Update";
            var dataToBeSent = $("form").serialize();            
            $.getJSON(url,dataToBeSent, function(data){
                $.each(data.error, function(i,response){		
                    if(response.s =="Success")
                    {					
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
});
</script>
<link href="../report_rent/style_progress.css" rel="stylesheet" type="text/css" /> 
</head>
<body id="dt_example" style="width: 90%">
<form id="myForm" name="myForm" action="" method="GET">
<!--<div id="container">-->
<center><h1>User Module</h1></center><br>
<div id="menuDiv" width="100%" align="right">
    <button class="buttonView" type="button" id="btnView"> View </button>
</div>

<br>
<div id="exampleDiv" width="100%">
    <table cellpadding="0" cellspacing="0" border="0" id="example" width="100%" style="font-size: 11px;">
    <thead>
            <tr>
                <th>Index</th>							
                <th>Employee</th>
		<th>Username</th>
                <th>Password</th>
                <th>Active</th>		
		<th>Edit</th>		    
            </tr>
    </thead>
    <tbody id="tbodyContent">
	<?php
/*             $sql="select a.usermasid,a.username, a.password as pwd,a.active,b.empname from mas_user a
                    inner join mas_employee b on b.empmasid =  a.empmasid;"; */
					
					$sql="select usermasid,username, password as pwd, active,username as empname from mas_user;";
            $result = mysql_query($sql);
            if($result != null)
            {
                $i=1;
                while($row = mysql_fetch_assoc($result))
                {
                    $empname = $row['empname'];
                    $usermasid =  $row['usermasid'];
                    $username = $row['username'];
                    $pwd = $row['pwd'];
                    $active = $row["active"];
                    if($active == 1)                    
                        $active = "active";
                    else                    
                        $active = "disabled";
                    $tr =  "<tr>
                    <td class='center'>".$i++."</td>
                    <td>".$empname."</td>
                    <td>".$username."</td>
                    <td>".$pwd."</td>                    
                    <td>".$active."</td>";
                    $tr .="<td><button type='button' id=btnEdit$i name='".$usermasid."'  val='".$usermasid."'>Edit</button>								</td>";
                    echo $tr;
                }
            }
        ?>
    </tbody>
    <tfoot>
            <tr>
                <th>Index</th>							
                <th>Employee</th>
		<th>Username</th>
                <th>Password</th>
                <th>Active</th>		
		<th>Edit</th>		    
            </tr>
    </tfoot>
</table>
</div>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<div id="dataManipDiv">

</div>
<!--</div> Main Div-->
</form>
</body>
</html>
