<?php
include 'db_connection.php';

$sql = "SELECT v.Registration_No, v.Vehicle_Type, v.Capacity, v.Min_Temp, v.Max_Temp, w.Warehouse_Name
        FROM vehicle v
        JOIN warehouse w ON v.Warehouse_ID = w.Warehouse_ID";
$result = $conn->query($sql);

$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

echo json_encode($vehicles);
?>
