<?php
require_once("DatabaseConnection.php");

if(isset($_GET['trade_size'])){
	if (($_GET['trade_size']) > 10000){
$sql = "INSERT INTO sessions (s_amount, s_time)
VALUES ('".($_GET['trade_size'])."', '".time()."')";
echo mysqlInsertData($sql, true);



	}
}

?>