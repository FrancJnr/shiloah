<?php
error_reporting(0);
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
$licensemasid = $_GET['licensemasid'];

?>
<!DOCTYPE html>
<html>
<head>  
<script src="../jquery/jquery-1.7.1.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="../jqueryte/jquery-te-1.4.0.css">
<script type="text/javascript" src="../jqueryte/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="../jqueryte/demo/demo.css">
<link type="text/css" rel="stylesheet" href="../jqueryte/jquery-te-1.4.0.css">

<script type="text/javascript" language="javascript">

  $(document).ready(function() {
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$("#status").click(function()
	{
            alert("touche");
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
       $("#save").click(function()
	{ 
          ajaxPOSTTest();
        });
       $("#print").click(function()
	{
          var url = "print_daily_license_pdf.php?licensemasid="+<?php echo $licensemasid;?>
        // window.open(url, "Print PDF", "width=800,height=800,toolbar:false,");
         window.open(url,  "windowOpenTab", "width=800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
         
         return false;
        });

     
    function ajaxPOSTTest() {
      //  alert("haha");
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxPOSTTestRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxPOSTTestRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxPOSTTestRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }

        ajaxPOSTTestRequest.onreadystatechange = ajaxCalled_POSTTest;
        
    
      
      var url = "save_daily_draft_document.php?action=Save&section=dailylease&licensemasid="+<?php echo $licensemasid;?>;
     // var url = "";//"save_draft_document.php?action=Save&section=lease&grouptenantmasid";
      var params =$("form").serialize();
        ajaxPOSTTestRequest.open("POST", url, true);
        ajaxPOSTTestRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajaxPOSTTestRequest.send(params);
    }

    //Create a function that will receive data sent from the server
    function ajaxCalled_POSTTest() {
        if (ajaxPOSTTestRequest.readyState == 4) {
//            document.getElementById("output").innerHTML = ajaxPOSTTestRequest.responseText;
        alert(ajaxPOSTTestRequest.responseText);
        }
    }

        
        });
</script>

</head>

<body>
 
 <?php

    try{
    

//create company view
$viewSql = "create view view_offerletter_company as SELECT * ,\n"
    . "DATE_FORMAT( acyearfrom, \"%d-%m-%Y\" ) as \"d1\" , \n"
    . "DATE_FORMAT( acyearto, \"%d-%m-%Y\" ) as \"d2\"\n"
    . "FROM mas_company where companymasid =". $companymasid;
$result = mysql_query($viewSql);
//load company details
$sql = "SELECT * FROM view_offerletter_company";
$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result))
{
	$companyname = $row['companyname'];
	$companyaddress = $row['address1']."<br> ".$row['address2']."<br>".
			"P.O.Box No: ".$row['poboxno'].",".$row['city']."<br>".
			$row['state'].",".$row['pincode']."<br>".
			$row['country'];
        $companycity = $row['city'];
	$companypoboxno = $row['poboxno'];
	$companypincode = $row['pincode'];
	$companyaddress2 = "P.O.Box No: ".$row['poboxno']."-".$row['pincode'].",".$row['pincode'];
        
	$companytelephone1 =$row['telephone1'];
	$companytelephone2 =$row['telephone2'];
	$companyfax =$row['fax'];
	$companyemailid =$row['emailid'];
	$companywebsite =$row['website'];
}

//create license view
$viewSql = "create view view_daily_tenant as select a.*, b.buildingname, \n"
    . "DATE_FORMAT(a.fromdt, \"%d-%m-%Y\" ) as \"d1\" , DATE_FORMAT( a.todt, \"%d-%m-%Y\" ) as \"d2\" \n"
    . "FROM mas_daily_license a \n"
    . "inner join mas_building b on b.buildingmasid = a.buildingmasid\n"
    . "where a.companymasid =". $companymasid;
$result = mysql_query($viewSql);
//print_r(mysql_error($result));
//load license details
//$sql = "SELECT * FROM view_daily_tenant where licensemasid=". $licensemasid;
$sql = "SELECT *,DATE_FORMAT(fromdt,'%d-%m-%Y')  as d1,DATE_FORMAT(todt,'%d-%m-%Y')  as d2 FROM mas_daily_license a INNER JOIN mas_building b on b.buildingmasid = a.buildingmasid where licensemasid=". $licensemasid;
$result = mysql_query($sql);
//print_r($result);
while ($row = mysql_fetch_assoc($result))
{
    $licensename = $row['licensename'];
    $licensepoboxno = $row['poboxno'];
    $licensepincode = $row['pincode'];
    $licensecity  = strtoupper($row['city']);
    $area = $row['area'];
    $fromdt = $row['d1'];
    $todt = $row['d2'];
//$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
//$f->format(1432);
	//$days=$f->format($row['totaldays']);
	$days=strtoupper(convertNumber($row['totaldays']));
    $rentamount = $row['rentamount'];
    $depositamount = $row['depositamount'];
    $buildingname = $row['buildingname'];
}
if(strtoupper($buildingname) == "MEGA PLAZA"){
					$buildingmunicipaladd = "Kisumu Municipality Block 7/380";
					$rowMunicipalAddress= "Municipality Block 7/380";
					$buildingcouncil = "Kisumu Municipal Council";
				}
				else if(strtoupper($buildingname) == "MEGA CITY"){
					$buildingmunicipaladd = "Kisumu Municipality Block 9/134 &amp; 9/135";
					$rowMunicipalAddress= "Municipality Block 9/134 &amp; 9/135";
					$buildingcouncil = "Kisumu Municipal Council";
				}
				else if(strtoupper($buildingname) == "MEGA MALL"){
					$buildingmunicipaladd = "Kakamega Municipality Block 111/97";
					$rowMunicipalAddress= "Municipality Block 111/97";
					$buildingcouncil = "Kakamega Municipal Council";
				}
				else if(strtoupper($buildingname) == "RELAINCE CENTRE"){					
					$buildingmunicipaladd = "WOODVALE GROVE, WESTLANDS, NAIROBI LR Number. 1870/IX/96, 1870/IX/114 AND 1870/IX/115";
					$rowMunicipalAddress= "";
					$buildingcouncil = "NAIROBI City Council";
				}else if(strtoupper($buildingname) == "KATANGI"){					
				}
				else if(strtoupper($buildingname) == "MEGA CENTRE"){					
					$buildingmunicipaladd = "Kitale Municipality Block 7/14";
					$rowMunicipalAddress= "Municipality Block 7/14";
					$buildingcouncil = "Kitale Municipal Council";
				}
                                
$pa= strtoupper($licensename);                               

//$div1="<span>Preview  Daily Licence for <font color='blue'><u> M/s. ".strtoupper($licensename)."</u></font></span>
        //  <p class='printable'><table width='100%' border=0><tbody><tr><td>";
//$div1 ="<ul id='sortable1' class='connectedSortable'> <li class='ui-widget-content'>
		$div1 = "<table width='100%' border=0>
		    <tr>
                            <center><td valign='middle' align='center'>
                                    <br><br>
                                        <strong><u>DAILY OCCUPATION - LICENSE AGREEMENT</u></strong>
                                    <br><br>
                            </td></center>
                    </tr>
                    <tr>
                        <td valign='middle' align='justify' style='line-height: 2.5em;'>
                           We,<strong>$companyname</strong> a company incorporated in the Republic of Kenya with a limited liability and of
                            $companyaddress2 in the Republic of Kenya (hereinafter called 'the Licensor' which expression shall where the context
                            so admits or requires include its successors and assigns) HEREY GRANT THIS LICENCE to
                            <font color='blue'><u> M/s. ".strtoupper($licensename)."</u></font> of Post Office Box Number $licensepoboxno-$licensepincode, $licensecity in the Republic of Kenya (hereinafter called ' the Licensee '
                            which expression shall where the context so admits or requires include its successors and assigns)
                            TO OCCUPY premises number $buildingmunicipaladd situated at $area (hereinafter known as 'the building') TO BE HELD by the
                            Licensee for <strong>$days DAYS</strong> starting from $fromdt to $todt YIELDING AND PAYING therefore and thereat during the said term at the
                            license fee of K.Shs.$rentamount + VAT to be paid in advance, FOR THE PERIOD ( hereinafter known as the license fee.)
                            <br>                           
                            <br>
                            The license fee in all cases is payable clear of all deductions by monthly in advance on first day of
                            each month without any deductions whatsoever except as authorized by any statutory enactment for the
                            time-being in force. The Licensor has acknowledged to carry forward a deposit of
                            <strong>Kshs. $depositamount.00/=</strong> to be
                            held by the Licensor during the license period free of interest as security for the payment of license
                            fee and undertaking repairs that may have to be carried out within the premises or the building and the
                            performance of all the Licensee's obligations and covenants herein contained. If the license fee or any
                            other monies payable by the Licensee under this lease shall not be paid on its due date the Licensee shall
                            pay to the Licensor penalty for late payment on the said monies to be calculated at 2% per month until payment
                            thereof in full <strong><u>SUBJECT HOWEVER</u></strong> to the following terms and conditions and covenants:-
                        </td>
                    </tr>
                </table>";
$div1 .="<table width='100%' border=0>
		<tr>
		    <td id='content1' valign='middle' align='justify' style='line-height: 2.5em;'>
		    1.	Either of the party to this agreement can terminate the said agreement:-
<br>-	In the event of the Licensee willing to vacate the premises it shall deliver to the Licensor One (1) day's written notice or by payment of 1 day's license fee to the licensor in lieu of such notice;
<br>-	In the event of the Licensor willing to vacate the premises it shall deliver to the Licensee One (1) day's written notice or one (1) day's license fee to the licensor in lieu of such notice.
<br>2.	The Licensee shall unconditionally vacate the premises forthwith upon the determination of this agreement or earlier as provided in this agreement.  
<br>3.	<u>The Licensee to the intent that the obligations hereinafter set out may continue  throughout the continuance of the term hereby created FURTHER COVENANTS AND AGREES with the Licensor as follows:-</u>
<br>4. To pay the rent (or licence fee) herein before reserved at the time and manner  
           aforesaid without any deductions and the rent shall be subject to Value Added  
           Tax and any other statutory taxes which may be levied on the rent, service 
           charge and on any other sums payable herein and to pay all other sums as  
           provided in the sub-lease;	

<br>a)	This agreement shall not be construed, impliedly or expressly, to convert the tenure or occupation of the premises or the said term or any balance thereof within the protection of the Landlord and Tenant (shops, hotels and catering Establishments) Act (Chapter 301) or any Act or Acts for the time being in force amending or replacing the same or any similar Act and shall not have the effect of creating a tenancy of the premises where the premises will be situated AND FURTHER this agreement shall be deemed at all times a simple license agreement between the licensor and the licensee solely for the occupation of the premises and at all times the interest and title to the premises shall solely be vested in the Licensor .

