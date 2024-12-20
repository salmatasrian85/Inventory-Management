<?php
include 'db_connection.php';

$registration_no = $_POST['registration_no'] ?? null;
$vehicle_type = $_POST['vehicle_type'] ?? null;
$capacity = $_POST['capacity'] ?? null;
$min_temp = $_POST['min_temp'] ?? null;
$max_temp = $_POST['max_temp'] ?? null;
$warehouse_id = $_POST['warehouse_id'] ?? null;

if (!$registration_no || !$vehicle_type || !$capacity || !$min_temp || !$max_temp || !$warehouse_id) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$sql = "INSERT INTO vehicle (Registration_No, Vehicle_Type, Capacity, Min_Temp, Max_Temp, Warehouse_ID) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssiddi', $registration_no, $vehicle_type, $capacity, $min_temp, $max_temp, $warehouse_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
?>
