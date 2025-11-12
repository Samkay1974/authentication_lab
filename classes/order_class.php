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
        $stmt = $db->prepare("INSERT INTO payment (amt, customer_id, order_id, currency, payment_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("diis", $amount, $customer_id, $order_id, $currency);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get order details for a specific order (items)
    public function get_order_details($order_id) {
        $db = $this->db_conn();
        $sql = "SELECT od.order_id, od.product_id, od.qty, p.product_title, p.product_price, p.product_image
                FROM orderdetails od
                LEFT JOIN products p ON p.product_id = od.product_id
                WHERE od.order_id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $items = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $items;
    }

    // Retrieve past orders for a customer (with optional include of items)
    public function get_orders_by_customer($customer_id, $include_items = false) {
        $db = $this->db_conn();
        $stmt = $db->prepare("SELECT order_id, customer_id, invoice_no, order_date, order_status FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
        if (!$stmt) return [];
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $orders = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        if ($include_items && !empty($orders)) {
            foreach ($orders as &$o) {
                $o['items'] = $this->get_order_details($o['order_id']);
            }
        }

        return $orders;
    }

    
}
?>
