<?php
require_once __DIR__ . '/../classes/customer_class.php';

/**
 * Register a new customer
 */
function register_customer_ctr($name, $email, $password, $phone_number, $country, $city, $role = 2)
{
    $customer = new Customer();
    return $customer->add_customer($name, $email, $password, $phone_number, $country, $city, $role);
}

/**
 * Get customer by email
 */
function get_customer_by_email_ctr($email)
{
    $customer = new Customer();
    return $customer->get_customer_by_email($email);
}

/**
 * Login customer
 */
function login_customer_ctr($email, $password)
{
    $customer = new Customer();
    return $customer->login_customer($email, $password);
}
?>
