// Function to get URL parameters
function getQueryParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Display the message if it exists
const message = getQueryParameter('message');
if (message) {
    document.getElementById('message').innerText = message;
}