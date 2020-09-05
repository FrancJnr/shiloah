<?php
echo "hello world <br>";
$arraylist = array(
                    array('1'=>'100','2'=>'2','3'=>'3'),
                    array('1'=>'200','2'=>'1','3'=>'2')
               );
//print_r($arraylist);

for($j=1;$j<=3;$j++)
{
  
  for($i=2013;$i<=2018;$i++)
  {
    $colname1 = $i."rent";$colname2 = $i."sc";    
    $arrayItem [] = array('rent'=>$colname1,'rentval'=>100,'sc'=>$colname2,'scval'=>10);        
  }  
}

//print('<pre>');
//print_r($arrayItem);
//print('</pre>');

$totals = array();
foreach ($arrayItem AS $row)
{    
    if (!isset($totals[$row['rent']]))
      $totals[$row['rent']] = 0;
      
    if (!isset($totals[$row['sc']]))
      $totals[$row['sc']] = 0;
      
    $totals[$row['rent']] += $row['rentval'];
    $totals[$row['sc']] += $row['scval'];
    //$arr[] = $totals;
}

print('<pre>');
print_r($totals);
print('</pre>');


$sqlGet ="";
$nk =0;
$tabletotal="<table><tr>";

foreach ($totals as $k=>$v) {
  $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";  
  $nk++;
}
$tabletotal .="</tr></table>";

//echo $tabletotal;
echo $sqlGet;