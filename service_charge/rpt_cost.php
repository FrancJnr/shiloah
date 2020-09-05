<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Monthly Rental Schedule</title>
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
                    $('#transexpmasid').empty();
                    $('#transexpmasid').append( new Option("-----Select Period-----","",true,false) );		    
                    $.each(data.myResult, function(i,response){
                            var a = response.fromdate +" to "+ response.todate;
                            $('#transexpmasid').append( new Option(a,response.transexpmasid,true,false) );			    
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
    $('[id^="transexpmasid"]').live('change', function() {
	var $x = $('#buildingmasid').val();
	$('#report').empty();$('#cc').empty();
        var url="load_cost.php?item=cost&transexpmasid="+$(this).val()+"&buildingmasid="+$x;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#report').html(response.msg);
                }
                else
                {
                    $('#cc').html(response.msg);
                }
            });		
        });
        
    });
     $('[id^="btnView"]').live('click', function() {
        var $x = $('#buildingmasid').val();
	var m1 = $('#m1').val(); var m2 = $('#m2').val(); var y1 = $('#y1').val();
	$('#report').empty();$('#cc').empty();
        var url="load_cost_test.php?item=cost&buildingmasid="+$x+"&m1="+m1+"&m2="+m2+"&y1="+y1;
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#report').html(response.msg);
                }
                else
                {
                    $('#cc').html(response.msg);
                }
            });		
        });
    });
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
    <h1 align='CENTER'>Collectable Cost Apportionment</h1>
    <br>
	<span></span>
    <br>   
    <table>
	<tr>
	    <th colspan='2'>Select Building and period: </th>
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
       <!--<tr>
            <td>Period<font color="red">*</font></td>
            <td>
                <select id="transexpmasid" name="transexpmasid" style='width: 225px;'>
                    <option value="0" selected>----Select Period ----</option>             
                </select>
            </td>
       </tr>-->
       <tr>
	    <td>SELECT PERIOD<font color="red">*</font></td>
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
            <td></td>
            <td>
		<button type="button" id="btnPrint">Print</button>
		&nbsp;&nbsp;
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

