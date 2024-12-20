<?php
include 'db_connection.php';

// Query to fetch sensor alerts grouped by assignment
$query = "
    SELECT 
        CONCAT(Assigned_To, '-', Assigned_ID) AS labels, 
        COUNT(Sensor_ID) AS alert_count 
    FROM 
        sensor 
    WHERE 
        Temperature > 50 OR Temperature < 5 
    GROUP BY 
        Assigned_To, Assigned_ID
";

$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['labels'];
    $data['values'][] = $row['alert_count'];
}
echo json_encode($data);
?>
