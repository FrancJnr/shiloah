<html>
<head>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<style>
 
 input[type=text] {
    background: 
      linear-gradient(#000, #000) center bottom 5px /calc(100% - 10px) 2px no-repeat;
    background-color: #fcfcfc;
    border: 0.5px solid;
    padding: 10px;
    background-color: transparent !important;
    border: 0px solid !important;
    height: 30px !important;
    width: 360px !important;
    color: #CCC !important;
}

</style>
</head>
<?php
if(isset($_POST['submit'])){

    //if no errors carry on
    if(!isset($error)){

        //create html of the data
        ob_start();
        ?>


<div class="container" style="width:100%">
<h1 style="text-align:center;">TENANCY VACATING FORM</h1>
<br><br>

<p><i><b>CONFIRMATION OF THE STATE OF THE PREMISES AT THE TIME OF VACATING</b><i></p>
<form action="tenancy_vacating_form.php" method="post">
<table class="table">
    <tbody>
    <tr style="text-align:left !important;">
      <div class="form-group form-inline">
        <td>      
          <label  for="tenant_name"><b>NAME OF TENANT:</b></label>
          <label  for="tenant_name"><?php echo $_POST['tenatnt_name']?></label>
        </td>
      </div>
    </tr>
    <tr style="text-align:left !important;">
      <div class="form-group form-inline">
        <td>      
          <label  for="location"><b>LOCATION (floor):</b></label>
          <label  for="location"><?php echo $_POST['location']?></label>
        </td>
      </div>
    </tr>
    <tr style="text-align:left !important;">
      <div class="form-group form-inline">
        <td>      
          <label  for="measurement"><b>MEASUREMENT (sq.ft):</b></label>
          <label  for="measurement"><?php echo $_POST['measurement']?></label>
        </td>
      </div>
    </tr>
    <tr style="text-align:left !important;">
      <div class="form-group form-inline">
        <td>      
          <label  for="floor_plan_details"><b>OFFICE/SHOP NO (floor plan details):</b></label>
          <label  for="floor_plan_details"><?php echo $_POST['floor_plan_details']?></label>
        </td>
      </div>
    </tr> 
      <tr>
         <td>
                <p style="width:100%; text-align:left !important;"><?php echo "This is to confirm that the above named tenant vacated the premises on ".date("l-m-Y")." after 
              reinstatement /without reinstatement of the premises to original condition and I hereby confirm the following being in
               order/or not in order"?></p>
        </td>
    </tr> 
    </tbody>
</table>
<table class="table">
    <thead>
      <tr>
        <td>
          <h3>1.Electrical systems & Fittings:</h3>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>a) Wall sockets</td>
       
        <td>
          <label  for="wall_sockets"><?php echo $_POST['wall_sockets']?></label>
        </td>
        
      </tr>
      <tr>
        <td>b) Light switches</td>
       
        <td>
           <label  for="wall_sockets"><?php echo $_POST['light_switches']?></label>
        </td>
        
      </tr>
      <tr>
        <td>b) Tube lights</td>
       
        <td>
        <label  for="tube_switches"><?php echo $_POST['tube_switches']?></label>
        </td>
        
      </tr>
      <tr>
        <td>d) LED lights</td>
       
        <td>
        <label  for="led_lights"><?php echo $_POST['led_lights']?></label>
       </td>
        
      </tr>
      <tr>
        <td>e) Bulb holders</td>
       
        <td>
        <label  for="bulb_holders"><?php echo $_POST['bulb_holders']?></label> 
        </td>
        
      </tr>
      <tr>
        <td>e) Consumer units</td>
        <td>
        <label  for="consumer_units"><?php echo $_POST['consumer_units']?></label> 
        </td>
        
      </tr>
      <tr>
        <td>g) Electrical meter/Submeter No.</td>
       
        <td>
        <label  for="electrical_meter_no"><?php echo $_POST['electrical_meter_no']?></label> 

        </td>
        <td>
        <label  for="account_no"><?php echo $_POST['account_no']?></label> 
        </td>
        
      </tr>
      <tr>
        <td>h) Phase.</td>
       
        <td>
        <label  for="phase"><?php echo $_POST['phase']?></label> 
        </td>
    
        
      </tr>
      <tr>
        <td>i) Account cleared and closed/not cleared and not closed.</td>
       
        <td>
        <label  for="account_status"><?php echo $_POST['account_status']?></label>         
        </td>  
      </tr>
      <tr>
        <td>j) Sealed/not sealed.</td>
       
        <td>
        <label  for="seal_status"><?php echo $_POST['seal_status']?></label>                 
        </td>
    
        
      </tr>
      <tr>
        <td>k)Outstanding bill on the account Kshs.</td>
       
        <td>
        <label  for="outsatnding_bill"><?php echo $_POST['outsatnding_bill']?></label>                 
        </td>
        
      </tr>
      
    </tbody>
