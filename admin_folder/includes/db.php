<?php
$host = "localhost"; // Change if needed
$user = "root"; // Your MySQL username
$pass = ""; // Your MySQL password
$dbname = "quiz_mst"; // Your Database Name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>
