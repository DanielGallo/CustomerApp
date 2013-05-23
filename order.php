<?php

header('Content-Type: application/json');

$method = $_SERVER["REQUEST_METHOD"];
$con = new PDO("mysql:host=127.0.0.1;port=8889;dbname=customers", "root", "root");

$CustomerID = $_GET["CustomerID"];

$sql = 	"SELECT CONCAT(MONTHNAME(OrderDate), ' ', YEAR(OrderDate)) AS Date, SUM(TotalValue) AS Total " .
		"FROM customer_order " .
		"WHERE CustomerID = $CustomerID " .
		"GROUP BY YEAR(OrderDate), MONTH(OrderDate)";

$sql = $con->prepare($sql);
$sql->execute();
$rows = array();

while ($row = $sql->fetch(PDO::FETCH_OBJ)) {
	$rows['data'][] = $row;
}

echo json_encode($rows, JSON_NUMERIC_CHECK);

$con = null;

?>