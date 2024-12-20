<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Monitoring</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                    <li><a href="sensor-monitoring.php" class="active">Sensor Monitoring</a></li>
                    <li><a href="warehouse-request-management.php">Request Management</a></li>
                    <li><a href="vehicles-management.php" >Vehicle Management</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Sensor Monitoring</h1>
            <section class="card">
                <h2>Current Sensor Data</h2>
                <table class="simple-table" id="sensor-table">
                    <thead>
                        <tr>
                            <th>Assigned Name</th>
                            <th>Sensor ID</th>
                            <th>Assigned To</th>
                            <th>Temperature (°C)</th>
                            <th>Humidity (%)</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic data will be populated here -->
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
        // Fetch sensor data dynamically
        function fetchSensorData() {
            fetch('fetch-sensor-data.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('sensor-table').querySelector('tbody');
                    tableBody.innerHTML = ''; // Clear existing rows

                    if (data.length === 0) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td colspan="7">No sensor data available</td>`;
                        tableBody.appendChild(row);
                        return;
                    }

                    let alertTriggered = false;
                    const alertSound = document.getElementById('alert-sound');

                    data.forEach(sensor => {
                        const { Assigned_Name, Sensor_ID, Assigned_To, Temperature, Humidity, Last_Updated } = sensor;

                        let status = 'Normal';
                        let statusClass = '';

                        if (Temperature < 15 || Temperature > 25) {
                            status = 'Out of Bounds';
                            statusClass = 'alert';
                            alertTriggered = true;
                        }

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${Assigned_Name || 'Unknown'}</td>
                            <td>${Sensor_ID}</td>
                            <td>${Assigned_To}</td>
                            <td>${Temperature !== null ? `${Temperature}°C` : 'N/A'}</td>
                            <td>${Humidity !== null ? `${Humidity}%` : 'N/A'}</td>
                            <td>${Last_Updated || 'N/A'}</td>
                            <td class="${statusClass}">${status}</td>
                        `;
                        tableBody.appendChild(row);
                    });

                    if (alertTriggered) {
                        alertSound.play();
                    }
                })
                .catch(error => console.error('Error fetching sensor data:', error));
        }

        setInterval(fetchSensorData, 5000);
        document.addEventListener('DOMContentLoaded', fetchSensorData);
    </script>

    <audio id="alert-sound" src="alert.mp3"></audio>
</body>
</html>
