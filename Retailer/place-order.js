document.addEventListener('DOMContentLoaded', () => {
    loadDistributors();
    loadProducts();

    const orderForm = document.getElementById('place-order-form');
    orderForm.addEventListener('submit', placeOrder);
});

// Load distributor options dynamically
function loadDistributors() {
    const distributorDropdown = document.getElementById('distributor');

    fetch('get-distributors.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.distributors.forEach(distributor => {
                    const option = document.createElement('option');
                    option.value = distributor.id;
                    option.textContent = `${distributor.name} (${distributor.location})`;
                    distributorDropdown.appendChild(option);
                });
            } else {
                alert('Failed to load distributors.');
            }
        })
        .catch(error => console.error('Error loading distributors:', error));
}

// Load product options dynamically
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

// Place a new order
function placeOrder(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    fetch('place-retailer-order.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order placed successfully!');
                event.target.reset(); // Clear form
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error placing order:', error));
}
