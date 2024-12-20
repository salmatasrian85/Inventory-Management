<?php
include 'db_connection.php';

$manager_id = 1; // Replace with session-based manager ID
$sql = "SELECT Produce_Name AS label, SUM(Stock_Quantity) AS value
        FROM Stock
        JOIN Product ON Stock.Product_ID = Product.Product_ID
        JOIN Warehouse ON Stock.Warehouse_ID = Warehouse.Warehouse_ID
        WHERE Warehouse.Manager_ID = ?
        GROUP BY Produce_Name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();

$data = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['label'];
    $data['values'][] = $row['value'];
}

echo json_encode($data);
?>
