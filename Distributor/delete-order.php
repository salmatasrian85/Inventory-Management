<?php
include 'db_connection.php';

$order_id = $_GET['id'];

$sql = "DELETE FROM `Order` WHERE Order_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete order.']);
}
?>
