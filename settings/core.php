
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();


/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn()
{
    return isset($_SESSION['customer_id']); // Giving true if session exists
}

/**
 * Get user ID from session
 * @return int|null
 */
function get_user_id()
{
    return isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
}

/**
 * Check if user has a specific role
 * @param int $role
 * @return bool
 */
function check_user_role($role)
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == $role;
}

/**
 * Check if user is admin
 * @return bool
 */
function isAdmin()
{
    return check_user_role(1); // 1 is the role ID for admin
}
?>
