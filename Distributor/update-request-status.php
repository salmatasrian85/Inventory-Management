<?php
include '../db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

$requestId = $data['requestId'];
$status = $data['status'];

if ($status === 'Accepted') {
    try {
        $requestDetailsQuery = "SELECT Product_ID, Warehouse_ID, Quantity FROM DistributorRequest WHERE Request_ID = $requestId";
        $requestDetails = $conn->query($requestDetailsQuery)->fetch_assoc();

        $productId = $requestDetails['Product_ID'];
        $warehouseId = $requestDetails['Warehouse_ID'];
        $quantityRequested = $requestDetails['Quantity'];

        // Update stock by reducing the accepted quantity
        $updateStock = "
            UPDATE stock 
            SET Stock_Quantity = Stock_Quantity - $quantityRequested, Last_Updated = NOW() 
            WHERE Warehouse_ID = $warehouseId AND Product_ID = $productId
        ";

        if ($conn->query($updateStock)) {
            $updateRequestStatus = "UPDATE DistributorRequest SET Request_Status = 'Accepted' WHERE Request_ID = $requestId";
            $conn->query($updateRequestStatus);

            echo json_encode(['success' => true]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Request status not recognized']);
}
?>
