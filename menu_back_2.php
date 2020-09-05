<?php
    include('config.php');
   require 'PHPMailers/PHPMailerAutoload.php';
    session_start();
    if(!isset($_SESSION['myusername']))     
    {
	 header("location:index.php");
    }    
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mega Investments</title>
    <link href="css/ddmenu.css" rel="stylesheet" type="text/css" />
    <script src="js/ddmenu.js" type="text/javascript"></script>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="menu_jquery/themes/start/jquery-ui.css"/>
    <script src="menu_jquery/jquery-1.9.1.js"></script>
    <script src="menu_jquery/jquery-ui.js"></script>    
    <script src="reports-pms/js/jspdf/jspdf.debug.js"></script> 
    <link rel="stylesheet" href="styles.css"/>
    <style>
        /*The following are for this demo page only (not required for the ddmenu).*/
        /*body { background: #eee url(images/ddmenu-bg.jpg) no-repeat center 0px; padding-top:90px;}*/
/*        body {
	    background: #eee url(images/megaicon.png) no-repeat center 0px; padding-top:90px;
            background: #eee url(images/ddmenu-bg.jpg) no-repeat center 0px; padding-top:90px;
	    font: 13px 'trebuchet MS', Arial, Helvetica;
            
	}*/
            /*tab*/
    #dialog label, #dialog input { display:block; }
    #dialog label { margin-top: 0.5em; }
    #dialog input, #dialog textarea { width: 95%; }
    #tabs { margin-top: 1em; }
    #tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
    #add_tab { cursor: pointer; clear: both;}
   iframe{
        clear: both;
	border: none;
        height: 500px;
    }
    #left{float:left;width:400px;}
    #right{float:right;width:400px;}
    #center{margin:0 auto;width:400px;}
    
    a,
a:link,
a:active,
a:visited,
a:hover {
  color: #f60;
  font-size: 16px;
  padding: 6px;
}

