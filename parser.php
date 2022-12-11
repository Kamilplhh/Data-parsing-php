<?php
$doc = new DOMDocument();
$doc->loadHTMLFile("wo_for_parse.html");

$list = array ('customer', 'trade', 'scheduled_date', 'nte', 'po_number', 'wo_number', 'location_name', 'location_address', 'location_phone');
$columns = array ('Customer', 'Trade', 'Scheduled date', 'Nte', 'PO number', 'Tracking number', 'Store ID', 'Street', 'City', 'State', 'Zip-code', 'Phone number');

$tags = $doc->getElementsByTagName('*');

$file = fopen("raport.csv","w");
$value = array();
fputcsv($file, $columns); 

foreach ($tags as $tag) {
       $node = $tag->getAttribute('id');
                     
       if (in_array($node, $list)) {

              if ($node == "location_address"){
                     $date = $tag->nodeValue;

                     $rest = explode(" ", $date);
                     implode(" ", array_splice($rest, 0, 3));

                     $street = array();
                     $street [] = implode(' ', array_slice(explode(' ', $date), 0, 3));

                     $date = array_merge($street, $rest);
                     $value = array_merge($value, $date);

              } elseif($node == "nte") {
                     $nte = array();

                     $number = $tag->nodeValue;
                     $number = ltrim($number, $number[0]);
                     $nte [] = str_replace(',', '.', $number);

                     $value = array_merge($value, $nte);

              } elseif ($node == "scheduled_date"){
                     $datetime = array();

                     $time = $tag->nodeValue;
                     $time = str_replace(',', 'th', $time);
                     $time = strtotime($time);
                     $datetime [] = date("Y-m-d H:i", $time);

                     $value = array_merge($value, $datetime);
                      
              } else {
                     $value [] = $tag->nodeValue;
              }
                       
       }
       
}

print_r($value);
fputcsv($file, $value);

?>