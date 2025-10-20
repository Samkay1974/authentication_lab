<?php
require_once __DIR__ . '/../settings/db_class.php';

class Product extends db_connection {

    // CREATE
    public function add_product($cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
        $db = $this->db_conn();

        $stmt = $db->prepare("INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) return false;

        $stmt->bind_param("iisdsss", $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // RETRIEVE
    public function get_all_products() {
        $db = $this->db_conn();
        $query = "SELECT p.*, c.cat_name, b.brand_name
                  FROM products p
                  LEFT JOIN categories c ON p.product_cat = c.cat_id
                  LEFT JOIN brands b ON p.product_brand = b.brand_id
                  ORDER BY b.brand_name, c.cat_name, p.product_title";
        $result = $db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // UPDATE
    public function update_product($id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
        $db = $this->db_conn();
        $stmt = $db->prepare("UPDATE products SET product_cat=?, product_brand=?, product_title=?, product_price=?, product_desc=?, product_image=?, product_keywords=? WHERE product_id=?");
        if (!$stmt) return false;

        $stmt->bind_param("iisdsssi", $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // DELETE
    public function delete_product($id) {
        $db = $this->db_conn();
        $stmt = $db->prepare("DELETE FROM products WHERE product_id=?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
