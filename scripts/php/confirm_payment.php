<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

// Database connection
$conn = new mysqli('your_host', 'your_username', 'your_password', 'your_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get session variables
$firstName = $_SESSION['first_name'];
$lastName = $_SESSION['last_name'];
$selectedClass = $_SESSION['selected_class'];

// Fetch the session ID from the URL
$sessionId = $_GET['session_id'] ?? '';

// Confirm the session with Stripe
$stripe = new \Stripe\StripeClient('your_stripe_secret_key');
$session = $stripe->checkout->sessions->retrieve($sessionId, []);

// Check the payment status
if ($session->payment_status === 'paid') {
    // Update the registration status in the database
    $updateQuery = "UPDATE registrations SET status = 'Enrolled' WHERE fname = ? AND lname = ? AND class = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $firstName, $lastName, $selectedClass);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Payment successful. You are now enrolled!';
    } else {
        $_SESSION['message'] = 'Payment confirmed, but enrollment failed.';
    }
} else {
    $_SESSION['message'] = 'Payment was not successful.';
}

// Redirect to results page
header("Location: /scripts/php/results.php");
exit();
?>