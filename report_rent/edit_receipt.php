<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    
	<title>Edit Receipt</title>
        <!--<script src="../js/jquery-2.1.4.min.js"></script>-->
        
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
	
                    header("location:../index.php");
		}
                
		include('../config.php');
		include('../MasterRef_Folder.php');
		$companymasid = $_SESSION['mycompanymasid'];
           //echo      $companymasid;
                //$action=$_GET['action'];
                
  function loadcompany()
    {
	$companymasid = $_SESSION['mycompanymasid'];
	if($companymasid == '2') // GRANDWAYS VENTURES LTD
	    $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid = '$companymasid' order by companymasid;";
	else
	    $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid != '2' order by companymasid;";
	
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['companymasid'].">".$row['companyname']."</option>");		
                }
        }
    }
            function loadTenant()
    {
$companymasid = $_SESSION['mycompanymasid'];
             $sql= "select 
            a.tenantmasid,a.tenanttypemasid,a.salutation, a.leasename, a.tradingname, a.tenantcode, a.companymasid, a.buildingmasid, a.blockmasid,
            a.floormasid, a.shopmasid, a.shoptypemasid, a.orgtypemasid, a.nob, a.agemasidlt, a.agemasidrc, a.agemasidcp, a.creditlimit,
            a.latefeeinterest, a.doo, a.doc, a.pin, a.regno, a.address1, a.address2, a.city, a.state, a.pincode, a.country, a.poboxno,
            a.telephone1, a.telephone2, a.fax, a.emailid, a.website, a.remarks, a.createdby, a.createddatetime, a.modifiedby, a.modifieddatetime,
            a.active from mas_tenant a where a.companymasid=$companymasid and a.active ='1' order by a.leasename asc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                   //  var a = response.leasename+"("+response.tenantcode+")";   
                    
                    echo("<option value=".$row['tenantmasid'].">".$row['leasename']."(".$row['tenantcode'].")"."</option>");		
                }
        }
    }
		function loadinvoicedesc()
    {
	    $sql = "select * from invoice_desc where active='1'";
	    $result = mysql_query($sql);
	    if($result != null)
	    {
		    while($row = mysql_fetch_assoc($result))
		    {
			    echo("<option value=".$row['invoicedescmasid'].">".$row['invoicedesc']."</option>");		
		    }
	    }
    }   
		function loadCptype()
		{
			$sql = "select cptype, cptypemasid from mas_cptype order by cptype asc";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['cptypemasid'].">".$row['cptype']."</option>");		
				}
			}
		}
                
		function loadShoptype()
		{
			$sql = "select shoptype, shoptypemasid from mas_shoptype where active =1 order by shoptype asc";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['shoptypemasid'].">".$row['shoptype']."</option>");		
				}
			}
		}
                
		function loadOrgtype()
		{
			$sql = "select orgtype, orgtypemasid from mas_orgtype where active =1 order by orgtype asc";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['orgtypemasid'].">".$row['orgtype']."</option>");		
				}
			}
		}
        //ENQUIRY FUNCTIONS        
     function loadbuilding()
    {
        $companymasid = $_SESSION['mycompanymasid'];
		$sql = "select buildingmasid, buildingname from mas_building WHERE companymasid =".$companymasid." order by buildingname asc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }               

            function loadreceipt()
    {
            $companymasid = $_SESSION['mycompanymasid'];
             $sql= "select rctno FROM  
                    invoice_rct_mas WHERE grouptenantmasid = '0' AND companymasid =".$companymasid."";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                   //  var a = response.leasename+"("+response.tenantcode+")";   
                    
                    echo("<option value=".$row['rctno'].">".$row['rctno']."</option>");		
                }
        }
    }
        
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
      
		$("#doo").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
		$("#doc").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});	
           
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Un-indetified Receipt");
		
		$("#tblHeaderCp").css('background-color', '#4ac0d5');		
		$("#tblheaderCp1").css('background-color', '#4ac0d5');
		$("#tblheaderCp2").css('background-color', '#4ac0d5');
		$("#tblheaderCp3").css('background-color', '#4ac0d5');
		
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#tenantcpDiv").show();
		$("#selectTenant").show();
		$("#editTr").show()
		$("#newTr").hide();
		                

		

