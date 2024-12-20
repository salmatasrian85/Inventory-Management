<?php
include 'db_connection.php';

$distributor_id = 1; // Replace with session-based distributor ID
$sql = "SELECT DATE_FORMAT(Shipment_Date, '%Y-%m') AS label, COUNT(Shipment_ID) AS value
        FROM Shipments
        WHERE Distributor_ID = ?
        GROUP BY DATE_FORMAT(Shipment_Date, '%Y-%m')
        ORDER BY Shipment_Date";
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
