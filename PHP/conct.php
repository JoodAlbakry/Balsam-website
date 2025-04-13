<?php
error_reporting(E_ALL ^ E_WARNING);

session_start();

// Create connection with Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blsm_db";

$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
