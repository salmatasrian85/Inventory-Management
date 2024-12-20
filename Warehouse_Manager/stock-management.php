<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="warehouse-dashboard.php" >Dashboard</a></li>
                    <li><a href="stock-management.php" class="active">Stock Management</a></li>
                    <li><a href="sensor-monitoring.php">Sensor Monitoring</a></li>
                    <li><a href="warehouse-request-management.php">Request Management</a></li>
                    <li><a href="vehicles-management.php" >Vehicle Management</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Stock Management</h1>

            <!-- Manage Dispatch Requests Section -->
            <section class="card">
                <h2>Manage Dispatch Requests</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Dispatch ID</th>
                            <th>Farmer ID</th>
                            <th>Product</th>
                            <th>Quantity (kg)</th>
                            <th>Request Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dispatch-requests-table">
                        <!-- Dynamic rows loaded via JS -->
                    </tbody>
                </table>
            </section>

            <!-- Dispatch In Transit Section -->
            <section class="card">
                <h2>Dispatch In Transit</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Dispatch ID</th>
                            <th>Product</th>
                            <th>Quantity (kg)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="in-transit-table">
                        <!-- Dynamic rows loaded via JS -->
                    </tbody>
                </table>
            </section>

            <!-- Current Stock Section -->
            <section class="card">
                <h2>Current Stock</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Warehouse</th>
                            <th>Stock Quantity (kg)</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody id="stock-table">
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
        // Load Dispatch Requests
        async function loadDispatchRequests() {
            const response = await fetch('fetch-dispatch-requests.php');
            const data = await response.json();

            const table = document.getElementById('dispatch-requests-table');
            table.innerHTML = '';

            if (data.length === 0) {
                table.innerHTML = '<tr><td colspan="6">No pending requests.</td></tr>';
                return;
            }

            data.forEach(dispatch => {
                const row = document.createElement('tr');
                row.id = `dispatch-${dispatch.Dispatch_ID}`;
                row.innerHTML = `
                    <td>${dispatch.Dispatch_ID}</td>
                    <td>${dispatch.Farm_ID}</td>
                    <td>${dispatch.Produce_Name}</td>
                    <td>${dispatch.Dispatch_Quantity}</td>
                    <td>${dispatch.Dispatch_Date}</td>
                    <td>
                        <button onclick="handleDispatch(${dispatch.Dispatch_ID}, 'accept')">Accept</button>
                        <button onclick="handleDispatch(${dispatch.Dispatch_ID}, 'reject')">Reject</button>
                    </td>
                `;
                table.appendChild(row);
            });
        }

        // Handle Dispatch Actions
        async function handleDispatch(dispatchId, action) {
            const response = await fetch('handle-dispatch-request.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ dispatch_id: dispatchId, action })
            });
            const data = await response.json();

            if (data.success) {
                alert(`Dispatch ${action}ed successfully!`);
                document.getElementById(`dispatch-${dispatchId}`).remove(); // Immediately remove the row
                loadDispatchInTransit(); // Refresh the Dispatch In Transit table
            } else {
                alert(`Error: ${data.message}`);
            }
        }

        // Load Dispatch In Transit
        async function loadDispatchInTransit() {
            const response = await fetch('fetch-dispatch-in-transit.php');
            const data = await response.json();

            const table = document.getElementById('in-transit-table');
            table.innerHTML = '';

            if (data.length === 0) {
                table.innerHTML = '<tr><td colspan="5">No dispatches in transit.</td></tr>';
                return;
            }

            data.forEach(dispatch => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${dispatch.Dispatch_ID}</td>
                    <td>${dispatch.Produce_Name}</td>
                    <td>${dispatch.Dispatch_Quantity}</td>
                    <td>${dispatch.Transit_Status}</td>
                    <td>
                        <button onclick="markDelivered(${dispatch.Dispatch_ID})">Delivered</button>
                        <button onclick="deleteDispatch(${dispatch.Dispatch_ID})">Delete</button>
                    </td>
                `;
                table.appendChild(row);
            });
        }

        // Load Stock Data
        async function loadStockData() {
            const response = await fetch('fetch-stock-data.php');
            const data = await response.json();

            const table = document.getElementById('stock-table');
            table.innerHTML = '';

            if (data.length === 0) {
                table.innerHTML = '<tr><td colspan="4">No stock data available.</td></tr>';
                return;
            }

            data.forEach(stock => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${stock.Produce_Name}</td>
                    <td>${stock.Warehouse_Name}</td>
                    <td>${stock.Stock_Quantity}</td>
                    <td>${stock.Last_Updated}</td>
                `;
                table.appendChild(row);
            });
        }

        // Mark Dispatch as Delivered
        async function markDelivered(dispatchId) {
            const response = await fetch('mark-dispatch-delivered.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ dispatch_id: dispatchId })
            });
            const data = await response.json();

            if (data.success) {
                alert('Dispatch marked as delivered!');
                loadDispatchInTransit();
                loadStockData();
            } else {
                alert(`Error: ${data.message}`);
            }
        }

        // Delete Dispatch
        async function deleteDispatch(dispatchId) {
            const response = await fetch('delete-dispatch.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ dispatch_id: dispatchId })
            });
            const data = await response.json();

            if (data.success) {
                alert('Dispatch deleted!');
                loadDispatchInTransit();
            } else {
                alert(`Error: ${data.message}`);
            }
        }

        // Initialize Page
        document.addEventListener('DOMContentLoaded', () => {
            loadDispatchRequests();
            loadDispatchInTransit();
            loadStockData();
        });
    </script>
</body>
</html>
