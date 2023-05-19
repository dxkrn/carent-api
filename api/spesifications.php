<?php

require_once "../models/Spesification.php";

$spesification = new Spesification();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_produk"])) {
            $id = $_GET["id_produk"];
            $spesification->get_spesification($id);
        } else {
            $spesification->get_spesifications();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_produk"])) {
            $id = $_GET["id_produk"];
            $spesification->update_spesification($id);
        } else {
            $spesification->insert_spesification();
        }
        break;
    case 'DELETE':
        $id = $_GET["id_produk"];
        $spesification->delete_spesification($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
