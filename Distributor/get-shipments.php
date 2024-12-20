<?php
session_start();
include 'db_connection.php';

$distributor_id = $_SESSION['user_id'];

$sql = "SELECT Tracking_Number.TrackingNumber AS tracking_number, `Order`.Order_ID AS order_id, 
               Vehicle.Registration_No AS vehicle, Tracking_Number.Shipment_Date AS shipment_date, 
               Tracking_Number.ExpectedDeliveryDate AS eta,
               CASE 
                   WHEN CURDATE() < Tracking_Number.ExpectedDeliveryDate THEN 'In Transit'
                   ELSE 'Delivered'
               END AS status
        FROM Tracking_Number
        JOIN `Order` ON Tracking_Number.Order_ID = `Order`.Order_ID
        JOIN Vehicle ON Tracking_Number.Registration_No = Vehicle.Registration_No
        WHERE `Order`.Distributor_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $distributor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $shipments = [];
    while ($row = $result->fetch_assoc()) {
        $shipments[] = $row;
    }
    echo json_encode(['success' => true, 'shipments' => $shipments]);
} else {
    echo json_encode(['success' => false, 'message' => 'No shipments found.']);
}
?>
