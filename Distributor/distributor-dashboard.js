document.addEventListener('DOMContentLoaded', () => {
    loadDistributorOverview();
});

// Load distributor overview data dynamically
function loadDistributorOverview() {
    const overviewDiv = document.getElementById('distributor-overview');

    fetch('get-distributor-overview.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const distributor = data.distributor;
                overviewDiv.innerHTML = `
                    <p><strong>Name:</strong> ${distributor.name}</p>
                    <p><strong>Contact:</strong> ${distributor.contact}</p>
                    <p><strong>Location:</strong> ${distributor.location}</p>
                    <p><strong>Total Orders:</strong> ${distributor.total_orders}</p>
                    <p><strong>Pending Shipments:</strong> ${distributor.pending_shipments}</p>
                `;
            } else {
                overviewDiv.innerHTML = `<p>${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error loading distributor overview:', error);
            overviewDiv.innerHTML = `<p>Unable to load distributor details.</p>`;
        });
}

// Redirect to a different page
function redirectTo(page) {
    window.location.href = page;
}
