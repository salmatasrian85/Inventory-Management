<?php
include 'db_connection.php';

// Retrieve POST data
$id = $_POST['id'] ?? null;
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$role = $_POST['role'] ?? null;

// Validate inputs
if (!$id || !$username || !$password || !$role) {
    echo '<script>alert("All fields are required!"); window.history.back();</script>';
    exit;
}

// Verify the ID exists in the respective table based on the role
$table_map = [
    'Farmer' => 'farmer',
    'Distributor' => 'distributor',
    'Warehouse_Manager' => 'manager',
    'Retailer' => 'retailer', // Ensure Retailer is included
];

if (!array_key_exists($role, $table_map)) {
    echo '<script>alert("Invalid role selected."); window.history.back();</script>';
    exit;
}

$role_table = $table_map[$role];
$id_column = $role_table . '_ID';

$sql_verify = "SELECT $id_column FROM $role_table WHERE $id_column = ?";
$stmt_verify = $conn->prepare($sql_verify);
$stmt_verify->bind_param("i", $id);
$stmt_verify->execute();
$result_verify = $stmt_verify->get_result();

if ($result_verify->num_rows === 0) {
    echo '<script>alert("The provided ID does not exist in the ' . $role . ' table."); window.history.back();</script>';
    exit;
}

// Check if the ID and Role already exist in the users table
$sql_check = "SELECT ID FROM users WHERE ID = ? AND Role = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("is", $id, $role);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo '<script>alert("User ID already exists for this role. Please use a different ID."); window.history.back();</script>';
    exit;
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the `users` table
$sql_insert = "INSERT INTO users (ID, Username, Password, Role) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("isss", $id, $username, $hashed_password, $role);

if ($stmt_insert->execute()) {
    echo '<script>alert("User registered successfully!"); window.location.href = "register.php";</script>';
} else {
    echo '<script>alert("Registration failed: ' . $stmt_insert->error . '"); window.history.back();</script>';
}
?>
