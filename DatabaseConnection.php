<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trader_Bot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




function mysqlInsertData($sql, $ret = false){
$conn = $GLOBALS['conn'];
if ($conn->query($sql) === TRUE) {
	if($ret){
		return $conn->insert_id;
	}
    
} else {
    die( "Error: " . $sql . "<br>" . $conn->error);
}

	
}
function mysqlUpdateData($sql, $ret = false){
$conn = $GLOBALS['conn'];
if ($conn->query($sql) === TRUE) {
	if($ret){
		return $conn->insert_id;
	}
    
} else {
    die( "Error: " . $sql . "<br>" . $conn->error);
}

	
}

function mysqlSelectSingle($sql){
$conn = $GLOBALS['conn'];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    echo "0 results";
}

}
?>