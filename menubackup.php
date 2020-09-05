<?php
    include('config.php');
    session_start();
    if(!isset($_SESSION['myusername']))     
    {
	 header("location:index.php");
    }    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>Mega Properties Group</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />-->    
    <!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
    <!--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
    <link rel="stylesheet" href="menu_jquery/themes/start/jquery-ui.css" />
    <script src="menu_jquery/jquery-1.9.1.js"></script>
    <script src="menu_jquery/jquery-ui.js"></script>    
    <style type="text/css">
	body {
	    background-color: #d5eaff;
	    font: 13px 'trebuchet MS', Arial, Helvetica;
	}
	/* You don't need the above styles, they are demo-specific ----------- */
	
	#menu, #menu ul {
		margin: 0;
		padding: 0;
		list-style: none;
	}
	
	#menu {
		width: auto;
		margin: 0px auto;
		border: 1px solid #808080;
		background-color: #111;
		background-image: -moz-linear-gradient(#444, #111); 
		background-image: -webkit-gradient(linear, left top, left bottom, from(#444), to(#111));	
		background-image: -webkit-linear-gradient(#444, #111);	
		background-image: -o-linear-gradient(#444, #111);
		background-image: -ms-linear-gradient(#444, #111);
		background-image: linear-gradient(#444, #111);
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		border-radius: 6px;
		-moz-box-shadow: 0 1px 1px #777, 0 1px 0 #666 inset;
		-webkit-box-shadow: 0 1px 1px #777, 0 1px 0 #666 inset;
		box-shadow: 0 1px 1px #777, 0 1px 0 #666 inset;
	}
	
	#menu:before,
	#menu:after {
		content: "";
		display: table;
	}
	
	#menu:after {
		clear: both;
	}
	
	#menu {
		zoom:1;
	}
	
	#menu li {
		float: left;
		border-right: 1px solid #808080;
		-moz-box-shadow: 1px 0 0 #444;
		-webkit-box-shadow: 1px 0 0 #444;
		box-shadow: 1px 0 0 #444;
		position: relative;
	}
	
	#menu a {
		float: left;
		padding: 12px 30px;
		color: #ffffff;
		text-transform: uppercase;
		font: bold 12px Arial, Helvetica;
		text-decoration: none;
		text-shadow: 0 1px 0 #000;
	}
	
	#menu li:hover > a {
		color: #00ff00;
	}
	
	*html #menu li a:hover { /* IE6 only */
		color: #fafafa;
	}
	
	#menu ul {
		margin: 20px 0 0 0;
		_margin: 0; /*IE6 only*/
		opacity: 0;
		visibility: hidden;
		position: absolute;
		top: 32px;
		left: 0;
		z-index: 1;    
		background: #444;
		background: -moz-linear-gradient(#444, #111);
		background-image: -webkit-gradient(linear, left top, left bottom, from(#444), to(#111));
		background: -webkit-linear-gradient(#444, #111);    
		background: -o-linear-gradient(#444, #111);	
		background: -ms-linear-gradient(#444, #111);	
		background: linear-gradient(#444, #111);
/*		-moz-box-shadow: 0 -1px rgba(255,255,255,.3);
		-webkit-box-shadow: 0 -1px 0 rgba(255,255,255,.3);
		box-shadow: 0 -1px 0 rgba(255,255,255,.3);	
		-moz-border-radius: 3px;
		-webkit-border-radius: 3px;
		border-radius: 3px;*/
/*		-webkit-transition: all .2s ease-in-out;
		-moz-transition: all .2s ease-in-out;
		-ms-transition: all .2s ease-in-out;
		-o-transition: all .2s ease-in-out;
		transition: all .2s ease-in-out;  */
	}

	#menu li:hover > ul {
		opacity: 1;
		visibility: visible;
		margin: 0;
	}
	
	#menu ul ul {
		top: 0;
		left: 150px;
		margin: 0 0 0 20px;
		_margin: 0; /*IE6 only*/
		-moz-box-shadow: -1px 0 0 rgba(255,255,255,.3);
		-webkit-box-shadow: -1px 0 0 rgba(255,255,255,.3);
		box-shadow: -1px 0 0 rgba(255,255,255,.3);		
	}
	
	#menu ul li {
		float: none;
		display: block;
		border: 0;
		_line-height: 0; /*IE6 only*/
		-moz-box-shadow: 0 1px 0 #111, 0 2px 0 #666;
		-webkit-box-shadow: 0 1px 0 #111, 0 2px 0 #666;
		box-shadow: 0 1px 0 #111, 0 2px 0 #666;
	}
	
	#menu ul li:last-child {   
		-moz-box-shadow: none;
		-webkit-box-shadow: none;
		box-shadow: none;    
	}
	
	#menu ul a {    
		padding: 10px;
		width: 130px;
		_height: 10px; /*IE6 only*/
		display: block;
		white-space: nowrap;
		float: none;
		text-transform: none;
	}
	
	#menu ul a:hover {
		background-color: #0186ba;
		background-image: -moz-linear-gradient(#04acec,  #0186ba);	
		background-image: -webkit-gradient(linear, left top, left bottom, from(#04acec), to(#0186ba));
		background-image: -webkit-linear-gradient(#04acec, #0186ba);
		background-image: -o-linear-gradient(#04acec, #0186ba);
		background-image: -ms-linear-gradient(#04acec, #0186ba);
		background-image: linear-gradient(#04acec, #0186ba);
	}
	
	#menu ul li:first-child > a {
		-moz-border-radius: 3px 3px 0 0;
		-webkit-border-radius: 3px 3px 0 0;
		border-radius: 3px 3px 0 0;
	}
	
	#menu ul li:first-child > a:after {
		content: '';
		position: absolute;
		left: 40px;
		top: -6px;
		border-left: 6px solid transparent;
		border-right: 6px solid transparent;
		border-bottom: 6px solid #444;
	}
	
	#menu ul ul li:first-child a:after {
		left: -6px;
		top: 50%;
		margin-top: -6px;
		border-left: 0;	
		border-bottom: 6px solid transparent;
		border-top: 6px solid transparent;
		border-right: 6px solid #3b3b3b;
	}
	
	#menu ul li:first-child a:hover:after {
		border-bottom-color: #04acec; 
	}
	
	#menu ul ul li:first-child a:hover:after {
		border-right-color: #0299d3; 
		border-bottom-color: transparent; 	
	}
	
	#menu ul li:last-child > a {
		-moz-border-radius: 0 0 3px 3px;
		-webkit-border-radius: 0 0 3px 3px;
		border-radius: 0 0 3px 3px;
	}
	
	/* Mobile */
	#menu-trigger {
		display: none;
	}

	@media screen and (max-width: 600px) {

		/* nav-wrap */
		#menu-wrap {
			position: relative;
		}

		#menu-wrap * {
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
		}

		/* menu icon */
		#menu-trigger {
			display: block; /* show menu icon */
			height: 40px;
			line-height: 40px;
			cursor: pointer;		
			padding: 0 0 0 35px;
			border: 1px solid #808080;
			color: #fafafa;
			font-weight: bold;
			background-color: #111;
			-moz-border-radius: 6px;
			-webkit-border-radius: 6px;
			border-radius: 6px;
			-moz-box-shadow: 0 1px 1px #777, 0 1px 0 #666 inset;
			-webkit-box-shadow: 0 1px 1px #777, 0 1px 0 #666 inset;
			box-shadow: 0 1px 1px #777, 0 1px 0 #666 inset;
		}
		
		/* main nav */
		#menu {
			margin: 0; padding: 10px;
			position: absolute;
			top: 20px;
			width: 100%;
			z-index: 1;
			background-color: #444;
			display: none;
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;		
		}

		#menu:after {
			content: '';
			position: absolute;
			left: 25px;
			top: -8px;
			border-left: 8px solid transparent;
			border-right: 8px solid transparent;
			border-bottom: 8px solid #444;
		}	

		#menu ul {
			position: static;
			visibility: visible;
			opacity: 1;
			margin: 0;
			background: none;
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;				
		}

		#menu ul ul {
			margin: 0 0 0 20px !important;
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;		
		}

		#menu li {
			position: static;
			display: block;
			float: none;
			border: 0;
			margin: 5px;
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;			
		}

		#menu ul li{
			margin-left: 20px;
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;		
		}

		#menu a{
			display: block;
			float: none;
			padding: 0;
			color: #ffffff;
		}

		#menu a:hover{
			color: #fafafa;
		}	

		#menu ul a{
			padding: 0;
			width: auto;		
		}

		#menu ul a:hover{
			background: none;	
		}

		#menu ul li:first-child a:after,
		#menu ul ul li:first-child a:after {
			border: 0;
		}		

	}

	@media screen and (min-width: 600px) {
		#menu {
			display: block !important;
		}
	}	

	/* iPad */
	.no-transition {
		-webkit-transition: none;
		-moz-transition: none;
		-ms-transition: none;
		-o-transition: none;
		transition: none;
		opacity: 1;
		visibility: visible;
		display: none;  		
	}

	#menu li:hover > .no-transition {
		display: block;
	}
    /*tab*/
    #dialog label, #dialog input { display:block; }
    #dialog label { margin-top: 0.5em; }
    #dialog input, #dialog textarea { width: 95%; }
    #tabs { margin-top: 1em; }
    #tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
    #add_tab { cursor: pointer; }
    div,iframe{
	border: none;
        height:1500px;
    }
    #left{float:left;width:400px;}
    #right{float:right;width:400px;}
    #center{margin:0 auto;width:400px;}s
