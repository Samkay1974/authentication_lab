<?php
require_once __DIR__ . '/../settings/db_class.php';

class Product extends db_connection {

    // CREATE
    public function add_product($cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
        $db = $this->db_conn();

        $stmt = $db->prepare("INSERT INTO products 
            (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) return false;

        $stmt->bind_param("iisdsss", $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
        $result = $stmt->execute();
        $stmt->close();

        // Return the newly inserted product ID (useful for image folder naming)
        return $result ? $db->insert_id : false;
    }

    // RETRIEVE (all products grouped with category + brand)
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

    // RETRIEVE (products created by a specific user)
    public function get_products_by_user($user_id) {
        $db = $this->db_conn();

        $query = "SELECT p.*, c.cat_name, b.brand_name
                  FROM products p
                  LEFT JOIN categories c ON p.product_cat = c.cat_id
                  LEFT JOIN brands b ON p.product_brand = b.brand_id
                  WHERE c.user_id = ? OR b.user_id = ?
                  ORDER BY b.brand_name, c.cat_name, p.product_title";

        $stmt = $db->prepare($query);
        if (!$stmt) return [];

        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();
        return $products;
    }

    // UPDATE
    public function update_product($id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
        $db = $this->db_conn();
        $stmt = $db->prepare("UPDATE products 
            SET product_cat=?, product_brand=?, product_title=?, product_price=?, product_desc=?, product_image=?, product_keywords=? 
            WHERE product_id=?");
        if (!$stmt) return false;

        $stmt->bind_param("iisdsssi", $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // UPDATE IMAGE ONLY
    public function update_product_image($product_id, $image_path) {
        $db = $this->db_conn();

        $stmt = $db->prepare("UPDATE products SET product_image=? WHERE product_id=?");
        if (!$stmt) return false;

        $stmt->bind_param("si", $image_path, $product_id);
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

    // GET SINGLE PRODUCT
public function get_single_product($product_id, $user_id)
{
    $db = $this->db_conn();

    $stmt = $db->prepare("SELECT * FROM products WHERE product_id=? AND user_id=?");
    if (!$stmt) return false;

    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $result;
}

}
?>