<br>b)	To pay the licensee fee herein before reserved at the time and manner aforesaid and to pay all other sums as provided in this agreement;

<br>c)	To operate strictly between the times of 10 AM TO 8 PM and the licensor shall have the sole discretion to suspend this license granted to the licensee at any time without any notice or without assigning any reasons thereof.  

<br>d)	To keep the premises and the building in a clean state at all times.

<br>e)	To keep the exterior and interior of the premises including all its fittings clean and in good repair order and condition ( fair wear and tear and all acts of God only excepted) also to make good any stoppage of or damage to premises caused or suffered by the Licensee or a member of its servants Licensee or visitors and at the expiration or soon determination of the term hereby granted peaceably and quietly yield up the premises to the Licensor in such state of repair order and condition as the same were at the commencement of the said term ( excepting only as aforesaid ) and with all locks and keys and fastenings complete;

<br>f)	To make good any damage caused to the building (<strong>$buildingname</strong>) where the premises shall be situated by the removal by the Licensee or the Licensee's servants employees agents or others of any furniture goods or other articles into or out of the premises or to its fixtures or resulting from fire explosion air conditioning or electrical short circuits flow or leakage or water or steam by bursting or leaking of pipes or plumbing works or from any other cause of any other kind or nature whatsoever due to carelessness, omission, neglect, improper or negligent conduct or other cause attributable to the Licensee the Licensee's servants employees agents visitors or Licensees;

