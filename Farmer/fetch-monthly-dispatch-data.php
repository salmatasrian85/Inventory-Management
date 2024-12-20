<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Farmer') {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

$farm_id = $_SESSION['user_id']; // Logged-in farmer's ID
$sql = "SELECT DATE_FORMAT(Dispatch_Date, '%Y-%m') AS label, SUM(Dispatch_Quantity) AS value
        FROM Dispatch
        WHERE Farm_ID = ?
        GROUP BY DATE_FORMAT(Dispatch_Date, '%Y-%m')
        ORDER BY Dispatch_Date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farm_id);
$stmt->execute();
$result = $stmt->get_result();

$data = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['label'];
    $data['values'][] = $row['value'];
}

echo json_encode($data);
?>
