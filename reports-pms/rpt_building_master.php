<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>BUILDING MASTER REPORT</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    
	
    function loadBuilding()
    {
        $companymasid = $_SESSION['mycompanymasid'];
		$sql = "select buildingmasid, buildingname from mas_building WHERE companymasid =".$companymasid;
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
<style type="text/css" media="screen">
@import "../css/Site.css";
@import "../css/TableTools.css";
div.dataTables_wrapper { font-size: 13px; }
table.display thead th, table.display td { font-size: 13px; }
.hover {
    background-color: #ffffa6;
    font-size: 10px;
    font-weight: 100;
}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#buildingmasid')[0].focus()
    $('#buildinglistTbl').hide();
    $('td').live('mouseover', function(){
        var i = $(this).prevAll('td').length;
        $(this).parent().addClass('hover')
        $($cols[i]).addClass('hover');
    }).live('mouseout', function(){
        var i = $(this).prevAll('td').length;
        $(this).parent().removeClass('hover');
        $($cols[i]).removeClass('hover');
    });
    $('[id^="buildingmasid"]').live('change', function() {
        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($(this).val()!="")
        {
            $('#buildingDiv').empty();
            var url="load_building_master.php";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $('#buildingDiv').append(response.divContent);
                        //$('#title').append("--<strong>"+response.tenant+"<strong>");
                    }
                    $('#buildinglistTbl').show('slow');                                
                    $('input:checkbox').attr('checked', true);
                });
            });
                
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
        }
    });
    $('.checkall').click(function () {
           $(this).parents('fieldset:eq(0)').find(':checkbox').attr('checked', this.checked);
    });	
    $('[id^="btnPrint"]').live('click', function() {
            $('.printable').print();
    });
});
</script>
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER'>BUILDING MASTER REPORT</h1>
    <br>
    Building <font color="red">*</font>
    <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
            <option value="" selected>----Select Building----</option>
            <?php loadBuilding();?>
    </select>
    <button type="button" id="btnPrint">Print</button>
    <div id='buildingDiv'>
    
    </div>
    </form>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <br>
</form>
</body>
</html>