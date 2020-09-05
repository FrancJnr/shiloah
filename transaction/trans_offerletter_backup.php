<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Offer Letter</title>
                 <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="../shopstable.css">
        <script src="../js/jquery-2.1.4.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>  
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');


                function loadAgeMasterRc()
		{
			$sql = "select age, agemasid from mas_age where active =1 and age like 'Per%' and age like 'per%'";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['agemasid'].">".$row['age']."</option>");		
				}
			}
		}
?>
<script type="text/javascript" src="../js/date.js"></script>
<script type="text/javascript" language="javascript">

// $( "input[type=checkbox]" ).on( "click", function() {
//            var n = $( "input:checked" ).val();
//            alert(n);
//          });
      $(document).ready(function(){  
          
      
          
//	(function($) {
//   $.fn.fixMe = function() {
//      return this.each(function() {
//         var $this = $(this),
//            $t_fixed;
//         function init() {
//            $this.wrap('<div class="dataManipDiv" />');
//          //  $this.wrap('<div class="exampleDiv" />');
//          //  $this.wrap('<div class="menuDiv" />');
//            $t_fixed = $this.clone();
//            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
//            resizeFixed();
//         }
//         function resizeFixed() {
//            $t_fixed.find("th").each(function(index) {
//               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
//            });
//         }
//         function scrollFixed() {
//            var offset = $(this).scrollTop(),
//            tableOffsetTop = $this.offset().top,
//            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
//            if(offset < tableOffsetTop || offset > tableOffsetBottom)
//               $t_fixed.hide();
//            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
//               $t_fixed.show();
//         }
//         $(window).resize(resizeFixed);
//         $(window).scroll(scrollFixed);
//         init();
//      });
//   };
//})(jQuery);
//
//$(document).ready(function(){
//   $("table").fixMe();
////   $(".up").click(function() {
////      $('html, body').animate({
////      scrollTop: 0
////   }, 2000);
//// });
//});
    // });


//	$("input[type=checkbox]").on("click", function() {
//  //var n = $( "input:checked" ).val();
//  //alert(n);
//});
        
//        $('#renewal :checkbox').click(function() {
//            var $this = $(this);
//            //alert('hi')
//            // $this will contain a reference to the checkbox   
//            if ($this.is(':checked')) {
//                // the checkbox was checked 
//            } else {
//                // the checkbox was unchecked
//            }
//        });

          $("#renewal").click(function(){
           
            var $this = $(this);
            if ($this.is(':checked')) {
                // the checkbox was checked 
                // alert('hello')
            } else {
                // alert('nono')
                // the checkbox was unchecked
            }
         });
        
        $('#dataManipDiv').hide();
	$('#rtsqrft').attr('readonly', true);
	$('#selectOptionTblSc').hide();
	$('#selectOptionTblRent').hide();
	$('#basicrentval').hide();
	$('#basicscval').hide();
        $('#rentsummary').hide();
        $('#scsummary').hide();
	oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"sPaginationType": "full_numbers"			
	});
	$('[id^="renttype"]').live('blur', function() {
		//var a = $('select.renttype option:selected').val();
		var a = $('#renttype').val();
		if(a =="rent")
		{
			$('#basicrentval').show().focus();
			$('#basicrentval').css("background-color", "violet").select();
			
		}
		else if(a=="rentpersqrft")
		{
			$('#basicrentval').show().focus();
			$('#basicrentval').css("background-color", "violet").select();
		}
		else
		{
		    //$('#basicrentval').hide(); 
		}
	});
       $("#renttype").change(function(){
           
           $('#basicrentval').show().focus();
           $('#basicrentval').css("background-color", "violet").select();
       });
	$('#editleegalfees').live('click', function() {
		
		var a = $('#editleegalfees').attr('checked');
		if (a== true)
		{
			$('#deposit5').attr('readonly',false);
			$('#deposit5').css("background-color", "yellow").select();
			fillDeposit();
		}
		else
		{	$('#deposit5').attr('readonly',true);	
			$('#deposit5').css("background-color", "#f9eae6");
		}
		calcRent();//to recalculate leegal fees
		var a = $('#deposit5').val();
		$('#deposit5').val(commafy(a));
	});
	$('#editstampduty').live('click', function() {
		
		var a = $('#editstampduty').attr('checked');
		if (a== true)
		{
			$('#deposit6').attr('readonly',false);
			$('#deposit6').css("background-color", "yellow").select();
			fillDeposit();
		}
		else
		{		
			$('#deposit6').attr('readonly',true);
			$('#deposit6').css("background-color", "#f9eae6");
			calcRent();//to recalculate stamp duty
		}
	});
	$('#basicrentval').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#basicrentval').live('blur', function() {
		var a = $('#renttype').val();
		var n = parseFloat(removeComma($(this).val()));		
		n = Math.round(n);		
		if(a =="rent")
		{
			$('#rentval0').val(commafy(n.toFixed(2)));
		}
		else if(a=="rentpersqrft")
		{
			var b = parseFloat($('td#shopsize').html());
			var c = Math.round(n*b);
			$('#rentval0').val(commafy(c.toFixed(2)));
		}
			this.value = commafy(n.toFixed(2));
			calcRent();
			var t = $('#sctype').val();
			if(t =="sc")
			{
				var a = parseFloat(removeComma($('#rentval0').val()));
				var b = parseFloat(removeComma($('#basicscval').val()));
				var c = Math.round(a*b/100);
				$('#servicechrgval0').val(commafy(c.toFixed(2)));
				calcSc();
			}
			if ($(this).val()=="") 
			$(this).val('0');
	});
	$('[id^="rentpercentage"]').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('[id^="rentpercentage"]').live('blur', function() {
		calcRent();
		if ($(this).val()=="") 
	        $(this).val('0');
	});
	function calcRent()
	{
            //alert("calcink");
		var i=1;
		var z=0;

              //var age=$('#aged').val();
            // if(age=="Years & Months"){
		$('[id^="rentval"]').each(function(){
			//first value
                       
                       //var className = $('.myclass').attr('class');
			var a = "#rentval"+z;
                        
                  
                       // var valueb=parseFloat(removeComma($(b).val()));
			var value = parseFloat(removeComma($(a).val()));
                        
			//second value
			var m = "#rentpercentage"+i;
                        var n="#"+$('.monthly').attr('id');
			//alert( n.match(/\d+/)[0]+"n :  m"+m.match(/\d+/)[0]);
//                        if((n==m)||(n<m)){
                        if(n<m){
                        //var j=m;
                        var f=parseFloat($(m).val())
                        if(f!=10){
                         var c = parseFloat($(m).val());  
                        }else{
                         var c = 0;  
                        }  
                        }else{
                         var c = parseFloat($(m).val());     
                        }
			var r =Math.round((value*c/100)+value);
			
			//final value
			var b = "#rentval"+i;
			$(b).val(commafy(r.toFixed(2)));

			i++;
			z++;
		});

		var lastRentId = "#rentval"+(z-1);
		//alert($(lastRentId).val());
		$('#amounts').html($(lastRentId).val());
                calcdeposit5(lastRentId);
		calcdeposit6(lastRentId);
		fillDeposit();
	}
	$('[id^="sctype"]').live('blur', function() {
		//var a = $('select.sctype option:selected').val();
		var a = $('#sctype').val();
		if(a =="sc")
		{
			$('#basicscval').show().focus();
			$('#basicscval').css("background-color", "#78c8e2").select();
			
		}
		else if(a=="scpersqrft")
		{
			$('#basicscval').show().focus();
			$('#basicscval').css("background-color", "yellow").select();
		}
		else
		{
			//$('#basicscval').hide();
		}
	});
        $("#sctype").change(function(){
           
         $('#basicscval').show().focus();
	 $('#basicscval').css("background-color", "violet").select();
       });
	$('#basicscval').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('#basicscval').live('blur', function() {
		var a = $('#sctype').val();		
		if(a =="sc")
		{
			var a = parseFloat(removeComma($('#rentval0').val()));
			var b = parseFloat($(this).val());
			var c = Math.round(a*b/100);
			$('#servicechrgval0').val(commafy(c.toFixed(2)));
			
		}
		else if(a=="scpersqrft")
		{
			var a = parseFloat(removeComma($(this).val()));
			var b = parseFloat($('td#shopsize').html());
			var c = Math.round(a*b);
			$('#servicechrgval0').val(commafy(c.toFixed(2)));
		}
		calcSc();
	});
	$('[id^="servicechrgpercentage"]').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('[id^="servicechrgpercentage"]').live('blur', function() {
		calcSc();
		if ($(this).val()=="") 
		$(this).val('0');
	});
	function calcSc()
	{
		var i=1;
		var z=0;
		$('[id^="servicechrgval"]').each(function(){
			//first value
			var a = "#servicechrgval"+z;
			var value = parseFloat(removeComma($(a).val()));
			
			//second value
			var m = "#servicechrgpercentage"+i;
			
			
			
                        //second value
			//var m = "#rentpercentage"+i;
                        var n="#"+$('.monthlysc').attr('id');
			//alert( n.match(/\d+/)[0]+"n :  m"+m.match(/\d+/)[0]);
//                        if((n==m)||(n<m)){
                         if(n<m){
                         var f=parseFloat($(m).val())
                        if(f!=10){
                         var c = parseFloat($(m).val());  
                        }else{
                         var c = 0;  
                        }
                        }else{
                        var c = parseFloat($(m).val());    
                        }
                        
                        
                        var r =Math.round((value*c/100)+value);
  
                        
			//final value
			var b = "#servicechrgval"+i;
			$(b).val(commafy(r.toFixed(2)));
			
			i++;
			z++;
		});
                var lastScId = "#servicechrgval"+(z-1);
                $('#scamounts').html($(lastScId).val());
		fillDeposit();
	}
	$('#deposit5').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit5').live('blur', function() {
		fillDeposit();
		this.value = commafy(this.value);
	})
	$('#deposit6').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit6').live('blur', function() {
		fillDeposit();
		this.value = commafy(this.value);
	})
	$('#depositmonthrent').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#depositmonthrent').live('blur', function() {
		fillDeposit();
	})
	$('#depositmonthsc').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#depositmonthsc').live('blur', function() {
		fillDeposit();
	})
	$('#advancemonthrent').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#advancemonthrent').live('blur', function() {
		fillDeposit();
	})
	$('#advancemonthsc').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#advancemonthsc').live('blur', function() {
		fillDeposit();
	})
	$('#leegalfeevat').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#leegalfeevat').live('blur', function() {
		var i=1;
		$('[id^="rentval"]').each(function(){
				i++;
		});
		var lastRentId = "#rentval"+(i-2);
		calcdeposit5(lastRentId);
		fillDeposit();
	})
	$('#stampdutyvat').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#stampdutyvat').live('blur', function() {
		var i=1;
		$('[id^="rentval"]').each(function(){
				i++;
		});
		var lastRentId = "#rentval"+(i-2);
		calcdeposit6(lastRentId);
		fillDeposit();
	})
	$('#rtsqrft').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit7').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('#deposit7').live('blur', function() {
		fillDeposit();
	})
	function fillDeposit()
	{
		if (($('#rentval0').val() !=0) || ($('#servicechrgval0').val() !=0))
		{
			 rentcycle=$('#rentcycle option:selected').text();
                      
			rentval=0;sc=0;
			if(rentcycle == "Per Quarter")
			{
                             // alert("Quarter");
				rent =Math.round(parseFloat(removeComma($('#rentval0').val()))/3);
				sc = Math.round(parseFloat(removeComma($('#servicechrgval0').val()))/3);
			}
			else if (rentcycle == "Per Year")
			{
                              //alert("Year");
				rent =Math.round(parseFloat(removeComma($('#rentval0').val()))/12);
				sc = Math.round(parseFloat(removeComma($('#servicechrgval0').val()))/12);
			}
			else if (rentcycle == "Per Month")
			{
    
                                rent = $('#rentval0').val();
				sc = $('#servicechrgval0').val();

			}			
						
			
			$('#deposit1').val(commafy(removeComma(rent)*$('#depositmonthrent').val()));
			$('#deposit2').val(commafy(removeComma(sc)*$('#depositmonthsc').val()));
			
			var a = parseFloat(removeComma(rent)*removeComma($('#advancemonthrent').val()));
			var v = '14';//VAT
			var b = Math.round((a+(a*v/100)));
			b = b.toFixed(2);
			$('#deposit3').val(commafy(b));
			
			var a = parseFloat(removeComma(sc)*removeComma($('#advancemonthsc').val()));
			var v = '14';//VAT
			var b = Math.round((a+(a*v/100)));
			b = b.toFixed(2);
			$('#deposit4').val(commafy(b));
			
			$('#deposit8').val(commafy(depositTotal()));

		}
	}
