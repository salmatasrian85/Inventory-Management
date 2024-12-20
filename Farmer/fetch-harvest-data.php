<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Farmer') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$farmer_id = $_SESSION['user_id'];

// Query to get harvest data grouped by product
$query = "
    SELECT 
        Product.Produce_Name AS product_name,
        SUM(Harvest.Harvested_Quantity) AS total_quantity
    FROM Harvest
    JOIN Product ON Harvest.Product_ID = Product.Product_ID
    WHERE Harvest.Farm_ID = ?
    GROUP BY Product.Product_ID
    ORDER BY Product.Produce_Name
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [
    'labels' => [],
    'values' => [],
    'colors' => []
];

$colors = [
    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
];
$colorIndex = 0;

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['product_name'];
    $data['values'][] = $row['total_quantity'];
    $data['colors'][] = $colors[$colorIndex % count($colors)];
    $colorIndex++;
}

echo json_encode($data);
?>
