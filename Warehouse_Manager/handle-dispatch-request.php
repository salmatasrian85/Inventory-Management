<?php
include 'db_connection.php';
session_start();

// Check for logged-in Warehouse Manager
if (!isset($_SESSION['warehouse_id']) || $_SESSION['role'] !== 'Warehouse_Manager') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$dispatch_id = $data['dispatch_id'];
$action = $data['action'];

if (!in_array($action, ['accept', 'reject'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

try {
    $conn->begin_transaction();

    if ($action === 'accept') {
        // Update the dispatch status to "Accepted"
        $updateDispatch = "UPDATE Dispatch SET Dispatch_Status = 'Accepted' WHERE Dispatch_ID = ?";
        $stmt = $conn->prepare($updateDispatch);
        $stmt->bind_param('i', $dispatch_id);
        $stmt->execute();

        // Insert the accepted dispatch into the Dispatch_Transit table
        $insertTransit = "
            INSERT INTO Dispatch_Transit (Dispatch_ID, Produce_ID, Dispatch_Quantity, Transit_Status, Warehouse_ID)
            SELECT Dispatch_ID, Produce_ID, Dispatch_Quantity, 'In Transit', Warehouse_ID
            FROM Dispatch
            WHERE Dispatch_ID = ?
        ";
        $stmt = $conn->prepare($insertTransit);
        $stmt->bind_param('i', $dispatch_id);
        $stmt->execute();
    } elseif ($action === 'reject') {
        // Update the dispatch status to "Rejected"
        $updateDispatch = "UPDATE Dispatch SET Dispatch_Status = 'Rejected' WHERE Dispatch_ID = ?";
        $stmt = $conn->prepare($updateDispatch);
        $stmt->bind_param('i', $dispatch_id);
        $stmt->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => "Dispatch $action successfully."]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
