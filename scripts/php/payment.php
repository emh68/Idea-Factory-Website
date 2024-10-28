<?php
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

// Fetch class and user info from session (set by registration.php)
$selectedClass = $_SESSION['selected_class'] ?? 'Unknown Class';
$firstName = $_SESSION['first_name'] ?? 'Student';
$lastName = $_SESSION['last_name'] ?? 'User';
$age = $_SESSION['age'] ?? '0';
$registrationId = $_SESSION['registrationId'] ?? 'Unknown ID';
$email = $_SESSION['email'] ?? 'unknown@example.com';

// Step 1: Create a customer on Stripe
$customer = $stripe->customers->create([
    'email' => $email,
    'name' => "$firstName $lastName",
    'metadata' => [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'age' => $age,
        'registration_id' => $registrationId,
        'class' => $selectedClass,
    ]
]);

// Step 2: Create the product and price (only if they do not already exist)
$product = $stripe->products->create([
    'name' => "Registration for $selectedClass",
]);

$price = $stripe->prices->create([
    'unit_amount' => 20000, // $200.00 in cents
    'currency' => 'usd',
    'recurring' => ['interval' => 'month'],
    'product' => $product->id,
]);

// Step 3: Create the subscription with the customer's ID
$subscription = $stripe->subscriptions->create([
    'customer' => $customer->id,
    'items' => [[
        'price' => $price->id,
        'quantity' => 1,
    ]],
    'payment_behavior' => 'default_incomplete',
    'trial_period_days' => 0, // Charge immediately, no trial
    'metadata' => [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'age' => $age,
        'class' => $selectedClass,
        'email' => $email,
        'registration_id' => $registrationId,
    ],
    'cancel_at' => strtotime("+2 months"), // Ends subscription after three payments
]);

// Check if the subscription and latest invoice are valid
if (isset($subscription->latest_invoice) && is_object($subscription->latest_invoice)) {
    $invoice = $stripe->invoices->retrieve($subscription->latest_invoice);
    $hostedInvoiceUrl = $invoice->hosted_invoice_url ?? null;

    if ($hostedInvoiceUrl) {
        header('Location: ' . $hostedInvoiceUrl);
        exit();
    } else {
        echo "Invoice URL not available. Please contact support.";
    }
} else {
    echo "Subscription or invoice creation failed. Please contact support.";
}
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
