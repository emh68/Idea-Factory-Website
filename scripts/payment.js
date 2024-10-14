const stripe = Stripe('your-publishable-key');

document.getElementById('payButton').addEventListener('click', async () => {
    const { sessionId } = await fetch('/create-checkout-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            class: new URLSearchParams(window.location.search).get('class'),
            email: new URLSearchParams(window.location.search).get('email')
        })
    }).then(response => response.json());

    stripe.redirectToCheckout({ sessionId });
});
