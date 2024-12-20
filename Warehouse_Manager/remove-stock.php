<?php
include 'db_connection.php';

$stock_id = $_GET['id'];

$sql = "DELETE FROM Dispatch WHERE Dispatch_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $stock_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove stock.']);
}
?>
