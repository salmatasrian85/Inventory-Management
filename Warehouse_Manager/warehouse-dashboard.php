<?php
session_start();

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Warehouse_Manager') {
    header("Location: /Login/login.php?error=Unauthorized access.");
    exit;
}

// Fetch the warehouse name for the logged-in manager
include 'db_connection.php';
$user_id = $_SESSION['user_id'];

$query = "SELECT Warehouse_ID, Warehouse_Name FROM warehouse WHERE Manager_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$warehouse = $result->fetch_assoc();

if (!$warehouse) {
    header("Location: /Login/login.php?error=Unauthorized access.");
    exit;
}

// Store the Warehouse_ID and Name in session
$_SESSION['warehouse_id'] = $warehouse['Warehouse_ID'];
$warehouse_name = $warehouse['Warehouse_Name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Manager Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .chart-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .chart-card h2 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .large-chart {
            grid-column: span 2;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="warehouse-dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="stock-management.php">Stock Management</a></li>
                    <li><a href="sensor-monitoring.php">Sensor Monitoring</a></li>
                    <li><a href="warehouse-request-management.php">Request Management</a></li>
                    <li><a href="vehicles-management.php" >Vehicle Management</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1><h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h1>
    <h2>Warehouse: <?= htmlspecialchars($warehouse_name); ?></h2>
    <p>Manage your warehouse data from here.</p></h1>

            <!-- Sensor Alerts Bar Chart -->
            <section class="chart-card large-chart">
                <h2>Sensor Alerts</h2>
                <canvas id="sensorBarChart"></canvas>
            </section>

            <!-- Other Charts -->
            <section class="chart-grid">
                <!-- Dispatch Requests by Status (Pie Chart) -->
                <div class="chart-card">
                    <h2>Dispatch Requests</h2>
                    <canvas id="dispatchPieChart"></canvas>
                </div>

                <!-- Monthly Stock Updates (Line Chart) -->
                <div class="chart-card">
                    <h2>Monthly Stock Updates</h2>
                    <canvas id="stockLineChart"></canvas>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Inventory System</p>
        </footer>
    </div>

    <script>
        // Fetch data from the server
        async function fetchChartData(endpoint) {
            const response = await fetch(endpoint);
            return response.json();
        }

        // Render Sensor Alerts Bar Chart
        async function renderSensorAlertsBarChart() {
            const data = await fetchChartData('fetch-sensor-alerts-data.php');
            const ctx = document.getElementById('sensorBarChart').getContext('2d');

            if (window.sensorAlertsChart) {
                window.sensorAlertsChart.destroy();
            }

            window.sensorAlertsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Sensor Alerts',
                        data: data.values,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Render Dispatch Pie Chart
        async function renderDispatchPieChart() {
            const data = await fetchChartData('fetch-dispatch-status-data.php');
            const ctx = document.getElementById('dispatchPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Dispatch Requests',
                        data: data.values,
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });
        }

        // Render Stock Line Chart
        async function renderStockLineChart() {
            const data = await fetchChartData('fetch-monthly-stock-data.php');
            const ctx = document.getElementById('stockLineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Stock Updates (kg)',
                        data: data.values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });
        }

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', () => {
            renderSensorAlertsBarChart();
            renderDispatchPieChart();
            renderStockLineChart();
        });
    </script>
</body>

</html>
