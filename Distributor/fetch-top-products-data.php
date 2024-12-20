<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a distributor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Distributor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get distributor ID from session
$distributor_id = $_SESSION['user_id'];

try {
    $query = "
        SELECT p.Produce_Name AS product, SUM(o.Order_Quantity) AS units_sold
        FROM orders o
        JOIN product p ON o.Product_ID = p.Product_ID
        JOIN warehouse w ON o.Warehouse_ID = w.Warehouse_ID
        JOIN distributor d ON w.Warehouse_ID = d.Warehouse_ID
        WHERE d.Distributor_ID = ?
        GROUP BY p.Product_ID
        ORDER BY units_sold DESC
        LIMIT 5
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $distributor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'labels' => array_column($data, 'product'),
        'values' => array_column($data, 'units_sold')
    ]);
} catch (Exception $e) {
    error_log('Error fetching top products data: ' . $e->getMessage());
    echo json_encode([]);
}
?>
