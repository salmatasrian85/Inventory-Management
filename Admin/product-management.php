<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
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
                    <li><a href="product-management.php" class="active">Product Management</a></li>
                    <li><a href="vehicle-management.php">Vehicle Management</a></li>
                    <li><a href="logs-reports.php">Logs & Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>Product Management</h1>

            <!-- Add New Product Section -->
            <section class="card">
                <h2>Add New Product</h2>
                <form id="add-product-form">
                    <label for="produce_name">Product Name:</label>
                    <input type="text" id="produce_name" name="produce_name" required>

                    <label for="season_of_produce">Season:</label>
                    <input type="text" id="season_of_produce" name="season_of_produce" required>

                    <label for="produce_type">Type:</label>
                    <input type="text" id="produce_type" name="produce_type" required>

                    <label for="usability_duration">Usability Duration (days):</label>
                    <input type="number" id="usability_duration" name="usability_duration" required>

                    <label for="min_temp">Min Temperature (°C):</label>
                    <input type="number" step="0.1" id="min_temp" name="min_temp" required>

                    <label for="max_temp">Max Temperature (°C):</label>
                    <input type="number" step="0.1" id="max_temp" name="max_temp" required>

                    <label for="optimum_humidity">Optimum Humidity (%):</label>
                    <input type="number" step="0.1" id="optimum_humidity" name="optimum_humidity" required>

                    <button type="submit" class="btn-primary">Add Product</button>
                </form>
            </section>

            <!-- Product List Section -->
            <section class="card">
                <h2>All Products</h2>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Season</th>
                            <th>Type</th>
                            <th>Usability Duration</th>
                            <th>Temperature Range (°C)</th>
                            <th>Optimum Humidity (%)</th>
                        </tr>
                    </thead>
                    <tbody id="product-table">
                        <!-- Dynamic rows populated via JS -->
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
        // Load products dynamically
        function loadProducts() {
            fetch('fetch-products.php')
                .then(response => response.json())
                .then(data => {
                    const productTable = document.getElementById('product-table');
                    productTable.innerHTML = '';

                    if (data.length === 0) {
                        productTable.innerHTML = '<tr><td colspan="7">No products found</td></tr>';
                        return;
                    }

                    data.forEach(product => {
                        const row = `
                            <tr>
                                <td>${product.Product_ID}</td>
                                <td>${product.Produce_Name}</td>
                                <td>${product.Season_Of_Produce}</td>
                                <td>${product.Produce_Type}</td>
                                <td>${product.Usability_Duration} days</td>
                                <td>${product.Min_Temp}°C to ${product.Max_Temp}°C</td>
                                <td>${product.Optimum_Humidity}%</td>
                            </tr>
                        `;
                        productTable.innerHTML += row;
                    });
                })
                .catch(error => console.error('Error loading products:', error));
        }

        // Handle Add Product Form Submission
        document.getElementById('add-product-form').addEventListener('submit', (event) => {
            event.preventDefault();

            const formData = new FormData(event.target);
            fetch('add-product.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added successfully!');
                        loadProducts(); // Reload products
                        event.target.reset(); // Reset the form
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error adding product:', error));
        });

        // Load products on page load
        document.addEventListener('DOMContentLoaded', loadProducts);
    </script>
</body>
</html>
