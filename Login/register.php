<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Registration</h1>
        <form action="process-registration.php" method="POST">
            <label for="id">User ID:</label>
            <input type="number" id="id" name="id" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="8" maxlength="20">

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Farmer">Farmer</option>
                <option value="Distributor">Distributor</option>
                <option value="Warehouse_Manager">Warehouse Manager</option>
                <option value="Retailer">Retailer</option>
            </select>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
