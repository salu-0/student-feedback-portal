<?php
session_start();
require 'db_new.php';
$registerCollection = getCollection('register');
$loginCollection = getCollection('login');

$swalScript = ""; // For storing success popup
$errorMessage = ""; // For storing errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname']);
    $student_id = htmlspecialchars($_POST['student_id']);
    $email = htmlspecialchars($_POST['email']);
    $mobile = htmlspecialchars($_POST['mobile']);
    $course = htmlspecialchars($_POST['course']);
    $semester = htmlspecialchars($_POST['semester']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $errorMessage = '<div class="alert alert-danger text-center mt-3 mx-auto" style="max-width: 600px;">
                            <i class="fas fa-exclamation-circle me-2"></i>Passwords do not match!
                         </div>';
    } else {
        try {
            $hashed_password = $password; // Plain text; ideally hash it

            $student_data = [
                'fullname' => $fullname,
                'student_id' => $student_id,
                'email' => $email,
                'mobile' => $mobile,
                'course' => $course,
                'semester' => $semester,
                'password' => $hashed_password,
                'registration_date' => new MongoDB\BSON\UTCDateTime()
            ];

            $result = $registerCollection->insertOne($student_data);

            if ($result->getInsertedCount() > 0) {
                $login_data = [
                    'email' => $email,
                    'student_id' => $student_id,
                    'password' => $hashed_password
                ];
                $loginResult = $loginCollection->insertOne($login_data);

                if ($loginResult->getInsertedCount() > 0) {
                    // Prepare SweetAlert script
                    $swalScript = '<script>
                        document.addEventListener("DOMContentLoaded", function () {
                            Swal.fire({
                                title: "Registration Successful!",
                                text: "You have been successfully registered in our system.",
                                icon: "success",
                                confirmButtonText: "Continue",
                                confirmButtonColor: "#0d6efd",
                                customClass: {
                                    popup: "rounded-3"
                                }
                            }).then(() => {
                                window.location.href = "login.php";
                            });
                        });
                    </script>';
                } else {
                    $errorMessage = '<div class="alert alert-warning text-center mt-3 mx-auto" style="max-width: 600px;">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Registration completed but login data not saved.
                                     </div>';
                }
            } else {
                $errorMessage = '<div class="alert alert-warning text-center mt-3 mx-auto" style="max-width: 600px;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Registration failed.
                                 </div>';
            }
        } catch (Exception $e) {
            $errorMessage = '<div class="alert alert-danger text-center mt-3 mx-auto" style="max-width: 600px;">
                                <i class="fas fa-exclamation-circle me-2"></i>Error: ' . $e->getMessage() . '
                             </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
            background-image: url('image/back1.jpeg');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        .registration-card {
            max-width: 600px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            border: none;
        }
        .form-icon {
            color: #0d6efd;
            font-size: 1.2rem;
        }
        .btn-register {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            font-weight: 600;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand text-primary" href="#"><i class="fas fa-user-graduate me-2"></i>Student Portal</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-primary" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-primary" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link active text-primary fw-bold" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link text-primary" href="feedback.php">Feedback</a></li>
                <li class="nav-item"><a class="nav-link text-primary" href="admin.php">Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Registration Form -->
<div class="container py-5">
    <?= $errorMessage ?>
    <div class="card registration-card mx-auto p-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-user-graduate me-2"></i>Student Registration</h2>
            <p class="text-muted">Please fill in all required fields</p>
        </div>

        <form action="register.php" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-id-card"></i></span>
                        <input type="text" class="form-control" name="student_id" placeholder="Student ID" required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-phone"></i></span>
                        <input type="tel" class="form-control" name="mobile" placeholder="Mobile Number" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-book"></i></span>
                        <input type="text" class="form-control" name="course" placeholder="Course" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" class="form-control" name="semester" placeholder="Semester" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text form-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary btn-register w-100 py-2">
                        <i class="fas fa-user-plus me-2"></i>Register Now
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $swalScript ?>

</body>
</html>
