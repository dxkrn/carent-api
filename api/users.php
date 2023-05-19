<?php

require_once "../models/User.php";

$user = new User();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_user"])) {
            $id = $_GET["id_user"];
            $user->get_user($id);
        } else {
            $user->get_users();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_user"])) {
            $id = $_GET["id_user"];
            $user->update_user($id);
        } else {
            $user->insert_user();
        }
        break;
    case 'DELETE':
        $id = $_GET["id_user"];
        $user->delete_user($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
