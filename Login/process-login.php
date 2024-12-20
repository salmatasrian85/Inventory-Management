<?php
session_start();
include 'db_connection.php';

// Retrieve and validate form data
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

if (!$username || !$password) {
    echo '<script>alert("Username and Password are required!"); window.history.back();</script>';
    exit;
}

// Check if the username exists
$sql = "SELECT ID, Role, Password FROM users WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script>alert("Invalid credentials."); window.history.back();</script>';
    exit;
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user['Password'])) {
    echo '<script>alert("Invalid credentials."); window.history.back();</script>';
    exit;
}

// Store session variables
$_SESSION['user_id'] = $user['ID'];
$_SESSION['role'] = $user['Role'];
$_SESSION['username'] = $username;

// Redirect based on role
switch ($user['Role']) {
    case 'Farmer':
        header("Location: ../Farmer/farmer-dashboard.php");
        break;
    case 'Distributor':
        header("Location: ../Distributor/distributor-dashboard.php");
        break;
    case 'Warehouse_Manager':
        header("Location: ../Warehouse_Manager/warehouse-dashboard.php");
        break;
    case 'Retailer':
        header("Location: ../Retailer/retailer-dashboard.php");
        break;
    case 'Admin':
        header("Location: ../Admin/admin-dashboard.php");
        break;
    default:
        echo '<script>alert("Invalid role."); window.history.back();</script>';
}
exit;
?>
