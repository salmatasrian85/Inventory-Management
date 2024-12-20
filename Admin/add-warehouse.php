<?php
include 'db_connection.php';

$warehouse_name = $_POST['warehouse_name'] ?? null;
$manager_id = $_POST['manager_id'] ?? null;

if (!$warehouse_name || !$manager_id) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Insert into the warehouse table
$sql = "INSERT INTO warehouse (Warehouse_Name, Manager_ID) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $warehouse_name, $manager_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
?>
