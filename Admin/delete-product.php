<?php
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Product ID is required.']);
    exit;
}

// Delete the product
$sql = "DELETE FROM product WHERE Product_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
?>
