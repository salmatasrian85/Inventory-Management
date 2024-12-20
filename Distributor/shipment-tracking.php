<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="distributor-dashboard.php">Dashboard</a></li>
                    <li><a href="distributor-request.php" >Product Request</a></li>
                    <li><a href="order-management.php">Order Management</a></li>
                    <li><a href="shipment-tracking.php" class="active">Shipment Management</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Shipment Management</h1>

            <!-- Shipments Section -->
            <section class="card">
                <h2>Current Shipments</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity (kg)</th>
                            <th>ETA</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="shipment-table">
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
            loadShipments();
        });

        // Load shipments dynamically
        function loadShipments() {
            fetch('fetch-shipments.php')
                .then(response => response.json())
                .then(data => {
                    const shipmentTable = document.getElementById('shipment-table');
                    shipmentTable.innerHTML = ''; // Clear previous rows
                    data.forEach(shipment => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${shipment.tracking_number}</td>
                            <td>${shipment.order_id}</td>
                            <td>${shipment.product}</td>
                            <td>${shipment.quantity}</td>
                            <td>${shipment.eta}</td>
                            <td>${shipment.status}</td>
                            <td>
                                <select class="shipment-status" data-tracking-number="${shipment.tracking_number}">
                                    <option value="Pending" ${shipment.status === 'Pending' ? 'selected' : ''}>Pending</option>
                                    <option value="In Transit" ${shipment.status === 'In Transit' ? 'selected' : ''}>In Transit</option>
                                    <option value="Delivered" ${shipment.status === 'Delivered' ? 'selected' : ''}>Delivered</option>
                                </select>
                            </td>
                        `;
                        shipmentTable.appendChild(row);
                    });

                    // Add change event listeners to status dropdowns
                    document.querySelectorAll('.shipment-status').forEach(select => {
                        select.addEventListener('change', event => {
                            const trackingNumber = event.target.getAttribute('data-tracking-number');
                            const newStatus = event.target.value;
                            updateShipmentStatus(trackingNumber, newStatus);
                        });
                    });
                })
                .catch(err => console.error('Error loading shipments:', err));
        }

        // Update shipment status
        function updateShipmentStatus(trackingNumber, status) {
            fetch('update-shipment-status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tracking_number: trackingNumber, status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Shipment status updated successfully!');
                        loadShipments(); // Reload shipments
                    } else {
                        alert('Error updating shipment status: ' + data.message);
                    }
                })
                .catch(err => console.error('Error updating shipment status:', err));
        }
    </script>
</body>
</html>
