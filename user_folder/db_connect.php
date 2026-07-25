<?php
// Start session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";  // Change if using a different server
$username = "root";         // Default XAMPP username
$password = "";             // Default XAMPP password (leave empty)
$database = "quiz_mst";     // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
