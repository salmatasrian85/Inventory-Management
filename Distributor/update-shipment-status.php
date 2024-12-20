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

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$tracking_number = $data['tracking_number'];
$status = $data['status'];

// Validate status
if (!in_array($status, ['Pending', 'In Transit', 'Delivered'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

// Update the shipment status only if it belongs to the distributor
$sql = "
    UPDATE Tracking
    SET Status = ?
    WHERE TrackingNumber = ?
      AND Order_ID IN (
          SELECT o.Order_ID
          FROM Orders o
          JOIN warehouse w ON o.Warehouse_ID = w.Warehouse_ID
          WHERE w.Manager_ID = ?
      )
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $status, $tracking_number, $distributor_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Shipment status updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Shipment not found or unauthorized access.']);
}
?>
