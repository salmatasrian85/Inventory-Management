<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Distributor Requests</title>
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
                    <li><a href="stock-management.php" >Stock Management</a></li>
                    <li><a href="sensor-monitoring.php" >Sensor Monitoring</a></li>
                    <li><a href="warehouse-request-management.php" class="active">Request Management</a></li>
                    <li><a href="vehicles-management.php" >Vehicle Management</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Manage Distributor Requests</h1>
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Distributor</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="request-table">
                    <!-- Requests dynamically populated -->
                </tbody>
            </table>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', fetchRequests);

            function fetchRequests() {
                fetch('fetch-distributor-requests.php')
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('request-table');
                        tableBody.innerHTML = ''; // Clear previous rows

                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        if (data.length === 0) {
                            tableBody.innerHTML = '<tr><td colspan="6">No requests available.</td></tr>';
                            return;
                        }

                        data.forEach(request => {
                            const row = `
                                <tr>
                                    <td>${request.Request_ID}</td>
                                    <td>${request.Distributor_Name}</td>
                                    <td>${request.Produce_Name}</td>
                                    <td>${request.Quantity}</td>
                                    <td>${request.Request_Status}</td>
                                    <td>
                                        ${request.Request_Status === 'Pending' ? `
                                            <button onclick="updateRequestStatus(${request.Request_ID}, 'Accepted')">Accept</button>
                                            <button onclick="updateRequestStatus(${request.Request_ID}, 'Declined')">Decline</button>
                                        ` : ''}
                                    </td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });
                    })
                    .catch(err => console.error('Error fetching requests:', err));
            }

            function updateRequestStatus(requestId, status) {
                fetch('update-request-status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ requestId, status })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Request updated successfully!');
                            fetchRequests(); // Reload requests
                        } else {
                            alert('Error updating request: ' + data.message);
                        }
                    });
            }
        </script>
    </div>
</body>
</html>