</table>
<table class="table">
    <thead>
      <tr>
        <td>
          <h3>2.Water and plumbing system:</h3>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>a) Water taps reinstated / not reinstated</td>
       
        <td>
        <label  for="water_tap_status"><?php echo $_POST['water_tap_status']?></label>                 
        </td>
        
      </tr>
      <tr>
        <td>b) Sinks reinstated / not reinstated</td>
       
        <td>
        <label  for="sink_status"><?php echo $_POST['sink_status']?></label>                 
       </td>
        
      </tr>
      <tr>
        <td>c) Full fledged washroom</td>
       
        <td>
        <label  for="full_fledged_washroom"><?php echo $_POST['full_fledged_washroom']?></label>                 
        </td>
        
      </tr>
      <tr>
        <td>d) Electrical meter/Submeter No.</td>
       
        <td>
        <label  for="water_meter_no"><?php echo $_POST['water_meter_no']?></label>                 
        </td>
        <td>
        <label  for="water_account_no"><?php echo $_POST['water_account_no']?></label>                 
        </td>
        
      </tr> 
      <tr>
        <td>e) Account cleared and closed/not cleared and not closed.</td>
       
        <td>
        <label  for="water_account_status"><?php echo $_POST['water_account_status']?></label>                 
        </td>  
      </tr> 
      <tr>
        <td>f) Outstanding bill on the account Kshs.</td>
       
        <td>
        <label  for="water_outsatnding_bill"><?php echo $_POST['water_outsatnding_bill']?></label>                 
        </td>
        
      </tr> 
    </tbody>
</table>
<table class="table">
    <thead>
      <tr>
        <td>
          <h3>3. Painting of the premises: <br><h3>
        </td>
      </tr>
    </thead>
    <td>
     <label  for="painting_status"><?php echo $_POST['painting_status']?></label>                 
    </td>
</table>
<table class="table">
    <thead>
      <tr>
        <td>
          <h3>4. Other items: <br><h3>
        </td>
      </tr>
    </thead>
    <tr>
        <td>The following items have been handed over /not handed over to me </td>
      </tr>
      <tbody>
      <tr>
        <td>a) Door locks</td>  
        <td>
            <div class="form-group form-inline">
                 <label for="door_locks_number">Number</label> 
                 <input type="text"  id="text" name="door_locks_number" value=<?=$_POST['door_locks_number']?>>                  
             </div>
            <div class="form-group form-inline">
                <label for="door_locks_condition">Condition</label>  
                <label for="door_locks_condition"><?php echo $_POST['door_locks_condition']?></label>  
            </div> 
        </td>
        
      </tr>
      <tr>
        <td>b) Keys</td>
       
        <td>
        <div class="form-group form-inline">
                 <label for="keys_number">Number</label>  
                 <input type="text"  id="text" name="keys_number" value=<?=$_POST['keys_number']?>>                  
            </div>
            <div class="form-group form-inline">
                <label for="keys_condition">Condition</label> 
                <label for="keys_condition"><?php echo $_POST['keys_condition']?></label>  
             </div>        
        </td>
        
      </tr>
      <tr>
        <td>c) Ceiling</td>
       
        <td>
            <div class="form-group form-inline">
                <label for="ceiling_type">Type</label>  
                <label for="ceiling_type"><?php echo $_POST['ceiling_type']?></label>  
            </div> 
            <div class="form-group form-inline">
                <label for="ceiling_condition">Condition</label>  
                <label for="ceiling_condition"><?php echo $_POST['ceiling_condition']?></label>  
            </div>
        
        </td>
        
      </tr>
      <tr>
        <td>d) Floor finish.</td>
       
        <td>
             <div class="form-group form-inline">
             <label for="tiled"><?php echo $_POST['tiled']?></label>   
            </div> 
            <div class="form-group form-inline">
                <label for="floor_finish">Condition</label>
                <label for="floor_finish"><?php echo $_POST['floor_finish']?></label>   
            </div> 
        </td>
        
      </tr> 
      <tr>
        <td>e) Frontage.</td>
       
        <td>
        <label for="frontage"><?php echo $_POST['frontage']?></label>   
        </td>
        
      </tr>  
      <tr>
        <td>f) Washroom access cards.</td>
       
        <td>
        <label for="washroom_access_cards"><?php echo $_POST['washroom_access_cards']?></label>   
        </td>
        
      </tr> 
      <tr>
        <td>Others:</td>
        <td>
          <textarea id="others" name="others"  cols="40" rows="5" class="form-control" value=<?=$_POST['others']?>></textarea>
        </td>
      </tr> 
      </tbody>
  
