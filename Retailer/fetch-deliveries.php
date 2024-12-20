<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get the retailer's ID from the session
$retailer_id = $_SESSION['user_id'];

// Fetch deliveries for the retailer
$sql = "
    SELECT 
        Tracking.TrackingNumber AS tracking_number,
        Orders.Order_ID AS order_id,
        Product.Produce_Name AS product,
        Orders.Order_Quantity AS quantity,
        Tracking.ETA AS eta,
        Tracking.Status AS status
    FROM Orders
    JOIN Tracking ON Orders.Order_ID = Tracking.Order_ID
    JOIN Product ON Orders.Product_ID = Product.Product_ID
    WHERE Orders.Retailer_ID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $retailer_id);
$stmt->execute();
$result = $stmt->get_result();

$deliveries = [];
while ($row = $result->fetch_assoc()) {
    $deliveries[] = $row;
}

echo json_encode($deliveries);
?>
