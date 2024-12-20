<?php
include 'db_connection.php';

$sql = "SELECT ID, Role, Username FROM users";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>
