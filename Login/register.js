document.getElementById('register-form').addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);

    fetch('process-registration.php', {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert('Registration successful! Redirecting to login...');
                window.location.href = 'login.php';
            } else {
                alert(data.message);
            }
        })
        .catch((error) => console.error('Error registering:', error));
});
