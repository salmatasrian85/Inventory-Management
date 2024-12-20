<?php
include 'db_connection.php';

header('Content-Type: application/json');

// Query to fetch all products
$sql = "SELECT Product_ID, Produce_Name, Season_Of_Produce, Produce_Type, 
               Usability_Duration, Min_Temp, Max_Temp, Optimum_Humidity 
        FROM product";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
?>
