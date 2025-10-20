<?php

header('Content-Type: application/json');

session_start();

$response = array();



// TODO: Check if the user is already logged in and redirect to the dashboard
if (isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/user_controller.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone_number = $_POST['phone_number'];
$country = $_POST['country'];
$city = $_POST['city'];
$role = $_POST['role'];

$check = get_user_by_email_ctr($email);
if ($check) {
    echo json_encode([
        "status" => "error",
        "message" => "This email is already registered. Please use a different one."
    ]);
    exit;
}      

$user_id = register_user_ctr($name, $email, $password, $phone_number, $country, $city, $role);


// Check if email already exists

if ($user_id) {
    $response['status'] = 'success';
    $response['message'] = 'Registered successfully';
    $response['user_id'] = $user_id;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Registration unsuccessful';
}

echo json_encode($response);