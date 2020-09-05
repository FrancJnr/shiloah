<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Expense Entry</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadBuilding()
    {
        $sql = "select buildingmasid, buildingname from mas_building where buildingmasid !='6'";
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
table { margin: 1em; border-collapse: collapse;font-family: arial;font-size: 12px;}
td, th { padding: .3em; border: 1px #000000 solid; }
thead { background: #fc9; }
}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#buildingmasid')[0].focus()
    $('#buildinglistTbl').hide();    
    $('[id^="buildingmasid"]').live('change', function() {
	$('#report').html("");
        loaddaterange($(this).val());
    });
    function loaddaterange($x)
    {
        var url="load_exp.php?item=loaddaterange&itemval="+$x;					
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#transexpmasid1').empty();$('#transexpmasid2').empty();
                    $('#transexpmasid1').append( new Option("-----Select Period 1-----","",true,false) );
		    $('#transexpmasid2').append( new Option("-----Select Period 2-----","",true,false) );		
                    $.each(data.myResult, function(i,response){
                            var a = response.fromdate +" to "+ response.todate;
                            $('#transexpmasid1').append( new Option(a,response.transexpmasid,true,false) );
			    $('#transexpmasid2').append( new Option(a,response.transexpmasid,true,false) );
                    });
                }
                else
                {
                    alert(response.s);
                }
            });		
        });
        $("#transexpmasid").focus();
    }
//    $('[id^="transexpmasid"]').live('change', function() {
//	var $x = $('#buildingmasid').val();	
//        var url="load_exp.php?item=loadtransreport&itemval="+$(this).val()+"&buildingmasid="+$x;	
//        $.getJSON(url,function(data){
//            $.each(data.error, function(i,response){
//                if(response.s == "Success")
//                {
//                    $('#report').html(response.msg);
//                }
//                else
//                {
//                    alert(response.s);
//                }
//            });		
//        });
//        
//    });
    $('[id^="btnView"]').live('click', function() {        
	if(jQuery.trim($("#buildingmasid").val()) == "")
	{
	    alert("Please Select Building");
	    $("#buildingmasid").focus();
	    return false;
	}
	var $x = $('#buildingmasid').val();
	var m1 = $('#m1').val(); var m2 = $('#m2').val(); var y1 = $('#y1').val();
	var m3 = $('#m3').val(); var m4 = $('#m4').val(); var y2 = $('#y2').val();
	
	var $y = $('#transexpmasid1').val();
	var $z = $('#transexpmasid2').val();
        var url="load_exp.php?item=loadexpreport&buildingmasid="+$x+"&m1="+m1+"&m2="+m2+"&y1="+y1+"&m3="+m3+"&m4="+m4+"&y2="+y2;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#report').html(response.msg);
                }
                else
                {                    
		    $('#cc').html(response.msg)
                }
            });		
        });
    }); 
////    $('[id^="btnView"]').live('click', function() {
////        var $x = $('#buildingmasid').val();
////	var $y = $('#transexpmasid1').val();
////	var $z = $('#transexpmasid2').val();
////        var url="load_exp.php?item=loadexpreport&transexpmasid1="+$y+"&transexpmasid2="+$z+"&buildingmasid="+$x;	
////        $.getJSON(url,function(data){
////            $.each(data.error, function(i,response){
////                if(response.s == "Success")
////                {
////                    $('#report').html(response.msg);
////                }
////                else
////                {
////                    alert(response.s);
////                }
////            });		
////        });
////    });
    $('[id^="btnPrint"]').live('click', function() {
        $('.printable').print();
    });
});
</script>
<link href="style_progress.css" rel="stylesheet" type="text/css" />
</head>

<body id="dt_example" style="width: 90%">
<form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER'>Expense Report</h1>
    <br>
	<span></span>
    <br>   
    <table>
	<tr>
	    <th colspan='2'>Expense Report</th>
	</tr>         
        <tr>
            <td>Building <font color="red">*</font></td>
            <td>
                <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
                    <option value="" selected>----Select Building----</option>
                    <?php loadBuilding();?>
                </select>    
            </td>
        </tr>
      <!-- <tr>
            <td>Period 1<font color="red">*</font></td>
            <td>
                <select id="transexpmasid1" name="transexpmasid1" style='width: 225px;'>
                    <option value="0" selected>----Select Period 1----</option>             
                </select>
            </td>
       </tr>
        <tr>
            <td>Period 2<font color="red">*</font></td>
            <td>
                <select id="transexpmasid2" name="transexpmasid2" style='width: 225px;'>
                    <option value="0" selected>----Select Period 2----</option>             
                </select>
            </td>
       </tr>-->
	<tr>
	    <td>PERIOD 1 <font color="red">*</font></td>
            <td>
                <select id="y1" name="y1" style='width: 70px;'>
		<?php
		    foreach(range((int)date("Y"),1978) as $year) {
			echo "\t<option value='".$year."'>".$year."</option>\n\r";
		    }
		?>
		</select> &nbsp;
		<select id="m1" name="m1" style='width: 70px;'>
                    <?php
			$monthNames = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
			foreach($monthNames as $month1) {
			    echo "\t<option value='".$month1."'>".$month1."</option>\n\r";
			}
		    ?>         
                </select>
		&nbsp; TO &nbsp;
		<select id="m2" name="m2" style='width: 70px;'>
                    <?php
		    $monthNames = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		    foreach($monthNames as $month2) {
			echo "\t<option value='".$month2."'>".$month2."</option>\n\r";
		    }
		    ?>         
                </select>
				
            </td>
	</tr>
	<tr>
	    <td>PERIOD 2 <font color="red">*</font></td>
            <td>
                <select id="y2" name="y2" style='width: 70px;'>
		<?php
		    foreach(range((int)date("Y"),1978) as $year) {
			echo "\t<option value='".$year."'>".$year."</option>\n\r";
		    }
		?>
		</select> &nbsp;
		<select id="m3" name="m3" style='width: 70px;'>
                    <?php
			$monthNames = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
			foreach($monthNames as $month1) {
			    echo "\t<option value='".$month1."'>".$month1."</option>\n\r";
			}
		    ?>         
                </select>
		&nbsp; TO &nbsp;
		<select id="m4" name="m4" style='width: 70px;'>
                    <?php
		    $monthNames = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		    foreach($monthNames as $month2) {
			echo "\t<option value='".$month2."'>".$month2."</option>\n\r";
		    }
		    ?>         
                </select>
				
            </td>
	</tr>
	<tr>
            <td></td>
            <td>
		<!--<button type="button" id="btnView">View</button>-->
		<!--<button type="button" id="btnPrint">Print</button>-->
		<button type="button" id="btnView">View</button>		
	    </td>
        </tr>        
    </table>	   
    <p id='heading' align='center'></p>
    <div id='report'>
    
    </div>
    </form>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <br>
</form>
</body>
</html>

