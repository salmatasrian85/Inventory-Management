<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Check if inputs are empty
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and Password are required.']);
        exit;
    }

    // Prepare query to fetch user data
    $query = "SELECT ID, Role, Password FROM users WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verify hashed password
        if (hash('sha256', $password) === $row['Password']) {
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['role'] = $row['Role'];
            $_SESSION['username'] = $username;

            echo json_encode(['success' => true, 'role' => $row['Role']]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
?>
