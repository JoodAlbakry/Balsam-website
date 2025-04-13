<?php
include 'conct.php';

$user = $_POST["user"];
$pass = $_POST["pass"];

// Use a prepared statement to prevent SQL injection
$sql = "SELECT * FROM users WHERE Username = ? AND Password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass); // "ss" means two string parameters
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    session_start();
    
    $userData = $result->fetch_assoc();
 
    $_SESSION['user'] = [
        'id' => $userData['id'],           
        'username' => $userData['username'], 
        'status' => $userData['Status'],     
        'stateVal' => $userData['StateVal'] 
    ];

    // Set a cookie for the username (valid for 30 days)
    setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/"); // Expires in 30 days

    header('Location: home.php', true, 302);
} else {
    header('Location: login.php?error=passwordError');
    exit();
}

$stmt->close();
$conn->close();
?>