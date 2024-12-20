if ($authenticated) {
    logActivity($userID, $role, 'Login', "User logged in with ID: $userID");
} else {
    logActivity(0, 'Unknown', 'Failed Login', "Failed login attempt with username: $username");
}
