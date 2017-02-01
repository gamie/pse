#!/usr/bin/php
<?php 

// Stock Information fetcher and import to MySQL
// Version: 1.0
// Author: Gamaliel Lagman
// Last update: 

require_once ('simple_html_dom.php');

// Get and check file //
if (isset($argv[1])) {
	$symbol = $argv[1];
} else {
	die("Please provide stock symbol!\n");
}
$table = file_get_html('/home/gamie/Work/data/stockinfo/'.$symbol);
#$table = file_get_html('https://www.investagrams.com/Stock/AC');


// Initialize Database //
$dbconn = new mysqli('localhost','pse','pse','pse');
/* check connection */
if ($dbconn->connect_error) {
	die('Connect Error: '. $mysql->connect_error);
}


// Initialize functions //
function ExecuteSQL ($dbconn, $symbol, $tablename, $strSQL) {
	$dbconn->query($strSQL);
	if (!preg_match("/^Duplicate entry/", $dbconn->error) and $dbconn->error) {
		$today = date("Ymd H:i:s");
		printf("$today|errorMessage: %s |Symbol: $symbol |Tablename: $tablename |SQL: $strSQL\n", $dbconn->error);
	}
}

// Initializing Arrays and variables //
$info = array('Last Price', 'Open', 'Volume', 'Change', 'Low', 'Value', '%Change', 'High', 'Net Foreign', 'Previous Close', 'Average Price', 
	'52-Week High', 'Earnings Per Share', 'Price to Book Value', '52-Week Low', 'Price-Earnings Ratio', 'Return on Equity', 'Fair Value:', 
	'Dividends Per Share', 'Support 1', 'Resistance 1', 'Short-Term Trend', 'Support 2', 'Resistance 2', 'Year to Date', 'Month to Date', 
	'MA 20', 'MA 50', 'MA 100', 'MA 200', 'RSI', 'MACD', 'ATR', 'CCI', 'STS', 'Williams', 'VolumeSMA', 'CandleStick');

$daily = array('Last Price', 'Open', 'Volume', 'Change', 'Low', 'Value', '%Change', 'High', 'Net Foreign', 'Previous Close', 'Average Price');
$daily_tb = array('symbol','recdate','lastprice','open','volume','pricechange','low','value','percentchange','high','netforeign','prevclose','aveprice');
$daily_vl = array();

$fundamental = array('52-Week High', 'Earnings Per Share', 'Price to Book Value', '52-Week Low', 'Price-Earnings Ratio', 'Return on Equity', 'Fair Value:', 'Dividends Per Share');
$fundamental_tb = array('symbol', 'recdate', 'support1', 'resistance1', 'shorttrend', 'support2', 'resistance2', 'yeartodate', 'monthtodate');
$fundamental_vl = array();

$technical = array('Support 1', 'Resistance 1', 'Short-Term Trend', 'Support 2', 'Resistance 2', 'Year to Date', 'Month to Date', 
	'MA 20', 'MA 50', 'MA 100', 'MA 200', 'RSI', 'MACD', 'ATR', 'CCI', 'STS', 'Williams', 'VolumeSMA', 'CandleStick');
$technical_full_tb = array('symbol', 'recdate', 'support1', 'resistance1', 'shorttrend', 'support2', 'resistance2', 'yeartodate', 'monthtodate',
	'ma20s', 'ma20s_stat', 'ma20e', 'ma20e_stat', 'ma50s', 'ma50s_stat', 'ma50e', 'ma50e_stat', 'ma100s', 'ma100s_stat', 'ma100e', 'ma100e_stat', 
	'ma200s', 'ma200s_stat', 'ma200e', 'ma200e_stat', 'rsi', 'rsi_stat', 'macd', 'macd_signal', 'macd_stat', 'atr', 'atr_percent', 'atr_stat', 
	'cci', 'cci_stat', 'sts', 'sts_stat', 'williams', 'williams_stat', 'volumesma', 'volumesma_stat', 'candlestick', 'candlestick_stat');
