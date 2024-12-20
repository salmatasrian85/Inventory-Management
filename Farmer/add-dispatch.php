<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Farmer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Retrieve the farmer's ID from the session
$farm_id = $_SESSION['user_id']; // Ensure this is set during login

// Retrieve POST data
$product_id = $_POST['product'] ?? null;
$quantity = $_POST['quantity'] ?? null;
$warehouse_id = $_POST['warehouse'] ?? null;

// Validate inputs
if (!$product_id || !$quantity || !$warehouse_id) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Generate a unique tracking number
$tracking_number = uniqid('TRK');

// Insert into the Dispatch table
$sql = "INSERT INTO Dispatch (TrackingNumber, Produce_ID, Dispatch_Quantity, Warehouse_ID, Dispatch_Time, Dispatch_Date, Dispatch_Status, Farm_ID) 
        VALUES (?, ?, ?, ?, CURTIME(), CURDATE(), 'Pending', ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siiii", $tracking_number, $product_id, $quantity, $warehouse_id, $farm_id);

try {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dispatch request created successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create dispatch request.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
