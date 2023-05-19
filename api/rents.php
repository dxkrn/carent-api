<?php

require_once "../models/Rent.php";

$rent = new Rent();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_peminjaman"])) {
            $id = $_GET["id_peminjaman"];
            $rent->get_rent($id);
        } else {
            $rent->get_rents();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_peminjaman"])) {
            $id = $_GET["id_peminjaman"];
            $rent->update_rent($id);
        } else {
            $rent->insert_rent();
        }
        break;
    case 'DELETE':
        $id = $_GET["id_peminjaman"];
        $rent->delete_rent($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
