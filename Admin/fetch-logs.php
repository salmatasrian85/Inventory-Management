<?php
include 'db_connection.php';

$sql = "SELECT l.timestamp, l.event_type, l.description, l.user_id, u.Username, l.role
        FROM logs l
        LEFT JOIN users u ON l.user_id = u.ID
        ORDER BY l.timestamp DESC LIMIT 100";
$result = $conn->query($sql);

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode($logs);

?>
