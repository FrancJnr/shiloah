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


<div class="container" >
  <h1>PREMISES OCCUPATION FORM</h1>

<p>CONFIRMATION OF THE STATE OF THE PREMISES AT THE TIME OF TAKING POSSESSION</p>
<table class="table">
    <thead>
      <tr>
        <th>
          <h3></h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><b>NAME OF TENANT:</b></td>     
        <td>
          <input name= "tenatnt_name" type="text" style="width: 350px; padding: 1px" value=<?=$_POST['tenatnt_name']?>;> 
        </td>    
      </tr>  
      <tr>
        <td><b>LOCATION (floor) :</b></td>     
        <td>
          <input name= "location" type="text" style="width: 350px; padding: 1px" value=<?=$_POST['location']?>> 
        </td>    
      </tr> 
      <tr>
        <td><b>MEASUREMENT (sq.ft) :</b></td>     
        <td>
          <input name= "measurement" type="text" style="width: 350px; padding: 1px" value=<?=$_POST['measurement']?>> 
        </td>    
      </tr> 
      <tr>
        <td><b>OFFICE/SHOP NO (floor plan details):</b></td>     
        <td>
          <input name= "floor_plan_details" type="text" style="width: 350px; padding: 1px" value=<?=$_POST['floor_plan_details']?>> 
        </td>    
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
          <input name= "walls_sockets" type="text" style="width: 45px; padding: 1px" value=<?=$_POST['walls_sockets']?>> 
        </td>
        
      </tr>
      <tr>
        <td>b) Light switches</td>
       
        <td>
          <input name= "lights_switches" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['lights_switches']?>> 
        </td>
        
      </tr>
      <tr>
        <td>b) Tube lights</td>
       
        <td>
          <input name= "tube_lights" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['tube_lights']?>> 
        </td>
        
      </tr>
      <tr>
        <td>d) LED lights</td>
       
        <td>
          <input name= "led_lights" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['led_lights']?>>
        </td>
        
      </tr>
      <tr>
        <td>e) Bulb holders</td>
       
        <td>
          <input name= "bulb_holders" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['bulb_holders']?>> 
        </td>
        
      </tr>
      <tr>
        <td>e) Consumer units</td>
        <td>
          <input name= "consumer_units" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['consumer_units']?>> 
        </td>
        
      </tr>
      <tr>
        <td>g) Electrical meter/Submeter No.</td>
       
        <td>
          <input name= "electrical_meter_no" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['electrical_meter_no']?>> 
        </td>
        <td>
          <input name= "account_no" type="text" style="width: 45px; padding: 1px" value=<?=$_POST['account_no']?>> 
        </td>
        
      </tr>
      <tr>
        <td>h) Phase.</td>
       
        <td>
        <input name= "phase" type="text" style="width: 45px; padding: 1px" value=<?=$_POST['phase']?>> 

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
        <td>a) Water taps</td>
       
        <td>
          <input name= "water_taps" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['water_taps']?>> 
        </td>
        
      </tr>
      <tr>
        <td>b) Sinks</td>
       
        <td>
          <input name= "sinks" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['sinks']?>> 
        </td>
        
      </tr>
      <tr>
        <td>c) Full fledged washroom</td>
       
        <td>
          <input name= "full_fledged_washroom" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['full_fledged_washroom']?>> 
        </td>
        
      </tr>
      <tr>
        <td>g) Electrical meter/Submeter No.</td>
       
        <td>
          <input name= "water_meter_no" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['water_meter_no']?>> 
        </td>
        <td>
          <input name= "water_account_no" type="text" style="width: 45px; padding: 1px" value=<?=$_POST['water_account_no']?>> 
        </td>
        
      </tr>   
    </tbody>
</table>
<table class="table">
    <thead>
      <tr>
        <th>
          <h3>3.Painting of the premises:<h3>
        <p style="text-align: left !important">The premises is hereby handed to you painted with soft white paint. 
        You are required to maintain it according to the terms in the lease agreement i.e to
        paint it every after 2 years and on handing over at the expiry of the lease agreement</p>
        </th>
      </tr>
    </thead>

  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>4. Other items::</h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>a) Door locks</td>
      
        <td>
          <input name= "door_locks" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['door_locks']?>> 
        </td>
        
      </tr>
      <tr>
        <td>b) Keys</td>
       
        <td>
          <input name= "keys" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['keys']?>> 
        </td>
        
      </tr>
      <tr>
        <td>c) Ceiling</td>
       
        <td>
          <input name= "ceiling" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['ceiling']?>> 
        </td>
        
      </tr>
      <tr>
        <td>g) Floor finish.</td>
       
        <td>
          <input name= "floor_finish" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['floor_finish']?>> 
        </td>
        
      </tr> 
      <tr>
        <td>e) Frontage.</td>
       
        <td>
          <input name= "frontage" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['frontage']?>> 
        </td>
        
      </tr>  
      <tr>
        <td>f) Washroom access cards.</td>
       
        <td>
          <input name= "washroom_access_cards" type="number" style="width: 45px; padding: 1px" value=<?=$_POST['washroom_access_cards']?>> 
        </td>
        
      </tr> 
      <tr>
      <td>Others:</td>
      <td>
       <textarea id="others" name="others" cols="40" rows="5" class="form-control" value=<?=$_POST['others']?>></textarea>
    </td>
      </tr> 
    </tbody>
  </table>
