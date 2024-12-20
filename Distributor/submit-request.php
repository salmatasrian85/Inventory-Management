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

// Get form data
$warehouse_id = $_POST['warehouse_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if ($warehouse_id && $product_id && $quantity) {
    $query = "
        INSERT INTO DistributorRequest (Distributor_ID, Warehouse_ID, Product_ID, Quantity, Request_Status, Request_Date)
        VALUES (?, ?, ?, ?, 'Pending', NOW())
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $distributor_id, $warehouse_id, $product_id, $quantity);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Request submitted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
}
?>
