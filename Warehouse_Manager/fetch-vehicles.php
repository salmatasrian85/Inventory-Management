<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is a warehouse manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Warehouse_Manager') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get the warehouse ID from session
$warehouse_id = $_SESSION['warehouse_id'];

// Fetch vehicles for the warehouse
$sql = "
    SELECT Registration_No, Vehicle_Type, Capacity, Min_Temp, Max_Temp
    FROM vehicle
    WHERE Warehouse_ID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $warehouse_id);
$stmt->execute();
$result = $stmt->get_result();

$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

echo json_encode(['success' => true, 'vehicles' => $vehicles]);
?>
