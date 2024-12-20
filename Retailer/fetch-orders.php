<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a retailer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    echo json_encode([]);
    exit;
}

// Get the retailer's ID from the session
$retailer_id = $_SESSION['user_id'];

// Fetch orders for the logged-in retailer
$sql = "SELECT Orders.Order_ID AS order_id, Product.Produce_Name AS product, Orders.Order_Quantity AS quantity,
        Warehouse.Warehouse_Name AS warehouse, Orders.Order_Date AS order_date, Orders.Order_Status AS status
        FROM Orders
        JOIN Product ON Orders.Product_ID = Product.Product_ID
        JOIN Warehouse ON Orders.Warehouse_ID = Warehouse.Warehouse_ID
        WHERE Orders.Retailer_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $retailer_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