//        function fillDeposit(rentcycle)
//	{
//		if ((rentcycle !=0))
//		{
////			rentcycle = $('#rentcycle').val();
////                        var age=$('#aged').html();
//			rentval=0;sc=0;
//			if(rentcycle == "Per Quarter")
//			{
//				rent =Math.round(parseFloat(removeComma($('#rentval0').val()))/3);
//				sc = Math.round(parseFloat(removeComma($('#servicechrgval0').val()))/3);
//			}
//			else if (rentcycle == "Per Year")
//			{
//				rent =Math.round(parseFloat(removeComma($('#rentval0').val()))/12);
//				sc = Math.round(parseFloat(removeComma($('#servicechrgval0').val()))/12);
//			}
//			else if (rentcycle == "Per Month")
//			{
////                            alert('Here');
////				if (age == "Years & Months")
////			     {
////				//$('#rentcycle').clear()
////                                rent =Math.round(parseFloat(removeComma($('#rentval0').val()))/12);
////				sc = Math.round(parseFloat(removeComma($('#servicechrgval0').val()))/12);
////                                
////                                
////			   }else{
//                                rent = $('#rentval0').val();
//				sc = $('#servicechrgval0').val();
//				
//		
////                            }
//			}			
//						
//			
//			$('#deposit1').val(commafy(removeComma(rent)*$('#depositmonthrent').val()));
//			$('#deposit2').val(commafy(removeComma(sc)*$('#depositmonthsc').val()));
//			
//			var a = parseFloat(removeComma(rent)*removeComma($('#advancemonthrent').val()));
//			var v = '14';//VAT
//			var b = Math.round((a+(a*v/100)));
//			b = b.toFixed(2);
//			$('#deposit3').val(commafy(b));
//			
//			var a = parseFloat(removeComma(sc)*removeComma($('#advancemonthsc').val()));
//			var v = '14';//VAT
//			var b = Math.round((a+(a*v/100)));
//			b = b.toFixed(2);
//			$('#deposit4').val(commafy(b));
//			
//			$('#deposit8').val(commafy(depositTotal()));
//
//		}
//	}
	function depositTotal()
	{
		var v1 = parseFloat(removeComma($('#deposit1').val()));
		var v2 = parseFloat(removeComma($('#deposit2').val()));
		var v3 = parseFloat(removeComma($('#deposit3').val()));
		var v4 = parseFloat(removeComma($('#deposit4').val()));
		var v5 = parseFloat(removeComma($('#deposit5').val()));
		var v6 = parseFloat(removeComma($('#deposit6').val()));
		var v7 = parseFloat(removeComma($('#deposit7').val()));
		var total = (v1+v2+v3+v4+v5+v6+v7);
		////var total = (v1+v2);
		total = total.toFixed(2);
		return total;
	}
	$('#rtsqrftsc').live('blur', function() {
			var sqVal = parseFloat($('td#shopsize').html());
			var b = parseFloat($('#rtsqrftsc').val());
			var c = Math.round(sqVal*b);
			var x = "#servicechrgval0";
			var j = $(x).val(commafy(c));
			var i=1;
			var z=1;
			$('[id^="servicechrgval"]').each(function(){
				var value = parseFloat(removeComma($(this).val()));
				var n = "#servicechrgval"+i;
				$(n).val(commafy(scCalc(value,i,z)));
				i++;
				z++;
			});
		fillDeposit();
	});
	function calcdeposit5(a)
	{
		var v= $('#editleegalfees').attr('checked');
                rentcycle=$('#rentcycle option:selected').text();
                //var b = parseFloat(removeComma($(a).val()));
                var x= parseFloat(removeComma($(a).val()));     
                
		        if(rentcycle == "Per Quarter")
			{
                         var x=(x/3);
                        } else if(rentcycle == "Per Year")
			{
                         var x=x/12;
                        }else if(rentcycle == "Per Month")
			{

                        var x=x;
                        }      
                     
                     
                     if( v== false)
		      {
                         var b=x; 
			var c = $('#leegalfeevat').val();
			var d = Math.round(((b*12*(c/100))*1.14));
			d = d.toFixed(2);
			$('#deposit5').val(commafy(d));
		     }    

			
		
		
	}
	function calcdeposit6(a)
	{
		var v= $('#editstampduty').attr('checked');
                rentcyclez=$('#rentcycle option:selected').text();
                var x= parseFloat(removeComma($(a).val()));
			
		        if(rentcycle == "Per Quarter")
			{
                         var x=(x/3);
                        } else if(rentcycle == "Per Year")
			{
                         var x=x/12;
                        }else if(rentcycle == "Per Month")
			{

                        var x=x;
                        }
                        if( v== false)
		      {                        
                        var b = x;//parseFloat(removeComma($(a).val()));
			var c = $('#stampdutyvat').val();
			var d = Math.round(((b*12*(c/100))));
			d = d.toFixed(2);
			$('#deposit6').val(commafy(d));
                    }
        }
	function removeComma(val)
	{
		return String(val).replace(/\,/g, '');
		//return val;
	}
	function commafy(nStr) {
	nStr += '';
		var x = nStr.split('.');
		var x1 = x[0];
		var x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Offer Letter");
                $("#header2").css('background-color', '#c8dbc9');
                $("#header3").css('background-color', '#c8dbc9');
		$("#header4").css('background-color', '#c8dbc9');
                $("#tblScHeader").css('background-color', '#9cccf3');
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$('#tenantmasid').show()
		$("#offerlettermasid").hide();
		$("#age").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#age").val("");
		$("#description").val("");
		loadTenant();
		clearForm();
	});
	
	function loadTenant()
	{
		 var url="load_offerletter_new.php?item=loadTenant";					
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
                                        $('#tenantmasid').empty();
					$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
					$.each(data.myResult, function(i,response){
						
						//if(response.tradingname !="")
						//	var a = response.tradingname+" ("+response.tenantcode+")";
						//else
						//	var a = response.leasename+" ("+response.tradingname+")";
						var t = response.tradingname;
							if(t !="")
							    var b = response.tradingname;
							else
							    var b = response.leasename;
							var r = response.renewalfromid;
							if(r <=0)
							    var a = b+" ("+response.shopcode+")";
							else
							var a = b+" ("+response.shopcode+" RENEWED)" ;
						$('#tenantmasid').append( new Option(a.toUpperCase(),response.tenantmasid,true,false) );
						
					});
				}
				else
				{
					//alert(response.s);
					alert("No new tenants found !!!");
					$('#tenantmasid').empty();
					$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
				}
			});
                        