//	});

	});


      	

});

    



 </script>
        
 
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
//});
    $('[id^="btnUpdate"]').live('click', function() {

		if($("#tenantrecpt").val() =="")
		{
		    alert("Please Select a receipt number.");
		    $("#tenantrecpt").focus();
		    return false;
		}
		if($("#companyeditrcpt").val() =="")
		{
		    alert("Please Select a Company.");
		    $("#companyeditrcpt").focus();
		    return false;
		}
	    
            if($("#rcptbuildingmasid").val() =="")
	    {
		alert("Please select a building.");
		$("#rcptbuildingmasid").focus();
		return false;
	    }
	    if($('#tenanteditrcpt').val() =="")
	    {
		alert("Please select a Tenant.");
		$("#tenanteditrcpt").focus();
		return false;
	    }
	    if($('#accountedtrcpt').val() =="")
	    {
		alert("Please Select an Account.");
		$("#accountedtrcpt").focus();
		return false;
	    }
              if($('#editincvenum').val() =="")
	    {
		alert("Please Select an Invoice Num.");
		$("#editincvenum").focus();
		return false;
	    }
	    if($('#remarks').val() =="" )
	    {
		alert("Remarks is mandatory.");
		$("#remarks").focus();
		return false;
	    }
            if($('#editpayof').val() =="" )
	    {
		alert("Being Paayment of Remarks is mandatory.");
		$("#editpayof").focus();
		return false;
	    }
	    var r = confirm("Are you sure. You Want to Update recpno: "+$("#tenantrecpt").val()+" ?");
	    if(r==true)
	    {
		
	        var dataToBeSent = $("form").serialize();
	        window.open("save_edited_receipt.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
	        return false;
	    }	    
    });
     
     });
     function tenantdetails(){
         var grouptenantmasid = $('#tenanteditrcpt').val();
		 var rcptbuildingmasid = $('#rcptbuildingmasid').val();
		 
		 //alert ('Test');tenantdetails
       //  var url="load_receipt.php?action=others_det&invoicemanmasid="+grouptenantmasid;
         	    var url="load_receipt.php?action=tenantdetailstoedit&grouptenantmasid="+grouptenantmasid+"&building="+rcptbuildingmasid;
	    //$('#cc').html(url);	
	    $.getJSON(url,function(data){
	    $.each(data.error, function(i,response){		    
			if(response.s == "Success")
			{
                           // $('#edittoaddress').val(response.toaddress);
			   // $('#editpremise').val(response.premise);
			    $('#edittoaddress').val(response.tenantaddress);
			    $('#editpremise').val(response.buildingaddress);		    
			}			
		});
     });
                    }
                    
     function findincvcenum (){
         $('[name="editincvenum"]').empty("");           
        var $grouptenant = $('#tenanteditrcpt').val();
            $.getJSON("load_receipt.php?action=getaccounttenanteditinvoicenum&descriptionacct="+$('#accountedtrcpt').val()+"&building="+$('#rcptbuildingmasid').val()+"&grouptenant="+$grouptenant, function(data) {
   $('[name="editincvenum"]').append("<option value=''>--Select--</option>");
    // alert(data);
    $.each(data, function(i, item) {       
    $('[name="editincvenum"]').append("<option value="+item.invoiceno+">"+item.invoiceno+"</option>");
 
       });  
	 }); 
                    
                }
                function loadeditrcpdet(){
                  var tenantrecpno = $('#tenantrecpt').val();
                 	    var url="load_receipt.php?action=getrcptdetails&tenantrecpt="+tenantrecpno;
	    //$('#cc').html(url);	
	    $.getJSON(url,function(data){
	    $.each(data.error, function(i,response){		    
			if(response.s == "Success")
			{

			    $('#editrcptamnt').val(response.recpamount);
			    $('#editchqnum').val(response.chqnum);		    
			}			
		});
     });    
                }   
</script>   
        
 <style>
            
body{
  font:1.2em normal Arial,sans-serif;
  color:#34495E;
}

h1{
  text-align:center;
  text-transform:uppercase;
  letter-spacing:-2px;
  font-size:2.5em;
  margin:20px 0;
}

.dataManipDiv{
  width:99%;
  margin:auto;
}

#tableusr{
  border-collapse:collapse;
  width:99%;
}

#tableusr{
  border:2px solid #1ABC9C;
}

#tableusr thead{
  background:#1ABC9C;
}

.purple{
  border:2px solid #9B59B6;
}

.purple thead{
  background:#9B59B6;
}

thead{
  color:white;
}

th,td{
  text-align:center;
  padding:5px 0;
}

tbody tr:nth-child(even){
  background:#ECF0F1;
}

tbody tr:hover{
background:#BDC3C7;
  color:#FFFFFF;
}

.fixed{
  top:0;
  position:fixed;
  width:auto;
  display:none;
  border:none;
}

.scrollMore{
  margin-top:600px;
}

.up{
  cursor:pointer;
}     
</style>
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="get">
<!--<div id="container">-->
<center><h1>Edit Receipt</h1></center>

<style>
    #dataManipDiv { width: 100%;height: 500px;}
     #usertbl { width: 100%;height: 100%; 
     
     }
/*     table.floatThead-table {
        border-top: none;
        border-bottom: none;
        background-color: #fff;
    }*/
     
</style>
<div id="dataManipDiv" class ="dataManipDiv">
<table id="usertbl" class="table1"  cellpadding="0" cellspacing="0" border="0"  width="100%">
   
<!--	<tr style="header:fixed;">-->
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Edit Unidentified Receipt	
			</th>
		</tr>
