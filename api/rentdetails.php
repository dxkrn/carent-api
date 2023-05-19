<?php

require_once "../models/RentDetail.php";

$rent_detail = new RentDetail();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_peminjaman"])) {
            $id = $_GET["id_peminjaman"];
            $rent_detail->get_rent_detail($id);
        } else {
            $rent_detail->get_rent_details();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_peminjaman"]) && !empty($_GET["id_produk"])) {
            $id_peminjaman = $_GET["id_peminjaman"];
            $id_produk = $_GET["id_produk"];
            $rent_detail->update_rent_detail($id_peminjaman, $id_produk);
        } else {
            $rent_detail->insert_rent_detail();
        }
        break;
    case 'DELETE':
        $id_peminjaman = $_GET["id_peminjaman"];
        $id_produk = $_GET["id_produk"];
        $rent_detail->delete_rent_detail($id_peminjaman, $id_produk);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
