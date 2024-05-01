<?php
require_once ('connection.php');

global $conn;

$sqlQuery = "SELECT `Date`, `Open`, `High`, `Low`, `Close` FROM price";

$stmt = $conn->prepare($sqlQuery);
$stmt->execute();

$output = array();

while ($sqlRow = $stmt->fetch()) {    
    $output[] = $sqlRow;
}

echo json_encode($output);

?>