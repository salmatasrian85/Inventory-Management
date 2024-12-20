<?php
include 'db_connection.php';

$sql = "SELECT Product_ID, Produce_Name FROM Product";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