//                        var options = $("#tenantmasid option");                    // Collect options         
//                        options.detach().sort(function(a,b) {               // Detach from select, then Sort
//                            var at = $(a).text();
//                            var bt = $(b).text();         
//                            return (at > bt)?1:((at < bt)?-1:0);            // Tell the sort function how to order
//                        });
//                        options.appendTo("#tenantmasid"); 

                        var options = $('#tenantmasid option');
                        var arr = options.map(function(_, o) { return { t: $(o).text(), v: o.value }; }).get();
                        arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
                        options.each(function(i, o) {
                          o.value = arr[i].v;
                          $(o).text(arr[i].t);
                        });
                        
//                        arr.sort(function(o1, o2) {
//                        var t1 = o1.t.toLowerCase(), t2 = o2.t.toLowerCase();
//
//                        return t1 > t2 ? 1 : t1 < t2 ? -1 : 0;
//                      });
		});
	
        
        
        
        
        }
	$('#btnEdit').click(function(){
		$('cc').html('');
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Offer Letter");
		$("#header2").css('background-color', '#d3d3d3');
                $("#header3").css('background-color', '#d3d3d3');
		$("#header4").css('background-color', '#d3d3d3');
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#offerlettermasid").show();
		$('#tenantmasid').hide()
		$("#agemasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$("#offerletter").val("");
		$("#description").val("");		
		loadOfferletter();
		clearForm();
	});
	function loadOfferletter()
	{
		var url="load_offerletter_new.php?item=loadOfferLetter";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#offerlettermasid').empty();
						$('#offerlettermasid').append( new Option("-----Select offer letter-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							//if(response.tradingname !="")
							//	var a = response.tradingname+" ("+response.tenantcode+")";
							//else
							//	var a = response.leasename+" ("+response.tenantcode+")";
							var t = response.tradingname;
							if(t !="")
							    var b = response.tradingname;
							else
							    var b = response.leasename;
							var r = response.renewalfromid;
							if(r <=0)
							    var a = b+" ("+response.shopcode+")";
							else
							var a = b+" ("+response.shopcode+" RENEWED)" ;
							$('#offerlettermasid').append( new Option(a,response.offerlettermasid,true,false) );
						});
					}
					else
					{
						alert(response.msg);
						$('#offerlettermasid').empty();
						$('#offerlettermasid').append( new Option("-----No Offer Letter-----","",true,false) );	
					}
				});		
			});
	}
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if($('#tenantmasid').val() =="" )
		{
			alert("Please select tenant");
			return false;
		}
		if(($('#basicrentval').val() ==0 ) || ($('#basicrentval').val() ==""))
		{
			alert("Please enter Rent Details");
			return false;
		}
	
		var r=confirm("Can you confirm this?");
		if (r == true)
		{       var y=$('#yearsno').val();
                        var m=$('#monthsno').val();
                        var term=y+","+m;
			var url="save_offerletter_new.php?action=Save&term="+term;
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{
							$('#depositmonthrent').val('3');
							$('#depositmonthsc').val('3');
							$('#advancemonthrent').val('14');
							$('#advancemonthsc').val('14');
							$('#leegalfeevat').val('4');
							$('#stampdutyvat').val('2.5');
							$('#cc').html(response.msg);
							$('#tenantDetails').html('');
							$('#rentbody').html('');
							$('#servicechrgbody').html('');
							$('#depositbody').html('');
							$('#premises').html('');
							$('#shopsize').html('');
							$('#leasename').html('');
							$('#term').html('');
							$('#term').val('');
							$('#doo').html('');
							$('#sp').val('');
							$('#doc').html('');
							$('#selectOptionTblRent').hide();
							$('#selectOptionTblSc').hide();
							loadTenant();
						}
						else
						{
							//alert(response.msg);
							$('#cc').html(response.msg);
						}
						//alert(response.msg);
						$('#cc').html(response.msg);
					});
			});
		}
		else
		{
			return false;
		}
	});
        $('#btnSaveAndProceed').click(function(){
		if($('#tenantmasid').val() =="" )
		{
			alert("Please select tenant");
			return false;
		}
		if(($('#basicrentval').val() ==0 ) || ($('#basicrentval').val() ==""))
		{
			alert("Please enter Rent Details");
			return false;
		}
	
		var r=confirm("Can you confirm this?");
		if (r == true)
		{       var y=$('#yearsno').val();
                        var m=$('#monthsno').val();
                        var term=y+","+m;
                        
			var url="save_offerletter_new.php?action=Save&term="+term;
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{
//			
                                            
                                      if(response.msg=="Data Saved Successfully"){  
									       if(response.isrenewal=="0"){
                                        var a = confirm("Offer Letter Created Successfully\nWould You Like to Proceed to Group Offer Letter? ");
										   }else{
										var b = confirm("Offer Letter Created Successfully\nWould You Like to Proceed to Print Offer Letter? ");	   
										   }
											if (a== true)
                                            {
                                              parent.top.$('div[name=masterdivtest]').html("<iframe  src='Group/offerletter.php?action=new' id='the_iframe3' scrolling='yes' width='100%'></iframe>");   
                                            }else if(b==true){
											parent.top.$('div[name=masterdivtest]').html("<iframe  src='reports-pms/print_offerletter.php' id='the_iframe3' scrolling='yes' width='100%'></iframe>");   	
												
											}else{
                                            $('#depositmonthrent').val('3');
                                            $('#depositmonthsc').val('3');
                                            $('#advancemonthrent').val('14');
                                            $('#advancemonthsc').val('14');
                                            $('#leegalfeevat').val('4');
                                            $('#stampdutyvat').val('2.5');
                                            $('#cc').html(response.msg);
                                            $('#tenantDetails').html('');
                                            $('#rentbody').html('');
                                            $('#servicechrgbody').html('');
                                            $('#depositbody').html('');
                                            $('#premises').html('');
                                            $('#shopsize').html('');
                                            $('#leasename').html('');
                                            $('#term').html('');
                                            $('#term').val('');
                                            $('#doo').html('');
                                            $('#sp').val('');
                                            $('#doc').html('');
                                            $('#selectOptionTblRent').hide();
                                            $('#selectOptionTblSc').hide();
                                            loadTenant();
                                            }
                          
                                        }else{
                                          
                                          return
                                      }
         
                             
                                    }
                                    else
                                    {
                                            //alert(response.msg);
                                            $('#cc').html(response.msg);
                                    }
						//alert(response.msg);
				            $('#cc').html(response.msg);
					});
			});
		}
		else
		{
			return false;
		}
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
	$('#btnUpdate').click(function(){
		if($('#offerlettermasid').val() =="" )
		{
			alert("Please select an offerletter");
			return false;
		}
		if(($('#rentval0').val() ==0 ) || ($('#rentval0').val() ==""))
		{
			alert("Please enter Rent details");
			return false;
		}
		var a = $('input[name=sctype]:checked').val();
		if(a == "sqrft")
		{
			if(($('#rtsqrft').val() ==0 ) || ($('#rtsqrft').val() ==""))
			{	
				alert("Please enter Rate/Sqrft");
				return false;
			}	
		}
		
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			var $offerlettermasid = $('#offerlettermasid').val();
			var url="save_offerletter.php?action=Update&offerlettermasid="+$offerlettermasid;
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{
							$('#depositmonthrent').val('3');
							$('#depositmonthsc').val('3');
							$('#advancemonthrent').val('14');
							$('#advancemonthsc').val('14');
							$('#leegalfeevat').val('4');
							$('#stampdutyvat').val('2.5');
							$('#cc').html(response.msg);
							$('#tenantDetails').html('');
							$('#rentbody').html('');
							$('#servicechrgbody').html('');
							$('#depositbody').html('');
							$('#premises').html('');
							$('#shopsize').html('');
							$('#leasename').html('');
							$('#term').html('');
							$('#term').val('');
							$('#doo').html('');
							$('#doc').html('');
							$('#selectOptionTblRent').hide();
							$('#selectOptionTblSc').hide();
							loadOfferletter();
						}
						else
						{
							//alert(response.msg);
							$('#cc').html(response.msg);
						}
						//alert(response.msg);
						$('#cc').html(response.msg);
					});
			});
		}
	});
	
	$("#offerlettermasid").change(function(){
		var $offerlettermasid = $('#offerlettermasid').val();
		clearForm();
		if($offerlettermasid !="")
		{
			var url="load_offerletter.php?item=detailsOfferLetter&itemval="+$offerlettermasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
					var i=0;
					//$("#rentbody").empty();
					$("#servicechrgbody").empty();
					 $.each(data.myResult, function(i,response){
						$('#cc').html('');
					         var tenantDetails = "Attn: "+response.cpname+"<br />"+response.leasename+"<br />"+response.poboxno+"-"+response.pincode+"<br />"+response.city;
                                                    var premises = response.buildingname+" <br />"+response.blockname+"<br />"+response.floorname+" <br />"+response.shopcode;
                                                    $('#tenantDetails').html(tenantDetails);
                                                    $('#premises').html(premises);
						    $('#shopsize').html(response.size+" Sqrft");
                                                    $('#leasename').html(response.leasename+" ("+response.tenantcode+")");
						    $("#hidLeasename").val(response.leasename);
                                                    $('#term').html(response.term);
                                                    $('#doo').html(response.doo);
                                                    $('#doc').html(response.doc);
                                                    var str = response.term;
                                                    var strArray = str.split(' ');
                                                    var cnt =strArray[0];
                                                    var s  = strArray[1].toLowerCase();
                                                    var url="load_offerletter.php?item=loadTransDetFromOfferLetter&itemval="+$offerlettermasid;
						    $('#rentbody').html('');
						    $('#servicechrgbody').html('');
						    $('#depositbody').html('');
                                                    $.getJSON(url,function(data){
                                                	$.each(data.error, function(i,response){
                                                		if(response.s == "Success")
                                                		{
									var a = response.renttype;
									$('#renttype').val(a);
									$('#basicrentval').val(commafy(response.basicrentval));
									$('#basicrentval').show();
									if(a== "rent") 
									{
										$('#basicrentval').css("background-color", "#78c8e2");
									}
									else
									{
										$('#basicrentval').css("background-color", "yellow");
									}
									var b = response.sctype;
									$('#sctype').val(b);
									$('#basicscval').val(response.basicscval);
									$('#basicscval').show();
									if(b== "sc")
									{
										$('#basicscval').css("background-color", "#78c8e2");
									}
									else
									{
										$('#basicscval').css("background-color", "yellow");
									}
									var renttblRows =response.msg;
                                                                        
									$('#rentbody').append(renttblRows);
									
									var servicetblRows =response.tbl;
									$('#servicechrgbody').append(servicetblRows);
									
									var deposit =  response.deposit;
									$('#depositbody').append(deposit);
									
									$('#selectOptionTblSc').show();
									$('#selectOptionTblRent').show();
									var a = response.editleegalfees;
									if(a==1) 
									{
										$('#deposit5').css("background-color", "yellow");
									}
									var b = response.editstampduty;
									if(b==1) 
									{
										$('#deposit6').css("background-color", "yellow");
									}
									
                                                                }
								else
								{
									alert(response.msg);
								}
                                                        });
                                                    });
                                                        i++;
                                            });
					}
					else
					{
						
					       alert(response.msg);
                                               clearForm();
					}
				});
			});
			var url="load_offerletter.php?item=detailsRent&itemval="+$offerlettermasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						
					}
					else
					{
					       $('#cc').html(response.msg);
					       //alert(response.msg);
                                               clearForm();
					}
				});
			});
		}
		else
		{
			clearForm();
		}
	});
	
	$("#tenantmasid").change(function(){
		var $tenantmasid = $('#tenantmasid').val();
		
		clearForm();
		if($tenantmasid !="")
		{
			var url="load_offerletter_new.php?item=detailsTenant&itemval="+$tenantmasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                          //  var i=0;
                                           //$("#rentbody").empty();
                                           $("#servicechrgbody").empty();
                                            $.each(data.myResult, function(i,response){
						$('#cc').html('');
                                                    var tenantDetails = "Attn: "+response.cpname+"<br />"+response.leasename+"<br />"+response.poboxno+"-"+response.pincode+"<br />"+response.city;
                                                    var premises = response.buildingname+" <br />"+response.blockname+"<br />"+response.floorname+" <br />"+response.shopcode;
                                                    $('#tenantDetails').html(tenantDetails);
                                                    $('#premises').html(premises);
						    $('#shopsize').html(response.size+" Sqrft");
                                                    $('#leasename').html(response.leasename+" ("+response.tenantcode+")");
						    $("#hidLeasename").val(response.leasename);
                                                    $('#term').html(response.term);
                                                    $('#term').val(response.term);
                                                    $('#doo').html(response.doo);
                                                    $('#doc').html(response.doc);
                                                    var str = response.term;
                                                    var strArray = str.split(' ');
                                                    var cnt =strArray[0];
                                                    var s  = strArray[1].toLowerCase();
						    
                                                   
                                                      //  i++;
                                            });
					
                                             var url="load_offerletter_new.php?item=loadRentTbl&itemval="+$tenantmasid;
						    $('#rentbody').html('');
						    $('#servicechrgbody').html('');
						    $('#depositbody').html('');
						    $('#selectOptionTblSc').show();
						    $('#selectOptionTblRent').show();
				
                                                    $.getJSON(url,function(data){
                                                	$.each(data.error, function(i,response){
                                                		if(response.s == "Success")
                                                		{
                                                                    var frmdate=response.msg;
                                                                    $('#frmdt').html(frmdate);
                                                                }   
                                                                else
                                                                {
                                                                        alert(response.msg);
                                                                        $('#cc').html(response.msg);
      
                                                                }
                                                        });
                                                    });
                                
                            
                                         }
					else
					{
						//alert(response.msg);
						$('#cc').html(response.msg);
                                                //$("#rentbody").empty();
                                               clearForm();
					}
				});             
                        });
		}
		else
		{
			//$('input[type=text]').val('');
			// $("#rentbody").empty();
			clearForm();
		}
	});
	function clearForm()
	{
		$('#tenantDetails').html('');
                $('#rentbody').html('');
		$('#servicechrgbody').html('');
		$('#depositbody').html('');
		$('#premises').html('');
		$('#shopsize').html('');
		$('#leasename').html('');
		$('#term').html('');
		$('#term').val('');
		$('#doo').html('');
		$('#doc').html('');
		$('#selectOptionTblSc').hide();
		$('#selectOptionTblRent').hide();
		$('#renttype').val('');
		//$('#basicrentval').hide();
		$('#sctype').val('');
		//$('#basicscval').hide();
	}
        
       
        function addCompoundCycle (y,  m, frmdt, rentcycle){
               
                var m = parseInt(removeComma(m));	
                var y = parseInt(removeComma(y));	
                var x=m+y;
          
               for (var j=0; j<x; j++){  
                $tbl  = "<tr>";
               //$tbl +=  "<tr class='hiderent'><td class='hiderent' colspan='5'><input type='hidden' id='rentcycle' name='rentcycle' style='width:20px;' value='"+$rentcycle+"'></tr>";  //hidden field 
		if(j>y-1){
                $tbl += "<td>"+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y).toString('M-d-yyyy')+"<input type='hidden' id='hidFromdateRent"+j+"' name='hidFromdateRent"+j+"' value="+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y).toString('M-d-yyyy')+"> </td>";
                $tbl += "<td>"+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y+1).toString('M-d-yyyy')+"<input type='hidden' id='hidTodateRent"+j+"' name='hidTodateRent"+j+"' value="+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y+1).toString('M-d-yyyy')+"> </td>";
                 } else{
                     
              
                $tbl += "<td>"+Date.parse(frmdt).addYears(j).toString('M-d-yyyy')+"<input type='hidden' id='hidFromdateRent"+j+"' name='hidFromdateRent"+j+"' value="+Date.parse(frmdt).addYears(j).toString('M-d-yyyy')+"> </td>";
                $tbl += "<td>"+Date.parse(frmdt).addYears(j+1).toString('M-d-yyyy')+"<input type='hidden' id='hidTodateRent"+j+"' name='hidTodateRent"+j+"' value="+Date.parse(frmdt).addYears(j+1).toString('M-d-yyyy')+"> </td>";
                
                }   
                if(j == 0){
		$tbl += "<td><input type='text' style='width:150px;' id='rentpercentage"+j+"' dir='rtl' name='rentpercentage"+j+"' value='0' readonly/></td>";
                 }else if(j>y-1){
                     $tbl += "<td><input class='monthly' type='text' style='width:150px;' id='rentpercentage"+j+"' dir='rtl' name='rentpercentage"+j+"' value='10'/></td>";       
                 }else{
                    $tbl += "<td><input type='text' style='width:150px;' id='rentpercentage"+j+"' dir='rtl' name='rentpercentage"+j+"' value='10'/></td>";       
                  }
               if(j < 1)
                $tbl +=  "<td><input type='text' style='width:150px;' id='rentval"+j+"' dir='rtl' name='rentval"+j+"' value='' readonly /></td>";//changed recenntly
	       else
	       $tbl +=  "<td><input type='text' style='width:150px;' id='rentval"+j+"' dir='rtl' name='rentval"+j+"'  value='' readonly/></td>";
		    
               $tbl +=   "<td>"+rentcycle+"</td></tr>";
                $('#rentbody').append($tbl);
                } 
