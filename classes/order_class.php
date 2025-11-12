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

    // Get all orders with customer info and item counts
    public function get_all_orders() {
        $db = $this->db_conn();
        $sql = "SELECT o.order_id, o.customer_id, o.invoice_no, o.order_date, o.order_status, c.customer_name, COUNT(od.product_id) as item_count
                FROM orders o
                LEFT JOIN orderdetails od ON od.order_id = o.order_id
                LEFT JOIN customers c ON c.customer_id = o.customer_id
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
        $res = $db->query($sql);
        if (!$res) return [];
        $items = $res->fetch_all(MYSQLI_ASSOC);
        return $items;
    }

    // Get order details for a specific order
    public function get_order_details($order_id) {
        $db = $this->db_conn();
        $sql = "SELECT od.order_id, od.product_id, od.qty, p.product_title, p.product_price, p.product_image
                FROM orderdetails od
                JOIN products p ON p.product_id = od.product_id
                WHERE od.order_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $items = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }

    // Get single order row
    public function get_order_by_id($order_id) {
        $db = $this->db_conn();
        $sql = "SELECT o.*, c.customer_name, c.customer_email
                FROM orders o
                LEFT JOIN customers c ON c.customer_id = o.customer_id
                WHERE o.order_id = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }

    // Count total orders
    public function count_orders() {
        $db = $this->db_conn();
        $res = $db->query("SELECT COUNT(*) as cnt FROM orders");
        if (!$res) return 0;
        $row = $res->fetch_assoc();
        return intval($row['cnt'] ?? 0);
    }
}
?>
