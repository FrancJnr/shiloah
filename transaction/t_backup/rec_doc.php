<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>RECTIFICATION OF DOC</title>
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
    oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    })
    $("#doc").datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat:"dd-mm-yy"
    })
    $('[id^="btnEdit"]').live('click', function() {
        $("#exampleDiv").hide();
	$("#dataManipDiv").show();
        var $a = $(this).attr('name');
        var url="load_rec_doc.php?item=tenantdetails&tenantmasid="+$(this).attr('val');
        var dataToBeSent = $("form").serialize();
        $.getJSON(url,dataToBeSent, function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                    
                    $.each(data.myResult, function(i,response){
                        var tenant =response.leasename
                        if(response.tradingname !="")
                            tenant +=" (T/A) "+response.tradingname;
                        var shop = response.shopcode;
                        var building = response.buildingname;
                        var doc = response.doc;
                        var sqrft = response.size;
                        var tenantdetails = tenant+"</br>"+shop+"</br>"+building+"</br> Sqrft: "+sqrft;			
			$('#tenantdetails').html(tenantdetails);
                        $('#doc').val(doc);
                    });                    
                }				
                else
                {
                    $('#cc').html(response.msg);
                }                        
            });             
        });
        var url="load_rec_doc.php?item=offerletterdetails&tenantmasid="+$(this).attr('val');
        var dataToBeSent = $("form").serialize();
        $.getJSON(url,dataToBeSent, function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                                        
                    $('#offerletterdetails').html(response.msg);
                    
                }				
                else
                {
                    $('#cc').html(response.msg);
                }                        
            });             
        });        
    })
   $('[id^="btnChange"]').live('click', function() {
        var doc= $('#doc').val();        
        var $c=0
        $(".rent").each(function() {
            $c++;			    
        });
        var url="load_rec_doc.php?item=changedoc&doc="+$(this).attr('val')+"&rowcnt="+$c;
        var dataToBeSent = $("form").serialize();
        $.getJSON(url,dataToBeSent, function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                                        
                    $('#cc').html(response.msg);
                    $i=0;
                    for($i=1;$i<=$c;$i++)
                    {            
                        var fromdate_rent = 'rent_fromdate'+$i;
                        var fromdate_sc = 'sc_fromdate'+$i;  
                        var rf = 'rowfrmdt'+$i;                        
                        $('.'+rf).each(function() {
                            var value = $(this).text();
                            $('#'+fromdate_rent).val(value);
                            $('#'+fromdate_sc).val(value);
                        });
                        
                        var todate_rent = 'rent_todate'+$i;
                        var todate_sc = 'sc_todate'+$i;  
                        var rt = 'rowtodt'+$i;                        
                        $('.'+rt).each(function() {
                            var value = $(this).text();
                            $('#'+todate_rent).val(value);
                            $('#'+todate_sc).val(value);                            
                        });
                    }
                    $('#cc').html("");
                }                
                else
                {
                    $('#cc').html(response.msg);
                }                        
            });             
        });           
    })   
    $('[id^="btnSave"]').live('click', function() {
        var r=confirm("Can you confirm this?");
	if (r == true)
	{            
            var url="save_rec_doc.php?action=Save";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){
                $.each(data.error, function(i,response){
                    if(response.s =="Success")
                    {							
                        $('#cc').html(response.msg);                        
                    }
                    else
                    {							
                        $('#cc').html(response.msg);
                    }												
                });
            });
        }
    })
    $('#btnView').click(function(){
	$('form').submit();
    })
})

</script>		
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
    <h1> RECTIFICATION OF DOC</h1>
    <div id="menuDiv" width="100%" align="right">
        <table>
            <tr>
                <td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>                
                <td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
            </tr>
        </table>
    </div>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <br>
    <div id="exampleDiv">
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
        <thead>
            <tr>
                <th>Index</th>                
                <th>Tenant</th>                
                <th>Shop</th>
                <th>Building</th>
                <th>Doc</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody id="tbodyContent">
            <?php
                $sql =" select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,c.buildingname,date_format(a.doc,'%d-%m-%Y') as doc from mas_tenant a
                        inner join mas_shop b on b.shopmasid = a.shopmasid
                        inner join mas_building c on c.buildingmasid = b.buildingmasid
                        where a.active ='1'
                        union
                        select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,c.buildingname,date_format(a.doc,'%d-%m-%Y') as doc from rec_tenant a
                        inner join mas_shop b on b.shopmasid = a.shopmasid
                        inner join mas_building c on c.buildingmasid = b.buildingmasid
                        where a.active ='1';";
                $result=mysql_query($sql);
                if($result != null) // if $result <> false
                {
                    if (mysql_num_rows($result) > 0)
                    {
                        $i=1;$tradingname="";
                        while ($row = mysql_fetch_assoc($result))
                        {
                            $tenantmasid = $row["tenantmasid"];
                            $tenant =$row["leasename"];
                            
                            if($row["tradingname"] !="")
                            $tenant .="  (T/A)  ".$row["tradingname"];
                            $premisis = $row["shopcode"];
                            $building = $row["buildingname"];
                            $doc = $row["doc"];
                            
                            $tr =  "<tr>
                                    <td class='center'>".$i++."</td>
                                    <td>".$tenant."</td>
                                    <td>".$premisis."</td>
                                    <td>".$building."</td>
                                    <td>".$doc."</td>
                                    <td>
                                        <button type='button' id=btnEdit$i name='".$i."'  val='".$tenantmasid."'>Edit</button>
                                    </td>";
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
                    <th>Shop</th>
                    <th>Building</th>
                    <th>Doc</th>
                    <th>Edit</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div id="dataManipDiv">
        <table class="table6">
            <thead>
                <tr>
                    <th colspan=2>
                        RECTIFICATION OF DOC
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tenant</td>                    
                    <td id='tenantdetails'></td>                    
                </tr>
                <tr>
                    <td>Doc</td>                    
                    <td>
                        <input type='text' id='doc' name='doc' style='width:90px;' readonly/>
                        <button type="button" id="btnChange">Change DOC</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" id="btnSave">Save</button>
                    </td>                    
                </tr>                
                <tr><td colspan='2'>OFFERLETTER DETAILS</td></tr>
                <tr>
                    <td colspan='2' id='offerletterdetails'>                        
                    </td>
                </tr>                
            </tbody>
        </table>
    </div>
</div> <!--Main Div-->
</form>
</body>
</html>
