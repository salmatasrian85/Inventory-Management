<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distributor Product Request</title>
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
                    <li><a href="distributor-request.php" class="active" >Product Request</a></li>
                    <li><a href="order-management.php">Order Management</a></li>
                    <li><a href="shipment-tracking.php">Shipment Tracking</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Distributor Product Request</h1>

            <!-- Request Form Section -->
            <section class="card">
                <h2>Create Product Request</h2>
                <form id="request-form" method="POST" action="submit-request.php">
                    <label for="warehouse">Select Warehouse:</label>
                    <select id="warehouse" name="warehouse_id" required>
                        <!-- Dynamic options loaded via JS -->
                    </select>

                    <label for="product">Select Product:</label>
                    <select id="product" name="product_id" required>
                        <!-- Dynamic options loaded via JS -->
                    </select>

                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required>

                    <button type="submit">Submit Request</button>
                </form>
            </section>

            <!-- Your Requests Section -->
            <section class="card">
                <h2>Your Requests</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Warehouse</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="request-table">
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
            // Load warehouses and products dynamically
            fetch('fetch-warehouse-product-data.php')
                .then(response => response.json())
                .then(data => {
                    const warehouseSelect = document.getElementById('warehouse');
                    const productSelect = document.getElementById('product');

                    data.warehouses.forEach(warehouse => {
                        const option = document.createElement('option');
                        option.value = warehouse.Warehouse_ID;
                        option.textContent = warehouse.Warehouse_Name;
                        warehouseSelect.appendChild(option);
                    });

                    data.products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.Product_ID;
                        option.textContent = product.Produce_Name;
                        productSelect.appendChild(option);
                    });
                });

            // Load requests dynamically
            fetchRequests();
        });

        function fetchRequests() {
    fetch('fetch-distributor-requests.php')
        .then(response => response.json())
        .then(data => {
            console.log('Requests Data:', data); // Debug log

            const requestTable = document.getElementById('request-table');
            requestTable.innerHTML = ''; // Clear previous rows

            if (data.length === 0) {
                requestTable.innerHTML = `<tr><td colspan="5">No requests found</td></tr>`;
                return;
            }

            data.forEach(request => {
                const row = `
                    <tr>
                        <td>${request.Request_ID}</td>
                        <td>${request.Warehouse_Name}</td>
                        <td>${request.Produce_Name}</td>
                        <td>${request.Quantity}</td>
                        <td class="status-${request.Request_Status.toLowerCase()}">${request.Request_Status}</td>
                    </tr>
                `;
                requestTable.innerHTML += row;
            });
        })
        .catch(err => console.error('Error fetching requests:', err));
}


        // Handle form submission
        document.getElementById('request-form').addEventListener('submit', (event) => {
            event.preventDefault();

            const formData = new FormData(event.target);
            formData.append('distributor_id', 1); // Default distributor ID for testing

            fetch('submit-request.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Request submitted successfully!');
                        fetchRequests(); // Reload requests
                        event.target.reset(); // Clear form
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        });
    </script>
</body>
</html>
