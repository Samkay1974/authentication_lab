<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Page- Easy Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/custom_theme.css">
</head>

<body>
    <div class="auth-outer">
        <div class="site-container">
            <div class="row justify-content-center animate__animated animate__fadeInDown">
                <div class="col-md-8 col-lg-6 auth-card">
                    <div class="card custom-card animate__animated animate__zoomIn">
                        <div class="card-header text-center">
                            <h4 class="mb-0">Login</h4>
                        </div>
                        <div class="card-body">
                        <!-- Alert Messages (To be handled by backend) -->
                        <!-- Example:
                        <div class="alert alert-info text-center">Login successful!</div>
                        -->

                        <form method="POST" action="" class="mt-4" id="login-form" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required>
                            </div>
                            <div class="mb-4">
                            <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                     <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                            </div>
                            <div class="mb-4">
                            <button type="submit" class="btn btn-accent w-100 pulse">Login</button>
                            </div>

                            <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePassword()">
                                        <label class="form-check-label" for="showPassword">Show Password</label>
                                    </div>
                                </div>
                    <div class="card-footer text-center">
                        Don't have an account? <a href="register.php" class="highlight">Register here</a>.
                    </div>

                </div>
            </div>
        </div>
    </div>
<script>
     function togglePassword() {
             let passwordInput = document.getElementById("password"); 
                   if (passwordInput.type === "password") {
                             passwordInput.type = "text";
            } else {
                     passwordInput.type = "password";
            }
           }
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>

    
</body>

</html>