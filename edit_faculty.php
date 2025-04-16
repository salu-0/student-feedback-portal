<?php
session_start();
require 'db_new.php';

use MongoDB\BSON\ObjectId;

// Use getCollection function from db_new.php to get the faculty collection
$collection = getCollection('faculty');

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables
$faculty = null;
$message = '';

// Helper function to validate ObjectId string
function isValidObjectId($id) {
    return preg_match('/^[a-f\d]{24}$/i', $id);
}

// Handle faculty selection
if (isset($_GET['faculty_id']) && isValidObjectId($_GET['faculty_id'])) {
    $faculty_id = new ObjectId($_GET['faculty_id']);
    $faculty = $collection->findOne(['_id' => $faculty_id]);
    if ($faculty) {
        // Convert BSON document to array for consistent access
        $faculty = (array)$faculty;
    }
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    if (isset($_POST['faculty_id']) && isValidObjectId($_POST['faculty_id'])) {
        $faculty_id = new ObjectId($_POST['faculty_id']);
        $name = $_POST['name'];
        $department = $_POST['department'];
        $subject = $_POST['subject'];

        $updateResult = $collection->updateOne(
            ['_id' => $faculty_id],
            ['$set' => [
                'name' => $name,
                'department' => $department,
                'subject' => $subject
            ]]
        );

        if ($updateResult->getModifiedCount() > 0) {
            $message = "Faculty updated successfully.";
        } else {
            $message = "No changes made or error updating faculty.";
        }

        // Refresh faculty data after update
        $faculty = $collection->findOne(['_id' => $faculty_id]);
    } else {
        $message = "Invalid faculty ID.";
    }
}

// Fetch all faculty for selection dropdown
$faculty_list = $collection->find([], ['sort' => ['name' => 1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Faculty</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
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
        select, input[type="text"], input[type="email"] {
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
        <h1>Edit Faculty</h1>

        <form method="get" action="edit_faculty.php">
            <label for="faculty_id">Select Faculty to Edit:</label>
            <select name="faculty_id" id="faculty_id" onchange="this.form.submit()">
                <option value="">-- Select Faculty --</option>
                <?php foreach ($faculty_list as $f): ?>
                    <option value="<?= $f->_id ?>" <?= (isset($faculty) && $faculty['_id'] == $f->_id) ? 'selected' : '' ?>>
                        <?= isset($f->name) ? htmlspecialchars($f->name) : 'Unnamed' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($faculty): ?>
            <form method="post" action="edit_faculty.php" id="editForm">
                <input type="hidden" name="faculty_id" value="<?= $faculty['_id'] ?>" />

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= isset($faculty['name']) ? htmlspecialchars($faculty['name'], ENT_QUOTES) : '' ?>" required />

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?= isset($faculty['department']) ? htmlspecialchars($faculty['department'], ENT_QUOTES) : '' ?>" required />

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" value="<?= isset($faculty['subject']) ? htmlspecialchars($faculty['subject'], ENT_QUOTES) : '' ?>" required />

                <button type="submit" name="update">Update Faculty</button>
            </form>
        <?php elseif (isset($_GET['faculty_id'])): ?>
            <p>No faculty found with the selected ID.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($message === "Faculty updated successfully."): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Faculty updated successfully.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'admin.php';
            });
        <?php endif; ?>
    </script>
</body>
</html>
