const stripe = require('stripe')('your-secret-key');

app.post('/create-checkout-session', async (req, res) => {
    const { class: selectedClass, email } = req.body;

    try {
        const session = await stripe.checkout.sessions.create({
            payment_method_types: ['card'],
            line_items: [{
                price_data: {
                    currency: 'usd',
                    product_data: {
                        name: `Registration for ${selectedClass}`,
                    },
                    unit_amount: 5000, // e.g., $50.00 registration fee
                },
                quantity: 1,
            }],
            mode: 'payment',
            success_url: `http://yourwebsite.com/success?session_id={CHECKOUT_SESSION_ID}`,
            cancel_url: 'http://yourwebsite.com/cancel',
            metadata: {
                email: email,
                class: selectedClass
            }
        });

        res.json({ sessionId: session.id });
    } catch (error) {
        console.error('Error creating checkout session:', error);
        res.status(500).json({ message: 'Payment setup failed' });
    }
});

app.get('/confirm-registration', async (req, res) => {
    const sessionId = req.query.session_id;

    try {
        const session = await stripe.checkout.sessions.retrieve(sessionId);
        const email = session.metadata.email;
        const selectedClass = session.metadata.class;

        if (session.payment_status === 'paid') {
            await client.connect();
            const database = client.db('yourDatabase');
            const registrations = database.collection('registrations');

            // Update the user’s registration to "confirmed"
            await registrations.updateOne(
                { email: email, class: selectedClass },
                { $set: { status: 'confirmed' } }
            );

            res.json({ message: 'Payment successful! Your registration is now confirmed.' });
        } else {
            res.json({ message: 'Payment was not completed. Please try again.' });
        }
    } catch (error) {
        console.error('Error confirming registration:', error);
        res.status(500).json({ message: 'Unable to confirm registration' });
    } finally {
        await client.close();
    }
});
