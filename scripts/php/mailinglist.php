<?php
session_start();

// Establish database connection
$conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

// Validate inputs
$errors = [];

if (empty($fname) || !preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
    $errors[] = "First name is required and can only contain letters and whitespace.";
}

if (empty($lname) || !preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
    $errors[] = "Last name is required and can only contain letters and whitespace.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

if (!empty($phone) && !preg_match("/^\d{10}$/", $phone)) {
    $errors[] = "Phone number must be 10 digits.";
}

// Handle errors
if (!empty($errors)) {
    $_SESSION['message'] = implode("<br>", $errors);
    header("Location: /scripts/php/results.php");
    exit();
}

// Sanitize inputs
$fname = $conn->real_escape_string(trim($fname));
$lname = $conn->real_escape_string(trim($lname));
$email = $conn->real_escape_string(trim($email));
$phone = $conn->real_escape_string(trim($phone));

// Insert data into the mailing_list table
$query = "INSERT INTO mailing_list (fname, lname, email, phone) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $fname, $lname, $email, $phone);

if ($stmt->execute()) {
    // After adding to mailing list
    $_SESSION['message_type'] = 'mailing_list';
    $_SESSION['message'] = "You have been successfully added to the mailing list!";
    header("Location: /scripts/php/results.php");
    exit();

} else {
    $_SESSION['message'] = "Error adding to mailing list.";
    header("Location: /scripts/php/results.php");
    exit();
}

// Close connections
$stmt->close();
$conn->close();
?>
