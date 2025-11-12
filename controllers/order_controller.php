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

function get_order_details_ctr($order_id) {
    $o = new Order();
    return $o->get_order_details($order_id);
}

function get_orders_by_customer_ctr($customer_id, $include_items = false) {
    $o = new Order();
    return $o->get_orders_by_customer($customer_id, $include_items);
}

?>
