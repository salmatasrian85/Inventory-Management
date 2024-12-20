document.addEventListener('DOMContentLoaded', () => {
    loadDeliveries();
});

// Load deliveries dynamically
function loadDeliveries() {
    const deliveriesTable = document.getElementById('deliveries-table');

    fetch('get-deliveries.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                deliveriesTable.innerHTML = ''; // Clear table
                data.deliveries.forEach(delivery => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${delivery.order_id}</td>
                        <td>${delivery.product}</td>
                        <td>${delivery.quantity}</td>
                        <td>${delivery.location}</td>
                        <td class="${delivery.status.toLowerCase()}">${delivery.status}</td>
                    `;
                    deliveriesTable.appendChild(row);
                });
            } else {
                deliveriesTable.innerHTML = `<tr><td colspan="5">${data.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error loading deliveries:', error));
}