</table>
<table class="table">
    <thead>
      <tr>
        <td>
          <h3>NOTE:</h3>
          <p>In case a tenant vacates premises without reinstating the premises, you must state reasons why i.e. the tenant was auctioned or vacated by court order or boycotted the premises. State your reasons here: </p>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>       
            <textarea id="reasons" name="reasons" style="width:100%;" cols="200" rows="8" class="form-control" value=<?=$_POST['reasons']?>></textarea>
        </td>
        
      </tr>

      </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>FOR: SHILOAH INVESTMENTS LIMITED</h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
      <td>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="signature">SIGNATURE:</label>  
            <label for="signature"><?php echo $_POST['signature']?></label>  
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="name_of_building_manager">NAME OF BUILDING MANAGER:</label>  
            <label for="name_of_building_manager"><?php echo $_POST['name_of_building_manager']?></label>  
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="date">DATE:</label>  
            <label for="date"><?php echo $_POST['date']?></label>  
        </div>
        </td>
      </tr>

      </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <td>
        <div class="form-group form-inline">
         <label  for="tenant_name"><h3>FOR:</h3></label>
         <label for="tenant_name"><?php echo $_POST['tenant_name']?></label>  
        </td>
        </div>
      </tr>
    </thead>
    <tbody>
      <tr>
      <td>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="signature">SIGNATURE:</label>  
            <label for="tenant_signature"><?php echo $_POST['tenant_signature']?></label>  
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="signature">NAME OF THE PERSON SIGNING THE FORM:</label>  
            <label for="name_of_the_person_signing_the_form"><?php echo $_POST['name_of_the_person_signing_the_form']?></label>  
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="date">DATE:</label>
            <label for="date2"><?php echo $_POST['date2']?></label>  
          </div>
        </td>
      </tr>

      </tbody>
  </table>
</div>
</form>
</body>

</html>
        <?php 
        $body = ob_get_clean();

        $body = iconv("UTF-8","UTF-8//IGNORE",$body);

        include("mpdf/mpdf.php");
        $mpdf = new mPDF([
            'mode' => 'UTF-8',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 27,
            'margin_bottom' => 25,
            'margin_header' => 5,
            'margin_footer' => 10
        ]);
        // $mpdf=new mPDF('', 'A4', 0, '', 2, 2,5, 0, 0, 0);
        // $mpdf=new \mPDF('c','A4','','' , 0, 0, 0, 0, 0, 0); 

        //write html to PDF
        $stylesheet = file_get_contents('pdf.css');

        $mpdf->WriteHTML($stylesheet,1);	
        $mpdf->WriteHTML($body, 2);
 
        //output pdf
        $mpdf->Output('test.pdf','D');

        //save to server
        //$mpdf->Output("mydata.pdf",'F');



    }
}

