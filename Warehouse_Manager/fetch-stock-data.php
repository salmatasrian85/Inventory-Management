<?php
include 'db_connection.php';

session_start();

$warehouse_id = $_SESSION['warehouse_id'];

try {
    $query = "
        SELECT 
            s.Product_ID,
            p.Produce_Name,
            w.Warehouse_Name,
            SUM(s.Stock_Quantity) AS Stock_Quantity,
            MAX(s.Last_Updated) AS Last_Updated
        FROM stock s
        JOIN product p ON s.Product_ID = p.Product_ID
        JOIN warehouse w ON s.Warehouse_ID = w.Warehouse_ID
        WHERE s.Warehouse_ID = ?
        GROUP BY s.Product_ID
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $warehouse_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);
} catch (Exception $e) {
    error_log('Error fetching stock data: ' . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch stock data.']);
}
?>
