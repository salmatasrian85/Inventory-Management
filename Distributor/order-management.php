<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="distributor-dashboard.php" >Dashboard</a></li>
                    <li><a href="distributor-request.php" >Product Request</a></li>
                    <li><a href="order-management.php" class="active" >Order Management</a></li>
                    <li><a href="shipment-tracking.php">Shipment Tracking</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Order Management</h1>

            <!-- Orders Section -->
            <section class="card">
                <h2>Retailer Orders</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Retailer ID</th>
                            <th>Product</th>
                            <th>Quantity (kg)</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="order-table">
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
            loadOrders();
        });

        // Load orders dynamically
        function loadOrders() {
            fetch('fetch-orders-distributor.php')
                .then(response => response.json())
                .then(data => {
                    const orderTable = document.getElementById('order-table');
                    orderTable.innerHTML = ''; // Clear previous rows
                    data.forEach(order => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${order.order_id}</td>
                            <td>${order.retailer_id}</td>
                            <td>${order.product}</td>
                            <td>${order.quantity}</td>
                            <td>${order.order_date}</td>
                            <td>${order.status}</td>
                            <td>
                                <select class="order-status" data-order-id="${order.order_id}">
                                    <option value="Pending" ${order.status === 'Pending' ? 'selected' : ''}>Pending</option>
                                    <option value="Accepted" ${order.status === 'Accepted' ? 'selected' : ''}>Accepted</option>
                                    <option value="Declined" ${order.status === 'Declined' ? 'selected' : ''}>Declined</option>
                                </select>
                            </td>
                        `;
                        orderTable.appendChild(row);
                    });

                    // Add change event listeners to status dropdowns
                    document.querySelectorAll('.order-status').forEach(select => {
                        select.addEventListener('change', event => {
                            const orderId = event.target.getAttribute('data-order-id');
                            const newStatus = event.target.value;
                            updateOrderStatus(orderId, newStatus);
                        });
                    });
                })
                .catch(err => console.error('Error loading orders:', err));
        }

        // Update order status
        function updateOrderStatus(orderId, status) {
            fetch('update-order-status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order status updated successfully!');
                        loadOrders(); // Reload orders
                    } else {
                        alert('Error updating order status: ' + data.message);
                    }
                })
                .catch(err => console.error('Error updating order status:', err));
        }
    </script>
</body>
</html>
