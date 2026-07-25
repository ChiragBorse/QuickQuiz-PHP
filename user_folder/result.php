<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    die("Error: User not found.");
}

$user_id = $_SESSION['user_id'];

// Initialize results array
$results = [];

// Check if user_answers is set
if (isset($_SESSION['user_answers'])) {
    $user_answers = $_SESSION['user_answers'];

    foreach ($user_answers as $quiz_id => $answers) {
        $correct_answers = 0;
        $total_marks = 0;

        // Fetch total number of questions and title for the quiz
        $query = "SELECT title FROM quizzes WHERE quiz_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $quiz_info = $result->fetch_assoc();

        // Fetch total number of questions for the quiz
        $query = "SELECT COUNT(*) as total_questions FROM questions WHERE quiz_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $question_info = $result->fetch_assoc();

        if ($quiz_info && $question_info) {
            $total_marks = $question_info['total_questions']; // Total marks is equal to the number of questions
            $quiz_title = $quiz_info['title']; // Get the quiz title

            foreach ($answers as $question_id => $user_answer) {
                $query = "SELECT correct_answer FROM questions WHERE question_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $question_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if ($row && strtolower(trim($row['correct_answer'])) === strtolower(trim($user_answer))) {
                    $correct_answers++;
                }
            }

            // Store the results for this quiz
            $results[] = [
                'title' => $quiz_title,
                'obtained_marks' => $correct_answers,
                'total_marks' => $total_marks
            ];

            // Insert marks into the database
            $insertQuery = "INSERT INTO marks (user_id, quiz_id, obtained_marks, total_marks) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iiii", $user_id, $quiz_id, $correct_answers, $total_marks);
            $stmt->execute();
        }
    }
} else {
    echo "No user answers found in session.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body { 
            background: url('image/m2.jpg');                                    
            font-family: Arial, sans-serif; 
            text-align: center; 
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .result-container { 
            background: white; 
            color: #333; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0px 4px 15px rgba(0,0,0,0.3);
            max-width: 550px;
            text-align: center;
            margin-top: 10px;
        }
        .score { 
            font-size: 28px; 
            font-weight: bold; 
            color: green; 
            margin-bottom: 15px; 
        }
        .celebrate { font-size: 40px; }
        .btn-custom { background: #ff6b6b;
             color: white;
              border: none; 
              padding: 10px 20px;
               border-radius: 5px; }
        .btn-custom:hover { background: #ff4757; }
    </style>
</head>
<body>
    <div class="result-container">
        <h2 class="celebrate">🎉 Quiz Completed! 🎉</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Title</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $index => $result): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($result['title']); ?></td>
                            <td><?php echo $result['obtained_marks'] . ' / ' . $result['total_marks']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No results available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="user.php" class="btn btn-custom">Go to Dashboard</a>
    </div>
</body>
</html>