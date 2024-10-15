// In success.js
const sessionId = new URLSearchParams(window.location.search).get('session_id');

fetch(`/confirm-registration?session_id=${sessionId}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('responseMessage').textContent = data.message;
    })
    .catch(error => {
        console.error('Error confirming registration:', error);
    });
