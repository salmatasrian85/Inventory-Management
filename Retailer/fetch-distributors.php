<?php
include 'db_connection.php';

// Fetch distributor details
$sql = "SELECT Distributor_ID AS id, Name AS name FROM distributor";
$result = $conn->query($sql);

$distributors = [];
while ($row = $result->fetch_assoc()) {
    $distributors[] = $row;
}

echo json_encode($distributors);
?>
