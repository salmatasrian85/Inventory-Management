<?php
include 'db_connection.php';

$manager_id = 1; // Replace with session-based manager ID
$sql = "SELECT Dispatch_Status AS label, COUNT(*) AS value
        FROM Dispatch
        JOIN Warehouse ON Dispatch.Warehouse_ID = Warehouse.Warehouse_ID
        WHERE Warehouse.Manager_ID = ?
        GROUP BY Dispatch_Status";
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
