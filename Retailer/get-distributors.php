<?php
include 'db_connection.php';

$sql = "SELECT Distributor_ID AS id, Name AS name, Location AS location FROM Distributor";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $distributors = [];
    while ($row = $result->fetch_assoc()) {
        $distributors[] = $row;
    }
    echo json_encode(['success' => true, 'distributors' => $distributors]);
} else {
    echo json_encode(['success' => false, 'message' => 'No distributors available.']);
}
?>
