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
        SELECT MONTHNAME(o.Order_Date) AS month, COUNT(*) AS requests
        FROM orders o
        JOIN warehouse w ON o.Warehouse_ID = w.Warehouse_ID
        JOIN distributor d ON w.Warehouse_ID = d.Warehouse_ID
        WHERE d.Distributor_ID = ?
        GROUP BY MONTH(o.Order_Date)
        ORDER BY MONTH(o.Order_Date)
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $distributor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'labels' => array_column($data, 'month'),
            'values' => array_column($data, 'requests')
        ]);
    } else {
        echo json_encode([
            'labels' => [],
            'values' => []
        ]);
    }
} catch (Exception $e) {
    error_log('Error fetching monthly requests data: ' . $e->getMessage());
    echo json_encode([
        'labels' => [],
        'values' => []
    ]);
}
?>
