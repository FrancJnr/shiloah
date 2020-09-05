<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Group Offer Letter</title>
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
    loadTenantDraft("loadTenantDraft");
    $('[id^="offerlettertype"]').live('click', function() {
        $('#grouptenant').empty();
        var a = $('input[name=offerlettertype]:checked').val();
        if(a == "Draft")
        {
            loadTenantDraft("loadTenantDraft");	    
        }
        else
        {
            loadTenantDraft("loadTenantFinalized");	    
        }
    });
    function loadTenantDraft(itemtype)
    {
        var url="load_offerletter.php?item="+itemtype;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#tenantmasid').empty();
                    $('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
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
                        //var a = response.leasename+" ("+response.shopcode+")";
                        $('#tenantmasid').append( new Option(a,response.tenantmasid,true,false) );			
                    });
                }
                else
                {
                    $('#tenantmasid').empty();
                    $('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
                    //alert(response.s);
                }
            });		
        });
	loadgrouptenant("loadgrouptenant");
    }
     function loadgrouptenant(itemtype)
    {
        var url="load_offerletter.php?item="+itemtype;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#grouptenantmasid').empty();
                    $('#grouptenantmasid').append( new Option("-----Select Group Tenant-----","",true,false) );
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
                            //var a = response.leasename+" ("+response.shopcode+")";
                            $('#grouptenantmasid').append( new Option(a,response.grouptenantmasid,true,false) );
                    });
                }
                else
                {
                    $('#grouptenantmasid').empty();
                    $('#grouptenantmasid').append( new Option("-----Select Group Tenant-----","",true,false) );
                   // alert(response.s);
                }
            });		
        });
    }
    $('[id^="tenantmasid"]').live('change', function() {	
        $('#grouptenant').empty();
        $('#divContent').empty();
        var str= $('#tenantmasid option:selected').text();		
        var temp = new Array();
        temp = str.split("-"); //split -
        temp = temp[1].split(")"); //split ')'
	var len = temp[0].length;
	if(len >2) // if extra letters after building shortname
	{
		temp = temp[0].split(" "); //split ')'
	}
	////alert(temp[0]);
        //temp[0]; // building shortname from lease name and tenant code
        var url="load_offerletter.php?item=grouptenant&itemval="+$(this).val()+"&buildingshortname="+temp[0];
	//alert(url);
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
		$('#grouptenant').html(response.msg) // if error anything misplaced like building, block 
                $.each(data.myResult, function(i,response){		    
                    $('#grouptenant').append(response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.tenantmasid+")<input type='checkbox' name='tenantmasid"+response.tenantmasid+"' value='"+response.tenantmasid+"'><br><br>");		    
                });
            });		
        });
    });
    
    $('#btnGroup').click(function(){
	if($("#tenantmasid option:selected").val()== "")
	{
	    alert("Please select tenant");return false;
	}
	var k = $(":checkbox").filter(":checked").size(); //find no of checked checkbox
	if (k >0)
	{
            var r=confirm("Can you confirm this?");
            if (r == true)
            {
                var url="save_offerletter.php?action=group";
		var dataToBeSent = $("#form1").serialize();
		$.getJSON(url,dataToBeSent, function(data){
                    $.each(data.error, function(i,response){
			if(response.s == "Success")
                        {
                            $('#divContent').html(response.divContent);
			    $('#grouptenant').empty(); loadTenantDraft("loadTenantDraft");
			    $('#grouptenantlist').empty();loadgrouptenant("loadgrouptenant");
                        }	
                        else
                        {
                            $('#divContent').html(response.msg);
                        }
                    });
                });
            }
        }
        else
        {
	    alert("Please select a tenant checkbox.");		
        }
    });
    
    $('#btnGroupAndProceed').click(function(){
	if($("#tenantmasid option:selected").val()== "")
	{
	    alert("Please select tenant");return false;
	}
	var k = $(":checkbox").filter(":checked").size(); //find no of checked checkbox
	if (k >0)
	{
            var r=confirm("Can you confirm this?");
            if (r == true)
            {
                var url="save_offerletter.php?action=group";
		var dataToBeSent = $("#form1").serialize();
		$.getJSON(url,dataToBeSent, function(data){
                    $.each(data.error, function(i,response){
			if(response.s == "Success")
                        {
                            $('#divContent').html(response.divContent);
			    $('#grouptenant').empty(); loadTenantDraft("loadTenantDraft");
			    $('#grouptenantlist').empty();loadgrouptenant("loadgrouptenant");
                        
                    if(response.msg=="Data Grouped Successfully"){  
                                        var a = confirm("Tenant Grouped Successfully\nWould You Like to Proceed to Print Offer Letter? ");
                                            if (a== true)
                                            {
                                              parent.top.$('div[name=masterdivtest]').html("<iframe  src='reports-pms/print_offerletter.php?action=new' id='the_iframe2' scrolling='yes' width='100%'></iframe>");   
                                            }else{
                                              $('#divContent').html(response.divContent);
                                            $('#grouptenant').empty(); loadTenantDraft("loadTenantDraft");
                                            $('#grouptenantlist').empty();loadgrouptenant("loadgrouptenant");
                                            }
                          
                                        }else{
                                          
                                          return
                                      }
                
            
        
    
                         }	
                        else
                        {
                            $('#divContent').html(response.msg);
                        }
                    });
                });
            }
        }
        else
        {
	    alert("Please select a tenant checkbox.");		
        }
    });
     $('[id^="grouptenantmasid"]').live('change', function() {
        $('#grouptenantlist').empty();
        $('#divContent').empty();
        var url="load_offerletter.php?item=grouptenantlist&itemval="+$(this).val();	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                $.each(data.myResult, function(i,response){                    
		    $('#grouptenantlist').append(response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.active+")<input type='checkbox' name='tenantmasid"+response.tenantmasid+"' value='"+response.tenantmasid+"'><br><br>");
                });
            });		
        });
    });	
     $('#btnUnGroup').click(function(){
	if($("#grouptenantmasid option:selected").val()== "")
	{
	    alert("Please select Grouped Tenant");return false;
	}
	var r=confirm("Can you confirm this?");
	if (r == true)
	{
	    var url="save_offerletter.php?action=ungroup";
	    var dataToBeSent = $("#form2").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#divContent').html(response.divContent);
			$('#grouptenant').empty(); loadTenantDraft("loadTenantDraft");
			$('#grouptenantlist').empty();loadgrouptenant("loadgrouptenant");
		    }	
		    else
		    {
			$('#divContent').html(response.msg);
		    }
		});
	    });
	}
    });
});
</script>
</head>
<body id="dt_example">
<div id="container">
	<br>
