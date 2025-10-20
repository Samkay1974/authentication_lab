<?php
require_once __DIR__ . '/../settings/db_class.php';

class Brand extends db_connection
{
    public function add_brand($user_id, $brand_name)
    {
        $db = $this->db_conn();

        // check if brand exists for this user
        $check = $db->prepare("SELECT brand_id FROM brands WHERE brand_name = ? AND user_id = ?");
        if (!$check) return false;
        $check->bind_param("si", $brand_name, $user_id);
        $check->execute();
        $result = $check->get_result();

        if ($result && $result->num_rows > 0) {
            $check->close();
            return false; // duplicate
        }
        $check->close();

        $stmt = $db->prepare("INSERT INTO brands (brand_name, user_id) VALUES (?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("si", $brand_name, $user_id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function get_brands_by_user($user_id)
    {
        $db = $this->db_conn();
        $stmt = $db->prepare("SELECT * FROM brands WHERE user_id = ? ORDER BY brand_id DESC");
        if (!$stmt) return false;

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $brands = [];
        while ($row = $result->fetch_assoc()) {
            $brands[] = $row;
        }

        $stmt->close();
        return $brands;
    }

    public function update_brand($user_id, $brand_id, $brand_name)
    {
        $db = $this->db_conn();

        // check duplicate
        $check = $db->prepare("SELECT brand_id FROM brands WHERE brand_name = ? AND user_id = ? AND brand_id != ?");
        if (!$check) return false;
        $check->bind_param("sii", $brand_name, $user_id, $brand_id);
        $check->execute();
        $res = $check->get_result();

        if ($res && $res->num_rows > 0) {
            $check->close();
            return false; // duplicate name
        }
        $check->close();

        $stmt = $db->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ? AND user_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("sii", $brand_name, $brand_id, $user_id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function delete_brand($user_id, $brand_id)
    {
        $db = $this->db_conn();
        $stmt = $db->prepare("DELETE FROM brands WHERE brand_id = ? AND user_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("ii", $brand_id, $user_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
