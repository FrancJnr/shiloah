<?php

include('../config.php');
session_start();
$response_array = array();

$load= $_GET['item'];
$sql="";
$table = "trans_offerletter";
$companymasid = $_SESSION['mycompanymasid'];

if($load == "loadOfferLetter")
{  
   
    $sql = "select b.leasename,b.tradingname,b.tenantcode,a.offerlettercode,a.offerlettermasid,b.renewalfromid,c.shopcode from
                trans_offerletter a
                inner join mas_tenant b on b.tenantmasid = a.tenantmasid
                inner join mas_shop c on c.shopmasid = b.shopmasid
                where a.editpermission = '1'
                and a.companymasid=$companymasid and  b.active='1'
                union
                select b1.leasename,b1.tradingname,b1.tenantcode,a1.offerlettercode,a1.offerlettermasid,b1.renewalfromid,c1.shopcode from
                trans_offerletter a1
                inner join mas_tenant b1 on b1.tenantmasid = a1.tenantmasid
                inner join mas_shop c1 on c1.shopmasid = b1.shopmasid
                where a1.editpermission = '1'
                and a1.companymasid=$companymasid and  b1.active='1'
                order by leasename asc";
}
elseif($load == "loadrecofferletter")
{
    $sql = "select b.leasename,b.tradingname,b.tenantcode,b.renewalfromid,a.offerlettercode,a.offerlettermasid,c.shopcode from
                rec_trans_offerletter a
                inner join rec_tenant b on b.tenantmasid = a.tenantmasid
                inner join mas_shop c on c.shopmasid = b.shopmasid
                where a.editpermission = '1'
                and a.companymasid=$companymasid and  b.active='1'                                
                union
                select b1.leasename,b1.tradingname,b1.tenantcode,b1.renewalfromid,a1.offerlettercode,a1.offerlettermasid,c1.shopcode from
                rec_trans_offerletter a1
                inner join mas_tenant b1 on b1.tenantmasid = a1.tenantmasid
                inner join mas_shop c1 on c1.shopmasid = b1.shopmasid
                where a1.editpermission = '1'
                and a1.companymasid=$companymasid and  b1.active='1'                                                
                order by leasename asc";
}
elseif($load == "detailsTenant")
{
   $sql = "SELECT n.cpname,a.leasename,a.tenantcode, a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
    . "FROM mas_tenant a\n"
    . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
    . "LEFT OUTER JOIN mas_tenant_cp n ON n.tenantmasid = a.tenantmasid\n"
    . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
    . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
    . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
    . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
    . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
    . "WHERE n.documentname='1' and a.tenantmasid =".$load= $_GET['itemval']
    . " and a.companymasid=$companymasid and a.active='1'";
    $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {                    
            $sql = "SELECT n.cpname,a.leasename,a.tenantcode, a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
            . "FROM rec_tenant a\n"
            . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
            . "LEFT OUTER JOIN rec_tenant_cp n ON n.rectenantmasid = a.rectenantmasid\n"
            . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
            . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
            . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
            . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
            . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
            . "WHERE n.documentname='1' and a.tenantmasid =".$load= $_GET['itemval']
            . " and a.companymasid=$companymasid and a.active='1'";
        }
    }
}
elseif($load == "detailsOfferLetter")
{
  $sql = "SELECT n.cpname,a.leasename,a.tenantcode ,a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
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
    . " and  t.companymasid=$companymasid and a.active='1'" ;
     $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {                    
           $sql = "SELECT n.cpname,a.leasename,a.tenantcode ,a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
            . "FROM trans_offerletter t\n"
            . "INNER JOIN rec_tenant a ON a.tenantmasid = t.tenantmasid\n"
            . "LEFT OUTER JOIN rec_tenant_cp n ON n.tenantmasid = t.tenantmasid\n"
            . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
            . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
            . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
            . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
            . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
            . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
            . "WHERE n.documentname='1'and t.offerlettermasid =".$load= $_GET['itemval']
            . " and  t.companymasid=$companymasid and a.active='1'" ;
        }
    }
}
elseif($load == "detailsRecTenant")
{
  $sql = "SELECT a.tenantmasid,n.cpname,a.leasename,a.tenantcode ,a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
            . "FROM rec_trans_offerletter t\n"
            . "INNER JOIN mas_tenant a ON a.tenantmasid = t.tenantmasid\n"
            . "LEFT OUTER JOIN mas_tenant_cp n ON n.tenantmasid = t.tenantmasid\n"
            . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
            . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
            . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
            . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
            . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
            . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
            . "WHERE n.documentname='1'and t.offerlettermasid =".$load= $_GET['itemval']
            . " and  t.companymasid=$companymasid and a.active='1'" ;
  $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {                    
           $sql = "SELECT n.cpname,a.leasename,a.tenantcode ,a.pincode,a.poboxno, a.city, a.active,DATE_FORMAT( a.doo, \"%d-%m-%Y\" ) AS doo, DATE_FORMAT( a.doc, \"%d-%m-%Y\" ) AS doc, b.age AS term, b1.age AS period,c.buildingname, d.blockname, e.floorname, f.shopcode, f.size\n"
            . "FROM rec_trans_offerletter t\n"
            . "INNER JOIN rec_tenant a ON a.tenantmasid = t.tenantmasid\n"
            . "LEFT OUTER JOIN rec_tenant_cp n ON n.tenantmasid = t.tenantmasid\n"
            . "INNER JOIN mas_age b ON b.agemasid = a.agemasidlt\n"
            . "INNER JOIN mas_age b1 ON b1.agemasid = a.agemasidrc\n"
            . "INNER JOIN mas_building c ON c.buildingmasid = a.buildingmasid\n"
            . "INNER JOIN mas_block d ON d.blockmasid = a.blockmasid\n"
            . "INNER JOIN mas_floor e ON e.floormasid = a.floormasid\n"
            . "INNER JOIN mas_shop f ON f.shopmasid = a.shopmasid\n"
            . "WHERE n.documentname='1'and t.offerlettermasid =".$load= $_GET['itemval']
            . " and  t.companymasid=$companymasid and a.active='1'" ;
        }
    }
}
elseif($load == "detailsRent")
{
  $sql = "select * from trans_offerletter_rent\n"
    . "where offerlettermasid =".$load= $_GET['itemval'];
}
else if($load == "loadTransDetFromRecOfferLetter")
{
    

   
    $currdt = date('Y-m-d');
    $currdt = date("d-F-Y", strtotime(date("d-F-Y", strtotime($currdt)) . " + 0 Year"));
    
   //-table 1
   $sql = "select a. *,b.leasename ,c.age as 'rentcycle'  from rec_trans_offerletter a "
            . " inner join rec_tenant b on b.tenantmasid = a.tenantmasid"
            . " inner join mas_age c on c.agemasid = b.agemasidrc"
            . " WHERE a.offerlettermasid =".$load= $_GET['itemval']." and b.active='1'";
    
    $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {
            $sql = "select a. *,b.leasename ,c.age as 'rentcycle'  from rec_trans_offerletter a "
                . " inner join mas_tenant b on b.tenantmasid = a.tenantmasid"
                . " inner join mas_age c on c.agemasid = b.agemasidrc"
                . " WHERE a.offerlettermasid =".$load= $_GET['itemval']." and b.active='1'";
        }
    }
    
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
   
  
//   //-table 2 rent
   $sql = "select * from rec_trans_offerletter_rent "
   . " WHERE offerlettermasid =".$load= $_GET['itemval'];
   $result=mysql_query($sql);
      
    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
	    $i=1;$n=0;
	    $j=0;            
            $cnt = mysql_num_rows($result);$k=1;
            $tr="";
            while ($row = mysql_fetch_assoc($result))
            {
	       $recofferletterrentmasid = $row["recofferletterrentmasid"];
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
		$toyear = date('Y', strtotime("$todt"));
                $curryr = date('Y');                 
                       
                        $tr .=  "<tr><td colspan='5'><input type='hidden' id='rentcycle' name='rentcycle' style='width:20px;' value='$rentcycle'></tr>";
                        $tr .=  "<tr>";
                        if ($k==1)
                        {   
                            $tr .=  "<td>                        
                            <input type='text' name='dateofrectificationrent' id='dateofrectificationrent' value=".$frmdt." style='width:120px;' />
                            <input type='hidden' id='hidFromdateRent".$n."' name='hidFromdateRent".$n."' value=".$frmdt."> </td>";
                            $k++;
                        }
                        else
                        {
                            $tr .=  "<td>".$frmdt."<input type='hidden' id='hidFromdateRent".$n."' name='hidFromdateRent".$n."' value=".$frmdt."> </td>";
                        }
                        
                        $tr .=  "<td>".$todt."<input type='hidden' id='hidTodateRent".$n."' name='hidTodateRent".$n."' value=".$todt."> </td>";
                        
                        if($n == 0)
                        {
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$n."' dir='rtl' name='rentpercentage".$n."' value=".$yearlyhike." ></td>";
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$n."' dir='rtl' name='rentval".$n."' value=".$amount." /></td>";
                        }
                        else
                        {
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$n."' dir='rtl' name='rentpercentage".$n."' value=".$yearlyhike."></td>";
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$n."' dir='rtl' name='rentval".$n."'  value=".$amount." /></td>";
                        }
                        $tr .=  "<td>".$rentcycle."<input type='hidden' id='hidofferletterrentmasid".$n."' name='hidofferletterrentmasid".$n."' value=".$recofferletterrentmasid."></td></tr>";
                        $n++;                    
	       $j++;
	    }
	}
	
    }
    //-table 3 sc
    $n=0;
   $sql = "select * from rec_trans_offerletter_sc "
   . " WHERE offerlettermasid =".$load= $_GET['itemval'];
   $result=mysql_query($sql);

    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
	    $i=1;
	    $j=0;
	    $tr2="";
            $cnt = mysql_num_rows($result);$k=1;
            while ($row = mysql_fetch_assoc($result))
            {
	       $recofferletterscmasid = $row["recofferletterscmasid"];
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
                $toyear = date('Y', strtotime("$todt"));
                $curryr = date('Y');                  
                        $tr2 .=  "<tr>";
                        $tr2 .=  "<td>".$frmdt."<input type='hidden' id='hidFromdateSc".$n."' name='hidFromdateSc".$n."' value=".$frmdt."></td>";                        
                        $tr2 .=  "<td>".$todt."<input type='hidden' id='hidTodateSc".$n."' name='hidTodateSc".$n."' value=".$todt."> </td>";                        
                        if($n == 0)
                        {
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$n."' dir='rtl' name='servicechrgpercentage".$n."' value=".$yearlyhike."></td>";
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgval".$n."' dir='rtl' name='servicechrgval".$n."' value=".$amount." /> </td>";
                        }
                        else
                        {
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$n."' dir='rtl' name='servicechrgpercentage".$n."' value=".$yearlyhike."></td>";
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgval".$n."' dir='rtl' name='servicechrgval".$n."'  value=".$amount." /></td>";
                        }
                        $tr2 .=  "<td>".$rentcycle."<input type='hidden' id='hidofferletterscmasid".$n."' name='hidofferletterscmasid".$n."' value=".$recofferletterscmasid."></td></tr>";
                        $n++;                    
	       $j++;
	    }
	}
	
    }
        //table 4 deposit
      $tr3="";
   $sql = "select * from rec_trans_offerletter_deposit "
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
		  $offerletterdepositmasid = $row["recofferletterdepositmasid"];
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
    $custom = array('msg'=>$tr,'tbl'=>$tr2,'deposit'=>$tr3,'renttype'=>$renttype,'basicrentval'=>$basicrentval,'sctype'=>$sctype,'basicscval'=>$basicscval,'s'=>"Success");
    $response_array [] = $custom;
    echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
    exit;
}
else if($load == "loadTransDetFromOfferLetter")
{
   
    
    $currdt = date('Y-m-d');
    $currdt = date("d-F-Y", strtotime(date("d-F-Y", strtotime($currdt)) . " + 0 Year"));
    
   //-table 1
   $sql = "select a. *,b.leasename ,c.age as 'rentcycle'  from trans_offerletter a "
	 . " inner join mas_tenant b on b.tenantmasid = a.tenantmasid"
	 . " inner join mas_age c on c.agemasid = b.agemasidrc"
	 . " WHERE a.offerlettermasid =".$load= $_GET['itemval']." and b.active='1'";
    $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {
           $sql = "select a. *,b.leasename ,c.age as 'rentcycle'  from trans_offerletter a "
            . " inner join rec_tenant b on b.tenantmasid = a.tenantmasid"
            . " inner join mas_age c on c.agemasid = b.agemasidrc"
            . " WHERE a.offerlettermasid =".$load= $_GET['itemval']." and b.active='1'";
        }
    }
    
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
   
   //-table 2 rent
   $sql = "select * from trans_offerletter_rent "
   . " WHERE offerlettermasid =".$load= $_GET['itemval'];
    $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {
             $sql = "select * from rec_trans_offerletter_rent "
             . " WHERE offerlettermasid =".$load= $_GET['itemval'];
        }
    }
   $result=mysql_query($sql);
      
    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
	    $i=1;$n=0;
	    $j=0;
            $tr="";
            $cnt = mysql_num_rows($result);$k=1;
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
		$toyear = date('Y', strtotime("$todt"));
                $curryr = date('Y');
                  if ($toyear >= $curryr)
                    {
                       
                        $tr .=  "<tr><td colspan='5'><input type='hidden' id='rentcycle' name='rentcycle' style='width:20px;' value='$rentcycle'></tr>";
                        $tr .=  "<tr>";
                        if ($k==1)
                        {   
                            $tr .=  "<td>
                            <input type='text' name='dateofrectificationrent' id='dateofrectificationrent' value=".$currdt." style='width:120px;' />
                            <input type='hidden' id='hidFromdateRent".$n."' name='hidFromdateRent".$n."' value=".$currdt."></td>";
                            $k++;
                        }
                        else
                        {
                            $tr .=  "<td>".$frmdt."<input type='hidden' id='hidFromdateRent".$n."' name='hidFromdateRent".$n."' value=".$frmdt."> </td>";
                        }
                        
                        $tr .=  "<td>".$todt."<input type='hidden' id='hidTodateRent".$n."' name='hidTodateRent".$n."' value=".$todt."> </td>";
                        
                        if($n == 0)
                        {
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$n."' dir='rtl' name='rentpercentage".$n."' value=".$yearlyhike." ></td>";
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$n."' dir='rtl' name='rentval".$n."' value=".$amount." /></td>";
                        }
                        else
                        {
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentpercentage".$n."' dir='rtl' name='rentpercentage".$n."' value=".$yearlyhike."></td>";
                           $tr .=  "<td><input type='text' style='width:150px;' id='rentval".$n."' dir='rtl' name='rentval".$n."'  value=".$amount." /></td>";
                        }
                        $tr .=  "<td>".$rentcycle."<input type='hidden' id='hidofferletterrentmasid".$n."' name='hidofferletterrentmasid".$n."' value=".$offerletterrentmasid."></td></tr>";
                        $n++;
                    }
	       $j++;
	    }
	}
	
    }
    //-table 3 sc
    $n=0;
   $sql = "select * from trans_offerletter_sc "
   . " WHERE offerlettermasid =".$load= $_GET['itemval'];
   $result=mysql_query($sql);
    if($result !=null)
    {
        $rcount = mysql_num_rows($result);
        if($rcount ==0) 
        {
             $sql = "select * from rec_trans_offerletter_sc "
             . " WHERE offerlettermasid =".$load= $_GET['itemval'];
        }
    }
   $result=mysql_query($sql);

    if($result != null) // if $result <> false
    {
	if (mysql_num_rows($result) > 0)
	{
	    $i=1;
	    $j=0;
	    $tr2="";
            $cnt = mysql_num_rows($result);$k=1;
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
                $toyear = date('Y', strtotime("$todt"));
                $curryr = date('Y');
                  if ($toyear >= $curryr)
                    {
                        $tr2 .=  "<tr>";
                        if ($k==1)
                        {                               
                            $tr2 .=  "<td>$currdt<input type='hidden' id='hidFromdateSc".$n."' name='hidFromdateSc".$n."' value=".$currdt."></td>";
                                $k++;
                        }
                        else
                        {
                            $tr2 .=  "<td>".$frmdt."<input type='hidden' id='hidFromdateSc".$n."' name='hidFromdateSc".$n."' value=".$frmdt."></td>";
                        }
                        
                        $tr2 .=  "<td>".$todt."<input type='hidden' id='hidTodateSc".$n."' name='hidTodateSc".$n."' value=".$todt."> </td>";
                        
                        if($n == 0)
                        {
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$n."' dir='rtl' name='servicechrgpercentage".$n."' value=".$yearlyhike."></td>";
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgval".$n."' dir='rtl' name='servicechrgval".$n."' value=".$amount." /> </td>";
                        }
                        else
                        {
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgpercentage".$n."' dir='rtl' name='servicechrgpercentage".$n."' value=".$yearlyhike."></td>";
                           $tr2 .=  "<td><input type='text' style='width:150px;' id='servicechrgval".$n."' dir='rtl' name='servicechrgval".$n."'  value=".$amount." /></td>";
                        }
                        $tr2 .=  "<td>".$rentcycle."<input type='hidden' id='hidofferletterscmasid".$n."' name='hidofferletterscmasid".$n."' value=".$offerletterscmasid."></td></tr>";
                        $n++;
                    }
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
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthrent' name='depositmonthrent' value=$depositmonthrent>Month;</td>";
			$tr3 .=  "<td align='right'><input  type='text' style='width:150px;' id='deposit1' dir='rtl' name='rentdeposit'  value=$rentdeposit ></td>";
			$tr3 .=  "</tr>";
		  //service charge deposit
		     $depositmonthsc = $row["depositmonthsc"];
		     $scdeposit =    number_format($row["scdeposit"], 2, '.', ','); 
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>2</td>";
		        $tr3 .=  "<td>Security deposit for service charge</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='depositmonthsc' name='depositmonthsc' value=$depositmonthsc>Month;</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit2' dir='rtl' name='scdeposit'  value=$scdeposit ></td>";
			$tr3 .=  "</tr>";
		  //rent advance
		     $advancemonthrent = $row["advancemonthrent"];
		     $rentwithvat =  number_format($row["rentwithvat"], 2, '.', ','); 
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>3</td>";
			$tr3 .=  "<td>Advance rent  with VAT</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthrent' name='advancemonthrent' value=$advancemonthrent>Month</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit3' dir='rtl' name='rentwithvat'  value=$rentwithvat ></td>";
			$tr3 .=  "</tr>";
		     //sc with vat
		     $advancemonthsc = $row["advancemonthsc"];
		     $scwithvat = number_format($row["scwithvat"], 2, '.', ','); 
			$tr3 .=  "<tr>";
			$tr3 .=  "<td align='center'>4</td>";
			$tr3 .=  "<td>Advance service charge with VAT</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' dir ='rtl' id='advancemonthsc' name='advancemonthsc' value=$advancemonthsc>Month</td>";
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit4' dir='rtl' name='scwithvat'  value=$scwithvat ></td>";
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
			$tr3 .=  "<input type='text' style='width:150px;' id='deposit5' dir='rtl' name='leegalfees'  value=$leegalfees ></td>";
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
			$tr3 .=  "<input type='text' style='width:150px;' id='deposit6' dir='rtl' name='stampduty'  value=$stampduty ></td>";
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
			$tr3 .=  "<td align='right'><input type='text' style='width:150px;' id='deposit8' dir='rtl' name='depositTotal'  value = $depositTotal >";
		        $tr3 .=  "<input type='hidden' id='hidofferletterdepositmasid' name='hidofferletterdepositmasid' value=".$offerletterdepositmasid.">";
			$tr3 .=  "</td>";
			$tr3 .=  "</tr>";
	       $j++;
	    }
	}
	
    }

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