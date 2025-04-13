<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0d6efd;
            background-image: url('image/back1.jpeg');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            color: #fff;
        }
        
        .hero-section {
            min-height: 80vh;
            background-color: rgba(13, 110, 253, 0.7);
            padding: 2rem;
            border-radius: 15px;
        }
        
        .feature-card {
            transition: transform 0.3s;
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #212529;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(5px);
        }
        
        .btn-primary {
            background-color: #fff;
            color: #0d6efd;
            border: 2px solid #fff;
        }
        
        .btn-outline-primary {
            color: #fff;
            border-color: #fff;
        }
        
        .btn-outline-primary:hover {
            background-color: #fff;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="index.php">
                <i class="fas fa-user-graduate me-2"></i>Student Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-primary">
                            <i class="fas fa-star me-1"></i> Welcome Students
                        </span>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-primary">
                            <i class="fas fa-heart me-1"></i> Share your thoughts!
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">Student Feedback Portal</h1>
                    <p class="lead mb-4">Share your thoughts and help improve our institution. Your feedback matters!</p>
                    <div class="d-flex gap-3">
                        <a href="feedback.php" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-comment me-2"></i>Give Feedback
                        </a>
                        <a href="login.php" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="image/front2.png" alt="Feedback Illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold text-primary">Why Your Feedback Matters</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle mb-3 mx-auto">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                            <h4 class="mb-3">Continuous Improvement</h4>
                            <p>Your feedback helps us identify areas for improvement and enhance the student experience.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle mb-3 mx-auto">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h4 class="mb-3">Community Voice</h4>
                            <p>Join fellow students in shaping the future of our institution through collective feedback.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle mb-3 mx-auto">
                                <i class="fas fa-shield-alt fa-2x"></i>
                            </div>
                            <h4 class="mb-3">Confidential & Secure</h4>
                            <p>All feedback is treated confidentially and securely to protect your privacy.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4">Ready to share your feedback?</h2>
            <p class="lead mb-4">It only takes a few minutes to make a difference.</p>
            <a href="feedback.php" class="btn btn-light btn-lg px-4">
                <i class="fas fa-pencil-alt me-2"></i>Submit Feedback Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-white text-primary">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Student Feedback Portal. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
