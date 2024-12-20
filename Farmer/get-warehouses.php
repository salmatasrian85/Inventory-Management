<?php
include 'db_connection.php';

$sql = "SELECT Warehouse_ID AS id, Warehouse_Name AS name, Location AS location FROM Warehouse";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $warehouses = [];
    while ($row = $result->fetch_assoc()) {
        $warehouses[] = $row;
    }
    echo json_encode(['success' => true, 'warehouses' => $warehouses]);
} else {
    echo json_encode(['success' => false, 'message' => 'No warehouses available.']);
}
?>
