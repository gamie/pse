<?php
    $stocks = simplexml_load_file('http://phisix-api3.appspot.com/stocks.xml');
    foreach ($stocks->stock as $ticker):
        $symbol=$ticker->key;
        $name=$ticker->name;
        $amount=$ticker->price->amount;
        $percent_change=$ticker->percent_change;
        $volume=$ticker->volume;
        print "$symbol, $name, $amount, $percent_change, $volume\n";
    endforeach;
?>