</style>
<!--  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
  <script type="text/javascript" language="javascript">
    $(function() {
//    $('[id^="tab"]').tabs().css({
//	    'min-height': '500px',
//	    'height': 'auto',
//	    'overflow': 'auto'
//    });
    $('[id^="tab"]').css({
	    'min-height': '100px',
	    'height': '100%',
	    'overflow':'auto'
    });
    
    
    // Tabs                                                                                                              
    var $tabs = $("#tabs").tabs();
    
    $("#masters a").click(function() {        
	addtabinform($(this));
    });    
    // actual addTab function: adds new tab using the input from the form above
    function addtabinform(link) {
	// If tab already exist in the list, return
    if ($("#" + $(link).attr("rel")).length != 0)    
    return;      
      var tabDisplayName  =  $(link).attr("name");
      var tabLocation = $(link).attr("location");
      var tabId = $(link).attr("rel");
      var path = $(link).attr("value");
      var tabContent = "<p>"+$(link).attr("location") +"</p><br><iframe src='"+ path+"' id='the_iframe"+tabCounter+"' scrolling='yes' width='99%'/>";      
      var label = tabDisplayName || "Tab " + tabCounter,
        id = $(link).attr("rel"),
	//id = "tab"+tabCounter,	
        li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
        tabContentHtml = tabContent || "Tab " + tabCounter + " content.";    
      tabs.find( ".ui-tabs-nav" ).append( li );
      //tabs.append( "<div id='" + id + "'><p>" + tabLocation + "</br><iframe width='100%' id='the_iframe"+tabCounter+"' src='"+ path+"' scrolling='no' id='"+tabId+"' onLoad='calcHeight("+tabCounter+");' frameborder='0' /></p></div>" );
      tabs.append( "<div style='min-height: 100%;height: auto !important;' id='"+id+"'>" + tabContentHtml +"</div>" );            
      tabs.tabs( "refresh");
      ////alert(tabCounter);
      tabs.tabs( "option", "active",tabCounter-1);      
      tabCounter++;      
    }            
    var tabTitle = $( "#tab_title" ),
      tabContent = $( "#tab_content" ),
      tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
      tabCounter = 2;
    
    var tabs = $( "#tabs" ).tabs();
    //tabs.tabs( "option", "active",tabCounter-1); 
 
    // close icon: removing the tab on click
    tabs.delegate( "span.ui-icon-close", "click", function() {
      var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
      $( "#" + panelId ).remove();
      tabs.tabs( "refresh" );
    });
 
    tabs.bind( "keyup", function( event ) {
      if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
        var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
        $( "#" + panelId ).remove();
        tabs.tabs( "refresh" );
      }
    });     
    $(".title").click(function(event) {
	event.preventDefault(); 
    });    
  });
    function frameHeight(s)
    {	
        //document.getElementById('the_iframe'+s).height="5000px";
    }   
  </script>
