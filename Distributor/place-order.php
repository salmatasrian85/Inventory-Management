<?php
session_start();
include 'db_connection.php';

$distributor_id = $_SESSION['user_id'];
$warehouse_id = $_POST['warehouse'];
$product_id = $_POST['product'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO `Order` (Order_Date, Distributor_ID, Warehouse_ID, Produce_ID, Quantity, Payment_Status)
        VALUES (NOW(), ?, ?, ?, ?, 0)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $distributor_id, $warehouse_id, $product_id, $quantity);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to place order.']);
}
?>
