<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Daily Invoice Report</title>
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
		$sql = "select buildingmasid, buildingname from mas_building where companymasid = '$companymasid' order by buildingname";
		$result = mysql_query($sql);
		if($result != null)
		{
			while($row = mysql_fetch_assoc($result))
			{
				//echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");
				echo($row['buildingname']."<input type='checkbox' id=".$row['buildingmasid']." name=".$row['buildingmasid']."> &nbsp;&nbsp");		
			}
		}
	}
//    function loadBuilding()
//    {
//        $sql = "select buildingmasid, buildingname from mas_building order by buildingmasid";
//        $result = mysql_query($sql);
//        if($result != null)
//        {
//                while($row = mysql_fetch_assoc($result))
//                {
//                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
//                }
//        }
//    }
    //function loadinvoicedesc()
    //{
    //    $sql = "select invoicedescmasid, invoicedesc from invoice_desc where active='1' order by invoicedesc";
    //    $result = mysql_query($sql);
    //    if($result != null)
    //    {
    //            while($row = mysql_fetch_assoc($result))
    //            {
    //                    echo("<option value=".$row['invoicedescmasid'].">".$row['invoicedesc']."</option>");		
    //            }
    //    }
    //}
    function loadinvoicedesc()
    {
        $sql = "select invoicedescmasid, invoicedesc from invoice_desc where active='1' order by invoicedesc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        $invoicedescmasid ="invoicedescmasid_".$row['invoicedescmasid'];
			$val = $row['invoicedescmasid'];			
			echo("<label><input type='checkbox' name='$invoicedescmasid' value='$val' />".$row['invoicedesc']."</label>");		
                }
        }
    }
    //TODAY'S DATE
    $start =  date("01-m-Y");    
 
     //1 MONTH FROM TODAY
    $end = date("d-m-Y",strtotime("-1 day"));
?>
<style type="text/css">
    @import "../css/print.css";	
</style>
<script type="text/javascript" src="../js/table2CSV.js"></script>
<script type="text/javascript" language="javascript">

