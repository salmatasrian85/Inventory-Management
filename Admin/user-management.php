<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Admin Panel</div>
            <nav>
                <ul>
                    <li><a href="admin-dashboard.php">Dashboard</a></li>
                    <li><a href="user-management.php" class="active">User Management</a></li>
                    <li><a href="warehouse-management.php">Warehouse Management</a></li>
                    <li><a href="product-management.php">Product Management</a></li>
                    <li><a href="vehicle-management.php">Vehicle Management</a></li>
                    <li><a href="logs-reports.php">Logs & Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>User Management</h1>

            <!-- Add User Section -->
            <section class="card">
                <h2>Add New User</h2>
                <form id="add-user-form">
                    <label for="user_id">User ID:</label>
                    <input type="number" id="user_id" name="user_id" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="Farmer">Farmer</option>
                        <option value="Distributor">Distributor</option>
                        <option value="Warehouse_Manager">Warehouse Manager</option>
                        <option value="Retailer">Retailer</option>
                    </select>

                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit" class="btn-primary">Add User</button>
                </form>
            </section>

            <!-- User List Section -->
            <section class="card">
                <h2>Manage Users</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Role</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-table">
                        <!-- Dynamic rows populated via JS -->
                    </tbody>
                </table>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Inventory System</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadUsers();

            // Handle Add User Form Submission
            document.getElementById('add-user-form').addEventListener('submit', (event) => {
                event.preventDefault();

                const formData = new FormData(event.target);
                fetch('add-user.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User added successfully!');
                            loadUsers();
                            event.target.reset();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => console.error('Error adding user:', err));
            });
        });

        // Fetch Users
        function loadUsers() {
            fetch('fetch-users.php')
                .then(response => response.json())
                .then(data => {
                    const userTable = document.getElementById('user-table');
                    userTable.innerHTML = ''; // Clear previous rows

                    if (data.length === 0) {
                        userTable.innerHTML = '<tr><td colspan="4">No users found.</td></tr>';
                        return;
                    }

                    data.forEach(user => {
                        const row = `
                            <tr>
                                <td>${user.ID}</td>
                                <td>${user.Role}</td>
                                <td>${user.Username}</td>
                                <td>
                                    <button onclick="deleteUser(${user.ID})">Delete</button>
                                </td>
                            </tr>
                        `;
                        userTable.innerHTML += row;
                    });
                })
                .catch(err => console.error('Error fetching users:', err));
        }

        // Delete User
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('delete-user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User deleted successfully!');
                            loadUsers();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => console.error('Error deleting user:', err));
            }
        }
    </script>
</body>
</html>
