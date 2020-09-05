  <?php
include('../config.php');
//include('../MasterRef_Folder.php');
session_start();  

	$companymasid = $_SESSION['mycompanymasid'];
 $companyname=strtoupper(ucwords($_SESSION["mycompany"]));
 $phptimedate = date("Y-m-d H:i:s"); //today's date and time
 $user=ucwords($_SESSION["myusername"]);
 $companyname1 = ucwords($_SESSION["mycompany"]);
 $res ='';
 
   // For each checked creditnote in the list, update table and copy
   
    
   function pingAddress($ip) {
    $ping = exec("ping -n 2 $ip", $output, $status);
    if (strpos($output[2], 'unreachable') !== FALSE) {
       $res = '<span style="color:#f00;">OFFLINE</span>';
    } else {
        $res = '<span style="color:green;">ONLINE</span>';
    }
}
   
while(list($key, $val) = each($_POST['isminvceselected'])) 
{
	// Check company to use folder
	if($companymasid=='1'){
	   $dest = 'C:/GetPDFs/'.$companyname1.'_'.$val.'.pdf'; // send to get pdf for signing
	   $filename = "C:/GetPDFs/Signed/".$companyname1."_".$val."*"; // Search signed file.
   }
	//Check whether directory is writtable
	//$src="../../pms_docs/creditnote/Grandways Venture Limited_331.pdf";
	 //die ($val);
	 
	 $file = 'D:/xampp/htdocs/pms_docs/man_invoices/'.$companyname1.'_'.$val.'.pdf';


 
/*get file contents and create same file here at new server*/
if (file_exists($file))
 {    
       
 
$data = file_get_contents($file);
$handle = fopen($dest,"w");
fwrite($handle, $data);
fclose($handle);
echo 'Copied Successfully.';
$sqlupdate = "UPDATE invoice_man_mas SET signed = '1' WHERE  invoiceno = $val AND companymasid= '".$companymasid."' ";
		mysql_query($sqlupdate);
	 
     
	// die();
	sleep(15);

	
foreach (glob($filename) as $filefound) {
  // rename($filefound,"test_1296.pdf");
   if (file_exists($filefound)) // if signed file exixts
{
$dest = 'D:/xampp/htdocs/pms_docs/man_invoices/'.$companyname1.'_'.$val.'.pdf'; //pathto copy signed creditnoe to
 
/*get file contents and create same file here at new server*/
$data = file_get_contents($filefound);
$handle = fopen($dest,"w");
fwrite($handle, $data);
fclose($handle);
echo 'Copied Successfully.';
}
else
{
echo "The original file that you want to rename doesn't exist";
}

}
	$sqlupdate2 = "UPDATE invoice_man_mas SET signed = '2' WHERE  invoiceno = $val AND companymasid= '".$companymasid."' ";
		mysql_query($sqlupdate2);
		$res = "Signed and Posted Successfully";
	//die();
} 
//die($sqlupdate);

}
$custom = array('msg'=>$res,'s'=>"Success",'tallyfb'=>$res); 
        $response_array [] = $custom;
		echo '{
               "myResult":'.json_encode($response_array).',
               "error":'.json_encode($response_array).
           '}';
	 exit;
    
?>   
