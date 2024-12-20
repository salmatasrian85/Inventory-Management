<?php
include 'db_connection.php';

// Simulate dynamic updates for sensors
$sensors = [
    ['id' => 'SENSOR001', 'assigned_to' => 'Warehouse', 'assigned_id' => 1, 'temperature' => rand(20, 25), 'humidity' => rand(60, 70)],
    ['id' => 'SENSOR002', 'assigned_to' => 'Warehouse', 'assigned_id' => 2, 'temperature' => rand(18, 22), 'humidity' => rand(70, 80)],
    ['id' => 'SENSOR003', 'assigned_to' => 'Vehicle', 'assigned_id' => 1, 'temperature' => rand(15, 20), 'humidity' => rand(65, 75)],
    ['id' => 'SENSOR004', 'assigned_to' => 'Vehicle', 'assigned_id' => 2, 'temperature' => rand(17, 21), 'humidity' => rand(63, 72)],
];

foreach ($sensors as $sensor) {
    // Update sensor table
    $sqlUpdate = "UPDATE sensor 
                  SET Temperature = ?, Humidity = ?, Last_Updated = NOW()
                  WHERE Sensor_ID = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("dds", $sensor['temperature'], $sensor['humidity'], $sensor['id']);
    $stmtUpdate->execute();

    // Log into sensor_timestamp table
    $sqlLog = "INSERT INTO sensor_timestamp (Sensor_ID, Assigned_To, Assigned_ID, Temperature, Humidity) 
               VALUES (?, ?, ?, ?, ?)";
    $stmtLog = $conn->prepare($sqlLog);
    $stmtLog->bind_param("ssidd", $sensor['id'], $sensor['assigned_to'], $sensor['assigned_id'], $sensor['temperature'], $sensor['humidity']);
    $stmtLog->execute();
}

echo "Sensor data updated and logged.";
?>
