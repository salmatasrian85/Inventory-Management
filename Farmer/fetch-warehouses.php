<?php
include 'db_connection.php';

$sql = "SELECT Warehouse_ID, Warehouse_Name FROM Warehouse";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
