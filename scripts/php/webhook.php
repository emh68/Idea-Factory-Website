<?php
require 'vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set your secret key from the environment variable
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Retrieve the request's body and parse it as JSON
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$endpoint_secret = $_ENV['STRIPE_SIGNING_SECRET']; // Use the signing secret from the .env file
$event = null;

try {
    // Verify the webhook signature
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    error_log('Invalid payload: ' . $e->getMessage());
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    error_log('Invalid signature: ' . $e->getMessage());
    exit();
}

// Handle the successful payment event
if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;

    // Retrieve the metadata from the session
    $firstName = $session->metadata->first_name;
    $lastName = $session->metadata->last_name;
    $age = $session->metadata->age;
    $class = $session->metadata->class;

    // Database connection details
    $conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        exit();
    }

    // Update the status in the database
    $update_query = "UPDATE registrations SET status = 'Registered' WHERE fname = ? AND class = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ss", $firstName, $class);
    
    if (!$update_stmt->execute()) {
        error_log("Error updating record: " . $conn->error);
    }
    
    $update_stmt->close();
    $conn->close();
}

http_response_code(200); // Respond with 200 OK to acknowledge receipt of the event
?>
