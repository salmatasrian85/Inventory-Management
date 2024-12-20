document.addEventListener('DOMContentLoaded', () => {
    loadProductOptions();
    loadWarehouseOptions();
    loadDispatchRequests();

    const dispatchForm = document.getElementById('dispatch-form');
    dispatchForm.addEventListener('submit', createDispatchRequest);
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

// Load warehouse options dynamically
function loadWarehouseOptions() {
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

// Load existing dispatch requests
function loadDispatchRequests() {
    const dispatchTable = document.getElementById('dispatch-table');

    fetch('get-dispatch-requests.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                dispatchTable.innerHTML = ''; // Clear table
                data.requests.forEach(request => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${request.product_name}</td>
                        <td>${request.quantity}</td>
                        <td>${request.warehouse_name}</td>
                        <td>${request.request_date}</td>
                        <td>${request.status}</td>
                    `;
                    dispatchTable.appendChild(row);
                });
            } else {
                dispatchTable.innerHTML = `<tr><td colspan="5">${data.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error loading dispatch requests:', error));
}

// Create a new dispatch request
function createDispatchRequest(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    fetch('create-dispatch-request.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Dispatch request created successfully!');
                loadDispatchRequests(); // Reload dispatch requests
                event.target.reset(); // Clear form
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error creating dispatch request:', error));
}
