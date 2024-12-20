<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Admin Panel</div>
            <nav>
                <ul>
                    <li><a href="admin-dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="user-management.php">User Management</a></li>
                    <li><a href="warehouse-management.php">Warehouse Management</a></li>
                    <li><a href="product-management.php">Product Management</a></li>
                    <li><a href="vehicle-management.php" >Vehicle Management</a></li>
                    <li><a href="logs-reports.php">Logs & Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Admin Dashboard</h1>

            <!-- Dashboard Summary -->
            <div class="dashboard-summary">
                <div class="card">
                    <h3>Products</h3>
                    <p id="products-count">0</p>
                </div>
                <div class="card">
                    <h3>Users</h3>
                    <p id="users-count">0</p>
                </div>
                <div class="card">
                    <h3>Orders</h3>
                    <p id="orders-count">0</p>
                </div>
            </div>

            <!-- Sales Chart -->
            <section class="card">
                <h2>Monthly Sales</h2>
                <canvas id="sales-chart"></canvas>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Inventory System</p>
        </footer>
    </div>

    <script>
        // Fetch dashboard data
        async function fetchDashboardData() {
            const response = await fetch('fetch-dashboard-data.php');
            return response.json();
        }

        // Render dashboard
        async function renderDashboard() {
            const data = await fetchDashboardData();

            // Update summary counts
            document.getElementById('products-count').textContent = data.products;
            document.getElementById('users-count').textContent = data.users;
            document.getElementById('orders-count').textContent = data.orders;

            // Render sales chart
            const ctx = document.getElementById('sales-chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.sales.map(sale => sale.month),
                    datasets: [{
                        label: 'Total Sales',
                        data: data.sales.map(sale => sale.total_sales),
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

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', renderDashboard);
    </script>
</body>
</html>
