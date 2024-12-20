<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION['warehouse_id']) || $_SESSION['role'] !== 'Warehouse_Manager') {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

// Get the current warehouse ID
$warehouse_id = $_SESSION['warehouse_id'];

try {
    // Query to fetch sensors assigned to the warehouse
    $warehouseSensorsQuery = "
        SELECT 
            s.Sensor_ID,
            w.Warehouse_Name AS Assigned_Name,
            'Warehouse' AS Assigned_To,
            s.Temperature,
            s.Humidity,
            s.Last_Updated
        FROM sensor s
        JOIN warehouse w ON s.Assigned_ID = w.Warehouse_ID
        WHERE s.Assigned_To = 'Warehouse' AND w.Warehouse_ID = ?
    ";

    $warehouseStmt = $conn->prepare($warehouseSensorsQuery);
    $warehouseStmt->bind_param("i", $warehouse_id);
    $warehouseStmt->execute();
    $warehouseResult = $warehouseStmt->get_result();

    $sensors = [];

    while ($row = $warehouseResult->fetch_assoc()) {
        $sensors[] = $row;
    }

    // Query to fetch sensors assigned to vehicles in the warehouse
    $vehicleSensorsQuery = "
        SELECT 
            s.Sensor_ID,
            v.Registration_No AS Assigned_Name,
            'Vehicle' AS Assigned_To,
            s.Temperature,
            s.Humidity,
            s.Last_Updated
        FROM sensor s
        JOIN vehicle v ON s.Assigned_ID = v.Registration_No
        WHERE s.Assigned_To = 'Vehicle' AND v.Warehouse_ID = ?
    ";

    $vehicleStmt = $conn->prepare($vehicleSensorsQuery);
    $vehicleStmt->bind_param("i", $warehouse_id);
    $vehicleStmt->execute();
    $vehicleResult = $vehicleStmt->get_result();

    while ($row = $vehicleResult->fetch_assoc()) {
        $sensors[] = $row;
    }

    echo json_encode($sensors);

} catch (Exception $e) {
    error_log("Error fetching sensor data: " . $e->getMessage());
    echo json_encode(['error' => 'Unable to fetch sensor data.']);
}
?>