//             
             }
             
               function addCompoundCycleSC(y,  m, frmdt, rentcycle){
//               
                var m = parseInt(removeComma(m));	
                var y = parseInt(removeComma(y));	
                var x=m+y;
          
               for (var j=0; j<x; j++){  
                $tbl  = "<tr>";
		if(j>y-1){          
                $tbl += "<td>"+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y).toString('M-d-yyyy')+"<input type='hidden' id='hidFromdateSc"+j+"' name='hidFromdateSc"+j+"' value="+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y).toString('M-d-yyyy')+"> </td>";
                $tbl += "<td>"+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y+1).toString('M-d-yyyy')+"<input type='hidden' id='hidTodateSc"+j+"' name='hidTodateSc"+j+"' value="+Date.parse(Date.parse(frmdt).addYears(y).toString('M-d-yyyy')).addMonths(j-y+1).toString('M-d-yyyy')+"> </td>";
                 } else{
                $tbl += "<td>"+Date.parse(frmdt).addYears(j).toString('M-d-yyyy')+" <input type='hidden' id='hidFromdateSc"+j+"' name='hidFromdateSc"+j+"' value="+Date.parse(frmdt).addYears(j).toString('M-d-yyyy')+"> </td>";
                $tbl += "<td>"+Date.parse(frmdt).addYears(j+1).toString('M-d-yyyy')+" <input type='hidden' id='hidTodateSc"+j+"' name='hidTodateSc"+j+"' value="+Date.parse(frmdt).addYears(j+1).toString('M-d-yyyy')+"> </td>";
                 }   
                if(j == 0){
		$tbl += "<td><input type='text' style='width:150px;' id='servicechrgpercentage"+j+"' dir='rtl' name='servicechrgpercentage"+j+"' value='0' readonly/></td>";
                 }else if(j>y-1){
                     $tbl += "<td><input class='monthlysc' type='text' style='width:150px;' id='servicechrgpercentage"+j+"' dir='rtl' name='servicechrgpercentage"+j+"' value='10'/></td>";       
                 }else{
                    $tbl += "<td><input type='text' style='width:150px;' id='servicechrgpercentage"+j+"' dir='rtl' name='servicechrgpercentage"+j+"' value='10'/></td>";       
               }
               if(j < 1)
                $tbl +=  "<td><input type='text' style='width:150px;' id='servicechrgval"+j+"' dir='rtl' name='servicechrgval"+j+"' value='' readonly /></td>";//changed recenntly
	       else
	       $tbl +=  "<td><input type='text' style='width:150px;' id='servicechrgval"+j+"' dir='rtl' name='servicechrgval"+j+"'  value='' readonly/></td>";
		    
               $tbl +=   "<td>"+rentcycle+"</td></tr>";
                $('#servicechrgbody').append($tbl);//depositbody
                } 
