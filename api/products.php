<?php

require_once "../models/Product.php";

$product = new Product();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_produk"])) {
            $id = $_GET["id_produk"];
            $product->get_product($id);
        } else if (!empty($_GET["key"])) {
            $key = $_GET["key"];
            $product->search_product($key);
        } else {
            $product->get_products();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_produk"])) {
            $id = $_GET["id_produk"];
            $product->update_product($id);
        } else {
            $product->insert_product();
        }
        break;
    case 'DELETE':
        $id = $_GET["id_produk"];
        $product->delete_product($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
