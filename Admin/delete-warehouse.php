<?php
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$warehouse_id = $data['warehouse_id'] ?? null;

if (!$warehouse_id) {
    echo json_encode(['success' => false, 'message' => 'Warehouse ID is required.']);
    exit;
}

// Delete the warehouse
$sql = "DELETE FROM warehouse WHERE Warehouse_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $warehouse_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
?>
