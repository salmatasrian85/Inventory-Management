document.addEventListener('DOMContentLoaded', () => {
    loadWarehouseDetails();
});

// Load warehouse details dynamically
function loadWarehouseDetails() {
    const warehouseDetailsDiv = document.getElementById('warehouse-details');

    fetch('get-warehouse-details.php') // Backend PHP script to fetch warehouse details
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const warehouse = data.warehouse;
                warehouseDetailsDiv.innerHTML = `
                    <p><strong>Warehouse Name:</strong> ${warehouse.name}</p>
                    <p><strong>Location:</strong> ${warehouse.location}</p>
                    <p><strong>Capacity:</strong> ${warehouse.capacity} tons</p>
                    <p><strong>Current Stock:</strong> ${warehouse.current_stock} tons</p>
                `;
            } else {
                warehouseDetailsDiv.innerHTML = `<p>${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error fetching warehouse details:', error);
            warehouseDetailsDiv.innerHTML = `<p>Unable to load warehouse details.</p>`;
        });
}

// Redirect to a different page
function redirectTo(page) {
    window.location.href = page;
}
