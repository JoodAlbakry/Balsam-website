<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Create connection with Database
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "blsm_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$fullname = $_POST['fullname'] ?? '';
$phonenumber = $_POST['phonenumber'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';
$status = $_POST['status'] ?? '';
$stateVal = 0; // Default value

// Validation
if (strlen($fullname) > 50 || strlen($username) > 50 || strlen($password) < 8 || 
    !filter_var($email, FILTER_VALIDATE_EMAIL) || 
    !preg_match('/^05[0-9]{8}$/', $phonenumber)) {
    echo "<script>alert('Invalid input data. Please try again.');</script>";
    echo "<script>window.history.back();</script>";
    exit();
}

// Insert data into the database
$sql = "INSERT INTO users (FullName, PhoneNumber, Username, Password, Email, Status, StateVal) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $fullname, $phonenumber, $username, $password, $email, $status, $stateVal);

if ($stmt->execute()) {
    // Save user data in session
    $_SESSION['user'] = [
        'fullname' => $fullname,
        'username' => $username,
        'status' => $status,
        'stateVal' => $stateVal,
    ];

    // Set a cookie for the username (valid for 30 days)
    setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/"); // Expires in 30 days
    
    header("Location: home.php");
    exit();
} else {
    echo "<script>alert('Error: Could not create account.');</script>";
    echo "<script>window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
