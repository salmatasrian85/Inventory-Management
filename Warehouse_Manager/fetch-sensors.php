<?php
include 'db_connection.php';

// Fetch all sensors
$sql = "SELECT * FROM sensor ORDER BY Last_Updated DESC";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
