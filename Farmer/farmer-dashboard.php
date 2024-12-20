<?php
session_start();

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Farmer') {
    header("Location: ../Login/login.php?error=Unauthorized access.");
    exit;
}

// Fetch farmer details from session
include 'db_connection.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT Farmer_Name FROM farmer WHERE Farmer_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$farmer = $result->fetch_assoc();

if (!$farmer) {
    header("Location: /Login/login.php?error=Unauthorized access.");
    exit;
}

// Store farmer name
$farmer_name = $farmer['Farmer_Name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="harvest-management.php">Harvest Management</a></li>
                    <li><a href="dispatch-requests.php">Dispatch Requests</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Welcome, <?= htmlspecialchars($farmer_name); ?></h1>
            <h2>Manage your farm data efficiently</h2>

            <!-- Chart Section -->
            <section>
                <h2>Statistics</h2>

                <!-- Bar Chart Full Width -->
                <div class="chart-container full-width">
                    <canvas id="harvestBarChart"></canvas>
                </div>

                <!-- Grid with Pie and Line Chart -->
                <div class="chart-grid">
                    <div class="chart-container">
                        <canvas id="dispatchPieChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <canvas id="dispatchLineChart"></canvas>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Inventory System</p>
        </footer>
    </div>

    <script>
        // Fetch chart data from the server
        async function fetchChartData(endpoint) {
            const response = await fetch(endpoint);
            return response.json();
        }

        // Render Harvest Bar Chart
async function renderHarvestBarChart() {
    const data = await fetchChartData('fetch-harvest-data.php');
    const ctx = document.getElementById('harvestBarChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels, // Products
            datasets: [{
                label: 'Harvest Quantities (kg)',
                data: data.values, // Quantities
                backgroundColor: data.colors,
                borderColor: data.colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                x: { title: { display: true, text: 'Products' } },
                y: { title: { display: true, text: 'Quantities (kg)' } }
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
                        backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
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

        // Render Monthly Dispatch Line Chart
        async function renderDispatchLineChart() {
            const data = await fetchChartData('fetch-monthly-dispatch-data.php');
            const ctx = document.getElementById('dispatchLineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Monthly Dispatch Quantities (kg)',
                        data: data.values,
                        borderColor: 'rgba(255, 99, 132, 1)',
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
            renderHarvestBarChart();
            renderDispatchPieChart();
            renderDispatchLineChart();
        });
    </script>
</body>
</html>