<br>h)	To use the premises solely for the purpose of <strong>$licensename</strong> by the Licensee AND not to convert use or occupy or permit or suffer to be used the premises or any part thereof into or for any other purpose or business whatsoever AND to use the same only for the purpose hereby authorized and not to use the same for any illegal or immoral purposes and IT IS HEREBY DECLARED AND AGREED that upon breach by the Licensee of the terms of this clause the Licensor may thereupon at any time repossess the premises and if the Licensor shall do so the term hereby created shall determine absolutely PROVIDED THAT in the event of such a determination of the term hereby created the Licensee shall remain liable to the Licensor for payment of all the licensee fee and/or any other sum payable under the terms and conditions of this agreement and for the entire period of this agreement;

<br>i)	Not to assign, transfer, sublet, charge or otherwise part with the possession of the premises or any part thereof without the written consent of the Licensor AND IT IS HEREBY EXPRESSLY AGREED AND DECLARED by and between the parties hereto that upon any breach by the Licensee of this covenant and agreement it shall be lawful for the Licensor to reenter upon the premises without notice and thereupon the said term shall determine absolutely PROVIDED THAT in the event of such a determination of the term hereby created the Licensee shall remain liable to the Licensor for payment of all the licensee fee and/or any other sum payable under the terms and conditions of this agreement and for the entire period of this agreement. 

