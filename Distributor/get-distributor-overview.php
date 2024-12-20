<?php
session_start();
include 'db_connection.php';

$distributor_id = $_SESSION['user_id']; // Assume distributor ID is stored in session

$sql = "SELECT Name AS name, Contact_Info AS contact, Location AS location, 
               (SELECT COUNT(*) FROM `Order` WHERE Distributor_ID = ?) AS total_orders,
               (SELECT COUNT(*) FROM Tracking_Number WHERE Order_ID IN 
                   (SELECT Order_ID FROM `Order` WHERE Distributor_ID = ?) AND Shipment_Date IS NULL) AS pending_shipments
        FROM Distributor
        WHERE Distributor_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $distributor_id, $distributor_id, $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $distributor = $result->fetch_assoc();
    echo json_encode(['success' => true, 'distributor' => $distributor]);
} else {
    echo json_encode(['success' => false, 'message' => 'No distributor details found.']);
}
?>
