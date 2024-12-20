<?php
include 'db_connection.php';

$sql = "SELECT Warehouse_ID, Warehouse_Name, Manager_ID FROM warehouse";
$result = $conn->query($sql);

$warehouses = [];
while ($row = $result->fetch_assoc()) {
    $warehouses[] = $row;
}

echo json_encode($warehouses);
?>