//if their are errors display them
if(isset($error)){
    foreach($error as $error){
        echo "<p style='color:#ff0000'>$error</p>";
    }
}
?> 

<body>


<div class="container" style="width:80%">
<h1 style="text-align:center;">TENANCY VACATING FORM</h1>
<br><br>

<p><i><b>CONFIRMATION OF THE STATE OF THE PREMISES AT THE TIME OF VACATING</b><i></p>
<form action="tenancy_vacating_form.php" method="post">
<table class="table">
    <thead>
      <tr>
     
      </tr>
    </thead>
    <tbody>
      <tr>
          <th>
             <div class="form-group form-inline">
             <label  for="tenant_name"><h>NAME OF TENANT:</h></label>
                <input name= "tenatnt_name" type="text" style="width: 500px !important;" > 
            </div> 
            <div class="form-group form-inline">
             <label  for="location"><h>LOCATION (floor) :</h></label>
             <input name= "location" type="text" style="width: 500px !important;" > 
            </div>   
            <div class="form-group form-inline">
             <label  for="measurement"><h>MEASUREMENT (sq.ft) :</h></label>
             <input name= "measurement" type="text" style="width: 500px !important;" > 
            </div>  
            <div class="form-group form-inline">
             <label  for="floor_plan_details"><h>OFFICE/SHOP NO (floor plan details):</h></label>
             <input name= "floor_plan_details" type="text" style="width: 500px !important;" > 
            </div> 
          </th>
          <th>
  
     
      </tr>  
      <tr>
         <th>
                <p style="width:100%"><?php echo "This is to confirm that the above named tenant vacated the premises on ".date("l-m-Y")." after 
              reinstatement /without reinstatement of the premises to original condition and I hereby confirm the following being in
               order/or not in order"?></p>
        </th>
    </tr> 
    </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>1.Electrical systems & Fittings:</h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>a) Wall sockets</td>
       
        <td>
        <input type="radio" class="form-check-input" id="radio1" name="wall_sockets" value="Reinstated" checked> Reinstated
        <input type="radio" class="form-check-input" id="radio1" name="wall_sockets" value="Not reinstated" checked>Not reinstated

        </td>
        
      </tr>
      <tr>
        <td>b) Light switches</td>
       
        <td>
        <input type="radio" class="form-check-input" id="radio2" name="light_switches" value="Reinstated" checked> Reinstated
        <input type="radio" class="form-check-input" id="radio2" name="light_switches" value="Not reinstated" checked>Not reinstated

        </td>
        
      </tr>
      <tr>
        <td>b) Tube lights</td>
       
        <td>
        <input type="radio" class="form-check-input" id="radio3" name="tube_switches" value="Reinstated" checked> Reinstated
        <input type="radio" class="form-check-input" id="radio3" name="tube_switches" value="Not reinstated" checked>Not reinstated
        </td>
        
      </tr>
      <tr>
        <td>d) LED lights</td>
       
        <td>
        <input type="radio" class="form-check-input" id="radio4" name="led_lights" value="Reinstated" checked> Reinstated
        <input type="radio" class="form-check-input" id="radio4" name="led_lights" value="Not reinstated" checked>Not reinstated
       </td>
        
      </tr>
      <tr>
        <td>e) Bulb holders</td>
       
        <td>
        <input type="radio" class="form-check-input" id="radio5" name="bulb_holders" value="Reinstated" checked> Reinstated
        <input type="radio" class="form-check-input" id="radio5" name="bulb_holders" value="Not reinstated" checked>Not reinstated
 
        </td>
        
      </tr>
      <tr>
        <td>e) Consumer units</td>
        <td>
        <input type="radio" class="form-check-input" id="radio6" name="consumer_units" value="Reinstated" checked> Reinstated
        <input type="radio" class="form-check-input" id="radio6" name="consumer_units" value="Not reinstated" checked>Not reinstated
        </td>
        
      </tr>
      <tr>
        <td>g) Electrical meter/Submeter No.</td>
       
        <td>
            
          <input name= "electrical_meter_no" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        <td>
          <input name= "account_no" type="text" style="width: 100px; padding: 1px" > 
        </td>
        
      </tr>
      <tr>
        <td>h) Phase.</td>
       
        <td>
            <select id="phase" name="phase" class="custom-select">
                <option value="single phase">Single Phase</option>
                <option value="3 phase">3 Phase</option>
            </select> 
        </td>
    
        
      </tr>
      <tr>
        <td>i) Account cleared and closed/not cleared and not closed.</td>
       
        <td>
            <input type="radio" class="form-check-input" id="radio7" name="account_status" value="cleared and closed" checked> cleared and closed
            <input type="radio" class="form-check-input" id="radio7" name="account_status" value="not cleared and not closed" checked>not cleared and not closed
        
        </td>  
      </tr>
      <tr>
        <td>j) Sealed/not sealed.</td>
       
        <td>
            <input type="radio" class="form-check-input" id="radio8" name="seal_status" value="Sealed" checked>Sealed
            <input type="radio" class="form-check-input" id="radio8" name="seal_status" value="not Sealed" checked>not Sealed
        
        </td>
    
        
      </tr>
      <tr>
        <td>k)Outstanding bill on the account Kshs.</td>
       
        <td>
          <input name= "outsatnding_bill" type="number" style="width: 200px; padding: 1px" value="0Ksh"> 
        </td>
        
      </tr>
      
    </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>2.Water and plumbing system:</h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>a) Water taps reinstated / not reinstated</td>
       
        <td>
            <input type="radio" class="form-check-input" id="radio9" name="water_tap_status" value="cleared and closed" checked>reinstated
            <input type="radio" class="form-check-input" id="radio9" name="water_tap_status" value="not cleared and not closed" checked>not reinstated        
        </td>
        
      </tr>
      <tr>
        <td>b) Sinks reinstated / not reinstated</td>
       
        <td>
            <input type="radio" class="form-check-input" id="radio10" name="sink_status" value="reinstated checked">reinstated
            <input type="radio" class="form-check-input" id="radio10" name="sink_status" value="not reinstated" checked>not reinstated        
     
       </td>
        
      </tr>
      <tr>
        <td>c) Full fledged washroom</td>
       
        <td>
          <input name= "full_fledged_washroom" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>d) Electrical meter/Submeter No.</td>
       
        <td>
          <input name= "water_meter_no" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        <td>
          <input name= "water_account_no" type="text" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr> 
      <tr>
        <td>e) Account cleared and closed/not cleared and not closed.</td>
       
        <td>
            <input type="radio" class="form-check-input" id="radio11" name="water_account_status" value="cleared and closed" checked> cleared and closed
            <input type="radio" class="form-check-input" id="radio11" name="water_account_status" value="not cleared and not closed" checked>not cleared and not closed
        
        </td>  
      </tr> 
      <tr>
        <td>f) Outstanding bill on the account Kshs.</td>
       
        <td>
          <input name= "water_outsatnding_bill" type="number" style="width: 200px; padding: 1px" value="0Ksh"> 
        </td>
        
      </tr> 
    </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>3. Painting of the premises: <br><h3>
        </th>
      </tr>
    </thead>
    <td>
            <input type="radio" class="form-check-input" id="radio12" name="painting_status" value="The premises have been painted with soft white paint" checked>The premises have been painted with soft white paint
            <input type="radio" class="form-check-input" id="radio12" name="painting_status" value="not painted." checked>not painted        
    </td>

  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>4. Other items:</h3>
          <p>The following items have been handed over /not handed over to me </p>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>a) Door locks</td>
      
        <td>
            <div class="form-group form-inline">
                 <label for="door_locks_number">Number</label>  
                <input name= "door_locks_number" type="number" style="width: 45px; padding: 1px" value="0"> 
            </div>
            <div class="form-group form-inline">
                <label for="door_locks_condition">Condition</label>  
                <input name= "door_locks_condition" type="text" style="width: 45px; padding: 1px" >
            </div> 

        </td>
        
      </tr>
      <tr>
        <td>b) Keys</td>
       
        <td>
        <div class="form-group form-inline">
                 <label for="keys_number">Number</label>  
                <input name= "keys_number" type="number" style="width: 45px; padding: 1px" value="0"> 
            </div>
            <div class="form-group form-inline">
                <label for="keys_condition">Condition</label>  
                <input name= "keys_condition" type="text" style="width: 45px; padding: 1px" >
            </div>        
        </td>
        
      </tr>
      <tr>
        <td>c) Ceiling</td>
       
        <td>
            <div class="form-group form-inline">
                <label for="ceiling_type">Type</label>  
                <input name= "ceiling_type" type="text" style="width: 45px; padding: 1px" >
            </div> 
            <div class="form-group form-inline">
                <label for="ceiling_condition">Condition</label>  
                <input name= "ceiling_condition" type="text" style="width: 45px; padding: 1px" >
            </div>
        
        </td>
        
      </tr>
      <tr>
        <td>d) Floor finish.</td>
       
        <td>
             <div class="form-group form-inline">
                <input type="radio" class="form-check-input" id="radio14" name="tiled" value="Tiled" checked>Tiled
                <input type="radio" class="form-check-input" id="radio14" name="tiled" value="Screened." checked>Screened       

            </div> 
            <div class="form-group form-inline">
                <label for="floor_finish">Condition</label>  
                <input name= "floor_finish" type="text" style="width: 45px; padding: 1px" >
            </div> 
        </td>
        
      </tr> 
      <tr>
        <td>e) Frontage.</td>
       
        <td>
          <input name= "frontage" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>  
      <tr>
        <td>f) Washroom access cards.</td>
       
        <td>
          <input name= "washroom_access_cards" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr> 
      <tr>
      <td>Others:</td>
      <td>
       <textarea id="others" name="others" style="width:100%" cols="40" rows="5" class="form-control"></textarea>
    </td>
      </tr> 
    </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>NOTE:</h3>
          <p>In case a tenant vacates premises without reinstating the premises, you must state reasons why i.e. the tenant was auctioned or vacated by court order or boycotted the premises. State your reasons here: </p>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>       
            <textarea id="reasons" name="reasons" style="width:100%;" cols="200" rows="8" class="form-control"></textarea>
        </td>
        
      </tr>

      </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>FOR: SHILOAH INVESTMENTS LIMITED</h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
      <td>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="signature">SIGNATURE:</label>  
            <input  name= "signature" type="text" style="width: 45px; padding: 1px" >
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="signature">NAME OF BUILDING MANAGER:</label>  
            <input  name= "name_of_building_manager" type="text" style="width: 45px; padding: 1px" >
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="date">DATE:</label>  
            <input  name= "date" type="date" style="width: 200px; height:30px; padding: 1px" >
        </div>
        </td>
      </tr>

      </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
        <div class="form-group form-inline">
         <label  for="tenant_name"><h3>FOR:</h3></label>
          <input  name= "tenant_name" type="text" style="width: 45px; padding: 1px" >
        </th>
        </div>
      </tr>
    </thead>
    <tbody>
      <tr>
      <td>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="signature">SIGNATURE:</label>  
            <input  name= "tenant_signature" type="text" style="width: 45px; padding: 1px" >
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="signature">NAME OF THE PERSON SIGNING THE FORM:</label>  
            <input  name= "name_of_the_person_signing_the_form" type="text" style="width: 45px; padding: 1px" >
        </div>
        <div class="form-group form-inline">
            <label style="font-weight:bold;" for="date">DATE:</label>  
            <input  name= "date2" type="date" style="width: 200px; height:30px; padding: 1px" >
        </div>
        </td>
      </tr>

      </tbody>
  </table>
  <button name="submit" type="submit" class="btn btn-primary">GENERATE FORM</button>
</div>
</form>
</body>

</html>


<!-- width: 300px;
  background: 
      linear-gradient(#000, #000) center bottom 5px /calc(100% - 10px) 2px no-repeat;
  background-color: #fcfcfc;
  border: 1px solid;
  padding: 10px; -->