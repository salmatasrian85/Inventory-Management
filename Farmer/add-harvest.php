<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Farmer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get the logged-in farmer's ID from the session
$farmer_id = $_SESSION['user_id'];

// Get the farm ID associated with the farmer
$sql = "SELECT Farm_ID FROM farm WHERE Farmer_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();
$farm = $result->fetch_assoc();

if (!$farm) {
    echo json_encode(['success' => false, 'message' => 'No farm associated with the logged-in farmer.']);
    exit;
}

$farm_id = $farm['Farm_ID'];

// Retrieve POST data
$produce = $_POST['produce'] ?? null;
$quantity = $_POST['quantity'] ?? null;
$date = $_POST['date'] ?? null;

// Validate inputs
if (!$produce || !$quantity || !$date) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Insert the new harvest into the database
$sql = "INSERT INTO harvest (HarvestDate, Harvested_Quantity, Product_ID, Farm_ID) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siii", $date, $quantity, $produce, $farm_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Harvest added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding harvest: ' . $conn->error]);
}
?>
