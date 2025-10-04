<?php
// classes/category_class.php
require_once __DIR__ . '/../settings/db_class.php';

class Category extends db_connection
{
        public function add_category($user_id, $category_name)
    {
        $db = $this->db_conn();

        $check = $db->prepare("SELECT * FROM categories WHERE cat_name = ? AND user_id = ?");
        $check->bind_param("si", $category_name, $user_id);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) {
            return false; 
        }
        $check->close();

        $stmt = $db->prepare("INSERT INTO categories (cat_name, user_id) VALUES (?, ?)");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("si", $category_name, $user_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Fetch all categories created by a user
     */
    public function get_categories_by_user($user_id)
    {
        $db = $this->db_conn();

        $stmt = $db->prepare("SELECT * FROM categories WHERE user_id = ? ORDER BY cat_id DESC");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        $stmt->close();
        return $categories;
    }

    /**
     * Update a category name
     */
    public function update_category($user_id, $category_id, $category_name)
    {
        $db = $this->db_conn();

        $stmt = $db->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ? AND user_id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sii", $category_name, $category_id, $user_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Delete a category
     */
    public function delete_category($user_id, $category_id)
    {
        $db = $this->db_conn();

        $stmt = $db->prepare("DELETE FROM categories WHERE cat_id = ? AND user_id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $category_id, $user_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}
