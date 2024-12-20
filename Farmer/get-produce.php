<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT Produce_ID AS id, Produce_Name AS name FROM Product";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $produce = [];
    while ($row = $result->fetch_assoc()) {
        $produce[] = $row;
    }
    echo json_encode(['success' => true, 'produce' => $produce]);
} else {
    echo json_encode(['success' => false, 'message' => 'No produce available.']);
}
?>
