<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Distributor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Validate input data
if (!isset($_POST['order_id'], $_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

$order_id = $_POST['order_id'];
$action = $_POST['action'];

try {
    if ($action === 'accept') {
        $conn->begin_transaction();

        // Update order status to 'Accepted'
        $updateOrder = "UPDATE Orders SET Order_Status = 'Accepted' WHERE Order_ID = ?";
        $stmt = $conn->prepare($updateOrder);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();

        // Insert into Tracking table
        $tracking_number = rand(100000, 999999); // Generate a 6-digit tracking number
        $eta = date('Y-m-d H:i:s', strtotime('+3 days')); // Example ETA (3 days from now)
        $insertTracking = "INSERT INTO Tracking (TrackingNumber, Order_ID, ETA, Status) VALUES (?, ?, ?, 'In Transit')";
        $stmt = $conn->prepare($insertTracking);
        $stmt->bind_param('iis', $tracking_number, $order_id, $eta);
        $stmt->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Order accepted and added to tracking.']);
    } elseif ($action === 'reject') {
        // Update order status to 'Rejected'
        $updateOrder = "UPDATE Orders SET Order_Status = 'Rejected' WHERE Order_ID = ?";
        $stmt = $conn->prepare($updateOrder);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Order rejected successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} catch (Exception $e) {
    $conn->rollback();
    error_log('Error handling distributor request: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>
