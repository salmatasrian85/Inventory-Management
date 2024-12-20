<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
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
                    <li><a href="place-order.php" class="active">Place Order</a></li>
                    <li><a href="track-deliveries.php">Track Deliveries</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Place Order</h1>

            <!-- Create New Order Section -->
            <section class="card">
                <h2>Create New Order</h2>
                <form id="order-form" method="POST">
                    <label for="product">Select Product:</label>
                    <select id="product" name="product" required>
                        <!-- Dynamic options loaded via fetch-products.php -->
                    </select>

                    <label for="quantity">Quantity (kg):</label>
                    <input type="number" id="quantity" name="quantity" required>

                    <label for="warehouse">Select Distributor:</label>
                    <select id="warehouse" name="warehouse" required>
                        <!-- Dynamic options loaded via fetch-warehouses.php -->
                    </select>

                    <button type="submit">Place Order</button>
                </form>
            </section>

            <!-- Existing Orders Section -->
            <section class="card">
                <h2>Your Orders</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity (kg)</th>
                            <th>Distributor</th>
                            <th>Order Date</th>
                            <th>Status</th>
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
            loadOptions('fetch-products.php', 'product');
            loadOptions('fetch-warehouses.php', 'warehouse');
            loadOrderTable();
        });

        // Load dynamic options (products and warehouses)
        function loadOptions(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const selectElement = document.getElementById(elementId);
                    selectElement.innerHTML = '';
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        selectElement.appendChild(option);
                    });
                })
                .catch(err => console.error(`Error fetching ${elementId}:`, err));
        }

        // Handle Order Form Submission
        const orderForm = document.getElementById('order-form');
        orderForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(orderForm);

            fetch('add-order.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order placed successfully!');
                        loadOrderTable(); // Reload the order table
                        orderForm.reset();
                    } else {
                        alert('Error placing order: ' + data.message);
                    }
                })
                .catch(err => console.error('Error placing order:', err));
        });

        // Load Existing Orders in the table
        function loadOrderTable() {
            fetch('fetch-orders.php')
                .then(response => response.json())
                .then(data => {
                    const orderTable = document.getElementById('order-table');
                    orderTable.innerHTML = ''; // Clear previous rows
                    data.forEach(order => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${order.order_id}</td>
                            <td>${order.product}</td>
                            <td>${order.quantity}</td>
                            <td>${order.warehouse}</td>
                            <td>${order.order_date}</td>
                            <td>${order.status}</td>
                        `;
                        orderTable.appendChild(row);
                    });
                })
                .catch(err => console.error('Error loading order table:', err));
        }
    </script>
</body>
</html>
