<?php
include("includes/db.php"); // Database connection

$errors = "";

// Registration Logic
if (isset($_POST['register'])) {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins (admin_name, admin_email, admin_password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $admin_name, $admin_email, $admin_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful! You can now login.');</script>";
    } else {
        $errors = "Registration failed. Try again.";
    }
}



// Login Logic
if (isset($_POST['login'])) {
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    $sql = "SELECT * FROM admins WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($admin_password, $row['admin_password'])) {
            session_start();
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_name'] = $row['admin_name'];
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit;
        } else {
            $errors = "Incorrect password.";
        }
    } else {
        $errors = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login & Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <script>
        function toggleForm(mode) {
            document.getElementById('register-form').style.display = mode === 'register' ? 'block' : 'none';
            document.getElementById('login-form').style.display = mode === 'register' ? 'none' : 'block';
            document.getElementById('form-title').innerText = mode === 'register' ? 'Admin Register' : 'Admin Login';
        }
    </script>
</head>
<body>

<div class="admin__background"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="admin__card p-4 shadow-lg">
        <h3 id="form-title">Admin Login</h3>

        <?php if ($errors) echo "<p class='text-danger'>$errors</p>"; ?>

        <!-- Login Form -->
        <form id="login-form" action="" method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="admin_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="admin_password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="admin__btn">Login</button>
            <p class="mt-3">Don't have an account? <a class="admin__link" href="javascript:void(0);" onclick="toggleForm('register')">Register</a></p>
        </form>

        <!-- Register Form -->
        <form id="register-form" action="" method="post" style="display: none;">
            <div class="mb-3">
                <label class="form-label">Admin Name</label>
                <input type="text" name="admin_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="admin_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="admin_password" class="form-control" required>
            </div>
            <button type="submit" name="register" class="admin__btn">Register</button>
            <p class="mt-3">Already have an account? <a class="admin__link" href="javascript:void(0);" onclick="toggleForm('login')">Login</a></p>
        </form>
    </div>
</div>

</body>
</html>
