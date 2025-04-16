<?php
session_start();
require 'db_new.php';

use MongoDB\BSON\ObjectId;

// Helper function to validate ObjectId string
function isValidObjectId($id) {
    return preg_match('/^[a-f\d]{24}$/i', $id);
}

// Use getCollection function from db_new.php to get the faculty collection
$collection = getCollection('faculty');

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle faculty deletion
if (isset($_GET['faculty_id']) && isValidObjectId($_GET['faculty_id'])) {
    $faculty_id = new ObjectId($_GET['faculty_id']);
    $deleteResult = $collection->deleteOne(['_id' => $faculty_id]);

    if ($deleteResult->getDeletedCount() > 0) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Faculty deleted successfully.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Error deleting faculty or faculty not found.'
        ];
    }

    header("Location: delete_faculty.php"); // Redirect to the same page to show alert
    exit();
}

// Fetch all faculty for deletion dropdown
$faculty_list = $collection->find([], ['sort' => ['name' => 1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Delete Faculty</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Added SweetAlert2 script -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('image/back1.jpeg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4285f4;
        }
        form {
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4285f4;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #3367d6;
        }
        .message {
            text-align: center;
            font-weight: bold;
            color: green;
            margin-bottom: 15px;
        }
        p {
            text-align: center;
            font-weight: 500;
            color: #e53935;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Faculty</h1>
        <form method="GET" action="delete_faculty.php">
            <label for="faculty_id">Select Faculty to Delete:</label>
            <select name="faculty_id" id="faculty_id" required>
                <option value="">--Select Faculty--</option>
                <?php foreach ($faculty_list as $faculty): ?>
                    <option value="<?= htmlspecialchars((string)$faculty['_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($faculty['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Delete Faculty</button>
        </form>

        <?php if (isset($_SESSION['alert'])): ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '<?= htmlspecialchars($_SESSION['alert']['type'], ENT_QUOTES, 'UTF-8') ?>',
                    title: '<?= htmlspecialchars($_SESSION['alert']['message'], ENT_QUOTES, 'UTF-8') ?>',
                    showConfirmButton: false,
                    timer: 3000
                });
            </script>
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>

        <a href="admin.php" class="back-link" style="display: block; text-align: center; margin-top: 20px; color: #4285f4; text-decoration: none;">Back to Admin Panel</a>
    </div>
</body>
</html>
