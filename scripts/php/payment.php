<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'vendor/autoload.php'; // Include Stripe PHP library

// Initialize Stripe Client with your live secret key
$stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

// Fetch class and email from session
$selectedClass = $_SESSION['selected_class'] ?? 'Unknown Class'; // Default value if not set
$email = $_SESSION['email'] ?? 'unknown@example.com'; // Default value if not set

// Create Stripe Checkout session
$session = $stripe->checkout->sessions->create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => "Registration for $selectedClass",
            ],
            'unit_amount' => 5000, // $50.00 (in cents)
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://ideafactoryrexburg.com/php/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://ideafactoryrexburg.com/classes.html',
]);

header('Location: ' . $session->url);
exit();
?>
