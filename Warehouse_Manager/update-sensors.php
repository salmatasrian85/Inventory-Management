<?php
include 'db_connection.php';

// Fetch all sensors assigned to vehicles
$sql = "SELECT s.Sensor_ID, v.Min_Temp, v.Max_Temp 
        FROM sensor s
        JOIN vehicle v ON s.Assigned_ID = v.Registration_No 
        WHERE s.Assigned_To = 'Vehicle'";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sensorId = $row['Sensor_ID'];
        $minTemp = $row['Min_Temp'];
        $maxTemp = $row['Max_Temp'];

        // Generate random temperature and humidity
        $randomTemp = rand($minTemp * 10, $maxTemp * 10) / 10; // Scale up for decimal precision
        $randomHumidity = rand(50, 80); // Simulate humidity

        // Update sensor table
        $updateQuery = "
            UPDATE sensor 
            SET Temperature = $randomTemp, Humidity = $randomHumidity, Last_Updated = NOW() 
            WHERE Sensor_ID = '$sensorId'
        ";
        $conn->query($updateQuery);
    }
    echo "Sensor data updated successfully.";
} else {
    echo "No sensors found.";
}

$conn->close();
?>
