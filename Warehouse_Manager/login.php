<?php
session_start();
include 'db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

try {
    $query = "
        SELECT u.ID, u.Role, w.Warehouse_ID 
        FROM users u
        LEFT JOIN warehouse w ON u.ID = w.Manager_ID 
        WHERE u.Username = ? AND u.Password = ? AND u.Role = 'Warehouse_Manager'
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['ID'] = $user['ID'];
        $_SESSION['Role'] = $user['Role'];
        $_SESSION['Warehouse_ID'] = $user['Warehouse_ID'];

        echo json_encode(['success' => true, 'message' => 'Login successful.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
