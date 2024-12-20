<?php
include 'db_connection.php';

try {
    // Fetching stock by warehouse and product
    $query = "
        SELECT w.Warehouse_Name, p.Produce_Name, SUM(s.Stock_Quantity) AS stock_quantity
        FROM stock s
        JOIN warehouse w ON s.Warehouse_ID = w.Warehouse_ID
        JOIN product p ON s.Product_ID = p.Product_ID
        GROUP BY w.Warehouse_Name, p.Produce_Name
        ORDER BY w.Warehouse_Name, p.Produce_Name
    ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = [];
        $warehouses = [];
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $warehouses[$row['Warehouse_Name']] = true;
            $products[$row['Produce_Name']][] = [
                'warehouse' => $row['Warehouse_Name'],
                'stock_quantity' => $row['stock_quantity']
            ];
        }

        $response = [
            'warehouses' => array_keys($warehouses),
            'products' => array_map(function ($product, $name) {
                return [
                    'product_name' => $name,
                    'stock_values' => array_column($product, 'stock_quantity'),
                    'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)) // Random color for each product
                ];
            }, $products, array_keys($products))
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['warehouses' => [], 'products' => []]);
    }
} catch (Exception $e) {
    error_log('Error fetching stock by warehouse data: ' . $e->getMessage());
    echo json_encode(['warehouses' => [], 'products' => []]);
}
?>
