<?php

$dataFetcher = new DataFetcher("EURUSD");

if($dataFetcher->getRetCode() == "1"){
	die("Curl Error");
}

$moves = 0;
$tracker;

$x = 0;
$historicalData = array();
$historicalDataDiff = array();
$historicalDataAvg = array();
foreach($dataFetcher->getData() as $rowData){
	$historicalData[$x] = $rowData->open;
	if($x >0){
		$historicalDataDiff[$x-1] = $historicalData[$x] - $historicalData[$x-1]; 
		$historicalDataAvg[$x-1] = ($historicalData[$x] + $historicalData[$x-1])/2;
	}
	$x++;
}

$totalDeltaMovement = array_sum($historicalDataDiff);
$totalDataAverage = array_sum($historicalDataAvg)/count($historicalDataAvg);
$totalHighLowAvg = (max($historicalData) + min($historicalData))/2;
$currentPrice=  $historicalData[0];
$buy = 0;
echo("\n Deviation from Average\'s Average: ".($currentPrice - $totalDataAverage));
echo ("\n Delta Movement: ".$totalDeltaMovement);
echo("\n Deviation from HighLow Average: ".($currentPrice - $totalHighLowAvg));
echo ("\n High-Low Average: ".$totalHighLowAvg);
echo("\n Data Average: ".$totalDataAverage);

$dataFetcher->setData();
if($dataFetcher->getRetCode() == "1"){
	die("Curl Error");
}


$stockCurrentPrice = $dataFetcher->getData()->bid;


?>