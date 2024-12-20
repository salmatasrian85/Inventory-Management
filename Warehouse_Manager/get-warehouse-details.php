<?php
session_start();
include 'db_connection.php'; // Include your database connection file

$manager_id = $_SESSION['user_id']; // Assume manager ID is stored in session

$sql = "SELECT Warehouse_Name AS name, CONCAT(City, ', ', Road) AS location, Capacity AS capacity, 
               (SELECT SUM(Dispatch_Quantity) FROM Dispatch WHERE Warehouse_ID = Warehouse.Warehouse_ID) AS current_stock
        FROM Warehouse
        WHERE Manager_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $warehouse = $result->fetch_assoc();
    echo json_encode(['success' => true, 'warehouse' => $warehouse]);
} else {
    echo json_encode(['success' => false, 'message' => 'No warehouse details found.']);
}
?>
