<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sensor_id = $_POST['sensor_id'];
    $assigned_to = $_POST['assigned_to']; // 'Warehouse' or 'Vehicle'
    $assigned_id = $_POST['assigned_id']; // Warehouse_ID or Registration_No

    if ($sensor_id && $assigned_to && $assigned_id) {
        // Check if the sensor is already assigned
        $checkQuery = "
            SELECT Assigned_To, Assigned_ID 
            FROM sensor 
            WHERE Sensor_ID = '$sensor_id'
        ";
        $result = $conn->query($checkQuery);

        if ($result && $result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Sensor is already assigned to another entity.']);
        } else {
            // Assign the sensor
            $assignQuery = "
                INSERT INTO sensor (Sensor_ID, Assigned_To, Assigned_ID)
                VALUES ('$sensor_id', '$assigned_to', '$assigned_id')
            ";

            if ($conn->query($assignQuery)) {
                echo json_encode(['success' => true, 'message' => 'Sensor assigned successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => $conn->error]);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    }
}
?>
