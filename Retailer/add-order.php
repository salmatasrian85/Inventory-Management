<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a retailer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get the retailer's ID from the session
$retailer_id = $_SESSION['user_id'];

// Get form data
$product = $_POST['product'] ?? null;
$quantity = $_POST['quantity'] ?? null;
$warehouse = $_POST['warehouse'] ?? null;

// Validate the input
if (!$product || !$quantity || !$warehouse) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Prepare data
$order_date = date('Y-m-d'); // Today's date
$order_status = 'Pending'; // Default order status

try {
    // Insert the order into the database
    $sql = "INSERT INTO Orders (Retailer_ID, Product_ID, Order_Quantity, Warehouse_ID, Order_Date, Order_Status)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisss", $retailer_id, $product, $quantity, $warehouse, $order_date, $order_status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order placed successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error placing order: ' . $conn->error]);
    }
} catch (Exception $e) {
    error_log('Error adding order: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
}
?>
