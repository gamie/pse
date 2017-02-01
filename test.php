<?php

$info = array('Last Price','Change' => 0);
$infodetails = array();

$key = "Last Price";
$value = "713";
$infodetails[$key] = $value;

$key = "Change";
$value = "2";
$infodetails[$key] = $value;


foreach ($infodetails as $item) {
    print $item." ".$infodetails[$item]."\n";
}

?>
