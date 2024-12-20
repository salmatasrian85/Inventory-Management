<?php
session_start();
include 'db_connection.php';

$distributor_id = $_SESSION['user_id'];

$sql = "SELECT `Order`.Order_ID AS id, Warehouse.Warehouse_Name AS warehouse, 
               Product.Produce_Name AS product, `Order`.Quantity AS quantity, 
               CASE WHEN `Order`.Payment_Status = 1 THEN 'Completed' ELSE 'Pending' END AS status
        FROM `Order`
        JOIN Warehouse ON `Order`.Warehouse_ID = Warehouse.Warehouse_ID
        JOIN Product ON `Order`.Produce_ID = Product.Product_ID
        WHERE `Order`.Distributor_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode(['success' => true, 'orders' => $orders]);
} else {
    echo json_encode(['success' => false, 'message' => 'No orders found.']);
}
?>