<br>j)	To comply forthwith in all respect with the provisions of every enactment ( which expressions in this sub clause includes every Act of parliament now or hereinafter enacted and every instrument regulation and by-law and every notice order or direction and every licence consent or permission made or given thereunder so far as the same shall effect the premises and to indemnify the Licensor in respect of all such matters as aforesaid;

<br>k)	To supply a copy to the Licensor of any notice or licence consent or permission relating to the premises within seven (7)days of the receipt thereof by the Licensee;

<br>l)	To perform and observe and also procure performance and observance by the Licensee's servants agents licensees and invitees of the rules and regulations ( including but not limited to regulations as to the opening and closing of the entrance doors ) as the Licensor may make from time to time for the management of the premises or of the building .The Licensee shall accept as final and binding the decision of the Licensor upon any matter arising out of such rules and regulations;

<br>m)	Not to permit or suffer to be done in or upon the premises or any part of the building anything which would or might be or become a nuisance annoyance inconvenience or disturbance to any person whatsoever ( including the tenants of the building and any other premises licensees) and to indemnify the Licensor against any costs charges and expenses incurred by the Licensor in abating such nuisance and execution of all such works as may be necessary for abating a nuisance or for remedying such nuisance;

<br>n)	Not to permit or suffer to be done anything whereby any insurance of the building where the premises is situated may become void or voidable against loss by fire or damage or whereby the rate of premium for any such insurance may be increased;

<br>o)	Not to permit any internal combustion or fires to be burned in the premises;

<br>p)	That no fore court staircase lift or passageway leading to the building shall be damaged or obstructed or used in such manner as to cause in the opinion of the Licensor any nuisance damage or annoyance;

<br>q)	Where applicable no goods or furniture or other equipment shall be carried in the lifts ( if any ) of the building unless previous arrangements have been made with the caretaker of the building in respect of such carriage AND not to allow or suffer or permit in any circumstance the total weight of any one load in any such passenger lift or lifts to exceed the margin of the safety prescribed therefor AND ALSO to observe at all times the rules which may be made by the Licensor from time to time for the operation of such lift or lifts;

<br>r)	Not to hold or permit or suffer to be held any sale by auction in the premises or the building;

<br>s)	That except with the previous consent in writing of the Licensor and in accordance with drawings and specifications approved by the Licensor at the cost of the Licensee no alteration or addition whatsoever shall be made in or to the premises PROVIDED ALWAYS that the Licensor may as a condition of giving any consent require the Licensee to enter into such covenants with the Licensor as the Licensor shall reasonably require in regard to the execution of any alteration or addition to the premises and the reinstatement thereof at the determination of the term hereby granted or otherwise;

