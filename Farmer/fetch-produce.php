<?php
include 'db_connection.php';
$sql = "SELECT Product_ID AS id, Produce_Name AS name FROM Product";
$result = $conn->query($sql);
$produce = [];
while ($row = $result->fetch_assoc()) {
    $produce[] = $row;
}
echo json_encode($produce);
?>
