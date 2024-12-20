<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id']; // Assume farmer's user ID is stored in the session

$sql = "SELECT Harvest.Lot_ID AS id, Product.Produce_Name AS produce_name, Harvest.Harvested_Quantity AS quantity, Harvest.HarvestDate AS date
        FROM Harvest
        JOIN Product ON Harvest.Product_ID = Product.Product_ID
        WHERE Harvest.Farm_ID = (SELECT Farm_ID FROM Farm WHERE Farmer_ID = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $harvests = [];
    while ($row = $result->fetch_assoc()) {
        $harvests[] = $row;
    }
    echo json_encode(['success' => true, 'harvests' => $harvests]);
} else {
    echo json_encode(['success' => false, 'message' => 'No harvests found.']);
}
?>