<!--Tab ends-->

</head>
<body>
    <!--<img src='images/mgagrp.png' height='150' width='150'/>-->
	<ul id="menu">
		<li><a class='title' href="#"><?php echo $_SESSION['mycompany'];?>.</a></li>
		<li><a class='title' href="#">Welcome <?php echo $_SESSION['myusername'];?></a></li>
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
			    echo "<li>";
				echo "<a  class='title' href=''>".$row1['modulename']."</a>";
				$sql2="select CONCAT(UCASE(MID(d.moduleheader,1,1)),MID(d.moduleheader,2)) as moduleheader,
					d.moduleheader as head ,d.moduleheadermasid from mas_module_user a
					inner join mas_module_det b on b.moduledetmasid = a.moduledetmasid
					inner join mas_module c on c.modulemasid = b.modulemasid
					inner join mas_module_header d on d.moduleheadermasid = b.moduleheadermasid
					where a.usermasid='$usermasid' and a.active='1' and c.active='1' and b.modulemasid = '".$row1['modulemasid']."' group by d.moduleheadermasid";
				$result2 = mysql_query($sql2);
				if($result2!=null)
				{
				    echo "<ul>";
					while($row2 = mysql_fetch_assoc($result2))
					{
					    // MODULE HEADER
					    echo "<li>";
						echo "<a  class='title' href=''>".$row2['moduleheader']."</a>";					    
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
						//echo "<ul id='".$row2['head']."'>";
						echo "<ul id='masters'>";
						while($row3 = mysql_fetch_assoc($result3))
						{						
						    // MODULE FILES
						    echo "<li>";
							echo "<a    href='#'
								    rel='".$row3['fileid']."'
								    name='".$row3['filename']."'
								    location='".$row1['modulename']." >> ".$row2['moduleheader']." >> ".$row3['filename']."'
								    value='".$row3['filepath']."'
								>".$row3['filename']."</a>";
						    echo "</li>";
						}
						echo "</ul>";
					    }
					    echo "</li>";
					}
				    echo "</ul>";
				}
			    echo "</li>";
			}
		    }
		?>
		<li><a href="logout.php" target="_top">Logout</a></li>
	</ul>
<!--<script type="text/javascript">
    $(function() {
		if ($.browser.msie && $.browser.version.substr(0,1)<7)
		{
		$('li').has('ul').mouseover(function(){
			$(this).children('ul').css('visibility','visible');
			}).mouseout(function(){
			$(this).children('ul').css('visibility','hidden');
			})
		}

		/* Mobile */
		$('#menu-wrap').prepend('<div id="menu-trigger">Menu</div>');		
		$("#menu-trigger").on("click", function(){
			$("#menu").slideToggle();
		});

		// iPad
		var isiPad = navigator.userAgent.match(/iPad/i) != null;
		if (isiPad) $('#menu ul').addClass('no-transition');      
    });          
</script>-->
<!--Home Tab starts-->
<div id="tabs">
  <ul>
    <li><a href="#tab1">Home</a> <span class="ui-icon ui-icon-close" role="presentation">Remove Tab</span></li>
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
    <iframe src='graph/tenant_status.php' scrolling='no' width='100%'/>
    <?php
	}
    else
    {
	echo "Welcome to ".$_SESSION['mycompany'];
    }
    ?>
    
      <?php
      
      
      
      
      
      

       ?>
  </div>  
</div>
<!--Tab ends-->
</body>
</html>