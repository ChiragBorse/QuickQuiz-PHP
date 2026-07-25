<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            background: #f4f4f4;
            margin-top: 20px;
            margin-left: 20px;
        }
        .sidebar {
            width: 250px;
            background: rgb(55, 124, 202);
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar a {
            color: white;
            padding: 12px;
            display: block;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #2D336B;
        }
        .content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
        }
        .navbar {
            background: #015551;
        }
        .card {
            text-align: center;
            border: none;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .btn-custom {
            background: #F38C79;
            color: Black;
        }
        .btn-custom:hover {
            background: #F6F8D5;
        }
    </style>
</head>
<body>
    

    <div class="sidebar">
    <img src="settings.png"  width="35" class="mx-auto d-block">
        <h4 class="text-center">Admin Panel</h4>
        <a href="#dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#users"><i class="fas fa-users"></i> Users</a>
        <a href="#admins"><i class="fas fa-user-shield"></i> Admins</a>
        <a href="#questions"><i class="fas fa-question-circle"></i> Questions</a>
        <a href="#reports"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
        <a href="#logout" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
 Main Content 
    <div class="content">
        
         Navbar 
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">Quiz Dashboard</a>
            <div class="ml-auto">
                <a href="#notifications" class="text-white mx-3"><i class="fas fa-bell"></i> Notifications</a>
                <a href="#settings" class="text-white mx-3"><i class="fas fa-cog"></i> Settings</a>
                <button class="btn btn-custom">Create Quiz</button>
            </div>
        </nav>
        
         Dashboard Cards 
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Users</h5>
                            <p>100</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Admins</h5>
                            <p>5</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5>Questions</h5>
                            <p>500</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5>Active Quizzes</h5>
                            <p>12</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5>Pending Reviews</h5>
                            <p>8</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>-->
