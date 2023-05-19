<?php

require_once "../models/RentReturn.php";

$rent_return = new RentReturn();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_peminjaman"])) {
            $id = $_GET["id_peminjaman"];
            $rent_return->get_rent_return($id);
        } else {
            $rent_return->get_rent_returns();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_peminjaman"])) {
            $id = $_GET["id_peminjaman"];
            $rent_return->update_rent_return($id);
        } else {
            $rent_return->insert_rent_return();
        }
        break;
    case 'DELETE':
        $id = $_GET["id_peminjaman"];
        $rent_return->delete_rent_return($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
