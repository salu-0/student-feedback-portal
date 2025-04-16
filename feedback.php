<?php
session_start();
require 'db.php';

// Check if user is logged in (student)
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $feedbackData = [
            'student_id' => $_SESSION['student_id'],
            'faculty_name' => $_POST['faculty_name'],
            'subject' => $_POST['subject'],
            'teaching_effectiveness' => (int)$_POST['teaching_effectiveness'],
            'clarity' => (int)$_POST['clarity'],
            'teaching_aids' => (int)$_POST['teaching_aids'],
            'doubt_clarification' => (int)$_POST['doubt_clarification'],
            'punctuality' => (int)$_POST['punctuality'],
            'overall_satisfaction' => (int)$_POST['overall_satisfaction'],
            'comments' => $_POST['comments'],
            'timestamp' => new MongoDB\BSON\UTCDateTime()
        ];

        // Insert into feedback collection
        $feedbackCollection = $database->selectCollection('feedback');
        $result = $feedbackCollection->insertOne($feedbackData);

        if ($result->getInsertedCount() === 1) {
            $success = "Feedback submitted successfully!";
        } else {
            $error = "Failed to submit feedback. Please try again.";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$facultyList = [
    'Mr.Ajith G S',
    'Dr. Gloriya Mathew',
    'Dr. Bijimol T K',
    'Mr. Jobin T J',
    'Dr. Paulin Paul'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            gap: 15px;
            direction: rtl;           /* This makes hover/active work left to right */
            width: max-content;       /* Avoid extra space pushing it right */
            margin-left: 0;           /* Align to left */
            margin-right: auto;       /* Pushes it left */
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            font-size: 32px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: gold;
        }
        .star-rating input:checked + label,
        .star-rating input:checked + label ~ label {
            color: gold;
        }

        :root {
            --primary-color: #4285f4;
            --primary-hover: #3367d6;
            --error-color: #d32f2f;
            --success-color: #388e3c;
            --text-color: #333;
            --light-text: #555;
            --border-color: #ddd;
            --card-bg: white;
            --page-bg: #f9f9f9;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            background-image: url('image/back1.jpeg');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        h1 {
            text-align: center;
            color: var(--text-color);
            margin-bottom: 30px;
            font-weight: 500;
        }
        
        .form-container {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group:last-child {
            border-bottom: none;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--light-text);
        }
        
        select, textarea, input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        select:focus, textarea:focus, input:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .error {
            color: var(--error-color);
            background: #ffebee;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success {
            color: var(--success-color);
            background: #e8f5e9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: var(--primary-hover);
        }

        .btn-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            padding: 10px;
            border: 1px solid var(--primary-color);
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-home:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>🎓 Student Feedback Form</h1>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="faculty_name">1️⃣ Faculty Name</label>
                <p class="description">Select the name of the faculty member for whom you're giving feedback.</p>
                <select name="faculty_name" id="faculty_name" required>
                    <option value="">-- Select Faculty --</option>
                    <?php foreach ($facultyList as $faculty): ?>
                        <option value="<?php echo htmlspecialchars($faculty); ?>"><?php echo htmlspecialchars($faculty); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="subject">2️⃣ Subject / Course</label>
                <p class="description">Select or enter the course name or subject taught by the faculty.</p>
                <input type="text" name="subject" id="subject" required placeholder="Enter subject name">
            </div>

            <div class="form-group">
                <label>3️⃣ Teaching Effectiveness</label>
                <p class="description">How effective was the teacher in delivering the subject content?</p>
                <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="teaching_effectiveness" id="te<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                        <label for="te<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label>4️⃣ Clarity of Explanation</label>
                <p class="description">How clearly did the teacher explain the concepts and topics?</p>
                <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="clarity" id="clarity<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                        <label for="clarity<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label>5️⃣ Use of Teaching Aids</label>
                <p class="description">How well did the teacher use teaching aids like slides, whiteboard, etc.?</p>
                <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="teaching_aids" id="aids<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                        <label for="aids<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label>6️⃣ Doubt Clarification</label>
                <p class="description">How effectively did the teacher clarify doubts and respond to questions?</p>
                <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="doubt_clarification" id="doubt<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                        <label for="doubt<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label>7️⃣ Punctuality</label>
                <p class="description">Was the teacher punctual and regular in conducting the classes?</p>
                <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="punctuality" id="punctuality<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                        <label for="punctuality<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label>8️⃣ Overall Satisfaction</label>
                <p class="description">Overall, how satisfied are you with this faculty's teaching performance?</p>
                <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="overall_satisfaction" id="overall<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                        <label for="overall<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="comments">9️⃣ Additional Comments</label>
                <p class="description">Please share any suggestions, feedback, or specific remarks (optional).</p>
                <textarea name="comments" id="comments" rows="4" placeholder="Share any additional feedback or suggestions"></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Submit Feedback</button>
            </div>
        </form>
        <div class="form-group">
            <a href="index.php" class="btn-home">🏠 Back to Home</a>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight stars on hover and click
        const starRatings = document.querySelectorAll('.star-rating');
        
        starRatings.forEach(rating => {
            const stars = rating.querySelectorAll('input[type="radio"]');
            const labels = rating.querySelectorAll('label');
            
            labels.forEach(label => {
                label.addEventListener('click', function() {
                    const id = this.getAttribute('for');
                    const input = document.getElementById(id);
                    input.checked = true;
                });
            });
        });
    });
</script>
</html>
