<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <header class="navbar">
            <div class="logo">Inventory System</div>
            <nav>
                <ul>
                    <li><a href="farmer-dashboard.php">Dashboard</a></li>
                    <li><a href="harvest-management.php" class="active">Harvest Management</a></li>
                    <li><a href="dispatch-requests.php">Dispatch Requests</a></li>
                    <li><a href="/Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Harvest Management</h1>

            <!-- Add New Harvest Section -->
            <section class="card">
                <h2>Add New Harvest</h2>
                <form id="add-harvest-form">
                    <label for="produce">Produce:</label>
                    <select id="produce" name="produce">
                        <!-- Dynamic options loaded via JS -->
                    </select>

                    <label for="quantity">Quantity (kg):</label>
                    <input type="number" id="quantity" name="quantity" required>

                    <label for="date">Harvest Date:</label>
                    <input type="date" id="date" name="date" required>

                    <button type="submit">Add Harvest</button>
                </form>
            </section>

            <!-- Existing Harvests Section -->
            <section class="card">
                <h2>Your Harvests</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Produce</th>
                            <th>Quantity (kg)</th>
                            <th>Harvest Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="harvest-table">
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
        // Load Produce Options
        document.addEventListener('DOMContentLoaded', () => {
            fetch('fetch-produce.php')
                .then(response => response.json())
                .then(data => {
                    const produceSelect = document.getElementById('produce');
                    data.forEach(produce => {
                        const option = document.createElement('option');
                        option.value = produce.id;
                        option.textContent = produce.name;
                        produceSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Error fetching produce:', err));
        });

        // Add Harvest
        const addHarvestForm = document.getElementById('add-harvest-form');
        addHarvestForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const formData = new FormData(addHarvestForm);

            fetch('add-harvest.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Harvest added successfully!');
                        loadHarvestTable(); // Refresh the table
                        addHarvestForm.reset();
                    } else {
                        alert('Error adding harvest: ' + data.message);
                    }
                })
                .catch(err => console.error('Error adding harvest:', err));
        });

        // Load Harvest Table
        function loadHarvestTable() {
            fetch('fetch-harvests.php')
                .then(response => response.json())
                .then(data => {
                    const harvestTable = document.getElementById('harvest-table');
                    harvestTable.innerHTML = '';
                    data.forEach(harvest => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${harvest.produce}</td>
                            <td>${harvest.quantity}</td>
                            <td>${harvest.date}</td>
                            <td>
                                <button onclick="deleteHarvest(${harvest.id})">Delete</button>
                            </td>
                        `;
                        harvestTable.appendChild(row);
                    });
                })
                .catch(err => console.error('Error loading harvest table:', err));
        }

        // Delete Harvest
        function deleteHarvest(harvestId) {
            if (confirm('Are you sure you want to delete this harvest?')) {
                fetch(`delete-harvest.php?id=${harvestId}`, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Harvest deleted successfully!');
                            loadHarvestTable(); // Refresh the table
                        } else {
                            alert('Error deleting harvest: ' + data.message);
                        }
                    })
                    .catch(err => console.error('Error deleting harvest:', err));
            }
        }

        // Load the table when the page loads
        loadHarvestTable();
    </script>
</body>
</html>
