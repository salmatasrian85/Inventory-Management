<?php
session_start();
include 'db_connection.php';

$manager_id = $_SESSION['user_id'];

$sql = "SELECT Product.Product_ID AS id, Product.Produce_Name AS product_name, 
               SUM(Dispatch.Dispatch_Quantity) AS quantity
        FROM Dispatch
        JOIN Product ON Dispatch.Produce_ID = Product.Product_ID
        WHERE Dispatch.Warehouse_ID = (SELECT Warehouse_ID FROM Warehouse WHERE Manager_ID = ?)
        GROUP BY Product.Product_ID";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stock = [];
    while ($row = $result->fetch_assoc()) {
        $stock[] = $row;
    }
    echo json_encode(['success' => true, 'stock' => $stock]);
} else {
    echo json_encode(['success' => false, 'message' => 'No stock available.']);
}
?>
