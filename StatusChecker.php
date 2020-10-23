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
$sql = "SELECT *, ifnull(t_price_close, 0) as tp FROM trades where t_sesh = ".$sessID;
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
	$profits = 0;
	$tradeArray = array();
		echo "
		CURRENT BID: USD ".$currentPrice."
";
while($row = $result->fetch_assoc()) {
		$r_units = $row['t_units'];
		$r_open_price = $row['t_price_open'];
		$r_close_price =  $row['tp'];
		$c_current_pos = $r_units*$stockCurrentPrice;
		$c_original_pos = $r_open_price*$r_units;
			if($r_close_price == 0){
				$status = "open";
				
			}else{
				$status = "closed";
				$c_current_pos = $r_close_price*$r_units;
			}
			
		if($row['t_type'] == 1){
			

			$profits += (($c_current_pos) - ($c_original_pos));
			echo "BUY (".$status.") | UNITS:".$r_units." | OPEN@ USD :".$r_open_price." CLOSED@ USD :".$r_close_price;
			echo " | INVESTED: ".round(($c_original_pos),2)." | POSITION SIZE: ".$c_current_pos." | PROFITS: ".(($c_current_pos) - ($c_original_pos))." |";
			
			$tradeArray[] = new buyTrade(($r_open_price*$r_units),$r_open_price,$sessID,false,$row['t_id']);
			
		}else{

			$profits += (($c_original_pos) -($c_current_pos));
			echo "SELL (".$status.") | UNITS:".$r_units." | OPEN@ USD :".$r_open_price." CLOSED@ USD :".$r_close_price;
			echo " | INVESTED: ".round(($c_original_pos),2)." | POSITION SIZE: ".$c_current_pos." | PROFITS: ".(($c_original_pos) -($c_current_pos))." |";
			
			$tradeArray[] = new sellTrade(($r_open_price*$r_units),$r_open_price,$sessID,false,$row['t_id']);
		}
		echo "\n";
}

$c_perc_profits = (($profits*100)/$capSize);
	if($c_perc_profits >= MAX_PROFIT_PERCENTAGE){
		foreach($tradeArray as $trade){
			$trade->closeTrade();
		}
	}else if($profits >= 0){
	echo "
SMALL PROFIT";

	}else{
		// loss
		if( $c_perc_profits <= (-1*MAX_LOSS_PERCENTAGE)){
			foreach($tradeArray as $trade){
						$trade->closeTrade($currentPrice);

		}
		}else{
			echo "
SMALL LOSS";


		}
	}
	
	
	echo "
	___________________________________________________________________
	|PROFIT: USD ".$profits."                                          
	___________________________________________________________________
	|PROFIT/LOSS %: ".($c_perc_profits)."                      
	___________________________________________________________________
	|INVESTED: USD ".$capSize."                                        
	___________________________________________________________________
	|EQUITY: USD ".number_format(round(($profits + $capSize),3),3)."   
	___________________________________________________________________";
	
	
} 


die();
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
