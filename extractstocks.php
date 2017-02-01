<?php

$url = "http://phisix-api3.appspot.com/stocks.xml";

$ch = curl_init();

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);

$data = curl_exec ($ch);
curl_close($ch);

$xml = simplexml_load_string($data);

#$con = mysql_connect("localhost","pse","pse");
#mysel_select_db("pse", $con) or die(mysql_error());

foreach ($xml -> item as $row) {
   $symbol = $row -> symbol;
   $currency = $row -> currency;
   $amount = $row -> amount;
   $percentage_change = $row -> percentage_change;
   $volume = $row -> volume;

$sql = "insert into 'stockticker' values "
   ."('$symbol','$currency','$amount','$percentage_change','$volume')";  
print "$sql";
#$result = mysql_query($sql);
#
#if (!$result) {
#  echo ' MySQL ERROR\n';
#  } else {
#  echo ' Success\n';
#  }

}

?>
