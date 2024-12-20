<?php
session_start();
include 'db_connection.php';

$retailer_id = $_SESSION['user_id']; // Assume retailer ID is stored in session

$sql = "SELECT Name AS name, Contact_Info AS contact,
               (SELECT COUNT(*) FROM Retailer_Order WHERE Retailer_ID = ?) AS total_orders,
               (SELECT COUNT(*) FROM Retailer_Order WHERE Retailer_ID = ? AND DeliveryTime IS NULL) AS pending_deliveries
        FROM Retailer
        WHERE Retailer_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $retailer_id, $retailer_id, $retailer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $retailer = $result->fetch_assoc();
    echo json_encode(['success' => true, 'retailer' => $retailer]);
} else {
    echo json_encode(['success' => false, 'message' => 'No retailer details found.']);
}
?>
