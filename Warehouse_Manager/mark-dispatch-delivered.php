<?php
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['dispatch_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

$dispatchId = $data['dispatch_id'];

try {
    $conn->begin_transaction();

    // Fetch dispatch details
    $fetchDispatch = "
        SELECT Produce_ID, Dispatch_Quantity, Warehouse_ID
        FROM Dispatch_Transit
        WHERE Dispatch_ID = ?
    ";
    $stmt = $conn->prepare($fetchDispatch);
    $stmt->bind_param('i', $dispatchId);
    $stmt->execute();
    $result = $stmt->get_result();
    $dispatch = $result->fetch_assoc();

    if (!$dispatch) {
        echo json_encode(['success' => false, 'message' => 'Dispatch not found.']);
        exit;
    }

    $produceId = $dispatch['Produce_ID'];
    $quantity = $dispatch['Dispatch_Quantity'];
    $warehouseId = $dispatch['Warehouse_ID'];

    // Update stock
    $updateStock = "
        INSERT INTO stock (Warehouse_ID, Product_ID, Stock_Quantity, Last_Updated)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE Stock_Quantity = Stock_Quantity + VALUES(Stock_Quantity), Last_Updated = NOW()
    ";
    $stmt = $conn->prepare($updateStock);
    $stmt->bind_param('iii', $warehouseId, $produceId, $quantity);
    $stmt->execute();

    // Remove from transit
    $deleteTransit = "DELETE FROM Dispatch_Transit WHERE Dispatch_ID = ?";
    $stmt = $conn->prepare($deleteTransit);
    $stmt->bind_param('i', $dispatchId);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Dispatch marked as delivered and stock updated.']);
} catch (Exception $e) {
    $conn->rollback();
    error_log('Error marking dispatch as delivered: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
