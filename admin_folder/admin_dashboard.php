<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php"); // Redirect if not logged in
    exit;
}

// Include database connection
include("includes/db.php");

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_name = $_POST['admin_name'];
    $new_email = $_POST['admin_email'];
    $new_password = !empty($_POST['admin_password']) ? password_hash($_POST['admin_password'], PASSWORD_BCRYPT) : $admin['admin_password'];

    $update_sql = "UPDATE admins SET admin_name = ?, admin_email = ?, admin_password = ? WHERE admin_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $new_name, $new_email, $new_password, $admin_id);
    
    if ($update_stmt->execute()) {
        header("Location: admin_dashboard.php?profile_updated=1");
        exit;
    } else {
        echo "<script>alert('Error updating profile. Please try again.');</script>";
    }
}

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $delete_sql = "DELETE FROM admins WHERE admin_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $admin_id);
    
    if ($delete_stmt->execute()) {
        session_destroy();
        header("Location: admin.php?account_deleted=1");
        exit;
    } else {
        echo "<script>alert('Error deleting account. Please try again.');</script>";
    }
}



// Handle DELETE request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz_id'])) {
    $quiz_id = $_POST['quiz_id'];

    // Delete Quiz from Database
    $delete_sql = "DELETE FROM quizzes WHERE quiz_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $quiz_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    $stmt->close();
    exit(); // Stop further execution
}



