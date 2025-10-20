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

function update_product_ctr($id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $product = new Product();
    return $product->update_product($id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}

function delete_product_ctr($id) {
    $product = new Product();
    return $product->delete_product($id);
}
?>
