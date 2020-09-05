 <?php
include("../config.php");
// The Chart table contains two fields: weekly_task and percentage
// This example will display a pie chart. If you need other charts such as a Bar chart, you will need to modify the code a little to make it work with bar chart and other charts
$sth = mysql_query("select buildingmasid,buildingname from mas_building order by buildingmasid");

$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'Tenant', 'type' => 'string'),
    array('label' => 'Tenantmasid', 'type' => 'number')

);
$obj=0;
$sqlExt="";
if(isset($_GET['myData']))
$obj = $_GET['myData'];


if($obj != "new")
{
    $tMonth = $_GET['tMonth'];
    $tYear = $_GET['tYear'];
    $dt    = $tYear."-".$tMonth."-01";
    $str1= " and date_format(a.createddatetime,'%Y-%m-%d') between date('$dt') and  LAST_DAY('$dt') ";   
}
else
{    
    $str1= "";
    
}

while($a = mysql_fetch_assoc($sth))
{

    $str =" select count(a.leasename) as cnt from mas_tenant a
            inner join mas_shop b on b.shopmasid = a.shopmasid
                       where a.active='1' and a.buildingmasid ='".$a['buildingmasid']."'";
    $str .= $str1;
    $str .= "union select count(a.leasename) as cnt from rec_tenant a
                inner join mas_shop b on b.shopmasid = a.shopmasid
                where a.active='1' and a.buildingmasid = ".$a['buildingmasid'];
    $str .= $str1;
    
    $sql = mysql_query($str);
    //$sql = mysql_query("select count(leasename) as cnt from mas_tenant
    //                   where active='1' and buildingmasid =".$a['buildingmasid'].$str1."
    //                   union
    //                   select count(leasename) as cnt from rec_tenant
    //                   where active='1' and buildingmasid =".$a['buildingmasid']."
    //                   and date_format(createddatetime,'%Y-%m-%d') between date('2013-06-01') and  LAST_DAY('2013-09-01');");
    $cnt =0;    
    while($r = mysql_fetch_assoc($sql)) {
        $cnt += $r['cnt'];
    }
    
        $temp = array();
        // the following line will be used to slice the Pie chart
        //$temp[] = array('v' => (string) $a['buildingname']);
        $temp[] = array('v' => (string) $a['buildingname']); 
        // Values of each slice
        $temp[] = array('v' => (int) $cnt); 
        $rows[] = array('c' => $temp);
    
        $table['rows'] = $rows;
}
$jsonTable = json_encode($table);
echo $jsonTable;

?>