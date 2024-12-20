<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a distributor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Distributor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get distributor ID from session
$distributor_id = $_SESSION['user_id'];

// Fetch shipments for the distributor's warehouses
$sql = "
    SELECT t.TrackingNumber AS tracking_number,
           o.Order_ID AS order_id,
           p.Produce_Name AS product,
           o.Order_Quantity AS quantity,
           t.ETA AS eta,
           t.Status AS status
    FROM Tracking t
    JOIN Orders o ON t.Order_ID = o.Order_ID
    JOIN Product p ON o.Product_ID = p.Product_ID
    WHERE o.Warehouse_ID IN (
        SELECT Warehouse_ID
        FROM warehouse
        WHERE Manager_ID = ?
    )
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

$shipments = [];
while ($row = $result->fetch_assoc()) {
    $shipments[] = $row;
}

echo json_encode($shipments);
?>
