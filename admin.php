<?php
session_start();
require 'db_new.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['admin_id'] !== 'admin') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Sample counts — replace with real MongoDB queries
$facultyCount = $db->faculty->countDocuments();
$feedbackCount = $db->feedback->countDocuments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f3f7fa;
        }

        .sidebar {
            width: 200px;
            background: linear-gradient(to bottom right, #0f2027, #203a43, #2c5364);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar a {
            margin: 8px 0;
            padding: 10px;
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .main-content {
            flex: 1;
            padding: 40px;
        }

        .welcome {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .card-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            flex: 1 1 300px;
            padding: 30px;
            border-radius: 12px;
            color: white;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 18px;
            font-weight: 500;
        }

        .faculty {
            background: linear-gradient(to right, #ff9966, #ff5e62);
        }

        .feedback {
            background: linear-gradient(to right, #36d1dc, #5b86e5);
        }

        .count {
            font-size: 40px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Feedback Portal</h2>
        <a href="add_faculty.php">Add Faculty</a>
        <a href="edit_faculty.php">Edit Faculty</a>
        <a href="delete_faculty.php">Delete Faculty</a>
        <a href="view_feedback.php">View Feedback</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="welcome">Welcome, <?= htmlspecialchars($_SESSION['admin_id']); ?></div>

        <div class="card-container">
            <div class="card faculty">
                Total Faculty
                <div class="count"><?= $facultyCount ?></div>
            </div>
            <div class="card feedback">
                Total Feedback
                <div class="count"><?= $feedbackCount ?></div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['alert'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['alert']['type'] ?>',
        title: '<?= $_SESSION['alert']['title'] ?>',
        text: '<?= $_SESSION['alert']['message'] ?>',
        confirmButtonColor: '#4285f4'
    });
</script>
<?php unset($_SESSION['alert']); ?>
<?php endif; ?>
</body>
</html>
