<?php    
    include('../config.php');
    session_start();   
    //$columns = $columns-2;// enable to avoid modifiedby , modifieddatetime columns    
    $to = "marketing@shiloahmega.com";
    $subject = "New Tenancies in Process";        
  /*   $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    $headers .= "From:MEGA ERP PMS" . "\r\n";
    $headers .= 'Cc: dipak@shiloahmega.com,mitesh@shiloahmega.com,arulraj@shiloahmega.com,jacobshavia@gmail.com'. "\r\n"; */
        $to = "marketing@shiloahmega.com";
    $address = "marketing@shiloahmega.com";
	$recipients = array(    
		'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
		'arulraj@shiloahmega.com' => 'Arul Raj',
		'marketing-ho@shiloahmega.com' => 'Marketing Ho',
		'dipak@shiloahmega.com'=>'Dipak',
		'mitesh@shiloahmega.com'=>'Mitesh',
     	'juma@shiloahmega.com'=>'Juma',
		'jacobshavia@gmail.com'=>'Jacob'		
    );  
   // $subject = "Tenancies Expiry List";     
//$subject = "Daily Occupation Licence";        
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    $headers .= 'From: MEGA ERP PMS ' . "\r\n";
$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
/* Mobile-specific Styles */
@media only screen and (max-device-width: 480px) { 
table[class=w0], td[class=w0] { width: 0 !important; }
table[class=w10], td[class=w10], img[class=w10] { width:10px !important; }
table[class=w15], td[class=w15], img[class=w15] { width:5px !important; }
table[class=w30], td[class=w30], img[class=w30] { width:10px !important; }
table[class=w60], td[class=w60], img[class=w60] { width:10px !important; }
table[class=w125], td[class=w125], img[class=w125] { width:80px !important; }
table[class=w130], td[class=w130], img[class=w130] { width:55px !important; }
table[class=w140], td[class=w140], img[class=w140] { width:90px !important; }
table[class=w160], td[class=w160], img[class=w160] { width:180px !important; }
table[class=w170], td[class=w170], img[class=w170] { width:100px !important; }
table[class=w180], td[class=w180], img[class=w180] { width:80px !important; }
table[class=w195], td[class=w195], img[class=w195] { width:80px !important; }
table[class=w220], td[class=w220], img[class=w220] { width:80px !important; }
table[class=w240], td[class=w240], img[class=w240] { width:180px !important; }
table[class=w255], td[class=w255], img[class=w255] { width:185px !important; }
table[class=w275], td[class=w275], img[class=w275] { width:135px !important; }
table[class=w280], td[class=w280], img[class=w280] { width:135px !important; }
table[class=w300], td[class=w300], img[class=w300] { width:140px !important; }
table[class=w325], td[class=w325], img[class=w325] { width:95px !important; }
table[class=w360], td[class=w360], img[class=w360] { width:140px !important; }
table[class=w410], td[class=w410], img[class=w410] { width:180px !important; }
table[class=w470], td[class=w470], img[class=w470] { width:200px !important; }
table[class=w580], td[class=w580], img[class=w580] { width:280px !important; }
table[class=w640], td[class=w640], img[class=w640] { width:300px !important; }
table[class*=hide], td[class*=hide], img[class*=hide], p[class*=hide], span[class*=hide] { display:none !important; }
table[class=h0], td[class=h0] { height: 0 !important; }
p[class=footer-content-left] { text-align: center !important; }
#headline p { font-size: 30px !important; }
.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }
.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}
img { height: auto; line-height: 100%;}
 } 
