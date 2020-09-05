<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";
$table = "trans_offerletter";
$companymasid = $_SESSION['mycompanymasid'];

if($load == "loadTenant")
{
   $sql = "select
	    a.tenantmasid,a.tenanttypemasid, a.leasename, a.tradingname,a.renewalfromid, a.tenantcode, a.companymasid, a.buildingmasid, a.blockmasid,
            a.floormasid, a.shopmasid, a.shoptypemasid, a.orgtypemasid, a.nob, a.agemasidlt, a.agemasidrc, a.agemasidcp, a.creditlimit,
            a.latefeeinterest, a.doo, a.doc, a.pin, a.regno, a.address1, a.address2, a.city, a.state, a.pincode, a.country, a.poboxno,
            a.telephone1, a.telephone2, a.fax, a.emailid, a.website, a.remarks, a.createdby, a.createddatetime, a.modifiedby, a.modifieddatetime,
            a.active,b.shopcode
	    from mas_tenant a
	    inner join mas_shop b on b.shopmasid = a.shopmasid
	    where a.active= '1' and a.companymasid=$companymasid and a.tenantmasid not in
	    (select tenantmasid from trans_offerletter where companymasid = $companymasid)
	    order by leasename asc";
}
elseif($load == "loadOfferLetter")
{
   $sql = "select b.leasename,b.tradingname,b.renewalfromid,b.tenantcode,a.offerlettercode,a.offerlettermasid,c.shopcode from
      trans_offerletter a
      inner join mas_tenant b on a.tenantmasid = b.tenantmasid
      inner join mas_shop c on c.shopmasid = b.shopmasid
      where a.editpermission = '1'
      and a.companymasid=$companymasid order by b.leasename asc";
}
elseif($load == "detailsTenant")
{
   $sql = "SELECT n.cpname,a.leasename,a.tradingname,a.tenantcode, a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
    . "FROM mas_tenant a\n"
    . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
    . "LEFT OUTER JOIN mas_tenant_cp n ON n.tenantmasid = a.tenantmasid\n"
    . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
    . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
    . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
    . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
    . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
    . "WHERE n.documentname='1' and a.tenantmasid =".$load= $_GET['itemval']
    . " and a.companymasid=$companymasid";}
