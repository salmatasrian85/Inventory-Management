<?php
include 'db_connection.php';
session_start();

// Check for logged-in Warehouse Manager
if (!isset($_SESSION['warehouse_id']) || $_SESSION['role'] !== 'Warehouse_Manager') {
    echo json_encode([]);
    exit;
}

// Get the warehouse ID from the session
$warehouse_id = $_SESSION['warehouse_id'];

// Fetch pending dispatch requests
$sql = "
    SELECT d.Dispatch_ID, d.Farm_ID, p.Produce_Name, d.Dispatch_Quantity, d.Dispatch_Date
    FROM Dispatch d
    JOIN Product p ON d.Produce_ID = p.Product_ID
    WHERE d.Dispatch_Status = 'Pending' AND d.Warehouse_ID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $warehouse_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
