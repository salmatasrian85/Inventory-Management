<?php
include 'db_connection.php';

try {
    // Fetching shipment statuses from the `dispatch` table
    $query = "
        SELECT Dispatch_Status AS status, COUNT(*) AS count
        FROM dispatch
        WHERE Dispatch_Status IS NOT NULL
        GROUP BY Dispatch_Status
    ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'labels' => array_column($data, 'status'),
            'values' => array_column($data, 'count')
        ]);
    } else {
        echo json_encode([
            'labels' => [],
            'values' => []
        ]);
    }
} catch (Exception $e) {
    error_log('Error fetching shipment status data: ' . $e->getMessage());
    echo json_encode([
        'labels' => [],
        'values' => []
    ]);
}
?>
