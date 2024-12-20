document.addEventListener('DOMContentLoaded', () => {
    loadProduceOptions();
    loadHarvests();

    const harvestForm = document.getElementById('add-harvest-form');
    harvestForm.addEventListener('submit', addHarvest);
});

// Load produce options dynamically
function loadProduceOptions() {
    const produceDropdown = document.getElementById('produce');

    fetch('get-produce.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.produce.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    produceDropdown.appendChild(option);
                });
            } else {
                alert('Failed to load produce options.');
            }
        })
        .catch(error => console.error('Error loading produce options:', error));
}

// Load existing harvests dynamically
function loadHarvests() {
    const harvestTable = document.getElementById('harvest-table');

    fetch('get-harvests.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                harvestTable.innerHTML = ''; // Clear table
                data.harvests.forEach(harvest => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${harvest.produce_name}</td>
                        <td>${harvest.quantity}</td>
                        <td>${harvest.date}</td>
                        <td>
                            <button onclick="deleteHarvest(${harvest.id})">Delete</button>
                        </td>
                    `;
                    harvestTable.appendChild(row);
                });
            } else {
                harvestTable.innerHTML = `<tr><td colspan="4">${data.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error loading harvests:', error));
}

// Add a new harvest
function addHarvest(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    fetch('add-harvest.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Harvest added successfully!');
                loadHarvests(); // Reload harvests
                event.target.reset(); // Clear form
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error adding harvest:', error));
}

// Delete a harvest
function deleteHarvest(harvestId) {
    if (confirm('Are you sure you want to delete this harvest?')) {
        fetch(`delete-harvest.php?id=${harvestId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Harvest deleted successfully!');
                    loadHarvests(); // Reload harvests
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error deleting harvest:', error));
    }
}
