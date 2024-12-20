<?php
include 'db_connection.php';

$sql = "
    SELECT 
        sensor.Sensor_ID, sensor.Assigned_To, sensor.Assigned_ID, 
        sensor.Temperature, sensor.Humidity, sensor.Last_Updated,
        CASE 
            WHEN sensor.Assigned_To = 'Warehouse' THEN warehouse.Warehouse_Name
            WHEN sensor.Assigned_To = 'Vehicle' THEN vehicle.Registration_No
            ELSE 'Unknown'
        END AS Assigned_Name
    FROM sensor
    LEFT JOIN warehouse ON sensor.Assigned_To = 'Warehouse' AND sensor.Assigned_ID = warehouse.Warehouse_ID
    LEFT JOIN vehicle ON sensor.Assigned_To = 'Vehicle' AND sensor.Assigned_ID = vehicle.Vehicle_ID
";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