<!--	</tr>-->
	<tbody>

	<tr id="selectTenant">
		<td>
			Receipt Number <font color="red">*</font>
		</td>
		<td>
                    <select id="tenantrecpt" name="tenantrecpt" onchange="loadeditrcpdet();">
				<option value="" selected>----Select Number----</option>
                               <?php loadreceipt();?>
			</select>
		</td>
	</tr>
	 <tr id="selectTenantType">
		<td>
			Select Company <font color="red">*</font>
		</td>
		<td>
			<select id="companyeditrcpt" name="companyeditrcpt">
				<option value="" selected>----Select Company----</option>
				<?php loadcompany();?>
			</select>
		</td>
	</tr>
        <tr style='display:none'>
		<td>
			Remarks:<font color="red">*</font>
		</td>
		<td>
			<textarea cols=100 rows=5 id="remarks" name="remarks" value="remarks">remarks</textarea>
		</td>
	</tr>	
        <tr id="selectBuilding">
		<td>
			Select Building <font color="red">*</font>
		</td>
		<td>
			<select id="rcptbuildingmasid" name="rcptbuildingmasid">
				<option value="" selected>----Select Building----</option>
                                <?php loadbuilding();?>
                                
			</select>
		</td>
	</tr>
	<tr id="selectBlock">
		<td>
			Select Tenant <font color="red">*</font>
		</td>
		<td>
			<select id="tenanteditrcpt" name="tenanteditrcpt" onchange="tenantdetails();">
				<option value="" selected>----Select Tenant----</option>
                                <?php loadTenant();?>
			</select>
		</td>
	</tr>
        	        <tr>
		<td>
			To address <font color="red">*</font>
		</td>
		<td>
                    <input type="text" id="edittoaddress" name="edittoaddress" readonly>
		</td>
	</tr>
        	        <tr>
		<td>
			Premise <font color="red">*</font>
		</td>
		<td>
                    <input type="text" id="editpremise" name="editpremise" readonly>
		</td>
	</tr>
	<tr id="selectFloor">
		<td>
			Select Account <font color="red">*</font>
		</td>
		<td>
                    <select id="accountedtrcpt" name="accountedtrcpt" class="accteditrcpt" onchange="findincvcenum();">
				<option value="" selected>----Select Account----</option>
                                <?php loadinvoicedesc(); ?>
			</select>
		</td>
		</tr>
        <tr id="selectShop">
		<td>
			Invoice Num <font color="red">*</font>
		</td>
		<td>
			<select id="editincvenum" name="editincvenum">
				<option value="" selected>----Invoice Num----</option>
			</select>
		</td>
		</tr>
                
                <tr>
		<td>
			Amount <font color="red">*</font>
		</td>
		<td>
                    <input type="text" id="editrcptamnt" name="editrcptamnt" readonly>
		</td>
	</tr>
	        <tr>
		<td>
			Cheque Number <font color="red">*</font>
		</td>
		<td>
                    <input type="text" id="editchqnum" name="editchqnum" readonly>
		</td>
	</tr>
                <tr>
		<td>
			Being Payment of:<font color="red">*</font>
		</td>
		<td>
			<input type="text" id="editpayof" name="editpayof">
		</td>
	</tr>
   
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Receipt</button>
		</td>
	</tr>
	</tbody>
</table>
<!--<script src="../js/jquery.floatThead.js"></script>-->
<script>
		$(document).ready(function() {
                    $(".thdyna").hide();
                     $("#add").hide();
			var id = 0;
			var i = 0;
			// Add button functionality
			$("#usertbl button.add").click(function() {
                            $(".thdyna").show();
                            $("#add").show();
                        });
                        
			$("table.dynatable button.add").click(function() {	
                                id++;
				i++;
				var master = $(this).parents("table.dynatable");
				
				// Get a new row based on the prototype row
				var prot = master.find(".prototype").clone();
				prot.find("input[type='text']").each(function() {
					var a = $(this).attr('name');
					$(this).attr('name',a+i);
					$(this).attr('value',a+i);
				});
				prot.find("input[type='radio']").each(function() {
					$(this).attr('checked',true);
				});
				prot.find("select").each(function() {
					var a = $(this).attr('name');
					$(this).attr('name',a+i);
				});
				prot.attr("class", "")
				////prot.find(".id").html(id);
				
				master.find("tbody").append(prot);
			});
			
			// Remove button functionality
			$("table.dynatable button.remove").live("click", function() {
				$(this).parents("tr").remove();
//                                if($('table.dynatable tr').length==0){
//                                 $(".thdyna").hide();  
//                                }
				//length =  $("table.dynatable tr").not('tr:first').length-1;
			});
			return false;
		
               $('#cptypemasid').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                 var valueSelected = this.value;
                 alert(valueSelected);
                if(optionSelected=="Other"){
                  alert(valueSelected);  

                }


        });
        
    



});
                
                
     
                
                
                
                
                
		</script>
		<style>
			.dynatable {
				border: solid 1px #ffffff; 
				border-collapse: collapse;
			}
			.dynatable th,
			.dynatable td {
				border: solid 1px #ffffff; 
				padding: 2px 2px;
				width: 100%;
				text-align: center;
			}
			.dynatable .prototype {
				display:none;
			}
		</style>

</div>

<!--</div> Main Div-->
<label id="cc"></label>
</form>
<!--  <div id="dialog-form" title="Add Designation">
  <form>
    <fieldset>
      <label for="name">Salutation/Designation</label>
      <input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all">
       Allow form submission with keyboard without duplicating the dialog button 
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>-->
</body>
</html>
