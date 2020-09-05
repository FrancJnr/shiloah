<?php
include('../config.php');
session_start();
try{
    $companymasid = $_SESSION['mycompanymasid'];
    $action = $_GET['item'];
    $buildingmasid = $_GET['buildingmasid'];
    $buildingname="MEGA PROPERTIES GROUP";
    if($buildingmasid >0)    
    {        
        $sql = "select buildingname from mas_building where buildingmasid = $buildingmasid";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $buildingname = $row["buildingname"];
    }
    
    if($action =="enquirydetails")
    {       
        $fromDt = date('Y-m-d', strtotime($_GET['fromDt']));
        $toDt = date('Y-m-d', strtotime($_GET['toDt']));
        $sql = "select enquirymasid,date_format(enquiryreceivedon,'%d-%m-%Y') as ercd,concat(upper(companyname),' - space for ',upper(nob)) as company,
                upper(cpname) as cpname,concat(address,' - ',a.city) as address,concat(postalcode,' - ',poboxno) as poboxno,
                concat(telephone,' - ', mobile) as phoneno,emailid,
                concat(b.buildingname,' - ',floorname,' - ',area, ' area required for ' ,lower(period)) area,referedby,remarks,a.createdby from mas_enquiry a
                inner join mas_building b on b.buildingmasid = a.buildingmasid ";
                
        if($buildingmasid >0)
        {
            $sql .=" where a.buildingmasid = '$buildingmasid' and date_format(a.createddatetime,'%Y-%m-%d') between '$fromDt' and '$toDt';";
        }
        else
        {
            $sql .=" where date_format(a.createddatetime,'%Y-%m-%d') between '$fromDt' and '$toDt' order by a.buildingmasid;";
        }
        
        
        $table="<p class='printable'><table class='table6'><tr><td colspan='30' style='font-weight:bold;'>".strtoupper($buildingname)." ENQUIRY MASTER DETAILS</td></tr>";        
        $table.="<tr>";
        $table.="<th align='center'>Sno</th>";
        $table.="<th>Company</th>";
        $table.="<th>Area</th>";
        $table.="<th>Address</th>";
        $table.="<th>Referedby</th>";
        $table.="<th>Createdby</th>";
        $table.="<th>Remarks</th>";
        $table.="<th>Follow-up</th>";
        $table.="</tr>";
        $i=1;
        $result = mysql_query($sql);
        if($result != null)
        {
            while($row =mysql_fetch_assoc($result))
            {
                $table.="<tr>";
                $table.="<td align='center'>$i</td>";
                $table.="<td>".$row['company']."</td>";
                $area = 
                $table.="<td>".$row['area']."</td>";
                $address = $row['cpname']."</br>".$row['address']."</br>".$row['poboxno']."</br>".$row['phoneno']."</br>".$row['emailid'];
                $table.="<td>$address</td>";
                $table.="<td>".$row['referedby']."</td>";
                $table.="<td>".$row['createdby']."</td>";
                $table.="<td>".$row['remarks']."</td>";
                $table.="<td>Follow-up</th>";
                $table.="</tr>";
                $i++;
            }
        }        
        $table.="</table></p>";
        $custom = array('divContent'=> "<br>".$table,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
    }
    else if($action =="enquirydetails_cons")
    {
        $fromDt = date('Y-m-d', strtotime($_GET['fromDt']));
        $toDt = date('Y-m-d', strtotime($_GET['toDt']));
        
        $table="<p class='printable'><table class='table6'><tr><td colspan='30' style='font-weight:bold;'>".strtoupper($buildingname)." ENQUIRY CONSOLIDATED DETAILS</td></tr>";        
        $table.="<tr>";
        $table.="<th align='center'>Sno</th>";
        $table.="<th>Building</th>";
        $table.="<th>Enquries</th>";        
        $table.="</tr>";
        $i=1;$cnt=0;
        $sql1 = "select buildingmasid,buildingname from mas_building;";
        $result1 = mysql_query($sql1);
        if($result1 != null)
        {
            while($row1 = mysql_fetch_assoc($result1))
            {
                $buildingmasid = $row1['buildingmasid'];
                $buildingname = $row1['buildingname'];
                $sql2 = "select count(enquirymasid) enquries from mas_enquiry where buildingmasid = '$buildingmasid'
                            and date_format(createddatetime,'%Y-%m-%d') between '$fromDt' and '$toDt';";
                $result2 = mysql_query($sql2);
                if($result2 != null)
                {
                    while($row2 = mysql_fetch_assoc($result2))
                    {
                        $enquries = $row2['enquries'];
                        $cnt +=$enquries;
                        $table.="<tr>";
                        $table.="<td align='center'>$i</td>";
                        $table.="<td>$buildingname</td>";
                        $table.="<td>$enquries</td>";        
                        $table.="</tr>";
                    }
                }
                $i++;
            }
        }
        $table.="<tr>";
        $table.="<td colspan='2'>Totoal</td>";        
        $table.="<td>$cnt</td>";        
        $table.="</tr>";
        $table.="</table></p>";
        $custom = array('divContent'=> "<br>".$table,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
    }
}
catch (Exception $err)
{
    $custom = array(
                'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>