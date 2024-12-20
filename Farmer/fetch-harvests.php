<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Farmer') {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

// Get the farmer's ID from the session
$farmer_id = $_SESSION['user_id'];

// Fetch harvests for the logged-in farmer
$sql = "
    SELECT 
        h.Lot_ID AS id, 
        p.Produce_Name AS produce, 
        h.Harvested_Quantity AS quantity, 
        DATE(h.HarvestDate) AS date
    FROM harvest h
    JOIN product p ON h.Product_ID = p.Product_ID
    JOIN farm f ON h.Farm_ID = f.Farm_ID
    WHERE f.Farmer_ID = ?
    ORDER BY h.HarvestDate DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
