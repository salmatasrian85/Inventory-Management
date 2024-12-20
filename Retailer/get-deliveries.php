<?php
session_start();
include 'db_connection.php';

$retailer_id = $_SESSION['user_id'];

$sql = "SELECT Retailer_Order.Retailer_Order_ID AS order_id, 
               Product.Produce_Name AS product, Retailer_Order.Quantity AS quantity, 
               Retailer_Order.Location AS location,
               CASE 
                   WHEN Retailer_Order.DeliveryTime IS NULL THEN 'Pending'
                   ELSE 'Delivered'
               END AS status
        FROM Retailer_Order
        JOIN Product ON Retailer_Order.Produce_ID = Product.Product_ID
        WHERE Retailer_Order.Retailer_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $retailer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $deliveries = [];
    while ($row = $result->fetch_assoc()) {
        $deliveries[] = $row;
    }
    echo json_encode(['success' => true, 'deliveries' => $deliveries]);
} else {
    echo json_encode(['success' => false, 'message' => 'No deliveries found.']);
}
?>
