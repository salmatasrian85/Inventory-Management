<?php
include 'db_connection.php';

try {
    // Fetch products count
    $productCount = $conn->query("SELECT COUNT(*) AS count FROM Product")->fetch_assoc()['count'];

    // Fetch users count
    $userCount = $conn->query("SELECT COUNT(*) AS count FROM Users")->fetch_assoc()['count'];

    // Fetch orders count
    $orderCount = $conn->query("SELECT COUNT(*) AS count FROM Orders")->fetch_assoc()['count'];

    // Fetch monthly sales
    $salesQuery = "
        SELECT DATE_FORMAT(Order_Date, '%Y-%m') AS month, SUM(Order_Quantity) AS total_sales
        FROM Orders
        GROUP BY DATE_FORMAT(Order_Date, '%Y-%m')
        ORDER BY Order_Date ASC";
    $salesResult = $conn->query($salesQuery);

    $monthlySales = [];
    while ($row = $salesResult->fetch_assoc()) {
        $monthlySales[] = $row;
    }

    // Prepare response
    $response = [
        'products' => $productCount,
        'users' => $userCount,
        'orders' => $orderCount,
        'sales' => $monthlySales,
    ];

    echo json_encode($response);
} catch (Exception $e) {
    error_log("Error fetching dashboard data: " . $e->getMessage());
    echo json_encode([]);
}
?>
