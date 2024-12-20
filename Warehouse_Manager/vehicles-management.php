<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                <li><a href="warehouse-dashboard.php" >Dashboard</a></li>
                    <li><a href="stock-management.php" >Stock Management</a></li>
                    <li><a href="sensor-monitoring.php" >Sensor Monitoring</a></li>
                    <li><a href="warehouse-request-management.php" >Request Management</a></li>
                    <li><a href="vehicles-management.php" class="active">Vehicle Management</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Vehicle Management</h1>

            <!-- Vehicle List Section -->
            <section class="card">
                <h2>Vehicles Assigned to Your Warehouse</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Registration No</th>
                            <th>Vehicle Type</th>
                            <th>Capacity (kg)</th>
                            <th>Min Temp (°C)</th>
                            <th>Max Temp (°C)</th>
                        </tr>
                    </thead>
                    <tbody id="vehicle-table">
                        <!-- Dynamic rows loaded via JS -->
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
        });

        function loadVehicles() {
            fetch('fetch-vehicles.php')
                .then(response => response.json())
                .then(data => {
                    const vehicleTable = document.getElementById('vehicle-table');
                    vehicleTable.innerHTML = ''; // Clear previous rows

                    if (!data.success || data.vehicles.length === 0) {
                        vehicleTable.innerHTML = `<tr><td colspan="5">No vehicles assigned to your warehouse.</td></tr>`;
                        return;
                    }

                    data.vehicles.forEach(vehicle => {
                        const row = `
                            <tr>
                                <td>${vehicle.Registration_No}</td>
                                <td>${vehicle.Vehicle_Type}</td>
                                <td>${vehicle.Capacity}</td>
                                <td>${vehicle.Min_Temp}</td>
                                <td>${vehicle.Max_Temp}</td>
                            </tr>
                        `;
                        vehicleTable.innerHTML += row;
                    });
                })
                .catch(err => console.error('Error loading vehicles:', err));
        }
    </script>
</body>
</html>
