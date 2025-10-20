<?php
require_once __DIR__ . '/../classes/brand_class.php';

function add_brand_ctr($user_id, $brand_name)
{
    $brand = new Brand();
    return $brand->add_brand($user_id, $brand_name);
}

function get_brands_by_user_ctr($user_id)
{
    $brand = new Brand();
    return $brand->get_brands_by_user($user_id);
}
function get_all_brands_ctr($user_id) {
    $brand = new Brand();
    return $brand->get_brands_by_user($user_id);
}

function update_brand_ctr($user_id, $brand_id, $brand_name)
{
    $brand = new Brand();
    return $brand->update_brand($user_id, $brand_id, $brand_name);
}

function delete_brand_ctr($user_id, $brand_id)
{
    $brand = new Brand();
    return $brand->delete_brand($user_id, $brand_id);
}
