<?php
// classes/order_class.php
require_once __DIR__ . '/../settings/db_class.php';

class Order extends db_connection {
    public function create_order($customer_id, $invoice_no, $status = 'pending') {
        $db = $this->db_conn();
        $stmt = $db->prepare("INSERT INTO orders (customer_id, invoice_no, order_date, order_status) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("iss", $customer_id, $invoice_no, $status);
        $ok = $stmt->execute();
        if (!$ok) { $stmt->close(); return false; }
        $order_id = $db->insert_id;
        $stmt->close();
        return $order_id;
    }

    public function add_order_detail($order_id, $product_id, $qty) {
        $db = $this->db_conn();
        $stmt = $db->prepare("INSERT INTO orderdetails (order_id, product_id, qty) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $order_id, $product_id, $qty);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function record_payment($order_id, $amount, $customer_id, $currency = 'GHS', $status = 'paid') {
        $db = $this->db_conn();
        $stmt = $db->prepare("INSERT INTO payments (amt, customer_id, order_id, currency, payment_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("diis", $amount, $customer_id, $order_id, $currency);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
?>
