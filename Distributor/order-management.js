document.addEventListener('DOMContentLoaded', () => {
    loadWarehouses();
    loadProducts();
    loadOrders();

    const orderForm = document.getElementById('place-order-form');
    orderForm.addEventListener('submit', placeOrder);
});

// Load warehouses dynamically
function loadWarehouses() {
    const warehouseDropdown = document.getElementById('warehouse');

    fetch('get-warehouses.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.warehouses.forEach(warehouse => {
                    const option = document.createElement('option');
                    option.value = warehouse.id;
                    option.textContent = `${warehouse.name} (${warehouse.location})`;
                    warehouseDropdown.appendChild(option);
                });
            } else {
                alert('Failed to load warehouses.');
            }
        })
        .catch(error => console.error('Error loading warehouses:', error));
}

// Load products dynamically
function loadProducts() {
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

// Load existing orders dynamically
function loadOrders() {
    const orderTable = document.getElementById('order-table');

    fetch('get-orders.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                orderTable.innerHTML = ''; // Clear table
                data.orders.forEach(order => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${order.id}</td>
                        <td>${order.warehouse}</td>
                        <td>${order.product}</td>
                        <td>${order.quantity}</td>
                        <td class="${order.status.toLowerCase()}">${order.status}</td>
                        <td>
                            <button onclick="deleteOrder(${order.id})">Delete</button>
                        </td>
                    `;
                    orderTable.appendChild(row);
                });
            } else {
                orderTable.innerHTML = `<tr><td colspan="6">${data.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error loading orders:', error));
}

// Place a new order
function placeOrder(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    fetch('place-order.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order placed successfully!');
                loadOrders(); // Reload orders table
                event.target.reset(); // Clear form
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error placing order:', error));
}

// Delete an order
function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order?')) {
        fetch(`delete-order.php?id=${orderId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order deleted successfully!');
                    loadOrders(); // Reload orders table
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error deleting order:', error));
    }
}
