<?php
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['dispatch_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

$dispatchId = $data['dispatch_id'];

try {
    $deleteDispatch = "DELETE FROM Dispatch_Transit WHERE Dispatch_ID = ?";
    $stmt = $conn->prepare($deleteDispatch);
    $stmt->bind_param('i', $dispatchId);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Dispatch deleted successfully.']);
} catch (Exception $e) {
    error_log('Error deleting dispatch: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
