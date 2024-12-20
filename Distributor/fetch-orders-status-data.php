<?php
include 'db_connection.php';

$distributor_id = 1; // Replace with session-based distributor ID
$sql = "SELECT Order_Status AS label, COUNT(*) AS value
        FROM Orders
        WHERE Distributor_ID = ?
        GROUP BY Order_Status";
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
