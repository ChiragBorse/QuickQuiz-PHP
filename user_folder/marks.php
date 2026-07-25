<?php
session_start();
include 'db.php'; // Ensure database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged in! Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's quiz results along with quiz titles
$sql = "SELECT m.quiz_id, m.obtained_marks, m.total_marks, q.title 
        FROM marks m 
        JOIN quizzes q ON m.quiz_id = q.quiz_id 
        WHERE m.user_id = ? 
        ORDER BY m.quiz_id DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quiz Results</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background: #f4f4f4; 
            padding: 20px; 
            font-family: Arial, sans-serif;
         }
        .container { max-width: 800px;
             background:  linear-gradient(135deg,rgb(233, 63, 208),rgb(197, 10, 147));
              margin-top: 30px; 
              padding: 20px; 
              border-radius: 8px; 
              box-shadow: 0px 0px 10px #ccc; }
        h2 { text-align: center;
             margin-bottom: 20px; }
        .table th, .table td { text-align: center; }
        .progress { height: 20px; }
    </style>
</head>
<body>
                       

                       
<div class="container">
    <h2>My Quiz Results</h2>
    <?php if ($result->num_rows > 0) { ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Quiz ID</th>
                    <th>Quiz Name</th>
                    <th>Obtained Marks</th>
                    <th>Total Marks</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { 
                    $percentage = ($row['obtained_marks'] / $row['total_marks']) * 100;
                ?>
                  <tr>
                      <td><?php echo htmlspecialchars($row['quiz_id']); ?></td>
                      <td><?php echo htmlspecialchars($row['title']); ?></td>
                      <td><?php echo htmlspecialchars($row['obtained_marks']); ?></td>
                      <td><?php echo htmlspecialchars($row['total_marks']); ?></td>
                      <td>
                        <div class="progress">
                            <div class="progress-bar bg-<?php echo ($percentage >= 50) ? 'success' : 'danger'; ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo $percentage; ?>%;" 
                                 aria-valuenow="<?php echo $percentage; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo round($percentage, 2); ?>%
                            </div>
                        </div>
                    </td>
                  </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-warning text-center">No quiz results found.</div>
    <?php } ?>
</div>

</body>
</html>