$technical_summary_tb = array('symbol', 'recdate', 'support1', 'resistance1', 'shorttrend', 'support2', 'resistance2', 'yeartodate', 'monthtodate');
$technical_indicator_tb1 = array('symbol', 'recdate', 'indicator', 'value1', 'status');
$technical_indicator_tb2 = array('symbol', 'recdate', 'indicator', 'value1', 'value2', 'status');
$technical_indicator_tb3 = array('symbol', 'recdate', 'indicator', 'tvalue', 'status');
$technical_full_vl = array();
$techncail_summary_vl = array();
$technical_indicator_vl1 = array();
$technical_indicator_vl2 = array();
$technical_indicator_vl3 = array();

$infodetails = array();
$dailydetails = array();
$fundamentaldetails = array();
$technicaldetails = array();
$recdate = array();
$datectr = null;


// Extracting stock content from file //
foreach($table->find('tr') as $row){ 
	$cellctr = null;
   foreach($row->find('td') as $cell) {
      $content = $cell->plaintext;
      $content = trim($content);
      foreach ($info as $item) {
         if (preg_match("/^$item/", $content)) {
            if (strpos($item,'MA ') !== false or strpos($item,'RSI') !== false or strpos($item,'MACD') !== false 
					or strpos($item,'ATR') !== false or strpos($item,'CCI') !== false or strpos($item,'STS') !== false 
               or strpos($item,'Williams') !== false or strpos($item,'VolumeSMA') !== false or strpos($item,'CandleStick') !== false)  {
					$value = trim($row->find('td', $cellctr+1)->plaintext).",".trim($row->find('td', $cellctr+2)->plaintext);
					$value = preg_replace ('/\(/', '|', $value);
					if (strpos($item, 'MA ') !== false or strpos($item, 'ATR') !== false) { 
						$value = preg_replace ('/\)/', '|', $value);
					} else {
						$value = preg_replace ('/\)/', '', $value);
					}
					$value = preg_replace ('/\s+/', '', $value);
					#print "$item: $value\n";
					if (strpos($item,'VolumeSMA') !== false) {
						$numeric = preg_replace('/[^\d.]/', '', $value); 
						$words = preg_replace('/\d/', '', $value);
						$value = $numeric."|".$words;
					}
					$value = str_replace("|,","|",$value);
					if (strpos($item,'MACD') !== false or strpos($item,'RSI') !== false 
						or strpos($item,'CCI') !== false or strpos($item,'STS') !== false 
						or strpos($item,'Williams') !== false or strpos($item,'CandleStick') !== false) {
							$value = str_replace(",","|",$value);
 					}
					$value = str_replace("|,","|",$value);
					if (empty($infodetails[$item])) {
				   	$infodetails[$item] = $value;
					}
				} else {
					if (empty($infodetails[$item])) {
						$value = trim($row->find('td', $cellctr+1)->plaintext);
						$value = preg_replace ('/\(/', '|', $value);
						$value = preg_replace ('/\)/', '|', $value);
						$value = preg_replace ('/\s+/', '', $value);
						#print "$item: $value\n";
						#$info[$item] = $value;
				   	$infodetails[$item] = $value;
					}
				}
			}
		}
		$cellctr = $cellctr+1;
   }

   if ($content == "Other Information") {
      break;
   }
}


// Extracting recording date of each section from file //
foreach ($table->find('p[class="stockLastUpdatedTimeStamp"]') as $segment) {
   $datectr = $datectr + 1; 
   $date = $segment->plaintext;
   $date = str_replace("As of: ","",$date); chop($date);
   $recdate[$datectr] = chop($date);
}


// Populating Daily table //
#print "=-=-=-=- Daily =-=-=-=-\n";
#print $recdate[1]."\n";
$recdate[1] = date("Y-m-d",strtotime($recdate[1]));
$strSQL = "insert into daily (symbol,recdate,lastprice,open,volume,pricechange,low,value,percentchange,high,netforeign,prevclose,aveprice) ".
	"values ('".$symbol."','".$recdate[1]."',";
foreach ($daily as $key) {
	$dailydetails[$key] = $infodetails[$key];
   if ($key == "%Change") {
       $dailydetails[$key] = str_replace("%","",$dailydetails[$key]);
       $dailydetails[$key] = $dailydetails[$key] / 100;
   }
   $dailydetails[$key] = str_replace(",","",$dailydetails[$key]);
   $strSQL = $strSQL."'".$dailydetails[$key]."',";
	array_push($daily_vl,$dailydetails[$key]);
  #print $key.": ".$infodetails[$key]."\n";
}
$strSQL = substr($strSQL,0,-1);
$strSQL = $strSQL.")";
$insert = $strSQL;

