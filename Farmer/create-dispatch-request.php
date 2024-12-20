<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product'];
$quantity = $_POST['quantity'];
$warehouse_id = $_POST['warehouse'];

$sql = "INSERT INTO Dispatch (Produce_ID, Dispatch_Quantity, Warehouse_ID, Farm_ID, Dispatch_Date)
        VALUES (?, ?, ?, (SELECT Farm_ID FROM Farm WHERE Farmer_ID = ?), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $product_id, $quantity, $warehouse_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create dispatch request.']);
}
?>
