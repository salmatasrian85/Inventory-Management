<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="auth-container">
        <h1>Login</h1>
        <form id="login-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Farmer">Farmer</option>
                <option value="Warehouse Manager">Warehouse Manager</option>
                <option value="Distributor">Distributor</option>
                <option value="Retailer">Retailer</option>
                <option value="Admin">Admin</option>
            </select>

            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>

    <script src="login.js"></script>
</body>
</html>
