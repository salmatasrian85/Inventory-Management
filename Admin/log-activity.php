<?php
include 'db_connection.php';

function logActivity($userID, $role, $eventType, $description) {
    global $conn;

    $sql = "INSERT INTO logs (user_id, role, event_type, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $userID, $role, $eventType, $description);
    $stmt->execute();
}
?>

