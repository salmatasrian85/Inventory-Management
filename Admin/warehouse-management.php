<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management</title>
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
                    <li><a href="user-management.php">User Management</a></li>
                    <li><a href="warehouse-management.php" class="active">Warehouse Management</a></li>
                    <li><a href="product-management.php">Product Management</a></li>
                    <li><a href="vehicle-management.php">Vehicle Management</a></li>
                    <li><a href="logs-reports.php">Logs & Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Warehouse Management</h1>

            <!-- Add Warehouse Section -->
            <section class="card">
                <h2>Add New Warehouse</h2>
                <form id="add-warehouse-form">
                    <label for="warehouse-name">Warehouse Name:</label>
                    <input type="text" id="warehouse-name" name="warehouse_name" required>

                    <label for="manager-id">Manager ID:</label>
                    <input type="number" id="manager-id" name="manager_id" required>

                    <button type="submit" class="btn-primary">Add Warehouse</button>
                </form>
            </section>

            <!-- Warehouse List Section -->
            <section class="card">
                <h2>Manage Warehouses</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Warehouse ID</th>
                            <th>Name</th>
                            <th>Manager ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="warehouse-table">
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
            loadWarehouses();

            // Handle Add Warehouse Form Submission
            document.getElementById('add-warehouse-form').addEventListener('submit', (event) => {
                event.preventDefault();

                const formData = new FormData(event.target);
                fetch('add-warehouse.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Warehouse added successfully!');
                            loadWarehouses();
                            event.target.reset();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => console.error('Error adding warehouse:', err));
            });
        });

        // Fetch Warehouses
        function loadWarehouses() {
            fetch('fetch-warehouses.php')
                .then(response => response.json())
                .then(data => {
                    const warehouseTable = document.getElementById('warehouse-table');
                    warehouseTable.innerHTML = ''; // Clear previous rows

                    if (data.length === 0) {
                        warehouseTable.innerHTML = '<tr><td colspan="4">No warehouses found.</td></tr>';
                        return;
                    }

                    data.forEach(warehouse => {
                        const row = `
                            <tr>
                                <td>${warehouse.Warehouse_ID}</td>
                                <td>${warehouse.Warehouse_Name}</td>
                                <td>${warehouse.Manager_ID}</td>
                                <td>
                                    <button onclick="deleteWarehouse(${warehouse.Warehouse_ID})">Delete</button>
                                </td>
                            </tr>
                        `;
                        warehouseTable.innerHTML += row;
                    });
                })
                .catch(err => console.error('Error fetching warehouses:', err));
        }

        // Delete Warehouse
        function deleteWarehouse(warehouseId) {
            if (confirm('Are you sure you want to delete this warehouse?')) {
                fetch('delete-warehouse.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ warehouse_id: warehouseId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Warehouse deleted successfully!');
                            loadWarehouses();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => console.error('Error deleting warehouse:', err));
            }
        }
    </script>
</body>
</html>
