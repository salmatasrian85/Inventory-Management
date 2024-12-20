
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs & Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Admin Panel</div>
            <nav>
                <ul>
                    <li><a href="admin-dashboard.php">Dashboard</a></li>
                    <li><a href="user-management.php">User Management</a></li>
                    <li><a href="warehouse-management.php">Warehouse Management</a></li>
                    <li><a href="product-management.php">Product Management</a></li>
                    <li><a href="vehicle-management.php">Vehicle Management</a></li>
                    <li><a href="logs-reports.php" class="active">Logs & Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Logs & Reports</h1>

            <!-- Logs Section -->
            <section class="card">
                <h2>System Logs</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Event Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="logs-table">
                        <!-- Logs data will be populated dynamically -->
                    </tbody>
                </table>
            </section>

            <!-- Reports Section -->
            <section class="card">
                <h2>Generate Reports</h2>
                <form id="report-form">
                    <label for="report-type">Select Report Type:</label>
                    <select id="report-type" name="report_type" required>
                        <option value="">Select</option>
                        <option value="orders">Order History</option>
                        <option value="stock">Stock Updates</option>
                        <option value="users">User Summary</option>
                    </select>
                    <button type="submit" class="btn-primary">Generate Report</button>
                </form>
                <div id="report-output" class="card mt-4">
                    <h3>Report Output</h3>
                    <table class="simple-table">
                        <thead id="report-header">
                            <!-- Report headers will be populated dynamically -->
                        </thead>
                        <tbody id="report-body">
                            <!-- Report data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Inventory System</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetchLogs();

            const reportForm = document.getElementById('report-form');
            reportForm.addEventListener('submit', (event) => {
                event.preventDefault();
                generateReport();
            });
        });

        // Fetch system logs
        function fetchLogs() {
            fetch('fetch-logs.php')
                .then(response => response.json())
                .then(data => {
                    const logsTable = document.getElementById('logs-table');
                    logsTable.innerHTML = ''; // Clear existing rows

                    if (data.length === 0) {
                        logsTable.innerHTML = '<tr><td colspan="3">No logs available.</td></tr>';
                        return;
                    }

                    data.forEach(log => {
                        const row = `
                            <tr>
                                <td>${log.timestamp}</td>
                                <td>${log.event_type}</td>
                                <td>${log.description}</td>
                            </tr>
                        `;
                        logsTable.innerHTML += row;
                    });
                })
                .catch(err => console.error('Error fetching logs:', err));
        }

        // Generate report
        function generateReport() {
            const reportType = document.getElementById('report-type').value;

            if (!reportType) {
                alert('Please select a report type.');
                return;
            }

            fetch(`generate-report.php?type=${reportType}`)
                .then(response => response.json())
                .then(data => {
                    const reportHeader = document.getElementById('report-header');
                    const reportBody = document.getElementById('report-body');

                    reportHeader.innerHTML = ''; // Clear existing headers
                    reportBody.innerHTML = ''; // Clear existing rows

                    if (data.headers && data.headers.length > 0) {
                        const headerRow = data.headers.map(header => `<th>${header}</th>`).join('');
                        reportHeader.innerHTML = `<tr>${headerRow}</tr>`;
                    }

                    if (data.rows && data.rows.length > 0) {
                        data.rows.forEach(row => {
                            const rowHtml = row.map(cell => `<td>${cell}</td>`).join('');
                            reportBody.innerHTML += `<tr>${rowHtml}</tr>`;
                        });
                    } else {
                        reportBody.innerHTML = '<tr><td colspan="10">No data available for the selected report.</td></tr>';
                    }
                })
                .catch(err => console.error('Error generating report:', err));
        }
    </script>
</body>
</html>
