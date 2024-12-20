<?php
include 'db_connection.php';

$data = [
    'total_users' => 0,
    'total_warehouses' => 0,
    'total_vehicles' => 0,
    'total_products' => 0,
];

try {
    // Fetch total users
    $result = $conn->query("SELECT COUNT(*) AS count FROM users");
    $data['total_users'] = $result->fetch_assoc()['count'];

    // Fetch total warehouses
    $result = $conn->query("SELECT COUNT(*) AS count FROM warehouse");
    $data['total_warehouses'] = $result->fetch_assoc()['count'];

    // Fetch total vehicles
    $result = $conn->query("SELECT COUNT(*) AS count FROM vehicle");
    $data['total_vehicles'] = $result->fetch_assoc()['count'];

    // Fetch total products
    $result = $conn->query("SELECT COUNT(*) AS count FROM product");
    $data['total_products'] = $result->fetch_assoc()['count'];
} catch (Exception $e) {
    error_log('Error fetching admin overview data: ' . $e->getMessage());
}

echo json_encode($data);
?>
