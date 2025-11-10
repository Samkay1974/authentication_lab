<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register- Easy Pizza</title>
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
                            <h3 class="mb-0">CREATE AN ACCOUNT</h3>
                        </div>
                        <div class="card-body">
                        <form method="POST" action="" class="mt-4" id="register-form" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Enter Full Name <i class="fa fa-user"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Valid Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number <i class="fa fa-phone"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="phone_number" name="phone_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <i class="fa fa-globe"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="country" name="country" required>
                            </div>
                
                            <div class="mb-3">
                                <label for="city" class="form-label">City <i class="fa fa-home"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="city" name="city" required>
                            </div>
                            <div class="mb-3">
                                        <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePassword()">
                                        <label class="form-check-label" for="showPassword">Show Passwords</label>
                                    </div>
                            <div class="mb-4">
                                <label class="form-label">User Type</label>
                                <div class="d-flex justify-content-start">
                                    <div class="form-check me-3 custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="customer" value="1" checked>
                                        <label class="form-check-label" for="customer">Administrator</label>
                                    </div>
                                    
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="owner" value="2">
                                        <label class="form-check-label" for="owner">Customer</label>
                                    </div>
                                </div>
                        
                            </div>
                            <button type="submit" class="btn btn-accent w-100 pulse">Sign Up</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Already have an account? <a href="login.php" class="highlight">Login here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
function togglePassword() {
    let pwd = document.getElementById("password");
    let confirmPwd = document.getElementById("confirm_password");
    [pwd, confirmPwd].forEach(input => {
        input.type = input.type === "password" ? "text" : "password";
    });
}
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js">
    </script>
</body>

</html>