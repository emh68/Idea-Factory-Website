<?php
session_start();
$conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$age = $_POST['age'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$class = $_POST['class'];

// Validate inputs
$errors = [];

// Check first name
if (empty($fname) || !preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
    $errors[] = "First name is required and can only contain letters and whitespace.";
}

// Check last name
if (empty($lname) || !preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
    $errors[] = "Last name is required and can only contain letters and whitespace.";
}

// Check age
if (empty($age) || !filter_var($age, FILTER_VALIDATE_INT) || $age < 12 || $age > 16) {
    $errors[] = "Age is required and must be a number between 12 and 16.";
}

// Check phone
if (empty($phone) || !preg_match("/^\d{10}$/", $phone)) {
    $errors[] = "Phone number is required and must be 10 digits.";
}

// Check email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

// Check class
if (empty($class)) {
    $errors[] = "Class selection is required.";
}

// If there are any errors, handle them
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    exit; // Stop further processing if there are errors
}

// Sanitize inputs
$fname = $conn->real_escape_string(trim($fname));
$lname = $conn->real_escape_string(trim($lname));
$age = (int)$age; // Cast age to integer
$phone = $conn->real_escape_string(trim($phone));
$email = $conn->real_escape_string(trim($email));
$class = $conn->real_escape_string(trim($class));

// Debugging: Check the data before executing
var_dump($fname, $lname, $age, $phone, $email, $class);

// Prepare and execute the statement
$stmt = $conn->prepare("INSERT INTO registrations (fname, lname, age, phone, email, class) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisss", $fname, $lname, $age, $phone, $email, $class);

if ($stmt->execute()) {
    // Redirect to success page upon successful registration
    header('Location: /success.html');
    exit();

} else {
    echo "Error: " . $stmt->error; // Output error if execution fails
}

$stmt->close();
$conn->close(); // Close the database connection
?>
