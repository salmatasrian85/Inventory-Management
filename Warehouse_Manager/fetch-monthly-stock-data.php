<?php
include 'db_connection.php';

$manager_id = 1; // Replace with session-based manager ID
$sql = "SELECT DATE_FORMAT(Last_Updated, '%Y-%m') AS label, SUM(Stock_Quantity) AS value
        FROM Stock
        JOIN Warehouse ON Stock.Warehouse_ID = Warehouse.Warehouse_ID
        WHERE Warehouse.Manager_ID = ?
        GROUP BY DATE_FORMAT(Last_Updated, '%Y-%m')
        ORDER BY Last_Updated";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();

$data = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['label'];
    $data['values'][] = $row['value'];
}

echo json_encode($data);
?>
