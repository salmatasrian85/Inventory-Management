<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    echo json_encode(['labels' => [], 'values' => []]);
    exit;
}

$retailer_id = $_SESSION['user_id'];
$sql = "SELECT Warehouse.Warehouse_Name AS label, SUM(Orders.Order_Quantity) AS value
        FROM Orders
        JOIN Warehouse ON Orders.Warehouse_ID = Warehouse.Warehouse_ID
        WHERE Orders.Retailer_ID = ?
        GROUP BY Warehouse.Warehouse_Name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $retailer_id);
$stmt->execute();
$result = $stmt->get_result();

$data = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['label'];
    $data['values'][] = $row['value'];
}

echo json_encode($data);
?>