/* Client-specific Styles */
#outlook a { padding: 0; }	/* Force Outlook to provide a "view in browser" button. */
body { width: 100% !important; }
.ReadMsgBody { width: 100%; }
.ExternalClass { width: 100%; display:block !important; } /* Force Hotmail to display emails at full width */
/* Reset Styles */
/* Add 100px so mobile switch bar doesn"t cover street address. */
body { background-color: #ececec; margin: 0; padding: 0; }
img { outline: none; text-decoration: none; display: block;}
br, strong br, b br, em br, i br { line-height:100%; }
h1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: blue !important; }
h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {	color: red !important; }
/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { color: purple !important; }
/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */  
table td, table tr { border-collapse: collapse; }
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
color: black; text-decoration: none !important; border-bottom: none !important; background: none !important;
}	/* Body text color for the New Yahoo.  This example sets the font of Yahoo"s Shortcuts to black. */
/* This most probably won"t work in all email clients. Don"t include <code _tmplitem="64" > blocks in email. */
code {
  white-space: normal;
  word-break: break-all;
}
#background-table { background-color: #ececec; }
/* Webkit Elements */
#top-bar { border-radius:6px 6px 0px 0px; -moz-border-radius: 6px 6px 0px 0px; -webkit-border-radius:6px 6px 0px 0px; -webkit-font-smoothing: antialiased; background-color: #618442; color: #ede899; }
#top-bar a { font-weight: bold; color: #ede899; text-decoration: none;}
#footer { border-radius:0px 0px 6px 6px; -moz-border-radius: 0px 0px 6px 6px; -webkit-border-radius:0px 0px 6px 6px; -webkit-font-smoothing: antialiased; }
/* Fonts and Content */
body, td { font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.header-content, .footer-content-left, .footer-content-right { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
/* Prevent Webkit and Windows Mobile platforms from changing default font sizes on header and footer. */
.header-content { font-size: 12px; color: #ede899; }
.header-content a { font-weight: bold; color: #ede899; text-decoration: none; }
#headline p { color: #ede899; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; font-size: 36px; text-align: center; margin-top:0px; margin-bottom:30px; }
#headline p a { color: #ede899; text-decoration: none; }
.article-title { font-size: 18px; line-height:24px; color: #e97900; font-weight:bold; margin-top:0px; margin-bottom:18px; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.article-title a { color: #e97900; text-decoration: none; }
.article-title.with-meta {margin-bottom: 0;}
.article-meta { font-size: 13px; line-height: 20px; color: #ccc; font-weight: bold; margin-top: 0;}
.article-content { font-size: 13px; line-height: 18px; color: #444444; margin-top: 0px; margin-bottom: 18px; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.article-content a { color: #e97900; font-weight:bold; text-decoration:none; }
.article-content img { max-width: 100% }
.article-content ol, .article-content ul { margin-top:0px; margin-bottom:18px; margin-left:19px; padding:0; }
.article-content li { font-size: 13px; line-height: 18px; color: #444444; }
.article-content li a { color: #e97900; text-decoration:underline; }
.article-content p {margin-bottom: 15px;}
.footer-content-left { font-size: 12px; line-height: 15px; color: #e2e2e2; margin-top: 0px; margin-bottom: 15px; }
.footer-content-left a { color: #ede899; font-weight: bold; text-decoration: none; }
.footer-content-right { font-size: 11px; line-height: 16px; color: #e2e2e2; margin-top: 0px; margin-bottom: 15px; }
.footer-content-right a { color: #ede899; font-weight: bold; text-decoration: none; }
#footer { background-color: #618442; color: #e2e2e2; }
#footer a { color: #ede899; text-decoration: none; font-weight: bold; }
#permission-reminder { white-space: normal; }
#street-address { color: #ede899; white-space: normal; }
table.gridtable a:link {
	color: #666;
	font-weight: bold;
	text-decoration:none;
}
table.gridtable a:visited {
	color: #999999;
	font-weight:bold;
	text-decoration:none;
}   
table.gridtable a:active,
table.gridtable a:hover {
	color: #bd5a35;
	text-decoration:underline;
}
table.gridtable {
	font-family:Arial, Helvetica, sans-serif;
	color:#666;
	font-size:12px;
	text-shadow: 1px 1px 0px #fff;
	background:#eaebec;
	margin:20px;
	border:#ccc 1px solid;

	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;

	-moz-box-shadow: 0 1px 2px #d1d1d1;
	-webkit-box-shadow: 0 1px 2px #d1d1d1;
	box-shadow: 0 1px 2px #d1d1d1;
}
table.gridtable th {
	padding:21px 25px 22px 25px;
	border-top:1px solid #fafafa;
	border-bottom:1px solid #e0e0e0;

	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table.gridtable th:first-child{
	text-align: left;
	padding-left:20px;
}
table.gridtable tr:first-child th:first-child{
	-moz-border-radius-topleft:3px;
	-webkit-border-top-left-radius:3px;
	border-top-left-radius:3px;
}
table.gridtable tr:first-child th:last-child{
	-moz-border-radius-topright:3px;
	-webkit-border-top-right-radius:3px;
	border-top-right-radius:3px;
}
table.gridtable tr{
	text-align: center;
	padding-left:20px;
}
table.gridtable tr td:first-child{
	text-align: left;
	padding-left:20px;
	border-left: 0;
}
table.gridtable tr td {
	padding:18px;
	border-top: 1px solid #ffffff;
	border-bottom:1px solid #e0e0e0;
	border-left: 1px solid #e0e0e0;
	
	background: #fafafa;
	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table.gridtable tr.even td{
	background: #f6f6f6;
	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table.gridtable tr:last-child td{
	border-bottom:0;
}
table.gridtable tr:last-child td:first-child{
	-moz-border-radius-bottomleft:3px;
	-webkit-border-bottom-left-radius:3px;
	border-bottom-left-radius:3px;
}
table.gridtable tr:last-child td:last-child{
	-moz-border-radius-bottomright:3px;
	-webkit-border-bottom-right-radius:3px;
	border-bottom-right-radius:3px;
}
table.gridtable tr:hover td{
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}

</style>
<!--[if gte mso 9]>
<style _tmplitem="64" >
.article-content ol, .article-content ul {
   margin: 0 0 0 24px;
   padding: 0;
   list-style-position: inside;
}
</style>
<![endif]--></head><body><table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
	<tbody><tr>
		<td align="center" bgcolor="#ececec">
        	<table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
            	<tbody><tr><td class="w640" width="640" height="20"></td></tr>
                
            	<tr>
                	<td class="w640" width="640">
                        <table id="top-bar" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#89a55a">
    <tbody><tr>
        <td class="w15" width="15"></td>
        <td class="w325" width="350" valign="middle" align="left">
            <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w325" width="350" height="8"></td></tr>
            </tbody></table>
            <div class="header-content"><webversion>PMS Web Version</webversion><span class="hide">&nbsp;&nbsp; - &nbsp; <preferences lang="en">13.05</preferences>&nbsp;&nbsp;: :&nbsp; <unsubscribe>Server Updates</unsubscribe></span></div>
            <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w325" width="350" height="8"></td></tr>
            </tbody></table>
        </td>
        <td class="w30" width="30"></td>
        <td class="w255" width="255" valign="middle" align="right">
            <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w255" width="255" height="8"></td></tr>
            </tbody></table>
            <table cellpadding="0" cellspacing="0" border="0">
    <tbody><tr>
        
<!--        <td valign="middle"><fblike><img src="https://img.createsend1.com/img/templatebuilder/like-glyph.png" border="0" width="8" height="14" alt="Facebook icon"=""></fblike></td>
        <td width="3"></td>
        <td valign="middle"><div class="header-content"><fblike>Like</fblike></div></td>
        
        
        <td class="w10" width="10"></td>
        <td valign="middle"><tweet><img src="https://img.createsend1.com/img/templatebuilder/tweet-glyph.png" border="0" width="17" height="13" alt="Twitter icon"=""></tweet></td>
        <td width="3"></td>
        <td valign="middle"><div class="header-content"><tweet>Tweet</tweet></div></td>
        
        
        <td class="w10" width="10"></td>
        <td valign="middle"><forwardtoafriend lang="en"><img src="https://img.createsend1.com/img/templatebuilder/forward-glyph.png" border="0" width="19" height="14" alt="Forward icon"=""></forwardtoafriend></td>
        <td width="3"></td>
        <td valign="middle"><div class="header-content"><forwardtoafriend lang="en">Forward</forwardtoafriend></div></td>-->
        
    </tr>
</tbody></table>
            <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w255" width="255" height="8"></td></tr>
            </tbody></table>
        </td>
        <td class="w15" width="15"></td>
    </tr>
</tbody></table>
                        
                    </td>
                </tr>
                <tr>
                <td id="header" class="w640" width="640" align="center" bgcolor="#89a55a">
    
    <table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
        <tbody><tr><td class="w30" width="30"></td><td class="w580" width="580" height="30"></td><td class="w30" width="30"></td></tr>
        <tr>
            <td class="w30" width="30"></td>
            <td class="w580" width="580">
                <div align="center" id="headline">
                    <p>
                        <strong><singleline label="Title">Shiloah Investments Ltd.</singleline></strong>
                    </p>
                </div>
            </td>
            <td class="w30" width="30"></td>
        </tr>
    </tbody></table>
    
    
</td>
                </tr>
                
                <tr><td class="w640" width="640" height="30" bgcolor="#ffffff"></td></tr>
                <tr id="simple-content-row"><td class="w640" width="640" bgcolor="#ffffff">
    <table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
        <tbody><tr>
            <td class="w30" width="30"></td>
            <td class="w580" width="580">
                <repeater>';                   
                     $message .= '<!-- Waiting List Status -->
                     <layout label="Text only">
                        <table class="w580" width="580" cellpadding="0" cellspacing="0" border="0">
                            <tbody><tr>
                                <td class="w580" width="580">
                                    <p align="left" class="article-title"><singleline label="Title">New Tenancies in Process:</singleline></p>
                                    <div align="left" class="article-content">
                                        <multiline label="Description">';                                                
                                        $sqlb = "select * from mas_building";                                                    
                                        $resultb = mysql_query($sqlb);
                                        if($resultb != null)
                                        {
                                            while ($rowb = mysql_fetch_assoc($resultb))
                                            {
                                                $buildingmasid = $rowb['buildingmasid'];
                                                $message .=' <table class="gridtable" cellspacing="0">
                                                            <tr>'.$rowb['buildingname'].'</tr>
                                                            <tr><th>S.No</th><th>Tenant</th><th>Shop</th><th>Commnce</th><th>Duration</th><th>Expiry</th></tr>';
                                                $sql ="select @a:=@a+1 sno,a.grouptenantmasid,c.tenantmasid,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,c.renewalfromid,d.shopcode,                                                        
                                                        d.size,dateDiff(current_timestamp,c.doc) as 'datediff',
                                                        dateDiff(c.doc,current_timestamp) as 'datediffleft',
                                                        e.age from waiting_list a
                                                        inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                                                        inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
                                                        inner join mas_shop d on d.shopmasid = c.shopmasid
                                                        inner join mas_age e on e.agemasid = c.agemasidlt
                                                        , (select @a:= 0) AS a
                                                        where c.active='1' and c.buildingmasid = $buildingmasid
                                                        union
                                                        select @a:=@a+1 sno,a.grouptenantmasid,c.tenantmasid,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,c.renewalfromid,d.shopcode,
                                                        d.size,dateDiff(current_timestamp,c.doc) as 'datediff',
                                                        dateDiff(c.doc,current_timestamp) as 'datediffleft',
                                                        e.age from waiting_list a
                                                        inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
                                                        inner join rec_tenant c on c.tenantmasid =  b.tenantmasid
                                                        inner join mas_shop d on d.shopmasid = c.shopmasid
                                                        inner join mas_age e on e.agemasid = c.agemasidlt
                                                        , (select @a:= 0) AS a
                                                        where c.active='1' and c.buildingmasid = $buildingmasid
                                                        order by sno;";
                                                $result=mysql_query($sql);
                                                if($result != null)
                                                {
                                                    while($row = mysql_fetch_assoc($result))
                                                    {
                                                        $message .='
                                                            <tr style="font-size:11px;">
                                                                <td>'.$row['sno'].'</td>
                                                                <td>'.$row['leasename'].'</td>                                                                        
                                                                <td>'.$row['shopcode'].' , ['.$row['size'].']</td>                                                                        
                                                                <td>'.$row['age'].'</td>
                                                                <td>'.$row['doc'].'</td>';
                                                        $r = $row['datediff'];
                                                        if($r>0)
                                                            $message .='<td><font color="red">'.$r.' days passed</font></td>';
                                                        else
                                                            $message .='<td><font color="green">'.$row['datediffleft'].' days left</font></td>';
                                                        $message .='</tr>';                                                                                                                                  
                                                    }
                                                }
                                            }
                                        }
                                        $message .= '</table>                                             
                                        </multiline>
                                    </div>
                                </td>
                            </tr>
                            <tr><td class="w580" width="580" height="10"></td></tr>
                        </tbody></table>
                    </layout>                   
                </repeater>
            </td>
            <td class="w30" width="30"></td>
        </tr>
    </tbody></table>
</td></tr>
    <tr><td class="w640" width="640" height="15" bgcolor="#ffffff"></td></tr>
                <tr>
                <td class="w640" width="640">
                <table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#618442">
                    <tbody><tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="30"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
                    <tr>
                        <td class="w30" width="30"></td>
                        <td class="w580" width="360" valign="top">
                        <span class="hide"><p id="permission-reminder" align="left" class="footer-content-left"></p></span>
                        <p align="left" class="footer-content-left"><preferences lang="en">All Rights reserved to  </preferences> | <unsubscribe>megapropertiesgroup.com &copy; 2013</unsubscribe></p>
                        </td>
                        <td class="hide w0" width="60"></td>
                        <td class="hide w0" width="160" valign="top">
                        <p id="street-address" align="right" class="footer-content-right"></p>
                        </td>
                        <td class="w30" width="30"></td>
                    </tr>
                    <tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="15"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
                </tbody></table>
        </td>
                </tr>
                <tr><td class="w640" width="640" height="60"></td></tr>
            </tbody></table>
        </td>
	</tr>
</tbody></table></body></html>';

//ini_set('SMTP','192.168.0.1');// DEFINE SMTP MAIL SERVER
//mail($to,$subject,$message,$headers);
//echo "Mail Sent";

//**************************** EMAIL *************************//               
            $mail->AddAddress($address, "Marketing");            
            foreach($recipients as $email => $name)
            {
               $mail->AddCC($email, $name);
            }
            $mail->Subject    = "Tenancy Waiting List";
            $mail->MsgHTML($message);
            
            if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
              echo "Message sent!";
            }        
        //**************************** EMAIL *************************//

echo $message;
?>