<br>t)	No payments by the Licensee howsoever made referable or on account of a period subsequent to the determination of the term hereby created ( whether by affluxion of time or otherwise ) shall constitute deemed or be construed as payment or acceptance of license fee and the same shall not have the effect of creating an occupation license of the premises in favour of the Licensee except where an agreement in favour of the Licensee is expressly and in writing created and entered into by the Licensor;

<br>u)	To give immediate notice to the Licensor if the premises be or become infested with vermin and to cause the same at the Licensee's own expense to be exterminated from time to time to the satisfaction of the Licensor and to employ such exterminators and such exterminating company or companies as shall be approved by the Licensor;

<br>v)	To provide and maintain security services by employing security guards for the premises and it is hereby agreed that the Licensor shall not warrant or guarantee the Licensee for injury ,damage or loss caused by burglary ,theft or otherwise howsoever caused and the Tenant is therefore under an obligation to take out requisite insurance cover against such injury ,loss or burglary as it deems necessary.

<br><br>PROVIDED ALWAYS AND IT IS HEREBY AGREED:
<br>(a)	If the <strong>licensee fee hereby reserved </strong>or any part thereof shall at any time be unpaid after becoming payable (whether lawfully demanded or not) or if any covenant on the part of the Licensee herein contained shall not be performed and observed or if the Licensee (if being a company) in whom for the time being the term hereby created shall be vested to go into liquidation whether compulsory or voluntary or if the Licensee being a person or persons in whom for the time being the term hereby created be vested shall become bankrupt or enter into any agreements with his or her creditors for liquidation of  his ,her ,their debts by composition or otherwise or suffer any distress or process of execution to be levied upon his or her goods then in any of the said case
<strong>it shall be lawful for the Licensor</strong>  at any time thereafter to <strong>repossess and reenter </strong>the premises or any part thereof  in the name of the whole by any action or proceeding or by force or otherwise and to enjoy them in their former state and thereupon this agreement shall  absolutely determine but without prejudice to the right of action of the Licensor in respect of any antecedent breach of any of the agreements on the part of the Licensee herein contained AND the Licensee hereby waives any rights to notice or re-entry or forfeiture under any law for the time being in force PROVIDED:-
<br>i)      In the event of such a determination of the term hereby created the Licensee shall remain liable to the Licensor for payment of all the licensee fees and/or any other sums payable under the terms and conditions of this agreement and for the entire period of this agreement AND;
<br>ii)      the licensor shall be entitled <strong>repossess and reenter</strong> the premises and take possession of all contents therein contained and retain the same as a lien till payment of all sums due under this agreement by the licensee to the licensor AND;
<br>iii)	If any sum due and payable by the licensee to the licensor shall remain unpaid for a period of seven (7) days from the date of termination of this agreement as provided herein, the licensor shall in its unfettered and sole discretion be entitled to sell the contents which were retained by the licensor by public auction or private treaty for recovery of any sums due under this agreement from the licensee;
<br>(b)	The Licensor, the owners and builders of the building shall not be liable for any loss damage or injury to the Licensee, the family, employees, servants, agents, visitors or licensees of the Licensee or the property of any such persons caused by :-
<br>i)	any defects in the premises or in the building or in any defect or electric wiring or of the installation thereof gas pipes stream pipes or from broken stairs or from bursting leaking or running over of any tank tub washstand water closet or waste pipe drains or any other pipe or tank in upon or about the building or the premises nor from the escape of steam or hot water from any boiler or radiator;
<br>ii)	any defective or negligent working constructions or maintenance of the lifts(if any ) or the lighting or equipment of other parts of the structures of the premises or the building provided the same is not attributable to any act or omission of the Licensor the family employees servants agents visitors or licensees of the Licensor;
<br>iii)	any lack or shortage of  water electricity or drainage;
<br>iv)	any act or default ( negligent or otherwise ) of servants of the Licensor employed in any capacity whatsoever;
<br>v)	any act or default of any other Licensees or tenants of the building or any portion thereof including servants or agents or licencees of such other Licensees or tenants;
<br>vi)	any fire, burglary or theft of the goods of the licensee from the premises;
<br>vii)	any fire explosion falling plaster steam rain or leak from any part of the building of from the pipes appliances or plumbing works or from the roofs or from any other place or by dampness however occurring provided the same is not attributable to any act or omission of the Licensor the family employees servants agents visitors or licensees of the Licensor;
<br>c)	The Licensee shall unconditionally and irrevocably indemnify the Licensor against all claims actions and proceedings by the Licensee's employees servants Licensee's agents and others in respect of loss damage or injury;
<br>d)	Each and every of the Licensee's covenants herein shall remain in full force both at law and in equity notwithstanding that the Licensor shall have waive or released in any manner whatsoever a similar covenant or covenants affecting other Licensees of other Premises or the Licensee's of the building;
<br>e)	No provision in this agreement shall be waived or varied by either party hereto except by agreement in writing;