//             
             }
             
             function AddDepositTable(){
                 
                 		//-table 3 Deposit
                        for(var $i=1;$i<9;$i++)
                        {
		      		
                       $tr3 = "<tr><td align='center'>"+$i+"</td>";
		     if($i==1){// Rent Deposit
		        $tr3 +=  "<td>Security deposit for rent</td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthrent' name='depositmonthrent' value='3' />Month</td>";
			$tr3 +=  "<td align='right'><input  type='text' style='width:150px;' id='deposit1' dir='rtl' name='rentdeposit'  value='0' readonly/></td>";
		     
		     }
		     else if ($i==2){//service charge deposit
		        $tr3 +=  "<td>Security deposit for service charge</td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthsc' name='depositmonthsc' value='3'/>Month</td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' id='deposit2' dir='rtl' name='scdeposit'  value='0' readonly/></td>";			
		     }
		     else if ($i==3){//rent advance
			$tr3 +=  "<td>Advance rent  with VAT</td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthrent' name='advancemonthrent' value='3' />Month</td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' id='deposit3' dir='rtl' name='rentwithvat'  value='0' readonly/></td>";
		     }
		     else if ($i==4){//sc with vat
			$tr3 +=  "<td>Advance service charge with VAT</td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthsc' name='advancemonthsc' value='3' />Month</td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' id='deposit4' dir='rtl' name='scwithvat'  value='0' readonly/></td>";
		     }
		     else if ($i==5){//legal fees
			$tr3 +=  "<td>Legal fees with VAT</td>";
			$tr3 +=  "<td align='right'>";
			$tr3 +=  "<input type='text' style='width:150px;' dir ='rtl' id='leegalfeevat' name='leegalfeevat' value='4'/>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$tr3 +=  "<td align='right'>";
			$tr3 +=  "<input type='checkbox' id='editleegalfees' name='editleegalfees'> Edit Value&nbsp&nbsp&nbsp";
			$tr3 +=  "<input type='text' style='width:150px;' id='deposit5' dir='rtl' name='leegalfees'  value='0' readonly/></td>";
		     }
		     else if ($i==6){
			$tr3 +=  "<td>Stamp Duty</td>";
			$tr3 +=  "<td align='right'>";
			$tr3 +=  "<input type='text' style='width:150px;' dir ='rtl' id='stampdutyvat' name='stampdutyvat' value='2.5'/>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$tr3 +=  "<td align='right'>";
			$tr3 +=  "<input type='checkbox' id='editstampduty' name='editstampduty'> Edit Value&nbsp&nbsp&nbsp";
			$tr3 +=  "<input type='text' style='width:150px;' id='deposit6' dir='rtl' name='stampduty'  value='0' readonly/></td>";
		     }
		     else if ($i==7){
			$tr3 +=  "<td>Registration Fees</td>";
			$tr3 +=  "<td></td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' id='deposit7' dir='rtl' name='registrationfees'  value='2500'/></td>";
		     }
		     else if ($i==8){
			$tr3 +=  "<td><b>Total<b></td>";
			$tr3 +=  "<td></td>";
			$tr3 +=  "<td align='right'><input type='text' style='width:150px;' id='deposit8' dir='rtl' name='depositTotal' readonly/></td>";
		     }
			$tr3 +=  "</tr>";
                        $('#depositbody').append($tr3);//depositbody
	       }
                 
             }
