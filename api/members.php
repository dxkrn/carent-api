<?php

require_once "../models/Member.php";

$member = new Member();
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_user"])) {
            $id = $_GET["id_user"];
            $member->get_member($id);
        } else {
            $member->get_members();
        }
        break;
    case 'POST':
        if (!empty($_GET["id_user"])) {
            $id = $_GET["id_user"];
            $member->update_member($id);
        } else {
            $member->insert_member();
        }
        break;
    case 'DELETE':
        $id = $_GET["id_user"];
        $member->delete_member($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
