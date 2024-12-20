<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a distributor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Distributor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get distributor ID from session
$distributor_id = $_SESSION['user_id'];

// Fetch orders for the logged-in distributor
$sql = "
    SELECT o.Order_ID AS order_id,
           o.Retailer_ID AS retailer_id,
           p.Produce_Name AS product,
           o.Order_Quantity AS quantity,
           o.Order_Date AS order_date,
           o.Order_Status AS status
    FROM Orders o
    JOIN Product p ON o.Product_ID = p.Product_ID
    WHERE o.Warehouse_ID IN (
        SELECT Warehouse_ID
        FROM warehouse
        WHERE Manager_ID = ?
    )
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