//function getCSVData(){
//  var csv_value=$('#myTable').table2CSV({delivery:'value'});
//  $("#csv_text").val(csv_value);  
//}
</script>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $("#sub").hide();
     $("#myTable").tablesorter(); 
    $('.dtclass').datepicker({
            showOn: "button",
	    buttonImage: "../images/calendar.gif",
	    buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true,
	    dateFormat:"dd-mm-yy"
    });   
    $('#buildinglistTbl').hide();

    $('[id^="btnList"]').live('click', function() {
       
	$('#cc').html("");
       // $('#csvform').hide();
	$('#result_div').empty();
        $('#csv_text').empty();
         $('#tableholder').empty();
        $('#sub').hide();
             var buildingmasid="";
                $(':checkbox').each(function() {
		if($(this).is(':checked')) {	
			var id = $(this).attr('id')
			buildingmasid +=id+",";
		}
	       });
	var url="load_invd_itax.php";
       
	var dataToBeSent = $("form").serialize()+"&buildings="+buildingmasid;
        // alert(dataToBeSent);
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
                  //alert(data.error); 
		if(response.s == "Success")
		{
		
                   $('#result_div').html(response.result);
		}
	    });
	});
    });

    $('[id^="btnToggle1"]').live('click', function() {
	if($(this).val()=='show')
	    $(this).val('hide');
	else
	    $(this).val('show');
	$('.rows1').toggle();
    });
    
    $('[id^="btnToggle2"]').live('click', function() {
	if($(this).val()=='show')
	    $(this).val('hide');
	else
	    $(this).val('show');
	$('.rows2').toggle();
    });
    $('[id^="btnToggle3"]').live('click', function() {
	if($(this).val()=='show')
	    $(this).val('hide');
	else
	    $(this).val('show');
	$('.rows3').toggle();
    });
      $('[id^="btnToggle4"]').live('click', function() {
	//alert("ala");
        if($(this).val()=='show')
	    $(this).val('hide');
	else
	    $(this).val('show');
	$('.rows4').toggle();
    });
    $('[id^="btnExport1"]').live('click', function() {
        $('#csvform').show();
        $('#tableholder').empty();
        $('#csv_text').empty();
        $('#hidethis').remove();
         $("#sub").show();
        $('.rows1').show();
        
        $('#tableholder').append($('#myTable').html());
        var csv_value=$('#myTable').table2CSV({delivery:'value'});
         $("#csv_text").val(csv_value); 
       


        
    });
    $('[id^="btnExport2"]').live('click', function() {
	  // alert($('#myTable1').html());
          $('#csvform').show();
          $('#tableholder').empty();
          $('#csv_text').empty();
          $('#hidethis1').remove();
            $("#sub").show();
             
          $('.rows2').show();
          $('#tableholder').append($('#myTable1').html());
         var csv_value=$('#myTable1').table2CSV({delivery:'value'});
         $("#csv_text").val(csv_value); 
    });
    $('[id^="btnExport3"]').live('click', function() {
        $('#csvform').show();
        $('#tableholder').empty();
        $('#csv_text').empty();
	  $('#hidethis2').remove();
          $("#sub").show();
         $('.rows3').show();
          $('#tableholder').append($('#myTable2').html());
         var csv_value=$('#myTable2').table2CSV({delivery:'value'});
        $("#csv_text").val(csv_value); 
//        var url="getCSV.php";
//     $.ajax({
//        url: url,
//        type: "post",
//        data: csv_value
//    });

   
    });
    $('[id^="btnExport4"]').live('click', function() {
        $('#csvform').show();
        $('#tableholder').empty();
        $('#csv_text').empty();
	  $('#hidethis3').remove();
          $("#sub").show();
         $('.rows4').show();
          $('#tableholder').append($('#myTable3').html());
         var csv_value=$('#myTable3').table2CSV({delivery:'value'});
        $("#csv_text").val(csv_value); 

    });
    $(function() {
     $(".multiselect").multiselect();
   });
   jQuery.fn.multiselect = function() {
    $(this).each(function() {
        var checkboxes = $(this).find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = $(this);
            // Highlight pre-selected checkboxes
            if (checkbox.prop("checked"))
                checkbox.parent().addClass("multiselect-on");
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.prop("checked"))
                    checkbox.parent().addClass("multiselect-on");
                else
                    checkbox.parent().removeClass("multiselect-on");
            });
        });
    });
};
});	
</script>
</head>

<body id="dt_example" style="width: 90%">
<form id="csvform" action="getCSV.php" method ="post">

<input type="hidden" name="csv_text" id="csv_text">
<input id="sub" type="submit" value="Download">
<div id="tableholder"></div>

</form>
<form id="myForm" name="myForm" action="" method="post">
    <center><p><font size="+1" color="#000066"> <b> SALES REPORT</b></font> </p></center><hr color="#000066" height="2px"></br><br>
    <div type="text/css" style='text-align: center'>
	From
	<input type='text' name='invdtfrom' id='invdtfrom' class ='dtclass' value='<?php  echo $start;?>' readonly style='width: 150px;'/>
	To
	<input type='text' name='invdtto' id='invdtto' class ='dtclass' value='<?php  echo $end;?>' readonly style='width: 150px;'/>    
    </div>
    <div type="text/css" style='text-align: center'>    
        <!--<div id="buildingmasid" name="buildingmasid" style='width: 130px;'>-->
	    <!--<option value=0 selected>Building</option>-->
	    <?php loadBuilding();?>
	<!--</select>-->	
	<!--<div class="multiselect" style='text-align: left'>
	    <?php //loadinvoicedesc();?>
	</div>-->
	</br>	</br>	
	<button type="button" id="btnList">List Sales</button>
    </div>
   
    </br>	    
    <div style="position: absolute; left: 710px; top: 165px; width: 500px;">
	
    </div>
    </br>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    </br>   
    <div id='result_div'>
    
    </div>
</form>
 

<br>
</body>
</html>

