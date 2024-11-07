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

// Fetch user info from session
$selectedClass = $_SESSION['selected_class'] ?? 'Unknown Class';
$firstName = $_SESSION['first_name'] ?? 'Student';
$lastName = $_SESSION['last_name'] ?? 'User';
$email = $_SESSION['email'] ?? 'unknown@example.com';

// Step 1: Create a Customer
try {
    $customer = $stripe->customers->create([
        'email' => $email,
        'name' => "$firstName $lastName",
        'metadata' => [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'class' => $selectedClass,
        ],
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating customer: " . $e->getMessage();
    exit;
}

// Step 2: Create Product and Price (if not already created in Stripe dashboard)
try {
    $product = $stripe->products->create([
        'name' => "Registration for $selectedClass",
    ]);

    // Create Price for $200 with recurring billing
    $price = $stripe->prices->create([
        'unit_amount' => 16000, // $200 in cents
        'currency' => 'usd',
        'recurring' => ['interval' => 'month'],
        'product' => $product->id,
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating product or price: " . $e->getMessage();
    exit;
}

// Step 3: Create a Checkout Session for Subscription
try {
    $checkoutSession = $stripe->checkout->sessions->create([
        'customer' => $customer->id,
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $price->id,
            'quantity' => 1,
        ]],
        'mode' => 'subscription',
        'success_url' => 'https://ideafactoryrexburg.com/scripts/php/results.php',
        'cancel_url' => 'https://ideafactoryrexburg.com/cancel.php',
    ]);

    // Redirect to Stripe's Checkout
    header("Location: " . $checkoutSession->url);
    exit();
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating checkout session: " . $e->getMessage();
    exit;
}

// After successful payment, set up a webhook or handle the subscription cancellation manually
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

// Fetch user info from session
$selectedClass = $_SESSION['selected_class'] ?? 'Unknown Class';
$firstName = $_SESSION['first_name'] ?? 'Student';
$lastName = $_SESSION['last_name'] ?? 'User';
$email = $_SESSION['email'] ?? 'unknown@example.com';

// Step 1: Create a Customer
try {
    $customer = $stripe->customers->create([
        'email' => $email,
        'name' => "$firstName $lastName",
        'metadata' => [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'class' => $selectedClass,
        ],
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating customer: " . $e->getMessage();
    exit;
}

// Step 2: Create Product and Price (if not already created in Stripe dashboard)
try {
    $product = $stripe->products->create([
        'name' => "Registration for $selectedClass",
    ]);

    // Create Price for $200 with recurring billing
    $price = $stripe->prices->create([
        'unit_amount' => 20000, // $200 in cents
        'currency' => 'usd',
        'recurring' => ['interval' => 'month'],
        'product' => $product->id,
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating product or price: " . $e->getMessage();
    exit;
}

// Step 3: Create a Checkout Session for Subscription
try {
    // Set the cancel date to two months from now (after 3 payments)
    $cancelAt = strtotime("+2 months");

    $checkoutSession = $stripe->checkout->sessions->create([
        'customer' => $customer->id,
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $price->id,
            'quantity' => 1,
        ]],
        'mode' => 'subscription',
        'subscription_data' => [
            'cancel_at' => $cancelAt,
        ],
        'success_url' => 'https://ideafactoryrexburg.com/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'https://ideafactoryrexburg.com/cancel.php',
    ]);

    // Redirect to Stripe's Checkout
    header("Location: " . $checkoutSession->url);
    exit();
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating checkout session: " . $e->getMessage();
    exit;
}





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

// Fetch user info from session
$selectedClass = $_SESSION['selected_class'] ?? 'Unknown Class';
$firstName = $_SESSION['first_name'] ?? 'Student';
$lastName = $_SESSION['last_name'] ?? 'User';
$email = $_SESSION['email'] ?? 'unknown@example.com';

// Step 1: Create a Customer
try {
    $customer = $stripe->customers->create([
        'email' => $email,
        'name' => "$firstName $lastName",
        'metadata' => [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'class' => $selectedClass,
        ],
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating customer: " . $e->getMessage();
    exit;
}

// Step 2: Create Product and Price (if not already created in Stripe dashboard)
try {
    $product = $stripe->products->create([
        'name' => "Registration for $selectedClass",
    ]);

    $price = $stripe->prices->create([
        'unit_amount' => 20000, // $200 in cents
        'currency' => 'usd',
        'recurring' => ['interval' => 'month'],
        'product' => $product->id,
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating product or price: " . $e->getMessage();
    exit;
}

// Step 3: Create a Checkout Session for Subscription
try {
    $checkoutSession = $stripe->checkout->sessions->create([
        'customer' => $customer->id,
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $price->id,
            'quantity' => 1,
        ]],
        'mode' => 'subscription',
        'success_url' => 'https://ideafactoryrexburg.com/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'https://ideafactoryrexburg.com/cancel.php',
    ]);

    // Redirect to Stripe's Checkout
    header("Location: " . $checkoutSession->url);
    exit();
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error creating checkout session: " . $e->getMessage();
    exit;
}





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
