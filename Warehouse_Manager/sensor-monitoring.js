document.addEventListener('DOMContentLoaded', () => {
    loadSensorData();
    setInterval(loadSensorData, 5000); // Refresh data every 5 seconds
});

// Load sensor data dynamically
function loadSensorData() {
    const sensorTable = document.getElementById('sensor-table');

    fetch('get-sensors.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sensorTable.innerHTML = ''; // Clear table
                data.sensors.forEach(sensor => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${sensor.sensor_id}</td>
                        <td>${sensor.zone}</td>
                        <td>${sensor.temperature}</td>
                        <td>${sensor.humidity}</td>
                        <td class="${sensor.status.toLowerCase()}">${sensor.status}</td>
                    `;
                    sensorTable.appendChild(row);
                });
            } else {
                sensorTable.innerHTML = `<tr><td colspan="5">${data.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error loading sensors:', error));
}
