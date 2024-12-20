<?php
include 'db_connection.php';

// Replace with session-based distributor ID
$distributor_id = 1;

$sql = "SELECT Warehouse.Warehouse_Name AS label, COUNT(Order_ID) AS value
        FROM Orders
        JOIN Warehouse ON Orders.Warehouse_ID = Warehouse.Warehouse_ID
        WHERE Assigned_Distributor = ?
        GROUP BY Warehouse.Warehouse_Name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

$data = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['label'];
    $data['values'][] = $row['value'];
}

echo json_encode($data);
?>
