<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    echo json_encode(['labels' => [], 'values' => []]);
    exit;
}

$retailer_id = $_SESSION['user_id'];
$sql = "SELECT DATE_FORMAT(Order_Date, '%Y-%m') AS label, SUM(Order_Quantity) AS value
        FROM Orders
        WHERE Orders.Retailer_ID = ?
        GROUP BY DATE_FORMAT(Order_Date, '%Y-%m')
        ORDER BY Order_Date";
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
