<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Farmer') {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

$farm_id = $_SESSION['user_id']; // Logged-in farmer's ID
$sql = "SELECT Dispatch.TrackingNumber, Product.Produce_Name, Dispatch.Dispatch_Quantity, 
        Warehouse.Warehouse_Name, Dispatch.Dispatch_Date, Dispatch.Dispatch_Status
        FROM Dispatch
        JOIN Product ON Dispatch.Produce_ID = Product.Product_ID
        JOIN Warehouse ON Dispatch.Warehouse_ID = Warehouse.Warehouse_ID
        WHERE Dispatch.Farm_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farm_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
