<?php
// Include your database connection code here
require 'db_connection.php'; // Adjust this to your actual database connection file

// Start the session
session_start();

// Get the session ID from the URL (make sure it's passed correctly)
$session_id = $_GET['session_id'];

// Query the database to confirm the registration
$query = "SELECT * FROM registrations WHERE email = ?"; // Use email to confirm the registration
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['email']); // Assuming email is stored in session after successful payment
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $registration = $result->fetch_assoc();
    $message = "Payment successful! Your registration for " . htmlspecialchars($registration['class']) . " is now confirmed.";
    // Optional: You might want to update the registration status here as well
} else {
    $message = "Unable to confirm registration. Please contact support.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
</head>
<body>
    <h1>Registration Successful!</h1>
    <p><?php echo $message; ?></p>
    <a href="index.html">Return to Homepage</a>
</body>
</html>
