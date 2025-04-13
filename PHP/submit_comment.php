<?php
require 'conct.php'; // Include database connection
session_start(); // Start the session

error_log("Username: " . $username);
error_log("Comment: " . $comment);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $productID = isset($_POST['productId']) ? intval($_POST['productId']) : null;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $productRating = isset($_POST['productRating']) ? intval($_POST['productRating']) : null;
    $username = isset($_SESSION['Username']) ? $_SESSION['Username'] : null;


    // Validate input
    if (!$productID || !$username || ($comment === '' && $productRating === null)) {
        header("Location: rating.php?productId=$productID&error=invalid_input");
        exit;
    }


    try {
        // Prepare the SQL query for inserting the review
        if ($comment !== '' && $productRating !== null) {
            // Both comment and rating provided
            $query = $conn->prepare("
                INSERT INTO reviews (username, productID, reviewDate, productRating, comment) 
                VALUES (?, ?, NOW(), ?, ?)
            ");
            $query->bind_param("sisi", $username, $productID, $productRating, $comment); // For comment and rating
        } elseif ($comment !== '') {
            // Only a comment provided
            $query = $conn->prepare("
                INSERT INTO reviews (username, productID, reviewDate, comment) 
                VALUES (?, ?, NOW(), ?)
            ");
            $query->bind_param("ssi", $username, $productID, $comment); // For comment only
        } elseif ($productRating !== null) {
            // Only a rating provided
            $query = $conn->prepare("
                INSERT INTO reviews (username, productID, reviewDate, productRating) 
                VALUES (?, ?, NOW(), ?)
            ");
            $query->bind_param("sii", $username, $productID, $productRating); // For rating only
        }

        // Execute the query
        if ($query->execute()) {
            // Redirect to the rating page with success message
            header("Location: rating.php?productId=$productID&success=true");
            exit;
        } else {
            throw new Exception("Failed to submit the review. Please try again.");
        }
    } catch (Exception $e) {
        // Log the error for debugging
        error_log($e->getMessage());
        // Redirect with error message
        header("Location: rating.php?productId=$productID&error=database_error");
        exit;
    }
} else {
    // Redirect to home if accessed improperly
    header("Location: home.php");
    exit;
}
?>
