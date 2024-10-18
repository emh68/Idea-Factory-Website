<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$dotenvPath = '/home/njhuystvdlws/public_html/scripts/php/.env';

// Load .env file from the correct directory
$dotenv = Dotenv\Dotenv::createImmutable(dirname($dotenvPath));
$dotenv->load();

// Retrieve the Stripe secret key from the environment variable
$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null; // Use $_ENV instead of getenv()

var_dump($stripeSecretKey); // Debug line to see the value of the key

if (!$stripeSecretKey) {
    die('Stripe secret key is not set or is empty.');
}

// Initialize Stripe Client
$stripe = new \Stripe\StripeClient($stripeSecretKey);

// Fetch class and email from session
$selectedClass = $_SESSION['selected_class'] ?? 'Unknown Class'; // Default value if not set
$email = $_SESSION['email'] ?? 'unknown@example.com'; // Default value if not set
$firstName = $_SESSION['first_name'] ?? 'Student';

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
    'metadata' => [
        'email' => $email,
        'class' => $selectedClass,
        'first_name' => $firstName,
    ],
]);
// After creating the Stripe Checkout session
if ($session) {
    // Store the message in the session based on payment success
    $_SESSION['message'] = 'registered'; // Or 'waiting_list' based on your logic
    $_SESSION['first_name'] = $firstName;
    $_SESSION['selected_class'] = $selectedClass;

    // Redirect to the Stripe Checkout page
    header('Location: ' . $session->url);
    exit();
}
?>
