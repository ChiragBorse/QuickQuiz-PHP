-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2026 at 11:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_mst`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `admin_email`, `admin_password`, `created_at`) VALUES
(1, 'Jay', 'Jay@12345', '$2y$10$nza59JlPoF5hlyVSQ2vu/u1d4DW3aQapCT2w77tc8tHEJsclmeN7q', '2025-03-12 09:47:24'),
(3, 'Ajay', 'A@500', '$2y$10$U4seYjIgLXTt04sj7VUIfu8pUdiDyGV6yIuur/bleyvYtCJ7/cG4K', '2025-03-12 09:49:47'),
(4, 'Narayan', 'nr123@gmail.com', '$2y$10$3IOiDkl0udPbxMQqiO/l7.6en3CspmYBSlJ4GUNTxWOA0BxwakwBq', '2025-03-12 10:08:36'),
(7, 'idk', 'idk@gmail.com', '$2y$10$c.WyMzWMezngiY9zVC6UW.E5nmfGHbXciuZiZj6mtprU2rkkzZSAG', '2025-03-13 06:34:18'),
(8, 'yukta', 'yukta@gmail.com', '$2y$10$lXupYS0B3IAMdXl2/wD4augF1.Lj/4EHnc5yGLNFBCrze4RGepKse', '2025-03-13 06:57:01'),
(9, 'Amol Sir', 'amolsir@gmail.com', '$2y$10$P6cqoYNnzhk.BHLE8L3qGuoe2k4uuh0qeduyUgbKFuxRgGjJ7znOO', '2025-03-13 07:46:29'),
(10, 'Chirag', 'Chirag@gmail.com', '$2y$10$TN0BydwUDMHAnWT3iIuO5.Staq.KhkAl0ZNf/mQh2QT6kNVg6D7vK', '2025-03-13 09:09:18'),
(11, 'Kunal Sir', 'kunal@gmail.com', '$2y$10$CkVFS5nj.pmQQ5cYml6WuOfhc3ruFFRoHfMCmucQkgpiPWOupNBhu', '2025-03-17 09:18:46'),
(12, 'Abhishek Patil', 'abhishek@gmail.com', '$2y$10$2MyofG3edAW3E3.7GE3.LeICUSJVz9S0EAPRjIY43wqGyOPlds5O6', '2025-11-06 11:38:38'),
(13, 'Harshal Kulkarni', 'harsh@gmail.com', '$2y$10$S7KJpFOhe5iCIQ7L9EDoNuMaLa8uojbCxhYbH5iuJscOMNlNeG0iu', '2025-11-06 11:40:00'),
(14, 'Harshada Borse', 'harshada@gmail.com', '$2y$10$gsxx/4RxnrSggbd3as4JEeynrbFTsj368OFAHltJhmhyK0ByjGw1e', '2026-02-19 06:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `marks_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `obtained_marks` int(11) NOT NULL,
  `total_marks` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`marks_id`, `user_id`, `quiz_id`, `obtained_marks`, `total_marks`, `created_at`) VALUES
(6, 1, 17, 10, 10, '2025-03-25 08:15:25'),
(88, 1, 18, 4, 6, '2025-04-16 04:42:46'),
(91, 4, 18, 3, 6, '2025-11-06 11:43:53'),
(92, 5, 18, 4, 6, '2026-02-19 06:35:24'),
(93, 5, 18, 5, 6, '2026-02-19 06:40:20'),
(94, 5, 17, 4, 5, '2026-02-19 06:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('MCQ','TrueFalse','ShortAnswer') NOT NULL,
  `option_1` varchar(255) DEFAULT NULL,
  `option_2` varchar(255) DEFAULT NULL,
  `option_3` varchar(255) DEFAULT NULL,
  `option_4` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `question_text`, `question_type`, `option_1`, `option_2`, `option_3`, `option_4`, `correct_answer`) VALUES
(18, 17, 'What is one of the main benefits of AI in business?', 'MCQ', 'Increased manual workload', 'Reduced ', 'Automation of repetitive tasks', 'Higher operational costs', 'Automation of repetitive tasks'),
(19, 17, 'Which company uses AI for personalized content recommendations?', 'MCQ', 'Walmart', 'Netflix', 'Toyota', 'Boeing', 'Netflix'),
(20, 17, 'Which industry benefits the most from AI-powered fraud detection?', 'MCQ', 'Retail', 'Banking and Finance', ' Education', 'Hospitality', 'Banking and Finance'),
(21, 17, 'AI-powered chatbots help businesses by:', 'MCQ', ' Increasing response time', 'Reducing customer service costs', 'Eliminating human employees', 'Ignoring customer complaints', 'Reducing customer service costs'),
(22, 17, 'What is a major challenge of implementing AI in businesses?', 'MCQ', 'Decreased data security risks', 'High implementation costs', 'Increased hiring of manual labor', 'No need for skilled professionals', 'High implementation costs'),
(28, 18, 'Which has more services currently - AWS or GCP?', 'MCQ', 'GCP', 'AWS', 'No answer text provided.', 'IBM', 'AWS'),
(29, 18, 'Is GCP considered one among the top three cloud computing platforms today?', 'MCQ', 'Yes', 'No', 'No answer text provided.', 'maybe', 'Yes'),
(30, 18, 'What is the name of the service that is used for storage on Google Cloud?', 'MCQ', 'Cloud Store', 'CloudRep', 'Cloud Storage', 'CloudDisk', 'Cloud Storage'),
(31, 18, 'Among GCP, AWS and Azure - Which one of these is the newest in the market?', 'MCQ', 'AWS', 'Azure', 'none of this', 'GCP', 'GCP'),
(32, 18, 'What is full form of PAAS', 'MCQ', 'Platform As Service', 'Platform As Storage', 'Platform Assistant Service', 'none of the above', 'Platform As Service'),
(33, 18, 'What IAAS stand for?', 'MCQ', 'International Authority Automation Service', 'Infrastructure as a service', 'Instruction as a Said', 'Infrastructure a as Service', 'Infrastructure as a service');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duration in minutes',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `admin_id`, `title`, `description`, `duration`, `status`, `created_at`) VALUES
(17, 10, 'Introduction to A.I', 'Test online', 10, 'active', '2025-03-25 08:12:13'),
(18, 10, 'Cloud', 'test 5 question 2 marks', 1, 'active', '2025-03-25 08:49:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `u_name` varchar(50) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_password` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `u_name`, `u_email`, `u_password`, `created_at`) VALUES
(1, 'Laksh', 'laksh@gmail.com', '123', '2025-03-16 08:59:44'),
(2, 'Akash', 'akash@gmail.com', '123', '2025-03-17 04:14:41'),
(4, 'aniket chavan', 'aniket@gmail.com', '123', '2025-11-06 11:43:02'),
(5, 'Kalyani', 'kalyani@gmail.com', '123', '2026-02-19 06:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_attempts`
--

CREATE TABLE `user_attempts` (
  `attempt_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL,
  `attempt_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`admin_email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`marks_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`u_email`),
  ADD UNIQUE KEY `unique_u_name` (`u_name`);

--
-- Indexes for table `user_attempts`
--
ALTER TABLE `user_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `marks_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_attempts`
--
ALTER TABLE `user_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `fk_marks_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_marks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_attempts`
--
ALTER TABLE `user_attempts`
  ADD CONSTRAINT `user_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
