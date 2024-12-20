<?php
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$registration_no = $data['registration_no'] ?? null;

if (!$registration_no) {
    echo json_encode(['success' => false, 'message' => 'Registration number is required.']);
    exit;
}

$sql = "DELETE FROM vehicle WHERE Registration_No = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $registration_no);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
?>
