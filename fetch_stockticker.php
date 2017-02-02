#!/usr/bin/php
<?php

// PSE JSON fetcher and import to MySQL
// Version: 1.0
// Author: Gamaliel Lagman


#// Ensures single instance of script run at a time.
#$result = shell_exec("ps aux | grep -v grep |  grep ". basename(__FILE__) );
#echo strlen($result) ? "running" : "not running";

// Initialize Database //
$dbconn = new mysqli('127.0.0.1','pse','pse','pse');
/* check connection */
if ($dbconn->connect_error) {
   die('Connect Error: '. $mysql->connect_error);
}


// Fetching data //
$url = "http://phisix-api3.appspot.com/stocks.json";
$content = file_get_contents($url);
if ($content === false) {
	$today = date("Ymd H:i:s");
   print "$today|Error: fetching $url\n";
   exit();
}
$json = json_decode($content, true);

if (!is_array($json)) {
   print "$today|Error: No content $url\n";
   exit();
}

// Parse data and load to database //
foreach($json['stock'] as $stock) {
   $symbol = $stock['symbol'];
   $name = $stock['name'];
   $amount = $stock['price']['amount'];
   $percent_change = $stock['percent_change'];
   $volume = $stock['volume'];
	
   $insert = "insert into stockticker (symbol,name,amount,percent_change,volume) values "
      ."('$symbol','$name','$amount','$percent_change','$volume')";  
	$update = "update stockticker set amount = '$amount', percent_change = '$percent_change', volume = '$volume' where symbol = '$symbol'";
	$dbconn->query($insert);
	if ($dbconn->error) {
	   if (!preg_match("/^Duplicate entry/", $dbconn->error)) {
	      $today = date("Ymd H:i:s");
	      printf("$today|errorMessage: %s |Symbol: $symbol |Tablename: stockticker |SQL: $insert\n", $dbconn->error);
	   }

		$strSQL = "select volume from stockticker where symbol = '$symbol'";
		$oldvolume = $dbconn->query($strSQL)->fetch_object()->volume; 
		if ($dbconn->error) {
	  		printf("$today|errorMessage: %s |Symbol: $symbol |Tablename: stockticker |SQL: $strSQL\n", $dbconn->error);
		} 
		$volumechange = $volume - $oldvolume;
		$strSQL = "insert into stockticker_int (symbol,amount,percent_change,volume) values "
			."('$symbol','$amount','$percent_change','$volumechange')";
		$dbconn->query($strSQL);
		if ($dbconn->error) {
	  		printf("$today|errorMessage: %s |Symbol: $symbol |Tablename: stockticker |SQL: $strSQL\n", $dbconn->error);
		} 

	   $dbconn->query($update);
	   if ($dbconn->error) {
	      if (!preg_match("/^Duplicate entry/", $dbconn->error)) {
	         $today = date("Ymd H:i:s");
	         printf("$today|errorMessage: %s |Symbol: $symbol |Tablename: stockticker |SQL: $update\n", $dbconn->error);
	      }
	   }
	}
}

mysqli_close($dbconn);

?>
