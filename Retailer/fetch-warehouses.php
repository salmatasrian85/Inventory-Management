<?php
include 'db_connection.php';

// Fetch warehouses from the database
$sql = "SELECT Warehouse_ID AS id, Warehouse_Name AS name FROM Warehouse";
$result = $conn->query($sql);

$warehouses = [];
while ($row = $result->fetch_assoc()) {
    $warehouses[] = $row;
}

echo json_encode($warehouses);
?>