$strSQL = "update daily set "; 
$daily_ctr = 0;
$column_ctr = 0;
foreach ($daily_tb as $key) {
	$column = $key;
	if ($column_ctr >= 2) {
		$strSQL = $strSQL.$column." = '".$daily_vl[$daily_ctr]."',";
		$daily_ctr = $daily_ctr + 1;
	}
	$column_ctr = $column_ctr + 1;
}
$strSQL = substr($strSQL,0,-1);
$strSQL = $strSQL." where symbol = '".$symbol."' and recdate = '".$recdate[1]."'";
$update = $strSQL;

$dbconn->query($insert);
if ($dbconn->error) {
	if (!preg_match("/^Duplicate entry/", $dbconn->error)) {
		$today = date("Ymd H:i:s");
		printf("$today|errorMessage: %s |Symbol: $symbol |Tablename: daily |SQL: $insert\n", $dbconn->error);
	}
	$dbconn->query($update);
	if ($dbconn->error) {
		if (!preg_match("/^Duplicate entry/", $dbconn->error)) {
			$today = date("Ymd H:i:s");
			printf("$today|errorMessage: %s |Symbol: $symbol |Tablename: daily |SQL: $update\n", $dbconn->error);
		}
	}
}


// Populating Fundamental table //
#print "=-=-=-=- Fundamental =-=-=-=-\n";
#print $recdate[2]."\n";
$recdate[2] = date("Y-m-d H:i:s",strtotime($recdate[2]));
$strSQL = "insert into fundamental (symbol,recdate,weekhigh,eps,epspercent,bookvalue,weeklow,earnratio,roe,fairvalue,fairvaluepercent,dps) ".
	"values ('".$symbol."','".$recdate[2]."',";
$eps = array(); $fairvalue = array();
foreach ($fundamental as $key) {
	$fundamentaldetails[$key] = $infodetails[$key];
   if ($key == "Earnings Per Share") {
		$eps = explode ("|", $fundamentaldetails[$key]);
		$eps[0] = trim($eps[0]); 
		$eps[1] = trim($eps[1]);
		$eps[1] = str_replace("%","",$eps[1]);
		$eps[1] = $eps[1] / 100;
		$fundamentaldetails[$key] = $eps[0]."','".$eps[1];
	}
   if ($key == "Fair Value:") {
		$fairvalue = explode ("|", $fundamentaldetails[$key]);
		$fairvalue[0] = trim($fairvalue[0]); 
		$fairvalue[1] = trim($fairvalue[1]);
		$fairvalue[1] = str_replace("%","",$fairvalue[1]);
		$fairvalue[1] = $fairvalue[1] / 100;
		$fundamentaldetails[$key] = $fairvalue[0]."','".$fairvalue[1];
	}
   if ($key == "Dividends Per Share") {
       $fundamentaldetails[$key] = str_replace("%","",$fundamentaldetails[$key]);
       $fundamentaldetails[$key] = $fundamentaldetails[$key] / 100;
   }
   if ($key !== "Earnings Per Share" and $key !== "Fair Value:") {
   	$fundamentaldetails[$key] = str_replace(",","",$fundamentaldetails[$key]);
   }
   $strSQL = $strSQL."'".$fundamentaldetails[$key]."',";
   #print $key.": ".$infodetails[$key]."\n";
}
$strSQL = substr($strSQL,0,-1);
$strSQL = $strSQL.")";
#print $strSQL."\n";
ExecuteSQL($dbconn, $symbol, "fundamental", $strSQL);


// Populating Technical table //
#print "=-=-=-=- Technical =-=-=-=-\n";
#print $recdate[3]."\n";
$recdate[3] = date("Y-m-d H:i:s",strtotime($recdate[3]));

