<?php
include 'db_connection.php';

// Fetch unique products
$sql = "SELECT DISTINCT Produce_Name FROM Harvest JOIN Product ON Harvest.Product_ID = Product.Product_ID";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row['Produce_Name'];
}

echo json_encode(['products' => $products]);
?>
