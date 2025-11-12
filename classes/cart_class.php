<?php
// classes/cart_class.php
require_once __DIR__ . '/../settings/db_class.php';

class Cart extends db_connection {

    // Add product or increment qty for this user
    public function add_to_cart($customer_id, $product_id, $qty = 1) {
        $db = $this->db_conn();

        // check existing for this user + product
        $stmt = $db->prepare("SELECT qty FROM cart WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param("ii", $customer_id, $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $newQty = intval($row['qty']) + intval($qty);
            $stmt->close();
            $up = $db->prepare("UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?");
            $up->bind_param("iii", $newQty, $customer_id, $product_id);
            $ok = $up->execute();
            $up->close();
            return $ok;
        }
        $stmt->close();

        // insert
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ins = $db->prepare("INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, ?, ?, ?)");
        $ins->bind_param("isii", $product_id, $ip, $customer_id, $qty);
        $ok = $ins->execute();
        $ins->close();
        return $ok;
    }

    // Update quantity (by customer + product)
    public function update_quantity($customer_id, $product_id, $qty) {
        $db = $this->db_conn();
        $stmt = $db->prepare("UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param("iii", $qty, $customer_id, $product_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Remove item (by customer + product)
    public function remove_item($customer_id, $product_id) {
        $db = $this->db_conn();
        $stmt = $db->prepare("DELETE FROM cart WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param("ii", $customer_id, $product_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Empty cart for customer
    public function empty_cart($customer_id) {
        $db = $this->db_conn();
        $stmt = $db->prepare("DELETE FROM cart WHERE c_id = ?");
        $stmt->bind_param("i", $customer_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get cart items with product details
    public function get_cart_items($customer_id) {
        $db = $this->db_conn();
        $sql = "SELECT c.p_id, c.qty, p.product_id, p.product_title, p.product_price, p.product_image
                FROM cart c
                JOIN products p ON p.product_id = c.p_id
                WHERE c.c_id = ?
                ORDER BY c.p_id DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $items = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }
}
?>
