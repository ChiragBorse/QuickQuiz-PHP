<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$dbUsername = "root"; 
$dbPassword = ""; 
$database = "quiz_mst"; 

$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u_email = trim($_POST['u_email']);
    $u_password = trim($_POST['u_password']);

    // Validate inputs
    if (empty($u_email) || empty($u_password)) {
        echo "<script>alert('Email and password are required!'); window.location.href='log.php';</script>";
        exit();
    }

    // Fetch user from database by email
    $sql = "SELECT user_id, u_name, u_email, u_password FROM users WHERE u_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $u_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Compare passwords directly (NO HASHING)
        if ($row['u_password'] === $u_password) {
            $_SESSION['user_id'] = $row['user_id']; // ✅ ADDED user_id to session
            $_SESSION['u_name'] = $row['u_name'];
            $_SESSION['u_email'] = $row['u_email'];

            echo "<script>alert('Login Successful!'); window.location.href='user.php';</script>";
            exit(); // ✅ Prevents further execution
        } else {
            echo "<script>alert('Incorrect Password!'); window.location.href='log.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('User Not Found!'); window.location.href='log.php';</script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Login</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Linking External CSS -->
</head>
<body>
    <div class="login-container">
        <h2>Login to Quiz</h2>
        <form action="login.php" method="POST">
           <!-- <div class="input-group">
                <label for="u_name">Username</label>
                <input type="text" id="u_name" name="u_name" placeholder="Enter your username" required>
            </div>-->
            <div class="input-group">
                <label for="u_email">Email</label>
                <input type="email" id="u_email" name="u_email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="u_password">Password</label>
                <input type="password" id="u_password" name="u_password" placeholder="Enter your password" required>
            </div>
            <div class="remember-me">
                <input type="checkbox" id="remember">
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
