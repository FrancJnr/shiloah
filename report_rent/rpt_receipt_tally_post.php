<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
 //include('../MasterRef_Folder.php');
?>
<html>
<head>
	<title>TALLY POST</title>
	<link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="../shopstable.css">
        <link rel="stylesheet" type="text/css" href="../../stores/datatables/datatables.min.css">
		<!--	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.jqueryui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="../../extensions/Editor/js/dataTables.editor.min.js"></script>
<script type="text/javascript" src="../../extensions/Editor/js/editor.jqueryui.min.js"></script>-->

  <style>
/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}

select{
 width:5px;   
}



select option{
  width:5px;   
}
/*#myDiv {
  display: none;
  text-align: center;
}*/
</style>   
        
<?php
error_reporting(0);
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../../index.php");
    }
    include('../config.php');
      include('../MasterRef_Folder.php');
   // include('../../stores/MasterRef_Folder.php');
   // $companymasid  = $_SESSION['mycompanymasid'];
    $companyname = $_SESSION['mycompany'];
    //echo $companymasid;
    //echo "<br>";
    echo $companyname;
   
    
    
    function loadBankRegister()
    {
        $sql = "SELECT * FROM mas_bank";
        $result = mysql_query($sql);
       // print_r($result);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['ledgermasid'].">".$row['name']."</option>");		
                }
        }
    }
    
    
   $start =  date("d-m-Y");        
?>
<script type="text/javascript" language="javascript">
//$(document).focus();

$(document).ready(function() {
    
   $('.dtclass').datepicker({
         showOn: "button",
	    buttonImage: "../images/calendar.gif",
	    buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true,
	    dateFormat:"dd-mm-yy"
    });   
/*   $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"sPaginationType": "full_numbers"			
    }); */
	   $("#loader").hide();
	$("#bankmasid").change(function(){
	var bankmasid = $("#bankmasid option:selected"). text();//$('#bankmasid').val();
	//alert(bankmasid);
	
	$('#bankid').val(bankmasid);
	
	});
      $('#btnPostToTally').click(function(e){
		    if($(".tallychckbox:checked").length == 0){
			alert('No receipt selected ');
			} 
			else{
           alert('Posting Tally ');
		$('#btnPostToTally').hide();

        $("#loader").show();
		   
    var form = $('#myForm');
    var url='rcpt_post_tally.php';

       //var form = $(this);
            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                dataType: 'json',
                success: function(data) {
					//alert(data.error);
					//console.log(data);
					 $("#loader").hide();
			 $.each(data.error, function(i,response){
				
				if(response.s == "Success")
				{
				 alert(response.tallyfb);	
				 $('#btnPostToTally').show();
				parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");/* 
					$.each(data.myResult, function(i,response){			
            //alert(data.msg);					
					
				        $('#btnPostToTally').show();
						parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");
                    
					}); */
				}
				else
				{
					alert("Failed to post");
				}
                    /* if(data.error == true) {
                       
                        alert(data.msg);
				        $('#btnPostToTally').show();
						parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");
                    
							

                            
					} else {
						//$("#loader").hide();
                        alert(data.msg);
						$('#btnPostToTally').show();
						parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");
                        
                    } */
                });
            }
          
});   
	  e.preventDefault();
			}
}); 
    $('#btnReload').click(function(){

    parent.top.$('div[name=masterdivtest]').html("<iframe  src='report_rent/rpt_receipt_tally_post.php' id='the_iframe2' scrolling='yes' width='100%'></iframe>");

    });


});
</script> 
    
</head>

<body id="dt_example">

<!--<div id="container">-->
<center><h1>Receipt Tally Post</h1></center><br>

<div id="loader"></div>

<div id="single" style="margin: auto;">

<br>
<label id="cc"></label>
<div id="exampleDiv" >
  <center><button class="buttonNew" name ="btnPostToTally" id="btnPostToTally"> Post All To Tally </button></center>    
 
 <form id="myForm" name="myForm">
  
  <div id="menuDiv" style="margin: auto;">
<table>

    
   <tr>
       <td id="banks">
        <select id="bankmasid" name="bankmasid">
			<option value="" selected>----Select Bank----</option>
			<?php 
                            loadBankRegister();
							
                        ?>
	
        </select>
      </td>
      <td><b>Transaction Date: </b> 
	  <input type='text' name='invdt' id='invdt' class ='dtclass' value='<?php  echo $start;?>'  style='width: 150px;'/>
      </td>
  </tr>
