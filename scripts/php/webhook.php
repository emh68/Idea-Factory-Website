<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$endpoint_secret = $_ENV['STRIPE_SIGNING_SECRET'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    error_log('Invalid payload: ' . $e->getMessage());
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    error_log('Invalid signature: ' . $e->getMessage());
    exit();
}

if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;

    $firstName = $session->metadata->first_name ?? null;
    $class = $session->metadata->class ?? null;

    file_put_contents('webhook_log.txt', "[" . date('Y-m-d H:i:s') . "] Received event: " . json_encode($event, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
    error_log("Processing checkout.session.completed for: firstName - {$firstName}, class - {$class}");

    $conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        exit();
    }

    if (!$firstName || !$class) {
        error_log("Metadata missing or empty: firstName - {$firstName}, class - {$class}");
    } else {
        $update_query = "UPDATE registrations SET status = 'Registered' WHERE fname = ? AND class = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $firstName, $class);

        if (!$update_stmt->execute()) {
            error_log("Error updating record: " . $update_stmt->error);
        } else {
            error_log("Status updated to 'Registered' for $firstName in $class");
        }

        $update_stmt->close();
    }

    $conn->close();
}

http_response_code(200);
?>





require 'vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set your secret key from the environment variable
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Retrieve the request's body and parse it as JSON
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$endpoint_secret = $_ENV['STRIPE_SIGNING_SECRET'];
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
    $firstName = $session->metadata->first_name ?? null;
    $lastName = $session->metadata->last_name ?? null;
    $age = $session->metadata->age;
    $class = $session->metadata->class ?? null;

    // Log the received event for debugging
    file_put_contents('webhook_log.txt', "[" . date('Y-m-d H:i:s') . "] " . json_encode($event, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

    // Database connection details
    $conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        exit();
    }

    // Prepare and execute the update query
    $update_query = "UPDATE registrations SET status = 'Registered' WHERE fname = ? AND class = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ss", $firstName, $class);
    error_log("Preparing to update: firstName = {$firstName}, class = {$class}");

    if (!$update_stmt->execute()) {
        error_log("Error updating record: " . $update_stmt->error);
    } else {
        error_log("Status updated to 'Registered' for $firstName in $class");
    }

    // Log if any metadata is missing
    if (!$firstName || !$class) {
        error_log("Metadata missing: firstName - {$firstName}, class - {$class}");
    }

    // Close the statement and connection
    $update_stmt->close();
    $conn->close();
}

// Respond with 200 OK to acknowledge receipt of the event
http_response_code(200);





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
    $firstName = $session->metadata->first_name ?? null;
    $lastName = $session->metadata->last_name ?? null;
    $age = $session->metadata->age;
    $class = $session->metadata->class ?? null;

    file_put_contents('webhook_log.txt', json_encode($event, JSON_PRETTY_PRINT), FILE_APPEND);


    // Database connection details
    $conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        exit();
    }

    // // Update the status in the database
    // $update_query = "UPDATE registrations SET status = 'Registered' WHERE fname = ? AND class = ?";
    // $update_stmt = $conn->prepare($update_query);
    // $update_stmt->bind_param("ss", $firstName, $class);
    
    // if (!$update_stmt->execute()) {
    //     error_log("Error updating record: " . $conn->error);
    // }

    $update_query = "UPDATE registrations SET status = 'Registered' WHERE fname = ? AND class = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ss", $firstName, $class);

    if (!$update_stmt->execute()) {
        error_log("Error updating record: " . $update_stmt->error);
    } else {
        error_log("Status updated to 'Registered' for $firstName in $class");
    }


    if (!$firstName || !$class) {
        error_log("Metadata missing: firstName - {$firstName}, class - {$class}");
    }
    
    
    $update_stmt->close();
    $conn->close();
}

http_response_code(200); // Respond with 200 OK to acknowledge receipt of the event

