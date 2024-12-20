<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and is a distributor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Distributor') {
    header("Location: ../Login/login.php?error=Unauthorized access.");
    exit;
}

// Fetch distributor's name
$user_id = $_SESSION['user_id'];
$query = "SELECT Name FROM distributor WHERE Distributor_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$distributor = $result->fetch_assoc();
$distributor_name = $distributor ? $distributor['Name'] : "Unknown";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distributor Dashboard</title>
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
                    <li><a href="distributor-dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="distributor-request.php">Product Request</a></li>
                    <li><a href="order-management.php">Order Management</a></li>
                    <li><a href="shipment-tracking.php">Shipment Tracking</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Distributor Dashboard</h1>
            <h1>Welcome, <?= htmlspecialchars($distributor_name); ?></h1>

            <!-- Stock by Warehouse and Product -->
            <div class="chart-card full-width">
                <h2>Stock by Warehouse and Product</h2>
                <canvas id="stockByWarehouseChart"></canvas>
            </div>

            <!-- Side-by-Side Graphs -->
            <div class="chart-grid">
                <!-- Top Products Sold -->
                <div class="chart-card">
                    <h2>Top Products Sold</h2>
                    <canvas id="topProductsChart"></canvas>
                </div>

                <!-- Monthly Requests -->
                <div class="chart-card">
                    <h2>Monthly Requests</h2>
                    <canvas id="monthlyRequestsChart"></canvas>
                </div>
            </div>
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

        // Render Stock by Warehouse Chart
       
async function renderStockByWarehouseChart() {
    const data = await fetchChartData('fetch-stock-by-warehouse-data.php');
    const ctx = document.getElementById('stockByWarehouseChart').getContext('2d');

    const predefinedColors = [
        'rgba(75, 192, 192, 0.7)', // Teal
        'rgba(255, 99, 132, 0.7)', // Red
        'rgba(54, 162, 235, 0.7)', // Blue
        'rgba(255, 206, 86, 0.7)', // Yellow
        'rgba(153, 102, 255, 0.7)', // Purple
        'rgba(255, 159, 64, 0.7)', // Orange
        'rgba(201, 203, 207, 0.7)' // Grey
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.warehouses,
            datasets: data.products.map((product, index) => ({
                label: product.product_name,
                data: product.stock_values,
                backgroundColor: predefinedColors[index % predefinedColors.length], // Cycle through colors
                borderColor: predefinedColors[index % predefinedColors.length],
                borderWidth: 1
            }))
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                x: { title: { display: true, text: 'Warehouses' } },
                y: { title: { display: true, text: 'Stock Quantity (kg)' } }
            }
        }
    });
}


        // Render Top Products Sold
        async function renderTopProductsChart() {
            const data = await fetchChartData('fetch-top-products-data.php');
            const ctx = document.getElementById('topProductsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Units Sold',
                        data: data.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
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

        // Render Monthly Requests Chart
        async function renderMonthlyRequestsChart() {
            const data = await fetchChartData('fetch-monthly-requests-data.php');
            const ctx = document.getElementById('monthlyRequestsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Requests',
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
            renderStockByWarehouseChart();
            renderTopProductsChart();
            renderMonthlyRequestsChart();
        });
    </script>
</body>
</html>