# Population of data for Technical Full Details
$strSQL = "insert into technical_full (symbol, recdate, support1, resistance1, shorttrend, support2, resistance2, yeartodate, monthtodate, ".
	"ma20s, ma20s_stat, ma20e, ma20e_stat, ma50s, ma50s_stat, ma50e, ma50e_stat, ma100s, ma100s_stat, ma100e, ma100e_stat, ma200s, ma200s_stat, ma200e, ma200e_stat, ".
   "rsi, rsi_stat, macd, macd_signal, macd_stat, atr, atr_percent, atr_stat, cci, cci_stat, sts, sts_stat, williams, williams_stat, volumesma, volumesma_stat, ".
	"candlestick, candlestick_stat) ".
	"values ('".$symbol."','".$recdate[3]."',";
foreach ($technical as $key) {
	$technicaldetails[$key] = $infodetails[$key];
   if ($key == "Year to Date" or $key == "Month to Date") {
      $technicaldetails[$key] = str_replace("%","",$technicaldetails[$key]);
      $technicaldetails[$key] = $technicaldetails[$key] / 100;
   }
   if (preg_match("/^MA/", $key) and $key !== "MACD") {
		$ma = explode ("|", $technicaldetails[$key]);
		foreach ($ma as &$mavalue) {
			$mavalue = str_replace(",","",$mavalue);
		}
		$technicaldetails[$key] = $ma[0]."','".$ma[1]."','".$ma[2]."','".$ma[3];
   }
   if ($key == "RSI" or $key == "CCI" or $key == "STS" or $key == "Williams" or $key == "VolumeSMA" or $key == "CandleStick") {
      $tvalue = explode ("|", $technicaldetails[$key]);
		foreach ($tvalue as &$tdetails) {
			#$tdetails = str_replace(",","",$tdetails);
			$tdetails = str_replace("'","",$tdetails);
		}
      $technicaldetails[$key] = $tvalue[0]."','".$tvalue[1];
   }
   if ($key == "MACD" or $key == "ATR") {
		$technicaldetails[$key] = str_replace(",","|",$technicaldetails[$key]);
      $tvalue = explode ("|", $technicaldetails[$key]);
		foreach ($tvalue as &$tdetails) {
			$tdetails = str_replace(",","",$tdetails);
			$tdetails = str_replace("'","",$tdetails);
		}
      if ($key == "ATR") {
			$tvalue[1] = str_replace("%","",$tvalue[1]);
  	   	$tvalue[1] = $tvalue[1] / 100;
		}
      $technicaldetails[$key] = $tvalue[0]."','".$tvalue[1]."','".$tvalue[2];
   }
   if ($key == "Support 1" or $key == "Support 2" or $key == "Resistance 1" or $key == "Resistance 2") {
      $technicaldetails[$key] = str_replace(",","",$technicaldetails[$key]);
   }
   $strSQL = $strSQL."'".$technicaldetails[$key]."',";
   #print $key.": ".$infodetails[$key]."\n";
}
$strSQL = substr($strSQL,0,-1);
$strSQL = $strSQL.")";
#print $strSQL."\n";
ExecuteSQL($dbconn, $symbol,"technical_full",$strSQL);


# Population of data for Technical Summary
$strSQL = "insert into technical_summary (symbol, recdate, support1, resistance1, shorttrend, support2, resistance2, yeartodate, monthtodate) ".
	"values ('".$symbol."','".$recdate[3]."',";
foreach ($technical as $key) {
	if ($key == "Support 1" or $key == "Resistance 1" or $key == "Short-Term Trend" or $key == "Support 2" or $key == "Resistance 2" or $key == "Year to Date" or $key == "Month to Date") {
		$technicaldetails[$key] = $infodetails[$key];
	   if ($key == "Year to Date" or $key == "Month to Date") {
	      $technicaldetails[$key] = str_replace("%","",$technicaldetails[$key]);
	      $technicaldetails[$key] = $technicaldetails[$key] / 100;
	   }
	   if ($key == "Support 1" or $key == "Support 2" or $key == "Resistance 1" or $key == "Resistance 2") {
	      $technicaldetails[$key] = str_replace(",","",$technicaldetails[$key]);
	   }
	   $strSQL = $strSQL."'".$technicaldetails[$key]."',";
	   #print $key.": ".$infodetails[$key]."\n";
	}
}
$strSQL = substr($strSQL,0,-1);
$strSQL = $strSQL.")";
#print $strSQL."\n";
ExecuteSQL($dbconn,$symbol,"technical_summary",$strSQL);


