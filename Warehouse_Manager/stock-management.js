document.addEventListener('DOMContentLoaded', () => {
    loadProductOptions();
    loadStock();

    const stockForm = document.getElementById('add-stock-form');
    stockForm.addEventListener('submit', addStock);
});

// Load product options dynamically
function loadProductOptions() {
    const productDropdown = document.getElementById('product');

    fetch('get-products.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    productDropdown.appendChild(option);
                });
            } else {
                alert('Failed to load products.');
            }
        })
        .catch(error => console.error('Error loading products:', error));
}

// Load current stock dynamically
function loadStock() {
    const stockTable = document.getElementById('stock-table');

    fetch('get-stock.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                stockTable.innerHTML = ''; // Clear table
                data.stock.forEach(item => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>
                            <button onclick="removeStock(${item.id})">Remove</button>
                        </td>
                    `;
                    stockTable.appendChild(row);
                });
            } else {
                stockTable.innerHTML = `<tr><td colspan="3">${data.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error loading stock:', error));
}

// Add stock
function addStock(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    fetch('add-stock.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Stock added successfully!');
                loadStock(); // Reload stock table
                event.target.reset(); // Clear form
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error adding stock:', error));
}

// Remove stock
function removeStock(stockId) {
    if (confirm('Are you sure you want to remove this stock?')) {
        fetch(`remove-stock.php?id=${stockId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Stock removed successfully!');
                    loadStock(); // Reload stock table
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error removing stock:', error));
    }
}
