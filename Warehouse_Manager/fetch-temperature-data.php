<?php
include 'db_connection.php';

// Query to fetch temperature trends grouped by assignment
$query = "
    SELECT 
        CONCAT(Assigned_To, '-', Assigned_ID) AS labels, 
        ROUND(AVG(Temperature), 2) AS avg_temp 
    FROM 
        sensor 
    WHERE 
        Temperature IS NOT NULL 
    GROUP BY 
        Assigned_To, Assigned_ID
";

$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['labels'];
    $data['values'][] = $row['avg_temp'];
}
echo json_encode($data);
?>
