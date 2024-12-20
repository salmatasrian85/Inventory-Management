<?php
session_start();
include 'db_connection.php';

$manager_id = $_SESSION['user_id'];
$product_id = $_POST['product'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO Dispatch (Produce_ID, Dispatch_Quantity, Warehouse_ID, Dispatch_Date)
        VALUES (?, ?, (SELECT Warehouse_ID FROM Warehouse WHERE Manager_ID = ?), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $product_id, $quantity, $manager_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add stock.']);
}
?>