</div>
        <?php 
        $body = ob_get_clean();

        $body = iconv("UTF-8","UTF-8//IGNORE",$body);

        include("mpdf/mpdf.php");
        $mpdf = new mPDF([
            'mode' => 'c',
            'margin_left' => 32,
            'margin_right' => 25,
            'margin_top' => 27,
            'margin_bottom' => 25,
            'margin_header' => 16,
            'margin_footer' => 13
        ]);
        // $mpdf=new mPDF('', 'A4', 0, '', 2, 2,5, 0, 0, 0);
        // $mpdf=new \mPDF('c','A4','','' , 0, 0, 0, 0, 0, 0); 

        //write html to PDF
        $stylesheet = file_get_contents('pdf.css');
        $mpdf->WriteHTML($stylesheet,1);	
        $mpdf->WriteHTML($body, 2);
 
        //output pdf
        $mpdf->Output('demo.pdf','D');

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


<div class="container" style="width:60%">
  <h1>PREMISES OCCUPATION FORM</h1>

<p>CONFIRMATION OF THE STATE OF THE PREMISES AT THE TIME OF TAKING POSSESSION</p>
<form action="premise_occupation_form.php" method="post">
<table class="table">
    <thead>
      <tr>
        <th>
          <h3></h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><b>NAME OF TENANT:</b></td>     
        <td>
          <input name= "tenatnt_name" type="text" style="width: 350px; padding: 1px" > 
        </td>    
      </tr>  
      <tr>
        <td><b>LOCATION (floor) :</b></td>     
        <td>
          <input name= "location" type="text" style="width: 350px; padding: 1px" > 
        </td>    
      </tr> 
      <tr>
        <td><b>MEASUREMENT (sq.ft) :</b></td>     
        <td>
          <input name= "measurement" type="text" style="width: 350px; padding: 1px" > 
        </td>    
      </tr> 
      <tr>
        <td><b>OFFICE/SHOP NO (floor plan details):</b></td>     
        <td>
          <input name= "floor_plan_details" type="text" style="width: 350px; padding: 1px" > 
        </td>    
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
          <input name= "walls_sockets" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>b) Light switches</td>
       
        <td>
          <input name= "lights_switches" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>b) Tube lights</td>
       
        <td>
          <input name= "tube_lights" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>d) LED lights</td>
       
        <td>
          <input name= "led_lights" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>e) Bulb holders</td>
       
        <td>
          <input name= "bulb_holders" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>e) Consumer units</td>
        <td>
          <input name= "consumer_units" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>g) Electrical meter/Submeter No.</td>
       
        <td>
          <input name= "electrical_meter_no" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        <td>
          <input name= "account_no" type="text" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>h) Phase.</td>
       
        <td>
            <select id="phase" name="phase" class="custom-select">
                <option value="single_phase">Single Phase</option>
                <option value="three_phase">3 Phase</option>
            </select> 
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
        <td>a) Water taps</td>
       
        <td>
          <input name= "water_taps" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>b) Sinks</td>
       
        <td>
          <input name= "sinks" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>c) Full fledged washroom</td>
       
        <td>
          <input name= "full_fledged_washroom" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>g) Electrical meter/Submeter No.</td>
       
        <td>
          <input name= "water_meter_no" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        <td>
          <input name= "water_account_no" type="text" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>   
    </tbody>
  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>3. Painting of the premises: <br><h3>
        <p>The premises is hereby handed to you painted with soft white paint. 
        You are required to maintain it according to the terms in the lease agreement i.e to
        paint it every after 2 years and on handing over at the expiry of the lease agreement.:</p>
        </th>
      </tr>
    </thead>

  </table>
  <table class="table">
    <thead>
      <tr>
        <th>
          <h3>4. Other items::</h3>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>a) Door locks</td>
      
        <td>
          <input name= "door_locks" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>b) Keys</td>
       
        <td>
          <input name= "keys" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>c) Ceiling</td>
       
        <td>
          <input name= "ceiling" type="number" style="width: 45px; padding: 1px" value="0"> 
        </td>
        
      </tr>
      <tr>
        <td>g) Floor finish.</td>
       
        <td>
          <input name= "floor_finish" type="number" style="width: 45px; padding: 1px" value="0"> 
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
       <textarea id="others" name="others" cols="40" rows="5" class="form-control"></textarea>
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