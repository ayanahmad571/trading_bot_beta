<?php
require_once("DatabaseConnection.php");

abstract class TradePosition {
	protected $initialStockPrice = 0;
	protected $initialPositionAmount = 0;
	protected $unitsBought = 0;
	protected $sessionID= 0;
	protected $tradeID = 0;
	public abstract function getProfit($currentRate);
	function __construct($initialPositionAmount, $initialStockPrice, $sessionID) {
        
        $this->initialStockPrice = $initialStockPrice;
        $this->initialPositionAmount = $initialPositionAmount;
		$this->sessionID = $sessionID;
        $this->unitsBought = $initialPositionAmount / $initialStockPrice ;
		
    }	
	
	function closeTrade($currentPrice) {
        $sql = "update trades set t_price_close = '".$currentPrice."' where t_id = '".$this->tradeID."'";
		mysqlUpdateData($sql);
		echo "
CLOSING TRADE WITH ID: ".$this->tradeID;
    }
}


class buyTrade extends TradePosition{
	function __construct($initialPositionAmount, $initialStockPrice, $sessionID, $newTrade = true, $givenDbID = 0){
		parent::__construct($initialPositionAmount, $initialStockPrice, $sessionID);
		if($newTrade){
			$sql = "INSERT INTO `trades`(`t_sesh`, `t_dnt`, `t_type`, `t_price_open`, `t_units`) 
			VALUES ('".$sessionID."' , '".time()."', '1', '".$initialStockPrice."', '".($initialPositionAmount/$initialStockPrice)."')";
			$this->tradeID = mysqlInsertData($sql, true);
		}else{
			$this->tradeID = $givenDbID;
		}
	}
	
	function getProfit($currentRate){
		//for buy, for profit the new amount must be greater than old
		return ($this->unitsBought * $currentRate) - $initialPositionAmount;
	}
	
}

class sellTrade extends TradePosition{
	
	function __construct($initialPositionAmount, $initialStockPrice, $sessionID, $newTrade = true, $givenDbID = 0){
		parent::__construct($initialPositionAmount, $initialStockPrice, $sessionID);
		if($newTrade){
			$sql = "INSERT INTO `trades`(`t_sesh`, `t_dnt`, `t_type`, `t_price_open`, `t_units`) 
			VALUES ('".$sessionID."' , '".time()."', '2', '".$initialStockPrice."', '".($initialPositionAmount/$initialStockPrice)."')";
			$this->tradeID = mysqlInsertData($sql, true);
		}else{
			$this->tradeID = $givenDbID;
		}
	}
	
	function getProfit($currentRate){
		//for buy, for profit the new amount must be greater than old
		return $initialPositionAmount - ($this->unitsBought * $currentRate);
	}
	

}


?>