<?php
include 'db_connection.php';

$sql = "
    SELECT 
        sensor_timestamp.Timestamp, sensor_timestamp.Sensor_ID, sensor_timestamp.Assigned_To, 
        sensor_timestamp.Assigned_ID, sensor_timestamp.Temperature, sensor_timestamp.Humidity,
        CASE 
            WHEN sensor_timestamp.Assigned_To = 'Warehouse' THEN warehouse.Warehouse_Name
            WHEN sensor_timestamp.Assigned_To = 'Vehicle' THEN vehicle.Registration_No
            ELSE 'Unknown'
        END AS Assigned_Name
    FROM sensor_timestamp
    LEFT JOIN warehouse ON sensor_timestamp.Assigned_To = 'Warehouse' AND sensor_timestamp.Assigned_ID = warehouse.Warehouse_ID
    LEFT JOIN vehicle ON sensor_timestamp.Assigned_To = 'Vehicle' AND sensor_timestamp.Assigned_ID = vehicle.Vehicle_ID
";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
