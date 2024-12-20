<?php
session_start();
include 'db_connection.php';

$manager_id = $_SESSION['user_id'];

// Get all sensors associated with the manager's warehouse
$sql = "SELECT Sensor.Sensor_ID AS sensor_id, Sensor.Zone AS zone, 
               Timestamp.Temperature_Rate AS temperature, Timestamp.Humidity_Rate AS humidity,
               CASE 
                   WHEN Timestamp.Temperature_Rate BETWEEN 5 AND 25 AND Timestamp.Humidity_Rate BETWEEN 40 AND 70 THEN 'Normal'
                   ELSE 'Alert'
               END AS status
        FROM Sensor
        JOIN Sensor_Assignment ON Sensor.Sensor_ID = Sensor_Assignment.Sensor_ID
        JOIN Timestamp ON Sensor.Sensor_ID = Timestamp.Sensor_ID
        WHERE Sensor_Assignment.Warehouse_ID = (SELECT Warehouse_ID FROM Warehouse WHERE Manager_ID = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sensors = [];
    while ($row = $result->fetch_assoc()) {
        $sensors[] = $row;
    }
    echo json_encode(['success' => true, 'sensors' => $sensors]);
} else {
    echo json_encode(['success' => false, 'message' => 'No sensors found.']);
}
?>
