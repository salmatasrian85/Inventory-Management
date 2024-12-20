<?php
include '/XAMPP/htdocs/Inventory Management/Farmer/db_connection.php';

// Fetch all dispatch data
$sql = "SELECT Dispatch.TrackingNumber AS tracking_number, Product.Produce_Name AS product, 
        Dispatch.Dispatch_Quantity AS quantity, Dispatch.DispatchUnitPrice AS unit_price, 
        Dispatch.Dispatch_Date AS dispatch_date, Warehouse.Warehouse_Name AS warehouse, 
        Dispatch.Dispatch_Status AS status
        FROM Dispatch
        LEFT JOIN Product ON Dispatch.Produce_ID = Product.Product_ID
        LEFT JOIN Warehouse ON Dispatch.Warehouse_ID = Warehouse.Warehouse_ID";

$result = $conn->query($sql);

$dispatches = [];
while ($row = $result->fetch_assoc()) {
    $dispatches[] = $row;
}

echo json_encode($dispatches);
?>
