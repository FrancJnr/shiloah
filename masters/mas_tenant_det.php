<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Tenant Master</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadtenant()
    {
        $sql = "select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,b.size from mas_tenant a
                inner join mas_shop b on b.shopmasid = a.shopmasid
                where a.active='1'
                union
                select a.tenantmasid,a.leasename,a.tradingname,b.shopcode,b.size from rec_tenant a
                inner join mas_shop b on b.shopmasid = a.shopmasid
                where a.active='1' order by leasename;";
        $result = mysql_query($sql);
        if($result != null)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $leasename = $row['leasename'];
                if($row['tradingname']!="")
                $leasename .= " T/A ".$row['tradingname'];
                
                $leasename .= " (".$row['shopcode']."-".$row['size'].")";
                echo("<option value=".$row['tenantmasid'].">$leasename</option>");		
            }
        }
    }
    function loadBuilding()
    {
        $sql = "select buildingmasid, buildingname from mas_building";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }
    function loadBlock()
    {
        $sql = "select blockmasid, blockname from mas_block";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['blockmasid'].">".$row['blockname']."</option>");		
                }
        }
    }
    function loadFloor()
    {
        $sql = "select floormasid, floorname from mas_floor";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['floormasid'].">".$row['floorname']."</option>");		
                }
        }
    }
    function loadShop()
    {
        $sql = "select shopmasid, shopcode from mas_shop";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['shopmasid'].">".$row['shopcode']."</option>");		
                }
        }
    }
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {    
    (function($) {
   $.fn.fixMe = function() {
      return this.each(function() {
         var $this = $(this),
            $t_fixed;
         function init() {
            $this.wrap('<div class="dataManipDiv" />');
          //  $this.wrap('<div class="exampleDiv" />');
          //  $this.wrap('<div class="menuDiv" />');
            $t_fixed = $this.clone();
            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
            resizeFixed();
         }
         function resizeFixed() {
            $t_fixed.find("th").each(function(index) {
               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
            });
         }
         function scrollFixed() {
            var offset = $(this).scrollTop(),
            tableOffsetTop = $this.offset().top,
            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
            if(offset < tableOffsetTop || offset > tableOffsetBottom)
               $t_fixed.hide();
            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
               $t_fixed.show();
         }
         $(window).resize(resizeFixed);
         $(window).scroll(scrollFixed);
         init();
      });
   };
})(jQuery);

//$(document).ready(function(){
   $("table").fixMe();
   $(".up").click(function() {
      $('html, body').animate({
      scrollTop: 0
   }, 2000);
 });
    $("#tenantmasid").change(function(){
        var $tenantmasid = $(this).val();
        if($tenantmasid !="")
        {
            var url="load_tenant_det.php?item=tenantdetails&itemval="+$tenantmasid;           
            $.getJSON(url,function(data){
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $.each(data.myResult, function(i,response){
			    $("#buildingmasid").val(response.buildingmasid);
			    $("#floormasid").val(response.floormasid);
			    $("#blockmasid").val(response.blockmasid);
			    $("#shopmasid").val(response.shopmasid);
                            $("#pin").val(response.pin);
                            $("#regno").val(response.regno);
                            $("#address1").val(response.address1);
                            $("#address2").val(response.address2);
                            $("#city").val(response.city);
                            $("#state").val(response.state);
                            $("#pincode").val(response.pincode);
                            $("#country").val(response.country);
                            $("#poboxno").val(response.poboxno);
                            $("#telephone1").val(response.telephone1);
                            $("#telephone2").val(response.telephone2);
                            $("#fax").val(response.fax);
                            $("#emailid").val(response.emailid);
                            $("#website").val(response.website);
                            $("#remarks").val(response.remarks);
        
                            $act = response.active;
                            if($act == "1")
                            {
                                $("#active").attr('checked','checked');
                            }
                            else
                            {
                                $("#active").removeAttr('checked')
                            }
                        });
                    }                   
                });             
            });
        }		
	});
    $("#btnUpdate").click(function(){
        var a = confirm("Can you confirm this ?");
	if (a== true)
	{
            var url="save_tenant_det.php?action=Update";
            var dataToBeSent = $("form").serialize();            
            $.getJSON(url,dataToBeSent, function(data){
                $.each(data.error, function(i,response){                    
                    if(response.s =="Success")
                    {
                        $('#cc').html(response.msg);
                    }
                    else
                    {
                        $('#cc').html(response.s);
                    }
                });
            });
        }
    });
});
</script>		
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="" method="get">
<div id="container">
<h1>ALTER TENANT DETAILS</h1>
<label id="cc"></label>
<table id="usertbl" class="table6" width="70%">	
	<tbody>
	<tr id="selectTenant">
		<td >
			Select Tenant <font color="red">*</font>
		</td>
		<td>
			<select id="tenantmasid" name="tenantmasid" style="width: 550px;">
				<option value="" selected>----Select Tenant----</option>
                                <?php loadtenant();?>
			</select>
                        <button type="button" id="btnUpdate">Update Tenant</button>
		</td>
	</tr>
	<tr>
		<td>
			Building 
		</td>
		<td>
			<select id="buildingmasid" name="buildingmasid">
				<option value="" selected>----Select Building----</option>
				<?php loadBuilding();?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Block 
		</td>
		<td>
			<select id="blockmasid" name="blockmasid">
				<option value="" selected>----Select Block----</option>
				<?php loadBlock();?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Floor 
		</td>
		<td>
			<select id="floormasid" name="floormasid">
				<option value="" selected>----Select Floor----</option>
				<?php loadFloor();?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Shop 
		</td>
		<td>
			<select id="shopmasid" name="shopmasid">
				<option value="" selected>----Select Shop----</option>
				<?php loadShop();?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			PIN 
		</td>
		<td>
			<input type="text" id="pin" name="pin">
		</td>
	</tr>
	<tr>
		<td>
			REG NO 
		</td>
		<td>
			<input type="text" id="regno" name="regno">
		</td>
	</tr>       
	<tr>
		<td><b>Address Details</b></td>
		<td><button type="button" id="show2"> >> </button></td>
	</tr>
	<tr id="r1">
		<td>
			Address 1
		</td>
		<td>
			<input type="text" id="address1" name="address1">
		</td>
	</tr>
	<tr id="r2">
		<td>
			Address 2
		</td>
		<td>
			<input type="text" id="address2" name="address2">
		</td>
	</tr>
	<tr id="r4">
		<td>
			City
		</td>
		<td>
			<input type="text" id="city" name="city">
		</td>
	</tr>
	<tr id="r5">
		<td>
			State
		</td>
		<td>
			<input type="text" id="state" name="state">
		</td>
	</tr>
	<tr id="r6">
		<td>
			Pincode
		</td>
		<td>
			<input type="text" id="pincode" name="pincode">
		</td>
	</tr>
	<tr id="r7">
		<td>
			Country
		</td>
		<td>
			<input type="text" id="country" name="country">
		</td>
	</tr>
	<tr id="r3">
		<td>
			P.O.Box No
		</td>
		<td>
			<input type="text" id="poboxno" name="poboxno">
		</td>
	</tr>
	<tr id="r8">
		<td>
			Telephone 1
		</td>
		<td>
			<input type="text" id="telephone1" name="telephone1">
		</td>
	</tr>
	<tr id="r9">
		<td>
			Telephone 2
		</td>
		<td>
			<input type="text" id="telephone2" name="telephone2">
		</td>
	</tr>
	<tr id="r10">
		<td>
			Fax
		</td>
		<td>
			<input type="text" id="fax" name="fax">
		</td>
	</tr>
	<tr id="r11">
		<td>
			Email Id
		</td>
		<td>
			<input type="text" id="emailid" name="emailid">
		</td>
	</tr>
	<tr id="r12">
		<td>
			Website
		</td>
		<td>
			<input type="text" id="website" name="website">
		</td>
	</tr>
	<tr id="r13">
		<td>
			Remarks
		</td>
		<td>
			<input type="text" id="remarks" name="remarks">
		</td>
	</tr>			
	</tbody>
</table>
</div> <!--Main Div-->
</form>
</body>
</html>
