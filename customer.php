<?php
header('Content-Type: application/json');

$method = $_SERVER["REQUEST_METHOD"];
$con = new PDO("mysql:host=127.0.0.1;port=8889;dbname=customers", "root", "root");

switch ($method) {
	case "GET":		// Return all records

		$sql = 	"SELECT * FROM customer " .
				"ORDER BY Name";

		$sql = $con->prepare($sql);
		$sql->execute();
		$rows = array();

		while ($row = $sql->fetch(PDO::FETCH_OBJ)) {
			$rows['data'][] = $row;
		}

		echo json_encode($rows, JSON_NUMERIC_CHECK);

		break;

	case "PUT":		// Update existing record, requires ID
		$postData = getPostData();
		$ID = getPostValue($postData, 'ID');
		$Name = getPostValue($postData, 'Name');
		$Address = getPostValue($postData, 'Address');

		$sql = 	"UPDATE customer " .
				"SET Name = '$Name', " .
				"Address = '$Address' " .
				"WHERE ID = '$ID' ";

		$sql = $con->prepare($sql);
		$result = $sql->execute();

		break;

	case "POST":	// New record
		$postData = getPostData();
		$Name = getPostValue($postData, 'Name');
		$Address = getPostValue($postData, 'Notes');

		$sql = 	"INSERT INTO customer (Name, Address) " .
				"VALUES ('$Name', '$Address')";

		$sql = $con->prepare($sql);
		$result = $sql->execute();
		$ID = $con->lastInsertId();

		echo "{\"ID\": $ID}";

		break;

	case "DELETE":	// Delete existing record, requires ID
		$postData = getPostData();
		$ID = getPostValue($postData, 'ID');

		$sql = 	"DELETE FROM customer " .
				"WHERE ID = '$ID' ";

		$sql = $con->prepare($sql);
		$result = $sql->execute();

		if (! $result) {
			echo "{\"success\": false}";
		} else {
			echo "{\"ID\": $ID}";
		}

		break;
}

$con = null;

function getPostData() {
	$fileContents = file_get_contents("php://input");
	return json_decode($fileContents, true);
}

function getPostValue($postData, $fieldName) {
	return (!empty($postData[$fieldName]) ? htmlspecialchars($postData[$fieldName]) : NULL);
}

?>