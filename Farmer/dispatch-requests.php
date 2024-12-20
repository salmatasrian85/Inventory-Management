<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Requests</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="harvest-management.php">Harvest Management</a></li>
                    <li><a href="dispatch-requests.php" class="active">Dispatch Requests</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="centered-content">
            <h1>Dispatch Requests</h1>

            <!-- Create New Dispatch Section -->
            <section class="card">
                <h2>Create Dispatch Request</h2>
                <form id="dispatch-form">
                    <div class="grid-layout">
                        <div>
                            <label for="product">Select Product:</label>
                            <select id="product" name="product" required>
                                <!-- Dynamic options loaded via JS -->
                            </select>
                        </div>
                        <div>
                            <label for="quantity">Quantity (kg):</label>
                            <input type="number" id="quantity" name="quantity" required>
                        </div>
                        <div>
                            <label for="warehouse">Select Warehouse:</label>
                            <select id="warehouse" name="warehouse" required>
                                <!-- Dynamic options loaded via JS -->
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Create Dispatch</button>
                </form>
            </section>

            <!-- Existing Dispatch Requests -->
            <section class="card">
                <h2>Your Dispatch Requests</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Warehouse</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="dispatch-table">
                        <!-- Dynamic rows loaded via JS -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadProducts();
            loadWarehouses();
            loadDispatchRequests();
        });

        function loadProducts() {
            fetch('fetch-products.php')
                .then(response => response.json())
                .then(data => {
                    const productSelect = document.getElementById('product');
                    data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.Product_ID;
                        option.textContent = product.Produce_Name;
                        productSelect.appendChild(option);
                    });
                });
        }

        function loadWarehouses() {
            fetch('fetch-warehouses.php')
                .then(response => response.json())
                .then(data => {
                    const warehouseSelect = document.getElementById('warehouse');
                    data.forEach(warehouse => {
                        const option = document.createElement('option');
                        option.value = warehouse.Warehouse_ID;
                        option.textContent = warehouse.Warehouse_Name;
                        warehouseSelect.appendChild(option);
                    });
                });
        }

        function loadDispatchRequests() {
    fetch('fetch-dispatch-requests.php')
        .then(response => response.json())
        .then(data => {
            const dispatchTable = document.getElementById('dispatch-table');
            dispatchTable.innerHTML = '';

            if (data.error) {
                dispatchTable.innerHTML = `<tr><td colspan="6">${data.error}</td></tr>`;
                return;
            }

            if (data.length === 0) {
                dispatchTable.innerHTML = '<tr><td colspan="6">No dispatch requests found.</td></tr>';
                return;
            }

            data.forEach(dispatch => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${dispatch.TrackingNumber}</td>
                    <td>${dispatch.Produce_Name}</td>
                    <td>${dispatch.Dispatch_Quantity}</td>
                    <td>${dispatch.Warehouse_Name}</td>
                    <td>${dispatch.Dispatch_Date}</td>
                    <td>${dispatch.Dispatch_Status}</td>
                `;
                dispatchTable.appendChild(row);
            });
        })
        .catch(error => console.error('Error loading dispatch requests:', error));
}


        document.getElementById('dispatch-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('add-dispatch.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        loadDispatchRequests();
                    } else {
                        alert(data.message);
                    }
                });
        });
    </script>
</body>
</html>