</table>
</div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin:auto !important;">
    <thead>
             
        
        <tr>                                          
                    <th style="color:black !important;">SNo</th>
                    <th style="color:black !important;">Receipt No</th>
                    <th style="color:black !important;">Receipt Date</th>
                    <th style="color:black !important;">Invoice No</th>                
                    <th style="color:black !important;">Receipt Amount</th>
                    <th style="color:black !important;">Balance</th>    
                    <th style="color:black !important;">Customer</th>  
                    <th style="color:black !important;">In Tally</th>
                    <th style="color:black !important;">Select</th>

       </tr>
    </thead>
    <tbody id="tbodyContent">
	<?php 
         $sql = "SELECT a.invoicerctmasid as sno,c.grouptenantmasid, d.leasename as debitledger, 
             d.tradingname as alias, a.rctdate, a.rctno, b.invoiceno, 
             b.total as totalamount,a.totalamount as rcptamnt,b.balance, a.is_java as intally, a.rmks as rmks , a.chqnum as chqnum FROM invoice_rct_mas a 
         INNER JOIN invoice_rct_det b ON a.invoicerctmasid = b.invoicerctmasid
         LEFT JOIN group_tenant_mas c ON c.grouptenantmasid = a.grouptenantmasid
         INNER JOIN mas_tenant d ON d.tenantmasid = c.tenantmasid ";
         
         $tr="";
         $result=mysql_query($sql);
        if($result != null) // if $result <> false
        {
                if (mysql_num_rows($result) > 0)
                {
                    $i=1; $j=1;$tradingname=""; $k=1;
                    while ($row = mysql_fetch_assoc($result)){
                        
                        $intally="";	
                       if($row['intally']=="0"){
                        $intally="NO";   
                        $html= "<td name[]='intally' style='color:red'>".$intally."</td>";
                         }else{
                           $intally="YES";  
                           $html= "<td name[]='intally' style='color:green'>".$intally."</td>";
                         }
                           if(!empty($row['debitledger'])){
                         $tradingname=$row['debitledger'];   
                       // $html= "<td style='color:red'>".$intally."</td>";
                         }else if(empty($row['debitledger']) && !empty($row['alias'])){
                           $tradingname=$row['alias'];  
                          
                         }else{
                           $tradingname='Sundry';    
                         } 
						 
						// $tradingname=$row['debitledger'];   
                        $tr .= "<tr>
                            <td name='sno[]' value=".$i++.">".$j++."</td>
                            <td name='rctno[]' value =".$row['rctno']."> ".$row['rctno']."<input type='hidden' name='rctno[]' value=".$row['rctno']."></td>
                            <td name='rctdate[]'>".$row['rctdate']."<input type='hidden' name='rctdate[]' value=".$row['rctdate']."></td>   
                            <td name='invoiceno[]'>".$row['invoiceno']."<input type='hidden' name='invoiceno[]' value=".$row['invoiceno']."></td>
                            <td name='totalamount[]'>".$row['totalamount']."<input type='hidden' name='totalamount[]' value=".$row['totalamount']."></td>
                            <td name='balance[]'>".$row['balance']."</td>  
                            <td name='tradingname[]' >".$tradingname."<input type='hidden' name='tradingname[]' value='".$tradingname."'></td>"
                                . "".$html."<td>"
                                . "<input type='checkbox' class='tallychckbox' name='isselected[]' value='".$k++."'></td>"
								. "<td id='tenantcode'><input type='hidden' name='chqnum[]' value='".$row['chqnum']."'></td><td>
								<input type='hidden' name='narration[]' value='".$row['rmks']."'></td>
								<td><input  type='hidden' name='tenantcode[]' value=".gettenancyrefcode($row['grouptenantmasid'])."></td>"
                                . "<td id='carryfwd'><input type='hidden' name='invoicerctmasid[]' value=".$row['invoicerctmasid']."></td>
								</tr>";

                       
                        
                        
                         }
                }

        }		

      echo $tr;
        ?>
    </tbody>
    <tfoot>
            <tr>
                    <th style="color:black !important;">SNo</th>
                    <th style="color:black !important;">Receipt No</th>
                    <th style="color:black !important;">Receipt Date</th>
                    <th style="color:black !important;">Invoice No</th>                
                    <th style="color:black !important;">Receipt Amount</th>
                    <th style="color:black !important;">Balance</th> 
                    <th style="color:black !important;">Customer</th>  
                    <th style="color:black !important;">In Tally</th>
                    <th style="color:black !important;">Select</th>
            </tr>
    </tfoot>
</table>
   <br>
  <input type='hidden' name='bankid'  id='bankid' value=''></input>  

</form>
 
</div>


</div>

</body>
</html>
    