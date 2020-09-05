$(document).ready(function() {
	//var x=0;
	//setInterval("x++",1000); // Increment value every second
	
        // Setup the ajax indicator    
	$("body").append('<div id="ajaxBusy">Working..<img src="../images/spinner.gif"></div>');
	
	$('#ajaxBusy').css({
		display:"none",
		margin:"0px",
		paddingLeft:"0px",
		paddingRight:"0px",
		paddingTop:"0px",
		paddingBottom:"0px",
		position:"absolute",
		right:"3px",
		top:"3px",
		width:"auto",
		color:"pink"
	});
	$("body").append('<div id="percentage">100%</div>');
	$('#percentage').css({
		display:"none",
		margin:"0px",
		paddingLeft:"0px",
		paddingRight:"0px",
		paddingTop:"0px",
		paddingBottom:"0px",
		position:"absolute",
		right:"3px",
		top:"45px",
		width:"auto"
	});
	// Ajax activity indicator bound 
	// to ajax start/stop document events	
	$(document).ajaxStart(function(){ 
		$('#ajaxBusy').show();
		$('#percentage').show();
	}).ajaxStop(function(){ 
		$('#ajaxBusy').hide();
		$('#percentage').hide();
	});	
});