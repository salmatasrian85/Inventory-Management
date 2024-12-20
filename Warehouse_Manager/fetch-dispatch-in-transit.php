<?php
include 'db_connection.php';

session_start();

$warehouse_id = $_SESSION['warehouse_id'];

try {
    $query = "
        SELECT 
            dt.Dispatch_ID,
            p.Produce_Name,
            dt.Dispatch_Quantity,
            dt.Transit_Status
        FROM dispatch_transit dt
        JOIN product p ON dt.Produce_ID = p.Product_ID
        WHERE dt.Warehouse_ID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $warehouse_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);
} catch (Exception $e) {
    error_log('Error fetching dispatch in transit: ' . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch dispatch in transit.']);
}
?>
