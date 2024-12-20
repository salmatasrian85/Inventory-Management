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

// Fetch distributor requests
$sql = "
    SELECT 
        dr.Request_ID,
        w.Warehouse_Name,
        p.Produce_Name,
        dr.Quantity,
        dr.Request_Status
    FROM DistributorRequest dr
    JOIN warehouse w ON dr.Warehouse_ID = w.Warehouse_ID
    JOIN product p ON dr.Product_ID = p.Product_ID
    WHERE dr.Distributor_ID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
