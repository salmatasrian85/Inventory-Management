<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Deliveries</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="retailer-dashboard.php">Dashboard</a></li>
                    <li><a href="place-order.php">Place Order</a></li>
                    <li><a href="track-deliveries.php" class="active">Track Deliveries</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Track Deliveries</h1>

            <section class="card">
                <h2>Deliveries</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity (kg)</th>
                            <th>Delivery ETA</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="delivery-table">
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
            loadDeliveries();
        });

        function loadDeliveries() {
            fetch('fetch-deliveries.php')
                .then(response => response.json())
                .then(data => {
                    const deliveryTable = document.getElementById('delivery-table');
                    deliveryTable.innerHTML = ''; // Clear previous rows
                    data.forEach(delivery => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${delivery.tracking_number}</td>
                            <td>${delivery.order_id}</td>
                            <td>${delivery.product}</td>
                            <td>${delivery.quantity}</td>
                            <td>${delivery.eta}</td>
                            <td>${delivery.status}</td>
                        `;
                        deliveryTable.appendChild(row);
                    });
                })
                .catch(err => console.error('Error loading deliveries:', err));
        }
    </script>
</body>
</html>
