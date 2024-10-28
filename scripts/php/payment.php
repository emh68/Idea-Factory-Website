<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'; // Stripe autoload

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session;

Stripe::setApiKey('your_secret_key');

// Get customer information from the form or session (passed from registration.php)
$class_name = $_POST['class_name'];
$user_name = $_POST['user_name'];
$email = $_POST['email'];

// Check if customer exists in Stripe to avoid duplicate creation
$existing_customer = null;
$customers = \Stripe\Customer::all(['email' => $email, 'limit' => 1]);

if (!empty($customers->data)) {
    $existing_customer = $customers->data[0];
}

// If the customer does not exist, create a new customer
if (!$existing_customer) {
    $customer = Customer::create([
        'email' => $email,
        'name' => $user_name,
    ]);
} else {
    $customer = $existing_customer;
}

// Create a Stripe Checkout session for the subscription
$checkout_session = Session::create([
    'customer' => $customer->id, // Link to the Stripe Customer
    'payment_method_types' => ['card'],
    'mode' => 'subscription',
    'line_items' => [[
        'price' => 'price_1NhJmLH5tgvpXqC7P6p1QwL2', // Replace with your subscription price ID
        'quantity' => 1,
    ]],
    'success_url' => 'https://yourdomain.com/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'https://yourdomain.com/cancel.html',
    'metadata' => [
        'class_name' => $class_name,
        'user_name' => $user_name,
        'email' => $email,
    ]
]);

// Redirect to Stripe checkout
header("Location: " . $checkout_session->url);
exit;
?>







ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$dotenvPath = '/home/njhuystvdlws/public_html/scripts/php/.env';
$dotenv = Dotenv\Dotenv::createImmutable(dirname($dotenvPath));
$dotenv->load();

$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;
if (!$stripeSecretKey) {
    die('Stripe secret key is not set or is empty.');
}

$stripe = new \Stripe\StripeClient($stripeSecretKey);

// Fetch class and name from session
$selectedClass = $_SESSION['selected_class'] ?? 'Unknown Class';
$firstName = $_SESSION['first_name'] ?? 'Student';
$lastName = $_SESSION['last_name'] ?? 'User'; // Assuming last name is stored
$age = $_SESSION['age'] ?? '0';
$registrationId = $_SESSION['registrationId'] ?? 'Unknown ID';
$email = $_SESSION['email'] ?? 'unknown@example.com'; // Ensure this variable is fetched

// Create Stripe Checkout session
$session = $stripe->checkout->sessions->create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => "Registration for $selectedClass",
            ],
            'unit_amount' => 20000, // $200.00 (in cents)
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://ideafactoryrexburg.com/scripts/php/results.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://ideafactoryrexburg.com/scripts/php/results.php',
    'metadata' => [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'age' => $age,
        'class' => $selectedClass,
        'email' => $email,
        'registration_id' => $registrationId, // Unique identifier for registration
    ],
]);

if ($session) {
    $_SESSION['message'] = 'registered';
    $_SESSION['first_name'] = $firstName;
    $_SESSION['last_name'] = $lastName;
    $_SESSION['age'] = $age;
    $_SESSION['selected_class'] = $selectedClass;

    header('Location: ' . $session->url);
    exit();
}