<h1>Group Letter Of Offer</h1>
<br>
<form id="form1" name="form1" action="#" method="post">
<table id="usertbl" class="table2" width="80%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Select Tenant
			</th>
		</tr>
	</thead>
	<tbody>
	 <tr  align='center'>
		<td colspan=2>
			Draft<input type="radio" id="offerlettertype" name="offerlettertype" value="Draft" checked/> |
			Finalized<input type="radio" id="offerlettertype" name="offerlettertype" value="Finalized " /> 
		</td>
	</tr>
	<tr>
		<td>
			Tenant
		</td>
		<td>
			<select id="tenantmasid" name="tenantmasid" style='width:525px;'>
				<option value="" selected>----Select Tenant----</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Select Tenant(s)
		</td>
		<td id='grouptenant'>			
		</td>
	</tr>
	<tr>
		<td>
			
		</td>
                <td>
			<button type="button" id="btnGroup">Group</button><button type="button" id="btnGroupAndProceed">Group And Proceed</button>
		</td>
	</tr>
</table>
</form>
<form id="form2" name="form2" action="#" method="post">
<table id="usertbl" class="table2" width="80%">
	<tr>
		<td>
			Group Tenant
		</td>
		<td>
			<select id="grouptenantmasid" name="grouptenantmasid" style='width:525px;'>
				<option value="" selected>----Select Group Tenant----</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Select Group Tenant(s)
		</td>
		<td id='grouptenantlist'>			
		</td>
	</tr>
<!--	<tr>
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUnGroup">Un-Group</button>
		</td>
	</tr>	-->
	
	</tbody>
</table>
</form>
</div>
 <!--Main Div-->
<div id="divContent">
	
</div>
</body>
</html>