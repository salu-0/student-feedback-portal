<?php
session_start();
require 'db_new.php'; // Updated to use the new database connection

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_name = $_POST['faculty_name'];
    $subject = $_POST['subject'];
    $department = $_POST['department'];

    // Insert faculty data into MongoDB
    try {
        $facultyCollection = getCollection('faculty'); // Use the getCollection function
        
        // Check if faculty already exists
        $existingFaculty = $facultyCollection->findOne(['name' => $faculty_name]);
        
        if ($existingFaculty) {
            // Update existing faculty
            $facultyCollection->updateOne(
                ['_id' => $existingFaculty['_id']],
                ['$set' => [
                    'subject' => $subject,
                    'department' => $department
                ]]
            );
            $message = "Faculty updated successfully";
        } else {
            // Insert new faculty
            $facultyCollection->insertOne([
                'name' => $faculty_name,
                'subject' => $subject,
                'department' => $department
            ]);
            $message = "Faculty added successfully";
        }
        
        // Store success message in session for SweetAlert
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Success',
            'message' => $message
        ];
        header("Location: admin.php");
        exit();
    } catch (Exception $e) {
        // Log error and redirect with error message
        error_log("Faculty save error: " . $e->getMessage());
        header("Location: admin.php?error=Error saving faculty data");
        exit();
    }
}

// Department options
$departments = [
    'Computer Science',
    'Electrical Engineering',
    'Mechanical Engineering',
    'Civil Engineering',
    'MCA',
    'BCA'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Faculty</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            background-image: url('image/back1.jpeg');
            background-size: cover;
            background-position: center;
        }
        h1 {
            color: #4285f4;
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
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn-submit {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #3367d6;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4285f4;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add Faculty</h1>
        <form method="POST" action="add_faculty.php">
            <div class="form-group">
                <label for="faculty_name">Faculty Name</label>
                <input type="text" id="faculty_name" name="faculty_name" required>
            </div>
            
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            
            <div class="form-group">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept) ?>"><?= htmlspecialchars($dept) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn-submit">Save Faculty</button>
        </form>
        <a href="admin.php" class="back-link">Back to Admin Panel</a>
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
