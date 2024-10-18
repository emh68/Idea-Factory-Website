<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

// Use the absolute path to your .env file
$dotenvPath = '/home/njhuystvdlws/public_html/scripts/php/.env';

// Debug: Verify the path and directory contents
var_dump(__DIR__);
var_dump(scandir(dirname($dotenvPath))); 

if (!file_exists($dotenvPath)) {
    die('The .env file does not exist at ' . $dotenvPath);
}

// Load .env file from the correct directory
$dotenv = Dotenv\Dotenv::createImmutable(dirname($dotenvPath)); // Load from the directory of .env
try {
    $dotenv->load();
} catch (Exception $e) {
    die('Failed to load .env file: ' . $e->getMessage());
}

// Check all loaded environment variables
print_r($_ENV);

// Retrieve the Stripe secret key from the environment variable
$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
var_dump($stripeSecretKey); // Debug line to see the value of the key

if (!$stripeSecretKey) {
    die('Stripe secret key is not set or is empty.');
}

// Initialize Stripe Client
$stripe = new \Stripe\StripeClient($stripeSecretKey);

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