//        function getToDate(frmdate, period){
//
//          var d = Date.parse(frmdate).addYears(1).toString('M-d-yyyy');
//          var d2 = Date.parse(frmdate).addMonths(12).toString('M-d-yyyy');
//          alert(d.toString('M-d-yyyy'));
//          
//        if(period=="years"){
//         var todt=d;
//        // todt = CurrentDate.setDate(CurrentDate.getDay() -1);
//        }else{
//          var todt=d2;  
//        // todt =CurrentDate.setDate(CurrentDate.getDay() +30); //date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdate)) . " + 30 Days"));
//        }
//        return todt;
//       }   
          
        
        
         $('#btnAddYear').click(function(){
             
            $("#rentbody").empty();
             $("#servicechrgbody").empty();
               $("#depositbody").empty();
             
            
             var y=$('#yearsno').val();
              var m=$('#monthsno').val();
             if(y==""){
               alert("Please Fill In Years!");  
               $('#yearsno').focus();
             }
//             }else if(m==""){
//                alert("Please Fill In Months!");  
//               $('#monthsno').focus(); 
//             }
            
             //var frmdt=$('#frmdt').html();
             var frmdt= $('#doc').text();
             var rentcycle=$('#rentcycle option:selected').text();
              if(rentcycle=='--Select Rent Cycle--'){
                 alert('Kindly Select Rent Cycle!');
                 return;
             }
	    addCompoundCycle (y,  0, frmdt, rentcycle);
            addCompoundCycleSC(y,  0, frmdt, rentcycle);
            AddDepositTable();
	 });
         
           $('#btnAddMonth').click(function(){
             $("#rentbody").empty();
             $("#servicechrgbody").empty();
             $("#depositbody").empty();
              var y=$('#yearsno').val();
              var m=$('#monthsno').val();
             if(m==""){
               alert("Please Fill In Months!");  
               $('#monthsno').focus();
               
             }
            
             //var frmdt=$('#frmdt').html();
             var frmdt= $('#doc').text();
             var rentcycle=$('#rentcycle option:selected').text();
              if(rentcycle=='--Select Rent Cycle--'){
                 alert('Kindly Select Rent Cycle!');
                 return;
             }
	    addCompoundCycle (0,  m, frmdt, rentcycle);
            addCompoundCycleSC(0,  m, frmdt, rentcycle);
            AddDepositTable();
	 });
         
         $('#btnAddCompound').click(function(){
            
             $("#rentbody").empty();
             $("#servicechrgbody").empty();
             $("#depositbody").empty();
             var y=$('#yearsno').val();
              var m=$('#monthsno').val();
             if(y==""){
               alert("Please Fill In Years!");  
               $('#yearsno').focus();
               
             }else if(m==""){
                alert("Please Fill In Months!");  
               $('#monthsno').focus(); 
             }
            
             //var frmdt=$('#frmdt').html();
             var frmdt= $('#doc').text();
			 alert(frmdt);
             var rentcycle=$('#rentcycle option:selected').text();
              if(rentcycle=='--Select Rent Cycle--'){
                 alert('Kindly Select Rent Cycle!');
                 return;
             }
             addCompoundCycle (y,  m, frmdt, rentcycle);
            addCompoundCycleSC(y,  m, frmdt, rentcycle);
            AddDepositTable();
	 });
         //addCompoundCycle
	
        $('[id^="monthsno"]').live('blur', function() {
            
         $("#term").empty();
	var y=$('#yearsno').val();
         var m=$('#monthsno').val();
         if(y==""){
          $('#term').html(0+" years "+m+" months ");	   
         }else{
           $('#term').html(y+" years "+m+" months ");  
         }
	  
	});
        $('[id^="yearsno"]').live('blur', function() {
		
        $("#term").empty();
	 var y=$('#yearsno').val();
         var m=$('#monthsno').val();
         if(m==""){
          $('#term').html(y+" years "+0+" months ");	   
         }else{
           $('#term').html(y+" years "+m+" months ");  
         }
	  
	});



        });
