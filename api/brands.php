<?php

require_once "../models/Brand.php";

$brand = new Brand();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_brand"])) {
            $id = $_GET["id_brand"];
            $brand->get_brand($id);
        } else {
            $brand->get_brands();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_merk"])) {
            $id = $_GET["id_merk"];
            $brand->update_brand($id);
        } else {
            $brand->insert_brand();
        }
        break;
    case 'DELETE':
        $id = $_GET["id_merk"];
        $brand->delete_brand($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
