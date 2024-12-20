<?php
include 'db_connection.php';
session_start();

// Get the Warehouse ID from the logged-in user's session
if (!isset($_SESSION['warehouse_id'])) {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

$warehouse_id = $_SESSION['warehouse_id'];

// Fetch distributor requests for this warehouse
$sql = "
    SELECT 
        dr.Request_ID,
        d.Name AS Distributor_Name,
        p.Produce_Name,
        dr.Quantity,
        dr.Request_Status
    FROM DistributorRequest dr
    JOIN distributor d ON dr.Distributor_ID = d.Distributor_ID
    JOIN product p ON dr.Product_ID = p.Product_ID
    WHERE dr.Warehouse_ID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $warehouse_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
