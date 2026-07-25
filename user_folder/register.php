<?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (empty)
$database = "quiz_mst";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the request is POST and check if fields are set
if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    if (isset($_POST['u_name'], $_POST['u_email'], $_POST['u_password']))
     {

        // Get form data safely
        $u_name = trim($_POST['u_name']);
        $u_email = trim($_POST['u_email']);
        $u_password = ($_POST['u_password']); // Corrected hashing

        // Insert into database
        $sql = "INSERT INTO users (u_name, u_email, u_password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $u_name, $u_email, $u_password);

        if ($stmt->execute()) 
        {
            echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
        } 
        else
        {
            echo "<script>alert('Error: Email already exists!'); window.location.href='register.php';</script>";
        }

        $stmt->close();
    }
     else 
     {
        echo "<script>alert('Please fill all fields'); window.location.href='register.php';</script>";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Registration</title>
    <link rel="stylesheet" href="css/register.css"> <!-- Linking External CSS -->
</head>
<body>



    <div class="register-container">
        <h2>Register here!</h2>
        <form action="register.php" method="POST">

           

            <div class="input-group">
                <label for="u_name">Full Name:</label>
                <input type="text" id="u_name" name="u_name" placeholder="Enter your full name" required>
            </div>
               
            

            <div class="input-group">
                <label for="u_email">Email:</label>
                <input type="email" id="u_email" name="u_email" placeholder="Enter your email" required>
            </div>

            

            <div class="input-group">
                <label for="u_password">Password:</label>
                <input type="password" id="u_password" name="u_password" placeholder="Create a password" required>
            </div>

            

            <button type="submit">Register</button>
            <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>

