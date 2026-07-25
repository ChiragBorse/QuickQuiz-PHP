<?php
session_start();
include 'db.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged in! Please login first.'); window.location.href='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        display: flex;
        background: #f4f4f4;
        font-family: Arial, sans-serif;
        margin-top: 20px;
        margin-left: 20px;
    }
    .sidebar {
        width: 250px;
        background:linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        height: 100vh;
        position: fixed;
        padding-top: 20px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .sidebar h3 {
        border-bottom: 2px solid white;
        padding-bottom: 10px;
        margin-bottom: 20px;
        text-align: center;
        width: 80%;
    }
    .sidebar a {
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        width: 100%;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s, padding-left 0.3s;
    }
    .sidebar a.active,
    .sidebar a:hover {
        background: #1F2A5C;
        padding-left: 25px;
    }
    .sidebar a i {
        margin-right: 10px;
        font-size: 18px;
    }
    .content {
        margin-left: 250px;
        flex-grow: 1;
        padding: 20px;
    }
    .navbar {
        background:linear-gradient(135deg, #007bff, #0056b3);;
        padding: 15px;
        color: white;
    }
    .gradient-blue {
        background: linear-gradient(135deg, #007bff, #00c6ff);
    }
    .gradient-green {
        background: linear-gradient(135deg, #28a745, #a0e047);
    }
    .gradient-red {
        background: linear-gradient(135deg, #dc3545, #ff758c);
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="image/logo_quiz_web.png" width="50" class="mx-auto d-block">
        <h3 class="text-center">Quick Quiz</h3>
        <a href="#" onclick="loadPage('dashboard')"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#" onclick="loadPage('myquizzes')"><i class="fas fa-list"></i> My Quizzes</a>
        <a href="#" onclick="loadPage('profile')"><i class="fas fa-user"></i> My Profile</a>
        <a href="#" onclick="loadPage('marks')"><i class="fas fa-poll"></i> Results</a>
        <a href="login.php" class="text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">

      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">User Dashboard</a>
         </nav>


        <div id="main-content" class="text-center">

        

            <div class="container mt-5 text-center">
                <h3>Welcome to Your Dashboard</h3>
                <p>Select an option from below.</p>
                <div class="row justify-content-center mt-4">
                    <div class="col-md-4">
                        <a href="myquizzes.php" class="gradient-blue d-block p-3 rounded text-white">
                            <h4>My Quizzes</h4>
                            <p>Check out and attempt quizzes.</p>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="profile.php" class="gradient-green d-block p-3 rounded text-white">
                            <h4>My Profile</h4>
                            <p>View and update your profile information.</p>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="marks.php" class="gradient-red d-block p-3 rounded text-white">
                            <h4>Results</h4>
                            <p>View your quiz performance.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    function loadPage(page) {
        $("#main-content").load(page + ".php");
    }
    </script>
</body>
</html>