a:hover {
    text-decoration: underline;
}
</style>
    </style>
    <script type="text/javascript" language="javascript">
            
     
    $(function() {
        
    $('[id^="tab"]').css({
	    'min-height': '100px',
	    'height': '100%'
	    //'overflow':'auto'
    });
    

    // Tabs                                                                                                              
    var $tabs = $("#tabs").tabs();
//     $(".full-width").click(function() {
//         
    
//     });
    $("#masters a").click(function() { 
        // alert($(this).html());
        
        $('.dropdown').mouseout(); 
        
//        if($(this).html()=="Enquiry"){
//                      
//            addEnquirytab($(this));
//            //addtabinforming($(this));
//        }else if($(this).html()=="Tenants"){
//                      
//          // alert($(this).html());
//            //addtabinforming($(this));
//        }else{
        
        
            addtabinform($(this));
//        }
    });  
 
     function addEnquirytab(link) {
	// If tab already exist in the list, return
    if ($("#" + $(link).attr("rel")).length != 0)    
    return;      
      var tabDisplayNames  =  "Enquiry";
      var path = "masters/mas_enquiry_updated.php";
     
      var tabContent = "<iframe  name='enquiryiframe' src='"+ path+"' id='the_iframe"+tabCounter+"' style='overflow: hidden !important;' width='100%'/>"; 
     

     var label = tabDisplayNames || "Tab " + tabCounter,
        id = $(link).attr("rel"),
	//id = "tab"+tabCounter,	
        li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
        tabContentHtml = tabContent|| "Tab " + tabCounter + " content.";    

      tabs.find( ".ui-tabs-nav" ).append( li );
      //tabs.append( "<div id='" + id + "'><p>" + tabLocation + "</br><iframe width='100%' id='the_iframe"+tabCounter+"' src='"+ path+"' scrolling='no' id='"+tabId+"' onLoad='calcHeight("+tabCounter+");' frameborder='0' /></p></div>" );
      tabs.append( "<div name='masterdivtest' style='min-height: 100%;height: auto !important;' id='"+id+"'>" + tabContentHtml+"</div>" ); 
      //tabs.append( "<div style='min-height: 100%;height: auto !important;' id='"+id+"'>" + tabContentHtmli +"</div>" );
   //tabContentHtml.append(nextButton);
      tabs.tabs( "refresh");
     //alert(tabCounter);

    $(document).ready(function(){
        $tabs = $("#tabs").tabs({
            select: function(event, ui) {
                $('a', ui.tab).click(function() {
                    $(ui.panel).load(this.href);
                    
                    return true;
                });
            }
        });
    });
      tabs.tabs( "option", "active",tabCounter-1);      
      tabCounter++;      
    }   
       // actual addTab function: adds new tab using the input from the form above
    function addtabinforming(link) {
	// If tab already exist in the list, return
    if ($("#" + $(link).attr("rel")).length != 0)    
    return;      
      var tabDisplayNames  =  "Occupation";
      var pathi = "masters/mas_enquiry.php";
      var pathii = "masters/mas_tenant.php";
      var pathiii = "transaction/trans_offerletter.php";
      var tabContenti = "<iframe  src='"+ pathi+"' id='the_iframe"+tabCounter+"' scrolling='yes' width='100%'/>";      
      var tabContentii = "<iframe src='"+ pathii+"' id='the_iframe"+tabCounter+"' scrolling='yes'  width='100%'/>";
      var tabContentiii = "<iframe  src='"+ pathiii+"' id='the_iframe"+tabCounter+"' scrolling='yes' width='100%'/>";

     var label = tabDisplayNames || "Tab " + tabCounter,
        id = $(link).attr("rel"),
	//id = "tab"+tabCounter,	
        li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
        tabContentHtmli = tabContenti || "Tab " + tabCounter + " content.";    
        tabContentHtmlii = tabContentii || "Tab " + tabCounter + " content.";
         tabContentHtmliii = tabContentiii || "Tab " + tabCounter + " content.";  
      tabs.find( ".ui-tabs-nav" ).append( li );
      //tabs.append( "<div id='" + id + "'><p>" + tabLocation + "</br><iframe width='100%' id='the_iframe"+tabCounter+"' src='"+ path+"' scrolling='no' id='"+tabId+"' onLoad='calcHeight("+tabCounter+");' frameborder='0' /></p></div>" );
      tabs.append( "<div name='masterdivtest' style='min-height: 100%;height: auto !important;' id='"+id+"'>" + tabContentHtmli + tabContentHtmlii + tabContentHtmliii+ "</div>" ); 
      //tabs.append( "<div style='min-height: 100%;height: auto !important;' id='"+id+"'>" + tabContentHtmli +"</div>" );
      tabs.tabs( "refresh");
     //alert(tabCounter);

    $(document).ready(function(){
        $tabs = $("#tabs").tabs({
            select: function(event, ui) {
                $('a', ui.tab).click(function() {
                    $(ui.panel).load(this.href);
                    
                    return true;
                });
            }
        });
    });
      tabs.tabs( "option", "active",tabCounter-1);      
      tabCounter++;      
    }   
          // actual addTab function: adds new tab using the input from the form above
    function addtabinform(link) {
     //alert($("#" + $(link).attr("rel")));
   // If tab already exist in the list, return
    if ($("#" + $(link).attr("rel")).length != 0)    
    return;      

      var tabDisplayName  =  $(link).attr("name");
      
      var tabLocation = $(link).attr("location");
      
      var tabId = $(link).attr("rel");
      
      var path = $(link).attr("value");
      
      var tabContent = "<iframe src='"+ path+"' id='the_iframe"+tabCounter+"' style='overflow: hidden !important;' width='99%'/>";  
      
      var label = tabDisplayName || "Tab " + tabCounter,
      
        id = $(link).attr("rel"),
		
        li = $(tabTemplate.replace( /#\{href\}/g, "#" + id ).replace(/#\{label\}/g, label)),
        
        tabContentHtml = tabContent || "Tab " + tabCounter + " content.";    
        
      tabs.find(".ui-tabs-nav" ).append(li);
      
      tabs.append( "<div name='masterdivtest' style='min-height: 100%;height: auto !important;' id='"+id+"'>" + tabContentHtml +"</div>" );            
      tabs.tabs( "refresh");


        $(document).ready(function(){
            $tabs = $("#tabs").tabs({
                select: function(event, ui) {
                    $('a', ui.tab).click(function() {
                        $(ui.panel).load(this.href);
                        return true;
                    });
                }
            });
        });
        tabs.tabs( "option", "active",tabCounter-1);      
        tabCounter++;      
        } 

    var tabTitle = $( "#tab_title" ),
      tabContent = $( "#tab_content" ),
      tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
      tabCounter = 2;
    
    var tabs = $( "#tabs" ).tabs();
    tabs.tabs( "option", "active",tabCounter-1); 
 
    // close icon: removing the tab on click
    tabs.delegate( "span.ui-icon-close", "click", function() {
      var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
      $( "#" + panelId ).remove();
      tabs.tabs( "refresh" );
      tabCounter--;
     });
 
    tabs.bind( "keyup", function( event ) {
      if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
        var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
        $( "#" + panelId ).remove();
        tabs.tabs( "refresh" );
      }
    });     
    $(".top-heading").click(function(event) {
	event.preventDefault(); 
    });    
  });

  </script>
</head>
<body>
    <!--<span style=' background:#0099cc; color:blue !important;'>USER: <bu style="color: green;"> <?php echo $_SESSION['myusername']; echo " on  ". date('d-M-y'); ?></bu></span><br>-->
    <span style="color: #0099cc; font-size: 18px; font-family: monospace !important; float:right;"><b> <?php echo $_SESSION['mycompany']; echo "  ".$_SESSION['myusername']; echo "  ". date('d.M.y');?></b></span><br>

<nav id="ddmenu">
    
    <ul id="menu" style="background:#0099cc">
<!--  <li class="no-sub"><a class="top-heading" href="#"><?php echo $_SESSION['mycompany'];?></a></li>
 <li class="no-sub"><a class="top-heading" href="#">Welcome <?php echo $_SESSION['myusername'];?></a></li>-->
  <?php
		    $usermasid = $_SESSION['myusermasid'];
		    $sql1 = "select c.modulename,c.modulemasid from mas_module_user a
			    inner join mas_module_det b on b.moduledetmasid = a.moduledetmasid
			    inner join mas_module c on c.modulemasid = b.modulemasid
			    where a.usermasid='$usermasid' and c.active='1' group by c.modulemasid;";
		    $result1 = mysql_query($sql1);
		    if($result1!=null)
		    {
			while($row1 = mysql_fetch_assoc($result1))
			{
			    // MAIN MODULE NAME
//                   
			    echo "<li class='full-width'>";
			    echo "<span class='top-heading'>".$row1['modulename']."</span><i class='caret'></i>";
                            echo "<div class='dropdown'><div class='dd-inner' style='background: #0099cc; color:white; opacity: 60;'>";
//                            echo "<div class='dropdown'><div class='dd-inner'>";
				$sql2="select CONCAT(UCASE(MID(d.moduleheader,1,1)),MID(d.moduleheader,2)) as moduleheader,
					d.moduleheader as head ,d.moduleheadermasid from mas_module_user a
					inner join mas_module_det b on b.moduledetmasid = a.moduledetmasid
					inner join mas_module c on c.modulemasid = b.modulemasid
					inner join mas_module_header d on d.moduleheadermasid = b.moduleheadermasid
					where a.usermasid='$usermasid' and a.active='1' and c.active='1' and b.modulemasid = '".$row1['modulemasid']."' group by d.moduleheadermasid";
				$result2 = mysql_query($sql2);
				if($result2!=null)
				{
				               
            
                                   // echo "<ul>";
                                    
					while($row2 = mysql_fetch_assoc($result2))
					{
					    // MODULE HEADER
                                            
					    //echo "<li>";
					    //echo "<a  class='title' href=''>".$row2['moduleheader']."</a>";	
                                            echo "<div class='column'>";   
                                            echo "<h3>".$row2['moduleheader']."</h3>";
					    $sql3="select concat(d.moduleheader,b.moduledetmasid) as fileid,
						    CONCAT(UCASE(MID(b.filename,1,1)),MID(b.filename,2)) AS filename,
						    b.filepath from mas_module_user a
						    inner join mas_module_det b on b.moduledetmasid = a.moduledetmasid
						    inner join mas_module c on c.modulemasid = b.modulemasid
						    inner join mas_module_header d on d.moduleheadermasid = b.moduleheadermasid
						    where a.active='1' and a.usermasid='$usermasid' and b.moduleheadermasid = '".$row2['moduleheadermasid']."' and b.modulemasid = '".$row1['modulemasid']."';";
					    $result3 = mysql_query($sql3);
					    if($result3!=null)
					    {
						
						echo "<div id='masters'>";
                                                
						while($row3 = mysql_fetch_assoc($result3))
						{						
						    // MODULE FILES
						   // echo "<li>";
                                                    
							echo "<a style='color: white;' href='#'
								    rel='".$row3['fileid']."'
								    name='".$row3['filename']."'
								    location='".$row1['modulename']." >> ".$row2['moduleheader']." >> ".$row3['filename']."'
								    value='".$row3['filepath']."'
								>".$row3['filename']."</a>";
						    //echo "</li>";
						}
						echo "</div>";
					    }
					   // echo "</li>";
                                            echo "</div>";
//                                         
					}
				    //echo "</ul>";
                                       //  echo "</div>";
				}
			    //echo "</li>";
                                echo "</div></div></li>";
			}
		    }
		?>

        <li class="no-sub"><a href="logout.php" target="_top" ><b>Logout</b></a></li>
<!--<a href="logout.php" target="_top">Logout</a>-->
<!--Home Tab starts-->
</ul>
</nav>
<!--Home Tab starts-->
<nav id="tabs" name="tabs">
  <ul>
    <li class="active"><a href="#tab1">Home</a> <span class="ui-icon ui-icon-close" role="presentation">Remove Tab</span></li>
  </ul>  

  <div id="tab1">
    <?php
    $username = $_SESSION['myusername'];
    $search_array = array(
	'admin' => 1,
	'arul' => 2, 'muthu' => 3,
	'dipak' => 4, 'mitesh' => 5
	);
	if (array_key_exists($username, $search_array)) {
    ?>
      <iframe src='graph/tenant_status.php' scrolling='no' width='100%'></iframe>
    <?php
	}
    else
    {
	echo "Welcome to ".$_SESSION['mycompany'];
    }
    ?>
    
<!--Tab ends-->
 </div>
</nav>  
</body>
</html>
