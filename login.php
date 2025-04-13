<?php
session_start();
require 'db_new.php';
$loginCollection = getCollection('login');

$swalScript = ''; // Default empty

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = htmlspecialchars($_POST['identifier']);
    $password = htmlspecialchars($_POST['password']);

    try {
        $student = $loginCollection->findOne([
            '$or' => [
                ['email' => $identifier],
                ['student_id' => $identifier]
            ]
        ]);

        // Check for admin credentials first
        if ($identifier === '1234' && $password === 'admin') {
            $_SESSION['admin_id'] = 'admin';
            $swalScript = '<script>
                window.onload = function() {
                    Swal.fire({
                        title: "Admin Login Successful!",
                        text: "Welcome Administrator",
                        icon: "success",
                        confirmButtonText: "Continue",
                        confirmButtonColor: "#0d6efd",
                        customClass: {
                            popup: "rounded-3"
                        }
                    }).then(() => {
                        window.location.href = "admin.php";
                    });
                };
            </script>';
        }
        // Regular student login
        elseif ($student && isset($student['password']) && $password === $student['password']) {
            $_SESSION['student_id'] = $student['student_id'];
            
            // Get fullname from register collection
            $registerCollection = getCollection('register');
            $studentData = $registerCollection->findOne(['student_id' => $student['student_id']]);
            $fullname = isset($studentData['fullname']) ? $studentData['fullname'] : 'Student';
            $_SESSION['fullname'] = $fullname;
            $swalScript = '<script>
                window.onload = function() {
                    Swal.fire({
                        title: "Login Successful!",
                        text: "Welcome back, ' . $fullname . '",
                        icon: "success",
                        confirmButtonText: "Continue",
                        confirmButtonColor: "#0d6efd",
                        customClass: {
                            popup: "rounded-3"
                        }
                    }).then(() => {
                        window.location.href = "feedback.php";
                    });
                };
            </script>';
        } else {
            $swalScript = '<script>
                window.onload = function() {
                    Swal.fire({
                        title: "Login Failed",
                        text: "Invalid credentials. Please try again.",
                        icon: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#0d6efd",
                        customClass: {
                            popup: "rounded-3"
                        }
                    });
                };
            </script>';
        }
    } catch (Exception $e) {
        $swalScript = '<script>
            window.onload = function() {
                Swal.fire({
                    title: "Error",
                    text: "' . $e->getMessage() . '",
                    icon: "error",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#0d6efd",
                    customClass: {
                        popup: "rounded-3"
                    }
                });
            };
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>

    <!-- ✅ SweetAlert2 must load early -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            background-image: url('image/back1.jpeg');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        .login-card {
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            border: none;
        }
        .form-icon {
            color: #0d6efd;
            font-size: 1.2rem;
        }
        .btn-login {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card login-card mx-auto p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Student Login
                        </h2>
                        <p class="text-muted">Enter your credentials to access your account</p>
                    </div>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text form-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" id="identifier" name="identifier" 
                                       placeholder="Email or Student ID" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text form-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Password" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-login py-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="register.php">Register here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ Echo SweetAlert2 response if needed -->
    <?php echo $swalScript; ?>
</body>
</html>
