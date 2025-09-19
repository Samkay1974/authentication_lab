<?php
require_once __DIR__ . '/../settings/db_class.php';

class Customer extends db_connection
{
    
    public function __construct()
    {
        
        $this->db = $this->db_conn();
    }

    /**
     * Add a new customer (registration) */
    
    public function add_customer($name, $email, $password, $phone_number, $country, $city, $role = 2)
    {
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO customer 
            (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssssssi", $name, $email, $hashed_password, $country, $city, $phone_number, $role);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Get customer by email
     */
    public function get_customer_by_email($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
        $stmt->close();

        return $customer ? $customer : false;
    }

    /**
     * Login customer (check email + password)
     */
    public function login_customer($email, $password)
    {
        $customer = $this->get_customer_by_email($email);

        if ($customer && password_verify($password, $customer['customer_pass'])) {
            return $customer; // Return customer row if login successful
        }

        return false; // Login failed
    }
}
?>
