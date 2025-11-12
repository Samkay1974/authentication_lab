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

    /**
     * Get orders that include products owned by a specific user (brand.user_id or category.user_id)
     */
    public function get_orders_for_user($user_id) {
        $db = $this->db_conn();
        $sql = "SELECT o.order_id, o.customer_id, o.invoice_no, o.order_date, o.order_status, c.customer_name, COUNT(DISTINCT od.product_id) as item_count
                FROM orders o
                JOIN orderdetails od ON od.order_id = o.order_id
                JOIN products p ON p.product_id = od.product_id
                LEFT JOIN brands b ON b.brand_id = p.product_brand
                LEFT JOIN categories cat ON cat.cat_id = p.product_cat
                LEFT JOIN customers c ON c.customer_id = o.customer_id
                WHERE COALESCE(b.user_id, 0) = ? OR COALESCE(cat.user_id, 0) = ?
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
        $stmt = $db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    /**
     * Get order details for a specific order but only items that belong to the given user
     */
    public function get_order_details_for_user($order_id, $user_id) {
        $db = $this->db_conn();
        $sql = "SELECT od.order_id, od.product_id, od.qty, p.product_title, p.product_price, p.product_image
                FROM orderdetails od
                JOIN products p ON p.product_id = od.product_id
                LEFT JOIN brands b ON b.brand_id = p.product_brand
                LEFT JOIN categories cat ON cat.cat_id = p.product_cat
                WHERE od.order_id = ? AND (COALESCE(b.user_id,0) = ? OR COALESCE(cat.user_id,0) = ? )";
        $stmt = $db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("iii", $order_id, $user_id, $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
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

    // Delete order and related rows (orderdetails, payments)
    public function delete_order($order_id) {
        $db = $this->db_conn();
        // Use transaction to ensure consistency
        $db->begin_transaction();
        try {
            $stmt = $db->prepare("DELETE FROM payments WHERE order_id = ?");
            if ($stmt) { $stmt->bind_param("i", $order_id); $stmt->execute(); $stmt->close(); }

            $stmt = $db->prepare("DELETE FROM orderdetails WHERE order_id = ?");
            if ($stmt) { $stmt->bind_param("i", $order_id); $stmt->execute(); $stmt->close(); }

            $stmt = $db->prepare("DELETE FROM orders WHERE order_id = ?");
            if ($stmt) { $stmt->bind_param("i", $order_id); $ok = $stmt->execute(); $stmt->close(); }
            else $ok = false;

            if ($ok) {
                $db->commit();
                return true;
            } else {
                $db->rollback();
                return false;
            }
        } catch (Exception $ex) {
            $db->rollback();
            return false;
        }
    }
}
?>
