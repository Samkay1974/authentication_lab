<?php
require_once __DIR__ . '/../classes/product_class.php';

function add_product_ctr($cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $product = new Product();
    return $product->add_product($cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}

function get_all_products_ctr() {
    $product = new Product();
    return $product->get_all_products();
}
function get_products_by_user_ctr($user_id) {
    $product = new Product();
    return $product->get_products_by_user($user_id);
}
function update_product_ctr($id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $product = new Product();
    return $product->update_product($id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}

function delete_product_ctr($id) {
    $product = new Product();
    return $product->delete_product($id);
}
function update_product_image_ctr($product_id, $relative_path) {
    $product = new Product();
    return $product->update_product_image($product_id, $relative_path);
}
function get_single_product_ctr($id) {
    $product = new Product();
    return $product->get_single_product($id, $_SESSION['customer_id']);
}
?>