// Handle quiz creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get quiz details
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];

    // Insert into `quizzes` table
    $query = "INSERT INTO quizzes (admin_id, title, description, duration, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $admin_id, $title, $description, $duration);

    if ($stmt->execute()) {
        $quiz_id = $stmt->insert_id; // Get last inserted quiz ID

        // Insert questions into `questions` table
        for ($i = 0; $i < count($_POST['question_text']); $i++) {
            $question_text = $_POST['question_text'][$i];
            $question_type = $_POST['question_type'][$i];
            $option_1 = $_POST['option_1'][$i];
            $option_2 = $_POST['option_2'][$i];
            $option_3 = $_POST['option_3'][$i];
            $option_4 = $_POST['option_4'][$i];
            $correct_answer = $_POST['correct_answer'][$i];

            $questionQuery = "INSERT INTO questions (quiz_id, question_text, question_type, option_1, option_2, option_3, option_4, correct_answer) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($questionQuery);
            $stmt2->bind_param("isssssss", $quiz_id, $question_text, $question_type, $option_1, $option_2, $option_3, $option_4, $correct_answer);
            $stmt2->execute();
        }
        header("Location: admin_dashboard.php?quiz_created=1");
        exit;
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - QuizWeb</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="assets/css/admin_dashboard.css"> <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/users_table.css">
    <link rel="stylesheet" href="assets/css/marks_table.css">
    <link rel="stylesheet" href="assets/css/quiz_ques.css">
    <link rel="stylesheet" href="assets/css/question.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
        <img src="assets/images/logo_quiz_web.png" alt="Logo">
        <h3>QuickQuiz</h3>
    </div>
    <hr>
    <ul class="nav-list">
        <li><a href="admin_dashboard.php" class="nav-item" onclick="showSection('profile-section')">
            <i class="fa-solid fa-user"></i> Profile</a></li>
        <li><a href="#" class="nav-item" onclick="showSection('quizzes-section')">
            <i class="fa-solid fa-brain"></i> Quizzes</a></li>
        <li><a href="#" class="nav-item" onclick="showSection('questions-section')">
            <i class="fa-solid fa-question-circle"></i> Questions</a></li>    
        <li><a href="#" class="nav-item" onclick="showSection('marks-section')">
            <i class="fa-solid fa-file-pen"></i> Marks</a></li>
        <li><a href="#" class="nav-item" onclick="showSection('users-section')">
            <i class="fa-solid fa-users"></i> Users</a></li>
        <li><a href="admin.php" class="nav-item logout-btn">
            <i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>



<!-- Main Content -->
<div class="content">
    <!-- Profile Section -->
    <div id="profile-section" class="section">
        <h2>Welcome, <?php echo $admin['admin_name']; ?>!</h2>
        <p>Manage quizzes, users, and more from here.</p>
        <hr>
        <div class="profile-card">
            <img src="assets/images/profile_bg_icon.png" alt="Profile Picture" class="profile-pic">
            <h3><?php echo $admin['admin_name']; ?></h3>
            <p>Email: <?php echo $admin['admin_email']; ?></p>
        </div>

        <hr>
        <div id="edit-profile-section" class="section">
            <h2><i class="fa-solid fa-file-pen"></i> Edit Profile</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="admin_name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="admin_name" value="<?php echo $admin['admin_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="admin_email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="admin_email" value="<?php echo $admin['admin_email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="admin_password" class="form-label">New Password (Leave blank to keep the same)</label>
                    <input type="password" class="form-control" name="admin_password">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                <button type="submit" name="delete_account" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action is irreversible.');">Delete Account</button>
            </form>
        </div>
    </div>
    
    

    <!-- Quizzes Section -->
    <div id="quizzes-section" class="section" style="display: none;">
        <div class="quizzes-block d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fa-solid fa-list-check"></i> Manage Quizzes</h2>
                <p>Here you can create, and delete quizzes.</p>
            </div>
            <div>
                <button id="openQuizPopup" class="btn btn-sm create-quiz-btn">
                <i class="fa-solid fa-plus"></i> Create Quiz</button>
            </div>
        </div>

        <!-- Pop-up Card -->
        <div id="quizPopup" class="popup-overlay">
            <div class="popup-card">
                
            
            
                <!-- Close Button -->
                <button id="close-card-button"><i class="fa-solid fa-times"></i></button>

                <!-- Quiz Form -->
                <form id="quizForm" method="POST">
                    <div class="popup-header">
                        <h3 class="text-center">Create New Quiz</h3>
                        <p class="text-center">Fill in the details below to create a new quiz.</p>
                        <hr>
                    </div>


                    
                    <!-- Quiz Details -->
                    <label>Quiz Title:</label>
                    <input type="text" name="title" class="form-control" required>

                    <label>Description:</label>
                    <textarea name="description" class="form-control" required></textarea>

                    <label>Duration (minutes):</label>
                    <input type="number" name="duration" class="form-control" required>

                    <hr>
                    <h4>Add Questions</h4>
                    <div id="questionsContainer">
                        <div class="question-block">
                            <label>Question:</label>
                            <input type="text" name="question_text[]" class="form-control" required>

                            <label>Question Type:</label>
                            <select name="question_type[]" class="form-control">
                                <option value="MCQ">Multiple Choice</option>
                                <option value="True/False">True/False</option>
                            </select>

                            <label>Option 1:</label>
                            <input type="text" name="option_1[]" class="form-control" required>

                            <label>Option 2:</label>
                            <input type="text" name="option_2[]" class="form-control" required>

                            <label>Option 3:</label>
                            <input type="text" name="option_3[]" class="form-control">

                            <label>Option 4:</label>
                            <input type="text" name="option_4[]" class="form-control">

                            <label>Correct Answer:</label>
                            <input type="text" name="correct_answer[]" class="form-control" required>
                        </div>
                    </div>

                    <!-- Add More Questions Button -->
                    <button type="button" id="addQuestion" class="btn btn-secondary mt-3">Add Questions</button>

                    <!-- Submit Button -->
                    <div class="popup-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <h3>Quiz table</h3>
        <?php
        // Fetch Quiz from the Database
        $quizzes_sql = "SELECT quz.quiz_id, quz.admin_id, quz.title, quz.description,
         quz.duration, quz.created_at, a.admin_name
          FROM quizzes quz 
          JOIN admins a ON quz.admin_id = a.admin_id";
        $quizzes_result = $conn->query($quizzes_sql);

        if ($quizzes_result->num_rows > 0) {
            echo "<table class='quizzes-table'>";
            echo "<thead>
                    <tr>
                        <th>Quiz ID</th>
                        <th>Admin Id</th>
                        <th>Admin Name</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Duration</th>
                        <th>Created At</th>
                        <th>Action</th> <!-- New Column for Delete Button -->
                    </tr>
                  </thead>
                  <tbody>";

            while ($quizzes = $quizzes_result->fetch_assoc()) {
                echo "<tr id='quizRow-" . $quizzes['quiz_id'] . "'>";
                echo "<td>" . $quizzes['quiz_id'] . "</td>";
                echo "<td>" . $quizzes['admin_id'] . "</td>";
                echo "<td>" . $quizzes['admin_name'] . "</td>";
                echo "<td>" . $quizzes['title'] . "</td>";
                echo "<td>" . $quizzes['description'] . "</td>";
                echo "<td>" . $quizzes['duration'] . "</td>";
                echo "<td>" . $quizzes['created_at'] . "</td>";
                echo "<td>
                        <button class='delete-quiz-button btn btn-sm btn-danger' 
                            data-quizid='" . $quizzes['quiz_id'] . "'>
                            <i class='fa-solid fa-trash'></i>
                        </button>
                      </td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No quiz found.</p>";
        }
        ?>
    </div>


    
    


    <!-- JavaScript to Handle Pop-up Functionality -->
    <script>
        document.getElementById("openQuizPopup").addEventListener("click", function() {
            document.getElementById("quizPopup").style.display = "flex";
        });

        document.getElementById("close-card-button").addEventListener("click", function() {
            document.getElementById("quizPopup").style.display = "none";
        });

        // Close pop-up when clicking outside the card
        document.getElementById("quizPopup").addEventListener("click", function(event) {
            if (event.target === this) {
                this.style.display = "none";
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".delete-quiz-button").forEach(function (button) {
                button.addEventListener("click", function () {
                    let quizId = this.getAttribute("data-quizid");

                    if (confirm("Are you sure you want to delete this quiz?")) {
                        // AJAX request to delete quiz
                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "admin_dashboard.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                let response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    // Remove the quiz row from the table
                                    document.getElementById("quizRow-" + quizId).remove();
                                } else {
                                    alert("Failed to delete quiz. Try again.");
                                }
                            }
                        };
                        xhr.send("quiz_id=" + quizId);
                    }
                });
            });
        });

        document.getElementById("addQuestion").addEventListener("click", function() {
            // Clone the question block
            const questionBlock = document.querySelector(".question-block");
            const newQuestionBlock = questionBlock.cloneNode(true);
            
            // Clear the input values in the cloned block
            const inputs = newQuestionBlock.querySelectorAll("input, select");
            inputs.forEach(input => {
                input.value = ""; // Clear the value
            });

            // Append the new question block to the container
            document.getElementById("questionsContainer").appendChild(newQuestionBlock);
        });
    </script>

    <!-- Question Section -->
    <div id="questions-section" class="section" style="display: none;">
        <div class="questions-block">
            <h2><i class="fa-solid fa-question"></i> Question</h2>
            <p>Here you can see the questions.</p>
        </div>
        <hr>

        <?php
        // Fetch quizzes from the database
        $quizzes_sql = "SELECT quiz_id, title FROM quizzes";
        $quizzes_result = $conn->query($quizzes_sql);

        if ($quizzes_result->num_rows > 0) {
            while ($quiz = $quizzes_result->fetch_assoc()) {
                $quiz_id = $quiz['quiz_id'];
                $quiz_title = $quiz['title'];

                // Display quiz information
                echo "<div class='quiz-info'>";
                echo "<h4>Quiz ID: " . $quiz_id . " . Quiz Name: " . $quiz_title . "</h4>";
                echo "</div>";

                // Fetch questions for the current quiz
                $questions_sql = "SELECT question_text, option_1, option_2, option_3, option_4, correct_answer 
                                  FROM questions WHERE quiz_id = ?";
                $questions_stmt = $conn->prepare($questions_sql);
                $questions_stmt->bind_param("i", $quiz_id);
                $questions_stmt->execute();
                $questions_result = $questions_stmt->get_result();

                // Display questions in a table format
                if ($questions_result->num_rows > 0) {
                    echo "<table class='questions-table'>";
                    echo "<thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Question Text</th>
                                <th>Option 1</th>
                                <th>Option 2</th>
                                <th>Option 3</th>
                                <th>Option 4</th>
                                <th>Correct Answer</th>
                            </tr>
                          </thead>
                          <tbody>";

                    $sr_no = 1; // Initialize serial number
                    while ($question = $questions_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $sr_no++ . "</td>"; // Display serial number
                        echo "<td>" . $question['question_text'] . "</td>";
                        echo "<td>" . $question['option_1'] . "</td>";
                        echo "<td>" . $question['option_2'] . "</td>";
                        echo "<td>" . $question['option_3'] . "</td>";
                        echo "<td>" . $question['option_4'] . "</td>";
                        echo "<td>" . $question['correct_answer'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No questions found for this quiz.</p>";
                }

                echo "<hr>"; // Line to separate quizzes
            }
        } else {
            echo "<p>No quizzes found.</p>";
        }
        ?>
    </div>


    


    <!-- Marks Section -->
    <div id="marks-section" class="section" style="display: none;">
        <div class="marks-block">
            <h2><i class="fa-solid fa-award"></i> View Marks</h2>
            <p>Check the marks of students here.</p>
        </div>
        <hr>

        <?php
        // Fetch marks from the database with user names and quiz titles
        $marks_sql = "SELECT m.marks_id, m.quiz_id, m.user_id, m.obtained_marks, m.total_marks, m.created_at, 
                             u.u_name, u.u_email, q.title 
                      FROM marks m 
                      JOIN users u ON m.user_id = u.user_id 
                      JOIN quizzes q ON m.quiz_id = q.quiz_id"; // Assuming quiz_id is the primary key in quizzes table
        $marks_result = $conn->query($marks_sql);

        if ($marks_result->num_rows > 0) {
            echo "<table class='marks-table'>";
            echo "<thead>
                    <tr>
                        <th>Marks ID</th>
                        <th>Quiz ID</th>
                        <th>Quiz Title</th> <!-- New column for Quiz Title -->
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>Obtained Marks</th>
                        <th>Total Marks</th>
                        <th>Created At</th>
                    </tr>
                  </thead>
                  <tbody>";
            while ($row = $marks_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['marks_id'] . "</td>";
                echo "<td>" . $row['quiz_id'] . "</td>";
                echo "<td>" . $row['title'] . "</td>";      // Display Quiz Title
                echo "<td>" . $row['user_id'] . "</td>";   //
                echo "<td>" . $row['u_name'] . "</td>";   // Display User Name
                echo "<td>" . $row['u_email'] . "</td>"; // Display User Email
                echo "<td>" . $row['obtained_marks'] . "</td>";
                echo "<td>" . $row['total_marks'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No marks found.</p>";
        }
        ?>
    </div>

    <!-- Users Section -->
    <div id="users-section" class="section" style="display: none;">
        <div class="questions-block">
            <h2><i class="fa-solid fa-users"></i> Manage Users</h2>
            <p>View and manage registered students.</p>
        </div>
        <hr>
        <?php
        // Fetch Users from the Database
        $user_sql = "SELECT user_id, u_name, u_email, created_at FROM users";
        $user_result = $conn->query($user_sql);

        if ($user_result->num_rows > 0) {
            echo "<table class='users-table'>";
            echo "<thead><tr><th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Registered At</th>
            </tr>
            </thead>
            <tbody>";
            while ($user = $user_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $user['user_id'] . "</td>";
                echo "<td>" . $user['u_name'] . "</td>";
                echo "<td>" . $user['u_email'] . "</td>";
                echo "<td>" . $user['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
    </div>
</div>



<!-- JavaScript for Section Toggle -->
<script>
    function showSection(sectionId) {
        // Hide all sections
        let sections = document.querySelectorAll('.section');
        sections.forEach(section => {
            section.style.display = 'none';
        });

        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';
    }
</script>

</body>
</html>