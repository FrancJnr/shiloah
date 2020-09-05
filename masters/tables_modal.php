<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Table Shops</title>
       

<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		//include('../MasterRef_Folder.php');
?>
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../shopstable.css">
        <script src="../bootbox.js"></script>
         <script src="../js/jquery-2.1.4.min.js"></script>
         <script src="../bootstrap/js/bootstrap.min.js"></script>       
         
<script>
    
    $(document).ready(function() {
     $('#btnLoad').click(function(){
    var url="load_tenant.php?item=loadFloorShop&itemval="+"1";					
    $.getJSON(url,function(data){
        $.each(data.error, function(i,response){
            if(response.s == "Success")
            {
                
                    $.each(data.myResult, function(i,response){
                            
                        //$('#floors').append( new Option(response.shopcode,response.shopmasid,true,false) );
                    
                       $('#floors').append("<div class='square'><div class='content'><div class='table'>\n\
                        <div id='intern' class='table-cell' style='color:white !important;'> shop: "+ response.shopcode +"  size: "+ response.size +"</div></div></div></div>");
                  });

            }
            else
            {
                    alert(response.msg);
            } 
        });             
          }); 

     });
     });
    </script>
    
</head>
<body>

 <div class="container">

  <button type="button" id="btnLoad" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Floor</button>
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body" >
            <div id="floors" style="overflow-y:auto !important;" >
          
<!--            <div class="square">
                 <div class="content">
                <div class="table">
                    <div class="table-cell numbers">
                            98%
                    </div>
                </div>
            </div>
            </div>-->


<!-- 2nd row verticaly centered images in square columns -->

<!--<div class="square">
   <div class="content">
        <div class="table">
            <div class="table-cell">
                <img class="rs" src="https://farm3.staticflickr.com/2878/10944255073_973d2cd25c.jpg"/>
                Responsive image.
            </div>
        </div>
    </div>
</div>


 3rd row responsive images in background with centered content 

<div class="square bg img1">
   <div class="content">
        <div class="table">
            <div class="table-cell">
                Centered responsive images as background with centered content over it.
            </div>
        </div>
    </div>
</div>-->

</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
</body>
</html>