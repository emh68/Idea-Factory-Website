<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Check class availability
$class_query = $conn->prepare("SELECT COUNT(*) FROM registrations WHERE class = ? AND status = 'Enrolled'");
$class_query->bind_param("s", $class);
$class_query->execute();
$class_query->bind_result($enrolled_count);
$class_query->fetch();
$class_query->close();

if ($enrolled_count >= 10) {
    // Insert user into the waiting list
    $stmt_waiting = $conn->prepare("INSERT INTO waiting_list (fname, lname, age, phone, email, class) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_waiting->bind_param("ssisss", $fname, $lname, $age, $phone, $email, $class);

    if ($stmt_waiting->execute()) {
        echo "Class is full. You have been added to the waiting list.";
        $stmt_waiting->close();
        $conn->close(); // Close the database connection
        exit; // Stop further processing if added to waiting list
    }
}

// Insert user information with "Pending" status
$status = 'Pending';
$timestamp = date('Y-m-d H:i:s'); // Current timestamp

$stmt = $conn->prepare("INSERT INTO registrations (fname, lname, age, phone, email, class, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisssss", $fname, $lname, $age, $phone, $email, $class, $status, $timestamp);

if ($stmt->execute()) {
    // Get the user ID of the newly created record
    $user_id = $stmt->insert_id;

    // Redirect to payment page with user ID as a query parameter
    header("Location: /payment.php?user_id=$user_id");
    exit();
} else {
    echo "Error: " . $stmt->error; // Output error if execution fails
}

$stmt->close();
$conn->close(); // Close the database connection
?>




// session_start();
// $conn = new mysqli('107.180.118.249', 'EliHansen', 'Bri@rwood2()', 'idea_factory');

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $fname = $_POST['fname'];
// $lname = $_POST['lname'];
// $age = $_POST['age'];
// $phone = $_POST['phone'];
// $email = $_POST['email'];
// $class = $_POST['class'];

// // Validate inputs
// $errors = [];

// // Check first name
// if (empty($fname) || !preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
//     $errors[] = "First name is required and can only contain letters and whitespace.";
// }

// // Check last name
// if (empty($lname) || !preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
//     $errors[] = "Last name is required and can only contain letters and whitespace.";
// }

// // Check age
// if (empty($age) || !filter_var($age, FILTER_VALIDATE_INT) || $age < 12 || $age > 16) {
//     $errors[] = "Age is required and must be a number between 12 and 16.";
// }

// // Check phone
// if (empty($phone) || !preg_match("/^\d{10}$/", $phone)) {
//     $errors[] = "Phone number is required and must be 10 digits.";
// }

// // Check email
// if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     $errors[] = "A valid email address is required.";
// }

// // Check class
// if (empty($class)) {
//     $errors[] = "Class selection is required.";
// }

// // If there are any errors, handle them
// if (!empty($errors)) {
//     foreach ($errors as $error) {
//         echo "<p>$error</p>";
//     }
//     exit; // Stop further processing if there are errors
// }

// // Sanitize inputs
// $fname = $conn->real_escape_string(trim($fname));
// $lname = $conn->real_escape_string(trim($lname));
// $age = (int)$age; // Cast age to integer
// $phone = $conn->real_escape_string(trim($phone));
// $email = $conn->real_escape_string(trim($email));
// $class = $conn->real_escape_string(trim($class));

// // Debugging: Check the data before executing
// var_dump($fname, $lname, $age, $phone, $email, $class);

// // Prepare and execute the statement
// $stmt = $conn->prepare("INSERT INTO registrations (fname, lname, age, phone, email, class) VALUES (?, ?, ?, ?, ?, ?)");
// $stmt->bind_param("ssisss", $fname, $lname, $age, $phone, $email, $class);

// if ($stmt->execute()) {
//     // Redirect to success page upon successful registration
//     header('Location: /success.html');
//     exit();

// } else {
//     echo "Error: " . $stmt->error; // Output error if execution fails
// }

// $stmt->close();
// $conn->close(); // Close the database connection

