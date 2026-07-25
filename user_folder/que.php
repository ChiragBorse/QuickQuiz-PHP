<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE)
 {
    session_start();
}

include('db_connect.php');

if (!isset($_GET['quiz_id']) || empty($_GET['quiz_id'])) 
{
    die("Error: No quiz selected.");
}

$quiz_id = $_GET['quiz_id'];
$_SESSION['quiz_id'] = $quiz_id;

// Fetch quiz duration and title
$quiz_query = "SELECT duration, title FROM quizzes WHERE quiz_id = ?";
$quiz_stmt = $conn->prepare($quiz_query);
$quiz_stmt->bind_param("i", $quiz_id);
$quiz_stmt->execute();
$quiz_result = $quiz_stmt->get_result();

if ($quiz_result->num_rows == 0) 
{
    die("Error: Quiz not found.");
}

$quiz = $quiz_result->fetch_assoc();
$_SESSION['duration'] = $quiz['duration'] * 60; // Convert minutes to seconds for the timer
$quiz_title = $quiz['title']; // Store the quiz title

// Fetch all the questions
$query = "SELECT * FROM questions WHERE quiz_id = ? ORDER BY question_id ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: No questions found for this quiz.");
}

$questions = $result->fetch_all(MYSQLI_ASSOC);
$_SESSION['questions'] = $questions;
$_SESSION['score'] = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body { 
            background-color: #f4f4f4; 
            font-family: Arial, sans-serif; 
            background-image: url('image/bg.jpg');
        }
        .quiz-container 
        { 
            max-width: 600px; 
            background: white; 
            padding: 20px; 
            margin: 50px auto; 
            border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2); 
            text-align: center; 
        }
        .option 
        { 
            display: block; 
            padding: 10px; 
            margin: 5px 0; 
            border-radius: 5px; 
            background: #f9f9f9; 
            cursor: pointer; 
            transition: background 0.3s; 
            border: 1px solid #ddd; 
            text-align: left; 
        }
        .option:hover 
        { 
            background: #e0e0e0;
         }
        .submit-btn 
        { 
            margin: 20px auto; 
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h6 id="time-display">Time Left: </h6>
        <h5><?php echo htmlspecialchars($quiz_title); ?></h5> <!-- Display the quiz title -->
        <form id="quiz-form" method="POST" action="">
            <div id="questions-container"></div>
            <input type="hidden" name="answers" id="answers">
            <button type="button" class="btn btn-success submit-btn" id="submit-btn" onclick="submitAnswers()">Submit</button>
        </form>
    </div>
     
        

    <script>
        let questions = <?php echo json_encode($_SESSION['questions']); ?>;
        let answers = {}; // Store user answers
        let duration = <?php echo json_encode($_SESSION['duration']); ?>; // Duration in seconds
        let timer;
        
        
        
        function loadQuestions()
         {
            let questionsHtml = "";
            questions.forEach((question, index) => {
                questionsHtml += `<div>
                                    <p>Question ${index + 1}: ${question.question_text}</p>
                                    <div class='option' onclick="selectOption('${question.question_id}', '${question.option_1}')">
                                        <input type="radio" name="answer_${question.question_id}" value="${question.option_1}" style="margin-right: 10px;"> ${question.option_1}
                                    </div>                                      
                                    <div class='option' onclick="selectOption('${question.question_id}', '${question.option_2}')">
                                        <input type="radio" name="answer_${question.question_id}" value="${question.option_2}" style="margin-right: 10px;"> ${question.option_2}
                                    </div>
                                    <div class='option' onclick="selectOption('${question.question_id}', '${question.option_3}')">
                                        <input type="radio" name="answer_${question.question_id}" value="${question.option_3}" style="margin-right: 10px;"> ${question.option_3}
                                    </div>                                                    
                                    <div class='option' onclick="selectOption('${question.question_id}', '${question.option_4}')">
                                        <input type="radio" name="answer_${question.question_id}" value="${question.option_4}" style="margin-right: 10px;"> ${question.option_4}
                                    </div>
                                  </div>`;
            });
            document.getElementById("questions-container").innerHTML = questionsHtml;
        }
        
        
        
        function selectOption(questionId, answer)
        {
            answers[questionId] = answer; // Store user answer
        }
        
        
        
        function submitAnswers() {
            document.getElementById("answers").value = JSON.stringify(answers);
            // Send answers to the server and get the next quiz
            fetch('next_quiz.php', {
                method: 'POST',
                headers: 
                {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ answers: answers, current_quiz_id: <?php echo $quiz_id; ?> })
            })
            .then(response => response.json())
            .then(data => {
                if (data.next_quiz_id) 
                {
                    window.location.href = `que.php?quiz_id=${data.next_quiz_id}`;
                } 
                else 
                {
                    window.location.href = 'result.php';
                }
            });
        }
        
        
        
        function startTimer() 
        {
            let timeLeft = duration; // duration is in seconds
            timer = setInterval(function() 
            {
                if (timeLeft <= 0)
                {
                    clearInterval(timer);
                    alert("Time's up! Submitting your answers.");
                    submitAnswers();
                }
                 
                 else 
                 {
                    let minutes = Math.floor(timeLeft / 60);
                    let seconds = timeLeft % 60;
                    document.getElementById("time-display").innerText = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    timeLeft--;
                }
            }, 1000);
        }
           
        

        window.onload = function()
         {
            loadQuestions();
            startTimer(); // Start the timer when the page loads
        };

        

    </script>
</body>
</html>