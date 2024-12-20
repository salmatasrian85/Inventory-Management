<?php
session_start();
include 'db_connection.php'; // Include your database connection file

$user_id = $_SESSION['user_id']; // Assume user_id is stored in session

$sql = "SELECT Name AS name, Location AS location, Farm_Size AS size, Farm_Type AS type
        FROM Farm
        WHERE Farmer_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $farm = $result->fetch_assoc();
    echo json_encode(['success' => true, 'farm' => $farm]);
} else {
    echo json_encode(['success' => false, 'message' => 'No farm details found.']);
}
?>
