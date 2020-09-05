<?php
include('../config.php');
session_start();
$response_array = array();
$load= $_GET['item'];
$sql="";
$companymasid = $_SESSION['mycompanymasid'];
if($load == "delete")
{    
    $grouptenantmasid=$_GET['grouptenantmasid'];
    $sql="select tenantmasid from group_tenant_mas where grouptenantmasid=".$_GET['grouptenantmasid'];
    
    $result =  mysql_query($sql);   
    echo mysql_error();
   if($result !=null)
   {
      while($row=mysql_fetch_assoc($result))
      {                     
         $tenantmasid=$row['tenantmasid'];    

      }  
         $sql1="delete from waiting_list where grouptenantmasid=".$grouptenantmasid; 
         $res1=mysql_query($sql1);
         echo mysql_error();
         //$arr[] = $res1;
         $sql2="delete from group_tenant_mas where grouptenantmasid=".$grouptenantmasid; 
         $res2=mysql_query($sql2);
         echo mysql_error();
         //$arr[] = $res2;
         $sql3="delete from group_tenant_det where grouptenantmasid=".$grouptenantmasid; 
         $res3=mysql_query($sql3);
         echo mysql_error();
         //$arr[] = $res3;
         $sql4="delete from rec_tenant where tenantmasid= ".$tenantmasid;
         $res4=mysql_query($sql4);
         echo mysql_error();
         
          $sql5="delete from rec_tenant_cp where tenantmasid= ".$tenantmasid;
         $res5=mysql_query($sql5);
         echo mysql_error();
         
         $sql6="delete from mas_tenant where tenantmasid= ".$tenantmasid;
         $res6=mysql_query($sql6);
         echo mysql_error();
         
          $sql7="delete from mas_tenant_cp where tenantmasid= ".$tenantmasid;
         $res7=mysql_query($sql7);
         echo mysql_error();
         
         
   }
   $sql="select offerlettermasid from trans_offerletter where tenantmasid=".$tenantmasid;
         $result =  mysql_query($sql);   
         echo mysql_error();
   if($result !=null)
   {
      while($row=mysql_fetch_assoc($result))
      {                     
         $offerlettermasid=$row['offerlettermasid'];    
      }  
   
         $sql="delete from trans_offerletter where tenantmasid= ".$tenantmasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
         $sql="delete from trans_offerletter_deposit where offerlettermasid= ".$offerlettermasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
         $sql="delete from trans_offerletter_rent where offerlettermasid= ".$offerlettermasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
         $sql="delete from trans_offerletter_sc where offerlettermasid= ".$offerlettermasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
         $sql="delete from rec_trans_offerletter where offerlettermasid= ".$offerlettermasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
          $sql="delete from rec_trans_offerletter_deposit where offerlettermasid= ".$offerlettermasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
         $sql="delete from rec_trans_offerletter_rent where offerlettermasid= ".$offerlettermasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
         $sql="delete from rec_trans_offerletter_sc where offerlettermasid= ".$offerlettermasid;
         $res=mysql_query($sql);
         echo mysql_error();
         
       
   }
    
    
   $custom = array('msg'=>"",'s'=>"Success"); 
        $response_array [] = $custom;
        echo '{
                  "myResult":'.json_encode($sql1).',
                  "error":'.json_encode($response_array).
               '}';
   
        exit; 
    
 
//      $sql = " select a.grouptenantmasid,c.tenantmasid,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,c.renewalfromid,d.shopcode,d.size from waiting_list a
//            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
//            inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
//            inner join mas_shop d on d.shopmasid = c.shopmasid
//            where c.active='1' and c.renewal =0 and $c
//            union
//            select a1.grouptenantmasid,c1.tenantmasid,c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,c1.renewalfromid,d1.shopcode,d1.size from waiting_list a1
//            inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
//            inner join rec_tenant c1 on c1.tenantmasid =  b1.tenantmasid
//            inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
//            where c1.active='1' and c1.renewal =0  and $c1
//            group by doc order by leasename asc;";
}
else if($load == "waitinglist")
{    
   $buildingmasid = rtrim($_GET['buildingmasid'],",");
   
   $c ="c.companymasid=$companymasid";
   $c1 ="c1.companymasid=$companymasid";
   
   if ($buildingmasid >0)
   {
      $c ="c.buildingmasid in ($buildingmasid)";
      $c1 ="c1.buildingmasid in ($buildingmasid)";
   }
      $sql = " select a.grouptenantmasid,c.tenantmasid,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,c.renewalfromid,d.shopcode,d.size from waiting_list a
            inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
            inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
            inner join mas_shop d on d.shopmasid = c.shopmasid
            where c.active='1' and c.renewal =0 and $c
            union
            select a1.grouptenantmasid,c1.tenantmasid,c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,c1.renewalfromid,d1.shopcode,d1.size from waiting_list a1
            inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
            inner join rec_tenant c1 on c1.tenantmasid =  b1.tenantmasid
            inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
            where c1.active='1' and c1.renewal =0  and $c1
            group by doc order by leasename asc;";
}
else if($load == "runningtenant")
{
   $buildingmasid = rtrim($_GET['buildingmasid'],",");
   
   $c ="c.companymasid=$companymasid";
   $c1 ="c1.companymasid=$companymasid";
   
   if ($buildingmasid >0)
   {
      $c ="c.buildingmasid in ($buildingmasid)";
      $c1 ="c1.buildingmasid in ($buildingmasid)";
   }
   
    $sql= "(select date_format(c.doc,'%d-%m-%Y') as doc,c.leasename ,c.tradingname ,c.renewalfromid,d.shopcode,d.size, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            where $c and c.active='1'  and shopoccupied='1' and a.grouptenantmasid not in (select grouptenantmasid from waiting_list)
            and c.tenantmasid in(select tenantmasid from trans_offerletter))
            union
            (select date_format(c1.doc,'%d-%m-%Y') as doc,c1.leasename ,c1.tradingname ,c1.renewalfromid,d1.shopcode,d1.size, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid
            where $c1 and c1.active='1'  and shopoccupied='1' and a1.grouptenantmasid not in (select grouptenantmasid from waiting_list)
            and c1.tenantmasid in(select tenantmasid from trans_offerletter))
            order by leasename asc;";
}
else if($load == "generate_tenancyrefcode")
{
   $buildingmasid = rtrim($_GET['buildingmasid'],",");
   
   $c ="c.companymasid=$companymasid";
   $c1 ="c1.companymasid=$companymasid";
   
   if ($buildingmasid >0)
   {
      $c ="c.buildingmasid in ($buildingmasid)";
      $c1 ="c1.buildingmasid in ($buildingmasid)";
   }
   
    $sql= "(select date_format(c.doc,'%d-%m-%Y') as doc,c.tenantmasid,c.leasename ,c.buildingmasid,c.tradingname ,c.renewalfromid,d.shopcode,d.size, a.grouptenantmasid from group_tenant_mas a
            inner join mas_tenant c on c.tenantmasid = a.tenantmasid
            inner join mas_shop d on  d.shopmasid = c.shopmasid
            where $c and c.active='1'  and shopoccupied='1' and a.grouptenantmasid not in (select grouptenantmasid from waiting_list)
            and c.tenantmasid in(select tenantmasid from trans_offerletter))
            union
            (select date_format(c1.doc,'%d-%m-%Y') as doc,c1.tenantmasid,c1.leasename ,c1.buildingmasid,c1.tradingname ,c1.renewalfromid,d1.shopcode,d1.size, a1.grouptenantmasid from group_tenant_mas a1
            inner join rec_tenant c1 on c1.tenantmasid = a1.tenantmasid
            inner join mas_shop d1 on  d1.shopmasid = c1.shopmasid
            where $c1 and c1.active='1'  and shopoccupied='1' and a1.grouptenantmasid not in (select grouptenantmasid from waiting_list)
            and c1.tenantmasid in(select tenantmasid from trans_offerletter))
            order by leasename asc;";
   $result =  mysql_query($sql);
   if($result !=null)
   {          
      $arr[] = array('codestatus'=>"");
      while($row = mysql_fetch_assoc($result))
      {         
         $tenantmasid = $row['tenantmasid'];
         $grouptenantmasid = $row['grouptenantmasid'];
         
         $leasename = $row['leasename'];
         $buildingmasid = $row['buildingmasid'];
         $tenancyrefcode = get_tenancyrefcode($leasename,$buildingmasid);
         
         $sqlchk ="select grouptenantmasid from mas_tenancyrefcode where grouptenantmasid = $grouptenantmasid;";
         $resultchk =  mysql_query($sqlchk);
         if($resultchk !=null)
         {
            if(mysql_num_rows($resultchk)<=0)
            {
               $createdby =$_SESSION['myusername'];
               $createddatetime =$datetime;
               $sqlcodeinsert = "insert into mas_tenancyrefcode (`tenancyrefcode`,`tenantmasid`,`grouptenantmasid`,`createdby`,`createddatetime`) values
                                 ('$tenancyrefcode',$tenantmasid,$grouptenantmasid,'$createdby','$createddatetime');";
               $resultcode = mysql_query($sqlcodeinsert);
               if($resultcode == false)
               {
                  $arr[] = array('codestatus'=>mysql_error());                  
               }
               else
               {
                  $arr[] = array('codestatus'=>"Success");
               }
            }
         }
      }
      $custom = array('msg'=>$sql,'s'=>"Success"); 
      $response_array [] = $custom;
      echo '{
         "myResult":'.json_encode($arr).',"error":'.json_encode($response_array).
      '}';
      exit;
   }
}
else if($load == "chkpinno")
{   
   $id= $_GET['grouptenantmasid'];
   $leasename="";$shopcode="";$pinno="";$tenancyrefcode="";
   $sql = "select b.pin,b.leasename,b.tradingname,date_format(b.doc,'%d-%m-%Y') as doc,c.shopcode,c.size,b.buildingmasid from group_tenant_det a
           inner join mas_tenant b on b.tenantmasid = a.tenantmasid
           inner join mas_shop c on c.shopmasid = b.shopmasid
           where a.grouptenantmasid ='$id' and b.active='1'
           union
           select b.pin,b.leasename,b.tradingname,date_format(b.doc,'%d-%m-%Y') as doc,c.shopcode,c.size,b.buildingmasid from group_tenant_det a
           inner join rec_tenant b on b.tenantmasid = a.tenantmasid
           inner join mas_shop c on c.shopmasid = b.shopmasid
           where a.grouptenantmasid ='$id' and b.active='1';";      
   $result =  mysql_query($sql);   
   if($result !=null)
   {
      while($row=mysql_fetch_assoc($result))
      {                     
          if($row['tradingname'] =="")
            $leasename .="<td>".$row['leasename']."</td>";
         else
            $leasename .="<td>".$row['leasename']." T/A ".$row['tradingname']."</td>";
         $shopcode = "( DOC: ".$row['doc']." , ".$row['shopcode']." sq: ".$row['size']." )";
         $pinno = $row['pin'];
         $tenancyrefcode = get_tenancyrefcode($row['leasename'],$row['buildingmasid']);         
      }      
   }
   $custom = array(
      'leasename'=>"<font color='red'>".$leasename."</font>",
      'shopcode'=>"<font color='red'>".$shopcode."</font>",
      'pinno'=>$pinno,
      'tenancyrefcode'=>$tenancyrefcode,
      's'=>"Success");   
   $response_array [] = $custom;
   echo '{
           "error":'.json_encode($response_array).
       '}';   
   exit;
   
   //while($obj = mysql_fetch_object($result))
   //{
   //   $arr[] = $obj;
   //}
   //$custom = array('msg'=>$sql,'s'=>"Success"); 
   //$response_array [] = $custom;
   //echo '{
   //   "myResult":'.json_encode($arr).',"error":'.json_encode($response_array).
   //'}';  
   //$sql="select a.grouptenantmasid ,c.shopoccupied,b.tenantmasid, c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,d.shopcode,d.size,dateDiff(current_timestamp,c.doc) as 'datediff' from waiting_list a
   //      inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
   //      inner join mas_tenant c on c.tenantmasid = b.tenantmasid
   //      inner join mas_shop d on d.shopmasid = c.shopmasid
   //      where c.active='1' and a.grouptenantmasid='$id'
   //      union
   //      select a1.grouptenantmasid ,c1.shopoccupied,b1.tenantmasid, c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,d1.shopcode,d1.size,dateDiff(current_timestamp,c1.doc) as 'datediff' from waiting_list a1
   //      inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
   //      inner join rec_tenant c1 on c1.tenantmasid = b1.tenantmasid
   //      inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
   //      where c1.active='1' and a1.grouptenantmasid='$id'
         //order by leasename;";
}
else if($load == "chkdoc")
{   
   $id= $_GET['grouptenantmasid'];
   $sql="select a.grouptenantmasid ,c.shopoccupied,b.tenantmasid, c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,d.shopcode,d.size,dateDiff(current_timestamp,c.doc) as 'datediff' from waiting_list a
         inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
         inner join mas_tenant c on c.tenantmasid = b.tenantmasid
         inner join mas_shop d on d.shopmasid = c.shopmasid
         where c.active='1' and a.grouptenantmasid='$id'
         union
         select a1.grouptenantmasid ,c1.shopoccupied,b1.tenantmasid, c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,d1.shopcode,d1.size,dateDiff(current_timestamp,c1.doc) as 'datediff' from waiting_list a1
         inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
         inner join rec_tenant c1 on c1.tenantmasid = b1.tenantmasid
         inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
         where c1.active='1' and a1.grouptenantmasid='$id'
         order by leasename asc;";
}
else if($load == "raiseinvoice")
{   
   $id= $_GET['grouptenantmasid'];
   $sql="select a.grouptenantmasid ,b.tenantmasid, c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,d.shopcode,d.size,
         dateDiff(current_timestamp,c.doc) as 'datediff',e.tenancyrefcode from group_tenant_mas a
         inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
         inner join mas_tenant c on c.tenantmasid = b.tenantmasid
         inner join mas_shop d on d.shopmasid = c.shopmasid
         left join mas_tenancyrefcode e on e.grouptenantmasid = b.grouptenantmasid
         where c.active='1' and a.grouptenantmasid='$id'
         union
         select a1.grouptenantmasid ,b1.tenantmasid, c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,d1.shopcode,d1.size,
         dateDiff(current_timestamp,c1.doc) as 'datediff',e1.tenancyrefcode from group_tenant_mas a1
         inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
         inner join rec_tenant c1 on c1.tenantmasid = b1.tenantmasid
         inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
         left join mas_tenancyrefcode e1 on e1.grouptenantmasid = b1.grouptenantmasid
         where c1.active='1' and a1.grouptenantmasid='$id'
         order by leasename asc;";
      //$custom = array('msg'=> $sql ,'s'=>'error');
      //$response_array[] = $custom;
      //echo '{"error":'.json_encode($response_array).'}';
      //exit;
}

$result =  mysql_query($sql);

if($result != null) 
{
    $cnt = mysql_num_rows($result);
   //if($cnt > 0)
   //{
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
   //}
   //else
   //{
   //    $custom = array('msg'=>$sql,'s'=>$sql);
   //    $response_array [] = $custom;
   //    echo '{
   //        "error":'.json_encode($response_array).
   //    '}';
   //}
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