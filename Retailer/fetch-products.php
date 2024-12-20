<?php
include 'db_connection.php';

$sql = "SELECT Product_ID AS id, Produce_Name AS name FROM product";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
