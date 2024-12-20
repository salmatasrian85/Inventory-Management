<?php
include 'db_connection.php';

try {
    $query = "
        SELECT w.Warehouse_Name AS warehouse, SUM(s.Stock_Quantity) AS stock
        FROM stock s
        JOIN warehouse w ON s.Warehouse_ID = w.Warehouse_ID
        GROUP BY w.Warehouse_ID
    ";
    $result = $conn->query($query);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'labels' => array_column($data, 'warehouse'),
        'values' => array_column($data, 'stock')
    ]);
} catch (Exception $e) {
    error_log('Error fetching warehouse stock data: ' . $e->getMessage());
    echo json_encode([]);
}
?>
