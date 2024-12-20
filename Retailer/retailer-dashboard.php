<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    header("Location: /Login/login.php?error=Unauthorized access.");
    exit;
}

include 'db_connection.php';

$retailer_id = $_SESSION['user_id'];
$query = "SELECT Name FROM retailer WHERE Retailer_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $retailer_id);
$stmt->execute();
$result = $stmt->get_result();
$retailer = $result->fetch_assoc();

if (!$retailer) {
    echo "Error fetching retailer details.";
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<!-- Add HTML and scripts -->


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retailer Dashboard</title>
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
                    <li><a href="retailer-dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="place-order.php">Place Order</a></li>
                    <li><a href="track-deliveries.php">Track Deliveries</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h1>
            <h2>Retailer Dashboard: <?=$retailer_name = $retailer['Name']?></h2>

            <!-- Chart Grid -->
            <section class="chart-grid">
                <!-- Products Sold (Bar Chart) -->
                <div class="chart-card">
                    <h2>Products Sold</h2>
                    <canvas id="productsBarChart"></canvas>
                </div>

                <!-- Orders by Status (Pie Chart) -->
                <div class="chart-card">
                    <h2>Orders by Status</h2>
                    <canvas id="ordersPieChart"></canvas>
                </div>

                <!-- Monthly Sales (Line Chart) -->
                <div class="chart-card">
                    <h2>Monthly Sales</h2>
                    <canvas id="salesLineChart"></canvas>
                </div>

                <!-- Sales by Warehouse (Doughnut Chart) -->
                <div class="chart-card">
                    <h2>Sales by Warehouse</h2>
                    <canvas id="warehouseDoughnutChart"></canvas>
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

        // Render Charts
        async function renderProductsBarChart() {
            const data = await fetchChartData('fetch-products-sold-data.php');
            const ctx = document.getElementById('productsBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Products Sold (Units)',
                        data: data.values,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                }
            });
        }

        async function renderOrdersPieChart() {
            const data = await fetchChartData('fetch-orders-status-data.php');
            const ctx = document.getElementById('ordersPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Orders by Status',
                        data: data.values,
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56']
                    }]
                }
            });
        }

        async function renderSalesLineChart() {
            const data = await fetchChartData('fetch-monthly-sales-data.php');
            const ctx = document.getElementById('salesLineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Sales (Units)',
                        data: data.values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1,
                        fill: false
                    }]
                }
            });
        }

        async function renderWarehouseDoughnutChart() {
            const data = await fetchChartData('fetch-sales-by-warehouse-data.php');
            const ctx = document.getElementById('warehouseDoughnutChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Sales by Warehouse',
                        data: data.values,
                        backgroundColor: ['#ff6384', '#36a2eb', '#ff9f40', '#4bc0c0']
                    }]
                }
            });
        }

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', () => {
            renderProductsBarChart();
            renderOrdersPieChart();
            renderSalesLineChart();
            renderWarehouseDoughnutChart();
        });
    </script>
</body>
</html>
