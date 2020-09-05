<?php
 ////echo php_uname();
 ////echo php_uname('n');
 $companymasid =$_SESSION['mycompanymasid'];
 $client_ip=getIP();
 $access_ip="";
 $sys_name="";
 $invfilepath="";
 $sql ="select ipno,invfilepath,systemname from mas_ip where ipno='$client_ip' and companymasid='$companymasid' and active='1';";
 $result = mysql_query($sql);
 if($result !=null)
 {
     $row =mysql_fetch_assoc($result);
     $access_ip=$row['ipno'];
     $sys_name=$row['systemname'];
     $invfilepath="//".$sys_name."/".$row['invfilepath']."/";
 }
 if($client_ip != $access_ip)
 {
     echo "<h2>PAGE NOT AUTHORISED TO $client_ip </br></br> CONTACT YOUR SYSTEM-ADMIN.</h2>";
     exit;
 }
//    if($client_ip!="127.0.0.1"){
//	    echo "<br><br><br><center><h1><font color='red'>ACCESS DENIED</font></h1></center><br><br><br><h2>Please contact your system-admin.</h2>";
//	    exit;
//    }
//    else
//    {
//	    echo "Your IP :".$client_ip;
//    }