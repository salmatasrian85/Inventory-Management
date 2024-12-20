<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management</title>
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
                    <li><a href="warehouse-management.php">Warehouse Management</a></li>
                    <li><a href="product-management.php">Product Management</a></li>
                    <li><a href="vehicle-management.php" class="active">Vehicle Management</a></li>
                    <li><a href="logs-reports.php">Logs & Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Vehicle Management</h1>

            <!-- Add Vehicle Section -->
            <section class="card">
                <h2>Add New Vehicle</h2>
                <form id="add-vehicle-form">
                    <label for="registration-no">Registration Number:</label>
                    <input type="text" id="registration-no" name="registration_no" required>

                    <label for="vehicle-type">Vehicle Type:</label>
                    <input type="text" id="vehicle-type" name="vehicle_type" required>

                    <label for="capacity">Capacity (kg):</label>
                    <input type="number" id="capacity" name="capacity" required>

                    <label for="min-temp">Min Temperature (°C):</label>
                    <input type="number" step="0.1" id="min-temp" name="min_temp" required>

                    <label for="max-temp">Max Temperature (°C):</label>
                    <input type="number" step="0.1" id="max-temp" name="max_temp" required>

                    <label for="warehouse">Assign to Warehouse:</label>
                    <select id="warehouse" name="warehouse_id" required>
                        <!-- Dynamic options loaded via JS -->
                    </select>

                    <button type="submit" class="btn-primary">Add Vehicle</button>
                </form>
            </section>

            <!-- Vehicle List Section -->
            <section class="card">
                <h2>Manage Vehicles</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Registration No</th>
                            <th>Type</th>
                            <th>Capacity (kg)</th>
                            <th>Min Temp (°C)</th>
                            <th>Max Temp (°C)</th>
                            <th>Warehouse</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vehicle-table">
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
            loadVehicles();
            loadWarehouses();

            // Handle Add Vehicle Form Submission
            document.getElementById('add-vehicle-form').addEventListener('submit', (event) => {
                event.preventDefault();

                const formData = new FormData(event.target);
                fetch('add-vehicle.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Vehicle added successfully!');
                            loadVehicles();
                            event.target.reset();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => console.error('Error adding vehicle:', err));
            });
        });

        // Fetch Vehicles
        function loadVehicles() {
            fetch('fetch-vehicles.php')
                .then(response => response.json())
                .then(data => {
                    const vehicleTable = document.getElementById('vehicle-table');
                    vehicleTable.innerHTML = ''; // Clear previous rows

                    if (data.length === 0) {
                        vehicleTable.innerHTML = '<tr><td colspan="7">No vehicles found.</td></tr>';
                        return;
                    }

                    data.forEach(vehicle => {
                        const row = `
                            <tr>
                                <td>${vehicle.Registration_No}</td>
                                <td>${vehicle.Vehicle_Type}</td>
                                <td>${vehicle.Capacity}</td>
                                <td>${vehicle.Min_Temp}</td>
                                <td>${vehicle.Max_Temp}</td>
                                <td>${vehicle.Warehouse_Name}</td>
                                <td>
                                    <button onclick="deleteVehicle('${vehicle.Registration_No}')">Delete</button>
                                </td>
                            </tr>
                        `;
                        vehicleTable.innerHTML += row;
                    });
                })
                .catch(err => console.error('Error fetching vehicles:', err));
        }

        // Fetch Warehouses
        function loadWarehouses() {
            fetch('fetch-warehouses.php')
                .then(response => response.json())
                .then(data => {
                    const warehouseSelect = document.getElementById('warehouse');
                    warehouseSelect.innerHTML = ''; // Clear previous options

                    data.forEach(warehouse => {
                        const option = document.createElement('option');
                        option.value = warehouse.Warehouse_ID;
                        option.textContent = warehouse.Warehouse_Name;
                        warehouseSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Error fetching warehouses:', err));
        }

        // Delete Vehicle
        function deleteVehicle(registrationNo) {
            if (confirm('Are you sure you want to delete this vehicle?')) {
                fetch('delete-vehicle.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ registration_no: registrationNo })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Vehicle deleted successfully!');
                            loadVehicles();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => console.error('Error deleting vehicle:', err));
            }
        }
    </script>
</body>
</html>
