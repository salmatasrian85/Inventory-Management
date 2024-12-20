<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produce_name = $_POST['produce_name'];
    $season_of_produce = $_POST['season_of_produce'];
    $produce_type = $_POST['produce_type'];
    $usability_duration = $_POST['usability_duration'];
    $min_temp = $_POST['min_temp'];
    $max_temp = $_POST['max_temp'];
    $optimum_humidity = $_POST['optimum_humidity'];

    // Insert product into the database
    $query = "INSERT INTO product (Produce_Name, Season_Of_Produce, Produce_Type, Usability_Duration, Min_Temp, Max_Temp, Optimum_Humidity) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssiddi", $produce_name, $season_of_produce, $produce_type, $usability_duration, $min_temp, $max_temp, $optimum_humidity);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}
?>
