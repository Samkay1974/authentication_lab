// Settings/core.php
<?php
session_start();


//for header redirection
ob_start();

//funtion to check for login
if (!isset($_SESSION['id'])) {
    header("Location: ../login/login.php");
    exit;
}


//function to get user ID
function get_user_id()
{
    return isset($_SESSION['id']) ? $_SESSION['id'] : null;
}

//function to check for role (admin, customer, etc)
function check_user_role($role)
{
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}


?>