elseif($load == "detailsOfferLetter")
{
  $sql = "SELECT n.cpname,a.leasename,a.tradingname,a.tenantcode ,a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
    . "FROM trans_offerletter t\n"
    . "INNER JOIN mas_tenant a ON a.tenantmasid = t.tenantmasid\n"
    . "LEFT OUTER JOIN mas_tenant_cp n ON n.tenantmasid = t.tenantmasid\n"
    . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
    . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
    . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
    . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
    . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
    . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
    . "WHERE n.documentname='1'and t.offerlettermasid =".$load= $_GET['itemval']
    . " and  t.companymasid=$companymasid";
}
elseif($load == "detailsRent")
{
  $sql = "select * from trans_offerletter_rent\n"
    . "where offerlettermasid =".$load= $_GET['itemval'];
}
else if($load == "loadRentTbl")
{
 $sql = "select a.*,\n"
        . "b.age AS 'rentcycle',\n"
        . "c.age AS 'term'\n"
        . "from mas_tenant a\n"
        . "inner join mas_age b on a.agemasidrc = b.agemasid\n"
        . "INNER JOIN mas_age c ON a.agemasidlt = c.agemasid\n"
        . "WHERE a.tenantmasid =".$load= $_GET['itemval']
        . " and  a.companymasid=$companymasid and a.active=1";
   $result=mysql_query($sql);
    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
            $i=1;
            $tr="";
	    $tr2="";
	    $tr3="";
            $trsum="";
            $cnt=1;
            while ($row = mysql_fetch_assoc($result))
            {
                $tenant = $row["leasename"];
		$frmdt = $row["doc"];
		$rentcycle = $row["rentcycle"];
                $term = $row["term"];
                $term = explode(" ",$term);
                 if(count($term)=='4'){//if term is years and months
                     $ym=true; //set year and month to true
                     $s2=  strtolower($term[3]); // get key months
                     $s1 = strtolower($term[1]); //get key for years
                     $cnt1 =$term[0];
                     $cnt2 =$term[2];
                     if($s1=="years"||$s1=="year"){
                     $cnt=($cnt1*12)+$cnt2; //Total Months
                     //echo $cnt;
                     //$s="months";
                     $s="Years & Months" ;
                     }else if($s1=="month"||$s1=="months"){
                         
                     $cnt=($cnt2*12)+$cnt1; //Total Months
                     $s="Years & Months" ;  
                     }
                 }else{
                      $ym=false;
                      $s = strtolower($term[1]); // get the key if months or year  
                      $cnt =$term[0];
                  }
               
		
  //Previous Implementation
                  
                                if(strtotime($frmdt) != 0)
		{
                    $frmdt = date_format(new DateTime($frmdt), "d-F-Y");
                    //E.g. Fri 03-August-2012 13:51:37
		}
		else
		{
                    $frmdt="";
		}    

                for($j=0;$j<$cnt;$j++)
                {
		  if(($s == "years")  || ($s=="year"))
		  {
		     $StartingDate = $frmdt;
		     $newEndingDate = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 1 Year"));
		     $todt =$newEndingDate;
		     //$m = date("F", strtotime(date("d-F", strtotime($frmdt)) . " + 1 Year"));
		     $d = date('d',strtotime("-1 days $frmdt"));
		     $y = date('d-F-Y', strtotime("+12 months $frmdt"));		     
		     $todt2 = date('d-F-Y',strtotime("-1 days $y"));   		     
		  }
		  else if(($s == "months")  || ($s=="month"))
		  {
		    $StartingDate = $frmdt;
                    $newEndingDate = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 1 Months"));
                    $todt =$newEndingDate;
		    $todt2 = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 30 Days"));
		  }
                  else if(($s == "Years & Months"))
		  {
		    $StartingDate = $frmdt;
                    $newEndingDate = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 1 Months"));
                    $todt =$newEndingDate;
		    $todt2 = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 30 Days"));
		  }
		  else if(($s == "weeks")  || ($s=="week"))
		  {
		    $StartingDate = $frmdt;
                    $newEndingDate = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 1 Weeks"));
                    $todt =$newEndingDate;
		    $todt2 = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 7 Days"));
		  }
		   else if(($s == "days")  || ($s=="day"))
		  {
		    $StartingDate = $frmdt;
                    $newEndingDate = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 1 Day"));
                    $todt =$newEndingDate;
		    $todt2 = date("d-F-Y", strtotime(date("d-F-Y", strtotime($frmdt)) . " + 1 Day"));
		  }
		    //-table 1 rent
		    $tr .=  "<tr class='hiderent'><td class='hiderent' colspan='5'><input type='hidden' id='rentcycle' name='rentcycle' style='width:20px;' value='$rentcycle'></tr>";  //hidden field 
                    $tr .=  "<tr>
                    <td>".$frmdt."<input type='hidden' id='hidFromdateRent".$j."' name='hidFromdateRent".$j."' value=".$frmdt."> </td>
                    <td>".$todt2."<input type='hidden' id='hidTodateRent".$j."' name='hidTodateRent".$j."' value=".$todt2."> </td>";
		    if($j == 0)
                   
		    $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$j."' dir='rtl' name='rentpercentage".$j."' value='0' readonly/></td>";
		    else
                        if($s=="Years & Months"){
		    $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$j."' dir='rtl' name='rentpercentage".$j."' value='0'/></td>";
                        }else{
                    $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$j."' dir='rtl' name='rentpercentage".$j."' value='10'/></td>";       
                        }
//$trsum
		    if($j < 1)
                    $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$j."' dir='rtl' name='rentval".$j."' value='' readonly /></td>";//changed recenntly
		    else
		    $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$j."' dir='rtl' name='rentval".$j."'  value='' readonly/></td>";
		    
                    $tr .=  "<td>".$rentcycle."</td></tr>";

		     //-table 2 servicecharge
		    $tr2 .=  "<tr>		
                    <td>".$frmdt."<input type='hidden' id='hidFromdateSc".$j."' name='hidFromdateSc".$j."' value=".$frmdt."> </td>
                    <td>".$todt2."<input type='hidden' id='hidTodateSc".$j."' name='hidTodateSc".$j."' value=".$todt2."> </td>";
		    if($j == 0)
		    $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$j."' dir='rtl' name='servicechrgpercentage".$j."' value='0' readonly/></td>";
		    else
                        if($s=="Years & Months"){
                       $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$j."' dir='rtl' name='servicechrgpercentage".$j."' value='0'/></td>";
                        }else{
                            $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$j."' dir='rtl' name='servicechrgpercentage".$j."' value='10'/></td>";
                        }
		    $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgval".$j."' dir='rtl' name='servicechrgval".$j."'  value='' readonly/></td>";
		    
                    $tr2 .=  "<td >".$rentcycle."</td></tr>";
                    $frmdt = $todt;
		     
		    
                }		  
		//-table 3 Deposit
	       for($i=1;$i<9;$i++)
               {
		    $tr3 .=  "<tr>		
                    <td align='center'>".$i."</td>";
		     if($i==1){// Rent Deposit
		        $tr3 .=  "<td>Security deposit for rent</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthrent' name='depositmonthrent' value='3' />Month</td>";
			$tr3 .=  "<td align='right'><input  type='text' style='width:150px;' id='deposit1' dir='rtl' name='rentdeposit'  value='0' readonly/></td>";
		     
		     }
		     elseif ($i==2){//service charge deposit
		        $tr3 .=  "<td>Security deposit for service charge</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthsc' name='depositmonthsc' value='3'/>Month</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit2' dir='rtl' name='scdeposit'  value='0' readonly/></td>";			
		     }
		     elseif ($i==3){//rent advance
			$tr3 .=  "<td>Advance rent  with VAT</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthrent' name='advancemonthrent' value='3' />Month</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit3' dir='rtl' name='rentwithvat'  value='0' readonly/></td>";
		     }
		     elseif ($i==4){//sc with vat
			$tr3 .=  "<td>Advance service charge with VAT</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthsc' name='advancemonthsc' value='3' />Month</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit4' dir='rtl' name='scwithvat'  value='0' readonly/></td>";
		     }
		     elseif ($i==5){//legal fees
			$tr3 .=  "<td>Leegal fees with VAT</td>";
			$tr3 .=  "<td align='right'>";
			$tr3 .=  "<input type='text' style='width:150px;' dir ='rtl' id='leegalfeevat' name='leegalfeevat' value='4'/>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$tr3 .=  "<td align='right'>";
			$tr3 .=  "<input type='checkbox' id='editleegalfees' name='editleegalfees'> Edit Value&nbsp&nbsp&nbsp";
			$tr3 .=  "<input type='text' style='width:150px;' id='deposit5' dir='rtl' name='leegalfees'  value='0' readonly/></td>";
		     }
		     elseif ($i==6){
			$tr3 .=  "<td>Stamp Duty</td>";
			$tr3 .=  "<td align='right'>";
			$tr3 .=  "<input type='text' style='width:150px;' dir ='rtl' id='stampdutyvat' name='stampdutyvat' value='2.5'/>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$tr3 .=  "<td align='right'>";
			$tr3 .=  "<input type='checkbox' id='editstampduty' name='editstampduty'> Edit Value&nbsp&nbsp&nbsp";
			$tr3 .=  "<input type='text' style='width:150px;' id='deposit6' dir='rtl' name='stampduty'  value='0' readonly/></td>";
		     }
		     elseif ($i==7){
			$tr3 .=  "<td>Registration Fees</td>";
			$tr3 .=  "<td></td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit7' dir='rtl' name='registrationfees'  value='2500'/></td>";
		     }
		     elseif ($i==8){
			$tr3 .=  "<td><b>Total<b></td>";
			$tr3 .=  "<td></td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit8' dir='rtl' name='depositTotal' readonly/></td>";
		     }
			$tr3 .=  "</tr>";
	       }
            }
        }
    }
    $custom = array('msg'=>$tr,'tbl'=>$tr2,'deposit'=>$tr3,'s'=>"Success", 'totalstotal'=>$cnt, 'rentcycle'=>$rentcycle, 'age'=>$s); 
    $response_array [] = $custom;
    echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
    exit;
}
else if($load == "loadTransDetFromOfferLetter")
{
   //-table 1
   //$sql = "select * from trans_offerletter "
   //. " WHERE offerlettermasid =".$load= $_GET['itemval'];
   $sql = "select a. *,b.leasename ,c.age as 'rentcycle'  from trans_offerletter a "
	 . " inner join mas_tenant b on b.tenantmasid = a.tenantmasid"
	 . " inner join mas_age c on c.agemasid = b.agemasidrc"
	 . " WHERE a.offerlettermasid =".$load= $_GET['itemval'];
   $result=mysql_query($sql);
   if($result != null) // if $result <> false
   {
      if (mysql_num_rows($result) > 0)
      {
	 while ($row = mysql_fetch_assoc($result))
         {
	    $renttype = $row["renttype"];
	    $basicrentval =  number_format($row["basicrentval"], 2, '.', ',');
	    $sctype = $row["sctype"];
	    $basicscval = number_format($row["basicscval"], 2, '.', ',');
	    $rentcycle = $row["rentcycle"];	    
	 }
      }
   }
    //	$custom = array('msg'=>$sql,'s'=>'error');
    //    $response_array[] = $custom;
    //    echo '{"error":'.json_encode($response_array).'}';
    //    exit;  
   //-table 2 rent
   $sql = "select * from trans_offerletter_rent "
   . " WHERE offerlettermasid =".$load= $_GET['itemval'];
   $result=mysql_query($sql);
      
    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
	    $i=1;
	    $j=0;
            $tr="";
            $cnt = mysql_num_rows($result);
            while ($row = mysql_fetch_assoc($result))
            {
	       $offerletterrentmasid = $row["offerletterrentmasid"];
	       $frmdt = $row["fromdate"];
	     
                if(strtotime($frmdt) != 0)
		{
                    $frmdt = date_format(new DateTime($frmdt), "d-F-Y");
		}
		else
		{
                    $frmdt="";
		}
		$todt = $row["todate"];
                if(strtotime($todt) != 0)
		{
                    $todt = date_format(new DateTime($todt), "d-F-Y");
		}
		else
		{
                    $todt="";
		}
	        $yearlyhike = $row["yearlyhike"];
		$amount = number_format($row["amount"], 2, '.', ',');
		//$rentcycle = "Per Month";
		  $tr .=  "<tr><td colspan='5'><input type='hidden' id='rentcycle' name='rentcycle' style='width:20px;' value='$rentcycle'></tr>";
		  $tr .=  "<tr>		
		  <td>".$frmdt."<input type='hidden' id='hidFromdateRent".$j."' name='hidFromdateRent".$j."' value=".$frmdt."> </td>
		  <td>".$todt."<input type='hidden' id='hidTodateRent".$j."' name='hidTodateRent".$j."' value=".$todt."> </td>";
		  
		  if($j == 0)
		  {
		     $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$j."' dir='rtl' name='rentpercentage".$j."' value=".$yearlyhike." readonly></td>";
		     $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$j."' dir='rtl' name='rentval".$j."' value=".$amount." /></td>";
		  }
		  else
		  {
		     $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$j."' dir='rtl' name='rentpercentage".$j."' value=".$yearlyhike."></td>";
		     $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$j."' dir='rtl' name='rentval".$j."'  value=".$amount." readonly/></td>";
		  }
		  $tr .=  "<td>".$rentcycle."<input type='hidden' id='hidofferletterrentmasid".$j."' name='hidofferletterrentmasid".$j."' value=".$offerletterrentmasid."></td></tr>";
	       $j++;
	    }
	}
	
    }
    //-table 3 sc
   $sql = "select * from trans_offerletter_sc "
   . " WHERE offerlettermasid =".$load= $_GET['itemval'];
   $result=mysql_query($sql);

    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
	    $i=1;
	    $j=0;
	    $tr2="";
            $cnt = mysql_num_rows($result);
            while ($row = mysql_fetch_assoc($result))
            {
	       $offerletterscmasid = $row["offerletterscmasid"];
	       $frmdt = $row["fromdate"];
                if(strtotime($frmdt) != 0)
		{
                    $frmdt = date_format(new DateTime($frmdt), "d-F-Y");
		}
		else
		{
                    $frmdt="";
		}
		$todt = $row["todate"];
                if(strtotime($todt) != 0)
		{
                    $todt = date_format(new DateTime($todt), "d-F-Y");
		}
		else
		{
                    $todt="";
		}
	        $yearlyhike = $row["yearlyhike"];
		$amount = number_format($row["amount"], 2, '.', ',');
		//$rentcycle = "Per Month";
		  $tr2 .=  "<tr>		
		  <td>".$frmdt."<input type='hidden' id='hidFromdateSc".$j."' name='hidFromdateSc".$j."' value=".$frmdt."></td>
		  <td>".$todt."<input type='hidden' id='hidTodateSc".$j."' name='hidTodateSc".$j."' value=".$todt."> </td>";
		  
		  if($j == 0)
		  {
		     $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$j."' dir='rtl' name='servicechrgpercentage".$j."' value=".$yearlyhike."></td>";
		     $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgval".$j."' dir='rtl' name='servicechrgval".$j."' value=".$amount." readonly/> </td>";
		  }
		  else
		  {
		     $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$j."' dir='rtl' name='servicechrgpercentage".$j."' value=".$yearlyhike."></td>";
		     $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgval".$j."' dir='rtl' name='servicechrgval".$j."'  value=".$amount." readonly/></td>";
		  }
		  $tr2 .=  "<td>".$rentcycle."<input type='hidden' id='hidofferletterscmasid".$j."' name='hidofferletterscmasid".$j."' value=".$offerletterscmasid."></td></tr>";
	       $j++;
	    }
	}
	
    }
    //table 4 deposit

   $sql = "select * from trans_offerletter_deposit "
   . " WHERE offerlettermasid =".$load= $_GET['itemval'];
   $result=mysql_query($sql);
    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
	    $i=1;
	    $j=0;
	    $tr3="";
            $cnt = mysql_num_rows($result);
            while ($row = mysql_fetch_assoc($result))
            {
		  $offerletterdepositmasid = $row["offerletterdepositmasid"];
		  // Rent Deposit
		  $depositmonthrent = $row["depositmonthrent"];
		  $rentdeposit =  number_format($row["rentdeposit"], 2, '.', ',');  
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>1</td>";
		        $tr3 .=  "<td>Security deposit for rent</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthrent' name='depositmonthrent' value=$depositmonthrent >Month;</td>";
			$tr3 .=  "<td align='right'><input  type='text' style='width:150px;' id='deposit1' dir='rtl' name='rentdeposit'  value=$rentdeposit readonly></td>";
			$tr3 .=  "</tr>";
		  //service charge deposit
		     $depositmonthsc = $row["depositmonthsc"];
		     $scdeposit =    number_format($row["scdeposit"], 2, '.', ','); 
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>2</td>";
		        $tr3 .=  "<td>Security deposit for service charge</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthsc' name='depositmonthsc' value=$depositmonthsc>Month;</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit2' dir='rtl' name='scdeposit'  value=$scdeposit readonly></td>";
			$tr3 .=  "</tr>";
		  //rent advance
		     $advancemonthrent = $row["advancemonthrent"];
		     $rentwithvat =  number_format($row["rentwithvat"], 2, '.', ','); 
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>3</td>";
			$tr3 .=  "<td>Advance rent  with VAT</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthrent' name='advancemonthrent' value=$advancemonthrent>Month</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit3' dir='rtl' name='rentwithvat'  value=$rentwithvat readonly></td>";
			$tr3 .=  "</tr>";
		     //sc with vat
		     $advancemonthsc = $row["advancemonthsc"];
		     $scwithvat = number_format($row["scwithvat"], 2, '.', ','); 
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>4</td>";
			$tr3 .=  "<td>Advance service charge with VAT</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthsc' name='advancemonthsc' value=$advancemonthsc>Month</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit4' dir='rtl' name='scwithvat'  value=$scwithvat readonly></td>";
			$tr3 .=  "</tr>";
		     //legal fees
		     $leegalfeevat = $row["leegalfeevat"];
		     $leegalfees =  number_format($row["leegalfees"], 2, '.', ',');
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>5</td>";
			$tr3 .=  "<td>Leegal fees with VAT</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='leegalfeevat' name='leegalfeevat' value=$leegalfeevat>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$tr3 .=  "<td align='right'>";
			$editleegalfees = $row['editleegalfees'];
			if($editleegalfees==1)
			$tr3 .=  "<input type='checkbox' id='editleegalfees' name='editleegalfees' checked> Edit Value&nbsp&nbsp&nbsp";
			else
			$tr3 .=  "<input type='checkbox' id='editleegalfees' name='editleegalfees'> Edit Value&nbsp&nbsp&nbsp";
			$tr3 .=  "<input type='text' style='width:150px;' id='deposit5' dir='rtl' name='leegalfees'  value=$leegalfees readonly></td>";
			$tr3 .=  "</tr>";
		     //stamp duty
		     $stampdutyvat = $row["stampdutyvat"];
		     $stampduty = number_format($row["stampduty"], 2, '.', ',');
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>6</td>";
			$tr3 .=  "<td>Stamp Duty</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='stampdutyvat' name='stampdutyvat' value=$stampdutyvat>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$tr3 .=  "<td align='right'>";
			$editstampduty = $row['editstampduty'];
			if($editstampduty==1)
			$tr3 .=  "<input type='checkbox' id='editstampduty' name='editstampduty' checked> Edit Value&nbsp&nbsp&nbsp";
			else
			$tr3 .=  "<input type='checkbox' id='editstampduty' name='editstampduty'> Edit Value&nbsp&nbsp&nbsp";
			$tr3 .=  "<input type='text' style='width:150px;' id='deposit6' dir='rtl' name='stampduty'  value=$stampduty readonly></td>";
			$tr3 .=  "</tr>";		  
		     //Registration Fee
		     $registrationfees = number_format($row["registrationfees"], 2, '.', ',');
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>7</td>";
			$tr3 .=  "<td>Registration Fees</td>";
			$tr3 .=  "<td></td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit7' dir='rtl' name='registrationfees'  value=$registrationfees></td>";
			$tr3 .=  "</tr>";
		     //Total
		     $depositTotal = number_format($row["depositTotal"], 2, '.', ',');
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>8</td>";
			$tr3 .=  "<td><b>Total<b></td>";
			$tr3 .=  "<td></td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit8' dir='rtl' name='depositTotal'  value = $depositTotal readonly>";
		        $tr3 .=  "<input type='hidden' id='hidofferletterdepositmasid' name='hidofferletterdepositmasid' value=".$offerletterdepositmasid.">";
			$tr3 .=  "</td>";
			$tr3 .=  "</tr>";
	       $j++;
	    }
	}
	
    }

    //$tr2 = "<tr><td>hi</td></tr>";
   $custom = array('msg'=>$tr,'tbl'=>$tr2,'renttype'=>$renttype,'basicrentval'=>$basicrentval,'sctype'=>$sctype,'basicscval'=>$basicscval,'editleegalfees'=>$editleegalfees,'editstampduty'=>$editstampduty,'deposit'=>$tr3,'s'=>"Success");
    //$custom = array('msg'=>$tr,'s'=>"Success"); 
    $response_array [] = $custom;
    echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
    exit;
}
$result =  mysql_query($sql);
    
    if($result != null) 
    {
        $cnt = mysql_num_rows($result);
        if($cnt > 0)
        {
            while($obj = mysql_fetch_object($result))
            {
                $arr[] = $obj;
            }
            $custom = array('msg'=>"",'s'=>"Success"); 
            $response_array [] = $custom;
            echo '{
                "myResult":'.json_encode($arr).',
                "error":'.json_encode($response_array).
            '}';
        }
        else
        {
            $custom = array('msg'=>$sql,'s'=>$sql);
            $response_array [] = $custom;
            echo '{
                "error":'.json_encode($response_array).
            '}';
        }
    }
    else
    {
        $custom = array('msg'=>mysql_error(),'s'=>$sql);
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
    
?>
                
                 
