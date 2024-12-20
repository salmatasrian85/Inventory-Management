<?php
include 'db_connection.php';

$sql = "SELECT Product_ID AS id, Produce_Name AS name FROM Product";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(['success' => true, 'products' => $products]);
} else {
    echo json_encode(['success' => false, 'message' => 'No products available.']);
}
?>
