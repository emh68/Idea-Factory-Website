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

// Current timestamp for created_at
$timestamp = date('Y-m-d H:i:s');

// Check the number of current registrations for the selected class
$query = "SELECT COUNT(*) AS count FROM registrations WHERE class = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $class);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_count = $row['count'];

// If there are less than 10 registrations, add to the registrations table
if ($current_count < 10) {
    // Insert user information with "Pending" status
    $status = 'Pending';
    $insert_query = "INSERT INTO registrations (fname, lname, age, phone, email, class, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ssisssss", $fname, $lname, $age, $phone, $email, $class, $status, $timestamp);

    if ($insert_stmt->execute()) {
        // Redirect to results.html with a success message
        header("Location: /results.html?message=" . urlencode("You are successfully registered for $class."));
        exit();
    } else {
        echo "Error in registration: " . $insert_stmt->error;
    }
} else {
    // If 10 or more registrations, add to waiting list
    $wait_query = "INSERT INTO waiting_list (fname, lname, age, phone, email, class, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $wait_stmt = $conn->prepare($wait_query);
    $wait_stmt->bind_param("ssissss", $fname, $lname, $age, $phone, $email, $class, $timestamp);

    if ($wait_stmt->execute()) {
        // Redirect to results.html with a waiting list message
        header("Location: /results.html?message=" . urlencode("The class is full. You have been added to the waiting list."));
        exit();
    } else {
        echo "Error adding to waiting list: " . $wait_stmt->error;
    }
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

