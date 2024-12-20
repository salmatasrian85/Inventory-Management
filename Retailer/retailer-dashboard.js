document.addEventListener('DOMContentLoaded', () => {
    loadRetailerOverview();
});

// Load retailer overview data dynamically
function loadRetailerOverview() {
    const overviewDiv = document.getElementById('retailer-overview');

    fetch('get-retailer-overview.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const retailer = data.retailer;
                overviewDiv.innerHTML = `
                    <p><strong>Name:</strong> ${retailer.name}</p>
                    <p><strong>Contact:</strong> ${retailer.contact}</p>
                    <p><strong>Total Orders:</strong> ${retailer.total_orders}</p>
                    <p><strong>Pending Deliveries:</strong> ${retailer.pending_deliveries}</p>
                `;
            } else {
                overviewDiv.innerHTML = `<p>${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error loading retailer overview:', error);
            overviewDiv.innerHTML = `<p>Unable to load retailer details.</p>`;
        });
}

// Redirect to a different page
function redirectTo(page) {
    window.location.href = page;
}
