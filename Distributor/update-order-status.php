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
$order_id = $data['order_id'];
$status = $data['status'];

// Validate status
if (!in_array($status, ['Pending', 'Accepted', 'Declined'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

// Update the order status
$updateOrderSql = "
    UPDATE Orders
    SET Order_Status = ?
    WHERE Order_ID = ?
      AND Warehouse_ID IN (
          SELECT Warehouse_ID
          FROM warehouse
          WHERE Manager_ID = ?
      )
";
$stmt = $conn->prepare($updateOrderSql);
$stmt->bind_param("sii", $status, $order_id, $distributor_id);
if ($stmt->execute() && $stmt->affected_rows > 0) {
    // If status is "Accepted", add an entry to the Tracking table
    if ($status === 'Accepted') {
        $trackingNumber = uniqid(); // Generate unique tracking number
        $eta = date('Y-m-d H:i:s', strtotime('+3 days')); // ETA: 3 days from now
        $trackingStatus = 'Pending';

        $insertTrackingSql = "
            INSERT INTO Tracking (TrackingNumber, Order_ID, ETA, Status)
            VALUES (?, ?, ?, ?)
        ";
        $trackingStmt = $conn->prepare($insertTrackingSql);
        $trackingStmt->bind_param("siss", $trackingNumber, $order_id, $eta, $trackingStatus);

        if ($trackingStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Order accepted and tracking created.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Order accepted, but tracking creation failed: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found or unauthorized access.']);
}
?>
