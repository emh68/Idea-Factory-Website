<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

// Database connection
$conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get session variables
if (!isset($_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['selected_class'])) {
    die("Session variables are not set.");
}

$firstName = $_SESSION['first_name'];
$lastName = $_SESSION['last_name'];
$selectedClass = $_SESSION['selected_class'];

// Fetch the session ID from the URL
$sessionId = $_GET['session_id'] ?? '';
if (empty($sessionId)) {
    die("Session ID is missing.");
}

// Confirm the session with Stripe
$dotenvPath = '/home/njhuystvdlws/public_html/scripts/php/.env';
$dotenv = Dotenv\Dotenv::createImmutable(dirname($dotenvPath));
$dotenv->load();

$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;
if (!$stripeSecretKey) {
    die('Stripe secret key is not set or is empty.');
}

$stripe = new \Stripe\StripeClient($stripeSecretKey);
$session = $stripe->checkout->sessions->retrieve($sessionId, []);

if (!$session) {
    die("Failed to retrieve the Stripe session.");
}

// Check the payment status
if ($session->payment_status === 'paid') {
    // Update the registration status in the database
    $updateQuery = "UPDATE registrations SET status = 'Enrolled' WHERE fname = ? AND lname = ? AND class = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $firstName, $lastName, $selectedClass);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Payment successful. You are now enrolled!';
    } else {
        $_SESSION['message'] = 'Payment confirmed, but enrollment failed. Error: ' . $stmt->error;
    }
} else {
    $_SESSION['message'] = 'Payment was not successful.';
}

// Redirect to results page
header("Location: /scripts/php/results.php");
exit();
?>