<br><br>5. In the event of a breach by the Lessee of any of the terms, conditions and/or covenants contained herein this agreement shall be determined and terminated and further in case of such a determination of the term hereby created the Licensee shall remain liable to the Licensor for payment of all the licensee fee and/or any other sum payable under the terms and conditions of this agreement and for the entire period of this agreement. 
   
<br>6.	All notices required under this agreement shall be in writing and shall in the case of notice to the Licensee be sufficiently served if addressed to the Licensee and delivered to the Licensee at the premises or sent by pre-paid registered post and in case of notice to the Licensor be sufficiently served if addressed and delivered to him or his authorised agents or posted to him or such agent by registered post so that any notice so posted shall be deemed to have been served within Seven (7) days following the date of posting.
<br>7.	The Licensee hereby accepts this agreement subject to the above conditions, restrictions and stipulations.

		    </td>
		</tr>
          </table>";
//$div1 .='<table width="100%" border="0"> </table>';
 $div1 .='<table width="100%" border="0">
        <tr>
            <td colspan="2" style="height:25px;" align="justify"><strong>IN WITNESS WHEREFORE</strong> the Lessor and the Lessee have caused their Common Seal to be affixed on
            this license agreement on the ____day of___________ '.convert_number(date("Y")).'. <br><br></td>            
        </tr>
        <tr>
            <td style="height:15px;">SEALED with the Common Seal of </td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:15px;">The Lessor: <b>'.$companyname.'</b></td>
            <td>]</td>
        </tr>
         <tr>
            <td style="height:15px;">In the presence of: -</td>
            <td>]</td>
        </tr>
       <tr>
            <td style="height:15px;"></td>
            <td>]</td>
        </tr>       
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]&nbsp;&nbsp;&nbsp;&nbsp;__________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;
            COMPANY SEAL</td>
        </tr>        
        <tr>
            <td style="height:25px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:30px;">_____________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;
            DIRECTOR</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:25px;">SIGNED BY THE LESSEE:</td>
            <td>]</td>
        </tr>
         <tr>
            <td style="height:12px;"></td>
            <td>]</td>
        </tr>
        <tr>
            <td><strong>'.$licensename.'</strong></td> 
            <td>]&nbsp;&nbsp;&nbsp;&nbsp;__________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;
            COMPANY SEAL</td>
        </tr>
        <tr>
            <td style="height:25px;">In the presence of :</td>
            <td>]</td>
        </tr>
        <tr>
            <td style="height:25px;">ADVOCATE:</td>
            <td>]</td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:25px;"><b><u>CERTIFICATE</u></b></td>            
        </tr>
         <tr>
            <td colspan="2" style="height:60px;">I, ____________________________ certify that the above directors of the Lessor
            appeared before me on this _______ day of __________________ '.convert_number(date("Y")).' and being  known to me acknowledged the above
            signatures to be theirs and that they had freely and voluntarily executed this instrument and understood its contents.</td>            
        </tr>
        <tr>
            <td colspan="2" align="center" style="height:35px;"><br><br><b>______________________________________<br>
            <i>Signature and designation of the person certifying</i>
</b></td>
        </tr>
        </table>';    
  
