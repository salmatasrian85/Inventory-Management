<?php
include 'db_connection.php';

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

$request_id = $data['requestId'];
$status = $data['status'];

// Validate input
if (!in_array($status, ['Pending', 'Accepted', 'Declined'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status.']);
    exit;
}

// Update the request status
$sql = "UPDATE DistributorRequest SET Request_Status = ? WHERE Request_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $request_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
?>
