<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// require 'vendor/autoload.php'; // Include Stripe PHP library
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

// // Load .env file if you're using vlucas/phpdotenv
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Corrected to point to the current directory
// $dotenv->load();
// var_dump(getenv('STRIPE_SECRET_KEY')); // Debug line

// $stripeSecretKey = getenv('STRIPE_SECRET_KEY');
// if (!$stripeSecretKey) {
//     die('Stripe secret key is not set or is empty.');
// }

// Specify the path to your .env file
$dotenvPath = __DIR__ . '/../../.env'; 
if (!file_exists($dotenvPath)) {
    die('The .env file does not exist at ' . $dotenvPath);
}

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
try {
    $dotenv->load();
} catch (Exception $e) {
    die('Failed to load .env file: ' . $e->getMessage());
}

// Get the Stripe secret key from the environment variable
$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
var_dump($stripeSecretKey); // Debug line to see the value of the key

if (!$stripeSecretKey) {
    die('Stripe secret key is not set or is empty.');
}

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
    'success_url' => 'http://ideafactoryrexburg.com/scripts/php/results.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://ideafactoryrexburg.com/scripts/php/results.php',
]);

header('Location: ' . $session->url);
exit();
?>
