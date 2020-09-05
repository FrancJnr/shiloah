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

		
		<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.jqueryui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="../../extensions/Editor/js/dataTables.editor.min.js"></script>
<script type="text/javascript" src="../../extensions/Editor/js/editor.jqueryui.min.js"></script>
		
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
    
    include('../../stores/MasterRef_Folder.php');
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
  $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"sPaginationType": "full_numbers"			
    });
	
        $('#btnPostToTally').click(function(){
            alert('Test');
        });
    $('#btnReload').click(function(){

      // parent.top.$('div[name=masterdivtest]').html("<iframe  src='../stores/purchase/trans_post_grn_tally.php' id='the_iframe3' scrolling='yes' width='100%'></iframe>");   

    });
 /*    $("#myForm").submit( function () { 
			alert("Posting To Tally!");
	          $('#results').show();	
              $.post(
               'rcpt_post_tally.php',
                $(this).serialize(),
                function(data){
                  $("#results").html(data)
                }
              );
              return false;   
            });  */

});
</script> 
    
</head>

<body id="dt_example">

<!--<div id="container">-->
<center><h1>Receipt Tally Post</h1></center><br>

<div id="loaders"></div>

<div id="single" style="margin: auto;">

<br>
<label id="cc"></label>
<div id="exampleDiv" >
    
 
 <form id="myForm" name="myForm" action="rcpt_post_tally.php" method="post">
   <center><button class="buttonNew" type="submit" id="btnPostToTally"> Post All To Tally </button></center> 
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
         $sql = "SELECT a.invoicerctmasid as sno, d.leasename as debitledger, 
             d.tradingname as alias, a.rctdate, a.rctno, b.invoiceno, 
             a.totalamount,b.balance, a.is_java as intally FROM invoice_rct_mas a 
         INNER JOIN invoice_rct_det b ON a.invoicerctmasid = b.invoicerctmasid
         LEFT JOIN group_tenant_mas c ON c.grouptenantmasid = a.grouptenantmasid
         INNER JOIN mas_tenant d ON d.tenantmasid = c.tenantmasid";
         
         $tr="";
         $result=mysql_query($sql);
        if($result != null) // if $result <> false
        {
                if (mysql_num_rows($result) > 0)
                {
                    $i=1;$tradingname="";
                    while ($row = mysql_fetch_assoc($result)){
                        
                        $intally="";	
                       if($row['intally']=="0"){
                        $intally="NO";   
                        $html= "<td name[]='intally' style='color:red'>".$intally."</td>";
                         }else{
                           $intally="YES";  
                           $html= "<td name[]='intally' style='color:green'>".$intally."</td>";
                         }
                          if(isset($row['debitledger'])){
                         $tradingname=$row['debitledger'];   
                       // $html= "<td style='color:red'>".$intally."</td>";
                         }else if(!isset($row['debitledger']) && isset($row['alias'])){
                           $tradingname=$row['alias'];  
                          
                         }else{
                           $tradingname='Sundry';    
                         }
                         $tr .= "<tr>
                            <td name='sno[]' value=".$i++.">".$i++."</td>
                            <td name='rctno[]' value =".$row['rctno']."> ".$row['rctno']."<input type='hidden' name='rctno[]' value=".$row['rctno']."></td>
                            <td name='rctdate[]'>".$row['rctdate']."<input type='hidden' name='rctdate[]' value=".$row['rctdate']."></td>   
                            <td name='invoiceno[]'>".$row['invoiceno']."<input type='hidden' name='invoiceno[]' value=".$row['invoiceno']."></td>
                            <td name='totalamount[]'>".$row['totalamount']."<input type='hidden' name='totalamount[]' value=".$row['totalamount']."></td>
                            <td name='balance[]'>".$row['balance']."</td>  
                            <td name='tradingname[]' >".$tradingname."<input type='hidden' name='tradingname[]' value='".$tradingname."'></td>"
                                . "".$html."<td>"
                                . "<input type='checkbox' name='isselected[]' value=''></td>"
                                . "<td id='carryfwd'><input type='hidden' name='invoicerctmasid[]' value=".$row['invoicerctmasid']."></td></tr>";

                       
                        
                        
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
    

</form>
 
</div>


</div>

</body>
</html>
    