</script>

</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<!--<div id="container">-->
<h1>Offer Letter</h1>
<div id="menuDiv" width="100%" align="center">
<table>
		<tr style="float:right">
			<td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
			<td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>
			<td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
		</tr>
<!--                <tr style="float:left"><td colspan=2>
			<b>Draft Offer Letter</b><input type="radio" id="offerlettertype" name="offerlettertype" value="Draft" checked/> |
			<b>Finalized Offer Letter</b><input type="radio" id="offerlettertype" name="offerlettertype" value="Finalized " /> 
		</td>
                </tr>-->
	</table>
</div>
<br>
<div id="exampleDiv">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>							
							<th>Tenant</th>
							<th>Premises</th>
							<th>Term</th>
							<th>Rent Cycle</th>
						</tr>
					</thead>
					<tbody id="tbodyContent" >
				<?php
					$companymasid = $_SESSION['mycompanymasid'];
					$sql = "select a.leasename,a.tradingname,a.tenantcode,b.buildingname,c.blockname,d.floorname,e.shopcode,f.age as 'term' , g.age as 'rentcycle' from\n"
					. "trans_offerletter t\n"
					. "inner join mas_tenant a on a.tenantmasid = t.tenantmasid\n"
					. "inner join mas_building b on b.buildingmasid = a.buildingmasid\n"
					. "inner join mas_block c on c.blockmasid = a.blockmasid\n"
					. "inner join mas_floor d on d.floormasid = a.floormasid\n"
					. "inner join mas_shop e on e.shopmasid = a.shopmasid\n"
					. "inner join mas_age f on f.agemasid = a.agemasidlt\n"
					. "inner join mas_age g on g.agemasid = a.agemasidrc\n"
					. "where t.companymasid=$companymasid and a.active='1' order by leasename asc";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;$tradingname="";
							   while ($row = mysql_fetch_assoc($result))
								   {
									//if($row['tradingname'] != "")
									//$tenant = $row["tradingname"]." (".$row["tenantcode"].")";
									//else
									//$tenant = $row["leasename"]." (".$row["tenantcode"].")";
									//$premisis = $row["buildingname"].",<br>".$row["blockname"].",<br>".$row["floorname"].",<br>".$row["shopcode"].".";
									$leasename = $row["leasename"];
									$premisis = $row["shopcode"];
									$term = $row["term"];
                                                                        $rentcycle = $row["rentcycle"];
									if($row["tradingname"] !="")
                                                                        $tradingname ="  <b>T/A</b>  ".$row["tradingname"];
									$leasename .= $tradingname;
                                                                        
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$leasename."</td>
									<td>".$premisis."</td>
									<td>".$term."</td>
                                                                        <td>".$rentcycle."</td>";
									echo $tr;
								}
						}
					}		
				?>
					</tbody>
					<tfoot>
						<tr>
							<th>Index</th>							
							<th>Tenant</th>
							<th>Premises</th>
							<th>Term</th>
							<th>Rent Cycle</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table2" width='100%'>
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan=4>
				Create New Offer Letter	
			</th>
		</tr>
	</thead>
	<tbody>
        <tr  align='center'>
		
	</tr>
	<tr id="selecTenant">
		<td>
			Select Tenant: <font color="red">*</font>
		</td>
		<td>
			<select id="tenantmasid" name="tenantmasid">
				<option value="" selected>--Select Tenant--</option>
			</select>
			<select id="offerlettermasid" name="offerlettermasid">
				<option value="" selected>--Select Offer Letter--</option>
			</select>
		</td>	
		<td>
			Date:
		</td>
                <td colspan=3>
			<?php echo date("d-F-Y");?>
			<input type="hidden" id="hidLeasename" name="hidLeasename" value="">
		</td>
	</tr>
	<tr>
		<td>
			Land Lord:
		</td>
		<td>
			<?php echo "<b>".strtoupper($_SESSION['mycompany']); ?>
		</td>
                <td>
			Tenant:
		</td>
		<td id="leasename">
			
		</td>
	</tr>
	<tr>
		<td>
			Tenant Address:
		</td>
		<td id="tenantDetails">
			
		</td>
		<td>
			Premises:
		</td>
		<td id=premises>
			
		</td>
	</tr>
        <tr>
		<td>
			Shop Size:
		</td>
		<td id=shopsize>
			
		</td>
                <td>
			Term:
		</td>
		<td id=term>
			
		</td>
	</tr>
        <tr>
		<td>
			Date of Occupation:
		</td>
		<td id=doo>
			
		</td>
                <td>
			Date of Commencement:
		</td>
		<td id=doc>
			
		</td>
	</tr>
