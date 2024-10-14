document.getElementById('registrationForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = {
        fname: document.getElementById('fname').value,
        lname: document.getElementById('lname').value,
        age: parseInt(document.getElementById('age').value),
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        class: document.getElementById('class').value
    };

    // Validate age before sending
    if (formData.age < 12 || formData.age > 16) {
        document.getElementById('responseMessage').textContent = 'You must be between 12 and 16 years old to register.';
        return;
    }

    // Send the form data to the server
    fetch('/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('responseMessage').textContent = data.message;

            // Redirect to payment.html with query parameters
            if (data.success) {
                const params = new URLSearchParams(formData).toString();
                window.location.href = `payment.html?${params}`;
            }
        })
        .catch(error => {
            document.getElementById('responseMessage').textContent = 'An error occurred. Please try again.';
            console.error('Error:', error);
        });
});
