<?php
session_start();
require 'db.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get form data
$faculty = $_POST['feedback_faculty'] ?? '';

// Define feedback questions
$feedback_questions = [
    'teaching_effectiveness' => 'Teaching Effectiveness',
    'clarity' => 'Clarity of Explanation',
    'teaching_aids' => 'Use of Teaching Aids',
    'doubt_clarification' => 'Doubt Clarification',
    'punctuality' => 'Punctuality',
    'overall_satisfaction' => 'Overall Satisfaction'
];

// Select the feedback collection
$feedbackCollection = $client->student->feedback;

// Fetch all feedback for Ajith G S
$feedback_cursor = $feedbackCollection->find([
    'faculty_name' => $faculty
]);

// Convert cursor to array only once
$feedback_data = iterator_to_array($feedback_cursor);

// // Debug output (optional)
// echo "<pre>";
// print_r($_POST);
// print_r($feedback_data);
// echo "</pre>";

// Prepare results
$question_scores = [];
$comments = [];

foreach ($feedback_data as $entry) {
    foreach ($feedback_questions as $key => $label) {
        if (isset($entry[$key])) {
            $question_scores[$key][] = $entry[$key];
        }
    }
    if (!empty($entry['comments'])) {
        $comments[] = $entry['comments'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Analysis</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-image: url('image/back1.jpeg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #333;
        }
        h1, h2, h3, h4 {
            color: #4285f4;
        }
        .results-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .feedback-detail {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f0f7ff;
            border-radius: 5px;
        }
        .rating-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .rating-label {
            font-weight: 500;
        }
        .rating-stars {
            color: #ffc107;
        }
        .back-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .back-btn button {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .back-btn button:hover {
            background-color: #3367d6;
        }
        .no-results {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        hr {
            border: 0;
            height: 1px;
            background: #ddd;
            margin: 20px 0;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        h1{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <h1>Feedback Analysis for <?= htmlspecialchars($faculty) ?></h1>
        <hr>

        <?php if (empty($feedback_data)): ?>
            <div class="no-results">
                <p>No feedback found for the selected criteria.</p>
            </div>
        <?php else: ?>
            <!-- Individual Feedback Details -->
            <?php foreach ($feedback_data as $feedback): ?>
                <div class="feedback-detail">
                <h3>Feedback from Student ID <?= htmlspecialchars($feedback['student_id']) ?> on <?= $feedback['timestamp']->toDateTime()->format('Y-m-d') ?></h3>
                    
                    <!-- <?php foreach ($feedback_questions as $key => $label): ?>
                        <?php if (isset($feedback[$key])): ?>
                            <div class="rating-item">
                                <span class="rating-label"><?= $label ?>:</span>
                                <span class="rating-stars"><?= str_repeat('★', $feedback[$key]) ?><?= str_repeat('☆', 5 - $feedback[$key]) ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?> -->
                    
                    <!-- <?php if (!empty($feedback['comments'])): ?>
                        <div class="rating-item">
                            <span class="rating-label">Additional Comments:</span>
                            <p><?= htmlspecialchars($feedback['comments']) ?></p>
                        </div>
                    <?php endif; ?> -->
                </div>
            <?php endforeach; ?>
            
            <hr>
            
            <!-- Summary Statistics -->
            <h2>Summary Statistics</h2>
            <?php foreach ($feedback_questions as $key => $label): ?>
                <h4><?= $label ?></h4>
                <?php if (isset($question_scores[$key])): ?>
                    <?php 
                    $avg = round(array_sum($question_scores[$key]) / count($question_scores[$key]), 2);
                    $count = count($question_scores[$key]);
                    ?>
                    <p>Average Rating: <?= $avg ?> (from <?= $count ?> responses)</p>
                <?php else: ?>
                    <p>No feedback given for this question.</p>
                <?php endif; ?>
                <hr>
            <?php endforeach; ?>
            
            <!-- Comments Section -->
            <h3>Comments Summary</h3>
            <?php if (!empty($comments)): ?>
                <ul>
                    <?php foreach ($comments as $comment): ?>
                        <li><?= htmlspecialchars($comment) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No comments available.</p>
            <?php endif; ?>
        <?php endif; ?>

        <a href="view_feedback.php" class="back-btn">
            <button>Back to Feedback Search</button>
        </a>
    </div>
</body>
</html>
