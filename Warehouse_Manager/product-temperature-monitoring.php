<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Temperature Monitoring</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        .alert {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="product-temperature-monitoring.php" class="active">Temperature Monitoring</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main class="centered-content">
            <h1>Product Temperature Monitoring</h1>

            <section class="card">
                <h2>Temperature Alerts</h2>
                <table class="simple-table" id="temperature-table">
                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Product</th>
                            <th>Min Temp (°C)</th>
                            <th>Max Temp (°C)</th>
                            <th>Current Temp (°C)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic data will be populated here -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <!-- Sound Alert -->
    <audio id="alert-sound" src="alert.mp3"></audio>

    <script>
        function fetchTemperatureData() {
            axios.get('fetch-product-temperature-data.php')
                .then(response => {
                    const table = document.getElementById('temperature-table').querySelector('tbody');
                    const alertSound = document.getElementById('alert-sound');
                    let alertTriggered = false;

                    table.innerHTML = '';
                    response.data.forEach(row => {
                        const { Warehouse_Name, Produce_Name, Min_Temp, Max_Temp, Temperature } = row;
                        let status = 'Normal';
                        let alertClass = '';

                        if (Temperature < Min_Temp || Temperature > Max_Temp) {
                            status = 'Out of Range';
                            alertClass = 'alert';
                            alertTriggered = true;
                        }

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${Warehouse_Name}</td>
                            <td>${Produce_Name}</td>
                            <td>${Min_Temp}°C</td>
                            <td>${Max_Temp}°C</td>
                            <td>${Temperature !== null ? Temperature + '°C' : 'N/A'}</td>
                            <td class="${alertClass}">${status}</td>
                        `;
                        table.appendChild(tr);
                    });

                    // Play alert sound if triggered
                    if (alertTriggered) {
                        alertSound.play();
                    }
                })
                .catch(error => console.error('Error fetching temperature data:', error));
        }

        // Fetch data every 10 seconds
        setInterval(fetchTemperatureData, 10000);

        // Initial load
        document.addEventListener('DOMContentLoaded', fetchTemperatureData);
    </script>
</body>
</html>
