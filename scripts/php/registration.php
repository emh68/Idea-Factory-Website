<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Establish database connection
$conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$age = $_POST['age'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$class = $_POST['class'];

// Validate inputs
$errors = [];

// Validate first name
if (empty($fname) || !preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
    $errors[] = "First name is required and can only contain letters and whitespace.";
}

// Validate last name
if (empty($lname) || !preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
    $errors[] = "Last name is required and can only contain letters and whitespace.";
}

// Validate age
if (empty($age) || !filter_var($age, FILTER_VALIDATE_INT) || $age < 12 || $age > 16) {
    $errors[] = "Age is required and must be a number between 12 and 16.";
}

// Validate phone
if (empty($phone) || !preg_match("/^\d{10}$/", $phone)) {
    $errors[] = "Phone number is required and must be 10 digits.";
}

// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

// Validate class selection
if (empty($class)) {
    $errors[] = "Class selection is required.";
}

// Handle errors
if (!empty($errors)) {
    $_SESSION['message'] = implode("<br>", $errors);
    // header("Location: /results.html"); need do adjust
    exit();
}

// Sanitize inputs
$fname = $conn->real_escape_string(trim($fname));
$lname = $conn->real_escape_string(trim($lname));
$age = (int)$age; // Cast age to integer
$phone = $conn->real_escape_string(trim($phone));
$email = $conn->real_escape_string(trim($email));
$class = $conn->real_escape_string(trim($class));

// Check the number of current registrations for the selected class
$query = "SELECT COUNT(*) AS count FROM registrations WHERE class = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $class);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_count = $row['count'];

// Current timestamp for created_at
$timestamp = date('Y-m-d H:i:s');

// Registration logic
if ($current_count < 10) {
    // Insert user information with "Pending" status
    $status = 'Pending';
    $insert_query = "INSERT INTO registrations (fname, lname, age, phone, email, class, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ssisssss", $fname, $lname, $age, $phone, $email, $class, $status, $timestamp);

    if ($insert_stmt->execute()) {
        // Store details in session
        $_SESSION['selected_class'] = $class;
        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $fname;
        $_SESSION['last_name'] = $lname;
        $_SESSION['age'] = $age;

        // Redirect to payment page
        header("Location: /scripts/php/payment.php");
        exit();
    } else {
        $_SESSION['message'] = "Error in registration.";
        header("Location: /scripts/php/results.php");
        exit();
    }
} else {
    // Add to waiting list
    $wait_query = "INSERT INTO waiting_list (fname, lname, age, phone, email, class, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $wait_stmt = $conn->prepare($wait_query);
    $wait_stmt->bind_param("ssissss", $fname, $lname, $age, $phone, $email, $class, $timestamp);

    if ($wait_stmt->execute()) {
        $_SESSION['message'] = "The class is full. You have been added to the waiting list.";
        header("Location: /scripts/php/results.php");
        exit();
    } else {
        $_SESSION['message'] = "Error adding to waiting list.";
        header("Location: /scripts/php/results.php");
        exit();
    }
}

// Close connections
$stmt->close();
$conn->close();
?>