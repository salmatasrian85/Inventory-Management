<?php
include 'db_connection.php';

$distributor_id = 1; // Replace with session-based distributor ID
$sql = "SELECT Product.Produce_Name AS label, COUNT(Order_ID) AS value
        FROM Orders
        JOIN Product ON Orders.Product_ID = Product.Product_ID
        WHERE Distributor_ID = ? AND Order_Status = 'Pending'
        GROUP BY Product.Produce_Name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

$data = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['label'];
    $data['values'][] = $row['value'];
}

echo json_encode($data);
?>
