<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quiz_mst");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch quizzes from database
$sql = "SELECT quiz_id, title FROM quizzes ORDER BY quiz_id ASC";
$result = $conn->query($sql);

// Gradient colors array
$gradientColors = [
    "linear-gradient(135deg, #007bff, #00c6ff)", // Blue
    "linear-gradient(135deg, #28a745, #a0e047)", // Green
    "linear-gradient(135deg, #ff5733, #ff8d72)", // Red-Orange
    "linear-gradient(135deg, #8a2be2, #ff7eb3)", // Purple-Pink
    "linear-gradient(135deg, #ffcc00, #ff8800)", // Yellow-Orange
    "linear-gradient(135deg, #17a2b8, #66e0ff)", // Cyan
    "linear-gradient(135deg, #ff1493, #ff69b4)", // Pink
    "linear-gradient(135deg, #6a0dad, #a06cd5)", // Violet
    "linear-gradient(135deg, #d9534f, #ff6f61)", // Coral
    "linear-gradient(135deg, #20c997, #76eec6)"  // Teal-Green
];

$index = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quizzes</title>
    <style>
        body {
            font-family: Arial, sans-serif; 
            text-align: center; 
            background-color: white;
            margin: 0;
            padding: 0;
            margin-top: 20px;
            margin-left: 20px;
        }
        header {
            background: linear-gradient(135deg,rgb(174, 0, 255),rgb(19, 92, 181));
            color: white;
            padding: 15px;
            font-size: 24px;
            margin: 25px;
        }
        .container {
            max-width: 100%;
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }
        .subject {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 15px;
            font-size: 20px;
            width: 200px;
            height: 150px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            color: white; /* Ensures text is visible */
        }
        .subject:hover {
            transform: scale(1.1);
            box-shadow: 4px 4px 15px rgba(0,0,0,0.3);
        }
        .play-btn {
            margin-top: 10px;
            padding: 8px 16px;
            background: rgba(0, 0, 0, 0.2);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .play-btn:hover {
            background: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body>
    <header>My Quizzes</header>
    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                    $color = $gradientColors[$index % count($gradientColors)]; // Assign color cyclically
                    $index++;
                ?>
                <div class="subject" style="background: <?= $color ?>;">
                    <h5><?= htmlspecialchars($row["title"]) ?></h5>
                    <a href="que.php?quiz_id=<?= $row["quiz_id"] ?>">
                        <button class="play-btn">Play</button>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No quizzes available.</p>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>
