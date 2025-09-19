<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/customer_controller.php';

// Check if required fields are provided
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing email or password."
    ]);
    exit;
}

$email = trim($_POST['email']);
$password = $_POST['password'];

// Call controller to attempt login
$customer = login_customer_ctr($email, $password);

if ($customer) {
    // Set session variables
    $_SESSION['customer_id']   = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'];
    $_SESSION['user_role']     = $customer['user_role'];
    $_SESSION['customer_email']= $customer['customer_email'];

    echo json_encode([
        "status" => "success",
        "message" => "Login successful! Welcome, " . $customer['customer_name']
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password."
    ]);
}
?>
