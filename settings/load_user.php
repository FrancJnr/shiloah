<?php

include('../config.php');
session_start();

$response_array = array();
$companymasid = $_SESSION['mycompanymasid'];
$load= $_GET['item'];
$custom = array('msg'=>"Success",'q'=>$load);
$sql="";

if($load == "Employee")
{
     //has to design accorsing to user 
	if($companymasid==1){
 $sql = "select emp_number as empmasid, concat_ws(' ', emp_firstname,emp_lastname) as empname  from orangehrm_mysql.hs_hr_employee order by emp_firstname asc";
 $db_name="orangehrm_mysql";
	}else if($companymasid==2){
 $sql = "select emp_number as empmasid, concat_ws(' ', emp_firstname,emp_lastname) as empname  from grandwayshr.hs_hr_employee order by emp_firstname asc";
 $db_name="grandwayshr";
	}else if($companymasid==3){
 $sql = "select emp_number as empmasid, concat_ws(' ', emp_firstname,emp_lastname) as empname  from katangihr.hs_hr_employee order by emp_firstname asc";
 $db_name="katangihr";
	}
$db_host="localhost";
/* $username="shiloah";
$password="shiloah123";  */
$username="hr";
$password="trymenot#";

$db_con=mysql_connect($db_host,$username,$password);
$connection_string=mysql_select_db($db_name);
// Connection
mysql_connect($db_host,$username,$password) or die('Error, connection query failed');

mysql_select_db($db_name);
}
if($load == "User")
{ 
/*      $sql = "select a.*, b.empname,\n"
    . "case a.active\n"
    . "when \"0\" then \"active\"\n"
    . "when \"1\" then \"disabled\"\n"
    . "end\n"
    . "from mas_user a\n"
    . "inner join mas_employee b on a.empmasid = b.empmasid \n"
    . "order by b.empmasid"; */
    $sql = "select a.*, b.empname, c.* from mas_user a 
	inner join mas_employee b on a.empmasid = b.empmasid 
	left join mas_department c on a.departmentmasid = c.departmentmasid
	order by b.empmasid";
}
else if($load == "UserDetails")
{
    $sql = "select a.username,a.password,c.*, a.active,b.empname from mas_user a\n"
    . "inner join mas_employee b on a.empmasid = b.empmasid\n"
	. "left join mas_department c on a.departmentmasid = c.departmentmasid\n"
    . "where a.usermasid =".$load= $_GET['itemval'];
}
    $result =  mysql_query($sql);
    
    if($result != null) 
    {
        while($obj = mysql_fetch_object($result))
        {
            $arr[] = $obj;
        }
        $response_array [] = $custom;
        echo '{
            "myResult":'.json_encode($arr).',
            "error":'.json_encode($response_array).
        '}';				
    }
    else
    {
        $custom = array('msg'=>"No recodrs available");
        $response_array [] = $custom;
        echo '{
            "error":'.json_encode($response_array).
        '}';
    }
?>