<?php
session_start();
require 'db.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
        }
        input[type="text"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #3367d6;
        }
        fieldset {
    border: none;
    padding: 0;
    margin: 0 0 25px 0;
}
legend {
    font-weight: 500;
    margin-bottom: 10px;
}

    </style>
</head>
<body>
    <div class="form-container">
        <h1>View Feedback</h1>
        <form method="POST" action="process_feedback.php">
            <div class="form-group">
                <label for="feedback_faculty">Faculty</label>
                <select name="feedback_faculty" id="feedback_faculty" required>
                    <option value="">-- Select Faculty --</option>
                    <option value="Mr.Ajith G S">Mr.Ajith G S</option>
                    <option value="Mr.Jobin T J">Mr.Jobin T J</option>
                    <option value="Dr.Paulin Paul">Dr.Paulin Paul</option>
                    <option value="Dr.Bijimol T K">Dr.Bijimol T K</option>
                    <option value="Dr.Gloriya Mathew">Dr.Gloriya Mathew</option>
                </select>
            </div>
            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" name="course" id="course" required>
            </div>
            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="text" name="semester" id="semester" required>
            </div>
            
            <fieldset class="form-group">
    <legend>Date Range</legend>
    <input type="date" name="start_date" id="start_date" required>
    <input type="date" name="end_date" id="end_date" required>
</fieldset>
            <button type="submit">View Feedback</button>
        </form>
    </div>
</body>
</html>
