<?php
session_start();
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $current_quiz_id = $data['current_quiz_id'];
    $answers = $data['answers'];

    // Store the answers in the session for later processing
    if (!isset($_SESSION['user_answers'])) {
        $_SESSION['user_answers'] = [];
    }
    $_SESSION['user_answers'][$current_quiz_id] = $answers;

    // Fetch the next quiz

    

    $next_quiz_query = "SELECT quiz_id FROM quizzes WHERE quiz_id > ? LIMIT 1";
    $stmt = $conn->prepare($next_quiz_query);
    $stmt->bind_param("i", $current_quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $next_quiz = $result->fetch_assoc();
        echo json_encode(['next_quiz_id' => $next_quiz['quiz_id']]);
    } else {
        // No more quizzes available
        echo json_encode(['next_quiz_id' => null]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>