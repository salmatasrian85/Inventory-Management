<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a distributor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Distributor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Initialize response arrays
$response = [
    'warehouses' => [],
    'products' => []
];

// Fetch warehouse data
$warehouseQuery = "SELECT Warehouse_ID, Warehouse_Name FROM warehouse";
$warehouseResult = $conn->query($warehouseQuery);

if ($warehouseResult && $warehouseResult->num_rows > 0) {
    while ($row = $warehouseResult->fetch_assoc()) {
        $response['warehouses'][] = [
            'Warehouse_ID' => $row['Warehouse_ID'],
            'Warehouse_Name' => $row['Warehouse_Name']
        ];
    }
}

// Fetch product data
$productQuery = "SELECT Product_ID, Produce_Name FROM product";
$productResult = $conn->query($productQuery);

if ($productResult && $productResult->num_rows > 0) {
    while ($row = $productResult->fetch_assoc()) {
        $response['products'][] = [
            'Product_ID' => $row['Product_ID'],
            'Produce_Name' => $row['Produce_Name']
        ];
    }
}

// Return JSON response
echo json_encode($response);
?>
