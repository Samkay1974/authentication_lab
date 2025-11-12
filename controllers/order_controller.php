<?php
require_once __DIR__ . '/../classes/order_class.php';

function create_order_ctr($customer_id, $invoice_no, $status = 'pending') {
    $o = new Order();
    return $o->create_order($customer_id, $invoice_no, $status);
}

function add_order_detail_ctr($order_id, $product_id, $qty) {
    $o = new Order();
    return $o->add_order_detail($order_id, $product_id, $qty);
}

function record_payment_ctr($order_id, $amount, $customer_id, $currency = 'GHS') {
    $o = new Order();
    return $o->record_payment($order_id, $amount, $customer_id, $currency);
}

function get_all_orders_ctr() {
    $o = new Order();
    return $o->get_all_orders();
}

function get_order_details_ctr($order_id) {
    $o = new Order();
    return $o->get_order_details($order_id);
}

function count_orders_ctr() {
    $o = new Order();
    return $o->count_orders();
}

function get_order_by_id_ctr($order_id) {
    $o = new Order();
    return $o->get_order_by_id($order_id);
}
?>
