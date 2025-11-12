<?php
require_once __DIR__ . '/../classes/cart_class.php';

function add_to_cart_ctr($customer_id, $product_id, $qty = 1) {
    $c = new Cart();
    return $c->add_to_cart($customer_id, $product_id, $qty);
}

function update_cart_item_ctr($customer_id, $product_id, $qty) {
    $c = new Cart();
    return $c->update_quantity($customer_id, $product_id, $qty);
}

function remove_from_cart_ctr($customer_id, $product_id) {
    $c = new Cart();
    return $c->remove_item($customer_id, $product_id);
}

function empty_cart_ctr($customer_id) {
    $c = new Cart();
    return $c->empty_cart($customer_id);
}

function get_user_cart_ctr($customer_id) {
    $c = new Cart();
    return $c->get_cart_items($customer_id);
}
?>
