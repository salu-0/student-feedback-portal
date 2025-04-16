<?php
session_start();
require 'db.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Verify admin session
if ($_SESSION['admin_id'] !== 'admin') {
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
            background: url('image/back1.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4285f4;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 100px auto 0 auto;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            flex: 1 1 45%;
            max-width: 200px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #3367d6;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Admin Panel</h1>
        <div class="button-group">
            <a href="add_faculty.php" class="btn">Add Faculty</a>
            <a href="edit_faculty.php" class="btn">Edit Faculty</a>
            <a href="view_feedback.php" class="btn">View Feedback</a>
            <a href="delete_faculty.php" class="btn">Delete Faculty</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
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
