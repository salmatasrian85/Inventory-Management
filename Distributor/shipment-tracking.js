document.addEventListener('DOMContentLoaded', () => {
    loadShipments();
});

// Load shipments dynamically
function loadShipments() {
    const shipmentTable = document.getElementById('shipment-table');

    fetch('get-shipments.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                shipmentTable.innerHTML = ''; // Clear table
                data.shipments.forEach(shipment => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${shipment.tracking_number}</td>
                        <td>${shipment.order_id}</td>
                        <td>${shipment.vehicle}</td>
                        <td>${shipment.shipment_date}</td>
                        <td>${shipment.eta}</td>
                        <td class="${shipment.status.toLowerCase()}">${shipment.status}</td>
                    `;
                    shipmentTable.appendChild(row);
                });
            } else {
                shipmentTable.innerHTML = `<tr><td colspan="6">${data.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error loading shipments:', error));
}
