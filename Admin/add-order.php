if ($stmt->execute()) {
    logActivity($_SESSION['user_id'], $_SESSION['role'], 'Add Order', "Placed an order with Order ID: {$conn->insert_id}");
    echo json_encode(['success' => true]);
} else {
    logActivity($_SESSION['user_id'], $_SESSION['role'], 'Add Order Failed', 'Failed to place an order');
    echo json_encode(['success' => false, 'message' => 'Error placing order: ' . $conn->error]);
}
