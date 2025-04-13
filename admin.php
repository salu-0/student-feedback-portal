<?php
session_start();
require 'db.php';

// Check if user is logged in as admin by verifying against admin collection
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Verify admin session
if ($_SESSION['admin_id'] !== 'admin') {
    // Invalid admin session
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Admin Panel</h1>
        <a href="manage_faculty.php">
            <button style="margin-bottom: 20px;">Add / Edit Faculty</button>
        </a>
        <a href="view_feedback.php">
            <button>View Feedback</button>
        </a>
    </div>
</body>
<script>
<?php if (isset($_SESSION['alert'])): ?>
    Swal.fire({
        icon: '<?= $_SESSION['alert']['type'] ?>',
        title: '<?= $_SESSION['alert']['title'] ?>',
        text: '<?= $_SESSION['alert']['message'] ?>',
        confirmButtonColor: '#4285f4'
    });
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
</script>
</html>