# Population of data for Technical Indicators
foreach ($technical as $key) {
	if ($key !== "Support 1" and $key !== "Resistance 1" and $key !== "Short-Term Trend" and $key !== "Support 2" and $key !== "Resistance 2" and $key !== "Year to Date" and $key !== "Month to Date") {
		$technicaldetails[$key] = $infodetails[$key];
	   if (preg_match("/^MA/", $key) and $key !== "MACD") {
			$ma = explode ("|", $technicaldetails[$key]);
			foreach ($ma as &$mavalue) {
				$mavalue = str_replace(",","",$mavalue);
			}
			$strSQL = "insert into technical_indicator (symbol, recdate, indicator, value1, status) values ('".$symbol."','".$recdate[3]."','".
				$key." Simple','".$ma[0]."','".$ma[1]."')";
			#print $strSQL."\n";
			ExecuteSQL($dbconn,$symbol,"technical_indicators",$strSQL);
			$strSQL = "insert into technical_indicator (symbol, recdate, indicator, value1, status) values ('".$symbol."','".$recdate[3]."','".
				$key." Exponential','".$ma[2]."','".$ma[3]."')";
			#print $strSQL."\n";
			ExecuteSQL($dbconn,$symbol,"technical_indicators",$strSQL);
	   } elseif ($key == "MACD" or $key == "ATR") {
			$strSQL = "insert into technical_indicator (symbol, recdate, indicator, value1, value2, status) ";
			$technicaldetails[$key] = str_replace(",","|",$technicaldetails[$key]);
		   $tvalue = explode ("|", $technicaldetails[$key]);
			foreach ($tvalue as &$tdetails) {
				$tdetails = str_replace("'","",$tdetails);
			}
		   if ($key == "ATR") {
				$tvalue[1] = str_replace("%","",$tvalue[1]);
		  		$tvalue[1] = $tvalue[1] / 100;
			}
			$strSQL = $strSQL."values ('".$symbol."','".$recdate[3]."','".$key."','".$tvalue[0]."','".$tvalue[1]."','".$tvalue[2]."')";
			#print $strSQL."\n";
			ExecuteSQL($dbconn,$symbol,"technical_indicators",$strSQL);
	   } elseif ($key == "CandleStick") {
			$strSQL = "insert into technical_indicator (symbol, recdate, indicator, tvalue, status) ";
			$strSQL = $strSQL."values ('".$symbol."','".$recdate[3]."','".$key."',";
		   $tvalue = explode ("|", $technicaldetails[$key]);
			foreach ($tvalue as &$tdetails) {
				$tdetails = str_replace(",","",$tdetails);
				$tdetails = str_replace("'","",$tdetails);
			}
		   $technicaldetails[$key] = $tvalue[0]."','".$tvalue[1];
		   $strSQL = $strSQL."'".$technicaldetails[$key]."',";
			$strSQL = substr($strSQL,0,-1);
			$strSQL = $strSQL.")";
			#print $strSQL."\n";
			ExecuteSQL($dbconn,$symbol,"technical_indicators",$strSQL);
	   } else {
			$strSQL = "insert into technical_indicator (symbol, recdate, indicator, value1, status) ";
			$strSQL = $strSQL."values ('".$symbol."','".$recdate[3]."','".$key."',";
		   $tvalue = explode ("|", $technicaldetails[$key]);
			foreach ($tvalue as &$tdetails) {
				$tdetails = str_replace(",","",$tdetails);
				$tdetails = str_replace("'","",$tdetails);
			}
		   $technicaldetails[$key] = $tvalue[0]."','".$tvalue[1];
		   #$technicaldetails[$key] = str_replace(",","",$technicaldetails[$key]);
		   $strSQL = $strSQL."'".$technicaldetails[$key]."',";
			$strSQL = substr($strSQL,0,-1);
			$strSQL = $strSQL.")";
			#print $strSQL."\n";
			ExecuteSQL($dbconn,$symbol,"technical_indicators",$strSQL);
		}
	}
}

$dbconn->close();
?>
