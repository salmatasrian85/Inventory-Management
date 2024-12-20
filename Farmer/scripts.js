// Redirect to a different page
function redirectTo(page) {
    window.location.href = page;
}

// Load farm details dynamically
document.addEventListener('DOMContentLoaded', () => {
    fetchFarmDetails();
});

function fetchFarmDetails() {
    const farmDetailsDiv = document.getElementById('farm-details');

    fetch('get-farm-details.php') // Backend PHP script to fetch farm details
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const farm = data.farm;
                farmDetailsDiv.innerHTML = `
                    <p><strong>Farm Name:</strong> ${farm.name}</p>
                    <p><strong>Location:</strong> ${farm.location}</p>
                    <p><strong>Size:</strong> ${farm.size} hectares</p>
                    <p><strong>Type:</strong> ${farm.type}</p>
                `;
            } else {
                farmDetailsDiv.innerHTML = `<p>${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error fetching farm details:', error);
            farmDetailsDiv.innerHTML = `<p>Unable to load farm details.</p>`;
        });
}