<!--         <tr>
		<td>
                     <b>Rent Chart</b>
                </td>
		<td colspan=3>-->
<!--			<div id="selectOptionTblRent">
				<select id='renttype' name='renttype'>
					<option selected='selected' value=''>Please select Rent Type</option>
					<option value='rent'>Flat Rate</option>
					<option value='rentpersqrft'>Rt/Sqrft</option>
				</select>
				<input type="text" style="width:150px; background:#fc9;" dir='rtl' id="basicrentval" name="basicrentval"/>
			</div>-->
<!--                </td>
        </tr>-->
        <tr>
		<td colspan=4>
			<table id="renttbl" class=table2 width='98%'>
                            <tr>
                                <thead id="header2">
                                    <th>From</th>
                                    <th>To</th>
                                    <th>yearly hike %</th>
                                    <th>Amount (KSH)</th>
                                    <th>Period</th>
    
                                </thead>
                            </tr>
                            <tr><center><b>
                              RENT SUMMARY </b><br><br>
                                <b>    YEARS:   </b><input type="number" required style="width:150px; background: violet;" dir='rtl' id="yearsno"  placeholder="Enter Years"/>
                                <b>    MONTHS:   </b><input type="number" required style="width:150px; background: violet;" dir='rtl' id="monthsno"  placeholder="Enter Months"/>
                                <b>    RENT CYCLE:   </b><select required style="width:150px; background: violet;" id="rentcycle" name="rentcycle">
                                        <option value="" selected>--Select Rent Cycle--</option>
                                        <?php loadAgeMasterRc();?>
                                </select>
                            </tr>
                              <tr id="data">
                               <center></b><br>
                              (Years Only)<button id="btnAddYear" type='button' >Add Yearly Cycle</button>
                              (Months Only)<button id="btnAddMonth" type='button' >Add Monthly Cycle</button>
                              (Years & Months)<button id="btnAddCompound" type='button' >Add Compound Cycle</button><br><br></center>
                                </tr>
                               <tr><center> <div id="selectOptionTblRent">
				<b>Rent Chart: </b><select id='renttype' name='renttype'>
					<option selected='selected' value=''>Please select Rent Type</option>
					<option value='rent'>Flat Rate</option>
					<option value='rentpersqrft'>Rt/Sqrft</option>
				</select>
				<input type="text" style="width:150px; background:violet;" dir='rtl' id="basicrentval" name="basicrentval"/>
			       </div></center></tr>
                                <tr id="rentsummary">
                                <td><b id="from"> </b></td>
                                <td><b id="to"></b></td>
                                <td><b id="hike"></b></td>
                                <td  style="background:#fc9;" ><b id="amounts"></b></td>
                                <td><b id="periods"></b></td>
                            </tr>
                                 
                            <tbody id=rentbody>
                                
                                
                                
                                
                                
                            </tbody>
                        </table>
                </td>
	</tr>
         <tr>
		<td>
                     <b>Service Charge Chart</b>
                </td>
		<td colspan=3>
			<div id="selectOptionTblSc">
				<select id='sctype' name='sctype'>
					<option selected='selected' value=''>Please select Service Charge Type</option>
					<option value='sc'>% In Rent</option>
					<option value='scpersqrft'>Rt/Sqrft</option>
				</select>
				<input type="text" style="width:150px; background:#fc9;" dir='rtl' id="basicscval" name="basicscval"/>
                                <!--<input type="hidden" id="aged" name="aged"/>-->
			</div>
                </td>
        </tr>
         <tr>
		<td colspan=4>
			<table id="servicechrgtbl" class=table2 width='98%'>
                            <tr>
                                <thead id="header3">
                                    <th>From</th>
                                    <th>To</th>
                                    <th>yearly hike %</th>
                                     <th>Amount (KSH)</th>
                                    <th>Period</th>
                                </thead>
                            </tr>
                            <tr><center><b>
                              SERVICE CHARGE SUMMARY 
                            </b></center></tr>
                               <tr id="scsummary">
                                <td><b id="scfrom"> </b></td>
                                <td><b id="scto"></b></td>
                                <td><b id="schike"></b></td>
                                 <td  style="background:#fc9;" ><b id="scamounts"></b></td>
                                <td><b id="scperiods"></b></td>
      
                            </tr>
                            <tbody id=servicechrgbody>
                                
                                
                            </tbody>
                        </table>
                </td>
	</tr>
	  <tr><td colspan=4>
                     <b>DEPOSIT SUMMARY</b>
                </td></tr>
<!--        <tr>
                <td colspan=4>
                     Is Renewal:<input type="checkbox" name="renewal"  id="renewal" value="1"> 
                </td>
         </tr>-->
         <tr>
		<td colspan=4>
			<table id="deposittbl" class=table2 width='98%'>
                            <tr>
                                <thead id="header4">
					<th>Index</th>
					<th>Description</th>
					<th>Month & VAT</th>
					<th>Amount (K)</th>
                                </thead>
                            </tr>
                            <tbody id=depositbody>
                            </tbody>
                        </table>
                </td>
	 </tr>
	<tr id="newTr">
		<td align='center'>
			<button type="reset" id="btnReset">Reset</button>
		</td>
		<td align='center' colspan=2>
			<button type="button" id="btnSave">Create New Offer Letter</button>
		</td>
                <td align='center' colspan=2>
			<button type="button" id="btnSaveAndProceed">Create And Proceed</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
		</td>
		<td colspan=3>
			<button type="button" id="btnUpdate">Update Offer Letter</button>
		</td>
	</tr>
	</tbody>
</table>

</div>
<!--</div> Main Div-->
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</body>
</html>
