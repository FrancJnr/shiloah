<?php
 session_start();
 include('../config.php');
// include('ip_test.php');
 require_once '../Classes/PHPExcel/IOFactory.php';
 try{
    if (! empty($_FILES['excelfile']))
    {
    $sql="";
    $sqlGet ="";
    $nk =0;
    //$companymasid="";
       while(list($key,$val) = each($_FILES['excelfile']))
        {
            if(!empty($val)) // this will check if any blank field is entered
            {
                $a = array(' ','#','$','%','^','&','*','?');
                $b = array('','No.','Dollar','Percent','','and','','');
                $c = array('');
                /////$path1 = str_replace($a,$b,$path1); // to revome special chracters if any in file path

                $filename = str_replace($a,$b,$val);  // filename stores the value            
                $sqlGet.= $nk."; Name: ".$key."; Value: ".$val."<BR>";
                $nk++;     

                $filePath = $_FILES["excelfile"]["tmp_name"];
                
                ////$filePath = "C:/".$filename;            
                ////$custom = array('msg'=> $filePath ,'s'=>'Success');
                ////$response_array[] = $custom;
                ////echo '{"error":'.json_encode($response_array).'}';
                ////exit;                
            }            
        }
        $sno="";
        //$table=" ASC INVOICE <br><table border='1'>";
        //$table .= '<tr><th>Sno</th><th>Building</th><th>Primary</th><th>Secondary</th><th>Group</th><th>Type</th><th>Qty</th><th>GPVal</th><th>Itemv.val</th><th>Location</th><th>Remarks</th><th>AssetCode</th>';
     class MYPDF extends TCPDF {
        //Page header
        public function Header() {
            // Logo
            $image_file = "../images/mpg1.png";
            $this->Image($image_file, '', '', 39, 39, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // Set font
            //$this->SetFont('helvetica', 'B', 20);
            // Title
            //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }
             // Page footer
            public function Footer() {
                // Position at 15 mm from bottom
                $this->SetY(-15);
                // Set font
                $this->SetFont('dejavusans', 'I', 8);
                // Page number
                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }
        }
   // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);	
        $pdf->SetAutoPageBreak(TRUE, 0);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        $pdf->SetMargins(PDF_MARGIN_LEFT, '', PDF_MARGIN_RIGHT);
        // set default footer margin
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
//        for($i=0; $i<100; $i++){
//            
//        }

// set default font subsetting mode
        $pdf->setFontSubsetting(true);     
        $objPHPExcel = PHPExcel_IOFactory::load($filePath);                
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            
            $worksheetTitle     = $worksheet->getTitle();
          //  echo $worksheetTitle."<br>";
             $highestRow         = $worksheet->getHighestRow(); // e.g. 10
           // echo $highestRow."<br>";
             $highestDataRow= $worksheet->getHighestDataRow();
             $highestDataColumn= $worksheet->getHighestDataColumn();
             $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            //$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            // echo $highestDataColumn."<br>".$highestColumnIndex."<br>";
            $nrColumns = ord($highestColumn) - 64;
            $boo = true;$sql="";
            $sno=0;$ins1="";$ins2="";
            for ($r = 2; $r <= $highestDataRow; $r++) {             
              $buildingname="";$companymasid=0;$invoiceno="";$fromdate="";$todate="";$toaddress="";$buildingmasid="";$grouptenantmasid=0;$premise="";$totalvalue=0;$totalvat=0;$totalamount=0;
             $invoicemanmasid=0;$invoicedescmasid=0;$value=0;$vat=0;$amount=0; $remarks="";$meterno=0; $opening=0; $closing=0; $units=0;$rate=0;
              for ($col = 0; $col < 15; $col ++) {
                  $cell = $worksheet->getCellByColumnAndRow($col, $r); // first row
                  $val = $cell->getFormattedValue();
                  //echo $val;
                  if ($col ==0)
                  {
                    $sno =strtolower(trim(mysql_real_escape_string($val)));
                    //echo $val."<br>"; 
                  }
                  else if($col==1)
                  {
                   // echo $val."<br>"; 
                      $shortname =strtolower(trim(mysql_real_escape_string($val)));  
                    //echo $shortname."<br>";
                    $sql ="select buildingname,companymasid, buildingmasid from mas_building where shortname='$shortname'";
                    $result = mysql_query($sql);
                    //print_r($result);
                    if($result)
                    {
                       $row = mysql_fetch_assoc($result);
                       $companymasid = $row['companymasid'];
                       $buildingname=$row['buildingname'];
                       $buildingmasid = $row['buildingmasid'];                                             
                    }
                    // echo $buildingname."<br>";
                  }
                  else if($col==2)
                  {
                    
                    $tenancyrefcode =strtoupper(trim(mysql_real_escape_string($val)));  
                    //echo $tenancyrefcode."<br>";
                       
                    $sql ="select grouptenantmasid from mas_tenancyrefcode where tenancyrefcode='$tenancyrefcode'";
                    $result = mysql_query($sql);
                    //print_r($result);W
                    if($result!=null)
                    {
                        $row = mysql_fetch_assoc($result);
                        $grouptenantmasid  = $row['grouptenantmasid'];
//                       $buildingname=$row['buildingname'];
//                       $buildingmasid = $row['buildingmasid'];                                             
                    }
       
                    
                    
                  }
                  else if($col==3)
                  {
                    $fromdate =$val;                    
                  //echo $val."<br>5"; 
                    
                  }
                  else if($col==4)
                  {
                    $todate =$val;   
                   //echo $val."<br>6"; 
                  }
                  else if($col==5)
                  {
                    $remarks =strtoupper(trim(mysql_real_escape_string($val)));   
                    //echo $val."<br>7"; 
                  }
                   else if($col==6)
                  {
                    $meterno =strtolower(trim(mysql_real_escape_string($val)));   
                    //echo $val."<br>7"; 
                    $remarks .="<br>Meter No: <b>".$meterno."</b>    ";
                    
                  }
				    else if($col==7)
                  {
                    $opening =strtolower(trim(mysql_real_escape_string($val)));   
                    //echo $val."<br>7"; 
                    $remarks .="<br>Opening Meter Reading: <b>".$opening."</b>    ";
                    
                  }
				    else if($col==8)
                  {
                    $closing =strtolower(trim(mysql_real_escape_string($val)));   
                    //echo $val."<br>7"; 
                    $remarks .="<br>Closing Meter Reading: <b>".$closing."</b>    ";
                    
                  }
                   else if($col==9)
                  {
                    $units =strtolower(trim(mysql_real_escape_string($val)));   
                    //echo $val."<br>7"; 
                     $remarks .="<br>Units Consumed : <b>".$units."</b>   ";
                  }
                   else if($col==10)
                  {
                    $rate =strtolower(trim(mysql_real_escape_string($val)));   
                    //echo $val."<br>7"; 
                    $remarks .="Rate Per Unit : <b>".$rate."</b>   ";
                  }
                  else if($col==11)
                  {
                    $value =strtolower(trim(mysql_real_escape_string($val)));
                    $totalvalue +=$value;   
                    //echo $val."<br>8"; 
                  }
                  else if($col==12)
                  {
                    $vat =strtolower(trim(mysql_real_escape_string($val)));
                    $totalvat +=$vat;
                   // echo $val."<br>9"; 
                  }
                  else if($col==13)
                  {
                     // echo $val."<br>10"; 
                    $amount =strtolower(trim(mysql_real_escape_string($val)));
                    $totalamount +=$amount;                     
                     // echo $amount;
                    }
              }
                // echo $remarks;
                   // echo $amount;
                  if($amount >0)
                  {
                     if($companymasid != $_SESSION['mycompanymasid'])
                     continue;
                   // echo "sawa";
                    $invoiceno = getinvno($companymasid);
                     //setinvno($companymasid,$invoiceno,$datetime);
                   //  echo $invoiceno."<br>";
                    // $invoiceno=getinvnoup($companymasid);
                    // $invoiceno++;
                     $tenancyrefcode = gettenancyrefcode($grouptenantmasid);
                     //echo $grouptenantmasid."<br>";
                     //echo $tenancyrefcode;
                    $tenantaddress="";$buildingaddress="";
                     $buildingmasid="";$companymasid="";
                     $shop="";
                     $sql="select 
                         case b.tradingname 
                                 when b.tradingname ='' then concat(b.leasename ,' (T/A) ',b.tradingname)
                                 when b.tradingname <>'' then concat(b.leasename)  
                         end as tenant,b.remarks,
                         b.poboxno,b.pincode,b.city,b.buildingmasid,b.companymasid,d.buildingname,c.shopcode
                         from group_tenant_det a
                                     inner join mas_tenant b on  b.tenantmasid = a.tenantmasid
                                     inner join mas_shop c on  c.shopmasid = b.shopmasid
                                                 inner join mas_building d on d.buildingmasid = c.buildingmasid
                                     where a.grouptenantmasid = '$grouptenantmasid'
                                     union
                         select 
                         case b1.tradingname 
                                 when b1.tradingname ='' then concat(b1.leasename ,' (T/A) ',b1.tradingname)
                                 when b1.tradingname <>'' then concat(b1.leasename)  
                         end as tenant,b1.remarks,
                         b1.poboxno,b1.pincode,b1.city,b1.buildingmasid,b1.companymasid,d1.buildingname,c1.shopcode
                         from group_tenant_det a1
                                     inner join rec_tenant b1 on  b1.tenantmasid = a1.tenantmasid
                                     inner join mas_shop c1 on  c1.shopmasid = b1.shopmasid
                                                 inner join mas_building d1 on d1.buildingmasid = c1.buildingmasid
                                     where a1.grouptenantmasid = '$grouptenantmasid';";
                     $result =  mysql_query($sql);    
                     if($result != null) 
                     {
                         while ($row = mysql_fetch_assoc($result))
                         {
                             $tenantaddress = $row['tenant']."," ;
                             $tenantaddress .= "Tenancy Refcode: ".$tenancyrefcode.",";
                             if($row['pincode'] == "")
                                     $tenantaddress .= "P.O.Box : ".$row['poboxno']."," ;
                             else
                                 $tenantaddress .= "P.O.Box : ".$row['poboxno']." - ".$row['pincode']."," ;
                             $tenantaddress .= $row['city'].".";
                             $shop .=$row['shopcode']." ";
                             $remarks .=$row['remarks']." ";                
                             $buildingaddress = $row['buildingname']." - Shop no: ".$shop;
                             $buildingmasid = $row['buildingmasid'];
                             $companymasid= $row['companymasid'];
                         }
                     }
                     $toaddress=$tenantaddress;
                     $premise=$buildingaddress;
                     $createdby = $_SESSION['myusername'];
                     $createddatetime = $datetime;
//                     $sfromdate =$fromdate;
//                     
//                     $stodate =$todate;
//                   // echo $todate;
                   
                    
//                     $chk = "select ROW_COUNT() as roe, grouptenantmasid from invoice_man_mas where
//                                fromdate='$sfromdate' and todate='$stodate' and
//                                buildingmasid = '$buildingmasid' and grouptenantmasid ='$grouptenantmasid' and				
//				premise='$premise' and totalvalue='$totalvalue' and totalvat='$totalvat' and totalamount=".$totalamount;
//                    //$sfromdate =$fromdate;
//                     
//                 
//                     $resultchk = mysql_query($chk);
//                    // echo mysql_error();
//                     $rcountchk=0;
//                  //    $rcountchks=0;
////                             $pinno .=$row['pin'];
////                             $vatno .=$row['vatno'];
//                     //  print_r(); 
//                       
//                    if($resultchk!=null)
//                    {
       
                        //$rcountchk = mysql_num_rows($resultchk);
//                         $rcountchks =  mysql_affected_rows();
                      //  echo $rcountchk."<br>".$rcountchks;
//                       while($rows=  mysql_fetch_assoc($result)){
//                           $rcountchk =  mysql_affected_rows(); 
//                           echo $rows['roe']." _ ".$rcountchk;
//                            
//                        if ($rows['roe']<=0)
//                        {
//                            // echo "<br>
                            // 
                            // <br>";
                            // echo "<br>".$invoiceno;
                             setinvno($companymasid,$invoiceno,$datetime);
                            $taxinvoiceno = $invoiceno."/".date('Y');
                            $ins1 ="insert into invoice_man_mas(companymasid,invoiceno,fromdate,todate,toaddress,buildingmasid,grouptenantmasid,premise,totalvalue,totalvat,totalamount,remarks,createdby,createddatetime)
                                   values ('$companymasid','$taxinvoiceno','$fromdate','$todate','$toaddress','$buildingmasid','$grouptenantmasid','$premise','$totalvalue','$totalvat','$totalamount','$remarks','$createdby','$createddatetime')";
                            $result = mysql_query($ins1);
                            if($result == false)
                            {
                                 echo mysql_error();
                                 exit;
                            }
                            else
                            {
                               $invoicemanmasid = mysql_insert_id();
                               $invoicedescmasid=29; //// Direct Electricity Charges
                               $ins2 =" insert into invoice_man_det (invoicemanmasid,invoicedescmasid,value,vat,amount)
                                      values ('$invoicemanmasid','$invoicedescmasid','$value','$vat','$amount')";
                               mysql_query($ins2);
                              
                              // echo "<br>".$datetime;
                               
                              // setinvno($companymasid,$invoiceno,$datetime);
                            
                              
                            }
                            
//                        }  
//                        else
//                        {
//                            echo "Invoice Details Already Exists !!! Please check the building and period.";
//                            exit;
//                        }
//                         }
//                       }
                   //  $resultchk=null;  
                     $address ='<table cellpadding="2" >		
                                <tr height="70px">    
                                        <td width="35%">P.O.Box 2501,Kisumu,Kenya<br>E-Mail:info@shiloahmega.com<br>www.megapropertiesgroup.co.ke</td>
                                        <td width="35%">Mega Plaza Block "A" 3rd Floor<br>Oginga Odinga Road<br>Kisumu.</td>
                                        <td align="right">Tel: 057 - 2023550 / 2021269 <br>Mobile: 0727944400<br>Fax: 254 - 57 - 2021658</td>    
                                </tr>	
                                </table>';
                        $rentdetails='<table width="100%" cellpadding="3" border="1">
                                   <tr align="center">
                                           <th bgcolor="#dddddd" width="10%">S.No</th>
                                           <th bgcolor="#dddddd" width="30%">Description</th>
                                           <th bgcolor="#dddddd" width="20%">Value</th>
                                           <th bgcolor="#dddddd" width="20%">Vat</th>
                                           <th bgcolor="#dddddd" width="20%">Amount</th>
                                   </tr>';
                        $rentdetails .='<tr>
                                           <td>1.</td>
                                           <td>Direct Electricity Charges</td>
                                           <td align="right">'.number_format($value, 0, '.', ',').'</td>
                                           <td align="right">'.number_format($vat, 0, '.', ',').'</td>
                                           <td align="right">'.number_format($amount, 0, '.', ',').'</td>
                                   </tr>';
                        $rentdetails .=' <tr>
                                                <td align="right" colspan="2">Grand Total</td>				
                                                <td align="right">'.number_format($totalvalue, 0, '.', ',').'</td>
                                                <td align="right">'.number_format($totalvat, 0, '.', ',').'</td>
                                                <td align="right">'.number_format($totalamount, 0, '.', ',').'</td>
                                        </tr></table>';
                       $outstring="";$i=0;
                       $toaddress = explode("," , $toaddress);
                    //   print_r($toaddress);
                       foreach ($toaddress as $toaddress) {
                           if($i==0)
                               $outstring .="<b>".$toaddress . "</b><br>";
                           else
                               $outstring .= $toaddress . "<br>";
                           $i++;
                       }
                      $tenantdetails ='<table width="100%">		
                                     <tr>
                                         <td valign="top">TO:<br>'.
                                                         $outstring.'
                                         </td>
                                         <td align="right">			
                                             <table cellpadding="5" border="1" style="font-weight:bold;">
                                                 <tr>
                                                     <td bgcolor="#dddddd" width="35%">Invoice #</td>
                                                     <td align="right">'.$taxinvoiceno.'</td>
                                                 </tr>
                                                  <tr>
                                                     <td bgcolor="#dddddd" width="35%">Date</td>
                                                     <td align="right">'.date("d/m/Y", strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +  0 Months")).'</td>
                                                 </tr>
                                                  <tr>
                                                     <td bgcolor="#dddddd" width="35%">Amount Due</td>
                                                     <td align="right">KSHS '.number_format($totalamount, 0, '.', ',').'/-</td>
                                                 </tr>
                                         </table>
                                         </td>
                                     </tr>
                                     </table>';
                     $premisesdetails='<table width="100%" cellpadding="5" border="1">
                                       <tr>
                                           <td width="25%" bgcolor="#dddddd">Premises / Shop</td>
                                           <td width="75%">'.$premise.'</td>
                                       </tr>                      
                                       <tr>
                                           <td width="25%" bgcolor="#dddddd">Period</td>
                                           <td width="75%"></td>
                                       </tr>
                                     </table>';   
                      $remark='<table cellpadding="5" border="1" width="100%">
                                  <tr height="30px">
                                      <td colspan="5" valign="top">REMARKS IF ANY:<br>'.$remarks.'</td>                                                
                                  </tr>                 
                                 </table>';    
                      $bottom='<table cellpadding="5" border="1" width="100%">
                                  <tr height="30px">
                                      <td colspan="5" valign="top">Terms:</td>                                                
                                  </tr>
                                   <tr height="60px">
                                      <td colspan="5" valign="top" align="justify">                
                                      1. All payments to be acknowledged by official receipts.<br><br>
                                      2. Any disputes on this invoice should be lodged in writing within 7 days of the date hereof.<br><br>		
                                      3. Interest will be charged on over due accounts, as provided in the agreement.<br><br>                
                                      </td>                                                
                                  </tr>
                      </table>';
                     
                     $sql = "select companyname from mas_company where companymasid = ".$companymasid;
                     //echo $sql;
                     $result = mysql_query($sql);
                     if($result !=null)
                     {
                         $row = mysql_fetch_assoc($result);
                         $companyname = $row['companyname'];                       
                     }
                        $pinno ="PIN NO: ";
                     $vatno="VAT NO: ";
                     $sqlpin = "select pin,vatno from mas_company where companymasid=$companymasid;";
                     $resultpin = mysql_query($sqlpin);
                     if($resultpin !=null)
                     {
                         while($row = mysql_fetch_assoc($resultpin))
                         {
                             $pinno .=$row['pin'];
                             $vatno .=$row['vatno'];
                         }
                     }
                  }
              //} end of 10 forloop
                     $pdf->AddPage();	
                     $pdf->ln(7);
                     $pdf->SetFont('dejavusans','B',22);
                     $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>'.strtoupper($companyname).'.</th></tr></table><hr>', true, false, true, false, '');
                     $pdf->ln(0.1);
                     $pdf->SetFont('dejavusans','',9);
                     $pdf->writeHTML($address, true, false, true, false, '');
                     $pdf->ln(11);
                     $pdf->SetFont('dejavusans','',9.5);
                  
                     if($companymasid =='3')
                     {
                         
                         $pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');
                         $pdf->ln(4);		    
                     }
                     else
                     {
                         $pdf->writeHTML('<b>'.$pinno.'</b>', true, false, true, false, '');
                         $pdf->ln(2);	
                         $pdf->writeHTML('<b>'.$vatno.'</b>', true, false, true, false, '');
                         $pdf->ln(2);	
                     }
                     $pdf->SetFont('dejavusans','BU',15);
                     $pdf->writeHTML('<table style="line-height: 1.5px;letter-spacing:+0.100mm;font-stretch:100%;"><tr align="center"><th>INVOICE</th></tr></table>', true, false, true, false, '');	
                     $pdf->ln(8);
                     $pdf->SetFont('dejavusans','',9.5);
                     $pdf->writeHTML($tenantdetails, true, false, true, false, '');
                     $pdf->ln(5);
                     $pdf->writeHTML($premisesdetails, true, false, true, false, '');
                     $pdf->ln(5);
                     $pdf->writeHTML($rentdetails, true, false, true, false, '');
                     $pdf->ln(5);
                     $pdf->writeHTML($remark, true, false, true, false, '');
                     $pdf->ln(5);
                     $pdf->writeHTML($bottom, true, false, true, false, '');
                     
                     $pdf->SetXY(10, 270);
                     $pdf->writeHTML("<hr>", true, false, true, false, '');    
                     if($companymasid =='1')//shiloah
                     {
                             $pdf->SetXY(15, 272);
                             $pdf->Image('../images/mp_logo.jpg', '', '', 14, 14, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                             $pdf->SetXY(95, 272);
                             $pdf->Image('../images/mc_logo.jpg', '', '', 14, 14, '', '', '', false, 200, '', false, false, 0, false, false, false);
                             $pdf->SetXY(180, 272);
                             $pdf->Image('../images/mm_logo.jpg', '', '', 14, 14, '', '', '', false, 300, '', false, false, 0, false, false, false);
                             
                     }
                     //echo $ins1;
                     //echo "</br>";
                     //echo $ins2;
                    
                 // }
              //}
            }
           
        }
        //$table .="</table>";
        //echo $table;
        //echo $ins1;
        //echo "<font color='green'>Transaction Completed</font>";              
       //$pdf->Output($buildingname, 'I');
        $rand=rand(999,100);
        $companyname = $companyname."_".$rand."_".date("d-m-Y");
        $pdf->Output("../../pms_docs/electricity_invoices/".$companyname.".pdf","F");
//        if($companymasid !=3)
//        $pdf->Output($invfilepath.$companyname.".pdf","F");
        echo("<font color='green'>Transaction Completed</font>");
     
        exit;
    }
    else
    {
      echo "Alert: No Files Selected, Please select Electricity charges excel File."; 
      exit;
    }
 }
 catch (Exception $err)
 {
   echo $err->getMessage().", Line No:".$err->getLine();
   exit;
 }
?>
 
