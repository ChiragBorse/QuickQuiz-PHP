<?php
session_start();

if (!isset($_SESSION['questions'])) {
    die("Error: No questions in session.");
}

$correctAnswers = 0;
$totalQuestions = count($_SESSION['questions']);

foreach ($_SESSION['questions'] as $index => $question) {
    $selectedAnswer = $_POST['answer' . $index] ?? null;
    if ($selectedAnswer && $selectedAnswer == $question['correct_answer']) {
        $correctAnswers++;
    }
}

$_SESSION['score'] = $correctAnswers * 2; // Each correct answer is worth 2 points
header("Location: result.php");
exit();
?>
