$(document).ready(function () {
    $('#login-form').submit(function (e) {
        e.preventDefault();

        let email = $('#email').val().trim();
        let password = $('#password').val();

        // Validate fields
        if (email === '' || password === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        }

        // Validate email format
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Please enter a valid email address!',
            });
            return;
        }

        // AJAX request
        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                password: password
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome!',
                        text: response.message,
                    }).then(() => {
                        // Redirect after login success
                        window.location.href = '../index.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Something went wrong. Please try again later.',
                });
            }
        });
    });
});
