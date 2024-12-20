<?php
session_start();
include 'db_connection.php';

$retailer_id = $_SESSION['user_id'];
$distributor_id = $_POST['distributor'];
$product_id = $_POST['product'];
$quantity = $_POST['quantity'];
$location = $_POST['delivery-location'];

$sql = "INSERT INTO Retailer_Order (Distributor_ID, Retailer_ID, Produce_ID, DeliveryTime, Location)
        VALUES (?, ?, ?, NULL, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $distributor_id, $retailer_id, $product_id, $location);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to place order.']);
}
?>