//              $div1 .="<table width='100%' border=0>
//                        <tr>
//                                <td colspan='3' align='center' style='valign='middle'><u>CERTIFICATE</u><br><br></td>
//                        </tr><tr>
//                                <td valign='middle' align='justify'>
//                                        I, ____________________Advocate CERTIFY that the above named persons appeared
//					before me on this _____ Day of _________________ ".convert_number(date('Y'))." and being
//                                        identified to me acknowledged the above signature to be his/her and that he/she had freely and voluntarily executed this
//                                        instrument and understood its contents.
//                                </td>
//                        </tr>
//                </table>";

//$custom = array(
//            'divContent'=>$div1,
//            's'=>'Success');
//    $response_array[] = $custom;

}//try
catch (Exception $err)
{
    $custom = array(
            'divContent'=>"Error :".$err->getMessage()." @ Line no: ".$err->getLine().$viewSql,
            's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
//


function convertNumber($number)
{
    list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer{0} == "-")
    {
        $output = "negative ";
        $integer    = ltrim($integer, "-");
    }
    else if ($integer{0} == "+")
    {
        $output = "positive ";
        $integer    = ltrim($integer, "+");
    }

    if ($integer{0} == "0")
    {
        $output .= "zero";
    }
    else
    {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group   = rtrim(chunk_split($integer, 3, " "), " ");
        $groups  = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g)
        {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++)
        {
            if ($groups2[$z] != "")
            {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? " and "
                            : ", "
                    );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0)
    {
        $output .= " point";
        for ($i = 0; $i < strlen($fraction); $i++)
        {
            $output .= " " . convertDigit($fraction{$i});
        }
    }

    return $output;
}

function convertGroup($index)
{
    switch ($index)
    {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3)
{
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
    {
        return "";
    }

    if ($digit1 != "0")
    {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0")
        {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0")
    {
        $buffer .= convertTwoDigit($digit2, $digit3);
    }
    else if ($digit3 != "0")
    {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0")
    {
        switch ($digit1)
        {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else if ($digit1 == "1")
    {
        switch ($digit2)
        {
            case "1":
                return "eleven";
            case "2":
                return "twelve";
            case "3":
                return "thirteen";
            case "4":
                return "fourteen";
            case "5":
                return "fifteen";
            case "6":
                return "sixteen";
            case "7":
                return "seventeen";
            case "8":
                return "eighteen";
            case "9":
                return "nineteen";
        }
    } else
    {
        $temp = convertDigit($digit2);
        switch ($digit1)
        {
            case "2":
                return "twenty-$temp";
            case "3":
                return "thirty-$temp";
            case "4":
                return "forty-$temp";
            case "5":
                return "fifty-$temp";
            case "6":
                return "sixty-$temp";
            case "7":
                return "seventy-$temp";
            case "8":
                return "eighty-$temp";
            case "9":
                return "ninety-$temp";
        }
    }
}

function convertDigit($digit)
{
    switch ($digit)
    {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}
?>
<style type="text/css">
 ol,tr {
    /*color: blue !important;*/
    line-height: 18.5px !important;
   /* This will increase the rule score */
}
 #container {
  width: 800px;
  height: 50px;
  border: 0px solid black;
  text-align: center; /** center the divs **/
  font-size: 0; /** remove the unwanted space caused by display: inline-block in .child **/
}

#container .status {
  display: inline-block; /** set the divs side-by-side **/
  vertical-align: top;
  width: 100px;
  height: 40px;
  font-size: 12px; /** override font-size: 0 of #container, so that text will be visible again **/
  text-align: left; /** set text in the .child divs back to normal alignment **/
  border: 1px solid blue; /** for viewing purposes **/
  box-sizing: border-box;
}
</style>
<div id="container">
  <button id="status" class="status">Toggle</button>
  <button id="save" class="status" >Save</button>
  <button id="print" class="status">Print</button>
</div>
<form>
 <textarea name="pa" class="jqte-test"><?php echo $pa; ?></textarea>
<textarea name="pb" class="jqte-test"><?php echo $div1; ?></textarea>
<!--<textarea name="pc" class="jqte-test"><?php //echo $div2; ?></textarea>-->
</form>
</body>
</html>