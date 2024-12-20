<?php
include 'db_connection.php';
$harvest_id = $_GET['id'];
$sql = "DELETE FROM harvest WHERE Lot_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $harvest_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
?>
