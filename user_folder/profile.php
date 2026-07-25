<?php
session_start();
include 'db.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged in! Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT u_name, u_email, u_password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();



// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $u_name = $_POST['u_name'];
    $u_email = $_POST['u_email'];

    $sql = "UPDATE users SET u_name=?, u_email=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $u_name, $u_email, $user_id);

    if ($stmt->execute()) {
        $_SESSION['u_name'] = $u_name;  // Update session data
        $_SESSION['u_email'] = $u_email;
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}

    

// Handle Password Change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) 
{
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
     

    // Check if current password is correct
    if ($current_password === $user['u_password']) 
   
    
   
    {
        if ($new_password === $confirm_password) {
            $sql = "UPDATE users SET u_password=? WHERE user_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password, $user_id);
            
            
            
            if ($stmt->execute()) {
                echo "<script>alert('Password changed successfully!'); window.location.href='profile.php';</script>";
            } 
            else 
            {
                echo "<script>alert('Error changing password.');</script>";
            }
        } 
        else 
        {
            echo "<script>alert('New passwords do not match.');</script>";
        }
     } 
    else
     {
        echo "<script>alert('Current password is incorrect.');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        body { font-family: Arial, sans-serif;
             background: white;
              padding: 0px; 
              margin-top: 20px;
              margin-left: 20px;
             }
        .container {
           width: 550px;
           margin: 50px auto; /* Adjusts margin to avoid excessive vertical space */
           background: linear-gradient(135deg,rgb(42, 216, 185),rgb(13, 184, 124));
           padding: 30px;
           border-radius: 8px;
           box-shadow: 0px 0px 10px #ccc;
           text-align: center; /* Ensures all text elements are aligned */
        }

        
  
        h2 { text-align: center; }
        label 
        { 
            font-weight: bold; 
            display: block; 
            margin-top: 10px; 
        }
        input
         {
             width: 94%; 
             padding: 8px; 
             margin-top: 5px; 
             border: 1px solid #ccc; 
             border-radius: 5px; 
            }
        button
         {
             width: 94%; 
             padding: 8px; 
             margin-top: 10px; 
             background: blue; 
             color: white; 
             border: none; 
             cursor: pointer; 
             border-radius: 5px; 
            }
        button:hover
         {
             background: darkblue;           
         }
    </style>
</head>
<body>

<div class="container">
    <h2>My Profile</h2>


    


    <!-- Update Profile Form -->
    <form method="post">
        <label>Name:</label>
        <input type="text" name="u_name" value="<?php echo htmlspecialchars($user['u_name']); ?>" required>
          
        <label>Email:</label>
        <input type="email" name="u_email" value="<?php echo htmlspecialchars($user['u_email']); ?>" required>

        <button type="submit" name="update_profile">Update Profile</button>
    </form>

    <hr>

    <!-- Change Password Form -->
    <h2>Change Password</h2>
    <form method="post">
        <label>Current Password</label>
        <input type="text" name="current_password" required>

        <label>New Password</label>
        <input type="text" name="new_password" required>

        <label>Confirm Password</label>
        <input type="text" name="confirm_password" required>

        <button type="submit" name="change_password">Change Password</button>
    </form>
</div>

</body>
</html>
