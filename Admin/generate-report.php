<?php
include 'db_connection.php';

$reportType = $_GET['type'] ?? '';

$response = [
    'headers' => [],
    'rows' => []
];

switch ($reportType) {
    case 'orders':
        $sql = "SELECT Order_ID, Retailer_ID, Product_ID, Order_Quantity, Order_Date, Order_Status FROM orders";
        $response['headers'] = ['Order ID', 'Retailer ID', 'Product ID', 'Quantity', 'Date', 'Status'];
        break;
    case 'stock':
        $sql = "SELECT Warehouse_ID, Product_ID, Stock_Quantity, Last_Updated FROM stock";
        $response['headers'] = ['Warehouse ID', 'Product ID', 'Quantity', 'Last Updated'];
        break;
    case 'users':
        $sql = "SELECT ID, Role, Username FROM users";
        $response['headers'] = ['User ID', 'Role', 'Username'];
        break;
    default:
        echo json_encode(['headers' => [], 'rows' => []]);
        exit;
}

$result = $conn->query($sql);
while ($row = $result->fetch_row()) {
    $response['rows'][] = $row;
}

echo json_encode($response);
?>
