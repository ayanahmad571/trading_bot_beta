<?php 
if(isset($_GET['ses']) && is_numeric($_GET['ses'])){}else{
	die('Sesion ID Invalid');
}

$sessID = $_GET['ses'];
include("TradePosition.php");
include("DataFetcher.php");

// SET CONSTANTS
$sql = "SELECT * FROM `sessions` where s_id = ".$sessID;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
$capSize = $row['s_amount'];
    }
} else {
    echo "0 results";
}

include_once("Settings.php");
 
include_once("HistoricalDataFetch.php");

for($yy =1; $yy<5; $yy++){
	$cil = $capSize/FRAGMENTS;
	echo "\n Tranche ".$yy;
	echo "\n Capital Investment Liquidity: USD ".$cil;
	
	if(($currentPrice - $totalHighLowAvg) < 0){
		//25% of total investment is determined by this.
		//if price right now is below the HighLow average.
		//buy
		new buyTrade((0.25*$cil),$currentPrice,$sessID);
		echo "\n BUY POSITION OPENED OF AMOUNT ".(0.25*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";
	}else{
		//buy
		new sellTrade((0.25*$cil),$currentPrice,$sessID);
		echo "\n SELL POSITION OPENED OF AMOUNT ".(0.25*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";
	}
	
	if(($currentPrice - $totalDataAverage) < 0){
		//50% of total investmnet by this
		//if price right now is below the Averages average.
		if($totalDataAverage > 0){
		//buy
		new buyTrade((0.5*$cil),$currentPrice,$sessID);
		echo "\n BUY POSITION OPENED OF AMOUNT ".(0.5*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";
		}else{
		new sellTrade((0.5*$cil),$currentPrice,$sessID);
		echo "\n SELL POSITION OPENED OF AMOUNT ".(0.5*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";
		}
		
	
	}else{
		if($totalDataAverage > 0){
		new sellTrade((0.5*$cil),$currentPrice,$sessID);
		echo "\n SELL POSITION OPENED OF AMOUNT ".(0.5*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";
		}else{
		//buy
		new buyTrade((0.5*$cil),$currentPrice,$sessID);
		echo "\n BUY POSITION OPENED OF AMOUNT ".(0.5*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";
		}
	}
	
	if(($currentPrice - $totalDataAverage) < 0){
		//25% of total investmnet by this
		//if price right now is below the Averages average.
		//buy
		new buyTrade((0.25*$cil),$currentPrice,$sessID);
		echo "\n BUY POSITION OPENED OF AMOUNT ".(0.25*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";
	}else{
		new sellTrade((0.25*$cil),$currentPrice,$sessID);
		echo "\n SELL POSITION OPENED OF AMOUNT ".(0.25*$cil)." | PRICE: ".$currentPrice."| SESSION ID: ".$sessID."";

	}
	
}


die();

$start_calc = time();
while($start_calc +RUNTIME_SEC >= time()){
	
	$moves++;
}

?>
